<?php
namespace Home\Controller;

use Think\Controller;

class AdminController extends BaseController
{
	static public $status = array(
		'1' => 'status_active',
		'0' => 'status_banned',
	);

	public function _initialize()
	{
		parent::_initialize();
		$this->v['icon'] = 'user';
	}

	//显示列表
	public function index()
	{
		//数据
		$model = M('Admin');
		$order = array('id' => 'asc',);
		$page = $this->page($model);
		$list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();

		//显示
		$this->v['title'] = 'admin_manager';
		$this->v['idKey'] = 'id';
		$this->v['isPage'] = '1';
		$this->v['list'] = $list;
		$this->v['tools'] = array(
			'title' => L('admin_add'),
			'action' => 'add',
		);
		$this->v['table'] = array(
			'admin_id' => array('field' => 'id',),
			'admin_account' => array('field' => 'username',),
			'admin_nickname' => array('field' => 'nickname',),
			'admin_status' => array('field' => 'status', 'type' => 'select', 'list' => self::$status, 'w' => 'small', 'h' => 'sm', 'action' => 'status'),
		);
		$this->v['operation'] = array(
			'edit' => array('type' => 'a_c', 'link' => '|'),
			'permission' => array('type' => 'a_c', 'link' => ''),
		);
		$this->adminDisplay('Public/list');//显示页面
	}

	//修改状态
	public function status()
	{
		$result['status'] = 0;
		$result['msg'] = 'illegal_ajax';
		if(IS_AJAX){
			//超级管理员不更改
			if (in_array(I('get.id'), get_config('super_admin'))) {
				$result['msg'] = 'not_update_super';
			} else {
				if (false === D('Admin')->changeStatus(I('get.id'), I('get.value'))) {
					$result['msg'] = 'fail';
				} else {
					$result['status'] = 1;
					$result['msg'] = 'success';
				}
			}
		}
		$result['msg'] = L($result['msg']);
		$this->ajaxReturn($result);
	}

