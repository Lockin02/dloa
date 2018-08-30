<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ����ǰ������model
 *
 */
  class model_rdproject_task_tkfront extends model_base {


	function __construct() {
		$this->tbl_name = "oa_rd_task_front";
		$this->sql_map = "rdproject/task/tkfrontSql.php";
		parent::__construct ();
	}

	/* ---------------------------------ҳ��ģ����ʾ����------------------------------------------*/
	/*
	 * ��ʾ���� ǰ��������ϸ��Ϣģ��
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
		<td class="main_tr_header" colspan="4"><b>ǰ������$i</b></td>
	</tr>
		<tr>
		<td class="form_text_left">��������</td>
		<td class="form_text_right" colspan="3">$tkfront[name]</td>
		</tr>
		<tr>
		<td class="form_text_left">������</td>
		<td class="form_text_right">$tkfront[chargeName]</td>
		<td class="form_text_left">������Ŀ</td>
		<td class="form_text_right">$tkfront[projectName]</td>
	</tr>
	<tr>
		<td class="form_text_left">�����</td>
		<td class="form_view_right">$tkfront[effortRate]</td>
		<td class="form_text_left">�����ƻ�</td>
		<td class="form_view_right">$tkfront[planName]</td>
	</tr>
		<tr>
		<td class="form_text_left">�ƻ���ʼ����</td>
		<td class="form_view_right">$tkfront[planBeginDate]</td>
		<td class="form_text_left">�ƻ��������</td>
		<td class="form_view_right">$tkfront[planEndDate]</td>
	</tr>
		<tr>
		<td class="form_text_left">ƫ����</td>
		<td class="form_view_right">$tkfront[warpRate]</td>
		<td class="form_text_left">״̬</td>
		<td class="form_view_right">$status</td>
	</tr>
	<tr></tr>
EOT;
$i++;
			}
		}
		else
		//$str.="û��ǰ��������Ϣ";
		$str = "<tr align='center'><td colspan='50'>û��ǰ��������Ϣ</td></tr>";
		return $str;
	 }


	/* -----------------------------------ҵ��ӿڵ���-------------------------------------------*/
	/*
	 * ��������id ��ȡ����ǰ������
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
	 * ��������ID����ȡ����
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
