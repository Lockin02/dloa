<?php

class model_clerical_mc extends model_base {

    public $page;
    public $db;
    public $limitdt;
    public $tt;
    public $tn;

    //*******************************构造函数***********************************
    function __construct() {
        parent::__construct();
        $this->db = new mysql();
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->limitdt='2017-09-13 17:00:00';
        $this->tt=array('com'=>'公司','post'=>'邮寄','project'=>'项目','customer'=>'客户');
        $this->tn=array('own'=>'本人','home'=>'家人');
    }

    //*********************************数据处理********************************

    /**
     * 插入数据
     *
     */
    function model_insert() {
        $_POST = mb_iconv($_POST);
        $totype=$_POST['totype'];
        if($totype=='com'){
            $sql="insert into moon_cake
                    ( userid , totype , toarea , rand_key )
                values
                    ('" . $_SESSION['USER_ID'] . "' , '" . $_POST['totype'] . "' , '" . $_POST['toarea'] . "' , '" . get_rand_key() . "' ) ";
        }elseif($totype=="post"||$totype=="project"||$totype=="customer"){
            $sql = "insert into moon_cake
                ( userid , totype , tonamet , toname , totel , toadd , topostcode , remark , rand_key  )
                values('" . $_SESSION['USER_ID'] . "', '" . $_POST['totype'] . "' , '" . $_POST['tonamet'] . "'  , '" . $_POST['toname'] . "' ,'" . $_POST['totel'] . "' ,
                    '" . $_POST['toadd'] . "' ,'" . $_POST['topostcode'] . "' , '" . $_POST['toremark'] . "' ,
                    '" . get_rand_key() . "' )";
        }
        try {
            if(time()>strtotime($this->limitdt)){
                throw new Exception('Deadline :'.$this->limitdt);
            }
            $this->db->query_exc($sql);
            echo '1';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 更新数据
     *
     */
    function model_update() {
        $_POST = mb_iconv($_POST);
        $totype=$_POST['totype'];
        if($totype=='com'){
            $sql="update
                    moon_cake
                set
                    totype='com'
                    ,toarea='" . $_POST['toarea'] . "'
                    ,toname='',tonamet='',totel='',toadd=''
                    ,topostcode='',remark=''
                where
                    rand_key='".$_POST['pid']."' ";
        }elseif($totype=="post"){
            $sql = "update  moon_cake  set
                    totype='post' ,tonamet='".$_POST['tonamet']."' , toname='".$_POST['toname']."', totel='".$_POST['totel']."' , toadd='".$_POST['toadd']."'
                    , topostcode='".$_POST['topostcode']."' , remark='".$_POST['toremark']."'
                    ,toarea=''
                where rand_key='".$_POST['pid']."' ";
        }elseif($totype=="project"){
            $sql = "update  moon_cake  set
                    totype='project' ,tonamet='".$_POST['tonamet']."' , toname='".$_POST['toname']."', totel='".$_POST['totel']."' , toadd='".$_POST['toadd']."'
                    , topostcode='".$_POST['topostcode']."' , remark='".$_POST['toremark']."'
                    ,toarea=''
                where rand_key='".$_POST['pid']."' ";
        }elseif($totype=="customer"){
            $sql = "update  moon_cake  set
                    totype='customer' ,tonamet='".$_POST['tonamet']."' , toname='".$_POST['toname']."', totel='".$_POST['totel']."' , toadd='".$_POST['toadd']."'
                    , topostcode='".$_POST['topostcode']."' , remark='".$_POST['toremark']."'
                    ,toarea=''
                where rand_key='".$_POST['pid']."' ";
        }
        try {
            if(time()>strtotime($this->limitdt)){
                throw new Exception('Deadline :'.$this->limitdt);
            }
            $this->db->query_exc($sql);
            echo 2;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

function model_delCustomer(){
	try {
            if(time()>strtotime($this->limitdt)){
                //throw new Exception('Deadline :'.$this->limitdt);
            }
            $sql="delete from moon_cakes where id='".$_POST['id']."' ";
            //echo $sql;
			$this->db->query_exc($sql);
            echo '1';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
	
}

    /**
     * 删除数据
     *
     */
    function model_delete() {
        try {
            if(time()>strtotime($this->limitdt)){
                throw new Exception('Deadline :'.$this->limitdt);
            }
            $sql="delete from moon_cake where rand_key='".$_POST['pid']."' ";
            $this->db->query_exc($sql);
            echo '1';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function model_info() {
        $arr = array();
        $userid = $_SESSION['USER_ID'];
        if($userid=='admin'||$userid=='penghui.zhao'){
            $arr['admin']='<input type="button" value="邮寄单管理" onClick="parent.openTab(\'index1.php?model=clerical_mc&action=stat\',\'邮寄单管理\');parent.tb_remove();" />';
        }else{
            $arr['admin']='';
        }
        $arr['username'] = $_SESSION['USERNAME'];
        $sql = "select userid ,
                toname , totel , toadd , topostcode , remark ,rand_key
                , totype , toarea , tonamet
            from
                moon_cake
            where
                userid='$userid';
            ";
        $row = $this->db->get_one($sql);
        if (!$row['userid']) {
            $arr['userinfo'] = '请选择月饼的领取方式和填入相关的信息,截止：9月13号下午5点。';
            //$sql = "select address  from hrms where user_id='$userid' ";
            //$row = $this->db->get_one($sql);
            //$arr['toadd'] = $row['address'];
            $arr['toadd'] = '';
            $arr['dipcom']='none';
            $arr['dippost']='none';
            $arr['diptoname']='none';
        } else {
            $arr['userinfo'] = '领取单信息如下，如需修改，请填入相关信息，截止：9月13号下午5点。';
            if($row['totype']=='com'){
                $arr['dippost']='none';
                $arr['diptoname']='none';
                $arr['totype1']='checked';
                if($row['toarea']=='珠海'){
                    $arr['toarea1']='checked';
                }
                if($row['toarea']=='北京-财经'){
                    $arr['toarea2']='checked';
                }
                if($row['toarea']=='北京-豪柏'){
                    $arr['toarea3']='checked';
                }
                if($row['toarea']=='沈阳'){
                    $arr['toarea4']='checked';
                }
                if($row['toarea']=='广州'){
                    $arr['toarea5']='checked';
                }
                if($row['toarea']=='南京'){
                    $arr['toarea6']='checked';
                }
                if($row['toarea']=='西安'){
                    $arr['toarea7']='checked';
                }
                if($row['toarea']=='上海'){
                    $arr['toarea8']='checked';
                }
				/*
                if($row['toarea']=='广州办'){
                    $arr['toarea9']='checked';
                }
                if($row['toarea']=='广州贝软'){
                    $arr['toarea10']='checked';
                }
                if($row['toarea']=='长沙'){
                    $arr['toarea11']='checked';
                }
                if($row['toarea']=='贵阳'){
                    $arr['toarea12']='checked';
                }*/
            }elseif($row['totype']=='post'){
                $arr['dipcom']='none';
                $arr['totype2']='checked';
                if($row['tonamet']=='own'){
                    $arr['tonamet1']='checked';
                    $arr['diptoname']='none';
                }
                if($row['tonamet']=='home'){
                    $arr['tonamet2']='checked';
                }
                $arr['toname'] = $row['toname'];
                $arr['totel'] = $row['totel'];
                $arr['toadd'] = $row['toadd'];
                $arr['topostcode'] = $row['topostcode'];
                $arr['toremark'] = $row['remark'];
            }elseif($row['totype']=='project'){
                $arr['dipcom']='none';
                $arr['totype3']='checked';
                if($row['tonamet']=='own'){
                    $arr['tonamet1']='checked';
                    $arr['diptoname']='none';
                }
                if($row['tonamet']=='home'){
                    $arr['tonamet3']='checked';
                }
                $arr['toname'] = $row['toname'];
                $arr['totel'] = $row['totel'];
                $arr['toadd'] = $row['toadd'];
                $arr['topostcode'] = $row['topostcode'];
                $arr['toremark'] = $row['remark'];
            }
            $arr['pid'] = $row['rand_key'];
        }
        return $arr;
    }


function model_customer() {
        $arr = array();
        $userid = $_SESSION['USER_ID'];
        $sql = "select userid ,id ,
                toname , totel , toadd , topostcode , remark ,rand_key
                , totype , toarea , tonamet
            from
                moon_cakes
            where
                userid='$userid';";
		$query=$this->db->query($sql);
        $i=0;
		$strline='';
        while($row=$this->db->fetch_array($query)){	
		  $strline.="<tr><td>".$row['toname']."</td><td>".$row['totel']."</td><td>".$row['toadd']."</td><td>".$row['topostcode']."</td><td>"
		  .$row['remark']."</td><td><img src='images/images/del.png' class='img' onclick=\"delData(this,'".$row['id']."');\"></td></tr>";
		   
        }
        return $strline;
}



    function model_sumbit() {
        $sql = "select userid from moon_cake where rand_key='" . $_POST['pid'] . "' or userid='".$_SESSION['USER_ID']."' ";
        $row = $this->db->get_one($sql);
        if ($row['userid']) {
            $this->model_update();
        } else {
            $this->model_insert();
        }
    }
	
	function model_addCustomer(){
		$_POST = mb_iconv($_POST);
		//print_r($_POST);
		//die();
        $toname=$_POST['toname'];
	    $totel=$_POST['totel'];
		$toadd=$_POST['toadd'];
	    $topostcode=$_POST['topostcode'];
		$remark=$_POST['remark'];
		
		/*try {
			if(time()>strtotime($this->limitdt)){
				throw new Exception('Deadline :'.$this->limitdt);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		*/
		if(is_array($toname)){
		   foreach($toname as $key=>$val){
		     $sql = "insert into moon_cakes
                ( userid , totype , tonamet , toname , totel , toadd , topostcode , remark , rand_key  )
                values('" . $_SESSION['USER_ID'] . "', 'customer' , ''  , '" . $toname[$key] . "' ,'" . $totel[$key] . "' ,'" . $toadd[$key] . "' ,'" . $topostcode[$key] . "' , '" . $remark[$key] . "' ,
                    '" . get_rand_key() . "' )";
				try {
					$this->db->query_exc($sql);
				} catch (Exception $e) {
					echo $e->getMessage();
				}	
		   }
		  $msg='1';
		}else{
		   $msg='2';
		}

		return $msg;
		
		
	}

    function model_stat_sea(){
        $checkc=isset($_POST['seacom'])?$_POST['seacom']:'';
        $checkd=isset($_POST['seadept'])?$_POST['seadept']:'';
        $checku=isset($_POST['seaname'])?$_POST['seaname']:'';
        $checks=isset($_POST['seasta'])?$_POST['seasta']:'';
        $checkt=isset($_POST['seatype'])?$_POST['seatype']:'';
        $s1=$checks=='1'?'selected':' ';
        $s2=$checks=='2'?'selected':' ';
        $s3=$checks=='3'?'selected':' ';
		$s4=$checks=='4'?'selected':' ';
        $t1=$checkt=='com'?'selected':' ';
        $t2=$checkt=='post'?'selected':' ';
        $t3=$checkt=='project'?'selected':' ';
		$t4=$checkt=='project'?'selected':' ';
        $res='';
        $res.=' 公司：<input type="text" name="seacom" size="18" value="'.$checkc.'" >';
        $res.=' 部门：<input type="text" name="seadept" size="18" value="'.$checkd.'" >';
        $res.=' 员工：<input type="text" name="seaname" size="18" value="'.$checku.'" >';
        $res.=' 状态：
            <select name="seasta">
                <option value="1" '.$s1.' >不限</option>
                <option value="2" '.$s2.' >已填</option>
                <option value="3" '.$s3.' >未填</option>
            </select> 
            ';
        $res.='领取方式：
            <select name="seatype">
                <option value="" >不限</option>
                <option value="com" '.$t1.' >公司</option>
                <option value="post" '.$t2.' >邮寄</option>
                <option value="project" '.$t3.' >项目</option>
                <option value="customer" '.$t4.' >客户</option>
            </select> ';
        return $res;
    }
    function model_stat_list(){
        $res='';
        $checkc=isset($_POST['seacom'])?$_POST['seacom']:'';
        $checkd=isset($_POST['seadept'])?$_POST['seadept']:'';
        $checku=isset($_POST['seaname'])?$_POST['seaname']:'';
        $checks=isset($_POST['seasta'])?$_POST['seasta']:'';
        $checkt=isset($_POST['seatype'])?$_POST['seatype']:'';
        $sqlStr="";
        if($checkc){
            $sqlStr.=" and i.namecn like '%$checkc%' ";
        }
        if($checkd){
            $sqlStr.=" and d.dept_name like '%$checkd%' ";
        }
        if($checku){
            $sqlStr.=" and u.user_name like '%$checku%' ";
        }
        if($checks==2){
            $sqlStr.=" and h.user_id=m.userid ";
        }
        if($checks==3){
            $sqlStr.=" and h.user_id not in ( select userid from moon_cake  ) ";
        }
        if($checkt){
            $sqlStr=" and m.totype='$checkt' ";
        }
        $sql="select
            d.dept_name as dn,p.userNo as userNo , u.user_name as un , m.toname ,m.totel , m.toadd , m.topostcode , m.remark , m.totype
            , m.toarea , m.tonamet , i.namecn , p.regionname
            from
                hrms h
                left join moon_cake m on (h.user_id=m.userid)
                left join user u on (h.user_id=u.user_id)
                left join department d on (u.dept_id=d.dept_id)
                left join branch_info i on (i.namept=u.company)
                left join oa_hr_personnel p on (p.userno=h.usercard)
            where
                u.del='0'
                and u.has_left='0' and u.usertype='1'
                and d.dept_id<>'1'
                and u.ready_left<>'1'
                $sqlStr
            order by d.dept_id 
            ";
        $query=$this->db->query($sql);
        $i=0;
        while($row=$this->db->fetch_array($query)){
            $i++;
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td height="20">'.$i.'</td>
                    <td>'.$row['namecn'].'</td>
                    <td>'.$row['dn'].'</td>
                    <td>'.$row['un'].'</td>
					<td>'.$row['userNo'].'</td>
                    <td>'.$this->tt[$row['totype']].'</td>
                    <td>'.$row['toarea'].'</td>
                    <td>'.$this->tn[$row['tonamet']].'</td>
                    <td>'.$row['toname'].'</td>
                    <td style="mso-number-format:"\@";">'.$row['totel'].'</td>
                    <td>'.$row['toadd'].'</td>
                    <td>'.$row['topostcode'].'</td>
                    <td>'.$row['remark'].'</td>
                </tr>';
        }
        return $res;
    }
    function model_stat_excel(){
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                <tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;">
                    <td style="height: 23px;" width="5%">序号</td>
                    <td width="5%">公司</td>
                    <td width="10%">部门</td>
                    <td width="8%">姓名</td>
					<td width="8%">工号</td>
                    <td width="5%">领取方式</td>
                    <td width="5%">领取区域</td>
                    <td width="5%">收件人类型</td>
                    <td width="8%">收件人</td>
                    <td width="8%">收件人电话</td>
                    <td width="20%">收件地址</td>
                    <td width="8%">邮编</td>
                    <td >备注</td>
               </tr>' . ($this->model_stat_list()) . '
            </table>';
    }
    //********************************显示数据**********************************
    //*********************************析构函数************************************
    function __destruct() {

    }

}
?>