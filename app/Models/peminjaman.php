<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Items;
use Illuminate\Support\Facades\DB;
use Inertia\Commands\StopSsr;

class Peminjaman extends Model
{
      protected $table = 'peminjaman';

    protected $fillable = [
        'keperluan',
        'user_id',
        'item_id',
        'tanggal',
        'finished_at',
        'status_tujuan',
        'status_pinjaman',
        'gambar_bukti',
        'jam_pembelajaran',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'finished_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'jam_pembelajaran' => 'array'
    ];

    public static function getAll(){
        return DB::table('peminjaman');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
