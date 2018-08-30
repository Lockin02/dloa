<?php

/**
 *
 * 采购申请model
 * @author fengxw
 *
 */
header("Content-type: text/html; charset=gb2312");
class model_asset_purchase_apply_apply extends model_base {
	//采购计划类型数组
	private $purchaseType;
	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_asset_purchase_apply";
		$this->sql_map = "asset/purchase/apply/applySql.php";
		$this->mailArr=$mailUser[$this->tbl_name];
		$this->aduitEmail=$mailUser["apply_audit"];//审批通过后发送邮件通知人

		$this->purchaseType = array (
			0 => array (
				'purchCName' => '销售采购',
				'purchKey' => 'contract_sales',
				'objectEquName' => 'model_purchase_plansales_purchsalesplanequ',
				'funByWayAmount' => 'funByWayAmount',
				'funUpdateBusiExeNum' => 'funUpdateBusiExeNum'
			),
			1 => array (
				'purchCName' => '补库采购',
				'purchKey' => 'stock'
			),
			2 => array (
				'purchCName' => '研发采购',
				'purchKey' => 'rdproject'
			),
			3 => array (
				'purchCName' => '资产采购',
				'purchKey' => 'assets'
			),
			4 => array (
				'purchCName' => '订单采购',
				'purchKey' => 'order'
			),
			5 => array (
				'purchCName' => '合同采购',
				'purchKey' => 'contract_sales'
			),
			6 => array (
				'purchCName' => '生产采购',
				'purchKey' => 'produce'
			),
			7=> array (
				'purchCName' => '销售合同采购',
				'purchKey' => 'oa_sale_order'
			),
			8 => array (
				'purchCName' => '租赁合同采购',
				'purchKey' => 'oa_sale_lease'
			),
			9 => array (
				'purchCName' => '服务合同采购',
				'purchKey' => 'oa_sale_service'
			),
			10 => array (
				'purchCName' => '研发合同采购',
				'purchKey' => 'oa_sale_rdproject'
			),
			11 => array (
				'purchCName' => '补库采购',
				'purchKey' => 'oa_borrow_borrow'
			),
			12 => array (
				'purchCName' => '补库采购',
				'purchKey' => 'oa_present_present'
			)
		);
		parent::__construct ();
	}
		private $mainArr;
		
		private $aduitEmail;
		
		//公司权限处理
		protected $_isSetCompany = 1;
		
	/**
	 * 新建保存采购申请及明细单
	 */
	function add_d($object){
		try{
			$this->start_d();
			
			//去掉页面删除的数据
			foreach ($object['applyItem'] as $key => $val){
				if($val['isDelTag'] == 1){
					unset($object['applyItem'][$key]);
				}
			}
			if(empty($object['applyItem'])){
				msg ( '请填写好采购申请明细单的信息！' );
				throw new Exception('采购申请信息不完整，保存失败！');
			}
			//生成业务编号
			$codeDao=new model_common_codeRule();
			$object['formCode']=$codeDao->purchApplyCode("oa_purch_plan_basic","asset");
			//如果是交付，则状态为已分配
			if($object['purchaseDept'] == "1"){
				$object['state'] = '已提交';
			}
			$id=parent::add_d($object,true);
			//保存明细单
			$datadictDao = new model_system_datadict_datadict();
			$applyItemDao=new model_asset_purchase_apply_applyItem();
			foreach($object['applyItem'] as $key => $val){
				if($val['isDelTag']!=1){
					$val['applyId']=$id;
					$val['applyCode']=$object['formCode'];
					$val['purchDept']= $object['purchaseDept'];
					$val['productCategoryName']=$datadictDao->getDataNameByCode($val['productCategoryCode']);
					$applyItemDao->add_d($val);
				}
			}

			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['applyItem'] )) {
				$id = parent::edit_d ( $object, true );
				$applyItemDao=new model_asset_purchase_apply_applyItem();
				//$itemsArr = $this->setItemMainId ( "applyId", $object ['id'], $object ['applyItem'] );
				$mainArr=array("applyId"=>$object ['id']);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['applyItem']);
				$itemsObj = $applyItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function assignPurchaser_d($object) {
		try {
			$this->start_d ();
			$id = parent::edit_d ( $object, true );
			$this->commit_d ();
			return $object ['id'];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 询价保存
	 */
	function inquire_edit_d($object) {
		try {
			$object['state']='已提交';
			$this->start_d ();
			if (is_array ( $object ['applyItem'] )) {
					$addArr = array();//正确信息数组
					foreach ( $object['applyItem'] as $key => $obj ) {
						//假删除标记
						if($object['applyItem'][$key]['isDelTag'] == 1){
							$object['applyItem'][$key]['isDel'] = 1;
						}
						$object['applyItem'][$key]['isDelTag'] = 0;
					}
//				echo "<pre>";
//				print_r($object);
				$id = parent::edit_d ( $object, true );
				$applyItemDao=new model_asset_purchase_apply_applyItem();
				//$itemsArr = $this->setItemMainId ( "applyId", $object ['id'], $object ['applyItem'] );
				$mainArr=array("applyId"=>$object ['id']);
//				echo "<pre>";
//				print_r($mainArr);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['applyItem']);
//				echo "<pre>";
//				print_r($itemsArr);
				$itemsObj = $applyItemDao->saveDelBatch ( $itemsArr );
//				echo "<pre>";
//				print_r($itemsObj);
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/**
	 * 拆分采购时，发送邮件进行通知
	 *@param $id 采购需求Id
	 */
	 function sendEmail_d($id){
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$itemRow=$applyItemDao->getItemByApplyId($id,1,0);
		if(is_array($itemRow)){
			//发送邮件通知采购负责人
			$mailRow=$this->mailArr;
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$mailRow['sendUserId'];
			$emailArr['TO_NAME']=$mailRow['sendName'];
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>采购数量</b></td><td><b>希望交货时间</b></td><td><b>备注</b></td></tr>";
				foreach($itemRow as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$amountAll=$equ ['purchAmount'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->pushPurch($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'asset_pushPurch',',采购申请单据号为：<font color=red><b>'.$equ["applyCode"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
		}
	 }

	/**
	 * 确认采购申请物料
	 * add by chengl 2012-04-07
	 */
	function confirmProduct_d($object) {
		try {
			$flag = false;
			$set = false;
			$id = null;
			foreach ( $object  as $key => $equ ) {
				//申请数量大于0并且采购申请物料名称不为空才进行操作
				if ( is_array($equ)&&count($equ)>0&&isset($equ['productId']) ) {
					$productName=$equ ['productName'];
					$productId=$equ ['productId'];
					$productCode=$equ ['productNumb'];
					$equId=$equ['id'];
					$id = $equ['applyId'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					if($equ ['productId']>0){
						$sql="update oa_asset_purchase_apply_item set productId=$productId,productName='$productName'," .
								"productCode='$productCode',pattem='$pattem',unitName='$unitName' where id=$equId";
						$this->query($sql);
					}
					$set = true;
				}
				if(empty($equ ['productId'])){
					$flag = true;
				}
			}
			//确认情况
			if($flag&&$set){
				$this->update(array("id"=>$id),array("productSureStatus"=>2));
			}else if(!$flag&&$set){
				$this->update(array("id"=>$id),array("productSureStatus"=>1));
			}else if($flag&&!$set){
				$this->update(array("id"=>$id),array("productSureStatus"=>0));
			}
			$equDao=new model_asset_purchase_apply_applyItem ();
			$row=$this->get_d($object['id']);
			$equRow= $equDao->getPurchItem_d ( $object['id']);
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['applicantId'];
			$emailArr['TO_NAME']=$row['applicantName'];
			if(is_array($equRow )){
				$j=0;
				$equName=array();
				$addmsg.="采购申请单编号：<font color='red'>".$row['formCode']."</font><br>";
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料类别</b></td><td><b>确认物料</b></td><td><b>物料名称</b></td><td><b>物料编码</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>申请数量</b></td><td><b>希望交货日期</b></td><td><b>备注</b></td></tr>";
				foreach($equRow as $key => $val ){
					$j++;
					$equName[]=$val['inputProductName'];
					$productCategoryName=$val['productCategoryName'];
					$inputProductName=$val ['inputProductName'];
					$productName=$val ['productName'];
					$productCode=$val ['productCode'];
					$amountAll=$val ['applyAmount'];
					$dateHope=$val['dateHope'];
					$remark=$val['remark'];
					$pattem=$val['pattem'];
					$unitName=$val['unitName'];
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$j</td>
								<td>$productCategoryName</td>
								<td>$inputProductName</td>
								<td>$productName</td>
								<td>$productCode</td>
								<td>$pattem</td>
								<td>$unitName</td>
								<td>$amountAll</td>
								<td>$dateHope</td>
								<td>$remark</td>
						</tr>
EOT;
					}
					$addmsg.="</table><br/>";
			}
			$equName=array_unique($equName);
			$equName=implode(",",$equName);
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->arrivalEmailWithEqu($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'采购物料信息确认('.$equName.')','',"该邮件由".$_SESSION['USERNAME']."进行发送。请查收！",$emailArr['TO_ID'],$addmsg,1);

			return true;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 查看采购申请设备列表（固定资产）分配采购员用
	 * add by chengl 2012-04-07
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showConfirmAssetRead_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				if(empty($productName)){
					$productName=$val['inputProductName'];
				}
				$isNeedConfirm=empty($val['productId']);
				$isBack=$val['isBack']==1?"checked":"";
				$backReason=$val['backReason'];
				$equId=$val['id'];
				$trstyle="";
				$isBackCheckbox="";
				//$backReasonCheckbox="";
				if($isNeedConfirm){
					$isBackCheckbox="<input type='checkbox' id='isBack$i' name='basic[equ][$i][isBack]' value='1' $isBack/>";
					//$backReasonCheckbox="<input class='txt' name='basic[equ][$i][backReason]'>$backReason</input>";
					$trstyle="style='color:red'";
				}

				$str .= <<<EOT
					<tr class="$iClass" $trstyle>
						<td>$i<input type="hidden" name="apply[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[applyAmount]</td>
						<td>$val[applyAmount]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[dateEnd]</td>
						<td>
							<div class='divChangeLine'>$val[remark]</div>
						</td>
						<td>$isBackCheckbox</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

	function getPlan_d($id) {
		$plan = $this->get_d ( $id );
		//var_dump($plan['id']);
		$i = 0;
		//设置采购类型
		$plan = $this->purchTypeToCName ( $plan );
		//获取相应产品方法
		$itemDao = new model_asset_purchase_apply_applyItem ();
		$plan ['childArr'] = $itemDao->findAll ( array('applyId'=>$plan['id'],'purchDept'=>1) );
		return $plan;
	}
	function purchTypeToCName($plan) {
		$showStatus = $this->purchTypeToVal ( $plan ['purchType'] );
		$plan ['purchTypeCName'] = $showStatus;
		return $plan;
	}
	/**
	 * 采购类型
	 * 通过value查找状态
	 */
	function purchTypeToVal($purchVal) {
		$returnVal = false;
		foreach ( $this->purchaseType as $key => $val ) {
			if ($val ['purchKey'] == $purchVal) {
				$returnVal = $val ['purchCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 查看采购申请设备列表（固定资产）
	 * add by chengl 2012-04-07
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showAssetRead_s($listEqu){
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				if(empty($productName)){
					$productName=$val['inputProductName'];
				}
				$trstyle="";
				if($val['isBack']){
					$trstyle="style='color:red'";//打回红色显示
				}

				$str .= <<<EOT
					<tr class="$iClass" $trstyle>
						<td>$i<input type="hidden" name="apply[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[checkDate]</td>
						<td>
							<div class='divChangeLine'>$val[remark]</div>
						</td>
						<td>$isBackCheckbox</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;


	}

	/**
	 * 打回申请单给申请人
	 */
	function backBasicToApplyUser_d($object){
		try {
			$this->start_d ();
			$applyId=$object['id'];
			$backReason=$object['backReason'];
			$sql="update oa_asset_purchase_apply set ExaStatus='物料确认打回',backReasion='$backReasion' where id=$applyId";//设置为打回状态
			$this->query($sql);
			$row=$this->get_d($object['id']);
			$equDao=new model_asset_purchase_apply_applyItem ();
			//var_dump($object['equ']);
			foreach($object['equ'] as $key=>$val){
				$equDao->edit_d($val);
			}

			$equRow= $equDao->getItemByApplyId ( $object['id'] );
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['sendUserId'];
			$emailArr['TO_NAME']=$row['sendName'];
			if(is_array($equRow )){
				$j=0;
				$addmsg.="采购申请单编号：<font color='red'>".$object['planNumb']."</font><br>";
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料类别</b></td><td><b>申请物料</b></td><td><b>物料名称</b></td><td><b>申请数量</b></td><td><b>申请日期</b></td><td><b>希望交货日期</b></td><td><b>备注</b></td></tr>";
				foreach($equRow as $key => $equ ){
					$j++;
					$productCategoryName=$equ['productCategoryName'];
					$inputProductName=$equ ['inputProductName'];
					$productName=$equ ['productName'];
					$amountAll=$equ ['amountAll'];
					$dateIssued=$equ['dateIssued'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark'];
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$j</td>
								<td>$productCategoryName</td>
								<td>$inputProductName</td>
								<td>$productName</td>
								<td>$amountAll</td>
								<td>$dateIssued</td>
								<td>$dateHope</td>
								<td>$remark</td>
						</tr>
EOT;
					}
					$addmsg.="</table><br/>";
					$addmsg.="打回原因：<br/>     ";
					$addmsg.="<font color='blue'>".$backReason."</font>";
		}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanBack','','',$emailArr['TO_ID'],$addmsg,1);
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	 /**
	 * 物料确认采购申请物料列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	 function showConfirmEdit_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				if($val['isBack']==0){
					$i ++;
					$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
					$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>
						$val[productCategoryName]
						<td>
						$val[inputProductName]
						</td>
						<td>
						<input type="hidden"  name="apply[$i][id]" value="$val[id]"/>
						<input type="hidden"  name="apply[$i][applyId]" value="$val[applyId]"/>
						<input type="text" class="txtshort" id="productNumb$i" name="apply[$i][productNumb]" value="$val[productCode]"/>
						<input type="hidden" id="productId$i" name="apply[$i][productId]" value="$val[productId]"/> </td>
						<td>
						<input type="text" class="txt" id="productName$i" name="apply[$i][productName]" value="$val[productName]"/> </td>
						<script>
							processProductCmp($i);
						</script>

						<td>
						<input type="text" class="readOnlyTxtItem" id="pattem$i" name="apply[$i][pattem]" value="$val[pattem]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="unitName$i" name="apply[$i][unitName]" value="$val[unitName]"/></td>
						<td>
						$val[applyAmount]</td>
						<td>
						$val[dateHope]</td>
						<td>
							$val[remark]
						</td>

					</tr>
EOT;
				}
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}
	/**
	 * 采购申请通过后时，发送邮件进行通知
	 *@param $id 采购需求Id
	 */
	 function auditSendEmail_d($id){
		$applyItemDao=new model_asset_purchase_apply_applyItem();
		$itemRow=$applyItemDao->getItem_d($id);
		if(is_array($itemRow)){
			//发送邮件通知资产采购负责人
			$mailRow=$this->aduitEmail;
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$mailRow['sendUserId'];
			$emailArr['TO_NAME']=$mailRow['sendName'];
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>申请数量</b></td><td><b>希望交货时间</b></td><td><b>备注</b></td></tr>";
				foreach($itemRow as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$applyAmount=$equ ['applyAmount'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$applyAmount</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->pushPurch($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'asset_pushPurch',',采购需求单据号为：<font color=red><b>'.$equ["applyCode"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
		}
	 }


	/**
	 * 查看相关业务信息
	 * @param $id
	 * @param $skey
	 * @return string
	 */
	function viewRelInfo( $id,$skey ) {
		return '<img src="images\icon\view.gif" onclick="showOpenWin(\'?model=asset_require_requirement&action=toView&id=' . $id .'&perm=view&skey='.$skey.'\',1)">';
	}

	/************************* 单据撤回 *********************/
	/**
	 * 判断单据是否可撤回
	 */
	function canBackForm_d($id){
		$sql = "select sum(applyAmount) as applyAmount,sum(applyAmount - if(issuedAmount is null or issuedAmount = '',0,issuedAmount)) as canBack from oa_asset_purchase_apply_item where applyId = '$id' group by applyId";
		$rs = $this->_db->getArray($sql);
		//如果数量相等,可全撤
		if($rs[0]['canBack'] == $rs[0]['applyAmount']){
			return 1;
		}elseif($rs[0]['canBack'] == 0){ //如果可撤回数量为0，返回0
			return 0;
		}else{ //部分撤回
			return 2;
		}
	}

	/**
	 * 撤回部分
	 */
	function backForm_d($id){
		try{
			$this->start_d();
			//更新主表撤回信息
			$this->update(array('id' => $id),array('state' => '已撤回','ifShow'=>'1'));

			//更新从表申请数量
			$applyItemDao = new model_asset_purchase_apply_applyItem();
			//获取撤回的物料id及其申请数量
			$applyItemInfo = $applyItemDao->findAll(array('applyId' => $id),null,'productId,applyAmount');
			$applyItemDao->update(array('applyId' => $id),array('applyAmount' => 0));
			
			//获取资产需求申请id
			$rs = $this->find(array('id' => $id),null,'relDocId');
			$requirementId = $rs['relDocId'];
			//实例化资产需求申请明细
			$requireitemDao = new model_asset_require_requireitem();
			//获取资产需求申请明细
			$requireitemInfo = $requireitemDao->findAll(array('mainId' => $requirementId),null,'productId,purchDept,purchAmount');
			foreach ($requireitemInfo as $key => $val){
				$purchAmount = 0;
				$purchDept = $val['purchDept'];
				foreach ($applyItemInfo as $k => $v){
					if($val['productId'] == $v['productId']){
						//申请数量 = 原来的申请数量-撤回的申请数量
						$purchAmount = $val['purchAmount'] - $v['applyAmount'];
					}
				}
				if($purchAmount == 0){
					$purchDept = '';
				}
				//更新资产需求申请从表信息
				$requireitemDao->update(array('mainId'=>$requirementId,'productId'=>$val['productId']),array('purchDept'=>$purchDept,'purchAmount'=>$purchAmount));
			}
			//更新资产需求申请主表信息
			$requirementDao = new model_asset_require_requirement();
			$requirementDao->updateRecognize($requirementId);

			$this->commit_d();
			return true;
		}catch(exception $e){
			throw $e;
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 撤回部分
	 */
	function backDetail_d($object){
		try{
			$this->start_d();

			//实例化明细
			$applyItemDao = new model_asset_purchase_apply_applyItem();
			$allBackNum = 0;
			$applyNum = 0;
			$canBackNum = 0;
			$newApplyNum = 0;
			//获取资产需求申请id
			$rs = $this->find(array('id' => $object['id']),null,'relDocId');
			$requirementId = $rs['relDocId'];
			//实例化资产需求申请明细
			$requireitemDao = new model_asset_require_requireitem();
			//获取资产需求申请明细
			$requireitemInfo = $requireitemDao->findAll(array('mainId'=>$requirementId),null,'productId,purchDept,purchAmount');
			foreach ($requireitemInfo as $key => $val){
				$purchAmount = 0;
				$purchDept = $val['purchDept'];
				foreach($object['applyItem'] as $k => $v){
					//循环处理-用以判断操作是否满足全单撤回
					$allBackNum = bcadd($allBackNum ,$v['backAmount']);
					$applyNum = bcadd($applyNum ,$v['applyAmount']);
					
					$canBackNum = $v['applyAmount'] - $v['issuedAmount'];
					if($v['backAmount'] <= $canBackNum){
						//更新剩余数量
						$newApplyNum = $v['applyAmount'] - $v['backAmount'];
						$applyItemDao->update(array('id' => $v['id']),array('applyAmount' => $newApplyNum));
						$allNewNum += $newApplyNum;
					}
					if($val['productId'] == $v['productId']){
						//申请数量 = 原来的申请数量-撤回的申请数量
						$purchAmount = $val['purchAmount'] - $v['backAmount'];
					}
				}
				if($purchAmount == 0){
					$purchDept = '';
				}
				//更新资产需求申请从表信息
				$requireitemDao->update(array('mainId'=>$requirementId,'productId'=>$val['productId']),array('purchDept'=>$purchDept,'purchAmount'=>$purchAmount));
			}
			//如果符合全单撤回，则更新状态
			if($allBackNum == $applyNum){
				$this->update(array('id' => $object['id']),array('state' => '已撤回','backReason'=>$object['backReason'],'ifShow'=>'1'));
			}
			//更新资产需求申请主表信息
			$requirementDao = new model_asset_require_requirement();
			$requirementDao->updateRecognize($requirementId);
			
			$this->commit_d();
			return true;
		}catch(exception $e){
			throw $e;
			$this->rollBack();
			return false;
		}
	}

	//***** 验证能否继续下达采购申请
	function checkComplate_d($id){

	}


	/**
	 * 提交申请后邮件通知
	 */
	 function sendMailAtAdd($mainObj){
		include (WEB_TOR . "model/common/mailConfig.php");
		$mailId = $mailUser['oa_asset_apply_requireAdd']['sendUserId'];
//	 	$outmailStr = implode(',',$outmailArr);
		$addMsg = $this->sendMesAsAdd($mainObj);
//		echo "<pre>";
//		print_R($mailId);
//		echo $addMsg;
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '下推','资产采购申请'.$mainObj['formCode'], $mailId, $addMsg, '1');
	 }
	/**
	 * 邮件中附加物料信息
	 */
	 function sendMesAsAdd($object){
		if(is_array($object ['applyItem'])){
			$j=0;
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>物料名称</td><td>规格</td><td>申请数量</td><td>单位</td><td>预计金额</td><td>询价金额</td><td>希望交货日期</td><td>备注</td><td>行政意见</td></tr>";
			foreach($object ['applyItem'] as $key => $equ ){
				$j++;
				$inputProductName=$equ['inputProductName'];
				$pattem=$equ['pattem'];
				$applyAmount=$equ ['applyAmount'];
				$unitName=$equ ['unitName'];
				$amounts=$equ ['amounts'];
				$inquiryAmount=$equ ['inquiryAmount'];
				$dateHope=$equ ['dateHope'];
				$remark=$equ ['remark'];
				$suggestion=$equ ['suggestion'];
				$addmsg .=<<<EOT
					<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$inputProductName</td><td>$pattem</td><td>$applyAmount</td><td>$unitName</td><td>$amounts</td><td>$inquiryAmount</td><td>$dateHope</td><td>$remark</td><td>$suggestion</td></tr>
EOT;
					}
		}
		return $addmsg;
	 }

	 /**
	 *交付采购，如果所有物料都已下达任务，则单据改为关闭状态
	 *
	 */
	 function updatePurchState_d($id){
		//获取物料数据
		$equipment = new model_asset_purchase_apply_applyItem ();
		$rows = $equipment->getPurchItem_d ( $id );
		if(is_array($rows)){
			$flag=false;
			//计算每一个采购申请的物料未下达数量之和
			$waitNumSum=0;
			foreach($rows as $equKey=>$equVal){
				$waitNumSum=$waitNumSum+($equVal['applyAmount']-$equVal['issuedAmount']);
				if($waitNumSum>0){
					$flag=false;
					break;
				}else{
					$flag=true;
				}
			}
			if($flag){
				$obj = array ('id' => $id, 'purchState' => '3',  'dateEnd' => date ( 'Y-m-d' ),'updateTime'=>date('Y-m-d H:i:s'));
				return parent::updateById ( $obj );
			}
		}
	 }
	/**
	 * @exclude 关闭采购申请
	 */
	function dealClose_d($object) {
		$equDao = new model_asset_purchase_apply_applyItem ();
		$obj = array ('id' => $object['id'],'purchCloseRemark'=>$object['purchCloseRemark'], 'purchState' => '3', 'dateEnd' => date ( 'Y-m-d' ),'updateTime'=>date('Y-m-d H:i:s'));
		$id=parent::updateById ( $obj );
		$equRow= $equDao->getPurchItem_d ( $object['id'] );
		//发送邮件
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['applicantId'];
		$emailArr['TO_NAME']=$object['applicantName'];
		if(is_array($equRow )){
			$j=0;
			$addmsg.="采购申请单编号：<font color='red'>".$object['formCode']."</font><br>";
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>确认物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>申请数量</b></td><td><b>下达任务数量</b></td><td><b>希望交货日期</b></td><td><b>备注</b></td></tr>";
			foreach($equRow as $key => $equ ){
				$j++;
				$productCategoryName=$equ['productCategoryName'];
				$productName=$equ['productName'];
				$pattem=$equ ['pattem'];
				$unitName=$equ ['unitName'];
				$amountAll=$equ ['applyAmount'];
				if($equ ['issuedAmount']==''){
					$amountIssued=0;
				}else{
					$amountIssued=$equ ['issuedAmount'];
				}
				$dateIssued=$equ['dateIssued'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark'];
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$productCategoryName</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$amountIssued</td>
							<td>$dateHope</td>
							<td>$remark</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
				$addmsg.="关闭原因：<br/>     ";
				$addmsg.="<font color='blue'>".$object['purchCloseRemark']."</font>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanClose','','',$emailArr['TO_ID'],$addmsg,1);
		return $id;
	}

			/**
	 * @exclude 重新启动采购申请
	 * @param	$id 采购申请ID
	 */
	function startApply_d($id){
		$condition = array(
					"id" => $id
				);
		$obj = array(
					"purchState" =>'0'
				);
		return parent::update ( $condition, $obj );
	}

		/**
	 * 确认物料确认分配人
	 */
	function confirmProductUser_d($object){
		$equDao=new model_asset_purchase_apply_applyItem ();
		$id=$object['id'];
		$productSureUserId=$object['productSureUserId'];
		$productSureUserName=$object['productSureUserName'];
		$sql="update oa_asset_purchase_apply set productSureUserId='$productSureUserId'," .
				"productSureUserName='$productSureUserName' where id=$id";
		$this->query($sql);
		$row=$this->get_d($object['id']);
		$equRow= $equDao->getPurchItem_d ( $object['id'] );
		//发送邮件
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['productSureUserId'];
		$emailArr['TO_NAME']=$object['productSureUserName'];
		if(is_array($equRow )){
			$j=0;
			$addmsg.="采购申请单编号：<font color='red'>".$row['formCode']."</font><br>";
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料类别</b></td><td><b>待确认物料</b></td><td><b>物料名称</b></td><td><b>申请数量</b></td><td><b>希望交货日期</b></td><td><b>申请人</b></td><td><b>备注</b></td></tr>";
			foreach($equRow as $key => $equ ){
				$j++;
				$productCategoryName=$equ['productCategoryName'];
				$inputProductName=$equ ['inputProductName'];
				$productName=$equ ['productName'];
				$amountAll=$equ ['applyAmount'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark'];
				$sendName=$row['applicantName'];
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$productCategoryName</td>
							<td>$inputProductName</td>
							<td>$productName</td>
							<td>$amountAll</td>
							<td>$dateHope</td>
							<td>$sendName</td>
							<td>$remark</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanTask','','',$emailArr['TO_ID'],$addmsg,1);
		return true;
	}
	//获取采购需求对应的资产需求申请id
	function getRequirementId($id){
		$sql = "
			SELECT
				relDocId
			FROM ".$this->tbl_name."
			WHERE
				id = '".$id."'";
		$rs = $this->_db->get_one($sql);
	
		return $rs['relDocId'];
	}
	//更新采购状态
	function updatePurchState($id,$purchStateVal){
		$object=array("id"=>$id,"purchState"=>$purchStateVal);
		$this->updateById($object);
	}
	//获取某个采购需求下状态为【采购中】的记录数
	function countPurch($relDocId){
		$sql = "
			SELECT
				COUNT(*) AS purchAmount
			FROM
				".$this->tbl_name."
			WHERE
				relDocId = '".$relDocId."'
			AND purchState = 0 
			AND ifShow = 0 ";
		$rs = $this->_db->get_one($sql);

		return $rs['purchAmount'];
	}
}