<?php
namespace Home\Model;

use Think\Model;

class UserModel extends BaseModel
{

	protected $_validate = array(
		array('phone', 'require', 'nickname_require', 0, 'regex', 3),
		array('wechat', 'require', 'nickname_require', 0, 'regex', 3),
		array('nickname', 'require', 'nickname_require', 0, 'regex', 3),
	);

	protected $_auto = array(
		array('ctime', 'time', 1, 'function'),
	);

	public function getRow($uid){
		$where['uid'] = $uid;
		return $this->getRowCondition($where);
	}

	//用户统计
	public function statistic($uid)
	{
		$where['uid'] = $uid;
		$save['last_purchase_time'] = time();
		$save['total_order_count'] = D('Order')->getTotalCount($uid);;
		$save['total_purchase'] = $totalPurchase = D('Order')->getTotalPurchase($uid);
		$totalCost = D('Order')->getTotalCost($uid);
		$totalExpress = D('Express')->getTotalPrice($uid);
		$save['total_profit'] = $totalPurchase - $totalCost - $totalExpress;
		return $this->UpdateData($save, $where);
	}

}