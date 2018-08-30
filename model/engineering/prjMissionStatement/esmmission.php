<?php
/**
 * @author evan
 * @Date 2010年12月7日 9:19:54
 * @version 1.0
 * @description:项目任务书 oa_esm_mission Model层
 */
 class model_engineering_prjMissionStatement_esmmission  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_mission";
		$this->sql_map = "engineering/prjMissionStatement/esmmissionSql.php";
		parent::__construct ();
	}

	/****************************************模板类替换方法*******************************************/

	/*
	 * @desription 项目系数的替换方法
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 下午08:11:39
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

	/****************************************外部接口类方法*******************************************/

	/*
	 * @desription 任务书的保存方法
	 * @param tags
	 * @author qian
	 * @date 2010-12-7 下午01:53:58
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
	 * @desription 处理任务书
	 * @param tags
	 * @author qian
	 * @date 2010-12-9 上午09:27:30
	 */
	function dealIssue_d ($rows) {
		$mailDao = new model_common_mail();
		$esmprojectDao = new model_engineering_project_esmproject();
		try{
			$this->start_d();
			$esmprojectID = $esmprojectDao->dealIssue_d($rows);
			$sendMailTag = $mailDao->batchEmail($rows['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'oa_esm_project','发送',$rows['name'],$rows['TO_ID'],$rows['content']);
			//操作成功后改变任务书状态
			$condiction = array('contractId'=>$rows['contractId']);
			$updateRows = array(
				'status'=>'已处理',
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