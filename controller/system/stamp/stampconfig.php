<?php
/**
 * @author Show
 * @Date 2012��3��29�� ������ 9:41:06
 * @version 1.0
 * @description:�������ñ���Ʋ�
 */
class controller_system_stamp_stampconfig extends controller_base_action {

	function __construct() {
		$this->objName = "stampconfig";
		$this->objPath = "system_stamp";
		parent::__construct ();
	}

	/*
	 * ��ת���������ñ��б�
	 */
    function c_page() {
		$this->view('list');
    }

    /**
     * ��д��ȡ��ҳ����ת��Json����
     * created by huanghaojin 2016-10-08
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();

        $rows = $this->addNameField($rows);// ͨ�������ֵ����Ӧ��������

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * ��д�����������ú���
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
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
        if ($id) {
            msg ( $msg );
        }
    }

    /**
     * ��д�޸ĸ������ú���
     * created by huanghaojin 2016-10-08
     */
    function c_edit($isEditInfo = false) {
        $this->checkSubmit();
        $object = $_POST [$this->objName];

        // �����˸��¸�����Ҫ���ɵ�����¼����ʷ����
        if($object['old_principalId'] != $object['principalId']){
            $oldData = $this->service->find(array('id' => $object['id']));
            // �����ʷ��¼
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
            msg ( '�༭�ɹ���' );
        }
    }

	/**
	 * ��񷽷�
	 */
	function c_jsonSelect(){
		$service = $this->service;
		$rows = null;

        if(!isset($_POST['sort']) || empty($_POST['sort'])){
            $_POST['sort'] = 'c.stampSort';
            $_POST['dir'] = 'ASC';
        }
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->pageBySqlId('select');
        $rows = $this->addNameField($rows);// ͨ�������ֵ����Ӧ��������
		$rows = $this->sconfig->md5Rows ( $rows );

		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
        $arr['sql'] = $service->listSql;
		echo util_jsonUtil :: encode($arr);
	}

   /**
	 * ��ת�������������ñ�ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

   /**
	 * ��ת���༭�������ñ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        $this->showDatadicts ( array ('typeId' => 'YZLB'),$obj['typeId'],true);
        $this->showDatadicts ( array ('businessBelong' => 'QYZT'),$obj['businessBelongId'],true);

        // ͨ�������ֵ����Ӧ��������
//        $datadictDao = new model_system_datadict_datadict();
//        $businessInfo = $datadictDao->getDataDictList_d('QYZT');//ǩԼ���壨��˾����
//        $businessBelongId = strtoupper($obj['businessBelongId']);
//        $this->assign ( "businessBelongName", $businessInfo[$businessBelongId] );

        $this->assign ( "old_principalId", $obj['principalId'] );
		$this->view ( 'edit');
	}

   /**
	 * ��ת���鿴�������ñ�ҳ��
    */
    function c_toView() {
        $this->permCheck (); //��ȫУ��
        $obj = $this->service->get_d ( $_GET ['id'] );
        foreach ( $obj as $key => $val ) {
            $this->assign ( $key, $val );
        }

        // ͨ�������ֵ����Ӧ��������
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT');//ǩԼ���壨��˾����
        $businessBelongId = $obj['businessBelongId'];
        $this->assign ( "businessBelongName", $businessInfo[$businessBelongId] );

        $this->assign('status',$this->service->rtStampStatus_d($obj['status']));
        $this->view ( 'view' );
    }

    /**
     * ��ת���鿴���¹�����ʷ���ݱ�ҳ��
     * created by huanghaojin 2016-10-08
     */
    function c_toViewHistory() {
        $this->permCheck (); //��ȫУ��
        $this->assign('parentId',$_GET ['pid']);
        $this->view ( 'viewHistory' );
    }

    /**
     * ��ȡ��ʷ��¼��json����
     * created by huanghaojin 2016-10-08
     */
    function c_jsonHistory(){
        $service = $this->service;
        $rows = null;

        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->pageBySqlId('select_history');

        foreach($rows as $k => $v){
            $rows[$k]['startTime'] = empty($rows[$k]['startTime'])? "��" : date("Y-m-d", $rows[$k]['startTime']);
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
     * ͨ�������ֵ����Ӧ��������
     * created by huanghaojin 2016-10-09
     */
    function addNameField($row){
        $rows = $row;
        // ͨ�������ֵ����Ӧ��������
        $datadictDao = new model_system_datadict_datadict();
        $businessInfo = $datadictDao->getDataDictList_d('QYZT');//ǩԼ���壨��˾����
        $typeInfo = $datadictDao->getDataDictList_d('YZLB');//ӡ�����
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
     * ͨ��ajax���������Ƿ��Ѵ���
     */
    function c_ajaxChkStampName(){
        $stampName = isset($_POST['stampName'])? $_POST['stampName'] : '';
        $stampId = isset($_POST['stampId'])? $_POST['stampId'] : '';
        $stampName = util_jsonUtil::iconvUTF2GB($stampName);
        $backArr = array("result"=>'',"msg"=>'');
        if($stampName != ''){
            $chkResultArr = ($stampId != '')? $this->service->findAll(" stampName = '$stampName' and id <> '$stampId'") : $this->service->findAll(array("stampName" => $stampName));
            $backArr['result'] = ($chkResultArr && count($chkResultArr) > 0)? 'fail' : 'ok';
            $backArr['msg'] = ($chkResultArr && count($chkResultArr) > 0)? '�˸������Ѵ���!' : '';
        }else{
            $backArr['result'] = 'error';
            $backArr['msg'] = '�������������, ������!';
        }
        echo util_jsonUtil::encode($backArr);
    }
}
?>