
<?php
/*
 * Created on 2010-9-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_task_stophistory extends model_base {

	/**
	 * @desription 构造函数
	 * @date 2010-9-14 下午02:50:04
	 */
	function __construct() {
		$this->tbl_name = "oa_esm_task_stop_history";
		$this->sql_map = "engineering/task/stophistorySql.php";
		parent::__construct ();
	}
	/*
	 * 新增暂停任务，同时更新新项目任务状态为暂停状态
	 */
	function add_d($object){
		try{
			$this->start_d();
		$protask['id']=$object['taskId'];

		$protask['status']="ZT";
		$rdTaskDao=new model_engineering_task_protask();
		$rdTaskDao->updateById($protask);
		$id= parent::add_d($object);
		$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
	/*
	 * 恢复任务，同时更新项目状态为原始状态
	 */

	 function edit_d($object){
		try{
			$this->start_d();
			$row = $this->searchArr=array(
				"taskId"=>$object['taskId'],
				"isAct"=>0
			);
			$lastophistorys=$this->find($row,null,'id,taskId,lastStatus,isAct');

			$rdtask['id']=$object['taskId'];
			$rdtask['status']=$lastophistorys['lastStatus'];
			$rdTaskDao=new model_engineering_task_protask();
			$rdTaskDao->updateById($rdtask);

			$object['id']=$lastophistorys['id'];
			parent::edit_d($object);
			$this->commit_d();
			return true;
		}
		catch(Exception $e)
		{
			$this->rollBack();
			return null;
		}
	 }
 }
?>