<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$year = $_GET['year'];
$areaCode = $_GET['areaCode'];
$area = $_GET['area'];
$principalId = $_GET['principalId'];
$principal = $_GET['principal'];
if($areaCode != "all"){
	$areaCodeA = " and areaCode = $areaCode";
}else{
	$areaCodeA = "";
}
//������
if($principalId != ""){
	$OprincipalId = " and prinvipalId = '$principalId'";
	$SprincipalId = " and orderPrincipalId = '$principalId'";
	$LprincipalId = " and hiresId = '$principalId'";
	$RprincipalId = " and orderPrincipalId = '$principalId'";
}else{
	$OprincipalId = "";
	$SprincipalId = "";
	$LprincipalId = "";
	$RprincipalId = "";
}
//��������
$Q1 = " and isTemp = 0 and createTime >= '$year-1-1' and createTime < '$year-4-1'    $areaCodeA";
$Q2 = " and isTemp = 0 and createTime >= '$year-4-1' and createTime < '$year-7-1'    $areaCodeA";
$Q3 = " and isTemp = 0 and createTime >= '$year-7-1' and createTime < '$year-10-1'   $areaCodeA";
$Q4 = " and isTemp = 0 and createTime >= '$year-10-1' and createTime <= '$year-12-31' $areaCodeA";
//��Χ
if($areaCode == "all" && $principalId == ""){
    $limit = "��˾";
}
if($areaCode != "all" && $principalId == "" ){
    $limit =  "��������($area)";
}else if($areaCode == "all" && $principalId != "" ){
	$limit = "��ͬ������($principal)";
}else if($principalId != "" && $areaCode != ""){
    $limit = "��������($area)  ��ͬ������($principal)";
}

