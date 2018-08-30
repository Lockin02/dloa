<?php
class model_common_mobile extends model_base
{

	//正常数组
	public $urlArr;

	//批量审批数组
	public $batchAuditArr;

	//变更数组
	public $changeFunArr;

	//收单操作对应数组
	public $receiveActionArr;
	//退单操作对应数组
	public $backActionArr;
	
	
	 function __construct() {
		include_once (WEB_TOR."model/common/workflow/workflowRegister.php");
		include_once (WEB_TOR."model/common/workflow/workflowExaInfoConfig.php");

		//工作流对应数组及其加密信息
		$this->urlArr = isset($urlArr) ? $urlArr : null;
		//批量审批数组
		$this->batchAuditArr = isset($batchAuditArr) ? $batchAuditArr : null;

		//审批历史加载数组
		$this->ExaInfoUserArr = isset($ExaInfoUserArr) ? $ExaInfoUserArr : null;

		//工作流对应变更数组
		$this->changeFunArr = isset($changeFunArr) ? $changeFunArr : null;

		//收单操作对应数组
		$this->receiveActionArr = isset($receiveActionArr) ? $receiveActionArr : null;
		//退单操作对应数组
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
	 * 返回注册工作流串
	 */
	function rtWorkflowStr_d(){
		return implode(array_keys($this->urlArr),',');
	}

	/**
	 * 返回注册工作流数组
	 */
	function getBatchAudit_d(){
		return array_keys($this->batchAuditArr);
	}

	/**
	 * 返回刻批量审批流数组
	 */
	function rtWorkflowArr_d(){
		return array_keys($this->urlArr);
	}

	/**
	 * 返回审批历史查看人配置数组
	 */
	function rtWorkflowExInfoArr_d(){
		return array_keys($this->ExaInfoUserArr);
	}

	/**
	 * 判断审批流是否存在审批信息
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
	 * 判断审批流是否存在审批信息
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
	 * 处理变更部分数据
	 */
	function rowsDeal_d($object){
		if(!empty($object) && !empty($this->changeFunArr)){
			//存在变更的表数组
			$changeKeyArray = array_keys($this->changeFunArr);

			foreach($object as $key => $val){
				//判断当前列是否在变更数组中
				if(in_array($val['name'],$changeKeyArray)){

					//验证是否是合同变更
					$objClassStr = $this->changeFunArr[$val['name']]['className'];
					$objFunStr = $this->changeFunArr[$val['name']]['funName'];
					//数组缓存实例化对象
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
	 * 根据临时id获取正式id，用于处理审批完成后的变更查看
	 */
	function getObjIdByTempId_d($tempId,$code){
		$changeDao = new model_common_changeLog($code);
		$rows = $changeDao->getObjByTempId($tempId);
		return $rows[0]['objId'];
	}

	/**
	 * 根据工作流spid获取工作流信息
	 */
	function getWfInfo_d($spid){
		$otherDao = new model_common_otherdatas();
		return $otherDao->getWorkflowInfo($spid);
	}

	/**
	 * 获取个人默认设置
	 */
	function getPersonSelectedSetting_d($gridId = 'auditing'){
		$selectsettingDao = new model_common_workflow_selectedsetting();
		return $selectsettingDao->rtUserSelected_d($gridId);
	}

	/**
	 * 判断是否存在特殊路径
	 */
	function getSpeUrl_d($formName){
		//特殊路径配置
		include (WEB_TOR."model/common/workflow/workflowSpeConfig.php");
		//工作流对应变更数组
		$speSetArr = isset($speSetArr) ? $speSetArr : null;
		if($speSetArr){
			//对象数组
			$keyArr = array_keys($speSetArr[$formName]);
			if(in_array($_SESSION['USER_ID'],$keyArr)){
				return $speSetArr[$formName][$_SESSION['USER_ID']];
			}
		}
		return '';
	}

	/**
	 * 判断审批流是否存在审批信息
	 */
	function isAudited_d($billId,$examCode){
		//验证业务是否还处于审批状态
		$sql = "select ExaStatus from $examCode where id = '$billId'";
    	$objArr = $this->_db->getArray($sql);
    	if($objArr[0]['ExaStatus'] != AUDITING){
			return 1;
    	}

		//获取主审批信息
    	$sql="select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
    	$taskArr = $this->_db->getArray($sql);
		$taskObj = array_pop($taskArr);

		//获取已审批信息
		$sql="SELECT ID FROM flow_step where wf_task_id='".$taskObj['task']."' and ( flag='ok' or status<>'ok' or flag='') ";
		$auditArr = $this->_db->getArray($sql);
		if($auditArr[0]['ID']){
			return 1;
		}else{
			return 0;
		}
	}

	/**
	 * 判断审批流是否存在审批信息(合同用)
	 */
	function isAuditedContract_d($billId,$examCode){
		//验证业务是否还处于审批状态
		$sql = "select ExaStatus from $examCode where id = '$billId'";
    	$objArr = $this->_db->getArray($sql);
    	if($objArr[0]['ExaStatus'] != AUDITING){
			return 1;
    	}

		//获取主审批信息
    	$sql="select task from wf_task where code='$examCode' and Pid='$billId' and Status='ok' ";
    	$taskArr = $this->_db->getArray($sql);
		$taskObj = array_pop($taskArr);

		//获取已审批信息
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

    /********************* 收单退单系列 ********************/
    /**
     * 收单操作
     */
    function receiveForm_d($taskId,$formName,$objId){
		if(isset($this->receiveActionArr[$formName])){
			$modelInfo = $this->receiveActionArr[$formName];
//			print_r($modelInfo);

			$rs = 1;
			try{
				$this->start_d();

				//更新当前的收单状态
				$this->update(
					array('task' => $taskId),
					array('receiveStatus' => 1,'recevierName' => $_SESSION['USERNAME'],'recevierId'=> $_SESSION['USER_ID'],'recevieTime'=>date('Y-m-d H:i:s'))
				);

				//更新业务收单状态
				//查询业务信息
				$newClass = $modelInfo['className'];
				$newAction = $modelInfo['funName'];
				//退单操作
				$newClassDao = new $newClass();
				$newClassDao->$newAction($objId);

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
				$rs = 0;
			}

			//如果收单成功，发送邮件
			if($rs == 1){
				//查询对象信息
				$obj = $newClassDao->find(array('id' => $objId));
				$this->receiveMail_d($obj);
			}
			return $rs;
		}else{
			return -1;
		}
    }

    /**
     * 退单邮件
     */
    function receiveMail_d($obj){
    	//内容
    	$content = "您好：<br/>" . $_SESSION['USERNAME'] . " 已经接收你的报销单号：$obj[BillNo] 的单据！<br/>详情请上oa系统查看，谢谢！";
    	//标题
    	$title = "OA-财务收单通知:".$obj['BillNo'];

    	$mailDao = new model_common_mail();
		$mailDao->mailClear($title,$obj['InputMan'],$content);
    }

    /**
     * 退单操作
     */
    function backForm_d($taskId,$formName,$objId){
		if(isset($this->backActionArr[$formName])){
			$modelInfo = $this->backActionArr[$formName];
//			print_r($modelInfo);

			$rs = 1;
			try{
				$this->start_d();

				//更新当前的收单状态
				$this->update(
					array('task' => $taskId),
					array('receiveStatus' => 0,'recevierName' => '','recevierId'=> '','recevieTime'=>date('Y-m-d H:i:s'))
				);

				//更新业务收单状态
				//查询业务信息
				$newClass = $modelInfo['className'];
				$newAction = $modelInfo['funName'];
				//退单操作
				$newClassDao = new $newClass();
				$newClassDao->$newAction($objId);

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
				$rs = 0;
			}

			//如果收单成功，发送邮件
			if($rs == 1){
				//查询对象信息
				$obj = $newClassDao->find(array('id' => $objId));
				$this->backMail_d($obj);
			}
			return $rs;
		}else{
			return -1;
		}
    }

    /**
     * 退单邮件
     */
    function backMail_d($obj){
    	//内容
    	$content = "您好：<br/>" . $_SESSION['USERNAME'] . " 已经退还你的报销单号：$obj[BillNo] 的单据！<br/>详情请上oa系统查看，谢谢！";
    	//标题
    	$title = "OA-财务退单通知:".$obj['BillNo'];

    	$mailDao = new model_common_mail();
		$mailDao->mailClear($title,$obj['InputMan'],$content);
    }

	/************************** 审批历史处理 **************************/
	/**
	 * 审批情况初始化
	 */
	function auditInfo_d($object){
		$rs = $this->rtWorkflowExInfoArr_d();
		if(in_array($_SESSION['USER_ID'],$rs)){
			foreach($object as $key => $val){
					$object[$key]['auditHistory'] = $this->initViewOnlyBack($this->initAuditInfo($val['Pid'],$val['code']),$val['task']);
			}
		}else{
			$object[0]['auditHistory'] = '加载本列数据可能导致列表读取变慢，如需开启，请联系OA管理员';
		}
		return $object;
	}

	/**
	 * 判断是否含有打回的记录
	 */
	function hasBackInfo($pid,$code){
		$sql = "select task from wf_task where pid = $pid and code = '$code'";
		$rs = $this->_db->getArray($sql);
		return $rs;
	}

	/**
	 * 构建审批情况
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
	 * 页面拼装
	 */
	function initView($rows,$thisTask){
//		print_r($rows);
		$str = "";
		if(is_array($rows)){
			$headStr = "<table  class='form_main_table' style='width:500'>";
			$headStr .=<<<EOT
                <tr style="color:blue;">
                	<td width="5%">序号</td>
                    <td width="15%">步骤名</td>
                    <td width="10%">审批人</td>
                    <td width="15%">审批日期</td>
                    <td width="9%">审批结果</td>
                    <td width="27%">审批意见</td>
                </tr>
EOT;
			$mark = "";
			$i = 0;//审批次号
			$x = 0;//行序号
			foreach($rows as $key => $val){
				if(empty($val['Result'])) continue;

				if($val['Result']=="ok")
					$rsStr = "<font color='green'>同意</font>";
				elseif($val['Result']=="no")
					$rsStr = "<font color='red'>不同意</font>";
				else $rsStr = "未审批";

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
						$thisTaskStr = '(本次审批)';
					}else{
						$thisTaskStr = '';
					}
					$str.=<<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">第 $i 次审批$thisTaskStr</td></tr>
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
	 * 页面拼装-只显示打回信息
	 */
	function initViewOnlyBack($rows,$thisTask){
//		print_r($rows);
		$str = "";
		if(is_array($rows)){
			$headStr = "<table  class='form_main_table' style='width:500'>";
			$headStr .=<<<EOT
                <tr style="color:blue;">
                	<td width="5%">序号</td>
                    <td width="15%">步骤名</td>
                    <td width="10%">审批人</td>
                    <td width="15%">审批日期</td>
                    <td width="9%">审批结果</td>
                    <td width="27%">审批意见</td>
                </tr>
EOT;
			$mark = "";
			$i = 0;//审批次号
			$x = 0;//行序号
			foreach($rows as $key => $val){
				if(empty($val['Result'])) continue;

				if($val['Result']=="ok")
					$rsStr = "<font color='green'>同意</font>";
				elseif($val['Result']=="no")
					$rsStr = "<font color='red'>不同意</font>";
				else $rsStr = "未审批";

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
						$thisTaskStr = '(本次审批)';
					}else{
						$thisTaskStr = '';
					}
					$str.=<<<EOT
						<tr class="form_header"><td colspan="6" style="text-align:left">第 $i 次审批$thisTaskStr</td></tr>
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
	 * 判断数据是否是变更
	 */
	function inChange_d($spid){
		$otherDao = new model_common_otherdatas();
		$rows = $otherDao->getWorkflowInfo($spid);

		//存在变更的表数组
		$changeKeyArray = array_keys($this->changeFunArr);

		if(in_array($rows['formName'],$changeKeyArray)){
			//验证是否是合同变更
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

	/******************* 批量审批部分 **********************/
	/**
	 * 获取审批信息
	 */
	function getAuditInfo_d($spids){
		$sql="select p.ID , p.Wf_task_ID , t.Pid , t.code, t.name , t.Creator , t.objCode ,t.infomation from flow_step_partent p , flow_step s , wf_task t where t.task=p.Wf_task_ID and p.ID in ($spids) and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
		return $workflowArrs = $this->_db->getArray($sql);
	}

	/**
	 * 初始化审批信息
	 */
	function initAuditInfo_d($workflowArrs){
		$str = null;
		//渲染审批信息
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
							<input type="radio" id="resultYes$id" name="result$id" value="ok" checked="checked" onclick="changeResult($id,'ok');"/><span id="resultYesInfo$id" class="blue">通过</span>
							<input type="radio" id="resultNo$id" name="result$id" value="no" onclick="changeResult($id,'no');"/><span id="resultNoInfo$id">不通过</span>
						</td>
						<td id="contentShow$id">
							<textarea id="content$id"></textarea>
						</td>
						<td id="mailShow$id">
							<input type="checkbox" id="isSend$id" checked="checked" value="y" title="通知提交人单据已审批"/>通知已审批
							<input type="checkbox" id="isSendNext$id" checked="checked" value="y" title="通知下一步审批者进行审批"/>通知下一步审批者
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * ajax审批 - 单独
	 */
	function ajaxAudit_d($spid,$result = 'ok',$content = '',$isSend = 'y',$isSendNext = 'y'){
		//传入spid判断
		if(!$spid){
			return '数据传入失败';
		}

		//审批流是否已经完成
		$isOver = false;
		//下一步审批人
		$nextChecker = "";

		//首先获取审批信息
		$sql="select p.SmallID, p.Wf_task_ID , s.Flow_prop ,t.Pid,t.code, t.name , t.Creator , t.DBTable,t.Enter_user from flow_step_partent p , flow_step s , wf_task t where t.task=p.Wf_task_ID and p.ID='$spid' and p.Wf_task_ID=s.Wf_task_ID and p.SmallID=s.SmallID and p.Flag!='1'";
		$workflowArrs = $this->_db->getArray($sql);
		$workflowInfo = $workflowArrs[0];
		if($workflowInfo){
			//正式进入审批流程
			try{
				$this->start_d();

				//暂时不明用途SQL
    			$sql="update wf_task set UpdateDT = now() where Status='ok' and task='".$workflowInfo['Wf_task_ID']."' ";
    			$this->_db->query($sql);

				//本步骤审批
			    $sql="update flow_step_partent set Flag='1', User='".$_SESSION['USER_ID']."',Result='".$result."',Content='".$content."',Endtime='".date('Y-m-d H:i:s')."' where ID=".$spid;
			    $this->_db->query($sql);

				//如果审批通过 或者 。。。
			    if($result == "ok" || $workflowInfo['Flow_prop'] == "1"){

					//查询后面的审批步骤中是否有自己，有则删除
			        $sql="select ID , User from flow_step where SmallID>'".$workflowInfo['SmallID']."' and Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and find_in_set('".$_SESSION['USER_ID']."',User)>0 ";
			        $nextFlowArr = $this->_db->getArray($sql);
			        foreach($nextFlowArr as $key => $val){
			            if(trim($val["User"],",")==$_SESSION['USER_ID']){
			                $this->_db->query("delete from flow_step where ID='".$val["ID"]."'");
			            }else{
			                $this->_db->query("update flow_step set User='".str_replace($_SESSION['USER_ID'].",","",$val["User"])."' where ID='".$val["ID"]."' ");
			            }
			        }

			        //未知条件
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

			        //下一步处理
			        if($nextFlow === true){
			            $sql="update flow_step set Flag='',Endtime=now() where Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and SmallID='".$workflowInfo['SmallID']."'";
			            $this->_db->query($sql);

			            /************* 外包修改 1 开始 select语句添加查询 isReceive 和 isEditPage字段 *****************/
			            $sql="select ID,User,Flag ,SmallID,isReceive,isEditPage from flow_step where SmallID>'".$workflowInfo['SmallID']."' and Wf_task_ID='".$workflowInfo['Wf_task_ID']."'";
			            /************* 外包修改 1 结束 *****************/
			            $nextFlowArr = $this->_db->getArray($sql);

			            //如果还存在下一步的信息，则进行处理
			            if($nextFlowArr[0]['ID']){
			            	$nextFlowInfo = $nextFlowArr[0];
			                $stepid= $nextFlowInfo["ID"];
			                $nextChecker = $nextFlowInfo["User"];
			                $nextSmallId= $nextFlowInfo["SmallID"];

			            	/*************** 外包修改 2 开始 添加 isReceive 和 isEditPage字段 ********************/
			                $isReceive = $nextFlowInfo["isReceive"];
			                $isEditPage= $nextFlowInfo["isEditPage"];
			            	/************* 外包修改 2 结束 *****************/

			                foreach( explode(",",trim($nextChecker,',')) as $val){
			                    if($val!=""){
			                    	/*************** 外包修改 3 开始 插入添加 isReceive 和 isEditPage字段 ********************/
			                        $sql="INSERT into flow_step_partent set StepID='$stepid',SmallID='$nextSmallId',Wf_task_ID='".$workflowInfo['Wf_task_ID']."',User='$val',Flag='0',START=now(),isReceive='$isReceive',isEditPage='$isEditPage' " ;
			                        /*************** 外包修改 3 结束 ******************/
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
					        //设置审批流已完成
					        $isOver = true;
			            }
			        }

			    }elseif($result == "no"){
			    	//更新审批步骤为已审
			        $sql="update flow_step set Flag='ok',Endtime=now() where Wf_task_ID='".$workflowInfo['Wf_task_ID']."' and SmallID='$nowSmallId'";
			        $this->_db->query($sql);

					//更新审批表为打回
			        $sql="update wf_task set examines='no' , Status='0' , finish=now() where task='".$workflowInfo['Wf_task_ID']."' and Status='ok' ";
			        $this->_db->query($sql);

			        $sql="select DisPassSqlCode , name from wf_task where task='".$workflowInfo['Wf_task_ID']."'";
			        $taskArrs = $this->_db->getArray($sql);
			        $taskInfo = $taskArrs[0];
			        if($taskInfo['DisPassSqlCode']!=""){
			            $this->_db->query(stripslashes($taskInfo['DisPassSqlCode']));
			        }
			        //设置审批流已完成
			        $isOver = true;
			    }

			    //审批完成业务处理
			    if(isset($this->batchAuditArr[$workflowInfo['name']]) && !empty($this->batchAuditArr[$workflowInfo['name']])){
					$newClass = $this->batchAuditArr[$workflowInfo['name']]['className'];
					$newAction = $this->batchAuditArr[$workflowInfo['name']]['funName'];
					$newClassDao = new $newClass();
					$newClassDao->$newAction($spid);
			    }

				//邮件处理
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
			return '审批已经完成，请重试';
		}
	}

	/**
	 * 邮件处理
	 */
	function mailSend_d($spid,$result,$isOver,$workflowInfo,$isSend = 'y',$enterUser,$isSendNext = 'y',$nextChecker,$content = null){
		/************************* 审批邮件发送设置 ***************************/
		include(WEB_TOR."model/common/workflow/workflowMailConfig.php");//引入配置文件
		include(WEB_TOR."model/common/workflow/workflowMailInit.php");//引入配置方法文件
		include(WEB_TOR."cache/DATADICTARR.cache.php");//引入数据字典缓存
		$DATADICTARR = isset($DATADICTARR) ? $DATADICTARR : "";
		$workflowMailConfig = isset($workflowMailConfig) ? $workflowMailConfig : "";

		//实例化邮件类
		$mailDao = new model_common_mail();

		$emailBody = null;//邮件内容
		$emailSql = null;//邮件查询sql
		//获取邮件配置
	    $taskName = $workflowInfo['code'];
	    $pid = $workflowInfo['Pid'];
	    $flowName = $workflowInfo['name'];
		$wfCreator= $workflowInfo['Creator'];

		//配置邮件信息
	    if(!empty($workflowMailConfig[$taskName])){
	    	//如果存在详细设置
	    	if($workflowMailConfig[$taskName]['detailSet']){

				//数据读取部分
				$sql = $workflowMailConfig[$taskName]['selectSql'] . " and c.id = $pid";
			    $rows = $this->_db->getArray($sql);

				//模板拼装
				$thisFun = $workflowMailConfig[$taskName]['actFunc'];
				$emailBody = $thisFun($rows,$DATADICTARR);
	    	}else{//如果不存在详细设置
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

		//判断是否发送到通知人
	    if($isSend == 'y')
	    {
	        $Subject = "OA-审批：".$flowName;
	        $ebody = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;".$_SESSION['USERNAME']."已经对审批单号为：".$workflowInfo['Wf_task_ID']." , 申请人：".$wfCreator." 的申请单进行审批！<br />&nbsp;&nbsp;&nbsp;&nbsp;审批结果：";
	        if($result=="no")
	            $ebody.="<font color='red'>不通过</font>";
	        else
	            $ebody.="<font color='blue'>通过</font>";
	        if(!empty($content)){
	            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;审批意见：'.$content;
	        }
	        if(!empty($emailBody)){
	            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
	        }
       		$mailDao->mailClear($Subject,$enterUser,$ebody);
	    }

		//通知下一个审批人
	    if($isSendNext=="y" && $result == "ok" && $isOver == false && $nextChecker)
	    {
	        $TO_ID = 'admin';
	        $Subject = "OA-审批：".$flowName;
	        $ebody = "您好！<br />&nbsp;&nbsp;&nbsp;&nbsp;有新的审批单需要您审批！
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;审批单号：".$workflowInfo['Wf_task_ID']."
	                <br />&nbsp;&nbsp;&nbsp;&nbsp;这封邮件由".$_SESSION["USERNAME"]."选择给您发送！";
	        if(!empty($content)){
	            $ebody.='<br />&nbsp;&nbsp;&nbsp;&nbsp;审批意见：'.$content;
	        }
	        if(!empty($emailBody)){
	            $ebody.='<br /> &nbsp;&nbsp;&nbsp;&nbsp;单据详情：<br /> &nbsp;&nbsp;&nbsp;&nbsp;'.$emailBody;
	        }
       		$mailDao->mailClear($Subject,$nextChecker,$ebody);
	    }
	}
}

?>