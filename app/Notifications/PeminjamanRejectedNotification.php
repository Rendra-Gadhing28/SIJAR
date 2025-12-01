<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\peminjaman;
use Carbon\Carbon;

class PeminjamanRejectedNotification extends Notification
{
    use Queueable;

    protected $peminjaman;
    protected $alasan;

    public function __construct(peminjaman $peminjaman, ?string $alasan = null)
    {
        $this->peminjaman = $peminjaman;
        $this->alasan = $alasan;
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
            'tanggal' => Carbon::parse($this->peminjaman->tanggal)->format('d M Y'),
            'reason' => $this->alasan,
            'message' => $this->alasan
                ? "Peminjaman {$this->peminjaman->item->nama_barang} Anda ditolak. Alasan: {$this->alasan}"
                : "Peminjaman {$this->peminjaman->item->nama_barang} Anda ditolak.",
        ];
    }
}