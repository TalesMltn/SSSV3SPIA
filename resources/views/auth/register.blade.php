<x-auth-layout>
    <div class="w-full max-w-md">
        <!-- Tarjeta premium -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100 backdrop-blur-sm">

            <!-- Título -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Crear cuenta</h1>
                <p class="text-sm text-gray-600 mt-2">Únete y comienza ahora</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-6 text-center text-sm" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre')" class="text-gray-700 font-medium" />
                    <x-text-input 
                        id="name" 
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        autocomplete="name" 
                        placeholder="Juan Pérez"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address (CONVERTIDO A MINÚSCULAS AUTOMÁTICO) -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 lowercase" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autocomplete="username" 
                        placeholder="tu@email.com"
                        x-data
                        x-on:input="value = value.toLowerCase()"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700 font-medium" />
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                        type="password"
                        name="password"
                        required 
                        autocomplete="new-password" 
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-700 font-medium" />
                    <x-text-input 
                        id="password_confirmation" 
                        class="block mt-1 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200" 
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password" 
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Botones y enlace -->
                <div class="flex flex-col sm:flex-row gap-3 mt-8 items-center justify-between">
                    <a href="{{ route('login') }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition underline-offset-2 hover:underline">
                        {{ __('¿Ya tienes cuenta? Inicia sesión') }}
                    </a>

                    <x-primary-button class="w-full sm:w-auto justify-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        {{ __('Registrarse') }}
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