<?php
/**
 * @author huangzf
 * @Date 2012��5��21�� ����һ 9:47:24
 * @version 1.0
 * @description:��������� Model�� 
 */
class model_produce_task_taskactor extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_produce_task_actor";
		$this->sql_map = "produce/task/taskactorSql.php";
		parent::__construct ();
	}
	
	/**
	 * 
	 * ͨ������id��ȡ����������ַ�����Ϣ
	 */
	function getActorsByTaskId($taskId) {
		$this->searchArr ['taskId'] = $taskId;
		$rows = $this->listBySqlId ();
		$actorIds = "";
		$actorNames = "";
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $value ) {
				$actorIds .= $value ['actUserCode'];
				$actorNames .= $value ['actUserName'];
				if (($key + 1) != count ( $rows )) {
					$actorIds .= ",";
					$actorNames .= ",";
				}
			}
		}
		
		return array ("actorIds" => $actorIds, "actorNames" => $actorNames );
	}
}
?>