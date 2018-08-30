<?php
/**
 * @author Administrator
 * @Date 2011��3��3�� 11:28:42
 * @version 1.0
 * @description:���ټ�¼���Ʋ� ���ټ�¼
 */
class controller_projectmanagent_track_track extends controller_base_action {

	function __construct() {
		$this->objName = "track";
		$this->objPath = "projectmanagent_track";
		parent::__construct ();
	 }

	/*
	 * ��ת�����ټ�¼
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * �����������ټ�¼
     */
    function c_toCluesTrack() {
    	$this->permCheck($_GET['id'],projectmanagent_clues_clues);
    	$this->assign('trackName' , $_SESSION['USERNAME']);
    	$this->assign('trackmanId' , $_SESSION['USER_ID']);
    	$this->assign('trackDate' , date ('Y-m-d'));
    	$this->assign('cluesId' , $_GET['id']);
    	$this->assign('id' , $_GET['id']);
    	$this->display('clues-add');
    }

    /**
     * �����̻����ټ�¼
     */
    function c_toChanceTrack() {
    	$this->permCheck($_GET['id'],projectmanagent_chance_chance);
    	$this->assign('trackName' , $_SESSION['USERNAME']);
    	$this->assign('trackDate' , date ('Y-m-d'));
    	$this->assign('chanceId' , $_GET['id']);
    	$this->assign('id' , $_GET['id']);
    	$this->display('chance-add');
    }

     function c_init() {
		$row = $this->service->get_d ( $_GET ['id'] );

		//����
		$rows['file'] = $this->service->getFilesByObjId($row['id'], false);
        $this->show->assign('file' , $rows['file']);
		foreach ( $row as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		//		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );

        $this->assign ( 'trackType', $this->getDataNameByCode ( $row['trackType'] ) );
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {

			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		} else {
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}
	/**
	 * ����Excel
	 */
	function c_outputtExcel(){
		$chanceId = $_GET['chanceId'];
	
		$service = $this->service;
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
// 		��ͷId����
				$colIdStr = $_GET['colId'];
				
				$colIdArr = explode(',', $colIdStr);
				$colIdArr = array_filter($colIdArr);
// 			print_r($colIdArr);
// 			die();	
		//��ͷName����
				$colNameStr = $_GET['colName'];
				$colNameArr = explode(',', $colNameStr);
				$colNameArr = array_filter($colNameArr);
		//��ͷ����
			$colArr = array_combine($colIdArr, $colNameArr);
		$service->searchArr['chanceId'] = $chanceId;  //��ѯ����
		$rows = $service->listBySqlId('select_default2');
   		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		return model_projectmanagent_track_export2ExcelUtil :: export2ExcelUtil($colArr, $dataArr);
	
	}
	
	
 }
?>