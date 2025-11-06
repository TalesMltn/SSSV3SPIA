<x-auth-layout>
    <div class="w-full max-w-md">
        <!-- Tarjeta premium -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100 backdrop-blur-sm">

            <!-- Título -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Bienvenido</h1>
                <p class="text-sm text-gray-600 mt-2">Inicia sesión para continuar</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center text-sm" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="tu@email.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div>
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 font-medium" />
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password" 
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Recordarme -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 focus:ring-offset-0 transition">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition underline-offset-2 hover:underline" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row gap-3 mt-8">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="w-full text-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition py-3 border border-gray-300 rounded-xl hover:border-indigo-400 hover:shadow-md">
                            {{ __('Crear cuenta') }}
                        </a>
                    @endif
                    <x-primary-button class="w-full justify-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        {{ __('Iniciar Sesión') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Footer -->
            <p class="mt-8 text-center text-xs text-gray-500">
                © {{ date('Y') }} Tu App. Todos los derechos reservados.
            </p>
        </div>
    </div>
</x-auth-layout>