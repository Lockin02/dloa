<?php
/*
 * Created on 2010-12-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_purchase_inquiry_inquirysheet extends model_base{

 	private $state;     //状态位
 	public $statusDao;  //状态类

	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_purch_inquiry";
		$this->sql_map = "purchase/inquiry/inquirysheetSql.php";
		$this->mailArr=$mailUser['oa_purchase_speed'];
		parent :: __construct();

		$this->state=array(
		    0=>array(
		       "stateEName"=>"save",
		       "stateCName"=>"保存",
		       "stateVal"=>"0"
		    ),
		    1=>array(
		       "stateEName"=>"wait",
		       "stateCName"=>"待指定",
		       "stateVal"=>"1"
		    ),
		    2=>array(
		       "stateEName"=>"assign",
		       "stateCName"=>"已指定",
		       "stateVal"=>"2"
		    ),
		    3=>array(
		       "stateEName"=>"close",
		       "stateCName"=>"已关闭",
		       "stateVal"=>"3"
		    ),
		   4=>array(
		       "stateEName"=>"done",
		       "stateCName"=>"已生成订单",
		       "stateVal"=>"4"
		    ),
		   5=>array(
		       "stateEName"=>"backtask",
		       "stateCName"=>"已退回任务",
		       "stateVal"=>"5"
		    )
		);

		$this->statusDao=new model_common_status();
		$this->statusDao->status=array(
		    0=>array(
		       "statusEName"=>"save",
		       "statusCName"=>"保存",
		       "key"=>"0"
		    ),
		    1=>array(
		       "statusEName"=>"wait",
		       "statusCName"=>"待指定",
		       "key"=>"1"
		    ),
		    2=>array(
		       "statusEName"=>"assign",
		       "statusCName"=>"已指定",
		       "key"=>"2"
		    ),
		    3=>array(
		       "statusEName"=>"close",
		       "statusCName"=>"已关闭",
		       "key"=>"3"
		    ),
		    4=>array(
		       "statusEName"=>"done",
		       "statusCName"=>"已生成订单",
		       "key"=>"4"
		    ),
		    5=>array(
		       "statusEName"=>"backtask",
		       "statusCName"=>"已退回任务",
		       "key"=>"5"
		    )
		);

		//调用初始化对象关联类
		parent::setObjAss();
	}
/*****************************************页面模板显示开始********************************************/
	/**
	 * 查看询价单的供应商的报价详细.
	 *
	 * @param $suppRows 供应商报价物料数组
	 * @param $inuqiryEqu 询价单物料数组
	 */
	function showSupp_s ( $suppRows ,$inuqiryEqu ) {
		$orderEquDao=new model_purchase_contract_equipment();
		$str = '';
		if($inuqiryEqu){
			$orderDateTime=$this->get_table_fields($this->tbl_name,'id='.$inuqiryEqu['0']['parentId'],'createTime');//询价日期
		foreach( $inuqiryEqu as $key => $val ){
			$rows=$orderEquDao->getHistoryInfo_d( $val['productNumb'],$orderDateTime);//获取最新历史价格

			$str.= "<tr><td>";
			$str.=  $val['productNumb']."<br>";
			$str.= $inuqiryEqu[$key]['name'] = $val['productName'];
			$str.= "</td>";
			foreach( $suppRows as $suppKey => $suppVal ){
				//判断供应商是否有报价，如果没有报价则设为“暂无报价”
				if(is_array($suppVal['child'])){
					foreach( $suppVal['child'] as $equKey => $equVal ){
						if( $val['productName'] == $equVal['productName']&&$val['id']==$equVal['inquiryEquId']&& $equVal['parentId']==$suppVal['id'] ){
							/*if($equVal[tax]>0){

							}else{
								$tax="";
							}*/
							$tax="<<span class='formatMoney'>$equVal[taxRate]</span>%>";
							$str.= <<<EOT
							 <td>
							<font color='blue' class='formatMoneySix'>$equVal[price]</font>$tax
							</td>
EOT;
						}
					}
				}else{
							$str.= "<td>暂无报价</td>";
				}
			}
			if(is_array($rows)){
				$str.= <<<EOT
				 <td>
					<font color='blue' class='formatMoneySix'>$rows[applyPrice]</font><<span class='formatMoney'>$rows[taxRate]</span>%>
				</td>
				 <td class='form_text_right'>
					供应商：<b>$rows[suppName]</b><br/>
					付款条件：<b>$rows[paymentConditionName]  $rows[payRatio]</b><br/>
					订单日期：<b>$rows[orderTime]</b><br/>
					数  量：<b>$rows[amountAll]</b><br/>
				</td>
EOT;
			}else{
				$str.= <<<EOT
				 <td>
					无历史价格
				</td>
				 <td>
				</td>
EOT;
			}
			$str.= <<<EOT
				<td class='form_text_right'>
					已下单数量：<b>$val[amount]</b><br/>
					$val[referPrice]
					</b><br/>
				</td>
EOT;

		}
			$str.= "</tr>";
		}else{
			$str="<tr align='center'><td colspan='50'>暂无相应信息</td></tr>";
		}
		return $str;
	}
