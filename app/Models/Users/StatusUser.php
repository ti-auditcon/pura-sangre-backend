<?php

namespace App\Models\Users;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * [StatusUser description]
 */
class StatusUser extends Model
{
    /**
     *  Id of an Status User
     *
     *  @param  integer
     */
    const ACTIVE = 1;

    /**
     *  Id of an Status User
     *
     *  @param  integer
     */
    const INACTIVE = 2;

    /**
     *  Id of an Status User
     *
     *  @param  integer
     */
    const TEST = 3;

	/**
	 *  [users description]
	 *  @return [type] [description]
	 */
	public function users()
	{
		return $this->hasMany(User::class);
    }

    /**
     *  listAllStatuses
     *
     *  @return  array
     */
    public static function listAllStatuses()
    {
        return [
            self::ACTIVE => 'ACTIVO',
            self::INACTIVE => 'INACTIVO',
            self::TEST => 'PRUEBA'
        ];
    }

    /**
     *  methodDescription
     *
     *  @return  returnType
     */
    public static function getStatus($statusId)
    {
        $statuses = self::listAllStatuses();

        return $statuses[$statusId] ?? 'SIN ESTADO';
    }

        /**
     *  listAllStatuses
     *
     *  @return  array
     */
    public static function listAllStatusesColors()
    {
        return [
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::TEST => 'warning'
        ];
    }

    /**
     *  methodDescription
     *
     *  @return  returnType
     */
    public static function getStatusColor($statusId)
    {
        $statuses = self::listAllStatusesColors();

        return $statuses[$statusId] ?? 'secondary';
    }
}
