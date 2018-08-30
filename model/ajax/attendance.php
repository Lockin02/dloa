<?php
class model_ajax_attendance extends model_base
{
    function __construct()
    {
        if ($_POST)
        {
            $_POST = mb_iconv($_POST);
        }
        parent::__construct();
    }

    function update_hols_stat()
    {
        try{
            if($_POST['id']){
                if($_POST['ty']=='work'){//修改工作日
                    $pub=$_POST['pub'];
                    $sql="select
                        s.begindt , s.enddt , s.realdays , s.userid , s.publicdays , s.workdays , s.type
                    from
                        hols_sta s
                    where
                        id='".$_POST['id']."' ";
                    $res=$this->_db->getArray($sql);
                    if($res){
                        $tempreal=$res[0]['realdays'];
                        $tempwork=$res[0]['workdays'];
                        $temppub=$res[0]['publicdays'];
                        $temptype=$res[0]['type']=='事假'?'p':'s';
                        $sql="update hols_sta
                            set
                                workdays='$pub' , realdays='".($pub-$temppub)."'
                            where id='".$_POST['id']."'
                            ";
                        $this->_db->query($sql,true);
                        return $temptype.'_'.($tempreal-($pub-$temppub));
                    }else{
                        throw new Exception('读取请休假统计错误'.$sql);
                    }
                }else{
                    $pub=$_POST['pub'];
                    $sql="select
                        s.begindt , s.enddt , s.realdays , s.userid , s.publicdays , s.workdays , s.type
                    from
                        hols_sta s
                    where
                        id='".$_POST['id']."' ";
                    $res=$this->_db->getArray($sql);
                    if($res){
                        $tempreal=$res[0]['realdays'];
                        $tempwork=$res[0]['workdays'];
                        $temptype=$res[0]['type']=='事假'?'p':'s';
                        if($pub>$tempwork){
                            throw new Exception('节假日天数不允许比工作日大！');
                        }
                        $sql="update hols_sta
                            set
                                publicdays='$pub' , realdays='".($tempwork-$pub)."'
                            where id='".$_POST['id']."'
                            ";
                        $this->_db->query($sql,true);
                        return $temptype.'_'.($tempreal-($tempwork-$pub));
                    }else{
                        throw new Exception('读取请休假统计错误'.$sql);
                    }
                }
            }
        }catch(Exception $e){
            echo 'e';
            writeToLog('请休假统计修改节假日错误：'.$e->getMessage(), 'attendanc.log');
        }
    }

    function handup_hols_stat(){
        try{
            $sql="update hols_sta set checkflag='1' where syear='".date('Y')."' and smon='".date('n')."' ";
            $this->_db->query($sql,true);
        }catch (Exception $e){
            echo 'e';
            writeToLog('请休假统计提交总监出错：'.$e->getMessage(), 'attendanc.log');
        }
    }
}
?>