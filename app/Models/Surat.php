<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Surat extends Model
{
    protected $primaryKey = 'surat_id';
    protected $fillable = ['mhs_id', 'jenis_surat_id', 'atas_nama', 'ormawa_id', 'perihal', 'deskripsi', 'status'];
    public function jenisSurat() {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
    public function lampiran() {
        return $this->hasOne(Lampiran::class, 'surat_id');
    }
    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class, 'mhs_id');
    }
    public function approvals() {
        return $this->hasMany(Approval::class, 'surat_id');
    }
}