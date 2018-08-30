<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:40
 * @version 1.0
 * @description:换货申请单 Model层
 */
 class model_projectmanagent_exchange_exchange extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_exchangeapply";
		$this->sql_map = "projectmanagent/exchange/exchangeSql.php";
		parent::__construct ();
	}
	/**--------------------------------------模板显示---------------------------------------**/
	/**
	 *
	 * 蓝色销售出库,物料清单模板
	 */
	function showProItemAtCkSalesBlue($rows){
			if ($rows['equ']) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows['equ'] as $key => $val ) {
                if($val['number'] - $val['executedNum'] > 0){
                $seNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
					</td>
                   <td>
                   		$seNum
                   </td>
                   <td>
                      <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]"  />
					  <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
					<td>
					  <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]"  />
					</td>
    				<td>
    				   <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[productModel]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$val[number]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"
							onblur="javascript:FloatMul('actOutNum$i','salecost$i','saleSubCost$i');FloatMul('actOutNum$i','cost$i','subCost$i');"  value="$val[number]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value=""  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value=""  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value=""  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
					</td>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="0"  />
					</td>
                     <td>
					******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="0"  />
					</td>
                     <td>
					******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" value="$val[price]"  />
					</td>
    				<td>
					******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney" value="$val[money]"  />
					</td>
			</tr>
EOT;
				$i ++;
			}
		}
		return $str;
	}
	}

	/**
	 *
	 * 红色销售出库,物料清单模板
	 */
	function showProItemAtCkSalesRed($rows){
			if ($rows['backequ']) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows['backequ'] as $key => $val ) {
                if($val['number'] - $val['executedNum'] > 0){
                $seNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
					</td>
                   <td>
                   		$seNum
                   </td>
                   <td>
                      <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]"  />
					  <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
					<td>
					  <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]"  />
					</td>
    				<td>
    				   <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[productModel]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$val[number]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"
							onblur="javascript:FloatMul('actOutNum$i','salecost$i','saleSubCost$i');FloatMul('actOutNum$i','cost$i','subCost$i');"  value="$val[number]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value=""  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value=""  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value=""  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
					</td>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="0"  />
					</td>
                     <td>
					******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="0"  />
					</td>
                     <td>
					******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" value="$val[price]"  />
					</td>
    				<td>
					******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney" value="$val[money]"  />
					</td>
			</tr>
