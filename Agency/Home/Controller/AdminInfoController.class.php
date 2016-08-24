<?php
namespace Home\Controller;

use Think\Controller;

class AdminInfoController extends BaseController
{

	public function _initialize()
	{
		parent::_initialize();
		$this->v['icon'] = 'user';
	}

	//编辑管理员信息
	public function index()
	{

		//修改
		if (!empty($_POST)) {

			$data['id'] = $_SESSION['adminId'];

			//修改昵称
			$data['nickname'] = I('post.nickname');

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
		if (!empty($_SESSION['adminId'])) {
			$where['id'] = $_SESSION['adminId'];
			$this->data = D('Admin')->getAdminRow($where);
		}

		//显示
		end:
		$this->v['alert'] = get_error();
		$this->display();//显示页面

	}

}