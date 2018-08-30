<?php
class model_system_branch_util extends model_base {

    public $page;
    public $num;
    public $start;
    public $db;
    public $globalUtil;

    //*******************************���캯��***********************************
    function __construct() {
        $_POST=mb_iconv($_POST);
        parent::__construct();
        $this->db = new mysql();
        $this->globalUtil= new includes_class_global();
        $this->page = intval($_GET['page']) ? intval($_GET[page]) : 1;
        $this->start = ($this->page == 1) ? 0 : ($this->page - 1) * pagenum;
        $this->num = intval($_GET['num']) ? intval($_GET['num']) : false;
    }

    //*********************************���ݴ���********************************

    /**
     * ��������
     *
     */
    function model_insert() {

    }

    /**
     * ��������
     *
     */
    function model_update() {

    }

    /**
     * ɾ������
     *
     */
    function model_deltet() {

    }

    //********************************��ʾ����**********************************

    /**
     * �б�
     */
    function model_showlist() {
        $res='';
        $sql="select id , namecn , nameen , namept , parentname , rand_key , comcard
            from branch_info  order by parentid ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $res.='<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['namecn'].'</td>
                <td>'.$row['nameen'].'</td>
                <td>'.$row['namept'].'</td>
                <td>'.$row['comcard'].'</td>
                <td>'.$row['parentname'].'</td>
                <td>
                    <input type="button" name="btn_edit" value="�༭" 
                        alt="?model=system_branch_util&action=edit&key='.$row['rand_key'].'&TB_iframe=true&modal=false&height=500&width=700"
                        title="�༭" class="thickbox">
                    <input type="button" name="btn_brah" value="�½��ӹ�˾"
                        alt="?model=system_branch_util&action=new&key='.$row['rand_key'].'&TB_iframe=true&modal=false&height=500&width=700"
                        title="�½��ӹ�˾" class="thickbox">
                </td>
                </tr>';
        }
        return $res;
    }
    
    function model_showedit(){
        $key=$_GET['key'];
        $pararr=array(0=>'�ܹ�˾');
        $sql="select namecn , nameen , namept , parentid  from branch_info where rand_key='".$key."' ";
        $info=$this->db->get_one($sql);
        $sql="select namecn , id from branch_info where rand_key<>'".$key."' order by parentid ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $pararr[$row['id']]=$row['namecn'];
        }
        $res='<tr>
                <td class="tableleft" width="25%">��˾�������ģ�</td>
                <td class="td_left">
                    <input type="text" value="'.$info['namecn'].'" name="namecn" id="namecn" size="38"/>
                    <input type="hidden" value="'.$key.'" name="key" id="key" />
                </td>
            </tr>
            <tr>
                <td class="tableleft">��˾����Ӣ�ģ�</td>
                <td class="td_left"><input type="text" value="'.$info['nameen'].'" name="nameen" id="nameen" size="38"/></td>
            </tr>
            <tr>
                <td class="tableleft">��˾���</td>
                <td class="td_left"><input type="text" value="'.$info['namept'].'" name="namept" id="namept" size="38"/></td>
            </tr>
            <tr>
                <td class="tableleft">�ϼ���˾</td>
                <td class="td_left">
                    <select name="parentid" id="parentid" >';
          if(!empty($pararr)){
              foreach($pararr as $key=>$val){
                  if($key==$info['parentid']){
                      $res.='<option value="'.$key.'" selected>'.$val.'</option>';
                  }else{
                      $res.='<option value="'.$key.'">'.$val.'</option>';
                  }
              }
          }
          $res.=     '</select>
                </td>
            </tr>';
        return $res;
    }
    function model_edit_in(){
        $sub=$_POST['sub'];
        $id=$_POST['id'];
        $namecn=$_POST['namecn'];
        $nameen=$_POST['nameen'];
        $namept=$_POST['namept'];
        $parentid=$_POST['parentid'];
        if($parentid=='0'){
            $parentna='�ܹ�˾';
        }else{
            $sql="select namecn from branch_info where id='".$parentid."' ";
            $parinf=$this->db->get_one($sql);
            $parentna=$parinf['namecn'];
        }
        try {
            if($sub=='edit'){
                $sql="update branch_info
                    set
                        namecn='".$namecn."' , nameen='".$nameen."' ,
                        namept='".$namept."' , parentid='".$parentid."' , parentname='".$parentna."'
                    where rand_key = '".$id."' ";
                $this->db->query($sql);
            }
            $responce->id = $id;
        } catch (Exception $e) {
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('�ӹ�˾', $id, '�ӹ�˾�޸��½�', 'ʧ��', $e->getMessage());
        }
        return $responce;
    }
    
    function model_new_in(){
        set_time_limit(360);
        $sub=$_POST['sub'];
        $id=$_POST['id'];
        $namecn=$_POST['namecn'];
        $nameen=$_POST['nameen'];
        $namept=$_POST['namept'];
        $parentid=$_POST['parentid'];
        $host=$_POST['host'];
        $db=$_POST['db'];
        $dbname=$_POST['dbname'];
        $dbpw=$_POST['dbpw'];
        try {
            if($sub=='new'){
                /*
                $mydb=new includes_classes_mydb($host, $dbname, $dbpw, $db);
                $mydb->query("set names utf8");
                $dbfile=file_get_contents(WEB_TOR.'model\system\branch\install.sql');
                if($dbfile){
                    $dbarr=explode(';', $dbfile);
                }
                if(!empty($dbarr)){
                    foreach ($dbarr as $val) {
                        if(!empty($val)&&$mydb->query($val)==false){
                            throw new Exception('Data Import failed');
                        }
                    }
                }
                 * 
                 */
                $confstr='<?php
$dbservertype = "mysql";
$servername = "'.$host.'";
$dbusername = "'.$db.'";
$dbpassword = "'.$dbpw.'";
$dbname = "'.$dbname.'";
$technicalemail = "webmaster@dinglicom.com";
$usepconnect = 1;
$emaildomain = "@dinglicom.com";
$companyname="'.$namecn.'";
?>';
                /*
                if(!file_put_contents(WEB_TOR.'includes\db_connect_'.$namept.'.php', $confstr)){
                    throw new Exception('File Build  failed');
                }
                 * 
                 */
                if($parentid=='0'){
                    $parentna='�ܹ�˾';
                }else{
                    $sql="select namecn from branch_info where id='".$parentid."' ";
                    $parinf=$this->db->get_one($sql);
                    $parentna=$parinf['namecn'];
                }
                $sql="insert into  branch_info
                    ( namecn , nameen , namept , parentid , parentname ) values
                    ( '".$namecn."' , '".$nameen."' , '".$namept."' , '".$parentid."' , '".$parentna."' )
                ";
                $this->db->query($sql);
            }
            $responce->id = $id;
            file_put_contents('xgq.txt',$sql);
        } catch (Exception $e) {
            $responce->error = $e->getMessage();
            $this->globalUtil->insertOperateLog('�ӹ�˾', $id, '�ӹ�˾�½��½�', 'ʧ��', $e->getMessage());
        }
        return $responce;
    }

    function model_branch_list($par='',$own=''){
        $res='<option value="0">�ܹ�˾</option>';
        if(empty($own)){
            $sql="select namecn , id from branch_info where rand_key<>'".$key."' order by parentid ";
        }else{
            $sql="select namecn , id from branch_info order by parentid ";
        }
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            if($par==$row['id']){
                $res.='<option value="'.$row['id'].'" selected>'.$row['namecn'].'</option>';
            }else{
                $res.='<option value="'.$row['id'].'">'.$row['namecn'].'</option>';
            }
        }
        return $res;
    }
    //*********************************��������************************************
    function __destruct() {

    }

}
?>