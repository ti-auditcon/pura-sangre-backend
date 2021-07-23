<?php

namespace App\Models\Clases;

use App\Models\Plans\Plan;
use App\Models\Clases\Block;
use Illuminate\Database\Eloquent\Model;

class BlockPlan extends Model
{
	/**
	 * [block description]
	 *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function block()
	{
		return $this->belongsTo(Block::class);
	}

	/**
	 * [plan description]
	 *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function plan()
	{
		return $this->belongsTo(Plan::class);
	}
}
