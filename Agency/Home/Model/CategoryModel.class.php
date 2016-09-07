<?php
namespace Home\Model;

use Think\Model;

class CategoryModel extends BaseModel
{
	//获取分类树状结构
	public function getTree()
	{
		//获取数据
		$select = $this->select();
		if (empty(select)) {
			return array();
		}

		$items = array();
		foreach ($select as $key => $value) {
			$items[$value['id']] = $value;
		}
		unset($select);

		//格式化
		foreach ($items as $item) {
			$items[$item['fid']]['list'][$item['id']] = &$items[$item['id']];
		}

		//返回
		return isset($items[0]['list']) ? $items[0]['list'] : array();
	}

}