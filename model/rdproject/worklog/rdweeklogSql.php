<?php
/*
 * Created on 2010-9-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	'base_list' => 'select c.id,c.weekTitle,c.weekBeginDate,c.weekEndDate,c.depName,c.createId,c.createName,c.updateTime from oa_rd_worklog_week c where 1=1 ',
	'checkIsSet' => "select c.id from oa_rd_worklog_week c where 1=1 ",
	'search_list' => 'select c.id,c.weekTitle,c.weekBeginDate,c.weekEndDate,c.depName,c.createName,c.updateTime,w.projectId ' .
			'from oa_rd_worklog_week c left join oa_rd_worklog w on c.id = w.weekId where 1=1 ',
	'select_inner' => 'select c.id,c.weekTitle,c.depName,w.projectId,w.executionDate,w.effortRate,w.workloadDay,w.workloadSurplus,w.projectName,w.taskName,w.updateTime,w.planEndDate,w.createName ' .
			'from oa_rd_worklog_week c inner join oa_rd_worklog w on c.id = w.weekId where 1=1 ',
	'subordinateLog' => 'select w.id,w.weekTitle,w.depName,w.updateTime,m.memberName,w.createId,w.createName from oa_rd_worklog_week w ,oa_rd_team_member m where w.createName = m.memberName ',
	'view_inproject' => 'select w.id,w.weekTitle ,w.createName,w.updateTime,w.depName,l.projectName,l.projectId from oa_rd_worklog_week w left join oa_rd_worklog l on w.id = l.weekId where 1=1 '
);

$condition_arr = array (
	array (
		"name" => "user_id",//��ͬ״̬
		"sql" => "and c.createId = # "
	),
	array (
		"name" => "startDate",//
		"sql" => "and c.weekBeginDate <= #"
	),
	array (
		"name" => "endDate",//
		"sql" => "and c.weekEndDate >= # "
	),
	array (//��ʼʱ��
		"name" => "beginDate",
		"sql" => "and c.weekEndDate >= #"
	),
	array (//����ʱ��
		"name" => "overDate",
		"sql" => "and c.weekBeginDate <= # "
	),
	array (//��ԱID
		"name" => "personIds",
		"sql" => "and c.createId in ( # ) "
	),
	array (//ִ������
		"name" => "executionDate",
		"sql" => "and w.executionDate =# "
	),
	array (//��д��
		"name" => "wcreateName",
		"sql" => "and w.createName like CONCAT('%',#,'%')"
	),
	array (//��������
		"name" => "wtaskName",
		"sql" => "and w.taskName like CONCAT('%',#,'%')"
	),
	array (//��Ŀ����
		"name" => "wprojectName",
		"sql" => "and w.projectName like CONCAT('%',#,'%')"
	),
	array(//����ID
		"name" => "departmentIds",
		"sql" => "and c.depId in ( # )"
	),array(
		"name" => "w_projectId",
		"sql" => " and w.projectId = # "
	),
	array (//��Ա��������
		"name" => "createName1",
		"sql" => " and w.createName like CONCAT('%',#,'%')"
	),
	array (//������������
		"name" => "depName1",
		"sql" => " and w.depName like CONCAT('%',#,'%')"
	),
	array(//���˼�����־
		"name" => "createName",
		"sql" => " and c.createName like CONCAT('%',#,'%')"
	),
	array (//��ԱID
		"name" => "projectId",
		"sql" => "and l.projectId = #  "
	)
);
?>
