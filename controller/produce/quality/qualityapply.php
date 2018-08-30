<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 10:47:28
 * @version 1.0
 * @description:质检申请单控制层
 */
class controller_produce_quality_qualityapply extends controller_base_action {

	function __construct() {
		$this->objName = "qualityapply";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}
	/**
	 * 跳转到相关Tab页面
	 */
	function c_toPageTab() {
		$this->assign('relDocType',$_GET['relDocType']);
		$this->view ( 'list-tab' );
	}
	/**
	 * 跳转到质检申请单列表
	 */
	function c_page() {
		$this->assign('relDocType',$_GET['relDocType']);
		$this->view('list');
	}
	/**
	 * 跳转到相关物料明细
	 */
	function c_pageDetail(){
		$this->assign('detailStatusArr',$_GET['detailStatusArr']);
		$this->assign('relDocType',$_GET['relDocType']);
		$this->view('list-detail');
	}
	/**
	 * 物料明细表 - 数据源
	 */
	function c_jsonDetail(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ('select_detail');
		//源单类型为生产的，获取关联合同号，客户信息
		if($_REQUEST['relDocTypeArr'] == 'ZJSQYDSC'){
			$produceplanDao = new model_produce_plan_produceplan();
			foreach($rows as $k => $v){
				$rs = $produceplanDao->find(array('id' => $v['relDocId']),null,'relDocId,relDocCode,relDocTypeCode,customerId,customerName');
				if(!empty($rs)){
					$rows[$k]['contractId'] = $rs['relDocId'];
					$rows[$k]['contractCode'] = $rs['relDocCode'];
					$rows[$k]['contractTypeCode'] = $rs['relDocTypeCode'];
					$rows[$k]['customerId'] = $rs['customerId'];
					$rows[$k]['customerName'] = $rs['customerName'];
				}
			}
		}
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
	 * 跳转到质检申请Tab
	 */
	function c_toMyTab() {
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->view ( 'mylist-tab' );
	}

	/**
	 * 跳转到新增质检申请单页面
	 */
	function c_toAdd() {
		$relDocType = isset ( $_GET ['relDocType'] ) ? $_GET ['relDocType'] : exit('传入参数错误');
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : exit('传入参数错误');
		$service = $this->service;
		//源单对应策略model
		$relClass = $service->applyStrategyArr [$relDocType];
		$relClassM = new $relClass ();//策略实例
		//获取源单信息
		$relDocObj = $service->ctGetRelDocInfo ( $relDocId, $relClassM );
        $this->assignFunc($relDocObj);
        $this->assign('relDocType',$relDocType);
        $this->assign('relDocTypeName',$this->getDataNameByCode($relDocType));
        $this->assign('relDocId',$relDocId);
        $this->assign('relDocCode',$_GET['relDocCode']);

	 	$this->view ( 'add' );
	}

	/**
	 * 跳转到新增质检申请单页面-原材料检验
	 */
	function c_toAddByArrival() {
		$relDocType = isset ( $_GET ['relDocType'] ) ? $_GET ['relDocType'] : exit('传入参数错误');
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : exit('传入参数错误');
		//获取源单信息
		$arrivalDao = new model_purchase_arrival_arrival();
		$object = $arrivalDao->get_d($relDocId);
		$relDocObj['applyUserName'] = $object['purchManName'];
		$relDocObj['applyUserCode'] = $object['purchManId'];
		$relDocObj['supplierName'] = $object['supplierName'];
		$relDocObj['supplierId'] = $object['supplierId'];
		$this->assignFunc($relDocObj);
		$this->assign('relDocType',$relDocType);
		$this->assign('relDocTypeName',$this->getDataNameByCode($relDocType));
		$this->assign('relDocId',$relDocId);
		$this->assign('relDocCode',$_GET['relDocCode']);

		$this->view ( 'add' );
	}

	/**
	 * 新增从表部分 -- 从策略取数
	 */
	function c_toAddDetail(){
		$relDocType = isset ( $_POST ['relDocType'] ) ? $_POST ['relDocType'] : "";
		$relDocId = isset ( $_POST ['relDocId'] ) ? $_POST ['relDocId'] : "";
		$service = $this->service;
		//源单对应策略model
		$relClass = $service->applyStrategyArr [$relDocType];
		$relClassM = new $relClass ();//策略实例
        $detailArr = $service->ctGetRelDetailInfo ( $relDocId, $relClassM );
		//获取源单信息
		echo util_jsonUtil::encode($detailArr);
	}

    /**
     * 新增对象操作
     */
    function c_add($isAddInfo = false) {
        $this->checkSubmit();
        $id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
        if ($id) {
            msg ( $msg );
        }
        //$this->listDataDict();
    }

	/**
	 * 跳转到编辑质检申请单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看质检申请单页面
	 */
	function c_toView($id = null) {
        $id = isset( $_GET ['id']) ?  $_GET ['id'] : $id;
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $id );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('relDocTypeName',$this->getDataNameByCode($obj['relDocType']));
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看质检申请单页面
	 */
	function c_toQualityView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
        //邮件发送人渲染
        $this->assignFunc($this->service->getMail_d('purchquality'));
		$this->assign('relDocTypeName',$this->getDataNameByCode($obj['relDocType']));
		$this->view ( 'view-quality' );
	}

    /**
     * 查看物料相关质检信息
     */
    function c_searchQuality(){
        $rs = $this->service->findQuality_d($_GET['relDocItemId']);
        if($rs['thisType'] == 'apply'){
            $this->c_toView($rs['mainId']);
        }else{
            succ_show("?model=produce_quality_qualityereport&action=toView&id=".$rs['mainId']);
        }
    }
}
?>