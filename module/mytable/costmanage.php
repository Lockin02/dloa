<?php 
//ģ��id ��鿴 config.php
$pos=isset($pos)?$pos:"left";
include_once( "../includes/getUSER_DEPT_ID.php" );
?>
<script language="javascript">
    <!--//
function plan_detail(PLAN_ID)
{
 URL="work_plan/show/plan_detail.php?PLAN_ID="+PLAN_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"read_work_plan","height=400,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}      
    //-->        
</script> 
<DIV class='module'>
      <DIV class='moduleHeader' >
      <table width="100%" border="0"><tr>
            <td nowrap><span style="font-size:12px;PADDING-TOP:5px;padding-left:5px;font-weight : normal ;"><img style="border:0px" src="../images/menu/diary.gif" >��������</span></td>
            <td width="90%" class='more_info' align="right"></td>
            <td nowrap class="icon" align="left" valign="top"><span ><A href="#" onClick="resize('<?php echo $pos;?>_9')"><IMG style='border:0px' id='img_<?php echo $pos;?>_9' title=�۵� src="../images/verpic_close.gif" ></A></SPAN></td>
      </tr></table>
      </DIV>
      <DIV class='module_body' id='table_<?php echo $pos;?>_9'>
      <UL style="list-style-type:none;">
      <?php
//����������
$alltoal = 1;
 $sql="select count(distinct l.BillNo) , max(l.isproject)
from cost_summary_list l,wf_task t,flow_step_partent s
left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1  
group by FROM_ID)  powi on (  find_in_set(   powi.from_id , s.User ) > 0 )
where 
l.BillNo=t.name and 
t.task=s.Wf_task_ID and 
s.Result='' and 
l.Status='��������' and 
(
s.User like '%,".$USER_ID.",%' or 
s.User like '".$USER_ID.",%'  
or ( find_in_set( '".$_SESSION['USER_ID']."' , powi.to_ids ) > 0 )
)";
//echo $sql;
$bxds = $msql->GetRow($sql);
if($bxds['count(distinct l.BillNo)']>0)
{
    if($bxds['max(l.isproject)']=='1'){
         echo "<LI style='color:#333333'>$alltoal �����������������̣���<a href='costmanage/reim/check_summary_list_pro.php'>".$bxds["count(distinct l.BillNo)"]."</a></LI>";
    }else{
         echo "<LI style='color:#333333'>$alltoal �����������������ţ���<a href='costmanage/reim/check_summary_list.php'>".$bxds["count(distinct l.BillNo)"]."</a></LI>";
    }
    $alltoal ++;
}

$sql="select count(distinct l.BillNo) 
from cost_summary_list l,wf_task t,flow_step_partent s 
where 
l.BillNo=t.name and 
t.task=s.Wf_task_ID and 
s.Result='' and 
l.Status='�������' and 
(
s.User like '%,".$USER_ID.",%' or 
s.User like '".$USER_ID.",%'
)";
//echo $sql;
$bxds = $msql->GetRow($sql);
if($bxds['count(distinct l.BillNo)']>0)
{
    echo "<LI style='color:#333333'>$alltoal ����˱����������񣩣�<a href='costmanage/reim/check_finance_list.php'>".$bxds["count(distinct l.BillNo)"]."</a></LI>";
    $alltoal ++;
}

//��������
 $sql="select count(distinct l.ID) 
from loan_list l,wf_task t,flow_step_partent s 
left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1  
group by FROM_ID)  powi on (  find_in_set(   powi.from_id , s.User ) > 0 )
where 
l.ID=t.Code and 
t.task=s.Wf_task_ID and 
s.Result='' and 
l.Status='��������' and 
(
s.User like '%".$USER_ID."%' or ( find_in_set( '".$_SESSION['USER_ID']."' , powi.to_ids ) > 0 ) )";
//echo $sql;
$bxds = $msql->GetRow($sql);
if($bxds['count(distinct l.ID)']>0)
{
    echo "<LI style='color:#333333'>$alltoal �������������ţ���<a href='costmanage/loan/check_loan_list.php'>".$bxds["count(distinct l.ID)"]."</a></LI>";
    $alltoal ++;
}

