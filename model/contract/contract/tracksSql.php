<?php
/**
 * @author HaoJin
 * @Date 2016年12月8日 11:35:28
 * @version 1.0
 * @description:合同执行轨迹新表 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id,c.contractId,c.contractCode,c.proportion,c.schedule,c.modelName,c.operationName,c.result,".
                        "c.recordTime,c.expand1,c.expand2,c.expand3,c.createTime,c.createId,c.remarks".
                        " from oa_contract_schdl_record c " .
                        " where 1=1 ",
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    )
);