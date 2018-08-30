<?php

class controller_common_mobile extends controller_base_action {
	public $chickSessionId;
	public $newOaUrl;
	
	function __construct() {
		$this->objName = "mobile";
		$this->objPath = "common";
		$this->chickSessionId=$_SESSION['USER_ID']?true:false;
		$this->newOAUrl="http://172.16.1.101/r/w?";
		parent :: __construct();
	}
	
	/**
	 * ��ȡ�������б�Я�������ͣ���Ա��ʱ�䣩 
	 */
	function c_auditingJson(){
		$responseCode=4;
		if($_SESSION['USER_ID']){
			$service = $this->service;
			$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$obj=$_REQUEST;
			if($obj["version"]>19&&$obj["sid"]){
				$postData=array (
								"cmd"  => "com.actionsoft.apps.sys.Audit_auditingJson",
								"sid"  =>  $obj["sid"]
							) ;	
				$AWSI =$this->curlInit($postData);
			}
			$service->searchArr['inNames'] = $service->rtWorkflowStr_d();
			$service->searchArr['findInName'] = $_SESSION['USER_ID'];
			//$service->searchArr['startTime'] =$service->getTime($obj['pageTime']);
			$this->service->sort = 'c.start';
			$service->asc = false;
			$rows = $service->list_d('select_auditing');
			if($rows){
				//��������������
				$rows = $service->rowsDeal_d($rows);
				//���������������
				//$rows = $service->auditInfo_d($rows);
			}else{
				$rows =array();
			}
			$arr = array ();
			if(is_array($rows)&&$rows){
				foreach($rows as $key =>$val){
					$rows[$key]['flowTime']=$service->sendTtime($val['flowTime']);
					//$rows[$key]['formName']=iconv('GB2312', 'UTF-8', $val['formName']);;
				}			
			}
			$responseCode=1;
		}else{
			$responseCode=2;
		}
		$rowI=$this->array_remove_empty((array_merge((array)$rows,(array)$AWSI["items"]))); 
		$arr['totalSize'] =$rowI?count($rowI):0;
		$arr['responseCode'] = $responseCode;
		$arr['items'] = $rowI?$rowI:array();
		file_put_contents('temp/auditingJson/'.$_SESSION['USER_ID'].'/'.time().'.txt',util_jsonUtil :: encode($arr));
		echo util_jsonUtil :: encode($arr);

	}
	
	
	/**
	 * ���� : ��ȡ�������ܼ�¼�� 
	 */
	function c_getCountAditingJson(){
		
		$responseCode=4;
		if($_SESSION['USER_ID']){
			$service = $this->service;
			$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$obj=$_REQUEST;
			//$service->searchArr['inNames'] = $service->rtWorkflowStr_d();
			$service->searchArr['findInName'] = $_SESSION['USER_ID'];
			//$service->searchArr['startTime'] =$service->getTime($obj['pageTime']);
			$this->service->sort = 'c.start';
			$service->asc = false;
			$rows = $service->list_d('select_auditing');
			if($rows){
				//��������������
				$rows = $service->rowsDeal_d($rows);
				//���������������
				//$rows = $service->auditInfo_d($rows);
			}else{
				$rows =array();
			}
			$arr = array ();
			if(is_array($rows)&&$rows){
				foreach($rows as $key =>$val){
					$rows[$key]['flowTime']=$service->sendTtime($val['flowTime']);
				}			
			}
			$responseCode=1;
		}else{
			$responseCode=2;
		}
		$arr['totalSize'] =$rows?count($rows):0;
		$arr['responseCode'] = $responseCode;
		echo util_jsonUtil :: encode($arr);

	}
	/**
	 * ����������ϸ����ͨ��ҳ��鿴�ģ�����һ��URL��
	 */
	function c_toObjInfo(){
		$emg='';
		$responseCode='';
		$url='index_mb.php';	
		$service = $this->service;
		$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$obj = $_REQUEST;
		file_put_contents('temp/toObjInfo/'.time().'q.txt',util_jsonUtil :: encode($obj));
		if($_SESSION['USER_ID']){
			unset ($obj['model']);
			unset ($obj['action']);
			if($obj['mbType']=="2"){
				$url ="r/w?sid=".$obj['sid']."&cmd=CLIENT_BPM_FORM_MAIN_PAGE_OPEN&processInstId=".$obj['billId']."&taskInstId=".$obj['taskId']."&openState=2";
				$responseCode= '1';
				$emg='';
			}else{
				$tempdb = $this->service->getWfInfo_d($obj['spId']);
				if (!empty ($tempdb['DBTable'])) {
					$dburlstr = '&gdbtable=' . $tempdb['DBTable'];
				} else {
					$dburlstr = '';
				}
				//�ж��Ƿ��б����ƣ�û����ֱ�ӽ��и���
				if(empty($obj['formName'])){
					$obj['formName'] = $tempdb['formName'];
				}
				$obj['formName']=iconv('UTF-8', 'GB2312', $obj['formName']);
				if (!empty ($this->service->urlArr[$obj['formName']]['url'])) {
					$objId = $obj['billId'];
					if (isset ($this->service->urlArr[$obj['formName']]['isChange']) && $_GET['audited']) { //�ж��Ƿ�Ϊ�������
						$objId = $this->service->getObjIdByTempId_d($objId, $this->service->urlArr[$obj['formName']]['changeCode']);
						$url.= $this->service->urlArr[$obj['formName']]['auditedViewUrl'] . $objId;
					} else {
						if(substr(trim($this->service->urlArr[$obj['formName']]['viewUrl']),0,1) =='?'){
							$url.=$this->service->urlArr[$obj['formName']]['viewUrl'] . $objId;
						}else{
							$url=$this->service->urlArr[$obj['formName']]['viewUrl'] . $objId;	
						}
					}
					if ($this->service->urlArr[$obj['formName']]['isSkey']) {
						$url .= '&skey=' . $this->md5Row($objId, $this->service->urlArr[$obj['formName']]['keyObj']).'&sessionId='.session_id();
					}else{
						$url.='&sessionId='.session_id();
					}
					$url .= $dburlstr;
					$responseCode= '1';
					$emg='';
				} else {
					$url='';
					$responseCode = '3';
					$emg='';
				}
			
			}
			
		}else{
			$url='';
			$responseCode = '2';
			$emg='';
		}
		$res['url']=$url;
		$res['mbType']=$obj['mbType'];
		$res['responseCode'] = $responseCode;
		$res['errorMsg']=$emg;
		file_put_contents('temp/toObjInfo/'.$_SESSION['USER_ID'].'/'.time().'.txt',util_jsonUtil :: encode($res));
		echo ( util_jsonUtil :: encode( $res ) );
	}
	
