<?php

namespace Tests\Traits;

/**
 * Trait CarbonTrait
 *
 * @package Tests\Traits
 */
trait CarbonTrait 
{
	/**
	 * Travel to a given date
	 *
	 * @param  string  $date
	 * @return void
	 */
	public function travelTo($date): void
	{
		\Carbon\Carbon::setTestNow($date);
	}
}