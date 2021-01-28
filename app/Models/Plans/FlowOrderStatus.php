<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Model;

class FlowOrderStatus extends Model
{
    /**
     *  Order status ID
     */
     const PAGADO = 1;

    /**
     *  Order status ID
     */
    const PENDIENTE = 2;

    /**
     *  Order status ID
     */
    const ANULADO = 3;

    /**
     *  Order status ID
     */
    const OTRO = 4;

    /**
     *  Undocumented function
     *
     *  @return  void
     */
    public function listAllStatus()
    {
        return [
            self::PAGADO     => 'pagada',
            self::PENDIENTE  => 'pendiente',
            self::OTRO       => 'OTRO',
            self::ANULADO    => 'Anulada',
        ];
    }

    /**
     *  Retrieve Flow Status Order by Id
     *
     *  @return  returnType
     */
    public function getStatus($statusOrderId)
    {
        $status_orders = $this->listAllStatusOrders();

        return $status_orders[$statusOrderId] ?? 'sin estado';
    }
}
