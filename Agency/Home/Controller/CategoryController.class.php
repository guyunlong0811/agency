<?php
namespace Home\Controller;

use Think\Controller;

class CategoryController extends BaseController
{

	private $mList;

	public function _initialize()
	{
		parent::_initialize();
		$this->mList = D('Category')->getTreeList();
	}

	//显示列表
	public function index()
	{
		//前缀
		$nbsp = '';
		for ($i = 1; $i <= 10; ++$i) {
			$nbsp .= '&nbsp';
		}

		$list = $this->mList;
		foreach ($list as $key => $value) {
			$pre = '';
			for ($i = 0; $i < $value['class']; ++$i) {
				$pre .= '|' . $nbsp;
			}
			$pre .= $value['fid'] == 0 ? '┏' : '┗ ';
			$list[$key]['name'] = "{$pre}{$value['name']}({$value['id']})";
		}

		//显示
		$this->v['title'] = 'category_manage';
		$this->v['idKey'] = 'id';
		$this->v['isPage'] = '0';
		$this->v['list'] = $list;
		$this->v['tools'] = array(
			'title' => L('category_add'),
			'action' => 'add',
		);
		$this->v['table'] = array(
			'category_name' => array('field' => 'name',),
		);
		$this->v['operation'] = array(
			'add' => array('type' => 'a_c', 'fa' => 'plus', 'color' => 'green', 'link' => ' | '),
			'edit' => array('type' => 'a_c', 'fa' => 'edit', 'color' => 'yellow', 'link' => ' | '),
			'remove' => array('type' => 'a_c', 'fa' => 'trash-o', 'color' => 'red', 'link' => ''),
		);
		$this->adminDisplay('Public/list');//显示页面
	}

	//新增管理员
	public function add()
	{

		if (!empty($_POST)) {

			$data['fid'] = I('post.fid');
			$data['name'] = I('post.name');
			if (false === D('Category')->CreateData($data)) {
				C('G_ERROR', 'fail');
			}
			C('G_ERROR', 'success');
		}

		//显示
		end:
		$fatherID = I('get.id') ? I('get.id') : 0;
		$this->v['form'] = 'main';
		$this->v['title'] = 'admin_add';
		$this->v['method'] = 'post';
		$this->v['action'] = 'add';
		$this->v['fa'] = 'plus';
		$this->v['category'] = D('Category')->getSelectList();
		$this->v['form_list'] = array(
			'fid' => array(
				'type' => 'category',
				'left' => 'category_father',
				'tip' => 'category_father_select',
				'w' => '',//xsmall | small | medium | large | xlarge | ''
				'value' => $fatherID,
			),
			'name' => array(
				'type' => 'text',
				'left' => 'category_name',
				'tip' => 'category_name_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'require' => array(
						'alert' => 'category_name_input',
					),
				),
			),
		);
		$this->adminDisplay('Public/add_edit');
	}

	//编辑管理员
	public function edit()
	{

		//修改
		if (!empty($_POST)) {

			$where['id'] = I('post.id');

			//修改昵称
			$data['nickname'] = I('post.nickname');

			//修改ip
			$data['ip_limit'] = I('post.ip_limit');

			//修改密码
			if (I('post.password') != '') {
				if (I('post.password') != I('post.repassword')) {
					C('G_ERROR', 'repassword_error');
					goto end;
				}
				$data['password'] = I('post.password');
			}
			$row = D('Admin')->UpdateData($data, $where);
			if ($row > 0) {
				C('G_ERROR', 'success');
			} else {
				C('G_ERROR', 'fail');
			}

		}

		end:
		//查询个人信息
		$id = I('get.id');
		if (!empty($id)) {
			$where['id'] = $id;
			$row = D('Admin')->getAdminRow($where);
		}

		//显示
		$this->v['form'] = 'main';
		$this->v['title'] = 'admin_edit';
		$this->v['method'] = 'post';
		$this->v['action'] = 'save';
		$this->v['fa'] = 'edit';
		$this->v['hKey'] = 'id';
		$this->v['hVal'] = $id;
		$this->v['form_list'] = array(
			'username' => array(
				'type' => 'text',
				'left' => 'admin_account',
				'button' => 'username_require',
				'w' => 'medium',//xsmall | small | medium | large | xlarge | ''
				'value' => $row['username'],
				'check' => array(
					'require' => array(
						'alert' => 'username_require',
					),
					'length' => array(
						'compare' => "< 4",
						'alert' => 'username_length',
					),
				),
				'disabled' => 1,
			),
			'nickname' => array(
				'type' => 'text',
				'left' => 'admin_nickname',
				'button' => 'nickname_require',
				'w' => 'medium',
				'value' => $row['nickname'],
				'check' => array(
					'require' => array(
						'alert' => 'nickname_require',
					),
				),
			),
			'password' => array(
				'type' => 'password',
				'left' => 'admin_pwd',
				'button' => 'pwd_input',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'length' => array(
						'compare' => "< 6",
						'alert' => 'password_length',
					),
				),
			),
			'repassword' => array(
				'type' => 'password',
				'left' => 'admin_repwd',
				'button' => 'pwd_confirm',
				'w' => 'medium',
				'value' => '',
				'check' => array(
					'value' => array(
						'compare' => "!= $('#password').val()",
						'alert' => 'repassword_error',
					),
				),
			),
			'ip_limit' => array(
				'type' => 'textarea',
				'left' => 'ip_limit',
				'button' => 'ip_limit_input',
				'cols' => '',
				'rows' => '3',
				'w' => '',
				'value' => $row['ip_limit'],
			),
		);
		$this->adminDisplay('Public/add_edit');
	}

}