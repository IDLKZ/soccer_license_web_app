@extends(get_user_layout())

@php
    $user = auth()->user();
    $userRole = $user->role ?? null;

    // Determine title based on role type
    $title = 'Панель управления';
    if ($userRole) {
        if ($userRole->value === 'admin') {
            $title = 'Панель администратора';
        } elseif ($userRole->is_administrative) {
            $title = 'Панель департамента';
        } else {
            $title = 'Панель клуба';
        }
    }

    // Determine which dashboard component to use
    $dashboardComponent = null;
    if ($userRole) {
        if ($userRole->value === 'admin') {
            $dashboardComponent = 'dashboard.admin';
        } elseif ($userRole->is_administrative) {
            $dashboardComponent = 'dashboard.department';
        } else {
            $dashboardComponent = 'dashboard.club';
        }
    }
@endphp

@section('title', $title)

@section('content')
    <div class="container mx-auto">
        @if($dashboardComponent)
            <x-dynamic-component :component="$dashboardComponent" />
        @else
            <!-- Fallback for unknown roles -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Добро пожаловать, {{ auth()->user()->first_name }}!
                </h1>
                <p class="text-gray-600 dark:text-gray-400">
                    Ваша роль: {{ auth()->user()->role->title_ru ?? 'Не назначена' }}
                </p>
            </div>
        @endif
    </div>
@endsection
