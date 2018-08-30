<?php

/**
 * 到款控制层类
 */
class controller_finance_income_income extends controller_base_action
{

	function __construct() {
		$this->objName = "income";
		$this->objPath = "finance_income";
		parent::__construct();
	}

	/**
	 * 重写page
	 */
	function c_page() {
		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);
		$this->display($thisObjCode . '-list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJsonList() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d();

		if (!empty($rows)) {
			//数据加入安全码
			$rows = $this->sconfig->md5Rows($rows);

			//单页小计加载
			$rows = $service->pageCount_d($rows);

			//总计栏加载
			$objArr = $service->listBySqlId('count_all');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['incomeNo'] = '合计';
				$rsArr['id'] = 'noId';
				$rows[] = $rsArr;
			}
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 新增到款页面
	 */
	function c_toAdd() {
		//设置数据字典
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'));
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'));
		$this->assign('incomeDate', day_date);

		//获取默认发送人
		$mailUser = $this->service->getMailUser_d('incomeMail');
		$this->assign('sendName', $mailUser['ccUserName']);
		$this->assign('sendUserId', $mailUser['ccUserId']);

		//获取归属公司名称
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		// 获取默认币别
		$this->assign('currency', '人民币');
		$this->assign('rate', 1);

		//策略调用新增页面
		$this->assign('formType', $_GET['formType']);
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);
		$this->display($thisObjCode . '-add');
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		//策略调用新增页面
		$thisClass = $this->service->getClass($object['formType']);
		if ($this->service->add_d($object, new $thisClass())) {
			msgRf('添加成功！', '?model=finance_income_income&action=toAdd&formType=' . $object['formType']);
		}
	}

	/**
	 * 新增其他合同到款页面
	 */
	function c_toAddOther() {
		//设置数据字典
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'));
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'));
		$this->assign('incomeDate', day_date);

		//获取默认发送人
		$mailUser = $this->service->getMailUser_d('incomeMail');
		$this->assign('sendName', $mailUser['ccUserName']);
		$this->assign('sendUserId', $mailUser['ccUserId']);

		//获取归属公司名称
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		$this->display('income-other-add');
	}

	/**
	 * 新增其它合同到款
	 */
	function c_addOther() {
		if ($this->service->addOther_d($_POST[$this->objName])) {
			msgRf('添加成功！', '?model=finance_income_income&action=toAddOther');
		} else {
			msgRf('添加失败！');
		}
	}

	/**
	 * 下推生成单据
	 */
	function c_addByPush() {
		//URL权限控制
		$this->permCheck();

		//获取主从表数据
		$income = $this->service->getInfoAndDetail_d($_GET ['id']);
		$this->assignFunc($income);

		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);

