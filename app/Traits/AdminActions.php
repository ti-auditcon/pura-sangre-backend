<?php

namespace App\Traits;

/**
 * [trait description]
 * @var [type]
 */
trait AdminActions
{
	/**
	 * [before description]
	 * @param  [type] $user    [description]
	 * @param  [type] $ability [description]
	 * @return [type]          [description]
	 */
	public function before($user, $ability)
    {
        if ($user->hasRole(1)) {
            return true;
        }
    }
}
