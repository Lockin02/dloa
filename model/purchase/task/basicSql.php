<?php
$sql_arr = array(
    "list_page" => "select id ,taskNumb ,sendUserId ,sendName ,sendTime ,dateHope ,dateFact ,dateReceive ,
            instruction ,remark ,state ,createId ,createName ,createTime ,updateId ,updateName ,updateTime ,
            isTemp,originalId,feedback,closeRemark,ExaStatus,businessBelongName
        from oa_purch_task_basic c  where 1=1",
    "list_page_shot" => "select " .
        "id ,taskNumb ,sendUserId ,sendName ,sendTime ,dateHope ,dateFact ,dateReceive ,instruction ,remark ,state  ,isTemp,originalId,closeRemark,ExaStatus" .
        "from " .
        "oa_purch_task_basic c where 1=1",
    "list_page_long" => "select " .
        "id ,taskNumb ,sendUserId ,sendName ,sendTime ,dateHope ,dateFact ,dateReceive ,instruction ,remark ,state ,createId ,createName ,createTime ,updateId ,updateName ,updateTime ,isTemp,originalId " .
        "from " .
        "oa_purch_task_basic c where 1=1",
    "list_page_change" => "select id ,taskNumb ,sendUserId ,sendName ,sendTime ,dateHope ,dateFact ,dateReceive ,instruction ,remark ,state ,createId ,createName ,createTime ,updateId ,updateName ,updateTime ,isTemp,originalId from oa_purch_task_basic c  where isTemp=1 and 1=1"
);
$condition_arr = array(
    array(
        "name" => "ids",
        "sql" => " and id in(arr)"
    ),
    array(
        "name" => "taskNumb",
        "sql" => " and taskNumb like CONCAT('%',#,'%')"
    ),
    array(
        "name" => "taskNumbMy",
        "sql" => " and taskNumb=# "
    ),
    array(
        "name" => "groupBy",
        "sql" => " group By # "
    ),
    array(
        "name" => "stockTaskId",
        "sql" => " and p.stockTaskId=#",
    ),
    array(
        "name" => "selectEqu",
        "sql" => " and id in(arr)",
    ),

    array(
        "name" => "sendUserId",
        "sql" => " and sendUserId=# "
    ),
    array(
        "name" => "sendName",
        "sql" => " and sendName like CONCAT('%',#,'%') ",
    ),
    array(
        "name" => "sendTime",
        "sql" => " and sendTime LIKE BINARY CONCAT('%',#,'%') ",
    ),
    array(
        "name" => "createId",
        "sql" => " and createId=# ",
    ),
    array(
        "name" => "createName",
        "sql" => " and createName =# ",
    ),

    array(
        "name" => "state",
        "sql" => " and state=# "
    ),
    array(
        "name" => "stateInArr",
        "sql" => " and state in(arr) "
    ),
    //是否是使用版本
//	array(
//		"name" => "isUse",
//		"sql" => " and isUse=# "
//	),
    array(
        "name" => "id",
        "sql" => " and id=# "
    ),
    array(
        "name" => "isTemp",
        "sql" => " and isTemp=#  "
    ),
    array(
        "name" => "originalId",
        "sql" => " and originalId=#  "
    ),
    array(
        "name" => "productNumb",
        "sql" => "and id in(select basicId from oa_purch_task_equ where productNumb like CONCAT('%',#,'%'))"
    ),
    array(
        "name" => "productName",
        "sql" => "and id in(select basicId from oa_purch_task_equ where productName like CONCAT('%',#,'%'))"
    )
);
?>
