<?php
/**
 * @author Administrator
 * @Date 2012��7��20�� ������ 11:33:19
 * @version 1.0
 * @description:�ڲ��Ƽ����� Model��
 */
class model_hr_recruitment_recomBonus  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_recommend_bonus";
		$this->sql_map = "hr/recruitment/recomBonusSql.php";
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
		0 => array (
				'statusEName' => 'save',
				'statusCName' => '����',
				'key' => '0'
				),
				1 => array (
				'statusEName' => 'failed',
				'statusCName' => '��ͨ��',
				'key' => '1'
				),
				2 => array (
				'statusEName' => 'waitfor',
				'statusCName' => '������',
				'key' => '2'
				),
				3 => array (
				'statusEName' => 'first',
				'statusCName' => '��һ�η���',
				'key' => '3'
				),
				4 => array (
				'statusEName' => 'second',
				'statusCName' => '�ڶ��η���',
				'key' => '4'
				),
				5 => array (
				'statusEName' => 'close',
				'statusCName' => '�ر�',
				'key' => '5'
				),
				6 =>array (                        //add chenrf 20130517
				'statusEName' => 'submit',
				'statusCName' => '�ύ',
				'key' => '6'
				)
				);
				parent::__construct ();
	}
	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
		try{
			$object['ExaStatus'] = '����';
			$object['ExaDT'] = date('Y-m-d');
			$object['formDate'] = date('Y-m-d');
			$object['formCode'] = "RB".date('YmdHis');
			$object['state'] = $this->statusDao->statusEtoK("save");
			$datadict = new model_system_datadict_datadict();
			$object['jobName'] = $datadict->getDataNameByCode($object['job']);
			$object['isBonus'] = 0;
			$id=parent::add_d($object,true);
			//���¸���������ϵ
			//var_dump($id);
			$this->updateObjWithFile ( $id );
			//��������
			$uploadFile = new model_file_uploadfile_management ();
			//			$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			return $id;
		}catch(exception $e){
			return null;
		}

	}
	/**
	 * ���������������ʼ�
	 *@param $id �ڲ��Ƽ�ID
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
							<td><b>���Ƽ���</b></td>
							<td><b>�Ƽ�ְλ</b></td>
							<td><b>�Ƽ���</b></td>
							<td><b>�Ƽ�����</b></td>
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
			$addmsg .= "<br>��˽����";
			$addmsg .= "<font color='blue'>ͨ��</font>";
			$addmsg .= "<br>��������";
			$addmsg .= "<font color='blue'>$bonus</font>";
			//echo $addmsg;
			$emailDao = new model_common_mail();
			$emailDao -> emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'recombonus_passed', '���ʼ�Ϊ�ڲ��Ƽ��������ͨ��֪ͨ', '', $emailArr['TO_ID'], $addmsg, 1);

		}
		$recom->update(array("id"=>$object['parentId']),array("bonus"=>$object['bonus'],"isBonus"=>1));

	}
	/**
	 * ���������޸Ķ���
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
	 * ��ת����Ӧ��������
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
	 * ����ID��ȡ���Ŷ�������ID(����)
	 * @param $arr  ���� �������DEPT_ID��PARENT_ID�ֶ�
	 * @param $id   Ҫ���ҵ�ID
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
	 * �ı�״̬����
	 * @param $obj ���󣬰���id��state
	 */
	function changeState($obj){
		return  $this->updateField(array('id'=>$obj['id']), 'state', $obj['state']);
	}

	/**
	 * ��Ӷ���ͬʱ�ύ����
	 */
	function addSubmit($object) {
		try{
			$object['ExaStatus'] = '����';
			$object['ExaDT'] = date('Y-m-d');
			$object['formDate'] = date('Y-m-d');
			$object['formCode'] = "RB".date('YmdHis');
			$object['state'] = $this->statusDao->statusEtoK("submit");
			$datadict = new model_system_datadict_datadict();
			$object['jobName'] = $datadict->getDataNameByCode($object['job']);
			$object['isBonus'] = 0;
			$id=parent::add_d($object,true);
			//���¸���������ϵ
			//var_dump($id);
			$this->updateObjWithFile ( $id );
			if($_POST ['fileuploadIds']){
				//��������
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