$sql="select count(distinct l.ID) 
from loan_list l,wf_task t,flow_step_partent s 
where 
l.ID=t.Code and 
t.task=s.Wf_task_ID and 
s.Result='' and 
l.Status='�������' and 
(
    s.User like '%,".$USER_ID.",%' or 
    s.User like '".$USER_ID.",%'
)";
//echo $sql;
$bxds = $msql->GetRow($sql);
if($bxds['count(distinct l.ID)']>0)
{
    echo "<LI style='color:#333333'>$alltoal ����˽������񣩣�<a href='costmanage/loan/check_finance_list.php'>".$bxds["count(distinct l.ID)"]."</a></LI>";
    $alltoal ++;
}

//����Ա:��ɫΪ����Ա���û�����ʾ
$isTeller = $msql->getrow("select count(distinct u.USER_ID) from user u,user_priv p where u.USER_PRIV=p.USER_PRIV and p.PRIV_NAME='����' and u.USER_ID='".$USER_ID."'");
if($isTeller["count(distinct u.USER_ID)"]==1)
{
    $payLoan = $msql->getrow("select count(ID) from loan_list where Status='����֧��'");
    if($payLoan['count(ID)']>0)
    {
        echo "<LI style='color:#333333'>$alltoal ��֧������<a href='costmanage/teller/loan_list.php'>".$payLoan['count(ID)']."</a></LI>";
        $alltoal ++;
    }
    $receipt = $msql->getrow("select count(ID) from loan_list where Status='��֧��'");
    if($receipt['count(ID)']>0)
    {
        echo "<LI style='color:#333333'>$alltoal �����ջ��<a href='costmanage/teller/receipt_list.php'>".$receipt['count(ID)']."</a></LI>";
        $alltoal++;
    }
    $payCost = $msql->getrow("select count(BillNo) from cost_summary_list  where Status='���ɸ���'");
    if($payCost['count(BillNo)']>0)
    {
        echo "<LI style='color:#333333'>$alltoal �����������<a href='costmanage/teller/pay_list.php'>".$payCost['count(BillNo)']."</a></LI>";
        $alltoal ++;
    }
}
//���İ���
$sqlStr="";
//�������Ϳ���
$typeArr=array('�������ڵ���','�ݼ�����','��Ƹ����','��Ŀ����','���ݼ�','���ݼ�A');
$typeSql=" and  w.name in ('".implode("' , '",$typeArr)."')";
//������Ȩ����
$powerSql=" and ( find_in_set('".$USER_ID."',p.User)>0  ";
$sql="select FROM_ID from power_set where TO_ID='".$USER_ID."' and STATUS='1' and BEGIN_DATE <= '".getlongdate()."' and END_DATE>='".getlongdate()."'";
$msql->query($sql);
while($msql->next_record()){
    $powerSql.=" or find_in_set('".$msql->f("FROM_ID")."',p.User)>0 ";
}
$powerSql.=" ) ";
//$sql="select count(distinct w.task) from wf_task w,flow_step_partent p , user u where u.USER_ID=w.Enter_user  and   w.task=p.Wf_task_ID and p.Flag='0' and w.examines='' and w.Status='ok' $powerSql $typeSql  ";
//echo $sql;
//$crow = $msql->getrow($sql);
//$pages = $crow["count(distinct w.task)" ];
//if($pages>0){
    //echo "<LI style='color:#333333'>$alltoal ���칫�ģ�<a href='doc_manage/doc_deal/'>".$pages."</a></LI>";
    //$alltoal++;
//}
/*
$sql="select count(*) from hols where proexa='".$_SESSION['USER_ID']."'";
$msql->query($sql);
$msql->next_record();
if($msql->f('count(*)')){
    echo "<LI style='color:#333333'>$alltoal �ݼ٣�������Ŀ����<a href='doc_manage/doc_deal/'>".$msql->f('count(*)')."</a></LI>";
    $alltoal++;
}
*/
$arr=getTodoList();
if($arr&&count($arr)>0){
    echo " �з���Ŀ��";
    foreach($arr as $val){
        echo "<LI style='color:#333333'>".$val['ty']."��<a href='".$val['url']."'>".$val['amount']."</a></LI>";
    }
}
$salaryArr=array();
$sql="select count(p.id) as am
    from salary_pay p
        left join user u on (s.userid=u.user_id)
        left join department d on (u.user_id=d.dept_id)
    where
        p.pyear='".date("Y")."'
        and p.pmon='".date("m")."'
        and (p.perholsdays<>'0' or p.sickholsdays<>'0')
        and ( find_in_set('".$_SESSION['USER_ID']."',d.majorid)>0
                or ( d.majorid ='' and find_in_set('".$_SESSION['USER_ID']."',d.vicemanager)>0 )
                )
    ";
