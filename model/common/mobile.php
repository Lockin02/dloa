<?php
class model_common_mobile extends model_base
{

	//��������
	public $urlArr;

	//������������
	public $batchAuditArr;

	//�������
	public $changeFunArr;

	//�յ�������Ӧ����
	public $receiveActionArr;
	//�˵�������Ӧ����
	public $backActionArr;
	
	
	 function __construct() {
		include_once (WEB_TOR."model/common/workflow/workflowRegister.php");
		include_once (WEB_TOR."model/common/workflow/workflowExaInfoConfig.php");

		//��������Ӧ���鼰�������Ϣ
		$this->urlArr = isset($urlArr) ? $urlArr : null;
		//������������
		$this->batchAuditArr = isset($batchAuditArr) ? $batchAuditArr : null;

		//������ʷ��������
		$this->ExaInfoUserArr = isset($ExaInfoUserArr) ? $ExaInfoUserArr : null;

		//��������Ӧ�������
		$this->changeFunArr = isset($changeFunArr) ? $changeFunArr : null;

		//�յ�������Ӧ����
		$this->receiveActionArr = isset($receiveActionArr) ? $receiveActionArr : null;
		//�˵�������Ӧ����
		$this->backActionArr = isset($backActionArr) ? $backActionArr : null;

		$this->tbl_name = "wf_task";
		$this->sql_map = "common/mobileSql.php";

		parent :: __construct();
	}
	
	function sendTtime($date){
		return strtotime($date).'000';
	}
	
	function getTime($date){
		return substr($date,0,10);
	}
	
/**
	 * ����ע�Ṥ������
	 */
	function rtWorkflowStr_d(){
		return implode(array_keys($this->urlArr),',');
	}

	/**
	 * ����ע�Ṥ��������
	 */
	function getBatchAudit_d(){
		return array_keys($this->batchAuditArr);
	}

	/**
	 * ���ؿ���������������
	 */
	function rtWorkflowArr_d(){
		return array_keys($this->urlArr);
	}

	/**
	 * ����������ʷ�鿴����������
	 */
	function rtWorkflowExInfoArr_d(){
		return array_keys($this->ExaInfoUserArr);
	}

	/**
	 * �ж��������Ƿ����������Ϣ
	 */
	function getWfInfo($pid,$code){
		$auditArr=array();
		if($pid&&$code){
			$sql="select  f.Item ,f.User , f.SmallID , f.Flag , f.Endtime , f.Flow_prop ,w.task from flow_step f , wf_task w 
			   where  w.code='$code' and w.Pid='$pid' and f.Wf_task_ID=w.task order by f.Wf_task_ID asc,f.SmallID asc";
			$auditArr = $this->_db->getArray($sql);	
		}
		return $auditArr;
	}
/**
	 * �ж��������Ƿ����������Ϣ
	 */
	function getWfsetp($taskId,$smallId){
		$auditArr=array();
		if($smallId){
			$sql="select p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag  from flow_step_partent p where p.Wf_task_ID='$taskId' and p.SmallID='".$smallId."'";
			$auditArr = $this->_db->getArray($sql);	
		}
		return $auditArr;
	}

function getUserNamelist($idlist){
    $idlist=trim($idlist);
    $restr="";
    if(trim($idlist,",")!=""){
        $sqlstr="";
        $idarr=explode(",",$idlist);
        foreach($idarr as $val){
            if(trim($val)!=""){
                $sqlstr.="'".$val."',";
            }
        }
        $sqlstr=substr($sqlstr,0,strlen($sqlstr)-1);
        if(strlen($sqlstr)){
            $sql="select USER_NAME from user where USER_ID in ($sqlstr)";
        }else{
            return $restr;
        }
        $appI=$this->_db->getArray($sql);
        if($appI&&is_array($appI)){
        	foreach($appI as $key =>$val){
        		$restr.=implode(',',$val).',';		
        	}
        }
        
        return trim($restr,',');
    }
}



