<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
$condition2 = " ";
extract($_GET);

if(!empty($formDateSta)){
	$condition.=" and date_format(c.createTime,'%Y-%m') >= '$formDateSta'";
	$condition2.=" and date_format(c2.createTime,'%Y-%m') >= '$formDateSta'";
	$condition3.=" and date_format(c3.createTime,'%Y-%m') >= '$formDateSta'";
}

if(!empty($formDateEnd)){
	$condition.=" and date_format(c.createTime,'%Y-%m') <= '$formDateEnd'";
	$condition2.=" and date_format(c2.createTime,'%Y-%m') <= '$formDateEnd'";
	$condition3.=" and date_format(c3.createTime,'%Y-%m') <= '$formDateEnd'";
}
$QuerySQL = <<<QuerySQL
SELECT
c.id,c.deptId,c.deptName,
	v.deptNameS,v.deptNameT,v.deptNameF,
IF (v.levelflag=4,SUM(c.needNum),IF(v.levelflag=3,t.needNumT,s.needNumS)) AS needNum,
IF (v.levelflag=4,SUM(c.beEntryNum),IF(v.levelflag=3,t.beEntryNumT,s.beEntryNumS)) AS beEntryNum,
IF (v.levelflag=4,SUM(c.entryNum),IF(v.levelflag=3,t.entryNumT,s.entryNumS)) AS entryNum,
IF (v.levelflag=4,SUM(c.stopCancelNum),IF(v.levelflag=3,t.stopCancelNumT,s.stopCancelNumS)) AS stopCancelNum,
IF (v.levelflag=4,
	SUM(c.needNum)-SUM(c.entryNum)-SUM(c.beEntryNum)-SUM(c.stopCancelNum),
	IF(v.levelflag=3,
		t.needNumT - t.entryNumT - t.beEntryNumT - t.stopCancelNumT,
		s.needNumS - s.entryNumS - s.beEntryNumS - s.stopCancelNumS
	)
) AS ingtryNum,

IF (v.levelflag=2,s.needNumS,0) as needNumAllS,
IF (v.levelflag=3,t.needNumT,0) as needNumAllT,
IF (v.levelflag=4,SUM(c.needNum),0) AS needNumAllF,

IF (v.levelflag=2,s.entryNumS,0) as entryNumAllS,
IF (v.levelflag=3,t.entryNumT,0) as entryNumAllT,
IF (v.levelflag=4,SUM(c.entryNum),0) AS entryNumAllF,

IF (v.levelflag=2,s.beEntryNumS,0) as beEntryNumAllS,
IF (v.levelflag=3,t.beEntryNumT,0) as beEntryNumAllT,
IF (v.levelflag=4,SUM(c.beEntryNum),0) AS beEntryNumAllF,

IF (v.levelflag=2,s.stopCancelNumS,0) as stopCancelNumAllS,
IF (v.levelflag=3,t.stopCancelNumT,0) as stopCancelNumAllT,
IF (v.levelflag=4,SUM(c.stopCancelNum),0) AS stopCancelNumAllF,

((s.entryNumS + s.beEntryNumS) / (s.needNumS - s.stopCancelNumS)) as completionS,
IF (v.levelflag=3 OR v.levelflag=4 ,(t.entryNumT + t.beEntryNumT) / (t.needNumT - t.stopCancelNumT) ,'') as completionT,
IF (v.levelflag=4 ,(SUM(c.entryNum) + SUM(c.beEntryNum)) / (SUM(c.needNum) - SUM(c.stopCancelNum)) ,'') as completionF,
	v.levelflag
FROM
oa_hr_recruitment_apply c
LEFT JOIN department_view v ON c.deptId = v.deptId
LEFT JOIN (
	SELECT
		SUM(c3.needNum) AS needNumT,
		SUM(c3.beEntryNum) AS beEntryNumT,
		SUM(c3.entryNum) AS entryNumT,
		SUM(c3.stopCancelNum) AS stopCancelNumT,
		v3.deptIdT
	FROM
		oa_hr_recruitment_apply c3
	LEFT JOIN department_view v3 ON c3.deptId = v3.deptId
	WHERE
		c3.ExaStatus = '完成'
	AND c3.state IN (1, 2, 3, 4, 5, 6)
		$condition3
	GROUP BY
		v3.deptIdT
) t ON v.deptIdT = t.deptIdT
LEFT JOIN (
	SELECT
		SUM(c2.needNum) AS needNumS,
		SUM(c2.beEntryNum) AS beEntryNumS,
		SUM(c2.entryNum) AS entryNumS,
		SUM(c2.stopCancelNum) AS stopCancelNumS,
		v2.deptIdS
	FROM
		oa_hr_recruitment_apply c2
	LEFT JOIN department_view v2 ON c2.deptId = v2.deptId
	WHERE
		c2.ExaStatus = '完成'
	AND c2.state IN (1, 2, 3, 4, 5, 6)
		$condition2
	GROUP BY
		v2.deptIdS
) s ON v.deptIdS = s.deptIdS
WHERE
	c.ExaStatus = '完成'
AND c.state IN (1, 2, 3, 4, 5, 6)
	$condition
GROUP BY
	v.deptIdS,
	v.deptNameT,
	v.deptNameF
ORDER BY
	v.deptIdS ,v.levelflag ASC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL ,false);
?>