	/**
	 * ������¼
	 */
	function c_getApp(){
		$emg='';
		$responseCode='';
		$url='';
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$obj = $_POST;
		if($this->chickSessionId){
			unset ($obj['model']);
			unset ($obj['action']);
			$tempdb = $this->service->getWfInfo_d($obj['spid']);
			if (!empty ($tempdb['DBTable'])) {
				$dburlstr = '&gdbtable=' . $tempdb['DBTable'];
			} else {
				$dburlstr = '&gdbtable=';
			}
			//�ж��Ƿ��б����ƣ�û����ֱ�ӽ��и���
			if(empty($obj['formName'])){
				$obj['formName'] = $tempdb['formName'];
			}
	        $obj['formName']=iconv('UTF-8', 'GB2312', $obj['formName']);
			if (!empty ($this->service->urlArr[$obj['formName']]['viewUrl'])) {
				$objId = $obj['billId'];
				if (isset ($this->service->urlArr[$obj['formName']]['isChange']) && $_GET['audited']) { //�ж��Ƿ�Ϊ�������
					$objId = $this->service->getObjIdByTempId_d($objId, $this->service->urlArr[$obj['formName']]['changeCode']);
					$url = $this->service->urlArr[$obj['formName']]['auditedViewUrl'] . $objId;
				} else {
					$url = $this->service->urlArr[$obj['formName']]['viewUrl'] . $objId;
				}
	
				if ($this->service->urlArr[$obj['formName']]['isSkey']) {
					$url .= '&skey=' . $this->md5Row($objId, $this->service->urlArr[$obj['formName']]['keyObj']).'&sessionId='.session_id();
				}else{
					$url.='&sessionId='.session_id();
				}
				$responseCode=1;
				$url .= $dburlstr;
			} else {
				$responseCode=3;
				$emg= 'δ������ɵ�������������ϵ����Ա��ɶ�Ӧ����';
			}
		}else{
			$responseCode=2;
		}
		$res['responseCode'] =$responseCode;
		$res['errorMsg'] =iconv('GB2312', 'UTF-8',$emg);
		$res['url'] = $url;
		echo (util_jsonUtil :: encode( $res ));
	}
	
