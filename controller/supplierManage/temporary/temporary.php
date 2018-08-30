<?php


/**
 * ��Ӧ�̿��Ʋ���
 */
class controller_supplierManage_temporary_temporary extends controller_base_action {
	/*
	 * ���캯��
	 */

	function __construct() {
		$this->objName = "temporary";
		$this->objPath = "supplierManage_temporary";
		parent :: __construct();
	}

	/**
	 * ******************************��ͨAction����*****************************************
	 */

	/**
	 * @desription ��ת����Ӧ���б�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����02:18:04
	 */
	function c_toSupplierlist() {
		$this->display('list');
	}
	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		//������
		$objCode = generatorSerial();
		//ϵͳ���
		$sysCode = generatorSerial();
		$this->assign('objCode', $objCode);
		$this->assign('systemCode', $sysCode);

		//��ȡ�����ֵ��������
		$this->showDatadicts(array('KHBank'=>'KHBANK'));	//��������
		//��Ӧ�̵ĳ�������
	    $datadictDao = new model_system_datadict_datadict ();
	    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
		$stasseDao=new model_supplierManage_temporary_stasse();
	    $str = $stasseDao->add_s($datadictArr['lskpg']);
	    $this->show->assign("str",$str);
	    $this->assign('flag',$_GET['flag']);

