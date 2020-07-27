<?php
// 应用容器绑定定义
return [
    'adminAuth' => \app\lib\AdminAuth::class,
    'apiAuth' => \app\lib\WxAppAuth::class
];
