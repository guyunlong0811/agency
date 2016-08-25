<?php
namespace Home\Model;

use Think\Model;

class ExpressModel extends BaseModel
{

	protected $_auto = array(
		array('delivered', null),
		array('ctime', 'time', 1, 'function'),
	);

	public function getRow($eid){
		$where['eid'] = $eid;
		return $this->getRowCondition($where);
	}

	public function getTotalPrice($uid){
		$where['uid'] = $uid;
		$sum = $this->where($where)->sum('price');
		$sum = $sum ? $sum : 0;
		return $sum;
	}

}