<?php
return array(
	'FUNCTION' => array(

		'admin_module' => array(

			'icon' => 'user',
			'list' => array(

				'Admin' => array(
					'name' => 'admin_manage',
					'permission' => array(
						array('action' => 'index', 'name' => 'admin_index',),
						array('action' => 'status', 'name' => 'admin_change_status',),
						array('action' => 'add', 'name' => 'admin_add',),
						array('action' => 'edit', 'name' => 'admin_edit',),
						array('action' => 'permission', 'name' => 'admin_permission',),
						array('action' => 'server', 'name' => 'admin_server',),
					),
				),

			),

		),

		'item_module' => array(

			'icon' => 'shopping-cart',
			'list' => array(

				'Category' => array(
					'name' => 'category_manage',
					'permission' => array(
						array('action' => 'index', 'name' => 'category_index',),
						array('action' => 'add', 'name' => 'category_add',),
						array('action' => 'edit', 'name' => 'category_edit',),
						array('action' => 'delete', 'name' => 'category_delete',),
					),
				),

			),

		),

		'user_module' => array(

			'name' => 'user_module',
			'icon' => 'group',
			'list' => array(

				'User' => array(
					'name' => 'user_manage',
					'permission' => array(
						array('action' => 'index', 'name' => 'user_index',),
						array('action' => 'add', 'name' => 'user_add',),
						array('action' => 'edit', 'name' => 'user_edit',),
						array('action' => 'order', 'name' => 'user_order',),
					),
				),

				'Order' => array(
					'name' => 'order_manage',
					'permission' => array(
						array('action' => 'index', 'name' => 'order_index',),
						array('action' => 'detail', 'name' => 'order_detail',),
					),
				),

			),

		),

	),

);