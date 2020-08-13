<?php


namespace tx\middleware;


use think\Db;

class WebOauthMiddleware
{
    public function handle($request, \Closure $next)
    {
        $users = session('wechat_oauth_user_default');
        $fans = Db::name('fans')->where('openid', $users->id)->find();
        if (empty($fans)){
            Db::name('fans')->insert(array(
                'openid' => $users->id,
                'nickname' => $users->nickname,
                'avatar' => $users->avatar,
                'create_time' => date('Y-m-d H:i:s')
            ));
        }
        return $next($request);
    }
}