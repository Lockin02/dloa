<?php

class model_system_import extends model_base
{

    public $page;
    public $num;
    public $start;
    public $db;
    public $emailClass;
    public $im;
    public $flag;

    //*******************************���캯��***********************************
    function __construct()
    {
        $str = '';
        //echo "'".str_replace("\r\n","','", $str )."'";
        parent::__construct();
        $this->db = new mysql();
        //echo $prikey=crypt_util('159257472817', 'encode', 'engineering_person_esmperson');
        //$this->im = new includes_class_ImInterface();
        //$this->model_ini();
        //$this->emailClass = new includes_class_sendmail();
        $this->flag = 'gcbz';
    }

    function model_sub()
    {
        set_time_limit(600);
        $ckt = $_POST['ckt'];
        $flag = $_POST['flag'];
        try {
            $excelfilename = 'attachment/sys_import/' . $ckt . ".xls";
            if (!file_exists($excelfilename)) {
                throw new Exception('File does not exist');
            } else {
                //��ȡexcel��Ϣ
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile(WEB_TOR . $excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr = $excel->getAllData();
                $excel->Close();
                if (!empty($excelArr)) {
                    if ($flag == 'bxht') {

                        if (count($excelArr) && !empty($excelArr)) {
                            $i = 0;
                            $infoE = array();
                            foreach ($excelArr['�º�ͬ��'] as $key => $val) {
                                if ($excelArr['����'][$key] == '����') {
                                    $sql = "UPDATE oa_sale_other 
SET
	initInvotherMoney ='" . $excelArr['��Ʊ'][$key] . "' ,  initInvoiceMoney ='" . $excelArr['��Ʊ'][$key] . "' 
	,initIncomeMoney ='" . $excelArr['����'][$key] . "' , initPayMoney ='" . $excelArr['����'][$key] . "'
    , orderCode = CONCAT( '" . $excelArr['�ɺ�ͬ��'][$key] . "'  ,'BX')
   	, ExaStatus  = '���' , `status` = 2 
    , createName ='admin' , createId ='admin'
where 
	orderCode = '" . $excelArr['�º�ͬ��'][$key] . "' ";
                                } elseif ($excelArr['����'][$key] == '���') {
                                    $sql = "UPDATE oa_sale_outsourcing 
SET
	initInvoiceMoney ='" . $excelArr['��Ʊ'][$key] . "' ,  initPayMoney ='" . $excelArr['����'][$key] . "' 
	, orderCode = CONCAT( '" . $excelArr['�ɺ�ͬ��'][$key] . "' ,'BX')
	, ExaStatus  = '���' , `status` = 2 
	, createName ='admin' , createId ='admin'
where 
	orderCode = '" . $excelArr['�º�ͬ��'][$key] . "' ";
                                }
                                $this->db->query_exc($sql);
                            }
                        }
                    }
                    if ($flag == 'gcbz') {
                        $sql = "select id ,  projectcode , projectname
		                        from oa_esm_project ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $ck[$row['id']] = $row['projectcode'];
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            $data = array();
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                if (in_array(trim($excelArr['��Ŀ���'][$key]), $ck)) {
                                    $data[$excelArr['Ա����'][$key]][$excelArr['��Ŀ���'][$key]][] =
                                        array(
                                            'dt' => $excelArr['��ʼʱ��'][$key]
                                        , 'et' => $excelArr['����ʱ��'][$key]
                                        , 'dy' => $excelArr['����(��)'][$key]
                                        , 'dd' => $excelArr['�ص�'][$key]
                                        , 'am' => $excelArr['�������'][$key]
                                        , 'rm' => $excelArr['��ע'][$key]);
                                }
                            }
                            if (!empty($data)) {
//                                 $sqlIm = " INSERT INTO cost_detail_import ( inputName, inputId, createTime)VALUES('" . $_SESSION['USER_NAME'] . "','" . $_SESSION['USER_ID'] . "',now()) ";
//                                 $this->db->query_exc($sqlIm);
//                                 $imId = $this->db->insert_id();
                                foreach ($data as $key => $val) {
                                    //$amt = 0 ;
                                    foreach ($val as $vkey => $vval) {
												$officeId="";
												$deptId="";
												$sqls="select id ,officeId,deptId
													from oa_esm_project Where projectcode='".$vkey."'";
												$gData=$this->db->get_one($sqls);
												//while($row=$this->db->fetch_array($query)){
													$officeId=$gData['officeId'];
													$deptId=$gData['deptId'];
												//}
												$sql2="select id ,officeId,deptId
													   from cost_detail_import Where deptId='".$deptId."' and officeId='".$officeId."' and DATE_FORMAT(createTime, '%Y-%m')= DATE_FORMAT(NOW(), '%Y-%m')  AND ( ExaStatus='���' OR ExaStatus IS NULL) ";
												$dData=$this->db->get_one($sql2);
												if($dData&&is_array($dData)){
														$imId=$dData['id'];
												}else{
													$sqlIm = " INSERT INTO cost_detail_import ( inputName, inputId, createTime,officeId,deptId)VALUES('".$_SESSION['USER_NAME']."','".$_SESSION['USER_ID']."',now(),'$officeId','$deptId') ";	
								 					  $this->db->query_exc($sqlIm);
													  $imId = $this->db->insert_id();
												}
                                        if ($vval) {
                                            $sql = " INSERT INTO cost_detail_list ( InputMan, InputDate, CostMan, isProject, ProjectNO, Purpose, `Status`, CostBelongTo, xm_sid, DetailType, daoUser, daoFlag, daoTime,imId )
 SELECT user_id, now(), user_id, 1 AS isProject, '" . $vkey . "' AS ProjectNO, '' AS Purpose, '�ύ' AS `Status`, 4 AS CostBelongTo, 1 AS xm_sid, 2 AS DetailType, '" . $_SESSION[' USER_ID '] . "' AS daoUser, 1 AS daoFlag, now() AS daoTime,'$imId' as imId
 FROM hrms WHERE usercard = '" . $key . "'";
                                            $query = $this->db->query_exc($sql);
                                            $lid = $this->db->insert_id();
                                            $i = 0;
                                            $amt = 0;
                                            foreach ($vval as $mkey => $mval) {
                                                $amt += $mval['am'];
                                                //$days=round((strtotime($mval['et'])-strtotime($mval['dt']))/3600/24) ;

                                                $i++;
                                                $sql = "insert INTO cost_detail_assistant (
																							HeadID,
																							RNo,
																							Place,
																							Note,
																							CostDateBegin,
																							CostDateEnd,
																							Days,
																							`Status`,
																							 imId
																							)
																					VALUES (
																							'$lid',
																							'$i',
																							'" . $mval['dd'] . "',
																							'" . $mval['rm'] . "' ,
																							'" . date('Y-m-d', strtotime($mval['dt'])) . "' ,
																							'" . date('Y-m-d', strtotime($mval['et'])) . "' ,
																							'" . $mval['dy'] . "',
																							'�ύ', 
																							'$imId'
																							)";
                                                $query = $this->db->query_exc($sql);
                                                $aid = $this->db->insert_id();
                                                $sql = "INSERT INTO cost_detail_project (
																								HeadID,
																								RNo,
																								CostTypeID,
																								CostMoney,
																								days,
																								Remark, 
																								AssID,
																								daoflag,
																								imId
																						) VALUES (
																								'$lid',
																							  '$i',
																								123,
																								'" . $mval['am'] . "',
																								1,
																								'" . $mval['rm'] . "' , 
																								'$aid',
																								 1,
																								'$imId' 
																								)";
                                                $query = $this->db->query_exc($sql);
                                                $did = $this->db->insert_id();
                                                //echo $amt;
                                                //if($amt<=3000){
                                                if ((int)($mval['am'] / $mval['dy']) <= 100) {
                                                    $sql = "
																							INSERT into bill_detail (
																								BillTypeID,
																								Days,
																								Amount,
																								BillDetailID,
																								BillAssID,
																								daoFlag
																								) 
																							VALUES 
																								(
																								20
																								,1
																								,'" . $mval['am'] . "'
																								,'$did'
																								,'$aid'
																								,1
																								)
																						";
                                                    $query = $this->db->query_exc($sql);

                                                } else {
                                                    $sql = "
																							INSERT into bill_detail (
																								BillTypeID,
																								Days,
																								Amount,
																								BillDetailID,
																								BillAssID,
																								daoFlag
																								) 
																							VALUES 
																								(
																								21
																								,1
																								,'" . ($mval['am'] - $mval['dy'] * 100) . "'
																								,'$did'
																								,'$aid'
																								,1
																								)
																						";
                                                    $query = $this->db->query_exc($sql);

                                                    $sqls = "
																							INSERT into bill_detail (
																								BillTypeID,
																								Days,
																								Amount,
																								BillDetailID,
																								BillAssID,
																								daoFlag
																								) 
																							VALUES 
																								(
																								20
																								,1
																								,'" . ($mval['dy'] * 100) . "'
																								,'$did'
																								,'$aid'
																								,1
																								)
																						";
                                                    $query = $this->db->query_exc($sqls);

                                                }
                                                /*
																					if((int)($mval['am']/$days)>100){
																						$sql = "
																							INSERT into bill_detail (
																								BillTypeID,
																								Days,
																								Amount,
																								BillDetailID,
																								BillAssID,
																								daoFlag
																								) 
																							VALUES 
																								(
																								21
																								,1
																								,'".($mval['am']-$days*100)."'
																								,'$did'
																								,'$aid'
																								,1
																								)
																						";
																						$query=$this->db->query_exc($sql); 
																						
																						$sql = "
																							INSERT into bill_detail (
																								BillTypeID,
																								Days,
																								Amount,
																								BillDetailID,
																								BillAssID,
																								daoFlag
																								) 
																							VALUES 
																								(
																								20
																								,1
																								,'".($days*100)."'
																								,'$did'
																								,'$aid'
																								,1
																								)
																						";
																						$query=$this->db->query_exc($sql);
																						
																						
																					}*/
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($flag == 'hrupdate') {
                        if (!in_array('Ա����', $excelFields) || !in_array('����', $excelFields)
                            || !in_array('�������´�', $excelFields) || !in_array('ְλ', $excelFields)
                        ) {
                            throw new Exception('Update failed');
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                $infoE[$val]['name'] = $excelArr['����'][$key];
                                $infoE[$val]['blg'] = $excelArr['�������´�'][$key];
                                $infoE[$val]['pos'] = $excelArr['ְλ'][$key];
                                $infoE[$val]['flag'] = '0';
                            }
                        }
                        $areaArr = array();
                        $sql = "select name , id  from area where del='0'";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $areaArr[$row['name']] = $row['id'];
                        }
                        $jobArr = array();
                        $sql = "select name , id  from user_jobs where dept_id='35' ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $jobArr[$row['name']] = $row['id'];
                        }
                        $sql = "select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id and u.dept_id='35'";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            if (array_key_exists($row['usercard'], $infoE)) {
                                $infoE[$row['usercard']]['flag'] = '1';
                            }
                        }
                        if (!empty($infoE)) {
                            foreach ($infoE as $key => $val) {
                                $sql = "update
                                    user u
                                        left join hrms h on (u.user_id=h.user_id)
                                        left join ecard e on (u.user_id=e.user_id)
                                    set
                                        u.user_priv='" . $jobArr[$val['pos']] . "'
                                        ,u.jobs_id='" . $jobArr[$val['pos']] . "'
                                        ,h.certificate='" . $val['pos'] . "'
                                        ,e.ministration='" . $val['pos'] . "'
                                        ,u.area='" . $areaArr[$val['blg']] . "'
                                    
                                    where
                                        h.usercard='" . $key . "'";
                                $this->db->query_exc($sql);
                            }
                        }
                    } elseif ($flag == 'htdq') {
                        $ck = array();
                        $sql = "select id , areaname , areaprincipal , areaprincipalid
                            from oa_system_region ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $ck[$row['areaname']]['id'] = $row['id'];
                            $ck[$row['areaname']]['aname'] = $row['areaname'];
                            $ck[$row['areaname']]['uid'] = $row['areaprincipalid'];
                            $ck[$row['areaname']]['uname'] = $row['areaprincipal'];
                        }
                        foreach ($excelArr['ҵ����'] as $key => $val) {
                            $sql = "update oa_contract_contract set  
                                    areacode ='" . $ck[$excelArr['����������'][$key]]['id'] . "'
                                    , areaname ='" . $ck[$excelArr['����������'][$key]]['aname'] . "'
                                    , areaprincipal ='" . $ck[$excelArr['����������'][$key]]['uname'] . "'
                                    , areaprincipalid ='" . $ck[$excelArr['����������'][$key]]['uid'] . "'
                                  where 
                                    objcode='" . $excelArr['ҵ����'][$key] . "'
                                ";
                            $this->db->query_exc($sql);
                        }
                    } elseif ($flag == 'hr_email') {
                        if (count($excelArr) && !empty($excelArr)) {
                            $infoE = array();
                            $sql = "select u.email , h.usercard
                                from user u
                                    left join hrms h on (u.user_id=h.user_id)
                                where u.user_id=h.user_id   ";
                            $query = $this->db->query_exc($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $infoE[$row['usercard']] = $row['email'];
                            }
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                try {
                                    $tmpstr = '<DIV><FONT size=2 face=Verdana>&nbsp;<FONT size=2 face=Verdana>' . $excelArr['����'][$key] . ',���ã�</FONT>
                                        <br/><br/>
                                        &nbsp;<FONT size=2 face=Verdana>' . $excelArr['֪ͨ'][$key] . '��</FONT>
                                        </DIV>
                                        <br/>
                                        <DIV><FONT color=#c0c0c0 size=2 face=Verdana>���Ͷ���</FONT></DIV>
                                        <DIV><FONT color=#c0c0c0 size=2 face=Verdana>2012-07-30 </FONT></DIV>';
                                    $this->emailClass->send('2012�����ְ�ʸ���֤�������', $tmpstr, $infoE[$val], '���Ͷ���', '���Ͷ���');
                                } catch (Exception $e) {
                                    return $e->getMessage();
                                }
                            }
                        }
                    } elseif ($flag == 'hr_up') {
                        if (count($excelArr) && !empty($excelArr)) {
                            $sexInfo = array('��' => '0', 'Ů' => '1');
                            $eduInfo = array("ר��" => '1', "����" => '2', "����" => '3', "��ר" => '4', "��ר" => '5'
                            , "����" => '6', "˶ʿ" => '7', "�о���" => '8', "��ʿ" => '9', "δ�����������" => '10');
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                try {
                                    $ckdt = substr($excelArr['ת������'][$key], 0, 6);
                                    if ($ckdt == '201207') {
                                        $cksta = '1';
                                    } else {
                                        $cksta = '0';
                                    }
                                    $this->db->query("START TRANSACTION");
                                    $sql = "update hrms h
                                        left join salary s on (s.userid=h.user_id)
                                        set 
                                            h.ContractState='" . $excelArr['ǩ��ͬ����'][$key] . "'
                                            ,h.ExpFlag='" . $excelArr['�ù���ʽ'][$key] . "'
                                            ,h.JOIN_DATE='" . $excelArr['ת������'][$key] . "'
                                            ,h.ContFlagB='" . $excelArr['��ͬ��ʼ����'][$key] . "'
                                            ,h.ContFlagE='" . $excelArr['��ͬ��ֹ����'][$key] . "'
                                            ,s.usersta='" . $cksta . "'
                                        where h.usercard='" . $excelArr['Ա����'][$key] . "' and s.userid=h.user_id";
                                    $this->db->query_exc($sql);

                                    $this->db->query("COMMIT");
                                } catch (Exception $e) {
                                    $this->db->query("ROLLBACK");
                                    return $e->getMessage();
                                }
                            }
                        }
                    } elseif ($flag == 'brn_ini') {
                        if (count($excelArr) && !empty($excelArr)) {
                            //��ȡ����/����/ְλ����
                            $deptInfo = array();
                            $areaInfo = array('' => '1');
                            $jobInfo = array();
                            $sexInfo = array('��' => '0', 'Ů' => '1');
                            $eduInfo = array("ר��" => '1', "����" => '2', "����" => '3', "��ר" => '4', "��ר" => '5'
                            , "����" => '6', "˶ʿ" => '7', "�о���" => '8', "��ʿ" => '9', "δ�����������" => '10');
                            $sql = "select dept_id , dept_name from department ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $deptInfo[$row['dept_name']] = $row['dept_id'];
                            }
                            $sql = "select name , id from area where del='0' ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $areaInfo[$row['name']] = $row['id'];
                            }
                            $sql = "select name , id from user_jobs  ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $jobInfo[$row['name']] = $row['id'];
                            }
                            $pw = md5('bettercomm.net');
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                try {
                                    $this->db->query("START TRANSACTION");
                                    $tempdept = array();
                                    $tempdeptname = '';
                                    $tempdept = $excelArr['����'][$key];
                                    $tempdeptname = $tempdept;
                                    $excelArr['Ա����'][$key] = '0' . $excelArr['Ա����'][$key];
                                    $excelArr['�����ַ'][$key] = $excelArr['�����ַ'][$key] . '@bettercomm.net';
                                    //Ĭ����ͨԱ��
                                    if (empty($excelArr['ְλ'][$key])) {
                                        $excelArr['ְλ'][$key] = '��ͨԱ��';
                                    }
                                    //ְλ����
                                    if (empty($jobInfo[$excelArr['ְλ'][$key]])) {
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('" . $excelArr['ְλ'][$key] . "','" . $deptInfo[$tempdeptname] . "','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('" . $excelArr['ְλ'][$key] . "','$jobinsertid')");
                                        $jobInfo[$excelArr['ְλ'][$key]] = $jobinsertid;
                                    }
                                    //�û���
                                    $sql = "insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '" . $excelArr['Ա����'][$key] . "','" . $excelArr['����'][$key] . "','" . $pw . "'
                                            ,'" . $excelArr['Ա����'][$key] . "','" . $jobInfo[$excelArr['ְλ'][$key]] . "','" . $jobInfo[$excelArr['ְλ'][$key]] . "',
                                            '" . $deptInfo[$tempdeptname] . "','" . $sexInfo[$excelArr['�Ա�'][$key]] . "','" . $excelArr['�����ַ'][$key] . "',
                                            '" . $areaInfo[$excelArr['����'][$key]] . "','��Ѷ',now(),'" . $_SESSION['USER_ID'] . "'
                                        )";
                                    $this->db->query_exc($sql);
                                    //���±�
                                    $sql = "insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('" . $excelArr['Ա����'][$key] . "','" . $excelArr['���֤'][$key] . "','" . $excelArr['��ְ����'][$key] . "','" . $excelArr['ת������'][$key] . "'
    ,'" . $eduInfo[$excelArr['ѧ��'][$key]] . "','" . $excelArr['ְλ'][$key] . "','" . $excelArr['��ҵѧУ'][$key] . "','" . $excelArr['רҵ'][$key] . "'
    ,'" . $excelArr['��ͥ��ַ'][$key] . "','" . $excelArr['�����ʺ�'][$key] . "','" . $excelArr['����'][$key] . "','" . $excelArr['������'][$key] . "'
    ,'" . $excelArr['�ֻ�����'][$key] . "','" . $excelArr['������'][$key] . "','" . $excelArr['�����ַ'][$key] . "','" . $_SESSION['USER_ID'] . "'
    ,now(),'" . $excelArr['��ע'][$key] . "','" . $excelArr['��ͬ��ʼ'][$key] . "','" . $excelArr['��ͬ��ֹ'][$key] . "','" . $excelArr['Ա����'][$key] . "'
        )";
                                    $this->db->query_exc($sql);
                                    $sql = "insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '" . $excelArr['Ա����'][$key] . "',
                                        '" . $excelArr['����'][$key] . "',
                                        '" . $sexInfo[$excelArr['�Ա�'][$key]] . "',
                                        '" . $deptInfo[$tempdeptname] . "',
                                        'http://www.bettercomm.net',
                                        '" . $excelArr['�����ַ'][$key] . "',
                                        '',
                                        '" . date('Y-m-d H:i:s') . "',
                                        '" . $excelArr['��˾�绰'][$key] . "',
                                        '" . $excelArr['�ֻ�����'][$key] . "'
                                        )";
                                    $this->db->query_exc($sql);
                                    $this->db->query("COMMIT");


                                    //=========����IM�û�==============

                                    $data = array(
                                        'COM_BRN_CN' => '���ݱ�Ѷ',
                                        'DEPT_NAME' => $excelArr['����'][$key],
                                        'USER_ID' => $excelArr['Ա����'][$key],
                                        'USER_NAME' => $excelArr['����'][$key],
                                        'PASSWORD' => $pw,
                                        'SEX' => $sexInfo[$excelArr['�Ա�'][$key]],
                                        'EMAIL' => $excelArr['�����ַ'][$key],
                                        'JOBS_NAME' => $excelArr['ְλ'][$key],
                                        'Mobile' => '',
                                        'Phone' => ''
                                    );
                                    $msg = $this->im->add_user($data);
                                    if ($msg) {
                                        $creat_im = '����IM�ʺųɹ�';
                                    } else {
                                        $creat_im = '����IM�ʺ�ʧ��';
                                    }
                                    $msg = 'good!';
                                } catch (Exception $e) {
                                    $this->db->query("ROLLBACK");
                                    $this->pf($sql . $e->getMessage());
                                    return $e->getMessage();
                                }
                            }
                        }
                        return un_iconv($msg);
                    } elseif ($flag == 'bx_ini') {
                        $filestr = '';
                        if (count($excelArr) && !empty($excelArr)) {
                            //��ȡ����/����/ְλ����
                            $deptInfo = array();
                            $jobInfo = array();
                            $pdeptInfo = array();
                            $sql = "select d.dept_id , j.id , j.name , d.DEPT_NAME , d.pdeptid , d.pdeptname from user_jobs j
	LEFT JOIN department d on (d.DEPT_ID = j.dept_id)
	WHERE 1";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $jobInfo[$row['DEPT_NAME']][$row['name']] = $row['id'];
                                $deptInfo[$row['DEPT_NAME']] = $row['dept_id'];

                                $pdeptInfo[$row['DEPT_NAME']]['sd'] = $row['dept_id'];
                                $pdeptInfo[$row['DEPT_NAME']]['sdid'] = $row['DEPT_NAME'];
                                $pdeptInfo[$row['DEPT_NAME']]['zd'] = $row['pdeptid'];
                                $pdeptInfo[$row['DEPT_NAME']]['zdid'] = $row['pdeptname'];
                                if ($row['pdeptid'] != $row['dept_id']) {
                                    $pdeptInfo[$row['DEPT_NAME']]['2d'] = $row['pdeptid'];
                                    $pdeptInfo[$row['DEPT_NAME']]['2did'] = $row['pdeptname'];
                                    $pdeptInfo[$row['DEPT_NAME']]['3d'] = $row['dept_id'];
                                    $pdeptInfo[$row['DEPT_NAME']]['3did'] = $row['DEPT_NAME'];
                                } else {
                                    $pdeptInfo[$row['DEPT_NAME']]['2d'] = $row['dept_id'];
                                    $pdeptInfo[$row['DEPT_NAME']]['2did'] = $row['DEPT_NAME'];
                                    $pdeptInfo[$row['DEPT_NAME']]['3d'] = '';
                                    $pdeptInfo[$row['DEPT_NAME']]['3did'] = '';
                                }

                            }
                            $setDept = array();
                            $sql = "select d.DEPT_NAME
	                				, if(dp.DEPT_NAME is null , d.DEPT_NAME , CONCAT(dp.DEPT_NAME , '/' , d.DEPT_NAME) ) as setd 
	                				from department d 
left join department dp on (d.pdeptid = dp.DEPT_ID and d.DEPT_ID <> dp.DEPT_ID )
WHERE d.setyear= 2014 ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $setDept[$row['DEPT_NAME']] = $row['setd'];
                            }
                            $pw = md5('dinglicom');
                            $setu = array('chen.chen', 'xibiao.chen', 'junqiang.lu', 'xuanna.weng', 'zequan.xu');
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                try {
                                    $this->db->query("START TRANSACTION");
                                    $tmp = array();
                                    //
                                    $tmp['usercard'] = $excelArr['Ա����'][$key];
                                    $tmp['username'] = $excelArr['����'][$key];
                                    $tmp['userid'] = $excelArr['OA�˺�'][$key];
                                    $tmp['deptid'] = $deptInfo[$excelArr['��������'][$key]];
                                    $tmp['deptname'] = $excelArr['��������'][$key];
                                    $tmp['jobid'] = $jobInfo[$excelArr['��������'][$key]][$excelArr['ְλ'][$key]];
                                    $tmp['jobname'] = $excelArr['ְλ'][$key];
                                    $tmp['cd'] = $excelArr['���֤'][$key];
                                    $tmp['bxe'] = $excelArr['��Ѷ�����ַ'][$key];
                                    $tmp['dle'] = $excelArr['��������'][$key];
                                    $tmp['isb'] = $excelArr['��ͨ'][$key];
                                    $tmp['pw'] = substr($excelArr['���֤'][$key], -8, 8);
                                    $tmp['imd'] = $setDept[$excelArr['��������'][$key]];
                                    //�û���
                                    $sqlu = "insert into user(
                                            user_id,user_name
                                     		,password,logname,user_priv
                                            ,jobs_id,dept_id,email
                                            ,company,creatdt,creator
                                        )values(
                                            '" . $tmp['userid'] . "','" . $tmp['username'] . "'
                                            ,'" . md5($tmp['pw']) . "','" . $tmp['userid'] . "','" . $tmp['jobid'] . "'
                                            ,'" . $tmp['jobid'] . "','" . $tmp['deptid'] . "','" . $tmp['dle'] . "'
                                           	,'bx',now(),'admin'
                                        )";
                                    if (!in_array($tmp['userid'], $setu)) {
                                        $this->db->query_exc($sqlu);
                                    }
                                    //���±�
                                    $sqlh = "insert into hrms
                                        (USER_ID,CARD_NO
                                        ,Email,Creator
                                     	,CreateDT
                                        ,usercard
                                        )
                                    values
   									 (	'" . $tmp['userid'] . "' , '" . $tmp['cd'] . "'
   									 		,'" . $tmp['dle'] . "'  ,'admin'
   									 		,now()
   									 		, '" . $tmp['usercard'] . "' )";
                                    $this->db->query_exc($sqlh);

                                    $sqle = "insert into ecard
                                    (
                                        User_id,Name
                                     	,Depart
                                     	,Email,Company
                                     	,CreateDT
                                    )values(
                                        '" . $tmp['userid'] . "' ,'" . $tmp['username'] . "'
                                        ,'" . $tmp['deptid'] . "'
                                        ,'" . $tmp['dle'] . "','���ݱ�Ѷ'
                                        ,now()
                                        )";
                                    $this->db->query_exc($sqle);

                                    $sqloh = "insert into oa_hr_personnel
	                                    (
	                                        userNo,userAccount
	                                     	,userName,staffName
	                                     	,belongDeptName,belongDeptId
                                     		,deptName,deptId
                                     		,deptNameS,deptIdS
                                     		,deptNameT,deptIdT
	                                    )values(
	                                        '" . $tmp['usercard'] . "' ,'" . $tmp['userid'] . "'
	                                        ,'" . $tmp['username'] . "','" . $tmp['username'] . "'
	                                       ,'" . $pdeptInfo[$tmp['deptname']]['sdid'] . "' ,'" . $pdeptInfo[$tmp['deptname']]['sd'] . "'
                                        	,'" . $pdeptInfo[$tmp['deptname']]['zdid'] . "','" . $pdeptInfo[$tmp['deptname']]['zd'] . "'
                                        	,'" . $pdeptInfo[$tmp['deptname']]['2did'] . "','" . $pdeptInfo[$tmp['deptname']]['2d'] . "'
                                        	,'" . $pdeptInfo[$tmp['deptname']]['3did'] . "','" . $pdeptInfo[$tmp['deptname']]['3d'] . "'
	                                        )";
                                    $this->db->query_exc($sqloh);


                                    //=========����IM�û�==============

                                    $data = array(
                                        'COM_BRN_CN' => '���Ͷ���',
                                        'DEPT_NAME' => $tmp['imd'],
                                        'USER_ID' => $tmp['userid'],
                                        'USER_NAME' => $tmp['username'],
                                        'PASSWORD' => md5($tmp['pw']),
                                        'SEX' => '1',
                                        'EMAIL' => $tmp['dle'],
                                        'JOBS_NAME' => $tmp['jobname']
                                    );
                                    $msg = $this->im->add_user($data);
                                    if ($msg) {
                                        $creat_im = '����IM�ʺųɹ�';
                                    } else {
                                        $creat_im = '����IM�ʺ�ʧ��';
                                        throw new ErrorException('shibai-im');
                                    }

                                    //=========��������==============
                                    $AddEmail = '';
                                    $AddEmail_URL = Email_Server_Api_Url . '?action=AddUser&key=' . urlencode(authcode(oa_auth_key . ' ' . time(), 'ENCODE'));
                                    $AddEmail_URL .= '&userid=' . $tmp['userid'] . '&username=' . $tmp['username'] . '&password=' . $tmp['pw'] . '';
                                    $AddEmail_URL .= '&domain=dinglicom.com&deptname=' . $tmp['deptname'];
                                    if ($tmp['isb'] != '�ѿ�ͨ') {
                                        $AddEmail = file_get_contents(trim($AddEmail_URL));
                                        if (trim($AddEmail) == 1) {

                                        } else {
                                            throw new ErrorException('shibai-yx');
                                            $emailerr .= $AddEmail . "\n";
                                        }
                                    }
                                    $log .= $sqlu . $sqlh . $sqle . $sqloh . $AddEmail_URL . print_r($data, true);
                                    $this->db->query("COMMIT");
                                } catch (Exception $e) {
                                    $this->db->query("ROLLBACK");
                                    $filestr .= $excelArr['Ա����'][$key] . '--' . $e->getMessage() . "\n";
                                    $msg = 'error';
                                }
                            }
                        }
                        $filestr .= 'x';
                        file_put_contents('bx.log', $filestr);
                        file_put_contents('bx-e.log', $emailerr);
                        return un_iconv($filestr);
                    } elseif ($flag == 'zgs_ini') {
                        $filestr = '';
                        if (count($excelArr) && !empty($excelArr)) {
                            //��ȡ����/����/ְλ����
                            $deptInfo = array();
                            $areaInfo = array('' => '1');
                            $jobInfo = array();
                            $sexInfo = array('��' => '0', 'Ů' => '1');
                            $eduInfo = array("ר��" => '1', "����" => '2', "����" => '3', "��ר" => '4', "��ר" => '5'
                            , "����" => '6', "˶ʿ" => '7', "�о���" => '8', "��ʿ" => '9', "δ�����������" => '10');
                            $sql = "select dept_id , dept_name from department ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $deptInfo[$row['dept_name']] = $row['dept_id'];
                            }
                            $sql = "select name , id from area where del='0' ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $areaInfo[$row['name']] = $row['id'];
                            }
                            $sql = "select name , id from user_jobs  ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $jobInfo[$row['name']] = $row['id'];
                            }
                            $sql = "SELECT namecn , namept FROM branch_info where type='1' ";
                            $query = $this->db->query($sql);
                            while ($row = $this->db->fetch_array($query)) {
                                $brInfo[$row['namecn']] = $row['namept'];
                            }
                            $pw = md5('dinglicom');
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                try {
                                    $this->db->query("START TRANSACTION");
                                    $tempdept = array();
                                    $tempdeptname = '';
                                    $tempdept = $excelArr['����'][$key];
                                    $tempdeptname = $tempdept;
                                    $excelArr['Ա����'][$key] = '0' . $excelArr['Ա����'][$key];
                                    $excelArr['�����ַ'][$key] = $excelArr['OA�û���'][$key] . '@dinglicom.com';
                                    //Ĭ����ͨԱ��
                                    if (empty($excelArr['ְλ'][$key])) {
                                        $excelArr['ְλ'][$key] = '��ͨԱ��';
                                    }
                                    //ְλ����
                                    if (empty($jobInfo[$excelArr['ְλ'][$key]])) {
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('" . $excelArr['ְλ'][$key] . "','" . $deptInfo[$tempdeptname] . "','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('" . $excelArr['ְλ'][$key] . "','$jobinsertid')");
                                        $jobInfo[$excelArr['ְλ'][$key]] = $jobinsertid;
                                    }
                                    //�û���
                                    $sql = "insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '" . $excelArr['Ա����'][$key] . "','" . $excelArr['����'][$key] . "','" . $pw . "'
                                            ,'" . $excelArr['OA�û���'][$key] . "','" . $jobInfo[$excelArr['ְλ'][$key]] . "','" . $jobInfo[$excelArr['ְλ'][$key]] . "',
                                            '" . $deptInfo[$tempdeptname] . "','" . $sexInfo[$excelArr['�Ա�'][$key]] . "','" . $excelArr['�����ַ'][$key] . "',
                                            '" . $areaInfo[$excelArr['����'][$key]] . "','" . $brInfo[$excelArr['��˾'][$key]] . "',now(),'" . $_SESSION['USER_ID'] . "'
                                        )";
                                    $this->db->query_exc($sql);
                                    //���±�
                                    $sql = "insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('" . $excelArr['Ա����'][$key] . "','" . $excelArr['���֤'][$key] . "','" . $excelArr['��ְ����'][$key] . "','" . $excelArr['ת������'][$key] . "'
    ,'" . $eduInfo[$excelArr['ѧ��'][$key]] . "','" . $excelArr['ְλ'][$key] . "','" . $excelArr['��ҵѧУ'][$key] . "','" . $excelArr['רҵ'][$key] . "'
    ,'" . $excelArr['��ͥ��ַ'][$key] . "','" . $excelArr['�����ʺ�'][$key] . "','" . $excelArr['����'][$key] . "','" . $excelArr['������'][$key] . "'
    ,'" . $excelArr['�ֻ�����'][$key] . "','" . $excelArr['������'][$key] . "','" . $excelArr['�����ַ'][$key] . "','" . $_SESSION['USER_ID'] . "'
    ,now(),'" . $excelArr['��ע'][$key] . "','" . $excelArr['��ͬ��ʼ'][$key] . "','" . $excelArr['��ͬ��ֹ'][$key] . "','" . $excelArr['Ա����'][$key] . "'
        )";
                                    $this->db->query_exc($sql);
                                    $sql = "insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '" . $excelArr['Ա����'][$key] . "',
                                        '" . $excelArr['����'][$key] . "',
                                        '" . $sexInfo[$excelArr['�Ա�'][$key]] . "',
                                        '" . $deptInfo[$tempdeptname] . "',
                                        'http://www.dinglicom.com',
                                        '" . $excelArr['�����ַ'][$key] . "',
                                        '',
                                        '" . date('Y-m-d H:i:s') . "',
                                        '" . $excelArr['��˾�绰'][$key] . "',
                                        '" . $excelArr['�ֻ�����'][$key] . "'
                                        )";
                                    $this->db->query_exc($sql);


                                    //=========����IM�û�==============

                                    $data = array(
                                        'COM_BRN_CN' => '���Ͷ���',
                                        'DEPT_NAME' => $excelArr['����'][$key],
                                        'USER_ID' => $excelArr['OA�û���'][$key],
                                        'USER_NAME' => $excelArr['����'][$key],
                                        'PASSWORD' => $pw,
                                        'SEX' => $sexInfo[$excelArr['�Ա�'][$key]],
                                        'EMAIL' => $excelArr['�����ַ'][$key],
                                        'JOBS_NAME' => $excelArr['ְλ'][$key],
                                        'Mobile' => '',
                                        'Phone' => $excelArr['��˾'][$key]
                                    );
                                    $msg = $this->im->add_user($data);
                                    if ($msg) {
                                        $creat_im = '����IM�ʺųɹ�';
                                    } else {
                                        $creat_im = '����IM�ʺ�ʧ��';
                                        throw new ErrorException('shibai-im');
                                    }

                                    //=========��������==============
                                    $AddEmail = '';
                                    $AddEmail_URL = Email_Server_Api_Url . '?action=AddUser&key=' . urlencode(authcode(oa_auth_key . ' ' . time(), 'ENCODE'));
                                    $AddEmail_URL .= '&userid=' . $excelArr['OA�û���'][$key] . '&username=' . $excelArr['����'][$key] . '&password=dinglicom';
                                    $AddEmail_URL .= '&domain=dinglicom.com&deptname=' . $excelArr['����'][$key];
                                    $AddEmail = file_get_contents(trim($AddEmail_URL));
                                    if (trim($AddEmail) == 1) {

                                    } else {
                                        //throw new ErrorException('shibai-yx');
                                        $emailerr .= $AddEmail . "\n";
                                    }

                                    $this->db->query("COMMIT");
                                } catch (Exception $e) {
                                    $this->db->query("ROLLBACK");
                                    $filestr .= $excelArr['Ա����'][$key] . '--' . $e->getMessage() . "\n";
                                    $msg = 'error';
                                }
                            }
                        }
                        $filestr .= 'x';
                        file_put_contents('zgs.log', $filestr);
                        file_put_contents('zgs-e.log', $emailerr);
                        return un_iconv($filestr);
                    } elseif ($flag == 'bx_email') {
                        if (count($excelArr) && !empty($excelArr)) {

                            foreach ($excelArr['Ա����'] as $key => $val) {

                                foreach ($excelFields as $fval) {
                                    if ($fval == '����') {
                                        $doType = $excelArr[$fval][$key];
                                        if ($doType == '1') {
                                            $excelArr['�ʼ�'][$key] = '1';
                                        } elseif ($doType == '����') {
                                            $excelArr['�ʼ�'][$key] = '2';
                                        } elseif ($doType == '�ѿ�����') {
                                            $excelArr['�ʼ�'][$key] = '3';
                                        }
                                    }
                                }

                                $toe = $excelArr['��Ѷ�����ַ'][$key];
                                $tobody = '';
                                if ($excelArr['�ʼ�'][$key] == '1') {
                                    $tobody = '<DIV><FONT size=2 face=Verdana>' . $excelArr['����'][$key] . '�����ã�</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>�������Ͷ���OA�������Ϻ������Ѿ���ͨ�ã������¼��Ϣ���£�</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>1.���Ͷ���OAϵͳ��¼�˺ţ�' . $excelArr['OA�˺�'][$key] . ' �����룺��֮ǰʹ���ܲ���OAϵͳ������һ�£�</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>2.IMϵͳ��BigAnt����¼�˺ţ�' . $excelArr['OA�˺�'][$key] . ' �����룺�Ա����ṩ����˾������Դ�����֤�ĺ��λ��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>3.���Ͷ��������ַ��' . $excelArr['��������'][$key] . ' �����룺�Ա����ṩ����˾������Դ�����֤�ĺ��λ��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>������֪Ϥ��</FONT></DIV>';
                                } elseif ($excelArr['�ʼ�'][$key] == '2') {
                                    $tobody = '<DIV><FONT size=2 face=Verdana>' . $excelArr['����'][$key] . '�����ã�</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>�������Ͷ���OA�������Ϻ������Ѿ���ͨ�ã������¼��Ϣ���£�</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>1.���Ͷ���OAϵͳ��¼�˺ţ�' . $excelArr['OA�˺�'][$key] . ' �����룺�Ա����ṩ����˾������Դ�����֤�ĺ��λ��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>2.IMϵͳ��BigAnt����¼�˺ţ�' . $excelArr['OA�˺�'][$key] . ' �����룺�Ա����ṩ����˾������Դ�����֤�ĺ��λ��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>3.���Ͷ��������ַ��' . $excelArr['��������'][$key] . ' �����룺�Ա����ṩ����˾������Դ�����֤�ĺ��λ��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>������֪Ϥ��</FONT></DIV>';
                                } elseif ($excelArr['�ʼ�'][$key] == '3') {
                                    $tobody = '<DIV><FONT size=2 face=Verdana>' . $excelArr['����'][$key] . '�����ã�</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>�������Ͷ���OA�ʹ������Ѿ���ͨ�ã������¼��Ϣ���£�</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>1.���Ͷ���OAϵͳ��¼�˺ţ�' . $excelArr['OA�˺�'][$key] . ' �����룺��֮ǰʹ���ܲ���OAϵͳ������һ�£�</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>2.IMϵͳ��BigAnt����¼�˺ţ�' . $excelArr['OA�˺�'][$key] . ' �����룺�Ա����ṩ����˾������Դ�����֤�ĺ��λ��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>3.���Ͷ��������ַ��' . $excelArr['��������'][$key] . ' ������������˺���ǰ�Ѿ���ͨ���������벻���䶯��</FONT></DIV>
<DIV style="TEXT-INDENT: 4em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>������֪Ϥ��</FONT></DIV>';
                                }
                                $this->emailClass->send('���Ͷ�����OAϵͳ\����\IMϵͳ��BigAnt���˺���Ϣ', $tobody, $toe);
                            }//foreach 
                        }//empty
                    } elseif ($flag == 'gcgz') {
                        $userInfo = array();
                        $sql = "SELECT
									h.UserCard ,
									u.email
								FROM
									USER u
								LEFT JOIN hrms h on (u.USER_ID = h.USER_ID)
								WHERE
									del = '0' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $userInfo[$row['UserCard']] = $row['email'];
                        }

                        if (count($excelArr) && !empty($excelArr)) {

                            foreach ($excelArr['Ա�����'] as $key => $val) {

                                $excelArr['����'][$key] = $userInfo[$excelArr['Ա�����'][$key]];

                                $tobody = '';

                                if ($excelArr['����'][$key] == 'A') {
                                    include_once 'A.php';
                                    $tobody = $a;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }

                                } elseif ($excelArr['����'][$key] == 'B') {
                                    include_once 'B.php';
                                    $tobody = $b;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }
                                } elseif ($excelArr['����'][$key] == 'C') {
                                    include_once 'C.php';
                                    $tobody = $c;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }
                                } elseif ($excelArr['����'][$key] == 'D') {
                                    include_once 'D.php';
                                    $tobody = $d;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }
                                }
                                $this->emailClass->send('н��ṹ����֪ͨ', $tobody, $excelArr['����'][$key]);
                            }//foreach 
                        }//empty
                    } elseif ($flag == 'levechange') {
                        if (!in_array('Ա����', $excelFields) || !in_array('����', $excelFields)
                            || !in_array('���뼶��', $excelFields) || !in_array('��֤�������󼶱�', $excelFields)
                        ) {
                            throw new Exception('Update failed');
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                $infoE[$val]['name'] = $excelArr['����'][$key];
                                $infoE[$val]['lev'] = $excelArr['���뼶��'][$key];
                                $infoE[$val]['sal'] = $excelArr['��֤�������󼶱�'][$key];
                                $infoE[$val]['flag'] = '0';
                            }
                        }
                        $sql = "select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id   ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            if (array_key_exists($row['usercard'], $infoE)) {
                                $infoE[$row['usercard']]['flag'] = '1';
                                $infoE[$row['usercard']]['email'] = $row['email'];
                            }
                        }
                        if (!empty($infoE)) {
                            foreach ($infoE as $key => $val) {
                                $tmpstr = '';
                                if ($val['flag'] == '1') {
                                    $tmpstr = '<DIV><FONT size=2 face=Verdana>&nbsp;<FONT size=2 face=Verdana>���ã�</FONT>
                                        <br/><br/>
                                        &nbsp;<FONT size=2 face=Verdana>��ϲ��˳��ͨ������ͨ������ְ�ʸ���֤���룬����֪Ϥ���¡���֤�������󼶱𡱡�</FONT>
                                        <FONT size=2 face=Verdana>
<BLOCKQUOTE style="MARGIN-RIGHT: 0px" dir=ltr>
  <DIV>
  <TABLE
  style="BORDER-BOTTOM: medium none; BORDER-LEFT: medium none; BORDER-COLLAPSE: collapse; FONT-SIZE: 10pt; BORDER-TOP: medium none; BORDER-RIGHT: medium none"
  border=1 cellSpacing=0 borderColor=#000000 cellPadding=2 width="50%">
    <TBODY>
    <TR>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>Ա����</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>����</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>���뼶��</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>��֤�������󼶱�</DIV></FONT></TD></TR>
    <TR>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>' . $key . '</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>' . $val['name'] . '</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>' . $val['lev'] . '</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>' . $val['sal'] . '</DIV></FONT></TD></TR></TBODY></TABLE></FONT><FONT
  size=2 face=Verdana><FONT size=2 face=Verdana></FONT></DIV></BLOCKQUOTE>
<DIV style="TEXT-INDENT: 2em"><FONT size=2
face=Verdana>������Դ��</FONT></FONT></FONT></DIV></DIV>';
                                    $this->emailClass->send('���Ź���ͨ����ְ�ʸ���֤������˽��', $tmpstr, $val['email'], '���Ͷ���', '���Ͷ���');
                                }
                            }
                        }
                    } elseif ($flag == 'userupdate') {
                        foreach ($excelArr['����'] as $key => $val) {
                            /*$sql="update hrms h left join user u on (h.user_id = u.user_id )
                                set h.usercard='".$val."'
                                where u.user_name='".$excelArr['����'][$key]."' ";
                            $this->_db->query($sql);
                             * 
                             */
                        }
                    } elseif ($flag == 'yeb') {
                        if (!in_array('Ա����', $excelFields) || !in_array('����', $excelFields)
                            || !in_array('���ս�', $excelFields)
                            || !in_array('��˰', $excelFields) || !in_array('ʵ�����', $excelFields)
                        ) {
                            throw new Exception('Update failed');
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            foreach ($excelArr['Ա����'] as $key => $val) {
                                $infoE[$val]['name'] = $excelArr['����'][$key];
                                $infoE[$val]['yam'] = $excelArr['���ս�'][$key];
                                $infoE[$val]['kam'] = $excelArr['��˰'][$key];
                                $infoE[$val]['pam'] = $excelArr['ʵ�����'][$key];
                                $infoE[$val]['flag'] = '0';
                            }
                        }
                        $sql = "select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id  ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            if (array_key_exists($row['usercard'], $infoE)) {
                                $infoE[$row['usercard']]['flag'] = '1';
                                $infoE[$row['usercard']]['email'] = $row['email'];
                            }
                        }
                        if (!empty($infoE)) {
                            $i = 1;
                            foreach ($infoE as $key => $val) {
                                if ($val['flag'] == '1') {
                                    $str = '���ã��������ս���ͨ����ˣ�������Ϣ���£�����֪Ϥ��<br/>
                                            <table border="1" cellspacing="1" cellpadding="1">
                                                <tr><td>Ա����</td><td>' . $val['name'] . '</td></tr>
                                                <tr><td>���ս���</td><td>' . $val['yam'] . '</td></tr>
                                                <tr><td>��˰��</td><td>' . $val['kam'] . '</td></tr>
                                                <tr><td>ʵ����</td><td>' . $val['pam'] . '</td></tr>
                                            </table><br/>
                                            ���ս�������ǰ���ţ�';
                                    $this->emailClass->send('���ս���Ϣ', $str, $val['email']);
                                }
                                $i++;
                            }
                        }
                    }
                }
            }
            return '1';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    function model_data($ckt)
    {
        set_time_limit(600);
        $type = $_POST['imfile'];
        $flag = $_POST['flag'];
        $str = '';
        //echo 'xgq';
        $infoA = array();
        try {
            $excelfilename = 'attachment/sys_import/' . $ckt . ".xls";
            if (empty ($_FILES["imfile"]["tmp_name"])) {
                $str = '<tr><td colspan="10">���ϴ����ݣ�</td></tr>';
                if (false) {
                    $sql = "SELECT menu_id , func_name  FROM sys_function f
                            where menu_id  like '3903%' and enabled='1' order by menu_id ";
                    $query = $this->db->query($sql);
                    $menu = array();
                    while ($row = $this->db->fetch_array($query)) {
                        $strlen = strlen($row['menu_id']);
                        $strmo = ($strlen - 4) / 2;
                        $strstr = '';
                        for ($i = 1; $i < $strmo; $i++) {
                            $strstr .= '��';
                        }
                        $menu[$row['menu_id']] = $strstr . $row['func_name'];
                    }

                    $str = '';
                    foreach ($menu as $key => $val) {
                        $menudata = array();
                        $sql = "SELECT u.user_name FROM user_jobs j 
                            left join user u on (u.jobs_id=j.id)
                            left join department d on (u.dept_id=d.dept_id)
                            where find_in_set('_" . $key . "' , j.func_id_str  ) 
                                and u.del='0' and u.has_left='0' and d.depart_x like '24%' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $menudata[$row['user_name']] = $row['user_name'];
                        }
                        $sql = "SELECT u.user_name FROM user u 
                            left join department d on (u.dept_id=d.dept_id)
                            where find_in_set('_" . $key . "' , u.func_id_yes  ) 
                                and u.del='0' and u.has_left='0' and d.depart_x like '24%' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $menudata[$row['user_name']] = $row['user_name'];
                        }
                        $str .= '<tr>
                                <td style="text-align: left;">' . $val . '</td>
                                <td style="text-align: left;">' . implode(',', $menudata) . '</td>
                            </tr>';
                    }

                }
            } elseif (!move_uploaded_file($_FILES["imfile"]["tmp_name"], $excelfilename)) {
                $str = '<tr><td colspan="10">�ϴ�ʧ�ܣ�</td></tr>';
            } else {
                //��ȡexcel��Ϣ
                include('includes/classes/excel.php');
                $excel = Excel::getInstance();
                $excel->setFile(WEB_TOR . $excelfilename);
                $excel->Open();
                $excel->setSheet();
                $excelFields = $excel->getFields();
                $excelArr = $excel->getAllData();
                $excel->Close();
                $ckA = array();
                $ckB = array();
                echo $flag;
                //die('xgq');
                if ($flag == 'bxht') {

                    if (count($excelArr) && !empty($excelArr)) {
                        $str .= '<tr><td>���</td><td>��ID</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        $infoE = array();
                        $sql = "SELECT orderCode , id  FROM `oa_sale_outsourcing` where 1 
UNION 
SELECT orderCode , id  FROM `oa_sale_other` where 1 ;";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $infoE[$row['orderCode']] = $row['id'];
                        }
                        foreach ($excelArr['�º�ͬ��'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            $str .= '<td>' . $infoE[$val] . '</td>';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                    }
                }

                if ($flag == 'hr_email') {

                    if (count($excelArr) && !empty($excelArr)) {
                        $str .= '<tr><td>���</td><td>����</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        $infoE = array();
                        $sql = "select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id and u.del=0 and u.has_left=0 ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $infoE[$row['usercard']] = $row['email'];
                        }
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            $str .= '<td>' . $infoE[$val] . '</td>';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                    }
                }
                if ($flag == 'hr_up') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $str .= '<tr><td>���</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        $infoE = array();
                        $sql = "select u.email , h.usercard
                            from user u
                                left join hrms h on (u.user_id=h.user_id)
                            where u.user_id=h.user_id and u.del=0 and u.has_left=0 ";
                        $query = $this->db->query_exc($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $infoE[] = $row['usercard'];
                        }
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $i++;
                            if (in_array($val, $infoE)) {
                                $str .= '<tr>';
                            } else {
                                $str .= '<tr style="color:red;">';
                            }
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                    }
                }

                if ($flag == 'bx_email') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $userInfo = array();
                        $userEmail = array();
                        $userLeft = array();
                        //
                        $str .= '<tr>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            //$str.='<td>'.$i.'</td>';
                            foreach ($excelFields as $fval) {
                                if ($fval == '����') {
                                    $doType = $excelArr[$fval][$key];
                                    if ($doType == '1') {
                                        $excelArr['�ʼ�'][$key] = '1';
                                    } elseif ($doType == '����') {
                                        $excelArr['�ʼ�'][$key] = '2';
                                    } elseif ($doType == '�ѿ�����') {
                                        $excelArr['�ʼ�'][$key] = '3';
                                    }
                                }
                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }

                if ($flag == 'gcgz') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $userInfo = array();
                        $userEmail = array();
                        $userLeft = array();
                        $sql = "SELECT
									h.UserCard ,
									u.email
								FROM
									USER u
								LEFT JOIN hrms h on (u.USER_ID = h.USER_ID)
								WHERE
									del = '0' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $userInfo[$row['UserCard']] = $row['email'];
                        }
                        $str .= '<tr>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['Ա�����'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            //$str.='<td>'.$i.'</td>';
                            $excelArr['����'][$key] = $userInfo[$excelArr['Ա�����'][$key]];

                            foreach ($excelFields as $fval) {

                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }

                //�ӹ�˾�ϲ�
                if ($flag == 'uname_ck') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $userInfo = array();
                        $userEmail = array();
                        $userLeft = array();
                        $sql = "select user_id , logName , email  , has_left  from user 
                				where del='0' and user_id not in ( 
                					'chen.chen','xibiao.chen','junqiang.lu','xuanna.weng','zequan.xu'
                				) ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $userInfo[$row['user_id']] = $row['user_id'];
                            $userInfo[$row['logName']] = $row['logName'];
                            $userEmail[$row['email']] = $row['email'];
                            $userLeft[$row['user_id']] = ($row['has_left'] == '1' ? '��ְ' : '��ְ');
                            $userLeft[$row['logName']] = ($row['has_left'] == '1' ? '��ְ' : '��ְ');
                        }
                        $jobInfo = array();
                        $sql = "select d.dept_id , j.id , j.name , d.DEPT_NAME from user_jobs j
