<?php 
namespace Leader\Model;
use Think\Model;
class BaseModel extends Model{
  
  //根据系别限制查询分页数据
	/**
	* $p 当前页码
	* $pagesize 每页显示的记录条数
	* $dept 所属系别
	*/
	public function selectByDept($p,$pagesize,$dept){

		$data['sid'] = $dept;
        $result = $this->order('id asc')->
                  where($data)->
                  page($p,$pagesize)->
                  select();

        return $result;
	}

}


?>