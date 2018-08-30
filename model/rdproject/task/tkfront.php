<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务前置任务model
 *
 */
  class model_rdproject_task_tkfront extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_front";
		$this->sql_map = "rdproject/task/tkfrontSql.php";
		parent::__construct ();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/
	/*
	 * 显示任务 前置任务详细信息模板
	 */
	 function showTkFrontDetail($rows){
	 	if($rows){
	 	$str="";
	 	$i=1;
			$datadictDao = new model_system_datadict_datadict ();
			foreach( $rows as $key => $tkfront ){
				$status = $datadictDao->getDataNameByCode ($tkfront['status'] );
$str .= <<<EOT
			<tr>
		<td class="main_tr_header" colspan="4"><b>前置任务$i</b></td>
	</tr>
		<tr>
		<td class="form_text_left">任务名称</td>
		<td class="form_text_right" colspan="3">$tkfront[name]</td>
		</tr>
		<tr>
		<td class="form_text_left">责任人</td>
		<td class="form_text_right">$tkfront[chargeName]</td>
		<td class="form_text_left">所属项目</td>
		<td class="form_text_right">$tkfront[projectName]</td>
	</tr>
	<tr>
		<td class="form_text_left">完成率</td>
		<td class="form_view_right">$tkfront[effortRate]</td>
		<td class="form_text_left">所属计划</td>
		<td class="form_view_right">$tkfront[planName]</td>
	</tr>
		<tr>
		<td class="form_text_left">计划开始日期</td>
		<td class="form_view_right">$tkfront[planBeginDate]</td>
		<td class="form_text_left">计划完成日期</td>
		<td class="form_view_right">$tkfront[planEndDate]</td>
	</tr>
		<tr>
		<td class="form_text_left">偏差率</td>
		<td class="form_view_right">$tkfront[warpRate]</td>
		<td class="form_text_left">状态</td>
		<td class="form_view_right">$status</td>
	</tr>
	<tr></tr>
EOT;
$i++;
			}
		}
		else
		//$str.="没有前置任务信息";
		$str = "<tr align='center'><td colspan='50'>没有前置任务信息</td></tr>";
		return $str;
	 }


	/* -----------------------------------业务接口调用-------------------------------------------*/
	/*
	 * 根据任务id 获取所有前置任务
	 */
	function getFrontByTaskId_d($taskId){
		$this->searchArr=array(
			"taskId"=>$taskId
		);

		$frontTks=$this->listBySqlId();

		$taskDao=new model_rdproject_task_rdtask();
		$taskIdArr=array();
		$ftkLength=count($frontTks);
		for($i=0;$i<$ftkLength;$i++){
			array_push($taskIdArr,$frontTks[$i]['frontTaskId']);
		}
		$taskDao->searchArr=array(
			"ids"=>$taskIdArr
		);
		return $taskDao->listBySqlId();
	}

	/**
	 * 根据任务ID串获取数据
	 */
	function getFontByTaskIds_d($taskIds){
		if(!empty($taskIds)){
			$taskIds = substr($taskIds,0,-1);
			$this->searchArr['taskIds']= $taskIds;
			$this->searchArr['noequal']= 0;
			return $this->listBySqlId();
		}else{
			return ;
		}
	}
}
?>
