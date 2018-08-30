<?php
/**
 * @author huanghj
 * @Date 2018年3月23日 星期五 00:23:30
 * @version 1.0
 * @description:租车登记汇总扣款信息 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select * from oa_outsourcing_allregister_deductinfo c where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    )
);