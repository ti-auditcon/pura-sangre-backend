<?php

namespace App\Models\Clases;

use App\Contracts\StatusInterface;
use App\Models\Clases\Reservation;
use Illuminate\Database\Eloquent\Model;

/**
 * [ReservationStatus description]
 */
class ReservationStatus extends Model implements StatusInterface
{
    /**
     *  For assign a pending reservation status
     */
    const PENDING = 1;

    /**
     *  For assign a confirmed reservation status
     */
    const CONFIRMED = 2;

    /**
     *  For assign a consumed reservation status
     */
    const CONSUMED = 3;

    /**
     *  Lost class reservation status
     */
    const LOST = 4;

    /**
     *  Massive assignment for this Model
     *
     *  @var  array
     */
    protected $fillable = ['reservation_status', 'type'];


    /**
     *  Return all ReservationsStatus
     *
     *  @return  array
     */
    public static function list() :array
    {
        return [
            self::PENDING   => [
                'status' => 'PENDIENTE',
                'color'  => 'warning',
                'class' => 'reserved'
            ],
            self::CONFIRMED => [
                'status' => 'CONFIRMADA',
                'color'  => 'success',
                'class' => 'confirmed'
            ],
            self::CONSUMED  => [
                'status' => 'CONSUMIDA',
                'color'  => 'info',
                 'class' => 'attended'
            ],
            self::LOST      => [
                'status' => 'PERDIDA',
                'color'  => 'danger',
                'class'  => 'missed'
            ]
        ];
    }

    /**
     *  Return all ReservationStatusColors
     *
     *  @return  array
     */
    public static function listColors()
    {
        return [
            self::PENDING   => 'warning',
            self::CONFIRMED => 'success',
            self::CONSUMED  => 'info',
            self::LOST      => 'danger',
        ];
    }

    /**
     *  Return a ReservationStatus by Identifier
     *
     *  @param   integer   $id  Id of the reservation status
     *
     *  @return  string
     */
    public static function getReservationStatus($id)
    {
        if (self::list()[$id]) {
            return self::list()[$id]['status'];
        }
        
        return 'SIN ESTADO';
    }

    /**
     *  Return a Css type color by an specific Status Id
     *
     *  @param   integer   $reservationStatusId  Id for a status
     *
     *  @return  string                          A Reservation Status Color (CSS)
     */
    public function color($reservationStatusId = null)
    {
        $reservation_status_colors = $this->listColors();

        return $reservation_status_colors[$reservationStatusId] ?? '';
    }
    
    /**
     *  Get all of the reservations for the ReservationStatus
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
