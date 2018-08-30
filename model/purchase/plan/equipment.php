<?php
header("Content-type: text/html; charset=gb2312");
class model_purchase_plan_equipment extends model_base{

	//状态位
	private $status;

	public $statusDao; //状态类

	function __construct() {
		$this->tbl_name = "oa_purch_plan_equ";
		$this->sql_map = "purchase/plan/equipmentSql.php";
		parent :: __construct();
		$this->status = array(
			0 => array(
				"stateEName" => "execution",
				"stateCName" => "执行",
				"stateVal" => "1"
			),
			1 => array(
				"stateEName" => "locking",
				"stateCName" => "锁定",
				"stateVal" => "2"
			),
			2 => array(
				"stateEName" => "end",
				"stateCName" => "完成",
				"stateVal" => "3"
			),
			3 => array(
				"stateEName" => "close",
				"stateCName" => "关闭",
				"stateVal" => "4"
			)
		);

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array(
				"statusEName" => "execution",
				"statusCName" => "执行",
				"key" => "1"
			),
			1 => array(
				"statusEName" => "locking",
				"statusCName" => "锁定",
				"key" => "2"
			),
			2 => array(
				"statusEName" => "end",
				"statusCName" => "完成",
				"key" => "3"
			),
			3 => array(
				"statusEName" => "close",
				"statusCName" => "关闭",
				"key" => "4"
			)
		);

		//调用初始化对象关联类
		parent::setObjAss();
	}

    //公司权限处理 TODO
    protected $_isSetCompany = 0; # 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分

	/*****************************************页面模板开始********************************************/

	/**采购管理-采购计划-设备清单显示模板
	*author can
	*2011-3-23
	 * @param	$rows 采购申请物料数组
	*/
	function showEqulist_s($rows){
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$systeminfoDao=new model_stock_stockinfo_systeminfo();
		$orderEquDao=new model_purchase_contract_equipment();
		$stockSysObj=$systeminfoDao->get_d("1");
		$saleStockId=$stockSysObj['salesStockId'];
		$str = "";
		$i = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				$i++;
//				$iClass = $i%2+1;
				$addAllAmount = 0;
				$strTab="";
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					$i++;
					$iClass = (($i%2)==0)?"tr_even":"tr_odd";
					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
					}else{
						$amountOk = $chdVal['amountAll'];
					}
					$addAllAmount += $amountOk;

					if( $amountOk==0 || $amountOk==""|| $amountOk<0){
						$checkBoxStr =<<<EOT
				        	$chdVal[basicNumb]
EOT;
					}else{
						$checkBoxStr =<<<EOT
						<input type="checkbox" class="checkChild" >$chdVal[basicNumb]
				    	<input type="hidden" class="hidden" value="$chdVal[id]"/>
EOT;
					}

					$strTab.=<<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td  align="left"  name="tdth05">
						    	$checkBoxStr
					        </td>
					        <td  width="18%"  name="tdth06">
					             $chdVal[purchTypeCName]
					        </td>
					        <td  width="12%"  name="tdth07">
					            $chdVal[amountAllOld]
					        </td>
					        <td  width="12%"  name="tdth07">
					            $chdVal[amountAll]
					        </td>
					        <td  width="12%"  name="tdth08">
					            $amountOk
					        </td>
					        <td  width="20%"  name="tdth09">
					            $chdVal[dateHope]
					        </td>
		            	</tr>
