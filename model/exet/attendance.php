<?php
class model_exet_attendance extends model_base
{

    public $page;
    public $num;
    public $start;
    public $db;
    public $halfarr;//������Ƶ�

    //*******************************���캯��***********************************
    function __construct(){
        parent::__construct();
        $this->db = new mysql();
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
        $this->halfarr=array(
            'begin'=>array(0=>' 09:00:00',1=>' 12:00:00')
            ,'end'=>array(0=>' 12:00:00',1=>' 18:00:00')
        );
    }

    //*********************************���ݴ���********************************

    /**
     * ��������
     *
     */
    function model_insert(){

    }

    /**
     * ��������
     *
     */
    function model_update(){

    }

    /**
     * ɾ������
     *
     */
    function model_deltet(){

    }

    //********************************��ʾ����**********************************

    /**
     * �б�
     */
    function model_showlist(){
        
    }

    function model_seachlist(){
        
        $str='';
        $seay=isset($_POST['seay'])?$_POST['seay']:date('Y');
        $seam=isset($_POST['seam'])?$_POST['seam']:date('n');
        $str.='��ݣ�<select name="seay">';
        for($i=2009;$i<=date('Y');$i++){
            if($seay==$i){
                $str.='<option value='.$i.' selected>'.$i.'</option>';
            }else{
                $str.='<option value='.$i.'>'.$i.'</option>';
            }
        }
        $str.='</select>';
        $str.='&nbsp;�·ݣ�<select name="seam">';
        for($i=1;$i<=12;$i++){
            if($seam==$i){
                $str.='<option value='.$i.' selected>'.$i.'</option>';
            }else{
                $str.='<option value='.$i.'>'.$i.'</option>';
            }
        }
        $str.='</select>';
        $str.='&nbsp;���ţ�<input type="type" name="seadept"  value="'.$_POST['seadept'].'" />';
        $str.='&nbsp;Ա����<input type="type" name="seauser"  value="'.$_POST['seauser'].'" />';
        $str.='&nbsp;<input type="submit" class="btn"  value="��ѯ" />';
		$str.='&nbsp;<input type="submit" class="btn"  id="exportbtn" value="Excel����" onClick="exToExcel();"  />';
        $sql="select count(*) as mcf from hols_sta where syear='".$seay."' and smon='".$seam."' and checkflag<>'1' ";
        $res=$this->db->get_one($sql);
        if(!empty($res['mcf'])){
            $str.='&nbsp;<input type="button" class="btn" onclick="handupstat()" value="�ύ�ܼ�" />';
        }
        return $str;
    }
    /**
     * 
     */
    function model_monthsta(){
        $sy=date("Y");
        $sm=date("n");
        $str='';
        $si=0;
        $pi=0;
        $holsInfo_S=array();
        $holsInfo_P=array();
        $holsUser=array();
        $seaSql='';
        $seay=isset($_POST['seay'])?$_POST['seay']:$sy;
        $seam=isset($_POST['seam'])?$_POST['seam']:$sm;
        if($_POST['seadept']){
            $seaSql.=" and d.dept_name like '%".$_POST['seadept']."%' ";
        }
        if($_POST['seauser']){
            $seaSql.=" and u.user_name like '%".$_POST['seauser']."%' ";
        }
        if($seay){
            $seaSql.=" and s.syear = '".$seay."' ";
        }
        if($seam){
            $seaSql.=" and s.smon = '".$seam."' ";
        }
        //��ʼ����
        if($sy==$seay&&$sm==$seam){
            $this->model_ini();
        }
        $sql="select
                s.userid ,b.userNo, s.type , s.begindt , s.enddt , s.rundays , s.workdays , s.publicdays , s.realdays , s.checkflag ,
                u.user_name , d.dept_name , s.id , s.beginhalf , s.endhalf
            from
                hols_sta s
                left join user u on (s.userid=u.user_id)
				left join oa_hr_personnel b on (b.userAccount=u.user_id)
                left join department d on (s.deptid=d.dept_id)
            where
                u.del='0'
                and s.checkflag <> '2'
                $seaSql
            order by s.deptid , s.userid , s.type ,s.begindt ";
        $query=$this->_db->query($sql,true);
        while($rs=$this->_db->fetch_array($query)){
            if($rs['type']=="����"){
                $ta['id']=$rs['id'];
                $ta['begindt']=$rs['begindt'];
                $ta['enddt']=$rs['enddt'];
                $ta['rundays']=$rs['rundays'];
                $ta['workdays']=$rs['workdays'];
                $ta['publicdays']=$rs['publicdays'];
                $ta['realdays']=$rs['realdays'];
                $ta['checkflag']=$rs['checkflag'];
                $ta['beginhalf']=$rs['beginhalf'];
                $ta['endhalf']=$rs['endhalf'];
                $holsInfo_S[$rs['userid']][]=$ta;
            }
            if($rs['type']=="�¼�"){
                $ta['id']=$rs['id'];
                $ta['begindt']=$rs['begindt'];
                $ta['enddt']=$rs['enddt'];
                $ta['rundays']=$rs['rundays'];
                $ta['workdays']=$rs['workdays'];
                $ta['publicdays']=$rs['publicdays'];
                $ta['realdays']=$rs['realdays'];
                $ta['checkflag']=$rs['checkflag'];
                $ta['beginhalf']=$rs['beginhalf'];
                $ta['endhalf']=$rs['endhalf'];
                $holsInfo_P[$rs['userid']][]=$ta;
            }
            $holsUser[$rs['userid']]['deptname']=$rs['dept_name'];
            $holsUser[$rs['userid']]['username']=$rs['user_name'];
			$holsUser[$rs['userid']]['userno']=$rs['userNo'];
        }
        if(is_array($holsUser)){
            foreach($holsUser as $key=>$val){
                $jskey=str_replace(".", "_", $key);
                $rsp=count($holsInfo_S[$key])>count($holsInfo_P[$key])?count($holsInfo_S[$key]):count($holsInfo_P[$key]);
                $ptol=0;
                $stol=0;
                for($i=0;$i<$rsp;$i++){
                    if($holsInfo_S[$key][$i]['realdays']){
                        $stol=$stol+$holsInfo_S[$key][$i]['realdays'];
                    }
                    if($holsInfo_P[$key][$i]['realdays']){
                        $ptol=$ptol+$holsInfo_P[$key][$i]['realdays'];
                    }
                }
                for($i=0;$i<$rsp;$i++){

                    $str.='<tr id="tr_'.$holsInfo_P[$key][$i]['id'].'_'.$holsInfo_S[$key][$i]['id'].'">';
                    if($i==0){
                        $str.=' <td nowrap rowspan="'.$rsp.'">'.$val['username'].'</td>
						 		<td nowrap rowspan="'.$rsp.'">'.$val['userno'].'</td>
                                <td nowrap rowspan="'.$rsp.'">'.$val['deptname'].'</td>';
                    }
                    $cstyle='';
                    if($holsInfo_P[$key][$i]['checkflag']=='0'){
                        $cstyle=' style="color:blue" ';
                    }
                    $bhalf=$holsInfo_P[$key][$i]['beginhalf']=='1'?' ����':'';
                    $ehalf=$holsInfo_P[$key][$i]['endhalf']=='0'?' ����':'';
                    $str.=' <td nowrap '.$cstyle.'>'.$holsInfo_P[$key][$i]['begindt'].$bhalf.'</td>
                                <td nowrap '.$cstyle.'>'.$holsInfo_P[$key][$i]['enddt'].$ehalf.'</td>
                                <td nowrap '.$cstyle.'>'.$holsInfo_P[$key][$i]['rundays'].'</td>';
                    if($holsInfo_P[$key][$i]['checkflag']=='1'){//������
                        $str.='<td nowrap '.$cstyle.' id="work_'.$holsInfo_P[$key][$i]['id'].'" >'.$holsInfo_P[$key][$i]['workdays'].'</td>';
                    }else{
                        $str.='<td nowrap '.$cstyle.' id="work_'.$holsInfo_P[$key][$i]['id'].'"
                                onmouseover="span_show(this,\''.$holsInfo_P[$key][$i]['id'].'\',\''.$jskey.'\',\'work\')"
                                onmouseout="span_hide(\'work\')" >'
                            .$holsInfo_P[$key][$i]['workdays'].'</td>';
                    }
                    if($holsInfo_P[$key][$i]['checkflag']=='1'){//�ڼ���
                        $str.='<td nowrap '.$cstyle.' id="pub_'.$holsInfo_P[$key][$i]['id'].'" >'.$holsInfo_P[$key][$i]['publicdays'].'</td>';
                    }else{
                        $str.='<td nowrap '.$cstyle.' id="pub_'.$holsInfo_P[$key][$i]['id'].'"
                                onmouseover="span_show(this,\''.$holsInfo_P[$key][$i]['id'].'\',\''.$jskey.'\')"
                                onmouseout="span_hide()" >'
                            .$holsInfo_P[$key][$i]['publicdays'].'</td>';
                    }
                    $str.='<td nowrap '.$cstyle.' id="rel_'.$holsInfo_P[$key][$i]['id'].'" >'.$holsInfo_P[$key][$i]['realdays'].'</td>';
                    if($i==0){
                        $str.=' <td nowrap  rowspan="'.$rsp.'" id="tol_p_'.$jskey.'" style="color:green" >'.$ptol.'</td>';
                    }
                    $cstyle='';
                    if($holsInfo_S[$key][$i]['checkflag']=='0'){
                        $cstyle=' style="color:blue" ';
                    }
                    $bhalf=$holsInfo_S[$key][$i]['beginhalf']=='1'?' ����':'';
                    $ehalf=$holsInfo_S[$key][$i]['endhalf']=='0'?' ����':'';
                    $str.='<td nowrap '.$cstyle.'>'.$holsInfo_S[$key][$i]['begindt'].$bhalf.'</td>
                                <td nowrap '.$cstyle.'>'.$holsInfo_S[$key][$i]['enddt'].$ehalf.'</td>
                                <td nowrap '.$cstyle.'>'.$holsInfo_S[$key][$i]['rundays'].'</td>';
                    if($holsInfo_S[$key][$i]['checkflag']=='1'){
                        $str.='<td nowrap '.$cstyle.' id="work_'.$holsInfo_S[$key][$i]['id'].'" >'.$holsInfo_S[$key][$i]['workdays'].'</td>';
                    }else{
                        $str.='<td nowrap '.$cstyle.' id="work_'.$holsInfo_S[$key][$i]['id'].'"
                                onmouseover="span_show(this,\''.$holsInfo_S[$key][$i]['id'].'\',\''.$jskey.'\',\'work\')"
                                onmouseout="span_hide(\'work\')" >'
                                .$holsInfo_S[$key][$i]['workdays'].'</td>';
                    }
                    if($holsInfo_S[$key][$i]['checkflag']=='1'){
                        $str.='<td nowrap '.$cstyle.' id="pub_'.$holsInfo_S[$key][$i]['id'].'" >'.$holsInfo_S[$key][$i]['publicdays'].'</td>';
                    }else{
                        $str.='<td nowrap '.$cstyle.' id="pub_'.$holsInfo_S[$key][$i]['id'].'"
                                onmouseover="span_show(this,\''.$holsInfo_S[$key][$i]['id'].'\',\''.$jskey.'\')"
                                onmouseout="span_hide()" >'
                                .$holsInfo_S[$key][$i]['publicdays'].'</td>';
                    }
                    $str.='<td nowrap '.$cstyle.' id="rel_'.$holsInfo_S[$key][$i]['id'].'" >'.$holsInfo_S[$key][$i]['realdays'].'</td>
                               ';
                   if($i==0){
                        $str.=' <td nowrap  rowspan="'.$rsp.'" id="tol_s_'.$jskey.'" style="color:green"  >'.$stol.'</td>';
                    }
                        $str.='</tr>';
                }
            }
        }
        return $str;
    }

