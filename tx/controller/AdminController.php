<?php
/**
 *  author: 谭潇
 *  create: 2020-07-28 14:08
 *  description:
 */

namespace tx\controller;


use think\App;
use think\Controller;
use tx\service\PageService;

class AdminController extends Controller
{


    /**
     * 快捷分页
     * @param $query mixed 查询构造器
     * @param $type int 0: 默认使用orm进行查询, 1: 使用sql进行查询
     * @param $page int 当前页数
     * @param $limit int 每页显示条数
     * @param $alias string 表别名
     * @param $join array 表连接
     * @param $where array 查询条件
     * @return array
     */
    protected function _page($query, $type, $page, $limit, $alias, $join, $where)
    {
        isset($type) || $type = 0;
        isset($page) || (!empty($this->app->request->get('page') ) ? $this->app->request->get('page') : 1);
        isset($limit) || (!empty($this->app->request->get('limit')) ? $this->app->request->get('limit') : 1 );
        $page = new PageService($this->app);
        return $page->alias($alias)->join($join)->where($where)->orm($query, $page, $limit);
    }

    /**
     * 快捷表单
     */
    protected function _form()
    {

    }

    /**
     * 快捷验证
     */
    protected function _valid()
    {

    }

    /**
     * 快捷更新
     */
    protected function _save()
    {

    }

    /**
     * 快捷删除
     */
    protected function _delete()
    {

    }

    /**
     * 检测表单令牌验证
     */
    protected function _applyFormToken()
    {

    }
}