$msql->query($sql);
$msql->next_record();
if($msql->f('am')){
    $salaryArr['���ݼ�']=$msql->f('am');
}
$sql="select count(fs.id) as ids , f.flowname from salary_flow_step fs
                left join salary_flow f on (fs.salaryfid=f.id)
            where find_in_set('".$_SESSION['USER_ID']."', fs.dealuser)>0 and  fs.sta='0' group by f.flowname ";
$msql->query($sql);
while($msql->next_record()){
    $salaryArr[$msql->f('flowname')]=$msql->f('ids');
}
if(!empty($salaryArr)){
    echo ' ����������';
    foreach ($salaryArr as $key=>$val){
        if($key){
            $key=$key=='��������'?'���⽱��':$key;
            echo "<LI style='color:#333333'>".$key."��<a href='../index1.php?model=salary&action=dp'>".$val."</a></LI>";
        }
    }
}
$sql="select count(0) as num from device_apply_sim where audit_userid='" . $_SESSION['USER_ID'] . "'";
$msql->query($sql);
while($msql->next_record()){
    $device_num=$msql->f('num');
}
if(!empty($device_num)){
    echo ' <a href="../index1.php?model=device_apply&action=apply_audit_list"> �豸����������'.$device_num.'</a>';
}

$sql="( select c.name , count(c.pid) as am  , if( c.name = '������Ŀ�ܱ�����' ,'../index1.php?model=engineering_project_statusreport&action=toAuditTab','' ) as url , '�ҵ�����' as tl
		from wf_task c 
		right join  flow_step_partent p on c.task = p.wf_task_id
		left join (SELECT group_concat( to_id)  as to_ids  , from_id   FROM power_set  
where to_days(BEGIN_DATE)<=to_days(now()) and to_days( END_DATE )>=to_days(now()) and STATUS=1  
group by FROM_ID)  powi on (  find_in_set(   powi.from_id , p.User ) > 0 )
		left join user u on c.Creator = u.USER_ID 
		left join 	flow_form_type t on (c.name =t.form_name)
where
		  p.Flag = 0 AND
		  c.examines <> 'no'   and  ( (find_in_set( '".$_SESSION['USER_ID']."' , p.User ) > 0) 
      or ( find_in_set( '".$_SESSION['USER_ID']."' , powi.to_ids ) > 0 ) ) 
       and t.isdel=0
group by c.name having am>0 order by c.name ) 
union (
select '������������' as name , count(d.id) as am 
,'../index1.php?model=outsourcing_workverify_workVerify&action=toTabWorkVerify' as url , '������������' as tl
from oa_outsourcing_workverify_detail d
left join oa_outsourcing_suppverify s on s.id=d.suppVerifyId
where s.`status`=1 and ((d.projectId in (select id from oa_esm_project where  find_in_set('".$_SESSION['USER_ID']."',managerId) > 0 ) and d.managerAuditState=0)
or (d.provinceId in (select id from oa_system_province_info where find_in_set('".$_SESSION['USER_ID']."',esmManagerId) > 0) and d.managerAuditState=1 and d.serverAuditState=0)
or (d.officeId in (select id from oa_esm_office_baseinfo where find_in_set('".$_SESSION['USER_ID']."',mainManagerId) > 0) and d.managerAuditState=1 and d.serverAuditState=1 and d.areaAuditState=0)) having am>0 
)union (

select '��ְ����ȷ��' as name,count(l.id) as num,'../index1.php?model=hr_leave_handover&action=handoverAffirm' as url 
, '��ְ����ȷ��' as tl 
from oa_hr_handover_list l where  find_in_set('".$_SESSION['USER_ID']."',l.recipientId) > 0 and l.affstate=0

)union (

select '��Ʒ��ȷ�����' as name,count(c.id) as num,'../index1.php?model=contract_contract_cost&action=confirmCostAppList' as url , '��Ʒ��ȷ�����' as tl 
FROM
	oa_contract_cost c 
	LEFT JOIN purview_info i on ( FIND_IN_SET( c.productLine , i.content ) and i.typeid =299 )
WHERE
	c.ExaState = '0' and state = '1' AND i.userid = '".$_SESSION['USER_ID']."' 
)
union (

select '��Ʒ��ȷ��' as name, sum(IF(o.state = 1 or o.state=3, 0, 1)) AS num , '../index1.php?model=contract_contract_contract&action=confirmCostEstimates' as url , '��Ʒ��ȷ��' as tl 

FROM
	oa_contract_contract c
LEFT JOIN purview_info i on ( FIND_IN_SET(  i.content , exeDeptStr ) and i.typeid =298 )
LEFT JOIN oa_contract_cost o ON c.id = o.contractId and i.content=o.productLine
WHERE
	c.isEngConfirm = '1'
AND c.engConfirm = '0'
AND isSubApp = '1'
AND c.isTemp = '0' 
AND i.userid = '".$_SESSION['USER_ID']."' 

)

