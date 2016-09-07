<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends BaseController
{

	static private $status = array(
		1 => 'order_completed',
		0 => 'order_processing',
		-1 => 'order_cancelled',
	);

	public function _initialize()
	{
		parent::_initialize();
		$this->v['icon'] = 'home';
	}

	public function index()
	{
		$this->display();//显示页面
	}

	public function show_list()
	{
		$order['`o`.`ctime`'] = 'desc';
		$list = M()->table(array('order' => 'o'))->field("`u`.*,`o`.*,`e`.`vendor`,`e`.`track_id`")->join("`user` `u` ON `u`.`uid`=`o`.`uid`")->join("LEFT JOIN `express` `e` ON `e`.`eid`=`o`.`eid1` || `e`.`eid`=`o`.`eid2`")->order($order)->select();
		if (!empty($list)) {
			foreach ($list as $key => $value) {
				$profit = $value['price'] - $value['cost'];
				$list[$key]['user'] = $this->getUserDisplayName($value);
				$list[$key]['status'] = L(self::$status[$value['status']]);
				$list[$key]['ctime'] = time2format($value['ctime']);
				$list[$key]['endtime'] = $value['endtime'] ? time2format($value['endtime']) : '-';
				$list[$key]['price'] = my_money_format($value['price']);
				$list[$key]['cost'] = my_money_format($value['cost']);
				$list[$key]['profit'] = my_money_format($profit);
				$list[$key]['eid'] = $value['eid'] ? $value['eid'] : '-';
				$list[$key]['comment'] = $value['comment'] ? $value['comment'] : '-';
			}
		}
		$this->v['list'] = $list;

		$this->v['form'] = 'search';
		$this->v['title'] = 'order_index';
		$this->v['method'] = 'get';
		$this->v['idKey'] = 'oid';
		$this->v['form_list'] = array(
			'type' => array(
				'type' => 'text',
				'placeholder' => 'product_type',
				'size' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'link' => ' AND ',
				'value' => '',
			),
			'brand' => array(
				'type' => 'text',
				'placeholder' => 'product_brand',
				'size' => 'medium',
				'link' => ' AND ',
				'value' => '',
			),
			'product' => array(
				'type' => 'text',
				'placeholder' => 'product_info',
				'size' => 'medium',
				'link' => ' AND ',
				'value' => '',
			),
			'status' => array(
				'type' => 'select',
				'size' => 'medium',
				'list' => array(
					'' => 'all_status',
					1 => 'order_completed',
					0 => 'order_processing',
					-1 => 'order_cancelled',
				),
				'link' => ' AND ',
				'value' => '',
			),
			'starttime' => array(
				'type' => 'date',
				'size' => 'small',
				'link' => ' - ',
				'value' => time2format(time() - 7 * 86400, 2),
				'fmt' => 'yyyy-MM-dd',
				'min' => '',
				'max' => '#F{$dp.$D(\\\'endtime\\\')}',
			),
			'endtime' => array(
				'type' => 'date',
				'size' => 'small',
				'link' => '&nbsp',
				'value' => time2format(null, 2),
				'fmt' => 'yyyy-MM-dd',
				'min' => '#F{$dp.$D(\\\'starttime\\\')}',
				'max' => time2format(null, 2),
			),
		);

		$this->v['table'] = array(
			'order_id' => array('field' => 'oid',),
			'order_user' => array('field' => 'user',),
			'product_type' => array('field' => 'type',),
			'product_brand' => array('field' => 'brand',),
			'product_price' => array('field' => 'price',),
			'product_count' => array('field' => 'count',),
			'order_status' => array('field' => 'status',),
			'order_ctime' => array('field' => 'ctime',),
			'foreign_express_id' => array('field' => 'eid1', 'type' => 't_a', 'a_href' => 'foreign_express_href', 'a_text' => 'foreign_express_text'),
			'local_express_id' => array('field' => 'eid2',),
		);

		$this->v['operation'] = array(
			'detail' => array('type' => 'detail'),
			'edit' => array('type' => 'a', 'href' => __URL__ . '/edit&oid=<{$value[$v[idKey]]}>&ret=<{$vSelfUrl}>'),
		);

		$this->v['detail'] = array(
			'product_info' => array('field' => 'product',),
			'product_cost' => array('field' => 'cost',),
			'order_profit' => array('field' => 'profit',),
			'order_endtime' => array('field' => 'endtime',),
			'order_comment' => array('field' => 'comment',),
		);

		$this->assign('v', $this->v);
		$this->display('Public/list');
	}

	public function test()
	{
		$rs = D('Category')->getTree();
		dump($rs);
	}

}