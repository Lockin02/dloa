<?php
/**
 * @author evan
 * @Date 2010��12��7�� 9:19:54
 * @version 1.0
 * @description:��Ŀ������ oa_esm_mission Model��
 */
 class model_engineering_prjMissionStatement_esmmission  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_mission";
		$this->sql_map = "engineering/prjMissionStatement/esmmissionSql.php";
		parent::__construct ();
	}

	/****************************************ģ�����滻����*******************************************/

	/*
	 * @desription ��Ŀϵ�����滻����
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����08:11:39
	 */
	function itemFactorList ($rows) {
		$rows = isset( $rows )?$rows:null;
		$str = "";
		if($rows){
			foreach($rows as $key => $val){
				$str .=<<<EOT
				<option id="$val" name="$val">$val</option>
EOT;
			}
		}else{
			$str = "<option>11</option>";
		}
		return $str;
	}

	/****************************************�ⲿ�ӿ��෽��*******************************************/

	/*
	 * @desription ������ı��淽��
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 ����01:53:58
	 */
	function addissue_d ($rows) {
		$rows = isset( $rows )?$rows:null;
		try{
			$this->start_d();

			parent::add_d($rows,true);
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			throw $e;

		}
		return true;
	}


	/*
	 * @desription ����������
	 * @param tags
	 * @author qian
	 * @date 2010-12-9 ����09:27:30
	 */
	function dealIssue_d ($rows) {
		$mailDao = new model_common_mail();
		$esmprojectDao = new model_engineering_project_esmproject();
		try{
			$this->start_d();
			$esmprojectID = $esmprojectDao->dealIssue_d($rows);
			$sendMailTag = $mailDao->batchEmail($rows['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'oa_esm_project','����',$rows['name'],$rows['TO_ID'],$rows['content']);
			//�����ɹ���ı�������״̬
			$condiction = array('contractId'=>$rows['contractId']);
			$updateRows = array(
				'status'=>'�Ѵ���',
				'projectId'=>$esmprojectID,
				'projectName'=>$rows['name'],
				'executor'=>$rows['executor'],
				'executorId'=>$rows['executorId']
//
			);
			$this->update($condiction,$updateRows);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

 }
?>