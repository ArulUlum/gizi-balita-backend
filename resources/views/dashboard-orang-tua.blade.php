<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Data Anak') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                  <th scope="col" class="px-6 py-3">
                    Nama
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Umur
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Berat Badan
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Tinggi
                  </th>
                  <th scope="col" class="px-6 py-3">
                    Lingkar Kepala
                  </th>
                  <th scope="col" class="px-6 py-3">
                    <span class="sr-only">Detail</span>
                  </th>
                </tr>
              </thead>
              <tbody>

                @foreach ($data as $anak)
                  <tr class="bg-white border-b text-gray-900 ">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                      {{ $anak->nama }}
                    </th>
                    <td class="px-6 py-4">
                      {{ $anak->umur(now()) }} bulan ({{ $anak->tanggal_lahir }})
                    </td>
                    <td class="px-6 py-4">
                      {{ $anak->beratTerakhir() }} Kg
                    </td>
                    <td class="px-6 py-4">
                      {{ $anak->tinggiTerakhir() }} Cm
                    </td>
                    </td>
                    <td class="px-6 py-4">
                      {{ $anak->lingkarKepalaTerakhir() }}
                    </td>
                    <td class="px-6 py-4 text-right">
                      <a href="/detail-anak/{{ $anak->id }}"
                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Detail</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
