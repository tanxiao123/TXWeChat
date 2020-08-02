<?php


namespace tx\middleware;


use EasyWeChat\Factory;

class WxAppAuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        // 校验用户是否已注册
        $config = config('mini_program.default');
        $app = Factory::miniProgram($config);
        $app->auth->session();
    }
}