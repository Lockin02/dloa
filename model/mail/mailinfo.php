<?php

/**
 * 邮寄信息model层类
 */
class model_mail_mailinfo extends model_base {

	function __construct() {
		$this->tbl_name = "oa_mail_info";
		$this->sql_map = "mail/mailinfoSql.php";
		parent::__construct ();

		$this->mailStatus = array ('未确认','已确认' ); //邮寄任务状态
	}

	/**
	 * 邮寄申请类型
	 */
	private $mailType = array(
		'YJSQDLX-FPYJ' => 'invoice'
	);

	/**
	 * 获取邮寄类型对应编码
	 */
	function getObjCode($thisVal){
		return $this->mailType[$thisVal];
	}


	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/

	function showlist($rows, $showpage) {
		$str = ""; //返回的模板字符串
		if ($rows) {
			$i = 0; //列表记录序号
			$datadictDao = new model_system_datadict_datadict ();

			foreach ( $rows as $key => $val ) {
				$mailType = $datadictDao->getDataNameByCode ( $val ['mailType'] );
				$mailStatus = $this->mailStatus [$val ['mailStatus'] - 1];
				$i ++;
				$str .= <<<EOT
						<tr id="tr_$val[id]">
							<td><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td align="center">$i</td>
							<td align="center">$val[mailNo]</td>
							<td align="center">$val[receiver]</td>
							<td align="center">$val[tel]</td>
							<td align="center">$val[mailTime]</td>
							<td align="center">$mailType</td>
							<td align="center">$mailStatus</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=mail_mailinfo&action=init&perm=view&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="修改邮寄信息" class="thickbox">修改</a>
								<a href="?model=mail_mailsign&action=toAdd&mailInfoId=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700" title="客户签收" class="thickbox">客户签收</a>

							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str . '<tr><td colspan="50" style="text-align:center;">' . $showpage->show ( 6 ) . '</td></tr>';
	}

	/**
	 * 页面显示动态邮寄申请产品调用方法,返回字符串给页面模板替换，用于修改到货申请
	 */
	function showproductsEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						<input type="hidden" id="productId$j" name="mailinfo[productsdetail][$j][productId]" value="$val[productId]"/>
						<input type="hidden" id="productNo$j" name="mailinfo[productsdetail][$j][productNo]" value="$val[productNo]"/>
						<input type="text" id="productName$j" class="txtlong" name="mailinfo[productsdetail][$j][productName]" value="$val[productName]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][mailNum]" value="$val[number]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][remark]" value="$val[remark]"/>
					</td>
					<td align="center">
						<img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='删除行'>
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}


	/**
	 * 发货邮寄编辑页面清单显示
	 */
	function showShipEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						<input type="hidden" id="mailInfoId" name="mailinfo[productsdetail][mailInfoId]" value="$val[mailInfoId]"/>
						<input type="hidden" id="mailInfoId$j" name="mailinfo[productsdetail][$j][mailInfoId]" value="$val[mailInfoId]"/>
						<input type="hidden" id="productId$j" name="mailinfo[productsdetail][$j][productId]" value="$val[productId]"/>
						<input type="hidden" id="productNo$j" name="mailinfo[productsdetail][$j][productNo]" value="$val[productNo]"/>
						<input type="text" id="productName$j" class="txtlong" name="mailinfo[productsdetail][$j][productName]" value="$val[productName]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][mailNum]" value="$val[mailNum]"/>
					</td>
					<td align="center">
						<input type="text" class="txtmiddle" name="mailinfo[productsdetail][$j][remark]" value="$val[remark]"/>
					</td>
					<td align="center">
						<img src='images/closeDiv.gif' onclick='mydel(this,"productslist")' title='删除行'>
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}

