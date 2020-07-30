<?php


namespace tx\service;


use think\App;
use think\Db;
use think\db\Query;
use tx\Service;

class PageService extends Service
{
    /**
     * 查询对象
     * @var Query
     */
    protected $query;

    /**
     * 当前页数
     * @var int
     */
    protected $page;

    /**
     * 总条数
     * @var int
     */
    protected $total;

    /**
     * 每页显示条数
     * @var int
     */
    protected $limit;

    /**
     * WHERE条件
     * @var array
     */
    protected $where = array();

    public function setQuery($dbQuery)
    {
        $this->query = $this->buildQuery($dbQuery);
        return $this;
    }

    public function queryWhere($field, $op = null, $condition = null)
    {
        $this->query->where($field, $op, $condition);
        return $this;
    }

    public function where($param = array() )
    {
        if (!isset($param) )return $this;
        if (!empty($this->where) ){
            array_merge($this->where, $param);
        }else{
            $this->where = $param;
        }
        return $this;
    }

    public function alias($name)
    {
        if (!isset($name) ) return $this;
        $this->query->alias($name);
        return $this;
    }

    public function join($array)
    {
        if (!isset($array) ) return $this;
        $this->query = $this->query->join($array['table'] || '', $array['condition'] || '');
        return $this;
    }

    public function orm($dbQuery = '', $page = 1, $limit = 10)
    {
        if (input('page') ){
            $this->page = input('page');
        }else{
            $this->page = $page;
        }
        empty($this->query) && $this->query = $this->buildQuery($dbQuery);
        $this->limit = $limit;

        $this->total = $this->query->where($this->where)->count();

        $result = $this->query->where($this->where)
            ->limit(( ($this->page - 1) * $this->limit),$this->limit)
            ->select();
        return array('result'=>$result, 'page'=>$this->page, 'limit'=>$this->limit, 'total'=>$this->total);
    }

    public function query($sql, $page, $limit)
    {
        $this->page = $page <= 0 ? 1 : $page;
        $this->limit = $limit;
        $this->total = count(Db::query($sql) );
        $sql = $sql. ' LIMIT '.($this->page * $this->limit).' , '.$this->limit;
        $result = Db::query($sql);
        return array('result'=>$result, 'page'=>$this->page, 'limit'=>$this->limit, 'total'=>$this->total);
    }
}