LEFT JOIN department d on (d.DEPT_ID = j.dept_id)
WHERE 1";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $jobInfo[$row['DEPT_NAME']][$row['name']] = $row['id'];
                        }
                        //
                        $str .= '<tr>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            //$str.='<td>'.$i.'</td>';
                            foreach ($excelFields as $fval) {
//                 				if($fval=='����'){
//                 					$uid = $this->getUserPinyin( trim($excelArr[$fval][$key])  );
//                 					$uemail = $uid.'@dinglicom.com';
//                 					$uidc = '';
//                 					$uemailc = '' ;
//                 					$uidlen = strlen($uid);
//                 					foreach ( $userInfo as $uval ){
//                 						//$pos = strpos($uval, $uid);
//                 						if( substr($uval, 0 , $uidlen ) == $uid ){
//                 							$uidc= '�ظ�';
//                 							$uemailc .= $uval.'('.$userLeft[$uval].')'.'��' ;
//                 						}
//                 					}
//                 					$excelArr['OA�˺�'][$key] = ( $uidc ==''? $uid:$uidc )  ;
//                 					$excelArr['���������ַ'][$key] = ( $uemailc ==''? $uemail:$uemailc )   ;
//                 				}
                                if ($fval == 'OA�˺�') {
                                    $uid = trim($excelArr[$fval][$key]);
                                    $uemail = $uid . '@dinglicom.com';
                                    $uidc = '';
                                    $uemailc = '';
                                    foreach ($userInfo as $uval) {
                                        if ($uval == $uid) {
                                            $uidc = '�ظ�';
                                            $uemailc .= $uval . '(' . $userLeft[$uval] . ')' . '��';
                                        }
                                    }
                                    //��
                                    foreach ($excelArr['OA�˺�'] as $bkey => $bval) {
                                        if ($bval == $uid && $bkey != $key) {
                                            $uidc = '�ظ�';
                                            $uemailc .= $uval . '(2)' . '��';
                                        }
                                    }
                                    $excelArr['OA�˺�'][$key] = ($uidc == '' ? $uid : $uidc);
                                    $excelArr['���������ַ'][$key] = ($uemailc == '' ? $uemail : $uemailc);
                                    $excelArr['ְλ'][$key] = $jobInfo[$excelArr['��������'][$key]][$excelArr['ְλ'][$key]];
                                }
                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }
                //�ӹ�˾�ϲ�
                if ($flag == 'job_ini') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $deptInfo = array();
                        $jobInfo = array();
                        $sql = "select dept_id , dept_name from department ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $deptInfo[$row['dept_name']] = $row['dept_id'];
                        }
                        $sql = "select d.dept_id , j.id , j.name , d.DEPT_NAME from user_jobs j 
