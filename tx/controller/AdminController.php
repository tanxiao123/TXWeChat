<?php
/**
 *  author: 谭潇
 *  create: 2020-07-28 14:08
 *  description:
 */

namespace tx\controller;


use think\App;
use think\Controller;
use tx\service\FormService;
use tx\service\PageService;

class AdminController extends Controller
{

    /**
     * 是否开启自动化
     * @var boolean
     */
    protected $isAuto = true;

    public function getAuto()
    {
        return $this->isAuto;
    }

    public function __construct(App $app = null)
    {
        bind('tx\controller\AdminController', $this);
        parent::__construct($app);
    }

    /**
     * 数据回调处理机制
     * @param string $name 回调方法名称
     * @param mixed $one 回调引用参数1
     * @param mixed $two 回调引用参数2
     * @return boolean
     */
    public function callback($name, &$one = [], &$two = [])
    {
        if (is_callable($name)) return call_user_func($name, $this, $one, $two);
        foreach ([$name, "_{$this->app->request->action()}{$name}"] as $method) {
            if (method_exists($this, $method)) {
                $this->$method($one, $two);
                return true;
            }else{
                return false;
            }
        }
    }

    public function fetch($template = '', $vars = [], $config = [])
    {
        $response =  parent::fetch($template, $vars, $config);
        return $response;
    }

    public function success($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        parent::success($msg, $url, $data, $wait, $header);
    }

    public function error($msg = '', $url = null, $data = '', $wait = 3, array $header = [])
    {
        parent::error($msg, $url, $data, $wait, $header);
    }

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
     * @param $dbQuery
     * @param string $template
     * @param string $field
     * @param array $where
     * @param array $data
     * @return mixed
     */
    protected function _form($dbQuery, $template = '', $field = '', $where = [], $data = [])
    {
        return FormService::instance()->init($dbQuery,$template,$field,$where,$data);
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