<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, \App\Traits\LogsActivity;

    protected $fillable = ['nama_kategori', 'parent_id'];

    // Kategori ini ada banyak spesimen (Isi Laci)
    public function specimens() {
        return $this->hasMany(Specimen::class, 'category_id');
    }

    // Untuk panggil sub-kategori bawahnya (Anak Laci)
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Untuk tahu siapa Induk di atasnya (Kabinet Utama)
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
