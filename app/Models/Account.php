<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class Account extends Model
{

//    public int $id;
    protected $table = 'accounts';
    protected $guarded = [];
    public $timestamps = false;
}
