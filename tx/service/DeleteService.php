<?php
/**
 *  author: 谭潇
 *  create: 2020-07-30 9:59
 *  description:
 */

namespace tx\service;


use think\db\Query;
use tx\Service;

class DeleteService extends Service
{
    protected $where;

    protected $field;

    protected $value;

    /**
     * @var Query
     */
    private $query;

    /**
     * 初始化操作
     * @param $dbQuery
     * @param string $field 操作数据主键
     * @param array $where 额外更新条件
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function init($dbQuery, $field = '', $where = [])
    {
        $this->where = $where;
        $this->query = $this->buildQuery($dbQuery);
        $this->field = $field ?: $this->query->getPk();
        $this->value = $this->app->request->post($this->field, null);

        // 主键限制处理
        if (!isset($this->where[$this->field]) && is_string($this->value) ){
            $this->query->whereIn($this->field, explode(',', $this->value) );
        }

        // 前置回调处理
//        if (false === $this->controller->callback('_delete_filter', $this->query, $where)) {
//            return null;
//        }

        if (method_exists($this->query,'getTableFields') && in_array('status', $this->query->getTableFields() )){
            $result = $this->query->where($this->where)->update(['status'=>9]);
        }else{
            $result = $this->query->where($this->where)->delete();
        }

        // 结果回调处理
//        if (false === $this->controller->callback('_delete_result', $result)) {
//            return $result;
//        }

        // 回复前端结果
        if ($result !== false) {
            $this->controller->success('成功', '');
        } else {
            $this->controller->error('成功','');
        }
    }
}