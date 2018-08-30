<?php
/**
 *
 * 统一源单处理
 * @author chengl
 *
 */
class controller_stock_withdraw_withcommon extends controller_base_action {

	function __construct() {
		$this->objName = "withcommon";
		$this->objPath = "stock_withdraw";
		parent::__construct ();
	}

	/**
	 * 批量产生业务编号
	 */
	function c_createObjCode(){
		$objType=$_GET['objType'];
		try{
			$this->service->batchCreateObjCode($objType);
			echo 1;
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	/**
	 * 跳转到更新业务编码页面
	 */
	function c_toUpdateObjCode(){
		$this->view("update");
	}

    /**
     * 更新旧系统合同数据至新系统跳转页面
     */
     function c_updateOldToNewContract(){
        $this->view("updateOldcontract");
     }
     /**
      * 更新旧数据方法
      */
      function c_updateOldcontract(){
      	   set_time_limit(0);
      	   $objType=$_GET['objType'];
      	   if($objType=="lock"){//锁定更新
				$msg = $this->service->updateLock_d();
      	   }else if($objType=="change"){//变更更新
				$msg = $this->service->updateChange_d($_REQUEST['contractType']);
      	   }else if($objType=="signin"){//签收更新
				$msg = $this->service->updateSignin_d();
      	   }else if($objType=="changerelation"){//变更关联
				$msg = $this->service->updateChangeRelate();
      	   }else{
      	   		//合同更新
	      	   //$msg = $this->service->updateOldcontract_d($objType);
      	   }
          echo $msg;
      }
	/**
	 * 合同发货列表从表数据获取
	 */
	function c_equJson() {
		$service = $this->service;
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo ();
		$lockDao = new model_stock_lock_lock ();
		$contType = $_POST ['docType'];
		$daoName = $service->getSourceEquDao($contType);
		$service = new $daoName ();
		$service->asc = false;
		$service->getParam ( $_POST );
		$service->searchArr ['isDel'] = 0;
		$service->searchArr ['isTemp'] = 0;
		$rows = $service->list_d ();
//		echo "<pre>";
//		print_R($service->searchArr);
		if( $contType== 'oa_borrow_borrow' ){
			$stockType='outStockCode';
		}else{
			$stockType='salesStockCode';
		}
		foreach ( $rows as $key => $val ) {
			$rows [$key] ['lockNum'] = $lockDao->getEquStockLockNum ( $rows [$key] ['id'],null,$_POST ['docType'] );
			if( $rows [$key] ['productId'] ){
				$rows [$key] ['exeNum'] = $inventoryDao->getExeNumsByStockType ( $rows [$key] ['productId'], $stockType );
			}else{
				$rows [$key] ['exeNum']=0;
			}
		}
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到设置发货需求类型页面
	 */
	function c_toSetType(){
		$typeDao = new model_projectmanagent_shipment_shipmenttype();
		$fields = "id,type";
		$daoName = $this->service->getSourceBaseDao($_GET['docType']);
		$docDao = new $daoName();
		$docInfo = $docDao->get_d($_GET['id']);
		$typeInfo = $typeDao->findAll(null,null,$fields);
		foreach( $typeInfo as $key=>$val ){
			$typeInfo[$key]['code']=$val['id'];
			$typeInfo[$key]['name']=$val['type'];
		}
		$this->showSelectOption('shipmentType',null,true,$typeInfo);
		$this->assign('customTypeId',$docInfo['customTypeId']);
		$this->assign('customTypeName',$docInfo['customTypeName']);
		$this->assign('warnDate',$docInfo['warnDate']);
		$this->assign('id',$_GET['id']);
		$this->assign('docType',$_GET['docType']);
		$this->view("settype");
	}

	/**
	 * setType
	 */
	 function c_setType(){
	 	$rows = $_POST[$this->objName];
	 	$flag = $this->service->setType_d( $rows['id'],$rows['docType'],$rows );
	 	if($flag){
	 		msg('设置成功！');
	 	}
	 }

	 function c_clearCusTypeByIds(){
	 	$this->service->clearCusTypeByIds(2);
	 }


	/**
	 * 关闭发货需求
	 */
	function c_closeCont() {
		switch ($_POST ['type']) {
			case 'oa_contract_contract' :
				$modelName = "contract_contract_contract";
				break;
			case 'oa_borrow_borrow' :
				$modelName = "projectmanagent_borrow_borrow";
				break;
			case 'oa_present_present' :
				$modelName = "projectmanagent_present_present";
				break;
			case 'oa_contract_exchangeapply' :
				$modelName = "model_projectmanagent_exchange_exchange";
				break;
		}
//		$this->permCheck ( $_POST ['id'], $modelName );
		$contType = $_POST ['type'];
		$daoName = $this->service->getSourceBaseDao($contType);
		$service = new $daoName ();
		echo $service->updateDeliveryStatus ( $_POST ['id'] );
	}

	/**
	 * 恢复发货需求状态
	 */
	function c_recoverCont() {
		switch ($_POST ['type']) {
			case 'oa_contract_contract' :
				$modelName = "contract_contract_contract";
				break;
			case 'oa_borrow_borrow' :
				$modelName = "projectmanagent_borrow_borrow";
				break;
			case 'oa_present_present' :
				$modelName = "projectmanagent_present_present";
				break;
		}
		$this->permCheck ( $_POST ['id'], $modelName );
		$contType = $_POST ['type'];
		$daoName = $this->service->getSourceBaseDao($contType);
		$service = new $daoName ();
		echo $service->updateOutStatus_d ( $_POST ['id'] );
	}


	  function c_getBorrwoToOrderequ(){
	  	$docId = $_POST['docId'];
	  	$docType = $_POST['docType'];
	  	$rowNum = $_POST['rowNum'];
        $daoStr = $this->service->getSourceEquDao($docType);
        $dao = new $daoStr();
        $rows = $dao->findAll(array("contractId" => $docId,"isBorrowToorder" => '1'));
        $rows = util_jsonUtil::iconvGB2UTFArr($rows);
        $equStr = $this->shwoBToOEqu($rows,$rowNum);
        echo $equStr;
	  }
     function shwoBToOEqu($rows,$rowNum){
		$str = "";
		$i = $rowNum;
		foreach($rows as $key => $val ){
				$j = $i + 1;
				//已下达数量
				$planNum = $rows[$key]['issuedShipNum'];
				//合同数量
				$contNum = $rows[$key]['number'];
				//待发货数量
				$contRemain = $contNum - $planNum;
				if($contRemain==0){
					continue;
				}
				$line = $j-$rowNum;
				$str .= <<<EOT
					<tr><td>$line
							<td>$val[productCode]
							<input type="hidden" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productCode]" class='readOnlyTxtItem' readonly/>
							<input type="hidden" id="BToOTips$j" name="outplan[productsdetail][$j][BToOTips]" value="1" class="txtmiddle"/></td>
							</td>
						<td>$val[productName]
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$planNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j" value='$planNum' class="txtmiddle"/>
						</td>
						<td>$contRemain
							<input type="hidden" name="outplan[productsdetail][$j][number]" id="number$j" value="$contRemain" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this,'borrowbody')" title="" />
						</td>
					</tr>
EOT;
				$i ++;
			}
		return $str;
     }


     /**
	 * 获取产品下的物料信息(用于物料确认开始带出)
	 */
	function c_getProductEqu() {
		$id = $_POST['conProductId'];
		$sourceType = $_GET['sourceType'];//源单类型：合同，借试用，赠送，换货
		$sourceType=empty($sourceType)?"contract":$sourceType;
		$service = $this->service;
		$equArr = $service->getProductEqu_d($id,$sourceType);
//		echo "<pre>";
//		print_R($equArr);
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		if (is_array($equArr) && count($equArr) > 0) {
			foreach ($equArr as $key => $val) {
				$equArr[$key]['warrantyPeriod'] = $val['warranty'];
//				if ( isset($val['number']) || $val['number']=='') {
//					$equArr[$key]['number'] = 1;
//				}
				$equArr[$key]['money'] = $val['number'] * $val['price'];
				if( $sourceType=='borrow' ){
					$exeNum = $inventoryDao->getExeNumsByStockType($equArr[$key]['productId'], 'outStockCode');
				}else{
					$exeNum = $inventoryDao->getExeNumsByStockType($equArr[$key]['productId'], 'salesStockCode');
				}
				if( empty($exeNum)||!isset($exeNum) ){
					$equArr[$key]['exeNum'] = '0';
				}else{
					$equArr[$key]['exeNum'] = $exeNum;
				}
			}
		}
		$equArr = $this->sconfig->md5Rows($equArr);
		echo util_jsonUtil :: encode($equArr);
	}


/***********************************************合同导入***********begin***************************************************/
	/**
	 * 上传EXCEL
	 */
	function c_upExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'contractTypeName', //合同类型
			1 => 'contractNatureName', //合同属性
			2 => 'winRate', //合同盈率
			3 => 'signSubjectName', //签约公司
			4 => 'contractName', //合同名称
			5 => 'contractCode', //合同编号
			6 => 'prinvipalName', //合同负责人
			7 => 'contractMoney', //合同金额
			8 => 'customerName', //客户名称
			9 => 'beginDate', //合同开始日期
			10 => 'endDate', //合同结束日期
			11 => 'contractSigner', //合同签署人
			12 => 'state',//合同状态
			13 => 'remark' //备注
		);
		$this->c_addExecel($objNameArr, $ExaDT);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExecel($objNameArr, $ExaDT) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$winRateArr = array("0.5"=>"50%","0.8"=>"80%","1"=>"100%");//合同赢率
				$conState = array("执行中"=>"2","已关闭"=>"3","已完成"=>"4");//合同状态
				$arrinfo = array();//导出数据
                //循环处理插入数组
                foreach($objectArr as $key => $val){
                    //判断合同类型和合同属性
                    $datadictDao = new model_system_datadict_datadict();
                    $HTLX = $datadictDao->getCodeByName("HTLX", $val['contractTypeName']);
                    $HTSX = $datadictDao->getCodeByName($HTLX, $val['contractNatureName']);
                   if(empty($HTLX) || empty($HTSX)){
                   	  array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败，合同类型或合同属性错误" ) );
                   }else{
                   	   $objectArr[$key]['contractType'] = $HTLX;
                   	   $objectArr[$key]['contractNature'] = $HTSX;
                   	   //判断合同赢率
                   	   $winRate = $val['winRate'];
                       if(empty($winRateArr[$winRate])){
                          array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,合同赢率错误（50%,80%,100%）" ) );
                       }else{
                       	  $objectArr[$key]['winRate'] = $winRateArr[$winRate];
                       	  //判断签约公司（签约主体）
                       	  $QYZT = $datadictDao->getCodeByName("QYZT", $val['signSubjectName']);
                       	  if(empty($QYZT)){
                             array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,签约公司错误" ) );
                       	  }else{
                       	  	 $objectArr[$key]['signSubject'] = $QYZT;
                       	  	 //判断合同号是否存在
                       	  	 $isCode = $this->service->getContractbyCode($val['contractCode']);
                             if(!empty($isCode)){
                               array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,合同编号错误" ) );
                             }else{
                                //合同负责人和合同签署人
                                $prinvipalNameId = $this->service->user($val['prinvipalName']);
                                $contractSignerId = $this->service->user($val['contractSigner']);
                                if(empty($prinvipalNameId) || empty($contractSignerId)){
                                  array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,合同负责人或合同签署人不存在" ) );
                                }else{
                                	$objectArr[$key]['prinvipalId'] = $prinvipalNameId;
                                	$objectArr[$key]['contractSignerId'] = $contractSignerId;
                                    //判断客户
                                    $cusIdArr = $this->service->isCus($val['customerName']);
                                    $cusId = $cusIdArr[0]['id'];
                                    if(empty($cusId)){
                                       array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,客户不存在" ) );
                                    }else{
                                       //处理客户相关信息
                                      $cusInfo = $this->service->isCusInfo($cusId);
                                      $objectArr[$key]['customerId'] = $cusId;
                                      $objectArr[$key]['customerType'] = $cusInfo['customerType'];
                                      $objectArr[$key]['customerTypeName'] = $datadictDao->getDataNameByCode($cusInfo['customerType']);
                                      $objectArr[$key]['address'] = $cusInfo['address'];
                                      $objectArr[$key]['contractCountry'] = $cusInfo['contractCountry'];
                                      $objectArr[$key]['contractCountryId'] = $cusInfo['contractCountryId'];
                                      $objectArr[$key]['contractProvince'] = $cusInfo['contractProvince'];
                                      $objectArr[$key]['contractProvinceId'] = $cusInfo['contractProvinceId'];
                                      $objectArr[$key]['contractCity'] = $cusInfo['contractCity'];
                                      $objectArr[$key]['contractCityId'] = $cusInfo['contractCityId'];
                                      $objectArr[$key]['areaPrincipal'] = $cusInfo['areaPrincipal'];
                                      $objectArr[$key]['areaPrincipalId'] = $cusInfo['areaPrincipalId'];
                                      $objectArr[$key]['areaName'] = $cusInfo['areaName'];
                                      $objectArr[$key]['areaCode'] = $cusInfo['areaCode'];
                                      //处理时间戳
                                      $objectArr[$key]['beginDate'] = $this->service->transitionTime($val['beginDate']);
                                      $objectArr[$key]['endDate'] = $this->service->transitionTime($val['endDate']);
                                      //处理合同状态
                   	                  $state = $val['state'];
                   	                  if(empty($conState[$state])){
                   	                  	array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,合同状态错误(执行中,已完成,已关闭)" ) );
                   	                  }else{
                                          $objectArr[$key]['state'] = $conState[$state];
                                          $objectArr[$key]['ExaDT'] = $ExaDT['ExaDT'];
                                          $objectArr[$key]['ExaDTOne'] = $ExaDT['ExaDT'];
                                          $objectArr[$key]['currency'] = "人民币";
                                          $objectArr[$key]['rate'] = "1";
                                          $objectArr[$key]['ExaStatus'] = "完成";
                                          $objectArr[$key]['isImport'] = "1";
                                          //业务编号
                                          $prinvipalId = $objectArr[$key]['prinvipalId'];
											$orderCodeDao = new model_common_codeRule();
											$deptDao = new model_deptuser_dept_dept();
											$dept = $deptDao->getDeptByUserId($prinvipalId);
											$objectArr[$key]['objCode'] = $orderCodeDao->getObjCode($objectArr[$key]['contractType'], $dept['Code']);
                                          $conDao = new  model_contract_contract_contract();
                                          $newId = $conDao->importAdd_d($objectArr[$key], true);
                                          if($newId){
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "导入成功！" ) );
                                          }else{
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['contractCode'],"cusName" => $val['prinvipalName'],"result" => "新增失败！" ) );
                                          }
                   	                  }
                                   }
                                }
                             }
                       	  }
                       }
                    }

                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "导入结果", array ("合同编号","合同负责人", "结果" ) );
				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}


