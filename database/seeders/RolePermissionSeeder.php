<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем роли
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Администратор',
                'description' => 'Полный доступ ко всем функциям системы',
                'is_active' => true,
            ]
        );

        $editorRole = Role::firstOrCreate(
            ['slug' => 'editor'],
            [
                'name' => 'Редактор',
                'description' => 'Может редактировать контент, но не может управлять пользователями',
                'is_active' => true,
            ]
        );

        // Создаем права доступа
        $permissions = [
            // Управление пользователями
            ['name' => 'Управление пользователями', 'slug' => 'users.manage', 'resource' => 'users'],
            ['name' => 'Создание пользователей', 'slug' => 'users.create', 'resource' => 'users'],
            ['name' => 'Редактирование пользователей', 'slug' => 'users.edit', 'resource' => 'users'],
            ['name' => 'Удаление пользователей', 'slug' => 'users.delete', 'resource' => 'users'],

            // Управление событиями
            ['name' => 'Управление событиями', 'slug' => 'events.manage', 'resource' => 'events'],
            ['name' => 'Создание событий', 'slug' => 'events.create', 'resource' => 'events'],
            ['name' => 'Редактирование событий', 'slug' => 'events.edit', 'resource' => 'events'],
            ['name' => 'Удаление событий', 'slug' => 'events.delete', 'resource' => 'events'],

            // Управление постами
            ['name' => 'Управление постами', 'slug' => 'posts.manage', 'resource' => 'posts'],
            ['name' => 'Создание постов', 'slug' => 'posts.create', 'resource' => 'posts'],
            ['name' => 'Редактирование постов', 'slug' => 'posts.edit', 'resource' => 'posts'],
            ['name' => 'Удаление постов', 'slug' => 'posts.delete', 'resource' => 'posts'],

            // Управление справочниками
            ['name' => 'Управление справочниками', 'slug' => 'references.manage', 'resource' => 'references'],
            ['name' => 'Создание справочников', 'slug' => 'references.create', 'resource' => 'references'],
            ['name' => 'Редактирование справочников', 'slug' => 'references.edit', 'resource' => 'references'],
            ['name' => 'Удаление справочников', 'slug' => 'references.delete', 'resource' => 'references'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['slug' => $permissionData['slug']],
                $permissionData
            );
        }

        // Назначаем права администратору (все права)
        $adminRole->permissions()->sync(Permission::all());

        // Назначаем права редактору (все кроме управления пользователями)
        $editorPermissions = Permission::where('resource', '!=', 'users')->get();
        $editorRole->permissions()->sync($editorPermissions);
    }
}
