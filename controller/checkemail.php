<?php
class controller_checkemail extends model_base {

    public $show; // 模板显示
    public $emailClass;
    public $dateUtil;
    /**
     * 构造函数
     *
     */

    function __construct() 	{
        parent::__construct();
        $this->tbl_name = '{table}';
        $this->pk = 'id';
        $this->show = new show();
        $this->emailClass = new includes_class_sendmail();
        $this->dateUtil=new includes_class_dateutil();
    }

    function c_index(){
        $data=array();
        $sql="select h.ID , h.BeginDT , h.EndDT , h.ApplyDT ,h.Type , h.Reason ,h.ExaStatus , h.Status ,h.DTA
                ,h.Reason ,u.USER_NAME as UserName , h.beginhalf , h.endhalf , h.RemarkChange
            from hols h , user u
            where h.UserId=u.USER_ID and ExaStatus='完成'
                and to_days(h.begindt)<=to_days('2011-01-30')
                and to_days(h.enddt)>=to_days('2011-01-30')
                and h.type in ('年休假','事假')
            order by h.ID desc ";
        $query=$this->_db->query($sql);
        while($row=$this->_db->fetch_array($query)){
            if(strtotime($row['EndDT'])<=strtotime('2011-02-01')){
                $workDaysInfo=$this->dateUtil->daysWorkdays($row['BeginDT'], $row['EndDT'] , $row['beginhalf'] , $row['endhalf']);
                $workDaysInfo=$workDaysInfo+1;
            }elseif(strtotime($row['EndDT'])>=strtotime('2011-02-09')&&strtotime($row['EndDT'])<strtotime('2011-02-12')){
                $workDaysInfo=$this->dateUtil->daysWorkdays($row['BeginDT'], $row['EndDT'] , $row['beginhalf'] , $row['endhalf']);
                $workDaysInfo=$workDaysInfo-4;
            }elseif(strtotime($row['EndDT'])>=strtotime('2011-02-12')){
                $workDaysInfo=$this->dateUtil->daysWorkdays($row['BeginDT'], $row['EndDT'] , $row['beginhalf'] , $row['endhalf']);
                $workDaysInfo=$workDaysInfo-3;
            }
            if($row['DTA']!=$workDaysInfo){
                $data[$row['ID']]=$workDaysInfo;
            }
        }
        $sql="SELECT sum(h.dta) as dtas , u.user_name , u.user_id
            FROM hols h
                left join user u on (h.userid=u.user_id)
            where h.ExaStatus='完成'
                and h.Type='年休假' and year(h.BeginDT)='".date("Y")."'
            group by h.UserId";
        $query=$this->_db->query($sql);
        while($row=$this->_db->fetch_array($query)){
            $data[$row['user_id']]['dtas']=$row['dtas'];
            $data[$row['user_id']]['name']=$row['user_name'];
        }
        $sql="SELECT come_date , join_date , user_id  from hrms ";
        $query=$this->_db->query($sql);
        while($row=$this->_db->fetch_array($query)){
            $comeDate=$row['come_date'];
            $joinDate=$row['join_date'];
            $comeY=substr($comeDate,0,4);
            $nowY=date("Y");
            if($comeY==$nowY){
                $haveDate=0;
            }elseif($comeY+1==$nowY){
                if(date('z',strtotime($comeDate))<date('z',strtotime($comeY.'-07-01'))){
                    $haveDate=5;
                }else{
                    $haveDate=3;
                }
            }elseif($comeY+1<$nowY){
                $haveDate=6+($nowY-$comeY-2)*1;
            }
            if($haveDate>15)
                $haveDate=15;
            if(!empty($data[$row['user_id']])){
                if($data[$row['user_id']]['dtas']>$haveDate){
                    $data[$row['user_id']]['has']=$haveDate;
                }
            }
        }
        foreach($data as $key=>$val){
            if(!empty($val['has'])){
                echo $val['name'].','.$val['has'].','.$val['dtas'].'</br>';
            }
        }
        if(!empty($data)){
            foreach($data as $key=>$val){
                //$sql="update hols set dta='".$val."' where id='".$key."' ";
                //$this->_db->query($sql);
            }
        }
        $this->show->display('checkemail');
    }

    function c_fun(){
        $data=array();
        $sql="select h.ID , h.BeginDT , h.EndDT , h.ApplyDT ,h.Type , h.Reason ,h.ExaStatus , h.Status ,h.DTA
                ,h.Reason ,u.USER_NAME as UserName , h.beginhalf , h.endhalf , h.RemarkChange
            from hols h , user u
            where h.UserId=u.USER_ID and ExaStatus='完成'
                and to_days(h.begindt)<=to_days('2011-01-30')
                and to_days(h.enddt)>=to_days('2011-01-30')
            order by h.ID desc ";
        $query=$this->_db->query($sql);
        while($row=$this->_db->fetch_array($query)){
            $data[$row['ID']]=$row['DTA'];
        }
        var_dump($data);
        /*
        set_time_limit(600);
        $tol=500;
        for($i=0; $i<$tol; $i++){
            try {
                $this->emailClass ->send('测试', '工资发送邮件测试', 'guoquan.xie@dinglicom.com', '世纪鼎利', '世纪鼎利');
                usleep(5);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
         * 
         */
        echo 'good!';
    }
    //##############################################结束#################################################
    /**
     * 析构函数
     */
    function __destruct() 	{

    }

}
?>