<?php

class model_cost_stat_finance extends model_base {

    public $db;
    public $xls;
    public $nowm;

    //*******************************构造函数***********************************

    function __construct() {
        parent::__construct();
        $this->db = new mysql();
        $this->nowm=date('n');
    }
    
    function model_account(){
        @extract ( $_GET );
        @extract ( $_POST );
        $data=array();
        if($seatype=='cost'){
            $res='<tr class="ui-widget-content jqgrow ui-row-ltr"  style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">员工</td>
                        <td>员工部门</td>
                        <td>费用归属部门</td>
                        <td>单号</td>
                        <td>时间</td>
                        <td>地址</td>
                        <td>事由</td>
                        <td>项目信息</td>
                        <td>金额</td>
                    </tr>';
            //员工	报销人员部门	费用归属部门	单号	时间	事由	项目信息	项目编号	金额
            /*
            $sql="select 
                    u.user_name , d.dept_name , l.costbelongtodeptids 
                    , l.billno , l.costdates , l.costclienttype
                    , l.projectno , x.name as prona 
                    , l.amount , group_concat(a.place) plc
                from cost_summary_list l
                    left join user u on (l.costman=u.user_id)
                    left join department d on (u.dept_id=d.dept_id)
                    left join xm_lx x on (l.projectno=x.projectno)
                    left join cost_detail_assistant a on (l.billno=a.billno)
                where 
                    to_days(paydt)>=to_days('".$seadtb."')
                    and to_days(paydt)<=to_days('".$seadte."') 
                    and u.company='".$_SESSION['COM_BRN_PT']."'
                    and l.status='完成' 
                    group by l.billno
                    order by l.billno
                     ";
               
        	$sql = "select
		             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		        where 
		            to_days(paydt)>=to_days('".$seadtb."')
                    and to_days(paydt)<=to_days('".$seadte."') 
		            and a.billno=d.billno
                    and l.status='完成' 
		            group by
			            d.ID,l.CostMan
			        ORDER BY  a.CostDateBegin DESC,l.CostMan,t.CostTypeID";
			        */
			$sql = "(select
		              sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status ,bi.namecn as bi
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		            left join branch_info bi on (bi.namept=u.company)
		        where
		            a.billno=d.billno  
		            and to_days(l.paydt)>=to_days('".$seadtb."')
                    and to_days(l.paydt)<=to_days('".$seadte."') 
                    and u.company='".$_SESSION['USER_COM']."' 		
		            and a.billno=d.billno
                    and l.status='完成' 
		        group by
		            l.billno , d.costtypeid )UNION(select
		              sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status ,bi.namecn as bi
		        from
		            cost_detail_project d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.costbelongtodeptids=dp.dept_name)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		            left join branch_info bi on (bi.namept=u.company)
		        where
		            a.billno=d.billno  
		            and to_days(l.paydt)>=to_days('".$seadtb."')
                    and to_days(l.paydt)<=to_days('".$seadte."')
                     and u.company='".$_SESSION['USER_COM']."' 
		            and a.billno=d.billno
                    and l.status='完成' 
		        group by
		            l.billno , d.costtypeid )";         
            $query = $this->db->query($sql);
         while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $data[$row['bn']]['bi'] = $row['bi'];
            $data[$row['bn']]['reson']= $row['costclienttype'];
            $data[$row['bn']]['dts']= $row['costdates'];
            $data[$row['bn']]['dpu']= $row['dpu'];
            $data[$row['bn']]['dp']= $row['dp'];
            $data[$row['bn']]['pro']= $row['projectno'];
            $data[$row['bn']]['Place']= $data[$row['bn']]['Place'].';'.$row['Place'];
            $data[$row['bn']]['sts']= $row['status'];
            if($row['CostDateBegin']==$row['CostDateEnd']){
            	 $data[$row['bn']]['bet']= $data[$row['bn']]['bet'].';'.$row['CostDateBegin'];
            }else{
            	 $data[$row['bn']]['bet']=  $data[$row['bn']]['bet'].';'.$row['CostDateBegin'].'~'.$row['CostDateEnd'];
            }
           
