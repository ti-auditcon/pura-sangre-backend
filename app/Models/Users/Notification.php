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
	 * [$fillable description]
	 * 
	 * @var [type]
	 */
    protected $fillable = ['users', 'title', 'body', 'sended', 'trigger_at'];
}
