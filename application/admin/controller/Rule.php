<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 14:12
 *  description:
 */

namespace app\admin\controller;


use think\Controller;

class Rule extends Controller
{
    public function index()
    {
        $this->assign('title','菜单管理');
        return $this->fetch();
    }
}