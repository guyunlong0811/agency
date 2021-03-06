<?php
namespace Home\Controller;

use Think\Controller;

class BaseController extends Controller
{

	protected $v = array();//显示参数
	protected $mSuper = false;//是否是超级管理员
	protected $mServerList = array();//游戏服务器列表
	protected $mServerPermissionList = array();//游戏服务器列表
	protected $mTransFlag;//事务是否成功
	protected $mNow;
	protected $mScriptUrl;

	//前置工作
	public function _initialize()
	{

		set_time_limit(0);
		header_info();
		$this->mNow = time();

		//执行脚本放过会员检查
		if (CONTROLLER_NAME == 'Script' || CONTROLLER_NAME == 'Test') {
			return;
		}

		//检查登录状态
		if (!isset($_SESSION['adminId'])) {
			redirect(__APP__ . MODULE_NAME . '/Home/logout');
		}

		//检测管理员合法性
		$where['id'] = $_SESSION['adminId'];
		$admin = D('Admin')->getAdminRow($where);

		//判断是否为超级管理员
		if (in_array($_SESSION['adminId'], get_config('super_admin'))) {
			$this->mSuper = true;
		}

		//判断权限
		if (!$this->permission()) {
			redirect(__APP__ . MODULE_NAME . '/Index/index&error=no_permission');
		}

		//模块
		$this->v['controller'] = CONTROLLER_NAME;

		//昵称
		$this->v['nickname'] = $admin['nickname'];
		$this->v['app'] = APP_STATUS;

		//功能
		$this->v['function'] = get_config('Function');
		$this->v['icon'] = 'home';
		foreach ($this->v['function'] as $key => $value) {
			foreach ($value['list'] as $k => $val) {
				if ($this->v['controller'] == $k) {
					$this->v['icon'] = $value['icon'];
					break;
				}
			}
			if ($this->v['icon'] != 'home') {
				break;
			}
		}

		//客户端信息
		$this->v['s'] = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
		$this->v['get'] = $_GET;
		$this->v['post'] = $_POST;
		if (isset($this->v['post']['ret'])) {
			$this->v['ret'] = $this->v['post']['ret'];
		} else if (isset($this->v['get']['ret'])) {
			$this->v['ret'] = $this->v['get']['ret'];
		} else {
			$this->v['ret'] = __CONTROLLER__;
		}
		$this->v['self'] = __SELF__ != '' ? urlencode(__SELF__) : '###';

	}

	//分页
	protected function page($model = false, $style = 'sql', $where = '1=1', $num = 15)
	{//分页

		if ($style == 'sql') {
			$count = $model->where($where)->count();
			$count = !empty($count) ? $count : '0';
		} else if ($style == 'array') {
			$count = count($model);
		} else {
			return false;
		}
//		if($count == 0)return false;
		//dump($count);
		$page = new \Think\Page($count, $num);
		$page->rollPage = 5;
		$page->setConfig('first', '<<');
		$page->setConfig('prev', '<');
		$page->setConfig('next', '>');
		$page->setConfig('last', '>>');
		$page->setConfig('theme', "%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%");//rows %NOW_PAGE%/%TOTAL_PAGE%pages
		$this->v['pageBar'] = $page->show();
		return $page;

	}

	//分页
	protected function pageApi($count, $num = 10)
	{//分页

		$page = new \Think\Page($count, $num);
		$page->setConfig('first', '<<');
		$page->setConfig('prev', '<');
		$page->setConfig('next', '>');
		$page->setConfig('last', '>>');
		$page->setConfig('theme', "%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% total:%TOTAL_ROW%");//rows %NOW_PAGE%/%TOTAL_PAGE%pages
		$this->v['pageBar'] = $page->show();
		return $page;

	}

	//记录管理员日志
	private function permission()
	{
		//超级管理员
		if ($this->mSuper) {
			return true;
		} else if (CONTROLLER_NAME == 'Index' && ACTION_NAME == 'index') {
			return true;
		} else {
			$flag = true;

			//功能权限
			if (!D('AdminPermission')->isPermission($_SESSION['adminId'], CONTROLLER_NAME, ACTION_NAME)) {
				$flag = false;
			}

			//服务器权限
			if (isset($_REQUEST['server_id']) && $_REQUEST['server_id'] != 0 && !D('AdminServer')->isPermission($_SESSION['adminId'], $_REQUEST['server_id'])) {
				$flag = false;
			}

			return $flag;
		}

	}

	//记录管理员日志
	private function log()
	{
		if (CONTROLLER_NAME == 'AdminLog' || CONTROLLER_NAME == 'Script' || CONTROLLER_NAME == 'Test') {
			return;
		}
		$data['admin_id'] = $_SESSION['adminId'];
		$data['controller'] = CONTROLLER_NAME;
		$data['action'] = ACTION_NAME;
		$data['server'] = C('G_SERVER');
		$sql = C('G_SQL');
		foreach ($sql as $value) {
			$data['sql'] .= $value . ';';
		}
		$data['ip'] = get_ip();
		D('AdminLog')->CreateData($data);
		return;
	}

	//http下载头部
	public function download($url, $file)
	{
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename="' . $file . '"');
		readfile($url);
	}

	//http下载头部
	public function adminDisplay($display, $assign = array())
	{
		$this->v['alert'] = get_error();
		$assign['v'] = $this->v;
		foreach ($assign as $key => $item) {
			$this->assign($key, $item);
		}
		$this->display($display);//显示页面
	}

	//获取客户显示信息
	protected function getUserDisplayName($info)
	{
		$user = $info['uid'];
		if (!empty($info['nickname'])) {
			$user = $info['nickname'];
		} else if (!empty($info['firstname']) && !empty($info['lastname'])) {
			$user = $info['lastname'] . $info['firstname'];
		} else if (!empty($info['wechat'])) {
			$user = $info['wechat'];
		} else if (!empty($info['email'])) {
			$user = $info['email'];
		} else if (!empty($info['phone'])) {
			$user = $info['phone'];
		}
		return $user;
	}

	//脚本返回
	protected function scriptReturn($error)
	{
		if (empty($error)) {
			$rs['status'] = 'success';
		} else {
			$rs['status'] = 'fail';
			$rs['error'] = json_encode($error);
		}
		$rs['sid'] = C('G_SID');
		$rs['timestamp'] = time();
//        header_info('json');
		echo json_encode($rs);
		return;
	}

	//析构函数
	public function __destruct()
	{
		//记录日志
//		$this->log();
	}

}