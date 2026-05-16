<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Peminjaman;
use App\Models\User;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * $schedule akan otomatis tersedia, di-inject oleh Laravel
     */
    protected function schedule(Schedule $schedule)
    {
        // Di sini Anda bisa menggunakan $schedule
        
        $schedule->call(function () {
            // Logic Anda di sini
            $telatPeminjaman = Peminjaman::where('status_pinjaman', 'dipinjam')
                ->where('returned_at', '<', now())
                ->get();
            
            foreach ($telatPeminjaman as $pinjam) {
                $telatJam = now()->diffInHours($pinjam->returned_at);
                $denda = $telatJam * 5000;
                
                $pinjam->update([
                    'status_pinjaman' => 'telat',
                    'denda' => $denda
                ]);
            }
        })->everyFiveMinutes();
    }
    
    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        
        require base_path('routes/console.php');
    }
}