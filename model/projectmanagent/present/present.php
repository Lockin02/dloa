<?php
/**
 * @author Administrator
 * @Date 2011年9月13日 14:34:44
 * @version 1.0
 * @description:赠送申请 Model层
 */
 class model_projectmanagent_present_present  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_present_present";
		$this->sql_map = "projectmanagent/present/presentSql.php";
		parent::__construct ();
	}


	/**
	 * 查看合同信息
	 */
	function OrderView($orderId,$orderType,$orderCode) {

            $orderIdArr = array("id" => $orderId);
            switch($orderType){
               case "oa_sale_order" :
               		$this->sconfig = new model_common_securityUtil ( "order" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
	               $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=projectmanagent_order_order&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
               case "oa_sale_service" :
                   $this->sconfig = new model_common_securityUtil ( "serviceContract" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
                   $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=engineering_serviceContract_serviceContract&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
               case "oa_sale_lease" :
                   $this->sconfig = new model_common_securityUtil ( "rentalcontract" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
                   $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
               case "oa_sale_rdproject" :
                   $this->sconfig = new model_common_securityUtil ( "rdproject" );
                   $skey = $this->sconfig->md5Row ( $orderIdArr);
                  $orderView = '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=rdproject_yxrdproject_rdproject&action=toViewTab&id='.$orderId.'&perm=view&skey='.$skey.'\')">';
	               break;
            }
		return $orderCode.$orderView;
	}

	 /**
	  * 重写get_d
	  */
	  function get_d($id,$selection = null){
	  	 //提取主表信息
	  	 $rows = parent::get_d($id);
	  	 if(empty($selection)){
	  	 	$equDao = new model_projectmanagent_present_presentequ();
	  	 	$rows['presentequ'] = $equDao -> getDetail_d($id);
	  	 }else if(is_array($selection)){
	  	 	if(in_array('borrowequ',$selection)){
				$equDao = new model_projectmanagent_present_presentequ();
				$rows['presentequ'] = $equDao->getDetail_d($id);
			}
	  	 }
	  	 return $rows;
	  }

	/**
	 *赠送编号自动生成
	 */
     function presentCode(){
        $billCode = "ZS".date("Ymd");
//        $billCode = "JL201208";
		$sql="select max(RIGHT(c.Code,4)) as maxCode,left(c.Code,10) as _maxbillCode " .
				"from oa_present_present c group by _maxbillCode having _maxbillCode='".$billCode."'";

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
	 * 重写add_d方法

	 */
	function add_d($object){
		try{
			$this->start_d();
			//产生业务编码
			$codeDao = new model_common_codeRule ();
			$salesNameId=$object['salesNameId'];
			$deptDao=new model_deptuser_dept_dept();
			$dept=$deptDao->getDeptByUserId($salesNameId);
			$object['objCode']=$codeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

            $object['Code'] = $this->presentCode();
			//插入主表信息
			$newId = parent::add_d($object,true);
			//插入从表信息
			//产品
			if (!empty ($object['product'])) {
				$orderequDao = new model_projectmanagent_present_product();
				$orderequDao->createBatch($object['product'], array (
					'presentId' => $newId
				), 'conProductName');
			}
			//设备
			 if(!empty($object['presentequ'])){
			 	$presentequDao = new model_projectmanagent_present_presentequ();
			    $presentequDao->createBatch($object['presentequ'],array('presentId' => $newId ,'presentCode'=>$object['Code']),'productName');

			    $licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $newId, 'objType' => $this->tbl_name , 'extType' => $presentequDao->tbl_name ),
					'presentId',
					'license'
				);
			 }
			$this->commit_d();
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
			//修改主表信息
			parent::edit_d($object,true);

			$presentId = $object['id'];
			//插入从表信息
            //产品
			$productDao = new model_projectmanagent_present_product();
			$productDao->delete(array (
				'presentId' => $presentId
			));
			foreach ($object['product'] as $k => $v) {
				if ($v['isDelTag'] == '1') {
					unset ($object['product'][$k]);
				}
			}
			$productDao->createBatch($object['product'], array (
				'presentId' => $presentId
			), 'conProductName');
			//设备
			$equDao = new model_projectmanagent_present_presentequ();
            $equDao->delete(array('presentId' => $presentId));
			$equDao->createBatch($object['presentequ'],array('presentId' => $presentId ),'productName');

			if($object['presentequ']){
				$licenseDao = new model_yxlicense_license_tempKey();
				$licenseDao->updateLicenseBacth_d(
					array( 'objId' => $object, 'objType' => $this->tbl_name , 'extType' => $equDao->tbl_name ),
					'presentId',
					'license'
				);
			}

			$this->commit_d();
			return true;
		}catch(exception $e){
            $this->rollBack();
			return false;
		}
	}

	/**
	 * 渲染方法 - 查看
	 */
	function initView($object){

        if(!empty($object['presentequ'])){

        	$equDao = new model_projectmanagent_present_presentequ();
        	$object['presentequ'] = $equDao -> initTableView($object['presentequ'],$object['id']);
        }else{
        	$object['presentequ'] = '<tr><td colspan="10">暂无相关信息</td></tr>';
        }
		return $object;
	}


	/**
	 * 渲染方法 - 编辑
	 */
	function initEdit($object){

		//设备
		$equDao = new model_projectmanagent_present_presentequ();
		$rows = $equDao->initTableEdit($object['presentequ']);
		$object['productNumber'] = $rows[0];
		$object['presentequ'] = $rows[1];
		return $object;
	}

  /**
   * 根据单据id 获取正盛单客户信息
   */
   function getCusinfoBypresent($id){
   	    $cusArr =   $this->get_d($id);
   	    $arr = array();
   	    $arr['customerName'] = $cusArr['customerName'];
   	    $arr['customerId'] = $cusArr['customerId'];
   	    return $arr;
   }

	/**
	 * 根据ID 获取全部信息
	 * $$presentId : 主表ID
	 * $getInfoArr 需要的从表信息 例:$getInfoArr = array('equ','product') 默认为空 取全部
	 *      prodcut-产品  equ-物料
	 */
	function getPresentInfo($presentId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'product',
				'equ',
			);
		}
		$daoArr = array (
			"product" => "model_projectmanagent_present_product",
			"equ" => "model_projectmanagent_present_presentequ",
		);
		$presentInfo = parent::get_d($presentId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			$presentInfo[$val] = $dao->getDetail_d($presentId);
		}
		return $presentInfo;
	}

	/**
	 * 根据ID 获取全部信息
	 * $$presentId : 主表ID
	 * $getInfoArr 需要的从表信息 例:$getInfoArr = array('equ','product') 默认为空 取全部
	 *      prodcut-产品  equ-物料
	 */
	function getPresentInfoWithTemp($presentId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'product',
				'equ',
			);
		}
		$daoArr = array (
			"product" => "model_projectmanagent_present_product",
			"equ" => "model_projectmanagent_present_presentequ",
		);
		$presentInfo = parent::get_d($presentId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			if( $val=='product' ){
				$presentInfo[$val] = $dao->getDetailWithTemp_d($presentId);
			}else{
				$presentInfo[$val] = $dao->getDetail_d($presentId);
			}
		}
		return $presentInfo;
	}

	/**
	 * 根据ID 获取全部信息(包括删除的记录)
	 * $$presentId : 主表ID
	 * $getInfoArr 需要的从表信息 例:$getInfoArr = array('equ','product') 默认为空 取全部
	 *      prodcut-产品  equ-物料
	 */
	function getPresentInfoAll($presentId, $getInfoArr = null) {
		if (empty ($getInfoArr)) {
			$getInfoArr = array (
				'product',
				'presentequ',
			);
		}
		$daoArr = array (
			"product" => "model_projectmanagent_present_product",
			"presentequ" => "model_projectmanagent_present_presentequ",
		);
		$presentInfo = parent::get_d($presentId);
		foreach ($getInfoArr as $key => $val) {
			$daoName = $daoArr[$val];
			$dao = new $daoName ();
			$dao->searchArr ['presentId'] = $presentId;
			$presentInfo[$val] = $dao->list_d();
		}
		return $presentInfo;
	}

