<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Input Statistik Anak') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-red-200">
          Nama : {{ $anak->nama }} </br>
          Panggilan : {{ $anak->panggilan }} </br>
          Alamat: {{ $anak->alamat }} </br>
          Umur: {{ $anak->umur(now()) }} bulan ({{ $anak->tanggal_lahir }})</br>

          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          <form method="POST" action="{{ route('input-statistik', $anak->id) }}">
            @csrf

            <!-- Name -->
            <div>
              <x-label for="berat" :value="__('Berat')" />

              <x-input id="berat" class="block mt-1 w-full" type="text" name="berat" :value="old('berat')"
                autofocus />
            </div>

            <div>
              <x-label for="z_score_berat" :value="__('Z Score Berat')" />
              <select id="z_score_berat" name="z_score_berat"
                class="rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                autofocus>
                <option value="">-</option>
                <option value="-4">Di bawah -3</option>
                <option value="-1">-3 sampai kurang dari -2</option>
                <option value="0">-2 sampai 1</option>
                <option value="1.5">Lebih dari 1 sampai 2</option>
                <option value="3">Di atas 2</option>
              </select>
            </div>

            <div>
              <x-label for="tinggi" :value="__('Tinggi')" />

              <x-input id="tinggi" class="block mt-1 w-full" type="text" name="tinggi" :value="old('tinggi')"
                autofocus />
            </div>

            <div>
              <x-label for="z_score_tinggi" :value="__('Z Score Tinggi')" />
              <select id="z_score_tinggi" name="z_score_tinggi"
                class="rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                autofocus>
                <option value="">-</option>
                <option value="-4">Di bawah -3</option>
                <option value="-1">-3 sampai kurang dari -2</option>
                <option value="1">-2 sampai 2</option>
                <option value="3">Di atas 2</option>
              </select>
            </div>

            <div>
              <x-label for="lingkar_kepala" :value="__('Lingkar Kepala')" />

              <x-input id="lingkar_kepala" class="block mt-1 w-full" type="text" name="lingkar_kepala"
                :value="old('lingkar_kepala')" autofocus />
            </div>

            <div>
              <x-label for="z_score_lingkar_kepala" :value="__('Z Score Lingkar Kepala')" />
              <select id="z_score_lingkar_kepala" name="z_score_lingkar_kepala"
                class="rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                autofocus>
                <option value="">-</option>
                <option value="-3">Di bawah -2</option>
                <option value="1">-2 sampai 2</option>
                <option value="3">Di atas 2</option>
              </select>
            </div>



            <div class="flex items-center justify-end mt-4">
              <x-button class="ml-4">
                {{ __('Tambahkan Data') }}
              </x-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
