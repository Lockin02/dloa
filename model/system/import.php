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

    //*******************************构造函数***********************************
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
                //读取excel信息
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
                            foreach ($excelArr['新合同号'] as $key => $val) {
                                if ($excelArr['类型'][$key] == '其他') {
                                    $sql = "UPDATE oa_sale_other 
SET
	initInvotherMoney ='" . $excelArr['收票'][$key] . "' ,  initInvoiceMoney ='" . $excelArr['开票'][$key] . "' 
	,initIncomeMoney ='" . $excelArr['到款'][$key] . "' , initPayMoney ='" . $excelArr['付款'][$key] . "'
    , orderCode = CONCAT( '" . $excelArr['旧合同号'][$key] . "'  ,'BX')
   	, ExaStatus  = '完成' , `status` = 2 
    , createName ='admin' , createId ='admin'
where 
	orderCode = '" . $excelArr['新合同号'][$key] . "' ";
                                } elseif ($excelArr['类型'][$key] == '外包') {
                                    $sql = "UPDATE oa_sale_outsourcing 
SET
	initInvoiceMoney ='" . $excelArr['开票'][$key] . "' ,  initPayMoney ='" . $excelArr['付款'][$key] . "' 
	, orderCode = CONCAT( '" . $excelArr['旧合同号'][$key] . "' ,'BX')
	, ExaStatus  = '完成' , `status` = 2 
	, createName ='admin' , createId ='admin'
where 
	orderCode = '" . $excelArr['新合同号'][$key] . "' ";
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
                            foreach ($excelArr['员工号'] as $key => $val) {
                                if (in_array(trim($excelArr['项目编号'][$key]), $ck)) {
                                    $data[$excelArr['员工号'][$key]][$excelArr['项目编号'][$key]][] =
                                        array(
                                            'dt' => $excelArr['开始时间'][$key]
                                        , 'et' => $excelArr['结束时间'][$key]
                                        , 'dy' => $excelArr['工期(天)'][$key]
                                        , 'dd' => $excelArr['地点'][$key]
                                        , 'am' => $excelArr['补助金额'][$key]
                                        , 'rm' => $excelArr['备注'][$key]);
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
													   from cost_detail_import Where deptId='".$deptId."' and officeId='".$officeId."' and DATE_FORMAT(createTime, '%Y-%m')= DATE_FORMAT(NOW(), '%Y-%m')  AND ( ExaStatus='打回' OR ExaStatus IS NULL) ";
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
 SELECT user_id, now(), user_id, 1 AS isProject, '" . $vkey . "' AS ProjectNO, '' AS Purpose, '提交' AS `Status`, 4 AS CostBelongTo, 1 AS xm_sid, 2 AS DetailType, '" . $_SESSION[' USER_ID '] . "' AS daoUser, 1 AS daoFlag, now() AS daoTime,'$imId' as imId
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
																							'提交', 
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
                        if (!in_array('员工号', $excelFields) || !in_array('姓名', $excelFields)
                            || !in_array('归属办事处', $excelFields) || !in_array('职位', $excelFields)
                        ) {
                            throw new Exception('Update failed');
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            foreach ($excelArr['员工号'] as $key => $val) {
                                $infoE[$val]['name'] = $excelArr['姓名'][$key];
                                $infoE[$val]['blg'] = $excelArr['归属办事处'][$key];
                                $infoE[$val]['pos'] = $excelArr['职位'][$key];
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
                        foreach ($excelArr['业务编号'] as $key => $val) {
                            $sql = "update oa_contract_contract set  
                                    areacode ='" . $ck[$excelArr['新增区域列'][$key]]['id'] . "'
                                    , areaname ='" . $ck[$excelArr['新增区域列'][$key]]['aname'] . "'
                                    , areaprincipal ='" . $ck[$excelArr['新增区域列'][$key]]['uname'] . "'
                                    , areaprincipalid ='" . $ck[$excelArr['新增区域列'][$key]]['uid'] . "'
                                  where 
                                    objcode='" . $excelArr['业务编号'][$key] . "'
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
                            foreach ($excelArr['员工号'] as $key => $val) {
                                try {
                                    $tmpstr = '<DIV><FONT size=2 face=Verdana>&nbsp;<FONT size=2 face=Verdana>' . $excelArr['姓名'][$key] . ',您好！</FONT>
                                        <br/><br/>
                                        &nbsp;<FONT size=2 face=Verdana>' . $excelArr['通知'][$key] . '。</FONT>
                                        </DIV>
                                        <br/>
                                        <DIV><FONT color=#c0c0c0 size=2 face=Verdana>世纪鼎利</FONT></DIV>
                                        <DIV><FONT color=#c0c0c0 size=2 face=Verdana>2012-07-30 </FONT></DIV>';
                                    $this->emailClass->send('2012年度任职资格认证结果反馈', $tmpstr, $infoE[$val], '世纪鼎利', '世纪鼎利');
                                } catch (Exception $e) {
                                    return $e->getMessage();
                                }
                            }
                        }
                    } elseif ($flag == 'hr_up') {
                        if (count($excelArr) && !empty($excelArr)) {
                            $sexInfo = array('男' => '0', '女' => '1');
                            $eduInfo = array("专科" => '1', "初中" => '2', "高中" => '3', "中专" => '4', "大专" => '5'
                            , "本科" => '6', "硕士" => '7', "研究生" => '8', "博士" => '9', "未接受正规教育" => '10');
                            foreach ($excelArr['员工号'] as $key => $val) {
                                try {
                                    $ckdt = substr($excelArr['转正日期'][$key], 0, 6);
                                    if ($ckdt == '201207') {
                                        $cksta = '1';
                                    } else {
                                        $cksta = '0';
                                    }
                                    $this->db->query("START TRANSACTION");
                                    $sql = "update hrms h
                                        left join salary s on (s.userid=h.user_id)
                                        set 
                                            h.ContractState='" . $excelArr['签合同次数'][$key] . "'
                                            ,h.ExpFlag='" . $excelArr['用工方式'][$key] . "'
                                            ,h.JOIN_DATE='" . $excelArr['转正日期'][$key] . "'
                                            ,h.ContFlagB='" . $excelArr['合同起始日期'][$key] . "'
                                            ,h.ContFlagE='" . $excelArr['合同终止日期'][$key] . "'
                                            ,s.usersta='" . $cksta . "'
                                        where h.usercard='" . $excelArr['员工号'][$key] . "' and s.userid=h.user_id";
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
                            //获取部门/区域/职位数据
                            $deptInfo = array();
                            $areaInfo = array('' => '1');
                            $jobInfo = array();
                            $sexInfo = array('男' => '0', '女' => '1');
                            $eduInfo = array("专科" => '1', "初中" => '2', "高中" => '3', "中专" => '4', "大专" => '5'
                            , "本科" => '6', "硕士" => '7', "研究生" => '8', "博士" => '9', "未接受正规教育" => '10');
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
                            foreach ($excelArr['员工号'] as $key => $val) {
                                try {
                                    $this->db->query("START TRANSACTION");
                                    $tempdept = array();
                                    $tempdeptname = '';
                                    $tempdept = $excelArr['部门'][$key];
                                    $tempdeptname = $tempdept;
                                    $excelArr['员工号'][$key] = '0' . $excelArr['员工号'][$key];
                                    $excelArr['邮箱地址'][$key] = $excelArr['邮箱地址'][$key] . '@bettercomm.net';
                                    //默认普通员工
                                    if (empty($excelArr['职位'][$key])) {
                                        $excelArr['职位'][$key] = '普通员工';
                                    }
                                    //职位新增
                                    if (empty($jobInfo[$excelArr['职位'][$key]])) {
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('" . $excelArr['职位'][$key] . "','" . $deptInfo[$tempdeptname] . "','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('" . $excelArr['职位'][$key] . "','$jobinsertid')");
                                        $jobInfo[$excelArr['职位'][$key]] = $jobinsertid;
                                    }
                                    //用户表
                                    $sql = "insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '" . $excelArr['员工号'][$key] . "','" . $excelArr['姓名'][$key] . "','" . $pw . "'
                                            ,'" . $excelArr['员工号'][$key] . "','" . $jobInfo[$excelArr['职位'][$key]] . "','" . $jobInfo[$excelArr['职位'][$key]] . "',
                                            '" . $deptInfo[$tempdeptname] . "','" . $sexInfo[$excelArr['性别'][$key]] . "','" . $excelArr['邮箱地址'][$key] . "',
                                            '" . $areaInfo[$excelArr['区域'][$key]] . "','贝讯',now(),'" . $_SESSION['USER_ID'] . "'
                                        )";
                                    $this->db->query_exc($sql);
                                    //人事表
                                    $sql = "insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('" . $excelArr['员工号'][$key] . "','" . $excelArr['身份证'][$key] . "','" . $excelArr['入职日期'][$key] . "','" . $excelArr['转正日期'][$key] . "'
    ,'" . $eduInfo[$excelArr['学历'][$key]] . "','" . $excelArr['职位'][$key] . "','" . $excelArr['毕业学校'][$key] . "','" . $excelArr['专业'][$key] . "'
    ,'" . $excelArr['家庭地址'][$key] . "','" . $excelArr['银行帐号'][$key] . "','" . $excelArr['卡号'][$key] . "','" . $excelArr['开户行'][$key] . "'
    ,'" . $excelArr['手机号码'][$key] . "','" . $excelArr['户籍地'][$key] . "','" . $excelArr['邮箱地址'][$key] . "','" . $_SESSION['USER_ID'] . "'
    ,now(),'" . $excelArr['备注'][$key] . "','" . $excelArr['合同期始'][$key] . "','" . $excelArr['合同期止'][$key] . "','" . $excelArr['员工号'][$key] . "'
        )";
                                    $this->db->query_exc($sql);
                                    $sql = "insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '" . $excelArr['员工号'][$key] . "',
                                        '" . $excelArr['姓名'][$key] . "',
                                        '" . $sexInfo[$excelArr['性别'][$key]] . "',
                                        '" . $deptInfo[$tempdeptname] . "',
                                        'http://www.bettercomm.net',
                                        '" . $excelArr['邮箱地址'][$key] . "',
                                        '',
                                        '" . date('Y-m-d H:i:s') . "',
                                        '" . $excelArr['公司电话'][$key] . "',
                                        '" . $excelArr['手机号码'][$key] . "'
                                        )";
                                    $this->db->query_exc($sql);
                                    $this->db->query("COMMIT");


                                    //=========创建IM用户==============

                                    $data = array(
                                        'COM_BRN_CN' => '广州贝讯',
                                        'DEPT_NAME' => $excelArr['部门'][$key],
                                        'USER_ID' => $excelArr['员工号'][$key],
                                        'USER_NAME' => $excelArr['姓名'][$key],
                                        'PASSWORD' => $pw,
                                        'SEX' => $sexInfo[$excelArr['性别'][$key]],
                                        'EMAIL' => $excelArr['邮箱地址'][$key],
                                        'JOBS_NAME' => $excelArr['职位'][$key],
                                        'Mobile' => '',
                                        'Phone' => ''
                                    );
                                    $msg = $this->im->add_user($data);
                                    if ($msg) {
                                        $creat_im = '创建IM帐号成功';
                                    } else {
                                        $creat_im = '创建IM帐号失败';
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
                            //获取部门/区域/职位数据
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
                            foreach ($excelArr['员工号'] as $key => $val) {
                                try {
                                    $this->db->query("START TRANSACTION");
                                    $tmp = array();
                                    //
                                    $tmp['usercard'] = $excelArr['员工号'][$key];
                                    $tmp['username'] = $excelArr['姓名'][$key];
                                    $tmp['userid'] = $excelArr['OA账号'][$key];
                                    $tmp['deptid'] = $deptInfo[$excelArr['所属部门'][$key]];
                                    $tmp['deptname'] = $excelArr['所属部门'][$key];
                                    $tmp['jobid'] = $jobInfo[$excelArr['所属部门'][$key]][$excelArr['职位'][$key]];
                                    $tmp['jobname'] = $excelArr['职位'][$key];
                                    $tmp['cd'] = $excelArr['身份证'][$key];
                                    $tmp['bxe'] = $excelArr['贝讯邮箱地址'][$key];
                                    $tmp['dle'] = $excelArr['鼎利邮箱'][$key];
                                    $tmp['isb'] = $excelArr['开通'][$key];
                                    $tmp['pw'] = substr($excelArr['身份证'][$key], -8, 8);
                                    $tmp['imd'] = $setDept[$excelArr['所属部门'][$key]];
                                    //用户表
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
                                    //人事表
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
                                        ,'" . $tmp['dle'] . "','广州贝讯'
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


                                    //=========创建IM用户==============

                                    $data = array(
                                        'COM_BRN_CN' => '世纪鼎利',
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
                                        $creat_im = '创建IM帐号成功';
                                    } else {
                                        $creat_im = '创建IM帐号失败';
                                        throw new ErrorException('shibai-im');
                                    }

                                    //=========创建邮箱==============
                                    $AddEmail = '';
                                    $AddEmail_URL = Email_Server_Api_Url . '?action=AddUser&key=' . urlencode(authcode(oa_auth_key . ' ' . time(), 'ENCODE'));
                                    $AddEmail_URL .= '&userid=' . $tmp['userid'] . '&username=' . $tmp['username'] . '&password=' . $tmp['pw'] . '';
                                    $AddEmail_URL .= '&domain=dinglicom.com&deptname=' . $tmp['deptname'];
                                    if ($tmp['isb'] != '已开通') {
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
                                    $filestr .= $excelArr['员工号'][$key] . '--' . $e->getMessage() . "\n";
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
                            //获取部门/区域/职位数据
                            $deptInfo = array();
                            $areaInfo = array('' => '1');
                            $jobInfo = array();
                            $sexInfo = array('男' => '0', '女' => '1');
                            $eduInfo = array("专科" => '1', "初中" => '2', "高中" => '3', "中专" => '4', "大专" => '5'
                            , "本科" => '6', "硕士" => '7', "研究生" => '8', "博士" => '9', "未接受正规教育" => '10');
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
                            foreach ($excelArr['员工号'] as $key => $val) {
                                try {
                                    $this->db->query("START TRANSACTION");
                                    $tempdept = array();
                                    $tempdeptname = '';
                                    $tempdept = $excelArr['部门'][$key];
                                    $tempdeptname = $tempdept;
                                    $excelArr['员工号'][$key] = '0' . $excelArr['员工号'][$key];
                                    $excelArr['邮箱地址'][$key] = $excelArr['OA用户名'][$key] . '@dinglicom.com';
                                    //默认普通员工
                                    if (empty($excelArr['职位'][$key])) {
                                        $excelArr['职位'][$key] = '普通员工';
                                    }
                                    //职位新增
                                    if (empty($jobInfo[$excelArr['职位'][$key]])) {
                                        $this->db->query_exc("insert into user_jobs(name,dept_id,level,func_id_str) 
                                            values
                                            ('" . $excelArr['职位'][$key] . "','" . $deptInfo[$tempdeptname] . "','0','')");
                                        $jobinsertid = $this->db->insert_id();
                                        $this->db->query_exc("insert into user_priv(priv_name,user_priv)values('" . $excelArr['职位'][$key] . "','$jobinsertid')");
                                        $jobInfo[$excelArr['职位'][$key]] = $jobinsertid;
                                    }
                                    //用户表
                                    $sql = "insert into user(
                                            user_id,user_name,password,logname,user_priv
                                            ,jobs_id,dept_id,sex,email
                                            ,area,company,creatdt,creator
                                        )values(
                                            '" . $excelArr['员工号'][$key] . "','" . $excelArr['姓名'][$key] . "','" . $pw . "'
                                            ,'" . $excelArr['OA用户名'][$key] . "','" . $jobInfo[$excelArr['职位'][$key]] . "','" . $jobInfo[$excelArr['职位'][$key]] . "',
                                            '" . $deptInfo[$tempdeptname] . "','" . $sexInfo[$excelArr['性别'][$key]] . "','" . $excelArr['邮箱地址'][$key] . "',
                                            '" . $areaInfo[$excelArr['区域'][$key]] . "','" . $brInfo[$excelArr['公司'][$key]] . "',now(),'" . $_SESSION['USER_ID'] . "'
                                        )";
                                    $this->db->query_exc($sql);
                                    //人事表
                                    $sql = "insert into hrms
                                        (USER_ID,CARD_NO,COME_DATE,JOIN_DATE,EDUCATION
                                        ,CERTIFICATE,School,Major,Address
                                        ,Account,AccCard,Bank,Tele,Native
                                        ,Email,Creator,CreateDT,Remark
                                        ,contflagb,contflage,usercard
                                        )
                                    values
    ('" . $excelArr['员工号'][$key] . "','" . $excelArr['身份证'][$key] . "','" . $excelArr['入职日期'][$key] . "','" . $excelArr['转正日期'][$key] . "'
    ,'" . $eduInfo[$excelArr['学历'][$key]] . "','" . $excelArr['职位'][$key] . "','" . $excelArr['毕业学校'][$key] . "','" . $excelArr['专业'][$key] . "'
    ,'" . $excelArr['家庭地址'][$key] . "','" . $excelArr['银行帐号'][$key] . "','" . $excelArr['卡号'][$key] . "','" . $excelArr['开户行'][$key] . "'
    ,'" . $excelArr['手机号码'][$key] . "','" . $excelArr['户籍地'][$key] . "','" . $excelArr['邮箱地址'][$key] . "','" . $_SESSION['USER_ID'] . "'
    ,now(),'" . $excelArr['备注'][$key] . "','" . $excelArr['合同期始'][$key] . "','" . $excelArr['合同期止'][$key] . "','" . $excelArr['员工号'][$key] . "'
        )";
                                    $this->db->query_exc($sql);
                                    $sql = "insert into ecard
                                    (
                                        User_id,Name,Sex,Depart,Http,Email,Company,CreateDT
                                        ,tel_no_dept,mobile1
                                    )values(
                                        '" . $excelArr['员工号'][$key] . "',
                                        '" . $excelArr['姓名'][$key] . "',
                                        '" . $sexInfo[$excelArr['性别'][$key]] . "',
                                        '" . $deptInfo[$tempdeptname] . "',
                                        'http://www.dinglicom.com',
                                        '" . $excelArr['邮箱地址'][$key] . "',
                                        '',
                                        '" . date('Y-m-d H:i:s') . "',
                                        '" . $excelArr['公司电话'][$key] . "',
                                        '" . $excelArr['手机号码'][$key] . "'
                                        )";
                                    $this->db->query_exc($sql);


                                    //=========创建IM用户==============

                                    $data = array(
                                        'COM_BRN_CN' => '世纪鼎利',
                                        'DEPT_NAME' => $excelArr['部门'][$key],
                                        'USER_ID' => $excelArr['OA用户名'][$key],
                                        'USER_NAME' => $excelArr['姓名'][$key],
                                        'PASSWORD' => $pw,
                                        'SEX' => $sexInfo[$excelArr['性别'][$key]],
                                        'EMAIL' => $excelArr['邮箱地址'][$key],
                                        'JOBS_NAME' => $excelArr['职位'][$key],
                                        'Mobile' => '',
                                        'Phone' => $excelArr['公司'][$key]
                                    );
                                    $msg = $this->im->add_user($data);
                                    if ($msg) {
                                        $creat_im = '创建IM帐号成功';
                                    } else {
                                        $creat_im = '创建IM帐号失败';
                                        throw new ErrorException('shibai-im');
                                    }

                                    //=========创建邮箱==============
                                    $AddEmail = '';
                                    $AddEmail_URL = Email_Server_Api_Url . '?action=AddUser&key=' . urlencode(authcode(oa_auth_key . ' ' . time(), 'ENCODE'));
                                    $AddEmail_URL .= '&userid=' . $excelArr['OA用户名'][$key] . '&username=' . $excelArr['姓名'][$key] . '&password=dinglicom';
                                    $AddEmail_URL .= '&domain=dinglicom.com&deptname=' . $excelArr['部门'][$key];
                                    $AddEmail = file_get_contents(trim($AddEmail_URL));
                                    if (trim($AddEmail) == 1) {

                                    } else {
                                        //throw new ErrorException('shibai-yx');
                                        $emailerr .= $AddEmail . "\n";
                                    }

                                    $this->db->query("COMMIT");
                                } catch (Exception $e) {
                                    $this->db->query("ROLLBACK");
                                    $filestr .= $excelArr['员工号'][$key] . '--' . $e->getMessage() . "\n";
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

                            foreach ($excelArr['员工号'] as $key => $val) {

                                foreach ($excelFields as $fval) {
                                    if ($fval == '处理') {
                                        $doType = $excelArr[$fval][$key];
                                        if ($doType == '1') {
                                            $excelArr['邮件'][$key] = '1';
                                        } elseif ($doType == '密码') {
                                            $excelArr['邮件'][$key] = '2';
                                        } elseif ($doType == '已开邮箱') {
                                            $excelArr['邮件'][$key] = '3';
                                        }
                                    }
                                }

                                $toe = $excelArr['贝讯邮箱地址'][$key];
                                $tobody = '';
                                if ($excelArr['邮件'][$key] == '1') {
                                    $tobody = '<DIV><FONT size=2 face=Verdana>' . $excelArr['姓名'][$key] . '，您好：</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>您的世纪鼎利OA，大蚂蚁和邮箱已经开通好，具体登录信息如下：</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>1.世纪鼎利OA系统登录账号：' . $excelArr['OA账号'][$key] . ' ；密码：跟之前使用总部的OA系统的密码一致；</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>2.IM系统（BigAnt）登录账号：' . $excelArr['OA账号'][$key] . ' ；密码：以本人提供至公司人力资源部身份证的后八位；</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>3.世纪鼎利邮箱地址：' . $excelArr['鼎利邮箱'][$key] . ' ；密码：以本人提供至公司人力资源部身份证的后八位。</FONT></DIV>
<DIV style="TEXT-INDENT: 4em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>以上请知悉。</FONT></DIV>';
                                } elseif ($excelArr['邮件'][$key] == '2') {
                                    $tobody = '<DIV><FONT size=2 face=Verdana>' . $excelArr['姓名'][$key] . '，您好：</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>您的世纪鼎利OA，大蚂蚁和邮箱已经开通好，具体登录信息如下：</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>1.世纪鼎利OA系统登录账号：' . $excelArr['OA账号'][$key] . ' ；密码：以本人提供至公司人力资源部身份证的后八位；</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>2.IM系统（BigAnt）登录账号：' . $excelArr['OA账号'][$key] . ' ；密码：以本人提供至公司人力资源部身份证的后八位；</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>3.世纪鼎利邮箱地址：' . $excelArr['鼎利邮箱'][$key] . ' ；密码：以本人提供至公司人力资源部身份证的后八位。</FONT></DIV>
<DIV style="TEXT-INDENT: 4em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>以上请知悉。</FONT></DIV>';
                                } elseif ($excelArr['邮件'][$key] == '3') {
                                    $tobody = '<DIV><FONT size=2 face=Verdana>' . $excelArr['姓名'][$key] . '，您好：</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>您的世纪鼎利OA和大蚂蚁已经开通好，具体登录信息如下：</FONT></DIV>
<DIV style="TEXT-INDENT: 2em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>1.世纪鼎利OA系统登录账号：' . $excelArr['OA账号'][$key] . ' ；密码：跟之前使用总部的OA系统的密码一致；</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>2.IM系统（BigAnt）登录账号：' . $excelArr['OA账号'][$key] . ' ；密码：以本人提供至公司人力资源部身份证的后八位；</FONT></DIV>
<DIV style="TEXT-INDENT: 4em"><FONT size=2 face=Verdana>3.世纪鼎利邮箱地址：' . $excelArr['鼎利邮箱'][$key] . ' ；鼎利邮箱的账号先前已经开通，邮箱密码不做变动。</FONT></DIV>
<DIV style="TEXT-INDENT: 4em">&nbsp;</DIV>
<DIV style="TEXT-INDENT: 2em"><FONT size=2 face=Verdana>以上请知悉。</FONT></DIV>';
                                }
                                $this->emailClass->send('世纪鼎利的OA系统\邮箱\IM系统（BigAnt）账号信息', $tobody, $toe);
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

                            foreach ($excelArr['员工编号'] as $key => $val) {

                                $excelArr['邮箱'][$key] = $userInfo[$excelArr['员工编号'][$key]];

                                $tobody = '';

                                if ($excelArr['类型'][$key] == 'A') {
                                    include_once 'A.php';
                                    $tobody = $a;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }

                                } elseif ($excelArr['类型'][$key] == 'B') {
                                    include_once 'B.php';
                                    $tobody = $b;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }
                                } elseif ($excelArr['类型'][$key] == 'C') {
                                    include_once 'C.php';
                                    $tobody = $c;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }
                                } elseif ($excelArr['类型'][$key] == 'D') {
                                    include_once 'D.php';
                                    $tobody = $d;
                                    foreach ($excelFields as $fval) {
                                        $tobody = str_replace('x' . $fval, $excelArr[$fval][$key], $tobody);
                                    }
                                }
                                $this->emailClass->send('薪酬结构调整通知', $tobody, $excelArr['邮箱'][$key]);
                            }//foreach 
                        }//empty
                    } elseif ($flag == 'levechange') {
                        if (!in_array('员工号', $excelFields) || !in_array('姓名', $excelFields)
                            || !in_array('申请级别', $excelFields) || !in_array('认证申请初审后级别', $excelFields)
                        ) {
                            throw new Exception('Update failed');
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            foreach ($excelArr['员工号'] as $key => $val) {
                                $infoE[$val]['name'] = $excelArr['姓名'][$key];
                                $infoE[$val]['lev'] = $excelArr['申请级别'][$key];
                                $infoE[$val]['sal'] = $excelArr['认证申请初审后级别'][$key];
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
                                    $tmpstr = '<DIV><FONT size=2 face=Verdana>&nbsp;<FONT size=2 face=Verdana>您好！</FONT>
                                        <br/><br/>
                                        &nbsp;<FONT size=2 face=Verdana>恭喜您顺利通过网优通道的任职资格认证申请，敬请知悉以下“认证申请初审后级别”。</FONT>
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
        <DIV align=center>员工号</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>姓名</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>申请级别</DIV></FONT></TD>
      <TD
      style="BORDER-BOTTOM: #000000 1px solid; BORDER-LEFT: #000000 1px solid; BORDER-TOP: #000000 1px solid; BORDER-RIGHT: #000000 1px solid"
      width="25%" noWrap><FONT size=2 face=Verdana>
        <DIV align=center>认证申请初审后级别</DIV></FONT></TD></TR>
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
face=Verdana>人力资源部</FONT></FONT></FONT></DIV></DIV>';
                                    $this->emailClass->send('网优工程通道任职资格认证申请审核结果', $tmpstr, $val['email'], '世纪鼎利', '世纪鼎利');
                                }
                            }
                        }
                    } elseif ($flag == 'userupdate') {
                        foreach ($excelArr['工号'] as $key => $val) {
                            /*$sql="update hrms h left join user u on (h.user_id = u.user_id )
                                set h.usercard='".$val."'
                                where u.user_name='".$excelArr['姓名'][$key]."' ";
                            $this->_db->query($sql);
                             * 
                             */
                        }
                    } elseif ($flag == 'yeb') {
                        if (!in_array('员工号', $excelFields) || !in_array('名字', $excelFields)
                            || !in_array('年终奖', $excelFields)
                            || !in_array('扣税', $excelFields) || !in_array('实发金额', $excelFields)
                        ) {
                            throw new Exception('Update failed');
                        }
                        if (count($excelArr) && !empty($excelArr)) {
                            foreach ($excelArr['员工号'] as $key => $val) {
                                $infoE[$val]['name'] = $excelArr['名字'][$key];
                                $infoE[$val]['yam'] = $excelArr['年终奖'][$key];
                                $infoE[$val]['kam'] = $excelArr['扣税'][$key];
                                $infoE[$val]['pam'] = $excelArr['实发金额'][$key];
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
                                    $str = '您好！您的年终奖已通过审核，具体信息如下，敬请知悉：<br/>
                                            <table border="1" cellspacing="1" cellpadding="1">
                                                <tr><td>员工：</td><td>' . $val['name'] . '</td></tr>
                                                <tr><td>年终奖：</td><td>' . $val['yam'] . '</td></tr>
                                                <tr><td>扣税金额：</td><td>' . $val['kam'] . '</td></tr>
                                                <tr><td>实发金额：</td><td>' . $val['pam'] . '</td></tr>
                                            </table><br/>
                                            年终奖将在年前发放！';
                                    $this->emailClass->send('年终奖信息', $str, $val['email']);
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
                $str = '<tr><td colspan="10">请上传数据！</td></tr>';
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
                            $strstr .= '└';
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
                $str = '<tr><td colspan="10">上传失败！</td></tr>';
            } else {
                //读取excel信息
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
                        $str .= '<tr><td>序号</td><td>新ID</td>';
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
                        foreach ($excelArr['新合同号'] as $key => $val) {
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
                        $str .= '<tr><td>序号</td><td>邮箱</td>';
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
                        foreach ($excelArr['员工号'] as $key => $val) {
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
                        $str .= '<tr><td>序号</td>';
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
                        foreach ($excelArr['员工号'] as $key => $val) {
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
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            //$str.='<td>'.$i.'</td>';
                            foreach ($excelFields as $fval) {
                                if ($fval == '处理') {
                                    $doType = $excelArr[$fval][$key];
                                    if ($doType == '1') {
                                        $excelArr['邮件'][$key] = '1';
                                    } elseif ($doType == '密码') {
                                        $excelArr['邮件'][$key] = '2';
                                    } elseif ($doType == '已开邮箱') {
                                        $excelArr['邮件'][$key] = '3';
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
                        foreach ($excelArr['员工编号'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            //$str.='<td>'.$i.'</td>';
                            $excelArr['邮箱'][$key] = $userInfo[$excelArr['员工编号'][$key]];

                            foreach ($excelFields as $fval) {

                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }

                //子公司合并
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
                            $userLeft[$row['user_id']] = ($row['has_left'] == '1' ? '离职' : '在职');
                            $userLeft[$row['logName']] = ($row['has_left'] == '1' ? '离职' : '在职');
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
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            //$str.='<td>'.$i.'</td>';
                            foreach ($excelFields as $fval) {
//                 				if($fval=='姓名'){
//                 					$uid = $this->getUserPinyin( trim($excelArr[$fval][$key])  );
//                 					$uemail = $uid.'@dinglicom.com';
//                 					$uidc = '';
//                 					$uemailc = '' ;
//                 					$uidlen = strlen($uid);
//                 					foreach ( $userInfo as $uval ){
//                 						//$pos = strpos($uval, $uid);
//                 						if( substr($uval, 0 , $uidlen ) == $uid ){
//                 							$uidc= '重复';
//                 							$uemailc .= $uval.'('.$userLeft[$uval].')'.'；' ;
//                 						}
//                 					}
//                 					$excelArr['OA账号'][$key] = ( $uidc ==''? $uid:$uidc )  ;
//                 					$excelArr['鼎利邮箱地址'][$key] = ( $uemailc ==''? $uemail:$uemailc )   ;
//                 				}
                                if ($fval == 'OA账号') {
                                    $uid = trim($excelArr[$fval][$key]);
                                    $uemail = $uid . '@dinglicom.com';
                                    $uidc = '';
                                    $uemailc = '';
                                    foreach ($userInfo as $uval) {
                                        if ($uval == $uid) {
                                            $uidc = '重复';
                                            $uemailc .= $uval . '(' . $userLeft[$uval] . ')' . '；';
                                        }
                                    }
                                    //本
                                    foreach ($excelArr['OA账号'] as $bkey => $bval) {
                                        if ($bval == $uid && $bkey != $key) {
                                            $uidc = '重复';
                                            $uemailc .= $uval . '(2)' . '；';
                                        }
                                    }
                                    $excelArr['OA账号'][$key] = ($uidc == '' ? $uid : $uidc);
                                    $excelArr['鼎利邮箱地址'][$key] = ($uemailc == '' ? $uemail : $uemailc);
                                    $excelArr['职位'][$key] = $jobInfo[$excelArr['所属部门'][$key]][$excelArr['职位'][$key]];
                                }
                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }
                //子公司合并
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
                        $str .= '<tr><td>序号</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        $newjob = array();
                        foreach ($excelArr['所属部门'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $col = '';
                                if (empty($deptInfo[$excelArr[$fval][$key]]) && $fval == '部门') {
                                    $col = ' color="red" ';
                                }
                                if (!empty($userInfo[$excelArr[$fval][$key]]) && $fval == 'OA用户名') {
                                    $col = ' color="red" ';
                                    $ckr .= $excelArr[$fval][$key] . '<br>';
                                }
                                if (empty($eduInfo[$excelArr[$fval][$key]]) && $fval == '学历') {
                                    $col = ' color="red" ';
                                }
                                if (empty($brInfo[$excelArr[$fval][$key]]) && $fval == '公司') {
                                    $col = ' color="red" ';
                                }
                                $str .= '<td ><font ' . $col . '>' . $excelArr[$fval][$key] . '<font></td>';


                            }
                            $newjob[$excelArr['所属部门'][$key]][$excelArr['职务'][$key]] = $jobInfo[$excelArr['所属部门'][$key]][$excelArr['职务'][$key]];
                            $str .= '</tr>';
                        }
                        $ckr;
                        $str = '<tr><td>部门</td><td>职位</td><td>ID</td></tr>';
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
                //子公司合并
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
                        $str .= '<tr><td>序号</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $col = '';
                                if (empty($deptInfo[$excelArr[$fval][$key]]) && $fval == '部门') {
                                    $col = ' color="red" ';
                                }
                                if (!empty($userInfo[$excelArr[$fval][$key]]) && $fval == 'OA用户名') {
                                    $col = ' color="red" ';
                                    $ckr .= $excelArr[$fval][$key] . '<br>';
                                }
                                if (empty($eduInfo[$excelArr[$fval][$key]]) && $fval == '学历') {
                                    $col = ' color="red" ';
                                }
                                if (empty($brInfo[$excelArr[$fval][$key]]) && $fval == '公司') {
                                    $col = ' color="red" ';
                                }
                                if ($fval == '所属部门') {
                                    $excelArr['职位'][$key] = $jobInfo[$excelArr['所属部门'][$key]][$excelArr['职位'][$key]];
                                    $excelArr[$fval][$key] = $deptInfo[$excelArr[$fval][$key]];

                                }
                                $str .= '<td >' . $excelArr[$fval][$key] . '</td>';
                            }
                            $str .= '</tr>';
                        }
                        echo $ckr;

                    }
                }
                //子公司合并
                if ($flag == 'zgs_ini') {
                    if (count($excelArr) && !empty($excelArr)) {
                        $deptInfo = array();
                        $areaInfo = array('' => '1');
                        $jobInfo = array();
                        $sexInfo = array('男' => '0', '女' => '1');
                        $eduInfo = array("专科" => '1', "初中" => '2', "高中" => '3', "中专" => '4', "大专" => '5'
                        , "本科" => '6', "硕士" => '7', "研究生" => '8', "博士" => '9', "未接受正规教育" => '10');
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
                        $str .= '<tr><td>序号</td>';
                        foreach ($excelFields as $val) {
                            $str .= '<td>' . $val . '</td>';
                        }
                        $str .= '</tr>';
                        $i = 0;
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $i++;
                            $str .= '<tr>';
                            $str .= '<td>' . $i . '</td>';
                            foreach ($excelFields as $fval) {
                                $col = '';
                                if (empty($deptInfo[$excelArr[$fval][$key]]) && $fval == '部门') {
                                    $col = ' color="red" ';
                                }
                                if (!empty($userInfo[$excelArr[$fval][$key]]) && $fval == 'OA用户名') {
                                    $col = ' color="red" ';
                                    $ckr .= $excelArr[$fval][$key] . '<br>';
                                }
                                if (empty($eduInfo[$excelArr[$fval][$key]]) && $fval == '学历') {
                                    $col = ' color="red" ';
                                }
                                if (empty($brInfo[$excelArr[$fval][$key]]) && $fval == '公司') {
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
                        foreach ($excelArr['员工号'] as $key => $val) {
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
                        $str .= '<td>验证</td>';
                        $str .= '</tr>';
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $str .= '<tr>';
                            $remark = '';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                                if ($fval == '项目编号' && !in_array(trim($excelArr[$fval][$key]), $ck)) {
                                    $remark .= '项目不存在';
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
                        $str .= '<td>区域负责人</td></tr>';
                        foreach ($excelArr['业务编号'] as $key => $val) {
                            $str .= '<tr>';
                            foreach ($excelFields as $fval) {
                                $str .= '<td>' . $excelArr[$fval][$key] . '</td>';
                            }

                            $str .= '<td>' . $ck[$excelArr['新增区域列'][$key]] . '</td></tr>';
                        }
                    }
                }
                if ($flag == 'hrupdate') {
                    if (!in_array('员工号', $excelFields) || !in_array('姓名', $excelFields)
                        || !in_array('归属办事处', $excelFields) || !in_array('职位', $excelFields)
                    ) {
                        throw new Exception('Update failed');
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $infoE[$val]['name'] = $excelArr['姓名'][$key];
                            $infoE[$val]['blg'] = $excelArr['归属办事处'][$key];
                            $infoE[$val]['pos'] = $excelArr['职位'][$key];
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
                                    <td>序号</td>
                                    <td>员工号</td>
                                    <td>姓名</td>
                                    <td>归属办事处</td>
                                    <td>职位</td>
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
                    if (!in_array('员工号', $excelFields) || !in_array('姓名', $excelFields)
                        || !in_array('申请级别', $excelFields) || !in_array('认证申请初审后级别', $excelFields)
                    ) {
                        throw new Exception('Update failed');
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $infoE[$val]['name'] = $excelArr['姓名'][$key];
                            $infoE[$val]['lev'] = $excelArr['申请级别'][$key];
                            $infoE[$val]['sal'] = $excelArr['认证申请初审后级别'][$key];
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
                                    <td>序号</td>
                                    <td>员工号</td>
                                    <td>姓名</td>
                                    <td>申请级别</td>
                                    <td>认证申请初审后级别</td>
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
                                    <td style="color:red;">请检查员工是否网优部或已离职！</td>
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
                            $infoA[$row['user_id']]['leave'] = '<font color="red">离职</font>';
                        } else {
                            $infoA[$row['user_id']]['leave'] = '在职';
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
                            $sk = array_search($val['name'], $excelArr['姓名']);
                            if ($sk !== FALSE && $excelArr['工号'][$sk]) {
                                $ckB[] = $excelArr['工号'][$sk];
                                /*
                                if(array_key_exists($excelArr['工号'][$sk], $infoB)){
                                    $infoB[$excelArr['工号'][$sk]]['name']=$infoB[$excelArr['工号'][$sk]]['name'].$val['name'];
                                }else{
                                    $infoB[$excelArr['工号'][$sk]]['name']=$val['name'];
                                }
                                */
                                $infoB[$excelArr['工号'][$sk]]['name'] = $val['name'];
                                $infoB[$excelArr['工号'][$sk]]['leave'] = $val['leave'];
                                $infoB[$excelArr['工号'][$sk]]['sex'] = $excelArr['性别'][$sk];
                                $infoB[$excelArr['工号'][$sk]]['dept'] = $excelArr['部门'][$sk];
                            } elseif ($val['leave'] == '在职') {
                                $infoC[$key] = $val;
                            } else {
                                $infoD[$key] = $val;
                            }
                        }
                        foreach ($excelArr['姓名'] as $key => $val) {
                            if (array_key_exists($excelArr['工号'][$key], $infoF)) {
                                $infoF[$excelArr['工号'][$key]] += 1;
                            } else {
                                $infoF[$excelArr['工号'][$key]] = 1;
                            }
                            if (array_search($val, $ckA) === false) {
                                $infoE[$key] = $val;
                            }
                            if (array_search($excelArr['工号'][$key], $ckB) === false) {
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
                    if (!in_array('员工号', $excelFields) || !in_array('名字', $excelFields)
                        || !in_array('年终奖', $excelFields)
                        || !in_array('扣税', $excelFields) || !in_array('实发金额', $excelFields)
                    ) {
                        throw new Exception('Update failed');
                    }
                    if (count($excelArr) && !empty($excelArr)) {
                        foreach ($excelArr['员工号'] as $key => $val) {
                            $infoE[$val]['name'] = $excelArr['名字'][$key];
                            $infoE[$val]['yam'] = $excelArr['年终奖'][$key];
                            $infoE[$val]['kam'] = $excelArr['扣税'][$key];
                            $infoE[$val]['pam'] = $excelArr['实发金额'][$key];
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
                                    <td>序号</td>
                                    <td>员工号</td>
                                    <td>名字</td>
                                    <td>年终奖</td>
                                    <td>扣税</td>
                                    <td>实发金额</td>
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
                                    <td style="color:red;">请检查员工号是否正确！</td>
                                </tr>';
                            }
                            $i++;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $str = '<tr><td colspan="10">导入数据错误，请检查格式！' . $e->getMessage() . '</td></tr>';
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
     * 获取名称拼音
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

    //*********************************析构函数************************************
    function __destruct()
    {
    }

}

?>