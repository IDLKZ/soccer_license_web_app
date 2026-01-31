<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\DbDumper\Databases\MySql;

#[Title('Управление резервными копиями базы данных')]
class DatabaseBackupManagement extends Component
{
    // Permissions
    #[Locked]
    public $canManageDb = false;

    public $backups = [];

    public function mount()
    {
        // Authorization
        $this->authorize('manage-db');

        // Set permissions
        $user = auth()->user();
        $this->canManageDb = $user ? $user->can('manage-db') : false;

        $this->loadBackups();
    }

    public function loadBackups()
    {
        $backupPath = storage_path('app/backups');

        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        $files = glob($backupPath . '/*.sql');
        $this->backups = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'path' => $file,
                'size' => $this->formatBytes(filesize($file)),
                'date' => date('Y-m-d H:i:s', filemtime($file))
            ];
        })->sortByDesc('date')->values()->toArray();
    }

    public function createBackup()
    {
        $this->authorize('manage-db');

        try {
            $backupPath = storage_path('app/backups');

            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $fileName = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupPath . '/' . $fileName;

            $dumper = MySql::create()
                ->setDbName(config('database.connections.mysql.database'))
                ->setUserName(config('database.connections.mysql.username'))
                ->setHost(config('database.connections.mysql.host'))
                ->setPort(config('database.connections.mysql.port') ?? 3306)
                ->doNotUseColumnStatistics(); // For MySQL 8.0 compatibility

            // Set password only if it's not empty
            $password = config('database.connections.mysql.password');
            if (!empty($password)) {
                $dumper->setPassword($password);
            }

            // Try to detect mysqldump path
            $mysqldumpPath = null;

            // First check if path is specified in .env
            if (env('MYSQLDUMP_PATH')) {
                $customPath = env('MYSQLDUMP_PATH');
                if (file_exists($customPath)) {
                    $mysqldumpPath = $customPath;
                }
            }

            // If not found in .env, try common Windows paths
            if (!$mysqldumpPath) {
                $possiblePaths = [
                    'C:\\xampp\\mysql\\bin\\mysqldump.exe',
                    'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
                    'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
                    'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe',
                    'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
                ];

                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $mysqldumpPath = $path;
                        break;
                    }
                }
            }

            // If found, set the dump binary path
            if ($mysqldumpPath) {
                $dumper->setDumpBinaryPath(dirname($mysqldumpPath));
            } else {
                throw new \Exception('mysqldump не найден. Пожалуйста, установите MySQL или укажите путь к mysqldump в .env файле (MYSQLDUMP_PATH=путь/к/mysqldump.exe)');
            }

            $dumper->dumpToFile($filePath);

            $this->loadBackups();

            session()->flash('message', 'Резервная копия успешно создана');
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при создании резервной копии: ' . $e->getMessage());
        }
    }

    public function deleteBackup($fileName)
    {
        $this->authorize('manage-db');

        $filePath = storage_path('app/backups/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);
            $this->loadBackups();
            session()->flash('message', 'Резервная копия удалена');
        } else {
            session()->flash('error', 'Файл не найден');
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function render()
    {
        return view('livewire.admin.database-backup-management')->layout(get_user_layout());
    }
}
