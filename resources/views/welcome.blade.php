{{-- @extends('app') --}}
<x-guest-layout>
    {{-- <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Bienvenu sur le CRM Super-Heros
    </div> --}}

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bienvenue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Bienvenue sur le CRM Super-Heros') }}
                    <div class="flex justify-center mt-6">
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 font-medium text-gray-900 bg-white rounded-md hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                            {{ __('Se connecter') }}
                        </a>
                    </div>
                    <div class="flex justify-center mt-6">
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 font-medium text-gray-900 bg-white rounded-md hover:bg-indigo-500 focus:outline-none focus:bg-indigo-500">
                            {{ __('S\'inscrire') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
