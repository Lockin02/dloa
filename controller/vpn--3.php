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
        $filename = "email.csv";
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        Header("Cache-Control: public");
        header("Content-type: application/vnd.ms-excel;");
        header("Content-Disposition: filename=" . $filename);
        $data="邮箱地址,密码,真实姓名,移动电话,公司/部门". base64_decode("DQo=");
        $sql = "select
            u.user_id , d.dept_name , e.mobile1 , u.email , u.user_name , u.password
            from hrms h
                left join user u on ( h.user_id =u.user_id )
                left join department d on (u.dept_id=d.dept_id)
                left join ecard e on (h.user_id=e.user_id)
            where del='0' and has_left='0' and u.dept_id<>'1' ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $data .= $this->escapeCSV($row['email']) . ','
                    . '{md5}'.$this->escapeCSV($row['password']) . ','
                    .$row['user_name'].','
                    . substr($this->escapeCSV($row['mobile1']), 0, 11).','
                    .$row['dept_name']
                    . base64_decode("DQo=");
        }
        echo $data;
    }
    //##############################################结束#################################################

    /**
     * 析构函数
     */
    function __destruct() {
        
    }

}
?>