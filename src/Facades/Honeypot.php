<?php

namespace Larapress\Honeypot\Facades;

use Illuminate\Support\Facades\Facade;
use Larapress\Honeypot\Honeypot as HoneypotCore;

/**
 * @method static mixed fields()
 * @method static bool isBot()
 * @method static array payload()
 * @method static mixed setRedirectTo(string|null $to)
 * @method static mixed redirectTo()
 * @method static \Larapress\Honeypot\Honeypot updateFields(string|array|null $fields = null)
 * @method static mixed request()
 * @method static void setRequest(\Illuminate\Http\Request $request)
 *
 * @see \Larapress\Honeypot\Honeypot
 */

class Honeypot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HoneypotCore::class;
    }
}
