<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
		'file_name', 
		'url', 
		'size', 
		'status'
	];
}