	/**查看邮寄信息模板
	*author can
	*2011-4-19
	*/
	function showMailInfo($rows){
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$str .= <<<EOT
				<tr>
					<td>$j</td>
					<td align="center">
						$val[productName]
					</td>
					<td align="center">
						$val[mailNum]
					</td>
					<td align="center">
						$val[remark]
					</td>
				</tr>
EOT;
				$i ++;
			}

		}
		return array( $str,$j );
	}

	/**
	 * 添加对象
	 */
	function add_d($object) {
		//print_r($object);
		try {
			$this->start_d ();
			//$object ['mailStatus'] = 1;
			if (empty ( $object ['mailApplyId'] )) {
				unset ( $object ['mailApplyId'] );
			}
			$productsDetailDao = new model_mail_mailproductsdetail ();
			$mailapplyDao = new model_mail_mailapply();
			$searchArr = array( 'id' => $object['mailApplyId'] );
			$mailinfoId = parent::add_d ( $object, true );
			if (! empty ( $object ['productsdetail'] )) {
				foreach ( $object ['productsdetail']  as $key => $value ) {
					$value ['mailInfoId'] = $mailinfoId;
					$productsDetailDao->add_d ( $value );
				}

			}
			$mailapplyDao->updateField( $searchArr , 'status', '2' );
			$this->commit_d ();
			return $mailinfoId;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}


	/**
	 * 添加对象
	 */
	function addShip_d($object) {
		try {
			$this->start_d ();
			$object ['mailStatus'] = 0;

            //获取邮寄记录
            $emailArr = $object['email'];
            unset($object['email']);

			$productsDetailDao = new model_mail_mailproductsdetail ();
			$shipDao = new model_stock_outplan_ship();
			$searchArr = array(
				'id' => $object['docId']
			);
			$shipDao->updateField( $searchArr,'mailCode',$object['mailNo'] );
			$auditNameStr = str_replace('，',',',$object['mailNo']);
			$auditNameStr1 = str_replace(',','/',$auditNameStr);
			$mailCodeArr = explode('/',$auditNameStr1);
			$mailObjArr = array();
			foreach ( $mailCodeArr as $key=>$val ){
				$mailObjArr[$key]=$object;
				$mailObjArr[$key]['mailNo'] = $mailCodeArr[$key];
				if($key!=0){
					unset($mailObjArr[$key]['productsdetail']);
				}
			}
			foreach( $mailObjArr as $key=>$val ){
				$mailinfoId = parent::add_d ( $mailObjArr[$key], true );
				if ( $key == 0 && !empty ( $mailObjArr[$key] ['productsdetail'] )) {
					foreach ( $mailObjArr[$key] ['productsdetail']  as $key => $value ) {
						$value ['mailInfoId'] = $mailinfoId;
						$productsDetailDao->add_d ( $value );
					}

				}
			}
            //发送邮件
            if( $object['ismail'] == '1'&& !empty($emailArr['TO_ID'])){
                $this->thisMailForShip_d($emailArr,$object);
            }
			$this->commit_d ();
			return $mailinfoId;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}


	/**
	 * 根据主键修改对象
	 */
	function edit_d($mail) {
		try {
			$this->start_d ();
			$productsDetailDao = new model_mail_mailproductsdetail ();
			//删除邮寄产品信息
			$productsDetailDao->deleteProductsByMailId ( $mail ['id'] );
			//邮寄产品明细
			if (is_array ( $mail ['productsdetail'] )) {
				foreach ( $mail ['productsdetail'] as $key => $mailproduct ) {
					if (! empty ( $mailproduct ['productName'] )) {
						$mailproduct ['mailInfoId'] = $mail [id];
						$productsDetailDao->add_d ( $mailproduct );
					}
				}

			}
			$apply = parent::edit_d ( $mail, true );

			$this->commit_d ();
			return $apply;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/**
	 * 根据主键修改对象
	 */
	function shipEdit_d($mail) {
		try {
//			echo "<pre>";
//			print_R( $mail );
			$this->start_d ();
			$productsDetailDao = new model_mail_mailproductsdetail ();
			//删除邮寄产品信息
			$productsDetailDao->deleteProductsByMailId ( $mail['productsdetail']['mailInfoId'] );
			unset( $mail ['productsdetail']['mailInfoId'] );
			//邮寄产品明细
			if (is_array ( $mail ['productsdetail'] )) {
				foreach ( $mail ['productsdetail'] as $key => $mailproduct ) {
					if (! empty ( $mailproduct ['productName'] )) {
						$productsDetailDao->add_d ( $mailproduct );
					}
				}

			}
			$apply = parent::edit_d ( $mail, true );

			$this->commit_d ();
			return $apply;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/*
	 * 获取邮寄信息及邮寄产品
	 */
	function get_d($id) {
		$mailproductDao = new model_mail_mailproductsdetail ();
		$mailproducts = $mailproductDao->getProductsDetail ( $id );
		$mailinfo = parent::get_d ( $id );

		$mailinfo ['mailproducts'] = $mailproducts;
		return $mailinfo;
	}


	/**根据用户ID查找所属部门
	*author can
	*2011-4-19
	*/
	function getDeptByUserId($userId){
		//查找用户所在的部门ID
		$sql="select DEPT_ID from user where USER_ID='" .$userId. "' ";
		$row=$this->_db->query($sql);
		$deptID=mysql_fetch_row($row);
		//查找部门名称
		$sql2="select DEPT_NAME from department where DEPT_ID='" .$deptID[0]. "' ";
		$res=$this->_db->query($sql2);
		$deptName=mysql_fetch_row($res);
//		print_r($deptName[0]);
		return $deptName[0];
	}
	/**
	 * 根据发货单Id获取发货单数据
	 */
	 function getShipMessage_d( $shipId ){
	 	$shipDao = new model_stock_outplan_ship();
	 	$shipInfo = $shipDao->get_d( $shipId );
	 	return $shipInfo;
	 }

	 /**************************发票邮寄部分****************************/

	/**
	 * 新增邮寄记录 ，同时改变发票状态
	 */
	function addInvoice_d($object){
		try{
			$this->start_d();

            //获取邮寄记录
            $emailArr = $object['email'];
            unset($object['email']);

			$newId = parent::add_d($object,true);
			$invoiceDao = new model_finance_invoice_invoice();
			$invoiceDao->changeMailStatus_d($object['docId'],1);

            //发送邮件
            if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
                $this->thisMailForInvoice_d($emailArr,$object);
            }

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

    /**
     * 获取业务信息
     */
    function getObjInfo_d($objId,$objType){
        if($objType == 'YJSQDLX-FPYJ'){
            $invoiceDao = new model_finance_invoice_invoice();
            $object = $invoiceDao->getInvoiceAndApply_d($objId,$objType);
        }
        return $object;
    }

	/**************************发票邮寄部分****************************/

	/**
	 * 确认邮寄信息
	 */
	function confirm_d($id){
		return $this->updateById( array('id' => $id ,'mailStatus' => 1) );
	}

    /**
     * 邮件发送
     */
    function thisMailForInvoice_d($emailArr,$object,$thisAct = '新增'){
        $addMsg = '发票号码为 ： ' . $object['docCode'] .'<br/>收件单位 ： '.$object['customerName'].'<br/>收件人 ：' . $object['receiver'].'<br/>'.
        '快递公司为 ：' .$object['logisticsName'] .'<br/>邮寄单号为 : ' .$object['mailNo'];

        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$thisAct,$object['invoiceNo'],$emailArr['TO_ID'],$addMsg,'1');
    }
    /**
     * 发货邮寄-邮件发送
     * 2011年7月28日 15:18:01
     * zengzx
     */
    function thisMailForShip_d($emailArr,$object,$thisAct = '新增'){
    	$shipDao = new model_stock_outplan_ship();
    	if($object['docId']){
    		$shipObj = $shipDao->get_d($object['docId']);
    	}else{
    		return 0;
    	}
//    	echo "<pre>";
//    	print_R($shipObj);
    	$docType = $shipObj['docType'];
    	$planDaoName = $shipDao->relatedStrategyArr[$docType];
    	$planObj = $shipDao->getDocInfo($shipObj['planId'],new $planDaoName());
//    	echo "<pre>";
//    	print_R($planObj);
		$type='';
		if( $docType ){
			switch($docType){
			case"oa_contract_contract":$type='合同发货';break;
			case"oa_borrow_borrow":$type='借用发货';break;
			case"oa_present_present":$type='赠送发货';break;
			case"oa_contract_exchangeapply":$type='换货发货';break;
			case"oa_service_accessorder":$type='服务管理配件订单发货';break;
			case"oa_service_repair_apply":$type='服务管理维修申请单发货';break;
			case"independent":$type='独立发货';break;
			}
		}
    	$title = $planObj['docCode'].",".$planObj['docName'].";邮寄信息";
//    	echo $title;
    	$products = $object['productsdetail'];
		$productMsg = "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>邮寄物品名称</td><td>数量</td><td>备注</td></tr>";
		$i=1;
    	foreach( $products as $key=>$val ){
    		$productMsg .=<<<EOT
    		<tr bgcolor='#7AD730' align="center" ><td>$i</td><td>$val[productName]</td><td>$val[mailNum]</td><td>$val[remark]</td></tr><br>
EOT;
    		$i++;
    	}
        $addMsg = '<br/>单据类型 ： ' . $type .'<br/>单据编号 ： ' . $planObj['docCode'] .'<br/>单据名称 ： ' . $planObj['docName'] .'<br/>发货单号为 ： '
        . $object['docCode'] .'<br/>收件单位 ： '.$object['customerName'].'<br/>收件人 ：' . $object['receiver'].'<br/>快递公司 ：' . $object['logisticsName'].
        '<br/>快递单号：' .$object['mailNo'] .'<br/>件数：'.$object['number'] .'<br/>邮寄地址：'.$object['address'] .'<br/>收件人电话：'.$object['tel']. $productMsg;
//        echo "<pre>";
//        print_R($addMsg);
        $emailDao = new model_common_mail();
        $emailDao->mailClear($title,$emailArr['TO_ID'],$addMsg);
        $emailInfo = $emailDao->batchEmail(1,$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$thisAct,$object['invoiceNo'],$emailArr['TO_ID'],$addMsg,'1');
    }

    function getMailNo($mailObj){
    	$sql = " select c.mailNo from " . $this->tbl_name . " c where docType='".$mailObj['docType']."' and docId=".$mailObj['docId'];
    	$mailNoArr = $this->_db->getArray($sql);
    	$mailCodeArr = array();
    	foreach( $mailNoArr as $key => $val){
    		$mailCodeArr[$key] = $mailNoArr[$key]['mailNo'];
    	}
    	foreach( $mailCodeArr as $key=>$val ){
    		if( $mailCodeArr[$key] == $mailObj['mailNo'] ){
    			unset($mailCodeArr[$key]);
    		}
    	}
    	$mailNoStr = implode( ',',$mailCodeArr );
    	return $mailNoStr;
    }
    /**
     * 根据mailId获取从表信息
     */
     function getEqu($mailObj){
     	$sql="select c.id from ".$this->tbl_name." c where c.docType='".$mailObj['docType']."' and c.docId=".$mailObj['docId']." limit 1";
     	$mailIdArr = $this->_db->getArray($sql);
     	$mailEquDao = new model_mail_mailproductsdetail();
     	$condiction=array(
     		'mailInfoId'=>$mailIdArr[0]['id']
     	);
     	$mailEquObj = $mailEquDao->findAll($condiction);
     	return $mailEquObj;
     }

     /**
      * 根据发货单获取邮件接收者
      */
      function getMailman($shipObj){
      	$docType=$shipObj['docType'];
      	$planId=$shipObj['planId'];
      	$outplanDao = new model_stock_outplan_outplan();
      	$shipDao = new model_stock_outplan_ship();
      	$relatedStrategy=$shipDao->relatedStrategyArr[$docType];
      	$saleman = $shipDao->getSaleman($shipObj['docId'],$docType,new $relatedStrategy);
      	$condition = array(
			'id'=>$planId
      	);
      	//发货计划Id
      	$createArr = $outplanDao->findAll($condition,null,'createName,createId',null);
//      	$createArr = $planDao->findBy('id',$planId);
      	$mailmanArr=array();
      	$mailmanIdArr=array();
      	$mailmanArr['saleman'] = $saleman['responsible'];
      	$mailmanIdArr['salemanId'] = $saleman['responsibleId'];
      	if( $mailmanIdArr['salemanId'] != $createArr[0]['createId'] ){
	      	$mailmanArr['createName'] = $createArr[0]['createName'];
	      	$mailmanIdArr['createId'] = $createArr[0]['createId'];
      	}
      	$mailmanStr = implode(',',$mailmanArr);
      	$mailmanIdStr = implode(',',$mailmanIdArr);
      	$mailman['TO_NAME'] = $mailmanStr.",".$_SESSION['USERNAME'];
      	$mailman['TO_ID'] = $mailmanIdStr.",".$_SESSION['USER_ID'];
      	return $mailman;
      }

	/**
	 * 邮寄费用信息导入处理
	 * 2011年7月30日 14:34:10
	 * zengzx
	 */
	 function addExecelDatabypro_d( $objKeyArr ){
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();
		$objectArr = array();
		$excelData = array ();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_mail_mailExcelUtil::upReadExcelData ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			//判断是否传入的是有效数据
			if ($excelData) {
				foreach ($excelData as $key=>$val){
					//将里程碑计划名称和任务名称格式化，删除多余的空格。如果任务名为空，则该条数据插入无效。
					$excelData[$key][0] = str_replace( ' ','',$val[0]);
					if( $excelData[$key][0] == '' ){
						$tempArr['docCode'] = $val[0];
						$tempArr['result'] = '导入失败！（邮寄单号为空，无法导入）';
						array_push( $resultArr,$tempArr );
						unset( $excelData[$key] );
					}
				}
				foreach ( $excelData as $rNum => $row ) {
					foreach ( $objKeyArr as $index => $fieldName ) {
						//将值赋给对应的字段
						$objectArr [$rNum] [$fieldName] = $row [$index];
					}
				}
				foreach( $objectArr as $key=>$val ){
					$condition = array(
						'mailNo' => $objectArr[$key]['mailNo']
					);
					$rows = array(
						'number' => $objectArr[$key]['number'],
						'weight' => $objectArr[$key]['weight'],
						'serviceType' => $objectArr[$key]['serviceType'],
						'fare' => $objectArr[$key]['fare'],
						'anotherfare' => $objectArr[$key]['anotherfare'],
						'mailMoney' => $objectArr[$key]['mailMoney']
					);
					$this->update( $condition,$rows );
					$tempArr['docCode']=$val['mailNo'];
					if ($this->_db->affected_rows () == 0) {
						$tempArr['result']='导入失败！（单号不存在或数据无效）';
					}else{
						$tempArr['result']='导入成功！';
					}
					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			} else {
				msg( "文件不存在可识别数据!");
//				echo ( "文件不存在可识别数据!");
			}
		} else {
			msg( "上传文件类型不是EXCEL!");
//			echo ( "上传文件类型不是EXECEL!");
		}

	}

	/**
	 * 根据源单Id及源单类型删除邮寄信息
	 */
	function deleteByDoc($docId,$docType){
		if( $docId && $docType ){
			$condiction = array(
				'docType'=>$docType,
				'docId'=>$docId
			);
			$mailObj = $this->findAll($condiction);
			$mailIdArr = array();
			if(is_array($mailObj)&&count($mailObj)>0){
				foreach ( $mailObj as $key=>$val){
					$mailIdArr[$key] = $val['id'];
				}
				$mailIdStr = implode(',',$mailIdArr);
				$mailEquDao = new model_mail_mailproductsdetail();
				mysql_query ( "delete from " . $mailEquDao->tbl_name . " where mailInfoId in(" . $mailIdStr . ")" );
				$this->deletes($mailIdStr);
			}
			return 1;
		}
	}

	function getDocIdByOrder_d($orderId,$type){
		//获取该合同关联的发票
		$invoiceDao = new model_finance_invoice_invoice();
		$invoiceIds = $invoiceDao->getInvId_d( $orderId,$type );
		$invIdStr = implode(',',$invoiceIds);
		//获取该合同关联的发货计划
		$outplanDao = new model_stock_outplan_ship();
		$outplanIds = $outplanDao->getShipId_d($orderId,$type);
		$outIdStr = implode(',',$outplanIds);
		$docIdArr = array(
			'invoiceIds' => $invIdStr,
			'outplanIds' => $outIdStr,
		);
		return $docIdArr;
	}
}
?>