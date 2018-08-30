<?php
/**
 * @author zengzx
 * @Date 2012年3月14日 9:36:12
 * @version 1.0
 * @description:合同 发货清单控制层
 */
class controller_contract_contract_equ extends controller_base_action {

	function __construct() {
		$this->objName = "equ";
		$this->objPath = "contract_contract";
		parent :: __construct();
	}

	/*
	 * 跳转到合同 发货清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增合同 发货清单页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑合同 发货清单页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看合同 发货清单页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * 获取物料配置
	 */
	function c_getCacheConfig() {
		$id = $_POST['id'];
		$obj = $this->service->configuration_d($_POST['id'], $_POST['productNum'], $_POST['rowNum'], $_POST['itemNum']);
		echo util_jsonUtil :: iconvGB2UTF($obj);
		//		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 获取产品下的物料信息[此方法无用，已移动到allsource.php]
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
	 * 获取产品物料(注意：临时数据isTemp从前台传递过来)
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
	 * 获取某个物料清单的配件信息
	 * add by chengl
	 */
	function c_getEquByParentEquId() {
		$equs = $this->service->getEquByParentEquId_d($_POST['parentEquId']);
		echo util_jsonUtil :: encode($equs);
	}

	/**
		 * 物料处理方法 新增
		 */
	function c_toEquAdd() {
		$this->permCheck(); //安全校验
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
		//获取需要特殊处理的物料id
		$this->assign('specialProId', specialProId);
		if($obj['dealStatus']=='0'){
			$this->view('add');
		}else{
			$this->view('edit');
		}

	}

	/**
	 * 物料处理方法 变更
	 */
	function c_toEquChange() {
		$this->permCheck(); //安全校验
		$contDao = new model_contract_contract_contract();
		$linkDao = new model_contract_contract_contequlink();
		$costDao = new model_contract_contract_cost();
		$linkObj = $linkDao->get_d($_GET['linkId']);
		//点击合同编号查看页面合同id
		$contractIdLink = $_GET['id'];
	    //获取合同变更原因
	    if($_GET['isSubAppChange'] == '1'){
	 		$cid = $_GET['oldId'];
	    }else{
	 	    $cid = $_GET['id'];
	 	    //根据合同id获取最近一次变更记录id，用于查看由交付发起并保存的变更
//	 	    $sql = "select max(id) as mid from oa_contract_contract where isSubAppChange = '0' and originalId = " .$_GET['id'];
//	 	    $rs = $this->service->_db->getArray($sql);
//	 	    if(!empty($rs[0]['mid'])){
//	 	    	$contractIdLink = $rs[0]['mid'];
//	 	    }
	    }
	    $changeReason = $contDao->getChangeReasonById($cid);
	    $this->assign('changeReason', $changeReason);
	    //加载成本概算明细备注
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

		//是否是销售变更标识
		$this->assign('isSubAppChange',$obj['isSubAppChange']);
		$oldId = isset ($_GET['oldId']) ? $_GET['oldId'] : "";
		$this->assign('oldId',$oldId);

		// 是否已处理
		$rs = $contDao->find(array('id' => $_GET['id']),null,'dealStatus');
		$this->assign('dealStatus',empty($rs['dealStatus']) ? '' : $rs['dealStatus']);
		//获取需要特殊处理的物料id
		$this->assign('specialProId', specialProId);

		$this->view('change');
	}

	/**
	 * 物料处理方法 编辑
	 */
	function c_toEquEdit() {
		$this->permCheck(); //安全校验
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
        //获取需要特殊处理的物料id
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
	 * 物料处理方法 编辑
	 */
	function c_toEquView() {
		$this->permCheck(); //安全校验
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
		if (!empty ($_GET['changeView'])) { //变更查看标志
			$this->assign("changeView", $_GET['changeView']);
		} else {
			$this->assign("changeView", '');
		}
		if (!empty ($_GET['isShowDel'])) { //是否显示删除物料
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
	 * 物料确认新增
	 */
	function c_equAdd($isEditInfo = true) {
		$this->permCheck(); //安全校验
		$object = $_POST['contract'];
		if( $_GET['act'] == "audit" ){
			$id = $this->service->equAdd_d($object,true);
		}else{
			$id = $this->service->equAdd_d($object);
		}
		if ($id && $_GET['act'] == "audit") {
//			msg('提交成功！该需求已转入到发货需求列表页。');
			//判断是否需要跳转审批
//			$this->c_subConfirmCostEqu($object['id']);
               msg('操作成功！单据已提交至销售人员进行发货物料确认！');
		} else{
			if ($id) {
				msg('保存成功！');
			} else {
				msg('保存失败！');
			}
		}
	}

    /**
     * 获取物料相关的配件信息
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
	 * 修改物料
	 */
	function c_equEdit($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$object = $_POST['contract'];
		if( $_GET['act']== "audit" ){
			$flag = $this->service->equEdit_d($object,true);
		}else{
			$flag = $this->service->equEdit_d($object);

		}
		if ($flag && $_GET['act'] == "audit") {
			//判断是否需要跳转审批
//			$this->c_subConfirmCostEqu($object['id']);
			msg('操作成功！单据已提交至销售人员进行发货物料确认！');
		} else{
			if ($flag) {
				msg('编辑成功！');
			} else {
				msg('编辑失败！');
			}
		}
	}

	/**
	 * 变更物料
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
			 //处理并将物料id 替换为临时记录的id
             $rows = $this->handleReplaceEquId($rows,$tempObjId);

            // 发货确认页直接变更物料提交审批,需要把销售产线以外的概算信息带过来
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
	           //获取合同信息
	    	    $row = $conDao->getContractInfo($contractId);
	//			$exGross = $conDao->handleCost($contractId);
				if($rows['isSubAppChange'] == '0'){
					if ($tempObjId) {
						$dateObj = array(
							'id'=>$contractId,
							'standardDate'=>$rows['standardDate'],
							'dealStatus'=>'4'
						);
						if($_GET['act'] == "noaudit"){// 2015.10.27 By weijb 罗权洲提出需求,点击确认变更按钮,后续无须审批,走这个流程:物料变更--销售确认--变更完成
							$dateObj['changeNoAudit'] = 1;// 变更无须审批标识
						}
						$conDao->updateById($dateObj);

//			              $configDeptIds = contractFlowDeptIds;//config内定义的 部门ID
//			              $deptIds = "";
//			          	  $deptIdStr = $configDeptIds.",".$deptIds;
//			          	  $deptIdStrArr = explode(",",$deptIdStr);
//				          $deptIdStrArr = array_unique($deptIdStrArr);
//				          $deptIdStr = implode(",",$deptIdStrArr);
//						  succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId='.$tempObjId.'&billDept='.$deptIdStr);
					}
		            msg('已提交，等待销售确认！');
		        }else{
		           //更新处理状态
			            $dateObj = array(
							'id'=>$row['originalId'],
							'standardDate'=>$rows['standardDate'],
							'dealStatus'=>'1'
						);
						$conDao->updateById($dateObj);
			       	 msg('操作成功！单据已提交!');
		        }
            }else{
            	msg('保存成功！');
            }

		} else{
			msg('变更失败！');
		}
	}

	/**
	 * 处理并替换物料id为临时记录id
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
	 * 跳转到查看物料确认tab
	 */
	function c_toViewTab() {
		$rows = $this->service->get_d($_GET['id']);
		$this->assign('id', $_GET['id']);
		$this->assign('originalId', $rows['originalId']);
		$this->display('view-tab');
	}

	/**
	 * 物料确认 独立新增物料
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
    * 判断是否需要跳转审批流
    */
   function c_subConfirmCostEqu($contractId){
   	  $conDao = new model_contract_contract_contract();
      //获取合同信息
	  $rows = $conDao->getContractInfo($contractId);
       //处理预计毛利率并返回值确认是否完成
        $exGross = $conDao->handleCost($contractId);
      if($exGross == 'none'){
       	  msg("确认成功！请等待其他部门确认成本概算！");
       }else{
	  	 //获取审批部门id 串
		  $deptIds = $conDao->getDeptIds($rows);
          $configDeptIds = contractFlowDeptIds;//config内定义的 部门ID
          if(!empty($deptIds)){
          	 $deptIdStr = $configDeptIds.",".$deptIds;
          }else{
          	 $deptIdStr = $configDeptIds;
          }
          $deptIdStrArr = explode(",",$deptIdStr);
          $deptIdStrArr = array_unique($deptIdStrArr);
          $deptIdStr = implode(",",$deptIdStrArr);
//	       if($arr['costType'] == '合同建立'){
	       	  if($exGross < EXGROSS){
				  succ_show('controller/contract/contract/ewf_index_50_list.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
	          }else{
			      succ_show('controller/contract/contract/ewf_index_Other_list.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
	          }
//	       }else if($arr['costType'] == '合同变更'){
//	          if($exGross < EXGROSS){
//				   succ_show('controller/contract/contract/ewf_index_change_50.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
//	          }else{
//			 	  succ_show('controller/contract/contract/ewf_index_change_Other.php?actTo=ewfSelect&billId='.$contractId.'&billDept='.$deptIdStr);
//	          }
//	       }
       }
   }

   /**
    * 获取所有数据返回json
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
   		// 2016年11月18日15:15:43 针对借用 添加规则， 归还数量不算在统计范围内
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
   	//数据加入安全码
   	echo util_jsonUtil::encode ( $temoArr );
   }

	/**
	 * 合同交付页面获取所有数据返回json
	 */
	function c_deliveryListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($val['productId']); //库存数量
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //在途数量
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}


	/* 获取已下达的数量=0
	 * 已执行数量==已退货数量
	 * 合同需求数量 == 合同需求数量-已执行数量+已退货数量
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

   //删除或启用物料
	function c_closeOpen(){
		$object = $_POST['outplan'];
		$result = $this->service->closeopen_d($object);
		if($result){
			msg("更新发货物料成功");
		}else{
			msg("更新发货物料失败");
		}
	}
	/**
	 * 选择物料弹出的选择页面
	 *
	 */
	function c_selectEqu() {
		$this->view ( "select" );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$conProductdao = new model_contract_contract_product();
		foreach($rows as $k => $v){
          $info = $conProductdao ->get_d($v['proId']);
          $productInfo = $conProductdao ->get_d($v['conProductId']);
          //获取退货申请数量
          $rows[$k]['isBackNum'] = $service->getBackNum($v['id']);
          $rows[$k]['conProductName'] = $productInfo['conProductName'];
          $rows[$k]['conProduct'] = $info['conProductName'];
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}


    /**
     * 获取所有数据返回json(产品分组)
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

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

	/**
	 * listJson(带出库存数量和在途数量)
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
				$rows[$key]['inventory']   = $inventoryDao->getExeNumsByStockType($val['productId']); //库存数量
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //在途数量
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * 查看合同内产品关联的物料 Created By HuangHaoJin
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
     * 查看合同物料是否存在发货计划 Created By HuangHaoJin
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
            $ids = substr($ids, 0, strlen($ids)-1);// 去除最后一个逗号
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