/***********************************锁定*****************************************************/

     /**
      * 订单处理的设备信息
      */
     function showDetaiInfo($rows) {
     	$orderequDao = new model_projectmanagent_present_presentequ();
     	$rows['orderequ'] =
     	$orderequDao->showDetailByOrder( $orderequDao->showEquListInByOrder($rows['id'],'oa_present_present'));

     	return $rows;
     }
/*******************************变更   开始***************************************************/
    /**
	 * 渲染方法 -变更
	 */
	function initChange($object){
		//设备
		$tentalcontractequDao = new model_projectmanagent_present_presentequ();
		$rows = $tentalcontractequDao->changeTable($object['presentequ']);
		$object['productNumber'] = $rows[0];
		$object['presentequ'] = $rows[1];
		return $object;
	}

   	/**
   	 * 变更处理
	*/
	function change_d($obj) {
		try{
			$this->start_d ();

			//变更记录,拿到变更的临时主对象id
			$changeLogDao = new model_common_changeLog ( 'present' );
			$forArr = array (
				"product",
				"presentequ"
			);
		    if(!empty($obj['presentequ'])){
		  	    foreach($obj['presentequ'] as $key=>$val){
		  	  	   if( empty($val['productId'])|| empty($val['productName'])){
		  	  	 	  unset($obj['presentequ'][$key]);
		  	  	   }
		  	    }
		    }
		    if(isset($obj['tempId']) && $obj['presentId'] != $obj['oldId']){//用于加载了临时保存记录后处理
		    	//合并临时保存记录删除掉的数据
		    	$tempObj = $this->getPresentInfoAll($obj['tempId']);
		    	foreach ($forArr as $key => $val) {
		    		foreach ($tempObj[$val] as $v) {
		    			if($v['isDel'] == '1'){
		    				if(!isset($obj[$val])){
		    					$obj[$val] = array();
		    				}
		    				array_push($obj[$val], $v);
		    			}
		    		}
		    	}
		    	foreach ($forArr as $key => $val) {
		    		foreach ($obj[$val] as $k => $v) {
		    			$obj[$val][$k]['oldId'] = empty($obj[$val][$k]['originalId']) ? '0' : $obj[$val][$k]['originalId'];//从表的originalId对应源单的id
		    		}
		    	}
		    }else{
		    	foreach ($forArr as $key => $val) {
		    		foreach ($obj[$val] as $k => $v) {
		    			$obj[$val][$k]['oldId'] = $obj[$val][$k]['id'];
		    		}
		    	}
		    }
			$tempObjId = $changeLogDao->addLog ( $obj );
			//删除本次加载的临时变更记录(若有)
			if(!empty($obj['tempId'])){
				$sql = "select id,ExaStatus from oa_present_changlog where objType = 'present' and tempId=".$obj['tempId'];
				$rs = $this->_db->getArray($sql);
				if(!empty($rs)){
					//取消加载变更记录，变更审批打回的变更记录不删除
					if($rs[0]['ExaStatus'] != '打回' || ($rs[0]['ExaStatus'] == '打回' && $obj['oldId'] != $obj['presentId'])){
						$delSql = "delete from oa_present_changedetail where parentId=".$rs[0]['id'];
						$this->_db->query($delSql);
						$delSql = "delete from oa_present_changlog where objType = 'present' and tempId=".$obj['tempId'];
						$this->_db->query($delSql);
					}
				}
			}
			if(!empty($tempObjId)){
                $this->updateIstempEquConProduct($tempObjId);
				if($obj['isSub'] == '0'){//保存时，将临时变更记录的审批状态改为保存
					$updateSql = "update oa_present_changlog set ExaStatus = '保存' where objType = 'present' and tempId=".$tempObjId;
				}else{//提交时，将变更记录的审批状态改为审批中
					$updateSql = "update oa_present_changlog set ExaStatus = '审批中' where objType = 'present' and tempId=".$tempObjId;
				}
				$this->_db->query($updateSql);
			}

			$this->commit_d ();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}

	}
	/******************************/
	/**
	 * 判断是否为变更的合同
	 */
	function isTemp($conId) {
		$cond = array (
			"id" => $conId
		);
		$isTemp = $this->find($cond, '', 'isTemp');
		$isTemp = implode(',', $isTemp);
		return $isTemp;
	}

     /**
      * 更新临时记录物料的产品ID
      *
      * @param $presentId //临时记录的ID
      */
	function updateIstempEquConProduct($presentId){
	    $equDao = new model_projectmanagent_present_presentequ();
        $equSql = "select id,originalId,parentEquId,conProductId from oa_present_equ where presentId = {$presentId} and (parentEquId <> 0 or conProductId <> 0);";
        $productSql = "select id,originalId from oa_present_product where presentId = {$presentId};";
        $equArr = $this->_db->getArray($equSql);
        $productArr = $this->_db->getArray($productSql);

        // 更新物料关联的产品ID
        echo"<pre>";
        $equOriginalArr = array();
        foreach ($equArr as $equK => $equV){
            $equOriginalArr[$equV['originalId']] = $equV['id'];
            foreach ($productArr as $productK => $productV){
                if($equV['conProductId'] == $productV['originalId']){
                    $equArr[$equK]['conProductId'] = $productV['id'];
                    break;
                }
            }
            $arr = array("id"=>$equV['id'],"conProductId"=>$equArr[$equK]['conProductId']);
            $equDao->updateById($arr);
        }

        // 更新物料配件关联的物料ID
        foreach ($equArr as $equK => $equV){
            if($equV['parentEquId'] != 0){
                $arr = array("id"=>$equV['id'],"parentEquId" => $equOriginalArr[$equV['parentEquId']]);
                $equDao->updateById($arr);
            }
        }
    }
   /*******************************变更   end***************************************************/


