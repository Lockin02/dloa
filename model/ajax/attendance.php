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
                if($_POST['ty']=='work'){//�޸Ĺ�����
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
                        $temptype=$res[0]['type']=='�¼�'?'p':'s';
                        $sql="update hols_sta
                            set
                                workdays='$pub' , realdays='".($pub-$temppub)."'
                            where id='".$_POST['id']."'
                            ";
                        $this->_db->query($sql,true);
                        return $temptype.'_'.($tempreal-($pub-$temppub));
                    }else{
                        throw new Exception('��ȡ���ݼ�ͳ�ƴ���'.$sql);
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
                        $temptype=$res[0]['type']=='�¼�'?'p':'s';
                        if($pub>$tempwork){
                            throw new Exception('�ڼ�������������ȹ����մ�');
                        }
                        $sql="update hols_sta
                            set
                                publicdays='$pub' , realdays='".($tempwork-$pub)."'
                            where id='".$_POST['id']."'
                            ";
                        $this->_db->query($sql,true);
                        return $temptype.'_'.($tempreal-($tempwork-$pub));
                    }else{
                        throw new Exception('��ȡ���ݼ�ͳ�ƴ���'.$sql);
                    }
                }
            }
        }catch(Exception $e){
            echo 'e';
            writeToLog('���ݼ�ͳ���޸Ľڼ��մ���'.$e->getMessage(), 'attendanc.log');
        }
    }

    function handup_hols_stat(){
        try{
            $sql="update hols_sta set checkflag='1' where syear='".date('Y')."' and smon='".date('n')."' ";
            $this->_db->query($sql,true);
        }catch (Exception $e){
            echo 'e';
            writeToLog('���ݼ�ͳ���ύ�ܼ����'.$e->getMessage(), 'attendanc.log');
        }
    }
}
?>