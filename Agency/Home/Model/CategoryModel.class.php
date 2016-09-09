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

	//获取分类树状结构
	public function getTreeList($tree = false, $class = 0)
	{
		//获取树
		if($tree === false){
			$tree = $this->getTree();
		}

		//空树
		if (empty($tree)) {
			return array();
		}

		//遍历树
		$list = array();
		foreach($tree as $key => $value){
			$arr['id'] = $value['id'];
			$arr['fid'] = $value['fid'];
			$arr['name'] = $value['name'];
			$arr['class'] = $class;
			$list[] = $arr;
			if(!empty($value['list'])){
				$cList = $this->getTreeList($value['list'], $class + 1);
				$list = array_merge($list, $cList);
			}
		}

		//返回
		return $list;
	}

	//获取选择框
	public function getSelectList()
	{
		//前缀
		$nbsp = '';
		for ($i = 1; $i <= 8; ++$i) {
			$nbsp .= '&nbsp';
		}
		$list = $this->getTreeList();
		foreach ($list as $key => $value) {
			$pre = '';
			for ($i = 0; $i < $value['class']; ++$i) {
				$pre .= $nbsp;
			}
			$pre .= $value['fid'] == 0 ? '┏' : '┗ ';
			$list[$key]['name'] = "{$pre}{$value['name']}";
		}
		return $list;
	}

}