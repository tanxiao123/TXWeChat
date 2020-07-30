<?php
/**
 *  author: 谭潇
 *  create: 2020-07-30 8:27
 *  description:
 */

namespace tx\service;


use think\db\Query;
use tx\Service;

class SaveService extends Service
{
    /**
     * 表单扩展数据
     * @var array
     */
    protected $data;

    /**
     * 表单额外更新条件
     * @var array
     */
    protected $where;

    /**
     * 数据对象主键名称
     * @var array|string
     */
    protected $field;

    /**
     * 数据对象主键值
     * @var string
     */
    protected $value;

    /**
     * @var Query
     */
    private $query;

    public function init($dbQuery, $data = [], $field = '', $where = [])
    {
        $this->where = $where;
        $this->query = $this->buildQuery($dbQuery);
        $this->field = $field ?: $this->query->getPk();
        $this->data = $data ?: $this->app->request->post();
        $this->value = $this->app->request->post($this->field, null);
        // 主键限制处理
        if (!isset($this->where[$this->field]) && is_string($this->value) ){
            $this->query->whereIn($this->field, explode(',', $this->value) );
            if (isset($this->data) ) unset($this->data[$this->field]);
        }
        // 前置回调处理
//        if (false === $this->controller->callback('_save_filter', $this->query, $this->data) ){
//            return false;
//        }
        // 执行更新操作
        $result = $this->query->where($this->where)->update($this->data) !== false;
        // 结果回调处理
//        if (false === $this->controller->callback('_save_result', $result)) {
//            return $result;
//        }
        // 回复前端结果
        if ($result !== false) {
            $this->controller->success('成功', '');
        } else {
            $this->controller->error('失败','');
        }
    }
}