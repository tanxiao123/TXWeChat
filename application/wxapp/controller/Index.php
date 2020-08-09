<?php


namespace app\wxapp\controller;


use tx\controller\WxAppController;

class Index extends WxAppController
{
    public function index()
    {
        echo "<h1>Hello, WxApp{$this->app_name}</h1>";
    }
}