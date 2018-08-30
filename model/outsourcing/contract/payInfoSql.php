<?php
/**
 * @author Michael
 * @Date 2017年10月24日 星期四 23:10:50
 * @version 1.0
 * @description:租车合同付款信息 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select * from oa_contract_rentcar_payinfos where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    )
);