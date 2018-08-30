<?php
/**
 * @author Michael
 * @Date 2015年6月24日
 * @version 1.0
 * @description:基础信息-分类管理 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.classifyName ,c.parent ,c.remark ,c.createTime ,c.createId ,c.createName,a.classifyName as parentName from oa_manufacture_classify c left join oa_manufacture_classify a on c.parent = a.id where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "classifyName",
		"sql" => " and c.classifyName LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%' ,# ,'%') "
	)
)
?>