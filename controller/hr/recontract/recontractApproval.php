<?php


/**
 * @author Administrator
 * @Date 2012-05-30 19:26:14
 * @version 1.0
 * @description:��ͬ��Ϣ���Ʋ�
 */
class controller_hr_recontract_recontractApproval extends controller_base_action {
	function __construct() {
		$this->objName = "recontractApproval";
		$this->objPath = "hr_recontract";
		parent :: __construct();
	}
	/*
	 * ��ת����ͬ��Ϣ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	* ��ȡ��ҳ����ת��Json
	*/
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = false;
		$rows = $service->page_d();
		foreach((array)$rows as $key => $val){
			if($val['mark']=='HR'){
				$rows[$key]['stepName']='������';
			}elseif($val['mark']=='SP'&&$val['Item']){
				$rows[$key]['stepName']=$val['Item'];
			}elseif($val['mark']=='ST'){
				$rows[$key]['stepName']='Ա��ȷ��';
			}else{
				//$rows[$key]['stepName']='';
			}
			if($rows[$key]['isFlag']=='2'){
				$rows[$key]['conStateName']='';
				$rows[$key]['conNumName']='';
				$rows[$key]['beginDate']='';
				$rows[$key]['closeDate']='';
			}
			
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		if ($object['recontractId']) {
			$id = $this->service->add_d($object);
			//�ж��Ƿ�ֱ���ύ����
			if ($id && $_GET['act'] == "app") {
				set_time_limit (0);
				$flowMoney=$this->service->getFlowType($object['recontractId']);
				$upstr="update hr_recontract set ExaStatus='���',statusId = '6',ExaDT = now() where id='".$object['recontractId']."'";
				$sel=$this->service->selectFlow($object['recontractId'],'hr_recontract','',"��ͬ��ǩ",$upstr,$object['deptId'],$flowMoney['UserLevel'],$flowMoney['companyId']);
	      	    $sels=$this->service->ewfBuild($sel);
	      	    if($sels&&$object['statusId']){
	      	    	 $this->service->sendMails($object['recontractId'],3);
	                 showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
	      	    }else{
	      	    	showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
	      	    }
			} else {
				showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
			}
		} else {
			showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
		}

	}
/**	
 * �����������
	 */
	function c_edit() {
		$object = $_POST[$this->objName];
		if ($object['recontractId']&&$object['id']) {
			$flag = $this->service->edit_d($object);
			//�ж��Ƿ�ֱ���ύ����
			if ($flag && $_GET['act'] == "app") {
				set_time_limit (0);
				$flowMoney=$this->service->getFlowType($object['recontractId']);
				$upstr="update hr_recontract set ExaStatus='���',statusId = '6',ExaDT = now() where id='".$object['recontractId']."'";
				$sel=$this->service->selectFlow($object['recontractId'],'hr_recontract','',"��ͬ��ǩ",$upstr,$object['deptId'],$flowMoney['UserLevel'],$flowMoney['companyId']);
	      	    $sels=$this->service->ewfBuild($sel);
	      	    if($sels&&$object['statusId']){
	      	    	 $this->service->sendMails($object['recontractId'],3);
	                 showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
	      	    }else{
	      	    	showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
	      	    }
			} else {
				showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
			}
		} else {
			showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
		}

	}
	/**
	 * �ҵı������-��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonAppList() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		
		if($_POST['ExaStatus']==1){
			$rows = $service->pageBySqlId('select_appListPost');// PASS
			$service->sort = 'b.start';
		}elseif($_POST['ExaStatus']==2){
			$rows = $service->pageBySqlId('select_appList');//NO PASS
			$service->sort = 'b.start';
		}else{
			$rows = $service->pageBySqlId('select_appListAll');
			$service->sort = 'c.start';
			//$rows = $service->pageBySqlId('select_appListPost');//NO PASS
		}
		foreach((array)$rows as $key => $val){
			if($val['Flag']=='1'){
				$rows[$key]['Flag']='������';
			}else{
				$rows[$key]['Flag']='δ����';
			}
			
			
		}  
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * �ҵı������-��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonStaffList() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����ϢuserAccount
		//$service->sort = 'b.start';
		$rows = $service->pageBySqlId('select_StaffList');
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * ��ת�������ٲ���Ϣҳ��
	 */	
	function c_viewArbitra() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('addArbitra');
	}
	/**
		 * �����������
		 */
	function c_addApploval() {
		$object = $_POST[$this->objName];
		if ($object['recontractId']) {
			$id = $this->service->addApploval_d($object);
			if ($id) {
				showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
			} else {
				showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
			}
		} else {
			showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
		}

	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toApproval() {
	      if ($_GET['id']) {
	      	$objs=$this->service->getApprovealInfo($_GET['id']);
	      	$flowMoney=$this->service->getFlowType($_GET['id']);
			$upstr="update hr_recontract set ExaStatus='���',statusId = '6',ExaDT = now() where id='".$_GET['id']."'";
			$sel=$this->service->selectFlow($_GET['id'],'hr_recontract','',"��ͬ��ǩ",$upstr,$objs[0]['deptId'],$flowMoney['UserLevel'],$flowMoney['companyId']);
	      	//$sel=$this->service->selectFlow($_GET['id'],'hr_recontract','',"��ͬ��ǩ","update hr_recontract set ExaStatus='���',statusId = '4',ExaDT = now() where id='".$_GET['id']."'",$objs[0]['deptId']);
	      	$sels=$this->service->ewfBuild($sel);
	      	    if($sels){
	                 showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
	      	    }else{
	      	    	showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
	      	    }
		 }
	}


	/**
	 * ��ת�������ٲ���Ϣҳ��
	 */
	function c_toInformStaff() {
		$this->permCheck(); //��ȫУ��
		$pid=(int)$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if($pid){
			$objs=$this->service->getApprovealInfo($pid);
	        if($objs[0]&&is_array($objs[0]) ){
	           $this->service->updateObjStatus($pid,5);
	           $flag= $this->service->updateObj($pid,'comResults',$objs[0]['id']);
	           //�����ʼ�
		       $this->service->sendMails($pid,5);
	        }
		}
        echo  $flag;
	}
/**
	 * ��ת�������ٲ���Ϣҳ��
	 */
	function c_staffList() {
		$this->permCheck(); //��ȫУ��
		
		$this->view('staffList');
	}

	/**
	 * ��ת�������ٲ���Ϣҳ��
	 */
	function c_InformStaff() {
		$this->permCheck(); //��ȫУ��
		$pid=$_GET['id'] ? $_GET['id'] : $_POST['id'];
		$isFlag=$_GET['isFlag'] ? $_GET['isFlag'] : $_POST['isFlag'];
		if($pid&&$isFlag){
		$objs=$this->service->getApprovealInfo($pid);
	        if($objs[0]&&is_array($objs[0]) ){
	        	$object=$objs[0];
	        	unset($object['id']);
	        	unset($object['createTime']);
	        	unset($object['fspId']);
	        	unset($object['conContent']);
	        	$object['isFlag']=$isFlag;
	        	$object['mark']='ST';
	        	$object['createId']=$_SESSION['USER_ID'];
	        	$object['createName']=$_SESSION['USERNAME'];
	        	$object['updateId']=$_SESSION['USER_ID'];
	        	$object['updateName']=$_SESSION['USERNAME'];
	        	$object['createTime']=date('y-m-d H:i:s');
	        	$object['updateTime']=date('y-m-d H:i:s');
	            $id = $this->service->add_d($object);
	        	$this->service->updateObjStatus($pid,5);
	            $flag= $this->service->updateObj($pid,'staffResults',$id);
	            //�����ʼ�
	            $this->service->sendMails($pid,5);
	        }
		}
        echo  $flag;
	}
/**
	 * ��ת�������ٲ���Ϣҳ��
	 */
	function c_InEnd() {
		$this->permCheck(); //��ȫУ��
		$pid=$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if($pid){
			//$objv=$this->service->getRecontractInfo($pid);
			
		    $objs=$this->service->getApprovealInfo($pid);
	        if($objs[0]&&is_array($objs[0]) ){
	        	$object=$objs[0];
	        	unset($object['id']);
	        	unset($object['createTime']);
	        	unset($object['fspId']);
	        	unset($object['conContent']);
	        	$object['mark']='HR';
	        	$object['createId']=$_SESSION['USER_ID'];
	        	$object['createName']=$_SESSION['USERNAME'];
	        	$object['updateId']=$_SESSION['USER_ID'];
	        	$object['updateName']=$_SESSION['USERNAME'];
	        	$object['createTime']=date('y-m-d H:i:s');
	        	$object['updateTime']=date('y-m-d H:i:s');
	        	if($object['isFlag']==2){
	        	   $object['statusId']=8;
	            }else{
	        	   $object['statusId']=7;
	            }
	        	$id = $this->service->add_d($object);
	        	$this->service->updateObjStatus($pid,$object['statusId']);
	            $flag= $this->service->updateObj($pid,'endResults',$id);
	            
	        }
		}
        echo  $flag;
	}
	
/**
	 * ��ת�������ٲ���Ϣҳ��
	 */
	function c_InPaperContract() {
		$this->permCheck(); //��ȫУ��
		$pid=$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if($pid){
		$this->service->updateObj($pid,'isPaperContract',2);	
		$flag=$this->service->updateObjStatus($pid,8);
	        
		}
        echo  $flag;
	}
	
/**
	 * ��ת�������ٲ���Ϣҳ��
	 */
	function c_InClose() {
		$this->permCheck(); //��ȫУ��
		$pid=$_GET['id'] ? $_GET['id'] : $_POST['id'];
		if($pid){
		$flag=$this->service->updateObjStatus($pid,9);
	        
		}
        echo  $flag;
	}
	
        /**
		 * �����������
		 */
	function c_addInformStaff() {
		$object = $_POST[$this->objName];
		if ($object['recontractId']) {
			$id = $this->service->addInformStaff_d($object);
			if ($id) {
				showmsg('�����ɹ�', 'self.parent.location.reload();', 'button');
			} else {
				showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
			}
		} else {
			showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
		}

	}

        /**
		 * �����������
		 */
	function c_options() {
		$ids=$_GET['ids'] ? $_GET['ids'] : $_POST['ids'];
		$status=$_GET['idstatus'] ? $_GET['status'] : $_POST['status'];
		$idI=explode(',',$ids);
		if ($status&&$idI&&is_array($idI)) {
			foreach($idI as $val){
				if($val){
					$objs=$this->service->getApprovealInfo($val);
					if($status=='2'){
						$flowMoney=$this->service->getFlowType($val);
						$upstr="update hr_recontract set ExaStatus='���',statusId = '6',ExaDT = now() where id='".$val."'";
						$sel=$this->service->selectFlow($val,'hr_recontract','',"��ͬ��ǩ",$upstr,$objs[0]['deptId'],$flowMoney['UserLevel'],$flowMoney['companyId']);
	      				//$sel=$this->service->selectFlow($val,'hr_recontract','',"��ͬ��ǩ","update hr_recontract set ExaStatus='���',statusId = '4',ExaDT = now() where id='".$val."'",$objs[0]['deptId']);
	      	 			$sels=$this->service->ewfBuild($sel);
					}elseif($status=='5'){
					        if($objs[0]&&is_array($objs[0]) ){
					           $this->service->updateObjStatus($val,5);
					           $flag= $this->service->updateObj($val,'comResults',$objs[0]['id']);
					           //�����ʼ�
						       $this->service->sendMails($val,5);
					        }
					}elseif($status=='7'){
					      $this->service->updateObj($val,'isPaperContract',2);	
						$flag=$this->service->updateObjStatus($val,7);

					}elseif($status=='8'){
					        if($objs[0]&&is_array($objs[0]) ){
					        	$object=$objs[0];
					        	unset($object['id']);
					        	unset($object['createTime']);
					        	unset($object['fspId']);
					        	unset($object['conContent']);
					        	$object['mark']='HR';
					        	$object['createId']=$_SESSION['USER_ID'];
					        	$object['createName']=$_SESSION['USERNAME'];
					        	$object['updateId']=$_SESSION['USER_ID'];
					        	$object['updateName']=$_SESSION['USERNAME'];
					        	$object['createTime']=date('y-m-d H:i:s');
					        	$object['updateTime']=date('y-m-d H:i:s');
					        	if($object['isFlag']==2){
					        	   $object['statusId']=8;
					            }else{
					        	   $object['statusId']=7;
					            }
					            $id = $this->service->add_d($object);
					        	$this->service->updateObjStatus($val,$object['statusId']);
					            $flag= $this->service->updateObj($val,'endResults',$id);
					        }
						
					}elseif($status=='9'){
					      $this->service->updateObjStatus($val,9);

					}
				}
				
				
			}
			
			
			
		} else {
			showmsg('����ʧ��', 'self.parent.location.reload();', 'button');
		}

	}
      /**
		 * ������������
		 */
	function c_batchApproval() {
		$ids=$_GET['ids'] ? $_GET['ids'] : $_POST['ids'];
		if($ids){
			$idI=json_decode(str_replace('\\','','['.trim($ids,",").']'));
		}
		$status=$_GET['idstatus'] ? $_GET['status'] : $_POST['status'];
		if ($status&&$idI&&is_array($idI)) {
			$si=0;
			foreach($idI as $key=>$val){
				if(is_object($val)){
					$vaI=get_object_vars($val);
				}else{
					$vaI=$val;
				}
				if($vaI){
					$object=$this->service->getApprovealInfo($vaI['pid']);
					$object=$object[0];
					$objs['userName']=$object['userName'];
					$objs['userAccount']=$object['userAccount'];
					$objs['recontractId']=$object['recontractId'];
					$objs['userNo']=$object['userNo'];
					$objs['deptName']=$object['deptName'];
					$objs['deptId']=$object['deptId'];
					$objs['jobName']=$object['jobName'];
					$objs['jobId']=$object['jobId'];
					$objs['isFlag']=$status=='3'?$object['isFlag']:$status;
					$objs['conState']=$object['conState'];
					$objs['conNum']=$object['conNum'];
					$objs['beginDate']=$object['beginDate'];
					$objs['closeDate']=$object['closeDate'];
					$objs['isSend']=1;
					$objs['isSendNext']=1;
					$objs['recorderName']=$_SESSION['USERNAME'];
					$objs['recorderId']=$_SESSION['USER_ID'];
					$objs['recordDate']=date('y-m-d H:i:s');
					$objs['mark']='SP';
					$objs['fspId']=$vaI['id'];
			        $id = $this->service->addApploval_d($objs);
			        if($id){
			        	$si++;
			        }else{
			        	$str.=$objs['userName'].',';
			        }
				}
			}
			if(!$str){
				$str=0;
			}
			echo json_encode(array('sc'=>$si,'fc'=>iconv("GB2312","UTF-8//IGNORE",trim($str,',')) ));
		} else {
			echo 1;
		}

	}


   function c_approvalInfoApp(){
   	   $pid=$_GET['id'] ? $_GET['id'] : $_POST['id'];
   	   if($pid){
   	   	  $objs=$this->service->getApprovalInfoApp($pid);
   	   	   foreach((array)$objs as $val){
   	   	   	
   	   	   	$str.='<tr >
						<td >��'.$val['SmallID'].'��</td>
						<td >'.$val['Item'].'</td>
						<td >'.($val['createName']?$val['createName']:$val['User']).'</td>
						<td >'.($val['isFlagName']?$val['isFlagName']:'δ����').'</td>
						<td >'.$val['conContent'].'</td>
						<td >'.$val['createTime'].'</td>
				    </tr>';
   	   	   }
   	   	  
   	   	  
   	   }
   	    $this->assign('list', $str);
   	  $this->view('approvalInfoApp');
   }
}
?>