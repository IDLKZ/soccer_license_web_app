<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User Management Permissions
            [
                'title_ru' => 'Просмотр пользователей',
                'title_kk' => 'Пайдаланушыларды көру',
                'title_en' => 'View Users',
                'description_ru' => 'Разрешение на просмотр списка пользователей системы',
                'description_kk' => 'Жүйе пайдаланушыларының тізімін көру рұқсаты',
                'description_en' => 'Permission to view system users list',
                'value' => 'view-users',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Создание пользователей',
                'title_kk' => 'Пайдаланушыларды жасау',
                'title_en' => 'Create Users',
                'description_ru' => 'Разрешение на создание новых пользователей системы',
                'description_kk' => 'Жүйеде жаңа пайдаланушыларды жасау рұқсаты',
                'description_en' => 'Permission to create new system users',
                'value' => 'create-users',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Управление пользователями',
                'title_kk' => 'Пайдаланушыларды басқару',
                'title_en' => 'Manage Users',
                'description_ru' => 'Разрешение на редактирование существующих пользователей',
                'description_kk' => 'Қолданыстағы пайдаланушыларды өңдеу рұқсаты',
                'description_en' => 'Permission to edit existing users',
                'value' => 'manage-users',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Удаление пользователей',
                'title_kk' => 'Пайдаланушыларды жою',
                'title_en' => 'Delete Users',
                'description_ru' => 'Разрешение на удаление пользователей системы',
                'description_kk' => 'Жүйе пайдаланушыларын жою рұқсаты',
                'description_en' => 'Permission to delete system users',
                'value' => 'delete-users',
                'is_system' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['value' => $permission['value']],
                $permission
            );
        }
    }
}