EOT;
				$i ++;
			}
		}
		return $str;
	}
	}


	/**
	 *编号自动生成
	 */
     function exchangeCode(){
        $billCode = "HH".date("Ymd");
//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.exchangeCode,4)) as maxCode,left(c.exchangeCode,10) as _maxbillCode " .
				"from oa_contract_exchangeapply c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="000".$newNum;break;
				case 2:$codeNum="00".$newNum;break;
				case 3:$codeNum="0".$newNum;break;
				case 4:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="0001";
		}

		return $billCode;
	}
	/**
	 * 重写add_d
	 */
	 function add_d($object){
          try{
          	$this->start_d();
          $object['exchangeCode'] = $this->exchangeCode();
          //处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['exchangeTypeName'] = $datadictDao->getDataNameByCode($object['exchangeType']);
          //插入主表信息
          $newId = parent::add_d($object,true);
          //插入从表信息
          if(!empty($object['backequ'])){
             $equDao = new model_projectmanagent_exchange_exchangebackequ();
             $equDao ->createBatch($object['backequ'],array('exchangeId' => $newId),'productName');
          }
          if(!empty($object['product'])){
             $equDao = new model_projectmanagent_exchange_exchangeproduct();
             $equDao ->createBatch($object['product'],array('exchangeId' => $newId),'conProductName');
          }
          if(!empty($object['equ'])){
             $equDao = new model_projectmanagent_exchange_exchangeequ();
             $equDao ->createBatch($object['equ'],array('exchangeId' => $newId),'productName');
          }

        $this->commit_d();
//        $this->rollBack();
        return $newId;
       }catch(exception $e){
       	 $this->rollBack();
       	 return false;
       }

	 }


	/**
	 * 重写编辑方法
	 */
	function edit_d($object){
		try{
			$this->start_d();
			//处理数据字典字段
			$datadictDao = new model_system_datadict_datadict();
			$object['exchangeTypeName'] = $datadictDao->getDataNameByCode($object['exchangeType']);
			//修改主表信息
			parent::edit_d($object,true);
			$exchangeId = $object['id'];
			//插入从表信息
			//设备
		  if(!empty($object['backequ'])){
             $backequDao = new model_projectmanagent_exchange_exchangebackequ();
               $backequDao->delete(array('exchangeId' => $exchangeId));
	            foreach ($object['backequ'] as $k => $v) {
					if ($v['isDelTag'] == '1') {
						unset ($object['backequ'][$k]);
					}
				}
             $backequDao ->createBatch($object['backequ'],array('exchangeId' => $exchangeId),'productName');
          }
          if(!empty($object['product'])){
             $productDao = new model_projectmanagent_exchange_exchangeproduct();
             $productDao->delete(array('exchangeId' => $exchangeId));
	            foreach ($object['product'] as $k => $v) {
					if ($v['isDelTag'] == '1') {
						unset ($object['product'][$k]);
					}
				}
             $productDao ->createBatch($object['product'],array('exchangeId' => $exchangeId),'conProductName');
          }
          if(!empty($object['equ'])){
             $equDao = new model_projectmanagent_exchange_exchangeequ();
             $equDao->delete(array('exchangeId' => $exchangeId));
	            foreach ($object['equ'] as $k => $v) {
					if ($v['isDelTag'] == '1') {
						unset ($object['equ'][$k]);
					}
				}
             $equDao ->createBatch($object['equ'],array('exchangeId' => $exchangeId),'productName');
          }
			$this->commit_d();
//			$this->rollBack();
			return true;
		}catch(exception $e){

			return false;
		}
	}

	/**
	 * 根据ID 获取全部信息
	 * $exchangeId : 主表ID
	 * $getInfoArr 需要的从表信息 例:$getInfoArr = array('linkman','product') 默认为空 取全部
	 *       backequ 退货物料  product 换货产品 equ 换货物料
	 */
	function getDetailInfo($exchangeId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'backequ',
				'product',
				'equ'
			);
		}
		$daoArr = array (
			"backequ" => "model_projectmanagent_exchange_exchangebackequ",
			"product" => "model_projectmanagent_exchange_exchangeproduct",
			"equ" => "model_projectmanagent_exchange_exchangeequ"
		);
		$contractInfo = parent::get_d($exchangeId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			$contractInfo[$val] = $dao->getDetail_d($exchangeId);
		}
		return $contractInfo;
	}
	/**
	 * 审批后的确认操作
	 */
	function workflowCallBack($spid){
		try {
			$this->start_d();
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($spid);
			$objId = $folowInfo['objId'];
			if (!empty ($objId)) {
				$contract = $this->get_d($objId);
				if ($contract['ExaStatus'] == "完成") {
					$findItemId = "select id,contractId,contractequId,number from oa_contract_exchange_backequ where exchangeId=$objId";
					$itemIdarr = $this->_db->getArray($findItemId);
                    foreach ($itemIdarr as $k=>$v){
                        $sql = "update oa_contract_equ set applyExchangeNum=".$v['number']." where id=".$v['contractequId']."";
                        $this->query($sql);
                    }
				}
			}
			$this->commit_d();
		} catch (Exception $e) {
			$this->rollBack();
		}
	}
	/*************************************************************************/
	/**
	 * 蓝字出库审核
	 */
    function updateAsOut($rows) {
		$backNum = $rows['outNum'];
		$sql = "update oa_contract_exchange_equ set executedNum = executedNum + " . $backNum
		. " where exchangeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$this->_db->query($sql);
		$this->updateContractinfoBlue($rows['relDocItemId'],$rows['outNum'],0);
	}

    /**
	 * 蓝字出库反审处理方法
	 */
	function updateAsAutiAudit($rows) {
		$backNum = $rows['outNum'] * (-1);
		$sql = "update oa_contract_exchange_equ set executedNum = executedNum + " . $backNum
		. " where exchangeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$this->_db->query($sql);
		$this->updateContractinfoBlue($rows['relDocItemId'],$rows['outNum'],1);
	}

	/**
	 * 红字出库审核处理方法
	 */
	function updateAsRedOut($rows) {
		$backNum = $rows['outNum'];
		$sql = "update oa_contract_exchange_backequ set executedNum = executedNum + " . $backNum
		. " where exchangeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$this->_db->query($sql);
		$this->updateContractinfoRed($rows['relDocItemId'],$rows['outNum'],0);
	}
	/**
	 * 红字出库反审处理方法
	 */
	function updateAsRedAutiAudit($rows) {
		$backNum = $rows['outNum'] * (-1);
		$sql = "update oa_contract_exchange_backequ set executedNum = executedNum + " . $backNum
		. " where exchangeId = " . $rows['relDocId'] . " and id= " . $rows['relDocItemId'] . " ";
		$this->_db->query($sql);
		$this->updateContractinfoRed($rows['relDocItemId'],$rows['outNum'],1);
	}
