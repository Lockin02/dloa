<?php
class model_general_system_user_adduser extends model_base {
	public $db;
	function __construct() {
		$this->db = new mysql();
	}
	/**
	 * 添加用户
	 * @param unknown_type $userinfo
	 */
	function adduser($userinfo) {
		if ($userinfo && is_array($userinfo)) {
			try {
				set_time_limit(0);
				$this->db->query("START TRANSACTION");
				$oId= trim($userinfo ['oId']);
	             if($oId){
					$o= new model_hr_recruitment_entryNotice();
					$o->updateBeEntryNumById_d($oId);
	             	$this->db->query("UPDATE  oa_hr_recruitment_entrynotice SET accountState=1 , userAccount ='".$userinfo ['USER_ID']."' WHERE id='$oId'");
	             }
				if (strtolower($userinfo['USER_ID']) != 'admin') {
					$UpUserSqlStr='';
					//=========创建用户====================
					foreach ($userinfo as $key => $val) {
						if ($key != 'byemail' && $key != 'IM' && $key != 'PASSWORDINIT'&&$key != 'oId') {
							$find .= $key . ',';
							$values .= "'$val',";
						}
					}
					$find = rtrim($find, ',');
					$values = rtrim($values, ',');
					$user_sql = "INSERT INTO user(user_priv,$find,InitPassword)
								 VALUES('" . $userinfo['jobs_id'] . "',$values,'" . crypt_util($userinfo['PASSWORDINIT'], 'encode') . "')";

					$userid = $this->db->query($user_sql);
					$FlagI['creat_user']=$userid;
					if ($userid) {
						$Flag['creat_user'] = '创建用户资料成功';
					} else {
						$Flag['creat_user'] = '创建用户资料失败';
					}
					//=========读取用户详细信息================
					$user = new includes_class_global();
					$info = $user->GetUserinfo($userinfo['USER_ID'], array (
						'logname',
						'user_name',
						'email',
						'areaname',
						'dept_name',
						'edept_name',
						'jobs_name'
					));
					//===============部门==================
					$obj = new model_system_dept();
					$p_dept = $obj->GetParent_ID($userinfo['DEPT_ID']);
					if ($p_dept) {
						$p_dept = array_reverse($p_dept);
						$dept_name = implode('/', array_keys($p_dept)) . '/' . $info['dept_name'];
					} else {
						$dept_name = $info['dept_name'];
					}
					//鼎利公司
					if (($userinfo['EmailFlag'] == 1 || $userinfo['IMFlag'] == 1) && $_SESSION['COM_BRN_PT'] == 'dl') {
						//=========添加用户资料================
						$wwwdomain = "http://www." . webdomain;
						$ecard_sql = "INSERT INTO ecard(User_id,Name,Sex,Depart,Http,Email,Company,CreateDT)
									  VALUES(
				    					'" . $userinfo['LogName'] . "',
				    					'" . $userinfo["USER_NAME"] . "',
				    					'" . $userinfo["SEX"] . "',
				    					'" . $userinfo["DEPT_ID"] . "',
				    					'$wwwdomain',
				    					'" . ($userinfo['EmailFlag'] == 1 ? $userinfo['EMAIL'] : '') . "',
				    					'" . ($_SESSION["COM_BRN_PT"] == 'dl' ? $userinfo["Company"] : $_SESSION['COM_BRN_CN']) . "',
				    					'" . date('Y-m-d H:i:s') . "')";
						$ecardid = $this->db->query($ecard_sql);
						$FlagI['creat_ecard']=$ecardid;
						if ($ecardid) {
							$Flag['creat_ecard'] = '创建通讯录资料成功';
						} else {
							$Flag['creat_ecard'] = '创建通讯录资料失败';
						}

						//=========创建IM帐户==============
						if ($userinfo['IMFlag'] == 1) {
							$im = new includes_class_ImInterface();
							$data = array (
								'COM_BRN_CN' => ($_SESSION['COM_BRN_CN'] ? $_SESSION['COM_BRN_CN'] : '世纪鼎利'),
								'DEPT_NAME' => $dept_name,
								'USER_ID' => $userinfo['LogName'],
								'USER_NAME' => $info['user_name'],
								'PASSWORD' => $userinfo['PASSWORD'],
								'SEX' => $userinfo["SEX"] == 1 ? 2 : 1,
								'EMAIL' => $userinfo['EMAIL'],
								'JOBS_NAME' => $info['jobs_name']
							);
							$creat_imsI=$im->add_user($data);
							$FlagI['creat_im']=$creat_imsI;
							if ($creat_imsI) {
								$creat_im = '创建IM帐号成功';
							} else {
								$UpUserSqlStr .= " IMFlag=0,";
								$creat_im = '创建IM帐号失败';
							}
						}
						/*
						//===============创建一卡通帐户==================
　　　　　　　　　　　　　if($userinfo['CIFlag'] == 1){
							$CI = new includes_class_CardInterface();
　　　　　　　　　　　　　	$Cdata = array (
								'DEPT_NAME' =>$info['dept_name'],
								'USER_NAME' =>$info['user_name'],
								'AREA_ID' => $userinfo['AREA'],
								'SEX' => $userinfo["SEX"]==1?'女':'男',
								'EMP_ID' => $ecardid
							);
						if ($CI->add_user($Cdata)) {
								$creat_CI = '创建CI帐号成功';
							} else {
								$creat_CI = '创建CI帐号失败';
							}
　　　　　　　　　　　　　}
						*/
						//===============创建邮箱帐户==================

						if ($userinfo['EmailFlag'] == 1) {
							//=========创建邮件用户============
							$AddEmail_URL = Email_Server_Api_Url . '?action=AddUser&key=' . urlencode(authcode(oa_auth_key . ' ' . time(), 'ENCODE'));
							$AddEmail_URL .= '&userid=' . $userinfo['USER_ID'] . '&username=' . $userinfo['USER_NAME'] . '&password='.$userinfo['PASSWORDINIT'];
							$AddEmail_URL .= '&domain=dinglicom.com&deptname=' . $info['dept_name'];
							$AddEmail = file_get_contents(trim($AddEmail_URL));
							$FlagI['creat_Email']=trim($AddEmail);
							if (trim($AddEmail) == 1) {
								$emailstatus = '创建邮件地址成功';
								$userEmailI['userId']=$userinfo['USER_ID'];
								$userEmailI['deptId']=$userinfo['DEPT_ID'];
								$userEmailI['areaId']=$userinfo['AREA'];
								$groupnameI=$this->model_mailgroup_update($userEmailI);
								if($groupnameI&&is_array($groupnameI)){
									$Flag['updata_GM']=implode('、',(array)$groupnameI);
								}
							} else {
								$UpUserSqlStr .= "EmailFlag=0,";
								$emailstatus = '创建邮件地址失败';
							}
						}

					}

					//=========子公司人事档案录入==============
					if (!empty ($_SESSION['COM_BRN_PT']) && $_SESSION['COM_BRN_PT'] != 'dl') {
						$sql = "INSERT INTO hrms(user_id, usercard)
		                        VALUES('" . $userinfo['LogName'] . "','" . $userinfo['LogName'] . "')";
						$hrmsid = $this->db->query($sql);
						$FlagI['creat_hrms']=$hrmsid;
						if ($hrmsid) {
							$Flag['creat_hrms'] = '创建人事资料成功';
						} else {
							$Flag['creat_hrms'] = '创建人事资料失败';
						}

						//=========子公司创建IM用户==============
						$im = new includes_class_ImInterface();
						$data = array (
							'COM_BRN_CN' => ($_SESSION['COM_BRN_CN'] ? $_SESSION['COM_BRN_CN'] : '世纪鼎利'),
							'DEPT_NAME' => $dept_name,
							'USER_ID' => $userinfo['USER_ID'],
							'USER_NAME' => $info['user_name'],
							'PASSWORD' => $userinfo['PASSWORD'],
							'SEX' => $userinfo["SEX"] == 1 ? 2 : 1,
							'EMAIL' => $userinfo['EMAIL'],
							'JOBS_NAME' => $info['jobs_name']
						);
						$creat_imId=$im->add_user($data);
                        $FlagI['creat_im']=$creat_imId;
						if ($creat_imId) {
							$creat_im = '创建IM帐号成功';
						} else {
							$creat_im = '创建IM帐号失败';
						}
					}
					if($UpUserSqlStr){
					  $usersql="update user set $UpUserSqlStr CreatDT=now() where USER_ID='".$userinfo['USER_ID']."';";
					  $this->db->query($usersql);
				    }
					//===========发送邮件==================
					if ((!empty ($_SESSION['COM_BRN_PT']) && $_SESSION['COM_BRN_PT'] != 'dl') || (($userinfo['EmailFlag'] == 1 || $userinfo['IMFlag'] == 1) && $_SESSION['COM_BRN_PT'] == 'dl')) {
						$ebody .= "您好!<br />&nbsp;&nbsp;&nbsp;&nbsp;建立创建帐号状态如下：<br /><br />";
						$ebody .= $Flag['creat_user'] ? '&nbsp;&nbsp;&nbsp;&nbsp;<b>用户资料</b>:' . $Flag['creat_user'] . '<br />' : '';
						$ebody .= $Flag['creat_hrms'] ? '&nbsp;&nbsp;&nbsp;&nbsp;<b>人事资料</b>:' . $Flag['creat_hrms'] . '<br />' : '';
						$ebody .= $Flag['creat_ecard'] ? '&nbsp;&nbsp;&nbsp;&nbsp;<b>通讯录</b>:' . $Flag['creat_ecard'] . '<br />' : '';
						$ebody .= $creat_im ? '&nbsp;&nbsp;&nbsp;&nbsp;<b>即时通讯</b>:' . $creat_im . '<br />' : '';
						$ebody .= $emailstatus ? '&nbsp;&nbsp;&nbsp;&nbsp;<b>邮件</b>:' . $emailstatus . '<br />' : '';
						$ebody .= $Flag['updata_GM'] ? '&nbsp;&nbsp;&nbsp;&nbsp;<b>邮件群组</b>:' . $Flag['updata_GM'] . '创建失败<br />' : '';
						$ebody .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>区域</b>:" . $info['areaname'] . "<br />";
						$ebody .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>部门</b>:" . $info['dept_name'] . "<br />";
						$ebody .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>姓名</b>:" . $info['user_name'] . "<br />";
						$ebody .= $emailstatus ? "&nbsp;&nbsp;&nbsp;&nbsp;<b>邮箱:</b>" . $info['email'] : $emailstatus;
						$Subject = "OA-新员工（" . $userinfo['USER_ID'] . "）入职通知";
						$email =ITManager;// $user->GetUserinfo(ITManager, 'email');
						$mail = new includes_class_sendmail();
						$mail->send($Subject, $ebody, $email);
						includes_class_global :: insertOperateLog('用户信息', $userinfo['LogName'], "新员工入职（" . $userinfo['LogName'] . "） 插入用户表 通信录 ，$creat_im ，$emailstatus", "成功");
						unset ($user);
					}
					$this->db->query("COMMIT");
					return $FlagI;
				} else {
					return false;
				}
			} catch (Exception $e) {
					$this->db->query("ROLLBACK");
				throw $e;
				throw new Exception($e->getMessage());
				return false;
			}
		} else {
			return false;
		}

	}
function model_mailgroup_update($dataI){
	if(!empty ($_SESSION['COM_BRN_PT']) && $_SESSION['COM_BRN_PT']=='dl'){
		$Opmail = new model_system_organizer_mailgroup_index();
		if($dataI&&is_array($dataI)){
				$sql = "SELECT  a.*
					   FROM sys_organizer_mailgroup as a
					   WHERE ((find_in_set('" . $dataI['userId'] . "',memberlist) and type=1) or
							  (find_in_set('" . $dataI['deptId'] . "',dept_id) and type=2 )  or
							  (find_in_set('" . $dataI['areaId'] . "',ascription) and type=3) or
							  (find_in_set('" . $dataI['areaId'] . "',ascription) and find_in_set('" . $dataI['deptId'] . "',dept_id) and type=4)
							 )
					    ORDER BY  type asc";
				$egroup_query = $this->db->query($sql);
				while (($row = $this->db->fetch_array($egroup_query)) != false) {
					$Egdata[] = $row;
				}
				if ($Egdata && is_array($Egdata)) {
					foreach ($Egdata as $key => $val) {
						if ($val['type'] == 1) {
							$data['UserId'] = $val['memberlist'];
							$data['unUserIds'] = $val['unsenderlist'];
						} else if ($val['type'] == 2) {
								$data['dept_id'] = $val['dept_id'];
								$data['deptUserId'] = $val['extra'];
								$data['dept_flag'] = 'P';
								$data['unDeptUserIds'] = $val['unmemberlist'];
						} else if ($val['type'] == 3) {
									$data['area_id'] = $val['ascription'];
									$data['areaUserId'] = $val['extra'];
									$data['unAreaUserIds'] = $val['unmemberlist'];
						}else if ($val['type'] == 4) {
									$data['dept_id'] = $val['dept_id'];
									$data['area_id'] = $val['ascription'];
									$data['mixUserId'] = $val['extra'];
									$data['unMixUserIds'] = $val['unmemberlist'];
						}
						$data['id']=$val['id'];
						$data['rand_key']=$val['rand_key'];
						$data['type'] = $val['type'];
						$data['send_type'] = $val['send_type'];
						$data['SendId'] = $val['senderlist'];
						$data['description'] = $val['description'];
						$data['unSendNames'] = $val['unsenderlist'];
						$data['noUserIdlist'] = $val['noUserIdlist'];
						$flag='';
						$flag = $Opmail->model_edit($data['id'], $data['rand_key'], $data);
						if ($flag) {
							$groupName[]=$val['groupname'];
						}
					}
				}
		}
		return $groupName;
	  }
	}
}
?>