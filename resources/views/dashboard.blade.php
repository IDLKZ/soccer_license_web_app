@extends(get_user_layout())

@section('title', 'Панель управления')

@section('content')
    <div class="container mx-auto">
        <!-- Welcome Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-8 mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-br {{ get_role_color(auth()->user()->role->value ?? '') }} rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas {{ get_role_icon(auth()->user()->role->value ?? '') }} text-3xl text-white"></i>
                </div>
                <div class="ml-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        Добро пожаловать, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                        <i class="fas fa-user-tag mr-1"></i>
                        {{ auth()->user()->role->title_ru ?? 'Пользователь' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Stat Card 1 -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Всего заявок</p>
                        <h3 class="text-3xl font-bold mt-2">24</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Одобренные</p>
                        <h3 class="text-3xl font-bold mt-2">18</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">В процессе</p>
                        <h3 class="text-3xl font-bold mt-2">5</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-spinner text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Лицензии</p>
                        <h3 class="text-3xl font-bold mt-2">12</h3>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    <i class="fas fa-history mr-2 text-blue-600 dark:text-blue-400"></i>
                    Последняя активность
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                Создана новая заявка
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">2 часа назад</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                Заявка #123 одобрена
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">5 часов назад</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-upload text-white"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                Загружен новый документ
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1 день назад</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
