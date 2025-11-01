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

            // Role Management Permissions
            [
                'title_ru' => 'Просмотр ролей',
                'title_kk' => 'Рөлдерді көру',
                'title_en' => 'View Roles',
                'description_ru' => 'Разрешение на просмотр списка ролей системы',
                'description_kk' => 'Жүйе рөлдерінің тізімін көру рұқсаты',
                'description_en' => 'Permission to view system roles list',
                'value' => 'view-roles',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Создание ролей',
                'title_kk' => 'Рөлдерді жасау',
                'title_en' => 'Create Roles',
                'description_ru' => 'Разрешение на создание новых ролей системы',
                'description_kk' => 'Жүйеде жаңа рөлдерді жасау рұқсаты',
                'description_en' => 'Permission to create new system roles',
                'value' => 'create-roles',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Управление ролями',
                'title_kk' => 'Рөлдерді басқару',
                'title_en' => 'Manage Roles',
                'description_ru' => 'Разрешение на редактирование существующих ролей',
                'description_kk' => 'Қолданыстағы рөлдерді өңдеу рұқсаты',
                'description_en' => 'Permission to edit existing roles',
                'value' => 'manage-roles',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Удаление ролей',
                'title_kk' => 'Рөлдерді жою',
                'title_en' => 'Delete Roles',
                'description_ru' => 'Разрешение на удаление ролей системы',
                'description_kk' => 'Жүйе рөлдерін жою рұқсаты',
                'description_en' => 'Permission to delete system roles',
                'value' => 'delete-roles',
                'is_system' => true,
            ],

            // Permission Management Permissions
            [
                'title_ru' => 'Просмотр прав',
                'title_kk' => 'Құқықтарды көру',
                'title_en' => 'View Permissions',
                'description_ru' => 'Разрешение на просмотр списка прав доступа',
                'description_kk' => 'Қатынау құқықтарының тізімін көру рұқсаты',
                'description_en' => 'Permission to view permissions list',
                'value' => 'view-permissions',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Создание прав',
                'title_kk' => 'Құқықтарды жасау',
                'title_en' => 'Create Permissions',
                'description_ru' => 'Разрешение на создание новых прав доступа',
                'description_kk' => 'Жаңа қатынау құқықтарын жасау рұқсаты',
                'description_en' => 'Permission to create new permissions',
                'value' => 'create-permissions',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Управление правами',
                'title_kk' => 'Құқықтарды басқару',
                'title_en' => 'Manage Permissions',
                'description_ru' => 'Разрешение на редактирование существующих прав доступа',
                'description_kk' => 'Қолданыстағы қатынау құқықтарын өңдеу рұқсаты',
                'description_en' => 'Permission to edit existing permissions',
                'value' => 'manage-permissions',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Удаление прав',
                'title_kk' => 'Құқықтарды жою',
                'title_en' => 'Delete Permissions',
                'description_ru' => 'Разрешение на удаление прав доступа',
                'description_kk' => 'Қатынау құқықтарын жою рұқсаты',
                'description_en' => 'Permission to delete permissions',
                'value' => 'delete-permissions',
                'is_system' => true,
            ],

            // Season Management Permissions
            [
                'title_ru' => 'Просмотр сезонов',
                'title_kk' => 'Маусымдарды көру',
                'title_en' => 'View Seasons',
                'description_ru' => 'Разрешение на просмотр списка сезонов',
                'description_kk' => 'Маусымдар тізімін көру рұқсаты',
                'description_en' => 'Permission to view seasons list',
                'value' => 'view-seasons',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Создание сезонов',
                'title_kk' => 'Маусымдарды жасау',
                'title_en' => 'Create Seasons',
                'description_ru' => 'Разрешение на создание новых сезонов',
                'description_kk' => 'Жаңа маусымдарды жасау рұқсаты',
                'description_en' => 'Permission to create new seasons',
                'value' => 'create-seasons',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Управление сезонами',
                'title_kk' => 'Маусымдарды басқару',
                'title_en' => 'Manage Seasons',
                'description_ru' => 'Разрешение на редактирование существующих сезонов',
                'description_kk' => 'Қолданыстағы маусымдарды өңдеу рұқсаты',
                'description_en' => 'Permission to edit existing seasons',
                'value' => 'manage-seasons',
                'is_system' => true,
            ],
            [
                'title_ru' => 'Удаление сезонов',
                'title_kk' => 'Маусымдарды жою',
                'title_en' => 'Delete Seasons',
                'description_ru' => 'Разрешение на удаление сезонов',
                'description_kk' => 'Маусымдарды жою рұқсаты',
                'description_en' => 'Permission to delete seasons',
                'value' => 'delete-seasons',
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