/*************************************************************************/
   /**
    * 根据退货单从表id 更新合同的状态和 数量
    */
   function updateContractinfoRed($returnItemId,$num,$as){
   	    //根据退货从表id获取合同id 和从表id
   	    $conSql = "select exchangeId,contractId,contractequId,productId from oa_contract_exchange_backequ where id=$returnItemId";
   	    $conarr = $this->_db->getArray($conSql);
		if($as == '0'){
			$backNum = $num ;
		}else if($as == '1'){
			$backNum = $num * (-1);
		}
//		$isSql="select productId from oa_contract_equ where id= " . $conarr[0]['contractequId'] . " ";
//		$isUpArr = $dao->_db->getArray($isSql);
//       if($conarr[0]['contractId'] == $isUpArr[0]['productId']){
//         $sql = "update oa_contract_equ set executedNum = executedNum + " . $backNum . ",exchangeBackNum = exchangeBackNum + " . $backNum . " where id= " . $conarr[0]['contractequId'] . " ";
//       }else{
//
//       }
         $sql = "update oa_contract_equ set exchangeBackNum = exchangeBackNum + " . $backNum . " where id= " . $conarr[0]['contractequId'] . " ";
		$dao = new model_contract_contract_contract();
		$dao->_db->query($sql);
		$dao->updateOutStatus_d($conarr[0]['contractId']);
   }
     function updateContractinfoBlue($returnItemId,$num,$as){
   	    //根据退货从表id获取合同id 和从表id
   	    $conSql = "select ce.id as contractequId,c.contractId from oa_contract_exchange_equ e left join oa_contract_exchangeapply c on e.exchangeId=c.id
    left join oa_contract_equ ce on ce.contractId=c.contractId and ce.productId=e.productId where e.id=".$returnItemId."";
   	    $conarr = $this->_db->getArray($conSql);
		if($as == '0'){
			$backNum = $num ;
		}else if($as == '1'){
			$backNum = $num * (-1);
		}
		$dao = new model_contract_contract_contract();
       if(!empty($conarr)){
         $sql = "update oa_contract_equ set executedNum = executedNum + " . $backNum . " where id= " . $conarr[0]['contractequId'] . " ";
		 $dao->_db->query($sql);
		 $dao->updateOutStatus_d($conarr[0]['contractId']);
       }

   }