	/**
	 * ��������������
	 */
	function rowsDeal_d($object){
		if(!empty($object) && !empty($this->changeFunArr)){
			//���ڱ���ı�����
			$changeKeyArray = array_keys($this->changeFunArr);

			foreach($object as $key => $val){
				//�жϵ�ǰ���Ƿ��ڱ��������
				if(in_array($val['name'],$changeKeyArray)){

					//��֤�Ƿ��Ǻ�ͬ���
					$objClassStr = $this->changeFunArr[$val['name']]['className'];
					$objFunStr = $this->changeFunArr[$val['name']]['funName'];
					//���黺��ʵ��������
					$obj[$val['name']] = isset($obj[$val['name']]) ? $obj[$val['name']] : new $objClassStr();
					if($obj[$val['name']]->$objFunStr($val['Pid'])){
						$object[$key]['name'] = $this->changeFunArr[$val['name']]['taskName'];
						$object[$key]['isTemp'] = 1;
					}else if($obj[$val['name']]->$objFunStr($val['Pid'])){
						$object[$key]['isTemp'] = 0;
					}
				}else{
					$object[$key]['isTemp'] = 0;
				}
			}
		}
		return $object;
	}

	/**
	 * ������ʱid��ȡ��ʽid�����ڴ���������ɺ�ı���鿴
	 */
	function getObjIdByTempId_d($tempId,$code){
		$changeDao = new model_common_changeLog($code);
		$rows = $changeDao->getObjByTempId($tempId);
		return $rows[0]['objId'];
	}

	/**
	 * ���ݹ�����spid��ȡ��������Ϣ
	 */
	function getWfInfo_d($spid){
		$otherDao = new model_common_otherdatas();
		return $otherDao->getWorkflowInfo($spid);
	}

	/**
	 * ��ȡ����Ĭ������
	 */
	function getPersonSelectedSetting_d($gridId = 'auditing'){
		$selectsettingDao = new model_common_workflow_selectedsetting();
		return $selectsettingDao->rtUserSelected_d($gridId);
	}

	/**
	 * �ж��Ƿ��������·��
	 */
	function getSpeUrl_d($formName){
		//����·������
		include (WEB_TOR."model/common/workflow/workflowSpeConfig.php");
		//��������Ӧ�������
		$speSetArr = isset($speSetArr) ? $speSetArr : null;
		if($speSetArr){
			//��������
			$keyArr = array_keys($speSetArr[$formName]);
			if(in_array($_SESSION['USER_ID'],$keyArr)){
				return $speSetArr[$formName][$_SESSION['USER_ID']];
			}
		}
		return '';
	}

	/**
	 * �ж��������Ƿ����������Ϣ
	 */
	function isAudited_d($billId,$examCode){
		//��֤ҵ���Ƿ񻹴�������״̬
		$sql = "select ExaStatus from $examCode where id = '$billId'";
    	$objArr = $this->_db->getArray($sql);
    	if($objArr[0]['ExaStatus'] != AUDITING){
			return 1;
    	}

		//��ȡ��������Ϣ
    	$sql="select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
    	$taskArr = $this->_db->getArray($sql);
		$taskObj = array_pop($taskArr);

		//��ȡ��������Ϣ
		$sql="SELECT ID FROM flow_step where wf_task_id='".$taskObj['task']."' and ( flag='ok' or status<>'ok' or flag='') ";
		$auditArr = $this->_db->getArray($sql);
		if($auditArr[0]['ID']){
			return 1;
		}else{
			return 0;
		}
	}

