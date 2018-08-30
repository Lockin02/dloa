<?php

/**
 * @author Administrator
 * @Date 2011��5��9�� 16:02:12
 * @version 1.0
 * @description:�����������Ʒ�嵥���Ʋ�
 */
class controller_projectmanagent_borrow_borrowequ extends controller_base_action {

	function __construct() {
		$this->objName = "borrowequ";
		$this->objPath = "projectmanagent_borrow";
		parent :: __construct();
	}

	/*
	 * ��ת�������������Ʒ�嵥
	 */
	function c_page() {
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * ��ͬ�����б�ӱ����ݻ�ȡ
	 */
	function c_pageJson() {
		if( $_POST['ifDeal'] ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$lockDao = new model_stock_lock_lock();
			$contractDao = new model_contract_contract_equ();
			$service = $this->service;
			$service->getParam($_POST);
			$service->searchArr['isDel'] = 0;
			$rows = $service->list_d();
			foreach ($rows as $key => $val) {
				$rows[$key]['lockNum'] = $lockDao->getEquStockLockNum($rows[$key]['id'], null, 'oa_borrow_borrow');
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($rows[$key]['productId'], 'outStockCode');
				$rows[$key]['toContractNum'] = $contractDao->getBorrowToContractNum($rows[$key]['id']);
			}
		}else{
			$rows=array();
		}
		$arr['collection'] = $rows;
		echo util_jsonUtil :: encode($arr);
	}
    /**
     * �������б�ӱ�
     */
    function c_listPageJson(){
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$lockDao = new model_stock_lock_lock ();
		$contractDao = new model_contract_contract_equ();
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->searchArr['isDel']=0;
        $service->searchArr['isTemp']=0;
		$rows=$service->list_d();
		foreach ( $rows as $key=>$val){
			$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,'oa_borrow_borrow' );
			$rows[$key]['exeNum'] =  $inventoryDao->getExeNumsByStockType( $rows[$key]['productId'],'outStockCode' );
			$rows[$key]['toContractNum'] =  $contractDao->getBorrowToContractNum( $rows[$key]['id']);
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr);
	}

