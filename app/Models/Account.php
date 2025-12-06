<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

//    public int $id;
    protected $table = 'accounts';
    protected $guarded = [];
    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
