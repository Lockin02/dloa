<?php
$sql_arr = array (
	"select_one" =>
	 "select i.id,i.itemId as projectCode,i.name as projectName,'-1' as groupId,'YLXM' as projectType,'XMYXJG' as projectLevel,i.remark as description,i.PrincipalId as managerId,u1.user_name as managerName,
	 i.applyId as updateId,u2.user_name as updateName,i.applyId as createId,u2.user_name as createName,Date as createTime,Date as updateTime,
	 Date as planDateStart,'0000-00-00' as planDateClose,Date as actBeginDate,'0000-00-00' as actEndDate,
		case i.ExaStatus
			when '������' then '���ύ'
			when '��������' then '��������'
			when '���' then '���'
			when '�Ѵ��' then '���'
			else '���' end as ExaStatus,
		case i.ExaStatus
			when '������' then '1'
			when '��������' then '2'
			when '���' then '4'
			when '�Ѵ��' then '4'
			when '�ر�' then '8'
			else '7' end as status,
		i.ExaDT,100 as effortRate,i.ChangeReason as closeDescription from item_one i,user u1,user u2 where i.PrincipalId=u1.user_id and i.applyId=u2.user_id ",

	"select_two" => "select i.id,i.itemId as projectCode,i.name as projectName,'-1' as groupId,'ELXM' as projectType,'XMYXJG' as projectLevel,i.remark as description,i.PrincipalId as managerId,u1.user_name as managerName,
	 i.applyId as updateId,u2.user_name as updateName,i.applyId as createId,u2.user_name as createName,Date as createTime,Date as updateTime,
	 Date as planDateStart,'0000-00-00' as planDateClose,Date as actBeginDate,'0000-00-00' as actEndDate,
		case i.ExaStatus
			when '������' then '���ύ'
			when '��������' then '��������'
			when '���' then '���'
			when '�Ѵ��' then '���'
			else '���' end as ExaStatus,
		case i.ExaStatus
			when '������' then '1'
			when '��������' then '2'
			when '���' then '4'
			when '�Ѵ��' then '4'
			when '�ر�' then '8'
			else '7' end as status,
		i.ExaDT,100 as effortRate,ChangeReason as closeDescription from item_two i,user u1,user u2 where i.PrincipalId=u1.user_id and i.applyId=u2.user_id ",

	"select_three" => "select i.id,i.itemId as projectCode,i.name as projectName,'-1' as groupId,'SLXM' as projectType,'XMYXJG' as projectLevel,i.remark as description,i.PrincipalId as managerId,u1.user_name as managerName,
	 i.applyId as updateId,u2.user_name as updateName,i.applyId as createId,u2.user_name as createName,Date as createTime,Date as updateTime,
	 Date as planDateStart,'0000-00-00' as planDateClose,Date as actBeginDate,'0000-00-00' as actEndDate,
		case i.ExaStatus
			when '������' then '���ύ'
			when '��������' then '��������'
			when '���' then '���'
			when '�Ѵ��' then '���'
			else '���' end as ExaStatus,
		case i.ExaStatus
			when '������' then '1'
			when '��������' then '2'
			when '���' then '4'
			when '�Ѵ��' then '4'
			when '�ر�' then '8'
			else '7' end as status,
		i.ExaDT,100 as effortRate,ChangeReason as closeDescription from item_three i,user u1,user u2 where i.PrincipalId=u1.user_id and i.applyId=u2.user_id ",

);
?>

