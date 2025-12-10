<?php
// App\Notifications\PeminjamanBaruNotification.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use App\Models\peminjaman;
use Carbon\Carbon;

class PeminjamanBaruNotification extends Notification
{
    use Queueable,Notifiable;

    protected Peminjaman $peminjaman;

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
            'item_name' => $this->peminjaman->item->nama_item,
            'tanggal' => Carbon::parse($this->peminjaman->tanggal)->format('d M Y'),
            'message' => "{$this->peminjaman->user->name} mengajukan peminjaman {$this->peminjaman->item->nama_item}",
        ];
    }
}