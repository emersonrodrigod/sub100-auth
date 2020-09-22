<?php


namespace sub100\Auth\Facades;


use Illuminate\Support\Facades\Facade;

class Authentication extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'Sub100Auth';
    }

}
