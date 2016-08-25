<?php
namespace Home\Model;

use Think\Model;

class OrderModel extends BaseModel
{

	protected $_auto = array(
		array('eid', 0),
		array('status', 0),
		array('comment', null),
		array('ctime', 'time', 1, 'function'),
		array('endtime', null),
	);

	public function getRow($oid)
	{
		$where['oid'] = $oid;
		return $this->getRowCondition($where);
	}

	public function getTotalCount($uid)
	{
		$where['uid'] = $uid;
		return $this->where($where)->count();
	}

	public function getTotalPurchase($uid)
	{
		$where['uid'] = $uid;
		$sum = $this->where($where)->sum('price');
		$sum = $sum ? $sum : 0;
		return $sum;
	}

	public function getTotalCost($uid)
	{
		$where['uid'] = $uid;
		$sum = $this->where($where)->sum('cost');
		$sum = $sum ? $sum : 0;
		return $sum;
	}

	public function express($oid, $eid)
	{
		$where['oid'] = $oid;
		$save['eid'] = $eid;
		return $this->UpdateData($save, $where);
	}
}