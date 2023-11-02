<?php

namespace StarInsure\Crm\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * An instance of the CRM API client
 *
 * @method @static call(string $method, string $uri, array $data = [])
 * @method @static get(string $endpoint, array $data = [])
 * @method @static post(string $endpoint, array $data = [])
 * @method @static put(string $endpoint, array $data = [])
 * @method @static del(string $endpoint, array $data = [])
 */
class StarCrm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'starcrm';
    }
}
