<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends BaseController
{

	public function _initialize()
	{
		parent::_initialize();
		$this->v['icon'] = 'group';
		$this->vTitle = 'user_manage';
	}

	public function index()
	{
		$this->vList = array();
		if (!empty($_GET)) {
			$this->server_id = I('get.phone');
			$where = array();
			$list = array();
			if (!I('get.phone')) {
				$where['phone'] = array('LIKE', '%' . I('get.phone') . '%');
			} else if (!I('get.wechat')) {
				$where['wechat'] = array('LIKE', '%' . I('get.wechat') . '%');
			} else if (!I('get.email')) {
				$where['email'] = array('LIKE', '%' . I('get.email') . '%');
			} else if (!I('get.nickname')) {
				$where['nickname'] = array('LIKE', '%' . I('get.nickname') . '%');
			}

			if (!empty($where)) {
				$model = D('User');
				$order = array('uid' => 'asc',);
				$page = $this->page($model);
				$list = $model->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
				if (!empty($list)) {
					foreach ($list as $key => $value) {
						switch($value['gender']){
							case '1':
								$list[$key]['gender'] = L('male');
								break;
							case '0':
								$list[$key]['gender'] = L('female');
								break;
							case '-1':
								$list[$key]['gender'] = L('unknown');
								break;
						}
						$list[$key]['ctime'] = time2format($value['ctime']);
						$list[$key]['last_purchase_time'] = empty($value['last_purchase_time']) ? '-' : time2format($value['last_purchase_time']);
						$list[$key]['total_purchase'] = my_money_format($value['total_purchase']);
						$list[$key]['total_profit'] = my_money_format($value['total_profit']);
					}
				}
			}
			$this->vList = $list;
		}
		//显示
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

	//新增客户
	public function add()
	{
		if (!empty($_POST)) {
			$add['phone'] = I('post.phone') ? I('post.phone') : null;
			$add['wechat'] = I('post.wechat') ? I('post.wechat') : null;
			$add['nickname'] = I('post.nickname') ? I('post.nickname') : null;
			$add['firstname'] = I('post.firstname') ? I('post.firstname') : null;
			$add['lastname'] = I('post.lastname') ? I('post.lastname') : null;
			$add['email'] = I('post.email') ? I('post.email') : null;
			$add['gender'] = I('post.gender') ? I('post.gender') : -1;
			$add['last_purchase_time'] = null;
			$add['total_purchase'] = 0;
			$add['total_profit'] = 0;
			$add['total_order_count'] = 0;
			$add['profile'] = I('post.profile') ? I('post.profile') : null;
			if (false === D('User')->CreateData($add)){
				C('G_ERROR', 'fail');
			}
			C('G_ERROR', 'success');
		}
		//显示
		end:
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}
	
	//修改客户
	public function edit()
	{
		$uid = I('get.uid');
		if (!empty($_POST)) {
			$where['uid'] = $uid = I('post.uid');
			$save['phone'] = I('post.phone') ? I('post.phone') : null;
			$save['wechat'] = I('post.wechat') ? I('post.wechat') : null;
			$save['nickname'] = I('post.nickname') ? I('post.nickname') : null;
			$save['firstname'] = I('post.firstname') ? I('post.firstname') : null;
			$save['lastname'] = I('post.lastname') ? I('post.lastname') : null;
			$save['email'] = I('post.email') ? I('post.email') : null;
			$save['gender'] = I('post.gender');
			$save['profile'] = I('post.profile') ? I('post.profile') : null;
			if (false === D('User')->UpdateData($save, $where)){
				C('G_ERROR', 'fail');
			}
			C('G_ERROR', 'success');
		}
		//显示
		end:
		$this->vRow = D('User')->getRow($uid);
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

	//新增客户订单
	public function order()
	{
		if (!empty($_POST)) {
			$add['uid'] = I('post.uid');
			$add['type'] = I('post.type') ? I('post.type') : null;
			$add['brand'] = I('post.brand') ? I('post.brand') : null;
			$add['product'] = I('post.product');
			$add['price'] = round(I('post.price'), 2) * 100;
			$add['cost'] = round(I('post.cost'), 2) * 100;
			$add['count'] = I('post.count');
			if (false === D('Order')->CreateData($add)){
				C('G_ERROR', 'fail');
			}
			//重新计算用户统计信息
			D('User')->statistic(I('post.uid'));
			C('G_ERROR', 'success');
		}
		//显示
		end:
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

}