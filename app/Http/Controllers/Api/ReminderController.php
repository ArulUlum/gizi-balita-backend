<?php

namespace App\Http\Controllers\Api;

use App\Models\Reminder;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReminderController extends ApiBaseController
{

    public function index()
    {
        $response = Reminder::all();
        return $this->successResponse("Data Reminder", $response);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = validator($request->all(), [
            'judul' => ['required', 'string'],
            'deskripsi' => ['required', 'string'],
            'tanggal_reminder' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return $this->errorValidationResponse("Inputan Tidak Sesuai", $validator->errors());
        }

        $reminder = Reminder::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal_reminder' => $request->tanggal_reminder,
            'id_desa' => $user->id_desa,
        ]);

        $reminder->save();

        return $this->successResponse("Reminder telah di set");
    }

    public function show()
    {
        $user = Auth::user();
        $tanggalSekarang = Carbon::now()->toDateString();    //untuk tanggal
        // $tanggalSekarang = Carbon::now()->format('Y-m-d');       //untuk bulan
        $reminder = Reminder::where('id_desa', $user->id_desa)->whereDate('tanggal_reminder', '>=', $tanggalSekarang)->orderBy('tanggal_reminder', 'asc')->get();
        
        return $this->successResponse("Data Reminder", $reminder);
    }


    public function update(Request $request, $id)
    {
        $reminder = Reminder::find($id);
        if (empty($reminder)) {
            return $this->errorNotFound("Data Reminder tidak ditemukan");
        }
        $reminder->fill($reminder->all());
        $reminder->save();

        return $this->successResponse("Success Update Data Reminder", $reminder);
    }

    public function destroy($id)
    {
        $reminder = Reminder::find($id);

        if (empty($reminder)) {
            return $this->errorNotFound("Data Reminder tidak ditemukan");
        }

        $reminder->delete();

        return $this->successResponse("Success Delete Data Reminder");
    }
}
