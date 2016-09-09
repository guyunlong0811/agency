<?php
namespace Home\Controller;

use Think\Controller;

class UserController extends BaseController
{

	public function index()
	{
		$list = array();
		if (!empty($_GET)) {
			$where = array();
			if (!I('get.phone')) {
				$where['phone'] = array('LIKE', '%' . I('get.phone') . '%');
			} else if (!I('get.real_name')) {
				$where['real_name'] = array('LIKE', '%' . I('get.real_name') . '%');
			} else if (!I('get.nickname')) {
				$where['nickname'] = array('LIKE', '%' . I('get.nickname') . '%');
			} else if (!I('get.wechat')) {
				$where['wechat'] = array('LIKE', '%' . I('get.wechat') . '%');
			} else if (!I('get.email')) {
				$where['email'] = array('LIKE', '%' . I('get.email') . '%');
			}

			if (!empty($where)) {
				$model = D('User');
				$order = array('uid' => 'asc',);
				$page = $this->page($model);
				$list = $model->page($this->pg . ',' . $page->listRows)->where($where)->order($order)->select();
				if (!empty($list)) {
					foreach ($list as $key => $value) {
						switch ($value['gender']) {
							case '1':
								$list[$key]['nickname'] .= '(♂)';
								break;
							case '0':
								$list[$key]['nickname'] .= '(♀)';
								break;
							case '2':
								$list[$key]['nickname'] .= '(-)';
								break;
						}
						$list[$key]['ctime'] = time2format($value['ctime']);
						$list[$key]['last_purchase_time'] = empty($value['last_purchase_time']) ? '-' : time2format($value['last_purchase_time']);
						$list[$key]['total_purchase'] = my_money_format($value['total_purchase']);
					}
				}
			}
			$this->v['list'] = $list;
		}

		//显示

		//标题
		$this->v['title'] = 'user_manage';

		//搜索框
		$this->v['form'] = 'search';
		$this->v['method'] = 'get';
		$this->v['form_list'] = array(
			'real_name' => array(
				'type' => 'text',
				'placeholder' => 'user_name',
				'w' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'link' => ' AND ',
				'value' => '',
			),
			'nickname' => array(
				'type' => 'text',
				'placeholder' => 'user_nickname',
				'w' => 'medium',
				'link' => ' AND ',
				'value' => '',
			),
			'phone' => array(
				'type' => 'text',
				'placeholder' => 'user_phone',
				'w' => 'medium',
				'link' => ' AND ',
				'value' => '',
			),
			'wechat' => array(
				'type' => 'text',
				'placeholder' => 'user_wechat',
				'w' => 'medium',
				'link' => ' AND ',
				'value' => '',
			),
			'email' => array(
				'type' => 'text',
				'placeholder' => 'user_email',
				'w' => 'medium',
				'link' => '',
				'value' => '',
			),
		);

		//主ID名
		$this->v['idKey'] = 'uid';

		//是否分页
		$this->v['isPage'] = '1';

		//数据
		$this->v['list'] = $list;

		//表格右上角功能
		$this->v['tools'] = array(
			'title' => L('user_add'),
			'action' => 'add',
		);

		//表格数据
		$this->v['table'] = array(
			'uid' => array('field' => 'uid',),
			'user_nickname' => array('field' => 'nickname',),
			'user_phone' => array('field' => 'phone',),
			'user_gender' => array('field' => 'gender',),
			'user_last_purchase_time' => array('field' => 'last_purchase_time',),
		);

		//表格操作
		$this->v['operation'] = array(
			'detail' => array('type' => 'detail', 'link' => ' | '),
			'edit' => array('type' => 'a_c', 'fa' => 'edit', 'color' => 'yellow', 'link' => ' | '),
			'order' => array('type' => 'a_c', 'name' => 'user_order', 'fa' => 'cny', 'color' => 'green', 'link' => ' | '),
			'order_module' => array('type' => 'a_m', 'module_name' => 'Order', 'link' => ''),
		);

		//详细页配置
		$this->v['detail'] = array(
			'user_name' => array('field' => 'real_name',),
			'user_ctime' => array('field' => 'ctime',),
			'user_email' => array('field' => 'email',),
			'user_total_purchase' => array('field' => 'total_purchase',),
			'user_total_order_count' => array('field' => 'total_order_count',),
			'user_profile' => array('field' => 'profile',),
		);

		//显示页面
		$this->adminDisplay('Public/list');
	}

