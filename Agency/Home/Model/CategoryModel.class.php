<?php
namespace Home\Model;

use Think\Model;

/*
功能:基于TP2.0的无限分类。
用法:
第一种用法,不采用数据库,可以不需要TP，例子如下
<?php
require('Category.class.php');//导入Category.class.php类
//测试数据
$data[]=array('cat_id'=>1,'pid'=>0,'name'=>'中国');
$data[]=array('cat_id'=>2,'pid'=>0,'name'=>'美国');
$data[]=array('cat_id'=>3,'pid'=>0,'name'=>'韩国');
$data[]=array('cat_id'=>4,'pid'=>1,'name'=>'北京');
$data[]=array('cat_id'=>5,'pid'=>1,'name'=>'上海');
$data[]=array('cat_id'=>6,'pid'=>1,'name'=>'广西');
$data[]=array('cat_id'=>7,'pid'=>6,'name'=>'桂林');
$data[]=array('cat_id'=>8,'pid'=>6,'name'=>'南宁');
$data[]=array('cat_id'=>9,'pid'=>6,'name'=>'柳州');
$data[]=array('cat_id'=>10,'pid'=>2,'name'=>'纽约');
$data[]=array('cat_id'=>11,'pid'=>2,'name'=>'华盛顿');
$data[]=array('cat_id'=>12,'pid'=>3,'name'=>'首尔');

$cat=new Category('',array('cat_id','pid','name','cname'));
$s=$cat->getTree($data);//获取分类数据树结构
//$s=$cat->getTree($data,1);获取pid=1所有子类数据树结构
foreach($s as $vo)
{
echo $vo['cname'].'<br>';
}

第二种用法,采用数据库,基于TP,例子如下
数据表,前缀_articlec_cat,包含cat_id,pid,title三个字段
require('Category.class.php');//导入Category.class.php类
$cat=new Category('ArticleCat',array('cat_id','pid','title','fulltitle'));
$s=$cat->getList();//获取分类结构
$s=$cat->getList('',1);//获取pid=1的子分类结构
$s=$cat->getList($condition,1);//$condition为查询条件，获取pid=1的子分类结构
$s=$cat->getPath(3);//获取分类id=3的路径

$s=$cat->add($data);//添加分类，$data需要包含上级分类pid
$s=$cat->edit($data);//修改分类,$data需要包含分类ID
$s=$cat->del(10);//删除分类id=10的分类
详细用法：参考代码说明
/**
+------------------------------------------------------------------------------
* 分类管理
+------------------------------------------------------------------------------
*/

class CategoryModel extends BaseModel
{

	//原始的分类数据
	private $rawList = array();
	//格式化后的分类
	private $formatList = array();
	//错误信息
	private $error = "";
	//格式化的字符
	private $icon = array('&nbsp&nbsp│', '&nbsp&nbsp├ ', '&nbsp&nbsp└ ');
	//字段映射，分类id，上级分类pid,分类名称title,格式化后分类名称fulltitle
	private $field = array(
		'id' => 'id',
		'pid' => 'pid',
		'title' => 'name',
		'fulltitle' => 'fullname',
	);

	/*
	功能：获取分类信息数据；
	属性：private;
	参数：查询条件$condition；
	返回：无；
	备注:需要TP支持
	*/

	private function _findAllCat($condition, $orderby = NULL)
	{
		if (empty($orderby)) {
			$this->rawList = $this->model->where($condition)->findAll();
		} else {
			$this->rawList = $this->model->where($condition)->order($orderby)->findAll();
		}
	}

	/*
	功能：返回给定上级分类$pid的所有同一级子分类；
	属性：public;
	参数：上级分类$pid；
	返回：子分类，二维数组；
	备注:
	*/

