<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Detail Anak') }}
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


          <div class="flex items-center justify-end mt-4">
            <a href="/input-statistik/{{ $anak->id }}">
              <x-button class="ml-4">
                {{ __('Tambah Data') }}
              </x-button>
            </a>
          </div>
          <div class="max-w-7xl sm:px-6 lg:px-8">
            <h3 class="font-medium text-xl text-gray-800 leading-tight">
              Tabel Hasil Pengukuran
            </h3>
          </div>
          <table class="w-full text-sm text-left text-gray-500 mt-4">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th scope="col" class="px-6 py-3">
                  Tanggal Pengambilan
                </th>
                <th scope="col" class="px-6 py-3">
                  Berat
                </th>
                <th scope="col" class="px-6 py-3">
                  Tinggi
                </th>
                <th scope="col" class="px-6 py-3">
                  Lingkar Kepala
                </th>
              </tr>
            </thead>
            <tbody>

              @foreach ($statistik as $stat)
                <tr class="bg-white border-b text-gray-900 ">
                  <td class="px-6 py-4">
                    {{ $stat->date }} - {{ $stat->anak->umur($stat->date) }} bulan
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex justify-between">
                      {{ $stat->berat }} Kg
                      @if ($stat->kategoriBerat() !== null)
                        <span class="bg-blue-300 rounded text-gray-800 px-3">{{ $stat->kategoriBerat() }}</span>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex justify-between">
                      {{ $stat->tinggi }} Cm
                      @if ($stat->kategoriTinggi() !== null)
                        <span class="bg-blue-300 rounded text-gray-800 px-3">{{ $stat->kategoriTinggi() }}</span>
                      @endif
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex justify-between">
                      {{ $stat->lingkar_kepala }} Cm
                      @if ($stat->kategoriLingkarKepala() !== null)
                        <span
                          class="bg-blue-300 rounded text-gray-800 px-3">{{ $stat->kategoriLingkarKepala() }}</span>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>


          <div class="mt-5">
            <div class="max-w-7xl sm:px-6 lg:px-8 my-2">
              <h3 class="font-medium text-xl text-gray-800 leading-tight">
                Grafik Berat Badan
              </h3>
              <canvas id="chartBerat" width="400" height="250"></canvas>
            </div>
            <div class="max-w-7xl sm:px-6 lg:px-8 my-2">
              <h3 class="font-medium text-xl text-gray-800 leading-tight">
                Grafik Pertumbuhan Tinggi Badan
              </h3>
              <canvas id="chartTinggi" width="400" height="250"></canvas>
            </div>
            <div class="max-w-7xl sm:px-6 lg:px-8 my-2">
              <h3 class="font-medium text-xl text-gray-800 leading-tight">
                Grafik Lingkar Kepala
              </h3>
              <canvas id="chartLingkarKepala" width="400" height="250"></canvas>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


  <script>
    @php
      $statistik = $statistik->reverse()->flatten();
      $labels = $statistik->map(function ($item, $key) {
          return $item['date'];
      });
      $berat = $statistik->map(function ($item, $key) {
          return $item['berat'];
      });
      $tinggi = $statistik->map(function ($item, $key) {
          return $item['tinggi'];
      });
      $lingkarKepala = $statistik->map(function ($item, $key) {
          return $item['lingkar_kepala'];
      });
    @endphp


    const ctxBerat = document.getElementById('chartBerat').getContext('2d');
    const chartBerat = new Chart(ctxBerat, {
      type: 'line',
      data: {
        labels: {{ Illuminate\Support\Js::from($labels) }},
        datasets: [{
          label: 'Berat',
          data: {{ Illuminate\Support\Js::from($berat) }},
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    const ctxTinggi = document.getElementById('chartTinggi').getContext('2d');
    const chartTinggi = new Chart(ctxTinggi, {
      type: 'line',
      data: {
        labels: {{ Illuminate\Support\Js::from($labels) }},
        datasets: [{
          label: 'Tinggi',
          data: {{ Illuminate\Support\Js::from($tinggi) }},
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    const ctxLingkarKepala = document.getElementById('chartLingkarKepala').getContext('2d');
    const chartLingkarKepala = new Chart(ctxLingkarKepala, {
      type: 'line',
      data: {
        labels: {{ Illuminate\Support\Js::from($labels) }},
        datasets: [{
          label: 'Lingkar Kepala',
          data: {{ Illuminate\Support\Js::from($lingkarKepala) }},
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</x-app-layout>
