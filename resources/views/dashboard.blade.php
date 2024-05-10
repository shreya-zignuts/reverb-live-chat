<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Contacts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($users as $user)
                    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md">
                        <div class="p-4">
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('chat', $user->id) }}" class="hover:underline">{{ $user->name }}</a>
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
