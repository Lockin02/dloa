<?php
/**
 * @author Administrator
 * @Date 2017��11��03�� ����һ 14:16:48
 * @version 1.0
 * @description:�⳵�ǼǼ�¼��������ʱ������Ϣ�� sql�����ļ�
 */
$sql_arr = array (
    "select_default"=>"select * from oa_contract_rentcar_expensetmp c where 1=1 ",
    "select_defaultWithDetail"=>"select c.allregisterId,c.ExaStatus,c.carNumber,c.rentalPropertyCode,c.rentalContractId,
            c.payInfo1Id,c.pay1Money,c.payInfo2Id,c.pay2Money,i.* 
            from oa_contract_rentcar_expensetmp c left join oa_contract_rentcar_expensetmp_item i on i.parentId = c.id where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    )
)
?>