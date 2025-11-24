<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\peminjaman;

class PeminjamanRejectedNotification extends Notification
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
            'type' => 'peminjaman_rejected',
            'peminjaman_id' => $this->peminjaman->id,
            'item_name' => $this->peminjaman->item->nama_barang,
            'tanggal' => $this->peminjaman->tanggal->format('d M Y'),
            'message' => "Peminjaman {$this->peminjaman->item->nama_barang} Anda ditolak.",
        ];
    }
}