<div class="w-full max-w-md">
    <!-- Logo & Title -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 dark:from-blue-500 dark:to-indigo-500 rounded-2xl shadow-2xl mb-4">
            <i class="fas fa-shield-halved text-3xl text-white"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
            Система лицензирования
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Футбольная федерация Казахстана
        </p>
    </div>

    <!-- Login Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-8">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6 text-center">
                <i class="fas fa-sign-in-alt mr-2 text-blue-600 dark:text-blue-400"></i>
                Вход в систему
            </h2>

            <!-- Error Message -->
            @if($errors->has('email'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-400 p-4 rounded">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 dark:text-red-400 mt-0.5"></i>
                    <p class="ml-3 text-sm text-red-700 dark:text-red-300">{{ $errors->first('email') }}</p>
                </div>
            </div>
            @endif

            <form wire:submit.prevent="login">
                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-envelope mr-1 text-gray-400 dark:text-gray-500"></i>
                        Email адрес
                    </label>
                    <input type="email"
                           id="email"
                           wire:model="email"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                           placeholder="example@email.com"
                           autofocus>
                    @error('email')
                        <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-lock mr-1 text-gray-400 dark:text-gray-500"></i>
                        Пароль
                    </label>
                    <input type="password"
                           id="password"
                           wire:model="password"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors"
                           placeholder="••••••••">
                    @error('password')
                        <span class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox"
                               wire:model="remember"
                               class="w-4 h-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 dark:focus:ring-blue-400">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Запомнить меня</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-150 shadow-lg hover:shadow-xl flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Войти
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 dark:bg-gray-700 px-8 py-4 border-t border-gray-200 dark:border-gray-600">
            <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                <i class="fas fa-info-circle mr-1"></i>
                Для получения доступа обратитесь к администратору
            </p>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-8">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} Футбольная федерация Казахстана
        </p>
    </div>
</div>
