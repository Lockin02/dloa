<?php
/*
 * Created on 2010-12-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * Ա���ܱ����˽��������Ϣ model
 */
 class model_engineering_assessment_assweek extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_ass_week";
		$this->sql_map = "engineering/assessment/assweekSql.php";
		parent::__construct ();
	}

   function add_d($object){
	try {
		$this->start_d();
		$id=parent::add_d($object);
		/*s:���濼�˽��*/
		$assresult=array(
							"weekResultId"=>$id
						);
		foreach($object['assresults'] as $key=>$value){
			$assresultDao=new model_engineering_assessment_assresult();//���˽��
			$assresult['indicatorId']=$value['indicatorId'];
			$assresult['score']=$value['score'];
			$assresult['indicatorsName']=$value['indicatorsName'];
			$assresultDao->add_d($assresult);

		}

		/*e:���濼�˽��*/
		$this->commit_d ();
		return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	/**
	 * @desription TODO
	 * @param tags
	 * @date 2010-12-11 ����10:37:04
	 * @qiaolong
	 */
	function editstatus ($arr) {
		$esmworklogweekDao = new model_engineering_worklog_esmworklogweek();
		$esmworklogweekDao->edit_d($arr);
	}
}
?>
