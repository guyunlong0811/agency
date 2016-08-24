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
		$this->vController = CONTROLLER_NAME;

		//昵称
		$this->vNickname = $admin['nickname'];
		$this->vApp = APP_STATUS;

		//功能
		$allFunction = get_config('Function');
		foreach ($allFunction as $key1 => $value1) {

			//将menu显示成特定语言
			$allFunction[$key1]['name'] = L($value1['name']);

			//将子menu显示成特定语言
			foreach ($allFunction[$key1]['list'] as $key2 => $value2) {

				$allFunction[$key1]['list'][$key2]['name'] = L($value2['name']);
				foreach ($value2['permission'] as $key3 => $value3) {
					$allFunction[$key1]['list'][$key2]['permission'][$key3]['name'] = L($value3['name']);
				}

			}

		}

		$this->vFunction = $allFunction;

		//页码
		$this->s = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
		$this->v['get'] = $_GET;
		$this->v['post'] = $_POST;
		$this->v['self'] = __SELF__ == '' ? urlencode(__SELF__) : '###';

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
		$this->vPageBar = $page->show();
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
		$this->vPageBar = $page->show();
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