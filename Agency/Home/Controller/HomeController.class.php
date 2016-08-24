<?php
namespace Home\Controller;

use Think\Controller;
use Think;

class HomeController extends Controller
{
	//登录
	public function login()
	{

		if ($_SESSION['adminId']) {
			redirect(__APP__ . MODULE_NAME . '/Index/index');
		}
		if (!empty($_POST)) {
			if (D('Admin')->login(I('post.username'), I('post.password'))) {
				redirect(__APP__ . MODULE_NAME . '/Index/index');
			}
		}
		$this->v['alert'] = get_error();
		$this->display();

	}

	//登出
	public function logout()
	{
		unset($_SESSION['adminId']);
		redirect(__APP__ . MODULE_NAME . '/Home/login');
	}

}