		$this->assign('thisFormType', $_GET['formType']);
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'), $income['incomeType']);
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'), $income['sectionType']);
		$this->display($thisObjCode . '-addbypush');
	}

	/**
	 * 初始到款页面
	 */
	function c_init() {
		//URL权限控制
		$this->permCheck();
		$income = $this->service->get_d($_GET ['id']);
		$this->assignFunc($income);

		//策略调用新增页面
		$thisObjCode = $this->service->getBusinessCode($income['formType']);
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'), $income['incomeType']);
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'), $income['sectionType']);
		$this->display($thisObjCode . '-edit');
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		if ($this->service->edit_d($_POST [$this->objName])) {
			msgRf('编辑成功！');
		}
	}

	/**
	 * 显示分配到款
	 */
	function c_toAllot() {
		//URL权限控制
		$this->permCheck();
		$perm = isset($_GET['perm']) ? $_GET['perm'] : null;

		//获取到款单以及分配信息
		$income = $this->service->getInfoAndDetail_d($_GET ['id']);

		// 是否转换
		$income['isAdjust'] = $income['isAdjust'] ? '是' : '否';

		//返回对象编码
		$thisObjCode = $this->service->getBusinessCode($income['formType']);
		$this->assignFunc($income);
		if ($perm == 'view') {
			$this->assign('incomeType', $this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionType', $this->getDataNameByCode($income['sectionType']));
			$this->display($thisObjCode . '-viewallot');
		} else {
			$this->assign('incomeTypeCN', $this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionTypeCN', $this->getDataNameByCode($income['sectionType']));
			$this->showDatadicts(array('incomeTypeList' => 'DKFS'), $income['incomeType']);
			$this->showDatadicts(array('sectionTypeList' => 'DKLX'), $income['sectionType']);
			//设置日期
			$this->assign('thisDate', day_date);
			$this->display($thisObjCode . '-editallot');
		}
	}

	/**
	 * 到款分配
	 */
	function c_allot() {
		if ($this->service->allot_d($_POST[$this->objName])) {
			msgRf('分配成功');
		} else {
			msgRf('分配失败');
		}
	}

	/**
	 * 到款分配列表
	 */
	function c_allotList() {
		$this->display('allotlist');
	}

	/**
	 * 获取分页数据转成Json--到款分配页面
	 */
	function c_allotPageJson() {
		$service = $this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$rows = $service->pageBySqlId('select_incomeAllot');
		//URL过滤
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 到款管理列表
	 */
	function c_manageList() {
		$this->display('managelist');
	}

	/**
	 * 获取分页数据转成Json--到款分配页面
	 */
	function c_manageJson() {
		$service = $this->service;
		$service->getParam($_POST);

		$service->asc = true;
		$rows = $service->pageBySqlId('select_income');
		//URL过滤
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 高级搜索
	 */
	function c_toSearch() {
		$year = date("Y");
		$yearStr = "";
		for ($i = $year; $i >= 2005; $i--) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr', $yearStr);
		$this->view('search');
	}

	/**
	 * 修改备注页面
	 */
	function c_toEditRemark() {
		//URL权限控制
		$this->permCheck();
		$income = $this->service->get_d($_GET ['id']);
		$this->assignFunc($income);
		$this->display('editremark');
	}

	/**
	 * 修改对象
	 */
	function c_editMsg() {
		if ($this->service->editEasy_d($_POST [$this->objName])) {
			msg('编辑成功！');
		}
	}

	/**
	 * 选择列表 - 用于选择到款单
	 */
	function c_selectPage() {
		$this->assign('objId', $_GET['objId']);
		$this->assign('objType', $_GET['objType']);
		$this->display('selectlist');
	}

	/**
	 * 选择列表 - 数据源
	 */
	function c_selectPageJson() {
		$service = $this->service;
		$service->getParam($_POST);

		$service->asc = true;
		$rows = $service->pageBySqlId('select_detail');
		//URL过滤
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/************************ excel导入部分*****************************/

	/**
	 *跳转到excel上传页面
	 */
	function c_toExcel() {
		$this->display('excel');
	}

	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		$resultArr = $this->service->addExecelData_d($_POST['isCheck']);
		$title = '到款信息导入结果列表';
		$thead = array('数据信息', '导入结果');
		echo util_excelUtil::showResult($resultArr, $title, $thead);
	}

	/**
	 * excel导出
	 */
	function c_toExcOut() {
		$service = $this->service;
		$service->getParam($_GET); //设置前台获取的参数信息
		$service->sort = 'c.incomeDate';
		$service->asc = false;
		$rows = $service->list_d('select_excelout');

		// 数据附加
		$contractDao = new model_contract_contract_contract();
		$contracts = $contractDao->findAll(null, null, 'id,areaName');
		if ($contracts) {
			$areaMap = array();
			foreach ($contracts as $v) {
				$areaMap[$v['id']] = $v['areaName'];
			}

			foreach ($rows as $k => $v) {
				if ($v['objType'] == 'KPRK-12') {
					$rows[$k]['areaName'] = $areaMap[$v['objId']];
				}
			}
		}
		return model_finance_common_financeExcelUtil::exportIncome($rows);
	}
}