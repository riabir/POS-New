<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Shareholder: ') . $shareholder->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('shareholders.update', $shareholder) }}">
                        @csrf
                        @method('PUT')
                        {{-- Shareholder Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><x-input-label for="name" value="Name" /><x-text-input id="name" name="name" :value="old('name', $shareholder->name)" class="block mt-1 w-full" required /></div>
                            <div><x-input-label for="email" value="Email" /><x-text-input id="email" name="email" :value="old('email', $shareholder->email)" type="email" class="block mt-1 w-full" /></div>
                            <div><x-input-label for="phone" value="Phone" /><x-text-input id="phone" name="phone" :value="old('phone', $shareholder->phone)" class="block mt-1 w-full" /></div>
                            <div><x-input-label for="join_date" value="Join Date" /><x-text-input id="join_date" name="join_date" :value="old('join_date', $shareholder->join_date->format('Y-m-d'))" type="date" class="block mt-1 w-full" required /></div>
                            <div class="md:col-span-2"><x-input-label for="address" value="Address" /><textarea id="address" name="address" class="block mt-1 w-full border-gray-300 ...">{{ old('address', $shareholder->address) }}</textarea></div>
                            <div>
                                <x-input-label for="is_active" value="Status" />
                                <select name="is_active" id="is_active" class="block mt-1 w-full border-gray-300 ...">
                                    <option value="1" @selected(old('is_active', $shareholder->is_active) == 1)>Active</option>
                                    <option value="0" @selected(old('is_active', $shareholder->is_active) == 0)>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('shareholders.index') }}" class="text-sm ...">Cancel</a>
                            <x-primary-button class="ml-4">Update Shareholder</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>