<?php
$sql_arr = array (
	"default" => "select c.id,c.name,c.code,c.start,c.Creator from
		(select c.task as id,c.Electric,c.Creator,c.Enter_user,c.name,c.code,c.infomation,c.train,c.form,
		c.secrecy,c.speed,c.sendread,c.seqn,c.Archiving,c.examines,c.`Status`,c.start,c.finish,
		c.ATTACHMENT_NAME,c.ATTACHMENT_ID,c.ATTACHMENT_COMMENT,c.Pid,c.Prcsalert,c.PassSqlCode,
		c.DisPassSqlCode,c.UpdateDT from wf_task c ) c ",
	"select_auditing" => "select p.ID as spId,c.task as taskId,c.start AS flowTime,c.infomation as objMsg,c.name as formName,c.code,c.Pid as billId,
		if(u.has_left='1', concat(u.USER_NAME,'����ְ��') , u.USER_NAME ) as userName,h.mobile AS phoneNumber,'1' as mbType 
		from wf_task c right join
		flow_step_partent p on c.task = p.wf_task_id 
		left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
			where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1  
			group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
    	left join user u on c.Creator = u.USER_ID
			LEFT JOIN oa_hr_personnel  h ON h.userAccount=u.USER_ID
    	where  p.Flag = 0 AND c.examines = ''
		and c.pid is not null  and c.pid<>''  and p.id is not null and p.id<>'' and c.task is not null and c.task<>'' 
		AND c.`name` NOT IN ('�⳥������','�������⳥��ȷ��','�ɹ�ѯ�۵�����','��ְ��������','���ÿ�������','������������','������Ŀ�ܱ�����')",
	"select_audited" => "select p.ID as spId,c.task as taskId,c.start AS flowTime,c.infomation as objMsg,c.name as formName,c.code,c.Pid as billId,
		u.USER_NAME  as userName,p.result,p.content,p.endTime,'1' as mbType
		from wf_task c right join
		flow_step_partent p on c.task = p.wf_task_id 
    left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and 1!=1  
group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
    left join user u on c.Creator = u.USER_ID 
	where	p.Flag = 1  and c.pid is not null  and c.pid<>''  and p.id is not null and p.id<>'' and c.task is not null and c.task<>''  "
);

$condition_arr = array (
	array (
		"name" => "inCode",
		"sql" => "and c.code in(arr)"
	),
	array (
		"name" => "code",
		"sql" => "and c.code = #"
	),
	array (
		"name" => "inNames",
		"sql" => "and c.name in(arr)"
	),
	array (
		"name" => "notInNames",
		"sql" => "and c.name not in(arr)"
	),
	array(
		"name" => "findFormName",//��ѯ��������
		"sql"=>" and  ( find_in_set( # , c.name ) > 0 ) "
	),
	array(
		"name" => "findInName",//������
		"sql"=>" and  ( ( find_in_set( # , p.User ) > 0 ) or ( find_in_set( # , powi.to_ids  ) > 0 )) "
	),
	array(
		"name" => "taskSearch",//�������Ų�ѯ
		"sql"=>" and c.task like concat('%',#,'%') "
	),
	array(
		"name" => "creatorNameSearch",//�ύ�˲�ѯ
		"sql"=>" and u.USER_NAME like concat('%',#,'%') "
	),
	array(
		"name" => "startSearch",//�ύʱ��
		"sql"=>" and c.start like concat('%',#,'%') "
	),
	array(
		"name" => "endTimeSearch",//����ʱ��
		"sql"=>" and p.Endtime like concat('%',#,'%') "
	),
	array(
		"name" => "objCodeSearch",//ҵ����
		"sql"=>" and c.objCode like concat('%',#,'%') "
	),
	array(
		"name" => "objNameSearch",//ҵ������
		"sql"=>" and c.objName like concat('%',#,'%') "
	),
	array(
		"name" => "objCustomerSearch",//ҵ��ͻ�
		"sql"=>" and c.objCustomer like concat('%',#,'%') "
	),
	array(
		"name" => "objAmountSearch",//ҵ����
		"sql"=>" and c.objAmount like concat('%',#,'%') "
	),
	array(
		"name" => "infomationSearch",//��չ��Ϣ
		"sql"=>" and c.infomation like concat('%',#,'%') "
	),
	array(
		"name" => "formName",//�ύʱ��
		"sql"=>" and c.name = # "
	),
	array(
		"name" => "formNames",//�ύʱ��
		"sql"=>" and c.name in(arr) "
	),
	array(
		"name" => "saleNotChange",//���ۺ�ͬ�Ǳ����������
		"sql" => " and (select count(ch.tempId) from oa_sale_order_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "saleIsChange",//���ۺ�ͬ�����������
		"sql" => " and (select count(ch.tempId) from oa_sale_order_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "serviceNotChange",//�����ͬ�Ǳ����������
		"sql" => " and (select count(ch.tempId) from oa_sale_service_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "serviceIsChange",//�����ͬ�����������
		"sql" => " and (select count(ch.tempId) from oa_sale_service_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "rentalNotChange",//���޺�ͬ�Ǳ����������
		"sql" => " and (select count(ch.tempId) from oa_sale_lease_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "rentalIsChange",//���޺�ͬ�����������
		"sql" => " and (select count(ch.tempId) from oa_sale_lease_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "rdprojectNotChange",//�з���ͬ�Ǳ����������
		"sql" => " and (select count(ch.tempId) from oa_sale_rdproject_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "rdprojectIsChange",//�з���ͬ�����������
		"sql" => " and (select count(ch.tempId) from oa_sale_rdproject_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "purchasecontractNotChange",//�ɹ������Ǳ����������
		"sql" => " and (select count(ch.tempId) from oa_purchase_contract_changlog ch where c.Pid = ch.tempId group by ch.tempId) is null "
	),
	array(
		"name" => "purchasecontractIsChange",//�ɹ����������������
		"sql" => " and (select count(ch.tempId) from oa_purchase_contract_changlog ch where c.Pid = ch.tempId group by ch.tempId) = 1 "
	),
	array(
		"name" => "startTime",//�ύʱ��
		"sql"=>" and c.start >from_unixtime(#) "
	),
	array(
		"name" => "endTime",//�ύʱ��
		"sql"=>" and p.endTime <from_unixtime(#) "
	),
	array(
		"name" => "endTimes",//�ύʱ��
		"sql"=>" and p.endTime >from_unixtime(#) "
	)
);
?>