	//新增管理员
	public function add()
	{

		if (!empty($_POST)) {

			$data['username'] = I('post.username');
			$data['nickname'] = I('post.nickname');
			$data['password'] = I('post.password');
			$data['ip_limit'] = I('post.ip_limit');
			//修改密码
			if (I('post.password') != '') {
				if (I('post.password') != I('post.repassword')) {
					C('G_ERROR', 'repassword_error');
					goto end;
				}
				$data['password'] = I('post.password');
			}

			$id = D('Admin')->CreateData($data);
			if ($id > 0){
				C('G_ERROR', 'success');
			}
		}

		//显示
		end:
		$this->v['form'] = 'main';
		$this->v['title'] = 'admin_add';
		$this->v['method'] = 'post';
		$this->v['action'] = 'add';
		$this->v['fa'] = 'plus';
		$this->v['form_list'] = array(
			'username' => array(
				'type' => 'text',
				'left' => 'admin_account',
				'button' => 'username_require',
				'w' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'username_require',
					),
					'length' => array(
						'compare' => "< 4",
						'alert' => 'username_length',
					),
				),
			),
			'nickname' => array(
				'type' => 'text',
				'left' => 'admin_nickname',
				'button' => 'nickname_require',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'nickname_require',
					),
				),
			),
			'password' => array(
				'type' => 'password',
				'left' => 'admin_pwd',
				'button' => 'pwd_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'password_require',
					),
					'length' => array(
						'compare' => "< 6",
						'alert' => 'password_length',
					),
				),
			),
			'repassword' => array(
				'type' => 'password',
				'left' => 'admin_repwd',
				'button' => 'pwd_confirm',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'value' => array(
						'compare' => "!= $('#password').val()",
						'alert' => 'repassword_error',
					),
				),
			),
			'ip_limit' => array(
				'type' => 'textarea',
				'left' => 'ip_limit',
				'button' => 'ip_limit_input',
				'cols' => '',
				'rows' => '3',
				'w' => '',
				'value' => '',
			),
		);
		$this->adminDisplay('Public/add_edit');
	}

	//编辑管理员
	public function edit()
	{

		//修改
		if (!empty($_POST)) {

			$where['id'] = I('post.id');

			//修改昵称
			$data['nickname'] = I('post.nickname');

			//修改ip
			$data['ip_limit'] = I('post.ip_limit');

			//修改密码
			if (I('post.password') != '') {
				if (I('post.password') != I('post.repassword')) {
					C('G_ERROR', 'repassword_error');
					goto end;
				}
				$data['password'] = I('post.password');
			}
			$row = D('Admin')->UpdateData($data, $where);
			if ($row > 0){
				C('G_ERROR', 'success');
			}else{
				C('G_ERROR', 'fail');
			}

		}

		end:
		//查询个人信息
		$id = I('get.id');
		if (!empty($id)) {
			$where['id'] = $id;
			$row = D('Admin')->getAdminRow($where);
		}

		//显示
		$this->v['form'] = 'main';
		$this->v['title'] = 'admin_edit';
		$this->v['method'] = 'post';
		$this->v['action'] = 'edit';
		$this->v['fa'] = 'edit';
		$this->v['hKey'] = 'id';
		$this->v['hVal'] = $id;
		$this->v['form_list'] = array(
			'username' => array(
				'type' => 'text',
				'left' => 'admin_account',
				'button' => 'username_require',
				'w' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'value' => $row['username'],
				'check' => array(
					'require' => array(
						'alert' => 'username_require',
					),
					'length' => array(
						'compare' => "< 4",
						'alert' => 'username_length',
					),
				),
				'disabled' => 1,
			),
			'nickname' => array(
				'type' => 'text',
				'left' => 'admin_nickname',
				'button' => 'nickname_require',
				'w' => 'medium',
				'value' => $row['nickname'],
				'check' => array(
					'require' => array(
						'alert' => 'nickname_require',
					),
				),
			),
			'password' => array(
				'type' => 'password',
				'left' => 'admin_pwd',
				'button' => 'pwd_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'length' => array(
						'compare' => "< 6",
						'alert' => 'password_length',
					),
				),
			),
			'repassword' => array(
				'type' => 'password',
				'left' => 'admin_repwd',
				'button' => 'pwd_confirm',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'value' => array(
						'compare' => "!= $('#password').val()",
						'alert' => 'repassword_error',
					),
				),
			),
			'ip_limit' => array(
				'type' => 'textarea',
				'left' => 'ip_limit',
				'button' => 'ip_limit_input',
				'cols' => '',
				'rows' => '3',
				'w' => '',
				'value' => $row['ip_limit'],
			),
		);
		$this->adminDisplay('Public/add_edit');
	}

	//管理员权限
	public function permission()
	{
		//修改
		if (!empty($_POST)) {
			//超级管理员不更改
			if (in_array(I('post.id'), get_config('super_admin'))) {
				C('G_ERROR', 'not_update_super');
			} else {
				//删除所有权限
				$where['id'] = I('post.id');
				if (false === D('AdminPermission')->DeleteList($where)) {
					goto end;
				}
				//增加权限
				$all = array();
				foreach (I('post.permission') as $value) {
					$arr = explode('.', $value);
					$data['id'] = I('post.id');
					$data['controller'] = $arr[0];
					$data['action'] = $arr[1];
					$all[] = $data;
				}
				if (false === D('AdminPermission')->CreateAllData($all)) {
					goto end;
				}
				C('G_ERROR', 'success');
			}

		}

		//查询个人信息
		$id = I('get.id');
		if (!empty($id)) {
			$where['id'] = $id;
			$this->data = D('Admin')->getAdminRow($where);
		}

		//获取已经开放的权限
		if (!empty($id)) {
			$this->permission = D('AdminPermission')->getList($id);
		}

		//显示
		end:
		$this->super = in_array(I('get.id'), get_config('super_admin')) ? '1' : '0';
		$this->display();//显示页面
	}

}