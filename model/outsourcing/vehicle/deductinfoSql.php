<?php
/**
 * @author huanghj
 * @Date 2018��3��23�� ������ 00:23:30
 * @version 1.0
 * @description:�⳵�Ǽǻ��ܿۿ���Ϣ sql�����ļ�
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