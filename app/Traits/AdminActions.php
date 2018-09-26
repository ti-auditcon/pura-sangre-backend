<?php

namespace App\Traits;

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
        if ($user->esAdministrador()) {
            return true;
        }
    }
}