LEFT JOIN department d on (d.DEPT_ID = j.dept_id)
WHERE 1";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $jobInfo[$row['DEPT_NAME']][$row['name']] = $row['id'];
                        }
                        //
                        $str .= '<tr><td>���</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        $newjob = array();
                        foreach ($excelArr['��������'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $col = '';
                                if (empty($deptInfo[$excelArr[$fval][$key]]) && $fval == '����') {
                                    $col = ' color="red" ';
                                }
                                if (!empty($userInfo[$excelArr[$fval][$key]]) && $fval == 'OA�û���') {
                                    $col = ' color="red" ';
                                    $ckr .= $excelArr[$fval][$key] . '<br>';
                                }
                                if (empty($eduInfo[$excelArr[$fval][$key]]) && $fval == 'ѧ��') {
                                    $col = ' color="red" ';
                                }
                                if (empty($brInfo[$excelArr[$fval][$key]]) && $fval == '��˾') {
                                    $col = ' color="red" ';
                                }
                                $str .= '<td ><font ' . $col . '>' . $excelArr[$fval][$key] . '<font></td>';


                            }
                            $newjob[$excelArr['��������'][$key]][$excelArr['ְ��'][$key]] = $jobInfo[$excelArr['��������'][$key]][$excelArr['ְ��'][$key]];
                            $str .= '</tr>';
                        }
                        $ckr;
                        $str = '<tr><td>����</td><td>ְλ</td><td>ID</td></tr>';
                        foreach ($newjob as $key => $val) {

                            foreach ($val as $vkey => $vval) {

                                if (empty($vval) && !empty($vkey)) {

                                    $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str)
                                            values
                                            ('" . $vkey . "','" . $deptInfo[$key] . "','0','')");
                                    $jobinsertid = $this->db->insert_id();

                                    $this->db->query_exc("insert into user_priv(priv_name,user_priv)
                							values('" . $vkey . "','$jobinsertid')");

                                    $str .= '<tr><td>' . $key . '</td><td>' . $vkey . '</td><td>' . $jobinsertid . '</td></tr>';
                                }
                            }
                        }
                        //print_r($newjob);
                    }
                }
                //�ӹ�˾�ϲ�
                if ($flag == 'bx_ini') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $deptInfo = array();
                        $jobInfo = array();
                        $sql = "select d.dept_id , j.id , j.name , d.DEPT_NAME from user_jobs j