	/**
	 * �������б�ӱ�
	 */
	function c_listJsonEqu(){
		$checkIdsStr = $_GET['checkIds'];
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$lockDao = new model_stock_lock_lock ();
		$contractDao = new model_contract_contract_equ();
		$service = $this->service;
		$service->getParam ( $_POST );
		$service->searchArr['checkIds'] = $checkIdsStr;
		$service->searchArr['isDel']=0;
        $service->searchArr['isTemp']=0;
		$costimitStr = "sql:  and c.executedNum-c.backNum  > 0 ";
		$service->searchArr['mySearchCondition'] = $costimitStr;
		$rows=$service->list_d();
		foreach ( $rows as $key=>$val){
			$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,'oa_borrow_borrow' );
			$rows[$key]['exeNum'] =  $inventoryDao->getExeNumsByStockType( $rows[$key]['productId'],'outStockCode' );
			$rows[$key]['toContractNum'] =  $contractDao->getBorrowToContractNum( $rows[$key]['id']);
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
        $linkDao = new model_projectmanagent_borrow_borrowequlink();

        // �������Ƶ����ϱ��,��linkId�ж�
        if(isset($_REQUEST['linkId']) && $_REQUEST['linkId'] != ''){
            $linkObj = $linkDao->get_d($_REQUEST['linkId']);
            if(isset($_REQUEST['temp']) && $_REQUEST['temp'] > 0){
                unset($_REQUEST['isTemp']);
            }else{
                unset($_REQUEST['temp']);
                $_REQUEST['isTemp'] = isset($linkObj['isTemp'])? $linkObj['isTemp'] : 0;
            }
        }

		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$conProductdao = new model_projectmanagent_borrow_product();
		foreach($rows as $k => $v){
          $productInfo = $conProductdao ->get_d($v['conProductId']);
          $rows[$k]['conProductName'] = $productInfo['conProductName'];
		}



		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJsonGroup() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->sort = "c.conProductId,SUBSTRING_INDEX(c.isCon,'_',1)*1  ,if(LOCATE('_',c.isCon),SUBSTRING_INDEX(c.isCon,'_',-1)*1,-1)";
        $service->asc = false;
        $rows = $service->list_d ();
        $conProductdao = new model_projectmanagent_borrow_product();
        foreach($rows as $k => $v){
            $productInfo = $conProductdao ->get_d($v['conProductId']);
            $rows[$k]['conProductName'] = $productInfo['conProductName'];
        }
        $rows = $service->filterRows_d($rows);
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
     * ��ȡ�����ò�Ʒ��ص�������Ϣ
     */
    function c_getRelativeEqu(){
        $service = $this->service;
        $borrowId = $_REQUEST['borrowId'];
        $isDel = $_REQUEST['isDel'];
        $conProId = $_REQUEST['conProductId'];
        $isTemp = $_REQUEST['isTemp'];
        $advCondition = isset($_REQUEST['advCondition'])? $_REQUEST['advCondition'] : '';
        $sql = "SELECT * FROM oa_borrow_equ WHERE borrowId='{$borrowId}' AND isDel = '{$isDel}' AND conProductId='{$conProId}' AND isTemp='{$isTemp}' {$advCondition};";
        $rows = $service->_db->getArray($sql);
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * �ж�ĳ����ͬ�Ƿ��в�Ʒ�嵥
	 */
	function c_getEquById() {
		$sql = "select count(*) as equNum from " . $this->service->tbl_name . " where borrowId=" . $_POST['id'] . " and isDel<>1";
		$equNum = $this->service->_db->getArray($sql);
		echo $equNum[0]['equNum'];
	}
	/**
	 * ������ת���ۻ�ȡ��������
	 */
	function c_toContractPageJson() {
		$contractDao = new model_contract_contract_equ();
		$service = $this->service;
		$service->getParam($_POST);
		$service->searchArr['isDel'] = 0;
		$rows = $service->list_d();
		foreach ($rows as $key => $val) {
			$rows[$key]['toContractNum'] = $contractDao->getBorrowToContractNum($rows[$key]['id']);
			$rows[$key]['number'] = $rows[$key]['number'] - $rows[$key]['toContractNum']-$rows[$key]['backNum'];
			$rows[$key]['money'] = $rows[$key]['number'] * $rows[$key]['price'];
			$rows[$key]['maxNum'] = $rows[$key]['number'];
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil :: encode($rows);
	}

	/***************************************����ȷ��   start************************************************/

	/**
	 * ��ȡ��Ʒ����(ע�⣺��ʱ����isTemp��ǰ̨���ݹ���)
	 */
	function c_getConEqu() {
//		echo "<pre>";
//		print_R($_REQUEST);
		$service = $this->service;
		$service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$contEqu = $service->list_d();
		if( is_array($contEqu)&&count($contEqu)>0 ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach( $contEqu as $key=>$val ){
				$exeNum = $inventoryDao->getExeNumsByStockType($val['productId'], 'outStockCode');
				if( empty($exeNum)||!isset($exeNum) ){
					$contEqu[$key]['exeNum'] = '0';
				}else{
					$contEqu[$key]['exeNum'] = $exeNum;
				}
			}
		}
		echo util_jsonUtil :: encode($contEqu);
	}

	function c_getChangeConEqu() {
        $service = $this->service;
        $linkDao = new model_projectmanagent_borrow_borrowequlink();

        // �������Ƶ����ϱ��,��linkId�ж�
        if(isset($_REQUEST['linkId']) && $_REQUEST['linkId'] != ''){
            $linkObj = $linkDao->get_d($_REQUEST['linkId']);
            if(!isset($_REQUEST['temp'])){
                $_REQUEST['isTemp'] = isset($linkObj['isTemp'])? $linkObj['isTemp'] : 0;
            }else{
                unset($_REQUEST['isTemp']);
            }
        }

		$service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$contEqu = $service->list_d('select_all');
		if( is_array($contEqu)&&count($contEqu)>0 ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach( $contEqu as $key=>$val ){
				$exeNum = $inventoryDao->getExeNumsByStockType($val['productId'], 'outStockCode');
				if( empty($exeNum)||!isset($exeNum) ){
					$contEqu[$key]['exeNum'] = '0';
				}else{
					$contEqu[$key]['exeNum'] = $exeNum;
				}
			}
		}
		echo util_jsonUtil :: encode($contEqu);
	}

	/**
	 * ��ȡ��Ʒ�µ�������Ϣ
	 */
	function c_getProductEqu() {
		$id = $_POST['conProductId'];
		$service = $this->service;
		$equArr = $service->getProductEqu_d($id);
		if( is_array($equArr)&& count($equArr)>0 ){
			foreach( $equArr as $key=>$val ){
				$equArr[$key]['productModel'] = $val['pattern'];
				if( isset($_POST['number']) ){
					$equArr[$key]['number'] = $val['number']*($_POST['number']*1);
				}
			}
		}
		$equArr = $this->sconfig->md5Rows($equArr);
		echo util_jsonUtil :: encode($equArr);
	}

	/**
	 * ��ȡĳ�������嵥�������Ϣ
	 * add by zengzx
	 */
	function c_getEquByParentEquId() {
		$equs = $this->service->getEquByParentEquId_d($_POST['parentEquId']);
//		echo "<pre>";
//		print_R($equs);
		echo util_jsonUtil :: encode($equs);
	}
	/**
		 * ���ϴ����� ����
		 */
	function c_toEquAdd() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_borrow_borrow();
		$obj = $contDao->getBorrowInfo($_GET['id']);
		$obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$singleArr = $contDao->getSingleCodeURL($obj);
		$obj['SingleType'] = $singleArr['SingleType'];
		$obj['singleCode'] = $singleArr['singleCode'];
		$products = $this->service->showItemView($obj['product']);
		$this->assign('docType', 'oa_borrow_borrow');
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

        if(isset($obj['borrowequ'])){
            $linkId = "";
            foreach ($obj['borrowequ'] as $k => $equ){
                if(isset($equ['linkId']) && !empty($equ['linkId']) && isset($equ['isTemp']) && $equ['isTemp'] == 0){
                    $linkId = $equ['linkId'];
                }
            }
            $this->assign("linkId", $linkId);
        }
		$this->view('add');
	}

	/**
	 * ���ϴ����� ���
	 */
	function c_toEquChange() {

		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_borrow_borrow();
		$linkDao = new model_projectmanagent_borrow_borrowequlink();
		$linkObj = $linkDao->get_d($_GET['linkId']);
		$obj = $contDao->getBorrowInfoWithTemp($_GET['id']);
		$obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$singleArr = $contDao->getSingleCodeURL($obj);
		$obj['SingleType'] = $singleArr['SingleType'];
		$obj['singleCode'] = $singleArr['singleCode'];
		$this->assign('ExaDTOne', $linkObj['ExaDTOne']);
		$this->assign("products", $products);
		$this->assign('docType', 'oa_borrow_borrow');
		$this->assign('fromWho', $_GET['fromWho']);
//        echo "<pre>";print_r($obj);exit();
        if(isset($obj['borrowequ'])){
            $linkId = "";
            foreach ($obj['borrowequ'] as $k => $equ){
                if(isset($equ['linkId']) && !empty($equ['linkId']) && isset($equ['isTemp']) && $equ['isTemp'] == 0 && empty($linkId)){
                    $linkId = $equ['linkId'];
                }
            }
            $this->assign("linkId", $linkId);
        }

		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('change');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquEdit() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_projectmanagent_borrow_borrow();
		$obj = $contDao->getBorrowInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		$this->assign('docType', 'oa_borrow_borrow');
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

        if(isset($obj['borrowequ'])){
            $linkId = "";
            foreach ($obj['borrowequ'] as $k => $equ){
                if(isset($equ['linkId']) && !empty($equ['linkId']) && isset($equ['isTemp']) && $equ['isTemp'] == 0){
                    $linkId = $equ['linkId'];
                }
            }
            $this->assign("linkId", $linkId);
        }

		$this->view('edit');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquView() {
		$this->permCheck(); //��ȫУ��
		$linkDao = new model_projectmanagent_borrow_borrowequlink();
		$link = $linkDao->get_d($_GET['linkId']);
		$contDao = new model_projectmanagent_borrow_borrow();
		$obj = $contDao->getBorrowInfoWithTemp($link['borrowId']);
		$obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $link['borrowId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$singleArr = $contDao->getSingleCodeURL($obj);
		$obj['SingleType'] = $singleArr['SingleType'];
		$obj['singleCode'] = $singleArr['singleCode'];
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("linkId", $link['id']);
		if(!empty($_GET['changeView'])){//����鿴��־
			$this->assign("changeView", $_GET['changeView']);
		}else{
			$this->assign("changeView", '');
		}
		if(!empty($_GET['isShowDel'])){//�Ƿ���ʾɾ������
			$this->assign("isShowDel", $_GET['isShowDel']);
		}else{
			$this->assign("isShowDel", 'true');
		}
		$this->assign('docType', 'oa_borrow_borrow');
		$this->assign("isTemp", $link['isTemp']);
		$this->assign("originalId", $link['originalId']);
		$this->view('view');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquChangeView() {
		$this->permCheck(); //��ȫУ��
		$linkDao = new model_projectmanagent_borrow_borrowequlink();
		$link = $linkDao->get_d($_GET['linkId']);
		$contDao = new model_projectmanagent_borrow_borrow();
		$obj = $contDao->getBorrowInfoWithTemp($link['borrowId']);
		$obj['Code'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $link['borrowId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$singleArr = $contDao->getSingleCodeURL($obj);
		$obj['SingleType'] = $singleArr['SingleType'];
		$obj['singleCode'] = $singleArr['singleCode'];
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("linkId", $link['id']);
		if(!empty($_GET['changeView'])){//����鿴��־
			$this->assign("changeView", $_GET['changeView']);
		}else{
			$this->assign("changeView", '');
		}
		if(!empty($_GET['isShowDel'])){//�Ƿ���ʾɾ������
			$this->assign("isShowDel", $_GET['isShowDel']);
		}else{
			$this->assign("isShowDel", 'true');
		}
		$this->assign('docType', 'oa_borrow_borrow');
		$this->assign("isTemp", $link['isTemp']);
		$this->assign("originalId", $link['originalId']);
		$this->view('changeview');
	}

	/**
	 * ����ȷ������
	 */
	function c_equAdd($isEditInfo = true) {
		$this->permCheck(); //��ȫУ��
		$object = $_POST['borrow'];
        $id = $this->service->equAddNew_d($object,$_GET['act']);
        if ($id) {
            $borrowDao = new model_projectmanagent_borrow_borrow();
            $obj = $borrowDao->get_d($id);
            if( $_GET['act'] == "audit" ){

                // ������Ҫ����Աȷ�ϵı�ʾ
                $borrowDao->updateById(array("id"=>$id,"dealStatus"=>1,"needSalesConfirm"=>"1","salesConfirmId"=>$id));

                if ($obj['limits'] == 'Ա��') {
                    if($obj['timeType'] == "���ڽ���"){
                        $rtl = $borrowDao->shortBorrowSub($id);
                        if($rtl){
                            msg('ȷ�ϳɹ�,�˽�����ΪԱ������,������ͨ����');
                        }else{
                            msg('�ύʧ��, �����ԣ�');
                        }
                    }else{
                        // succ_show('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId=' . $obj['id']);
                    }
                } else {
                    $this->service->sendMailAfterEquConfirm($object,"�ύ");
                    msg('ȷ�ϳɹ�, �ȴ�����ȷ�Ϸ������ϣ�');
                    // succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $obj['id']);
                }
            }else{
                msg('����ɹ���');
            }
        } else {
            msg('�ύʧ�ܣ�');
        }

//		if( $_GET['act'] == "audit" ){
//			$id = $this->service->equAdd_d($object,true);
//		}else{
//			$id = $this->service->equAdd_d($object);
//		}
//		if ($id && $_GET['act'] == "audit") {
//			msg('�ύ�ɹ���');
//		} else{
//			if ($id) {
//				msg('����ɹ���');
//			} else {
//				msg('����ʧ�ܣ�');
//			}
//		}
	}

	/**
	 * �޸�����
	 */
	function c_equEdit($isEditInfo = false) {
	    $this->permCheck (); //��ȫУ��
		$object = $_POST['borrow'];
        $id = $this->service->equEditNew_d($object,$_GET['act']);
        if ($id) {
            $borrowDao = new model_projectmanagent_borrow_borrow();
            $obj = $borrowDao->get_d($id);
            if( $_GET['act'] == "audit" ){
                if ($obj['limits'] == 'Ա��') {
                    //succ_show('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId=' . $obj['id']);
                } else {
                    // ������Ҫ����Աȷ�ϵı�ʾ
                    $borrowDao->updateById(array("id"=>$id,"dealStatus"=>1,"needSalesConfirm"=>"1","salesConfirmId"=>$obj['id']));
                    $this->service->sendMailAfterEquConfirm($object,"�ύ");
                    msg('ȷ�ϳɹ�, �ȴ�����ȷ�Ϸ������ϣ�');
                    // succ_show('controller/projectmanagent/borrow/ewf_index.php?actTo=ewfSelect&billId=' . $obj['id']);
                }
            }else{
                msg('����ɹ���');
            }
        } else {
            msg('�ύʧ�ܣ�');
        }
//		if( $_GET['act']== "audit" ){
//			$flag = $this->service->equEdit_d($object,true);
//		}else{
//			$flag = $this->service->equEdit_d($object);
//
//		}
//		if ($flag && $_GET['act'] == "audit") {
//			msg('�ύ�ɹ���');
//		} else{
//			if ($flag) {
//				msg('�༭�ɹ���');
//			} else {
//				msg('�༭ʧ�ܣ�');
//			}
//		}
	}

	/**
	 * �������
	 */
	function c_equChange() {
		$object = $_POST['borrow'];
        $contDao = new model_projectmanagent_borrow_borrow();
		if ($_POST['fromWho'] == "manager") {
			$id = $this->service->equChangeFromManager_d($object);
			if ($id) {
                $contDao->updateById(array("id"=>$object['id'],"dealStatus"=>1,"needSalesConfirm"=>"3","salesConfirmId"=>$id));
                $contDao->_db->query("update oa_borrow_changlog set ExaStatus = '��������ȷ��' where objType = 'borrowequ' and tempId='{$id}';");
                $this->service->sendMailAfterEquConfirm($object,"���");
                msg('ȷ�ϳɹ�, �ȴ�����ȷ�Ϸ������ϣ�');
                // succ_show('controller/projectmanagent/borrow/ewf_borrowequ_manager.php?actTo=ewfSelect&billId=' . $id);
			} else {
//				msg('���ʧ�ܣ�');
			}
		} else {
//			$id = $this->service->equChange_d($object);
            $id = $this->service->equChangeNew_d($object);

            // ������Ҫ����Աȷ�ϵı�ʾ
            $contract = $contDao->get_d($id);

            $contDao->updateById(array("id"=>$id,"dealStatus"=>1));
            $contDao->updateById(array("id"=>$contract['originalId'],"dealStatus"=>1,"needSalesConfirm"=>"2","salesConfirmId"=>$id));

            if ($contract['limits'] == 'Ա��') {
                // succ_show('controller/projectmanagent/borrow/ewf_prochange_index.php?actTo=ewfSelect&billId=' . $id);
            } else {
                $this->service->sendMailAfterEquConfirm($object,"���");
                msg('ȷ�ϳɹ�, �ȴ�����ȷ�Ϸ������ϣ�');
                // succ_show('controller/projectmanagent/borrow/ewf_change_index.php?actTo=ewfSelect&billId=' . $id);
            }
		}
	}

	/**
	 * ��ת���鿴����ȷ��tab
	 */
	function c_toViewTab() {
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('id', $_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}
	/***************************************����ȷ��   end************************************************/
	/**
	 * ����ȷ�� �����������
	 */
	function c_getNoProductEqu(){
		$contractId = $_POST['borrowId'];
        $linkDao = new model_projectmanagent_borrow_borrowequlink();

        // �������Ƶ����ϱ��,��linkId�ж�
        if(isset($_REQUEST['linkId']) && $_REQUEST['linkId'] != ''){
            $linkObj = $linkDao->get_d($_REQUEST['linkId']);
            $_REQUEST['isTemp'] = isset($linkObj['isTemp'])? $linkObj['isTemp'] : 0;
        }

		$this->service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$rows = $this->service->getNoProductEqu_d($contractId);
		if( is_array($rows)&&count($rows)>0 ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach( $rows as $key=>$val ){
				$exeNum = $inventoryDao->getExeNumsByStockType($val['productId'], 'outStockCode');
				if( empty($exeNum)||!isset($exeNum) ){
					$rows[$key]['exeNum'] = '0';
				}else{
					$rows[$key]['exeNum'] = $exeNum;
				}
			}
		}
		echo util_jsonUtil::encode ( $rows );
	}


	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonReturn() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->searchArr['executedNumSql'] = "sql: and (c.executedNum > '0' && c.executedNum <> c.applyBackNum && c.executedNum <> c.backNum)";
		$rows = $service->list_d();
		$productIdArr = array();
		foreach ($rows as $key => $val) {
			$rows[$key]['maxNum'] = $val['executedNum'] - $val['applyBackNum'];
			$productIdArr[] = $val['productId'];
		}

		// ƥ�����ϵ����ò��Զ�����
		if(!empty($productIdArr)){
			$rows = $service->settingDeal_d($rows, $productIdArr);
		}

		echo util_jsonUtil::encode($rows);
	}

	/**
	 * �������豸�嵥PageJson
	 */
	function c_borrowEquPageJson() {
		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['pageUser'] = $_SESSION['USER_ID'];
		$service->groupBy = "c.productId";
		$rows = $service->pageBySqlId('select_equlist');

		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �����÷������ϱ���ص�
	 */
	function c_dealAfterChangeAudit() {
		$this->service->dealAfterChangeAudit_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

    /**
     * ����ǰ̨�����������Ϣͳ�Ƴɱ�����
     */
	function c_curEqusEstimate(){
	    $equArr = $_POST['equs'];
        $equCost = $this->service->curEquEstimates($equArr);
        echo util_jsonUtil::encode($equCost);
    }
}