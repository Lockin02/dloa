<?php

class model_purchase_plan_basic extends model_base {

	public static $pageArr = array ();
	//---------->
	//采购计划类型数组，不使用
	private $planType;
	//状态位
	private $state;

	//采购计划类型数组
	private $purchaseType;
	//----------<
	public $statusDao; //状态类


	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = 'oa_purch_plan_basic';
		$this->sql_map = 'purchase/plan/basicSql.php';
		$this->pushPurch=$mailUser['asset_pushPurch'];//资产采购由行政部下达时，邮件发送通知人数组
		$this->planApproval=$mailUser['asset_approval'];//资产采购审批通过时，邮件发送通知人数组
		$this->planChange=$mailUser['oa_purchase_planChange'];//资产采购审批通过时，邮件发送通知人数组
		parent::__construct ();
		//---------->
		$planType = array (
			'PURCHSALSE' => array (
				'tabPlan' => 'oa_purch_sales_basic',
				'tabPlanEqu' => 'oa_purch_sales_device',
				'tabBasic' => 'oa_contract_sales',
				'tabBasicEqu' => 'oa_contract_sales_equ'
			),
			'PURCHPLANSALESEQU' => array (
				'tabPlan' => 'oa_purch_sales_basic',
				'tabPlanEqu' => 'oa_purch_sales_device',
				'tabBasic' => 'oa_contract_sales',
				'tabBasicEqu' => 'oa_contract_sales_equ'
			)
		);

