<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     *  Return an array list with the available minutes
     *
     *  @return  array
     */
    public static function listOfAvailableMinutesToSendPushes()
    {
        return [
            30, 45, 60, 90, 120, 150, 180, 210
        ];
    }

    /**
     *  The time to remove users from a class should always be after the time of push notification
     *
     *  @return  array
     */
    public static function listOfAvailableMinutesToRemoveUsersFromClases()
    {
        return [
            15, 30, 45, 60, 90, 120, 150, 180
        ];
    } 
}
