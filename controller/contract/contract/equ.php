<?php
/**
 * @author zengzx
 * @Date 2012��3��14�� 9:36:12
 * @version 1.0
 * @description:��ͬ �����嵥���Ʋ�
 */
class controller_contract_contract_equ extends controller_base_action {

	function __construct() {
		$this->objName = "equ";
		$this->objPath = "contract_contract";
		parent :: __construct();
	}

	/*
	 * ��ת����ͬ �����嵥�б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת��������ͬ �����嵥ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭��ͬ �����嵥ҳ��
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
	 * ��ת���鿴��ͬ �����嵥ҳ��
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
	 * ��ȡ��������
	 */
	function c_getCacheConfig() {
		$id = $_POST['id'];
		$obj = $this->service->configuration_d($_POST['id'], $_POST['productNum'], $_POST['rowNum'], $_POST['itemNum']);
		echo util_jsonUtil :: iconvGB2UTF($obj);
		//		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ȡ��Ʒ�µ�������Ϣ[�˷������ã����ƶ���allsource.php]
	 */
	function c_getProductEqu() {
		$id = $_POST['conProductId'];
		$service = $this->service;
		$equArr = $service->getProductEqu_d($id);
		if (is_array($equArr) && count($equArr) > 0) {
			foreach ($equArr as $key => $val) {
				$equArr[$key]['warrantyPeriod'] = $val['warranty'];
				if (isset ($_POST['number'])) {
					$equArr[$key]['number'] = $val['number'] * ($_POST['number'] * 1);
				}
			}
		}
		$equArr = $this->sconfig->md5Rows($equArr);
		echo util_jsonUtil :: encode($equArr);
	}

	/**
	 * ��ȡ��Ʒ����(ע�⣺��ʱ����isTemp��ǰ̨���ݹ���)
	 */
	function c_getConEqu() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$contEqu = $service->list_d();
		if( is_array($contEqu)&&count($contEqu)>0 ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach( $contEqu as $key=>$val ){
				$exeNum = $inventoryDao->getExeNumsByStockType($val['productId'], 'salesStockCode');
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
	 * ��ȡĳ�������嵥�������Ϣ
	 * add by chengl
	 */
	function c_getEquByParentEquId() {
		$equs = $this->service->getEquByParentEquId_d($_POST['parentEquId']);
		echo util_jsonUtil :: encode($equs);
	}

	/**
		 * ���ϴ����� ����
		 */
	function c_toEquAdd() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_contract_contract_contract();
		$obj = $contDao->getContractInfo($_GET['id']);
		$obj['contractCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['contractCode'] . '</a>';
        $products = $this->service->showItemView($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('docType', 'oa_contract_contract');
		$this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
		$this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
		//��ȡ��Ҫ���⴦�������id
		$this->assign('specialProId', specialProId);
		if($obj['dealStatus']=='0'){
			$this->view('add');
		}else{
			$this->view('edit');
		}

	}

	/**
	 * ���ϴ����� ���
	 */
	function c_toEquChange() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_contract_contract_contract();
		$linkDao = new model_contract_contract_contequlink();
		$costDao = new model_contract_contract_cost();
		$linkObj = $linkDao->get_d($_GET['linkId']);
		//�����ͬ��Ų鿴ҳ���ͬid
		$contractIdLink = $_GET['id'];
	    //��ȡ��ͬ���ԭ��
	    if($_GET['isSubAppChange'] == '1'){
	 		$cid = $_GET['oldId'];
	    }else{
	 	    $cid = $_GET['id'];
	 	    //���ݺ�ͬid��ȡ���һ�α����¼id�����ڲ鿴�ɽ������𲢱���ı��
//	 	    $sql = "select max(id) as mid from oa_contract_contract where isSubAppChange = '0' and originalId = " .$_GET['id'];
//	 	    $rs = $this->service->_db->getArray($sql);
//	 	    if(!empty($rs[0]['mid'])){
//	 	    	$contractIdLink = $rs[0]['mid'];
//	 	    }
	    }
	    $changeReason = $contDao->getChangeReasonById($cid);
	    $this->assign('changeReason', $changeReason);
	    //���سɱ�������ϸ��ע
		$rs = $costDao->find(array('contractId' => $_GET['id'],'issale' => '1'),null,'costRemark');
		$this->assign('costRemark', empty($rs['costRemark']) ? '' : $rs['costRemark']);
		$obj = $contDao->getContractInfoWithTemp($_GET['id'],null,$_GET['isSubAppChange']);
//		echo "<pre>";
//		print_R($obj);
		$obj['contractCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id=' . $contractIdLink . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['contractCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product'],$_GET['isSubAppChange']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('linkId', $_GET['linkId']);
		$this->assign('ExaDTOne', $linkObj['ExaDTOne']);
		$this->assign('docType', 'oa_contract_contract');
		$this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
		$this->assign('customerType', $this->getDataNameByCode($obj['customerType']));

		//�Ƿ������۱����ʶ
		$this->assign('isSubAppChange',$obj['isSubAppChange']);
		$oldId = isset ($_GET['oldId']) ? $_GET['oldId'] : "";
		$this->assign('oldId',$oldId);

		// �Ƿ��Ѵ���
		$rs = $contDao->find(array('id' => $_GET['id']),null,'dealStatus');
		$this->assign('dealStatus',empty($rs['dealStatus']) ? '' : $rs['dealStatus']);
		//��ȡ��Ҫ���⴦�������id
		$this->assign('specialProId', specialProId);

		$this->view('change');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquEdit() {
		$this->permCheck(); //��ȫУ��
		$contDao = new model_contract_contract_contract();
		$obj = $contDao->getContractInfo($_GET['id']);
		$obj['contractCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['contractCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
		$this->assign('customerType', $this->getDataNameByCode($obj['customerType']));

        $ruArr = $contDao->costinfoView($obj['exeDeptStr'], $_GET['id'],1);
        $this->assign("costAppRemark", $ruArr['remark']);
        //��ȡ��Ҫ���⴦�������id
        $this->assign('specialProId', specialProId);
        $sql = "select * from oa_contract_cost where contractId = '".$_GET['id']."' order by id desc";
        $rows = $this->service->_db->getArray($sql);
        if($rows){
            $this->assign("costRemark", $rows[0]['costRemark']);
        }else{
            $this->assign("costRemark", '');
        }
		$this->view('edit');
	}

	/**
	 * ���ϴ����� �༭
	 */
	function c_toEquView() {
		$this->permCheck(); //��ȫУ��
		$linkDao = new model_contract_contract_contequlink();
		$link = $linkDao->get_d($_GET['linkId']);
		$contDao = new model_contract_contract_contract();
		$obj = $contDao->getContractInfoWithTemp($link['contractId']);
		$obj['contractCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=init&perm=view&id=' . $link['contractId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['contractCode'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("linkId", $link['id']);
		if (!empty ($_GET['changeView'])) { //����鿴��־
			$this->assign("changeView", $_GET['changeView']);
		} else {
			$this->assign("changeView", '');
		}
		if (!empty ($_GET['isShowDel'])) { //�Ƿ���ʾɾ������
			$this->assign("isShowDel", $_GET['isShowDel']);
		} else {
			$this->assign("isShowDel", 'true');
		}
		$this->assign("isTemp", $link['isTemp']);
		$this->assign("originalId", $link['originalId']);
		$this->assign('contractType', $this->getDataNameByCode($obj['contractType']));
		$this->assign('customerType', $this->getDataNameByCode($obj['customerType']));
		$this->view('view');
	}

	/**
	 * ����ȷ������
	 */
	function c_equAdd($isEditInfo = true) {
		$this->permCheck(); //��ȫУ��
		$object = $_POST['contract'];
		if( $_GET['act'] == "audit" ){
			$id = $this->service->equAdd_d($object,true);
		}else{
			$id = $this->service->equAdd_d($object);
		}
		if ($id && $_GET['act'] == "audit") {
//			msg('�ύ�ɹ�����������ת�뵽���������б�ҳ��');
			//�ж��Ƿ���Ҫ��ת����
//			$this->c_subConfirmCostEqu($object['id']);
               msg('�����ɹ����������ύ��������Ա���з�������ȷ�ϣ�');
		} else{
			if ($id) {
				msg('����ɹ���');
			} else {
				msg('����ʧ�ܣ�');
			}
		}
	}

    /**
     * ��ȡ������ص������Ϣ
     */
    function c_getRelativeEqu(){
        $service = $this->service;
        $isDel = $_REQUEST['isDel'];
        $isTemp = $_REQUEST['isTemp'];
        $advCondition = isset($_REQUEST['advCondition'])? $_REQUEST['advCondition'] : '';
        $sql = "SELECT * FROM oa_contract_equ WHERE isDel = '{$isDel}' AND isTemp='{$isTemp}' {$advCondition};";
        $rows = $service->_db->getArray($sql);
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * �޸�����
	 */
	function c_equEdit($isEditInfo = false) {
		//		$this->permCheck (); //��ȫУ��
		$object = $_POST['contract'];
		if( $_GET['act']== "audit" ){
			$flag = $this->service->equEdit_d($object,true);
		}else{
			$flag = $this->service->equEdit_d($object);

		}
		if ($flag && $_GET['act'] == "audit") {
			//�ж��Ƿ���Ҫ��ת����
//			$this->c_subConfirmCostEqu($object['id']);
			msg('�����ɹ����������ύ��������Ա���з�������ȷ�ϣ�');
		} else{
			if ($flag) {
				msg('�༭�ɹ���');
			} else {
				msg('�༭ʧ�ܣ�');
			}
		}
	}

	/**
	 * �������
	 */
	function c_equChange($isEditInfo = false) {
		set_time_limit(0);
		$rows = $_POST['contract'];
		$conDao = new model_contract_contract_contract();
		$contractId = $rows['id'];

        if($rows['isSubAppChange'] == '0'){
			 $tempObjId = $conDao->getConTempById($contractId,$rows['changeReason']);
			 $rows['id'] = $tempObjId;
             $rows['oldId'] = $contractId;
			 //����������id �滻Ϊ��ʱ��¼��id
             $rows = $this->handleReplaceEquId($rows,$tempObjId);

            // ����ȷ��ҳֱ�ӱ�������ύ����,��Ҫ�����۲�������ĸ�����Ϣ������
            if(($_GET['fromSales'] == 0 && $_GET['act'] == "audit") || $_GET['act'] == "noaudit"){
                $costDao = new model_contract_contract_cost();
                $oldRows = $this->service->_db->getArray("select * from oa_contract_cost where contractId = '".$contractId."' AND issale <> 1");
                foreach($oldRows as $k => $v){
                    $oldCostArr = array();
                    foreach($v as $vk => $vv){
                        if($vk != 'id'){
                            if($vk == 'contractId'){
                                $oldCostArr[$vk] = $tempObjId;
                            }else{
                                $oldCostArr[$vk] = ($vv == 'NULL')? '' : $vv;
                            }
                        }
                    }
                    $costDao->add_d($oldCostArr);
                }
            }
	    }
        if( $_GET['act'] == "audit" || $_GET['act'] == "noaudit" ){
			$id = $this->service->equChange_d($rows,true);
		}else{
			$id = $this->service->equChange_d($rows);
		}


		if ($id) {
            if( $_GET['act'] == "audit" || $_GET['act'] == "noaudit" ){
	           //��ȡ��ͬ��Ϣ
	    	    $row = $conDao->getContractInfo($contractId);
	//			$exGross = $conDao->handleCost($contractId);
				if($rows['isSubAppChange'] == '0'){
					if ($tempObjId) {
						$dateObj = array(
							'id'=>$contractId,
							'standardDate'=>$rows['standardDate'],
							'dealStatus'=>'4'
						);
						if($_GET['act'] == "noaudit"){// 2015.10.27 By weijb ��Ȩ���������,���ȷ�ϱ����ť,������������,���������:���ϱ��--����ȷ��--������
							$dateObj['changeNoAudit'] = 1;// �������������ʶ
						}
						$conDao->updateById($dateObj);

//			              $configDeptIds = contractFlowDeptIds;//config�ڶ���� ����ID
//			              $deptIds = "";
//			          	  $deptIdStr = $configDeptIds.",".$deptIds;
//			          	  $deptIdStrArr = explode(",",$deptIdStr);
//				          $deptIdStrArr = array_unique($deptIdStrArr);
//				          $deptIdStr = implode(",",$deptIdStrArr);
//						  succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId='.$tempObjId.'&billDept='.$deptIdStr);
					}
		            msg('���ύ���ȴ�����ȷ�ϣ�');
		        }else{
		           //���´���״̬
			            $dateObj = array(
							'id'=>$row['originalId'],
							'standardDate'=>$rows['standardDate'],
							'dealStatus'=>'1'
						);
						$conDao->updateById($dateObj);
			       	 msg('�����ɹ����������ύ!');
		        }
            }else{
            	msg('����ɹ���');
            }

		} else{
			msg('���ʧ�ܣ�');
		}
	}

	/**
	 * �����滻����idΪ��ʱ��¼id
	 */
	function handleReplaceEquId($rows,$tempObjId ){
        $arrSql = "select * from oa_contract_equ where contractId = '".$tempObjId."'";
        $arr = $this->service->_db->getArray($arrSql);
        foreach( $arr as $key => $val){
        	$tempId = $val['originalId'];
        	$newId  = $val['id'];
        	$tempArr[$tempId] = $newId;
        }
    	foreach ($rows['detail'] as $k => $v){
             if (is_array($v)) {
				foreach ($v as $ke => $va){
					$oid = $va['id'];
                    $nId = $tempArr[$oid];
                    if(!empty($nId)){
                    	$rows['detail'][$k][$ke]['id'] = $nId;
                    	if(isset($va['alreadyDel']) && $va['alreadyDel'] == '1' && $va['isDel'] == '0'){
                    		$rows['detail'][$k][$ke]['remark'] = $rows['detail'][$k][$ke]['originalTempId'];
                    	}
                    	unset($rows['detail'][$k][$ke]['originalId']);
                    	unset($rows['detail'][$k][$ke]['originalTempId']);
                    }else{
                    	$rows['detail'][$k][$ke]['id'] = "";
                        if(isset($va['alreadyDel']) && $va['alreadyDel'] == '1' && $va['isDel'] == '0'){
                    		$rows['detail'][$k][$ke]['remark'] = $rows['detail'][$k][$ke]['originalTempId'];
                    	}
                    	unset($rows['detail'][$k][$ke]['originalId']);
                    	unset($rows['detail'][$k][$ke]['originalTempId']);
                    }
				}
             }
    	}
        return $rows;
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

	/**
	 * ����ȷ�� ������������
	 */
	function c_getNoProductEqu() {
		$contractId = $_POST['contractId'];
		$this->service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$rows = $this->service->getNoProductEqu_d($contractId);
        $conProductdao = new model_contract_contract_product();
		if( is_array($rows)&&count($rows)>0 ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach( $rows as $key=>$val ){
				$exeNum = $inventoryDao->getExeNumsByStockType($val['productId'], 'salesStockCode');
				if( empty($exeNum)||!isset($exeNum) ){
					$rows[$key]['exeNum'] = '0';
				}else{
					$rows[$key]['exeNum'] = $exeNum;
				}
                $info = $conProductdao ->get_d($val['proId']);
                $rows[$key]['conProduct'] = $info['conProductName'];
			}
		}
		echo util_jsonUtil :: encode($rows);
	}

   /**
    * �ж��Ƿ���Ҫ��ת������
    */
   function c_subConfirmCostEqu($contractId){
   	  $conDao = new model_contract_contract_contract();
      //��ȡ��ͬ��Ϣ
	  $rows = $conDao->getContractInfo($contractId);
       //����Ԥ��ë���ʲ�����ֵȷ���Ƿ����
        $exGross = $conDao->handleCost($contractId);
      if($exGross == 'none'){
       	  msg("ȷ�ϳɹ�����ȴ���������ȷ�ϳɱ����㣡");
       }else{
	  	 //��ȡ��������id ��
		  $deptIds = $conDao->getDeptIds($rows);
          $configDeptIds = contractFlowDeptIds;//config�ڶ���� ����ID
          if(!empty($deptIds)){
          	 $deptIdStr = $configDeptIds.",".$deptIds;
          }else{
          	 $deptIdStr = $configDeptIds;
          }
          $deptIdStrArr = explode(",",$deptIdStr);
          $deptIdStrArr = array_unique($deptIdStrArr);
          $deptIdStr = implode(",",$deptIdStrArr);
//	       if($arr['costType'] == '��ͬ����'){
	       	  if($exGross < EXGROSS){
				  succ_show('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
	          }else{
			      succ_show('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
	          }
//	       }else if($arr['costType'] == '��ͬ���'){
//	          if($exGross < EXGROSS){
//				   succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
//	          }else{
//			 	  succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
//	          }
//	       }
       }
   }

   /**
    * ��ȡ�������ݷ���json
    */
   function c_productJson() {
   	$service = $this->service;
   	$equIds = $_POST['equIds'];
   	$purchType = $_POST['docType'];
   	$contractId = $_POST['contractId'];
   	$borrowId = $_POST['contractId'];
   	switch ($purchType){
   		case "oa_present_present" :
   			$detailDao = new model_projectmanagent_present_presentequ;
   			$detailDao->getParam ( $_REQUEST );
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['presentId'] = $borrowId;
   			}
   			break;
   		case "oa_borrow_borrow" :
   			$detailDao = new model_projectmanagent_borrow_borrowequ;
   			$detailDao->getParam ( $_REQUEST );
            $detailDao->searchArr ['isTemp'] = 0;
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['borrowId'] = $borrowId;
   			}
   			break;
   		case "oa_contract_contract" :
   			$detailDao = new model_contract_contract_equ;
   			$detailDao->getParam ( $_REQUEST );
   			$detailDao->searchArr ['isBorrowToorder'] = "0";
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['contractId'] = $contractId;
   			}
   			break;
   		case "oa_contract_exchangeapply" :
   			$detailDao = new model_projectmanagent_exchange_exchangeequ;
   			$detailDao->getParam ( $_REQUEST );
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['exchangeId'] = $contractId;
   			}
   			break;
   	}
   	$rows = $detailDao->list_d ();
   	foreach($rows as $k=>$v){
   		// 2016��11��18��15:15:43 ��Խ��� ��ӹ��� �黹����������ͳ�Ʒ�Χ��
   		if(($purchType == "oa_borrow_borrow" && $v['number']-$v['issuedShipNum']>0) || ($purchType != "oa_borrow_borrow" && $v['number']-$v['issuedShipNum']+$v['backNum']>0)){

   			$temp = $v;
	   		if($purchType == "oa_contract_contract" || $purchType == "oa_contract_exchangeapply"){
	   			$temp['productNo'] = $rows[$k]['productCode'];
	   		}
	   		if(empty($v['originalId'])){
	   			$temp['contEquId'] = $rows[$k]['id'];
			}else{
				$temp['contEquId'] = $rows[$k]['originalId'];
			}

			$temp['contRemain'] = $rows[$k]['number'] - $rows[$k]['issuedShipNum'] + $rows[$k]['backNum'];
			$temp['lockNum'] = $rows[$k]['number'] - $rows[$k]['issuedShipNum'] + $rows[$k]['backNum'];
			$temp['executedNum'] = $rows[$k]['number'] - $rows[$k]['issuedShipNum'] + $rows[$k]['backNum'];
			$temp['contNum'] = $rows[$k]['number'] - $rows[$k]['issuedShipNum'] + $rows[$k]['backNum'];
			$temp['contractNum'] = $rows[$k]['number'];
			$temp['number'] = $rows[$k]['number'] - $rows[$k]['issuedShipNum'] + $rows[$k]['backNum'];
	   		$temp['applyNum'] = $rows[$k]['productCode'];
	   		$temp['stockId'] = $rows[$k]['outStockNameId'];
	   		$temp['stockCode'] = $rows[$k]['outStockCode'];
	   		$temp['stockName'] = $rows[$k]['outStockName'];
	   		$temoArr[] = $temp;
   		}
   	}
   	//���ݼ��밲ȫ��
   	echo util_jsonUtil::encode ( $temoArr );
   }

	/**
	 * ��ͬ����ҳ���ȡ�������ݷ���json
	 */
	function c_deliveryListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //��;����
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}


	/* ��ȡ���´������=0
	 * ��ִ������==���˻�����
	 * ��ͬ�������� == ��ͬ��������-��ִ������+���˻�����
	 *
	 */

   function c_getoutmat(){
	   	$contractId = $_POST['contractId'];
	   	$borrowId = $_POST['contractId'];
	   	$purchType = $_POST['docType'];
    	switch ($purchType){
   		case "oa_present_present" :
   			$detailDao = new model_projectmanagent_present_presentequ;
//   			$detailDao->getParam ( $_REQUEST );
	   		$detailDao->searchArr['isTemp'] = 0;
	   		$detailDao->searchArr['issuedShipNum'] = 0;
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['presentId'] = $borrowId;
   				$resultArr = $detailDao->list_d ("select_closematb");
   			}
   			break;
   		case "oa_borrow_borrow" :
   			$detailDao = new model_projectmanagent_borrow_borrowequ;
	   		$detailDao->searchArr['isTemp'] = 0;
	   		$detailDao->searchArr['issuedShipNum'] = 0;
//   			$detailDao->getParam ( $_REQUEST );
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['borrowId'] = $borrowId;
   				$resultArr = $detailDao->list_d ("select_closematb");
   			}
   			break;
   		case "oa_contract_contract" :
   			$detailDao = new model_contract_contract_equ;
	   		$detailDao->searchArr['isTemp'] = 0;
	   		$detailDao->searchArr['issuedShipNum'] = 0;
//   			$detailDao->getParam ( $_REQUEST );
//   			$detailDao->searchArr ['isBorrowToorder'] = "0";
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['contractId'] = $contractId;
   				$resultArr = $detailDao->list_d("select_closemat");
   			}
   			break;
   		case "oa_contract_exchangeapply" :
   			$detailDao = new model_projectmanagent_exchange_exchangeequ;
	   		$detailDao->searchArr['isTemp'] = 0;
	   		$detailDao->searchArr['issuedShipNum'] = 0;
//   			$detailDao->getParam ( $_REQUEST );
   			if(!empty($equIds)){
   				$detailDao->searchArr ['equIds'] = $equIds;
   			}else{
   				$detailDao->searchArr ['exchangeId'] = $contractId;
   			}
   			break;
   	}
//   		$cid = $_POST['contractId'];
//   		$this->service->searchArr['contractId'] = $_POST['contractId'];
//   		$this->service->searchArr['isDel'] = 0;
   		echo util_jsonUtil::encode ( $resultArr );
   }

   //ɾ������������
	function c_closeOpen(){
		$object = $_POST['outplan'];
		$result = $this->service->closeopen_d($object);
		if($result){
			msg("���·������ϳɹ�");
		}else{
			msg("���·�������ʧ��");
		}
	}
	/**
	 * ѡ�����ϵ�����ѡ��ҳ��
	 *
	 */
	function c_selectEqu() {
		$this->view ( "select" );
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$conProductdao = new model_contract_contract_product();
		foreach($rows as $k => $v){
          $info = $conProductdao ->get_d($v['proId']);
          $productInfo = $conProductdao ->get_d($v['conProductId']);
          //��ȡ�˻���������
          $rows[$k]['isBackNum'] = $service->getBackNum($v['id']);
          $rows[$k]['conProductName'] = $productInfo['conProductName'];
          $rows[$k]['conProduct'] = $info['conProductName'];
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}


    /**
     * ��ȡ�������ݷ���json(��Ʒ����)
     */
    function c_listJsonGroup() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->sort = "c.conProductId,SUBSTRING_INDEX(c.isCon,'_',1)*1  ,if(LOCATE('_',c.isCon),SUBSTRING_INDEX(c.isCon,'_',-1)*1,-1)  ";
        $service->asc = false;
        $rows = $service->list_d ();
        $conProductdao = new model_contract_contract_product();
        foreach($rows as $k => $v){
            $info = $conProductdao ->get_d($v['proId']);
            $productInfo = $conProductdao ->get_d($v['conProductId']);
            $rows[$k]['conProductName'] = $productInfo['conProductName'];
            $rows[$k]['conProduct'] = $info['conProductName'];
        }
        $rows = $service->filterRows_d($rows);

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * listJson(���������������;����)
	 */
	function c_listJsonWith() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao  = new model_purchase_contract_equipment();
			$productDao   = new model_stock_productinfo_productinfo();
			foreach ($rows as $key => $val) {
				$productObj = $productDao->get_d($val['productId']);
				$rows[$key]['proType']     = $productObj['proType'];
				$rows[$key]['proTypeId']   = $productObj['proTypeId'];
				$rows[$key]['inventory']   = $inventoryDao->getExeNumsByStockType($val['productId']); //�������
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //��;����
			}
		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * �鿴��ͬ�ڲ�Ʒ���������� Created By HuangHaoJin
     */
    function c_chkRelativeEqu(){
        $contractid = $_REQUEST['contractid'];
        $productid = $_REQUEST['productid'];
        $sql = "select count(id) as num from oa_contract_equ where conproductId = '{$productid}' and contractId = '{$contractid}' and (executedNum - backNum)>0";
        $rows = $this->service->_db->getArray($sql);
//        echo util_jsonUtil::encode ( $rows[0] );
        echo $rows[0]['num'];
    }

    /**
     * �鿴��ͬ�����Ƿ���ڷ����ƻ� Created By HuangHaoJin
     */
    function c_chkPlaningEqu(){
        $contractid = $_REQUEST['contractid'];
        $productId = $_REQUEST['productId'];
        $ids = '';
        $id_row = $this->service->findAll(array("contractId"=>$contractid,"conProductId"=>$productId),'',"id");
        foreach($id_row as $v){
            $ids .= $v['id'].',';
        }
        if( strlen($ids) > 0 ){
            $ids = substr($ids, 0, strlen($ids)-1);// ȥ�����һ������
            $sql = "select count(id) as num from oa_stock_outplan_product where docId = '{$contractid}' and contEquId IN ({$ids}) and isDelete <> 1";
            $rows = $this->service->_db->getArray($sql);
            $returnNum = $rows[0]['num'];
        }else{
            $returnNum=0;
        }
//        echo util_jsonUtil::encode ( $returnNum );
        echo $returnNum;
    }
}