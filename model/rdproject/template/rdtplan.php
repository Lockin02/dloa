<?php
/*
 * Created on 2010-10-19
 *	计划模板
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_rdproject_template_rdtplan extends model_base{
	function __construct(){
		$this->tbl_name = "oa_rd_template_plan";
		$this->sql_map = "rdproject/template/rdtplanSql.php";
		parent::__construct ();
	}


	/************************************页面显示*****************************/
	function showlist($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$status = $this->returnStatus($val['status']);
				$str.=<<<EOT
					<tr class="$classCss" title="$val[id]" id="$i">
						<td>
							$i
							<input type="hidden" id="status$i" value="$val[status]" />
							<input type="hidden" id="templateName$i" value="$val[templateName]" />
						</td>
						<td>
							<a href="?model=rdproject_template_rdtnode&action=readPlanTemplate&id=$val[id]&templateName=$val[templateName]">$val[templateName]</a>
						</td>
						<td>
							$status
						</td>
						<td class="remarkClass">
							$val[description]
						</td>
						<td>
							$val[createName]
						</td>
						<td>
							$val[createTime]
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">暂无相关模板</td></tr>';
		}
		return $str;

	}


	/*************************************业务处理*****************************/
	/**
	 * 重写add_d方法
	 */
	function add_d($object){

		$tknodeDao = new model_rdproject_task_tknode();
		$rows = $tknodeDao->getNodeTkInPlan_d($object['planId']);

		$rdtnodeDao = new model_rdproject_template_rdtnode();
		$rdttaskDao = new model_rdproject_template_rdttask();
		$taskRows = array();
		$nodeRows = array();
		$nodeLeftKey = array();
		$idArray = array();

		$object['createId'] = $_SESSION['USER_ID'];
		$object['createName'] = $_SESSION['USERNAME'];
		$object['createTime'] = date('Y-m-d H:i:s');
		$object['status'] = '0';

		try{
			$this->start_d();
			$id = parent::add_d($object);
			foreach($rows as $key=> $val){
				if(isset($val['status'])){//如果存在状态，则为任务
					$taskRows = $val;
					$taskRows['templateId'] = $id;
					$taskRows['templateName'] = $object['templateName'];
					if($val['belongNodeId']!= -1){
						$taskRows['nodeLeftKey'] = $nodeLeftKey[$val['belongNodeId']];
						$taskRows['belongNodeId'] =$idArray[$val['belongNodeId']] ;
					}
					unset($taskRows['id']);
					$rdttaskDao->add_d($taskRows);

				}else{//不存在则为节点
					$nodeRows = $val;
					$nodeRows['templateId'] = $id;
					$taskRows['templateName'] = $object['templateName'];
					$nodeLeftKey[$val['id']] = $val['lft'];
					if($val['parentId']!= -1){
						$nodeRows['nodeLeftKey'] = $nodeLeftKey[$val['parentId']];
						$nodeRows['parentId'] = $idArray[$val['parentId']] ;
					}
					$oldId = $nodeRows['id'];
					unset($nodeRows['id']);
					$in_id = $rdtnodeDao->addSimple($nodeRows);
					$idArray[$oldId] = $in_id;
				}
			}
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 发布
	 */
	function issue($id){
		$object = array( 'id' => $id ,'status' => 1);
		return $this->edit_d($object);
	}

	/**
	 * 返回模板状态 0为保存，1为发布
	 */
	function returnStatus($key){
		if($key){
			return '发布';
		}else{
			return '保存';
		}
	}

	/**
	 * 模板
	 */
	function templateImport($id,$projectId,$projectName,$planId,$planName){
		$taskKey = array();
		$taskKeyName = array();

		$templateNodeDao = new model_rdproject_template_rdtnode();
		$tNodeRows = $templateNodeDao->getNodes($id);

		$templateTaskDao = new model_rdproject_template_rdttask();
		$tTaskRows = $templateTaskDao->getTasks($id);

//		echo "<pre>";
		try{
			if($tNodeRows){
				$tkNodeDao = new model_rdproject_task_tknode();
				foreach($tNodeRows as $val){
					unset($val['id']);
					$val['projectId'] = $projectId;
					$val['projectName'] = $projectName;
					$val['planId'] = $planId;
					$val['planName'] = $planName;
					if(!empty($val['nodeLeftKey'])){
						$val['parentId'] = $taskKey[$val['nodeLeftKey']];
						$val['parentName'] = $taskKeyName[$val['nodeLeftKey']];
					}
					$in_id = $tkNodeDao->addSimple($val);
					$taskKey[$val['lft']] = $in_id;
					$taskKeyName[$val['lft']] = $val['nodeName'];
	//				print_r($val);
				}
			}

			if($tTaskRows){
				$taskDao = new model_rdproject_task_rdtask();
				foreach($tTaskRows as $val){
					unset($val['id']);
					$val['status'] = 'WFB';
					$val['projectId'] = $projectId;
					$val['projectName'] = $projectName;
					$val['planId'] = $planId;
					$val['planName'] = $planName;
					if($val['belongNodeId'] != -1){
						$val['belongNodeId'] = $taskKey[$val['nodeLeftKey']];
						$val['belongNode'] = $taskKeyName[$val['nodeLeftKey']];
					}
					$in_id = $taskDao->addSimple($val);
	//				print_r($val);
				}
			}
		}catch(exception $e){
			throw $e;
		}

	}
}
?>
