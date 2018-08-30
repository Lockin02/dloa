<?php
/**
 * @author Show
 * @Date 2012��7��29�� 16:32:12
 * @version 1.0
 * @description:������ϸ���Ʋ�
 */
class controller_engineering_cost_esmcostdetail extends controller_base_action {

	function __construct() {
		$this->objName = "esmcostdetail";
		$this->objPath = "engineering_cost";
		parent :: __construct();
	}

    /******************* �б��� *****************************/

	/**
	 * ��ת��������ϸ�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * �б���ʾ���������ϸ
	 */
	function c_feeListJson(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.createId,c.costTypeId having costMoney <> 0';
		$rows = $service->list_d ('select_fee2');

//		print_r($rows);
		if(!empty($rows)){
			$newArr = array();
			$cacheArr = array();
			foreach($rows as $key => $val){
				if(!isset($cacheArr[$val['createId']])){
					//������������
					$cacheArr[$val['createId']] = count($newArr);

					//����������Ա��Ϣ
					$newArr[$cacheArr[$val['createId']]]['createId'] = $val['createId'];
					$newArr[$cacheArr[$val['createId']]]['createName'] = $val['createName'];
				}
				$newArr[$cacheArr[$val['createId']]][$val['costTypeId']] = $val['costMoney'];
			}
		}

		echo util_jsonUtil::encode ( $newArr );
	}

	/**
	 * ��ȡ���ñ�ͷ
	 */
	function c_getFeeTitle(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = 'c.costTypeId';
		$service->asc = false;
		$rows = $service->list_d ('select_feetitle');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/******************* ��Ŀ���������˲���  *********************/
	/**
	 * ��Ŀ����������
	 */
	function c_projectManageList(){
	    $weekDao = new model_engineering_baseinfo_week();
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		$showType = empty($_GET['showType']) ? 1 : $_GET['showType'];

		//��ȡ��ǰ��
        $weekTimes = $weekDao->getWeekNoByDayTimes();
		$this->assign('weekTimes',$weekTimes);

		//����������
		if($_GET['beginDate']){
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		}else{
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
        $this->assign('beginDate',$beginDate);
        $this->assign('endDate',$endDate);

		//�б�������Ⱦ
		$pageInfo = $this->service->projectManage_d($projectId,$showType,$beginDate,$endDate);
		$this->assignFunc($pageInfo);

        $this->assign('projectId',$projectId);

        //�Ƿ��ǲ鿴ҳ��
        $this->assign('isView',0);

		$this->display('listmanage');
	}

	/**
	 * ��Ŀ����������
	 */
	function c_ajaxManageList(){
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		$showType = empty($_POST['showType']) ? 1 : $_POST['showType'];

		//����������
		if($_POST['beginDate']){
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		}else{
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}

		//�б�������Ⱦ
		$pageInfo = $this->service->projectManage_d($projectId,$showType,$beginDate,$endDate);
		$pageInfo['beginDate'] = $beginDate;
		$pageInfo['endDate'] = $endDate;

		echo util_jsonUtil::encode($pageInfo);
	}

	/**
	 * ��Ŀ���������� - ��������
	 */
	function c_ajaxManageListYW(){
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		$weekTimes = $_POST['weekTimes'] ? $_POST['weekTimes'] : die();
		$showType = empty($_POST['showType']) ? 1 : $_POST['showType'];

		//����������
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekTimes);
        $weekBEArr = (!empty($weekInfo))? $weekDao->getWeekRange($weekInfo['week'],$weekInfo['year']) : array();
//		print_r($weekBEArr);exit();
		$beginDate = (!empty($weekInfo) && !empty($weekBEArr))? $weekBEArr['beginDate'] : '';
		$endDate = (!empty($weekInfo) && !empty($weekBEArr))? $weekBEArr['endDate'] : '';

		//�б�������Ⱦ
		$pageInfo = $this->service->projectManage_d($projectId,$showType,$beginDate,$endDate);
		$pageInfo['beginDate'] = $beginDate;
		$pageInfo['endDate'] = $endDate;
		$pageInfo['weekTimes'] = $weekInfo['weekTimes'];

		echo util_jsonUtil::encode($pageInfo);
	}

	/**
	 * ��Ŀ��Ա����ҳ��鿴�ķ�������
	 */
	function c_pageJsonCostMoney() {
		$service = $this->service;

		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$service->groupBy = 'c.executionDate';
		$rows = $service->page_d ('count_projectdate');
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
	 * ��Ŀ�鿴������Ϣ
	 */
	function c_projectViewList(){
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		$showType = empty($_GET['showType']) ? 1 : $_GET['showType'];

		//��ȡ��ǰ��
        $weekDao = new model_engineering_baseinfo_week();
        $weekTimes = $weekDao->getWeekNoByDayTimes();
		$this->assign('weekTimes',$weekTimes);

		//����������
		if($_GET['beginDate']){
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		}else{
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
        $this->assign('beginDate',$beginDate);
        $this->assign('endDate',$endDate);

		//�б�������Ⱦ
		$pageInfo = $this->service->projectView_d($projectId,$showType,$beginDate,$endDate);
		$this->assignFunc($pageInfo);

        $this->assign('projectId',$projectId);

        //�Ƿ��ǲ鿴ҳ��
        $this->assign('isView',1);

		$this->display('listmanage');
	}

	/**
	 * ��ȡ����δ��˷��õ��� (��ǰ��һ��)
	 */
	function c_getUnconfirmWeek(){
		$projectId = $_POST['projectId'];
		if(empty($projectId)){
			echo -1;
		}
		$weekTimes = $this->service->getUnconfirmWeek_d($projectId);
		if($weekTimes){
			echo $weekTimes;
		}else{
			echo 0;
		}
		exit();
	}

    /******************* ��ɾ�Ĳ� *****************************/
	/**
	 * ��ת������������ϸҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

    /**
     * ��־������������Ϣ
     */
    function c_toAddInWorklog(){
        $rs = $this->service->checkConfig_d();
        if($rs != 1){
            echo $rs;
            die();
        }

        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //��ȡģ����Ϣ
        $templateArr = $this ->service->initTemplateAdd_d($worklogObj);

        $this->assign('templateName',$templateArr['templateName']);
        $this->assign('detail',$templateArr['str']);

    	$this->view('addinworklog');
    }

    /**
     * ��־������������Ϣ
     */
    function c_addInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->addBatch_d($object);
        if($countMoney){
           msg('����ɹ�');
        }else{
           msg('����ʧ��');
        }
    }

    /**
     * ��־������������Ϣ
     */
    function c_toEditInWorklog(){
        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //��ȡģ����Ϣ
        $templateArr = $this ->service->initTemplateEdit_d($worklogObj);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);

        $this->view('editinworklog');
    }

    /**
     * �༭����
     */
    function c_editInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->editBatch_d($object);
        if($countMoney){
           msg('����ɹ�');
        }else{
           msg('����ʧ��');
        }
    }

