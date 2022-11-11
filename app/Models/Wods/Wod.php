<?php

namespace App\Models\Wods;

use Carbon\Carbon;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use Illuminate\Database\Eloquent\Model;

class Wod extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['date', 'clase_type_id'];

    /**
     * [$appends description]
     *
     * @var [type]
     */
    protected $appends = ['start','allDay','title','url'];

    /**
     * [setDateAttribute description]
     *
     * @param   [type]  $value  [$value description]
     *
     * @return  [type]          [return description]
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * [clases description]
     *
     * @return  [type]  [return description]
     */
    public function clases()
    {
        return $this->hasMany(Clase::class);
    }

    /**
     * [clase_type description]
     *
     * @return  [type]  [return description]
     */
    public function clase_type()
    {
        return $this->belongsTo(ClaseType::class);
    }

    /**
     * [stages description]
     *
     * @return  [type]  [return description]
     */
    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    //ETAPA POR ID
    public function stage($id)
    {
        return $this->hasMany(Stage::class)->where('stage_type_id',$id)->first() ?? null;
    }

    /**
     * [getAllDayAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getAllDayAttribute()
    {
        return true;
    }

    /**
     * [getStartAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getStartAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    /**
     * Get the ClaseTypr for the Clases Calendar
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        return "Rutina de {$this->clase_type->clase_type}";
    }

    /**
     * [getUrlAttribute description]
     *
     * @return  [type]  [return description]
     */
    public function getUrlAttribute()
    {
        return url('/wods/'.$this->id.'/edit');
    }
}