		$this->state = array (
			0 => array (
				'stateEName' => 'execute',
				'stateCName' => '执行中',
				'stateVal' => '0'
			),
			1 => array (
				'stateEName' => 'Locking',
				'stateCName' => '锁定',
				'stateVal' => '1'
			),
			2 => array (
				'stateEName' => 'end',
				'stateCName' => '完成',
				'stateVal' => '2'
			),
			3 => array (
				'stateEName' => 'close',
				'stateCName' => '关闭',
				'stateVal' => '3'
			),
			4 => array (
				'stateEName' => 'change',
				'stateCName' => '待变更',
				'stateVal' => '4'
			)
		);

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
			),
			13=> array (
				'purchCName' => '销售合同采购',
				'purchKey' => 'HTLX-XSHT'
			),
			14 => array (
				'purchCName' => '租赁合同采购',
				'purchKey' => 'HTLX-ZLHT'
			),
			15 => array (
				'purchCName' => '服务合同采购',
				'purchKey' => 'HTLX-FWHT'
			),
			16 => array (
				'purchCName' => '研发合同采购',
				'purchKey' => 'HTLX-YFHT'
			),
			17 => array (
				'purchCName' => '资产采购',
				'purchKey' => 'oa_asset_purchase_apply'
			)
		);

		//----------<
		$this->statusDao = new model_common_status ();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'execute',
				'statusCName' => '执行中',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'Locking',
				'statusCName' => '锁定',
				'key' => '1'
			),
			2 => array (
				'statusEName' => 'end',
				'statusCName' => '完成',
				'key' => '2'
			),
			3 => array (
				'statusEName' => 'close',
				'statusCName' => '关闭',
				'key' => '3'
			),
			4 => array (
				'statusEName' => 'change',
				'statusCName' => '待变更',
				'key' => '4'
			)
		);

		//调用初始化对象关联类
		parent::setObjAss ();

	}

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分

	/*****************************************页面模板开始********************************************/
	/**
	 * 显示变更通知单列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showChangeNotice_s($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ( $rows as $key => $val ) {
				$i ++;
				$iClass = $i % 2 + 1;
				$str .= <<<EOT
			<tr class="$classCss">
				<td  >
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td align="left" >
		            $val[planNumb]
		        </td>
		        <td  >
		            $val[createName]
		        </td>
		        <td  >
		            $val[purchTypeCName]
		        </td>
		        <td  >
		            $val[stateC]
		        </td>
		        <td width="50%" class="tdChange td_table" >
					<table width="100%"  class="shrinkTable main_table_nested">
EOT;
					foreach ( $val ['childArr'] as $chdKey => $chdVal ) {
						$str .= <<<EOT
						<tr align="center">
		        			<td  align="left" >
					        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
					        	$chdVal[productNumb] <br> $chdVal[productName]
					        </td>
					        <td width="20%">
					            $chdVal[amountAll]
					        </td>
					        <td width="20%">
					            $chdVal[amountWait]
					        </td>
					        <td width="20%">
					            $chdVal[dateHope]
					        </td>
		        		</tr>
EOT;
				}
					$str .= <<<EOT
        			</table>
		        	<div class="readThisTable"><单击展开物料具体信息></div>
		        </td>
		        <td >
					<select onchange=" selectButton( this , '$val[id]', '$val[planNumb]' ) " >
					    <option value="value1">请选择</option>
					    <option value="readReceive">查看接收</option>
				  	</select>
				</td>
		    </tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 查看采购计划列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showRead_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				//add by chengl 2012-04-06
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				$productNumb=empty($val['productNumb'])?$val['productCategoryName']:$val['productNumb'];
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>$productNumb<br/> $productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[qualityName]</td>
						<td><font color='red'>$val[testType]</font></td>
						<td>$val[amountAll]</td>
						<td>$val[amountAllOld]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[dateEnd]</td>
						<td>
							<textarea class="textarea_read_blue" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

		/**
	 * 查看采购计划列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showStockRead_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				//add by chengl 2012-04-06
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				$productNumb=empty($val['productNumb'])?$val['productCategoryName']:$val['productNumb'];
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>$productNumb<br/> $productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[qualityName]</td>
						<td><font color='red'>$val[testType]</font></td>
						<td>$val[amountAll]</td>
						<td>$val[amountAllOld]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[dateEnd]</td>
						<td>
							<textarea class="textarea_read_blue" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 查看采购申请设备列表（研发用）
	 * add by chengl 2012-04-07
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showRdRead_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productName=empty($val['productId'])?$val['productName']:$val['productNumb']."<br/>".$val['productName'];
				$isProduce=$val['isProduce']==1?"是":"否";
				if(empty($productName)){
					$productName=$val['inputProductName'];
				}
				$trstyle="";
				if($val['isBack']){
					$trstyle="style='color:red'";//打回红色显示
				}
				$str .= <<<EOT
					<tr class="$iClass" $trstyle>
						<td>$i</td>
						<td>$val[productCategoryName]</td>
						<td>$isProduce</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[qualityName]</td>
						<td>$val[amountAllOld]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[dateEnd]</td>
						<td>
							<div class='divChangeLine'>$val[remark]</div>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

	/**
	 *分配采购申请设备列表（研发用）
	 * add by chengl 2012-04-07
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showConfirmRdRead_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productName=empty($val['productId'])?$val['productName']:$val['productNumb']."<br/>".$val['productName'];
				$isProduce=$val['isProduce']==1?"是":"否";
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
						<td>$i<input type="hidden" name="basic[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$isProduce</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
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
						<td>$i<input type="hidden" name="basic[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[qualityName]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
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
						<td>$i<input type="hidden" name="basic[equ][$i][id]" value="$equId"/></td>
						<td>$val[productCategoryName]</td>
						<td>$productName</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
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

	/**
	 * 查看采购计申请物料执行情况
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showExecute_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			$equipmentDao = new model_purchase_plan_equipment ();
			foreach ( $listEqu as $key => $val ) {
				//获取任务负责人
				$purchasers=$equipmentDao->getTaskPurchaser_d($val['id']);
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>$val[productName]</td>
						<td>$val[amountAll]</td>
						<td>$purchasers</td>
						<td><a title="查看关联的采购任务" href="index1.php?model=purchase_task_basic&action=readTaskByPlanId&basicId=$val[basicId]&basicNum=$val[basicNumb]">$val[amountIssued]</a></td>
						<td>$val[inquiryNumbs]</td>
						<td>$val[orderAmount]</td>
						<td>$val[stokcNum]</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料信息</td></tr>";
		}
		return $str;
	}
	/*
	 * 导出批次物料获取入库数量
	 */
	 function getStockNumbTotal($rows){
	 	if ($rows) {
			foreach ( $rows as $key => $val ) {
					$stockNumbTotal=0;
					$this->searchArr=array("planEquId"=>$val['id']);
					$orderEquRows = $this->listBySqlId("select_stockNumbTotal");
//							//获取订单物料信息
							if(is_array($orderEquRows)){
								foreach($orderEquRows as $oKey=>$oVal){
									$stockNumbTotal=$stockNumbTotal+$oVal['amountIssued'];
								}
							}
					$rows[$key]['stockNumbTotal'] = $stockNumbTotal;
			}
			return $rows;
	 	}
	 	return $rows;
	 }

	/**
	 * 查看采购计申请物料执行情况
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showEquExecuteList_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			$taskEauDao   = new model_purchase_task_equipment();
			$orderEquDao  = new model_purchase_contract_equipment();
			foreach ( $listEqu as $key => $val ) {
				$taskNumbTotal=0;//任务数量小计
				$inquiryNumbTotal=0;//询价数量总计
				$orderNumbTotal=0;
				$stockNumbTotal=0;
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				//add by chengl 2012-04-06
				$productName=empty($val['productName'])?$val['inputProductName']:$val['productName'];
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td width="28%">$val[productNumb]<br>$productName</td>
						<td width="8%">$val[amountAll]</td>
				        <td width="60%" class="tdChange td_table" colspan="6">
							<table width="100%" class="shrinkTable main_table_nested">
EOT;
				$taskEquRows=$taskEauDao->findByPlanEquId($val['id']);
				if(is_array($taskEquRows)){
					foreach($taskEquRows as $tKey=>$tVal){
						$taskNumbTotal=$taskNumbTotal+$tVal['amountAll'];
						$inquiryNumbTotal=$inquiryNumbTotal+$tVal['amountIssued'];
						$str .= <<<EOT
							<tr>
								<td width="20%">$tVal[sendName]</td>
								<td width="15%">$tVal[amountAll]</td>
								<td width="15%">$tVal[amountIssued]</td>
						        <td width="50%" class="tdChange td_table" colspan="6">
									<table width="100%" class="shrinkTable main_table_nested">
EOT;
						//获取订单物料信息
						$orderEquRows=$orderEquDao->getEqusByTaskEquId($tVal['id']);
						if(is_array($orderEquRows)){
							foreach($orderEquRows as $oKey=>$oVal){
								$orderNumbTotal=$orderNumbTotal+$oVal['amountAll'];
								$stockNumbTotal=$stockNumbTotal+$oVal['amountIssued'];
								$str .= <<<EOT
									<tr>
										<td width="20%">$oVal[amountAll]</td>
										<td width="30%">$oVal[dateHope]</td>
										<td width="30%">$oVal[dateIssued]</td>
										<td width="20%">$oVal[amountIssued]</td>
									</tr>
EOT;
							}
						}else{
								$str .= <<<EOT
									<tr>
										<td width="20%">0</td>
										<td width="30%">--</td>
										<td width="30%">--</td>
										<td width="20%">0</td>
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
								<td width="20%">--</td>
								<td width="15%">0</td>
								<td width="15%">0</td>
						        <td width="50%" class="tdChange td_table" colspan="6">
									<table width="100%" class="shrinkTable main_table_nested">
									<tr>
										<td width="20%">0</td>
										<td width="30%">--</td>
										<td width="30%">--</td>
										<td width="20%">0</td>
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
					<tr class='tr_count'>
					<td><b>小计</b></td>
					<td></td>
					<td>$val[amountAll]</td>
					<td width="60%" class="tdChange td_table" colspan="6">
						<table width="100%" class="shrinkTable main_table_nested">
						<tr>
							<td width="20%"></td>
							<td width="15%">$taskNumbTotal</td>
							<td width="15%">$inquiryNumbTotal</td>
								<td width="50%" class="tdChange td_table" colspan="6">
									<table width="100%" class="shrinkTable main_table_nested">
									<tr>
										<td width="20%">$orderNumbTotal</td>
										<td width="30%"></td>
										<td width="30%"></td>
										<td width="20%">$stockNumbTotal</td>
									</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 查看采购计划列表(带物料类型)
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showReadType_s($listEqu) {
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$systeminfoDao=new model_stock_stockinfo_systeminfo();
		$stockSysObj=$systeminfoDao->get_d("1");
		$saleStockId=$stockSysObj['salesStockId'];
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>$val[productNumb] <br/> $val[productName]</td>
						<td>$val[productTypeName]</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[qualityName]</td>
				        <td>
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
						<td>$val[leastPackNum]</td>
						<td>$val[leastOrderNum]</td>
						<td>$val[amountAllOld]</td>
						<td>$val[amountAll]</td>
						<td>$val[amountIssued]</td>
						<td>$val[dateIssued]</td>
						<td>$val[dateHope]</td>
						<td>$val[dateEnd]</td>
						<td>
							<textarea class="textarea_read" readOnly>$val[remark]</textarea>
						</td>
						<td>
							<textarea class="textarea_read" readOnly>$val[appOpinion]</textarea>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 编辑采购申请物料列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showEdit_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>
						<input type="text" class="readOnlyTxtItem" id="" name="basic[equipment][$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" id="productId$i" name="basic[equipment][$i][productId]" value="$val[productId]"/> </td>
						<td>
						<input type="text" class="readOnlyTxtItem" id="productName$i" name="basic[equipment][$i][productName]" value="$val[productName]"/> </td>
						<td>
						<input type="text" class="readOnlyTxtItem" id="pattem$i" name="basic[equipment][$i][pattem]" value="$val[pattem]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="unitName$i" name="basic[equipment][$i][unitName]" value="$val[unitName]"/></td>
						<td>
						<input type="text" class="txtshort" id="amountAll$i" name="basic[equipment][$i][amountAll]" value="$val[amountAll]"/></td>
						<td>
						<input type="text" class="readOnlyTxtItem" id="dateIssued$i" name="basic[equipment][$i][dateIssued]" value="$val[dateIssued]"/></td>
						<td>
						<input type="text" class="txtshort" id="dateHope$i"  onfocus="WdatePicker()" name="basic[equipment][$i][dateHope]" value="$val[dateHope]"/></td>
						<td>
							<input type="text" class="txt" value="$val[remark]" name="basic[equipment][$i][remark]"/>
							<input type="hidden"  value="" name="basic[equipment][$i][equObjAssId]"/>
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif"></td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 编辑资产物料列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showAssetEdit_s($plan) {
		$listEqu=$plan['childArr'];
		$ExaStatus=$plan['ExaStatus'];
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				if($ExaStatus!="物料确认打回"||($ExaStatus=="物料确认打回"&&$val['isBack'])){
					$i ++;
					$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
					$isProduce=$val['isProduce'];
					$checked=$isProduce==1?"checked":"";
					$trstyle="";
					$isBackCheckbox="";
					$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>
							<input type="hidden" name="basic[equipment][$i][id]" value="$val[id]" />
						<select  id="productCategoryCode$i" name="basic[equipment][$i][productCategoryCode]" val="$val[productCategoryCode]"/>
						<td>
						<input type="text" class="txt" id="inputProductName$i" name="basic[equipment][$i][inputProductName]" value="$val[inputProductName]"/> </td>
						<td>
						<input type="text" class="txtshort" id="amountAll$i" name="basic[equipment][$i][amountAll]" value="$val[amountAll]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="dateIssued$i" name="basic[equipment][$i][dateIssued]" value="$val[dateIssued]"/></td>
						<td>
						<input type="text" class="txtshort" id="dateHope$i"  onfocus="WdatePicker()" name="basic[equipment][$i][dateHope]" value="$val[dateHope]"/></td>
						<td>
							<input type="text" class="txt" value="$val[remark]" name="basic[equipment][$i][remark]"/>
							<input type="hidden"  value="" name="basic[equipment][$i][equObjAssId]"/>
						</td>
						<td>
							<img id="delButton$i" title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif"></td>
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
	 * 编辑研发采购申请物料列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showRdEdit_s($plan) {
		$listEqu=$plan['childArr'];
		$ExaStatus=$plan['ExaStatus'];
		$str = "";
		$i = 0;
		if ($listEqu) {
			$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ( $listEqu as $key => $val ) {
				if($ExaStatus!="物料确认打回"||($ExaStatus=="物料确认打回"&&$val['isBack'])){
					$i ++;
					$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
					$isProduce=$val['isProduce'];
					$checked=$isProduce==1?"checked":"";
					$checkTypeStr=$this->getDatadictsStr( $datadictArr ['CGZJSX'], $val['qualityCode'] );
					$trstyle="";
					$isBackCheckbox="";
				//$backReasonCheckbox="";
					$str .=<<<EOT
						<tr class="$iClass">
							<td>$i</td>
							<td>
							<input type="hidden" name="basic[equipment][$i][id]" value="$val[id]" />
							<select  id="productCategoryCode$i" name="basic[equipment][$i][productCategoryCode]" val="$val[productCategoryCode]"/></td>
							<td>
							<input type="checkbox"  id="isProduce$i" name="basic[equipment][$i][isProduce]" val="1" $checked/></td>
							<td>
							<input type="text" class="txtshort"  id="productNumb$i" name="basic[equipment][$i][productNumb]" value="$val[productNumb]"/>
							<input type="hidden" id="productId$i" name="basic[equipment][$i][productId]" value="$val[productId]"/> </td>
							<td>
							<input type="text" class="txt" id="productName$i" name="basic[equipment][$i][productName]" value="$val[productName]"/> </td>
							<script>
								processProductCmp($i);
								if($isProduce==0){
									jQuery("#productName$i").attr("readonly",false);
								}
							</script>
							<td>
							<input type="text" class="readOnlyTxtItem" id="pattem$i" name="basic[equipment][$i][pattem]" value="$val[pattem]"/></td>
							<td>
							<input type="text" class="readOnlyTxtShort" id="unitName$i" name="basic[equipment][$i][unitName]" value="$val[unitName]"/></td>
							<td>
								<select  name="basic[equipment][$i][qualityCode]">$checkTypeStr</select>
							</td>
							<td>
							<input type="text" class="txtshort" id="amountAll$i" name="basic[equipment][$i][amountAll]" value="$val[amountAll]"/></td>
							<td>
							<input type="text" class="readOnlyTxtShort" id="dateIssued$i" name="basic[equipment][$i][dateIssued]" value="$val[dateIssued]"/></td>
							<td>
							<input type="text" class="txtshort" id="dateHope$i"  onfocus="WdatePicker()" name="basic[equipment][$i][dateHope]" value="$val[dateHope]"/></td>
							<td>
								<input type="text" class="txt" value="$val[remark]" name="basic[equipment][$i][remark]"/>
								<input type="hidden"  value="" name="basic[equipment][$i][equObjAssId]"/>
							</td>
							<td>
								<img id="delButton$i" title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif"></td>
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
	 * 物料确认采购申请(资产及研发)物料列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showConfirmEdit_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				if(empty($val['productId'])&&$val['isBack']==0){
					$i ++;
					$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
					$productName=empty($val['productId'])?"":$val['productName'];
					$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>
						$val[productCategoryName]
						<td>
						$val[inputProductName]
						</td>
						<td>
						<input type="hidden"  name="basic[equipment][$i][id]" value="$val[id]"/>
						<input type="text" class="txtshort" id="productNumb$i" name="basic[equipment][$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" id="productId$i" name="basic[equipment][$i][productId]" value="$val[productId]"/> </td>
						<td>
						<input type="text" class="txt" id="productName$i" name="basic[equipment][$i][productName]" value="$productName"/> </td>
						<script>
							processProductCmp($i);
						</script>

						<td>
						<input type="text" class="readOnlyTxtItem" id="pattem$i" name="basic[equipment][$i][pattem]" value="$val[pattem]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="unitName$i" name="basic[equipment][$i][unitName]" value="$val[unitName]"/></td>
						<td>
						$val[amountAll]</td>
						<td>
						$val[dateIssued]</td>
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
	 * 编辑采购申请物料列表(有物料类型列，目前只有生产采购)
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showEditType_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ( $listEqu as $key => $val ) {
				$checkTypeStr=$this->getDatadictsStr( $datadictArr ['CGZJSX'], $val['qualityCode'] );
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>
							<img title="删除行" onclick="mydelProduce(this , 'mytable')" src="images/closeDiv.gif"></td>
						<td>$i</td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="" name="basic[equipment][$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" id="productId$i" name="basic[equipment][$i][productId]" value="$val[productId]"/> </td>
						<td>
						<input type="text" class="readOnlyTxtMiddle" id="productName$i" name="basic[equipment][$i][productName]" value="$val[productName]"/> </td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="" name="basic[equipment][$i][productTypeName]" value="$val[productTypeName]"/>
						<input type="hidden" id="productTypeId$i" name="basic[equipment][$i][productTypeId]" value="$val[productTypeId]"/>
						</td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="pattem$i" name="basic[equipment][$i][pattem]" value="$val[pattem]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="unitName$i" name="basic[equipment][$i][unitName]" value="$val[unitName]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="leastPackNum$i" name="basic[equipment][$i][leastPackNum]" value="$val[leastPackNum]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="leastOrderNum$i" name="basic[equipment][$i][leastOrderNum]" value="$val[leastOrderNum]"/></td>

						<td>
							<select  name="basic[equipment][$i][qualityCode]">$checkTypeStr</select>
						</td>
						<td>
						<input type="text" class="txtshort" id="amountAll$i" name="basic[equipment][$i][amountAll]" value="$val[amountAll]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="dateIssued$i" name="basic[equipment][$i][dateIssued]" value="$val[dateIssued]"/></td>
						<td>
						<input type="text" class="txtshort" id="dateHope$i"  onfocus="WdatePicker()" name="basic[equipment][$i][dateHope]" value="$val[dateHope]"/></td>
						<td>
							<input type="text" class="txtshort" value="$val[remark]" name="basic[equipment][$i][remark]"/>
							<input type="hidden"  value="" name="basic[equipment][$i][equObjAssId]"/>
						</td>
					</tr>
EOT;
			}
			$str.="<input type='hidden' id='countNumP' value='$i'/>";
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

		/**
	 * 采购申请物料列表(有物料类型列，目前只有生产采购)，物料BOM清单
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showProductApply_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			$productDao=new model_stock_productinfo_productinfo();
			$datadictArr = $this->getDatadicts ( "CGZJSX" );
			$date=date("Y-m-d");
			foreach ( $listEqu as $key => $val ) {
//				if($val['purchTotalNum']>0){
				$checkTypeStr=$this->getDatadictsStr( $datadictArr ['CGZJSX'] );
				$productInfo=$productDao->get_d($val['productId']);
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>
							<img title="删除行" onclick="mydelProduce(this , 'mytable')" src="images/closeDiv.gif"></td>
						<td>$i</td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="" name="basic[equipment][$i][productNumb]" value="$val[productCode]"/>
						<input type="hidden" id="productId$i" name="basic[equipment][$i][productId]" value="$val[productId]"/> </td>
						<td>
						<input type="text" class="readOnlyTxtMiddle" id="productName$i" name="basic[equipment][$i][productName]" value="$val[productName]"/> </td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="" name="basic[equipment][$i][productTypeName]" value="$productInfo[proType]"/>
						<input type="hidden" id="productTypeId$i" name="basic[equipment][$i][productTypeId]" value="$productInfo[proTypeId]"/>
						</td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="pattem$i" name="basic[equipment][$i][pattem]" value="$val[pattern]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="unitName$i" name="basic[equipment][$i][unitName]" value="$val[unitName]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="leastPackNum$i" name="basic[equipment][$i][leastPackNum]" value="$productInfo[leastPackNum]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="leastOrderNum$i" name="basic[equipment][$i][leastOrderNum]" value="$productInfo[leastOrderNum]"/></td>

						<td>
							<select  name="basic[equipment][$i][qualityCode]">$checkTypeStr</select>
						</td>
						<td>
						<input type="text" class="amount txtshort" id="amountAll$i" name="basic[equipment][$i][amountAll]" value="$val[purchTotalNum]"/></td>
						<td>
						<input type="text" class="readOnlyTxtShort" id="dateIssued$i" name="basic[equipment][$i][dateIssued]" value="$date"/></td>
						<td>
						<input type="text" class="txtshort" id="dateHope$i"  onfocus="WdatePicker()" name="basic[equipment][$i][dateHope]" value="$date"/></td>
						<td>
							<input type="text" class="txtshort" value="" name="basic[equipment][$i][remark]"/>
							<input type="hidden"  value="" name="basic[equipment][$i][equObjAssId]"/>
						</td>
					</tr>
EOT;

//				}
			}
			$str.="<input type='hidden' id='invnumber' value='$i'/>";
		}else {
			$str = "";
		}
		return $str;
	}

	/**
	 * 编辑采购申请物料列表(有物料类型列，目前只有生产采购)
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showEditAudit_s($listEqu) {
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$systeminfoDao=new model_stock_stockinfo_systeminfo();
		$stockSysObj=$systeminfoDao->get_d("1");
		$saleStockId=$stockSysObj['salesStockId'];
		$str = "";
		$i = 0;
		if ($listEqu) {
			$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ( $listEqu as $key => $val ) {
				$checkTypeStr=$this->getDatadictsStr( $datadictArr ['CGZJSX'], $val['qualityCode'] );
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>$i</td>
						<td>$val[productNumb] <br/> $val[productName]</td>
						<td>$val[productTypeName]</td>
						<td>$val[pattem]</td>
						<td>$val[unitName]</td>
						<td>$val[qualityName]</td>
				        <td>
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
						<td>$val[leastPackNum]</td>
						<td>$val[leastOrderNum]</td>
						<td>$val[amountAllOld]</td>
						<!--<td>
							<input type="text" class="txtshort" id="amountAll$i" name="basic[equipment][$i][amountAll]" value="$val[amountAll]"/>
							<input type="hidden" class="txtshort" id="id$i" name="basic[equipment][$i][id]" value="$val[id]"/>
						</td>
						<td>
						$val[dateHope]</td>
						<!--<td>
							<input type="checkbox" class="txtshort" checked id="isPurch$i" name="basic[equipment][$i][isPurch]"/>
						</td>-->
					</tr>
EOT;
			}
			$str.="<input type='hidden' id='countNumP' value='$i'/>";
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}

	/**
	 * @exclude 变更采购计划列表
	 * @author ouyang
	 * @param　$listEqu　采购申请物料数组
	 * @version 2010-8-17 下午03:53:55
	 */
	function showChange_s($listEqu) {
		$str = "";
		$i = 0;
		if ($listEqu) {
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="basic[equment]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="basic[equment]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
					<tr class="$iClass">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
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
							$val[amountIssued]
						</td>
						<td>
							<input type="text" size="10" id="amountAll$i" name="basic[equment][$i][amountAll]" value="$val[amountAll]" class="taskAmount" />

							<input type="hidden" name="basic[equment][$i][maxAmount]"  value="$val[amountAll]" />
							<input type="hidden" name="basic[equment][$i][amountIssued]" value="$val[amountIssued]" />
							<!--
							<input type="hidden" name="basic[equment][$i][id]" value="$val[id]" /> -->
							<input type="hidden" name="basic[equment][$i][basicNumb]" value="$val[basicNumb]" />
							<input type="hidden" name="basic[equment][$i][purchType]" value="$val[purchType]" />
							<input type="hidden" name="basic[equment][$i][productName]" value="$val[productName]" />
							<input type="hidden" name="basic[equment][$i][productId]" value="$val[productId]" />
							<input type="hidden" name="basic[equment][$i][productNumb]" value="$val[productNumb]" />
							<input type="hidden" name="basic[equment][$i][amountIssuedActual]" value="$val[amountIssuedActual]" />
							<input type="hidden" name="basic[equment][$i][dateIssued]" value="$val[dateIssued]"/>
							<input type="hidden" name="basic[equment][$i][dateEnd]" value="$val[dateEnd]" />
						</td>
						<td>
							&nbsp;<input type="text" id="dateHope$i" name="basic[equment][$i][dateHope]" size="9" maxlength="12" class="BigInput"  onfocus="WdatePicker()"  value="$val[dateHope]" readonly />
						</td>
						<td>
							<input class="txt" id="remark$i" name="basic[equment][$i][remark]" value="$val[remark]"/>
						</td>
					</tr>
EOT;
			}
		} else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}

		return $str;
	}

	/**
	 * 显示计划-设备列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showPlanlist_s($rows) {
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$orderEquDao=new model_purchase_contract_equipment();
		$systeminfoDao=new model_stock_stockinfo_systeminfo();
		$stockSysObj=$systeminfoDao->get_d("1");
		$saleStockId=$stockSysObj['salesStockId'];
		$str = "";
		$i = 0;
		$j=0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				if(is_array($val ['childArr'])){
				$stockNum= $inventoryDao->getActNumByProId( $val['productId']);
				$i ++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$productSureStatus="未确认";
				if($val['productSureStatus']==1){
					$productSureStatus="已确认";
				}else if($val['productSureStatus']==2){
					$productSureStatus="部分确认";
				}


				//$mainCheckbox=$val['productSureStatus']==1?"<input id='allCheckbox$i' type='checkbox'>":"";
				$mainCheckbox="<input id='allCheckbox$i' type='checkbox' />";

				$str .= <<<EOT
			<tr class="$classCss">
				<td   name="tdth01">
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td align="left"  name="tdth02">
		            <p class="checkChildAll">$mainCheckbox $val[planNumb] </p>
		        </td>
		        <td   name="tdth03">
		            $val[department]
		        </td>
		        <td   name="tdth04">
		            $val[sendName]
		        </td>
		        <td   name="tdth05">
		            $val[purchTypeCName]
		        </td>
		        <td   name="tdth14">
					$productSureStatus
		        </td>
		        <td width="55%" class="tdChange td_table" >
					<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				if (is_array ( $val ['childArr'] )) {
					foreach ( $val ['childArr'] as $chdKey => $chdVal ) {
						$chdVal['actNum']= $inventoryDao->getActNumByProId( $chdVal['productId']);
						//在途数量
						$onwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$chdVal['productId']));
						//在途数量(补库)
						$fillupOnwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$chdVal['productId'],'purchTypeArr'=>'stock'));

						$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$chdVal['productId']);
						$inventoryArr=$inventoryDao->listBySqlId();
						$stockNum=$inventoryArr[0]['exeNum'];
						//add by chengl 2012-04-07
						$productName=empty($chdVal['productName'])?$chdVal['inputProductName']:$chdVal['productName'];
						$productNumb=empty($chdVal['productNumb'])?$chdVal['productCategoryName']:$chdVal['productNumb'];
						if(!is_array($inventoryArr)){
							$stockNum="0";
						}

						$checkBoxStr = '';
						if ($chdVal ['amountWait'] != 0 && $chdVal ['amountWait'] != ""&&!empty($chdVal['productId'])) {
							$j++;
							$typeFlag='';
							if($chdVal['purchType']=='oa_asset_purchase_apply'){
								$typeFlag='asset';
							}
							$checkBoxStr = <<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$typeFlag$chdVal[id]"/>
EOT;
						}

						//获取物料采购负责人
						if($chdVal['productNumb']!=""){
							$purchManger=$this->get_table_fields('oa_stock_product_info','productCode="'.$chdVal['productNumb'].'"','purchUserName');
						}else{
							$purchManger="";
						}
						$confirmStr="";
						if($val[productSureStatus]==0){
							$confirmStr="<option value='confrimUser'>分配人员</option>";
						}
						$feedbackStr ="";
						if($val['purchType']== 'oa_asset_purchase_apply'){
							$feedbackStr="<option value='feedback'>反馈信息</option>";
						}
						$str .= <<<EOT
					<tr align="center">
	        			<td  align="left"  name="tdth06">
				            $checkBoxStr
				            $productNumb<br> $productName
				        </td>
				        <td width="10%"  name="tdth13">
				            $purchManger
				        </td>
				        <td width="10%"  name="tdth07">
				           $chdVal[amountAllOld]
				        </td>
				        <td width="10%"  name="tdth07">
				            $chdVal[amountAll]
				        </td>
				        <td width="10%"  name="tdth08">
				            $chdVal[amountWait]
				        </td>
				        <td width="10%"  name="tdth09">
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$chdVal[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
				        <td width="15%"  name="tdth10" title="补库在途数量:$fillupOnwayAmount">
				            $onwayAmount($fillupOnwayAmount)
				        </td>
				        <td width="15%"  name="tdth12">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
					}
				}
					$str .= <<<EOT
	        	</table>
	        	<div class="readThisTable"><单击展开物料具体信息></div>
	        </td>
	        <td  name="tdth11">

					<SCRIPT language=JavaScript>
						if($j==0){
							jQuery("#allCheckbox$i").hide();
						}
					</SCRIPT>
				<select onchange=" selectButton( this , '$val[id]','$val[purchType]' ) " >
				    <option value="value1">请选择</option>
				    <option value="read">查看</option>
				    $confirmStr
				    $feedbackStr
				    <option value="close">关闭</option>
			  	</select>
			  	<input type="hidden" id="check$val[id]" value="$val[skey_]"/>
			</td>
	    </tr>
EOT;
			$j=0;

				}
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 显示计划-设备列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showListByContract_s($rows) {
		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$str = "";
		$i = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$i ++;
				$classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
			<tr class="$classCss">
				<td  >
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td align="left" >
		             $val[planNumb]
		        </td>
		        <td  >
		            $val[createName]
		        </td>
		        <td  >
		            $val[purchTypeCName]
		        </td>
		        <td  >
		            $val[stateC]
		        </td>
		        <td width="50%" class="tdChange td_table" >
					<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				if (is_array ( $val ['childArr'] )) {
					foreach ( $val ['childArr'] as $chdKey => $chdVal ) {
						$chdVal['actNum']= $inventoryDao->getActNumByProId( $chdVal['productId']);    //查看即时库存

						$str .= <<<EOT
					<tr align="center">
	        			<td  align="left" >
				            $chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="13%">
				            $chdVal[amountAll]
				        </td>
				        <td width="13%">
				            $chdVal[amountWait]
				        </td>
				        <td width="13%">
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$chdVal[productId]">$chdVal[actNum]</a>
				        </td>
				        <td width="20%">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
					}
				}
					$str .= <<<EOT
	        	</table>
	        	<div class="readThisTable"><单击展开物料具体信息></div>
	        </td>
	        <td >
				<select onchange=" selectButton( this , '$val[id]' ) " >
				    <option value="value1">请选择</option>
				    <option value="read">查看</option>
			  	</select>
			  	<input type="hidden" id="check$val[id]" value="$val[skey_]"/>
			</td>
	    </tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}



	/**
	 * 显示计划列表-已完成
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showPlanEndlist_s($rows) {
		$str = '';
		$i = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
			<tr class="TableLine $iClass" align="center" >
				<td   name="tdth01">
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td   name="tdth02">
		            $val[planNumb]
		        </td>
		        <td   name="tdth03">
		            $val[purchTypeCName]
		        </td>
		        <td   name="tdth04">
		             $val[sendTime]
		        </td>
		        <td   name="tdth05">
		             $val[dateEnd]
		        </td>
		        <td   name="tdth06">
		             $val[batchNumb]
		        </td>
		        <td width="38%" class="tdChange td_table" >

EOT;
				foreach ( $val ['childArr'] as $chdKey => $chdVal ) {
					if($val['purchType']=='oa_asset_purchase_apply'){
						$amountAll=$chdVal['applyAmount'];
						$dateEnd=$val['dateEnd'];
					}else{
						$amountAll=$chdVal['amountAll'];
						$dateEnd=$chdVal['dateEnd'];

					}
					$str .= <<<EOT
					<table width="100%"  class="shrinkTable main_table_nested">
						<tr align="center">
		        			<td  align="left"   name="tdth07">
					        	$chdVal[productNumb] <br> $chdVal[productName]
					        </td>
					        <td width="20%"   name="tdth08">
								$amountAll
					        </td>
					        <td width="30%"   name="tdth09">
					            $dateEnd
					        </td>
		        		</tr>
	        		</table>
EOT;
				}
					if($val['purchType']=='oa_asset_purchase_apply'){
						$clickRead='?model=asset_purchase_apply_apply&action=purchView&id='.$val[id].'&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000';
						$readStr="<a href='".$clickRead.";' class='thickbox'>查看</a>｜";
						$reopenStr='<img src="images/icon/view.gif" title="重新启动申请" onclick="startApply('.$val[id].');"/>';
					}else{
						$readStr='<a href="?model=purchase_plan_basic&action=read&id='.$val[id].'&purchType='.$val[purchType].'&skey='.$val[skey_].'">查看</a>｜';
						$reopenStr='<img src="images/icon/view.gif" title="重新启动申请" onclick="startPlan('.$val[id].');"/>';
					}

				$str .= <<<EOT

		        	<div class="readThisTable"><单击展开物料具体信息></div>
		        </td>
		        <td name="tdth10">
		        $readStr
		        $reopenStr
				</td>
		    </tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 显示我下达的采购计划-设备列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showMyPlanlist_s($rows) {
		$str = "";
		$i = 0;
		if ( is_array( $rows ) ) {
			foreach ( $rows as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
			<tr class="TableLine $iClass" align="center">
				<td  >
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td align="left" >
		            $val[planNumb]
		        </td>
		        <td  >
		            $val[sourceNumb]
		        </td>
		        <td  >
		            $val[createName]
		        </td>
		        <td  >
		            $val[purchTypeCName]
		        </td>
		        <td  >
		            $val[stateC]
		        </td>
		        <td width="50%" class="tdChange td_table" >

EOT;
				if (is_array ( $val ['childArr'] )) {
					foreach ( $val ['childArr'] as $chdKey => $chdVal ) {
						$str .= <<<EOT
						<table width="100%"  class="shrinkTable main_table_nested">
							<tr align="center">
			        			<td  align="left" >
						            <!--input type="checkbox" class="checkChild"-->
						        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
						        	$chdVal[productNumb] <br> $chdVal[productName]
						        </td>
						        <td width="20%">
						            $chdVal[amountAll]
						        </td>
						        <td width="20%">
						            $chdVal[amountWait]
						        </td>
						        <td width="20%">
						            $chdVal[dateHope]
						        </td>
			        		</tr>
			        	</table>
EOT;
					}
					$str .= <<<EOT

		        	<div class="readThisTable"><单击展开物料具体信息></div>
		        </td>
		        <td >
					<select onchange=" selectButton( this , '$val[id]' ) " >
					    <option value="value1">请选择</option>
					    <option value="read">查看</option>
					    <option value="change">变更</option>
					    <!--option value="close">退回此计划</option-->
				  	</select>
				</td>
		    </tr>
EOT;
				}
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;

	}

	/**
	 * 显示我下达完成的采购计划-设备列表
	 *
	 * @param	array	显示所需数组
	 * @return	string	显示HTML字符串
	 */
	function showMyPlanEndlist_s($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ( $rows as $key => $val ) {
				$i ++;
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				$str .= <<<EOT
			<tr class="TableLine $iClass" align="center" >
				<td >
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td width="11%" >
		            $val[planNumb]
		        </td>
		        <td  width="15%" >
		            $val[sourceNumb]
		        </td>
		        <td width="7%" >
		             $val[sendTime]
		        </td>
		        <td width="10%" >
		             $val[batchNumb]
		        </td>
		        <td width="20%" >
		             $val[applyReason]
		        </td>
		        <td width="30%" class="tdChange td_table" >
					<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ( $val ['childArr'] as $chdKey => $chdVal ) {
					$str .= <<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[amountAll]
				        </td>
	        		</tr>
EOT;
				}
				$str .= <<<EOT
		        	</table>
		        	<div class="readThisTable"><单击展开物料具体信息></div>
		        </td>
		        <td width="50" >
					<a href="?model=purchase_plan_basic&action=read&id=$val[id]&purchType=$val[purchType]&skey=$val[skey_]">查看</a>
				</td>
		    </tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/*****************************************页面模板结束********************************************/

	/*****************************************状态和类型相关开始********************************************/

	/**
	 * 通过value查找状态
	 * $stateVal 状态value
	 */
	function stateToVal($stateVal) {
		$returnVal = false;
		foreach ( $this->state as $key => $val ) {
			if ($val ['stateVal'] == $stateVal) {
				$returnVal = $val ['stateCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 通过状态查找value
	 * $stateSta 状态
	 */
	function stateToSta($stateSta) {
		$returnVal = false;
		foreach ( $this->state as $key => $val ) {
			if ($val ['stateEName'] == $stateSta) {
				$returnVal = $val ['stateVal'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
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
	 * 采购类型
	 * 返回数组
	 */
	function purchTypeToArr($purchVal) {
		$returnVal = false;
		foreach ( $this->purchaseType as $key => $val ) {
			if ($val ['purchKey'] == $purchVal) {
				$returnVal = $val;
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 采购类型
	 * 通过状态查找value
	 */
	function purchTypeToSta($purchVal) {
		$returnVal = false;
		foreach ( $this->purchaseType as $key => $val ) {
			if ($val ['purchCName'] == $purchVal) {
				$returnVal = $val ['purchKey'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 将数组中采购类型转换成中文
	 */
	function purchTypArrayCName($chiRows) {
		foreach ( $chiRows as $chiKey => $chiVal ) {
			$showStatus = $this->purchTypeToVal ( $chiVal ['purchType'] );
			$chiRows [$chiKey] ['purchTypeCName'] = $showStatus;
		}
		return $chiRows;
	}

	/**
	 * 将计划对象中采购类型转换成中文
	 * $plan 采购申请数组
	 */
	function purchTypeToCName($plan) {
		$showStatus = $this->purchTypeToVal ( $plan ['purchType'] );
		$plan ['purchTypeCName'] = $showStatus;
		return $plan;
	}

	/**
	 * $code 数据字典中编码
	 * return 采购类型数组
	 */
	function getPlanType($code) {
		return $this->planType [$code];
	}

	/*****************************************状态和类型相关结束********************************************/


	/*****************************************业务处理开始********************************************/

	/**
	 * @exclude 通过Id返回采购计划列表
	 * @author ouyang
	 * @param	$id 采购申请ID
	 * @return
	 * @version 2010-8-17 上午09:49:18
	 */
	function readPlanByBasicId_d($id) {
		$searchArr = array ('id' => $id );
		$this->__SET ( 'searchArr', $searchArr );
		$rows = $this->listBySqlId ( 'plan_list_page' );
		$i = 0;
		if ($rows) {
			//设置采购类型
			$rows = $this->purchTypArrayCName ( $rows );
			$equipment = new model_purchase_plan_equipment ();
			foreach ( $rows as $key => $val ) {
				$equipment->resetParam ();
				$searchArr = array ('basicId' => $val ['id'] );//'status' => $equipment->statusToSta('execution')

				$equipment->__SET ( 'groupBy', 'p.id' );
				$equipment->__SET ( 'sort', 'p.productId' );
				$equipment->__SET ( 'searchArr', $searchArr );
				$chiRows = $equipment->listBySqlId ( 'equipment_list' );
				$rows [$i] ['childArr'] = $chiRows;
				++ $i;
			}
			return $rows;
		} else {
			return false;
		}
	}

	/**
	 * @exclude 接收变更
	 * @author ouyang
	 * @param	$id 采购申请ID
	 * @param	$id 采购申请编号
	 * @return
	 * @version 2010-8-17 上午11:22:58
	 */
	function receiveChange_d($id, $numb) {
		try {
			$this->start_d ();
			$oldCondition = array ('planNumb' => $numb, 'isUse' => '1' );
			$oldObject = array ('state' => $this->stateToSta ( 'close' ) );
			$this->update ( $oldCondition, $oldObject );

			$equDao = new model_purchase_plan_equipment ();
			$oldConditionEqu = array ('basicNumb' => $numb, 'deviceIsUse' => '1' );
			$oldObjectEqu = array ('deviceIsUse' => '0' );
			$equDao->update ( $oldConditionEqu, $oldObjectEqu );

			$newCondition = array ('planNumb' => $numb, 'state' => $this->stateToSta ( 'change' ) );
			$newObject = array ('state' => $this->stateToSta ( 'execute' ), 'isUse' => '1' );
			$this->update ( $newCondition, $newObject );

			$newConditionEqu = array ('basicId' => $id );
			$newObjectEqu = array ('deviceIsUse' => '1' );
			$equDao->update ( $newConditionEqu, $newObjectEqu );

			//TODO:采购计划变更接收 更新合同下达数量
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * @exclude 退回变更
	 * @author ouyang
	 * @param	$id 采购申请ID
	 * @param	$id 采购申请编号
	 * @return
	 * @version 2010-8-17 上午11:23:13
	 */
	function returnChange_d($id, $numb) {
		try {
			$this->start_d ();
			$oldCondition = array ('planNumb' => $numb, 'isUse' => '1' );
			$oldObject = array ('state' => $this->stateToSta ( 'execute' ) );
			$this->update ( $oldCondition, $oldObject );

			$newCondition = array ('planNumb' => $numb, 'state' => $this->stateToSta ( 'change' ) );
			$newObject = array ('state' => $this->stateToSta ( 'close' ), 'isUse' => '0' );
			$this->update ( $newCondition, $newObject );

			//TODO:采购计划变更拒绝 更新合同下达数量
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();;
			return false;
		}
	}

	/***********************************************数据操作方法*****************************************************/

	/**
	 * 采购计划获取数据方法-分页
	 */
	function pagePlan_d() {
		$rows = $this->pageBySqlId ( 'plan_list_noAsset' );
		if ($rows) {
			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();
			//获取设备数据
			$equipment = new model_purchase_plan_equipment ();
			$rows = $equipment->getPlanAsEqu_d ( $rows );
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				//计算每一个采购申请的物料未下达数量之和
				$rows[$key]['waitNumSum']=0;
				foreach($val['childArr'] as $equKey=>$equVal){
					$rows[$key]['waitNumSum']=$rows[$key]['waitNumSum']+$equVal['amountWait'];
				}
					if($rows[$key]['waitNumSum']>0){
						$rows[$key]['waitNumSum']=1;
					}
				if($rows[$key]['waitNumSum']==0){
//					$condiction=array('id' => $val['id']);
//					$obj = array ('state' => $this->stateToSta ( 'end' ), 'dateEnd' => date ( 'Y-m-d' ), 'dateFact' => date ( 'Y-m-d' ) ,'updateTime'=>date('Y-m-d H:i:s'));
//					parent::update ($condiction, $obj );
				}
				$watiNumSum[$key]=$rows[$key]['waitNumSum'];
				$ids[$key]=$val['id'];
				$purchTypes[$key]=$val['purchType'];
			}
			$rows=$this->uniquePlanRow_d($rows);
			//根据物料的未下达数量之和与id进行排序
			array_multisort($watiNumSum,SORT_DESC,$purchTypes,SORT_DESC,$ids,SORT_DESC,$rows);
			return $rows;
		} else {
			return false;
		}
	}

		/**
	 * 采购计划获取数据方法-分页
	 */
	function pageListPlan_d() {
		$rows = $this->pageBySqlId ( 'plan_list_noAsset' );
		if ($rows) {
			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();
			//获取设备数据
			$equipment = new model_purchase_plan_equipment ();
			$rows = $equipment->getPlanEqu_d ( $rows );
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				//计算每一个采购申请的物料未下达数量之和
				$rows[$key]['waitNumSum']=0;
				if(is_array($val['childArr'])){
					foreach($val['childArr'] as $equKey=>$equVal){
						$rows[$key]['waitNumSum']=$rows[$key]['waitNumSum']+$equVal['amountWait'];
					}

				}
					if($rows[$key]['waitNumSum']>0){
						$rows[$key]['waitNumSum']=1;
					}
				if($rows[$key]['waitNumSum']==0){
//					$condiction=array('id' => $val['id']);
//					$obj = array ('state' => $this->stateToSta ( 'end' ), 'dateEnd' => date ( 'Y-m-d' ), 'dateFact' => date ( 'Y-m-d' ) ,'updateTime'=>date('Y-m-d H:i:s'));
//					parent::update ($condiction, $obj );
				}
				$watiNumSum[$key]=$rows[$key]['waitNumSum'];
				$ids[$key]=$val['id'];
				$purchTypes[$key]=$val['purchType'];
			}
//			$rows=$this->uniquePlanRow_d($rows);
			//根据物料的未下达数量之和与id进行排序
			array_multisort($watiNumSum,SORT_DESC,$purchTypes,SORT_DESC,$ids,SORT_DESC,$rows);
			return $rows;
		} else {
			return false;
		}
	}

		/**
	 * 采购申请-固定资产在执行列表
	 */
	function pageListUnion_d() {
		$rows = $this->pageBySqlId ( 'plan_list_union2' );
		if ($rows) {
			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();
			//获取设备数据
			$equipment = new model_purchase_plan_equipment ();
			$assetEquDao=new model_asset_purchase_apply_applyItem();
//			$rows = $equipment->getPlanEqu_d ( $rows );
			foreach ( $rows as $key => $val ) {
//				$rows[$key]['stateC'] = $this->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				if($val['purchType']!='oa_asset_purchase_apply'){
					$rows[$key]['childArr']=$equipment->getEquByPlanIdList_d ( $val['id'] );
				}else if($val['purchType']=='oa_asset_purchase_apply'){
					$rows[$key]['childArr']=$assetEquDao->getItemByApplyId($val['id'],'1','0');
					if(is_array($rows[$key]['childArr'])){
						foreach( $rows[$key]['childArr'] as $assetkey => $assetval ){
							$rows[$key]['childArr'][$assetkey]['amountAll'] = $assetval['applyAmount'];
							$rows[$key]['childArr'][$assetkey]['productNumb'] = $assetval['productCode'];
							//计划的总数量
							if( !isset( $assetval['applyAmount'] )||$assetval['applyAmount']==0 ||$assetval['applyAmount']=='' ){
								$rows[$key]['childArr'][$assetkey]['amountAll'] = 0;
								continue;
							}
							//未下达数量
							if( isset( $assetval['issuedAmount']) && $assetval['issuedAmount']!='' ){
								$rows[$key]['childArr'][$assetkey]['amountWait'] = $assetval['applyAmount'] - $assetval['issuedAmount'];
							}else{
								$rows[$key]['childArr'][$assetkey]['amountWait'] = $assetval['applyAmount'];
							}
							$rows[$key]['childArr'][$assetkey]['amountAllOld']=$rows[$key]['childArr'][$assetkey]['amountAll'];		//原申请数量等于审批数量
							$rows[$key]['childArr'][$assetkey]['purchType']='oa_asset_purchase_apply';
							$rows[$key]['childArr'][$assetkey]['purchTypeCName'] = $interfObj->typeKToC( 'oa_asset_purchase_apply' );	//类型名称
						}
					}
				}
				//计算每一个采购申请的物料未下达数量之和
				$rows[$key]['waitNumSum']=0;
				if(is_array($rows[$key]['childArr'])){
					foreach($rows[$key]['childArr'] as $equKey=>$equVal){
						$rows[$key]['waitNumSum']=$rows[$key]['waitNumSum']+$equVal['amountWait'];
					}

				}
					if($rows[$key]['waitNumSum']>0){
						$rows[$key]['waitNumSum']=1;
					}
				$watiNumSum[$key]=$rows[$key]['waitNumSum'];
				$ids[$key]=$val['id'];
				$purchTypes[$key]=$val['purchType'];
			}
//			$rows=$this->uniquePlanRow_d($rows);
			//根据物料的未下达数量之和与id进行排序
			array_multisort($watiNumSum,SORT_DESC,$purchTypes,SORT_DESC,$ids,SORT_DESC,$rows);
			return $rows;

		} else {
			return false;
		}
	}

		/**
	 * 采购申请-固定资产关闭列表
	 */
	function pageEndListUnion_d() {
		$rows = $this->pageBySqlId ( 'plan_list_union' );
		if ($rows) {
			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();
			//获取设备数据
			$equipment = new model_purchase_plan_equipment ();
			$assetEquDao=new model_asset_purchase_apply_applyItem();
//			$rows = $equipment->getPlanEqu_d ( $rows );
			foreach ( $rows as $key => $val ) {
//				$rows[$key]['stateC'] = $this->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				if($val['purchType']!='oa_asset_purchase_apply'){
					$rows[$key]['childArr']=$equipment->getCloseEquByPlanIdList_d ( $val['id'] );
				}else if($val['purchType']=='oa_asset_purchase_apply'){
					$rows[$key]['childArr']=$assetEquDao->getItemByApplyId($val['id'],'1','0');
					if(is_array($rows[$key]['childArr'])){
						foreach( $rows[$key]['childArr'] as $assetkey => $assetval ){
							$rows[$key]['childArr'][$assetkey]['productNumb'] = $assetval['productCode'];

						}
					}
				}
				$ids[$key]=$val['id'];
				$purchTypes[$key]=$val['purchType'];
			}
			//根据物料的未下达数量之和与id进行排序
//			array_multisort($watiNumSum,SORT_DESC,$purchTypes,SORT_DESC,$ids,SORT_DESC,$rows);
			return $rows;
		} else {
			return false;
		}
	}

	/**
	 * 采购计划获取数据方法-分页
	 */
	function pageEndPlan_d() {
		$rows = $this->pageBySqlId ( 'plan_list_page' );
		if ($rows) {
			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();
			//获取设备数据
			$equipment = new model_purchase_plan_equipment ();
			$rows = $equipment->getPlanAsEqu_d ( $rows );
			foreach ( $rows as $key => $val ) {
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC ( $rows [$key] ['state'] );	//状态中文
				$rows[$key]['purchTypeCName'] = $interfObj->typeKToC( $val['purchType'] );		//类型名称
				//计算每一个采购申请的物料未下达数量之和
				$rows[$key]['waitNumSum']=0;
				foreach($val['childArr'] as $equKey=>$equVal){
					$rows[$key]['waitNumSum']=$rows[$key]['waitNumSum']+$equVal['amountWait'];
				}
					if($rows[$key]['waitNumSum']>0){
						$rows[$key]['waitNumSum']=1;
					}
				if($rows[$key]['waitNumSum']==0){
//					$condiction=array('id' => $val['id']);
//					$obj = array ('state' => $this->stateToSta ( 'end' ), 'dateEnd' => date ( 'Y-m-d' ), 'dateFact' => date ( 'Y-m-d' ) ,'updateTime'=>date('Y-m-d H:i:s'));
//					parent::update ($condiction, $obj );
				}
				$watiNumSum[$key]=$rows[$key]['waitNumSum'];
				$ids[$key]=$val['id'];
				$purchTypes[$key]=$val['purchType'];
			}
			//根据物料的未下达数量之和与id进行排序
			array_multisort($watiNumSum,SORT_DESC,$purchTypes,SORT_DESC,$ids,SORT_DESC,$rows);
			return $rows;
		} else {
			return false;
		}
	}

	/**
	 * 采购申请更新状态
	 */
	function updateData_d($idArr=null) {
		try {
			$this->start_d ();
			if($idArr){
				$this->searchArr ['updateIds'] =$idArr;
			}
			$rows = $this->listBySqlId ( 'plan_list_page' );
			if ($rows) {
				//获取设备数据
				$equipment = new model_purchase_plan_equipment ();
				$rows = $equipment->getPlanAsEqu_d ( $rows );
				foreach ( $rows as $key => $val ) {
					$flag=false;
					//计算每一个采购申请的物料未下达数量之和
					$rows[$key]['waitNumSum']=0;
					foreach($val['childArr'] as $equKey=>$equVal){
						$rows[$key]['waitNumSum']=$rows[$key]['waitNumSum']+$equVal['amountWait'];
						if($rows[$key]['waitNumSum']>0){
							$flag=false;
							break;
						}else{
							$flag=true;
						}
						//判断采购合同是否还有单据没审批完成
						if((substr($equVal['purchType'],0,5)=='HTLX-')||$equVal['purchType']=='produce'){
							$sql = "select isApp from oa_purch_plan_equ where basicId='".$val['id']."'";
							$date = $this->_db->getArray ( $sql );
							foreach($date as $k=>$v){
								if($v['isApp']!=3){
									$flag=false;
									break;
								}
							}
						}
					}
					if($flag){
						$this->close_d($val['id']);
					}
				}
			}
			$this->commit_d ();
			return 1;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return 0;
		}
	}

	/**
	 * 获取采购计划详细信息
	 * @param	$id 采购申请ID
	 */
	function getPlan_d($id) {
		$plan = $this->get_d ( $id );
		$i = 0;
		//设置采购类型
		$plan = $this->purchTypeToCName ( $plan );
		//获取相应产品方法
		$equipmentDao = new model_purchase_plan_equipment ();
		$plan ['childArr'] = $equipmentDao->getEquByPlanId_d ( $id );
		return $plan;
	}


	/*****************************************关闭完成方法********************************************/

	/**
	 * @exclude 关闭采购计划
	 * @author ouyang
	 * @param	$id 采购申请ID
	 * @return
	 * @version 2010-8-10 下午11:19:32
	 */
	function close_d($id) {
		$equDao = new model_purchase_plan_equipment ();
		$equDao->closePlanEqu_d ( $id );
		$obj = array ('id' => $id, 'state' => $this->stateToSta ( 'close' ), 'dateFact' => date ( 'Y-m-d' ), 'dateEnd' => date ( 'Y-m-d' ),'updateTime'=>date('Y-m-d H:i:s'));
		return parent::updateById ( $obj );
	}
		/**
	 * @exclude 关闭采购申请
	 */
	function dealClose_d($object) {
		$equDao = new model_purchase_plan_equipment ();
		$equDao->closePlanEqu_d ( $object['id'] );
		$obj = array ('id' => $object['id'],'closeRemark'=>$object['closeRemark'], 'state' => $this->stateToSta ( 'close' ), 'dateFact' => date ( 'Y-m-d' ), 'dateEnd' => date ( 'Y-m-d' ),'updateTime'=>date('Y-m-d H:i:s'));
		$id=parent::updateById ( $obj );
		$equRow= $equDao->getEquByPlanId_d ( $object['id'] );
		//发送邮件
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['sendUserId'];
		$emailArr['TO_NAME']=$object['sendName'];
		if(is_array($equRow )){
			$j=0;
			$addmsg.="采购申请单编号：<font color='red'>".$object['planNumb']."</font><br>";
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>申请数量</b></td><td><b>下达任务数量</b></td><td><b>申请日期</b></td><td><b>希望交货日期</b></td><td><b>备注</b></td></tr>";
			foreach($equRow as $key => $equ ){
				$j++;
				$productName=$equ['productName'];
				$pattem=$equ ['pattem'];
				$unitName=$equ ['unitName'];
				$amountAll=$equ ['amountAll'];
				$amountIssued=$equ ['amountIssued'];
				$dateIssued=$equ['dateIssued'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark'];
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$amountIssued</td>
							<td>$dateIssued</td>
							<td>$dateHope</td>
							<td>$remark</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
				$addmsg.="关闭原因：<br/>     ";
				$addmsg.="<font color='blue'>".$object['closeRemark']."</font>";
		}
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanClose','','',$emailArr['TO_ID'],$addmsg,1);
		return $id;
	}

	/**
	 * @exclude 完成采购计划控制器方法
	 * @author ouyang
	 * @param 采购计划Id
	 * @return 0:异常，1:完成,2：不可完成
	 * @version 2010-8-10 下午11:19:32
	 */
	function end_d($id) {
		try {
			$this->start_d ();
			//更新设备完成状态
			$equDao = new model_purchase_plan_equipment ();
			//成功完成计划设备后才能完成计划
			if ($equDao->endPlanByBasicId_d ( $id )) {
				$condiction=array('id' => $id);
				$obj = array ('state' => $this->stateToSta ( 'end' ), 'dateEnd' => date ( 'Y-m-d' ), 'dateFact' => date ( 'Y-m-d' ) ,'updateTime'=>date('Y-m-d H:i:s'));
				parent::update ($condiction, $obj );
			} else {
				return 2;
			}
			$this->commit_d ();
			return 1;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return 0;
		}
	}

	/*****************************************关闭完成方法********************************************/

	/**
	 * @desription 添加保存采购计划
	 * @param tags
	 * @date 2010-12-20 上午11:09:11
	 */
	function add_d($obj) {
		try {
			$this->start_d ();
			$obj ['state'] = $this->statusDao->statusEtoK ( 'execute' );
			$obj ['objCode'] = $this->objass->codeC ( "purch_plan" );
			$obj ['planNumb'] = "purchplan-" . generatorSerial ();
			$planId = parent::add_d ( $obj, true );
			//执行采购计划与关联对象新增操作
			$sql = "insert into oa_purch_plan_basic_objass(planId,objAssId,objAssCode,objAssName,objAssType) values('" . $planId . "','" . $obj ['objAssId'] . "','" . $obj ['objAssCode'] . "','" . $obj ['objAssName'] . "','" . $obj ['objAssType'] . "')";
			$this->query ( $sql );
			$equDao = new model_purchase_plan_equipment ();
			//$contractEquDao = new model_contract_equipment_equipment ();
			$addAssObjs = array ();
			$interfObj = new model_common_interface_obj();
			foreach ( $obj ['equipment'] as $key => $equ ) {
				//计划数量大于0才进行操作
				if ($equ ['amountAll'] > 0) {
					$i = isset( $i )? (++$i) : 1 ;	//判断有多少条可用产品清单
					$equ ['basicId'] = $planId;
					$equ ['basicNumb'] = $obj ['planNumb'];
					$equ ['objCode'] = $this->objass->codeC ( 'purch_plan_equ' );
					$equ ['status'] = $this->statusDao->statusEtoK ( 'execution' );
					$equ ['amountIssued'] = 0; //确保已下达数量一开始为0
					if( $equ['dateHope'] == '' ){
						unset($equ['dateHope']);
					}
					$equId = $equDao->add_d ( $equ );
					//执行采购设备关联对象新增操作
					$sql = "insert into oa_purch_plan_equ_objass(planEquId,objAssId,objAssType) values('" . $equId . "','" . $equ ['contOnlyId'] . "','" . $obj ['equObjAssType'] . "')";
					$this->query ( $sql );

					//设置采购总关联表数据
					$addAssObjs [] = array ('planAssType' => $obj ['objAssType'], 'planAssCode' => $obj ['objAssCode'], 'planAssName' => $obj ['objAssName'], 'planAssId' => $obj ['objAssId'], 'planAssEquId' => $equ ['contOnlyId'], //关联合同设备id
										'planCode' => $obj ['planNumb'], 'planId' => $planId, 'planEquType' => $obj ['equObjAssType'], //计划设备关联类型
										'planEquId' => $equId );

					//更新合同设备的已下达数量
					//$contractEquDao->updateAmountIssued ( $equ ['contOnlyId'], $equ ['amountAll'] );

					//更新上级下达数量
					$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
					$supDao = new $supDaoName();	//实例化对象
					$supDao->updateAmountIssued ( $equ ['contOnlyId'], $equ ['amountAll'] );


				}
			}
			//不存在产品时，抛出异常
			if( $i == 0 ){
				throw new Exception( '采购计划无可用设备' );
			}
			//加入采购总关联表
			$this->objass->addModelObjs ( "purch", $addAssObjs );
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * @exclude 变更保存方法:直接修改数据，并产生变更通知单
	 * (产生变更通知单这里时间比较急，没有抽方法出来，写的比较烂，等以后再整理)
	 * @author ouyang
	 * @param
	 * @return
	 * @version 2010-8-17 下午06:25:28
	 */
	function change_d($plan) {
		try {
			$this->start_d ();
			$changeLogDao = new model_common_changeLog ( 'purchaseplan',false );
			//变更记录,拿到变更的临时主对象id
			$changeLogDao->addLog ( $plan );
			$planEquDao = new model_purchase_plan_equipment ();

			$oldPlan=parent::get_d($plan['oldId']);
			$plan['id']=$plan['oldId'];
			$plan['isTemp']=0;
			$plan['isChange']=1;

			$emailArr = $plan['email'];
			unset($plan['email']);
			$flag=parent::edit_d ( $plan );

			$interfObj = new model_common_interface_obj();
			foreach ( $plan ['equment'] as $key => $val ) {
				$planEquDao->searchArr = array( 'id'=>$val ['oldId'] );
				$oldEqu=$planEquDao->get_d($val ['oldId']);
				$val['id']= $val ['oldId'];
				$planEquDao->edit_d ( $val );

				//变更处理
				if($oldEqu['amountAll']!=$val['amountAll'] ){
					$changeRemark.=$oldEqu['productName']."变更数量：".$oldEqu['amountAll']."=>".$val['amountAll'].";\n";
					//更新上级下达数量
					$supDaoName = $interfObj->typeKToObj( $val['purchType'] );  //获取采购类型对象名称
					$supDao = new $supDaoName();	//实例化对象
					if($oldEqu ['applyEquId']){
						$supDao->updateAmountIssued ( $oldEqu ['applyEquId'], 0,$oldEqu['amountAll']-$val['amountAll'] );
					}
				}
			}

			$emailArr['issend'] = 'y';
			if(!empty($emailArr['TO_ID'])){
				$mailDefault=$this->planChange;
				$mailIdArr=explode(',',$emailArr['TO_ID']);
				if(!empty($mailDefault['sendUserId'])){
					foreach(explode(',',$mailDefault['sendUserId']) as $mKey=>$mVal){
						array_push($mailIdArr,$mVal);
					}
				}
				$emailArr['TO_ID']=implode(',',$mailIdArr);
			}else{
				$mailArr=$this->planChange;
				$emailArr['TO_ID']=$mailArr['sendUserId'];
			}
			//发送邮件 ,当操作为提交时才发送
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$emailDao = new model_common_mail();
				$emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanChange','变更',$plan['planNumb'],$emailArr['TO_ID'],$changeRemark,1);
			}
			$this->commit_d ();
			return $flag;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
//		function change_d($plan) {
//		try {
//			$this->start_d ();
//			$changeLogDao = new model_common_changeLog ( 'purchaseplan' );
//			//变更记录,拿到变更的临时主对象id
//			$tempObjId=$changeLogDao->addLog ( $plan );
//			$planEquDao = new model_purchase_plan_equipment ();
//			$this->updateField("id=".$plan['oldId'],'isChange','1');
//
//			$emailArr = $plan['email'];
//			unset($plan['email']);
//
//
//
//			//发送邮件 ,当操作为提交时才发送
//			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
//				$emailDao = new model_common_mail();
//				$emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'变更',$plan['planNumb'],$emailArr['TO_ID'],$addmsg=null,1);
//			}
//			$this->commit_d ();
//			return $tempObjId;
//		} catch ( Exception $e ) {
//			$this->rollBack ();
//			return null;
//		}
//	}

	//变更审批处理方法
	function dealChange_d($obj){
		$planEquDao = new model_purchase_plan_equipment ();

		$interfObj = new model_common_interface_obj();
		if($obj['ExaStatus']=="完成"){
			if($obj['purchType']!="assets"&&$obj['purchType']!="rdproject"&&$obj['purchType']!="produce"){
				$condition="basicId=".$obj['id'];
				$equRows=$planEquDao->findAll(array('basicId'=>$obj['id']));
				foreach ( $equRows as $key => $val ) {
					$oldEqu=$planEquDao->get_d($val ['originalId']);
					//变更处理
					if($oldEqu['amountAll']!=$val['amountAll'] ){
						//更新上级下达数量
						$supDaoName = $interfObj->typeKToObj( $val['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						if($oldEqu ['applyEquId']){
							$supDao->updateAmountIssued ( $oldEqu ['applyEquId'], 0,$oldEqu['amountAll']-$val['amountAll'] );
						}
					}
				}

			}

		}
		$emailArr = array();
		$emailArr['TO_ID']=$obj['sendUserId'];
		$emailArr['issend']="y";
		//发送邮件 ,当操作为提交时才发送
		if(!empty($emailArr['TO_ID'])){
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'审批变更',$obj['planNumb'],$emailArr['TO_ID'],$addmsg=null,1);
		}
	}
	/**
	 *删除采购申请
	 */
	function deletesInfo_d($id) {
		try {
			$this->start_d();

			$this->deletes ($id);
			$this->commit_d();
			return true;
		}catch ( Exception $e ) {
			$this->rollBack();
			return false;
		}
	}
	/**
	 * 资产采购下达时发送邮件
	 *@param $id 采购申请Id
	 *@param $applyNumb 采购申请编号
	 *@param $mailArr 默认邮件收件人
	 */
	 function sendEmail_d($id,$applyNumb,$mailArr){

		//发送邮件通知采购负责人
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$mailArr['sendUserId'];
		$emailArr['TO_NAME']=$mailArr['sendName'];
		//获取采购申请物料信息
		$equDao=new model_purchase_plan_equipment ();
		$equRows=$equDao->getEquByPlanId_d($id);
		//获取主表信息
		$row=$this->get_d($id);

		if($emailArr['TO_ID']!=""){
		if(is_array($equRows)){
			$j=0;
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>申请部门</b></td><td><b>申请人</b></td><td><b>申请数量</b></td><td><b>申请日期</b></td><td><b>希望完成时间</b></td><td><b>备注</b></td></tr>";
			foreach($equRows as $key => $equ ){
				$j++;
				$sendName=$row['sendName'];
				$inputProductName=$equ ['inputProductName'];
				$department=$row ['department'];
				$amountAll=$equ ['amountAll'];
				$dateIssued=$equ['dateIssued'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark']." ";
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$inputProductName</td>
							<td>$department</td>
							<td>$sendName</td>
							<td>$amountAll</td>
							<td>$dateIssued</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->pushPurch($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'asset_pushPurch',',采购申请编号为：<font color=red><b>'.$applyNumb.'</b></font>','',$emailArr['TO_ID'],$addmsg,1);

		}
	 }

	 /**
	 * 获取采购申请的备注信息
	 *
	 */
	 function getRemarkInfo_d($equRows){
		$infoRow=array("instruction"=>"","remark"=>"");
		if(is_array($equRows)){
			$planId=array();
			foreach($equRows as $key=>$val){
				foreach($val['childArr'] as $chlKey=>$chlVal){
					$planId[]=$chlVal['basicId'];
				}
			}
			if(is_array(array_unique($planId))){
				foreach(array_unique($planId) as $tKey=>$tVal){
					$planRow=$this->get_d($tVal);
					if($planRow['instruction']!=""){//拼接采购说明
						$infoRow['instruction'].="申请编号<".$planRow['planNumb'].">:".$planRow['instruction']."\n";
//						$infoRow['instruction'].=$planRow['applyReason']."\n";
					}
					if($planRow['remark']!=""){//拼接采购备注
						$infoRow['remark'].="申请编号<".$planRow['planNumb'].">:\n".$planRow['remark']."\n";
					}
				}
			}
		}
		return $infoRow;

	 }

	 /**过滤采购物料为空的申请
	 * @author evan
	 *
	 */
	 function uniquePlanRow_d($rows){
	 	$newRows=array();
	 	if(is_array($rows)){
			foreach($rows as $key=>$val){
				if(is_array($val['childArr'])){
					array_push($newRows,$val);
				}
			}
	 	}
		return $newRows;
	 }
	/*****************************************业务处理结束********************************************/

	/*********************** 导出采购时效列表 ********************/
	/**
	 * 获取失效数据
	 */
	function myPlanExportAging_d($object){
		$beginDate = $object['beginDate'];
		$endDate = $object['endDate'];

//		$purchTypes = implode($object['purchType'],',');

		$sql = "select
					c.objAssId,c.planAssType,c.planAssCode,c.planAssId,c.planCode,c.sourceNumb,c.purchType,

					c.planId,
					c.planSendDate,
					WEEKDAY(c.planSendDate) as planSendDay,
					c.planEndDate ,
					WEEKDAY(c.planEndDate) as planEndDay,
					date_format(c.planSendDate,'%m') as planMonth,
					c.planCreateBy,
					c.customerName,

					c.taskId,
					t.taskNumb as taskNo,
					t.sendTime as taskSendDate,
					WEEKDAY(t.sendTime) as taskSendDay,
					t.dateReceive as taskEndDate,
					WEEKDAY(t.dateReceive) as taskEndDay,
					t.createName as taskCreateBy,
					t.sendName as taskSendName,

					c.inquiryId,
					i.inquiryCode as inquiryNo,
					i.inquiryBgDate as inquiryBeginDate,
					WEEKDAY(i.inquiryBgDate) as inquiryBeginDay,
					i.updateDate as inquiryEndDate,
					WEEKDAY(i.updateDate) as inquiryEndDay,
					i.createName as inquiryCreateBy,
					i.ExaUser as inquiryExaBy,
					i.examines as inquiryExaStatus,

					c.applyId,
					b.hwapplyNumb as applyNo,
					date_format(b.createTime,'%Y-%m-%d') as applyBeginDate,
					WEEKDAY(b.createTime) as applyBeginDay,
					b.updateDate as applyEndDate,
					WEEKDAY(b.updateDate) as applyEndDay,
					b.createName as applyCreateBy,
					b.ExaUser as applyExaBy,
					b.examines as applyExaStatus

				from
					(
					select
						o.id as objAssId,o.planCode,
						o.planId,c.sendTime as planSendDate,c.dateEnd as planEndDate,c.createName as planCreateBy,c.sourceNumb,c.purchType,
						o.taskId,
						o.inquiryId,
						o.applyId,
						o.planAssType,o.planAssCode,o.planAssId,v.customerName
					from
						oa_purch_plan_basic c
						left join view_oa_order v on c.sourceId = v.orgId and c.purchType = v.tablename
						inner join
						oa_purch_objass o
							on c.id = o.planId
					where c.createId = '".$_SESSION['USER_ID']."' and c.createTime between  '$beginDate' and '$endDate'
					group by o.planId,o.taskId,o.inquiryId,o.applyId
					order by c.createTime
					) c
					left join
					oa_purch_task_basic t
						on c.taskId = t.id
					left join
					( select
						i.id,i.inquiryCode,i.inquiryBgDate,i.ExaDT,i.createName ,w.ExaUser,w.updateDate,w.examines
					from
						oa_purch_inquiry i
						left join
						(
						select
							c.task,c.name,c.code,c.Pid ,c.user as ExaUserId,u.USER_NAME as ExaUser,date_format(c.updateDT,'%Y-%m-%d') as updateDate,c.examines
						from
							(select
								c.task,c.name,c.code,c.Pid,p.user,c.examines,c.updateDT
							from
								flow_step_partent p
								inner join
								wf_task c
									on c.task = p.wf_task_id
								where c.name='采购询价单审批' and c.examines <> ''
								group by c.Pid,c.name
								order by c.task
							) c
							left join
							user u
								on c.user = u.USER_ID
						) w
						on i.id = w.Pid
					) i
						on c.inquiryId = i.id
					left join
					(
					select
						b.id,b.hwapplyNumb,b.createTime,b.createName,b.ExaDT,w.ExaUser,w.updateDate,w.examines
					from
						oa_purch_apply_basic b
						left join
						(
						select
							c.task,c.name,c.code,c.Pid ,c.user as ExaUserId,u.USER_NAME as ExaUser,date_format(c.updateDT,'%Y-%m-%d') as updateDate,c.examines
						from
							(select
								c.task,c.name,c.code,c.Pid,p.user,c.examines,c.updateDT
							from
								flow_step_partent p
								inner join
								wf_task c
									on c.task = p.wf_task_id
								where c.name='采购合同审批' and c.examines <> ''
								group by c.Pid,c.name
								order by c.task
							) c
							left join
							user u
								on c.user = u.USER_ID
						) w
							on b.id = w.Pid
					) b
							on c.applyId = b.id
					HAVING !(c.applyId is not null and b.hwapplyNumb is null)";

		$rs = $this->_db->getArray($sql);

		return $rs;
	}



	/**
	 * 确认采购申请物料
	 * add by chengl 2012-04-07
	 */
	function confirmProduct_d($object) {
		try {
			$this->start_d ();
			$equDao=new model_purchase_plan_equipment ();
			foreach ( $object ['equipment'] as $key => $equ ) {
				//申请数量大于0并且采购申请物料名称不为空才进行操作
				if ( !empty($equ ['productId'])) {
					$productName=$equ ['productName'];
					$productId=$equ ['productId'];
					$productNumb=$equ ['productNumb'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$equId=$equ['id'];
					$sql="update oa_purch_plan_equ set productId=$productId,productName='$productName'," .
							"productNumb='$productNumb',pattem='$pattem',unitName='$unitName' where id=$equId";
					$this->query($sql);
				}

			}
			$applyId=$object['id'];
			$equs=$equDao->getEquByPlanId_d($applyId);
			$productSureStatus=1;
			foreach($equs as $k=>$v){
				if(empty($v['productId'])){
					$productSureStatus=2;
				}
			}

			$sql="update oa_purch_plan_basic set productSureStatus=$productSureStatus where id=$applyId";//更新物料确认状态
			$this->query($sql);
			$row=$this->get_d($object['id']);
			$equRow= $equDao->getEquByPlanId_d ( $object['id'] );
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['sendUserId'];
			$emailArr['TO_NAME']=$row['sendName'];
			if(is_array($equRow )){
				$j=0;
				$equName=array();
				$addmsg.="采购申请单编号：<font color='red'>".$object['planNumb']."</font><br>";
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料类别</b></td><td><b>确认物料</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>申请数量</b></td><td><b>申请日期</b></td><td><b>希望交货日期</b></td><td><b>备注</b></td></tr>";
				foreach($equRow as $key => $val ){
					$j++;
					$equName[]=$val['inputProductName'];
					$productCategoryName=$val['productCategoryName'];
					$inputProductName=$val ['inputProductName'];
					$productName=$val ['productName'];
					$amountAll=$val ['amountAll'];
					$dateIssued=$val['dateIssued'];
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
								<td>$pattem</td>
								<td>$unitName</td>
								<td>$amountAll</td>
								<td>$dateIssued</td>
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
			$this->commit_d();
			return true;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 确认物料确认分配人
	 */
	function confirmProductUser_d($object){
		$equDao=new model_purchase_plan_equipment ();
		$id=$object['id'];
		$productSureUserId=$object['productSureUserId'];
		$productSureUserName=$object['productSureUserName'];
		$sql="update oa_purch_plan_basic set productSureUserId='$productSureUserId'," .
				"productSureUserName='$productSureUserName' where id=$id";
		$this->query($sql);
		$row=$this->get_d($object['id']);
		$equRow= $equDao->getEquByPlanId_d ( $object['id'] );
		//发送邮件
		$emailArr=array();
		$emailArr['issend'] = 'y';
		$emailArr['TO_ID']=$object['productSureUserId'];
		$emailArr['TO_NAME']=$object['productSureUserName'];
		if(is_array($equRow )){
			$j=0;
			$addmsg.="采购申请单编号：<font color='red'>".$object['planNumb']."</font><br>";
			$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料类别</b></td><td><b>待确认物料</b></td><td><b>物料名称</b></td><td><b>申请数量</b></td><td><b>申请日期</b></td><td><b>希望交货日期</b></td><td><b>申请人</b></td><td><b>备注</b></td></tr>";
			foreach($equRow as $key => $equ ){
				$j++;
				$productCategoryName=$equ['productCategoryName'];
				$inputProductName=$equ ['inputProductName'];
				$productName=$equ ['productName'];
				$amountAll=$equ ['amountAll'];
				$dateIssued=$equ['dateIssued'];
				$dateHope=$equ['dateHope'];
				$remark=$equ['remark'];
				$sendName=$row['sendName'];
				$addmsg .=<<<EOT
				<tr align="center" >
							<td>$j</td>
							<td>$productCategoryName</td>
							<td>$inputProductName</td>
							<td>$productName</td>
							<td>$amountAll</td>
							<td>$dateIssued</td>
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

	/**
	 * 打回申请单给申请人
	 */
	function backBasicToApplyUser_d($object){
		try {
			$this->start_d ();
			$applyId=$object['id'];
			$backReason=$object['backReason'];
			$sql="update oa_purch_plan_basic set ExaStatus='物料确认打回',backReason='$backReason' where id=$applyId";//设置为打回状态
			$this->query($sql);
			$row=$this->get_d($object['id']);
			$equDao=new model_purchase_plan_equipment ();
			//print_r($object['equ']);
			foreach($object['equ'] as $key=>$val){
				$equDao->edit_d($val);
			}

			$equRow= $equDao->getBackEqu_d ( $object['id'] );
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
	 * 生产采购审批通过，邮件通知申请人
	 */
	function sendApprovalMail($object){
			$row=$this->get_d($object['id']);
			$equDao=new model_purchase_plan_equipment ();
			$equRow=$equDao->getAllEqu_d($object['id']);
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['sendUserId'];
			$emailArr['TO_NAME']=$row['sendName'];
			if(is_array($equRow )){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>申请数量</b></td><td><b>审批数量</b></td><td><b>是否审批通过</b></td></tr>";
				foreach($equRow as $key => $equ ){
					$j++;
					$productNumb=$equ ['productNumb'];
					$productName=$equ ['productName'];
					$amountAll=$equ ['amountAll'];
					$amountAllOld=$equ['amountAllOld'];
					$pattem=$equ['pattem'];
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
			$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'produceApplyPurch','您的采购申请单已审批通过,申请单编号为：<font color=red><b>'.$object['planNumb'].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
			return true;
	}

	/**
	 * 采购申请审批通过后处理
	 */
	function confirmAudit($spid){
		try{
			$this->start_d();
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $spid );
			$objId = $folowInfo ['objId'];
			$obj = $this->_db->getArray("select * from oa_purch_plan_basic where id = {$objId};");
			$operator = ($obj)? $obj[0]['createName'] : '';

			// 研发采购审批通过后发送邮件通知
			$externalDao = new model_purchase_external_external();
			$mailArr['TO_NAME'] = $externalDao->_emailName;
			$mailArr['TO_ID'] = $externalDao->_emailID;
			$mailArr['typeName'] = "研发采购申请";
			$mailArr['operator'] = $operator;
			$externalDao->sendEmail_d($objId,$mailArr);

			$sql = "update oa_purch_plan_equ set isBack = 0 where basicId = ".$objId;
			$this->query ( $sql );
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
		}
	}

		/**
	 * @exclude 重新启动采购申请
	 * @param	$尖 采购申请ID
	 */
	function startPlan_d($id){

		$equDao = new model_purchase_plan_equipment ();
		$equDao->startPlanEqu_d ( $id);
		$condition = array(
					"id" => $id
				);
		$obj = array(
					"state" => $this->stateToSta('execute')
				);
		return parent::update ( $condition, $obj );
	}


	/**
	 * 审批DATA
	 *
	 */
	function showAppList() {
		$appType=$_POST['appType']?$_POST['appType']:$_GET['appType'];
		$keyType=$_POST['keyType']?$_POST['keyType']:$_GET['keyType'];
		$keyWords=$_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords'];
		$keyWords=trim($keyWords);
		$keyTypeI=explode(',',$keyType);
		$sqlStr='';
		$sqlKeyStr='';
		if($appType){
			$sqlStr.=" AND l.type='$appType'";
		}
		if($keyTypeI&&is_array($keyTypeI)&&$keyWords){
			foreach($keyTypeI as $key => $val){
			 if($val){
			 	$sqlKeyStr.="or l.$val like '%$keyWords%' ";
			 }
			}
			if($sqlKeyStr){
				$sqlKeyStr=trim($sqlKeyStr,'or');
			}
			$sqlStr.=" AND ($sqlKeyStr)";
		}
// 		print_r($sqlStr);
// 		die();
		$sql="SELECT l.id,l.pid,l.type,l.updateName,l.sendTime,l.productNumb,l.productName,l.amountAllOld,l.amountAll,l.qualityCode,l.productId,
					 l.planNumb,l.appOpinion,l.isApp
			FROM((
			SELECT b.id,a.id as pid,(case a.purchType
			when 'produce' then '1'
			when 'oa_borrow_borrow' then '7'
			when 'HTLX-XSHT' then '3'
			when 'HTLX-FWHT' then '4'
			when 'HTLX-ZLHT' then '5'
 				when 'HTLX-YFHT' then '6' end) as type,a.updateName,a.sendTime,b.productNumb,b.productName,b.amountAllOld,b.amountAll,
				   b.qualityCode,b.productId, a.planNumb,b.appOpinion,b.isApp
			FROM  oa_purch_plan_basic a LEFT JOIN oa_purch_plan_equ b  ON b.basicId=a.id
			WHERE b.amountAllOld>0 and b.amountAllOld-b.amountAll>0 AND  b.isApp=2 AND a.ExaStatus='完成' AND b.isClose='0' and (a.purchType = 'produce' or a.purchType like 'HTLX-%' or a.purchType='oa_borrow_borrow')
			)UNION(
			SELECT b.id,a.id as pid,'2' AS type,a.updateName,a.createTime as sendTime,b.sequence as productNumb,b.productName,b.amountAllOld,
				   b.stockNum as amountAll,b.qualityCode,b.productId,a.fillupCode as planNumb,b.appOpinion,b.isApp
			FROM  oa_stock_fillup a LEFT JOIN oa_stock_fillup_detail b  ON b.fillUpId=a.id
			WHERE b.amountAllOld>0 AND  b.isApp=2 AND a.ExaStatus='完成' AND b.isClose='0'
			)) l
			WHERE l.isApp=2 AND l.amountAllOld>0  $sqlStr
				";
		$rs = $this->_db->getArray($sql);
		$str = "";
		$i = 0;
		//$rs = $this->sconfig->md5Rows ( $rs );
		if ($rs) {
			$orderEquDao=new model_purchase_contract_equipment();
			$systeminfoDao=new model_stock_stockinfo_systeminfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$stockSysObj=$systeminfoDao->get_d("1");
			$saleStockId=$stockSysObj['salesStockId'];
			//$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ( $rs as $key => $val ) {
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				$fillupOnwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId'],'purchTypeArr'=>'stock'));
				$noAppamount=$val[amountAllOld]-$val[amountAll]>0?$val[amountAllOld]-$val[amountAll]:0;
				$i ++;
				$id=$val[id];
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				if($val[type]==2){
					$Prefix = 'stock';
				  $links = '?model=stock_fillup_fillup&action=init&perm=view&id='.$val[pid].'&skey=';
				  $objType = 'stock_fillup_fillup';
				  $type = '补库';
				}
				else{
					 $links = '?model=purchase_plan_basic&action=toAuditEdit&purchType=produce&actType=audit&id='.$val[pid].'&skey=';
					 $objType = 'purchase_plan_basic';
					switch ($val[type]){
						case '1': $Prefix = 'pro';
								  $type = '生产';
								  break;
						case '3': $Prefix = 'HTLX-XSHT';
								  $type = '销售采购';
								  break;
						case '4': $Prefix = 'HTLX-FWHT';
								  $type = '服务采购';
								  break;
						case '5': $Prefix = 'HTLX-ZLHT';
								  $type = '租赁采购';
								  break;
						case '6': $Prefix = 'HTLX-YFHT';
								  $type = '研发采购';
								  break;
                        case '7': $Prefix = 'oa_borrow_borrow';
                            $type = '补库';
                            break;
					}
				}


//				$Prefix=$val[type]=='1'?'pro':'stock';
//				$links=$val[type]=='1'?'?model=purchase_plan_basic&action=toAuditEdit&purchType=produce&actType=audit&id='.$val[pid].'&skey=':'?model=stock_fillup_fillup&action=init&perm=view&id='.$val[pid].'&skey=';
//				$objType=$val[type]=='1'?'purchase_plan_basic':'stock_fillup_fillup';
//				$type=$val[type]=='1'?'生产':'补库';
				$sendTime=date('Y-m-d',strtotime($val['sendTime']));
				$str .= <<<EOT
					<tr class="$iClass">
						<td >$i</td>
						<td>$type</td>
						<td><a  title="查看详细" href="javascript:showLink('$links','$objType',$val[pid])">$val[planNumb]</a></td>
						<td>$val[updateName]</td>
						<td>$sendTime</td>
						<td>$val[productNumb]</td>
						<td>$val[productName]</td>
						<td>$val[amountAllOld]</td>
						<td>$noAppamount</td>
				        <td>
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
						<td>$fillupOnwayAmount</td>
						<td class="isTrue"><input type="checkbox" class="txtshort"  id="$Prefix$val[id]" value="1"  name="basic[$Prefix][$id][isTrue]" style="width:20px;" /></td>
						<td >
							<input type="text" class="txtshort" id="amountAll$i" onkeyup="this.value=this.value.replace(/\D/g,'')" name="basic[$Prefix][$id][amountAll]" value="$noAppamount" style=" width:40px;"/>
							<input type="hidden"  id="idd$i" name="basic[$Prefix][$id][id]" value="$val[id]"/>
							<input type="hidden"  id="ids$i" name="basic[$Prefix][$id][amountAllOld]" value="$val[amountAllOld]"/>
						</td>
						<td>
							<textarea  type="text" class="txtshort" id="appOpinion$i" row=3 name="basic[$Prefix][$id][appOpinion]" style=" width:160px;"/></textarea>
						    <input type='hidden' id="appOpinionOld$i" name="basic[$Prefix][$id][appOpinionOld]" value="$val[appOpinion]"/>
						</td>
						<td class="isClose">
							<input type="checkbox" class="txtshort"  id="isClose$i" style="width:40px;" value='1' name="basic[$Prefix][$id][isClose]"/>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}


		/**
	 * 审批DATA
	 *
	 */
	function showAppCloseList() {
		$appType=$_POST['appType']?$_POST['appType']:$_GET['appType'];
		$keyType=$_POST['keyType']?$_POST['keyType']:$_GET['keyType'];
		$keyWords=$_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords'];
		$keyWords=trim($keyWords);
		$keyTypeI=explode(',',$keyType);
		$sqlStr='';
		$sqlKeyStr='';
		if($appType){
			$sqlStr.=" AND l.type='$appType'";
		}
		if($keyTypeI&&is_array($keyTypeI)&&$keyWords){
			foreach($keyTypeI as $key => $val){
			 if($val){
			 	$sqlKeyStr.="or l.$val like '%$keyWords%' ";
			 }
			}
			if($sqlKeyStr){
				$sqlKeyStr=trim($sqlKeyStr,'or');
			}
			$sqlStr.=" AND ($sqlKeyStr)";
		}

		$sql="SELECT l.id,l.pid,l.type,l.updateName,l.sendTime,l.productNumb,l.productName,l.amountAllOld,l.amountAll,l.qualityCode,l.productId,
					 l.planNumb,l.appOpinion,l.isApp
			FROM((
			SELECT b.id,a.id as pid,(case a.purchType
			when 'produce' then '1'
			when 'oa_borrow_borrow' then '7'
			when 'HTLX-XSHT' then '3'
			when 'HTLX-FWHT' then '4'
			when 'HTLX-ZLHT' then '5'
 				when 'HTLX-YFHT' then '6' end) as type,a.updateName,a.sendTime,b.productNumb,b.productName,b.amountAllOld,b.amountAll,
				   b.qualityCode,b.productId, a.planNumb,b.appOpinion,b.isApp
			FROM  oa_purch_plan_basic a LEFT JOIN oa_purch_plan_equ b  ON b.basicId=a.id
			WHERE b.amountAllOld>0  AND a.ExaStatus='完成' AND b.isClose='1' and (a.purchType = 'produce' or a.purchType like 'HTLX-%' or a.purchType='oa_borrow_borrow')
			)UNION(
			SELECT b.id,a.id as pid,'2' AS type,a.updateName,a.createTime as sendTime,b.sequence as productNumb,b.productName,b.amountAllOld,
				   b.stockNum as amountAll,b.qualityCode,b.productId,a.fillupCode as planNumb,b.appOpinion,b.isApp
			FROM  oa_stock_fillup a LEFT JOIN oa_stock_fillup_detail b  ON b.fillUpId=a.id
			WHERE b.amountAllOld>0  AND a.ExaStatus='完成' AND b.isClose='1'
			)) l
			WHERE  l.amountAllOld>0  $sqlStr
				";
		$rs = $this->_db->getArray($sql);
		$str = "";
		$i = 0;
		//$rs = $this->sconfig->md5Rows ( $rs );
		if ($rs) {
			$orderEquDao=new model_purchase_contract_equipment();
			$systeminfoDao=new model_stock_stockinfo_systeminfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$stockSysObj=$systeminfoDao->get_d("1");
			$saleStockId=$stockSysObj['salesStockId'];
			//$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ( $rs as $key => $val ) {
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				$fillupOnwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId'],'purchTypeArr'=>'stock'));
				$noAppamount=$val[amountAllOld]-$val[amountAll]>0?$val[amountAllOld]-$val[amountAll]:0;
				$i ++;
				$id=$val[id];
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";

				if($val[type]==2){
					$Prefix = 'stock';
				  $links = '?model=stock_fillup_fillup&action=init&perm=view&id='.$val[pid].'&skey=';
				  $objType = 'stock_fillup_fillup';
				  $type = '补库';
				}
				else{
					 $links = '?model=purchase_plan_basic&action=toAuditEdit&purchType=produce&actType=audit&id='.$val[pid].'&skey=';
					 $objType = 'purchase_plan_basic';
					switch ($val[type]){
						case '1': $Prefix = 'pro';
								  $type = '生产';
								  break;
						case '3': $Prefix = 'HTLX-XSHT';
								  $type = '销售采购';
								  break;
						case '4': $Prefix = 'HTLX-FWHT';
								  $type = '服务采购';
								  break;
						case '5': $Prefix = 'HTLX-ZLHT';
								  $type = '租赁采购';
								  break;
						case '6': $Prefix = 'HTLX-YFHT';
								  $type = '研发采购';
								  break;
                        case '7': $Prefix = 'oa_borrow_borrow';
                            $type = '补库';
                            break;
					}
				}
				$sendTime=date('Y-m-d',strtotime($val['sendTime']));
				$str .= <<<EOT
					<tr class="$iClass">
						<td >$i</td>
						<td>$type</td>
						<td><a  title="查看详细" href="javascript:showLink('$links','$objType',$val[pid])">$val[planNumb]</a></td>
						<td>$val[updateName]</td>
						<td>$sendTime</td>
						<td>$val[productNumb]</td>
						<td>$val[productName]</td>
						<td>$val[amountAllOld]</td>
						<td>$noAppamount</td>
				        <td>
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
						<td>$fillupOnwayAmount</td>
						<td  class="isClose">
							<input type="hidden"  id="idd$i" name="basic[$Prefix][$id][id]" value="$val[id]"/>
							<input type="hidden"  id="ids$i" name="basic[$Prefix][$id][amountAllOld]" value="$val[amountAllOld]"/>
							<input type="checkbox" class="txtshort"  id="isClose$i" style="width:40px;" value='1' name="basic[$Prefix][$id][isClose]"/>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;
	}
	/**
	 * 补库/生产通过审批DATA
	 */
	function ApprovedList(){
		$appType=$_POST['appType']?$_POST['appType']:$_GET['appType'];
		$keyType=$_POST['keyType']?$_POST['keyType']:$_GET['keyType'];
		$keyWords=$_POST['keyWords']?$_POST['keyWords']:$_GET['keyWords'];
		$keyWords=trim($keyWords);
		$keyTypeI=explode(',',$keyType);
		$sqlStr='';
		$sqlKeyStr='';
		if($appType){
			$this->searchArr['type'] = $appType;
		}
		if($keyTypeI&&is_array($keyTypeI)&&$keyWords){
			$arrNum = count($keyTypeI);
			if($arrNum==1){
				foreach($keyTypeI as $key => $val){
					if($val){
						$this->searchArr[$val.'S'] = $keyWords;
					}
				}
			}else{
				$this->searchArr['allS'] = $keyWords;
			}
		}
		$this->searchArr['isAppS'] = 3;
		$rs = $this->pageBySqlId('select_approvedList');
		$str = "";
		$i = 0;
		if ($rs) {
			$orderEquDao=new model_purchase_contract_equipment();
			$systeminfoDao=new model_stock_stockinfo_systeminfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$stockSysObj=$systeminfoDao->get_d("1");
			$saleStockId=$stockSysObj['salesStockId'];
			//$datadictArr = $this->getDatadicts ( "CGZJSX" );
			foreach ( $rs as $key => $val ) {
				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$val['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}
				$fillupOnwayAmount=$orderEquDao->getEqusOnway(array('productId'=>$val['productId'],'purchTypeArr'=>'stock'));
				$appamount=$val[amountAll];
				$i ++;
				$id=$val[id];
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				if($val[type]==2){
					$Prefix = 'stock';
				  $links = '?model=stock_fillup_fillup&action=init&perm=view&id='.$val[pid].'&skey=';
				  $objType = 'stock_fillup_fillup';
				  $type = '补库';
				}
				else{
					 $links = '?model=purchase_plan_basic&action=toAuditEdit&purchType=produce&actType=audit&id='.$val[pid].'&skey=';
					 $objType = 'purchase_plan_basic';
					switch ($val[type]){
						case '1': $Prefix = 'pro';
								  $type = '生产';
								  break;
						case '3': $Prefix = 'HTLX-XSHT';
								  $type = '销售采购';
								  break;
						case '4': $Prefix = 'HTLX-FWHT';
								  $type = '服务采购';
								  break;
						case '5': $Prefix = 'HTLX-ZLHT';
								  $type = '租赁采购';
								  break;
						case '6': $Prefix = 'HTLX-YFHT';
								  $type = '研发采购';
								  break;
                        case '7': $Prefix = 'oa_borrow_borrow';
                            $type = '补库';
                            break;
					}
				}
				$sendTime=date('Y-m-d',strtotime($val['sendTime']));
				$str .= <<<EOT
					<tr class="$iClass">
						<td >$i</td>
						<td>$type</td>
						<td><a  title="查看详细" href="javascript:showLink('$links','$objType',$val[pid])">$val[planNumb]</a></td>
						<td>$val[updateName]</td>
						<td>$sendTime</td>
						<td>$val[productNumb]</td>
						<td>$val[productName]</td>
						<td>$val[amountAllOld]</td>
						<td>$appamount</td>
				        <td>
				            <a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
						<td>$fillupOnwayAmount</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr><td colspan='8'>暂无物料清单信息</td></tr>";
		}
		return $str;

	}
	/**
	 * 审批数据更新；
	 *
	 */
	function inProAppList($object) {
		try {
			$this->start_d ();
			if($object&&is_array($object)){
				foreach($object as $k => $v){
					//采购更新
					if(($k=='pro'&&is_array($v))||($k=='HTLX-XSHT'&&is_array($v))||($k=='HTLX-FWHT'&&is_array($v))||($k=='HTLX-ZLHT'&&is_array($v))||($k=='HTLX-YFHT'&&is_array($v))||($k=='oa_borrow_borrow'&&is_array($v))){
						$equipmentDao = new model_purchase_plan_equipment();
						foreach($v as $key=>$val){
							if($val['isTrue']=='1'||$val['isClose']=='1'){
								$typStr=$val['isClose']?'关闭':'通过';
								$appOpinion=$val['appOpinionOld']?$val['appOpinionOld'].'('.$typStr.'-审批数量：'.$val['amountAll'].')'.$val['appOpinion']:($val['appOpinion']?'('.$typStr.'-审批数量：'.$val['amountAll'].')'.$val['appOpinion']:'('.$typStr.'-审批数量：'.$val['amountAll'].')');
								$isClose=$val['isClose']?$val['isClose']:0;
								$amountAll=$val[amountAll];
								$equipmentObj = $equipmentDao->get_d($val['id']);
								if ($amountAll <= $equipmentObj['amountAllOld'] - $equipmentObj['amountAll']) {
									if($isClose){
										$sql="UPDATE oa_purch_plan_equ SET isApp=IF(amountAll>=amountAllOld,3,2),isClose='$isClose',appOpinion='$appOpinion' WHERE id=".$val['id'];
									}else{
										$sql="UPDATE oa_purch_plan_equ SET amountAll=(IFNULL(amountAll,0)+$amountAll),isApp=IF(amountAll>=amountAllOld,3,2),isClose='$isClose',appOpinion='$appOpinion' WHERE id=".$val['id'];
									}
								}
								//数量不符合时
								if( !$sql ){
									throw new Exception( '审批数量大于可审批数量！' );
								} else {
									$flag=$this->query($sql);
								}
								if($flag!=1){
									return $flag;
								}
							}
						}
					}
					//补库更新
					if($k=='stock'&&is_array($v)){
						foreach($v as $key=>$val){
							if($val['isTrue']=='1'||$val['isClose']=='1'){
								$fillupproDao = new model_stock_fillup_filluppro();
								$typStr=$val['isClose']?'关闭':'通过';
								$appOpinion=$val['appOpinionOld']?$val['appOpinionOld'].'('.$typStr.'-审批数量：'.$val['amountAll'].')'.$val['appOpinion']:($val['appOpinion']?'('.$typStr.'-审批数量：'.$val['amountAll'].')'.$val['appOpinion']:'('.$typStr.'-审批数量：'.$val['amountAll'].')');
								$isClose=$val['isClose']?$val['isClose']:0;
								$amountAll=$val[amountAll];
								$fillupproObj = $fillupproDao->get_d($val['id']);
								if ($amountAll <= $fillupproObj['amountAllOld'] - $fillupproObj['stockNum']) {
									if($isClose){
										$sql="UPDATE oa_stock_fillup_detail SET isApp=IF(stockNum>=amountAllOld,3,2),isClose='$isClose',appOpinion='$appOpinion' WHERE id=".$val['id'];
									}else{
										$sql="UPDATE oa_stock_fillup_detail SET stockNum=(IFNULL(stockNum,0)+$amountAll),isApp=IF(stockNum>=amountAllOld,3,2),isClose='$isClose',appOpinion='$appOpinion' WHERE id=".$val['id'];
									}
								}
								//数量不符合时
								if( !$sql ){
									throw new Exception( '审批数量大于可审批数量！' );
								} else {
									$flag=$this->query($sql);
								}
								if($flag!=1){
									return $flag;
								}
							}
						}
					}
				}
				$this->commit_d();
				return $flag;
			}
			else {
				$this->commit_d();
				return 2;
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

		/**
	 * 审批数据更新；
	 *
	 */
	function inProAppClose($object) {
		if($object&&is_array($object)){
//			$flag=0;
			foreach($object as $k=>$v){
				if(($k=='pro'&&is_array($v))||($k=='HTLX-XSHT'&&is_array($v))||($k=='HTLX-FWHT'&&is_array($v))||($k=='HTLX-ZLHT'&&is_array($v))||($k=='HTLX-YFHT'&&is_array($v))||($k=='oa_borrow_borrow'&&is_array($v))){
					foreach($v as $key=>$val){
						if($val['isClose']=='1'){
							$sql="UPDATE oa_purch_plan_equ SET isApp=2,isClose='0' WHERE id=".$val['id'];
							$flag=$this->query($sql);
							if($flag!=1){
							return $flag;
							}
						}
					}
				}
				if($k=='stock'&&is_array($v)){
					foreach($v as $key=>$val){
						if($val['isClose']=='1'){
							$sql="UPDATE oa_stock_fillup_detail SET isApp=2,isClose='0' WHERE id=".$val['id'];
							$flag=$this->query($sql);
							if($flag!=1){
							return $flag;
							}
						}
					}
				}
			}
			return $flag;;
		}
		else {return 2;}
	}
	/*
	 * 补库/生产审批邮件通知
	 *
	 * */
	function sendNotification($obj){
		$strArr0=array();		//存放关闭审批数据
		$strArr1=array();		//存放通过审批数据
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr=$mailUser['oa_production_replenishment'];
		$mailId = $mailArr['TO_ID'];

		foreach($obj as $key=>$val){
			foreach($val as $k=>$v){
				if($v['isClose']==1){
					$sql = "SELECT	l.id,l.pid,l.type,l.updateName,l.updateId,l.sendTime,l.productNumb,
				l.productName,l.amountAllOld,l.amountAll,l.qualityCode,l.productId,l.planNumb,l.appOpinion,
				l.isApp FROM ((	SELECT	b.id,a.id AS pid,(
					CASE a.purchType
					WHEN 'produce' THEN
						'1'
					WHEN 'HTLX-XSHT' THEN
						'3'
					WHEN 'HTLX-FWHT' THEN
						'4'
					WHEN 'HTLX-ZLHT' THEN
						'5'
					WHEN 'HTLX-YFHT' THEN
						'6'
			        when 'oa_borrow_borrow' then
			        '7'
					END
				) AS type,a.updateName,a.updateId,a.sendTime,b.productNumb,
				b.productName,b.amountAllOld,b.amountAll,b.qualityCode,b.productId,a.planNumb,b.appOpinion,
				b.isApp	FROM oa_purch_plan_basic a LEFT JOIN oa_purch_plan_equ b ON b.basicId = a.id WHERE
				b.amountAllOld > 0	AND b.isApp = 2	AND a.ExaStatus = '完成'	AND b.isClose = '0'	AND (a.purchType = 'produce' or a.purchType like 'HTLX-%' or a.purchType = 'oa_borrow_borrow' )
				) UNION	( SELECT b.id,a.id AS pid,'2' AS type,a.updateName,a.updateId,a.createTime AS sendTime,b.sequence AS productNumb,
				b.productName,b.amountAllOld,b.stockNum AS amountAll,b.qualityCode,b.productId,a.fillupCode AS planNumb,b.appOpinion,
				b.isApp	FROM oa_stock_fillup a LEFT JOIN oa_stock_fillup_detail b ON b.fillUpId = a.id WHERE b.amountAllOld > 0	AND b.isApp = 2
				AND a.ExaStatus = '完成' AND b.isClose = '0')) l WHERE l.isApp = 2 AND l.amountAllOld > 0 AND l.id = '".$v['id']."'";
					$str0=$this->_db->getArray($sql);
					$strArr0 = array_merge($strArr0,$str0);
				}
				if($v['isTrue']==1&&empty($v['isClose'])){
					$sql = "SELECT	l.id,l.pid,l.type,l.updateName,l.updateId,l.sendTime,l.productNumb,
				l.productName,l.amountAllOld,l.amountAll,l.qualityCode,l.productId,l.planNumb,l.appOpinion,
				l.isApp FROM ((	SELECT	b.id,a.id AS pid,(
					CASE a.purchType
					WHEN 'produce' THEN
						'1'
					WHEN 'HTLX-XSHT' THEN
						'3'
					WHEN 'HTLX-FWHT' THEN
						'4'
					WHEN 'HTLX-ZLHT' THEN
						'5'
					WHEN 'HTLX-YFHT' THEN
						'6'
			        when 'oa_borrow_borrow' then
			        '7'
					END
				) AS type,a.updateName,a.updateId,a.sendTime,b.productNumb,
				b.productName,b.amountAllOld,b.amountAll,b.qualityCode,b.productId,a.planNumb,b.appOpinion,
				b.isApp	FROM oa_purch_plan_basic a LEFT JOIN oa_purch_plan_equ b ON b.basicId = a.id WHERE
				b.amountAllOld > 0	AND b.isApp = 2	AND a.ExaStatus = '完成'	AND b.isClose = '0'	AND (a.purchType = 'produce' or a.purchType like 'HTLX-%' or a.purchType = 'oa_borrow_borrow')
				) UNION	( SELECT b.id,a.id AS pid,'2' AS type,a.updateName,a.updateId,a.createTime AS sendTime,b.sequence AS productNumb,
				b.productName,b.amountAllOld,b.stockNum AS amountAll,b.qualityCode,b.productId,a.fillupCode AS planNumb,b.appOpinion,
				b.isApp	FROM oa_stock_fillup a LEFT JOIN oa_stock_fillup_detail b ON b.fillUpId = a.id WHERE b.amountAllOld > 0	AND b.isApp = 2
				AND a.ExaStatus = '完成' AND b.isClose = '0')) l WHERE l.isApp = 2 AND l.amountAllOld > 0 AND l.id = '".$v['id']."'";
				$str1=$this->_db->getArray($sql);
				$str1['0']['num']=$v['amountAll'];
				$strArr1 = array_merge($strArr1,$str1);
				}
			}
		}
		/*[type] => 2
            [updateId] => admin
            [sendTime] => 2013-11-16 10:27:46
            [productNumb] => 110200247
            [productName] => Thinkpad E430 3254-AB2 笔记本电脑
            [planNumb] => FILL000255
            [num] => 5
            amountAll
            amountAllOld*/
		if(!empty($strArr0)){
			foreach($strArr0 as $k=>$v){
				$receiveUsers.=$v['updateId'].',';
			}
			$receiveUsers = explode(",",$receiveUsers);
			array_pop($receiveUsers);
			$receiveUsers = array_flip($receiveUsers);
			$sendMail = new model_common_mail();
			foreach($receiveUsers as $key=>$val){
				$sendMessage = "您好，补库/生产审批关闭通知，以下是详细信息:<br><table border='1' cellspacing='0' width='100%' bordercolorlight='#333333' bordercolordark='#efefef' align='center'><tbody><tr bgcolor='#D5D5D5' align='center'><td>类型</td><td>单据编号</td><td>申请人</td><td>申请时间</td><td>物料编码</td><td>物料名称</td><td>申请数量</td><td>通过数量</td></tr>
								";
				foreach($strArr0 as $k=>$v){
					if($key==$v['updateId']){
						switch ($v['type']){
							case '1' : $v['type']="生产采购";break;
							case '2' : $v['type']="补库";break;
							case '3' : $v['type']="销售采购";break;
							case '4' : $v['type']="服务采购";break;
							case '5' : $v['type']="租赁采购";break;
							case '6' : $v['type']="研发采购";break;
						}
						$sendMessage.="<tr align='center'><td>".$v['type']."</td><td>".$v['planNumb']."</td><td>".$v['updateName']."</td><td>".$v['sendTime']."</td><td>".$v['productNumb']
						."</td><td>".$v['productName']."</td><td>".$v['amountAllOld']."</td><td>".$v['amountAll']."</td></tr>";

					}
				}
				$sendMessage.="</tbody></table>";
				$keyArr =$key.','.$mailId.',';
				$emailInfo = $sendMail->mailClear("补库/生产审批关闭通知",$keyArr,$sendMessage);
			}
		}
		if(!empty($strArr1)){
			$receiveUsers="";
			foreach($strArr1 as $k=>$v){
					$receiveUsers.=$v['updateId'].',';
			}
			$receiveUsers = explode(",",$receiveUsers);
			array_pop($receiveUsers);
			$receiveUsers = array_flip($receiveUsers);
			$sendMail = new model_common_mail();
			foreach($receiveUsers as $key=>$val){
				$sendMessage = "您好，补库/生产审批通知，以下是详细信息:<br><table border='1' cellspacing='0' width='100%' bordercolorlight='#333333' bordercolordark='#efefef' align='center'><tbody><tr bgcolor='#D5D5D5' align='center'><td>类型</td><td>单据编号</td><td>申请人</td><td>申请时间</td><td>物料编码</td><td>物料名称</td><td>申请数量</td><td>此次通过数量</td><td>通过总数量</td></tr>
								";
				foreach($strArr1 as $k=>$v){
					if($key==$v['updateId']){
						switch ($v['type']){
							case '1' : $v['type']="生产采购";break;
							case '2' : $v['type']="补库";break;
							case '3' : $v['type']="销售采购";break;
							case '4' : $v['type']="服务采购";break;
							case '5' : $v['type']="租赁采购";break;
							case '6' : $v['type']="研发采购";break;
						}
						$amountAll = $v['num']+$v['amountAll'];
						$sendMessage.="<tr align='center'><td>".$v['type']."</td><td>".$v['planNumb']."</td><td>".$v['updateName']."</td><td>".$v['sendTime']."</td><td>".$v['productNumb']
						."</td><td>".$v['productName']."</td><td>".$v['amountAllOld']."</td><td>".$v['num']."</td><td>".$amountAll."</td></tr>";
					}
				}
				$sendMessage.="</tbody></table>";
				$keyArr =$key.','.$mailId.',';
				$emailInfo = $sendMail->mailClear("补库/生产审批通知",$keyArr,$sendMessage);
			}
		}
	}

	/**
	 * 查询采购申请
	 * @param unknown $param
	 * @return unknown
	 */
	function search_d($param){
		$equipmentDao = new model_purchase_plan_equipment();
		$idArr = $equipmentDao->findAll($param,null,'basicId');
		$idStr = "";
		foreach ($idArr as $key =>$val){
			$idStr .= $val['basicId'].",";
		}
		$idStr = substr($idStr,0,strlen($idStr)-1);
		$this->searchArr = array('ids'=>$idStr);
		$this->$sort = 'sendTime';
		$arr = $this->page_d();
		return $arr;
	}

	/**
	 * 审批回调方法
	 *
	 */
	function workflowCallBack($spid){
        $this->confirmAudit($spid);//审批后的业务处理isBack
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		$planRow=$this->get_d($objId);
		if($planRow['purchType']=="assets"){    //判断是否为”资产采购“
			if($folowInfo['examines']=="ok"){  //审批通过则发送邮件
				//发送邮件通知资产采购负责人（行政部）
				$mailArr=$this->planApproval;  //获取默认收件人数组
				$this->sendEmail_d($objId,$planRow['planNumb'],$mailArr);
			}
		}
	}
	/**
	 * 生产采购审批回调
	 */
	 function workflowCallBack_deal($spid){
        $otherdatas = new model_common_otherdatas ();
		$equipmentDao = new model_purchase_plan_equipment ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		if($folowInfo['examines']=="ok"){
			$equRows=$equipmentDao->getEquByPlanId_d ( $folowInfo['objId'] );
			foreach($equRows as $key=>$val){
				$equRows[$key]['id']=$val['id'];
//                    $equRows[$key]['amountAll'] = $equRows[$key]['amountAllold'];
                $equRows[$key]['isApp']= '3';
                $equRows[$key]['isAsset']= '';//这个字段审批后的值变为'NU'，影响视图的isnull() 判断
				$equipmentDao->edit_d($equRows[$key]);
			}

			$obj = $this->_db->getArray("select * from oa_purch_plan_basic where id = {$folowInfo['objId']};");
			$operator = ($obj)? $obj[0]['createName'] : '';

			// 生产采购审批通过后发送邮件通知
			$externalDao = new model_purchase_external_external();
			$mailArr['TO_NAME'] = $externalDao->_emailName;
			$mailArr['TO_ID'] = $externalDao->_emailID;
			$mailArr['typeName'] = "生产采购申请";
			$mailArr['operator'] = $operator;
			$externalDao->sendEmail_d($folowInfo['objId'],$mailArr);
		}
	 }
	 function workflowCallBack_change($spid){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if (! empty ( $objId )) {
			$contract = $this->get_d ( $objId );
			$this->dealChange_d($contract);
			$changeLogDao = new model_common_changeLog ( 'purchaseplan' );
			$changeLogDao->confirmChange_d ( $contract );
		}
	 }
}
?>