	/**
	 * �ж��������Ƿ����������Ϣ(��ͬ��)
	 */
	function isAuditedContract_d($billId,$examCode){
		//��֤ҵ���Ƿ񻹴�������״̬
		$sql = "select ExaStatus from $examCode where id = '$billId'";
    	$objArr = $this->_db->getArray($sql);
    	if($objArr[0]['ExaStatus'] != AUDITING){
			return 1;
    	}

		//��ȡ��������Ϣ
    	$sql="select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
    	$taskArr = $this->_db->getArray($sql);
		$taskObj = array_pop($taskArr);

		//��ȡ��������Ϣ
		$sql="SELECT ID FROM flow_step where wf_task_id='".$taskObj['task']."' and ( flag='ok' or status<>'ok' or flag='') ";
		$auditArr = $this->_db->getArray($sql);

		$sql="select name from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
    	$taskNameArr = $this->_db->getArray($sql);
		if($auditArr[0]['ID']){
			return 1;
		}else{
			return $taskNameArr[0]['name'];
		}
	}

    /********************* �յ��˵�ϵ�� ********************/
    /**
     * �յ�����
     */
    function receiveForm_d($taskId,$formName,$objId){
		if(isset($this->receiveActionArr[$formName])){
			$modelInfo = $this->receiveActionArr[$formName];
//			print_r($modelInfo);

			$rs = 1;
			try{
				$this->start_d();

				//���µ�ǰ���յ�״̬
				$this->update(
					array('task' => $taskId),
					array('receiveStatus' => 1,'recevierName' => $_SESSION['USERNAME'],'recevierId'=> $_SESSION['USER_ID'],'recevieTime'=>date('Y-m-d H:i:s'))
				);

				//����ҵ���յ�״̬
				//��ѯҵ����Ϣ
				$newClass = $modelInfo['className'];
				$newAction = $modelInfo['funName'];
				//�˵�����
				$newClassDao = new $newClass();
				$newClassDao->$newAction($objId);

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
				$rs = 0;
			}

			//����յ��ɹ��������ʼ�
			if($rs == 1){
				//��ѯ������Ϣ
				$obj = $newClassDao->find(array('id' => $objId));
				$this->receiveMail_d($obj);
			}
			return $rs;
		}else{
			return -1;
		}
    }

    /**
     * �˵��ʼ�
     */
    function receiveMail_d($obj){
    	//����
    	$content = "���ã�<br/>" . $_SESSION['USERNAME'] . " �Ѿ�������ı������ţ�$obj[BillNo] �ĵ��ݣ�<br/>��������oaϵͳ�鿴��лл��";
    	//����
    	$title = "OA-�����յ�֪ͨ:".$obj['BillNo'];

    	$mailDao = new model_common_mail();
		$mailDao->mailClear($title,$obj['InputMan'],$content);
    }

    /**
     * �˵�����
     */
    function backForm_d($taskId,$formName,$objId){
		if(isset($this->backActionArr[$formName])){
			$modelInfo = $this->backActionArr[$formName];
//			print_r($modelInfo);

			$rs = 1;
			try{
				$this->start_d();

				//���µ�ǰ���յ�״̬
				$this->update(
					array('task' => $taskId),
					array('receiveStatus' => 0,'recevierName' => '','recevierId'=> '','recevieTime'=>date('Y-m-d H:i:s'))
				);

				//����ҵ���յ�״̬
				//��ѯҵ����Ϣ
				$newClass = $modelInfo['className'];
				$newAction = $modelInfo['funName'];
				//�˵�����
				$newClassDao = new $newClass();
				$newClassDao->$newAction($objId);

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
				$rs = 0;
			}

			//����յ��ɹ��������ʼ�
			if($rs == 1){
				//��ѯ������Ϣ
				$obj = $newClassDao->find(array('id' => $objId));
				$this->backMail_d($obj);
			}
			return $rs;
		}else{
			return -1;
		}
    }

    /**
     * �˵��ʼ�
     */
    function backMail_d($obj){
    	//����
    	$content = "���ã�<br/>" . $_SESSION['USERNAME'] . " �Ѿ��˻���ı������ţ�$obj[BillNo] �ĵ��ݣ�<br/>��������oaϵͳ�鿴��лл��";
    	//����
    	$title = "OA-�����˵�֪ͨ:".$obj['BillNo'];

    	$mailDao = new model_common_mail();
		$mailDao->mailClear($title,$obj['InputMan'],$content);
    }