/********************************************************发货相关******************************************************************************/


	/**
	 * 根据合同id修改合同及发货计划状态
	 */
	 function updateOutStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_present_equ o where o.presentId=".$id." and o.isTemp=0 and o.isDel=0) as executeNum
						 from (select e.presentId,(e.number-e.executedNum) as remainNum from oa_present_equ e
						where e.presentId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
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
        }else
        	echo 0;
    }


	/**
	 * 根据发货情况修改合同及发货计划状态
	 */
	 function updateShipStatus_d( $id ){
	 	$orderRemainSql = "select count(0) as countNum,(select sum(o.issuedShipNum) from oa_present_equ o where o.presentId=".$id." and o.isTemp=0 and o.isDel=0) as issuedShipNum
						 from (select e.presentId,(e.number-e.issuedShipNum) as remainNum from oa_present_equ e
						where e.presentId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
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
      * 根据发货情况修改合同及发货计划状态
      * (关联PMS2381, 恢复按钮访问的是下面这个未定义的函数,为了不影响其他地方上面那个函数,及时相似,还是重新定义了一个)
      */
	 function updateOrderShipStatus_d( $id ){
         $orderRemainSql = "select count(0) as countNum,(select sum(o.issuedShipNum) from oa_present_equ o where o.presentId=".$id." and o.isTemp=0 and o.isDel=0) as issuedShipNum
						 from (select e.presentId,(e.number-e.issuedShipNum) as remainNum from oa_present_equ e
						where e.presentId=".$id." and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
         $remainNum = $this->_db->getArray( $orderRemainSql );
         if( $remainNum[0]['countNum'] <= 0 ){//已发货
             $statusInfo = array(
                 'id' => $id,
                 'DeliveryStatus' => 'YFH',
                 'makeStatus' => 'YXD'
             );
         }elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['issuedShipNum']==0 ){//未发货
             $statusInfo = array(
                 'id' => $id,
                 'DeliveryStatus' => 'WFH',
                 'makeStatus' => 'WXD'
             );
         } else {//部分发货
             $statusInfo = array(
                 'id' => $id,
                 'DeliveryStatus' => 'BFFH',
                 'makeStatus' => 'BFXD'
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
					$addAllAmount += $chdVal['number']-$chdVal['executedNum'];
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
						    	$chdVal[code]
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
  * 更新旧数据
  */
  function updatePresent_d(){
	try {
		$this->start_d();
	  	$objArr = $this->list_d();
		$linkdao = new model_projectmanagent_present_presentequlink();
		if( is_array($objArr)&&count($objArr)>0 ){
			$mainSql = "update oa_present_present set dealStatus='1'";
			$this->_db->query($mainSql);
		  	foreach( $objArr as $key=>$val ){
		  		$presentId = $val['id'];
				//物料确认审批表
				$link = array (
					"presentId" => $presentId,
					"rObjCode" => $val['objCode'],
					"presentCode" => $val['Code'],
					"presentName" => '',
					"presentType" => "oa_present_present",
					"ExaStatus" => '完成',
					"ExaDTOne" => $val['ExaDT'],
					"ExaDT" => $val['ExaDT'],
					"changeTips" => 0,
					"updateTime" => $val['updateTime'],
					"updateId" => $val['updateId'],
					"updateName" => $val['updateName'],
					"createTime" => $val['createTime'],
					"createName" => $val['createName'],
					"createId" => $val['createId']
				);
				$linkArr[$presentId] = $linkdao->create($link); //缓存linkId
		  	}
		  	if( is_array($linkArr)&&count($linkArr)>0 ){
			  	foreach( $linkArr as $key=>$val ){
			  		$itemSql = "update oa_present_equ set linkId=".$val." where presentId=".$key;
					$this->_db->query($itemSql);
			  	}
		  	}
		}
		$this->commit_d();
		return true;
	} catch (exception $e) {
		$this->rollBack();
		return $e;
	}
  }


     /**
      * 关闭物料确认
      */
      function setEmailAfterCloseConfirm($id){
        try{
			$this->start_d();
			$linkDao = new model_projectmanagent_present_presentequlink();
			$linkDao->update( array('presentId'=>$id),array('ExaStatus'=>'完成','ExaDT'=>day_date) );
			$addMsg = '该赠送申请无需变更物料确认。';
		 	$mainObj = $this->get_d($id);
		 	$updateKey = array(
		 		'dealStatus' => '3'
		 	);
		 	$this->update(array('id'=>$id),$updateKey);
		 	$outmailArr=array(
		 		$mainObj['salesNameId']
		 	);
		 	$outmailStr = implode(',',$outmailArr);
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL']
			, 'oa_present_equ', '关闭', $mainObj['Code'], $outmailStr, $addMsg, '1');

			$this->commit_d();
			return 1;
		}catch(exception $e){
			$this->rollBack();
			return 0;
		}
      }
    /**
     * workflow callback
     */
     function workflowCallBack($spid){
     	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$contract = $this->get_d ( $objId );

			$changeLogDao = new model_common_changeLog ( 'present' );
            $changeLogDao->confirmChange_d ( $contract );
			if ($contract ['ExaStatus'] == "完成") {
                //修改确认状态
                $dealStatusSql = "update oa_present_present set dealStatus=1,standardDate=".$contract['standardDate'].",costEstimates='".$contract['costEstimates']."',costEstimatesTax='".$contract['costEstimatesTax']."' where id=" . $contract['originalId'] . "";
                $this->query($dealStatusSql);

                //更新物料关联产品ID
                $getEqusSql = "select * from oa_present_equ where presentId = ".$contract['originalId'];
                $equs = $this->_db->getArray($getEqusSql);
                if($equs){
                    foreach ($equs as $equ){
                        $getProductsSql = "select originalId from oa_present_product where id = ".$equ['conProductId'];
                        $products = $this->_db->getArray($getProductsSql);
                        if($products && isset($products[0]['originalId']) && $products[0]['originalId'] != '0'){
                            $dealEquConProductIdSql = "update oa_present_equ set conProductId=".$products[0]['originalId']." where id=" . $equ['id'] . "";
                            $this->query($dealEquConProductIdSql);
                        }
                    }
                }

                $this->updateOutStatus_d($contract['originalId']);
                $this->updateShipStatus_d($contract['originalId']);
            }
		}
     }

     function workflowCallBack_equConfirm($spid){
         $otherdatas = new model_common_otherdatas();
         $folowInfo = $otherdatas->getWorkflowInfo($spid);
         $presentequDao = new model_projectmanagent_present_presentequ();
         $linkDao = new model_projectmanagent_present_presentequlink();
         $objId = $folowInfo['objId'];
         $linkObj = $linkDao->findBy('presentId', $objId);

         if (!empty ($objId)) {
             $presentObj = $this->get_d($objId);
             if($presentObj['ExaStatus'] == '完成'){
                 // 更新原单关联数据
                 $dateObj['dealStatus']=1;
                 $linkObj['ExaStatus']='完成';
                 $linkObj['ExaDT']=day_date;
                 $linkObj['ExaDTOne']=day_date;
                 $linkDao->edit_d($linkObj);
                 $this->updateById($dateObj);

                 // 发货处理
                 $object['id'] = $objId;
                 $this->updateShipStatus_d($object['id']);
                 $this->updateOutStatus_d($object['id']);
                 $presentequDao->sendMailAtAudit($object,'提交');
             }
         }
     }

     /**
      * 根据赠送合同id获取最近一次变更记录id
      */
     function findChangeId($id)
     {
         $sql = "select max(id) as Mid from oa_present_present where originalId = $id";
         $idArr = $this->_db->getArray($sql);
         return $idArr[0]['Mid'];
     }
 }
?>