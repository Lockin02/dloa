<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$beginYear=$_GET['beginYear'];
$endYear=$_GET['endYear'];
$suppName=$_GET['suppName'];
$condition="";
if(!empty($beginYear)){
	$condition.= ' and c.assesYear >= "'.$beginYear.'" ';
}
if(!empty($endYear)){
	$condition.= ' and c.assesYear <= "'.$endYear.'" ';
}
if(!empty($suppName)){
	$condition.= " and c.suppName like CONCAT('%','".$suppName."','%') ";
}
$QuerySQL = <<<QuerySQL
select c.id ,c.suppId ,c.suppName ,c.formCode,c.formDate,c.assesYear as formYear,
c.suppLinkName ,c.suppAddress ,c.suppTel ,c.mainProduct,
fs.id as FSid,if(fs.totalNum>0,CONCAT(fs.totalNum,'(',fs.suppGrade,')'),'') as FSnum,
ss.id as SSid,if(ss.totalNum>0,CONCAT(ss.totalNum,'(',ss.suppGrade,')'),'') as SSnum,
ts.id as TSid,if(ts.totalNum>0,CONCAT(ts.totalNum,'(',ts.suppGrade,')'),'') as TSnum,
ds.id as DSid,if(ds.totalNum>0,CONCAT(ds.totalNum,'(',ds.suppGrade,')'),'') as DSnum,
ns.id as NSid,if(ns.totalNum>0,CONCAT(ns.totalNum,'(',ns.suppGrade,')'),'') as NSnum,
case ns.totalNum
   when ifnull(ns.totalNum,0)   then (ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/if((4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>0,(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0))),1)*0.6+ns.totalNum*0.4
	 else (ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))
end as yearNum,
case ns.totalNum
		when ifnull(ns.totalNum,0)
			then (case
					when ((ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/if((4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>0,(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0))),1)*0.6+ns.totalNum*0.4 )>90 then "A"
					when ((ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/if((4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>0,(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0))),1)*0.6+ns.totalNum*0.4 )>75 then "B"
					when ((ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/if((4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>0,(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0))),1)*0.6+ns.totalNum*0.4 )>60 or
							((ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/if((4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>0,(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0))),1)*0.6+ns.totalNum*0.4 )=60		then "C"
					else "D"
				end
				)
		else (case
			when (ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>90 then "A"
			when (ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>75 then "B"
			when (ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))>60 or
					(ifnull(fs.totalNum,0)+ifnull(ss.totalNum,0)+ifnull(ts.totalNum,0)+ifnull(ds.totalNum,0))/(4-(if(fs.totalNum is null,1,0))-(if(ds.totalNum is null,1,0))-(if(ss.totalNum is null,1,0))-(if(ts.totalNum is null,1,0)))=60		then "C"
			else "D"
		end
	)
end as yearGrade
  from oa_supp_suppasses c
		left join (select f.id,f.suppId,f.assesYear as formYear,f.totalNum,f.suppGrade from oa_supp_suppasses f where f.assessType='gysjd' and f.ExaStatus='完成' and f.assesQuarter=1)fs
						on fs.suppId=c.suppId  and fs.formYear=c.assesYear
		left join (select s.id,s.suppId,s.assesYear as formYear,s.totalNum,s.suppGrade from oa_supp_suppasses s where s.assessType='gysjd' and s.ExaStatus='完成' and s.assesQuarter=2)ss
						on ss.suppId=c.suppId  and ss.formYear=c.assesYear
		left join (select t.id,t.suppId,t.assesYear as formYear,t.totalNum,t.suppGrade from oa_supp_suppasses t where t.assessType='gysjd' and t.ExaStatus='完成' and t.assesQuarter=3)ts
						on ts.suppId=c.suppId  and ts.formYear=c.assesYear
		left join (select d.id,d.suppId,d.assesYear as formYear,d.totalNum,d.suppGrade from oa_supp_suppasses d where d.assessType='gysjd' and d.ExaStatus='完成' and d.assesQuarter=4)ds
						on ds.suppId=c.suppId  and ds.formYear=c.assesYear
		left join (select n.id,n.suppId,n.assesYear as formYear,n.totalNum,n.suppGrade from oa_supp_suppasses n where n.assessType='gysnd' and n.ExaStatus='完成' )ns
						on ns.suppId=c.suppId  and ns.formYear=c.assesYear
 where  (c.assessType='gysjd' or c.assessType='gysnd') and c.ExaStatus='完成' $condition
group by c.suppId,c.assesYear
order by c.suppId,c.assesYear DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
