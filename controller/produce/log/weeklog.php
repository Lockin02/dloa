<?php
/**
 * @author Administrator
 * @Date 2012��5��16�� ������ 14:23:03
 * @version 1.0
 * @description:�����ܱ����Ʋ� 
 */
class controller_produce_log_weeklog extends controller_base_action {
	
	function __construct() {
		$this->objName = "weeklog";
		$this->objPath = "produce_log";
		parent::__construct ();
	}
	
	/**
	 * ��ת�������ܱ��б�
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
	 * ��ת�����������ܱ�ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}
	
	/**
	 * ��ת��������־ҳ��
	 */
	function c_toOpenall() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		//var_dump($obj['id']);
		if (! $obj) {
			msg ( '�����ڴ������ܱ�' );
		}
		$title = $obj ['weekTitle'] . '(' . $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] . ')';
		$this->assign ( 'title', $title );
		
		$this->assign ( 'name', $obj ['updateName'] );
		$this->assign ( 'time', $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] );
		$this->assign ( 'dep', $obj ['depName'] );
		//$this->assign('name', $obj['updateName']);
		$content = <<<EOF
		<tr class="main_tr_header">

	<th colspan="20" class="main_td_align_left"> ���������¼ </th>

</tr>

<tr class="main_tr_header">

	<th> ��� </th>


	<th> ������ </th>

	<th> ����״̬ </th>

	<th> �´����� </th>

	<th> ���뵥��� </th>

	<th> �ƻ���ʼʱ�� </th>

	<th> �ƻ�����ʱ�� </th>
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
	
	//��ĳ���ض���tasklist
	function c_toOpen() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		//var_dump($obj['id']);
		if (! $obj) {
			msg ( '�����ڴ������ܱ�' );
		}
		$title = $obj ['weekTitle'] . '(' . $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] . ')';
		$this->assign ( 'title', $title );
		
		$this->assign ( 'name', $obj ['updateName'] );
		$this->assign ( 'time', $obj ['weekBeginDate'] . '~' . $obj ['weekEndDate'] );
		$this->assign ( 'dep', $obj ['depName'] );
		//$this->assign('name', $obj['updateName']);
		$content = <<<EOF
		<tr class="main_tr_header">

	<th colspan="20" class="main_td_align_left"> ���������¼ </th>

</tr>

<tr class="main_tr_header">

	<th> ��� </th>


	<th> ������ </th>

	<th> ����״̬ </th>

	<th> �´����� </th>

	<th> ���뵥��� </th>

	<th> �ƻ���ʼʱ�� </th>

	<th> �ƻ�����ʱ�� </th>
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
									$content .= '<td>δִ��</td>';
									break;
								case 1 :
									$content .= '<td>ִ����</td>';
									break;
								case 2 :
									$content .= '<td>�����</td>';
									break;
								case 3 :
									$content .= '<td>�ѹر�</td>';
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
	 * ��ת���༭�����ܱ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}
	
	/**
	 * ��ת���鿴�����ܱ�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

}
?>