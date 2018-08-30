<?php
/**
 * @author zengqin
 * @Date 2015-3-10
 * @version 1.0
 * @description:预算明细修改历史记录SQL配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id,c.detailId,c.modifyField,c.beforeModify,c.afterModify,c.updateId,c.updateName,c.updateTime from oa_finance_budget_log c where 1=1 "
);

$condition_arr = array (
	array(
        "name" => "detailId",
        "sql" => " and c.detailId=(#) "
    )
)
 ?>