	/**
	 * ������¼
	 */
	function c_getApproval(){
		$emg='';
		$responseCode='';
		$url='';
		$service = $this->service;
		$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$obj = $_REQUEST;
		if($obj['mbType']=="2"){
			if($obj["version"]>19&&$obj["sid"]){
				$url ="r/w?sid=".$obj['sid']."&cmd=CLIENT_BPM_FORM_TRACK_OPEN&supportCanvas=true&processInstId=".$obj['billId']."&taskInstId=".$obj['taskId']."&openState=2";
				$responseCode= '1';
				$emg='';
			}
		
		}else{
			if($this->chickSessionId){
					if($obj['taskId']&&$obj['billId']&&$obj['code']){
						$url='index_mb.php?model=common_mobile&action=toView&itemtype='.$obj['code'].'&pid='.$obj['billId'].'&taskId='.$obj['taskId'].'&sessionId='.session_id();
						$responseCode=1;
					} else {
						$responseCode=3;
						$emg= 'δ������ɵ�������������ϵ����Ա��ɶ�Ӧ����';
					}
				}else{
					$responseCode=2;
				}
		}

		$res['responseCode'] =$responseCode;
		$res['errorMsg'] =iconv('GB2312', 'UTF-8',$emg);
		$res['mbType']=$obj['mbType'];
		$res['url'] = $url;
		file_put_contents('temp/getApproval/'.$_SESSION['USER_ID'].'/'.time().'.txt',util_jsonUtil :: encode($res));
		echo (util_jsonUtil :: encode( $res ));
	}
	/**
	 * ��ȡ�������б�
	 */
	function c_auditedJson(){
		
		$arr = array ();
		$rows = array ();
		$service = $this->service;
		$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$obj=$_REQUEST;
		if($this->chickSessionId){
			if($obj["version"]>19&&$obj["sid"]){
				$postData=array (
								"cmd"  => "com.actionsoft.apps.sys.Audit_auditedJson",
								"sid"  =>  $obj["sid"],
								"operateType"  =>  $obj["operateType"],
								"pageTime"  =>$service->getTime($obj['pageTime'])
							) ;	
				$AWSI =$this->curlInit($postData);
			}
			
			$service->searchArr['findInName'] = $_SESSION['USER_ID'];
			if($obj['operateType']==1||$obj['operateType']==3){
				$service->searchArr['endTime'] =$service->getTime($obj['pageTime']);
				$this->service->sort = 'p.endTime';
				$service->asc = true;
				$rows = $service->pageBySqlId('select_audited');	
			}elseif($obj['operateType']==2){
				$service->searchArr['endTimes'] =$service->getTime($obj['pageTime']);
				$this->service->sort = 'p.endTime';
				$service->asc = true;
				$rows = $service->pageBySqlId('select_audited');	
			}
			
			
			
			if($rows){
				//��������������
				$rows = $service->rowsDeal_d($rows);
				//���������������
				$rows = $service->auditInfo_d($rows);
			}
			$responseCode=1;
		}else{
			$responseCode=2;
		}
		
		if(is_array($rows)&&$rows){
			foreach($rows as $key =>$val){
				$rows[$key]['flowTime']=$service->sendTtime($val['flowTime']);
				$rows[$key]['endTime']=$service->sendTtime($val['endTime']);
				//$rows[$key]['taskId']= $val['endTime']."adf-adadf4555-afasdfdsf-455adasd";
				
			}
		}
		if(is_array($AWSI["items"])&&$AWSI["items"]){
			foreach($rows as $key =>$val){
				$AWSI["items"][$key]['flowTime']=$service->sendTtime($val['flowTime']);
				$AWSI["items"][$key]['endTime']=$service->sendTtime($val['endTime']);
				//$rows[$key]['taskId']= $val['endTime']."adf-adadf4555-afasdfdsf-455adasd";
				
			}
		}
		$rowI=$this->array_remove_empty((array_merge((array)$rows,(array)$AWSI["items"]))); 
		//$rowI=$AWSI["items"];//$this->array_remove_empty((array_merge((array)$rows,(array)$AWSI["items"]))); 
		$arr['totalSize'] =$rowI?count($rowI):0;
		$arr['responseCode'] =$responseCode;
		$arr['errorMsg'] = '';
		$arr['items'] = $rowI?$rowI:array();
		
		file_put_contents('temp/getApproval/'.$_SESSION['USER_ID'].'_'.time().'.txt',util_jsonUtil :: encode($arr));
		echo util_jsonUtil :: encode($arr);
		
	}
	/**
	 * ע��
	 */
	function c_logout(){
		$service = $this->service;
		$emg='';
		$responseCode='';
		if($this->chickSessionId){
			$service->_db->query( "update  login_log  set ON_LINE='0' where USER_ID='".$_SESSION['USER_ID']."' and ON_LINE='1'" );
		    setcookie("_ck_user",'',-1);
		    setcookie("_ck_password",'',-1);
		    setcookie("_ck_autologin",'',-1);
		    @session_unset( );
			$responseCode=1;
		}else{
			$responseCode=2;
		}
		$res['msgCode']=$responseCode;
		$res['responseCode'] = $responseCode;
		echo ( util_jsonUtil :: encode( $res ) );
	}
	/**
	 * ��ȡԱ���˺�
	 */
	function c_getUser(){
		$service = $this->service;
		$userName=trim($_REQUEST['userName']);
		$userName=iconv('UTF-8', 'GB2312', $userName);
		$emg='';
		$dataArr=array();
		$responseCode='';
		if($userName){
			$sql="SELECT a.USER_NAME AS userName,a.USER_ID AS userId ,b.DEPT_NAME AS deptName 
					FROM `user` a LEFT JOIN department b ON a.DEPT_ID=b.DEPT_ID
					WHERE a.DEL=0 AND a.HAS_LEFT=0 AND a.USER_NAME LIKE '%$userName%'";
			$dataArr = $service->_db->getArray($sql);
			$responseCode = '1';

		}else{
			$responseCode = '3';
		}
		$res['responseCode'] = $responseCode;
		$res['errorMsg'] = $emg;
		$res['datas'] = $dataArr?$dataArr:array();		
		echo (util_jsonUtil :: encode( $res ) );
	}
	 /**
	 * ��ȡ���ͽӿ�
	 */
	function c_getFlowType(){
		$emg='';
		$dataArr=array();
		$responseCode='';
		$orgArr = $this->service->rtWorkflowArr_d();
		if($orgArr&&is_array($orgArr)){
			foreach ($orgArr as $key => $val) {
				$dataArr[$key]['typeName'] = $val;
			}	
		}
		$res['responseCode'] = '1';
		$res['datas'] =$dataArr?$dataArr:array();
		echo util_jsonUtil :: encode($res);
	}
	
