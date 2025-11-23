<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Kategori;
use App\Models\Jurusan;
use App\Models\peminjaman;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jurusan_id',
        'kategori_id',
        'kelas',
        'telepon',
        'profile'
    ];

    public static function users(){
        return DB::table('users')->get();
    }

    public function peminjaman(){
        return $this->hasMany(peminjaman::class);
    }

    public function jurusan(){
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