	//新增客户
	public function add()
	{
		if (!empty($_POST)) {
			$add['real_name'] = I('post.real_name') ? I('post.real_name') : null;
			$add['nickname'] = I('post.nickname') ? I('post.nickname') : null;
			$add['phone'] = I('post.phone') ? I('post.phone') : null;
			$add['wechat'] = I('post.wechat') ? I('post.wechat') : null;
			$add['email'] = I('post.email') ? I('post.email') : null;
			$add['gender'] = I('post.gender') >= 0 ? I('post.gender') : 2;
			$add['age'] = I('post.age') >= 0 ? I('post.age') : 0;
			$add['last_purchase_time'] = null;
			$add['total_purchase'] = 0;
			$add['total_order_count'] = 0;
			$add['profile'] = I('post.profile') ? I('post.profile') : null;
			$add['invite_uid'] = I('post.invite_uid') ? I('post.invite_uid') : 0;
			if (false === D('User')->CreateData($add)) {
				C('G_ERROR', 'fail');
			}
			C('G_ERROR', 'success');
		}

		//显示
		end:
		$this->v['form'] = 'main';
		$this->v['title'] = 'user_add';
		$this->v['method'] = 'post';
		$this->v['action'] = 'add';
		$this->v['fa'] = 'plus';
		$this->v['form_list'] = array(
			'nickname' => array(
				'type' => 'text',
				'left' => 'user_nickname',
				'tip' => 'user_nickname_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'user_phone_input',
					),
				),
			),
			'phone' => array(
				'type' => 'text',
				'left' => 'user_phone',
				'tip' => 'user_phone_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'user_phone_input',
					),
				),
			),
			'real_name' => array(
				'type' => 'text',
				'left' => 'user_name',
				'tip' => 'user_name_input',
				'w' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'value' => '',
			),
			'wechat' => array(
				'type' => 'text',
				'left' => 'user_wechat',
				'tip' => 'user_wechat_input',
				'w' => 'medium',
				'value' => '',
			),
			'email' => array(
				'type' => 'text',
				'left' => 'user_email',
				'tip' => 'user_email_input',
				'w' => 'medium',
				'value' => '',
			),
			'gender' => array(
				'type' => 'radio',
				'left' => 'user_gender',
				'tip' => 'user_gender_select',
				'size' => '',
				'value' => '2',
				'list' => array(
					'1' => array('name' => 'male', 'disabled' => ''),
					'0' => array('name' => 'female', 'disabled' => ''),
					'2' => array('name' => 'unknown', 'disabled' => ''),
				),
			),
			'age' => array(
				'type' => 'text',
				'left' => 'user_age',
				'tip' => 'user_age_input',
				'w' => 'small',
				'value' => '0',
				'check' => array(
					'regular' => array(
						'compare' => get_js_regular('age'),
						'alert' => 'user_age_input',
					),
				),
			),
			'invite_uid' => array(
				'type' => 'text',
				'left' => 'user_invite_uid',
				'tip' => 'user_invite_uid_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'regular' => array(
						'compare' => get_js_regular('num'),
						'alert' => 'user_invite_uid_input',
					),
				),
			),
			'profile' => array(
				'type' => 'textarea',
				'left' => 'user_profile',
				'tip' => 'user_profile_input',
				'cols' => '',
				'rows' => '2',
				'w' => '',
				'value' => '',
			),
		);
		$this->adminDisplay('Public/add_edit');
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
			$save['age'] = I('post.age');
			$save['invite_uid'] = I('post.invite_uid') ? I('post.invite_uid') : 0;
			$save['profile'] = I('post.profile') ? I('post.profile') : null;
			if (false === D('User')->UpdateData($save, $where)) {
				C('G_ERROR', 'fail');
			}
			C('G_ERROR', 'success');
		}

		//显示
		end:
		$this->v['row'] = D('User')->getRow($uid);
		$this->v['form'] = 'main';
		$this->v['title'] = 'user_edit';
		$this->v['method'] = 'post';
		$this->v['action'] = 'save';
		$this->v['fa'] = 'edit';
		$this->v['hKey'] = 'uid';
		$this->v['hVal'] = $uid;
		$this->v['form_list'] = array(
			'uid_view' => array(
				'type' => 'text',
				'left' => 'uid',
				'tip' => 'uid',
				'w' => 'medium',
				'value' => $uid,
				'disabled' => '1',
			),
			'nickname' => array(
				'type' => 'text',
				'left' => 'user_nickname',
				'tip' => 'user_nickname_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'user_phone_input',
					),
				),
			),
			'phone' => array(
				'type' => 'text',
				'left' => 'user_phone',
				'tip' => 'user_phone_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'user_phone_input',
					),
				),
			),
			'real_name' => array(
				'type' => 'text',
				'left' => 'user_name',
				'tip' => 'user_name_input',
				'w' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'value' => '',
			),
			'wechat' => array(
				'type' => 'text',
				'left' => 'user_wechat',
				'tip' => 'user_wechat_input',
				'w' => 'medium',
				'value' => '',
			),
			'email' => array(
				'type' => 'text',
				'left' => 'user_email',
				'tip' => 'user_email_input',
				'w' => 'medium',
				'value' => '',
			),
			'gender' => array(
				'type' => 'radio',
				'left' => 'user_gender',
				'tip' => 'user_gender_select',
				'size' => '',
				'value' => '',
				'list' => array(
					'1' => array('name' => 'male', 'disabled' => ''),
					'0' => array('name' => 'female', 'disabled' => ''),
					'2' => array('name' => 'unknown', 'disabled' => ''),
				),
			),
			'age' => array(
				'type' => 'text',
				'left' => 'user_age',
				'tip' => 'user_age_input',
				'w' => 'small',
				'value' => '',
				'check' => array(
					'regular' => array(
						'compare' => get_js_regular('age'),
						'alert' => 'user_age_input',
					),
				),
			),
			'invite_uid' => array(
				'type' => 'text',
				'left' => 'user_invite_uid',
				'tip' => 'user_invite_uid_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'regular' => array(
						'compare' => get_js_regular('num'),
						'alert' => 'user_invite_uid_input',
					),
				),
			),
			'profile' => array(
				'type' => 'textarea',
				'left' => 'user_profile',
				'tip' => 'user_profile_input',
				'cols' => '',
				'rows' => '2',
				'w' => '',
				'value' => '',
			),
		);
		$this->adminDisplay('Public/add_edit');
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
			if (false === D('Order')->CreateData($add)) {
				C('G_ERROR', 'fail');
			}
			//重新计算用户统计信息
			D('User')->statistic(I('post.uid'));
			C('G_ERROR', 'success');
		}
		//显示
		end:
		$this->display();//显示页面
	}

}