<?php
/**
 * @author Administrator
 * @Date 2011年5月9日 16:02:12
 * @version 1.0
 * @description:借试用申请产品清单控制层
 */
class controller_projectmanagent_present_presentequ  extends controller_base_action {

	function __construct() {
		$this->objName = "presentequ";
		$this->objPath = "projectmanagent_present";
		parent::__construct ();
	 }

	/*
	 * 跳转到借试用申请产品清单
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }


	/**
	 * 发货列表从表数据获取
	 */
	function c_pageJson() {
		if( $_POST['ifDeal'] ){
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$lockDao = new model_stock_lock_lock ();
			$service = $this->service;
			$service->getParam ( $_POST );
			$this->service->searchArr['isDel']=0;
			$rows=$service->list_d();
			foreach ( $rows as $key=>$val){
				$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,'oa_present_present' );
				$rows[$key]['exeNum'] =  $inventoryDao->getExeNums( $rows[$key]['productId'], '1' );
			}
		}else{
			$rows = array();
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr);
	}



	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$conProductdao = new model_projectmanagent_present_product();
		foreach($rows as $k => $v){
          $productInfo = $conProductdao ->get_d($v['conProductId']);
          $rows[$k]['conProductName'] = $productInfo['conProductName'];
		}

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * 获取所有数据返回json
     */
    function c_listJsonGroup() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->sort = "c.conProductId";
        $rows = $service->list_d ();
        $conProductdao = new model_projectmanagent_present_product();
        foreach($rows as $k => $v){
            $productInfo = $conProductdao ->get_d($v['conProductId']);
            $rows[$k]['conProductName'] = $productInfo['conProductName'];
        }
        $rows = $service->filterRows_d($rows);

        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }



	/**
	 * 获取分页数据转成Json
	 */
	function c_listpageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ();
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
	 * 判断某个合同是否有产品清单
	 */
	 function c_getEquById(){
	 	$sql = "select count(*) as equNum from " . $this->service->tbl_name . " where presentId=" . $_POST['id'] . " and isDel<>1";
	 	$equNum = $this->service->_db->getArray ( $sql );
	 	echo $equNum[0]['equNum'];
	 }

	/***************************************物料确认   start************************************************/

	/**
	 * 获取产品物料(注意：临时数据isTemp从前台传递过来)
	 */
	function c_getConEqu() {
//		echo "<pre>";
//		print_R($_REQUEST);
		$service = $this->service;
		$service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$service->asc = false;
		$contEqu = $service->list_d();
		echo util_jsonUtil :: encode($contEqu);
	}


	/**
	 * 获取产品下的物料信息
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
	 * 获取某个物料清单的配件信息
	 * add by zengzx
	 */
	function c_getEquByParentEquId() {
		$equs = $this->service->getEquByParentEquId_d($_POST['parentEquId']);
//		echo "<pre>";
//		print_R($equs);
		echo util_jsonUtil :: encode($equs);
	}
	/**
		 * 物料处理方法 新增
		 */
	function c_toEquAdd() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_present_present();
		$obj = $contDao->getPresentInfo($_GET['id']);
		$products = $this->service->showItemView($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if(!empty($obj['orderCode'])){
			$this->assign("orderCode",$obj['orderCode']);
		}else{
			$this->assign("orderCode",$obj['chanceCode']);
		}
		$this->assign('docType', 'oa_present_present');
		$this->view('add');
	}

	/**
	 * 物料处理方法 变更
	 */
	function c_toEquChange() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_present_present();
		$linkDao = new model_projectmanagent_present_presentequlink();
		$linkObj = $linkDao->get_d($_GET['linkId']);
		$obj = $contDao->getPresentInfoWithTemp($_GET['id']);
		$obj['presentCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_present_present&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign('ExaDTOne', $linkObj['ExaDTOne']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		// 在页面添加变更标签
        $isChangeTip = 0;
		if(isset($_GET['isChange']) && $_GET['isChange'] == 1){
            $isChangeTip = 1;
        }
        $this->assign('isChangeTip', $isChangeTip);
		$this->assign('docType', 'oa_present_present');
		$this->view('change');
	}

	/**
	 * 物料处理方法 编辑
	 */
	function c_toEquEdit() {
		$this->permCheck(); //安全校验
		$contDao = new model_projectmanagent_present_present();
		$obj = $contDao->getPresentInfo($_GET['id']);
//		echo "<pre>";
//		print_R($obj);
		$this->assign('docType', 'oa_present_present');
		$obj['presentCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_present_present&action=init&perm=view&id=' . $_GET['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 物料处理方法 编辑
	 */
	function c_toEquView() {
		$this->permCheck(); //安全校验
		$linkDao = new model_projectmanagent_present_presentequlink();
		$link = $linkDao->get_d($_GET['linkId']);
		$contDao = new model_projectmanagent_present_present();
		$obj = $contDao->getPresentInfoWithTemp($link['presentId']);
		$obj['presentCode'] = '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=projectmanagent_present_present&action=init&perm=view&id=' . $link['presentId'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' . $obj['Code'] . '</a>';
		$products = $this->service->showItemChange($obj['product']);
		$this->assign("products", $products);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("linkId", $link['id']);
		if(!empty($_GET['changeView'])){//变更查看标志
			$this->assign("changeView", $_GET['changeView']);
		}else{
			$this->assign("changeView", '');
		}
		if(!empty($_GET['isShowDel'])){//是否显示删除物料
			$this->assign("isShowDel", $_GET['isShowDel']);
		}else{
			$this->assign("isShowDel", 'true');
		}
		$this->assign('docType', 'oa_present_present');
		$this->assign("isTemp", $link['isTemp']);
		$this->assign("originalId", $link['originalId']);
		$this->view('view');
	}

	/**
	 * 物料确认新增
	 */
	function c_equAdd($isEditInfo = true) {
		$this->permCheck(); //安全校验
		$object = $_POST['present'];
		if( $_GET['act'] == "audit" ){
            $costEstimates = $object['costEstimates'];//物料概算不含税
			$id = $this->service->equAdd_d($object,true);
		}else{
			$id = $this->service->equAdd_d($object);
		}
		if ($id && $_GET['act'] == "audit") {
			msg('提交成功！该需求已转入到发货需求列表页。');
		} else{
			if ($id) {
				msg('保存成功！');
			} else {
				msg('保存失败！');
			}
		}
	}

	/**
	 * 修改物料
	 */
	function c_equEdit($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$object = $_POST['present'];
        $contDao = new model_projectmanagent_present_present();
        $salesNameId = '';
        // 获取原单申请人ID
        if(isset($object['id'])){
            $sql = "select salesNameId from oa_present_present where id = {$object['id']}";
            $idArr = $contDao->_db->getArray($sql);
            $salesNameId = $idArr[0]['salesNameId'];
        }

		if( $_GET['act']== "audit" ){// 物料确认页提交审批
			$flag = $this->service->equEdit_d($object,true);
		}else if($_GET['act']== "needAudit"){
            $costEstimates = $object['costEstimatesTax'];//物料概算含税
            $flag = $this->service->equEdit_d($object);
            succ_show ( 'controller/projectmanagent/present/ewf_present.php?actTo=ewfSelect&billId=' . $object['id'] .'&flowMoney='. $costEstimates.'&eUserId='.$salesNameId);
        }else{
			$flag = $this->service->equEdit_d($object);
		}
//		if ($flag && $_GET['act'] == "audit") {
//			msg('提交成功！该需求已转入到发货需求列表页。');
//		} else{
			if ($flag) {
				msg('编辑成功！');
			} else {
				msg('编辑失败！['.$flag.']');
			}
//		}
	}

	/**
	 * 变更物料
	 */
	function c_equChange($isEditInfo = false) {
		$rows = $_POST['present'];
//		$id = $this->service->equChange_d($rows);
        $id = $this->service->equChangeNew_d($rows);
		if ($id) {
            $salesNameId = '';
            if(isset($rows['oldId'])){
                // 获取原单申请人ID
                $contDao = new model_projectmanagent_present_present();
                $sql = "select salesNameId from oa_present_present where id = {$rows['oldId']}";
                $idArr = $contDao->_db->getArray($sql);
                $salesNameId = $idArr[0]['salesNameId'];
            }
            $costEstimates = $rows['costEstimatesTax'];//物料概算含税
            succ_show ( 'controller/projectmanagent/present/ewf_change_index.php?actTo=ewfSelect&billId=' . $rows['id'] .'&flowMoney='. $costEstimates.'&eUserId='.$salesNameId);
//			msg('变更成功！');
		} else{
			msg('变更失败！');
		}
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
	/***************************************物料确认   end************************************************/
	/**
	 * 物料确认 独立添加物料
	 */
	function c_getNoProductEqu(){
		$contractId = $_POST['presentId'];
		$this->service->getParam($_REQUEST);
		$this->service->sort = ' c.isDel';
		$this->service->asc = false;
		$rows = $this->service->getNoProductEqu_d($contractId);
		echo util_jsonUtil::encode ( $rows );
	}

	function c_getEquCost(){
	    $equArr = $_POST['equArr'];
        $backArr = array("msg"=>'',"data"=>array());
        if(is_array($equArr) && !empty($equArr)){
            $costEstimates = $costEstimatesTax = 0;
            $backArr['data']['cacheArr'] = array();
            foreach ($equArr as $v){
                $sql = "select priCost from oa_stock_product_info where id='" . $v['productId'] . "'";
                $equCostArr = $this->service->_db->getArray($sql);
                $equCost = $equCostArr[0]['priCost'];
                $costEstimates += $equCost* $v['number'];
                // 按17%税率，计算税后金额
                $equCostTax = bcmul($equCost, '1.17', 2)* $v['number'];
                $costEstimatesTax += $equCostTax;

                if(isset($backArr['data']['cacheArr'][$v['productId']])){
                    $backArr['data']['cacheArr'][$v['productId']]['num'] += $v['number'];
                }else{
                    $backArr['data']['cacheArr'][$v['productId']]['num'] = $v['number'];
                    $backArr['data']['cacheArr'][$v['productId']]['cost'] = $equCost;
                }
            }
            $backArr['msg'] = 'ok';
            $backArr['data']['costEstimates'] = $costEstimates;
            $backArr['data']['costEstimatesTax'] = $costEstimatesTax;
        }

        echo util_jsonUtil::encode ( $backArr );
    }

    /**
     * 物料确认打回处理
     */
    function c_ajaxBack(){
        $service = $this->service;
        $presentId = isset($_REQUEST['id'])? $_REQUEST['id'] : '';
        $isSubAppChange = isset($_REQUEST['isSubAppChange'])? $_REQUEST['isSubAppChange'] : '';

        $contDao = new model_projectmanagent_present_present();
        if($isSubAppChange == 1){
            $sql = "select originalId from oa_present_present where id = $presentId";
            $idArr = $contDao->_db->getArray($sql);
            $presentId = $idArr[0]['originalId'];
        }
        $result = $service->ajaxBack($presentId,$isSubAppChange);
        echo $result;
    }
 }
?>