<?php

namespace App\Models\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class StatusUser extends Model
{
  protected $fillable = ['status_user'];

  public function users()
  {
    return $this->hasMany(User::class);
  }
}
