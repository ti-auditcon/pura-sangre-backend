<?php

namespace Tests\Unit\Clases;

use Tests\UnitTestCase;
use App\Contracts\StatusInterface;
use App\Models\Clases\ReservationStatus;

class ReservationStatusTest extends UnitTestCase
{
    // ? maybe this shouldnt be here
    /** @test */
    public function reservation_class_implements_status_interface()
    {
        $reflect = new \ReflectionClass(ReservationStatus::class);

        $this->assertTrue($reflect->implementsInterface(StatusInterface::class));
    }

    /** @test */
    public function an_array_is_returned_at_statuses_list_call()
    {
        $this->assertIsArray(ReservationStatus::list());
    }
}
