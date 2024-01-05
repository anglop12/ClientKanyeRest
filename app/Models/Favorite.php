<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';

    public $timestamps = true;

    public $fillable = [
        'quote', 'user_id'
    ];

    protected $appends = ['user_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserNameAttribute() {
        return $this->user->name ?? '';
    }

}
