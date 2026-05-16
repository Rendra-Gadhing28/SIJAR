<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'rejected_at'
    ];

    protected $casts = [
        'jam_pembelajaran' => 'array',
        'tanggal' => 'date',
        'finished_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Scope untuk peminjaman aktif
    public function scopeActive($query)
    {
        return $query->where('status_pinjaman', 'dipinjam');
    }

    // Scope untuk peminjaman selesai
    public function scopeCompleted($query)
    {
        return $query->where('status_pinjaman', 'selesai');
    }

    // Scope untuk peminjaman telat
    public function scopeLate($query)
    {
        return $query->where('status_pinjaman', 'telat');
    }

    // Accessor untuk status yang sudah diformat
    public function getStatusPinjamanFormattedAttribute(): string
    {
        return match($this->status_pinjaman) {
            'dipinjam' => 'Dipinjam',
            'selesai' => 'Selesai',
            'telat' => 'Terlambat',
            default => ucfirst($this->status_pinjaman)
        };
    }

    // Accessor untuk status tujuan yang sudah diformat
    public function getStatusTujuanFormattedAttribute(): string
    {
        return match($this->status_tujuan) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status_tujuan)
        };
    }
}