/***********************************************合同导入***********end***************************************************/

/***********************************************商机导入***********begin***************************************************/
	/**
	 * 上传EXCEL
	 */
	function c_upExceltoChance() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'createTime', //建立时间
			1 => 'chanceCode', //商机编号
			2 => 'status', //商机状态
			3 => 'customerCode', //客户编号
			4 => 'chanceName', //商机名称
			5 => 'prinvipalName', //商机负责人
			6 => 'chanceTypeName', //商机类型
			7 => 'chanceNatureName', //商机属性
			8 => 'chanceMoney', //商机总额
			9 => 'winRate', //商机赢率
			10 => 'chanceStage', //商机阶段
			11 => 'predictContractDate', //预计合同签署日期
			12 => 'predictExeDate', //预计合同执行日期
			13 => 'contractPeriod', //合同执行周期
			14 => 'contractCode',//合同号
			15 => 'contractTurnDate', //转合同日期
			16 => 'progress',//项目进展描述
			17 => 'competitor'//项目竞争对手
		);
		$this->c_addExeceltoChance($objNameArr);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExeceltoChance($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}

				$winRateArr = array("0"=>"0","0.25"=>"25","0.5"=>"50","0.8"=>"80","1"=>"100");//合同赢率
				$StateArr = array("关闭"=>"3","已生成合同"=>"4","跟踪中"=>"5","暂停"=>"6");//状态
				$arrinfo = array();//导出数据

                //循环处理插入数组
                foreach($objectArr as $key => $val){
                    //判断商机类型和商机属性
                    $datadictDao = new model_system_datadict_datadict();
                    $SJLX = $datadictDao->getCodeByName("SJLX", $val['chanceTypeName']);
                    $SJSX = $datadictDao->getCodeByName($SJLX, $val['chanceNatureName']);
                    $SJJD = $datadictDao->getCodeByName("SJJD", $val['chanceStage']);
                    $chanceCode = $this->service->findChanceCode($val['chanceCode']);
                  if(empty($val['chanceCode']) || !empty($chanceCode)){
                  	  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,商机编号为空或已存在" ) );
                  }else{
                  	if(empty($SJLX) || empty($SJJD)){
                   	  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败，商机类型或商机阶段错误" ) );
                   }else{
                   	   $objectArr[$key]['chanceType'] = $SJLX;
                   	   $objectArr[$key]['chanceNature'] = $SJSX;
                   	   $objectArr[$key]['chanceStage'] = $SJJD;
                   	   //判断赢率
                   	   $winRate = $val['winRate'];
//                   	   $winRateName = $datadictDao->getDataNameByCode($winRate,$returnType = 'name');
                       if((empty($winRate)) && $winRate != '0'){
                          array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,赢率填写错误" ) );
                       }else{

                       	  $objectArr[$key]['winRate'] = $winRate * 100;
                                //负责人
                                $prinvipalArr = $this->service->userArr($val['prinvipalName']);
                                if(empty($prinvipalArr)){
                                  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,商机负责人不存在" ) );
                                }else{
                                	$objectArr[$key]['prinvipalId'] = $prinvipalArr['USER_ID'];
                                	$objectArr[$key]['prinvipalDept'] = $prinvipalArr['DEPT_NAME'];
                                	$objectArr[$key]['prinvipalDeptId'] = $prinvipalArr['DEPT_ID'];
                                	if(empty($val['customerCode'])){
                                		array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,客户编号为空" ) );
                                	}else{
                                		 //判断客户
                                    $cusIdArr = $this->service->isCusCode($val['customerCode']);
                                    $cusId = $cusIdArr[0]['id'];
                                    if(empty($cusId)){
                                       array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,客户不存在" ) );
                                    }else{
                                       //处理客户相关信息
                                      $cusInfo = $this->service->isCusInfo($cusId);
                                      $objectArr[$key]['customerId'] = $cusId;
                                      $objectArr[$key]['customerName'] = $cusInfo['customerName'];
                                      $objectArr[$key]['customerType'] = $cusInfo['customerType'];
                                      $objectArr[$key]['customerTypeName'] = $datadictDao->getDataNameByCode($cusInfo['customerType']);
                                      $objectArr[$key]['Country'] = $cusInfo['contractCountry'];
                                      $objectArr[$key]['CountryId'] = $cusInfo['contractCountryId'];
                                      $objectArr[$key]['Province'] = $cusInfo['contractProvince'];
                                      $objectArr[$key]['ProvinceId'] = $cusInfo['contractProvinceId'];
                                      $objectArr[$key]['City'] = $cusInfo['contractCity'];
                                      $objectArr[$key]['CityId'] = $cusInfo['contractCityId'];
                                      $objectArr[$key]['areaPrincipal'] = $cusInfo['areaPrincipal'];
                                      $objectArr[$key]['areaPrincipalId'] = $cusInfo['areaPrincipalId'];
                                      $objectArr[$key]['areaName'] = $cusInfo['areaName'];
                                      $objectArr[$key]['areaCode'] = $cusInfo['areaCode'];
                                      //处理时间戳
                                      $objectArr[$key]['createTime'] = $this->service->transitionTime($val['createTime']);
                                      $objectArr[$key]['createName'] = $_SESSION['USERNAME'];
                                      $objectArr[$key]['createId'] = $_SESSION['USER_ID'];

                                      $objectArr[$key]['predictContractDate'] = $this->service->transitionTime($val['predictContractDate']);
                                      $objectArr[$key]['predictExeDate'] = $this->service->transitionTime($val['predictExeDate']);
                                      //处理合同状态
                   	                  $state = $val['status'];
                   	                  if(empty($StateArr[$state])){
                   	                  	array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入失败,商机状态错误" ) );
                   	                  }else{
                                          $objectArr[$key]['status'] = $StateArr[$state];
                                          $objectArr[$key]['isImport'] = "1";
                                          $chanceDao = new  model_projectmanagent_chance_chance();
                                          $newId = $chanceDao->importAdd_d($objectArr[$key], false);
                                          if($newId){
                                              //处理竞争对手
                                             if(!empty($val['competitor'])){
                                                $isCom = $this->chanceCompetitor($val['competitor'],$newId);
                                                if(!$isCom){
                                                    array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "竞争对手格式不正确，商机已插入成功，请在系统内手工更新对手信息" ) );
                                                }
                                             }
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "导入成功！" ) );
                                          }else{
                                          	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['prinvipalName'],"result" => "新增失败！" ) );
                                          }
                                }
                             }
                                	}
                       	  }
                       }
                    }
                  }
                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "导入结果", array ("商机编号","商机负责人", "结果" ) );
				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}

