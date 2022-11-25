<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Input Data Baru') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-red-200">
          <!-- Validation Errors -->
          <x-auth-validation-errors class="mb-4" :errors="$errors" />

          @if (Auth::user()->role->role == 'ORANG_TUA')
            <form method="POST" enctype="multipart/form-data" action="{{ route('input-anak-orang-tua') }}">
          @endif
          @if (Auth::user()->role->role == 'KADER_POSYANDU')
            <form method="POST" enctype="multipart/form-data" action="{{ route('input-anak-orang-tua') }}">
          @endif

          @csrf

          <!-- Name -->
          <div>
            <x-label for="nama" :value="__('Nama')" />

            <x-input id="nama" class="block mt-1 w-full" type="text" name="nama" :value="old('nama')" required
              autofocus />
          </div>

          <div>
            <x-label for="panggilan" :value="__('Panggilan')" />

            <x-input id="panggilan" class="block mt-1 w-full" type="text" name="panggilan" :value="old('panggilan')"
              autofocus />
          </div>

          <div>
            <x-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />

            <x-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')"
              required autofocus />
          </div>

          <div>
            <x-label for="tinggi" :value="__('Tinggi')" />

            <x-input id="tinggi" class="block mt-1 w-full" type="text" name="tinggi" :value="old('tinggi')"
              autofocus />
          </div>

          <div>
            <x-label for="berat" :value="__('Berat')" />

            <x-input id="berat" class="block mt-1 w-full" type="text" name="berat" :value="old('berat')"
              autofocus />
          </div>

          <div>
            <x-label for="lingkar_kepala" :value="__('Lingkar Kepala')" />

            <x-input id="lingkar_kepala" class="block mt-1 w-full" type="text" name="lingkar_kepala"
              :value="old('lingkar_kepala')" autofocus />
          </div>

          @if (Auth::user()->role->role == 'KADER_POSYANDU')
            <div>
              <x-label for="nama_orang_tua" :value="__('Nama Orang Tua')" />

              <x-input id="nama_orang_tua" class="block mt-1 w-full" type="text" name="nama_orang_tua"
                :value="old('nama_orang_tua')" required autofocus />
            </div>

            <div>
              <x-label for="alamat" :value="__('Alamat')" />

              <x-input id="alamat" class="block mt-1 w-full" type="text" name="alamat" :value="old('alamat')"
                autofocus />
            </div>
          @endif


          <div>
            <x-label for="gender" :value="__('Jenis Kelamin')" />
            <select id="gender" name="gender"
              class="rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
              required autofocus>
              <option value="LAKI_LAKI">Laki Laki</option>
              <option value="PEREMPUAN">Perempuan</option>
            </select>
          </div>

          <div>
            <x-label for="image" :value="__('Foto')" />
            <input
              class="rounded-md shadow-sm border-gray-300 focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50"
              id="image" type="file">
          </div>

          <div class="flex items-center justify-end mt-4">
            <x-button class="ml-4">
              {{ __('Tambahkan Anak') }}
            </x-button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
