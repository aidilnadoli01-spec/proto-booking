<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nama
 * @property string $spesialisasi
 * @property string $no_str
 * @property string|null $telepon
 * @property string|null $alamat
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';

    protected $fillable = ['nama', 'spesialisasi', 'no_str', 'telepon', 'alamat'];

    public function jadwalDokter(): HasMany
    {
        return $this->hasMany(JadwalDokter::class);
    }
}
