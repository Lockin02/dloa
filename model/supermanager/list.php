<?php
include('../../base.php');
set_time_limit(300);
class model_supermanger
{
	private $db;
	
	function __construct()
	{
		$this->db = new mysql();
	}
        
        function show(){
            $show_type=$_GET['show_type'];
            if($show_type=='sarea'){
                // up  model/supermanager/list.php?show_type=sarea&act=up&upid=100&sub=list  
                // change model/supermanager/list.php?show_type=sarea&act=change&oid=100&nid=101&sub=list
                $this->act_sarea();
            }elseif($show_type=='compri'||$show_type=='chapri'){
                //model/supermanager/list.php?show_type=compri&sub=ini
                $this->act_compri();
            }elseif($show_type=='comarea'||$show_type=='chaarea'){
                //model/supermanager/list.php?show_type=comarea&sub=ini
                $this->act_comarea();
            }elseif($show_type=='xgq'){
            
            $str = '����Ա��ְ��2013��10��24��ϵͳ������ͬ�����ˣ��� ����� ����Ϊ ������';
            preg_match('/(?:[1-9].*?)��(.*?)��/', $str, $matches);
            //print_r($matches);
            $pos=mb_stristr($str, "��") ;
            //print_r(explode(" ",$pos));
            $preg='/(?:19|20)?(?:[0-9]{2})?(?:-|\s|\/|.|��)?(?:0[1-9]|1[012])(?:-|\s|\/|.|��)(?:0[1-9]|[12][0-9]|3[01])(?:��)?\s?(?:[01][0-9]|2[1-4])?:?(?:[0-6]?[0-9])?:?(?:[0-6]?[0-9])?/i'; 
            
            $res = '<tr><td>ҵ���</td><td>����</td><td>7</td></tr>';
              $sql="SELECT  c.id as objCode ,c.remark , c.id 
 FROM `oa_contract_contract` c  
where c.isTemp=0 
and c.remark like '%��ͬ������%'
group by c.id ";
              $query = $this->db->query($sql);
              $data=array();
              while (($row = $this->db->fetch_array($query))!=false)
              {
                
                $remark = $row['remark'];
                $remark = explode("\r\n",$remark);
                foreach($remark as $key=>$val){
                  if( strpos($val,'��ͬ������') !==false ) {
                      //echo $val;
                      $temparr=array();
                      preg_match('/(?:[1-9].*?)��(.*?)��/', $val, $matches);
                      //print_r($matches);
                      if( strpos($matches[0],'��') ===false ) {
                        $matches[0]='2013��'.$matches[0];
                      }
                      $matches[0]= str_replace(array('��','��','��'), array('-','-',''), $matches[0] );
                      $temparr['time']=date('Y-m-d',strtotime($matches[0]) );
                      $temparr['timep']=date('Ymd',strtotime($temparr['time']) );
                      $pstr=mb_stristr($val, "��") ;
                      $pstr=explode(" ",$pstr);
                      $temparr['oldValue'] = $pstr['1'];
                      $temparr['newValue'] = $pstr['3'];
                      $temparr['id'] = $row['id'];
                      $temparr['remark'] = '��ע��¼��'.$temparr['time'].' �����ˣ�'.$temparr['oldValue'].'����Ϊ��'.$temparr['newValue'];
                      //print_r($temparr);
                      if($temparr['oldValue']){
                        $data[$row['objCode']][strtotime($temparr['timep'])]=$temparr;
                      }
                      if('XS20110831E0049'==$row['objCode']){
                        //print_r($temparr);
                        //print_r($data);
                      }
                      
                  }
                }
                  //$res.='<tr><td>'.$row['objCode'].'</td><td>'.$remark.'</td></tr>';
              }
//              $sql="SELECT cc.objCode , c.changeTime ,  d.oldValue ,  d.newValue 
//FROM oa_contract_changlog c
//left join oa_contract_changedetail d on (d.parentId=c.id and d.changefield ='prinvipalName')
//left join oa_contract_contract cc on (cc.id=c.objId)
//where d.parentId=c.id and d.changefield ='prinvipalName' ";
//              $query = $this->db->query($sql);
//              while (($row = $this->db->fetch_array($query))!=false)
//              {
//                $temparr=array();
//                $temparr['time']=date('Y-m-d',strtotime($row['changeTime']) );
//                $temparr['timep']=date('Ymd',strtotime($temparr['time']) );
//                $temparr['oldValue'] = $row['oldValue'];
//                $temparr['newValue'] = $row['newValue'];
//                $data[$row['objCode']][strtotime($row['changeTime'])]=$temparr;
//              }
              
              //print_r($data);
              //echo count($data);
              $i=0;
              foreach($data as $key=>$val){
                $d='';
                $d7='';
                ksort($val);
                foreach($val as $vkey=>$vval){
                
                  $sql="INSERT INTO oa_contract_changlog 
                      (`objId`,`objType`,`changeManId`,`changeManName`,`changeTime`,`changeReason`,`tempId`,`ExaStatus`) 
                      VALUES ('".$vval['id']."','contract','admin','ϵͳ����Ա', '".$vval['time']."' ,'".$vval['remark']."','','���')";
                  //$this->db->query($sql);
                  //$logid=$this->db->insert_id();

                  $sql="INSERT INTO oa_contract_changedetail 
                  (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
VALUES ('contract','��ͬ����','".$vval['id']."','','".$logid."','contract','prinvipalName','��ͬ������','".$vval['oldValue']."','".$vval['newValue']."')";
                  //$this->db->query($sql);
                  
                  $res .= '<tr><td>'.$key.'</td><td>'.$vval['remark'].'</td><td>�Ѹ���</td></tr>';
                  $i++;
                }
                
              }
              echo $i;
            }
            return $res;
        }
        
        function act_comarea(){
            $res='';
            $show_type=$_GET['show_type'];
            
            if($show_type=='comarea'){
                $ck_code='contract';
                $ck_type='��ͬ';
            }elseif($show_type=='chaarea'){
                $ck_code='chance';
                $ck_type='�̻�';
            }
            $sub=$_GET['sub'];
            if($sub=='ini'){
                $remark = '';
                $file= 'comarea.htm';
                $areaArr= array();
                $sql="SELECT areaname , id as areacode , areaPrincipal , areaPrincipalId  FROM oa_system_region where 1 and isstart = 0  order by areaname ";
                $query = $this->db->query($sql);
                while (($row = $this->db->fetch_array($query))!=false)
                {
                    $areaArr[$row['areacode']]['areaname']= $row['areaname'];
                    $areaArr[$row['areacode']]['areaPrincipal']= $row['areaPrincipal'];
                    $areaArr[$row['areacode']]['areaPrincipalId']= $row['areaPrincipalId'];
                }
                $areaStr='';
                foreach($areaArr as $key=>$val){
                    $areaStr .= '<option value="'.$key.'">'.$val['areaname'].'-'.$val['areaPrincipal'].'</option>';
                }
                if (file_exists ( $file )) {
                    $html = file_get_contents ($file);
                    $html = str_replace ( '{areaStr}', $areaStr, $html );
                    $html = str_replace ( '{remark}', $remark, $html );
                    $html = str_replace ( '{show_type}', $show_type, $html );
                    echo $html;
                    exit ();
                }
            }elseif($sub=='list'||$sub=='sub'){
                $data=array();
                $codes=  mb_iconv($_POST['codes']) ;
                $narea=  mb_iconv($_POST['narea']) ;
                $remark=  mb_iconv($_POST['remark']) ;
                $codeType= $_POST['codeType'];
                $codes=str_replace("\n", ",", $codes);
                $codes=str_replace("\r", ",", $codes);
                $codes=str_replace("\r\n", ",", $codes);
//                $codes=trim(trim($codes),',');
                $codes=  explode(',', $codes);
                $ck_codes = array_flip($codes);
                
                $sql="SELECT user_name,user_id  FROM user where user_name ='".trim($npri)."' ";
                $npri = $this->db->get_one($sql);
                
                $sql="SELECT areaname , id as areacode , areaPrincipal , areaPrincipalId  
                    FROM oa_system_region where 1 and isstart = 0 and id ='".$narea."'  order by areaname ";
                $narea = $this->db->get_one($sql);
                
                if($ck_code=='contract'){
                    $sql="SELECT c.prinvipalName , c.id , c.".$codeType." as contractcode , c.prinvipalId 
                           , c.areaname , c.areacode , c.areaPrincipal , c.areaPrincipalId 
                        FROM oa_contract_contract c 
                        where c.".$codeType." in ('".implode("','", $codes)."') and c.istemp=0 and c.state not in ('0') order by c.contractcode ";
                    $query = $this->db->query($sql);
                    while (($row = $this->db->fetch_array($query))!=false)
                    {
                        $data[$row['id']]['oarea']=$row['areaname'];
                        $data[$row['id']]['oareaid']=$row['areacode'];
                        $data[$row['id']]['oareap']=$row['areaPrincipal'];
                        $data[$row['id']]['oareapid']=$row['areaPrincipalId'];
                        $data[$row['id']]['code']=$row['contractcode'];
                        
                        $data[$row['id']]['narea']=$narea['areaname'];
                        $data[$row['id']]['nareaid']=$narea['areacode'];
                        $data[$row['id']]['nareap']=$narea['areaPrincipal'];
                        $data[$row['id']]['nareapid']=$narea['areaPrincipalId'];

                        if(in_array($row['contractcode'], $codes)){
                            $ck_codes[$row['contractcode']]='pass';
                        }

                        $data[$row['id']]['remark']=$remark;
                        $data[$row['id']]['remark'] = str_replace ( '$opri', $row['prinvipalName'], $data[$row['id']]['remark'] );
                        $data[$row['id']]['remark'] = str_replace ( '$npri', $npri['user_name'], $data[$row['id']]['remark'] );

                    }
                }elseif($ck_code=='chance'){
                    $sql="SELECT c.prinvipalName , c.id , c.chancecode , c.prinvipalId 
                    		, c.areaname , c.areacode , c.areaPrincipal , c.areaPrincipalId 
                    		FROM oa_sale_chance c 
                        where c.chancecode in ('".implode("','", $codes)."') order by c.chancecode ";
                    $query = $this->db->query($sql);
                    while (($row = $this->db->fetch_array($query))!=false)
                    {
                        $data[$row['id']]['oarea']=$row['areaname'];
                        $data[$row['id']]['oareaid']=$row['areacode'];
                        $data[$row['id']]['oareap']=$row['areaPrincipal'];
                        $data[$row['id']]['oareapid']=$row['areaPrincipalId'];
                        $data[$row['id']]['code']=$row['chancecode'];
                        
                        $data[$row['id']]['narea']=$narea['areaname'];
                        $data[$row['id']]['nareaid']=$narea['areacode'];
                        $data[$row['id']]['nareap']=$narea['areaPrincipal'];
                        $data[$row['id']]['nareapid']=$narea['areaPrincipalId'];

                        if(in_array($row['chancecode'], $codes)){
                            $ck_codes[$row['chancecode']]='pass';
                        }

                        $data[$row['id']]['remark']=$remark;
                        $data[$row['id']]['remark'] = str_replace ( '$opri', $row['prinvipalName'], $data[$row['id']]['remark'] );
                        $data[$row['id']]['remark'] = str_replace ( '$npri', $npri['user_name'], $data[$row['id']]['remark'] );

                    }
                }
 //               print_r($data);
//                print_r($ck_codes);
                if($sub=='list'){
                    $res='';
                    $res.='<table  class="table" style="width:100%"><tr><td>���</td><td>'.($ck_code=='contract'?'��ͬ��':'�̻���')
                        .'</td><td>������</td><td>����������</td><td>������</td><td>����������</td><td>��ע</td><td>���</td></tr>';
                    $i=1;
                    foreach($ck_codes as $key=>$val){
                        if($val!=='pass'){
                            $res.='<tr style="color:red;"><td>'.$i.'</td><td>'.$key.'</td><td>'
                                    .$val['code'].'</td><td>'.$npri['user_name'].'</td><td>'.$val['remark'].'</td><td>�޸���Ϣ</td></tr>';
                            $i++;
                        }
                    }
                    foreach($data as $val){
                        $res.='<tr><td>'.$i.'</td><td>'.$val['code']
                                .'</td><td>'.$val['oarea'].'</td><td>'.$val['oareap']
                                .'</td><td>'.$val['narea'].'</td><td>'.$val['nareap']
                                .'</td><td>'.$val['remark'].'</td><td>'.
                                (($val['oarea']!=$val['narea']||$val['oareap']!=$val['nareap'])?'��֤�ɹ�':'�������').'</td></tr>';
                        $i++;
                    }
                    $res.='</table>';
                }elseif($sub=='sub'){
                    if(!empty($data)){
                        $up_codes = array_keys($data);
                        if($ck_code=='contract'){
                            $sql="update oa_contract_contract c 
                                    set 
                                        c.areaname  ='".$narea['areaname']."' ,  c.areacode  ='".$narea['areacode']."' 
                                        ,  c.areaPrincipal  ='".$narea['areaPrincipal']."' ,  c.areaPrincipalId  ='".$narea['areaPrincipalId']."' 
                                where c.id in ('".implode("','", $up_codes)."') and c.istemp=0 and c.state not in ('0') ";
                            $query = $this->db->query($sql);
                            foreach($data as $key=>$val){
                                if($val['oarea']!=$val['narea']||$val['oareap']!=$val['nareap']){
                                    $sql="INSERT INTO oa_contract_changlog 
                                        (`objId`,`objType`,`changeManId`,`changeManName`,`changeTime`,`changeReason`,`tempId`) 
                                        VALUES ('".$key."','contract','admin','ϵͳ����Ա', now() ,'".$val['remark']."','')";
                                    $this->db->query($sql);
                                    $logid=$this->db->insert_id();
                                    
                                    if($val['oarea']!=$val['narea']){
                                        $sql="INSERT INTO oa_contract_changedetail 
                                        (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
                    VALUES ('contract','��ͬ����','".$key."','','".$logid."','contract','areaName','��ͬ����','".$val['oarea']."','".$val['narea']."')";
                                        $this->db->query($sql);
                                    }
                                    if($val['oareap']!=$val['nareap']){
                                        $sql="INSERT INTO oa_contract_changedetail 
                                        (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
                    VALUES ('contract','��ͬ����','".$key."','','".$logid."','contract','areaName','��������','".$val['oareap']."','".$val['nareap']."')";
                                        $this->db->query($sql);
                                    }
                                    
                                    $data[$key]['up']='up';
                                }
                            }
                        }elseif($ck_code=='chance'){
                            $sql="update oa_sale_chance c 
                                    set 
                            			c.areaname  ='".$narea['areaname']."' ,  c.areacode  ='".$narea['areacode']."' 
                                        ,  c.areaPrincipal  ='".$narea['areaPrincipal']."' ,  c.areaPrincipalId  ='".$narea['areaPrincipalId']."' 
                                where c.id in ('".implode("','", $up_codes)."') ";
                            $query = $this->db->query($sql);
                            foreach($data as $key=>$val){
                            	 if($val['oarea']!=$val['narea']||$val['oareap']!=$val['nareap']){
                                    $sql="INSERT INTO oa_chance_changlog 
                                        (`objId`,`objType`,`changeManId`,`changeManName`,`changeTime`,`changeReason`,`tempId`) 
                                        VALUES ('".$key."','chance','admin','ϵͳ����Ա', now() ,'".$val['remark']."','')";
                                    $this->db->query($sql);
                                    $logid=$this->db->insert_id();

                                    if($val['oarea']!=$val['narea']){
                                    	$sql="INSERT INTO oa_chance_changedetail
                                        (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`)
                    VALUES ('chance','�̻�����','".$key."','','".$logid."','chance','areaCode','�̻�����','".$val['oarea']."','".$val['narea']."')";
                                    	$this->db->query($sql);
                                    }
                                    if($val['oareap']!=$val['nareap']){
                                    	$sql="INSERT INTO oa_chance_changedetail
                                        (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`)
                    VALUES ('chance','�̻�����','".$key."','','".$logid."','chance','areaName','��������','".$val['oareap']."','".$val['nareap']."')";
                                    	$this->db->query($sql);
                                    }
                                    
                                    $data[$key]['up']='up';
                                }
                            }
                        }
                        
                    }
                    $res='';
                    $res.='<table  class="table" style="width:100%"><tr><td>���</td><td>'.($ck_code=='contract'?'��ͬ��':'�̻���')
                        .'</td><td>������</td><td>����������</td><td>������</td><td>����������</td><td>��ע</td><td>���</td></tr>';
                    $i=1;
                    foreach($ck_codes as $key=>$val){
                        if($val!=='pass'){
                            $res.='<tr style="color:red;"><td>'.$i.'</td><td>'.$key.'</td><td>'
                                    .$val['code'].'</td><td>'.$npri['user_name'].'</td><td>'.$val['remark'].'</td><td>�޸���Ϣ</td></tr>';
                            $i++;
                        }
                    }
                    foreach($data as $val){
                        $res.='<tr><td>'.$i.'</td><td>'.$val['code']
                                .'</td><td>'.$val['oarea'].'</td><td>'.$val['oareap']
                                .'</td><td>'.$val['narea'].'</td><td>'.$val['nareap']
                                .'</td><td>'.$val['remark'].'</td><td>'.
                                ($val['up']=='up'?'���³ɹ�':'�������').'</td></tr>';
                        $i++;
                    }
                    $res.='</table>';
                    
                }
                
            }
            echo un_iconv($res);
            die();
        }
        
        function act_compri(){
            $res='';
            $show_type=$_GET['show_type'];
            if($show_type=='compri'){
                $ck_code='contract';
                $ck_type='��ͬ';
            }elseif($show_type=='chapri'){
                $ck_code='chance';
                $ck_type='�̻�';
            }
            $sub=$_GET['sub'];
            if($sub=='ini'){
                $remark = '����Ա��ְ��';
                $file= 'compri.htm';
                if (file_exists ( $file )) {
                    $html = file_get_contents ($file);
                    $html = str_replace ( '{remark}', $remark, $html );
                    $html = str_replace ( '{show_type}', $show_type, $html );
                    echo $html;
                    exit ();
                }
            }elseif($sub=='list'||$sub=='sub'){
                $data=array();
                $codes=  mb_iconv($_POST['codes']) ;
                $npri=  mb_iconv($_POST['npri']) ;
                $remark=  mb_iconv($_POST['remark']) ;
                $codeType= $_POST['codeType'];
                $codes=str_replace("\n", ",", $codes);
                $codes=str_replace("\r", ",", $codes);
                $codes=str_replace("\r\n", ",", $codes);
//                $codes=trim(trim($codes),',');
                $codes=  explode(',', $codes);
                $ck_codes = array_flip($codes);
                
                $sql="SELECT user_name,user_id  FROM user where user_name ='".trim($npri)."' ";
                $npri = $this->db->get_one($sql);
                
                if($ck_code=='contract'){
                    $sql="SELECT c.prinvipalName , c.id , c.".$codeType." as contractcode , c.prinvipalId FROM oa_contract_contract c 
                        where c.".$codeType." in ('".implode("','", $codes)."') and c.istemp=0 and c.state not in ('0') order by c.".$codeType." ";
                    $query = $this->db->query($sql);
                    while (($row = $this->db->fetch_array($query))!=false)
                    {
                        $data[$row['id']]['opri']=$row['prinvipalName'];
                        $data[$row['id']]['opriid']=$row['prinvipalId'];
                        $data[$row['id']]['code']=$row['contractcode'];
                        $data[$row['id']]['npri']=$npri['user_name'];
                        $data[$row['id']]['npriid']=$npri['user_id'];

                        if(in_array($row['contractcode'], $codes)){
                            $ck_codes[$row['contractcode']]='pass';
                        }

                        $data[$row['id']]['remark']=$remark;
                        $data[$row['id']]['remark'] = str_replace ( '$opri', $row['prinvipalName'], $data[$row['id']]['remark'] );
                        $data[$row['id']]['remark'] = str_replace ( '$npri', $npri['user_name'], $data[$row['id']]['remark'] );

                    }
                }elseif($ck_code=='chance'){
                    $sql="SELECT c.prinvipalName , c.id , c.".$codeType." as chancecode , c.prinvipalId FROM oa_sale_chance c 
                        where c.".$codeType." in ('".implode("','", $codes)."') order by c.chancecode ";
                    $query = $this->db->query($sql);
                    while (($row = $this->db->fetch_array($query))!=false)
                    {
                        $data[$row['id']]['opri']=$row['prinvipalName'];
                        $data[$row['id']]['opriid']=$row['prinvipalId'];
                        $data[$row['id']]['code']=$row['chancecode'];
                        $data[$row['id']]['npri']=$npri['user_name'];
                        $data[$row['id']]['npriid']=$npri['user_id'];

                        if(in_array($row['chancecode'], $codes)){
                            $ck_codes[$row['chancecode']]='pass';
                        }

                        $data[$row['id']]['remark']=$remark;
                        $data[$row['id']]['remark'] = str_replace ( '$opri', $row['prinvipalName'], $data[$row['id']]['remark'] );
                        $data[$row['id']]['remark'] = str_replace ( '$npri', $npri['user_name'], $data[$row['id']]['remark'] );

                    }
                }
//                print_r($data);
//                print_r($ck_codes);
                if($sub=='list'){
                    $res='';
                    $res.='<table  class="table" style="width:100%"><tr><td>���</td><td>'.($ck_code=='contract'?'��ͬ��':'�̻���').'</td><td>�ɸ�����</td><td>�¸�����</td><td>��ע</td><td>���</td></tr>';
                    $i=1;
                    foreach($ck_codes as $key=>$val){
                        if($val!=='pass'){
                            $res.='<tr style="color:red;"><td>'.$i.'</td><td>'.$key.'</td><td>'
                                    .$val['code'].'</td><td>'.$npri['user_name'].'</td><td>'.$val['remark'].'</td><td>�޸���Ϣ</td></tr>';
                            $i++;
                        }
                    }
                    foreach($data as $val){
                        $res.='<tr><td>'.$i.'</td><td>'.$val['code'].'</td><td>'.$val['opri'].'</td><td>'.$val['npri'].'</td><td>'.$val['remark'].'</td><td>'.
                                ($val['opri']!=$val['npri']?'��֤�ɹ�':'�������').'</td></tr>';
                        $i++;
                    }
                    $res.='</table>';
                }elseif($sub=='sub'){
                    if(!empty($data)){
                        $up_codes = array_keys($data);
                        if($ck_code=='contract'){
                            $sql="update oa_contract_contract c 
                                    set prinvipalName ='".$npri['user_name']."'  ,  prinvipalId  ='".$npri['user_id']."' 
                                where c.id in ('".implode("','", $up_codes)."') and c.istemp=0 ";
                            $query = $this->db->query($sql);
                            foreach($data as $key=>$val){
                                if($val['opriid']!=$val['npriid']){
                                    $sql="INSERT INTO oa_contract_changlog 
                                        (`objId`,`objType`,`changeManId`,`changeManName`,`changeTime`,`changeReason`,`tempId`) 
                                        VALUES ('".$key."','contract','admin','ϵͳ����Ա', now() ,'".$val['remark']."','')";
                                    $this->db->query($sql);
                                    $logid=$this->db->insert_id();

                                    $sql="INSERT INTO oa_contract_changedetail 
                                    (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
                VALUES ('contract','��ͬ����','".$key."','','".$logid."','contract','areaName','��ͬ������','".$val['opri']."','".$val['npri']."')";
                                    $this->db->query($sql);
                                    $data[$key]['up']='up';
                                }
                            }
                        }elseif($ck_code=='chance'){
                            $sql="update oa_sale_chance c 
                                    set prinvipalName ='".$npri['user_name']."'  ,  prinvipalId  ='".$npri['user_id']."' 
                                where c.id in ('".implode("','", $up_codes)."') ";
                            $query = $this->db->query($sql);
                            foreach($data as $key=>$val){
                                if($val['opriid']!=$val['npriid']){
                                    $sql="INSERT INTO oa_chance_changlog 
                                        (`objId`,`objType`,`changeManId`,`changeManName`,`changeTime`,`changeReason`,`tempId`) 
                                        VALUES ('".$key."','chance','admin','ϵͳ����Ա', now() ,'".$val['remark']."','')";
                                    $this->db->query($sql);
                                    $logid=$this->db->insert_id();

                                    $sql="INSERT INTO oa_chance_changedetail 
                                    (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
                VALUES ('chance','�̻�����','".$key."','','".$logid."','chance','areaName','�̻�������','".$val['opri']."','".$val['npri']."')";
                                    $this->db->query($sql);
                                    $data[$key]['up']='up';
                                }
                            }
                        }
                        
                    }
                    $res='';
                    $res.='<table  class="table" style="width:100%"><tr><td>���</td><td>'.($ck_code=='contract'?'��ͬ��':'�̻���').'</td><td>�ɸ�����</td><td>�¸�����</td><td>��ע</td><td>���</td></tr>';
                    $i=1;
                    foreach($ck_codes as $key=>$val){
                        if($val!=='pass'){
                            $res.='<tr style="color:red;"><td>'.$i.'</td><td>'.$key.'</td><td>'
                                    .$val['code'].'</td><td>'.$npri['user_name'].'</td><td>'.$val['remark'].'</td><td>�޸���Ϣ</td></tr>';
                            $i++;
                        }
                    }
                    foreach($data as $val){
                        $res.='<tr><td>'.$i.'</td><td>'.$val['code'].'</td><td>'.$val['opri'].'</td><td>'.$val['npri'].'</td><td>'.$val['remark'].'</td><td>'.
                                ($val['up']=='up'?'���³ɹ�':'�������').'</td></tr>';
                        $i++;
                    }
                    $res.='</table>';
                    
                }
                
            }
            echo un_iconv($res);
            die();
        }
        
        //��������
	function act_sarea(){
            
            $act=$_GET['act'];
            $sub=$_GET['sub'];
            $setlog=$_GET['setlog'];
            if($act=='up'){
                // model/supermanager/list.php?show_type=sarea&act=up&upid=100&sub=list
                $upid=$_GET['upid'];
                if($sub=='list'){
                    $sql="SELECT areaname , id as areacode , areaPrincipal , areaPrincipalId  FROM oa_system_region where id ='".$upid."' ";
                    $sarea = $this->db->get_one($sql);
                    $res='<tr><td>����������Ϣ������'.$sarea['areaname'].'-'.$upid
                            .' <a href="?show_type=sarea&act='.$act.'&upid='.$upid.'&sub=sub&setlog=set&TB_iframe=true" class="thickbox">���£�������ʷ��¼��</a> | '.
                            '<a href="?show_type=sarea&act='.$act.'&upid='.$upid.'&sub=sub&TB_iframe=true" class="thickbox">���£�������ʷ��¼��������������ƣ�</a>'.
                            '</td></tr>';
                }elseif($sub=='sub'){
                    
                    $data=$this->uparea($upid, $upid, $setlog);
                    //��ʾ
                    //print_r($data);
                    $res='<tr><td>����</td><td>Դ����</td><td>������</td><td>�ɸ�����</td><td>������</td><td>�¸�����</td></tr>';
                    foreach($data as $key=>$val){
                        foreach($val as $vkey=>$vval){
                            $res.='<tr><td>'.$key.'</td><td>'.$vval['ccode'].'</td><td>'.$vval['oan'].'</td><td>'.$vval['oaun']
                                    .'</td><td>'.$vval['nan'].'</td><td>'.$vval['naun'].'</td></tr>';
                        }
                    }
                    
                }
                
            }elseif($act=='change'){
                // model/supermanager/list.php?show_type=sarea&act=change&oid=100&nid=101&sub=list
                $oid=$_GET['oid'];
                $nid=$_GET['nid'];
                if($sub=='list'){
                    $sql="SELECT areaname , id as areacode , areaPrincipal , areaPrincipalId  FROM oa_system_region where id ='".$oid."' ";
                    $oarea = $this->db->get_one($sql);
                    $sql="SELECT areaname , id as areacode , areaPrincipal , areaPrincipalId  FROM oa_system_region where id ='".$nid."' ";
                    $narea = $this->db->get_one($sql);
                    $res='<tr><td>����������ɣ� '.$oarea['areaname'].'-'.$oid.' ����Ϊ�� '.$narea['areaname'].'-'.$nid
                            .' <a href="?show_type=sarea&act='.$act.'&oid='.$oid.'&nid='.$nid.'&sub=sub&setlog=set&TB_iframe=true" class="thickbox">���£�������ʷ��¼��</a> | '.
                            '<a href="?show_type=sarea&act='.$act.'&oid='.$oid.'&nid='.$nid.'&sub=sub&TB_iframe=true" class="thickbox">���£�������ʷ��¼��������������ƣ�</a>'.
                            '</td></tr>';
                }elseif($sub=='sub'){
                    
                    $data=$this->uparea($nid, $oid, $setlog);
                    //��ʾ
                    //print_r($data);
                    $res='<tr><td>����</td><td>Դ����</td><td>������</td><td>�ɸ�����</td><td>������</td><td>�¸�����</td></tr>';
                    foreach($data as $key=>$val){
                        foreach($val as $vkey=>$vval){
                            $res.='<tr><td>'.$key.'</td><td>'.$vval['ccode'].'</td><td>'.$vval['oan'].'</td><td>'.$vval['oaun']
                                    .'</td><td>'.$vval['nan'].'</td><td>'.$vval['naun'].'</td></tr>';
                        }
                    }
                }
            }
            
            echo $res;
        }
        /**
         *
         * @param type $nid ���º�ID
         * @param type $oid �����ID
         * @param type $setlog �Ƿ��¼��ʷ
         */
        function uparea($nid,$oid,$setlog){
            
            $data=array();
            $sql="SELECT areaname as nan, id as nac  , areaPrincipal as naun, areaPrincipalId as nau FROM oa_system_region where id ='".$nid."' ";
            $sarea = $this->db->get_one($sql);
            //��ͬ
            $sql="SELECT c.areaprincipal , c.areaprincipalid ,  c.areaname  , c.areacode , c.id , c.contractcode  FROM oa_contract_contract c 
                    where c.areacode='".$oid."' and c.istemp=0";
            $query = $this->db->query($sql);
            while (($row = $this->db->fetch_array($query))!=false)
            {
                $data['contract'][$row['id']]['oac']=$row['areacode'];
                $data['contract'][$row['id']]['oan']=$row['areaname'];
                $data['contract'][$row['id']]['oau']=$row['areaprincipalid'];
                $data['contract'][$row['id']]['oaun']=$row['areaprincipal'];
                $data['contract'][$row['id']]['ccode']=$row['contractcode'];
                $data['contract'][$row['id']]['change']='��ͬ����';
                $data['contract'][$row['id']]['naun']=$sarea['naun'];
                $data['contract'][$row['id']]['nan']=$sarea['nan'];
            }
            //����
            $sql="update oa_contract_contract c 
                    set c.areaprincipal ='".$sarea['naun']."', c.areaprincipalid ='".$sarea['nau']."'
                        ,  c.areaname  ='".$sarea['nan']."' , c.areacode  ='".$sarea['nac']."'
                    where c.areacode='".$oid."' and c.istemp=0 ";
            $query = $this->db->query($sql);

            //�̻�
            $sql="SELECT c.areaprincipal , c.areaprincipalid ,  c.areaname , c.areacode , c.id , c.chancecode FROM oa_sale_chance c 
                    where c.areacode='".$oid."'  ";
            $query = $this->db->query($sql);
            while (($row = $this->db->fetch_array($query))!=false)
            {
                $data['chance'][$row['id']]['oac']=$row['areacode'];
                $data['chance'][$row['id']]['oan']=$row['areaname'];
                $data['chance'][$row['id']]['oau']=$row['areaprincipalid'];
                $data['chance'][$row['id']]['oaun']=$row['areaprincipal'];
                $data['chance'][$row['id']]['ccode']=$row['chancecode'];
                $data['chance'][$row['id']]['change']='�̻�����';
                $data['chance'][$row['id']]['naun']=$sarea['naun'];
                $data['chance'][$row['id']]['nan']=$sarea['nan'];
            }
            //����
            $sql="update oa_sale_chance c 
                    set c.areaprincipal ='".$sarea['naun']."', c.areaprincipalid ='".$sarea['nau']."'
                        ,  c.areaname  ='".$sarea['nan']."' , c.areacode  ='".$sarea['nac']."'
                    where c.areacode='".$oid."' ";
            $query = $this->db->query($sql);

            //�ͻ�
            $sql="SELECT AreaLeaderId , AreaLeader ,  AreaName , AreaId , id  , name  FROM customer c 
                    where c.AreaId='".$oid."'  ";
            $query = $this->db->query($sql);
            while (($row = $this->db->fetch_array($query))!=false)
            {
                $data['customer'][$row['id']]['oac']=$row['AreaId'];
                $data['customer'][$row['id']]['oan']=$row['AreaName'];
                $data['customer'][$row['id']]['oau']=$row['AreaLeaderId'];
                $data['customer'][$row['id']]['oaun']=$row['AreaLeader'];
                $data['customer'][$row['id']]['ccode']=$row['name'];
                $data['customer'][$row['id']]['naun']=$sarea['naun'];
                $data['customer'][$row['id']]['nan']=$sarea['nan'];
            }
            //����
            $sql="update customer c 
                    set c.AreaLeader ='".$sarea['naun']."', c.AreaLeaderId ='".$sarea['nau']."'
                        ,  c.AreaName  ='".$sarea['nan']."' , c.AreaId  ='".$sarea['nac']."'
                    where c.AreaId='".$oid."' ";
            $query = $this->db->query($sql);

            //��ʷ��¼
            if($setlog=='set'){
                foreach($data as $key=>$val){
                    foreach($val as $vkey=>$vval){
                        if($key!='customer'){

                            if($vval['oan']!=$sarea['nan']||$vval['oau']!=$sarea['nau']){
                                $sql="INSERT INTO oa_".$key."_changlog 
                                    (`objId`,`objType`,`changeManId`,`changeManName`,`changeTime`,`changeReason`,`tempId`) 
                                    VALUES ('".$vkey."','".$key."','admin','ϵͳ����Ա', now() ,'ϵͳ��̨����','')";
                                $this->db->query($sql);
                                $logid=$this->db->insert_id();
                            }
                            if($vval['oan']!=$sarea['nan']){
                                $sql="INSERT INTO oa_".$key."_changedetail 
                                (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
            VALUES ('".$key."','".$vval['change']."','".$vkey."','','".$logid."','".$key."','areaName','��ͬ��������','".$vval['oan']."','".$sarea['nan']."')";
                                $this->db->query($sql);
                                $ck=true;
                            }
                            if($vval['oau']!=$sarea['nau']){
                                $sql="INSERT INTO oa_".$key."_changedetail 
                                (`detailType`,`detailTypeCn`,`objId`,`tempId`,`parentId`,`parentType`,`changeField`,`changeFieldCn`,`oldValue`,`newValue`) 
            VALUES ('".$key."','".$vval['change']."','".$vkey."','','".$logid."','".$key."','areaPrincipal','��������','".$vval['oaun']."','".$sarea['naun']."')";
                                $this->db->query($sql);
                                $ck=true;
                            }
                        }
                    }
                }
            }
            //print_r($data);
            return $data;
        }
        
	function order_list()
	{
                $stype=array(
                    'oa_sale_service'=>'����'
                    ,'oa_sale_order'=>'����'
                    ,'oa_sale_lease'=>'����'
                    ,'oa_sale_rdproject'=>'�з�'
                );
                $seakey=$_GET['seakey']?$_GET['seakey']:'-';
		$query = $this->db->query("select ordercode , ordertempcode , ordername , tablename , orgid 
                    from view_oa_order where  ( ordercode='$seakey' or ordertempcode like '%$seakey%' or ordername like '%$seakey%') ");
		while (($row = $this->db->fetch_array($query))!=false)
		{
			$str .='<tr>
                                <td>'.$row['orgid'].'</td>
                                <td>'.$row['ordercode'].'</td>
                                <td>'.$row['ordertempcode'].'</td>
                                <td>'.$row['ordername'].'</td>
                                <td>'.$stype[$row['tablename']].'</td>
                                <td>
                                    <a href="?act=del&tab='.$row['tablename'].'&orgid='.$row['orgid'].'&TB_iframe=true
                                        &width=300&height=250" class="thickbox">ɾ��</a>|
                                    <a href="?act=toord&tab='.$row['tablename'].'&orgid='.$row['orgid'].'&TB_iframe=true
                                        &width=300&height=250" class="thickbox">ת����</a>|
                                    <a href="?act=toser&tab='.$row['tablename'].'&orgid='.$row['orgid'].'&TB_iframe=true
                                        &width=300&height=250" class="thickbox" >ת����</a>|
                                    <a href="?act=tolea&tab='.$row['tablename'].'&orgid='.$row['orgid'].'&TB_iframe=true
                                        &width=300&height=250" class="thickbox" >ת����</a>
                                </td>
                               </tr>';
		}
		return $str;
	}
        
        function del(){
            $tab=$_GET['tab'];
            $orgid=$_GET['orgid'];
            $sql=" delete from $tab where id='".$orgid."' ";
            $this->db->query($sql);
        }
        
        function toorder(){
            $tab=$_GET['tab'];
            $orgid=$_GET['orgid'];
            $act=$_GET['act'];
            $objtype='-';
            /**
             * ��ȡ����
             */
            if($tab=='oa_sale_order'){
                $objtype=" and objtype in ('KPRK-02','KPRK-01') ";
                $delsql=" delete from oa_sale_order where id='".$orgid."' ";
                $upfile=" where servicetype in ('oa_sale_order','oa_sale_order2') and serviceid='$orgid'  "; 
                $sql="select `district` AS `district`,`isTemp` AS `isTemp`
                    ,`sign` AS `sign`,`orderstate` AS `orderstate`
                    ,`parentOrder` AS `parentOrder`,`orderCode` AS `orderCode`
                    ,`orderTempCode` AS `orderTempCode`,`orderName` AS `orderName`
                    ,`ExaStatus` AS `ExaStatus`,`state` AS `state`
                    ,`createTime` AS `createTime`,`areaName` AS `areaName`
                    ,`areaCode` AS `areaCode`,`createId` AS `createId`
                    ,`areaPrincipalId` AS `areaPrincipalId`,`areaPrincipal` AS `areaPrincipal`
                    ,`prinvipalId` AS `prinvipalId`,`customerName` AS `customerName`
                    ,`customerId` AS `customerId`,`orderTempMoney` AS `orderTempMoney`
                    ,`orderMoney` AS `orderMoney`,`ExaDT` AS `ExaDT`
                    ,`prinvipalName` AS `prinvipalName`,`customerProvince` AS `customerProvince`
                    ,`orderProvince` AS `orderProvince`,`orderCity` AS `orderCity`
                    ,`customerType` AS `customerType`,`isBecome` AS `isBecome`
                    ,`shipCondition` AS `shipCondition`,`signIn` AS `signIn`
                    ,`objCode` AS `objCode`,`orderNatureName` AS `orderNatureName`
                    ,`orderNature` AS `orderNature`,`signinType` AS `signinType` 
                    , orderprovinceid , ordercityid , remark , deliverystatus 
                    from `oa_sale_order` where (`oa_sale_order`.`isTemp` = 0 and id='".$orgid."') ";
            }elseif($tab=='oa_sale_lease'){
                $objtype=" and objtype in ('KPRK-05','KPRK-06') ";
                $delsql=" delete from oa_sale_lease where id='".$orgid."' ";
                $upfile=" where servicetype in ('oa_sale_lease','oa_sale_lease2') and serviceid='$orgid'  "; 
                $sql="select `district` AS `district`,`isTemp` AS `isTemp`
                    ,`sign` AS `sign`,`orderstate` AS `orderstate`,`parentOrder` AS `parentOrder`
                    ,`orderCode` AS `orderCode`,`orderTempCode` AS `orderTempCode`
                    ,`orderName` AS `orderName`,`ExaStatus` AS `ExaStatus`,`state` AS `state`
                    ,`createTime` AS `createTime`,`areaName` AS `areaName`,`areaCode` AS `areaCode`
                    ,`createId` AS `createId`,`areaPrincipalId` AS `areaPrincipalId`
                    ,`areaPrincipal` AS `areaPrincipal`,`hiresId` AS `prinvipalId`,`tenant` AS `customerName`
                    ,`tenantId` AS `customerId`,`orderTempMoney` AS `orderTempMoney`,`orderMoney` AS `orderMoney`
                    ,`ExaDT` AS `ExaDT`,`hiresName` AS `prinvipalName`,`customerProvince` AS `customerProvince`
                    ,`orderProvince` AS `orderProvince`,`orderCity` AS `orderCity`,`customerType` AS `customerType`
                    ,`isBecome` AS `isBecome`,`shipCondition` AS `shipCondition`,`signIn` AS `signIn`
                    ,`objCode` AS `objCode`,`orderNatureName` AS `orderNatureName`,`orderNature` AS `orderNature`
                    ,`signinType` AS `signinType` 
                    , orderprovinceid , ordercityid , remark , deliverystatus 
                    from `oa_sale_lease` where (`oa_sale_lease`.`isTemp` = 0 and id='".$orgid."') 
                    ";
            }elseif($tab=='oa_sale_service'){
                $objtype=" and objtype in ('KPRK-03','KPRK-04') ";
                $delsql=" delete from oa_sale_service where id='".$orgid."' ";
                $upfile=" where servicetype in ('oa_sale_service','oa_sale_service2') and serviceid='$orgid'  "; 
                $sql="select `district` AS `district`,`isTemp` AS `isTemp`,`sign` AS `sign`
                    ,`orderstate` AS `orderstate`,`parentOrder` AS `parentOrder`,`orderCode` AS `orderCode`
                    ,`orderTempCode` AS `orderTempCode`,`orderName` AS `orderName`,`ExaStatus` AS `ExaStatus`
                    ,`state` AS `state`
                    ,`createTime` AS `createTime`,`areaName` AS `areaName`
                    ,`areaCode` AS `areaCode`,`createId` AS `createId`,`areaPrincipalId` AS `areaPrincipalId`
                    ,`areaPrincipal` AS `areaPrincipal`,`orderPrincipalId` AS `prinvipalId`
                    ,`cusName` AS `customerName`,`cusNameId` AS `customerId`
                    ,`orderTempMoney` AS `orderTempMoney`,`orderMoney` AS `orderMoney`,`ExaDT` AS `ExaDT`
                    ,`orderPrincipal` AS `prinvipalName`,`customerProvince` AS `customerProvince`
                    ,`orderProvince` AS `orderProvince`,`orderCity` AS `orderCity`
                    ,`customerType` AS `customerType`,`isBecome` AS `isBecome`
                    ,`shipCondition` AS `shipCondition`,`signIn` AS `signIn`,`objCode` AS `objCode`
                    ,`orderNatureName` AS `orderNatureName`,`orderNature` AS `orderNature`
                    ,`signinType` AS `signinType` 
                    , orderprovinceid , ordercityid , remark , deliverystatus 
                    from `oa_sale_service` where (`oa_sale_service`.`isTemp` = 0 and id='".$orgid."') 
                    ";
            }elseif($tab=='oa_sale_rdproject'){
                $objtype=" and objtype in ('KPRK-07','KPRK-08') ";
                $delsql=" delete from oa_sale_rdproject where id='".$orgid."' ";
                $upfile=" where servicetype in ('oa_sale_rdproject','oa_sale_rdproject2') and serviceid='$orgid' "; 
                $sql="select `district` AS `district`,`isTemp` AS `isTemp`,`sign` AS `sign`
                    ,`orderstate` AS `orderstate`,`parentOrder` AS `parentOrder`
                    ,`orderCode` AS `orderCode`,`orderTempCode` AS `orderTempCode`
                    ,`orderName` AS `orderName`,`ExaStatus` AS `ExaStatus`,`state` AS `state`
                    ,`createTime` AS `createTime`,`areaName` AS `areaName`,`areaCode` AS `areaCode`
                    ,`createId` AS `createId`,`areaPrincipalId` AS `areaPrincipalId`
                    ,`areaPrincipal` AS `areaPrincipal`,`orderPrincipalId` AS `prinvipalId`
                    ,`cusName` AS `customerName`,`cusNameId` AS `customerId`
                    ,`orderTempMoney` AS `orderTempMoney`,`orderMoney` AS `orderMoney`
                    ,`ExaDT` AS `ExaDT`,`orderPrincipal` AS `prinvipalName`
                    ,`customerProvince` AS `customerProvince`,`orderProvince` AS `orderProvince`
                    ,`orderCity` AS `orderCity`,`customerType` AS `customerType`
                    ,`isBecome` AS `isBecome`,`shipCondition` AS `shipCondition`
                    ,`signIn` AS `signIn`,`objCode` AS `objCode`,`orderNatureName` AS `orderNatureName`
                    ,`orderNature` AS `orderNature`,`signinType` AS `signinType` 
                    , orderprovinceid , ordercityid , remark , deliverystatus
                    from `oa_sale_rdproject` where (`oa_sale_rdproject`.`isTemp` = 0 and id='".$orgid."')
                    ";  
            }
            try {
                $this->db->query("START TRANSACTION");
                if($act=='toord'){

                    $sqlto="insert into oa_sale_order 
                    ( 
                        `district` ,`isTemp` 
                        ,`sign`,`orderstate` 
                        ,`parentOrder` ,`orderCode`
                        ,`orderTempCode` ,`orderName`
                        ,`ExaStatus`,`state`
                        ,`createTime`,`areaName`
                        ,`areaCode`,`createId`
                        ,`areaPrincipalId` ,`areaPrincipal`
                        ,`prinvipalId`,`customerName`
                        ,`customerId`,`orderTempMoney`
                        ,`orderMoney`,`ExaDT`
                        ,`prinvipalName`,`customerProvince`
                        ,`orderProvince`,`orderCity`
                        ,`customerType`,`isBecome`
                        ,`shipCondition`,`signIn` 
                        ,`objCode`,`orderNatureName`
                        ,`orderNature`,`signinType`
                        , orderprovinceid , ordercityid 
                        , remark , deliverystatus
                    ) 
                        $sql ";
                    $this->db->query_exc($sqlto);
                    $newid=$this->db->insert_id();
                    if(!empty($newid)){
                        //���µ���
                        $sql="update oa_finance_income_allot 
                        set objid='$newid' , objtype='KPRK-01'
                        where objid='".$orgid."' $objtype ";
                        $this->db->query_exc($sql);
                        //���¿�Ʊ
                        $sql="update oa_finance_invoice 
                        set objid='$newid' , objtype='KPRK-01'
                        where objid='".$orgid."' $objtype ";
                        $this->db->query_exc($sql);
                        //�ϴ�
                        if($upfile){
                            $sql=" update oa_uploadfile_manage set serviceid='$newid' ".$upfile; 
                            $this->db->query_exc($sql);
                        }
                        //ɾ��
                        $this->db->query_exc($delsql);
                    }
                    
                }elseif($act=='toser'){

                    $sqlto="insert into oa_sale_service 
                    ( 
                       `district` ,`isTemp` ,`sign` 
                        ,`orderstate`,`parentOrder`,`orderCode`
                        ,`orderTempCode`,`orderName`,`ExaStatus`
                        ,`state`
                        ,`createTime` ,`areaName`
                        ,`areaCode` ,`createId` ,`areaPrincipalId` 
                        ,`areaPrincipal`,`orderPrincipalId`
                        ,`cusName`,`cusNameId`
                        ,`orderTempMoney`,`orderMoney`,`ExaDT`
                        ,`orderPrincipal`,`customerProvince`
                        ,`orderProvince`,`orderCity`
                        ,`customerType` ,`isBecome`
                        ,`shipCondition`,`signIn`,`objCode` 
                        ,`orderNatureName`,`orderNature`
                        ,`signinType`
                        , orderprovinceid , ordercityid 
                        , remark , deliverystatus 
                    ) 
                        $sql ";
                    $this->db->query_exc($sqlto);
                    $newid=$this->db->insert_id();
                    if(!empty($newid)){
                        //���µ���
                        $sql="update oa_finance_income_allot 
                        set objid='$newid' , objtype='KPRK-03'
                        where objid='".$orgid."' $objtype ";
                        $this->db->query_exc($sql);
                        //���¿�Ʊ
                        $sql="update oa_finance_invoice 
                        set objid='$newid' , objtype='KPRK-03'
                        where objid='".$orgid."' $objtype ";
                        $this->db->query_exc($sql);
                        //�ϴ�
                        if($upfile){
                            $sql=" update oa_uploadfile_manage set serviceid='$newid' ".$upfile; 
                            $this->db->query_exc($sql);
                        }
                        //ɾ��
                        $this->db->query_exc($delsql);
                    }
                    
                }elseif($act=='tolea'){//����

                    $sqlto="insert into oa_sale_lease 
                    ( 
                       `district` ,`isTemp`
                        ,`sign` ,`orderstate`,`parentOrder` 
                        ,`orderCode`,`orderTempCode`
                        ,`orderName`,`ExaStatus`,`state`
                        ,`createTime` ,`areaName`,`areaCode` 
                        ,`createId` ,`areaPrincipalId` 
                        ,`areaPrincipal` ,`hiresId` ,`tenant` 
                        ,`tenantId` ,`orderTempMoney`,`orderMoney` 
                        ,`ExaDT` ,`hiresName`,`customerProvince`
                        ,`orderProvince` ,`orderCity` ,`customerType`
                        ,`isBecome` ,`shipCondition` ,`signIn` 
                        ,`objCode`,`orderNatureName` ,`orderNature` 
                        ,`signinType` 
                        , orderprovinceid , ordercityid
                        , remark , deliverystatus 
                    ) 
                        $sql ";
                    $this->db->query_exc($sqlto);
                    $newid=$this->db->insert_id();
                    if(!empty($newid)){
                        //���µ���
                        $sql="update oa_finance_income_allot 
                        set objid='$newid' , objtype='KPRK-05'
                        where objid='".$orgid."' $objtype ";
                        $this->db->query_exc($sql);
                        //���¿�Ʊ
                        $sql="update oa_finance_invoice 
                        set objid='$newid' , objtype='KPRK-05'
                        where objid='".$orgid."' $objtype ";
                        $this->db->query_exc($sql);
                        //�ϴ�
                        if($upfile){
                            $sql=" update oa_uploadfile_manage set serviceid='$newid' ".$upfile; 
                            $this->db->query_exc($sql);
                        }
                        //ɾ��
                        $this->db->query_exc($delsql);
                    }
                    
                }
                $this->db->query("COMMIT");
            } catch (Exception $e) {
                echo $e;
                $this->db->query("ROLLBACK");
            }
            
        }
	function __destruct()
	{
		$this->db->close();
	}
}
$sup=new model_supermanger();
$res=$sup->show();
?>
<html>
<head>
<meta http-equiv="Content-Language" content="zh_cn" />
<meta name="GENERATOR" content="Zend Studio" />
<meta http-equiv="Content-Type" content="text/html; charset=GB2312" />
<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript" src="../../js/thickbox.js"></script>
<link rel="stylesheet" href="../../js/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../../images/style.css" type="text/css" media="screen" />
<title>��ͬ�б�</title>
</head>
<body>
    <table border="0" class="table" cellpadding="0" cellspacing="0" width="100%" style="margin-buttom:2px;font-size: 9px;text-align: left;">
        <?php
        echo $res;
        ?>
    </table>
</body>
</html>