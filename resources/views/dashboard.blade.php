<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in a ") . Auth::user()->name }}
                </div>
            </div>
            <section class="bg-white border rounded-2xl shadow-sm mt-6 p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-gray-700 font-semibold">Saldo</h3>
                        <p class="mt-2 text-3xl md:text-4xl font-bold tracking-tight">
                            Rp. <span class="counters-rupiah" data-count="{{ $saldo ?? 0 }}">{{ number_format($saldo ?? 0, 0, ',', '.') }}</span>
                        </p>
                        <p class="mt-1 text-sm text-gray-500">Saldo tersedia</p>
                    </div>
                </div>
            </section>
            <section class="bg-white border rounded-2xl shadow-sm mt-0 p-6">
                <h3 class="text-gray-700 font-semibold">Salin Link</h3>
                <p class="mt-1 text-sm text-gray-500">Bagikan tautan Sawer</p>

                <div class="mt-4 flex gap-3">
                    <input id="profile-url" type="text" readonly value="{{ url('/profile/' . Auth::user()->username) }}"
                        class="flex-1 rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-700
                    focus:outline-none focus:ring-0 focus:border-gray-400">
                    <button onclick="copyProfileUrl()" class="px-5 py-3 rounded-xl font-medium bg-blue-600 text-white
                     hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Salin
                    </button>
                </div>
            </section>
        </div>
    </div>
    @push('scripts')
        <script>
            function copyProfileUrl() {
                const profileUrlInput = document.getElementById('profile-url');
                profileUrlInput.select();
                profileUrlInput.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(profileUrlInput.value);
                alert('Profile URL copied to clipboard!');
            }
        </script>
    @endpush
</x-app-layout>