	/************************** ������ʷ���� **************************/
	/**
	 * ���������ʼ��
	 */
	function auditInfo_d($object){
		$rs = $this->rtWorkflowExInfoArr_d();
		if(in_array($_SESSION['USER_ID'],$rs)){
			foreach($object as $key => $val){
					$object[$key]['auditHistory'] = $this->initViewOnlyBack($this->initAuditInfo($val['Pid'],$val['code']),$val['task']);
			}
		}else{
			$object[0]['auditHistory'] = '���ر������ݿ��ܵ����б��ȡ���������迪��������ϵOA����Ա';
		}
		return $object;
	}

	/**
	 * �ж��Ƿ��д�صļ�¼
	 */
	function hasBackInfo($pid,$code){
		$sql = "select task from wf_task where pid = $pid and code = '$code'";
		$rs = $this->_db->getArray($sql);
		return $rs;
	}

	/**
	 * �����������
	 */
	function initAuditInfo($pid,$code){
		$sql = "select
					p.task,p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag,p.Item,p.examines,u.USER_NAME
				from
				(
				select w.task,p.User  , p.Content, p.Result, p.Endtime ,p.ID ,p.Flag,f.Item,w.examines from flow_step f
						RIGHT JOIN wf_task w ON w.task = f.Wf_task_ID
						LEFT JOIN flow_step_partent p on p.StepID = f.ID where
					w.pid = $pid
				AND w. CODE = '$code'
				) p left JOIN user u on p.User = u.USER_ID ";
		$rs = $this->_db->getArray($sql);
		return $rs;
	}

	/**
	 * ҳ��ƴװ
	 */
	function initView($rows,$thisTask){
//		print_r($rows);
		$str = "";
		if(is_array($rows)){
			$headStr = "<table  class='form_main_table' style='width:500'>";
			$headStr .=<<<EOT
                <tr style="color:blue;">
                	<td width="5%">���</td>
                    <td width="15%">������</td>
                    <td width="10%">������</td>
                    <td width="15%">��������</td>
                    <td width="9%">�������</td>
                    <td width="27%">�������</td>
                </tr>
EOT;
			$mark = "";
			$i = 0;//�����κ�
			$x = 0;//�����
			foreach($rows as $key => $val){
				if(empty($val['Result'])) continue;

				if($val['Result']=="ok")
					$rsStr = "<font color='green'>ͬ��</font>";
				elseif($val['Result']=="no")
					$rsStr = "<font color='red'>��ͬ��</font>";
				else $rsStr = "δ����";

				if(!empty($val['Endtime'])){
					$endDate = date('Y-m-d',strtotime($val['Endtime']));
				}else{
					$endDate = "";
				}

				if(empty($mark) || $mark != $val['task']){
					$mark = $val['task'];
					$i ++;
					$x = 0;


					if($thisTask == $mark){
						$thisTaskStr = '(��������)';
					}else{
						$thisTaskStr = '';
					}
					$str.=<<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">�� $i ������$thisTaskStr</td></tr>
EOT;
				}
				$x ++;
				$contentStr = model_common_util::mb_str_split ( $val ['Content'], 20 );
//				$contentStr = implode ( "<br />", $contentArr );
				$str .=<<<EOT
		                <tr class='extr'>
		                	<td width="5%">$x</td>
		                    <td width="15%">$val[Item]</td>
		                    <td width="10%">$val[USER_NAME]</td>
		                    <td width="15%">$endDate</td>
		                    <td width="9%">$rsStr</td>
		                    <td width="27%">$contentStr</td>
		                </tr>
EOT;
			}
			if(empty($str)){
				return '';
			}else{
				return $headStr.$str."</table>";
			}
		}
		return $str;
	}