	/**
	 * ��ת���༭������ϸҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴������ϸҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

    /**
     * ��־������������Ϣ
     */
    function c_toViewInWorklog(){
        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //��ȡģ����Ϣ
        $templateArr = $this ->service->initTemplateView_d($worklogObj);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);
        $this->assign('invoiceNumber',$templateArr['invoiceNumber']);
        $this->assign('invoiceMoney',$templateArr['invoiceMoney']);

        $this->view('viewinworklog');
    }

	/**
	 * ȷ�Ϸ��ù���
	 */
	function c_toConfirm(){
        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //��ȡģ����Ϣ
        $templateArr = $this ->service->initTemplateConfirm_d($worklogObj);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);
        $this->assign('invoiceNumber',$templateArr['invoiceNumber']);
        $this->assign('invoiceMoney',$templateArr['invoiceMoney']);

        $this->view('confirm');
	}

	/**
	 * ȷ�Ϸ���
	 */
	function c_confirm(){
        $object = $_POST[$this->objName];
        $rs = $this->service->confirm_d($object);
        if($rs){
           msgRf('ȷ�ϳɹ�');
        }else{
           msgRf('ȷ��ʧ��');
        }
	}

	/**
	 * ���±༭ - ��غ�
	 */
	function c_toReeditInWorklog(){
        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        //��ȡģ����Ϣ
        $templateArr = $this ->service->initTemplateEdit_d($worklogObj,true);

        $this->assign('detail',$templateArr['str']);
        $this->assign('countMoney',$templateArr['countMoney']);

        $this->view('reeditinworklog');
	}

    /**
     * �༭����
     */
    function c_reeditInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->reeditBatch_d($object);
        if($countMoney){
           msg('����ɹ�');
        }else{
//           msg('����ʧ��');
        }
    }

	/********************* ����¼�����ģ�岿�� *********************/
    /**
     * ���ñ���ģ����Ⱦ
     */
    function c_initTempAdd(){
		$modelType = $_POST['modelType'];
		if($modelType){
			$modelStr =  $this->service->initTempAdd_d($modelType);
			echo util_jsonUtil::iconvGB2UTF ( $modelStr['str'] );
		}else{
			echo "";
		}
		exit();
    }

	/******************** Ʊ�������� ***************************/
	/**
	 * Ʊ�������������б�
	 */
	function c_manageExpenseList(){
		//��Ⱦ��������Ϣ
		$expenseId = $_GET['expenseId'];
		$expenseObj = $this->service->getExpenseInfo_d($expenseId);
		$this->assignFunc($expenseObj);

		//���ر�������Ӧ��־��ϸ
		$costdetail = $this->service->getCostdetail_d($expenseObj['esmCostdetailId']);
		$this->assignFunc($this->service->initCostdetail_d($costdetail));

		$this->view('listmanageexpense');
	}
	/**
	 * ��ת����ϸ���ñ���
	 */
	function c_costDetail(){
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		$this->assign('projectId',$projectId);
	
		$this->view('costDetai');
	}
	/**
	 * ��ϸ���ñ���
	 */
	function c_listHtml() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		$status = $_POST['status'] ? $_POST['status'] : die();
		//�б�������Ⱦ
		$listInfo = $this->service->listHtml_d($projectId,$status);

		echo util_jsonUtil::iconvGB2UTF($listInfo);
	}
}