";

$msql->query($sql);
while($msql->next_record()){
    //$comArr[$msql->f('name')]=$msql->f('am');
    $doarr[$msql->f('name')]['am'] = $msql->f('am');
    $doarr[$msql->f('name')]['url'] = $msql->f('url');
    $doarr[$msql->f('name')]['tl'] = $msql->f('tl');
}
if(!empty($doarr)){
    echo ' <br/>ҵ��������';
    foreach ($doarr as $key=>$val){
        if($key&&!empty($val['am'])){
        	if($key=='��ͬ��ǩ'){
        		echo "<LI style='color:#333333'>".''.'<A href="../index1.php?model=hr_recontract_recontractapproval"  >'.$key.'��'.$val['am']."</a></LI>";
        	}else if($key=='���ݼ�A'){
        	 	echo "<LI style='color:#333333'>".''.'<A href="../index1.php?model=common_workflow_workflow&action=auditTab&selectedCode='.$key.'" >���ݼ� ��'.$val['am']."</a></LI>";	
        	}else{
        		$url=empty($val['url'])?'../index1.php?model=common_workflow_workflow&action=auditTab&selectedCode='.$key:$val['url'];
        	 	echo "<LI style='color:#333333'>".''.'<A href="'.$url.'"  >'.$key.'��'.$val['am']."</a></LI>";	
        	}
            
        }
    }
}
/*
if(!empty($comArr)){
    echo ' <br/>ҵ��������';
    foreach ($comArr as $key=>$val){
        if($key){
        	if($key=='��ͬ��ǩ'){
        		echo "<LI style='color:#333333'>".''.'<A href="#"  onclick="openTab(\'../index1.php?model=hr_recontract_recontractapproval\',\'��ͬ��ǩ����\',\'rep\')">'.$key.'��'.$val."</a></LI>";
        	}else if($key=='��������'){
        		echo "<LI style='color:#333333'>".''.'<A href="#"  onclick="openTab(\'../index1.php?model=info_notice&action=audit_list\',\'��������\',\'rep\')">'.$key.'��'.$val."</a></LI>";
        	}else if($key=='���ݼ�A'){
        	 	echo "<LI style='color:#333333'>".''.'<A href="#"  onclick="openTab(\'../index1.php?model=common_workflow_workflow&action=auditTab&selectedCode='.$key.'\',\'�ҵ�����\',\'rep\')">���ݼ� ��'.$val."</a></LI>";	
        	}else{
        	 	echo "<LI style='color:#333333'>".''.'<A href="#"  onclick="openTab(\'../index1.php?model=common_workflow_workflow&action=auditTab&selectedCode='.$key.'\',\'�ҵ�����\',\'rep\')">'.$key.'��'.$val."</a></LI>";	
        	}
            
        }
    }
}
*/
//

$sql2="SELECT
	COUNT(*) as am
