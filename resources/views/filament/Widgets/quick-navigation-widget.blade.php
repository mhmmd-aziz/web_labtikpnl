@php
    use App\Filament\Resources\UserResource;
    use App\Filament\Resources\ProdiResource;
    use App\Filament\Resources\RoomResource;
@endphp

<x-filament-widgets::widget>
    <x-filament::card>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Navigasi Cepat</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Akses cepat ke fitur utama sistem</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                
                {{-- Tombol Kelola User --}}
                <a href="{{ UserResource::getUrl() }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all duration-200 group">
                    <x-heroicon-o-users class="w-8 h-8 text-primary-500 mr-4"/>
                    <div class="text-left">
                        <h4 class="font-medium text-gray-900 dark:text-white">Kelola User</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manajemen pengguna sistem</p>
                    </div>
                </a>

                {{-- Tombol Program Studi --}}
                <a href="{{ ProdiResource::getUrl() }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all duration-200 group">
                    <x-heroicon-o-academic-cap class="w-8 h-8 text-primary-500 mr-4"/>
                    <div class="text-left">
                        <h4 class="font-medium text-gray-900 dark:text-white">Program Studi</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Data program studi</p>
                    </div>
                </a>

                {{-- Tombol Ruang Kuliah --}}
                <a href="{{ RoomResource::getUrl() }}" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/10 transition-all duration-200 group">
                     <x-heroicon-o-building-office-2 class="w-8 h-8 text-primary-500 mr-4"/>
                    <div class="text-left">
                        <h4 class="font-medium text-gray-900 dark:text-white">Ruang Kuliah</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manajemen ruang perkuliahan</p>
                    </div>
                </a>

            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>