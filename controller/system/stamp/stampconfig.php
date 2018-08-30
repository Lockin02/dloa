<?php
/**
 * @author Show
 * @Date 2012年3月29日 星期四 9:41:06
 * @version 1.0
 * @description:盖章配置表控制层
 */
class controller_system_stamp_stampconfig extends controller_base_action {

	function __construct() {
		$this->objName = "stampconfig";
		$this->objPath = "system_stamp";
		parent::__construct ();
	}

	/*
	 * 跳转到盖章配置表列表
	 */
    function c_page() {
		$this->view('list');
    }

    /**
     * 重写获取分页数据转成Json函数
     * created by huanghaojin 2016-10-08
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //设置前台获取的参数信息

        //$service->asc = false;
        $rows = $service->page_d ();

        $rows = $this->addNameField($rows);// 通过数据字典查相应数据名称

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 重写新增盖章配置函数
     * created by huanghaojin 2016-10-08
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];
        $object['createId'] = $_SESSION ['USER_ID'];
        $object['createName'] = $_SESSION ['USERNAME'];
        $object['createTime'] = time();
        $object['updateId'] = $_SESSION ['USER_ID'];
        $object['updateName'] = $_SESSION ['USERNAME'];
        $object['updateTime'] = time();
        $id = $this->service->add_d ( $object, $isAddInfo );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        if ($id) {
            msg ( $msg );
        }
    }

    /**
     * 重写修改盖章配置函数
     * created by huanghaojin 2016-10-08
     */
    function c_edit($isEditInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];

        // 更新了盖章负责人要将旧的数据录入历史数据
        if($object['old_principalId'] != $object['principalId']){
            $oldData = $this->service->find(array('id' => $object['id']));
            // 添加历史记录
            if(is_array($oldData)){
                $history['pid'] = $oldData['id'];
                $history['stampName'] = $oldData['stampName'];
                $history['principalName'] = $oldData['principalName'];
                $history['principalId'] = $oldData['principalId'];
                $history['businessBelongId'] = $oldData['businessBelongId'];
                $history['typeId'] = $oldData['typeId'];
                $history['typeName'] = $oldData['typeName'];
                $history['startTime'] = ($oldData['lastHistoryTime'] == '0')? $oldData['createTime'] : $oldData['lastHistoryTime'];
                $history['endTime'] = time();
                $history['createId'] = $_SESSION ['USER_ID'];
                $history['createName'] = $_SESSION ['USERNAME'];
                $history['remark'] = $oldData['remark'];
                $vals = $cols = array();
                foreach ( $history as $key => $value ) {
                    $cols [] = $key;
                    $vals [] = "'" . $this->service->__val_escape ( $value ) . "'";
                }
                $col = join ( ',', $cols );
                $val = join ( ',', $vals );
                $sql = "INSERT INTO oa_system_stamp_config_history ({$col}) VALUES ({$val})";
                if (FALSE != $this->service->_db->query ( $sql )) {
                    $object['lastHistoryTime'] = time();
                }
            }
        }

