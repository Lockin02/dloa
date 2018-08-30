<?php
/**
 * @author huangzf
 * @Date 2011年1月17日 11:51:07
 * @version 1.0
 * @description:补库计划 Model层
 */
 class model_stock_fillup_fillup  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_fillup";
		$this->sql_map = "stock/fillup/fillupSql.php";
		parent::__construct ();
	}

     //公司权限处理 TODO
     protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分
     protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分
/*===================================页面模板======================================*/
	/**
	 * @desription 列表显示模板方法
	 * @linzx
	 */
	function showViewDePro ($rows) {
			if ($rows) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$systeminfoDao=new model_stock_stockinfo_systeminfo();
			$orderEquDao=new model_purchase_contract_equipment();
			$stockSysObj=$systeminfoDao->get_d("1");
			$saleStockId=$stockSysObj['salesStockId'];
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				//在途数量
				$onwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId']));
				//在途数量(补库)
				$fillupOnwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId'],'purchTypeArr'=>'stock'));
				$sql = "select * from oa_stock_product_info where productCode='".$val['sequence']."'";
				$data = $this->findSql($sql);
				//arrivalPeriod（交货周期），purchPeriod（采购周期），leastOrderNum（最小订单量）
				if(!empty($data)){
						$val['arrivalPeriod']=$data[0]['arrivalPeriod'];
						$val['purchPeriod']=$data[0]['purchPeriod'];
						$val['leastOrderNum']=$data[0]['leastOrderNum'];
				}
// 				echo"<pre>";
// 				print_r($val['purchPeriod']);
// 				die();
				$str .=<<<EOT
					<tr align="center">
					<td class="tabledata">
						{$val['sequence']}
					</td>
					<td class="tabledata">
						{$val['productName']}
					</td>
					<td class="tabledata">
						{$val['pattern']}
					</td>
					<td class="tabledata">
						{$val['unitName']}
					</td>
					<td class="tabledata">
						{$val['arrivalPeriod']}
					</td><td class="tabledata">
						{$val['purchPeriod']}
					</td><td class="tabledata">
						{$val['leastOrderNum']}
					</td>
					<td class="tabledata">
						{$val['qualityName']}
					</td>
			        <td  class="tabledata">
			            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]">$stockNum</a>
			        </td>
					<td class="tabledata" title="补库在途数量:$fillupOnwayAmount">
						$onwayAmount($fillupOnwayAmount)
					</td>
					<td class="tabledata">
						{$val['stockNum']}
					</td>
					<td class="tabledata">
						{$val['amountAllOld']}
					</td>
					<td class="tabledata">
						{$val['intentArrTime']}
					</td>

					<td class="tabledata">
						{$val['appOpinion']}
					</td>
					</tr>
