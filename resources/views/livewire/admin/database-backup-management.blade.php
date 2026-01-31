<div>
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                Управление резервными копиями базы данных
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Создание и управление резервными копиями базы данных
            </p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-300 dark:border-green-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 mr-3"></i>
                    <p class="text-green-800 dark:text-green-200">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-300 dark:border-red-700 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mr-3"></i>
                    <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-database text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Всего резервных копий</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ count($backups) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-server text-green-600 dark:text-green-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">База данных</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ config('database.connections.mysql.database') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-hdd text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Общий размер</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            @if(count($backups) > 0)
                                {{ collect($backups)->sum(function($backup) {
                                    return filesize($backup['path']);
                                }) / 1024 / 1024 > 1
                                    ? number_format(collect($backups)->sum(function($backup) {
                                        return filesize($backup['path']);
                                    }) / 1024 / 1024, 2) . ' MB'
                                    : number_format(collect($backups)->sum(function($backup) {
                                        return filesize($backup['path']);
                                    }) / 1024, 2) . ' KB'
                                }}
                            @else
                                0 KB
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Backup Button -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">
                        Создать новую резервную копию
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Создание резервной копии текущего состояния базы данных
                    </p>
                </div>
                @if ($canManageDb)
                    <button
                        wire:click="createBackup"
                        class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-plus-circle mr-2"></i>
                        Создать резервную копию
                    </button>
                @endif
            </div>
        </div>

        <!-- Backups Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-list mr-2 text-blue-600 dark:text-blue-400"></i>
                    Список резервных копий
                </h2>
            </div>

            @if(count($backups) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <i class="fas fa-file mr-2"></i>
                                        Имя файла
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Дата создания
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <i class="fas fa-weight mr-2"></i>
                                        Размер
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        <i class="fas fa-cog mr-2"></i>
                                        Действия
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($backups as $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-database text-blue-600 dark:text-blue-400"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $backup['name'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 flex items-center">
                                            <i class="fas fa-clock text-gray-400 mr-2"></i>
                                            {{ $backup['date'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                            {{ $backup['size'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($canManageDb)
                                                <a
                                                    href="{{ route('admin.database-backup.download', ['fileName' => $backup['name']]) }}"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors"
                                                    title="Скачать"
                                                >
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button
                                                    wire:click="deleteBackup('{{ $backup['name'] }}')"
                                                    wire:confirm="Вы уверены, что хотите удалить эту резервную копию?"
                                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                                                    title="Удалить"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="fas fa-database text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                        Резервных копий пока нет
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        Создайте первую резервную копию базы данных
                    </p>
                    @if ($canManageDb)
                        <button
                            wire:click="createBackup"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
                        >
                            <i class="fas fa-plus-circle mr-2"></i>
                            Создать резервную копию
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
