<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <center> <h1> Powered By </h1> </center>
            <a href="/">
                <img src="{{url('img/telkom.png')}}" alt="logo">
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div class="w-full text-center my-3">
            <a href="{{ route('register-orang-tua') }}">
                <button class="w-full px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Orang Tua
                </button>
            </a>
        </div>
        <div class="w-full text-center my-3">
            <a href="{{ route('register-posyandu') }}">
                <button class="w-full px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Kader Posyandu
                </button>
            </a>
        </div>


    </x-auth-card>
    <div class="footer">
        <h1>Telkom University <p> Telkom University | Universitas Swasta Terbaik</h1>
        <style>
            .footer {
              left: 0;
              bottom: 0;
              width: 100%;
              background-color: rgb(255, 0, 0);
              color: white;
              text-align: center;
            }
            </style>
    </div>
</x-guest-layout>