EOT;
		$i++;
			}
			return $str;
			}
		}

		/**
		 * @desription 修改类表显示数据
		 * @linzx
		 */
		function showEditDePro ($rows) {
 		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ($rows as $key => $val) {
				$sql = "select * from oa_stock_product_info where productCode='".$val['sequence']."'";
				$data = $this->findSql($sql);
				//arrivalPeriod（交货周期），purchPeriod（采购周期），leastOrderNum（最小订单量）
				if(!empty($data)){
					$val['arrivalPeriod']=$data[0]['arrivalPeriod'];
					$val['purchPeriod']=$data[0]['purchPeriod'];
					$val['leastOrderNum']=$data[0]['leastOrderNum'];
				}
// 				echo"<pre>";
// 				print_r($val['purchPeriod']);
				$seNum=$i+1;
				$checkTypeStr=$this->getDatadictsStr( $datadictArr ['CGZJSX'], $val['qualityCode'] );
				$str .=<<<EOT
					<tr align="center" >
						<td class="tabledata">
							$seNum
						</td>
						<td class="tabledata">
							<input type="hidden" value="$val[id]" name="fillup[products][$i][id]" id="id$i">
							<input type="hidden" value="$val[fillUpId]" name="fillup[products][$i][fillUpId]" id="fillUpId$i">
							<input type="text" class="txtshort" value="$val[sequence]" name="fillup[products][$i][sequence]" id="sequence$i">
						</td>
						<td class="tabledata">
							<input type="text"  value="$val[productName]" name="fillup[products][$i][productName]" id="productName$i"  class="txt" />
							<input type="hidden" value="$val[productId]" name="fillup[products][$i][productId]" id="productId$i" >
						</td>
						<td class="tabledata">
							<input type="text" class="readOnlyTxtItem" value="$val[pattern]" name="fillup[products][$i][pattern]" id="pattern$i" />
						</td>
						<td class="tabledata">
							<input type="text" class="readOnlyTxtMin"  value="$val[unitName]" name="fillup[products][$i][unitName]" id="unitName$i" />
						</td>
						<td class="tabledata">
							<input type="text" class="readOnlyTxtMin"  value="$val[arrivalPeriod]" name="fillup[products][$i][arrivalPeriod]" id="arrivalPeriod$i" readonly/>
						</td>
						<td class="tabledata">
							<input type="text" class="readOnlyTxtMin"  value="$val[purchPeriod]" name="fillup[products][$i][purchPeriod]" id="purchPeriod$i" readonly/>
						</td>
						<td class="tabledata">
							<input type="text" class="readOnlyTxtMin"  value="$val[leastOrderNum]" name="fillup[products][$i][leastOrderNum]" id="leastOrderNum$i" readonly/>
						</td>
						<td>
							<select class="txtshort"  name="fillup[products][$i][qualityCode]">$checkTypeStr</select>
						</td>
						<td class="tabledata">
							<input type="text" class="txtshort"  value="$val[stockNum]" name="fillup[products][$i][stockNum]" id="stockNum$i" onblur="return checkIsNum(this);"/>
						</td>
						<td class="tabledata">
							<input type="text" class="txtshort"  value="$val[intentArrTime]" name="fillup[products][$i][intentArrTime]" id="intenArrTime$i" onfocus="WdatePicker()" />
						</td>

						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this);" title="删除行">
						</td>
					</tr>
EOT;
		$i++;
			}
			return $str;
			}
	 }

	 		/**
		 * @desription 修改类表显示数据
		 * @linzx
		 */
		function showEditAudit ($rows) {
 		if ($rows) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$systeminfoDao=new model_stock_stockinfo_systeminfo();
			$orderEquDao=new model_purchase_contract_equipment();
			$stockSysObj=$systeminfoDao->get_d("1");
			$saleStockId=$stockSysObj['salesStockId'];
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				//在途数量
				$onwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId']));
				//在途数量(补库)
				$fillupOnwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId'],'purchTypeArr'=>'stock'));
				$seNum=$i+1;
				$str .=<<<EOT
					<tr align="center" >
						<td class="tabledata">
							$seNum
						</td>
						<td class="tabledata">
							<input type="hidden" value="$val[id]" name="fillup[products][$i][id]" id="id$i">
							$val[sequence]
						</td>
						<td class="tabledata">
							$val[productName]
						</td>
						<td class="tabledata">
							$val[pattern]
						</td>
						<td class="tabledata">
							$val[unitName]
						</td>
						<td>
							$val[qualityName]
						</td>
				        <td  class="tabledata">
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]">$stockNum</a>
				        </td>
						<td class="tabledata" title="补库在途数量:$fillupOnwayAmount">
							$onwayAmount($fillupOnwayAmount)
						</td>
						<td class="tabledata">
							$val[stockNum]
						</td>
						<td class="tabledata">
							<input type="text" class="txtshort"  value="$val[stockNum]" name="fillup[products][$i][stockNum]" id="stockNum$i" onblur="return checkIsNum(this);"/>
						</td>
						<td class="tabledata">
							$val[intentArrTime]
						</td>
						<td>
							<input type="checkbox" class="txtshort" checked id="isPurch$i" name="basic[equipment][$i][isPurch]"/>
						</td>
					</tr>
