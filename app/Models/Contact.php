<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'category',
        'sort_order',
        'photo',
        'is_active'
    ];

    // Связь с кнопками мессенджеров
    public function links()
    {
        return $this->hasMany(ContactLink::class);
    }
}
