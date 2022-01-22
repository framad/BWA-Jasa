<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            //jika gagal login jadi langsung ke halaman index (kaerna kan pake modal)
            return route('index');
        }

        //jika berhasil login redirect ke halaman index
        return route('index');
    }
}
