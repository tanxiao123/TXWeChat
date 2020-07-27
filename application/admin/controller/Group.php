<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 13:26
 *  description:
 */

namespace app\admin\controller;


use think\Controller;

class Group extends Controller
{
    public function index()
    {
        $this->fetch();
    }
}