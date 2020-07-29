<?php
/**
 *  author: 谭潇
 *  create: 2020-07-29 10:09
 *  description:
 */

namespace tx\service;


use think\db\Query;
use tx\DataBase;
use tx\Service;

class FormService extends Service
{

    /**
     * @var Query
     */
    protected $query;

    protected $data;

    protected $where;

    protected $field;

    protected $value;

    protected $template;

    /**
     * @param string|$dbQuery
     * @param string $template 模板文件
     * @param string $field 指定数据主键
     * @param array $where 额外更新条件
     * @param array $data 表单扩展数据
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function init($dbQuery, $template = '', $field = '', $where = [], $data = [])
    {
        $this->query = $this->buildQuery($dbQuery);
        list($this->template, $this->where, $this->data) = [$template, $where, $data];
        $this->field = $field ?: ($this->query->getPk() ?: 'id');
        $this->value = input($this->field, $data[$this->field] ?? null);
        // GET请求， 获取数据并显示表单页面
        if ($this->app->request->isGet() ){
            if ($this->value != null){
                $where = [$this->field => $this->value];
                $data = (array) $this->query->where($where)->find();
            }
            $data = array_merge($data, $this->data);
            if (false !== $this->controller->callback('_form_filter', $data) ){
                return $this->controller->fetch($this->template, ['vo'=>$data]);
            }
            return $data;
        }
        // POST请求, 数据自动存库处理
        if ($this->app->request->isPost() ){
            $data = array_merge($this->app->request->post(), $this->data);
            if (false !== $this->controller->callback('_form_result', $result, $data) ){
                if ($result !== false){
                    $this->controller->success('成功');
                }else{
                    $this->controller->error('失败');
                }
            }else{
                if ($this->controller->getAuto() ){
                    $action = strtolower($this->app->request->action() );
                    if (in_array($action, ['add','update','delete']) ){
                        $func = '_auto_'.$action;
                        DataBase::instance()->setTable(config('database.prefix').$dbQuery)->$func();
                        $this->controller->success('成功');
                    }
                }
                $this->controller->error('失败');
            }
        }
    }
}