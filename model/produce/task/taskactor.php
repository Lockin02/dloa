<?php
/**
 * @author huangzf
 * @Date 2012年5月21日 星期一 9:47:24
 * @version 1.0
 * @description:任务参与人 Model层 
 */
class model_produce_task_taskactor extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_produce_task_actor";
		$this->sql_map = "produce/task/taskactorSql.php";
		parent::__construct ();
	}
	
	/**
	 * 
	 * 通过任务id获取任务参与人字符串信息
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