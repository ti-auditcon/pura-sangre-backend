<?php

namespace App\Models\Wods;

use Carbon\Carbon;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use Illuminate\Database\Eloquent\Model;

class Wod extends Model
{
    protected $dates = ['date'];
    protected $fillable = ['date','clase_type_id'];
    protected $appends = ['start','allDay','title','url'];

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function clases()
    {
        return $this->hasMany(Clase::class);
    }

    public function clase_type()
    {
        return $this->belongsTo(ClaseType::class);
    }

    public function stages()
    {
        return $this->hasMany(Stage::class);
    }

    //ETAPA POR ID
    public function stage($id)
    {
        return $this->hasMany(Stage::class)->where('stage_type_id',$id)->first() ?? null;
    }

    public function getAllDayAttribute()
    {
        return true;
    }

    public function getStartAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function getTitleAttribute()
    {
        return 'WorkOut';
    }

    public function getUrlAttribute()
    {
        return url('/wods/'.$this->id.'/edit');
    }
}
