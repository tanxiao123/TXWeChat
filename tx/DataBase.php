<?php
/**
 *  author: 谭潇
 *  create: 2020-07-29 16:48
 *  description:
 */

namespace tx;


use think\App;
use think\Container;
use think\Model;

class DataBase extends Model
{
    protected $table;

    protected $pk;

    protected $app;

    protected $request;

    public function __construct(App $app, $data = [])
    {
        $this->app = $app;
        $this->request = $app->request;
        parent::__construct($data);
    }

    public static function instance(...$args)
    {
        return Container::getInstance()->invokeClass(static::class, $args);
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function setPk($pk)
    {
        $this->pk = $pk;
        return $this;
    }

    /**
     * 自动化新增
     * @return int|string
     */
    public function _auto_add()
    {
        $form_data = $this->request->post('form_data',[]);
        return $this->insertGetId($form_data);
    }

    /**
     * 自动化删除方法
     * @param array $where
     * @param int $isDelete
     * @param array $default
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function _auto_delete($where = array(), $isDelete = 0, $default = array())
    {
        if (empty($where)) $where = $this->request->post('where') ?? [];
        if ($isDelete == 0){
            $this->where($where)->delete();
        }else{
            $this->where($where)->update($default);
        }
    }

    /**
     * 自动化更新
     * @param array $where
     * @param array $value
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function _auto_update($where = array(), $value = array())
    {
        if (empty($where)) $where = $this->request->post('where') ?? [];
        if (empty($value)) $value = $this->request->post('form_data') ?? [];
        $this->where($where)->update($value);
    }
}