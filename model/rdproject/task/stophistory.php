
<?php
/*
 * Created on 2010-9-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_rdproject_task_stophistory extends model_base {

	/**
	 * @desription ���캯��
	 * @date 2010-9-14 ����02:50:04
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_task_stop_history";
		$this->sql_map = "rdproject/task/stophistorySql.php";
		parent::__construct ();
	}
	/*
	 * ������ͣ����ͬʱ��������Ŀ����״̬Ϊ��ͣ״̬
	 */
	function add_d($object){
		try{
			$this->start_d();
		$rdtask['id']=$object['taskId'];
		$rdtask['status']="SQZT";
		$rdTaskDao=new model_rdproject_task_rdtask();
		$rdTaskDao->updateById($rdtask);
		$id= parent::add_d($object);
		$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/*
	 * �ָ�����ͬʱ������Ŀ״̬Ϊԭʼ״̬
	 */
	 function edit_d($object){
		try{
			$this->start_d();
			$this->searchArr=array(
				"taskId"=>$object['taskId'],
				"isAct"=>0
			);
			$lastophistorys=$this->list_d();

			$rdtask['id']=$object['taskId'];
			$rdtask['status']=$lastophistorys['0']['lastStatus'];
			$rdTaskDao=new model_rdproject_task_rdtask();
			$rdTaskDao->updateById($rdtask);
			$object['id']=$lastophistorys['0']['id'];
			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	 }


	/*
	 * �����ͣ����
	 */
	 function fightback_d($taskId){
		try{
			$this->start_d();
			$this->searchArr=array(
				"taskId"=>$taskId,
				"isAct"=>2
			);
			$lastophistorys=$this->list_d();

			$rdtask['id']=$taskId;
			$rdtask['status']=$lastophistorys['0']['lastStatus'];
			$rdTaskDao=new model_rdproject_task_rdtask();
			$rdTaskDao->updateById($rdtask);
			$object['id']=$lastophistorys['0']['id'];
			$object['isAct']=3;
			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	 }

	/*
	 * ��ͣ����--������ͣ��
	 */
	 function stopByApply_d($object){
		try{
			$this->start_d();
			$this->searchArr=array(
				"taskId"=>$object['taskId'],
				"isAct"=>2
			);
			$lastophistorys=$this->list_d();
			$rdtask['id']=$object['taskId'];
			$rdtask['status']="ZT";
			$rdTaskDao=new model_rdproject_task_rdtask();
			$rdTaskDao->updateById($rdtask);
			$object['id']=$lastophistorys['0']['id'];
			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	 }

	/*
	 * ֱ����ͣ
	 */
	function stop_d($object){
		try{
			$this->start_d();
			$rdtask['id']=$object['taskId'];
			$rdtask['status']="ZT";
			$rdTaskDao=new model_rdproject_task_rdtask();
			$rdTaskDao->updateById($rdtask);
			$id= parent::add_d($object);
			$this->commit_d();
				return $id;
			} catch (Exception $e) {
				$this->rollBack();
				return null;
			}
	}

 }
?>