        $object['updateId'] = $_SESSION ['USER_ID'];
        $object['updateName'] = $_SESSION ['USERNAME'];
        $object['updateTime'] = time();
        if ($this->service->edit_d ( $object, $isEditInfo )) {
            msg ( '编辑成功！' );
        }
    }

	/**
	 * 表格方法
	 */
	function c_jsonSelect(){
		$service = $this->service;
		$rows = null;

        if(!isset($_POST['sort']) || empty($_POST['sort'])){
            $_POST['sort'] = 'c.stampSort';
            $_POST['dir'] = 'ASC';
        }
		$service->getParam($_POST); //设置前台获取的参数信息

		$rows = $service->pageBySqlId('select');
        $rows = $this->addNameField($rows);// 通过数据字典查相应数据名称
		$rows = $this->sconfig->md5Rows ( $rows );

		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
        $arr['sql'] = $service->listSql;
		echo util_jsonUtil :: encode($arr);
	}

   /**
	 * 跳转到新增盖章配置表页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

   /**
	 * 跳转到编辑盖章配置表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $this->showDatadicts ( array ('typeId' => 'YZLB'),$obj['typeId'],true);
        $this->showDatadicts ( array ('businessBelong' => 'QYZT'),$obj['businessBelongId'],true);

        // 通过数据字典查相应数据名称
//        $datadictDao = new model_system_datadict_datadict();
//        $businessInfo = $datadictDao->getDataDictList_d('QYZT');//签约主体（公司名）
//        $businessBelongId = strtoupper($obj['businessBelongId']);
//        $this->assign ( "businessBelongName", $businessInfo[$businessBelongId] );

        $this->assign ( "old_principalId", $obj['principalId'] );
		$this->view ( 'edit');
	}

   /**
	 * 跳转到查看盖章配置表页面
    */
    function c_toView() {
        $this->permCheck (); //安全校验
        $obj = $this->service->get_d ( $_GET ['id'] );
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
        }

        // 通过数据字典查相应数据名称
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT');//签约主体（公司名）
        $businessBelongId = $obj['businessBelongId'];
        $this->assign ( "businessBelongName", $businessInfo[$businessBelongId] );

        $this->assign('status',$this->service->rtStampStatus_d($obj['status']));
        $this->view ( 'view' );
    }

    /**
     * 跳转到查看盖章管理历史数据表页面
     * created by huanghaojin 2016-10-08
     */
    function c_toViewHistory() {
        $this->permCheck (); //安全校验
        $this->assign('parentId',$_GET ['pid']);
        $this->view ( 'viewHistory' );
    }

    /**
     * 获取历史记录的json数据
     * created by huanghaojin 2016-10-08
     */
    function c_jsonHistory(){
        $service = $this->service;
        $rows = null;

        $service->getParam($_POST); //设置前台获取的参数信息
        $rows = $service->pageBySqlId('select_history');

        foreach($rows as $k => $v){
            $rows[$k]['startTime'] = empty($rows[$k]['startTime'])? "无" : date("Y-m-d", $rows[$k]['startTime']);
            $rows[$k]['endTime'] = date("Y-m-d", $rows[$k]['endTime']);
        }
        $rows = $this->addNameField($rows);

        $rows = $this->sconfig->md5Rows ( $rows );

        $arr = array ();
        $arr['collection'] = $rows;
        $arr['totalSize'] = $service->count;
        $arr['page'] = $service->page;
        echo util_jsonUtil :: encode($arr);
    }

    /**
     * 通过数据字典查相应数据名称
     * created by huanghaojin 2016-10-09
     */
    function addNameField($row){
        $rows = $row;
        // 通过数据字典查相应数据名称
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT');//签约主体（公司名）
        $typeInfo = $datadictDao->getDataDictList_d('YZLB');//印章类别
        foreach($rows as $k => $v){
            $businessBelongId = $v['businessBelongId'];
            $businessName = $businessInfo[$businessBelongId];
            $typeId = $v['typeId'];
            $rows[$k]['businessBelongName'] = $businessName;
            $rows[$k]['typeName'] = $typeInfo[$typeId];
        }
        return $rows;
    }

    /**
     * 通过ajax检查盖章名是否已存在
     */
    function c_ajaxChkStampName(){
        $stampName = isset($_POST['stampName'])? $_POST['stampName'] : '';
        $stampId = isset($_POST['stampId'])? $_POST['stampId'] : '';
        $stampName = util_jsonUtil::iconvUTF2GB($stampName);
        $backArr = array("result"=>'',"msg"=>'');
        if($stampName != ''){
            $chkResultArr = ($stampId != '')? $this->service->findAll(" stampName = '$stampName' and id <> '$stampId'") : $this->service->findAll(array("stampName" => $stampName));
            $backArr['result'] = ($chkResultArr && count($chkResultArr) > 0)? 'fail' : 'ok';
            $backArr['msg'] = ($chkResultArr && count($chkResultArr) > 0)? '此盖章名已存在!' : '';
        }else{
            $backArr['result'] = 'error';
            $backArr['msg'] = '输入的章名有误, 请重试!';
        }
        echo util_jsonUtil::encode($backArr);
    }
}
?>