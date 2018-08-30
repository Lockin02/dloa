<?php
class model_cost_bill_cost extends model_base
{

    public $page;
    public $num;
    public $start;
    public $db;
    public $comsta;
    public $glo; //�������

    //*******************************���캯��***********************************
    function __construct(){
        parent::__construct();
        $this->db = new mysql();
        $this->glo = new includes_class_global();
        $this->comsta = '���';//����¼��
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
    }
    
    //*********************************���ݴ���********************************
    function model_guide($ckt){
        $excelfilename='attachment/cost_guide/'.$ckt.".xls";
        if(empty ($_FILES["xfile"]["tmp_name"])){
            $str='';
        }elseif (!move_uploaded_file( $_FILES["xfile"]["tmp_name"], $excelfilename) ){
            $str='�ϴ�ʧ�ܣ�';
        }else{
            try{
                //��ȡexcel��Ϣ
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile(WEB_TOR.$excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr=$excel->getAllData();
                $excel->Close();
                if(!in_array('����', $excelFields)||!in_array('����', $excelFields)){
                    throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
                }
                if($excelFields){
                    $costTypeSql=array();
                    $sql="select costtypeid , costtypename
                        from cost_type
                        where costtypename in ('".  implode("','", $excelFields)."')
                        group by costtypename ";
                    $query=$this->db->query($sql);
                    while($row=$this->db->fetch_array($query)){
                        $costTypeSql[$row['costtypeid']]=$row['costtypename'];
                    }
                    $str.='<table class="ui-widget-content ui-corner-all"
                                align="center" style="width: 100%;" cellpadding="0"
                                cellspacing="1" border="0">
                                <tr><td colspan="'.count($excelFields).'">
                                <table  >
                                  <tr>
                                      <td>̧ͷ��ɫ��ʾ��</td>
                                      <td style="background-color: #D3E5FA;padding:3px;">�������͸�OA�ǺϿɵ���</td>
                                      <td style="background: #666666;color:#FFFFFF;padding:3px;">OA�����˷������Ͳ��ɵ���</td>
                                      <td style="color:red">*���������Ƿ���ȷ��������ύ��ť�ύ���ݣ�</td>
                                  </tr>
                              </table></td></tr>';
                    $str.='<tr class="trl">';
                    foreach($excelFields as $key=>$val){
                        if($val!='����'&&$val!='����'&&$val!='�ϼ�'&&!in_array($val, $costTypeSql)){
                            $str.='<td style="background: #666666;color:#FFFFFF;">'.$val.'</td>';
                        }  else {
                            $str.='<td >'.$val.'</td>';
                        }
                    }
                    $str.='</tr>';
                    foreach($excelArr['����'] as $key=>$val ){
                        $str.='<tr class="TableLine'.($key%2+1).'">';
                        foreach($excelFields as $vkey=>$vval){
                            $str.='<td>'.$excelArr[$vval][$key].'</td>';
                        }
                        $str.='</tr>';
                    }
                    $str.='</table>';
                }
            }catch(Exception $e){
                $str=$e->getMessage();
            }
        }
        return $str;
    }
    function model_guide_sub($ckt){
        $remark=$_POST['remark'];
        $pro=$_POST['pro'];
        $excelfilename='attachment/cost_guide/'.$ckt.".xls";
        if(file_exists($excelfilename)){//�ļ�����
            try{
                $this->db->query("START TRANSACTION");
                //��ȡexcel��Ϣ
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile(WEB_TOR.$excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr=$excel->getAllData();
                $excel->Close();
                if(!in_array('����', $excelFields)||!in_array('����', $excelFields)){
                    throw new Exception('�������ݱ�ͷ����ȷ����˶��Ƿ��ģ��һ�£�');
                }
                if($excelFields&&$excelArr['����']){
                    $costTypeSql=array();
                    $sql="select costtypeid , costtypename
                        from cost_type
                        where costtypename in ('".  implode("','", $excelFields)."')
                        group by costtypename ";
                    $query=$this->db->query($sql);
                    while($row=$this->db->fetch_array($query)){
                        $costTypeSql[$row['costtypeid']]=$row['costtypename'];
                    }
                    if(empty($pro)){
                        $sql="insert into cost_detail_list
                            (InputMan,InputDate,CostMan,CostDepartID,ProjectNo,Purpose
                                ,Status,CustomerType,isProject,xm_sid,DetailType,CostBelongTo
                            )values
                            ('".$_SESSION['USER_ID']."',now(),'".$_SESSION['USER_ID']."','".$_SESSION['DEPT_ID']."','',''
                                ,'�༭','','0','1','1','1'
                            )";
                    }else{
                        $sql="insert into cost_detail_list
                            (InputMan,InputDate,CostMan,CostDepartID,ProjectNo,Purpose
                                ,Status,CustomerType,isProject,xm_sid,DetailType,CostBelongTo
                            )values
                            ('".$_SESSION['USER_ID']."',now(),'".$_SESSION['USER_ID']."','".$_SESSION['DEPT_ID']."','".$pro."','".$remark."'
                                ,'�༭','','1','1','2','4'
                            )";
                    }
                    $this->db->query($sql);
                    $headid=$this->db->insert_id();
                    $i=0;
                    foreach($excelArr['����'] as $key=>$val){
                        $i++;
                        $detailSql='';
                        foreach($costTypeSql as $ckey=>$cval){
                            if(!empty($excelArr[$cval][$key])){
                                $detailSql.="('$headid','$ckey','".$excelArr[$cval][$key]."','$i',1,'','{assid}'),";
                            }
                        }
                        if(!empty($detailSql)){
                            $sql="insert into cost_detail_assistant
                                    (HeadID,Place,Note,RNo,CostDateBegin
                                        ,CostDateEnd,CreateDT,Creator
                                    )
                                values
                                    ('$headid','','','$i','".$val."'
                                        ,'".$val."'
                                        ,'".$val." ".date("H:i:s")."'
                                        ,'".$_SESSION['USER_ID']."')";
                            $this->db->query($sql);
                            $assid=$this->db->insert_id();//��ȡassistant ID
                            if(empty($pro)){
                                $sql="insert into cost_detail
                                        (HeadID,CostTypeID,CostMoney,RNo,days,Remark,AssID)
                                    values ".trim(str_replace('{assid}', $assid, $detailSql),',');
                            }else{
                                $sql="insert into cost_detail_project
                                        (HeadID,CostTypeID,CostMoney,RNo,days,Remark,AssID)
                                    values ".trim(str_replace('{assid}', $assid, $detailSql),',');
                            }
                            $this->db->query($sql);
                        }
                    }
                }
                $this->db->query("COMMIT");
            }catch(Exception $e){
                $this->db->query("ROLLBACK");
                $msg=$e->getMessage();
            }
        }else{
            $msg='Cannot find files';
        }
        return $msg;
    }
    /**
     * ��Ŀ��Ϣ
     */
    function model_xm(){
        $res=array();
        $sql="select name , projectno from xm where 1 ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $res[$row['projectno']]=$row['name'];
        }
        return $res;
    }
    //*********************************��������************************************
    function __destruct(){

    }

}

?>