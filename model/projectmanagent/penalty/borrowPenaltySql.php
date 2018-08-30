<?php
$sql_arr = array (
		'select_default'=>'select c.id,c.penaltyCode,c.objectTypeId,c.objectType,c.penaltyName,c.penaltyMoney,c.payment,c.status,c.recentlyTime,c.completeTime,c.principalName,c.sourceId,c.sourceCode,c.sourceType,c.expirationDate,
TO_DAYS(now())-TO_DAYS(c.penaltyDate) as dateNumber,c.createTime,c.cacheDate,c.intervalDate,c.limitMoney,c.money,c.costType,c.multiple,c.penaltyDate,c.closeReason,c.limits
 ,c.closeName,c.closeTime,c.returnStatus,c.returnTime,c.limitType,c.penaltyNameId from oa_borrow_penalty c',
         'select_detail'=>'select c.id,c.penaltyCode,c.objectTypeId,c.objectType,c.penaltyName,c.penaltyMoney,c.payment,c.status,c.recentlyTime,c.completeTime,c.principalName,c.sourceId,c.sourceCode,c.sourceType,c.expirationDate,
TO_DAYS(now())-TO_DAYS(c.penaltyDate) as dateNumber,c.createTime,c.cacheDate,c.intervalDate,c.limitMoney,c.money,c.costType,c.multiple,c.penaltyDate,c.closeReason ,
(p.number-p.backNum) as noreturnNum ,c.closeName,c.closeTime ,c.returnStatus,c.returnTime,c.limitType,c.penaltyNameId
from oa_borrow_penalty c left join oa_borrow_expiredProduct p
on c.id=p.penaltyId  '
);

$condition_arr = array (
array(
	        'name' => 'id',
	        'sql' => 'and c.id =#'
	        ),
	        array(
	        'name' => 'penaltyCode',
	        'sql' => 'and c.penaltyCode =#'
	        ),
	        array(
	        'name' => 'objectTypeId',
	        'sql' => 'and c.objectTypeId =#'
	        ),
	        array(
	        'name' => 'objectType',
	        'sql' => "and c.objectType like CONCAT('%',#,'%') "
	        ),
	        array(
	        'name' => 'penaltyName',
	        'sql' => "and c.penaltyName like CONCAT('%',#,'%')"
	        ),
	        array(
	        'name' => 'penaltyMoney',
	        'sql' => 'and c.penaltyMoney =#'
	        ),
	        array(
	        'name' => 'payment',
	        'sql' => 'and c.payment =#'
	        ),
	        array(
	        'name' => 'status',
	        'sql' => 'and c.status =#'
	        ),
	        array(
	        'name'=>'recentlyTime',
	        'sql'=>' and c.recentlyTime != #'
	        ),
	        array(
	        'name'=>'completeTime',
	        'sql'=>' and c.completeTime = #'
	        ),
	        array(
	        'name'=>'principalName',
	        'sql'=>' and c.principalName = #'
	        ),
	        array(
	        'name'=>'sourceId',
	        'sql'=>' and c.sourceId = #'
	        ),
	        array(
	        'name'=>'sourceCode',
	        'sql'=> "and c.sourceCode like CONCAT('%',#,'%')"
	        ),
	        array(
	        'name'=>'sourceType',
	        'sql'=>' and c.sourceType = #'
	        ),
	        array(
	        'name'=>'expirationDate',
	        'sql'=>' and c.expirationDate = #'
	        ),
	        array(
	        'name'=>'dateNumber',
	        'sql'=>' and c.dateNumber = #'
	        ),
	        array(
	        'name'=>'createTime',
	        'sql'=>' and c.createTime = #'
	        ),
	        array(
	        'name'=>'cacheDate',
	        'sql'=>' and c.cacheDate = #'
	        ),
	        array(
	        'name'=>'intervalDate',
	        'sql'=>' and c.intervalDate = #'
	        ),
	        array(
	        'name'=>'limitMoney',
	        'sql'=>' and c.limitMoney = #'
	        ),
	        array(
	        'name'=>'money',
	        'sql'=>' and c.money  = #'
	        ),
	        array(
	        'name'=>'penaltyNameId',
	        'sql'=>' and c.penaltyNameId = #'
	        )
	        );
