<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $table = 'reminders';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_reminder',
        'id_desa',
    ];
}
