<?php

Route::group('admin', function (){
    // 跳转登录视图
    Route::get('login', 'admin/Index/login');
    // 登录事件
    Route::post('doLogin', 'admin/Index/doLogin');
    // 退出登录操作
    Route::rule('loginOut','admin/Index/loginOut');

    Route::group('home', function (){
        // 加载控制台
        Route::get('index', 'admin/Index/index');
    })->middleware('AdminAuthMiddleware');

});