	public function getChild($pid)
	{
		$childs = array();
		foreach ($this->rawList as $Category) {
			if ($Category['pid']] == $pid)
				$childs[] = $Category;
		}
		return $childs;
	}

	/*
	功能：无限分类核心部分，递归格式化分类前的字符；
	属性：private;
	参数：分类id,前导空格；
	返回：无；
	备注:
	*/

	private function _searchList($CatID = 0, $space = "")
	{
		//下级分类的数组
		$childs = $this->getChild($CatID);
		//如果没下级分类，结束递归
		if (!($n = count($childs)))
			return;
		$cnt = 1;
		//循环所有的下级分类
		for ($i = 0; $i < $n; $i++) {
			$pre = "";
			$pad = "";
			if ($n == $cnt) {
				$pre = $this->icon[2];
			} else {
				$pre = $this->icon[1];
				$pad = $space ? $this->icon[0] : "";
			}
			$childs[$i]['fulltitle']] = ($space ? $space . $pre : "") . $childs[$i]['title']];
			$this->formatList[] = $childs[$i];
			//递归下一级分类
			$this->_searchList($childs[$i]['id']], $space . $pad . "&nbsp;&nbsp;");
			$cnt++;
		}
	}

	/*
	功能：不采用数据模型时，可以从外部传递数据，得到递归格式化分类；
	属性：public;
	参数：$condition，数字或字符串，需要符合TP查询条件规则,起始分类id,$CatID=0；
	$orderby 排序参数
	返回：递归格式化分类数组；
	备注:需要TP支持
	*/

	public function getList($condition = NULL, $CatID = 0, $orderby = NULL)
	{
		unset($this->rawList);
		unset($this->formatList);
		$this->_findAllCat($condition, $orderby, $orderby);
		$this->_searchList($CatID);
		return $this->formatList;
	}

	/*
	功能：不采用数据模型时，可以从外部传递数据，得到递归格式化分类；
	属性：public;
	参数：$data，二维数组，起始分类id,默认$CatID=0；
	返回：递归格式化分类数组；
	备注:
	*/

	public function getTree($data, $CatID = 0)
	{
		unset($this->rawList);
		unset($this->formatList);
		$this->rawList = $data;
		$this->_searchList($CatID);
		return $this->formatList;
	}

	/*
	功能：获取错误信息；
	属性：public;
	参数：无；
	返回：错误信息字符串；
	备注:
	*/

	public function getError()
	{
		return $this->error;
	}

	/*
	功能：检查分类参数$CatID,是否为空；
	属性：private;
	参数：分类参数$CatID,整型。
	返回：正确，返回true，错误，返回false；
	备注:
	*/

	private function _checkCatID($CatID)
	{
		if (intval($CatID)) {
			return true;
		} else {
			$this->error = "参数分类ID为空或者无效！";
			return false;
		}
	}

	/*
	功能：查询路径；
	属性：private;
	参数：分类参数$CatID,整型。
	返回：出错返回false；
	备注:需要TP支持
	*/

	private function _searchPath($CatID)
	{

		//检查参数
		if (!$this->_checkCatID($CatID))
			return false;

		//初始化对象，查找上级Id；
		$rs = $this->model->find($CatID);

		//保存结果
		$this->formatList[] = $rs;

		$this->_searchPath($rs['pid']]);
	}

	/*
	功能：查询给定分类id的路径；
	属性：public;
	参数：分类参数$CatID,整型。
	返回：数组；
	备注:需要TP支持
	*/

	public function getPath($CatID)
	{
		unset($this->rawList);
		unset($this->formatList);
		//查询分类路径
		$this->_searchPath($CatID);

		return array_reverse($this->formatList);
	}

	/*     * **************************************以下为分类添加、修改、删除*************************************************** */
	/*
	功能：添加分类；
	属性：public;
	参数：$data,一维数组，要添加的数据，$data需要包含上级分类ID。
	返回：添加成功，返回相应的分类ID,添加失败，返回FALSE；
	备注:需要TP支持
	*/

	public function add($data)
	{
		if (empty($data))
			return false;
		return $this->model->data($data)->add();
	}

	/*
	功能：修改分类；
	属性：public;
	参数：$data,一维数组，传递编辑的数据，$data需要包含要修改的分类ID。
	返回：修改成功，返回相应的分类ID,修改失败，返回FALSE；
	备注:需要TP支持
	*/

	public function edit($data)
	{
		if (empty($data))
			return false;
		return $this->model->data($data)->save();
	}

	/*
	功能：删除分类；
	属性：public;
	参数：分类ID
	返回：删除成功，返回相应的分类ID,删除失败，返回FALSE；
	备注:需要TP支持
	*/

	public function del($CatID)
	{
		$CatID = intval($CatID);
		if (empty($CatID))
			return false;
		$conditon['id']] = $CatID;

		return $this->model->where($conditon)->delete();
	}

}