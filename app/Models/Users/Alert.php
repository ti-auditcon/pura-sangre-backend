<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    /**
     *  The attributes that should be mutated to dates.
     *
     *  @var  array
     */
    protected $dates = ['from', 'to'];

    /**
     *  The attributes that should be cast to native types. Like a getter Method
     *
     *  @var  array
     */
    protected $casts = [
        'from' => 'datetime:d-m-Y',
        'to' => 'datetime:d-m-Y'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = ['from', 'to', 'message'];

    /**
     *  [setFromAttribute description]
     *
     *  @param   [type]  $value  [$value description]
     *
     *  @return  [type]          [return description]
     */
    public function setFromAttribute($value)
    {
        $this->attributes['from'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     *  [setToAttribute description]
     *
     *  @param   [type]  $value  [$value description]
     *
     *  @return  [type]          [return description]
     */
    public function setToAttribute($value)
    {
        $this->attributes['to'] = Carbon::parse($value)->format('Y-m-d');
    }
}
