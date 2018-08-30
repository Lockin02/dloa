<?php
/**
 * @author Administrator
 * @Date 2012年8月27日 星期二 15:54:13
 * @version 1.0
 * @description:书籍查看
 */
$sql_arr = array(
         "select_default"=>"select p.userNo ,p.userName , p.belongDeptName, a.USER_ID, b.ISBN ,b.BOOK_NAME ,a.BR_SDATE ,a.BR_EDATE ,a.BR_ID, a.BOOK_ID, a.BR_EDATE as CHECK_EDATE,a.BR_PASS2 from book_read a join book_info b on a.BOOK_ID=b.BOOK_ID left join oa_hr_personnel p on a.USER_ID=p.userAccount WHERE 1=1"
);

$condition_arr = array (
	array(
   		"name" => "userId",
   		"sql" => " and a.USER_ID=# "
        )
);
?>