		$this->display('add');
	}
	/**
	 * @desription ��ת���޸�ҳ��
	 * @param tags
	 * @date 2010-11-8 ����03:55:02
	 */
	function c_toEdit() {
		$rows = $this->service->get_d($_GET['id']);
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$parentCode = isset ($_GET['parentCode']) ? $_GET['parentCode'] : null;
		$this->assign('parentId', $parentId);
		$this->assign('parentCode', $parentCode);

		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}

		$this->display('edit');
	}

	/**
	 * @desription ע��ҳ���з�����һҳ--����ϵ�˷��ص���Ӧ�̵�ע�������Ϣҳ��
	 * @param tags
	 * @date 2010-11-20 ����03:46:38
	 */
	function c_toEdit1() {
		$rows = $this->service->get_d($_GET['id']);
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$parentCode = isset ($_GET['parentCode']) ? $_GET['parentCode'] : null;
		$this->assign('parentId', $parentId);
		$this->assign('parentCode', $parentCode);

		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->display('edit1');
	}

	/*
	 * ע�ṩӦ����Ϣ����
	 * @date 2010-9-20 ����02:06:22
	 */
	function c_addsupp() {
		$supp = $_POST[$this->objName];

		if ($_GET['id']) {
			$supp['ExaStatus'] = 'WQD';
		}
		foreach ($supp as $key => $val) {
			$this->assign($key, $val);
		}
		$objCode = $supp['objCode'];
		$id = $this->service->addsupp_d($supp, true);
		if ($id) {
			succ_show("?model=supplierManage_temporary_stcontact&action=tolinkmanlist&parentId=$id&parentCode=$objCode");
		}

	}

	/**ע�ṩӦ���µı��淽��
	*author can
	*2011-4-7
	*/
	function c_add(){
		$id=$this->service->add_d( $_POST[$this->objName]);
		if($id){
			if($_POST['flag']){
				msg('����ɹ�');
			}else{
				msgGo('����ɹ�');
			}
		}else{
			msgGo('����ʧ��');
		}
	}

	/**
	 * @desription �����޸ĵ���Ϣ
	 * @param tags
	 * @date 2010-11-16 ����09:01:12
	 */
	function c_edit() {
		$editInfo = $_POST[$this->objName];
		$uptnum = $this->service->editinfo_d($editInfo, true);
		if ($uptnum) {
			if (isset ($_GET['editType'])) {
				//��ת����һ����Ӧ����ϵ���б�ҳ��
				succ_show("?model=supplierManage_temporary_stcontact&action=tolinkmanlist&parentId=" . $editInfo['id'] . "&parentCode=" . $editInfo['busiCode']);
			} else {
				showmsg('�޸ĳɹ�');
			}
		} else {
			showmsg('�޸�ʧ��');
		}

	}

	/*
	 * #############################��ע��Ĺ�Ӧ�̵���ز���#############################
	 */

	/**
	 * @desription ��ת����Ӧ�̲鿴ҳ��
	 * @param tags
	 * @date 2010-11-10 ����04:26:34
	 */
	function c_toViewSupp() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//��ȫУ��
		$service = $this->service;
		$id = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$rows = $service->get_d($id);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$trainDao = new model_supplierManage_temporary_tempbankinfo() ;
		$this->assign('list',$trainDao->showViewBank($id,array('KHBank'=>'KHBANK')));
		$this->display('view');
	}

	function c_init() {
		$this->permCheck ();//��ȫУ��
		$service = $this->service;
		$id = isset ($_GET['id']) ? $_GET['id'] : null;

		$rows = $service->get_d($id);
		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('parentId', $rows['id']);
		$this->assign('parentCode', $rows['objCode']);
		$this->assign('skey_',$_GET['skey']);
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			//��һ���м�ҳ�棬����Tab��ǩ����ʾ��
			//�ô��Ƿ���Tab��Tab֮������Ĵ��ݡ�

			$this->show->display($this->objPath . '_' . 'stviewsuppliertab');
		} else {
			//��ת���༭Tab��ǩҳ��
			$this->show->display($this->objPath . '_' . 'steditsuppliertab');
		}

	}

	/**
	 * @desription Tab��ǩ��ת���鿴��ϵ�ˡ�
	 * @param tags
	 * @date 2010-11-12 ����02:01:33
	 */
	function c_toViewLinkMan() {
		$linkManDao = new model_supplierManage_temporary_stcontact();
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$rows = $linkManDao->getByid_d($parentId);

		if ($rows) {
			foreach ($rows as $key0 => $val0) {
				foreach ($val0 as $key => $val)
					$this->assign($key, $val);
			}
		}
		$this->show->display($this->objPath . '_' . 'stcontact-view');
	}

	/**
	 * @desription Tab��ǩ��ת���鿴ע����Ϣ
	 * @param tags
	 * @date 2010-11-12 ����03:17:15
	 */
	function c_toViewRegisterInfo() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//��ȫУ��
		$linkManDao = new model_supplierManage_temporary_stcontact();
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$rows = $linkManDao->get_d($parentId);
		$rows1 = $this->service->get_d($parentId);

		if ($rows) {
			foreach ($rows as $key => $val) {
				$this->assign($key, $val);
			}
		}

		if ($rows1) {
			foreach ($rows1 as $key => $val) {
				$this->assign($key, $val);
			}
		}
		$this->assign('list', $this->service->isApprovaled($parentId));
		$this->show->display($this->objPath . '_' . 'registerinfo-view');
	}

	/**
	 * @desription Tab��ǩ��ת���鿴��Ӧ��Ʒ
	 * @param tags
	 * @date 2010-11-12 ����03:53:31
	 */
	function c_toViewSuppGoods() {
		$service = $this->service;

		$productDao = new model_supplierManage_temporary_stproduct();

		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$parentCode = isset ($_GET['parentCode']) ? $_GET['parentCode'] : null;
		$this->assign('goodslist', $service->showGoods($parentId));
		$this->show->display($this->objPath . '_' . 'stproduct-view');
	}

	/**
	 * @desription ��ת��������Ϣ�ı༭ҳ��
	 * @param tags
	 * @date 2010-11-16 ����08:06:21
	 */
	function c_toEditSupp() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//��ȫУ��
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$rows = $this->service->get_d($parentId);
		$rows['file'] = $this->service->getFilesByObjId($rows['id']);

		foreach ($rows as $key => $val) {
			$this->assign($key, $val);
		}
			$trainDao = new model_supplierManage_temporary_tempbankinfo() ;
			$this->assign('list',$trainDao->showTrainEditList($parentId,array('KHBank'=>'KHBANK')));
		$this->showDatadicts ( array('KHBank'=>'KHBANK'));
		$this->display('edit');
	}

	/**
	 * @desription ��ת���༭��ϵ�˵�ҳ��
	 * @param tags
	 * @date 2010-11-16 ����08:11:50
	 */
	function c_toEditLinkMan() {
		$linkManDao = new model_supplierManage_temporary_stcontact();
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : null;
		$rows = $linkManDao->getByid_d($parentId);
		if ($rows) {
			foreach ($rows as $key1 => $val1) {
				foreach ($val1 as $key => $val) {
					$this->assign($key, $val);
				}

			}
		}
		$this->show->display($this->objPath . '_' . 'stcontact-edit');
	}

	/*
	 * #############################��ע��Ĺ�Ӧ�̵���ز���#############################
	 */

	/**
	 * ***********************************Ajax��JSON����****************************************
	 */

	/**
	 * @desription ajax�жϹ�Ӧ�������Ƿ��ظ�
	 * @param tags
	 * @date 2010-11-10 ����05:06:08
	 */
	function c_ajaxSuppName() {
		$service = $this->service;
		$suppName = isset ($_GET['suppName']) ? $_GET['suppName'] : false;
		$searchArr = array (
			"ajaxSuppName" => $suppName
		);
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$isRepeat = $service->isRepeat($searchArr, $id);
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/*********************************��������ز���**********************************************/
	/**
	 * ����ҳ��
	 */
	function c_rpToWork() {
		$this->show->display('common_mySuppAudit');
	}

	function c_rpMenu() {
		$this->show->display('common_mySuppAuditMenu');
	}

	/**
	 * @desription ������
	 * @param tags
	 * @date 2010-11-17 ����
	 */
	function c_rpApprovalNo() {
		$service = $this->service;
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $this->service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_examine');
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign(); //���÷�ҳ��ʾ
		$this->assign('suppNameSeach', $_GET['suppNameSeach']);
		$this->assign('list', $service->rpApprovalNo_s($rows));
		$this->display('my-ApprovalNo');
	}

	function c_rpApprovalYes() {
		$service = $this->service;
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr['examines'] = "";
		$service->searchArr['workFlowCode'] = $this->service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_examine');
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign(); //���÷�ҳ��ʾ
		$this->assign('suppNameSeach', $_GET['suppNameSeach']);
		$this->assign('list', $service->rpApprovalYes_s($rows));
		$this->display('my-ApprovalYes');
	}

	/*************************************************************************************/
	/**
	* @desription ��ת����ע��Ĺ�Ӧ���б�
	* @param tags
	* @date 2010-11-16 ����01:47:36
	*/
	function c_myloglist() {
		$this->display('myloglist');
	}
	/*
	 * �Ҳ�Ĺ�Ӧ���б����ݻ�ȡ
	 */
	function c_mylogpageJson() {
		$service = $this->service;
		$createId = isset ($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$this->searchVal('suppName');
		$this->searchVal('mainProduct');
		$rows = $this->service->myLogSupp($createId);
		$rows = $this->sconfig->md5Rows ( $rows );
		$service->asc = true;
		$service->sort = "createTime";
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/*
	 * ��ȡע���json����
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->asc = true;
		$service->sort = "createTime";
		$rows = $service->page_d();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ¼����Ӫ��
	 */
	function c_putInFormal() {
		if ($this->service->putInFormal($_GET['id'])) {
			msg("¼��ɹ�");
			echo 1;
		} else {
			msg("¼��ʧ��");
		}
		exit();
	}

	/**��ɾ����Ӧ��
	*author can
	*2011-4-7
	*/
	function c_delSupplier(){
		$condition=array('id'=>$_POST['id']);
		if($this->service->updateField($condition,'delFlag','1')){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}
}
?>
