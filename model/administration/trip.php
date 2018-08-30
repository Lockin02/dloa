<?php
class model_administration_trip extends model_base {

    public $page;
    public $num;
    public $start;
    public $db;

    //*******************************构造函数***********************************
    function __construct() {
        parent::__construct();
        $this->db = new mysql();
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
    }
    
    //*********************************数据处理********************************

    function model_stat($flag='list'){
        $seadept=$_REQUEST['seadept'];
        $seapro=$_REQUEST['seapro'];
        $seaname=$_REQUEST['seaname'];
        $seadtb=$_REQUEST['seadtb'];
        $seadte=$_REQUEST['seadte'];
        if(!empty($seadept)){
            $sqlstr.=" and u.dept_id='".$seadept."' ";
        }
        if(!empty($seapro)){
            $sqlstr.=" and l.projectno='".$seapro."' ";
        }
        if(!empty($seaname)){
            $sqlstr.=" and u.user_name like '%".$seaname."%' ";
        }
        if(!empty($seadtb)){
            $sqlstr.=" and to_days(a.costdatebegin)>=to_days('".$seadtb."') ";
        }
        if(!empty($seadte)){
            $sqlstr.=" and to_days(a.costdateend)<=to_days('".$seadte."') ";
        }
        $sql="select
                dp.dept_name , u.user_name , x.name as proname , x.projectno , d.days , a.costdatebegin , a.costdateend
                , d.remark , l.costclienttype
            from cost_detail d
                left join cost_detail_assistant a on (d.headid=a.headid and d.rno=a.rno)
                left join cost_summary_list l on (a.billno=l.billno)
                left join xm x on (l.projectno=x.projectno)
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where d.costtypeid='123'
                and l.status in ('财务审核','出纳付款','完成')
            $sqlstr
            order by dp.dept_id , u.user_id
                ";
        $query=$this->db->query($sql);
        $i=0;
        while($row=$this->db->fetch_array($query)){
            if(empty($row['remark'])){
                $remark=$row['costclienttype'];
            }else{
                $remark=$row['remark'];
            }
            if($flag=='list'){
                if ($i % 2 == 0) {
                    $res.='<tr style="background: #F3F3F3;">';
                } else {
                    $res.='<tr style="background: #FFFFFF;">';
                }
                $res.='<td>'.$row['dept_name'].'</td>
                        <td>'.$row['user_name'].'</td>
                        <td>'.$row['proname'].'-'.$row['projectno'].'</td>
                        <td>'.$row['days'].'</td>
                        <td>'.$row['costdatebegin'].'</td>
                        <td>'.$row['costdateend'].'</td>
                        <td>'.$remark.'</td>';
                $res.='</tr>';
            }elseif($flag=='excel'){
                $res[]=array(
                    $row['dept_name'],$row['user_name']
                    ,$row['proname'].'-'.$row['projectno'],$row['days']
                    ,$row['costdatebegin'],$row['costdateend'],$remark
                );
            }
            $i++;
        }
        if($flag=='list'){
            $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;" width="12%">部门</td>
                        <td width="10%">员工</td>
                        <td width="25%">项目名称</td>
                        <td width="7%">出差天数（天）</td>
                        <td width="10%">开始日期</td>
                        <td width="10%">结束日期</td>
                        <td >备注</td>
                   </tr>' . $res ;
        }
        return $res;
    }
    function model_stat_excel(){
        $Title = array(array (
                 '部门', '员工', '项目名称', '出差天数（天）', '开始日期', '结束日期'
                 , '备注'
                 ));
       $xls = new includes_class_excel();
       $xls->SetTitle(array('ManagerTrip'),$Title);
       $xls->SetContent(array($this->model_stat('excel')));
       $xls->OutPut(); 
    }
    /**
     *部门信息
     * @param <type> $departmentid
     * @return string 
     */
    function model_trip_dept($departmentid='') {
		if(empty($departmentid)){
    	 $departmentid = $_REQUEST ['seadept'] ? $_REQUEST ['seadept'] : $_REQUEST ['seadept'];
		}
		$str = '';
		$sql = "select
                    dept_id
                    ,dept_name
                 from
                    department
                 where
                    dept_id<>'1' ";
		$query = $this->db->query ( $sql );
		while ( ($row = $this->db->fetch_array ( $query )) != false ) {
			if ($row ['dept_id']) {
				$str .= "<option value='" . $row ['dept_id'] . "'";
				if ($departmentid == $row ['dept_id'])
					$str .= "selected";
				$str .= ">" . trim ( $row ['dept_name'] ) . "</option>";
			}
		}
		return $str;
	}
    /**
     *项目信息
     * @param <type> $proid
     * @return string 
     */
	function model_trip_pro($proid='') {
		if(empty($proid)){
		  $proid = $_REQUEST ['seapro'] ? $_REQUEST ['seapro'] : $_REQUEST ['seapro'];
		}
		$str = '';
		$sql = "select id,name,projectno
                 from xm";
		$query = $this->db->query ( $sql );
		while ( ($row = $this->db->fetch_array ( $query )) != false ) {
			if ($row ['id']) {
				$str .= "<option value='" . $row ['projectno'] . "'";
				if ($proid == $row ['projectno'])
					$str .= "selected";
				$str .= ">" . trim ( $row ['name'] ) . "(" . trim ( $row ['projectno'] ) . ")</option>";
			}
		}
		return $str;
	}
    //*********************************析构函数************************************
    function __destruct() {
    }
}
?>