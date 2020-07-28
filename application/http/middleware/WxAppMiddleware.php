<?php

namespace app\http\middleware;

class WxAppMiddleware
{
    public function handle($request, \Closure $next)
    {
        // 启用账户授权
        app('wxAppAuth')->start();
        return $next($request);
    }
}
