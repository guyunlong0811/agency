<?php
namespace Home\Controller;

use Think\Controller;

class TestController extends BaseController
{

	public function index()
	{

		$starttime1 = strtotime('2015-05-13 00:00:00');
		$endtime1 = strtotime('2015-05-13 23:59:59');
		$where1['ctime'] = array('between', array($starttime1, $endtime1));
		$list1 = D('UCLogin')->where($where1)->group('uid')->getField('uid', true);

		$starttime2 = strtotime('2015-05-14 00:00:00');
		$endtime2 = strtotime('2015-05-14 23:59:59');
		$where2['ctime'] = array('between', array($starttime2, $endtime2));
		$list2 = D('UCLogin')->where($where2)->group('uid')->getField('uid', true);

		$list = array_intersect($list1, $list2);

		C('G_SID', 101);
		$dbConfig = change_db_server(101);
		$model = M()->db(101, $dbConfig);

		$create = $model->db(101)->table('g_team')->where("`ctime`>'0'")->getField('uid', true);

		$arr = array_diff($list, $create);
		dump($arr);
		dump(count($arr));


		$endtime3 = $starttime2 + 3 * 3600;
		$select = $model->db(101)->table('g_team')->field('`tid`,`uid`,`last_login_time`')->where("`ctime`>'0' && `ctime`<'{$endtime1}' && `last_login_time` between '{$starttime2}' and '{$endtime3}'")->select();
		dump($model->db(101)->getLastSql());
		dump($select);


	}

}