<?php

Route::group('admin', function (){
    // 跳转登录视图
    Route::get('login', 'admin/Index/login');
    // 登录事件
    Route::post('doLogin', 'admin/Index/doLogin');
    // 退出登录操作
    Route::rule('loginOut','admin/Index/loginOut');
    // 获取验证码
    Route::rule('verify', 'admin/Index/verify');
    // 基础资料
    Route::rule('basics', 'admin/Index/basics');
});