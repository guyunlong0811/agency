<?php
return array(
	'FUNCTION' => array(
		array(
			'name' => 'admin_manager',
			'icon' => 'user',
			'list' => array(
				array(
					'name' => 'admin_model',
					'controller' => 'Admin',
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

		array(
			'name' => 'user_module',
			'icon' => 'group',
			'list' => array(
				array(
					'name' => 'user_manage',
					'controller' => 'User',
					'permission' => array(
						array('action' => 'index', 'name' => 'user_index',),
						array('action' => 'add', 'name' => 'user_add',),
						array('action' => 'edit', 'name' => 'user_edit',),
						array('action' => 'order', 'name' => 'user_order',),
					),
				),

				array(
					'name' => 'order_manage',
					'controller' => 'Order',
					'permission' => array(
						array('action' => 'index', 'name' => 'order_index',),
						array('action' => 'detail', 'name' => 'order_detail',),
					),
				),
			),
		),

	),
);