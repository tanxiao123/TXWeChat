<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 13:26
 *  description:
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;

class Group extends Controller
{

    protected $middleware = ['AdminAuthMiddleware'];

    public function index(Request $request)
    {
        $this->fetch();
    }
    
}