	/**
	 * ҳ��ƴװ-ֻ��ʾ�����Ϣ
	 */
	function initViewOnlyBack($rows,$thisTask){
//		print_r($rows);
		$str = "";
		if(is_array($rows)){
			$headStr = "<table  class='form_main_table' style='width:500'>";
			$headStr .=<<<EOT
                <tr style="color:blue;">
                	<td width="5%">���</td>
                    <td width="15%">������</td>
                    <td width="10%">������</td>
                    <td width="15%">��������</td>
                    <td width="9%">�������</td>
                    <td width="27%">�������</td>
                </tr>
EOT;
			$mark = "";
			$i = 0;//�����κ�
			$x = 0;//�����
			foreach($rows as $key => $val){
				if(empty($val['Result'])) continue;

				if($val['Result']=="ok")
					$rsStr = "<font color='green'>ͬ��</font>";
				elseif($val['Result']=="no")
					$rsStr = "<font color='red'>��ͬ��</font>";
				else $rsStr = "δ����";

				if(!empty($val['Endtime'])){
					$endDate = date('Y-m-d',strtotime($val['Endtime']));
				}else{
					$endDate = "";
				}
				if($val['Result'] != 'no') continue;

				if(empty($mark) || $mark != $val['task']){
					$mark = $val['task'];
					$i ++;
					$x = 0;

					if($thisTask == $mark){
						$thisTaskStr = '(��������)';
					}else{
						$thisTaskStr = '';
					}
					$str.=<<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">�� $i ������$thisTaskStr</td></tr>
EOT;
				}
				$x ++;
				$contentStr = model_common_util::mb_str_split ( $val ['Content'], 20 );
//				$contentStr = implode ( "<br />", $contentArr );
				$str .=<<<EOT
		                <tr class='extr'>
		                	<td width="5%">$x</td>
		                    <td width="15%">$val[Item]</td>
		                    <td width="10%">$val[USER_NAME]</td>
		                    <td width="15%">$endDate</td>
		                    <td width="9%">$rsStr</td>
		                    <td width="27%">$contentStr</td>
		                </tr>
EOT;
			}
			if(empty($str)){
				return '';
			}else{
				return $headStr.$str."</table>";
			}
		}
		return $str;
	}

	/**
	 * �ж������Ƿ��Ǳ��
	 */
	function inChange_d($spid){
		$otherDao = new model_common_otherdatas();
		$rows = $otherDao->getWorkflowInfo($spid);

		//���ڱ���ı�����
		$changeKeyArray = array_keys($this->changeFunArr);

		if(in_array($rows['formName'],$changeKeyArray)){
			//��֤�Ƿ��Ǻ�ͬ���
			$objClassStr = $this->changeFunArr[$rows['formName']]['className'];
			$objFunStr = $this->changeFunArr[$rows['formName']]['funName'];
			$obj = new $objClassStr();
			if($obj->$objFunStr($rows['objId'])){
				return $this->changeFunArr[$rows['formName']]['rtUrl'].$spid;
			}else{
				return false;
			}
		}else{
			return false;
		}

	}

	/******************* ������������ **********************/
	/**
	 * ��ȡ������Ϣ
	 */
	function getAuditInfo_d($spids){
		$sql="select p.ID , p.Wf_task_ID , t.Pid , t.code, t.name , t.Creator , t.objCode ,t.infomation from flow_step_partent p , flow_step s , wf_task t where t.task=p.Wf_task_ID and p.ID in ($spids) and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
		return $workflowArrs = $this->_db->getArray($sql);
	}

