<?php
/**
 * @author show
 * @Date 2013年8月23日 17:19:19
 * @version 1.0
 * @description:日志考核结果设置 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.resultName ,c.resultVal ,c.resultScore,c.score_10,
        c.score_9, c.score_8,c.score_7, c.score_6,c.score_5, c.score_4,c.score_3, c.score_2,c.score_1, c.score_0
        from oa_esm_ass_result c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "resultName",
        "sql" => " and c.resultName=# "
    ),
    array(
        "name" => "resultVal",
        "sql" => " and c.resultVal=# "
    ),
    array(
        "name" => "resultScore",
        "sql" => " and c.resultScore=# "
    )
);