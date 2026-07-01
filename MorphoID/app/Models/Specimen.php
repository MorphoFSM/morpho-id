<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specimen extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $fillable = [
        'nama_spesimen',
        'ciri_ciri',
        'gambar',
        'penerangan',
        'category_id',
        'parent_id', // <--- Kita masukkan balik untuk fungsi sub-spesimen
        'galeri',
        ];

        protected $casts = [
        'ciri_ciri'  => 'array',
        'galeri'     => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==========================================
    // HUBUNGAN DENGAN JADUAL CATEGORIES
    // ==========================================
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ==========================================
    // HUBUNGAN REKURSIF (UNTUK SUB-SPESIMEN)
    // ==========================================

    // Spesimen ni boleh ada banyak anak
    public function children()
    {
        return $this->hasMany(Specimen::class, 'parent_id');
    }

    // Spesimen ni ada satu induk (kalau dia sub-item)
    public function parent()
    {
        return $this->belongsTo(Specimen::class, 'parent_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