FROM
	(
		SELECT
			c.*
		FROM
			oa_contract_contract c
		LEFT JOIN oa_contract_cost t ON c.id = t.contractId
		WHERE
			t.state = '3'
		AND c.isSubAppChange = 0
		GROUP BY
			c.id
		UNION ALL
			SELECT
				c.*
			FROM
				oa_contract_contract c
			LEFT JOIN (
				SELECT
					max(id) AS Mid,
					originalId
				FROM
					oa_contract_contract
				GROUP BY
					originalId
			) AS c1 ON c.id = c1.originalId
			LEFT JOIN oa_contract_cost t ON c1.Mid = t.contractId
			WHERE
				c.state = '3'
			AND c.isSubAppChange = 1
			GROUP BY
				c.id
			UNION ALL
				SELECT
					*
				FROM
					oa_contract_contract
				WHERE
					dealStatus = '4'
	) c
WHERE
	c.isTemp = '0'
AND c.state IN (0, 1, 2, 3, 4, 5, 6, 7)
AND c.prinvipalId = '".$_SESSION['USER_ID']."';
";


$msql->query($sql2);
while($msql->next_record()){
    $ds_app_nums=$msql->f('am');
}
if(!empty($ds_app_nums)){
    echo "<LI style='color:#333333'>".' <a href="../index1.php?model=contract_contract_contract&action=mycontract&todo" > ��������ȷ�ϣ�'.$ds_app_nums.'</a>';
}

$sql3="SELECT
	COUNT(*) as am
FROM
	oa_finance_payablesapply c
WHERE
	1
AND ((c.`status` = 'FKSQD-00'))
AND ((c.ExaStatus = '���'))
AND ((c.createId = '".$_SESSION['USER_ID']."'))";

$msql->query($sql3);
while($msql->next_record()){
    $co_app_nums=$msql->f('am');
}
if(!empty($co_app_nums)){
    echo "<LI style='color:#333333'>".' <a href="../index1.php?model=finance_payablesapply_payablesapply&action=toMyApply" > ���ύ����֧����'.$co_app_nums.'</a>';
}






$sql="select  count(a.id) as am
from device_apply_order as a									
where a.dept_id in(select  c.content
				from  purview as a 
					 left join purview_type as b on b.tid=a.id
					left join purview_info as c on c.tid=a.id and c.typeid=b.id
				where  a.models = 'device_apply'
					   and b.name='��������'
					 and c.userid='".$_SESSION['USER_ID']."')
and a.status=0";
$msql->query($sql);
while($msql->next_record()){
    $device_app_num=$msql->f('am');
}
if(!empty($device_app_num)){
    echo "<LI style='color:#333333'>".$alltoal.' <a href="../index1.php?model=device_apply&action=apply_audit_list" > ��������������'.$device_app_num.'</a>';
}
//
$sql="select count(id) as am from oa_sale_chance c
	where  c.isTemp=0  and c.status ='5' and c.predictContractDate < now()
 	and c.prinvipalId='".$_SESSION['USER_ID']."'";
$msql->query($sql);
while($msql->next_record()){
    $sale_chance=$msql->f('am');
}
if(!empty($sale_chance)){
    echo '<LI style="color:#333333"><a href="../index1.php?model=projectmanagent_chance_chance&action=toMyChanceTab" > �ѹ����̻�����'.$sale_chance.'</a>';
}
$sql="select count(*) as amt  from oa_esm_resource_apply where confirmStatus = 1 AND FIND_IN_SET('".$_SESSION['USER_ID']."','$defaultEsmResource')";
$msql->query($sql);
while($msql->next_record()){
    $deviceAmt=$msql->f('amt');
}
if(!empty($deviceAmt)){
    echo '<LI style="color:#333333"><a href="../index1.php?model=engineering_resources_resourceapply&action=waitConfirm" > ��ȷ���豸���룺'.$deviceAmt.'</a>';
}

$sqls="SELECT
	COUNT(*) AS num
FROM
	oa_borrow_return
WHERE
	disposeState = '9'
AND ExaStatus IN ('���', '����')
AND salesId = '".$_SESSION['USER_ID']."'
GROUP BY
	salesId;";
