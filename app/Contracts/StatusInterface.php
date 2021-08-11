<?php 

namespace App\Contracts;

interface StatusInterface
{
    /**
     *  We need to make sure that all list are gonna return an array
     *
     *  @return  array
     */
    public static function list() :array;
}