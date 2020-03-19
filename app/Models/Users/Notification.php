<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	/**
	 * [$table description]
	 *
	 * @var string
	 */
	protected $table = 'notifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['users', 'title', 'body', 'sended', 'trigger_at'];
}