	/**
	 * ��ʼ��������Ϣ
	 */
	function initAuditInfo_d($workflowArrs){
		$str = null;
		//��Ⱦ������Ϣ
		if($workflowArrs){
			foreach($workflowArrs as $key => $val){
				$trClass = $key%2 == 0 ? "tr_odd" : "tr_even";
				$id = $val['ID'];
				$str .=<<<EOT
					<tr class="$trClass">
						<td>
							$val[Wf_task_ID]
							<input type="hidden" id="task$id" value="$val[Wf_task_ID]"/>
							<input type="hidden" id="spid$id" value="$val[ID]"/>
						</td>
						<td title="$val[infomation]">$val[objCode]</td>
						<td id="resultShow$id">
							<input type="radio" id="resultYes$id" name="result$id" value="ok" checked="checked" onclick="changeResult($id,'ok');"/><span id="resultYesInfo$id" class="blue">ͨ��</span>
							<input type="radio" id="resultNo$id" name="result$id" value="no" onclick="changeResult($id,'no');"/><span id="resultNoInfo$id">��ͨ��</span>
						</td>
						<td id="contentShow$id">
							<textarea id="content$id"></textarea>
						</td>
						<td id="mailShow$id">
							<input type="checkbox" id="isSend$id" checked="checked" value="y" title="֪ͨ�ύ�˵���������"/>֪ͨ������
							<input type="checkbox" id="isSendNext$id" checked="checked" value="y" title="֪ͨ��һ�������߽�������"/>֪ͨ��һ��������
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * ajax���� - ����
	 */
	function ajaxAudit_d($spid,$result = 'ok',$content = '',$isSend = 'y',$isSendNext = 'y'){
		//����spid�ж�
		if(!$spid){
			return '���ݴ���ʧ��';
		}

		//�������Ƿ��Ѿ����
		$isOver = false;
		//��һ��������
		$nextChecker = "";

		//���Ȼ�ȡ������Ϣ
		$sql="select p.SmallID, p.Wf_task_ID , s.Flow_prop ,t.Pid,t.code, t.name , t.Creator , t.DBTable,t.Enter_user from flow_step_partent p , flow_step s , wf_task t where t.task=p.Wf_task_ID and p.ID='$spid' and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
		$workflowArrs = $this->_db->getArray($sql);
		$workflowInfo = $workflowArrs[0];
		if($workflowInfo){
			//��ʽ������������
			try{
				$this->start_d();

				//��ʱ������;SQL
    			$sql="update wf_task set UpdateDT = now() where Status='ok' and task='".$workflowInfo['Wf_task_ID']."' ";
    			$this->_db->query($sql);

				//����������
			    $sql="update flow_step_partent set Flag='1', User='".$_SESSION['USER_ID']."',Result='".$result."',Content='".$content."',Endtime='".date('Y-m-d H:i:s')."' where ID=".$spid;
			    $this->_db->query($sql);

				//�������ͨ�� ���� ������
			    if($result == "ok" || $workflowInfo['Flow_prop'] == "1"){

					//��ѯ����������������Ƿ����Լ�������ɾ��
			        $sql="select ID , User from flow_step where SmallID>'".$workflowInfo['SmallID']."' and Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and find_in_set('".$_SESSION['USER_ID']."',User)>0 ";
			        $nextFlowArr = $this->_db->getArray($sql);
			        foreach($nextFlowArr as $key => $val){
			            if(trim($val["User"],",")==$_SESSION['USER_ID']){
			                $this->_db->query("delete from flow_step where ID='".$val["ID"]."'");
			            }else{
			                $this->_db->query("update flow_step set User='".str_replace($_SESSION['USER_ID'].",","",$val["User"])."' where ID='".$val["ID"]."' ");
			            }
			        }

			        //δ֪����
			        if($workflowInfo['Flow_prop']==1){
			            $sql="select ID from flow_step_partent where Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and SmallID='".$workflowInfo['SmallID']."' and Flag!='1' and ID!='$spid' ";
			            $flowPartentArr = $this->_db->getArray($sql);
			            if($flowPartentArr[0]['ID']){
			                $nextFlow=true;
			            }
			        }else{
			            $sql="delete from flow_step_partent where Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and SmallID='".$workflowInfo['SmallID']."' and ID!='$spid' ";
			            $this->_db->query($sql);
			            $nextFlow=true;
			        }

			        //��һ������
			        if($nextFlow === true){
			            $sql="update flow_step set Flag='',Endtime=now() where Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and SmallID='".$workflowInfo['SmallID']."'";
			            $this->_db->query($sql);

			            /************* ����޸� 1 ��ʼ select�����Ӳ�ѯ isReceive �� isEditPage�ֶ� *****************/
			            $sql="select ID,User,Flag ,SmallID,isReceive,isEditPage from flow_step where SmallID>'".$workflowInfo['SmallID']."' and Wf_task_ID='".$workflowInfo['Wf_task_ID']."'";
			            /************* ����޸� 1 ���� *****************/
			            $nextFlowArr = $this->_db->getArray($sql);

			            //�����������һ������Ϣ������д���
			            if($nextFlowArr[0]['ID']){
			            	$nextFlowInfo = $nextFlowArr[0];
			                $stepid= $nextFlowInfo["ID"];
			                $nextChecker = $nextFlowInfo["User"];
			                $nextSmallId= $nextFlowInfo["SmallID"];

			            	/*************** ����޸� 2 ��ʼ ��� isReceive �� isEditPage�ֶ� ********************/
			                $isReceive = $nextFlowInfo["isReceive"];
			                $isEditPage= $nextFlowInfo["isEditPage"];
			            	/************* ����޸� 2 ���� *****************/

			                foreach( explode(",",trim($nextChecker,',')) as $val){
			                    if($val!=""){
			                    	/*************** ����޸� 3 ��ʼ ������� isReceive �� isEditPage�ֶ� ********************/
			                        $sql="INSERT into flow_step_partent set StepID='$stepid',SmallID='$nextSmallId',Wf_task_ID='".$workflowInfo['Wf_task_ID']."',User='$val',Flag='0',START=now(),isReceive='$isReceive',isEditPage='$isEditPage' " ;
			                        /*************** ����޸� 3 ���� ******************/
			                        $this->_db->query($sql);
			                    }
			                }
			            }else{
			                $sql="update wf_task set examines='ok' , finish=now() , Status='0' where task='".$workflowInfo['Wf_task_ID']."' and Status='ok' ";
			                $this->_db->query($sql);

			                $sql="select PassSqlCode , name from wf_task where task='".$workflowInfo['Wf_task_ID']."'";
							$taskArrs = $this->_db->getArray($sql);
					        $taskInfo = $taskArrs[0];
					        if($taskInfo['PassSqlCode']!=""){
					            $this->_db->query(stripslashes($taskInfo['PassSqlCode']));
					        }
					        //���������������
					        $isOver = true;
			            }
			        }

			    }elseif($result == "no"){
			    	//������������Ϊ����
			        $sql="update flow_step set Flag='ok',Endtime=now() where Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and SmallID='$nowSmallId'";
			        $this->_db->query($sql);

					//����������Ϊ���
			        $sql="update wf_task set examines='no' , Status='0' , finish=now() where task='".$workflowInfo['Wf_task_ID']."' and Status='ok' ";
			        $this->_db->query($sql);

			        $sql="select DisPassSqlCode , name from wf_task where task='".$workflowInfo['Wf_task_ID']."'";
			        $taskArrs = $this->_db->getArray($sql);
			        $taskInfo = $taskArrs[0];
			        if($taskInfo['DisPassSqlCode']!=""){
			            $this->_db->query(stripslashes($taskInfo['DisPassSqlCode']));
			        }
			        //���������������
			        $isOver = true;
			    }

			    //�������ҵ����
			    if(isset($this->batchAuditArr[$workflowInfo['name']]) && !empty($this->batchAuditArr[$workflowInfo['name']])){
					$newClass = $this->batchAuditArr[$workflowInfo['name']]['className'];
					$newAction = $this->batchAuditArr[$workflowInfo['name']]['funName'];
					$newClassDao = new $newClass();
					$newClassDao->$newAction($spid);
			    }

				//�ʼ�����
			    $this->mailSend_d($spid,$result,$isOver,$workflowInfo,$isSend,$workflowInfo['Enter_user'],$isSendNext,$nextChecker,$content);

//				$this->rollBack();
				$this->commit_d();
				return true;
			}catch(Exception $e){
				$this->rollBack();
				throw $e;
				return false;
			}
		}else{
			return '�����Ѿ���ɣ�������';
		}
	}

	/**
	 * �ʼ�����
	 */
	function mailSend_d($spid,$result,$isOver,$workflowInfo,$isSend = 'y',$enterUser,$isSendNext = 'y',$nextChecker,$content = null){
		/************************* �����ʼ��������� ***************************/
		include(WEB_TOR."model/common/workflow/workflowMailConfig.php");//���������ļ�
		include(WEB_TOR."model/common/workflow/workflowMailInit.php");//�������÷����ļ�
		include(WEB_TOR."cache/DATADICTARR.cache.php");//���������ֵ仺��
		$DATADICTARR = isset($DATADICTARR) ? $DATADICTARR : "";
		$workflowMailConfig = isset($workflowMailConfig) ? $workflowMailConfig : "";

		//ʵ�����ʼ���
		$mailDao = new model_common_mail();

		$emailBody = null;//�ʼ�����
		$emailSql = null;//�ʼ���ѯsql
		//��ȡ�ʼ�����
	    $taskName = $workflowInfo['code'];
	    $pid = $workflowInfo['Pid'];
	    $flowName = $workflowInfo['name'];
		$wfCreator= $workflowInfo['Creator'];

		//�����ʼ���Ϣ
	    if(!empty($workflowMailConfig[$taskName])){
	    	//���������ϸ����
	    	if($workflowMailConfig[$taskName]['detailSet']){

				//���ݶ�ȡ����
				$sql = $workflowMailConfig[$taskName]['selectSql'] . " and c.id = $pid";
			    $rows = $this->_db->getArray($sql);

				//ģ��ƴװ
				$thisFun = $workflowMailConfig[$taskName]['actFunc'];
				$emailBody = $thisFun($rows,$DATADICTARR);
	    	}else{//�����������ϸ����
			    $emailSql="select " . $workflowMailConfig[$taskName]['thisCode'] . "  from ". $taskName . " c
			        left join wf_task b on (c.id=b.pid)
			        where c.id is not null  ";
			    $emailBody= $workflowMailConfig[$taskName]['thisInfo'];

		        $emailSql.=" and b.task='".$workflowInfo['Wf_task_ID']."' ";
			    $emailArrs = $this->_db->getArray($emailSql);
			    $emailData = $emailArrs[0];

		        if(is_array($emailData)){
		            foreach($emailData as $key=>$val){
		                $emailBody=str_replace('$'.$key, $val, $emailBody);
		            }
		        }
	    	}
	    }

		//�ж��Ƿ��͵�֪ͨ��
	    if($isSend == 'y')
	    {
	        $Subject = "OA-������".$flowName;
	        $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['USERNAME']."�Ѿ�����������Ϊ��".$workflowInfo['Wf_task_ID']." , �����ˣ�".$wfCreator." �����뵥����������<br />&nbsp;&nbsp;&nbsp;&nbsp;���������";
	        if($result=="no")
	            $ebody.="<font color='red'>��ͨ��</font>";
	        else
	            $ebody.="<font color='blue'>ͨ��</font>";
	        if(!empty($content)){
	            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;���������'.$content;
	        }
	        if(!empty($emailBody)){
	            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
	        }
       		$mailDao->mailClear($Subject,$enterUser,$ebody);
	    }

		//֪ͨ��һ��������
	    if($isSendNext=="y" && $result == "ok" && $isOver == false && $nextChecker)
	    {
	        $TO_ID = 'admin';
	        $Subject = "OA-������".$flowName;
	        $ebody = "���ã�<br />&nbsp;&nbsp;&nbsp;&nbsp;���µ���������Ҫ��������
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;�������ţ�".$workflowInfo['Wf_task_ID']."
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;����ʼ���".$_SESSION["USERNAME"]."ѡ��������ͣ�";
	        if(!empty($content)){
	            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;���������'.$content;
	        }
	        if(!empty($emailBody)){
	            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;�������飺<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
	        }
       		$mailDao->mailClear($Subject,$nextChecker,$ebody);
	    }
	}
}

?>