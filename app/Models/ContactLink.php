<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLink extends Model
{
    protected $fillable = [
        'contact_id',
        'type',
        'url'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
