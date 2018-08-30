<?php


/**
 * 供应商控制层类
 */
class controller_supplierManage_temporary_temporary extends controller_base_action {
	/*
	 * 构造函数
	 */

	function __construct() {
		$this->objName = "temporary";
		$this->objPath = "supplierManage_temporary";
		parent :: __construct();
	}

	/**
	 * ******************************普通Action方法*****************************************
	 */

	/**
	 * @desription 跳转到供应商列表页面
	 * @param tags
	 * @date 2010-11-8 下午02:18:04
	 */
	function c_toSupplierlist() {
		$this->display('list');
	}
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		//对象编号
		$objCode = generatorSerial();
		//系统编号
		$sysCode = generatorSerial();
		$this->assign('objCode', $objCode);
		$this->assign('systemCode', $sysCode);

		//获取数据字典里的数据
		$this->showDatadicts(array('KHBank'=>'KHBANK'));	//开户银行
		//供应商的初步评估
	    $datadictDao = new model_system_datadict_datadict ();
	    $datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
		$stasseDao=new model_supplierManage_temporary_stasse();
	    $str = $stasseDao->add_s($datadictArr['lskpg']);
	    $this->show->assign("str",$str);
	    $this->assign('flag',$_GET['flag']);

		$this->display('add');
	}
	/**
	 * @desription 跳转到修改页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
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
	 * @desription 注册页面中返回上一页--从联系人返回到供应商的注册基本信息页面
	 * @param tags
	 * @date 2010-11-20 下午03:46:38
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
	 * 注册供应商信息保存
	 * @date 2010-9-20 下午02:06:22
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

	/**注册供应商新的保存方法
	*author can
	*2011-4-7
	*/
	function c_add(){
		$id=$this->service->add_d( $_POST[$this->objName]);
		if($id){
			if($_POST['flag']){
				msg('保存成功');
			}else{
				msgGo('保存成功');
			}
		}else{
			msgGo('保存失败');
		}
	}

	/**
	 * @desription 保存修改的信息
	 * @param tags
	 * @date 2010-11-16 下午09:01:12
	 */
	function c_edit() {
		$editInfo = $_POST[$this->objName];
		$uptnum = $this->service->editinfo_d($editInfo, true);
		if ($uptnum) {
			if (isset ($_GET['editType'])) {
				//跳转到下一步供应商联系人列表页面
				succ_show("?model=supplierManage_temporary_stcontact&action=tolinkmanlist&parentId=" . $editInfo['id'] . "&parentCode=" . $editInfo['busiCode']);
			} else {
				showmsg('修改成功');
			}
		} else {
			showmsg('修改失败');
		}

	}

	/*
	 * #############################我注册的供应商的相关操作#############################
	 */

	/**
	 * @desription 跳转到供应商查看页面
	 * @param tags
	 * @date 2010-11-10 下午04:26:34
	 */
	function c_toViewSupp() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
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
		$this->permCheck ();//安全校验
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
			//加一个中间页面，用于Tab标签的显示。
			//好处是方面Tab与Tab之间参数的传递。

			$this->show->display($this->objPath . '_' . 'stviewsuppliertab');
		} else {
			//跳转到编辑Tab标签页面
			$this->show->display($this->objPath . '_' . 'steditsuppliertab');
		}

	}

	/**
	 * @desription Tab标签跳转。查看联系人。
	 * @param tags
	 * @date 2010-11-12 下午02:01:33
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
	 * @desription Tab标签跳转。查看注册信息
	 * @param tags
	 * @date 2010-11-12 下午03:17:15
	 */
	function c_toViewRegisterInfo() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
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
	 * @desription Tab标签跳转。查看供应产品
	 * @param tags
	 * @date 2010-11-12 下午03:53:31
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
	 * @desription 跳转到基本信息的编辑页面
	 * @param tags
	 * @date 2010-11-16 下午08:06:21
	 */
	function c_toEditSupp() {
		$this->permCheck ($_GET['parentId'],'supplierManage_temporary_temporary');//安全校验
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
	 * @desription 跳转到编辑联系人的页面
	 * @param tags
	 * @date 2010-11-16 下午08:11:50
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
	 * #############################我注册的供应商的相关操作#############################
	 */

	/**
	 * ***********************************Ajax、JSON方法****************************************
	 */

	/**
	 * @desription ajax判断供应商名称是否重复
	 * @param tags
	 * @date 2010-11-10 下午05:06:08
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

	/*********************************工作流相关部分**********************************************/
	/**
	 * 审批页面
	 */
	function c_rpToWork() {
		$this->show->display('common_mySuppAudit');
	}

	function c_rpMenu() {
		$this->show->display('common_mySuppAuditMenu');
	}

	/**
	 * @desription 待审批
	 * @param tags
	 * @date 2010-11-17 下午
	 */
	function c_rpApprovalNo() {
		$service = $this->service;
		$service->getParam($_GET); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $this->service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_examine');
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign(); //设置分页显示
		$this->assign('suppNameSeach', $_GET['suppNameSeach']);
		$this->assign('list', $service->rpApprovalNo_s($rows));
		$this->display('my-ApprovalNo');
	}

	function c_rpApprovalYes() {
		$service = $this->service;
		$service->getParam($_GET); //设置前台获取的参数信息
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 1;
		$service->searchArr['examines'] = "";
		$service->searchArr['workFlowCode'] = $this->service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId('sql_examine');
		$rows = $this->sconfig->md5Rows ( $rows );
		$this->pageShowAssign(); //设置分页显示
		$this->assign('suppNameSeach', $_GET['suppNameSeach']);
		$this->assign('list', $service->rpApprovalYes_s($rows));
		$this->display('my-ApprovalYes');
	}

	/*************************************************************************************/
	/**
	* @desription 跳转到我注册的供应商列表
	* @param tags
	* @date 2010-11-16 下午01:47:36
	*/
	function c_myloglist() {
		$this->display('myloglist');
	}
	/*
	 * 我册的供应商列表数据获取
	 */
	function c_mylogpageJson() {
		$service = $this->service;
		$createId = isset ($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : null;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 获取注册库json数据
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 * 录入运营库
	 */
	function c_putInFormal() {
		if ($this->service->putInFormal($_GET['id'])) {
			msg("录入成功");
			echo 1;
		} else {
			msg("录入失败");
		}
		exit();
	}

	/**假删除供应商
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