/**
 * 处理商机竞争对手
 */
function chanceCompetitor($competitor,$newId){
   $competitorArr = explode("/",$competitor);
   //处理竞争对手数组
   foreach($competitorArr as $k=>$v){
   	  $competitorInfo[$k] = array("competitor"=>$v);
   }
  if(is_array($competitorArr)){
    //对手
	$competitor = new model_projectmanagent_chance_competitor();
	$competitor->createBatch($competitorInfo, array (
		'chanceId' => $newId
	), 'competitor');
	 return true;
  }
     return false;
}


	/**
	 * 上传EXCEL
	 */
	function c_upExceltoChanceGoods() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'chanceCode', //商机编号
			1 => 'goodsId', //产品代码
			2 => 'goodsName', //产品名称
			3 => 'number', //产品数量
			4 => 'money', //总金额
		);
		$this->c_addExeceltoChanceGoods($objNameArr);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_addExeceltoChanceGoods($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}
				$arrinfo = array();//导出数据
                //循环处理插入数组
                foreach($objectArr as $key => $val){
                  if(empty($val['chanceCode']) || empty($val['goodsId'])){
                     array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "导入失败,商机编号为空或产品代码为空" ));
                  }else{
                  	  $chanceInfo = $this->service->findChanceCode($val['chanceCode']);
	                  if(empty($chanceInfo[0]['id'])){
	                  	  array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "导入失败,商机编号不存在" ));
	                  }else{
	                  	 $objectArr[$key]['chanceId'] = $chanceInfo[0]['id'];
	                  	 $goodsDao = new model_goods_goods_goodsbaseinfo();
	                  	 $goodsInfo = $goodsDao->get_d($val['goodsId']);
	                  	 if(empty($goodsInfo)){
	                  	 	array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "导入失败,产品信息不存在" ));
	                  	 }else{
                            $objectArr[$key]['goodsTypeId'] = $goodsInfo['goodsTypeId'];
                            $objectArr[$key]['goodsTypeName'] = $goodsInfo['goodsTypeName'];
                            $objectArr[$key]['goodsName'] = $goodsInfo['goodsName'];
                            $chanceDao = new  model_projectmanagent_chance_goods();
                            $newId = $chanceDao->add_d($objectArr[$key], false);
                            if($newId){
                              	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "导入成功！" ) );
                              }else{
                              	 array_push ( $arrinfo, array ("orderCode" => $val['chanceCode'],"cusName" => $val['goodsId'],"result" => "新增失败！" ) );
                              }
	                  	 }
	                  }
                  }
                }

	            if ($arrinfo){
				  echo util_excelUtil::showResultOrder ( $arrinfo, "导入结果", array ("商机编号","产品代码", "结果" ) );
				}
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}

	/**
	 * 上传EXCEL
	 */
	function c_updateChance() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);

		$ExaDT = $_POST['import'];
		$objNameArr = array (
			0 => 'createTime', //建立时间
			1 => 'chanceCode', //商机编号
			2 => 'status', //商机状态
			3 => 'customerCode', //客户编号
			4 => 'chanceName', //商机名称
			5 => 'prinvipalName', //商机负责人
			6 => 'chanceTypeName', //商机类型
			7 => 'chanceNatureName', //商机属性
			8 => 'chanceMoney', //商机总额
			9 => 'winRate', //商机赢率
			10 => 'chanceStage', //商机阶段
			11 => 'predictContractDate', //预计合同签署日期
			12 => 'predictExeDate', //预计合同执行日期
			13 => 'contractPeriod', //合同执行周期
			14 => 'contractCode',//合同号
			15 => 'contractTurnDate', //转合同日期
			16 => 'progress',//项目进展描述
			17 => 'competitor'//项目竞争对手
		);
		$this->c_updateChanceDate($objNameArr);
	}

	/**
	 * 上传EXCEl并导入其数据
	 */
	function c_updateChanceDate($objNameArr) {
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$excelData = array ();
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$upexcel = new model_contract_common_allcontract();
			$excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
			spl_autoload_register('__autoload'); //改变加载类的方式
			if ($excelData) {
				$objectArr = array ();
				foreach ($excelData as $rNum => $row) {
					foreach ($objNameArr as $index => $fieldName) {
						$objectArr[$rNum][$fieldName] = $row[$index];
					}
				}

				$arrinfo = array();//导出数据

                //循环处理插入数组
                foreach($objectArr as $key => $val){

                     //处理时间戳
                     $predictContractDate = $this->service->transitionTime($val['predictContractDate']);//预计合同签署日期
                     $predictExeDate = $this->service->transitionTime($val['predictExeDate']);//预计合同执行日期
                     $contractTurnDate = $this->service->transitionTime($val['contractTurnDate']);//转合同日期
                    $sql = "update oa_sale_chance set predictContractDate='".$predictContractDate."',predictExeDate='".$predictExeDate."',contractTurnDate='".$contractTurnDate."' where chanceCode='".$val['chanceCode']."'";
                    $sqlTiming = "update oa_sale_chance_timing set predictContractDate='".$predictContractDate."',predictExeDate='".$predictExeDate."',contractTurnDate='".$contractTurnDate."' where chanceCode='".$val['chanceCode']."'";
                    $this->service->query($sql);
                    $this->service->query($sqlTiming);
                }
				  echo "更新完成！";
			} else {
				echo "文件不存在可识别数据!";
			}
		} else {
			echo "上传文件类型不是EXCEL!";
		}

	}

/***********************************************商机导入***********end***************************************************/
	/**
	 * 判断源单是否有产品
	 * 2012.09.19
	 * zengzx
	 */
	function c_hasProduct() {
		$docType = $_POST ['type'];
		$daoName = $this->service->getSourceProDao($docType);
		$Dao = new $daoName ();
		$source = $this->service->sourceArr[$docType];
		$mainField = $source['mainField'];
		$sql = "select count(*) as proNum from " . $Dao->tbl_name . " where ".$mainField."= " . $_POST ['id'];
		$cusInfo = $Dao->_db->getArray ( $sql );
		echo $cusInfo [0] ['proNum'];
	}

	/**
	 * 关闭物料确认
	 * 2012.09.19
	 * zengzx
	 */
	function c_closeConfirm() {
		$docType = $_POST ['docType'];
		$daoName = $this->service->getSourceBaseDao($docType);
		$Dao = new $daoName ();
		if( isset($_POST['id']) ){
			echo $Dao->setEmailAfterCloseConfirm($_POST['id']);
		}else{
			echo 0;
		}
	}



}
?>
