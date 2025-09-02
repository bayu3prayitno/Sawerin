<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="mx-auto max-w-3xl px-4 py-10">
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">
                Kirim dukungan ke {{ $user->name }}
            </h1>

            @guest
                <a href="{{ route('login') }}"
                    class="shrink-0 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Login
                </a>
            @endguest
        </div>

        <p class="mt-1 text-gray-700">
            Sawer ke <span class="underline">{{ $user->username }}</span>
        </p>
        {{-- KARTU FORM dengan bayangan offset hitam --}}
        <div class="relative mt-6">
            <div class="absolute inset-0 translate-x-2 translate-y-2 rounded-2xl bg-black"></div>

            <form method="POST" action="{{ route('donation.store')}}"
                class="relative rounded-2xl border-[3px] border-black bg-emerald-50 p-6 md:p-8">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="space-y-6">
                    {{-- Dari --}}
                    <div>
                        <label class="block text-lg font-medium">Dari:</label>
                        <input type="text" name="name" placeholder="John Doe" required class="mt-1 w-full bg-transparent border-0 border-b-2 border-black
                        placeholder-gray-400 focus:border-black focus:ring-0"
                            value="{{auth()->check() ? auth()->user()->name : old('name')}}" @if(auth()->check())
                            readonly @endif>
                    </div>
                    {{-- Email --}}
                    <div>
                        <label class="block text-lg font-medium">Email:</label>
                        <input type="email" name="email" required placeholder="budi@saweria.co" class="mt-1 w-full bg-transparent border-0 border-b-2 border-black
                        placeholder-gray-400 focus:border-black focus:ring-0"
                            value="{{auth()->check() ? auth()->user()->email : old('email')}}" @if(auth()->check())
                            readonly @endif>
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    {{-- Nominal --}}
                    <div>
                        <label class="block text-lg font-medium">Nominal:<span class="text-red-600"> *</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}" placeholder="10000" min="1000"
                            required class="mt-1 w-full bg-transparent border-0 border-b-2 border-black
                        placeholder-gray-400 focus:border-black focus:ring-0">
                        @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    {{-- Pesan --}}
                    <div>
                        <label class="block text-lg font-medium">Pesan:<span class="text-red-600"> *</span></label>
                        <textarea name="message" value="{{ old('message') }}" required class="mt-1 w-full bg-transparent border-0 border-b-2 border-black
                        placeholder-gray-400 focus:border-black focus:ring-0"></textarea>
                        @error('message') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                {{-- Tombol--}}
                <div class="mt-8 flex justify-end gap-3">
                    <button type="submit" name="method" value="" class="border-2 border-black rounded-xl px-6 py-3 font-semibold tracking-wide text-white
                       bg-blue-500 hover:brightness-95
                       shadow-[4px_4px_0_#000] active:translate-x-[2px] active:translate-y-[2px]
                       active:shadow-[2px_2px_0_#000]">
                        KIRIM
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>