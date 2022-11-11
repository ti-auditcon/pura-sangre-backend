<?php

namespace App\Models\Bills;

use App\Models\Bills\Bill;
use Illuminate\Database\Eloquent\Model;

/**
 * [PaymentType description]
 */
class PaymentType extends Model
{
    /**
     * Efectivo payment Id
     *
     * @var  int
     */
    const EFECTIVO = 1;
    
    /**
     * Transferencia payment Id
     *
     * @var  int
     */
    const TRANSFERENCIA = 2;
    
    /**
     * Cheque payment Id
     *
     * @var  int
     */
    const CHEQUE = 3;
    
    /**
     * Debito payment Id
     *
     * @var  int
     */
    const DEBITO = 4;
    
    /**
     * Credito payment Id
     *
     * @var  int
     */
    const CREDITO = 5;

    /**
     * Flow payment Id
     *
     * @var  int
     */
    const FLOW = 6;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = ['payment_type'];

    /**
     * Return the relationship of this PaymentType with their bills
     * 
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Return list of PaymentTypes
     *
     * @return  [type]           [return description]
     */
    public static function humanList()
    {
        return [
            self::EFECTIVO      => 'Efectivo',
            self::TRANSFERENCIA => 'Transferencia',
            self::CHEQUE        => 'Cheque',
            self::DEBITO        => 'Debito',
            self::CREDITO       => 'Credito',
            self::FLOW          => 'Flow',
        ];
    }
}
