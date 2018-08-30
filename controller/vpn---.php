<?php

class controller_vpn extends model_base {

    public $show; // 模板显示

    /**
     * 构造函数
     *
     */

    function __construct() {
        parent::__construct();
        $this->pk = 'id';
        $this->show = new show();
    }

    /**
     * 默认访问显示
     *
     */
    function c_csvde(){
        $filename = "csvde.csv";
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        Header("Cache-Control: public");
        header("Content-type: application/vnd.ms-excel;");
        header("Content-Disposition: filename=" . $filename);
        $data="DN,objectClass,samAccountName,userPrincipalName,DisplayName,userAccoutControl". base64_decode("DQo=");
        $sql = "select
            u.user_id , d.dept_name , e.mobile1 , u.user_name , u.email
            from hrms h
                left join user u on ( h.user_id =u.user_id )
                left join department d on (u.dept_id=d.dept_id)
                left join ecard e on (h.user_id=e.user_id)
            where del='0' and has_left='0' and u.dept_id<>'1' ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $data .= '"'.'CN='.$this->escapeCSV($row['user_name']) .',ou='.$this->escapeCSV($row['dept_name']).',DC=dinglicom,DC=com'. '",'
                    .'user,'
                    . $this->escapeCSV($row['user_id']) . ','
                    .$this->escapeCSV($row['email']) . ','
                    . $this->escapeCSV($row['user_name']).','
                    .'514'
                    . base64_decode("DQo=");
        }
        echo $data;
    }
    function c_index() {
        $filename = "sslvpn.csv";
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        Header("Cache-Control: public");
        header("Content-type: application/vnd.ms-excel;");
        header("Content-Disposition: filename=" . $filename);
        $data="用户,[用户组],[密码],[[区号-]手机号],[虚拟IP],[用户描述]". base64_decode("DQo=");
        $sql = "select
            u.user_id , d.dept_name , e.mobile1
            from hrms h
                left join user u on ( h.user_id =u.user_id )
                left join department d on (u.dept_id=d.dept_id)
                left join ecard e on (h.user_id=e.user_id)
            where del='0' and has_left='0' and u.dept_id<>'1' ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $data .= $this->escapeCSV($row['user_id']) . ','
                    . $this->escapeCSV($row['dept_name']) . ','
                    .'dingli.com,'
                    . substr($this->escapeCSV($row['mobile1']), 0, 11)
                    .','
                    .','
                    . base64_decode("DQo=");
        }
        echo $data;
    }
    
    function c_sg() {
        $filename = "sg.csv";
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        Header("Cache-Control: public");
        header("Content-type: application/vnd.ms-excel;");
        header("Content-Disposition: filename=" . $filename);
        $data="用户名,所属组,IP地址,MAC地址,认证方式,描述,密码". base64_decode("DQo=");
        $sql = "select
            u.user_id , d.dept_name , e.mobile1
            from hrms h
                left join user u on ( h.user_id =u.user_id )
                left join department d on (u.dept_id=d.dept_id)
                left join ecard e on (h.user_id=e.user_id)
            where del='0' and has_left='0' and u.dept_id<>'1' ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $data .= $this->escapeCSV($row['user_id']) . ','
                    . $this->escapeCSV($row['dept_name']) . ','
                    .','
                    .','
                    .','
                    .','
                    .'dingli.com,'
                    . base64_decode("DQo=");
        }
        echo $data;
    }
    function escapeCSV($str) {

        $str = str_replace(array(',', '"', "\n\r"), array('.', '""', ''), $str);
        if ($str == "") {
            $str = '""';
        }
        return $str;
    }

    function c_email() {
        $filename = "email.txt";
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        Header("Cache-Control: public");
        header("Content-type: application/vnd.ms-excel;");
        header("Content-Disposition: filename=" . $filename);
        $data="名称,全名,业务电话,电子邮件,部门信息,职务,手机,性别". base64_decode("DQo=");
        $sql = "select
            u.user_id , d.dept_name , e.mobile1 , u.email , u.user_name , u.password
            from hrms h
                left join user u on ( h.user_id =u.user_id )
                left join department d on (u.dept_id=d.dept_id)
                left join ecard e on (h.user_id=e.user_id)
            where del='0' and has_left='0' and u.dept_id<>'1'  order by come_date ";
        $query = $this->_db->query($sql);
        $i=1001;
        while ($row = $this->_db->fetch_array($query)) {
            $data .= $this->escapeCSV($row['user_id']) . ','
                    .$row['user_name'].','
                    .','
                    .$row['email'].','
                    .$row['dept_name'].','
                    .','
                    .$row['mobile1']
                    .','
                    . base64_decode("DQo=");
            $i++;
        }
        echo $data;
    }
    function c_x(){
        include( WEB_TOR . 'includes/classes/excel_out_class.php');
        $xls = new ExcelXML('gb2312', false, 'My Test Sheet');
        $deptArr=array();
        $sql="select dept_id , dept_name from department ";
        $query = $this->_db->query($sql);
        while($row = $this->_db->fetch_array($query)){
          $deptArr[$row['dept_id']]=$row['dept_name'];
        }
        $processArr=array();
        $sql="select flow_id , id , prcs_name from flow_process  ";
        $query=$this->_db->query($sql);
        while($row=$this->_db->fetch_array($query)){
            $processArr[$row['flow_id']][$row['id']]=$row['prcs_name'];
        }
        $data = array(1=> array ('流程名称','类型', '部门名称', '最小金额'
                ,'最大金额', '审批流程')
                );
        $sql = "select t.flow_id
                  , t.flow_name , f.form_name , t.flow_depts , t.minmoney
                  , t.maxmoney
                from flow_type t 
                left join flow_form_type f on (t.form_id=f.form_id)
                where t.form_id in (153 , 160 ) order by t.form_id  ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $td=explode(',', $row['flow_depts']);
            $tr='';
            $tp='';
            foreach($td as $val){
                $tr.=$deptArr[$val].' ';
            }
            if($processArr[$row['flow_id']]){
                $tp=implode('->',$processArr[$row['flow_id']]);
            }
            $data[]=array(
            $row['flow_name'],$row['form_name'],$tr,$row['minmoney']
            ,$row['maxmoney'],$tp
            );
            
        }
        //print_r($data);
        $xls->addArray($data);
        $xls->generateXML(time());
    }
    function c_ix(){
    }
    //##############################################结束#################################################

    /**
     * 析构函数
     */
    function __destruct() {
        
    }

}
?>