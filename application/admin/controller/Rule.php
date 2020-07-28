<?php
/**
 *  author: 谭潇
 *  create: 2020-07-27 14:12
 *  description:
 */

namespace app\admin\controller;


use app\common\model\AuthRule;
use think\Controller;
use think\Request;
use tx\ServerResponse;

class Rule extends Controller
{

    public function index()
    {
        $this->assign('title','菜单管理');
        return $this->fetch();
    }

    public function getRuleList(Request $request, ServerResponse $response)
    {
        $count = AuthRule::where('status','<>',9)->count();
        $result = AuthRule::where('status','<>',9)->select();
        $result = arr2table($result->toArray());
        $response->layResponse($result,$count);
    }

}