$QuerySQL = <<<QuerySQL
select  $year as year,"$limit" as lim,rs.orderType,rs.orderNatureName,sum(Q1Num) as Q1Num,sum(Q1Money) as Q1Money,sum(Q1TempNum) as Q1TempNum,sum(Q1TempMoney) as Q1TempMoney,sum(Q2Num) as Q2Num,sum(Q2Money) as Q2Money,sum(Q2TempNum) as Q2TempNum,sum(Q2TempMoney) as Q2TempMoney,sum(Q3Num) as Q3Num,sum(Q3Money) as Q3Money,sum(Q3TempNum) as Q3TempNum,sum(Q3TempMoney) as Q3TempMoney,sum(Q4Num) as Q4Num,sum(Q4Money) as Q4Money,sum(Q4TempNum) as Q4TempNum,sum(Q4TempMoney) as Q4TempMoney
from
(
/* ���ۺ�ͬ���� ��ʼ*/
/* ��һ����������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,a.orderNatureName,count(a.id) as Q1Num,sum(a.orderMoney) as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order a where a.orderMoney is not null and a.orderMoney <> '' and a.orderMoney <> '0' $OprincipalId and a.ExaStatus = '���'  $Q1 and a.state in ('2','3','4') and signinType <> 'service'  group by a.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,b.orderNatureName,0 as Q1Num,0 as Q1Money,count(b.id) as Q1TempNum,sum(b.orderTempMoney) as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order b where (b.orderCode is null or b.orderCode = '') $OprincipalId  and b.ExaStatus = '���'  $Q1 and b.state in ('2','3','4') and signinType <> 'service'  group by b.orderNatureName
union all
/* �ڶ�����������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,c.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,count(c.id) as Q2Num,sum(c.orderMoney) as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order c where c.orderMoney is not null and c.orderMoney <> '' and c.orderMoney <> '0' $OprincipalId  and c.ExaStatus = '���'  $Q2 and c.state in ('2','3','4') and signinType <> 'service' group by c.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,d.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,count(d.id) as Q2TempNum,sum(d.orderTempMoney) as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order d where (d.orderCode is null or d.orderCode = '') $OprincipalId and   d.ExaStatus = '���'  $Q2 and d.state in ('2','3','4') and signinType <> 'service' group by d.orderNatureName
union all
/* ��������������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,e.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,count(e.id) as Q3Num,sum(e.orderMoney) as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order e where e.orderMoney is not null and e.orderMoney <> '' and e.orderMoney <> '0' $OprincipalId  and e.ExaStatus = '���' $Q3 and e.state in ('2','3','4') and signinType <> 'service' group by e.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,f.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,count(f.id) as Q3TempNum,sum(f.orderTempMoney) as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order f where (f.orderCode is null or f.orderCode = '') $OprincipalId and  f.ExaStatus = '���'  $Q3 and f.state in ('2','3','4') and signinType <> 'service' group by f.orderNatureName
union all
/* ���ļ���������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,g.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,count(g.id) as Q4Num,sum(g.orderMoney) as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order g where g.orderMoney is not null and g.orderMoney <> '' and g.orderMoney <> '0' $OprincipalId and g.ExaStatus = '���'  $Q4 and g.state in ('2','3','4') and signinType <> 'service' group by g.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,h.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,count(h.id) as Q4TempNum,sum(h.orderTempMoney) as Q4TempMoney from oa_sale_order h where (h.orderCode is null or h.orderCode = '') $OprincipalId and  h.ExaStatus = '���'  $Q4 and h.state in ('2','3','4') and signinType <> 'service' group by h.orderNatureName
union all
 /*��ͬ����*/
select '��Ʒ���ۺ�ͬ' as orderType,sys.dataName as orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_system_datadict sys where sys.parentCode = 'XSHTSX' group by sys.dataName
/*����ת���� ������ͬ����ǩԼ�ܽ��*/
union all
select '��Ʒ���ۺ�ͬ' as orderType,sa.orderNatureName,count(sa.id) as Q1Num,sum(sa.orderMoney) as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sa where sa.orderMoney is not null and sa.orderMoney <> '' and sa.orderMoney <> '0' $SprincipalId and sa.ExaStatus = '���' $Q1 and sa.state in ('2','3','4') and signinType = 'order' group by sa.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,sb.orderNatureName,0 as Q1Num,0 as Q1Money,count(sb.id) as Q1TempNum,sum(sb.orderTempMoney) as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sb where (sb.orderCode is null or sb.orderCode = '') $SprincipalId and sb.ExaStatus = '���' and sb.state in ('2','3','4') $Q1 and signinType = 'order' group by sb.orderNatureName
union all
/* �ڶ�����������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,sc.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,count(sc.id) as Q2Num,sum(sc.orderMoney) as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sc where sc.orderMoney is not null and sc.orderMoney <> '' and sc.orderMoney <> '0' $SprincipalId and sc.ExaStatus = '���' $Q2 and sc.state in ('2','3','4') and signinType = 'order' group by sc.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,sd.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,count(sd.id) as Q2TempNum,sum(sd.orderTempMoney) as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sd where (sd.orderCode is null or sd.orderCode = '') $SprincipalId and sd.ExaStatus = '���'  $Q2 and sd.state in ('2','3','4') and signinType = 'order' group by sd.orderNatureName
union all
/* ��������������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,se.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,count(se.id) as Q3Num,sum(se.orderMoney) as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service se where se.orderMoney is not null and se.orderMoney <> '' and se.orderMoney <> '0' $SprincipalId and se.ExaStatus = '���' $Q3 and se.state in ('2','3','4') and signinType = 'order' group by se.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,sf.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,count(sf.id) as Q3TempNum,sum(sf.orderTempMoney) as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sf where (sf.orderCode is null or sf.orderCode = '') $SprincipalId and sf.ExaStatus = '���' $Q3 and sf.state in ('2','3','4') and signinType = 'order'  group by sf.orderNatureName
union all
/* ���ļ���������ͬ�� , ǩԼ��ͬ���  */
select '��Ʒ���ۺ�ͬ' as orderType,sg.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,count(sg.id) as Q4Num,sum(sg.orderMoney) as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sg where sg.orderMoney is not null and sg.orderMoney <> '' and sg.orderMoney <> '0' $SprincipalId and sg.ExaStatus = '���'  $Q4 and sg.state in ('2','3','4') and signinType = 'order' group by sg.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '��Ʒ���ۺ�ͬ' as orderType,sh.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,count(sh.id) as Q4TempNum,sum(sh.orderTempMoney) as Q4TempMoney from oa_sale_service sh where (sh.orderCode is null or sh.orderCode = '') $SprincipalId and sh.ExaStatus = '���'  $Q4 and sh.state in ('2','3','4') and signinType = 'order' group by sh.orderNatureName

/* ���ۺ�ͬ���� ����*/

/*�����ͬ����    ��ʼ*/
union all
select '���̷����ͬ' as orderType,sa.orderNatureName,count(sa.id) as Q1Num,sum(sa.orderMoney) as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sa where sa.orderMoney is not null and sa.orderMoney <> '' and sa.orderMoney <> '0' $SprincipalId and sa.ExaStatus = '���' $Q1 and sa.state in ('2','3','4') and signinType <> 'order' group by sa.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,sb.orderNatureName,0 as Q1Num,0 as Q1Money,count(sb.id) as Q1TempNum,sum(sb.orderTempMoney) as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sb where (sb.orderCode is null or sb.orderCode = '') $SprincipalId and sb.ExaStatus = '���' and sb.state in ('2','3','4') $Q1 and signinType <> 'order' group by sb.orderNatureName
union all
/* �ڶ�����������ͬ�� , ǩԼ��ͬ���  */
select '���̷����ͬ' as orderType,sc.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,count(sc.id) as Q2Num,sum(sc.orderMoney) as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sc where sc.orderMoney is not null and sc.orderMoney <> '' and sc.orderMoney <> '0' $SprincipalId and sc.ExaStatus = '���' $Q2 and sc.state in ('2','3','4') and signinType <> 'order' group by sc.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,sd.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,count(sd.id) as Q2TempNum,sum(sd.orderTempMoney) as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sd where (sd.orderCode is null or sd.orderCode = '') $SprincipalId and sd.ExaStatus = '���'  $Q2 and sd.state in ('2','3','4') and signinType <> 'order' group by sd.orderNatureName
union all
/* ��������������ͬ�� , ǩԼ��ͬ���  */
select '���̷����ͬ' as orderType,se.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,count(se.id) as Q3Num,sum(se.orderMoney) as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service se where se.orderMoney is not null and se.orderMoney <> '' and se.orderMoney <> '0' $SprincipalId and se.ExaStatus = '���' $Q3 and se.state in ('2','3','4') and signinType <> 'order' group by se.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,sf.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,count(sf.id) as Q3TempNum,sum(sf.orderTempMoney) as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sf where (sf.orderCode is null or sf.orderCode = '') $SprincipalId and sf.ExaStatus = '���' $Q3 and sf.state in ('2','3','4') and signinType <> 'order'  group by sf.orderNatureName
union all
/* ���ļ���������ͬ�� , ǩԼ��ͬ���  */
select '���̷����ͬ' as orderType,sg.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,count(sg.id) as Q4Num,sum(sg.orderMoney) as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_service sg where sg.orderMoney is not null and sg.orderMoney <> '' and sg.orderMoney <> '0' $SprincipalId and sg.ExaStatus = '���'  $Q4 and sg.state in ('2','3','4') and signinType <> 'order' group by sg.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,sh.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,count(sh.id) as Q4TempNum,sum(sh.orderTempMoney) as Q4TempMoney from oa_sale_service sh where (sh.orderCode is null or sh.orderCode = '') $SprincipalId and sh.ExaStatus = '���'  $Q4 and sh.state in ('2','3','4') and signinType <> 'order' group by sh.orderNatureName
union all
 /*��ͬ����*/
select '���̷����ͬ' as orderType,sys1.dataName as orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_system_datadict sys1 where sys1.parentCode = 'FWHTSX' group by sys1.dataName
/*���ۺ�ͬת�����ͬ*/
/* ��һ����������ͬ�� , ǩԼ��ͬ���  */
union all
select '���̷����ͬ' as orderType,a.orderNatureName,count(a.id) as Q1Num,sum(a.orderMoney) as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order a where a.orderMoney is not null and a.orderMoney <> '' and a.orderMoney <> '0' $OprincipalId and a.ExaStatus = '���'  $Q1 and a.state in ('2','3','4') and signinType = 'service'  group by a.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,b.orderNatureName,0 as Q1Num,0 as Q1Money,count(b.id) as Q1TempNum,sum(b.orderTempMoney) as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order b where (b.orderCode is null or b.orderCode = '') $OprincipalId  and b.ExaStatus = '���'  $Q1 and b.state in ('2','3','4') and signinType = 'service'  group by b.orderNatureName
union all
/* �ڶ�����������ͬ�� , ǩԼ��ͬ���  */
select '���̷����ͬ' as orderType,c.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,count(c.id) as Q2Num,sum(c.orderMoney) as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order c where c.orderMoney is not null and c.orderMoney <> '' and c.orderMoney <> '0' $OprincipalId  and c.ExaStatus = '���'  $Q2 and c.state in ('2','3','4') and signinType = 'service' group by c.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,d.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,count(d.id) as Q2TempNum,sum(d.orderTempMoney) as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order d where (d.orderCode is null or d.orderCode = '') $OprincipalId and   d.ExaStatus = '���'  $Q2 and d.state in ('2','3','4') and signinType = 'service' group by d.orderNatureName
union all
/* ��������������ͬ�� , ǩԼ��ͬ���  */
select '���̷����ͬ' as orderType,e.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,count(e.id) as Q3Num,sum(e.orderMoney) as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order e where e.orderMoney is not null and e.orderMoney <> '' and e.orderMoney <> '0' $OprincipalId  and e.ExaStatus = '���' $Q3 and e.state in ('2','3','4') and signinType = 'service' group by e.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,f.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,count(f.id) as Q3TempNum,sum(f.orderTempMoney) as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order f where (f.orderCode is null or f.orderCode = '') $OprincipalId and  f.ExaStatus = '���'  $Q3 and f.state in ('2','3','4') and signinType = 'service' group by f.orderNatureName
union all
/* ���ļ���������ͬ�� , ǩԼ��ͬ���  */
select '���̷����ͬ' as orderType,g.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,count(g.id) as Q4Num,sum(g.orderMoney) as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_order g where g.orderMoney is not null and g.orderMoney <> '' and g.orderMoney <> '0' $OprincipalId and g.ExaStatus = '���'  $Q4 and g.state in ('2','3','4') and signinType = 'service' group by g.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���̷����ͬ' as orderType,h.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,count(h.id) as Q4TempNum,sum(h.orderTempMoney) as Q4TempMoney from oa_sale_order h where (h.orderCode is null or h.orderCode = '') $OprincipalId and  h.ExaStatus = '���'  $Q4 and h.state in ('2','3','4') and signinType = 'service' group by h.orderNatureName

/*�����ͬ����    ����*/

/*�з���ͬ     ��ʼ*/
union all
select 'ί�п�����ͬ' as orderType,ra.orderNatureName,count(ra.id) as Q1Num,sum(ra.orderMoney) as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject  ra where ra.orderMoney is not null and ra.orderMoney <> '' and ra.orderMoney <> '0' $RprincipalId and ra.ExaStatus = '���'  $Q1 and ra.state in ('2','3','4')  group by ra.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select 'ί�п�����ͬ' as orderType,rb.orderNatureName,0 as Q1Num,0 as Q1Money,count(rb.id) as Q1TempNum,sum(rb.orderTempMoney) as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject rb where (rb.orderCode is null or rb.orderCode = '') $RprincipalId and rb.ExaStatus = '���'  $Q1 and rb.state in ('2','3','4') group by rb.orderNatureName
union all
/* �ڶ�����������ͬ�� , ǩԼ��ͬ���  */
select 'ί�п�����ͬ' as orderType,rc.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,count(rc.id) as Q2Num,sum(rc.orderMoney) as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject rc where rc.orderMoney is not null and rc.orderMoney <> '' and rc.orderMoney <> '0' $RprincipalId and rc.ExaStatus = '���'  $Q2 and rc.state in ('2','3','4') group by rc.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select 'ί�п�����ͬ' as orderType,rd.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,count(rd.id) as Q2TempNum,sum(rd.orderTempMoney) as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject rd where (rd.orderCode is null or rd.orderCode = '') $RprincipalId and rd.ExaStatus = '���'  $Q2 and rd.state in ('2','3','4') group by rd.orderNatureName
union all
/* ��������������ͬ�� , ǩԼ��ͬ���  */
select 'ί�п�����ͬ' as orderType,re.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,count(re.id) as Q3Num,sum(re.orderMoney) as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject re where re.orderMoney is not null and re.orderMoney <> '' and re.orderMoney <> '0' $RprincipalId and re.ExaStatus = '���'  $Q3 and re.state in ('2','3','4') group by re.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select 'ί�п�����ͬ' as orderType,rf.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,count(rf.id) as Q3TempNum,sum(rf.orderTempMoney) as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject rf where (rf.orderCode is null or rf.orderCode = '') $RprincipalId and rf.ExaStatus = '���' $Q3 and rf.state in ('2','3','4') group by rf.orderNatureName
union all
/* ���ļ���������ͬ�� , ǩԼ��ͬ���  */
select 'ί�п�����ͬ' as orderType,rg.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,count(rg.id) as Q4Num,sum(rg.orderMoney) as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_rdproject rg where rg.orderMoney is not null and rg.orderMoney <> '' and rg.orderMoney <> '0' $RprincipalId and rg.ExaStatus = '���' $Q4 and rg.state in ('2','3','4') group by rg.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select 'ί�п�����ͬ' as orderType,rh.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,count(rh.id) as Q4TempNum,sum(rh.orderTempMoney) as Q4TempMoney from oa_sale_rdproject rh where (rh.orderCode is null or rh.orderCode = '') $RprincipalId and rh.ExaStatus = '���' $Q4 and rh.state in ('2','3','4') group by rh.orderNatureName
union all
 /*��ͬ����*/
select 'ί�п�����ͬ' as orderType,sys2.dataName as orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_system_datadict sys2 where sys2.parentCode = 'YFHTSX' group by sys2.dataName

/*�з���ͬ     ����*/

/*���޺�ͬ     ��ʼ*/
union all
select '���޺�ͬ' as orderType,la.orderNatureName,count(la.id) as Q1Num,sum(la.orderMoney) as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease la where la.orderMoney is not null and la.orderMoney <> '' and la.orderMoney <> '0' $LprincipalId and la.ExaStatus = '���' $Q1 and la.state in ('2','3','4') group by la.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���޺�ͬ' as orderType,lb.orderNatureName,0 as Q1Num,0 as Q1Money,count(lb.id) as Q1TempNum,sum(lb.orderTempMoney) as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease lb where (lb.orderCode is null or lb.orderCode = '') $LprincipalId and lb.ExaStatus = '���' $Q1 and lb.state in ('2','3','4') group by lb.orderNatureName
union all
/* �ڶ�����������ͬ�� , ǩԼ��ͬ���  */
select '���޺�ͬ' as orderType,lc.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,count(lc.id) as Q2Num,sum(lc.orderMoney) as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease lc where lc.orderMoney is not null and lc.orderMoney <> '' and lc.orderMoney <> '0' $LprincipalId and lc.ExaStatus = '���' $Q2 and lc.state in ('2','3','4') group by lc.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���޺�ͬ' as orderType,ld.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,count(ld.id) as Q2TempNum,sum(ld.orderTempMoney) as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease ld where (ld.orderCode is null or ld.orderCode = '') $LprincipalId and ld.ExaStatus = '���' $Q2 and ld.state in ('2','3','4') group by ld.orderNatureName
union all
/* ��������������ͬ�� , ǩԼ��ͬ���  */
select '���޺�ͬ' as orderType,le.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,count(le.id) as Q3Num,sum(le.orderMoney) as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease le where le.orderMoney is not null and le.orderMoney <> '' and le.orderMoney <> '0' $LprincipalId and le.ExaStatus = '���' $Q3 and le.state in ('2','3','4') group by le.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���޺�ͬ' as orderType,lf.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,count(lf.id) as Q3TempNum,sum(lf.orderTempMoney) as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease lf where (lf.orderCode is null or lf.orderCode = '') $LprincipalId and lf.ExaStatus = '���' $Q3 and lf.state in ('2','3','4') group by lf.orderNatureName
union all
/* ���ļ���������ͬ�� , ǩԼ��ͬ���  */
select '���޺�ͬ' as orderType,lg.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,count(lg.id) as Q4Num,sum(lg.orderMoney) as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_sale_lease lg where lg.orderMoney is not null and lg.orderMoney <> '' and lg.orderMoney <> '0' $LprincipalId and lg.ExaStatus = '���' $Q4 and lg.state in ('2','3','4') group by lg.orderNatureName
union all
/* ��ʱ��ͬ�� , ��ʱ��ͬ��� */
select '���޺�ͬ' as orderType,lh.orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,count(lh.id) as Q4TempNum,sum(lh.orderTempMoney) as Q4TempMoney from oa_sale_lease lh where (lh.orderCode is null or lh.orderCode = '') $LprincipalId and lh.ExaStatus = '���' $Q4 and lh.state in ('2','3','4') group by lh.orderNatureName
union all
 /*��ͬ����*/
select '���޺�ͬ' as orderType,sys3.dataName as orderNatureName,0 as Q1Num,0 as Q1Money,0 as Q1TempNum,0 as Q1TempMoney,0 as Q2Num,0 as Q2Money,0 as Q2TempNum,0 as Q2TempMoney,0 as Q3Num,0 as Q3Money,0 as Q3TempNum,0 as Q3TempMoney,0 as Q4Num,0 as Q4Money,0 as Q4TempNum,0 as Q4TempMoney from oa_system_datadict sys3 where sys3.parentCode = 'ZLHTSX' group by sys3.dataName

/*���޺�ͬ  ����*/
) rs
group by rs.orderNatureName ,rs.orderType order by rs.orderType
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