/*****************************************页面模板显示结束********************************************/

/*****************************************业务处理开始********************************************/

	/**采购询价单添加方法
	*author can
	*2010-12-28
	* @param $object 询价单数组
	*/
	function add_d ($object) {

		try{
			$this->start_d();
			$object['state']=$this->statusDao->statusEtoK('save');
			$object['ExaStatus']="未提交";
			$object['objCode']=$this->objass->codeC("purch-inquirysheet");
			$codeDao=new model_common_codeRule();
			$object['inquiryCode']=$codeDao->purchaseCode("oa_purch_inquiry");
			$object['isUse']="0";   //初始值为0代表询价单还没生成采购合同，如果为1则代表已根据采购询价单生成采购合同
			$id=parent::add_d($object,true);
            $equmentInquiryDao=new model_purchase_inquiry_equmentInquiry();

			//保存产品清单
			if(is_array($object['equmentInquiry'])){   //对物料信息进行判断，如果存在物料则保存，否则抛出异常
				foreach($object['equmentInquiry'] as $val){
					if(!empty($val['productName'])){
						$val['parentId']=$id;
						$equId=$equmentInquiryDao->add_d($val);

						//设置总关联表数据，这里根据设备清单id去找总关联表，如果要更新的数据为空，则进行update操作，否则复制不为空的数据进行新增操作
						$this->objass->saveModelObjs("purch",array("taskEquId"=>$val['taskEquId']),array("inquiryCode"=>$object['inquiryCode'],"inquiryId"=>$id,"inquiryEquId"=>$equId));
						//更新采购任务设备的已下达/申请数量
						$taskEquDao=new model_purchase_task_equipment();
						$taskEquDao->updateAmountIssued($val['taskEquId'],$val['amount']);
					}
				}
			}else{
				throw new Exception( '无物料信息' );
			}

			//更新附件关联关系
			$this->updateObjWithFile($id,$object['inquiryCode']);



            //附件处理
			if(isset($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])){
				$uploadFile = new model_file_uploadfile_management();
				$uploadFile->updateFileAndObj($_POST['fileuploadIds'],$id,$object['inquiryCode']);
			}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
//			throw $e;
			return null;
		}

	}

	/**添加供应商
	*author can
	*2010-12-29
	 * @param $supp 供应商数组
	*/
    function addSupp_d($supp){
    	$arr['parentId']=$supp['parentId'];
    	$arr['suppId']=$supp['supplierId'];
    	if (util_jsonUtil::is_utf8 ( $supp['supplierName'] )) {
			$arr['suppName'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierName'] );
		}else{
    		$arr['suppName']=$supp['supplierName'];
    	}
    	if (util_jsonUtil::is_utf8 ( $supp['supplierPro'] )) {
			$arr['suppTel'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierPro'] );
		}else{
    		$arr['suppTel']=$supp['supplierPro'];
    	}
    	$supplierDao=new model_purchase_inquiry_inquirysupp();
    	$suppId=$supplierDao->add_d($arr);
    	return $suppId;

    }

    /**如果已选供应商，则重新保存
	*author can
	*2011-1-3
	*/
	function suppAdd_d($supp){
		$id=$supp['suppIds'];
		$arr['parentId']=$supp['parentId'];
    	$arr['suppId']=$supp['supplierId'];
    	if (util_jsonUtil::is_utf8 ( $supp['supplierName'] )) {
			$arr['suppName'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierName'] );
		}else{
    		$arr['suppName']=$supp['supplierName'];
    	}
    	if (util_jsonUtil::is_utf8 ( $supp['supplierPro'] )) {
			$arr['suppTel'] = util_jsonUtil::iconvUTF2GB ( $supp['supplierPro'] );
		}else{
    		$arr['suppTel']=$supp['supplierPro'];
    	}
    	$condiction=array('id'=>$id);
    	$supplierDao=new model_purchase_inquiry_inquirysupp();
		$supplierDao->updateField($condiction,'suppName',$arr['suppName']);
		$supplierDao->updateField($condiction,'suppId',$arr['suppId']);
		$supplierDao->updateField($condiction,'parentId',$arr['parentId']);
		$supplierDao->updateField($condiction,'suppTel',$arr['suppTel']);
    	return $id;
	}
    /**删除询价单
	*author can
	*2010-12-29
	 * @param $id 询价单ID
	*/
	function deletesInfo_d($id) {
		try {
			$this->start_d();
			//减去关联采购任务设备清单的已申请数量

			$equDao=new model_purchase_inquiry_equmentInquiry();
			$equDao->del_d($id);

			$this->deletes ($id);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}
    /**退回询价单
	*author can
	*2010-12-29
	 * @param $id 询价单ID
	*/
	function backToTask_d($id) {
		try {
			$this->start_d();
			//减去关联采购任务设备清单的已申请数量

			$equDao=new model_purchase_inquiry_equmentInquiry();
			$equDao->del_d($id);

			$object['id']=$id;
			$object['state']=5;
			$this->edit_d ($object);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}

	/**获取修改的对象
	*author can
	*2010-12-30
	 * @param $id 询价单ID
	*/
	function get_d($id ,$perm = null){
		$inquiry=parent::get_d($id);

		//获取询价产品
		$inquiryProDao=new model_purchase_inquiry_equmentInquiry();
        $uniqueEquRows=$inquiryProDao->getUniqueByParentId($id);
		$equs=$inquiryProDao->getEqusByParentId($id);
		if(is_array($equs)){
			$equs=$inquiryProDao->getPurchName($equs);
		}
		$inquiry['equs']=$equs;
		if(!empty($perm)){
			$arr=$inquiryProDao->rowsShow($equs,$uniqueEquRows);
		}else{
			$arr=$inquiryProDao->rowsEdit($equs);
		}
		$inquiry['productLidt']=$arr;

		//获取指定供应商信息
		if($inquiry['suppId']){
			$flibraryDao=new model_supplierManage_formal_flibrary();
			$supplier=$flibraryDao->get_d($inquiry['suppId']);
			$inquiry['supplier']=$supplier;
		}
		return $inquiry;
	}

	/**修改询价单
	*author can
	*2010-12-30
	*$object 前台传送过来的数组
	*/
	function edit_d($object){
		try{
			$this->start_d();
			$inquiryProDao=new model_purchase_inquiry_equmentInquiry();
			$inquirySupDao=new model_purchase_inquiry_inquirysupp();
			$inquirySupproDao=new model_purchase_inquiry_inquirysupppro();

			$suppROws=$inquirySupDao->getSuppByParentId($object['id']);

			$taskEquDao=new model_purchase_task_equipment();
			$equmentRows=$object['equmentInquiry'];
			unset($object['equmentInquiry']);
			//修改主表的内容
			$newId=parent::edit_d($object,true);
			//删除从表的内容，并添加新内容
//			$deleteCondition=array('parentId'=>$object['id']);
//			$inquiryProDao->delete($deleteCondition);
			if($equmentRows){
				foreach($equmentRows as $val){
					if(!empty($val['productName'])){
						$taskEquDao->updateAmountIssued($val['taskEquId'],$val['amount'],$val['amountOld']);
						$val['parentId']=$object['id'];
						$equId=$inquiryProDao->edit_d($val);

						//设置总关联表数据，这里根据设备清单id去找总关联表，如果要更新的数据为空，则进行update操作，否则复制不为空的数据进行新增操作
//						$this->objass->saveModelObjs("purch",array("taskEquId"=>$val['taskEquId']),array("inquiryCode"=>$object['inquiryCode'],"inquiryId"=>$object['id'],"inquiryEquId"=>$equId));

						//根据供应商保存ID来获取其相关的物料信息，并更新供应商物料信息中的询价物料ID
//						foreach($suppROws as $supKey=>$supVal){
//							$supproRows=$inquirySupproDao->getProByParentId($supVal['id']);
//							foreach($supproRows as $proKsy=>$proVal){
//								if($val['productName']==$proVal['productName']&&$val['taskEquId']==$proVal['takeEquId']){
//									$condiction=array('id'=>$proVal['id']);
//									$inquirySupproDao->updateField($condiction,'inquiryEquId',$equId);
//								}
//							}
//						}
					}
				}
			}

			$this->commit_d();

            return $object['id'];
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}
	}

	/**指定供应商
	*author can
	*2011-1-3
	*/
	function assignSupp_d($objcet){
		try{
			$this->start_d();
			$condiction=array('id'=>$objcet['id']);
			parent::edit_d($objcet);
			$this->updateField($condiction,'state','2');
			$this->commit_d();
			return true;
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}

	}

	/**
	 * 采购询价单审批通过后，发送邮件
	 *@param $id 采购询价单ID
	 */
	 function sendEmail_d($id){
		try{
			$this->start_d();
			//获取采购询价单信息及物料信息
			$rows=$this->get_d($id);

			$emailArr=array();
			$sendIdArr=array();
			$sendNameArr=array();
			$emailArr['issend'] = 'y';

			$emailArr['TO_ID']=$rows['purcherId'];
			if($emailArr['TO_ID']){
				$addmsg="";
				if(is_array($rows['equs'])){
					$planDao=new model_purchase_plan_basic();
					$interfObj = new model_common_interface_obj();
//					$orderContractDao = new model_projectmanagent_order_order();
					$j=0;
					//构造表格详细信息
					$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>询价数量</b></td><td><b>预计到货时间</b></td><td><b>采购类型</b></td><td><b>负责人</b></td><td><b>申请编号</b></td><td><b>源单(合同)编号</b></td><td><b>客户名称</b></td><td><b>申请人</b></td></tr>";
					foreach($rows['equs'] as $key => $equ ){
						$planNumb="";
						$applyUser="";
						$dateHope="";
						$sourceNumb="";
						$customer="";
						//获取采购申请ID
						$planId=$this->get_table_fields('oa_purch_task_equ','id='.$equ ['taskEquId'],'planId');
						if($planId){//获取采购申请信息
							$planRow=$planDao->get_d($planId);
							$planNumb=$planRow['planNumb'];
							$applyUser=$planRow['sendName'];
							$sourceNumb=$planRow['sourceNumb'];
							array_push($sendIdArr,$planRow['sendUserId']);
							array_push($sendNameArr,$planRow['sendName']);
							if($equ['purchType']=="oa_sale_order"||$equ['purchType']=="oa_sale_lease"||$equ['purchType']=="oa_sale_service"||$equ['purchType']=="oa_sale_rdproject"){
								$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
								$supDao = new $supDaoName();	//实例化对象
								$sourceRow=$orderContractDao->getCusinfoByorder($equ['purchType'],$planRow['sourceID']);
								$customer=$sourceRow['customerName'];
							}else if($equ['purchType']=="oa_present_present"||$equ['purchType']=="oa_borrow_borrow"){
								$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
								$supDao = new $supDaoName();	//实例化对象
								$sourceRow=$supDao->getInfoList($planRow['sourceID']);
								$customer=$sourceRow['customerName'];
							}
							if($equ['purchType']=="HTLX-XSHT"||$equ['purchType']=="HTLX-ZLHT"||$equ['purchType']=="HTLX-FWHT"||$equ['purchType']=="HTLX-YFHT"){
								$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
								$supDao = new $supDaoName();	//实例化对象
								$sourceRow=$supDao->getInfoList($planRow['sourceID']);
								$customer=$sourceRow['customerName'];
							}
						}
						//获取报价供应商ID
						$iqnuirySuppId=$this->get_table_fields('oa_purch_inquiry_supp','parentId='.$id.' and suppId='.$rows['suppId'],'id');
						if($iqnuirySuppId){
							//获取报价供应商ID
							$dateHope=$this->get_table_fields('oa_purch_inquiry_suppequ','parentId='.$iqnuirySuppId.' and inquiryEquId='.$equ['id'],'deliveryDate');

						}
						$j++;
						$productName=$equ['productName'];
						$pattem=$equ ['pattem'];
						$unitName=$equ ['units'];
						$amountAll=$equ ['amount'];
						$purchTypeCn=$equ['purchTypeCn'];
						$applyNumb=$planNumb;
						$applyer=$applyUser;
						$deliveryDate=$dateHope;
						$purcher=$rows['purcherName'];
						$addmsg .=<<<EOT
						<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$pattem</td>
									<td>$unitName</td>
									<td>$amountAll</td>
									<td>$deliveryDate</td>
									<td>$purchTypeCn</td>
									<td>$purcher</td>
									<td>$applyNumb</td>
									<td>$sourceNumb</td>
									<td>$customer</td>
									<td>$applyer</td>
								</tr>
EOT;
						}
						$addmsg.="</table>";
				}
				$mailArr=$this->mailArr;
				$sendIds=implode(',',array_unique($sendIdArr)).",".$mailArr['sendUserId'];
				$emailDao = new model_common_mail();
//				$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'采购询价单已审批通过,询价单编号为：<font color=red><b>'.$rows['inquiryCode'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
				$emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchSpeed','该邮件为采购进度信息反馈','',$sendIds,$addmsg,1);

			}

			$this->commit_d();
			return true;
		}catch (Exception $e){
			$this->rollBack();
			return null;
		}

	 }

	/**
	 * 获取采购询价单及设备清单
	 * @param $inquiryId 询价单ID
	 */
	function getInquirysheetWithEqus($inquiryId){
		$inquiry=$this->get_d($inquiryId);

	}

	/**
	 * @desription 添加采购合同时候更新接口
	 * @param tags     by qian
	 * @date 2011-1-12 下午02:49:11
	 */
	function isAddPurchOrder ( $inquiryId,$state) {
		//更新询价单状态
		$condiction = array('id'=>$inquiryId);
		$this->updateField($condiction,'state',$state);
	}

	//判断物料所对应的任务是否都已下达订单
	function isEndInquiry_d($id){
		$inquiryProDao=new model_purchase_inquiry_equmentInquiry();
		$equs=$inquiryProDao->getEqusByParentId($id);
		if(is_array($equs)){
			$flag=1;
			foreach($equs as $key=>$val){
				$contractAmount=$this->get_table_fields('oa_purch_task_equ','id='.$val ['taskEquId'],'contractAmount');
				if($val['amount']!=$contractAmount){
					$flag=0;
					break;
				}
			}
			return $flag;
		}else{
			return 0;
		}

	}

	/**
	 * 提交询价单审批，发送邮件通知申请人
	 *$id 采购询价单ID
	 */
	 function sendEmailByAduit_d($id){
		$taskEquDao=new model_purchase_task_equipment();
		$planDao=new model_purchase_plan_basic();
		//获取采购询价物料
		$rows=$this->get_d($id);
		print_r($rows);
		$addmsg="";
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$rows['purcherId'];
		$emailArr['TO_NAME']=$rows['purcherName'];
		if(is_array($rows['equs'])){
			$j=0;
			//构造表格详细信息
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>询价数量</b></td><td><b>采购类型</b></td></tr>";
			foreach($rows['equs'] as $key => $equ ){
				//获取采购申请ID
				$planId=$this->get_table_fields();
				$j++;
				$productName=$equ['productName'];
				$pattem=$equ ['pattem'];
				$unitName=$equ ['units'];
				$amountAll=$equ ['amount'];
				$purchTypeCn=$equ['purchTypeCn'];
				$addmsg .=<<<EOT
				<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$pattem</td>
									<td>$unitName</td>
									<td>$amountAll</td>
									<td>$purchTypeCn</td>
								</tr>
EOT;
						}
						$addmsg.="</table>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'该邮件由'.$_SESSION['USERNAME'].'发送。<br>采购询价单已提交审批,询价单编号为：<font color=red><b>'.$rows['inquiryCode'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);


	 }
/*****************************************页面模板显示结束********************************************/

    /**
     * 审批流回调方法
     */
    function workflowCallBack($spid,$inquirysheetRows){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);

		if(is_array($inquirysheetRows)&&sizeof($inquirysheetRows)>0&&$folowInfo['examines']=="ok"){  //审批通过则指定供应商
			$assignSupp=$this->assignSupp_d($inquirysheetRows);
			//发送邮件通知采购员
			$this->sendEmail_d($inquirysheetRows['id']);
		}
    }


}
?>