EOT;
				}

				$val['actNum']= $inventoryDao->getActNumByProId( $val['productId']);

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
				//获取物料采购负责人
				if($val['productNumb']!=""){
					$purchManger=$this->get_table_fields('oa_stock_product_info','productCode="'.$val['productNumb'].'"','purchUserName');
				}else{
					$purchManger="";
				}


				$str .=<<<EOT
					<tr class="$iClass">
				        <td    height="30"  name="tdth01">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td  name="tdth02">
				            $val[productNumb]<br>$val[productName]
				        </td>
				        <td width="6%" name="tdth11">
				            $purchManger
				        </td>
				        <td width="3%"   name="tdth03">
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
				        <td   width="9%"  name="tdth10" title="补库在途数量:$fillupOnwayAmount">
				            $onwayAmount($fillupOnwayAmount)
				        </td>
				        <td   width="8%"  name="tdth04">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><input id="allCheckbox$i" type="checkbox">$addAllAmount</p>
				            </p>
							<SCRIPT language=JavaScript>
								if($addAllAmount==0){
									jQuery("#allCheckbox$i").hide();
								}
							</SCRIPT>
				        </td>
				        <td width="50%" class="tdChange td_table" >
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
	 * 新建采购任务显示列表
	 * @param	$listEqu 采购申请物料数组
	 */
	public function newTask($listEqu){
		$planDao=new model_purchase_plan_basic();
		$applyDao=new model_asset_purchase_apply_apply();
		$str="";
		$i = $m = 0;
		if( is_array( $listEqu ) ){
			foreach ($listEqu as $key => $val) {
				$i++;
				$strChild = "";
				foreach ($val['childArr'] as $chdKey => $chdVal){
				    $businessInfo = array();
                    // ------- 获取上级单据归属公司信息 ------- //
                    $mainId = isset($val['applyId'])? $val['applyId'] : $val['basicId'];
                    $sql = "select * from purchase_asset_union where id = {$mainId} and purchType = '{$chdVal['purchType']}';";
                    $applyData = $this->_db->getArray($sql);
                    $applyData = ($applyData && !empty($applyData))? $applyData[0] : array();

                    $businessInfo['formBelong'] = isset($applyData['formBelong'])? $applyData['formBelong'] : '';
                    $businessInfo['formBelongName'] = isset($applyData['formBelongName'])? $applyData['formBelongName'] : '';
                    $businessInfo['businessBelong'] = isset($applyData['businessBelong'])? $applyData['businessBelong'] : '';
                    $businessInfo['businessBelongName'] = isset($applyData['businessBelongName'])? $applyData['businessBelongName'] : '';
                    // ------- ./获取上级单据归属公司信息 ------- //

					$planRows=$planDao->get_d($chdVal['basicId']);
					if($chdVal['purchType']==""){      //判断采购类型是否为空
						$chdVal['purchType']=$planRows['purchType'];
					}
					switch($chdVal['purchType']){
						case "oa_sale_order":$objModel='model_projectmanagent_order_order';$objName='order';break;
						case "oa_sale_lease":$objModel='model_contract_rental_rentalcontract';$objName='rentalcontract';break;
						case "oa_sale_service":$objModel='model_engineering_serviceContract_serviceContract';$objName='serviceContract';break;
						case "oa_sale_rdproject":$objModel='model_rdproject_yxrdproject_rdproject';$objName='rdproject';break;
						case "oa_borrow_borrow":$objModel='model_projectmanagent_borrow_borrow';$objName='borrow';break;
						case "oa_present_present":$objModel='model_projectmanagent_present_present';$objName='present';break;
						case "stock":$objModel='model_stock_fillup_fillup';$objName='fillup';break;
						default:$objModel='';$objName='';break;
					}
					$securityDao=new model_common_securityUtil($objName);
					if($planRows['sourceID']!=""&&$objModel!=''){
						$sourceDao=new $objModel();
						$sourceRow=$sourceDao->find(array ("id" =>$planRows['sourceID'] ) );
						$skey=$securityDao->md5Row($sourceRow,'id',$objModel);
					}
					//根据不同的采购类型，查看不同的源单信息
					switch($val['purchType']){
						case "oa_sale_order":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_order_order&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "oa_sale_lease":$souceNum='<a target="_bank" href="index1.php?model=contract_rental_rentalcontract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "oa_sale_service":$souceNum='<a target="_bank" href="index1.php?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "oa_sale_rdproject":$souceNum='<a target="_bank" href="index1.php?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "stock":$souceNum='<a target="_bank" href="index1.php?model=stock_fillup_fillup&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看补库信息">'.$planRows[sourceNumb].'</a>';break;
						case "oa_borrow_borrow":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_borrow_borrow&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看借试用信息">'.$planRows[sourceNumb].'</a>';break;
						case "oa_present_present":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_present_present&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看赠送信息">'.$planRows[sourceNumb].'</a>';break;
						case "HTLX-XSHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "HTLX-ZLHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "HTLX-FWHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						case "HTLX-YFHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
						default:$souceNum=$planRows[sourceNumb];break;
					}
					if($chdVal['purchType']=='oa_asset_purchase_apply'){
						$applyRow=$applyDao->get_d($chdVal['applyId']);
						$planRows['sendName']=$applyRow['applicantName'];
						$planId='';
						$applyId=$chdVal['applyId'];
						$planEquId='';
						$applyEquId=$chdVal['id'];
						$productNumb=$chdVal['productCode'];
					}else{
						$planId=$chdVal['basicId'];
						$applyId='';
						$planEquId=$chdVal['id'];
						$applyEquId='';
						$productNumb=$chdVal['productNumb'];
					}
					$strChild.=<<<EOT
				<tr  width="70%">
						<td width="13%">
							$chdVal[basicNumb]
							<input type="hidden" name="basic[equment][$m][sendUserId]" value="$chdVal[sendUserId]" />
							<input type="hidden" name="basic[equment][$m][sendName]" value="$chdVal[sendName]" />
							<input type="hidden" name="basic[equment][$m][applyNum]" value="$chdVal[basicNumb]" />
						</td>
						<td width="8%">
						$planRows[sendName]
						</td>
						<td width="10%">
							$chdVal[purchTypeCName]
							<input type="hidden" name="basic[equment][$m][purchTypeCName]" value="	$chdVal[purchTypeCName]" />
						</td>
						<td width="15%">
							$souceNum
							<input type="hidden" name="basic[equment][$m][sourceNum]" value="$planRows[sourceNumb]" />
						</td>
						<td width="15%">
							$planRows[rObjCode]
						</td>
						<td width="8%">
							<input type="text" name="basic[equment][$m][amountAll]" id="taskAmount$m" value="$chdVal[amount]"  class="taskAmount txtmin">
							<input type="hidden" name="myAmount" value="$chdVal[amount]" />
							<input type="hidden" name="basic[equment][$m][planAmonut]" value="$chdVal[amountAll]" />
							<input type="hidden" name="basic[equment][$m][planId]" value="$planId" />
							<input type="hidden" name="basic[equment][$m][applyId]" value="$applyId" />
							<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]" />
							<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]" />
							<input type="hidden" name="basic[equment][$m][productNumb]" value="$productNumb" />
							<input type="hidden" name="basic[equment][$m][pattem]" value="$chdVal[pattem]" />
							<input type="hidden" name="basic[equment][$m][unitName]" value="$chdVal[unitName]" />
							<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
							<input type="hidden" name="basic[equment][$m][contractAmount]" value="0" />
							<input type="hidden" name="basic[equment][$m][planEquId]" value="$planEquId" />
							<input type="hidden" name="basic[equment][$m][applyEquId]" value="$applyEquId" />
							<input type="hidden" name="basic[equment][$m][purchType]" value="$chdVal[purchType]" />
							<input type="hidden" name="basic[equment][$m][batchNumb]" value="$chdVal[batchNumb]" />
							<input type="hidden" name="basic[equment][$m][qualityCode]" value="$chdVal[qualityCode]" />
							<input type="hidden" name="basic[equment][$m][qualityName]" value="$chdVal[qualityName]" />
							<input type="hidden" name="basic[equment][$m][formBelong]" value="$businessInfo[formBelong]" />
							<input type="hidden" name="basic[equment][$m][formBelongName]" value="$businessInfo[formBelongName]" />
							<input type="hidden" name="basic[equment][$m][businessBelong]" value="$businessInfo[businessBelong]" />
							<input type="hidden" name="basic[equment][$m][businessBelongName]" value="$businessInfo[businessBelongName]" />
						</td>
						<td width="10%">
							<input class='txtshort datehope' type="text" id="dateHope$m" name="basic[equment][$m][dateHope]"  value="$chdVal[dateHope]" onfocus="WdatePicker()"  readonly />
						</td>
						<td width="10%">
							<input class="txtshort" name="basic[equment][$m][remark]" id="remark$m" value="$chdVal[remark]"/>
						</td>
					</tr>
EOT;
					++$m;
				}

				$str.=<<<EOT
					<tr height="28" align="center">
						<td   width="5%">$i
							</p>
						</td>
						<td   width="13%">
							$productNumb<br>$val[productName]
						</td>
						<td>
							<p class="">$val[pattem]</p>
						</td>
						<td>
							<p class="">$val[unitName]</p>
						</td>
						<td >
							<p class="">$val[allAmount]</p>
						</td>
						<td class="td_table">
							<table class="main_table_nested" width="70%">
								$strChild
			        		</table>

			        	</div>

			        </td>
			        <td>
						 <img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="删除行" />
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
	 * 根据设备清单下达采购计划
	 * @param	$listEqu 采购申请物料数组
	 */
	 public function newAdd($listEqu){
			$str="";
			$i = $m = 0;
			if( is_array( $listEqu ) ){
				foreach ($listEqu as $key => $val) {
					$i++;
					$strChild = "";
					foreach ($val['childArr'] as $chdKey => $chdVal){
						$strChild.=<<<EOT
					<tr align="center">
							<td width="40%">
							$chdVal[basicNumb]
						</td>
						<td  width="12%">
							<input type="text" name="basic[equment][$m][amountAll]" id="taskAmount$m" value="$chdVal[amount]" size=6 class="taskAmount">
							<input type="hidden" name="myAmount" value="$chdVal[amount]" />

							<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]" />
							<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]" />
							<input type="hidden" name="basic[equment][$m][productNumb]" value="$chdVal[productNumb]" />
							<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
							<input type="hidden" name="basic[equment][$m][assId]" value="$chdVal[id]" />
						</td>
						<td width="15%">
							&nbsp;<input type="text" id="dateHope$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput" value="" onfocus="WdatePicker()"  readonly />
						</td>
						<td>
							<textarea rows="2" cols="25" name="basic[equment][$m][remark]" id="remark$m"></textarea>
						</td>

					</tr>
EOT;
					++$m;
				}

				$str.=<<<EOT
					<tr height="28" align="center">
						<td   width="5%">
							<p class="childImg">
								<image src="images/expanded.gif" />$i
							</p>
						</td>
						<td   width="13%">
							$val[productNumb]/$val[productName]
						</td>
						<td   width="10%">
							<p class="allAmount">$val[allAmount]</p>
						</td>
						<td class="td_table">
							<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
								$strChild
			        		</table>
			        	<div class="readThisTable"><单击展开本设备具体信息></div>
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
	 * 同批次物料显示模板
	 *$rows  物料数据
	 */
	 function batchEquList($rows){
			$str="";
			$i = $m = 0;
			if( is_array( $rows ) ){

					$str.=<<<EOT
					<table class="main_table_nested">
						<thead>
							<tr>
								<td colspan="13" class="form_header">同批次已申请清单
									</td>
							</tr>
							<tr class="main_tr_header">
								<th width="3%">序号</th>
								<th width="17%">物料名称</th>
								<th width="8%">物料编号</th>
								<th width="13%">申请编号</th>
								<th width="8%">申请人</th>
								<th width="10%">物料类型</th>
								<th width="7%">规格型号</th>
								<th width="4%">单位</th>
								<th width="7%">申请数量</th>
								<th width="8%">申请日期</th>
								<th width="8%">希望交货日期</th>
								<th >备注</th>
							</tr>
						</thead>
						<tbody>
EOT;
				foreach ($rows as $key => $val) {
					$i++;
					$str.=<<<EOT
					<tr align="center">
						<td>
							$i
						</td>
						<td>
							$val[productName]
						</td>
						<td>
							$val[productNumb]
						</td>
						<td>
							$val[basicNumb]
						</td>
						<td>
							$val[sendName]
						</td>
						<td>
							$val[productTypeName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[dateIssued]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$val[remark]
						</td>

					</tr>
EOT;
			}
			$str.=<<<EOT
						</tbody>
			</table>
EOT;
		}
		return $str;

	 }
	 /**
	 * 采购申请物料执行情况
	 *
	 */
	 function showEquProgressList($listEqu){
		$str = "";
		$i = 0;
		if (is_array($listEqu)) {
			$orderEquDao=new model_purchase_contract_equipment();
			$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
			$inquiryDao=new model_purchase_inquiry_inquirysheet();
			$stockinDao=new model_stock_instock_stockin();
			$taskEquDao=new model_purchase_task_equipment();
			$interfObj = new model_common_interface_obj();
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$purchTypeCName = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				$str .= <<<EOT
					<tr class="$iClass">
						<td   name="tdth01">$i</td>
		                <td  width="6%"   name="tdth02">$val[dateIssued]</td>
		                <td  width="10%"   name="tdth03">$val[sourceNumb]</td>
						<td  width="10%"   name="tdth04">$val[customerName]</td>
						<td  width="6%"   name="tdth05">$purchTypeCName</td>
						<td  width="11%"   name="tdth06">$val[productNumb]<br>$val[productName]</td>
						<td  width="6%"   name="tdth7">$val[dateHope]</td>
						<td  width="5%"   name="tdth16">$val[amountAll]</td>
				        <td width="43%" class="tdChange td_table" colspan="6">
							<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				$taskEquRows=$taskEquDao->findByPlanEquId($val['id']);
				if($taskEquRows){
					foreach($taskEquRows as $teKey=>$teVal){
						$str .= <<<EOT
							<tr>
								<td  width="12%"   name="tdth08">$teVal[sendName]</td>
								<td  width="10%"   name="tdth09">$teVal[amountAll]</td>
						        <td width="78%" class="tdChange td_table" colspan="6">
									<table width="100%"  class="shrinkTable main_table_nested">
EOT;
						$inquiryEquRows=$inquiryEquDao->getEqusByTaskEquId_d($teVal['id']);
						if($inquiryEquRows){
							foreach($inquiryEquRows as $tKey=>$tVal){
								$str .= <<<EOT
									<tr>
										<td  width="15%"   name="tdth10">$tVal[amount]</td>
										<td  width="17%"   name="tdth11">$tVal[inquiryTime]</td>
								        <td width="68%" class="tdChange td_table" colspan="6">
											<table width="100%"  class="shrinkTable main_table_nested">
EOT;
							//获取订单物料信息
							$orderEquRows=$orderEquDao->getEqusByInquiryEquId($tVal['id']);
							if($orderEquRows){
								foreach($orderEquRows as $oKey=>$oVal){
									$stockDate=$stockinDao->getOrderProLastDate($oVal['basicId'],$oVal['productId']);
									$str .= <<<EOT
										<tr>
											<td width="20%"   name="tdth12">$oVal[amountAll]</td>
											<td width="30%"   name="tdth13">$oVal[dateIssued]</td>
											<td width="20%"   name="tdth14">$oVal[amountIssued]</td>
											<td width="30%"   name="tdth15">$stockDate</td>
										</tr>
EOT;

								}
							}else{
									$str .= <<<EOT
										<tr>
											<td width="20%"   name="tdth12">0</td>
											<td width="30%"   name="tdth13">--</td>
											<td width="20%"   name="tdth14">0</td>
											<td width="30%"   name="tdth15">--</td>
										</tr>
EOT;

							}
							$str .= <<<EOT
	        						</table>
									</td>
								</tr>
EOT;
					}
					}else{
							$str .= <<<EOT
								<tr>
									<td  width="15%"   name="tdth10">0</td>
									<td  width="17%"   name="tdth11">--</td>
							        <td width="68%" class="tdChange td_table" colspan="4">
										<table width="100%"  class="shrinkTable main_table_nested">
										<tr>
											<td width="20%"   name="tdth12">0</td>
											<td width="30%"   name="tdth13">--</td>
											<td width="20%"   name="tdth14">0</td>
											<td width="30%"   name="tdth15">--</td>
										</tr>
	        						</table>
									</td>
								</tr>
EOT;

				}
						$str .= <<<EOT
        						</table>
								</td>
							</tr>
EOT;

					}
				}else{
						$str .= <<<EOT
							<tr>
								<td width="12%"   name="tdth08">--</td>
								<td width="10%"   name="tdth09">0</td>
						        <td width="78%" class="tdChange td_table" colspan="4">
									<table width="100%"  class="shrinkTable main_table_nested">
										<tr>
											<td  width="15%"   name="tdth10">0</td>
											<td  width="17%"   name="tdth11">--</td>
									        <td width="68%" class="tdChange td_table" colspan="4">
												<table width="100%"  class="shrinkTable main_table_nested">
												<tr>
													<td width="20%"   name="tdth12">0</td>
													<td width="30%"   name="tdth13">--</td>
													<td width="20%"   name="tdth14">0</td>
													<td width="30%"   name="tdth15">--</td>
												</tr>
			        							</table>
											</td>
										</tr>
									   </table>
								 </td>
								</tr>
EOT;

				}
				$str .= <<<EOT
        				</table>
					</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;


	 }	/**
	 * 采购申请物料执行情况
	 *
	 */
	 function showStockProgressList($listEqu){
		$str = "";
		$i = 0;
		if (is_array($listEqu)) {
			$orderEquDao=new model_purchase_contract_equipment();
			$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
			$inquiryDao=new model_purchase_inquiry_inquirysheet();
			$stockinDao=new model_stock_instock_stockin();
			$taskEquDao=new model_purchase_task_equipment();
			$interfObj = new model_common_interface_obj();
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$purchTypeCName = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				$str .= <<<EOT
					<tr class="$iClass">
						<td   name="tdth01">$i</td>
		                <td  width="6%"   name="tdth02">$val[dateIssued]</td>
		                <td  width="9%"   name="tdth03">$val[basicNumb]</td>
						<td  width="6%"   name="tdth04">$purchTypeCName</td>
						<td  width="13%"   name="tdth05">$val[productNumb]<br>$val[productName]</td>
						<td  width="6%"   name="tdth06">$val[dateHope]</td>
						<td  width="5%"   name="tdth07">$val[amountAll]</td>
				        <td width="52%" class="tdChange td_table" colspan="6">
							<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				$taskEquRows=$taskEquDao->findByPlanEquId($val['id']);
				if($taskEquRows){
					foreach($taskEquRows as $teKey=>$teVal){
						$str .= <<<EOT
							<tr>
								<td  width="12%"   name="tdth08">$teVal[sendName]</td>
								<td  width="10%"   name="tdth09">$teVal[amountAll]</td>
						        <td width="78%" class="tdChange td_table" colspan="6">
									<table width="100%"  class="shrinkTable main_table_nested">
EOT;
						$inquiryEquRows=$inquiryEquDao->getEqusByTaskEquId_d($teVal['id']);
						if($inquiryEquRows){
							foreach($inquiryEquRows as $tKey=>$tVal){
								$str .= <<<EOT
									<tr>
										<td  width="15%"   name="tdth10">$tVal[amount]</td>
										<td  width="17%"   name="tdth11">$tVal[inquiryTime]</td>
								        <td width="68%" class="tdChange td_table" colspan="6">
											<table width="100%"  class="shrinkTable main_table_nested">
EOT;
							//获取订单物料信息
							$orderEquRows=$orderEquDao->getEqusByInquiryEquId($tVal['id']);
							if($orderEquRows){
								foreach($orderEquRows as $oKey=>$oVal){
									$stockDate=$stockinDao->getOrderProLastDate($oVal['basicId'],$oVal['productId']);
									$str .= <<<EOT
										<tr>
											<td width="20%"   name="tdth12">$oVal[amountAll]</td>
											<td width="30%"   name="tdth13">$oVal[dateIssued]</td>
											<td width="20%"   name="tdth14">$oVal[amountIssued]</td>
											<td width="30%"   name="tdth15">$stockDate</td>
										</tr>
EOT;

								}
							}else{
									$str .= <<<EOT
										<tr>
											<td width="20%"   name="tdth12">0</td>
											<td width="30%"   name="tdth13">--</td>
											<td width="20%"   name="tdth14">0</td>
											<td width="30%"   name="tdth15">--</td>
										</tr>
EOT;

							}
							$str .= <<<EOT
	        						</table>
									</td>
								</tr>
EOT;
					}
					}else{
							$str .= <<<EOT
								<tr>
									<td  width="15%"   name="tdth10">0</td>
									<td  width="17%"   name="tdth11">--</td>
							        <td width="68%" class="tdChange td_table" colspan="4">
										<table width="100%"  class="shrinkTable main_table_nested">
										<tr>
											<td width="20%"   name="tdth12">0</td>
											<td width="30%"   name="tdth13">--</td>
											<td width="20%"   name="tdth14">0</td>
											<td width="30%"   name="tdth15">--</td>
										</tr>
	        						</table>
									</td>
								</tr>
EOT;

				}
						$str .= <<<EOT
        						</table>
								</td>
							</tr>
EOT;

					}
				}else{
						$str .= <<<EOT
							<tr>
								<td width="12%"   name="tdth08">--</td>
								<td width="10%"   name="tdth09">0</td>
						        <td width="78%" class="tdChange td_table" colspan="4">
									<table width="100%"  class="shrinkTable main_table_nested">
										<tr>
											<td  width="15%"   name="tdth10">0</td>
											<td  width="17%"   name="tdth11">--</td>
									        <td width="68%" class="tdChange td_table" colspan="4">
												<table width="100%"  class="shrinkTable main_table_nested">
												<tr>
													<td width="20%"   name="tdth12">0</td>
													<td width="30%"   name="tdth13">--</td>
													<td width="20%"   name="tdth14">0</td>
													<td width="30%"   name="tdth15">--</td>
												</tr>
			        							</table>
											</td>
										</tr>
									   </table>
								 </td>
								</tr>
EOT;

				}
				$str .= <<<EOT
        				</table>
					</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;


	 }



	/*****************************************页面模板结束********************************************/

	/*****************************************状态相关处理开始********************************************/

	/**
	 * 通过value查找状态
	 * $stateVal Key值
	 * return 中文名称
	 */
	function statusToVal($stateVal){
		$returnVal = false;
		foreach( $this->status as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 通过状态查找value
	 * $stateSta 英文名
	 * return key值
	 */
	function statusToSta($stateSta){
		$returnVal = false;
		foreach( $this->status as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

/*****************************************状态相关处理结束********************************************/


/*****************************************业务处理开始********************************************/


	/**
	 * 对外接口
	 * 更行业务对象可执行数量
	 * @param	$planNumb 采购申请编号
	 * @param	$objectsNumb 采购申请物料编号
	 * @param	$productId 物料ID
	 * @param	$subAssignNum 采购申请物料时数量
	 */
	function updateBusiExeNum($planNumb,$objectsNumb,$productId,$subAssignNum){
		$searchArr = array(
					"basicNumb" => $planNumb,
					"deviceIsUse" => "1",
					"objectsNumb" => $objectsNumb,
					"productId" => $productId
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->pageBySqlId("equipment_list");
		$typeTabName = $rows["0"]["typeTabName"];
		$equId = $rows["0"]["typeEquTabId"];
		$planDao = new model_purchase_plan_basic();
		$arrPurchaseType = $planDao ->purchTypeToArr( $typeTabName );

		$objModelName = $arrPurchaseType["objectEquName"];
		$funUpdateBusiExeNum = $arrPurchaseType["funUpdateBusiExeNum"];
		$objDao = new $objModelName();
		return $objDao->$funUpdateBusiExeNum($equId,$subAssignNum);
	}

	/**
	 * 变更设备方法
	 * @param	$arr 采购申请物料时数组
	 */
	function changeEqu_d($arr){
		try {
			$this->start_d ();
			$sql = "insert into ".$this->tbl_name."( deviceNumb,deviceIsUse,basicNumb,basicId," .
						"productName,productId,productNumb,objectsNumb,typeTabName,typeTabId,typeEquTabName,amountIssued,dateIssued,dateEnd," .
						"basicVersionNumb,amountAll,dateHope,remark,status,typeEquTabId )   " .
					"(select deviceNumb,deviceIsUse,basicNumb,basicId," .
						"productName,productId,productNumb,objectsNumb,typeTabName,typeTabId,typeEquTabName,amountIssued,dateIssued,dateEnd," .
						"basicVersionNumb+1,'".$arr['amountAll']."','".$arr['dateHope']."','".$arr['remark']."','".$this->statusToSta('locking')."','".$arr['typeEquTabId']."' " .
						"from ".$this->tbl_name." where id='".$arr['id']."' )  ";
			$this->_db->query($sql);
			$returnId = $this->_db->insert_id();
			$updateArr = array(
				"id" => $arr['id'],
				"deviceIsUse" => '0'
			);
			$this->updateById($updateArr);
			$this->commit_d ();
			return $returnId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception($e);
		}
	}

	/**
	 * @desription 添加设备
	 * @param tags
	 * @date 2010-12-20 下午04:03:07
	 */
	function ppeAdd_d ( $arr , $basicId , $basicNumb ) {

		foreach( $arr as $key => $val ){
			$val['objCode'] = $this->objass->codeC( 'purch_plan_equ' );
			$val['basicNumb'] = $basicNumb;
			$val['basicId'] = $basicId;
			$val['status'] = $this->statusDao->statusEtoK('execution');
			$id = parent::add_d( $val );
		}
	}

	/**
	 * 采购设备-计划数组
	 */
	function pageEqu_d(){
		$searchArr = $this->__GET("searchArr");
		$basicDao = new model_purchase_plan_basic();
		$searchArr['state']=$basicDao->stateToSta("execute");
		$this->__SET('searchArr', $searchArr);
		$this->groupBy = 'productId';
		$rows = $this->pageBySqlId("equpment_basic");
		$i = 0;
		if( is_array( $rows ) ){
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
//				$this->resetParam();
				$searchArr = $this->__GET("searchArr");
				$searchArr['productId'] = $val['productId'];
				$searchArr['state']=$basicDao->stateToSta("execute");    //只显示正在执行的采购设备
				$this->__SET('groupBy', "p.id");
				$this->__SET('sort', "p.id");
				$this->__SET('searchArr', $searchArr );
				$chiRows = $this->listBySqlId("equpment_basic");
				$rows[$key]['purchAll']=0;//未下达任务数量总和
				foreach ( $chiRows as $chiKey => $chiVal ){
					$chiRows[$chiKey]['purchTypeCName'] = $interfObj->typeKToC( $chiVal['purchType'] );		//类型名称
					$rows[$key]['purchAll']=$rows[$key]['purchAll']+$chiVal['amountAll']-$chiVal['amountIssued'];

					if($chiVal['amountAll']-$chiVal['amountIssued']>0){
						$unPurchSum[$chiKey]=1;
					}else{
						$unPurchSum[$chiKey]=0;
					}
					$equIds[$chiKey]=$chiVal['id'];
					$equPurchType[$chiKey]=$chiVal['purchType'];
				}
				array_multisort($unPurchSum,SORT_DESC,$equPurchType,SORT_DESC,$equIds,SORT_DESC,$chiRows);// 对物料进行排序，
				$rows[$i]['childArr']=$chiRows;
				++$i;
				$purchAll[$key]=$rows[$key]['purchAll'];
				$ids[$key]=$val['id'];
			}
			//根据物料的未下达数量之和与id进行排序
			array_multisort($purchAll,SORT_DESC,$ids,SORT_DESC,$rows);

			return $rows;
		}
		else {
			return false;
		}
	}
	/**获取最小希望希望时间
	 * @author Administrator
	 *
	 */
		function getMinHopeDate_d($idsArry){
		$searchArr = array (
			'arrayIds' => $idsArry
		);
		$this->getParam($_GET);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('minDateHope');
		return $rows['0']['dateHope'];
		}
	/**获取同批次物料(不包含申请ID的物料)
	 * @author Administrator
	 *
	 */
		function getBatchEqu_d($basicId,$batchNumb){
		$searchArr = array (
			'batchNumb' => $batchNumb,
			'notInId' => $basicId,
			'purchType' => "produce",
		);
		$this->getParam($_GET);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('equpment_batch');
		return $rows;
		}
	/**获取同批次物料
	 * @author Administrator
	 *
	 */
		function getBatchEquWithBatch_d($batchNumb){
		$searchArr = array (
			'batchNumb' => $batchNumb,
			'purchType' => "produce",
		);
		$this->getParam($_GET);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('equpment_batch');
		return $rows;
		}
	/**获取合同申请物料
	 * @author Administrator
	 *
	 */
		function getProcessEqu_d(){
			$searchArr = $this->__GET("searchArr");
			$this->__SET('searchArr', $searchArr);
			$this->groupBy = 'p.id';
			$rows = $this->pageBySqlId("equpment_progress");
			return $rows;
		}
	/**获取申请物料信息
	 * @author Administrator
	 *
	 */
		function getPlanEquList_d($searchArr){
			$this->__SET('searchArr', $searchArr);
			$this->groupBy = 'p.id';
			$rows = $this->listBySqlId("equpment_plan");
			return $rows;
		}

	/**
	 * @desription 采购设备-计划不分页列表
	 * @param $str	物料id数组
	 * @return
	 * @date 2010-12-22 上午09:39:56
	 */
	function listPlanEqu_d($str){
		$searchArr = array (
			'arrayIds' => $str
		);
		$this->getParam($_GET);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('equpment_basic');

		$retRows = array();
		if( is_array( $rows ) ){
			foreach( $rows as $key => $val ){
				$i = true;
				foreach( $retRows as $retKey => $retVal ){
					if( $val['productId'] == $retVal['productId'] ){
						$retRows[$retKey]['childArr'][] = $val;
						$i = false;
					}
				}
				if( $i ){
					$val['childArr'][] = $rows[$key];
					$retRows[] = $val;
				}
			}

			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();

			foreach( $retRows as $retKey => $retVal ){
				$retRows[$retKey]['allAmount'] = 0;
				foreach( $retVal['childArr'] as $cKey => $cVal ){
					$retRows[$retKey]['childArr'][$cKey]['amountIssued'] = $cVal['amountIssued']
						= isset( $cVal['amountIssued'] )?$cVal['amountIssued']:0;

					$retRows[$retKey]['childArr'][$cKey]['amount'] = $cVal["amount"] =
						$cVal["amountAll"] - $cVal["amountIssued"];

					$retRows[$retKey]['childArr'][$cKey]['purchTypeCName'] = $interfObj->typeKToC( $cVal['purchType'] );//类型名称

					$retRows[$retKey]['allAmount'] += $cVal["amount"];
				}
			}
			return $retRows;
		}
		else {
			return false;
		}
	}

	/**
	 * 下达采购任务（合并）
	 */
	function getEquForTask_d($str){
		if(!empty($str)){
			$basicIdArr=explode(',',$str);
			if(is_array($basicIdArr)){
				$assetIdArr=array();
				$planIdArr=array();
				foreach($basicIdArr as $arrKey=>$arrVal){
					if(substr($arrVal,0,1)=='a'){
						array_push($assetIdArr,substr($arrVal,5));
					}else{
						array_push($planIdArr,$arrVal);
					}
				}
				if(!empty($assetIdArr)){//获取固定资产信息
					$assetIdStr=implode(',',$assetIdArr);
				 	$applyItemDao=new model_asset_purchase_apply_applyItem();
				 	//获取采购需求信息
				 	$applyItemRow=$applyItemDao->getItemByIdArr($assetIdStr);
				}
				if(!empty($planIdArr)){
					$planIdStr=implode(',',$planIdArr);
					$searchArr = array (
						'arrayIds' => $planIdStr
					);
					$this->getParam($_GET);
					$this->__SET('searchArr', $searchArr);
					$planrows = $this->listBySqlId('equpment_basic');
				}
				if(isset($applyItemRow)&&isset($planrows)){
					$rows=array_merge($applyItemRow,$planrows);
				}else if(isset($applyItemRow)){
					$rows=$applyItemRow;
				}else{
					$rows=$planrows;
				}
				$retRows = array();
				if( is_array( $rows ) ){
					foreach( $rows as $key => $val ){
						$i = true;
						foreach( $retRows as $retKey => $retVal ){
							if( $val['productId'] == $retVal['productId'] ){
								$retRows[$retKey]['childArr'][] = $val;
								$i = false;
							}
						}
						if( $i ){
							$val['childArr'][] = $rows[$key];
							$retRows[] = $val;
						}
					}

					//调用接口组合数组将获取一个objAss的子数组
					$interfObj = new model_common_interface_obj();

					foreach( $retRows as $retKey => $retVal ){
						$retRows[$retKey]['allAmount'] = 0;
						foreach( $retVal['childArr'] as $cKey => $cVal ){
							if($cVal['purchType']!=''){
								$retRows[$retKey]['childArr'][$cKey]['amountIssued'] = $cVal['amountIssued']= isset( $cVal['amountIssued'] )?$cVal['amountIssued']:0;
								$retRows[$retKey]['childArr'][$cKey]['amount'] = $cVal["amount"] =$cVal["amountAll"] - $cVal["amountIssued"];
								$retRows[$retKey]['childArr'][$cKey]['purchTypeCName'] = $interfObj->typeKToC( $cVal['purchType'] );//类型名称
							}else{
								if($cVal["issuedAmount"]==''||$cVal["issuedAmount"]=='null'){
									$retRows[$retKey]['childArr'][$cKey]["issuedAmount"]=0;
								}
								$retRows[$retKey]['childArr'][$cKey]['amountIssued'] = $cVal['issuedAmount']= isset( $cVal['issuedAmount'] )?$cVal['issuedAmount']:0;
								$retRows[$retKey]['childArr'][$cKey]['amount'] =$cVal["applyAmount"] - $cVal["issuedAmount"];
								$retRows[$retKey]['childArr'][$cKey]['purchType'] ='oa_asset_purchase_apply';
								$retRows[$retKey]['childArr'][$cKey]['basicNumb']=$cVal["applyCode"];
								$retRows[$retKey]['childArr'][$cKey]['batchNumb']='';
								$retRows[$retKey]['childArr'][$cKey]['purchTypeCName'] = $interfObj->typeKToC( 'oa_asset_purchase_apply' );//类型名称
							}
							$retRows[$retKey]['allAmount'] += $retRows[$retKey]['childArr'][$cKey]["amount"];
						}
					}
					return $retRows;
				}
				else {
					return false;
				}
			}
		}

	}

	/*****************************************关闭完成方法********************************************/

	/**
	 * @exclude 判断设备是否可完成此采购计划
	 * @author ouyang
	 * @param	$basicId 采购申请ID
	 * @return
	 * @version 2010-8-10 下午11:23:56
	 */
	function endPlanByBasicId_d ($basicId) {
		$searchArrByEnd = array (
			"basicId" => $basicId
		);
		$this->__SET('searchArr', $searchArrByEnd);
		$rows = $this->listBySqlId("equipment_list");
		//总需求数量大于已下达数量，无法进行完成操作(返回false)
		if( is_array($rows) ){
			$taskEquDao = new model_purchase_task_equipment();
			foreach($rows as $key =>$val){
				if( !$taskEquDao->checkTaskEqus_d( $val['id'] ) ){
					return false;
				}
			}
		}

		$searchArrByEnd = array (
			"basicId" => $basicId
		);
		$obj = array(
					'dateEnd' => date('Y-m-d'),
					'status' => $this->statusToSta('end')
		);
		parent::update($searchArrByEnd, $obj);
		return true;
	}

	/**
	 * @exclude 关闭采购计划所属设备，采购任务，采购申请单
	 * @author ouyang
	 * @param	$basicId 采购申请ID
	 * @return
	 * @version 2010-8-11 上午10:00:00
	 */
	function closePlanEqu_d ($basicId) {
		try {
			$this->start_d ();
			$searchArrByEnd = array (
						"basicId" => $basicId
			);
			$obj = array(
						'dateEnd' => date('Y-m-d'),
						'status' => $this->statusToSta('close')
			);
			parent::update($searchArrByEnd, $obj);

			$this->searchArr = array (
						"basicId" => $basicId
//						"status" => $this->statusToSta('execution')
			);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception($e);
			return false;
		}
	}
		/**
	 * @exclude 关闭采购计划所属设备，采购任务，采购申请单
	 * @author ouyang
	 * @param	$basicId 采购申请ID
	 * @return
	 * @version 2010-8-11 上午10:00:00
	 */
	function startPlanEqu_d ($basicId) {
		try {
			$this->start_d ();
			$searchArrByEnd = array (
						"basicId" => $basicId
			);
			$obj = array(
						'status' => $this->statusToSta('execution')
			);
			parent::update($searchArrByEnd, $obj);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception($e);
			return false;
		}
	}

	/*****************************************外部接口方法********************************************/

	/**
	 * 通过主对象数组获取数据合并返回
	 * 提供外部接口使用（采购计划）
	 *
	 * @param	array	采购计划数组
	 * @return	array	采购计划+设备数组
	 */
	function getPlanAsEqu_d ( $planRows ) {
		$ids = '';
		foreach($planRows as $key => $val){
			$ids .= $planRows[$key]['id'].',';
		}
		//合并采购计划所有ID
		$ids = ($ids=='') ? null : substr($ids,0,-1);
		if($ids){
			$this->resetParam();
			$searchArr = array (
				'basicIds' => $ids,
				'isNoAsset'=>"on"
			);
			$this->__SET('sort', 'p.id');
			$this->searchArr = $searchArr;
			$chiRows = $this->listBySqlId('equipment_list');
			foreach( $chiRows as $keyC => $valC ){
				//计划的总数量
				if( !isset( $valC['amountAll'] )||$valC['amountAll']==0 ||$valC['amountAll']=='' ){
					$chiRows[$keyC]['amountAll'] = 0;
					continue;
				}
				//未下达数量
				if( isset( $valC['amountIssued']) && $valC['amountIssued']!='' ){
					$chiRows[$keyC]['amountWait'] = $valC['amountAll'] - $valC['amountIssued'];
				}else{
					$chiRows[$keyC]['amountWait'] = $valC['amountAll'];
				}
				//循环将设备放入计划数组中
				foreach( $planRows as $key => $val ){
					if( $valC['basicId'] == $val['id'] ){
						$planRows[$key]['childArr'][] = $chiRows[$keyC];
					}
				}
			}
		}
		return $planRows;
	}

		/**
	 * 通过主对象数组获取数据合并返回
	 * 提供外部接口使用（采购计划）
	 *
	 * @param	array	采购计划数组
	 * @return	array	采购计划+设备数组
	 */
	function getPlanEqu_d ( $planRows ) {
		$ids = '';
		foreach($planRows as $key => $val){
			$ids .= $planRows[$key]['id'].',';
		}
		//合并采购计划所有ID
		$ids = ($ids=='') ? null : substr($ids,0,-1);
		if($ids){
			$this->resetParam();
			$searchArr = array (
				'basicIds' => $ids,
				'isNoAsset'=>"on",
				'isNotTask'=>''
			);
			$this->__SET('sort', 'p.id');
			$this->searchArr = $searchArr;
			$chiRows = $this->listBySqlId('equipment_list');
			foreach( $chiRows as $keyC => $valC ){
				//计划的总数量
				if( !isset( $valC['amountAll'] )||$valC['amountAll']==0 ||$valC['amountAll']=='' ){
					$chiRows[$keyC]['amountAll'] = 0;
					continue;
				}
				//未下达数量
				if( isset( $valC['amountIssued']) && $valC['amountIssued']!='' ){
					$chiRows[$keyC]['amountWait'] = $valC['amountAll'] - $valC['amountIssued'];
				}else{
					$chiRows[$keyC]['amountWait'] = $valC['amountAll'];
				}
				//循环将设备放入计划数组中
				foreach( $planRows as $key => $val ){
					if( $valC['basicId'] == $val['id'] ){
						$planRows[$key]['childArr'][] = $chiRows[$keyC];
					}
				}
			}
		}
		return $planRows;
	}

	/**
	 * 执行中列表物料
	 * @param	$planId 采购申请ID
	 */
	function getEquByPlanIdList_d($planId){
		$this->searchArr=array("basicId"=>$planId,
				'isNoAsset'=>"on",
				'isNotTask'=>'');
		$equs = $this->listBySqlId('equipment_list');
		$interfObj = new model_common_interface_obj();
		if(is_array($equs)){
			foreach( $equs as $key => $val ){
				//计划的总数量
				if( !isset( $val['amountAll'] )||$val['amountAll']==0 ||$val['amountAll']=='' ){
					$equs[$key]['amountAll'] = 0;
					continue;
				}
				//未下达数量
				if( isset( $val['amountIssued']) && $val['amountIssued']!='' ){
					$equs[$key]['amountWait'] = $val['amountAll'] - $val['amountIssued'];
				}else{
					$equs[$key]['amountWait'] = $val['amountAll'];
				}
				$equs[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );	//类型名称
			}

		}
		return $equs;
	}
	/**
	 * 关闭列表物料
	 * @param	$planId 采购申请ID
	 */
	function getCloseEquByPlanIdList_d($planId){
		$this->searchArr=array("basicId"=>$planId,
				'isNoAsset'=>"on");
		$equs = $this->listBySqlId('equipment_list');
		$interfObj = new model_common_interface_obj();
		if(is_array($equs)){
			foreach( $equs as $key => $val ){
				$equs[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );	//类型名称
			}

		}
		return $equs;
	}
	/**
	 * 根据计划获取设备清单 add by chengl
	 * 常用接口方法需要抽出来
	 * @param	$planId 采购申请ID
	 */
	function getEquByPlanId_d($planId){
		$this->searchArr=array("basicId"=>$planId);
		$equs = $this->listBySqlId('equipment_list');
		$interfObj = new model_common_interface_obj();
		foreach( $equs as $key => $val ){
			//计划的总数量
			if( !isset( $val['amountAll'] )||$val['amountAll']==0 ||$val['amountAll']=='' ){
				$equs[$key]['amountAll'] = 0;
				continue;
			}
			//未下达数量
			if( isset( $val['amountIssued']) && $val['amountIssued']!='' ){
				$equs[$key]['amountWait'] = $val['amountAll'] - $val['amountIssued'];
			}else{
				$equs[$key]['amountWait'] = $val['amountAll'];
			}
			$equs[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );	//类型名称
		}
		return $equs;
	}

	/**
	 * 根据计划获取设备清单执行情况
	 * 常用接口方法需要抽出来
	 * @param	$planId 采购申请ID
	 */
	function getEquExecute_d($planId){
		$this->searchArr=array("basicId"=>$planId);
		$this->groupBy="p.id";
		$equs = $this->listBySqlId('equipment_execute_list');
		return $equs;
	}

		/**
	 * 根据计划获取设备清单
	 * 常用接口方法需要抽出来
	 * @param	$planId 采购申请ID
	 */
	function getAllEqu_d($planId){
		$this->searchArr=array("basicId"=>$planId);
		$this->groupBy="p.id";
		$equs = $this->listBySqlId('equipment_list_all');
		return $equs;
	}

	/**
	 * 根据计划获取设备清单执行情况
	 * 常用接口方法需要抽出来
	 * @param	$planId 采购申请ID
	 */
	function getBackEqu_d($planId){
		$this->searchArr=array("basicId"=>$planId,"isBack"=>"1");
		$this->groupBy="p.id";
		$equs = $this->listBySqlId('equipment_list');
		return $equs;
	}

	/**
	 * 获取任务负责人
	 * @param	$id 采购申请物料ID
	 */
	function getTaskPurchaser_d($id){
		$this->searchArr=array("planEquId"=>$id);
		$this->groupBy="te.id";
		$purchaser = $this->listBySqlId('equipment_purchaser_list');
		$purchaserStr="";
		if(is_array($purchaser)){
			$sendManArr=array();
			foreach($purchaser as $key=>$val){
				array_push($sendManArr,$val['sendName']);
			}
			$purchaserStr=implode(',',array_unique($sendManArr));
		}
		return $purchaserStr;
	}

	/**
	 * @exclude 更新采购计划设备下达数量
	 * @author ouyang
	 * @param $equId 采购计划设备id
	 * @param $issuedNum 采购计划设备下达数量
	 * @param $lastIssueNum 采购计划设备还原数量
	 * @return
	 * @version 2010-8-10 下午10:10:16
	 */
	function updateAmountIssued($equId,$issuedNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set amountIssued=(ifnull(amountIssued,0) + $issuedNum - $lastIssueNum) where id=$equId  ";
			}else{
				$sql = " update ".$this->tbl_name." set amountIssued=(ifnull(amountIssued,0) + $issuedNum) where id=$equId ";
			}
			//echo $sql;
			return $this->query($sql);
		}
	}

}
?>