	function c_toView(){
		$flowProp="";
		$itemName="";
		$pid = isset($_GET['pid'])?addslashes($_GET['pid']):$_POST['pid'];
		$thisTaskId = isset($_GET['taskId'])?addslashes($_GET['taskId']):$_POST['taskId'];
		$itemtype = isset($_GET['itemtype'])?addslashes($_GET['itemtype']):$_POST['itemtype'];
		$mark = "";
		$Html='';
		$i = 0;//�����κ�
		if($pid&&$itemtype){
			$arr=$this->service->getWfInfo($pid,$itemtype);
			if($arr&&is_array($arr)){
				foreach($arr as $key => $val){
					$taskId=$val['task'];
				if(empty($mark) || $mark != $taskId){
					$i++;
		        	$x = 0;
					if(!empty($mark)){
						$Html.='<tr><td colspan="6"></td> </tr>';
					}
					$mark = $taskId;
					$title='';
					if( !empty($thisTaskId)&& $taskId == $thisTaskId){ 
						 $title="(��������)";
					}
					$Html.='<tr class="form_header"><td colspan="6" style="text-align:left">��'.$i.'������'.$title.'</td></tr>
			                <tr style="color:blue;">
			                	<td width="5%">���</td>
			                    <td width="15%">������</td>
			                    <td width="10%">������</td>
			                    <td width="20%">��������</td>
			                    <td width="9%">�������</td>
			                    <td width="27%">�������</td>
			                </tr>';
			                
					}             
			         $arrSetp=$this->service->getWfsetp($val['task'],$val['SmallID']);
			         if($arrSetp&&is_array($arrSetp)){
			         	foreach($arrSetp as $key => $va){
			         		$x++;
			         	   $Html.='<tr class="extr TableLine2" >
						            <td>'.$x.'</td>
						            <td rowspan="1" '.($va['Flag']==0?'style=\'color:red;\'':'').'>'.$val['Item'].'</td>
						            <td>'.$this->service->getUserNamelist($va['User']).'</td>
						            <td style=\'color:green;\'>'.$va['Endtime'].'</td>
						            <td>'.($va['Result']=='ok'?"<font color=\'green\'>ͬ��</font>":($va['Result']=='no'?"<font color=\'red\'>��ͬ��</font>":"δ����")).'</td>
						            <td>'.$va['Content'].'</td>
						        </tr>';
			         		
			         	}
			         	
			         }else{
			         	$x++;
			         	 $Html.='<tr class="extr TableLine2" >
						            <td>'.$x.'</td>
						            <td rowspan="1" '.($val['Flag']==0?'style=\'color:red;\'':'').'>'.$val['Item'].'</td>
						            <td>'.$this->service->getUserNamelist($val['User']).'</td>
						            <td style=\'color:green;\'>'.$val['Endtime'].'</td>
						            <td>'.($val['Result']=='ok'?"<font color=\'green\'>ͬ��</font>":($val['Result']=='no'?"<font color=\'red\'>��ͬ��</font>":"δ����")).'</td>
						            <td>'.$val['Content'].'</td>
						        </tr>';
			         	
			         }
			                
			                
				
				
			}
				
		}
		
		}
		$Html=$Html?$Html:'<tr><td colspan="6">��</td> </tr>';
		$this->show->assign('HTML', $Html);
		$this->show->display('common_mobile_mbApp');

}

