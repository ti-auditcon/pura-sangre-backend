<?php

namespace App\Models\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    // id type is string
    protected $keyType = 'string';

    protected $guarded = [];
    
    protected $hidden = ['payload'];
    
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