/********************************************************发货相关******************************************************************************/


	/**
	 * 根据合同id修改合同及发货计划状态
	 */
	 function updateOutStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_contract_exchange_equ o where o.exchangeId=".$id.") as executeNum
						 from (select e.exchangeId,(e.number-e.executedNum) as remainNum from oa_contract_exchange_equ e
						where e.exchangeId=".$id.") c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//已发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => 'YFH'
		 	);
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['executeNum']==0 ){//未发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => 'WFH'
		 	);

		} else {//部分发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'DeliveryStatus' => 'BFFH'
		 	);
	 	}
	 	$this->updateById( $statusInfo );
	 	return 0;
	 }
    /**
     * 改变发货状态 --- 关闭
     */
    function updateDeliveryStatus ($id) {
    	$condiction = array ("id" => $id);
        if( $this->updateField($condiction,"DeliveryStatus","TZFH") ){
        	echo 1;
        }else{
        	echo 0;
        }
    }


	/**
	 * 根据发货情况修改合同及发货计划状态
	 */
	 function updateShipStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.issuedShipNum) from oa_contract_exchange_equ o where o.exchangeId=".$id.") as issuedShipNum
						 from (select e.exchangeId,(e.number-e.issuedShipNum) as remainNum from oa_contract_exchange_equ e
						where e.exchangeId=".$id.") c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//已发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'makeStatus' => 'YXD'
		 	);
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['issuedShipNum']==0 ){//未发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'makeStatus' => 'WXD'
		 	);
		} else {//部分发货
		 	$statusInfo = array(
		 		'id' => $id,
		 		'makeStatus' => 'BFXD'
		 	);
	 	}
	 	$this->updateById( $statusInfo );
	 	return 0;
	 }

	/**
	 * 根据退货明细更新收货下达状态
	 */
	 function updateBackStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.issuedBackNum) from oa_contract_exchange_backequ o where o.exchangeId=".$id.") as issuedBackNum
						 from (select e.exchangeId,(e.number-e.issuedBackNum) as remainNum from oa_contract_exchange_backequ e
						where e.exchangeId=".$id.") c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $orderRemainSql );
	 	if( $remainNum[0]['countNum'] <= 0 ){//已下达
		 	$statusInfo = array(
		 		'id' => $id,
		 		'issuedBackStatus' => 'YXD'
		 	);
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['issuedBackNum']==0 ){//未下达
		 	$statusInfo = array(
		 		'id' => $id,
		 		'issuedBackStatus' => 'WXD'
		 	);
		} else {//部分下达
		 	$statusInfo = array(
		 		'id' => $id,
		 		'issuedBackStatus' => 'BFXD'
		 	);
	 	}
	 	$this->updateById( $statusInfo );
	 	return 0;
	 }


