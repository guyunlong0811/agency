<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{

	public function _initialize()
	{

		parent::_initialize();
		$this->v['icon'] = 'home';
		$this->vController = 'Index';

	}

	public function index()
	{
		$this->display();//显示页面
	}

}