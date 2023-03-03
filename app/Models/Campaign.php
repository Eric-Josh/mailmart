<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'from_name',
        'from_email',
        'reply_to',
        'message',
        'attachment',
        'list_id',
        'total_sent',
        'status'
    ];

    public function list()
    {
        return $this->belongsTo(\App\Models\ContactList::class, 'list_id');
    }
}
