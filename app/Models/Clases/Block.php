<?php

namespace App\Models\Clases;

use App\Models\Users\User;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    /**
     * $table variable to define table name
     * 
     * @var string
     */
    protected $table = 'blocks';

    /**
     * $fillable for mass assignment
     * 
     * @var array
     */
    protected $fillable = ['start', 'end', 'dow', 'title',
        'date', 'profesor_id', 'quota', 'clase_type_id'
    ];

    /**
     * Append attributes to queries
     * 
     * @var array
     */
    // protected $appends = ['plans_id','color'];

    // protected $with = array('plans');

    /**
     * [getEndAttribute description]
     * 
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getEndAttribute($value)
    {
        if ($this->date!=null) {
            
            return $this->date.' '.$value;
        
        } else {
            
            return $value;
        
        }
    }

    /**
     * Get the color of the block who this belongs to
     * 
     * @return [type] [description]
     */
    public function getColorAttribute()
    {
        return $this->claseType->clase_color;
    }

    /**
     * Transformamos el valor de dow a un arreglo para FullCalendar
     * 
     * @param  [type] $value [description]
     * @return array
     */
    public function getDowAttribute($value)
    {
        $array = [];
        
        array_push($array,$value);
        
        return $array;
    }

    /**
     * [getPlansIdAttribute description]
     * 
     * @return array
     */
    public function getPlansIdAttribute()
    {
        return $this->plans->pluck('id');
    }

    /**
     * [getStartAttribute description]
     * 
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getStartAttribute($value)
    {
        if ($this->date!=null) {
            
            return $this->date.' '.$value;
        
        } else {
            
            return $value;
        
        }
    }

    /**
     * Get all the clases of this Model
     * 
     * @return Illuminate\Database\Eloquent
     */
    public function clases()
    {
        return $this->hasMany(Clase::class);
    }

    /**
     * Get all the plans of this Model
     * 
     * @return Illuminate\Database\Eloquent
     */
    public function plans()
    {
        return $this->belongsToMany('App\Models\Plans\Plan', 'block_plan');
    }

    /**
     * Get the User of this Model
     * 
     * @return Illuminate\Database\Eloquent
     */
    public function user()
    {
        return $this->belongsTo(User::class,'profesor_id');
    }

    /**
     * Get the clase type of this Model
     * 
     * @return Illuminate\Database\Eloquent
     */
    public function claseType()
    {
        return $this->belongsTo(ClaseType::class);
    }
}