/**************************************************合同设备统计操作 start****************************************************/
	/**
	 * 采购设备-计划数组
	 */
	function pageEqu_d(){
		$stockDao = new model_stock_stockinfo_systeminfo();
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$datadictDao = new model_system_datadict_datadict();
		$searchArr = $this->__GET("searchArr");
		$this->__SET('searchArr', $searchArr);
		$this->groupBy = 'productId';
		$rows = $this->getPagePlan("select_equ");
		$equIdArr = array();
		if( is_array($rows)&&count($rows)>0 ){
			foreach ( $rows as $key => $val ){
				$equIdArr[] = $val['productId'];
			}
			$equIdStr = implode(',',$equIdArr);
			$stockArr = $stockDao->get_d(1);
			$equInvInfo = $inventoryDao->getInventoryInfos($stockArr['salesStockId'],$equIdStr);
			foreach ( $rows as $key=>$val ){
				$rows[$key]['inventoryNum'] = 0;
				foreach( $equInvInfo as $k => $v ){
					if( $val['productId']==$v['productId'] ){
						$rows[$key]['inventoryNum'] = $v['exeNum'];
					}
				}
			}
			$i = 0;
			foreach($rows as $key => $val){
				$searchArr = $this->__GET("searchArr");
				$searchArr['productId'] = $val['productId'];
				$this->groupBy="id";
				$this->sort="id";
				$this->searchArr=$searchArr;
				$chiRows = $this->listBySqlId("select_cont");
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
//			echo "<pre>";
//			print_R($rows);
			return $rows;
		}
		else {
			return false;
		}
	}


	/**采购管理-采购计划-设备清单显示模板
	*author can
	*2011-3-23
	*/
	function showEqulist_s($rows){
		$str = "";
		$i = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				$i++;
				$addAllAmount = 0;
				$strTab="";
				foreach ($val['childArr'] as $chdKey => $chdVal){
					switch($chdVal['tablename']){
						case 'oa_sale_order': $chdVal['tablename'] = '销售发货';break;
						case 'oa_sale_lease': $chdVal['tablename'] = '租赁发货';break;
						case 'oa_sale_rdproject': $chdVal['tablename'] = '研发发货';break;
						case 'oa_sale_service': $chdVal['tablename'] = '服务发货';break;
					}
//					$i++;
					$iClass = (($i%2)==0)?"tr_even":"tr_odd";
//					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
//						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
//					}else{
//						$amountOk = $chdVal['amountAll'];
//					}
					$addAllAmount += $chdVal['number'];
					$inventoryNum = $rows[$key]['inventoryNum'];

//					if( $amountOk==0 || $amountOk=="" ){
//						$checkBoxStr =<<<EOT
//				        	$chdVal[basicNumb]
//EOT;
//					}else{
//						<input type="checkbox" class="checkChild" >
//						$checkBoxStr =<<<EOT
//				    	$chdVal[orderTempCode]<input type="hidden" class="hidden" value="$chdVal[orgid]"/>
//EOT;
//					}

					$strTab.=<<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td width="20%">
						    	$chdVal[exchangeCode]
					        </td>
					        <td  width="8%">
					            $chdVal[number]
					        </td>
					        <td  width="8%">
					            $chdVal[onWayNum]
					        </td>
					        <td  width="8%">
					            $chdVal[executedNum]
					        </td>
		            	</tr>
EOT;
				}

				$str .=<<<EOT
					<tr class="$iClass">
				        <td    height="30" width="4%">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td >
				            $val[productNo]<br>$val[productName]
				        </td>
				        <td   width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$addAllAmount</p>
				            </p>
				        </td>
				        <td width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$inventoryNum</p>
				            </p>
				        </td>
				        <td width="65%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
				        	<div class="readThisTable"><单击展开物料具体信息></div>
				        </td>
				    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 合同设备总汇 分页
	 * 2011年10月19日 16:24:57
	 */
	 function getPagePlan($sql){
		$sql=$this->sql_arr [$sql];
		$countsql = "select count(0) as num " . substr ( $sql, strpos ( $sql, "FROM" ) );
		$countsql = $this->createQuery ( $countsql, $this->searchArr );
		$this->count = $this->queryCount ( $countsql );
		//拼装搜索条件
		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		//构建排序信息
		$asc = $this->asc ? "DESC" : "ASC";
		//echo $this->asc;
		$sql .= " group by productId order by " . $this->sort . " " . $asc;
		//构建获取记录数
		$sql .= " limit " . $this->start . "," . $this->pageSize;
//		echo $sql;
		return $this->_db->getArray ( $sql );
	 }
/**************************************************合同设备统计操作 end***************************************/



     /**
      * 关闭物料确认
      */
      function setEmailAfterCloseConfirm($id){
        try{
			$this->start_d();
			$addMsg = '该换货申请无需变更物料确认。';
		 	$mainObj = $this->getDetailInfo($id);
		 	$updateKey = array(
		 		'dealStatus' => '3'
		 	);
		 	$this->update(array('id'=>$id),$updateKey);
		 	$outmailArr=array(
		 		$mainObj['saleUserId']
		 	);
		 	$outmailStr = implode(',',$outmailArr);
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL']
			, 'oa_contract_exchange_equ', '关闭', $mainObj['exchangeCode'], $outmailStr, $addMsg, '1');

			$this->commit_d();
			return 1;
		}catch(exception $e){
			$this->rollBack();
			return 0;
		}
      }

     /**
      * 增加销售换货逾期7天未归还邮件提醒（每过7天发一份）
      * @return bool
      */
      function sendWarningMailProecessPer7Days_d(){
          $sql = "
          select * from (
                SELECT
                    exa.exchangeCode,
                    exa.arrivalDate,
                    datediff(now(), exa.arrivalDate) AS offDays,
                    datediff(exa.arrivalDate, now()) AS beforeDays,
                    exe.id as eid,
                    exe.number,
                    exe.productName,
                    exe.productCode,
                    exe.executedNum,
                    IF(
                        T.rkExecutedNum IS NULL,
                        0,
                        T.rkExecutedNum 
                    ) as rkExecutedNum,
                    c.id as contractId,
                    c.areaName
                FROM
                    oa_contract_exchange_backequ exe
                LEFT JOIN oa_contract_exchangeapply exa ON exe.exchangeId = exa.id
                LEFT JOIN oa_contract_contract c ON c.id = exa.contractId
                LEFT JOIN (
                    select
                    ex.id as exchangeId,
                    ex.exchangeCode, 
                    ie.productCode,
                    sum(ie.executedNum) as rkExecutedNum
                    FROM
                    oa_stock_innotice_equ ie
                    LEFT JOIN oa_stock_innotice i ON i.id = ie.mainId
                    LEFT JOIN oa_contract_exchangeapply ex ON ex.id = i.docId
                    group by ex.exchangeCode,ie.productCode
                )T on (T.exchangeId = exa.id and exe.productCode = T.productCode)
                WHERE
                     1=1
				AND (
                        (
                            exa.ExaStatus IN ('完成', '变更审批中')
                        )
                    )
				AND (
                        (
                            exa.DeliveryStatus IN ('WFH', 'BFFH')
                        )
                    )
                AND exa.deliveryCondition = '先发后到'
                AND (T.rkExecutedNum is null or (exe.number - T.rkExecutedNum) > 0)
                AND (
                    date_format(exa.arrivalDate, '%Y%m%d') <= date_format(now(), '%Y%m%d')
                    OR datediff(exa.arrivalDate, now()) = 7
                )
          )t where (t.number - t.rkExecutedNum > 0)
          ";
          $data = $this->_db->getArray($sql);
          $overSeaCatchArr = $localCatchArr = array();
          if(count($data) > 0){
              foreach ($data as $v){
                  $per7day = ($v['offDays'] > 0 && ($v['offDays'] % 7) == 0)? true : false;// 延后每七天一次
                  if($v['beforeDays'] == 7 || $per7day || $v['arrivalDate'] == date("Y-m-d")){// 在警报天数内的数据(提前7天,当天,延后每七天一次)
                      if($v['areaName'] == "海外"){
                          $overSeaCatchArr[$v['exchangeCode']] = isset($overSeaCatchArr[$v['exchangeCode']])? $overSeaCatchArr[$v['exchangeCode']] : array();
                          $overSeaCatchArr[$v['exchangeCode']][] = $v;
                      }else{
                          $localCatchArr[$v['exchangeCode']] = isset($localCatchArr[$v['exchangeCode']])? $localCatchArr[$v['exchangeCode']] : array();
                          $localCatchArr[$v['exchangeCode']][] = $v;
                      }
                  }
              }
              $title = "销售换货未退货提醒";

              // 归属区域为【海外】的合同换货申请,发送提醒邮件到orderdesk@dingli.com
              if(!empty($overSeaCatchArr)){
                  $mailContent = "";
                  foreach ($overSeaCatchArr as $k => $v){
                      $mailContent .= "换货申请单【{$k}】中的物料:  ";
                      foreach ($v as $k => $vValue){
                          $mailContent .= ($k > 0)? ",【{$vValue['productName']}】" : "【{$vValue['productName']}】";
                      }
                      $mailContent .= " ;<br>";
                  }
                  $mailContent .= "还未退货。";

                  $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','$title','$mailContent','orderdesk@dingli.com','',NOW(),'','','1')";
                  $this->_db->query($sql);
              }

              // 归属区域国内的，【先发后到】的换货流程，邮件提醒杨锦、郑娟娟、梁燕红和我罗权洲
              if(!empty($localCatchArr)){
                  $mailContent = "";
                  foreach ($localCatchArr as $k => $v){
                      $mailContent .= "换货申请单【{$k}】中的物料: ";
                      foreach ($v as $k => $vValue){
                          $mailContent .= ($k > 0)? ", 【{$vValue['productName']}】" : "【{$vValue['productName']}】";
                      }
                      $mailContent .= ";<br>";
                  }
                  $mailContent .= "还未退货。";

                  // 邮件信息
                  $otherDataDao = new model_common_otherdatas();
                  $sendIds = $otherDataDao->getConfig('exchangePer7DaysWarningSendIds');
                  $uIds = "'".str_replace(",","','",rtrim($sendIds,","))."'";
                  $sql = "select GROUP_CONCAT(EMAIL) as address from user where USER_ID in(".$uIds.")";
                  $adrsArr = $this->_db->getArray($sql);
                  $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";

                  $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','$title','$mailContent','$addresses','',NOW(),'','','1')";
                  $this->_db->query($sql);
              }
          }
          return true;
      }
}
?>