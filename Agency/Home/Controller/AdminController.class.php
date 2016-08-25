<?php
namespace Home\Controller;

use Think\Controller;

class AdminController extends BaseController
{

	public function _initialize()
	{
		parent::_initialize();
		$this->v['icon'] = 'user';
	}

	//显示列表
	public function index()
	{
		//查询所有信息
		$model = M('Admin');
		$order = array('id' => 'asc',);
		$page = $this->page($model);
		$list = $model->page($this->pg . ',' . $page->listRows)->order($order)->select();
		foreach ($list as $key => $value) {
			$field = $value['status'] == 1 ? 'active' : 'banned';
			$list[$key]['status'] = L('status_' . $field);
		}
		//显示
		$this->v['title'] = 'admin_list';
		$this->v['list'] = $list;
		$this->display();//显示页面
	}

	//修改状态
	public function status()
	{
		//超级管理员不更改
		if (in_array(I('get.id'), get_config('super_admin'))) {
			C('G_ERROR', 'not_update_super');
		} else {
			if (false === D('Admin')->changeStatus(I('get.id'))) {
				C('G_ERROR', 'fail');
			} else {
				C('G_ERROR', 'success');
			}
		}
		$this->v['alert'] = get_error();
		$this->jump = __CONTROLLER__ . "/index&p=" . I('get.p');
		$this->display("Public:jump");//显示页面
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

			$row = D('Admin')->CreateData($data);
			if ($row > 0)
				C('G_ERROR', 'success');

		}

		//显示
		end:
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

	//编辑管理员
	public function edit()
	{

		//修改
		if (!empty($_POST)) {

			$data['id'] = I('post.id');

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
			$row = D('Admin')->UpdateData($data);
			if ($row > 0)
				C('G_ERROR', 'success');
		}

		//查询个人信息
		$id = I('get.id');
		if (!empty($id)) {
			$where['id'] = $id;
			$this->data = D('Admin')->getAdminRow($where);
		}

		//显示
		end:
		$this->v['alert'] = get_error();
		$this->display();//显示页面
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
		$this->v['alert'] = get_error();
		$this->display();//显示页面
	}

}