<?php
// App\Notifications\PeminjamanBaruNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\peminjaman;

class PeminjamanBaruNotification extends Notification
{
    use Queueable;

    protected $peminjaman;

    public function __construct(peminjaman $peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'peminjaman_baru',
            'peminjaman_id' => $this->peminjaman->id,
            'user_name' => $this->peminjaman->user->name,
            'item_name' => $this->peminjaman->item->nama_barang,
            'keperluan' => $this->peminjaman->keperluan,
            'tanggal' => $this->peminjaman->tanggal->format('d M Y'),
            'message' => "{$this->peminjaman->user->name} mengajukan peminjaman {$this->peminjaman->item->nama_barang}",
        ];
    }
}