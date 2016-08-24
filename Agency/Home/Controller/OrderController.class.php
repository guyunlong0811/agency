<?php
namespace Home\Controller;

use Think\Controller;

class OrderController extends BInitController
{

	static private $status = array(
		1 => 'order_completed',
		0 => 'order_processing',
		-1 => 'order_cancelled',
	);

	public function _initialize()
	{
		parent::_initialize();
		$this->v['icon'] = 'group';
		$this->vTitle = 'order_manage';
		$this->vStatus = self::$status;
		$this->selectDateStartEnd();
	}

	public function index()
	{
		$this->vList = array();
		if (!empty($_GET)) {
			$this->server_id = I('get.phone');
			$where = array();
			$list = array();
			if (!I('get.type')) {
				$where['o.type'] = array('LIKE', '%' . I('get.type') . '%');
			}

			if (!I('get.brand')) {
				$where['o.brand'] = array('LIKE', '%' . I('get.brand') . '%');
			}

			if (!I('get.product')) {
				$where['o.product'] = array('LIKE', '%' . I('get.product') . '%');
			}

			if (empty($where) && !I('get.uid')) {
				$where['o.uid'] = I('get.uid');
			}

			if (!I('get.status') && I('get.status') != '') {
				$where['o.status'] = I('get.status');
			}

			if (!I('get.starttime') && !I('get.endtime')) {
				$where['o.ctime'] = array('between', array(strtotime(I('get.starttime') . ' 00:00:00'), strtotime(I('get.endtime') . ' 23:59:59')));
			}


			dump(111);
			dump($where);
			if (!empty($where)) {
				$order['o.ctime'] = 'desc';
				$list = M()->table('orders o')->field("`u`.*,`o`.*,`e`.`vendor`,`e`.`track_id`")->join("`users` `u` ON `u`.`uid`=`o`.`uid`")->join("LEFT JOIN `expresses` `e` ON `e`.`eid`=`o`.`eid`")->where($where)->order($order)->select();
				if (!empty($list)) {
					foreach ($list as $key => $value) {
						$profit = $value['price'] - $value['cost'];
						$list[$key]['user'] = $this->getUserDisplayName($value);
						$list[$key]['status'] = L(self::$status[$value['status']]);
						$list[$key]['ctime'] = time2format($value['ctime']);
						$list[$key]['endtime'] = $value['endtime'] ? time2format($value['endtime']) : '-';
						$list[$key]['price'] = my_money_format($value['price']);
						$list[$key]['cost'] = my_money_format($value['cost']);
						$list[$key]['profit'] = my_money_format($profit);
						$list[$key]['eid'] = $value['eid'] ? $value['eid'] : '-';
						$list[$key]['comment'] = $value['comment'] ? $value['comment'] : '-';
					}
				}
			}
			$this->vList = $list;
		}
		//显示
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}
	
	//修改
	public function edit()
	{
		$oid = I('get.oid');
		if (!empty($_POST)) {
			$where['oid'] = $oid = I('post.oid');
			$save['type'] = I('post.type');
			$save['brand'] = I('post.brand');
			$save['product'] = I('post.product');
			$save['price'] = I('post.price') * 100;
			$save['cost'] = I('post.cost') * 100;
			$save['count'] = I('post.count');
			$save['eid'] = I('post.eid');
			$save['status'] = I('post.status');
			switch(I('post.status')){
				case '0':
					$save['endtime'] = null;
					break;
				default:
					$save['endtime'] = time();
			}
			if (false === D('Orders')->UpdateData($save, $where)){
				C('G_ERROR', 'fail');
			}
			//重新计算用户统计信息
			D('Users')->statistic(I('post.uid'));
			C('G_ERROR', 'success');
		}

		//显示
		end:
		$row1 = D('Orders')->getRow($oid);
		$row2 = D('Users')->getRow($row1['uid']);
		$row = array_merge($row1, $row2);
		$row['user'] = $this->getUserDisplayName($row);
		$row['price'] = sprintf("%01.2f", $row['price'] / 100);
		$row['cost'] = sprintf("%01.2f", $row['cost'] / 100);
		$this->vRow = $row;
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

	//获取客户显示信息
	private function getUserDisplayName($info){
		$user = $info['uid'];
		if(!empty($info['nickname'])){
			$user = $info['nickname'];
		}else if(!empty($info['firstname']) && !empty($info['lastname'])){
			$user = $info['lastname'] . $info['firstname'];
		}else if(!empty($info['wechat'])){
			$user = $info['wechat'];
		}else if(!empty($info['email'])){
			$user = $info['email'];
		}else if(!empty($info['phone'])){
			$user = $info['phone'];
		}
		return $user;
	}

	//新增快递信息
	public function express()
	{
		if (!empty($_POST)) {
			$oid = I('post.oid');
			$uid = D('Orders')->where(array('oid' => $oid))->getField('uid');
			$add['vendor'] = I('post.vendor');
			$add['track_id'] = I('post.track_id');
			$add['price'] = I('post.price');
			$add['shipped'] = I('post.shipped') ? I('post.shipped') : null;
			if (false === $inserId = D('Expresses')->CreateData($add)){
				C('G_ERROR', 'fail');
			}
			D('Orders')->express($oid, $inserId);
			//重新计算用户统计信息
			D('Users')->statistic($uid);
			C('G_ERROR', 'success');
		}
		//显示
		end:
		$this->vShipped = time2format(null, 2);
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

}