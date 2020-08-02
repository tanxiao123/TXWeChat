<?php

namespace tx\middleware;
/**
 * 此中间件用于校验用户是否登录 获取用户信息、校验角色权限、获取菜单等相关信息
 * Class AdminAuthMiddleware
 * @package app\http\middleware
 */
class AdminAuthMiddleware
{

    protected $whiteList = [
        'admin/login'
    ];

    public function handle($request, \Closure $next)
    {
        if (!session('admin') ){
            if (!in_array($request->path(), $this->whiteList)){
                return redirect('/admin/login');
            }
        }

        // 启动授权服务验证
        app('adminAuth')->start();

        return $next($request);
    }
}
