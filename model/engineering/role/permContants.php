<?php
/**
 * @description: 定义权限点编码(暂时不用)
 */
	$perm_arr = array ("项目管理" => 
		array (
			"project_dashboard" => "项目仪表盘",
			"chirdren"=>
				array(
					"project_dashboard_report"=>"项目报告生成"
				) 
		),
		array (
			"project-office" => "虚拟办公室"
		),
		array (
			"project_teamMember" => "项目组成员",
			"chirdren"=>
				array(
					"project_teamMember_admin"=>"新建角色/新建成员/设置权限"
				) 
		),
		array (
			"project_task" => "项目任务"
		),
		array (
			"project_baseInfo" => "基本信息",
			"chirdren"=>
				array(
					"project_baseInfo_update"=>"修改项目属性"
				) 
		),
		array (
			"project_advInfo" => "高级信息",
			"chirdren"=>
				array(
					"project_advInfo_update"=>"修改高级信息"
				) 
		),
		array (
			"project_close" => "项目关闭/项目暂停/项目恢复"
		)
	);
?>