EOT;
		$i++;
			}
			return $str;
			}
	 }


	/*===================================业务处理======================================*/

	/**
	 * 设置关联从表的申请单id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			$value['issuedPurNum']=0;
			$value['amountAllOld']=$value['stockNum'];
			array_push($resultArr, $value);
		}
		return $resultArr;
	}	/**
	 * 设置关联从表的采购属性信息
	 */
	function setQualityName($iteminfoArr) {
		$datadictDao=new model_system_datadict_datadict();
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value['qualityName']=$datadictDao->getDataNameByCode($value['qualityCode']);
			array_push($resultArr, $value);
		}
		return $resultArr;
	}
	/**
	 * @desription 添加保存方法
	 * @linzx
	 */
	function add_d ($fillupinfo) {
		try{
			$this->start_d();
			$codeDao=new model_common_codeRule();
			$fillupinfo['fillupCode']=$codeDao->stockCode("oa_stock_fillup","FILL");
			if(is_array($fillupinfo['products'])){

                 $id = parent :: add_d($fillupinfo,true);
                 $fillupproDao = new model_stock_fillup_filluppro();
                 $itemsArr = $this->setItemMainId ( "fillUpId", $id,$fillupinfo['products']);
                 $itemsArr = $this->setQualityName ( $itemsArr);
                 $itemsObj = $fillupproDao->saveDelBatch ( $itemsArr );
			     $this->commit_d();
			     return $id;
			}else {
				throw new Exception ( "单据信息不完整!" );
				}

		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}
	/**
	 * @desription 修改保存方法
	 * @linzx
	 */
	function edit_d ($fillupinfo) {
		try{
			$this->start_d();

			if(is_array($fillupinfo['products'])){
				$id=parent :: edit_d($fillupinfo,true);
			    $fillupproDao = new model_stock_fillup_filluppro();
                $itemsArr = $this->setItemMainId ( "fillUpId",$fillupinfo['id'],$fillupinfo['products']);
                 $itemsArr = $this->setQualityName ( $itemsArr);
                 $itemsObj = $fillupproDao->saveDelBatch ( $itemsArr );
			$this->commit_d();
			return true;
			}else {
				throw new Exception ( "单据信息不完整!" );
				}
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * @desription 根据id获取补库申请单所有产品信息
	 * @param tags
	 * @date 2011-1-8 下午02:30:37
	 * @qiaolong
	 */
	function get_d($id){
		$fillupproDao = new model_stock_fillup_filluppro();
		$fillupproDao->searchArr['fillUpId']=$id;
		$filluppros = $fillupproDao->listBySqlId();
		$fillupinfo = parent :: get_d($id);
		$fillupinfo['details'] = $filluppros;
		return $fillupinfo;
	}

	/**
	 * 更改申请单的状态
	 */
	function changeAuditStatus($id){
			return $this->update(
							array('id'=>$id),
							array("auditStatus"=>'已提交')
							);
	}

	/**判断是否可以下达采购计划
	*author can
	*2011-3-30
	*/
	function isAddPlan_d($id){
		$fillupproDao = new model_stock_fillup_filluppro();
		$fillupproRows=$fillupproDao->getItemByFillUpId($id);
		$flag=false;
		foreach($fillupproRows as $key =>$val){
			if($val['stockNum']-$val['issuedPurNum']>0){
				$flag=true;
				break;
			}else{
				$flag=false;
			}
		}
		return $flag;
	}

	/**
	 * 审批通过，邮件通知申请人
	 */
	function sendApprovalMail($object){
			$equDao=new model_stock_fillup_filluppro();
			$equRow=$equDao->getAllEqu_d($object['id']);
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['createId'];
			$emailArr['TO_NAME']=$object['createName'];
			if(is_array($equRow )){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>申请数量</b></td><td><b>审批数量</b></td><td><b>是否审批通过</b></td></tr>";
				foreach($equRow as $key => $equ ){
					$j++;
					$productNumb=$equ ['sequence'];
					$productName=$equ ['productName'];
					$amountAll=$equ ['stockNum'];
					$amountAllOld=$equ['amountAllOld'];
					$pattem=$equ['pattern'];
					if($equ['isPurch']==0){
						$isPurch='<font color=red><b>否</b></font>';
					}else{
						$isPurch='是';
					}
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$j</td>
								<td>$productNumb</td>
								<td>$productName</td>
								<td>$pattem</td>
								<td>$amountAllOld</td>
								<td>$amountAll</td>
								<td>$isPurch</td>
						</tr>
EOT;
					}
					$addmsg.="</table><br/>";
		}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'fillupAudit','您的补库申请单已审批通过,申请单编号为：<font color=red><b>'.$object['fillupCode'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
			return true;
	}

	/**
	 * workflow callback
	 */
	 function workflowCallBack($spid){
	 	$otherdatas = new model_common_otherdatas ();
         $fillupproDao = new model_stock_fillup_filluppro();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if($folowInfo['examines']=="ok"){  //审批通过后自动下达采购
				$equRows=$fillupproDao->getAllEqu_d($objId);
				if(is_array($equRows)){
					foreach($equRows as $key=>$val){
						$equ[$key]['id']=$val['id'];
//                        $equ[$key]['stockNum']=0;
                        //跳过物料确认步骤
//                        $equ[$key]['stockNum']=$equ[$key]['amountAllOld'];
                        $equ[$key]['isApp']= '3';
						$fillupproDao->edit_d($equ[$key]);
					}
				}
//			$row=$this->service->get_d($objId);//获取补库主表信息
//			//发送邮件通知申请人
//			$this->service->sendApprovalMail($row);

		}
	 }

 }
?>