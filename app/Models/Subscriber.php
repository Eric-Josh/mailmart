<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = ['list_id','name','email','status'];

    public function list()
    {
        return $this->belongsTo(\App\Models\ContactList::class, 'list_id');
    }
}
