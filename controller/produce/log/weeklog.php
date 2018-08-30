<?php
/**
 * @author Administrator
 * @Date 2012年5月16日 星期三 14:23:03
 * @version 1.0
 * @description:工作周报控制层 
 */
class controller_produce_log_weeklog extends controller_base_action {
	
	function __construct() {
		$this->objName = "weeklog";
		$this->objPath = "produce_log";
		parent::__construct ();
	}
	
	/**
	 * 跳转到工作周报列表
	 */
	function c_page() {
		$this->assign ( 'userid', $_SESSION ['USER_ID'] );
		$this->view ( 'list' );
	}
	
	function c_allpage() {
		//var_dump($_SESSION);
		$this->view ( 'listall' );
	}
	
	/**
	 * 跳转到新增工作周报页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到工作日志页面
	 */
	function c_toOpenall() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		//var_dump($obj['id']);
		if (! $obj) {
			msg ( '不存在此任务周报' );
		}
		$title = $obj ['weekTitle'] . '(' . $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] . ')';
		$this->assign ( 'title', $title );
		
		$this->assign ( 'name', $obj ['updateName'] );
		$this->assign ( 'time', $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] );
		$this->assign ( 'dep', $obj ['depName'] );
		//$this->assign('name', $obj['updateName']);
		$content = <<<EOF
		<tr class="main_tr_header">

	<th colspan="20" class="main_td_align_left"> 工作任务记录 </th>

</tr>

<tr class="main_tr_header">

	<th> 序号 </th>


	<th> 任务编号 </th>

	<th> 任务状态 </th>

	<th> 下达日期 </th>

	<th> 申请单编号 </th>

	<th> 计划开始时间 </th>

	<th> 计划结束时间 </th>
</tr>
EOF;
		$work = new model_produce_log_worklog ();
		$gettask = $work->findAll ( array ('weekId' => $obj ['id'] ) );
		
		$task = new model_produce_task_producetask ();
		for($i = 0; $i < count ( $gettask ); $i ++) {
			$getinfo = $task->findAll ( array ('docCode' => $gettask [$i] ['produceTaskCode'] ), null, 'id,docCode,docStatus,docDate,applyDocCode,planStartDate,planEndDate' );
			if ($getinfo) {
				for($i = 0; $i < count ( $getinfo ); $i ++) {
					$content .= '<tr class="tr_odd">';
					foreach ( $getinfo [$i] as $key => $value ) {
						$content .= '<td>' . $value . '</td>';
					}
					$content .= '</tr>';
				}
			} else
				continue;
		}
		
		$this->assign ( 'content', $content );
		$this->view ( 'openall' );
	}
	
	//打开某个特定的tasklist
	function c_toOpen() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		//var_dump($obj['id']);
		if (! $obj) {
			msg ( '不存在此任务周报' );
		}
		$title = $obj ['weekTitle'] . '(' . $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] . ')';
		$this->assign ( 'title', $title );
		
		$this->assign ( 'name', $obj ['updateName'] );
		$this->assign ( 'time', $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] );
		$this->assign ( 'dep', $obj ['depName'] );
		//$this->assign('name', $obj['updateName']);
		$content = <<<EOF
		<tr class="main_tr_header">

	<th colspan="20" class="main_td_align_left"> 工作任务记录 </th>

</tr>

<tr class="main_tr_header">

	<th> 序号 </th>


	<th> 任务编号 </th>

	<th> 任务状态 </th>

	<th> 下达日期 </th>

	<th> 申请单编号 </th>

	<th> 计划开始时间 </th>

	<th> 计划结束时间 </th>
</tr>
EOF;
		$work = new model_produce_log_worklog ();
		$gettask = $work->findAll ( array ('weekId' => $obj ['id'] ) );
		//var_dump($gettask);
		$task = new model_produce_task_producetask ();
		for($i = 0; $i < count ( $gettask ); $i ++) {
			$getinfo = $task->findAll ( array ('docCode' => $gettask [$i] ['produceTaskCode'] ), null, 'id,docCode,docStatus,docDate,applyDocCode,planStartDate,planEndDate' );
			//var_dump($getinfo);
			if ($getinfo) {
				for($i = 0; $i < count ( $getinfo ); $i ++) {
					$content .= '<tr class="tr_odd">';
					foreach ( $getinfo [$i] as $key => $value ) {
						if ($key == "docStatus") {
							switch ($value) {
								case 0 :
									$content .= '<td>未执行</td>';
									break;
								case 1 :
									$content .= '<td>执行中</td>';
									break;
								case 2 :
									$content .= '<td>已完成</td>';
									break;
								case 3 :
									$content .= '<td>已关闭</td>';
									break;
							}
						
						} else {
							$content .= '<td>' . $value . '</td>';
						}
					
					}
					$content .= '</tr>';
				}
			} else
				continue;
		}
		
		$this->assign ( 'content', $content );
		$this->view ( 'open' );
	}
	/**
	 * 跳转到编辑工作周报页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看工作周报页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

}
?>