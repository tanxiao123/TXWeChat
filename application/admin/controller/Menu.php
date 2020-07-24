<?php
/**
 *  author: 谭潇
 *  create: 2020-07-24 16:12
 *  description:
 */

namespace app\admin\controller;


use app\common\model\AuthRule;
use think\Controller;
use think\Request;

class Menu extends Controller
{
    /**
     * 获取父级下面的菜单列表
     * @param Request $request
     * @param AuthRule $authRule
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getChildrenMenus(Request $request, AuthRule $authRule)
    {
        $mid = $request->get('mid');
        if (empty($mid) ){
            $this->error('菜单ID不可为空');
        }
        $result = $authRule->where('pid', $mid)->where(['type' => 1, 'status' => 1])->field(['id, title, rule'])->select();
        $this->success('SUCCES','',$result);
    }
}