/**
	 * ע��
	 */
	function c_feedback(){
		$service = $this->service;
		$service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$obj=$_REQUEST;
		$emg='';
		$responseCode='';
		if($this->chickSessionId){
			if($obj['msgType']&&$obj['msg']){
				$service->_db->query( "INSERT INTO `feedback` (`msgType`, `userId`, `msg`) VALUES ('".$obj['msgType']."','".$_SESSION['USER_ID']."', '".$obj['msg']."')");
				$msgCode=1;
			}else{
				$msgCode=2;
			}
			$responseCode=1;
		}else{
			$msgCode=2;
			$responseCode=2;
		}
		$res['msgCode']=$msgCode;
		$res['responseCode'] = $responseCode;
		echo ( util_jsonUtil :: encode( $res ) );
	}
	
	
	function curlInit($postData){
		$postFields = empty($postData) ? "" : http_build_query($postData);
		$ch = curl_init($this->newOAUrl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$result = curl_exec($ch);
		curl_close($ch);
		$AWSI = util_jsonUtil::decode ( $result, true );
				
		return $AWSI; 	
	}

	function array_remove_empty($Data){
		$arrI=array();
		if($Data&&is_array($Data)){
			foreach ($Data as $key => $arr) {
				if($arr&&is_array($arr)){
					$arrI[]=$arr;
				}
				
			}
			
		}
				
		return $arrI; 	
	}
	
	
	
	
	
	
	
	
	
}


?>