<?php
/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��Ŀ������¼ sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select * from oa_esm_notice c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "contentSearch",
   		"sql" => " and c.content like CONCAT('%',#,'%') "
    ),
    array(
        "name" => "noticeTitleSearch",
   		"sql" => " and c.noticeTitle like CONCAT('%',#,'%') "
    ),
    array (//�Զ�������
        "name" => "limitOffice",
        "sql" => " and (officeIds = '' # "
    ),
    array(
        "name" => "nullOffice",
        "sql" => " and c.officeIds = '' "
    )
);