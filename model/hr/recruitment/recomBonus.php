<?php
/**
 * @author Administrator
 * @Date 2012年7月20日 星期五 11:33:19
 * @version 1.0
 * @description:内部推荐奖金 Model层
 */
class model_hr_recruitment_recomBonus  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recommend_bonus";
		$this->sql_map = "hr/recruitment/recomBonusSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
		0 => array (
				'statusEName' => 'save',
				'statusCName' => '保存',
				'key' => '0'
				),
				1 => array (
				'statusEName' => 'failed',
				'statusCName' => '不通过',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'waitfor',
				'statusCName' => '待发放',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'first',
				'statusCName' => '第一次发放',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'second',
				'statusCName' => '第二次发放',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'close',
				'statusCName' => '关闭',
				'key' => '5'
				),
				6 =>array (                        //add chenrf 20130517
				'statusEName' => 'submit',
				'statusCName' => '提交',
				'key' => '6'
				)
				);
				parent::__construct ();
	}
	/**
	 * 添加对象
	 */
	function add_d($object) {
		try{
			$object['ExaStatus'] = '保存';
			$object['ExaDT'] = date('Y-m-d');
			$object['formDate'] = date('Y-m-d');
			$object['formCode'] = "RB".date('YmdHis');
			$object['state'] = $this->statusDao->statusEtoK("save");
			$datadict = new model_system_datadict_datadict();
			$object['jobName'] = $datadict->getDataNameByCode($object['job']);
			$object['isBonus'] = 0;
			$id=parent::add_d($object,true);
			//更新附件关联关系
			//var_dump($id);
			$this->updateObjWithFile ( $id );
			//附件处理
			$uploadFile = new model_file_uploadfile_management ();
			//			$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			return $id;
		}catch(exception $e){
			return null;
		}

	}
	/**
	 * 审批后反馈，发送邮件
	 *@param $id 内部推荐ID
	 */
	function postMailto($object) {
		$emailArr = array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['recommendId'];
		$emailArr['TO_NAME']=$object['recommendName'];
		$recom = new model_hr_recruitment_recommend();
		//$emailArr['TO_ID']=$this->mailArr['sendUserId'];
		//$emailArr['TO_NAME']=$this->mailArr['sendName'];
		if ($emailArr['TO_ID']) {
			$addmsg = "";
			$isRecommendName=$object['isRecommendName'];
			$positionName=$object ['positionName'];
			$recommendName=$object ['recommendName'];
			$recommendReason=$object['recommendReason'];
			$bonus = $object['bonus'];
			$addmsg .=  <<<EOT
				<table width="500px">
					<thead>
						<tr align="center">
							<td><b>被推荐人</b></td>
							<td><b>推荐职位</b></td>
							<td><b>推荐人</b></td>
							<td><b>推荐理由</b></td>
						</tr>
					</thead>
					<tbody>
					<tr align="center" >
							<td>$isRecommendName</td>
							<td>$positionName</td>
							<td>$recommendName</td>
							<td>$recommendReason</td>
						</tr>
					</tbody>
					</table>
EOT;
			$addmsg .= "<br>审核结果：";
			$addmsg .= "<font color='blue'>通过</font>";
			$addmsg .= "<br>奖金奖励：";
			$addmsg .= "<font color='blue'>$bonus</font>";
			//echo $addmsg;
			$emailDao = new model_common_mail();
			$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'recombonus_passed', '该邮件为内部推荐奖励审核通过通知', '', $emailArr['TO_ID'], $addmsg, 1);

		}
		$recom->update(array("id"=>$object['parentId']),array("bonus"=>$object['bonus'],"isBonus"=>1));

	}
	/**
	 * 根据主键修改对象
	 */
	function edit_d($object, $isEditInfo = false) {
		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		$uploadFile = new model_file_uploadfile_management ();
		$fileinfo = $uploadFile->getFilesByObjId ($object['id'],'oa_hr_recommend_bonus');
		//var_dump($object);
		return $this->updateById ( $object );
	}

	/********************add chenrf 20150520***************/

	/**
	 *
	 * 跳转到对应的审批流
	 * @param $id id
	 */
	function c_redirectEwf($id){
		$obj=$this->get_d($id);
		$dataArr=$this->findSql('SELECT c.DEPT_ID,c.PARENT_ID FROM department c');
		$deptId=$this->getParentID($dataArr,$obj['deptId']);
		$ewf='';
		if(35==$deptId[0]){
			$ewf='ewf_index.php';
		}else{
			$ewf='ewf_index3.php';
		}
		$url='controller/hr/recruitment/'.$ewf.'?actTo=ewfSelect&billId='.$obj['id'].'&billDept='.$obj['deptId'];
		return $url;
	}
	/**
	 *
	 * 根据ID获取部门顶级父类ID(数组)
	 * @param $arr  数组 必须包含DEPT_ID和PARENT_ID字段
	 * @param $id   要查找的ID
	 */
	function getParentID($arr,$id){
		$parentArr=array();
		foreach ($arr as $row){
			if($id==$row['DEPT_ID']){
				if($row['PARENT_ID']==0){
					array_push($parentArr, $id);
				}
				$parentArr=array_merge($parentArr,$this->getParentID($arr,$row['PARENT_ID']));
			}
		}
		return $parentArr;
	}
	/********************add chenrf 20150527**************************/
	/**
	 *
	 * 改变状态函数
	 * @param $obj 对象，包含id和state
	 */
	function changeState($obj){
		return  $this->updateField(array('id'=>$obj['id']), 'state', $obj['state']);
	}

	/**
	 * 添加对象同时提交操作
	 */
	function addSubmit($object) {
		try{
			$object['ExaStatus'] = '保存';
			$object['ExaDT'] = date('Y-m-d');
			$object['formDate'] = date('Y-m-d');
			$object['formCode'] = "RB".date('YmdHis');
			$object['state'] = $this->statusDao->statusEtoK("submit");
			$datadict = new model_system_datadict_datadict();
			$object['jobName'] = $datadict->getDataNameByCode($object['job']);
			$object['isBonus'] = 0;
			$id=parent::add_d($object,true);
			//更新附件关联关系
			//var_dump($id);
			$this->updateObjWithFile ( $id );
			if($_POST ['fileuploadIds']){
				//附件处理
				$uploadFile = new model_file_uploadfile_management ();
				//				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			return $id;
		}catch(exception $e){
			return null;
		}

	}
}
?>