$msql->query($sqls);
while($msql->next_record()){
    $deviceAmts=$msql->f('num');
}
if(!empty($deviceAmts)){
    echo '<LI style="color:#333333"><a href="../index1.php?model=projectmanagent_borrowreturn_borrowreturn&action=myConfirmList" > ��ȷ�Ϲ黹���뵥'.$deviceAmts.'</a>';
}


$sqlAudit="select count(0) as num from appraisal_performance as a 
	  where  1   and   FIND_IN_SET('".$_SESSION['USER_ID']."',a.audit_userid) and a.inFlag<>10  and a.inFlag='5' ";
$msql->query($sqlAudit);
while($msql->next_record()){
    $AuditAmt=$msql->f('num');
}
if(!empty($AuditAmt)){
    echo '<LI style="color:#333333"><a href="../index1.php?model=administration_appraisal_performance_list&action=perManager" > ��Ч���˴���ˣ�'.$AuditAmt.'</a>';
}

$sqlAsse="select count(0) as num from appraisal_performance as a 
	  where  1   and   FIND_IN_SET('".$_SESSION['USER_ID']."',a.assess_userid) and a.inFlag<>10  and a.inFlag='4' ";
$msql->query($sqlAsse);
while($msql->next_record()){
    $AsseAmt=$msql->f('num');
}
if(!empty($AsseAmt)){
    echo '<LI style="color:#333333"><a href="../index1.php?model=administration_appraisal_performance_list&action=perManager" > ��Ч���˴����ˣ�'.$AsseAmt.'</a>';
}

$sqlEval="SELECT count(0) as num
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
				WHERE 1 AND a.kId IS NOT NULL AND( b.inFlag>2 or (b.inFlag=2 AND b.isAss<>1  )) and FIND_IN_SET('".$_SESSION['USER_ID']."',a.evaluators_userid)  ";
$msql->query($sqlEval);
while($msql->next_record()){
    $EvalAmt=$msql->f('num');
}
if(!empty($EvalAmt)){
    echo '<LI style="color:#333333"><a href="../index1.php?model=administration_appraisal_performance_list&action=perManager" > ��Ч���˴����ۣ�'.$EvalAmt.'</a>';
}

function getTodoList(){
	global $msql;
	global $USER_ID;
    $rows=array();
	/****************************����������ʹ���������******************/
	$sql="select count(Id) ,status  " .
			"from item_task i " .
			"where " .
				"( i.BurdenPeople='$USER_ID' and i.Status='����' ) or " .
				"( i.CheckPeople='$USER_ID' and i.Status='�ύ' ) group by status";
	$msql->query($sql);
	while($msql->next_record()){
	    if($msql->f('status')=="�ύ"){
			$rows[0]['ty'] = "����������";
			$rows[0]['amount'] = $msql->f('count(Id)');
			$rows[0]['url'] = "develop_manage/task/myWaiting/index.php";
	    }else if($msql->f('status')=="����"){
	    	$rows[1]['ty'] = "����������";
			$rows[1]['amount'] = $msql->f('count(Id)');
			$rows[1]['url'] = "develop_manage/task/myTask/index.php";
	    }
	}

	/*******************************����������Ŀ����*******************/
	$typeSql=" w.code in ( 'item_one','item_two','item_change') ";
	$powerSql=" and ( find_in_set('".$USER_ID."',p.User)>0  )";
	$sql = "select " .
				"count(p.Id),w.code " .
					"from " .
						"flow_step_partent p left join wf_task w on p.wf_task_id = w.task " .
						"left join user u on u.USER_ID =  p.User " .
					"where 1 and " .
						"p.flag='0' and " .
						"$typeSql $powerSql group by w.code";
	$msql->query($sql);
	while($msql->next_record()){
	    if($msql->f('code')=="item_change"){
			$rows[2]['ty'] = "�������";
			$rows[2]['amount'] = $msql->f('count(p.Id)');
			$rows[2]['url'] = "develop_manage/pj_examine/index1.php";
	    }else{
	    	$rows[3]['ty'] = "��������";
			$rows[3]['amount'] += $msql->f('count(p.Id)');
			$rows[3]['url'] = "develop_manage/pj_examine/index.php";
	    }
	}
	return array_filter($rows);
}
?> 
</UL></DIV></DIV></DIV>