            $cost_type[$row['ct']] = $row['cn'];
            $data_type[$row['bn']]=isset($data_type[$row['bn']])?$data_type[$row['bn']]+$row['sm']:$row['sm'];
            $data_all[$row['ct']]=isset($data_all[$row['ct']])?$data_all[$row['ct']]+$row['sm']:$row['sm'];
        }
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>公司</td>
            <td>报销人部门</td>
            <td>费用归属部门</td>
            <td>事由</td>
            <td>地址</td>
            <td>项目信息</td>
            <td>报销时间</td>
            <td>状态</td>
          ';
        foreach ((array)$cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='<td>小计</td>';
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr class="ui-widget-content jqgrow ui-row-ltr"  style="background: #F3F3F3;">';
            } else {
                $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['bi'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dpu'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['reson'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['Place'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['pro'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['bet'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['sts'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
               // $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
                $res.='<td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo='.$key.'&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">' . num_to_maney_format($val[$vkey])  . '</a></td>';
     
            }
              $res.='<td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo='.$key.'&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">' .  num_to_maney_format($data_type[$key]) . '</a></td>';
     
           // $res.='<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res.='</tr>';
            $i++;
        }
        $res.='<tr  class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="10">小计：</td>';
        foreach ((array)$cost_type as $key => $val) {
           
             $res.='<td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo='.$key.'&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">' . num_to_maney_format($data_all[$key]) . '</a></td>';
        }
        $res.='<td>'.num_to_maney_format(array_sum((array)$data_all)).'</td>
            </tr>';
         
            
              /*
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['billno']]['u']=$row['user_name'];
                $data[$row['billno']]['d']=$row['dept_name'];
                $data[$row['billno']]['td']=$row['costbelongtodeptids'];
                $data[$row['billno']]['dt']=$row['costdates'];
                $data[$row['billno']]['rea']=$row['costclienttype'];
                $data[$row['billno']]['pro']=$row['projectno'].$row['prona'];
                $data[$row['billno']]['am']=$row['amount'];
                $data[$row['billno']]['plc']=$row['plc'];
            }
             if(!empty($data)){
                $i=0;
                foreach($data as $key => $val){
                    $i++;
                    if ($i % 2 == 1) {
                        $res.='<tr style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr style="background: #FFFFFF;">';
                    }
                    $res.='
                            <td style="height: 19px;">'.$val['u'].'</td>
                            <td>'.$val['d'].'</td>
                            <td>'.$val['td'].'</td>
                            <td>'.$key.'</td>
                            <td width="120">'.$val['dt'].'</td>
                            <td width="120">'.$val['plc'].'</td>
                            <td>'.$val['rea'].'</td>
                            <td>'.$val['pro'].'</td>
                            <td><a href="index1.php?model=cost_stat_finance&action=account_detail&billNo='.$key.'&TB_iframe=true&amp;height=500&amp;width=820" class="thickbox">'.$val['am'].'</a></td>
                        </tr>';
                }
            }
            */
        }elseif($seatype=='bill'){
            $res='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">制单人</td>
                        <td>流水号</td>
                        <td>记账日期</td>
                        <td>部门</td>
                        <td>款项内容</td>
                        <td>费用类型</td>
                        <td>科目类型</td>
                        <td>项目信息</td>
                        <td>合同信息</td>
                        <td>金额</td>
                    </tr>';
            //制单人	流水号	记账日期	部门	款项内容	费用类型	科目类型	项目信息	合同信息	金额
            $sql="select 
                    u.user_name , l.serialno , tallydt 
                    , dt.dept_name , l.content , ct.costtypename as ctname
                    , bt.name as btname , d.proname , d.prono
                    , d.contname , d.contno , d.amount , d.id as billno
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join user u on (l.inputman=u.user_id)
                    left join department dt on (d.billdept=dt.dept_id)
                    left join cost_type ct on (d.costtypeid=ct.costtypeid)
                    left join bill_type bt on (d.billtypeid=bt.id)
                where 
                    to_days(tallydt)>=to_days('".$seadtb."')
                    and to_days(tallydt)<=to_days('".$seadte."') 
                    and u.company='".$_SESSION['USER_COM']."'
		           
                    and l.status='完成' 
                    order by d.id ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['billno']]['u']=$row['user_name'];
                $data[$row['billno']]['s']=$row['serialno'];
                $data[$row['billno']]['td']=$row['tallydt'];
                $data[$row['billno']]['dt']=$row['dept_name'];
                $data[$row['billno']]['con']=$row['content'];
                $data[$row['billno']]['ct']=$row['ctname'];
                $data[$row['billno']]['bt']=$row['btname'];
                $data[$row['billno']]['pro']=$row['prono'].$row['proname'];
                $data[$row['billno']]['cont']=$row['contno'].$row['contname'];
                $data[$row['billno']]['am']=$row['amount'];
            }
            if(!empty($data)){
                $i=0;
                foreach($data as $key => $val){
                    $i++;
                    if ($i % 2 == 1) {
                        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                    }
                    $res.='
                            <td style="height: 19px;">'.$val['u'].'</td>
                            <td>'.$val['s'].'</td>
                            <td>'.$val['td'].'</td>
                            <td>'.$val['dt'].'</td>
                            <td width="120">'.$val['con'].'</td>
                            <td>'.$val['ct'].'</td>
                            <td>'.$val['bt'].'</td>
                            <td>'.$val['pro'].'</td>
                            <td>'.$val['cont'].'</td>
                            <td>'.$val['am'].'</td>
                        </tr>';
                }
            }
        }
        return $res;
    }
    function model_account_detail(){
    	$billNo=$_POST['billNo']?$_POST['billNo']:$_GET['billNo'];
    	if($billNo){
    		$sql="(SELECT d.ID, t.CostTypeName,t.ParentCostTypeID,d.CostTypeID,sum(d.CostMoney*d.days) as acount
				 			, group_concat(d.remark separator '；') as rmk
				   FROM cost_detail_project d , cost_type t , cost_detail_assistant a
				   WHERE a.id=d.assid AND a.BillNo='$billNo'
									AND d.CostTypeID=t.CostTypeID  
					GROUP BY d.CostTypeID
					)UNION 
					(SELECT d.ID, t.CostTypeName,t.ParentCostTypeID,d.CostTypeID,sum(d.CostMoney*d.days) as acount
									, group_concat(d.remark separator '；') as rmk
					FROM cost_detail d , cost_type t , cost_detail_assistant a
					WHERE a.id=d.assid and a.BillNo='$billNo'
							 AND d.CostTypeID=t.CostTypeID  
					     GROUP BY  d.CostTypeID
					) 
					ORDER BY ParentCostTypeID";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['ID']]['CostTypeName']=$row['CostTypeName'];
                $data[$row['ID']]['acount']=$row['acount'];
                $data[$row['ID']]['rmk']=$row['rmk'];
            }
             $str='<tr style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">类型</td>
                        <td>金额</td>
                        <td>备注</td>
                    </tr>';
    		if($data&&is_array($data)){
    			$i=0;
    			foreach($data as $key=>$val){
    				$i++;
                    if ($i % 2 == 1) {
                        $str.='<tr style="background: #F3F3F3;">';
                    } else {
                        $str.='<tr style="background: #FFFFFF;">';
                    }
    				 $str.='
                            <td style="height: 20px;color:#000;text-align: left; width:25%">'.$val['CostTypeName'].'</td>
                            <td style="height: 20px;color:#000;text-align: left;width:25%">'.$val['acount'].'</td>
                            <td style="height: 20px;color:#000;text-align: left;width:40%">'.$val['rmk'].'</td>
                        </tr>';
    			}
    			
    		}
    		
    	}
    	     return $str;
    	
    }

    /**
     * 读取各部门信息
     */
    function model_dept_data($flag='',$xls=false) {
        @extract ( $_GET );
        @extract ( $_POST );
        $sqlStr='';
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seayear=$seayear?$seayear:date('Y');
        $nowm = $seayear<date('Y')?12:date('n');
        $this->nowm=$nowm;
        $ckb=1;
        $cke=$this->nowm;
        if($seatype=='bill'||$seatype=='all'){//非报销统计
            if($seadept){
                $sqlStrB.=" and dp.dept_name like '%".$seadept."%' ";
            }
            if($seamonb){
                $ckb=$seamonb;
                $sqlStrB.=" and month(l.inputdt)>='".$seamonb."' ";
            }
            if($seamone){
                $cke=$seamone;
                $sqlStrB.=" and month(l.inputdt)<='".$seamone."' ";
            }
            if($seayear==date('Y')&&$cke>date('n')){
                $cke=date('n');
                $this->nowm=$cke;
            }
            if($seayear){
              $sqlStrB.=" and year(l.inputdt)='".$seayear."' ";
            }
        }
        if($seatype=='cost'||$seatype=='all'){
            if($seadept){
                $sqlStr.=" and l.costbelongtodeptids like '%".$seadept."%' ";
            }
            if($seamonb){
                $ckb=$seamonb;
                $sqlStr.=" and month(l.paydt)>='".$seamonb."' ";
            }
            if($seamone){
                $cke=$seamone;
                $sqlStr.=" and month(l.paydt)<='".$seamone."' ";
            }
            if($seayear==date('Y')&&$cke>date('n')){
                $cke=date('n');
                $this->nowm=$cke;
            }
            if($seayear){
                $sqlStr.=" and year(l.paydt)='".$seayear."' ";
            }
            if($combrn){
            	 $sqlStr.=" and (u.company ='".$combrn."' or l.belongtocom ='".$combrn."' or l.CostBelongComId ='".$combrn."') ";
            }
        }
        if($seatype=='cost'||$seatype=='all'){//报销费用统计
            //部门报销-区分PK
            $sql = "(select
                    t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                    , l.costbelongtodeptids as cbd  , d.costtypeid as dct
                from
                    cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                    left join  cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list l on ( d.billno=l.billno )
                    left join user u on (l.costman=u.user_id)
                    left join department dp on (u.dept_id=dp.dept_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    and  year(l.paydt)='".$seayear."'
                    and l.projectno not like 'PK%'
                    $sqlStr
                group by  l.costbelongtodeptids , left(l.paydt,7) , d.costtypeid
                order by t.parentcosttypeid , l.costbelongtodeptids 
                )union (
                select
                    t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                    , 'PK项目'  as cbd  , d.costtypeid as dct
                from
                    cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                    left join  cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list l on ( d.billno=l.billno )
                    left join user u on (l.costman=u.user_id)
                    left join department dp on (u.dept_id=dp.dept_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    and  year(l.paydt)='".$seayear."'
                    and l.projectno like 'PK%'
                    $sqlStr
                group by  left(l.paydt,7) , d.costtypeid
                order by t.parentcosttypeid , l.costbelongtodeptids 
                )";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $row['ct']=$row['dct'];
                $data[$row['cbd']][$row['mon']][$row['ct']] =isset($data[$row['cbd']][$row['mon']][$row['ct']])?$data[$row['cbd']][$row['mon']][$row['ct']]+$row['sm']:$row['sm'];
                $cost_type[$row['ct']] = 1;
                $total[$row['cbd']][$row['ct']] = isset($total[$row['cbd']][$row['ct']]) ? $total[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
                $total_all[$row['ct']] = isset($total_all[$row['ct']]) ? $total_all[$row['ct']] + $row['sm'] : $row['sm'];
                $total_mon[$row['cbd']][$row['mon']] = isset($total_mon[$row['cbd']][$row['mon']]) ? $total_mon[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            }
            //项目报销
            $sql = "select
                    t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                    ,  if(l.projectno like 'PK%','PK项目','网优工程部（项目）')  as cbd , d.costtypeid as dct
                from
                    cost_detail_project d left join cost_type t on ( d.costtypeid=t.costtypeid)
                    left join  cost_detail_assistant a on (  a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list l on ( d.billno=l.billno )
                    left join user u on (l.costman=u.user_id)
                    left join department dp on (u.dept_id=dp.dept_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    and  year(l.paydt)='".$seayear."'
                    $sqlStr
                group by l.billno , l.costbelongtodeptids , left(l.paydt,7) , d.costtypeid
                order by t.parentcosttypeid ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $row['ct']=$row['dct'];
                $data[$row['cbd']][$row['mon']][$row['ct']] =isset($data[$row['cbd']][$row['mon']][$row['ct']])?$data[$row['cbd']][$row['mon']][$row['ct']]+$row['sm']:$row['sm'];
                $cost_type[$row['ct']] = 1;
                $total[$row['cbd']][$row['ct']] = isset($total[$row['cbd']][$row['ct']]) ? $total[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
                $total_all[$row['ct']] = isset($total_all[$row['ct']]) ? $total_all[$row['ct']] + $row['sm'] : $row['sm'];
                $total_mon[$row['cbd']][$row['mon']] = isset($total_mon[$row['cbd']][$row['mon']]) ? $total_mon[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            }
        }
        if(($seatype=='bill'||$seatype=='all')&&($combrn==''||$combrn=='dl')){//非报销统计
            if(empty($seabilltype)){
                $sql="select
                    t.parentcosttypeid as ct , month(l.inputdt) as mon , sum(d.days*d.amount) as sm , t.costtypename as cn
                    , dp.dept_name as cbd , d.costtypeid as dct
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                where
                    l.status='完成'
                    $sqlStrB
                    group by d.billdept , left(l.inputdt,7) , d.costtypeid
                    order by dp.dept_name ";
            }else{
                $sql="select
                    t.parentcosttypeid as ct , month(l.inputdt) as mon , sum(d.days*d.amount) as sm , t.costtypename as cn
                    , dp.dept_name as cbd , d.costtypeid as dct
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                where
                    l.status='完成' and d.billtypeid='".$seabilltype."'
                    $sqlStrB
                    group by d.billdept , left(l.inputdt,7) , d.costtypeid
                    order by dp.dept_name ";
            }
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $row['ct']=$row['dct'];
                $data[$row['cbd']][$row['mon']][$row['ct']] =isset($data[$row['cbd']][$row['mon']][$row['ct']])?$data[$row['cbd']][$row['mon']][$row['ct']]+$row['sm']:$row['sm'];
                if(!empty($row['ct'])){
                     $cost_type[$row['ct']] = 1;
                }
                $total[$row['cbd']][$row['ct']] = isset($total[$row['cbd']][$row['ct']]) ? $total[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
                $total_all[$row['ct']] = isset($total_all[$row['ct']]) ? $total_all[$row['ct']] + $row['sm'] : $row['sm'];
                $total_mon[$row['cbd']][$row['mon']] = isset($total_mon[$row['cbd']][$row['mon']]) ? $total_mon[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            }
        }
        if(!empty($cost_type)){
            $sql = "select costtypeid as ct , costtypename as cn
                from cost_type
                where
                    costtypeid in (" . implode(',', array_keys($cost_type)) . ")
                ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $cost_type[$row['ct']] = $row['cn'];
            }
        }
        $res.='<tr style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;">部门</td>
                    <td>月份</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</a></td>';
        }
        $res.='     <td>合计</td>
                </tr>';
        $cki = 0;
        foreach ($data as $key => $val) {
            $cki++;
            $si = 0;
            for($i=1;$i<=$this->nowm;$i++){
                if($i>=$ckb&&$i<=$cke){
                    if ($si % 2 == 1) {
                        $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                    }
                    if ($si == 0) {
                        $res.='<td id="head_' . $cki . '" rowspan="' . ($cke-$ckb+1+1) . '" style="text-align: center;">' . $key . '</td>';
                    }
                    $res.='<td height="20" style="text-align: center;"><a href="javascript:newParentTab(\''.$key.'\',\''.$seayear.'\',\''.$i.'\',\'\',\''.$seatype.'\',\''.$seabilltype.'\',\''.$combrn.'\');">' . $i . '月</a></td>';
                    foreach($cost_type as $vkey=>$vval){
                        if($xls){
                            $res.='<td>' . num_to_maney_format($val[$i][$vkey]) . '</td>';//$seabilltype
                        }else{
                            $res.='<td><a href="javascript:newParentTab(\''.$key.'\',\''.$seayear.'\',\''.$i.'\',\''.$vkey.'\',\''.$seatype.'\',\''.$seabilltype.'\',\''.$combrn.'\');">' . num_to_maney_format($val[$i][$vkey]) . '</a></td>';
                        }
                    }
                    $res.='<td>' . num_to_maney_format($total_mon[$key][$i]) . '</td>';
                    $si++;
                    $res.='</tr>';
                }
                
            }
            $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #2E982E;color:#FFFFFF;">
                <td height="20" style="text-align: center;">小计</td>';
            foreach($cost_type as $vkey=>$vval){
                    $res.='<td>' . num_to_maney_format($total[$key][$vkey]) . '</td>';
            }
            $res.='
                <td>' . num_to_maney_format(array_sum($total[$key])) . '</td>
              </tr>
                ';
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #CCCCFF;">
                <td height="28" style="text-align: center;" colspan="2">合计</td>';
        foreach($cost_type as $vkey=>$vval){
                    $res.='<td>' . num_to_maney_format($total_all[$vkey]) . '</td>';
        }
        $res.='
                <td>' . num_to_maney_format(array_sum($total_all)) . '</td>
              </tr>';
        return $res;
    }

    /**
     * 读取各部门信息
     */
    function model_dept_fn_data($flag='',$xls=false) {
        @extract ( $_GET );
        @extract ( $_POST );
        $sqlStr='';
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seayear=$seayear?$seayear:date('Y');
        $nowm = $seayear<date('Y')?12:date('n');
        $this->nowm=$nowm;
        $ckb=1;
        $cke=$this->nowm;
        $seatype='all';
        if($seatype=='bill'||$seatype=='all'){//非报销统计
            if($seadept){
                $sqlStrB.=" and dp.dept_name like '%".$seadept."%' ";
            }
            if($seamonb){
                $ckb=$seamonb;
                $sqlStrB.=" and month(l.inputdt)>='".$seamonb."' ";
            }
            if($seamone){
                $cke=$seamone;
                $sqlStrB.=" and month(l.inputdt)<='".$seamone."' ";
            }
            if($seayear==date('Y')&&$cke>date('n')){
                $cke=date('n');
                $this->nowm=$cke;
            }
        }
        if($seatype=='cost'||$seatype=='all'){
            if($seadept){
                $sqlStr.=" and l.costbelongtodeptids like '%".$seadept."%' ";
            }
            if($seamonb){
                $ckb=$seamonb;
                $sqlStr.=" and month(l.paydt)>='".$seamonb."' ";
            }
            if($seamone){
                $cke=$seamone;
                $sqlStr.=" and month(l.paydt)<='".$seamone."' ";
            }
            if($seayear==date('Y')&&$cke>date('n')){
                $cke=date('n');
                $this->nowm=$cke;
            }
        }
        $spro=array();
        //项目报销
        $sql = "select 
                    l.projectno as pro , sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as dct
                from 
                    cost_detail_project d 
                    left join cost_type t on ( d.costtypeid=t.costtypeid )
                    left join cost_summary_list l on (d.billno=l.billno)

                where 
                    l.status='完成'
                    and l.isproject='1'
                    and year(l.paydt)='2011'
                group by l.projectno , d.costtypeid 
                order by d.costtypeid";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $spro[$row['pro']]=$row['pro'];
            $data[$row['pro']][$row['dct']]=isset($data[$row['pro']][$row['dct']])?
            $data[$row['pro']][$row['dct']]+$row['sm']:$row['sm'];
            $cost_type[$row['dct']] = $row['cn'];
            $total[$row['dct']] = isset($total[$row['dct']]) ? $total[$row['dct']] + $row['sm'] : $row['sm'];
        }
        //非报销类
        
        $sql = "select
                    d.contno , d.prono ,  sum(d.days*d.amount) as sm , t.costtypename as cn , d.costtypeid as dct , d.billno 
                    , d.costtypeid as dct
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                where
                    l.status='完成'
                    and d.billtypeid='7'
                    and year(l.tallydt)='2011'
                group by d.contno  , d.prono  , d.costtypeid
                order by d.costtypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $row['pro']=$row['contno'];
            if(empty($row['contno'])){
                $row['pro']=$row['prono'];
            }
            if(empty($row['contno'])&&empty($row['prono'])){
                $row['pro']='空';
            }
            $spro[$row['pro']]=$row['pro'];
            $data_b[$row['pro']][$row['dct']]=isset($data_b[$row['pro']][$row['dct']])?
            $data_b[$row['pro']][$row['dct']]+$row['sm']:$row['sm'];
            $cost_type_b[$row['dct']] = $row['cn'];
            $total_b[$row['dct']] = isset($total_b[$row['dct']]) ? $total_b[$row['dct']] + $row['sm'] : $row['sm'];
        }
        
        $res.='<tr style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;" rowspan="2">项目</td>';
        $res.='     <td colspan="'.count($cost_type).'" >报销费用</td>';
        $res.='     <td rowspan="2">小计</a></td>';
        $res.='     <td colspan="'.count($cost_type_b).'" >非报销费用</td>';
        $res.='     <td rowspan="2">小计</td>
                </tr>';
        $res.='<tr style="background: #D3E5FA;text-align: center;">';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</a></td>';
        }
        foreach ($cost_type_b as $key => $val) {
            $res.='<td>' . $val . '</a></td>';
        }
        $res.='</tr>';
        $cki = 0;
        foreach ($spro as $val) {
            $cki++;
            $res.='<tr class=" ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
            $res.='<td>'.$val.'</td>';
            foreach($cost_type as $vkey=>$vval){
                $res.='<td>' . num_to_maney_format($data[$val][$vkey]) . '</td>';
            }
            $res.='<td>'.num_to_maney_format(array_sum($data[$val])).'</td>';
            foreach($cost_type_b as $vkey=>$vval){
                $res.='<td>' . num_to_maney_format($data_b[$val][$vkey]) . '</td>';
            }
            $res.='<td>'.num_to_maney_format(array_sum($data_b[$val])).'</td>';
            $res.='</tr>';
        }
        return $res;
    }
    
    /**
     * 读取各部门信息
     */
    function model_dept_dp_data($flag='',$xls=false) {
        @extract ( $_GET );
        @extract ( $_POST );
        $sqlStr='';
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seayear=$seayear?$seayear:date('Y');
        $nowm = $seayear<date('Y')?12:date('n');
        $this->nowm=$nowm;
        $ckb=1;
        $cke=$this->nowm;
        $seatype='all';
        if($seatype=='bill'||$seatype=='all'){//非报销统计
            if($seadept){
                $sqlStrB.=" and dp.dept_name like '%".$seadept."%' ";
            }
            if($seamonb){
                $ckb=$seamonb;
                $sqlStrB.=" and month(l.inputdt)>='".$seamonb."' ";
            }
            if($seamone){
                $cke=$seamone;
                $sqlStrB.=" and month(l.inputdt)<='".$seamone."' ";
            }
            if($seayear==date('Y')&&$cke>date('n')){
                $cke=date('n');
                $this->nowm=$cke;
            }
        }
        if($seatype=='cost'||$seatype=='all'){
            if($seadept){
                $sqlStr.=" and l.costbelongtodeptids like '%".$seadept."%' ";
            }
            if($seamonb){
                $ckb=$seamonb;
                $sqlStr.=" and month(l.paydt)>='".$seamonb."' ";
            }
            if($seamone){
                $cke=$seamone;
                $sqlStr.=" and month(l.paydt)<='".$seamone."' ";
            }
            if($seayear==date('Y')&&$cke>date('n')){
                $cke=date('n');
                $this->nowm=$cke;
            }
        }
        $spro=array();
        //项目报销
        $sql = "select 
                    l.projectno as pro , sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as dct
                    ,l.paydt
                from 
                    cost_detail d 
                    left join cost_type t on ( d.costtypeid=t.costtypeid )
                    left join cost_summary_list l on (d.billno=l.billno)
                    left join xm x on (x.projectno=l.projectno)
                where 
                    l.status='完成'
                    and l.isproject='0'
                    and (l.projectno <> '' )
                    and year(l.paydt)='2010'
                    and x.id is null 
                    and l.projectno not in ('123','11','DL01','00000')
                group by l.projectno , d.costtypeid 
                order by d.costtypeid ";
        $sql="select
                    d.contno , d.prono ,  sum(d.days*d.amount) as sm , t.costtypename as cn , d.costtypeid as dct , d.billno 
                    ,  l.serialno , l.tallydt , l.payee , l.content
                from bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                where
                    l.status='完成'
                    and d.billtypeid='7'
		                and year(l.tallydt)='2011'
		            group by d.contno  , d.prono  ,d.billno , t.costtypeid 
                order by d.contno  , d.prono , l.serialno ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $row['pro']=$row['contno'];
            if(empty($row['contno'])){
                $row['pro']=$row['prono'];
            }
            if(empty($row['contno'])&&empty($row['prono'])){
                $row['pro']='空';
            }
            $spro[$row['pro']][$row['billno']]['sno']=$row['serialno'];
            $spro[$row['pro']][$row['billno']]['tdt']=$row['tallydt'];
            $spro[$row['pro']][$row['billno']]['hm']=$row['payee'];
            $spro[$row['pro']][$row['billno']]['kx']=$row['content'];
            $data[$row['pro']][$row['billno']][$row['dct']]=$row['sm'];
            $cost_type[$row['dct']] = $row['cn'];
            $total[$row['dct']] = isset($total[$row['dct']]) ? $total[$row['dct']] + $row['sm'] : $row['sm'];
        }
        
        $res.='<tr style="background: #D3E5FA;text-align: center;">';
        $res.='<td style="height: 23px;">项目</td>';
        $res.='<td style="height: 23px;">流水号</td>';
        $res.='<td style="height: 23px;">记帐日期</td>';
        $res.='<td style="height: 23px;">收款单位</td>';
        $res.='<td style="height: 23px;">款项内容</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</a></td>';
        }
        $res.='<td>小计</td></tr>';
        $cki = 0;
        foreach ($spro as $val=>$key) {
          
            foreach($key as $bkey=>$bval){
                $cki++;
                $res.='<tr class=" ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
                $res.='<td>'.$val.'</td>';
                $res.='<td>'.$bval['sno'].'</td>';
                $res.='<td>'.$bval['tdt'].'</td>';
                $res.='<td>'.$bval['hm'].'</td>';
                $res.='<td>'.$bval['kx'].'</td>';
                foreach($cost_type as $vkey=>$vval){
                    $res.='<td>' . num_to_maney_format($data[$val][$bkey][$vkey]) . '</td>';
                }
                $res.='<td>'.num_to_maney_format(array_sum($data[$val][$bkey])).'</td>';
                $res.='</tr>';
            }
        }
        return $res;
    }
    
    function model_dept_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_data('',true)) . '
            </table>';
    }
    /**
     * 查询借款超时
     */
    function model_loan_overtime(){
        $checkdept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        if ($checkdept) {
            $sqlStr.=" and d.dept_name like '%$checkdept%' ";
        }
        if ($checkuser) {
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        $data=array();
        $datadept=array();
        $deptam=array();
        $sql="select
                l.debtor , u.user_name, ( l.amount - IFNULL(r.repam,0)) as lam
                , l.id 
                , r.repam 
                , u.email , l.paydt , d.dept_name  , to_days(now())-to_days(l.paydt) as ldt
                , l.no_writeoff
            from
                loan_list l
                left join user u on (l.debtor = u.user_id )
                left join  (select loan_id , sum(money) as repam from loan_repayment group by loan_id  )  r on (l.id=r.loan_id )
                left join department d on (u.dept_id=d.dept_id)
    
            where
                l.status in ('还款中','已支付')
                and to_days(  DATE_ADD(l.paydt , INTERVAL 60 DAY) ) <=  to_days(now())
                and u.company = '".$_SESSION['USER_COM']."'
                $sqlStr 
            order by l.debtor ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            if($row['lam']<=0){
                continue;
            }
            $data[$row['dept_name']][$row['id']]['name']=$row['user_name'];
            $data[$row['dept_name']][$row['id']]['lam']=$row['lam'];
            $data[$row['dept_name']][$row['id']]['email']=$row['email'];
            $data[$row['dept_name']][$row['id']]['ldt']=$row['ldt'];
            $data[$row['dept_name']][$row['id']]['ncx']=($row['no_writeoff']=='1'?'是':'否');
            //部门
            						$datadept[$row['dept_name']]=isset($datadept[$row['dept_name']])?$datadept[$row['dept_name']]+$row['lam']:$row['lam'];
        }
        if(!empty($data)){
            $i=0;
            foreach($data as $key=>$val){
                $y=0;
                $deptam[$key]=0;
                $deptdt[$key]=0;
                foreach($val as $vkey=>$vval){
                    $deptam[$key] += $vval['lam'];
                    $deptdt[$key] += $vval['ldt'];
                    $i++;
                    $y++;
                    if ($i % 2 == 0) {
                        $res.='<tr style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr style="background: #FFFFFF;">';
                    }
                    if($y==1){
                        $res.='<td nowrap style="text-align:center;height:20px;" rowspan="'.(count($val)+1).'">' . $key . '</td>';
                    }
                    $res.= '<td style="text-align:center;">'.$vval['name'].'</td>';
                    $res.= '<td style="text-align:center;">'.$vkey.'</td>';
                    $res.= '<td>'.num_to_maney_format($vval['lam']).'</td>';
                    $res.= '<td style="text-align:center;">'.$vval['ldt'].'</td>';
                    $res.= '<td style="text-align:center;">'.$vval['ncx'].'</td>';
                    $res.='</tr>';
                }
                $res.='<tr style="background: #D3E5FA;">
                       <td style="text-align:center;">小计：</td>
                       <td></td>
                       <td>'.num_to_maney_format($deptam[$key]).'</td>
                       <td></td>
                       <td></td>
                       </tr>';
            }
        }
        $res='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td>部门</td>
                <td>员工</td>
                <td>借款单号</td>
                <td>逾期借款总额</td>
                <td>借款天数</td>
                <td>是否已备案为长期借款</td>
              </tr>
            '.$res.'
              <tr style="background: green;">
                <td></td>
                <td style="text-align:center;">合计：</td>
                <td></td>
                <td>'.num_to_maney_format(array_sum($deptam)).'</td>
                <td></td>
                <td></td>
              </tr>';
        return $res;
    }

    function model_loan_overtime_xls(){
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_loan_overtime()) . '
            </table>';
    }

    function model_loan_avg_xls(){
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_loan_avg()) . '
            </table>';
    }

    function model_loan_avg(){
        $seadept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $seauser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seadtb = isset($_POST['seadtb']) ? $_POST['seadtb'] : date('Y').'-01-01';
        $seadte = isset($_POST['seadte']) ? $_POST['seadte'] : date('Y-m-d');
        $loanarr=array();
        $reparr=array();
        if($seadept){
            $sqlStr.=" and d.dept_name like '%".$seadept."%' ";
        }
        if($seauser){
            $sqlStr.=" and u.user_name like '%".$seauser."%'";
        }
        $sql="SELECT
                l.debtor , sum( l.amount ) as sm
                , u.user_name , d.dept_name
                , (to_days('".$seadte."') - to_days('".$seadtb."')+1) as dts
            FROM 
                loan_list l 
                left join user u on (l.debtor = u.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join user u1 on (l.payee = u1.user_id)
            where 
                l.status in ('还款中','已支付','已还款')
                and to_days(l.paydt) >= to_days('".$seadtb."')
                and to_days(l.paydt) <= to_days('".$seadte."')
                and u1.company = '".$_SESSION['USER_COM']."'
                $sqlStr
            group by l.debtor ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $loanarr[$row['dept_name']][$row['debtor']]['name']=$row['user_name'];
            $loanarr[$row['dept_name']][$row['debtor']]['sm']=$row['sm'];
            $dts=$row['dts'];
        }
        $sql="select
                r.loan_id , r.money , (to_days(r.createdt)-to_days(l.paydt)+1) as dt , l.debtor
            from loan_repayment r
                left join loan_list l on (l.id=r.loan_id)
                left join user u on (l.debtor=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where
                to_days(r.createdt) >= to_days('".$seadtb."')
                and to_days(r.createdt) <= to_days('".$seadte."')
                and to_days(l.paydt) >= to_days('".$seadtb."')
                and to_days(l.paydt) <= to_days('".$seadte."')
                $sqlStr  ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $reparr[$row['debtor']][$row['loan_id']]['am']=$row['money'];
            $reparr[$row['debtor']][$row['loan_id']]['dt']=$row['dt'];
        }
        if(!empty($loanarr)){
            $i=0;
            foreach($loanarr as $key=>$val){
                $y=0;
                foreach($val as $vkey=>$vval){
                    $i++;
                    $y++;
                    $rpam=0;
                    $rpsm=0;
                    $rpst='';
                    if($reparr[$vkey]){
                        foreach($reparr[$vkey] as $rkey=>$rval){
                            $rpam+=$rval['am'];
                            $rpsm+=$rval['am']*$rval['dt'];
                            $rpst.=$rval['am'].'*'.$rval['dt'].' | ';
                        }
                    }
                    if($vval['sm']-$rpam){
                        $rpsm+=($vval['sm']-$rpam)*$dts;
                        $rpst.=($vval['sm']-$rpam).'*'.$dts.' | ';
                    }
                    $rpsm=ceil($rpsm/$vval['sm']);
                    $rpst=trim($rpst,'| ');
                    if ($i % 2 == 0) {
                        $res.='<tr style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr style="background: #FFFFFF;">';
                    }
                    if($y==1){
                        $res.='<td nowrap style="text-align:center;height:20px;" rowspan="'.count($val).'">' . $key . '</td>';
                    }
                    $res.= '<td style="text-align:center;">'.$vval['name'].'</td>';
                    $res.= '<td>'.num_to_maney_format($vval['sm']).'</td>';
                    $res.= '<td>'.num_to_maney_format($rpam).'</td>';
                    $res.= '<td>'.num_to_maney_format($vval['sm']-$rpam).'</td>';
                    $res.= '<td style="text-align:center;">'.$rpsm.'</td>';
                    $res.= '<td style="text-align:left;">'.$rpst.'</td>';
                    $res.='</tr>';
                }
            }
        }
        $res='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td>部门</td>
                <td>借款人</td>
                <td>借款总额</td>
                <td>还款总额</td>
                <td>尚欠总额</td>
                <td>平均还款天数</td>
                <td>明细（还款金额*还款天数）</td>
              </tr>'.$res;
        return $res;
    }
    /**
     *查询详情
     * @return string
     */
    function model_dept_detail_tmp() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : 'cost';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $cktype=isset($_REQUEST['flag']) ? $_REQUEST['flag'] : '';
        $data = array();
        $cost_type = array();
        $data_type=array();
        $data_all=array();
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr.=" and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if(!empty($checkt)){
            $sqlStr.=" and d.costtypeid ='$checkt' ";
        }
        if($cktype=='p'){
            $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costdates , l.status , l.costclienttype
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join wf_task wt on (l.BillNo=wt.name)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status<>'打回'
                    and a.billno=d.billno
                    and ( 
                        ( l.costclienttype like '%培训%'
                            and l.costbelongtodeptids ='人力资源部' 
                        )
                    or wt.train='388' )
                group by
                    l.billno , d.costtypeid ";
        }else{
            $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costdates , l.status , l.costclienttype
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                    left join wf_task wt on (l.BillNo=wt.name)
                    left join flow_step_partent s on (wt.task=s.Wf_task_ID)
                where
                    l.status<>'打回'
                    and a.billno=d.billno
                    and s.User='yunxia.zhu'
                group by
                    l.billno , d.costtypeid ";
        }
        
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $data[$row['bn']]['cds'] = $row['costdates'];
            $data[$row['bn']]['sta'] = $row['status'];
            $data[$row['bn']]['cct'] = $row['costclienttype'];
            $cost_type[$row['ct']] = $row['cn'];
            $data_type[$row['bn']]=isset($data_type[$row['bn']])?$data_type[$row['bn']]+$row['sm']:$row['sm'];
            $data_all[$row['ct']]=isset($data_all[$row['ct']])?$data_all[$row['ct']]+$row['sm']:$row['sm'];
        }
        //echo $sql;
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td width="120">事由</td>
            <td>日期</td>
            <td>状态</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='<td>小计</td>';
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['cct'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['cds'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['sta'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res.='<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res.='</tr>';
            $i++;
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="5">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res.='<td>'.num_to_maney_format(array_sum($data_all)).'</td>
            </tr>';
        return $res;
    }
    /**
     *查询详情
     * @return string
     */
    function model_dept_detail() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : 'cost';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $combrn = isset($_REQUEST['combrn']) ? $_REQUEST['combrn'] : '';
        $data = array();
        $cost_type = array();
        $data_type=array();
        $data_all=array();
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr.=" and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if(!empty($checkt)){
            $sqlStr.=" and d.costtypeid ='$checkt' ";
        }
        if($combrn){
            	 $sqlStr.=" and (u.company ='".$combrn."' or l.belongtocom ='".$combrn."' or l.CostBelongComId ='".$combrn."') ";
         }
        if($seatype=='cost'||$seatype=='all'){
            if($checkd=='网优工程部（项目）'){
                $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail_project d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno)
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno and l.projectno not like 'PK%'
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                group by
                    l.billno , d.costtypeid ";
            }elseif($checkd=='PK项目'){
                $sql = "(select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail_project d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno)
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno and l.projectno like 'PK%'
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                group by
                    l.billno , d.costtypeid 
                 )UNION (
                		select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                    and l.projectno like 'PK%'
                group by
                    l.billno , d.costtypeid 
                )";
            }else{
                $sql = "select
                    sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un , l.costclientarea , l.purpose , l.costclientType , l.province
                from
                    cost_detail d
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
                    left join cost_summary_list  l on (l.billno=d.billno)
                    left join user u on (l.costman=u.user_id)
                where
                    l.status='完成' 
                    and a.billno=d.billno
                    $sqlStr
                    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
                    and l.costbelongtodeptids='$checkd'
                    and l.projectno not like 'PK%'
                group by
                    l.billno , d.costtypeid ";
            }
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['bn']][$row['ct']] = $row['sm'];
                $data[$row['bn']]['un'] = $row['un'];
                if(empty($row['province'])){
                  $data[$row['bn']]['ca'] = $row['costclientarea'];
                }else{
                  $data[$row['bn']]['ca'] = $row['province'];
                }
                if(empty($row['purpose'])){
                  $data[$row['bn']]['pp'] = $row['clientType'];
                }else{
                  $data[$row['bn']]['pp'] = $row['purpose'];
                }
                
                $cost_type[$row['ct']] = $row['cn'];
                $data_type[$row['bn']]=isset($data_type[$row['bn']])?$data_type[$row['bn']]+$row['sm']:$row['sm'];
                $data_all[$row['ct']]=isset($data_all[$row['ct']])?$data_all[$row['ct']]+$row['sm']:$row['sm'];
            }
        }
        if(($seatype=='bill'||$seatype=='all')&&($combrn==''||$combrn=='dl')){
            if(empty($seabilltype)){
                $sql = "select
                    sum( d.days*d.amount ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un
                from
                    bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                    left join user u on (l.inputman=u.user_id)
                where
                    l.status='完成'
                    $sqlStr
                    and year(l.inputdt)='$checky' and month(l.inputdt)='$checkm'
                    and dp.dept_name='$checkd'
                group by
                    l.billno , d.costtypeid ";
            }else{
                $sql = "select
                    sum( d.days*d.amount ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
                    , u.user_name as un
                from
                    bill_detail d
                    left join bill_list l on (d.billno=l.billno)
                    left join cost_type t on (d.costtypeid=t.costtypeid)
                    left join department dp on (d.billdept=dp.dept_id)
                    left join user u on (l.inputman=u.user_id)
                where
                    l.status='完成'
                    $sqlStr
                    and year(l.inputdt)='$checky' and month(l.inputdt)='$checkm'
                    and dp.dept_name='$checkd'
                    and d.billtypeid='".$seabilltype."'
                group by
                    l.billno , d.costtypeid ";
            }
            
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $data[$row['bn']][$row['ct']] = $row['sm'];
                $data[$row['bn']]['un'] = $row['un'];
                $cost_type[$row['ct']] = $row['cn'];
                $data_type[$row['bn']]=isset($data_type[$row['bn']])?$data_type[$row['bn']]+$row['sm']:$row['sm'];
                $data_all[$row['ct']]=isset($data_all[$row['ct']])?$data_all[$row['ct']]+$row['sm']:$row['sm'];
            }
        }
       
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>地区</td>
            <td>第一审批人</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='<td >小计</td>';
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            $sql="SELECT  smallid , u.user_name   FROM flow_step_partent  s 
                  left join wf_task w on (s.wf_task_id=w.task)
                  left join user u on (u.user_id=s.user )
                  where ( w.name = '".$key."' or w.objcode='".$key."' ) and smallid=1 ";
            $query = $this->db->get_one($sql);
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['ca'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $query['user_name'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res.='<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res.='</tr>';
            $i++;
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="4">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res.='<td>'.num_to_maney_format(array_sum($data_all)).'</td>
            </tr>';
        return $res;
    }

    function model_dept_detail_sea() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype']:'';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype']:'';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $res .= '<select name="seay" >';
        for ($i = 2008; $i <= $nowy; $i++) {
            if ($checky == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> 年 ';
        //开始月
        $res.='<select name="seam" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkm == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> 月 ';
        $res.=' 部门 <input type="seabill" name="seabill" size="18" value="' . $checkbill . '" />';
        $res.=' 报销人 <input type="seabill" name="seauser" size="18" value="' . $checkuser . '" />';
        $res.=' <input type="hidden" name="sead" value="'.$checkd.'" /> ';
        $res.=' <input type="hidden" name="seat" value="'.$checkt.'" /> ';
        $res.=' <input type="hidden" name="seatype" value="'.$seatype.'" /> ';
        $res.=' <input type="hidden" name="seabilltype" value="'.$seabilltype.'" /> ';
        return $res;
    }

    function model_dept_detail_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_detail()) . '
            </table>';
    }
    
    function model_dept_detail_excel_tmp() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_detail_tmp()) . '
            </table>';
    }
    /**
     * 分公司费用统计
     */
    function model_cost_com_list(){
        $info=array();
        $total=array();
        $billtal=0;
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr.=" and d.dept_name like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if ($checky) {
            $sqlStr.=" and year(p.paydt) = '$checky' ";
        }
        if ($checkm) {
            $sqlStr.=" and month(p.paydt) = '$checkm' ";
        }
        $sql="select d.dept_name , u.user_name , p.paymoney , p.billnos , p.paycom
            from cost_pay p
                left join user u on (p.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where 1 $sqlStr
            order by billnos ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $info[$row['billnos']]['dept']=$row['dept_name'];
            $info[$row['billnos']]['name']=$row['user_name'];
            $info[$row['billnos']][$row['paycom']]=$row['paymoney'];
            $total[$row['paycom']]=empty ($total[$row['paycom']])?$row['paymoney']:$total[$row['paycom']]+$row['paymoney'];
        }
        $sql="select l.billnos , sum(l.money) as sjlr
            from loan_repayment l
                left join cost_pay p on (p.billnos=l.billnos)
                left join user u on (p.userid=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
            where l.way='0' $sqlStr
            group by l.billnos ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $info[$row['billnos']]['sjlr']=$row['sjlr'];
            $total['sjlr']=empty ($total['sjlr'])?$row['sjlr']:$total['sjlr']+$row['sjlr'];
        }
        if(!empty ($info)){
            $i=0;
            foreach($info as $key=>$val){
                $tmp=array();
                if(strpos($key, "','")!==false){
                    $sql="select billno , amount from cost_summary_list l where l.billno in ($key) ";
                    $query=$this->db->query($sql);
                    while($row=$this->db->fetch_array($query)){
                        $tmp[$row['billno']]=$row['amount'];
                    }
                }else{
                    $tmp[str_replace("'", '', $key)]=$val['世纪']+$val['鼎利']+$val['sjlr'];
                }
                $billtal=array_sum($tmp)+$billtal;
                $tmpc=count($tmp);
                $y=0;
                foreach($tmp as $tkey=>$tval){
                    if ($i % 2 == 0) {
                        $res.='<tr style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr style="background: #FFFFFF;">';
                    }
                    if($y==0){
                        $res.='<td nowrap style="text-align:center;height:20px;" rowspan="'.$tmpc.'">' . $i . '</td>';
                        $res.='<td nowrap style="text-align:center" rowspan="'.$tmpc.'"> ' . $val['dept'] . '</td>';
                        $res.='<td nowrap style="text-align:center" rowspan="'.$tmpc.'"> ' . $val['name'] . '</td>';
                        $res.='<td rowspan="'.$tmpc.'">' . num_to_maney_format($val['世纪']) . '</td>';
                        $res.='<td rowspan="'.$tmpc.'">' . num_to_maney_format($val['sjlr']) . '</td>';
                        $res.='<td rowspan="'.$tmpc.'">' . num_to_maney_format($val['鼎利']) . '</td>';
                        $res.='<td nowrap style="text-align:center" >&nbsp;' . $tkey . '</td>';
                        $res.='<td >' . num_to_maney_format($tval) . '</td>';
                    }else{
                        $res.='<td nowrap style="text-align:center" >&nbsp;' . $tkey . '</td>';
                        $res.='<td >' . num_to_maney_format($tval) . '</td>';
                    }
                    $res.='</tr>';
                    $y++;
                }
                $i++;
            }
            $res.='<tr style="background: #FFFFFF;">';
            $res.='<td nowrap style="text-align:center;height:20px;" ><font color="red">合计</font></td>';
            $res.='<td nowrap style="text-align:center"> </td>';
            $res.='<td nowrap style="text-align:center"> </td>';
            $res.='<td >' . num_to_maney_format($total['世纪']) . '</td>';
            $res.='<td >' . num_to_maney_format($total['sjlr']) . '</td>';
            $res.='<td >' . num_to_maney_format($total['鼎利']) . '</td>';
            $res.='<td > </td>';
            $res.='<td > '.num_to_maney_format($billtal).'</td>';
            $res.='</tr>';
        }
        return $res;
    }
    function model_cost_com_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        $str= '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" cellpadding="0" cellspacing="0" border="1" >
                <tr style="background: #FFFFFF;">
                    <td align="center" height="30" rowspan="2" width="10%">序号</td>
                    <td align="center" rowspan="2">部门</td>
                    <td align="center" rowspan="2">姓名</td>
                    <td align="center" colspan="2">世纪</td>
                    <td align="center" rowspan="1">鼎利</td>
                    <td align="center" rowspan="2">报销单</td>
                    <td align="center" rowspan="2">金额</td>
                </tr>
                <tr style="background: #FFFFFF;">
                    <td align="center" height="30" >支付</td>
                    <td align="center" >还款</td>
                    <td align="center" >支付</td>
                </tr>
                ' . ($this->model_cost_com_list()) . '
            </table>';
        echo un_iconv($str);
    }

    function model_dept_other(){
        $seadept = isset($_POST['seadept']) ? $_POST['seadept'] : '';
        $seauser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seacd = isset($_POST['seacd']) ? $_POST['seacd'] : '';
        $seadtb = isset($_POST['seadtb']) ? $_POST['seadtb'] : date('Y-m').'-01';
        $seadte = isset($_POST['seadte']) ? $_POST['seadte'] : date('Y-m-d');
        if(!empty($seadept)){
            $sqlStr.=" and d.dept_name like '%".$seadept."%'";
        }
        if(!empty($seauser)){
            $sqlStr.=" and u.user_name like '%".$seauser."%'";
        }
        if(!empty($seacd)){
            $sqlStr.=" and l.costbelongtodeptids like '%".$seacd."%'";
        }
        $data=array();
        $sql="select
                IF(l.isproject = '1','工程项目',l.costbelongtodeptids ) as cd
                , d.dept_name , u.user_name
                , l.billno , l.amount
                , u.user_id
            from
                cost_summary_list l
                left join user u on (u.user_id=l.costman)
                left join department d on (l.costdepartid=d.dept_id)
            where
                ( ( l.costbelongtodeptids != d.dept_name and l.isproject='0' )
                 or ( l.isproject = '1' and d.dept_name <>'网络服务部' )   )
                and to_days(l.paydt)>= to_days('".$seadtb."')
                and to_days(l.paydt)<= to_days('".$seadte."')
                and l.status='完成'
                and u.company = '".$_SESSION['USER_COM']."'
                $sqlStr
                ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $data[$row['user_id']]['name']=$row['user_name'];
            $data[$row['user_id']]['dept']=$row['dept_name'];
            $data[$row['user_id']]['bill'][$row['billno']]['am']=$row['amount'];
            $data[$row['user_id']]['bill'][$row['billno']]['cd']=$row['cd'];
        }
        if(!empty($data)){
            $i=0;
            foreach($data as $key=>$val){
                $y=0;
                foreach($val['bill'] as $vkey=>$vval){
                    $cb=count($val['bill']);
                    $i++;
                    $y++;
                    if ($i % 2 == 0) {
                        $res.='<tr style="background: #F3F3F3;">';
                    } else {
                        $res.='<tr style="background: #FFFFFF;">';
                    }
                    $res.='<td nowrap style="text-align:center; height:20px;" > ' . $val['name'] . '</td>';
                    $res.='<td nowrap style="text-align:center" > ' . $val['dept'] . '</td>';
                    $res.='<td style="text-align:center">'.$vval['cd'].'</td>';
                    $res.='<td style="text-align:center">'.$vkey.'</td>';
                    $res.='<td style="padding-right:5px;">'.  num_to_maney_format($vval['am']).'</td>';
                    $res.='</tr>';
                }
            }
        }
        $res='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td>员工</td>
                <td>部门</td>
                <td>费用归属部门</td>
                <td>单号</td>
                <td>金额</td>
              </tr>'.$res;
        return $res;
    }

    function model_dept_other_xls(){
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        $str= '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" cellpadding="0" cellspacing="0" border="1" >               
                ' . ($this->model_dept_other()) . '
            </table>';
        echo un_iconv($str);
    }

    function model_bill_type($slt){
        $sql="select id as id , name as name , parentid as pid
            from bill_type
            where parentid<>0 ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $carr[$row['id']]=$row['name'];
        }
        $res='';
        foreach($carr as $key=>$val){
            if($slt==$key){
                $res.='<option value="'.$key.'" selected>'.$val.'</option>';
            }else{
                $res.='<option value="'.$key.'" >'.$val.'</option>';
            }
        }
        return $res;
    }

    /**
     *研发项目查询
     * @return string
     */
    function model_dept_detail_dev() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '';
        $checkt = isset($_REQUEST['seat']) ? $_REQUEST['seat'] : '';
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $seatype = isset($_REQUEST['seatype']) ? $_REQUEST['seatype'] : 'cost';
        $seabilltype = isset($_REQUEST['seabilltype']) ? $_REQUEST['seabilltype'] : '';
        $data = array();
        $cost_type = array();
        $data_type=array();
        $data_all=array();
        $sqlStr = '';
        if ($checkbill) {
            //$sqlStr.=" and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            //$sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if(!empty($checkt)){
            //$sqlStr.=" and d.costtypeid ='$checkt' ";
        }
        $sql = "select
            sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn 
            , u.user_name as un , l.projectno as pn , dp.dept_name as dp
        from
            cost_detail d
            left join cost_type t on (d.costtypeid=t.costtypeid)
            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
            left join cost_summary_list  l on (l.billno=d.billno)
            left join user u on (l.costman=u.user_id)
            left join department dp on (u.dept_id=dp.dept_id)
        where
            l.status='完成'
            and a.billno=d.billno
            and u.company = '".$_SESSION['USER_COM']."'
            $sqlStr
            and ( l.projectno like '%统一数据管理平台%' or l.projectno like '%统一分析平台%' or l.projectno like '%统一数据集%'  )
        group by
            l.billno , d.costtypeid
        order by l.projectno ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $data[$row['bn']]['pn'] = $row['pn'];
            $data[$row['bn']]['dp'] = $row['dp'];
            $cost_type[$row['ct']] = $row['cn'];
            $data_type[$row['bn']]=isset($data_type[$row['bn']])?$data_type[$row['bn']]+$row['sm']:$row['sm'];
            $data_all[$row['ct']]=isset($data_all[$row['ct']])?$data_all[$row['ct']]+$row['sm']:$row['sm'];
        }
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">项目</td>
            <td>报销人</td>
            <td>报销单</td>
            <td>部门</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='<td>小计</td>';
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $val['pn']  . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $key . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res.='<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res.='</tr>';
            $i++;
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="4">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res.='<td>'.num_to_maney_format(array_sum($data_all)).'</td>
            </tr>';
        return $res;
    }

    function model_dept_stat(){
    	global $func_limit;
        $checkm = date('Y-m-d');
        $checkme = date('Y-m-d');
       
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $checkm;
        $checkme = isset($_REQUEST['seame']) ? $_REQUEST['seame'] : $checkme;
        $checkd = isset($_REQUEST['sead']) ? $_REQUEST['sead'] : '0';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $deprtid = isset($_POST['deprtid']) ? $_POST['deprtid'] : $_GET['deprtid'];
        $details = isset($_POST['details']) ? $_POST['details'] : $_GET['details'];
        $statuses=isset($_POST['statuses']) ? $_POST['statuses'] : $_GET['statuses'];
        $isProject=isset($_POST['isProject']) ? $_POST['isProject'] : $_GET['isProject'];
        $dateStatus=isset($_POST['dateStatus']) ? $_POST['dateStatus'] : $_GET['dateStatus'];
        if($func_limit['浏览部门']){
            $sead=$func_limit['浏览部门'].','.$_SESSION["DEPT_ID"];
        }else{
            $sead=$_SESSION["DEPT_ID"];
        }
        
        if($deprtid&&$deprtid!=0){
        	$dept = new model_system_dept();
			$son_id=$dept->GetSon_ID($deprtid);
			if($son_id&&is_array($son_id)){
				$deprtid=$deprtid.",".implode(",",(array)$son_id);
			}
        }else{
        	$deprtid=$sead;
        }
        $data = array();
        $cost_type = array();
        $data_type=array();
        $data_all=array();
        $sqlStr = '';
        if(empty($dateStatus)){
        	 $dateStatus=1;
        }
        if($dateStatus==1){
        	$sqlStr.=" and to_days(a.CostDateBegin)>=to_days('$checkm') and to_days(a.CostDateBegin)<=to_days('$checkme') ";
        }elseif($dateStatus==2){
           $sqlStr.=" and to_days(l.PayDT)>=to_days('$checkm') and to_days(l.PayDT)<=to_days('$checkme') ";
        }
        if ($checkuser) {
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if($statuses){
        	 $sqlStr.=" and l.status='$statuses' ";
        }
        if($details==1){
        	if($_SESSION['COM_BRN_PT']=='bx'&&$isProject==1){
	           $sql = "	(SELECT
					             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
					            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
					            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
						FROM
							            cost_detail d
							            left join cost_type t on (d.costtypeid=t.costtypeid)
							            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
							            left join cost_summary_list  l on (l.billno=d.billno)
							            left join department dp on (l.CostBelongDeptId=dp.DEPT_ID)
							            left join user u on (l.costman=u.user_id)
							            left join department dpu on (dpu.dept_id=u.dept_id)
					    WHERE
					            u.company = '".$_SESSION['USER_COM']."'
					            and a.billno=d.billno
					            $sqlStr
					            and dp.dept_id in (".$deprtid.")
					    GROUP BY  d.ID,l.CostMan
								 ORDER BY  l.isProject,a.CostDateBegin DESC,l.CostMan,t.CostTypeID
					    )UNION(
					    SELECT
							             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
							            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
							            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
					    FROM
							            cost_detail_project d
							            LEFT JOIN cost_type t on (d.costtypeid=t.costtypeid)
							            LEFT JOIN cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
							            LEFT JOIN cost_summary_list  l on (l.billno=d.billno)
													LEFT JOIN xm ON  xm.ProjectNo=l.ProjectNo 
							            LEFT JOIN department dp on (xm.deptId=dp.DEPT_ID)
							            LEFT JOIN user u on (l.costman=u.user_id)
							            LEFT JOIN department dpu on (dpu.dept_id=u.dept_id)
													
					     WHERE
								u.company = '".$_SESSION['USER_COM']."'
					            and a.billno=d.billno
					            $sqlStr
					            and xm.deptId in (".$deprtid.")
					     GROUP BY  d.ID,l.CostMan
						 ORDER BY l.isProject,a.CostDateBegin DESC,l.CostMan,t.CostTypeID
					       )";
        	}else{
        	$sql = "select
		             d.id,t.costtypename as cn ,sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.CostBelongDeptId=dp.DEPT_ID)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		        where
		            u.company = '".$_SESSION['USER_COM']."'
		            and a.billno=d.billno
		            $sqlStr
		            and dp.dept_id in (".$deprtid.")
		            group by
			            d.ID,l.CostMan
			        ORDER BY  a.CostDateBegin DESC,l.CostMan,t.CostTypeID";
        		
        	}    
		        $query = $this->db->query($sql);
		        $cat=0;
        while ($row = $this->db->fetch_array($query)) {
        	$data[$row['id']]['bn'] = $row['bn'];
            $data[$row['id']]['un'] = $row['un'];
            $data[$row['id']]['sm'] = $row['sm'];
            $data[$row['id']]['reson']= $row['costclienttype'];
            $data[$row['id']]['dts']= $row['costdates'];
            $data[$row['id']]['dpu']= $row['dpu'];
            $data[$row['id']]['dp']= $row['dp'];
            $data[$row['id']]['pro']= $row['projectno'];
            $data[$row['id']]['bet']= $row['CostDateBegin'];        
            $data[$row['id']]['cn'] = $row['cn'];
            $data[$row['id']]['nt'] = $row['Note'];
            $data[$row['id']]['pc'] = $row['Place'];
            $data[$row['id']]['sts'] = $row['status'];
            $cat=isset($row['sm'])?$row['sm']+$cat:$cat;
            //$data[$row['id']]['ct']=isset($data_all[$row['id']]['ct'])?$data_all[$row['id']['ct']]+$row['sm']:$row['sm'];
        }
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>报销人部门</td>
            <td>费用归属部门</td>
            <td>报销时间</td>
            <td>金额</td>
            <td>类型</td>
            <td>项目信息</td>
            <td>事由</td>
            <td>摘要</td>
            <td>地点</td>
            <td>状态</td>
          ';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' .  $val['bn'] . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dpu'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
             //$res.='<td nowrap style="text-align:center"> ' . $val['dts'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['bet'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['sm'] . '</td>';
              $res.='<td nowrap style="text-align:center"> ' . $val['cn'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['pro'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['reson'] . '</td>';
            
             $res.='<td nowrap style="text-align:center"> ' . $val['nt'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['pc'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['sts'] . '</td>';
            $res.='</tr>';
            $i++;
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="6">小计：</td>';
        
        $res.='<td>'.num_to_maney_format($cat).'</td><td colspan="5"></td>
            </tr>';
	        	
        }else{
           if($_SESSION['COM_BRN_PT']=='bx'){
           	  if($isProject==1){
           	  	 $sql = "(SELECT
				                sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
				            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
				            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
				        FROM
				            cost_detail d
				            left join cost_type t on (d.costtypeid=t.costtypeid)
				            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
				            left join cost_summary_list  l on (l.billno=d.billno)
				            left join department dp on (l.CostBelongDeptId=dp.DEPT_ID)
				            left join user u on (l.costman=u.user_id)
				            left join department dpu on (dpu.dept_id=u.dept_id)
				        WHERE
				            u.company = '".$_SESSION['USER_COM']."'
				            and a.billno=d.billno
				            $sqlStr
				            and to_days(a.CostDateBegin)>=to_days('$checkm') and to_days(a.CostDateBegin)<=to_days('$checkme')
				            and dp.dept_id in (".$deprtid.")
				        group by
				            l.billno , d.costtypeid 
				        )UNION(
						SELECT
				            sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
				            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
				            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status 
				        FROM
							cost_summary_list  l 
							LEFT JOIN  cost_detail_project d  on (l.BillNo=d.BillNo)
				            LEFT JOIN cost_type t on (d.costtypeid=t.costtypeid)
				            LEFT JOIN cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
				            LEFT JOIN xm ON  xm.ProjectNo=l.ProjectNo 
				            LEFT JOIN department dp on (xm.deptId=dp.DEPT_ID)
				            LEFT JOIN user u on (l.costman=u.user_id)
				            LEFT JOIN department dpu on (dpu.dept_id=u.dept_id)
							LEFT JOIN cost_detail_list cl on (cl.HeadID=a.HeadID)
				        WHERE 
				            u.company = '".$_SESSION['USER_COM']."'
				            $sqlStr
				            and xm.deptId in (".$deprtid.")
				        group by
				            l.billno , d.costtypeid 
				            )";
        
           	  }else{
           	  	$sql = "SELECT
				            sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
				            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
				            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status
				        FROM
				            cost_detail d
				            left join cost_type t on (d.costtypeid=t.costtypeid)
				            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
				            left join cost_summary_list  l on (l.billno=d.billno)
				            left join department dp on (l.CostBelongDeptId=dp.DEPT_ID)
				            left join user u on (l.costman=u.user_id)
				            left join department dpu on (dpu.dept_id=u.dept_id)
				        WHERE
				            u.company = '".$_SESSION['USER_COM']."'
				            and a.billno=d.billno
				            $sqlStr
				            and dp.dept_id in (".$deprtid.")
				        group by
				            l.billno , d.costtypeid 
				        ";
           	  	
           	  }
          }else{
		      $sql = "select
		              sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
		            , u.user_name as un , l.costclienttype , l.costdates  , dpu.dept_name as dpu , dp.dept_name as dp , l.projectno
		            ,a.CostDateBegin,a.CostDateEnd,a.Note,a.Place,l.status ,bi.namecn as bi
		        from
		            cost_detail d
		            left join cost_type t on (d.costtypeid=t.costtypeid)
		            left join cost_detail_assistant a on ( a.id=d.assid and a.billno=d.billno )
		            left join cost_summary_list  l on (l.billno=d.billno)
		            left join department dp on (l.CostBelongDeptId=dp.DEPT_ID)
		            left join user u on (l.costman=u.user_id)
		            left join department dpu on (dpu.dept_id=u.dept_id)
		            left join branch_info bi on (bi.namept=u.company)
		        where
		            a.billno=d.billno  
		            $sqlStr
		            and dpu.dept_id in (".$deprtid.")
		        group by
		            l.billno , d.costtypeid ";
         }
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $data[$row['bn']]['bi'] = $row['bi'];
            $data[$row['bn']]['reson']= $row['costclienttype'];
            $data[$row['bn']]['dts']= $row['costdates'];
            $data[$row['bn']]['dpu']= $row['dpu'];
            $data[$row['bn']]['dp']= $row['dp'];
            $data[$row['bn']]['pro']= $row['projectno'];
            $data[$row['bn']]['sts']= $row['status'];
            if($row['CostDateBegin']==$row['CostDateEnd']){
            	 $data[$row['bn']]['bet']= $row['CostDateBegin'];
            }else{
            	 $data[$row['bn']]['bet']= $row['CostDateBegin'].'~'.$row['CostDateEnd'];
            }
           
            $cost_type[$row['ct']] = $row['cn'];
            $data_type[$row['bn']]=isset($data_type[$row['bn']])?$data_type[$row['bn']]+$row['sm']:$row['sm'];
            $data_all[$row['ct']]=isset($data_all[$row['ct']])?$data_all[$row['ct']]+$row['sm']:$row['sm'];
        }
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">报销单</td>
            <td>报销人</td>
            <td>公司</td>
            <td>报销人部门</td>
            <td>费用归属部门</td>
            <td>事由</td>
            <td>项目信息</td>
            <td>报销时间</td>
            <td>状态</td>
          ';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='<td>小计</td>';
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['bi'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dpu'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['dp'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['reson'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['pro'] . '</td>';
            //$res.='<td nowrap style="text-align:center"> ' . $val['dts'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['bet'] . '</td>';
             $res.='<td nowrap style="text-align:center"> ' . $val['sts'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res.='<td>' . num_to_maney_format($data_type[$key]) . '</td>';
            $res.='</tr>';
            $i++;
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                <td colspan="9">小计：</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . num_to_maney_format($data_all[$key]) . '</td>';
        }
        $res.='<td>'.num_to_maney_format(array_sum($data_all)).'</td>
            </tr>';
        }     
        return $res;
    }

    function model_dept_stat_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_stat()) . '
            </table>';
    }
    
    function model_dept_account() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;charset=utf-8;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_account()) . '
            </table>';
    }
    //*********************************析构函数************************************

    function __destruct() {

    }

}
?>