    function model_ini() {
        $dateUtil= new includes_class_dateutil();
        $ymb=date('Y-m',mktime(0, 0, 0, date('m')-1, 1,   date('Y'))).'-25';
        if($ymb=='2011-01-25'){
            $ymb='2011-01-24';
        }
        $yme=date('Y-m').'-24';
        if($yme=='2011-01-24'){
            $yme='2011-01-23';
        }
        $sy=date("Y");
        $sm=date("n");
        try {
            $this->_db->query("START TRANSACTION");
			
			//ɾ��δ���������ٵ�����
			$sql = "delete hs from hols_sta hs where IFNULL(hs.CheckFlag,0) = 0 and not EXISTS (select 1 from hols h where h.ID = hs.AttdId)";
			$query = $this->_db->query ( $sql, true );
			
			$sql="select
                    h.userid , h.type , h.begindt , h.enddt , h.id , u.dept_id , h.beginhalf , h.endhalf 
                from
                    hols h
                    left join user u on (h.userid=u.user_id)
                where
                    h.exastatus='���'
                    and ( h.type='�¼�' or h.type='����' )
                    and
                        (  (to_days('$ymb')<=to_days(h.begindt) and to_days(h.begindt)<=to_days('$yme')  )
                            or ( to_days('$ymb')>=to_days(h.begindt) and to_days('$ymb')<=to_days(h.enddt) ) )
                    and h.id not in 
                        ( select attdid from hols_sta where syear='$sy' and smon='$sm' ) ";
            $query=$this->_db->query($sql,true);
            while ($rs = $this->_db->fetch_array($query)){
                $tdb=strtotime($rs['begindt'])<strtotime($ymb)?$ymb:$rs['begindt'];
                $tde=strtotime($rs['enddt'])>strtotime($yme)?$yme:$rs['enddt'];
                $thb=$tdb==$rs['begindt']?$rs['beginhalf']:'0';
                $the=$tde==$rs['enddt']?$rs['endhalf']:'1';
                $runDays=$dateUtil->daysRunningdays($tdb, $tde , $thb , $the);
                $holeDays=$dateUtil->getHoleWorkDays($tdb, $tde , $thb , $the);
                $sql="insert into
                        hols_sta
                    set
                        deptid='".$rs['dept_id']."' ,
                        attdid='".$rs['id']."' ,
                        userid='".$rs['userid']."',
                        type='".$rs['type']."',
                        begindt='$tdb',
                        enddt='$tde',
                        rundays='$runDays',
                        workdays='".($holeDays['hwdts'])."',
                        realDays='".($holeDays['hdts'])."',
                        publicdays='".($holeDays['fjdts'])."',
                        beginhalf='$thb',
                        endhalf='$the',
                        handler='".$_SESSION['USER_ID']."',
                        updatedt=now(),syear='$sy',smon='$sm' ";
                $this->_db->query($sql,true);
            }
            $this->_db->query("COMMIT");
        } catch (Exception $e) {
            $this->_db->query("ROLLBACK");
            $filename=__FILE__;
            writeToLog('���ݼ��¶�ͳ�ƣ�' . $e->getMessage(), 'hols.log',$sql,$filename);
            return false;
        }
        return true;
    }
    /**
     * ����ͳ��������
     */
    function model_dept_seachlist(){

        $str='';
        $seay=isset($_POST['seay'])?$_POST['seay']:date('Y');
        $seam=isset($_POST['seam'])?$_POST['seam']:date('n');
        $str.='��ݣ�<select name="seay">';
        for($i=2009;$i<=date('Y');$i++){
            if($seay==$i){
                $str.='<option value='.$i.' selected>'.$i.'</option>';
            }else{
                $str.='<option value='.$i.'>'.$i.'</option>';
            }
        }
        $str.='</select>';
        $str.='&nbsp;�·ݣ�<select name="seam">';
        for($i=1;$i<=12;$i++){
            if($seam==$i){
                $str.='<option value='.$i.' selected>'.$i.'</option>';
            }else{
                $str.='<option value='.$i.'>'.$i.'</option>';
            }
        }
        $str.='</select>';
        $str.='&nbsp;���ţ�<input type="type" name="seadept"  value="'.$_POST['seadept'].'" />';
        $str.='&nbsp;Ա����<input type="type" name="seauser"  value="'.$_POST['seauser'].'" />';
        $str.='&nbsp;<input type="submit" class="btn"  value="��ѯ" />';
        return $str;
    }
/**
 * ����ͳ���б�
 */
    function model_deptsta(){
        $sy=date("Y");
        $sm=date("n");
        $str='';
        $si=0;
        $pi=0;
        $holsInfo_S=array();
        $holsInfo_P=array();
        $holsUser=array();
        $seaSql='';
        $seay=isset($_POST['seay'])?$_POST['seay']:$sy;
        $seam=isset($_POST['seam'])?$_POST['seam']:$sm;
        if($seay==$sy&&$seam==$sm){
            $changeflag=true;
        }else{
            $changeflag=false;
        }
        if($_POST['seadept']){
            $seaSql.=" and d.dept_name like '%".$_POST['seadept']."%' ";
        }
        if($_POST['seauser']){
            $seaSql.=" and u.user_name like '%".$_POST['seauser']."%' ";
        }
        if($seay){
            $seaSql.=" and s.syear = '".$seay."' ";
        }
        if($seam){
            $seaSql.=" and s.smon = '".$seam."' ";
        }
        //��ʼ����
        if($sy==$seay&&$sm==$seam){
            $this->model_ini();
        }
        $dppow=$this->model_dp_pow();
        if(count($dppow['1'])){
            $seaSql.=" and u.dept_id in (".implode(',', $dppow['1']).") ";
        }
        $sql="select
                s.userid , s.type , s.begindt , s.enddt , s.rundays , s.workdays , s.publicdays , s.saldays , s.checkflag ,
                u.user_name , d.dept_name , s.id , s.beginhalf , s.endhalf
            from
                hols_sta s
                left join user u on (s.userid=u.user_id)
                left join department d on (s.deptid=d.dept_id)
            where
                u.del='0'
                and u.has_left='0'
                and s.checkflag>=1
                $seaSql
            order by s.deptid , s.userid , s.type ,s.begindt ";
        $query=$this->_db->query($sql,true);
        while($rs=$this->_db->fetch_array($query)){
            if($rs['type']=="����"){
                $ta['id']=$rs['id'];
                $ta['begindt']=$rs['begindt'];
                $ta['enddt']=$rs['enddt'];
                $ta['rundays']=$rs['rundays'];
                $ta['workdays']=$rs['workdays'];
                $ta['publicdays']=$rs['publicdays'];
                $ta['realdays']=$rs['realdays'];
                $ta['checkflag']=$rs['checkflag'];
                $ta['beginhalf']=$rs['beginhalf'];
                $ta['endhalf']=$rs['endhalf'];
                $ta['saldays']=$rs['saldays'];
                $holsInfo_S[$rs['userid']][]=$ta;
            }
            if($rs['type']=="�¼�"){
                $ta['id']=$rs['id'];
                $ta['begindt']=$rs['begindt'];
                $ta['enddt']=$rs['enddt'];
                $ta['rundays']=$rs['rundays'];
                $ta['workdays']=$rs['workdays'];
                $ta['publicdays']=$rs['publicdays'];
                $ta['realdays']=$rs['realdays'];
                $ta['checkflag']=$rs['checkflag'];
                $ta['beginhalf']=$rs['beginhalf'];
                $ta['endhalf']=$rs['endhalf'];
                $ta['saldays']=$rs['saldays'];
                $holsInfo_P[$rs['userid']][]=$ta;
            }
            $holsUser[$rs['userid']]['deptname']=$rs['dept_name'];
            $holsUser[$rs['userid']]['username']=$rs['user_name'];
        }
        if(is_array($holsUser)){
            foreach($holsUser as $key=>$val){
                $jskey=str_replace(".", "_", $key);
                $rsp=count($holsInfo_S[$key])>count($holsInfo_P[$key])?count($holsInfo_S[$key]):count($holsInfo_P[$key]);
                $ptol=0;
                $stol=0;
                for($i=0;$i<$rsp;$i++){
                    if($holsInfo_S[$key][$i]['realdays']){
                        $stol=$stol+$holsInfo_S[$key][$i]['realdays'];
                    }
                    if($holsInfo_P[$key][$i]['realdays']){
                        $ptol=$ptol+$holsInfo_P[$key][$i]['realdays'];
                    }
                }
                for($i=0;$i<$rsp;$i++){

                    $str.='<tr id="tr_'.$holsInfo_P[$key][$i]['id'].'_'.$holsInfo_S[$key][$i]['id'].'">';
                    if($i==0){
                        $str.=' <td nowrap rowspan="'.$rsp.'">'.$val['username'].'</td>
                                <td nowrap rowspan="'.$rsp.'">'.$val['deptname'].'</td>';
                    }
                    $cstyle='';
                    if($changeflag){
                        $cstyle=' style="color:blue" ';
                    }
                    $bhalf=$holsInfo_P[$key][$i]['beginhalf']=='1'?' ����':'';
                    $ehalf=$holsInfo_P[$key][$i]['endhalf']=='0'?' ����':'';
                    $str.=' <td nowrap '.$cstyle.'>'.$holsInfo_P[$key][$i]['begindt'].$bhalf.'</td>
                                <td nowrap '.$cstyle.'>'.$holsInfo_P[$key][$i]['enddt'].$ehalf.'</td>';
                    if($i==0){
                        if($changeflag){
                            $str.='<td nowrap '.$cstyle.' rowspan="'.$rsp.'" id="pub_'.$holsInfo_P[$key][$i]['id'].'"
                                onmouseover="span_show(this,\''.$holsInfo_P[$key][$i]['id'].'\',\''.$jskey.'\')"
                                onmouseout="span_hide()" >'
                                .$holsInfo_P[$key][$i]['saldays'].'</td>';
                        }else{
                            $str.='<td nowrap '.$cstyle.' rowspan="'.$rsp.'">'.$holsInfo_P[$key][$i]['saldays'].'</td>';
                        }
                    }
                    $cstyle='';
                    if($changeflag){
                        $cstyle=' style="color:blue" ';
                    }
                    $bhalf=$holsInfo_S[$key][$i]['beginhalf']=='1'?' ����':'';
                    $ehalf=$holsInfo_S[$key][$i]['endhalf']=='0'?' ����':'';
                    $str.='<td nowrap '.$cstyle.'>'.$holsInfo_S[$key][$i]['begindt'].$bhalf.'</td>
                                <td nowrap '.$cstyle.'>'.$holsInfo_S[$key][$i]['enddt'].$ehalf.'</td>';
                   if($i==0){
                       if($changeflag){
                           $str.='<td nowrap '.$cstyle.' rowspan="'.$rsp.'" id="pub_'.$holsInfo_S[$key][$i]['id'].'"
                                onmouseover="span_show(this,\''.$holsInfo_S[$key][$i]['id'].'\',\''.$jskey.'\')"
                                onmouseout="span_hide()" >'
                                .$holsInfo_S[$key][$i]['saldays'].'</td>';
                       }else{
                           $str.='<td nowrap '.$cstyle.' rowspan="'.$rsp.'" >'.$holsInfo_S[$key][$i]['saldays'].'</td>';
                       }
                    }
                        $str.='</tr>';
                }
            }
        }
        return $str;
    }
    /**
     *Ȩ�޶�ȡ
     */
    function model_dp_pow(){
        $dppow=array();
        $sql="select majorid , vicemanager , dept_id
            from department d
            where
                ( find_in_set('".$_SESSION['USER_ID']."',d.majorid)>0
                    or find_in_set('".$_SESSION['USER_ID']."',d.vicemanager)
                ) ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            if(trim($row['majorid'])){
                if(strpos($row['majorid'], strtolower($_SESSION['USER_ID']))===false){
                    $dppow['2'][]=$row['dept_id'];
                }else{
                    $dppow['1'][]=$row['dept_id'];
                }
            }else{
                $dppow['1'][]=$row['dept_id'];
            }
        }
        if($_SESSION['USER_PRIV']==80){
            $sql="select majorid , vicemanager , dept_id from department d ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                if(trim($row['majorid'])||trim($row['vicemanager'])){
                    $dppow['2'][]=$row['dept_id'];
                }else{
                    $dppow['1'][]=$row['dept_id'];
                }
            }
        }
        return $dppow;
    }
    /**
     *�������ݼ�
     * @param type $cdt
     * @param $nowY ������
     * @return int 
     */
    function get_year_hole_days($cdt,$nowY='') {
        
        $comeDate=$cdt;//��ְ����
        $comeTime=strtotime($comeDate);//ʱ���
        $comeY=date('Y', $comeTime);//��ְ���
        $nowY=$nowY==''?date("Y"):$nowY;
        //��������
        if ($comeY == $nowY) {//����
            $haveDate = 0;
        } elseif ($comeY + 1 == $nowY) {//ȥ��
            if (date('z', strtotime($comeDate)) < date('z', strtotime($comeY . '-07-01'))) {//����ǰ
                $haveDate = 5;
            } else {
                $haveDate = 3;
            }
        } elseif ($comeY + 1 < $nowY) {
            $haveDate = 6 + ($nowY - $comeY - 2) * 1;
        }
        if ($haveDate > 15) {//���15��
            $haveDate = 15;
        }
        return $haveDate;
    }
    /**
     * ���
     */
    function model_ck_dt(){
        $atype=$_GET['atype'];
        $db=$_GET['db'];
        $de=$_GET['de'];
        $hb=$_GET['hb'];
        $he=$_GET['he'];
        $res=array('error'=>'0');
        $dbtime=strtotime($db.$this->halfarr['begin'][$hb]);
        $detime=strtotime($de.$this->halfarr['end'][$he]);
        $sql="select count(*) as am from hols 
            where ( ( begin_time>=". $dbtime ."  
                        and begin_time>=". $detime ." )
                or ( end_time>=". $dbtime ."  
                        and end_time>=". $detime ." ) 
                ) and userid='".$_SESSION['USER_ID']."' ";
        $query=$this->db->get_one($sql);
        if(!empty($query['am'])&&$query['am']>0){
            $res['error']='�Ѵ�������ʱ�������ڵ���ٵ���������ѡ��ʱ�䡣';
        }else{
            //��ȡ����Ĺ�����
            
            //
            if($atype=='���ݼ�'){//���
                if(date('Y',$dbtime)!=date('Y',$detime)){
                    $res['error']='���ݼ��ݼ����䲻�ܿ�����У���֪����';
                }else{
                    $sql="select sum(h.dta) as am ,  hr.come_date   from hols h
                        left join hrms hr on (h.userid=hr.user_id) 
                        where h.type='���ݼ�' and year(h.begindt)=".date('Y',$dbtime)." 
                            and h.userid='".$_SESSION['USER_ID']."' 
                        group by h.userid ";
                    $query=$this->db->get_one($sql);
                    $aeddt=empty($query['am'])?0:$query['am'];
                    $hasdt=$this->get_year_hole_days($query['come_date'],date('Y',$dbtime));
                    
                }
            }
        }
        
    }
    
    //*********************************��������************************************
    function __destruct(){

    }

}
?>
