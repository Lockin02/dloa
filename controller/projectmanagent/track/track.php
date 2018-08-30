<?php
/**
 * @author Administrator
 * @Date 2011年3月3日 11:28:42
 * @version 1.0
 * @description:跟踪记录控制层 跟踪记录
 */
class controller_projectmanagent_track_track extends controller_base_action {

	function __construct() {
		$this->objName = "track";
		$this->objPath = "projectmanagent_track";
		parent::__construct ();
	 }

	/*
	 * 跳转到跟踪记录
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

    /**
     * 新增线索跟踪记录
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
     * 新增商机跟踪记录
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

		//附件
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
	 * 导出Excel
	 */
	function c_outputtExcel(){
		$chanceId = $_GET['chanceId'];
	
		$service = $this->service;
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
// 		表头Id数组
				$colIdStr = $_GET['colId'];
				
				$colIdArr = explode(',', $colIdStr);
				$colIdArr = array_filter($colIdArr);
// 			print_r($colIdArr);
// 			die();	
		//表头Name数组
				$colNameStr = $_GET['colName'];
				$colNameArr = explode(',', $colNameStr);
				$colNameArr = array_filter($colNameArr);
		//表头数组
			$colArr = array_combine($colIdArr, $colNameArr);
		$service->searchArr['chanceId'] = $chanceId;  //查询条件
		$rows = $service->listBySqlId('select_default2');
   		//匹配导出列
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