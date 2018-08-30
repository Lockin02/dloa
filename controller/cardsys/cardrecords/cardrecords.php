<?php
/**
 * @author Show
 * @Date 2012年1月5日 星期四 16:06:21
 * @version 1.0
 * @description:测试卡使用记录控制层
 */
class controller_cardsys_cardrecords_cardrecords extends controller_base_action {

	function __construct() {
		$this->objName = "cardrecords";
		$this->objPath = "cardsys_cardrecords";
		parent::__construct ();
	}

	/*
	 * 跳转到测试卡使用记录列表
	 */
    function c_page() {
		$this->view('list');
    }

    /*
	 * 跳转到测试卡使用记录列表
	 */
    function c_pageForProject() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
    }

   /**
	 * 跳转到新增测试卡使用记录页面
	 */
	function c_toAdd() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('userName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);
		$this->view ( 'add' );
	}

    /**
     * 日志新增测试卡信息
     */
    function c_toAddInWorklog(){
        $worklogId = $_GET['worklogId'];
        //获取日志中的其他信息
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        $this->view('addinworklog');
    }

    /**
     * 日志新增测试卡信息
     */
    function c_addInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->addBatch_d($object);
        if($countMoney){
            echo "<script>alert('保存成功');if(window.opener){window.opener.returnValue = $countMoney;}window.returnValue = $countMoney;window.close();</script>";
        }else{
            echo "<script>alert('保存失败');window.close();</script>";
        }
        exit();
    }

   /**
	 * 跳转到编辑测试卡使用记录页面
	 */
	function c_toEdit() {
   		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

   /**
	 * 跳转到查看测试卡使用记录页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 获取测试卡使用记录数据返回json
	 */
	function c_listJsonForWeeklog() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$service->sort = 'c.useDate';
		$service->asc = false;

		$rows = $this->service->list_d('select_forweeklog');
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 验证对应测试卡是否已存在使用记录
	 */
	function c_hasRecords(){
		$cardId = $_POST['cardId'];
		$rs = $this->service->find(array('cardId' => $cardId),null,'id');
		if(is_array($rs)){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>