LEFT JOIN department d on (d.DEPT_ID = j.dept_id)
WHERE 1";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $jobInfo[$row['DEPT_NAME']][$row['name']] = $row['id'];
                            $deptInfo[$row['DEPT_NAME']] = $row['dept_id'];
                        }
                        //
                        $str .= '<tr><td>���</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $col = '';
                                if (empty($deptInfo[$excelArr[$fval][$key]]) && $fval == '����') {
                                    $col = ' color="red" ';
                                }
                                if (!empty($userInfo[$excelArr[$fval][$key]]) && $fval == 'OA�û���') {
                                    $col = ' color="red" ';
                                    $ckr .= $excelArr[$fval][$key] . '<br>';
                                }
                                if (empty($eduInfo[$excelArr[$fval][$key]]) && $fval == 'ѧ��') {
                                    $col = ' color="red" ';
                                }
                                if (empty($brInfo[$excelArr[$fval][$key]]) && $fval == '��˾') {
                                    $col = ' color="red" ';
                                }
                                if ($fval == '��������') {
                                    $excelArr['ְλ'][$key] = $jobInfo[$excelArr['��������'][$key]][$excelArr['ְλ'][$key]];
                                    $excelArr[$fval][$key] = $deptInfo[$excelArr[$fval][$key]];

                                }
                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }
                //�ӹ�˾�ϲ�
                if ($flag == 'zgs_ini') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $deptInfo = array();
                        $areaInfo = array('' => '1');
                        $jobInfo = array();
                        $sexInfo = array('��' => '0', 'Ů' => '1');
                        $eduInfo = array("ר��" => '1', "����" => '2', "����" => '3', "��ר" => '4', "��ר" => '5'
                        , "����" => '6', "˶ʿ" => '7', "�о���" => '8', "��ʿ" => '9', "δ�����������" => '10');
                        $sql = "select dept_id , dept_name from department ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $deptInfo[$row['dept_name']] = $row['dept_id'];
                        }
                        $sql = "select name , id from area where del='0' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $areaInfo[$row['name']] = $row['id'];
                        }
                        $sql = "select name , id from user_jobs  ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $jobInfo[$row['name']] = $row['id'];
                        }
                        $sql = "select user_name , user_id , has_left from user where del='0' and has_left='0' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $userInfo[$row['user_id']]['na'] = $row['user_name'];
                            $userInfo[$row['user_id']]['le'] = $row['has_left'];
                        }
                        $sql = "SELECT namecn , namept FROM branch_info where type='1' ";
                        $query = $this->db->query($sql);
                        while ($row = $this->db->fetch_array($query)) {
                            $brInfo[$row['namecn']] = $row['namept'];
                        }
                        //
                        $str .= '<tr><td>���</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $col = '';
                                if (empty($deptInfo[$excelArr[$fval][$key]]) && $fval == '����') {
                                    $col = ' color="red" ';
                                }
                                if (!empty($userInfo[$excelArr[$fval][$key]]) && $fval == 'OA�û���') {
                                    $col = ' color="red" ';
                                    $ckr .= $excelArr[$fval][$key] . '<br>';
                                }
                                if (empty($eduInfo[$excelArr[$fval][$key]]) && $fval == 'ѧ��') {
                                    $col = ' color="red" ';
                                }
                                if (empty($brInfo[$excelArr[$fval][$key]]) && $fval == '��˾') {
                                    $col = ' color="red" ';
                                }
                                $str .= '<td ><font ' . $col . '>' . $excelArr[$fval][$key] . '<font></td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }
                if ($flag == 'ecard-up') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $str .= '<tr>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $str .= '<tr>';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                    }
                }
                if ($flag == 'gcbz') {
                    $sql = "select id ,  projectcode , projectname
                        from oa_esm_project ";
                    $query = $this->db->query_exc($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        $ck[$row['id']] = $row['projectcode'];
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        $str .= '<tr>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '<td>��֤</td>';
                        $str .= '</tr>';
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $str .= '<tr>';
                            $remark = '';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                                if ($fval == '��Ŀ���' && !in_array(trim($excelArr[$fval][$key]), $ck)) {
                                    $remark .= '��Ŀ������';
                                }
                            }
                            $str .= '<td style = " color:red ;">' . $remark . '</td>';
                            $str .= '</tr>';
                        }
                    }
                }
                if ($flag == 'htdq') {
                    $sql = "select id , areaname , areaprincipal , areaprincipalid
                        from oa_system_region ";
                    $query = $this->db->query_exc($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        $ck[$row['areaname']] = $row['areaprincipal'];
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        $str .= '<tr>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '<td>��������</td></tr>';
                        foreach ($excelArr['ҵ����'] as $key => $val) {
                            $str .= '<tr>';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                            }

                            $str .= '<td>' . $ck[$excelArr['����������'][$key]] . '</td></tr>';
                        }
                    }
                }
                if ($flag == 'hrupdate') {
                    if (!in_array('Ա����', $excelFields) || !in_array('����', $excelFields)
                        || !in_array('�������´�', $excelFields) || !in_array('ְλ', $excelFields)
                    ) {
                        throw new Exception('Update failed');
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $infoE[$val]['name'] = $excelArr['����'][$key];
                            $infoE[$val]['blg'] = $excelArr['�������´�'][$key];
                            $infoE[$val]['pos'] = $excelArr['ְλ'][$key];
                            $infoE[$val]['flag'] = '0';
                        }
                    }
                    $sql = "select u.email , h.usercard
                        from user u
                            left join hrms h on (u.user_id=h.user_id)
                        where u.user_id=h.user_id and u.dept_id='35'";
                    $query = $this->db->query_exc($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        if (array_key_exists($row['usercard'], $infoE)) {
                            $infoE[$row['usercard']]['flag'] = '1';
                        }
                    }
                    if (!empty($infoE)) {
                        $i = 1;
                        $str .= '<tr>
                                    <td>���</td>
                                    <td>Ա����</td>
                                    <td>����</td>
                                    <td>�������´�</td>
                                    <td>ְλ</td>
                                </tr>';
                        foreach ($infoE as $key => $val) {
                            if ($val['flag'] == '1') {
                                $str .= '<tr style="color:green;">
                                    <td>' . $i . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['blg'] . '</td>
                                    <td>' . $val['pos'] . '</td>
                                </tr>';
                            } else {
                                $str .= '<tr style="color:#FF9900;">
                                    <td>' . $i . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['blg'] . '</td>
                                    <td>' . $val['pos'] . '</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                } elseif ($flag == 'levechange') {
                    if (!in_array('Ա����', $excelFields) || !in_array('����', $excelFields)
                        || !in_array('���뼶��', $excelFields) || !in_array('��֤�������󼶱�', $excelFields)
                    ) {
                        throw new Exception('Update failed');
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $infoE[$val]['name'] = $excelArr['����'][$key];
                            $infoE[$val]['lev'] = $excelArr['���뼶��'][$key];
                            $infoE[$val]['sal'] = $excelArr['��֤�������󼶱�'][$key];
                            $infoE[$val]['flag'] = '0';
                        }
                    }
                    $sql = "select u.email , h.usercard
                        from user u
                            left join hrms h on (u.user_id=h.user_id)
                        where u.user_id=h.user_id ";
                    $query = $this->db->query_exc($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        if (array_key_exists($row['usercard'], $infoE)) {
                            $infoE[$row['usercard']]['flag'] = '1';
                            $infoE[$row['usercard']]['email'] = $row['email'];
                        }
                    }
                    if (!empty($infoE)) {
                        $i = 1;
                        $str .= '<tr>
                                    <td>���</td>
                                    <td>Ա����</td>
                                    <td>����</td>
                                    <td>���뼶��</td>
                                    <td>��֤�������󼶱�</td>
                                    <td>Email</td>
                                </tr>';
                        foreach ($infoE as $key => $val) {
                            if ($val['flag'] == '1') {
                                $str .= '<tr style="color:green;">
                                    <td>' . $i . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['lev'] . '</td>
                                    <td>' . $val['sal'] . '</td>
                                    <td>' . $val['email'] . '</td>
                                </tr>';
                            } else {
                                $str .= '<tr style="color:#FF9900;">
                                    <td>' . $i . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['lev'] . '</td>
                                    <td>' . $val['sal'] . '</td>
                                    <td style="color:red;">����Ա���Ƿ����Ų�������ְ��</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                } elseif ($flag == 'userupdate') {
                    $sql = "select
                            u.user_id , u.user_name , u.del , u.has_left
                        from
                            user u
                            left join hrms h on(u.user_id=h.user_id)
                        where u.user_id=h.user_id  and u.dept_id<>1 order by h.come_date  ";
                    $query = $this->db->query_exc($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        $ckA[] = $row['user_name'];
                        $infoA[$row['user_id']]['name'] = $row['user_name'];
                        if ($row['del'] == '1' || $row['has_left'] == '1') {
                            $infoA[$row['user_id']]['leave'] = '<font color="red">��ְ</font>';
                        } else {
                            $infoA[$row['user_id']]['leave'] = '��ְ';
                        }
                    }
                    $infoB = array();
                    $infoC = array();
                    $infoD = array();
                    $infoE = array();
                    $infoF = array();
                    $infoJ = array();
                    if (!empty($infoA)) {
                        foreach ($infoA as $key => $val) {
                            $sk = array_search($val['name'], $excelArr['����']);
                            if ($sk !== FALSE && $excelArr['����'][$sk]) {
                                $ckB[] = $excelArr['����'][$sk];
                                /*
                                if(array_key_exists($excelArr['����'][$sk], $infoB)){
                                    $infoB[$excelArr['����'][$sk]]['name']=$infoB[$excelArr['����'][$sk]]['name'].$val['name'];
                                }else{
                                    $infoB[$excelArr['����'][$sk]]['name']=$val['name'];
                                }
                                */
                                $infoB[$excelArr['����'][$sk]]['name'] = $val['name'];
                                $infoB[$excelArr['����'][$sk]]['leave'] = $val['leave'];
                                $infoB[$excelArr['����'][$sk]]['sex'] = $excelArr['�Ա�'][$sk];
                                $infoB[$excelArr['����'][$sk]]['dept'] = $excelArr['����'][$sk];
                            } elseif ($val['leave'] == '��ְ') {
                                $infoC[$key] = $val;
                            } else {
                                $infoD[$key] = $val;
                            }
                        }
                        foreach ($excelArr['����'] as $key => $val) {
                            if (array_key_exists($excelArr['����'][$key], $infoF)) {
                                $infoF[$excelArr['����'][$key]] += 1;
                            } else {
                                $infoF[$excelArr['����'][$key]] = 1;
                            }
                            if (array_search($val, $ckA) === false) {
                                $infoE[$key] = $val;
                            }
                            if (array_search($excelArr['����'][$key], $ckB) === false) {
                                $infoJ[$key] = $val;
                            }
                        }
                    }
                    $i = 1;
                    if (!empty($infoB)) {
                        ksort($infoB);
                        foreach ($infoB as $key => $val) {
                            $str .= '<tr style="color:green;">
                                    <td>' . $i . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['sex'] . '</td>
                                    <td>' . $val['dept'] . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['leave'] . '</td>
                                </tr>';
                            $i++;
                        }
                    }
                    if (!empty($infoC)) {
                        foreach ($infoC as $key => $val) {
                            $str .= '<tr style="color:blue;">
                                <td>' . $val['name'] . '</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>' . $val['leave'] . '</td>
                            </tr>';
                        }
                    }
                    if (!empty($infoD)) {
                        foreach ($infoD as $key => $val) {
                            $str .= '<tr style="color:blue;">
                                <td>' . $val['name'] . '</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>' . $val['leave'] . '</td>
                            </tr>';
                        }
                    }
                    if (!empty($infoE)) {
                        foreach ($infoE as $key => $val) {
                            $str .= '<tr >
                                <td>' . $val . '</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                    if (!empty($infoF)) {
                        foreach ($infoF as $key => $val) {
                            if ($val == '1') {
                                continue;
                            }
                            $str .= '<tr style="color:red;">
                                <td>' . $key . '</td>
                                <td>' . $val . '</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                    if (!empty($infoJ)) {
                        foreach ($infoJ as $key => $val) {
                            $str .= '<tr >
                                <td>' . $val . '</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }
                } elseif ($flag == 'yeb') {
                    if (!in_array('Ա����', $excelFields) || !in_array('����', $excelFields)
                        || !in_array('���ս�', $excelFields)
                        || !in_array('��˰', $excelFields) || !in_array('ʵ�����', $excelFields)
                    ) {
                        throw new Exception('Update failed');
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        foreach ($excelArr['Ա����'] as $key => $val) {
                            $infoE[$val]['name'] = $excelArr['����'][$key];
                            $infoE[$val]['yam'] = $excelArr['���ս�'][$key];
                            $infoE[$val]['kam'] = $excelArr['��˰'][$key];
                            $infoE[$val]['pam'] = $excelArr['ʵ�����'][$key];
                            $infoE[$val]['flag'] = '0';
                        }
                    }
                    $sql = "select u.email , h.usercard
                        from user u
                            left join hrms h on (u.user_id=h.user_id)
                        where u.user_id=h.user_id ";
                    $query = $this->db->query_exc($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        if (array_key_exists($row['usercard'], $infoE)) {
                            $infoE[$row['usercard']]['flag'] = '1';
                            $infoE[$row['usercard']]['email'] = $row['email'];
                        }
                    }
                    if (!empty($infoE)) {
                        $i = 1;
                        $str .= '<tr>
                                    <td>���</td>
                                    <td>Ա����</td>
                                    <td>����</td>
                                    <td>���ս�</td>
                                    <td>��˰</td>
                                    <td>ʵ�����</td>
                                    <td>Email</td>
                                </tr>';
                        foreach ($infoE as $key => $val) {
                            if ($val['flag'] == '1') {
                                $str .= '<tr style="color:green;">
                                    <td>' . $i . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['yam'] . '</td>
                                    <td>' . $val['kam'] . '</td>
                                    <td>' . $val['pam'] . '</td>
                                    <td>' . $val['email'] . '</td>
                                </tr>';
                            } else {
                                $str .= '<tr style="color:#FF9900;">
                                    <td>' . $i . '</td>
                                    <td>' . $key . '</td>
                                    <td>' . $val['name'] . '</td>
                                    <td>' . $val['yam'] . '</td>
                                    <td>' . $val['kam'] . '</td>
                                    <td>' . $val['pam'] . '</td>
                                    <td style="color:red;">����Ա�����Ƿ���ȷ��</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $str = '<tr><td colspan="10">�������ݴ��������ʽ��' . $e->getMessage() . '</td></tr>';
        }
        return $str;
    }

    function model_ini()
    {
        $card = 1;
        $userArr = array();
        $sql = "select u.user_id
            from hrms h
                left join user u on (h.user_id=u.user_id )
            where h.usercard='' and ( u.del='1' or u.has_left='1' ) ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $muc = sprintf("%05.0f", $card);
            $sql = "update hrms set usercard='L" . $muc . "' where user_id='" . $row['user_id'] . "' ";
            $this->_db->query($sql);
            $card++;
        }
    }

    /**
     * ��ȡ����ƴ��
     * @return string
     */
    function getUserPinyin($username)
    {
        if ($username) {
            $len = iconv_strlen($username, 'GBK');
            $word = array();
            for ($j = 1; $j < $len; $j++) {
                for ($i = 0; $i < $len; $i++) {
                    $word[] = iconv_substr($username, $i, $j, 'GBK');
                }
            }
            if ($word) {
                $pinyin = new includes_class_pinyin();
                $name = '';
                for ($i = 1; $i < $len; $i++) {
                    $name .= $word[$i];
                }
                return $pinyin->GetPinyin($name) . '.' . $pinyin->GetPinyin($word[0]);
            }
        }
    }

    //*********************************��������************************************
    function __destruct()
    {
    }

}

?>