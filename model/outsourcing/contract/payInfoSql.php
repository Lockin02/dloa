<?php
/**
 * @author Michael
 * @Date 2017��10��24�� ������ 23:10:50
 * @version 1.0
 * @description:�⳵��ͬ������Ϣ sql�����ļ�
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