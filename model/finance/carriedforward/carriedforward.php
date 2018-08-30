<?php
/**
 * @author Show
 * @Date 2011年8月8日 星期一 11:00:07
 * @version 1.0
 * @description:合同结转表 Model层
 */
class model_finance_carriedforward_carriedforward extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_carriedforward";
		$this->sql_map = "finance/carriedforward/carriedforwardSql.php";
		parent::__construct ();
    }

    /**
     * 结转方式
     */
	private $carryType = array(
		'开票结转','出库结转'
	);

    /*****************************页面显示层************************/


	/**
	 * 显示发票钩稽页面
	 */
	function showInvoiceDetail($rows,$dateArr){
		$sysYear = $dateArr['thisYear'];
		$sysMonth = $dateArr['thisMonth'];
		$str = null;
		if(!empty($rows)){
			$i = 0;   //计数器
			$markContractId = $markInvoiceId = $markOutStockId  = 'none';   //字符串记录 合同号   开票号   出库单号
			$hookStr = $invoiceTypeName = $disabledCheck = $disabledInput =  null;  //钩稽部分字符串 发票类型 不可用
			$outAllMoney = $hookMoney = $carryRate = 0;   //金额字段   小计金额   已钩稽金额  钩稽比例
			$datadictDao = new model_system_datadict_datadict();
			foreach($rows as $key => $val){
				$i ++ ;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$mainId = $val['id'] .'-'.$val['detailId'];  //主列标注ID
				$carryRate = empty($val['carryRate']) ? 0 : $val['carryRate'];  //钩稽比例

				if($markContractId != $val['objId']){//不同合同号不同发票行处理
					if($markContractId != 'none'){
						$hookMoney = round($hookMoney,2);
						$str .=<<<EOT
							<tr class="tr_count">
								<td colspan="7"></td>
								<td><b title="同一合同号的出库金额不会重复统计">小计</b><input type="hidden" id="count$markContractId" value="$outAllMoney"/></td>
								<td class="formatMoney">$outAllMoney</td>
								<td colspan="2"></td>
								<td><b>已钩稽</b><input type="hidden" id="hooked$markContractId" value="$hookMoney"/></td>
								<td class="formatMoney" id="hookedView$markContractId">$hookMoney</td>
								<td colspan="2"></td>
							</tr>
EOT;
						$outAllMoney = $hookMoney = 0;
					}
					$markContractId = $val['objId'];
					$markOutStockId = $val['outStockId'];
					$markInvoiceId = $val['id'];
					$invoiceTypeName = $datadictDao->getDataNameByCode($val['invoiceType']);
					if(!empty($markOutStockId)){
						$disabledInput =  ' class="txtshort"';
						$outAllMoney = $val['outMoney'];
						if($sysYear != $val['thisYear'] || $sysMonth != $val['thisMonth']){
							if($carryRate != 0){
								$disabledCheck = ' disabled="disabled"';
							}else{
								$disabledCheck =  null;
							}
							if($carryRate == 100){
								$disabledInput = ' disabled="disabled" class="readOnlyTxtShort"';
							}
						}else{
							$disabledCheck =  null;
						}
						$hookStr =<<<EOT
							<td>
								<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$val[detailId]" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check')"/>
								<input type="hidden" id="invoice$mainId" value="$markInvoiceId"/>
								<input type="hidden" id="sale$mainId" value="$markContractId"/>
								<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
								<input type="hidden" id="objType$mainId" value="$val[objType]"/>
								<input type="hidden" id="outStockId$mainId" value="$markOutStockId"/>
								<input type="hidden" id="outStockCode$mainId" value="$val[docCode]"/>
								<input type="hidden" id="thisMoney$mainId" value="$val[subCost]"/>
								<input type="hidden" id="thisYear$mainId" value="$val[thisYear]"/>
								<input type="hidden" id="thisMonth$mainId" value="$val[thisMonth]"/>
								<input type="hidden" id="beforeCarry$mainId" value="$val[beforeCarry]"/>
								<input type="hidden" id="thisCarry$mainId" value="$val[thisCarry]"/>
							</td>
							<td>
								<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
								<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
							</td>
EOT;
					}else{
						$hookStr =<<<EOT
							<td colspan="2">
							</td>
EOT;
					}
					$str .=<<<EOT
						<tr class="$classCss">
							<td>
								$val[objCode]
							</td>
							<td>
								$val[psTypeNames]
							</td>
							<td>
								$invoiceTypeName
							</td>
							<td class='formatMoney'>
								$val[invoiceMoney]
							</td>
							<td class='formatMoney'>
								$val[historyMoney]
							</td>
							<td class='formatMoney'>
								$val[notOpenMoney]
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								$val[docCode]
							</td>
							<td class='formatMoney'>
								$val[outMoney]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[actOutNum]
							</td>
							<td class='formatMoney'>
								$val[cost]
							</td>
							<td class='formatMoney'>
								$val[subCost]
							</td>
							$hookStr
						</tr>
EOT;
				}else if($markContractId == $val['objId'] && $markInvoiceId == $val['id'] && $markOutStockId != $val['outStockId'] ){
					$markOutStockId = $val['outStockId'];
					if(!empty($markOutStockId)){
						$disabledInput =  ' class="txtshort"';
						if($sysYear != $val['thisYear'] || $sysMonth != $val['thisMonth']){
							if($carryRate != 0){
								$disabledCheck = ' disabled="disabled" ';
							}else{
								$disabledCheck =  null;
							}
							if($carryRate == 100){
								$disabledInput = ' disabled="disabled" class="readOnlyTxtShort"';
							}
						}else{
							$disabledCheck =  null;
						}
						$outAllMoney += $val['outMoney'];
						$hookStr =<<<EOT
							<td>
								<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$val[detailId]" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check')"/>
								<input type="hidden" id="invoice$mainId" value="$markInvoiceId"/>
								<input type="hidden" id="sale$mainId" value="$markContractId"/>
								<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
								<input type="hidden" id="objType$mainId" value="$val[objType]"/>
								<input type="hidden" id="outStockId$mainId" value="$markOutStockId"/>
								<input type="hidden" id="outStockCode$mainId" value="$val[docCode]"/>
								<input type="hidden" id="thisMoney$mainId" value="$val[subCost]"/>
								<input type="hidden" id="thisYear$mainId" value="$val[thisYear]"/>
								<input type="hidden" id="thisMonth$mainId" value="$val[thisMonth]"/>
								<input type="hidden" id="beforeCarry$mainId" value="$val[beforeCarry]"/>
								<input type="hidden" id="thisCarry$mainId" value="$val[thisCarry]"/>
							</td>
							<td>
								<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
								<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
							</td>
EOT;
					}else{
						$hookStr =<<<EOT
							<td colspan="2">
							</td>
EOT;
					}
					$str .=<<<EOT
						<tr class="$classCss">
							<td colspan="6">
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								$val[docCode]
							</td>
							<td class='formatMoney'>
								$val[outMoney]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[actOutNum]
							</td>
							<td class='formatMoney'>
								$val[cost]
							</td>
							<td class='formatMoney'>
								$val[subCost]
							</td>
							$hookStr
						</tr>
EOT;
				}else if($markContractId == $val['objId'] && $markInvoiceId != $val['id'] && $markOutStockId == $val['outStockId'] ){
					$markInvoiceId = $val['id'];
					$outAllMoney = $val['outMoney'];
					$invoiceTypeName = $datadictDao->getDataNameByCode($val['invoiceType']);
					if(!empty($markOutStockId)){
						$disabledInput =  ' class="txtshort"';
						if($sysYear != $val['thisYear'] || $sysMonth != $val['thisMonth']){
							if($carryRate != 0){
								$disabledCheck = ' disabled="disabled" ';
							}else{
								$disabledCheck =  null;
							}
							if($carryRate == 100){
								$disabledInput = ' disabled="disabled" class="readOnlyTxtShort"';
							}
						}else{
							$disabledCheck =  null;
						}
						$hookStr =<<<EOT
							<td>
								<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$val[detailId]" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check')"/>
								<input type="hidden" id="invoice$mainId" value="$markInvoiceId"/>
								<input type="hidden" id="sale$mainId" value="$markContractId"/>
								<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
								<input type="hidden" id="objType$mainId" value="$val[objType]"/>
								<input type="hidden" id="outStockId$mainId" value="$markOutStockId"/>
								<input type="hidden" id="outStockCode$mainId" value="$val[docCode]"/>
								<input type="hidden" id="thisMoney$mainId" value="$val[subCost]"/>
								<input type="hidden" id="thisYear$mainId" value="$val[thisYear]"/>
								<input type="hidden" id="thisMonth$mainId" value="$val[thisMonth]"/>
								<input type="hidden" id="beforeCarry$mainId" value="$val[beforeCarry]"/>
								<input type="hidden" id="thisCarry$mainId" value="$val[thisCarry]"/>
							</td>
							<td>
								<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
								<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
							</td>
EOT;
					}else{
						$hookStr =<<<EOT
							<td colspan="2">
							</td>
EOT;
					}
					$str .=<<<EOT
						<tr class="$classCss">
							<td>
							</td>
							<td>
								$val[psTypeNames]
							</td>
							<td>
								$invoiceTypeName
							</td>
							<td class='formatMoney'>
								$val[invoiceMoney]
							</td>
							<td class='formatMoney'>
								$val[historyMoney]
							</td>
							<td class='formatMoney'>
								$val[notOpenMoney]
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								$val[docCode]
							</td>
							<td class='formatMoney'>
								$val[outMoney]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[actOutNum]
							</td>
							<td class='formatMoney'>
								$val[cost]
							</td>
							<td class='formatMoney'>
								$val[subCost]
							</td>
							$hookStr
						</tr>
EOT;
				}else if($markContractId == $val['objId'] && $markInvoiceId == $val['id'] && $markOutStockId == $val['outStockId'] ){
					$disabledInput =  ' class="txtshort"';
					if($sysYear != $val['thisYear'] || $sysMonth != $val['thisMonth']){
						if($carryRate != 0){
							$disabledCheck = ' disabled="disabled" ';
						}else{
							$disabledCheck =  null;
						}
						if($carryRate == 100){
							$disabledInput = ' disabled="disabled" class="readOnlyTxtShort"';
						}
					}else{
						$disabledCheck =  null;
					}
					$hookStr =<<<EOT
						<td>
							<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$val[detailId]" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check')"/>
							<input type="hidden" id="invoice$mainId" value="$markInvoiceId"/>
							<input type="hidden" id="sale$mainId" value="$markContractId"/>
							<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
							<input type="hidden" id="objType$mainId" value="$val[objType]"/>
							<input type="hidden" id="outStockId$mainId" value="$markOutStockId"/>
							<input type="hidden" id="outStockCode$mainId" value="$val[docCode]"/>
							<input type="hidden" id="thisMoney$mainId" value="$val[subCost]"/>
							<input type="hidden" id="thisYear$mainId" value="$val[thisYear]"/>
							<input type="hidden" id="thisMonth$mainId" value="$val[thisMonth]"/>
							<input type="hidden" id="beforeCarry$mainId" value="$val[beforeCarry]"/>
							<input type="hidden" id="thisCarry$mainId" value="$val[thisCarry]"/>
						</td>
						<td>
							<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
							<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
						</td>
EOT;
					$str .=<<<EOT
						<tr class="$classCss">
							<td colspan="9">
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[actOutNum]
							</td>
							<td class='formatMoney'>
								$val[cost]
							</td>
							<td class='formatMoney'>
								$val[subCost]
							</td>
							$hookStr
						</tr>
EOT;
				}else{
					$markOutStockId = $val['outStockId'];
					$markInvoiceId = $val['id'];
					$outAllMoney = $val['outMoney'];
					$disabledInput =  ' class="txtshort"';
					if($sysYear != $val['thisYear'] || $sysMonth != $val['thisMonth']){
						if($carryRate != 0){
							$disabledCheck = ' disabled="disabled" ';
						}else{
							$disabledCheck =  null;
						}
						if($carryRate == 100){
							$disabledInput = ' disabled="disabled" class="readOnlyTxtShort"';
						}
					}else{
						$disabledCheck =  null;
					}
					$hookStr =<<<EOT
						<td>
							<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$val[detailId]" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check')"/>
							<input type="hidden" id="invoice$mainId" value="$markInvoiceId"/>
							<input type="hidden" id="sale$mainId" value="$markContractId"/>
							<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
							<input type="hidden" id="objType$mainId" value="$val[objType]"/>
							<input type="hidden" id="outStockId$mainId" value="$markOutStockId"/>
							<input type="hidden" id="outStockCode$mainId" value="$val[docCode]"/>
							<input type="hidden" id="thisMoney$mainId" value="$val[subCost]"/>
							<input type="hidden" id="thisYear$mainId" value="$val[thisYear]"/>
							<input type="hidden" id="thisMonth$mainId" value="$val[thisMonth]"/>
							<input type="hidden" id="beforeCarry$mainId" value="$val[beforeCarry]"/>
							<input type="hidden" id="thisCarry$mainId" value="$val[thisCarry]"/>
						</td>
						<td>
							<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
							<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
						</td>
EOT;
					$invoiceTypeName = $datadictDao->getDataNameByCode($val['invoiceType']);
					$str .=<<<EOT
						<tr class="$classCss">
							<td>
							</td>
							<td>
								$val[psTypeNames]
							</td>
							<td>
								$invoiceTypeName
							</td>
							<td class='formatMoney'>
								$val[invoiceMoney]
							</td>
							<td class='formatMoney'>
								$val[historyMoney]
							</td>
							<td class='formatMoney'>
								$val[notOpenMoney]
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								$val[docCode]
							</td>
							<td class='formatMoney'>
								$val[outMoney]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[actOutNum]
							</td>
							<td class='formatMoney'>
								$val[cost]
							</td>
							<td class='formatMoney'>
								$val[subCost]
							</td>
							$hookStr
						</tr>
EOT;
				}
				if( $carryRate != 0){
					$hookMoney = $hookMoney + $carryRate/100 * $val['subCost'];
				}
			}
			$hookMoney = round($hookMoney,2);
			$str .=<<<EOT
				<tr class="tr_count">
					<td colspan="7"></td>
					<td><b title="同一合同号的出库金额不会重复统计">小计</b><input type="hidden" id="count$markContractId" value="$outAllMoney"/></td>
					<td class="formatMoney">$outAllMoney</td>
					<td colspan="2"></td>
					<td><b>已钩稽</b><input type="hidden" id="hooked$markContractId" value="$hookMoney"/></td>
					<td class="formatMoney" id="hookedView$markContractId">$hookMoney</td>
					<td colspan="2"></td>
				</tr>
EOT;
		}
		return array($str,$i);
	}


	/**
	 * 显示发票钩稽页面 - 改版
	 * p1 合同集
	 * p2 查询条件
	 * p3 系统日期
	 */
	function showInvoiceDetail2($rows,$object,$dateArr){
		$sysYear = $dateArr['thisYear'];
		$sysMonth = $dateArr['thisMonth'];
		$str = null;
		$contractIds = null;
		$outstockIds = null;
		if(!empty($rows)){
			$i = 0;
			$stockoutDao = new model_stock_outstock_stockout();
			$thisEndDate = $object['thisYear'] .'-' .$object['thisMonth'].'-31';  //查询日期
			$contractArr = array();
			$outstockArr = array();
			foreach($rows as $key => $val){
				$i ++;
				$thisStr = null;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';

				//获取合同对应出库单据
				$stockRows = $stockoutDao->findByContract($thisEndDate,$val['objType'],$val['objId']);
				//出库金额和已钩稽金额初始化
				$outAllMoney = $hookMoney = 0;
				//设置合同号
				$markContractId = $val['objId'];
				array_push($contractArr,$markContractId);
				//初始化选择框和输入框样式
				$disabledCheck = $disabledInput =  null;

				//如果次行数据存在发货，则同时渲染设备列
				if(is_array($stockRows)){

					$outstockIds = $this->outstockIdsDeal_d($stockRows);
					//获取出库单钩稽记录
					$hookRateArr = $this->getHookRate_d($object,$dateArr,$outstockIds);

					foreach($stockRows as $inKey => $inVal){
						$mainId = $inVal['id'];
						$thisYear = $hookRateArr[$mainId]['thisYear'];
						$thisMonth = $hookRateArr[$mainId]['thisMonth'];
						$beforeCarry = $hookRateArr[$mainId]['beforeCarry'];
						$thisCarry = $hookRateArr[$mainId]['thisCarry'];
						$outAllMoney = bcadd($outAllMoney,$inVal['subCost'],2);

						$carryRate = empty($hookRateArr[$mainId]['carryRate']) ? 0 : $hookRateArr[$mainId]['carryRate'];  //钩稽比例

						$hookMoney = bcadd($hookMoney,bcmul(bcDiv($carryRate,100,2),$inVal['subCost'],2),2);
						if($sysYear != $thisYear || $sysMonth != $thisMonth){
							if($carryRate != 0){
								$disabledCheck = ' disabled="disabled" ';
							}else{
								$disabledCheck =  null;
							}
							if($carryRate == 100){
								$disabledInput = ' disabled="disabled" class="readOnlyTxtShort"';
							}else{
								$disabledInput = ' class="txtshort"';
							}
						}else{
							$disabledInput = ' class="txtshort"';
						}
						if($inKey == 0){
							$thisStr .=<<<EOT
								<tr class="$classCss">
									<td>
										$val[objCode]
									</td>
									<td>
										$val[psType]
									</td>
									<td>
										$val[invoiceType]
									</td>
									<td class="formatMoney">
										$val[thisInvoiceMoney]
									</td>
									<td class="formatMoney">
										$val[historyInvoiceMoney]
									</td>
									<td class="formatMoney">
										$val[unInvoiceMoney]
									</td>
									<td>
										$inVal[auditDate]
									</td>
									<td>
										$inVal[docCode]
									</td>
									<td class="formatMoney">
										$inVal[subCost]
									</td>
									<td >
										<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$mainId" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check');"/>
										<input type="hidden" id="thisMoney$mainId" value="$inVal[subCost]"/>
										<input type="hidden" id="sale$mainId" value="$markContractId"/>
										<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
										<input type="hidden" id="objType$mainId" value="$val[objType]"/>
										<input type="hidden" id="outStockId$mainId" value="$inVal[id]"/>
										<input type="hidden" id="outStockCode$mainId" value="$inVal[docCode]"/>
										<input type="hidden" id="thisYear$mainId" value="$thisYear"/>
										<input type="hidden" id="thisMonth$mainId" value="$thisMonth"/>
										<input type="hidden" id="beforeCarry$mainId" value="$beforeCarry"/>
										<input type="hidden" id="thisCarry$mainId" value="$thisCarry"/>
									</td>
									<td>
										<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
										<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
									</td>
									<td>
										<input type="button" class="txt_btn_a" value=" 详 细 " onclick="viewOutStock($mainId,'$inVal[skey_]')"/>
									</td>
								</tr>
EOT;
						}else{
							$thisStr .=<<<EOT
								<tr class="$classCss">
									<td colspan="6">
									</td>
									<td>
										$inVal[auditDate]
									</td>
									<td>
										$inVal[docCode]
									</td>
									<td class="formatMoney">
										$inVal[subCost]
									</td>
									<td >
										<input type="checkbox" class="countCheck$markContractId" name="outStock" id="$mainId" value="$mainId" $disabledCheck onclick="setRate(this.id);countHooked($markContractId,'$mainId','check');"/>
										<input type="hidden" id="thisMoney$mainId" value="$inVal[subCost]"/>
										<input type="hidden" id="sale$mainId" value="$markContractId"/>
										<input type="hidden" id="saleCode$mainId" value="$val[objCode]"/>
										<input type="hidden" id="objType$mainId" value="$val[objType]"/>
										<input type="hidden" id="outStockId$mainId" value="$inVal[id]"/>
										<input type="hidden" id="outStockCode$mainId" value="$inVal[docCode]"/>
										<input type="hidden" id="thisYear$mainId" value="$thisYear"/>
										<input type="hidden" id="thisMonth$mainId" value="$thisMonth"/>
										<input type="hidden" id="beforeCarry$mainId" value="$beforeCarry"/>
										<input type="hidden" id="thisCarry$mainId" value="$thisCarry"/>
									</td>
									<td>
										<input type="input" name="contRate$markContractId" id="rate$mainId" value="$carryRate" $disabledInput onblur="checkThisNum(this,'$mainId');countHooked($markContractId,'$mainId','input')" />
										<input type="hidden" id="hiddenRate$mainId" value="$carryRate"/>
									</td>
									<td>
										<input type="button" class="txt_btn_a" value=" 详 细 " onclick="viewOutStock($mainId,'$inVal[skey_]')"/>
									</td>
								</tr>
EOT;

						}
					}

					$thisStr .=<<<EOT
						<tr class="tr_count">
							<td colspan="7"></td>
							<td><b title="同一合同号的出库金额不会重复统计">小计</b><input type="hidden" id="count$markContractId" value="$outAllMoney"/></td>
							<td class="formatMoney">$outAllMoney</td>
							<td><b>已钩稽</b><input type="hidden" id="hooked$markContractId" value="$hookMoney"/></td>
							<td class="formatMoney" id="hookedView$markContractId">$hookMoney</td>
							<td></td>
						</tr>
EOT;
				}else{//否则只渲染主行
				$thisStr.=<<<EOT
					<tr class="$classCss">
						<td>
							$val[objCode]
						</td>
						<td>
							$val[psType]
						</td>
						<td>
							$val[invoiceType]
						</td>
						<td class="formatMoney">
							$val[thisInvoiceMoney]
						</td>
						<td class="formatMoney">
							$val[historyInvoiceMoney]
						</td>
						<td class="formatMoney">
							$val[unInvoiceMoney]
						</td>
						<td colspan="6">
						</td>
					</tr>
EOT;
				}
			$str .= $thisStr;
			}
			$contractIds = implode($contractArr,',');
		}
		return array($str,$contractIds);
	}

	/**
	 * 显示发票钩稽页面 - 改版
	 * p1 合同集
	 * p2 查询条件
	 * p3 系统日期
	 */
	function showInvoiceDetail3($rows,$object,$dateArr){
		$str = null;
		$contractIds = null;
		if(!empty($rows)){
			$i = 0;
			$contractArr = array();
			$datadictDao = new model_system_datadict_datadict();
			$markId = null;
			$thisInvoiceMoney = 0;
			$historyMoney = 0;
			foreach($rows as $key => $val){
				$i ++;
				$thisStr = null;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$red = $val['isRed'] == 0 ? '' : 'red';
				$productModel = $datadictDao->getDataNameByCode($val['productModel']);
				if(empty($markId)){//首行处理
					$markId = $val['id'];
					$thisInvoiceMoney = bcadd($thisInvoiceMoney,$val['thisInvoiceMoney'],2);
					$historyMoney = $val['historyInvoiceMoney'];
					$str.=<<<EOT
						<tr class="$classCss" ondblclick="getStockoutInfo($i)">
							<td>
								<a href="#" title="点击查看合同" onclick="viewSale('$val[objId]','$val[objType]')">$val[objCode]</a>
							</td>
							<td class="formatMoney lc_$markId">
								$val[contractMoney]
							</td>
							<td class="formatMoney lc_$markId">
								$val[historyInvoiceMoney]
							</td>
							<td class="lc_$markId">
								<a href="#" title="点击查看发票" onclick="viewInvoice('$val[invoiceId]')">$val[invoiceNo]</a>
							</td>
							<td class="lc_$markId">
								$productModel
							</td>
							<td class="lc_$markId">
								<span class="formatMoney $red">$val[thisInvoiceMoney]</span>
								<input type="hidden" id="invoiceId$i" value="$val[invoiceId]"/>
								<input type="hidden" id="invoiceDetailId$i" value="$val[invoiceDetailId]"/>
								<input type="hidden" id="objId$i" value="$val[objId]"/>
								<input type="hidden" id="objCode$i" value="$val[objCode]"/>
								<input type="hidden" id="objType$i" value="$val[objType]"/>
								<input type="hidden" id="rObjCode$i" value="$val[rObjCode]"/>
								<input type="hidden" id="invoiceNo$i" value="$val[invoiceNo]"/>
								<input type="hidden" id="productModel$i" value="$productModel"/>
								<span id="row$i"></span>
							</td>
						</tr>
EOT;
				}elseif($markId == $val['id']){//同合同号处理
					$thisInvoiceMoney = bcadd($thisInvoiceMoney,$val['thisInvoiceMoney'],2);
					$str.=<<<EOT
						<tr class="$classCss lc_$markId" ondblclick="getStockoutInfo($i)">
							<td colspan="3"></td>
							<td class="lc_$markId">
								<a href="#" title="点击查看发票" onclick="viewInvoice('$val[invoiceId]')">$val[invoiceNo]</a>
							</td>
							<td class="lc_$markId">
								$productModel
							</td>
							<td class="lc_$markId">
								<span class="formatMoney $red">$val[thisInvoiceMoney]</span>
								<input type="hidden" id="invoiceId$i" value="$val[invoiceId]"/>
								<input type="hidden" id="invoiceDetailId$i" value="$val[invoiceDetailId]"/>
								<input type="hidden" id="objId$i" value="$val[objId]"/>
								<input type="hidden" id="objCode$i" value="$val[objCode]"/>
								<input type="hidden" id="objType$i" value="$val[objType]"/>
								<input type="hidden" id="rObjCode$i" value="$val[rObjCode]"/>
								<input type="hidden" id="invoiceNo$i" value="$val[invoiceNo]"/>
								<input type="hidden" id="productModel$i" value="$productModel"/>
								<span id="row$i"></span>
							</td>
						</tr>
EOT;
				}else{//不同合同号处理
					$beforeMoney = bcsub($historyMoney,$thisInvoiceMoney,2);
					$str .=<<<EOT
						<tr class="tr_count" ondblclick="closeList('$markId')">
							<td>
								<b>小计</b>
								<img src="images/icon/icon003.gif" id="img_$markId"/>
								<input type="hidden" id="mark_$markId" value="0"/>
							</td>
							<td><b>前期开票合计</b></td>
							<td class="formatMoney">$beforeMoney</td>
							<td></td>
							<td><b>本期开票合计</b></td>
							<td class="formatMoney">$thisInvoiceMoney</td>
						</tr>
EOT;
					$thisInvoiceMoney = bcadd(0,$val['thisInvoiceMoney'],2);
					$historyMoney = $val['historyInvoiceMoney'];
					$markId = $val['id'];
					$str.=<<<EOT
						<tr class="$classCss" ondblclick="getStockoutInfo($i)">
							<td>
								<a href="#" title="点击查看合同" onclick="viewSale('$val[objId]','$val[objType]')">$val[objCode]</a>
							</td>
							<td class="formatMoney lc_$markId">
								$val[contractMoney]
							</td>
							<td class="formatMoney lc_$markId">
								$val[historyInvoiceMoney]
							</td>
							<td class="lc_$markId">
								<a href="#" title="点击查看发票" onclick="viewInvoice('$val[invoiceId]')">$val[invoiceNo]</a>
							</td>
							<td class="lc_$markId">
								$productModel
							</td>
							<td class="lc_$markId">
								<span class="formatMoney $red">$val[thisInvoiceMoney]</span>
								<input type="hidden" id="invoiceId$i" value="$val[invoiceId]"/>
								<input type="hidden" id="invoiceDetailId$i" value="$val[invoiceDetailId]"/>
								<input type="hidden" id="objId$i" value="$val[objId]"/>
								<input type="hidden" id="objCode$i" value="$val[objCode]"/>
								<input type="hidden" id="objType$i" value="$val[objType]"/>
								<input type="hidden" id="rObjCode$i" value="$val[rObjCode]"/>
								<input type="hidden" id="invoiceNo$i" value="$val[invoiceNo]"/>
								<input type="hidden" id="productModel$i" value="$productModel"/>
								<span id="row$i"></span>
							</td>
						</tr>
EOT;
				}

			}
			$beforeMoney = bcsub($historyMoney,$thisInvoiceMoney,2);
			$str .=<<<EOT
				<tr class="tr_count" ondblclick="closeList('$markId')">
					<td>
						<b>小计</b>
						<img src="images/icon/icon003.gif" id="img_$markId"/>
						<input type="hidden" id="mark_$markId" value="0"/>
					</td>
					<td><b>前期开票合计</b></td>
					<td class="formatMoney">$beforeMoney</td>
					<td></td>
					<td><b>本期开票合计</b></td>
					<td class="formatMoney">$thisInvoiceMoney</td>
				</tr>
EOT;
			$contractIds = implode($contractArr,',');
		}
		return array($str,$contractIds);
	}



	/**
	 * 渲染出库单
	 */
	function stockList($rows,$object){
		$str = null;
		if(is_array($rows)){
			$i = 0;
			//已钩稽全部金额
			$allCarryMoney = 0;
			//已钩稽出库单id
			$outstockIdArr = array();
			//已钩稽出库单id串
			$outstockIds = null;
			//出库金额合计
			$allOutstockMoney = 0;
			foreach($rows as $key => $val){
				$i++;
				$allCanCarryRate = $val['canCarryRate'] + $val['utilPeriodCarryRate'];

				//当钩稽类型为 详细钩稽时,只能进行详细钩稽
                if($val['hookStatus'] == 1){
                    $readonly = "readonly = 'readonly' class='readOnlyTxtMin' ";
                    $inputDbclick = " ondblclick='carryDetail($i,$val[id])'";
                }else{
                    $readonly = " class='txtmin' ";
                    $inputDbclick = "";
                }

				//当前期钩稽比例不为0时,不允许取消钩稽
				if($val['beforePeriodCarryRate'] != 0){
					$checkdisable = " disabled = 'disabled' ";
				}else{
					$checkdisable = "";
				}

				//当全部钩稽状态为0时,允许双击钩稽
				if($val['allPeriodCarryRate'] == 0){
					$isCheck = '';
					$inputDbclick = " ondblclick='carryDetail($i,$val[id])'";
				}else{
					$isCheck = "checked = 'checked'";
					//已钩稽金额
					$allCarryMoney = bcadd($allCarryMoney,$val['utilPeriodCarryMoney'],2);
					//已钩稽出库单id
					array_push($outstockIdArr,$val['id']);
				}

//				$canCarryRate = bcsub($val['canCarryRate'],$val['beforePeriodCarryRate']);
				$allOutstockMoney = bcadd($allOutstockMoney,$val['subCost'],2);

				$canCarryRate = isset($val['canCarryRate']) ? $val['canCarryRate'] : 0;
				$beforePeriodCarryRate = isset($val['beforePeriodCarryRate']) ? $val['beforePeriodCarryRate'] : 0;
				$thisPeriodCarryRate = isset($val['thisPeriodCarryRate']) ? $val['thisPeriodCarryRate'] : 0;
				$utilPeriodCarryRate = isset($val['utilPeriodCarryRate']) ? $val['utilPeriodCarryRate'] : 0;
				$str.=<<<EOT
					<tr align="center">
						<td>
							<a href="#" title="点击查看出库单" onclick="viewStock('$val[id]')">$val[docCode]</a><img title="打印" src="images/icon/print.gif" onclick="printOutstock('$val[id]')"/>
							<input type="hidden" id="outstockId$i" value="$val[id]"/>
							<input type="hidden" id="outstockCode$i" value="$val[docCode]"/>
							<input type="hidden" id="outstockMoney$i" value="$val[subCost]"/>
							<input type="hidden" id="canCarryRate$i" value="$canCarryRate"/>
							<input type="hidden" id="canCarryMoney$i" value="$val[canCarryMoney]"/>
							<input type="hidden" id="orgCanCarryRate$i" value="$val[canCarryRate]"/>
							<input type="hidden" id="allCanCarryRate$i" value="$allCanCarryRate"/>
							<input type="hidden" id="beforePeriodCarryRate$i" value="$beforePeriodCarryRate"/>
							<input type="hidden" id="thisPeriodCarryRate$i" value="$thisPeriodCarryRate"/>
							<input type="hidden" id="mainI$i" value="$object[thisI]"/>
		                </td>
		                <td>
							$val[auditDate]
		                </td>
		                <td class="formatMoney">
							$val[subCost]
		                </td>
		                <td title="金额为 $val[canCarryMoney]">
							<span id="canCarryRateView$i">$canCarryRate</span> %
		                </td>
						<td>
							<input type="checkbox" name="outstock" id="checkBtn$i" onclick="setRate($i);countHooked($i)" value="$i" $isCheck $checkdisable/>
						</td>
		    			<td>
		    				<input type="text" id="rate$i" onblur="checkThisNum(this,'$i');countHooked($i)" value="$utilPeriodCarryRate" $inputDbclick $readonly/>
		    				<input type="hidden" id="rateHidden$i" value="$utilPeriodCarryRate"/>
						</td>
		    			<td class="form_text_right">
							<input type="text" id="hookMoney$i" class="readOnlyTxtShort formatMoney" readonly="readonly" value="$val[utilPeriodCarryMoney]"/>
						</td>
					</tr>
EOT;
			}
			if(!empty($outstockIdArr)){
				$outstockIds = implode($outstockIdArr,',');
			}
			$str .=<<<EOT
				<tr class="tr_count">
					<td title="$outstockIds">
						<b>小计</b>
						<input type="hidden" id="outstockIds" value="$outstockIds"/>
					</td>
					<td><b>出库金额合计</b></td>
					<td class="formatMoney">$allOutstockMoney</td>
					<td></td>
					<td></td>
					<td><b>钩稽合计</b></td>
					<td class="form_text_right"><span class="formatMoney" id="allHookMoney">$allCarryMoney</span></td>
				</tr>
EOT;
		}
		return $str;
	}

	/**
	 * 出库单详细钩稽
	 */
	function showOutStockDetailCarry($rows,$object){
		$str = null;
		if(is_array($rows)){
			$i = 0;
			//出库金额合计
			$allOutstockMoney = 0;
			//钩稽金额合计
			$allCarryMoney = 0;
			foreach($rows as $key => $val){
				$i++;
				$allCanCarryRate = $val['carryRate'] + $val['canCarryRate'];

				//出库金额合计
				$allOutstockMoney = bcadd($allOutstockMoney,$val['subCost'],2);

				//钩稽金额合计
				$allCarryMoney = bcadd($allCarryMoney,$val['carryMoney'],2);

				//钩稽动作,默认为0,即无动作
				$hookType = 0;
				$str.=<<<EOT
					<tr align="center">
						<td>
							$val[productCode]
							<input type="hidden" id="outstockId$i" name="carriedforward[$i][outStockId]" value="$val[outstockId]"/>
							<input type="hidden" id="outstockDetailId$i" name="carriedforward[$i][outStockDetailId]" value="$val[outstockDetailId]"/>
							<input type="hidden" name="carriedforward[$i][invoiceDetailId]" value="$object[invoiceDetailId]"/>
							<input type="hidden" name="carriedforward[$i][thisYear]" value="$object[sysYear]"/>
							<input type="hidden" name="carriedforward[$i][thisMonth]" value="$object[sysMonth]"/>
							<input type="hidden" name="carriedforward[$i][saleId]" value="$object[salesId]"/>
							<input type="hidden" name="carriedforward[$i][saleCode]" value="$object[salesCode]"/>
							<input type="hidden" name="carriedforward[$i][saleType]" value="$object[salesType]"/>
							<input type="hidden" name="carriedforward[$i][invoiceId]" value="$object[invoiceId]"/>
							<input type="hidden" name="carriedforward[$i][outStockCode]" value="$val[outstockCode]"/>
							<input type="hidden" name="carriedforward[$i][carryType]" value="0"/>
							<input type="hidden" name="carriedforward[$i][mainI]" value="$object[mainI]"/>
							<input type="hidden" name="carriedforward[$i][hookType]" id="hookType$i" value="$hookType"/>

							<input type="hidden" id="orgCanCarryRate$i" value="$val[canCarryRate]"/>
							<input type="hidden" id="canCarryRate$i" value="$val[canCarryRate]"/>
							<input type="hidden" id="rateHidden$i" value="$val[carryRate]"/>
							<input type="hidden" id="allCanCarryRate$i" value="$allCanCarryRate"/>
							<input type="hidden" id="subCost$i" value="$val[subCost]"/>
		                </td>
		                <td>
							$val[productName]
		                </td>
		                <td>
		                	<span class="formatMoney">$val[subCost]</span>
		                </td>
		                <td>
		                	<span id="canCarryRateView$i">$val[canCarryRate]</span> %
		                </td>
						<td>
		    				<input type="text" id="rate$i" name="carriedforward[$i][carryRate]" class="txtshort carryRateClass" onblur="checkThisNum(this,'$i');countHooked($i)" value="$val[carryRate]"/>
						</td>
		    			<td>
							<input type="text" id="hookMoney$i" class="readOnlyTxtMiddle formatMoney" readonly="readonly" value="$val[carryMoney]"/>
						</td>
					</tr>
EOT;
			}
			$str .=<<<EOT
				<tr class="tr_count">
					<td>
						<b>小计</b>
					</td>
					<td><b>出库金额合计</b></td>
					<td class="formatMoney">$allOutstockMoney</td>
					<td></td>
					<td><b>钩稽合计</b></td>
					<td class="form_text_right"><span class="formatMoney" id="allHookMoney">$allCarryMoney</span></td>
				</tr>
EOT;
		}
		return $str;
	}

	/*************************************发票钩稽页面***********************************/

	/*************************************出库钩稽页面***********************************/

	/**
	 * 显示出库详细页
	 */
	function showOutStockDetail($rows,$carryObj){
		$str = null;
		if(!empty($rows)){
			$i = 0;
			$markContractId = $markOutStockId = $invoiceTypeName = null;
			$datadictDao = new model_system_datadict_datadict();
			foreach($rows as $key => $val){
				$i ++ ;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				if($markContractId != $val['contractId'] && $markOutStockId != $val['id']){//不同合同号不同出库单行处理
					if($markContractId){
						$str .=<<<EOT
							<tr class="tr_count"><td colspan="10"></td></tr>
EOT;
					}
					$markContractId = $val['contractId'];
					$markOutStockId = $val['id'];
					$invoiceTypeName = $datadictDao->getDataNameByCode($val['invoiceType']);
					$str .=<<<EOT
						<tr class="$classCss">
							<td>
								<input type="checkbox" name="outStock" id="$val[id]" value="$val[id]"/>
								<input type="hidden" value="$val[skey_]" id="skey$val[id]"/>
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								<a href="#" onclick="viewOutStock($markOutStockId,'$val[skey_]')">$val[docCode]</a><img title='打印' src="images/icon/print.gif" onclick="printOutstock($markOutStockId,'$val[skey_]')"/>
							</td>
							<td class='formatMoney'>
								$val[outMoney]
							</td>
							<td>
								$val[contractCode]
							</td>
							<td>
								$val[psTypeNames]
							</td>
							<td>
								$invoiceTypeName
							</td>
							<td class='formatMoney'>
								$val[allInoivceMoney]
							</td>
							<td class='formatMoney'>
								$val[notInvoiceMoney]
							</td>
							<td class='formatMoney'>
								$val[contractMoney]
							</td>
						</tr>
EOT;
				}else if($markContractId == $val['contractId'] && $markOutStockId != $val['id']){//相同合同号不同出库单号处理
					$markOutStockId = $val['id'];
					$str .=<<<EOT
						<tr class="$classCss">
							<td>
								<input type="checkbox" name="outStock" id="$val[id]" value="$val[id]"/>
							</td>
							<td>
								$val[auditDate]
							</td>
							<td>
								$val[docCode]
							</td>
							<td class='formatMoney'>
								$val[outMoney]
							</td>
							<td colspan="6">
							</td>
						</tr>
EOT;
				}else{
					$str .=<<<EOT
						<tr class="$classCss">
							<td colspan="9">
							</td>
						</tr>
EOT;
				}
			}
			$str .=<<<EOT
				<tr class="tr_count"><td colspan="10"></td></tr>
EOT;
		}
		return array($str,$i);
	}

    /***************************接口调用方法************************/

    /**
     * 获取财务周期
     */
    function getPeriod_d(){
    	$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
    }

	/************************开票结转部分**************************/
	/**
	 * 获取开票记录
	 * 参数1 查询条件
	 * 参数2 当前财务周期参数
	 */
	function getInvoiceDetail_d($object,$dateArr){
		$thisDate = $object['thisYear'] .'-' .$object['thisMonth'].'-01'; //查询日期
		$thisEndDate = $object['thisYear'] .'-' .$object['thisMonth'].'-31';  //查询日期
		$sysDate = $dateArr['thisYear'] .'-' .$dateArr['thisMonth'].'-01';  //财务日期
		$thisYear = $object['thisYear'];
		$thisMonth = $object['thisMonth'];
		$customerId  = empty($object['customerId']) ? null : ' and invoice.invoiceUnitId='.$object['customerId'] ;
    	$sql = "select main.id,main.invoiceNo,main.invoiceUnitId,main.objId,main.objCode,main.objType,( main.historyMoney - main.invoiceMoney) as historyMoney,main.psTypeNames,main.contractMoney, (main.contractMoney - main.historyMoney) as notOpenMoney,
					main.invoiceUnitName,main.invoiceType,main.invoiceMoney,main.softMoney,main.repairMoney,main.hardMoney,
					main.serviceMoney,main.productName,main.actOutNum,main.cost,main.subCost,main.outStockId,main.detailId,
					main.docCode,main.auditDate,reco.carryRate,main.outMoney,reco.thisYear,reco.thisMonth,reco.beforeCarry,reco.thisCarry
				from (
					select invoice.id,invoice.invoiceNo,invoice.invoiceUnitId,invoice.objId,invoice.objCode,invoice.objType,invoice.historyMoney,invoice.psTypeNames,invoice.contractMoney,
						invoice.invoiceUnitName,invoice.invoiceType,invoice.invoiceMoney,invoice.softMoney,invoice.repairMoney,invoice.hardMoney,
						invoice.serviceMoney,outStock.productName,outStock.actOutNum,outStock.cost,outStock.subCost,outStock.id as outStockId,outStock.detailId as detailId,
						outStock.docCode,outStock.auditDate,outStock.outMoney
					from
						(select
							id,invoiceNo,invoiceUnitId,objId,objCode,invoice.objType,invoiceUnitName,
							invoiceType,invoiceMoney,softMoney,repairMoney,hardMoney,serviceMoney,
							psTypeNames ,
							historyMoney,
							contractMoney
						from (select
								invoice.id,invoice.invoiceNo,invoice.invoiceUnitId,invoice.objId,invoice.objCode,invoice.objType,invoice.invoiceUnitName,
								invoice.invoiceType,invoice.invoiceMoney,invoice.softMoney,invoice.repairMoney,invoice.hardMoney,invoice.serviceMoney,
								invDe.psTypeNames ,
								if( his.invoiceMoney is null , 0 ,his.invoiceMoney) as historyMoney,
								orderV.contractMoney
							from
								financeview_invoice invoice
							inner join
								(select d.invoiceId,d.psType,group_concat(dd.dataName) as psTypeNames from oa_finance_invoice_detail d left join oa_system_datadict dd on d.psType = dd.dataCode group by d.invoiceId) as invDe
							on invoice.id = invDe.invoiceId
							left join
								(select sum(i.invoiceMoney) as invoiceMoney,i.objId,i.objType from financeview_invoice i group by i.objId,i.objType) his
							on invoice.objId = his.objId and invoice.objType = his.objType
							left join
								financeview_orderinvoice orderV
								on invoice.objId = orderV.orgid and invoice.objType = orderV.thisTableName
							where year(invoice.invoiceTime) = '$thisYear' and month(invoice.invoiceTime) = $thisMonth $customerId ) invoice) invoice
					left join (
						select
							o.id,it.mainId,o.docCode,o.docType,o.isRed,o.contractId,it.productId,it.productCode,it.productName,it.unitName,
							it.cost,it.subCost,o.contractName,o.contractCode,o.customerId,o.auditDate,it.actOutNum,it.id as detailId,omo.outMoney
						from
							oa_stock_outstock o
						left join
							oa_stock_outstock_item it
						on (o.id = it.mainId)
						left join
							(select o.id,sum(it.subcost) as outMoney from oa_stock_outstock o
							left join
								oa_stock_outstock_item it on o.id = it.mainId where docType = 'CKSALES' group by o.id) omo
						on o.id = omo.id
					where o.docType = 'CKSALES' and o.docStatus = 'YSH' order by o.contractId,it.id) outStock on invoice.objId = outStock.contractId  ) main
					left join (select
									c.saleId ,c.saleType ,c.invoiceId ,c.outStockId ,c.outStockDetailId ,c.thisDate,c.thisYear,c.thisMonth,
									sum(c.carryRate) as carryRate,sum(if(DATE_FORMAT(c.hookDate,'%Y%m') < DATE_FORMAT('$sysDate','%Y%m'),c.carryRate,0)) as beforeCarry,sum(if(DATE_FORMAT(c.hookDate,'%Y%m') = DATE_FORMAT('$sysDate','%Y%m'),c.carryRate,0)) as thisCarry
								from oa_finance_carriedforward c where DATE_FORMAT(c.hookDate,'%Y%m') <= DATE_FORMAT('$sysDate','%Y%m') group by c.invoiceId,c.outStockDetailId having carryRate <>  0) reco
					on main.id = reco.invoiceId and main.detailId = reco.outStockDetailId";
		$this->searchArr = null;
		$this->sort = 'main.objId,main.id,main.outStockId,main.detailId';
		$this->asc = false;
	    $rows = $this->listBySql($sql);

		return $rows;
	}

	/**
	 * 获取已钩稽的出库单据id
	 */
	function getInvoiceHooked_d($object,$dateArr){
		$hookDate =  $dateArr['thisYear'] .'-' .$dateArr['thisMonth'].'-01';  //财务日期
		$ids = 'none';
		$sql = "select c.outStockId,c.invoiceId,c.outStockDetailId from ". $this->tbl_name . " c where invoiceId is not null and DATE_FORMAT(hookDate,'%Y%m') <= DATE_FORMAT('$hookDate','%Y%m')";

		$this->searchArr = null;
		$this->sort = 'c.saleId';
		$this->groupBy = 'c.outStockDetailId,c.invoiceId';
		$rows = $this->listBySql($sql);
		if(!empty($rows)){
			foreach($rows as $key => $val){
				if($ids != 'none'){
					$ids .= ','.$val['invoiceId'] . '-' .$val['outStockDetailId'] ;
				}else{
					$ids = $val['invoiceId'] . '-' .$val['outStockDetailId'];
				}
			}
		}
		return $ids;
	}

	/**
	 * 保存钩稽记录
	 */
	function carryInvoice_d($object){
		$sysArr = array( "sysYear" => $object['sysYear'] ,"sysMonth" => $object['sysMonth'],
					"thisMonth" => $object['thisMonth'],"thisYear" => $object['thisYear']
		);
//		print_r($object);
		try{
			$this->start_d();

			if($object['thisYear'] != $object['sysYear'] || $object['thisMonth'] != $object['sysMonth']){
				//清除次月钩稽记录
				$this->deleteAfterHook_d($object);
			}

			//删除钩稽
			$this->invoiceUnHook_d($object['delStr'],$sysArr);

			//新增钩稽
			$this->invoiceHook_d($object['newStr'],$sysArr);

			//修改钩稽
			$this->invoiceUpdateHook_d($object['updateStr'],$sysArr);

			$this->commit_d();

			//获取钩稽
			return $this->getInvoiceHooked_d($object,$sysArr);
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 新增钩稽
	 */
	function invoiceHook_d($object,$sysArr){
		$thisYear = $sysArr['sysYear'] ;
		$thisMonth = $sysArr['sysMonth'] ;
		$periodNo = $sysArr['sysYear'] .'.'. ($sysArr['sysMonth']*1);
		$thisDate = $sysArr['sysYear'] .'-'. $sysArr['sysMonth'] . '-01';
		if(is_array($object)){
			try{
				foreach($object as $key => $val){
					$sql = "insert into ".$this->tbl_name." (saleId,saleCode,saleType,invoiceId,invoiceDetailId,outStockId,outStockCode,outStockType,outStockDetailId,carryRate,rObjCode,thisDate,createId,createName,periodNo,thisYear,thisMonth,carryType,hookDate)
						select ".
							$val['saleId']." as saleId,'". $val['saleCode']."' as saleCode,'". $val['saleType']."' as saleType,".
							$val['invoiceId']." as invoiceId,".$val['invoiceDetailId']." as invoiceDetailId,".
							$val['outStockId']." as outStockId,'". $val['outStockCode']."' as outStockCode,'CKSALES' as outStockType,i.id as outStockDetailId,".
							$val['carryRate']." as carryRate,'".$val['rObjCode']."' as rObjCode,'".
							$thisDate."' as thisDate,'" .$_SESSION['USER_ID']."' as createId,'" .$_SESSION['USERNAME']."' as createName,
							'$periodNo' as periodNo,$thisYear as thisYear,$thisMonth as thisMonth,0 as carryType,'".day_date."' as hookDate
						from oa_stock_outstock_item i inner join oa_stock_outstock o on o.id = i.mainId where o.id = ".$val['outStockId'];
					$this->_db->query($sql);
				}
			}catch(exception $e){
				throw $e;
			}
		}else{
			return false;
		}
	}

	/**
	 * 编辑钩稽
	 */
	function invoiceUpdateHook_d($object,$sysArr){
		$thisYear = $sysArr['sysYear'] ;
		$thisMonth = $sysArr['sysMonth'] ;
		if($object){
			foreach($object as $key => $val){
				$this->update(array('invoiceId' => $val['invoiceId'] ,'outStockDetailId' => $val['outStockDetailId'],
					'thisYear' => $thisYear,'thisMonth' =>$thisMonth
				),$val);
			}
			return true;
		}
		return false;
	}

	/**
	 * 删除钩稽
	 */
	function invoiceUnHook_d($object,$sysArr){
		$thisYear = $sysArr['sysYear'] ;
		$thisMonth = $sysArr['sysMonth'] ;
		if($object){
			foreach($object as $key => $val){
				$this->delete(array('invoiceId' => $val['invoiceId'] ,'outStockDetailId' => $val['outStockDetailId'],
					'thisYear' => $thisYear,'thisMonth' =>$thisMonth
				));
			}
			return true;
		}
		return false;
	}

	/**
	 * 清除次月钩稽记录
	 */
	function deleteAfterHook_d($object){
		if(!empty($object['delStr'])){
			foreach($object['delStr'] as $key => $val){
				$sql = "delete from ". $this->tbl_name . " where outStockDetailId = $val[outStockDetailId] and invoiceId = $val[invoiceId] and thisYear = $object[sysYear] and thisMonth > $object[sysMonth] ";
				$this->_db->query($sql);
			}
		}

		if(!empty($object['newStr'])){
			foreach($object['newStr'] as $key => $val){
				$sql = "delete from ". $this->tbl_name . " where outStockDetailId = $val[outStockDetailId] and invoiceId = $val[invoiceId] and thisYear = $object[sysYear] and thisMonth > $object[sysMonth] ";
				$this->_db->query($sql);
			}
		}

		if(!empty($object['updateStr'])){
			foreach($object['updateStr'] as $key => $val){
				$sql = "delete from ". $this->tbl_name . " where outStockDetailId = $val[outStockDetailId] and invoiceId = $val[invoiceId] and thisYear = $object[sysYear] and thisMonth > $object[sysMonth] ";
				$this->_db->query($sql);
			}
		}
	}
	/***************---E----***开票结转改*******************************/

	/***************---S----***开票结转改*******************************/

	/**
	 * 开票结转改
	 * 获取本期开过票的合同
	 */
	function getThisPeriodInvOrder_d($object,$dateArr){
		$thisDate = $object['thisYear'] .'-' .$object['thisMonth'].'-01'; //查询日期
		$thisEndDate = $object['thisYear'] .'-' .$object['thisMonth'].'-31';  //查询日期
		$sysDate = $dateArr['thisYear'] .'-' .$dateArr['thisMonth'].'-01';  //财务日期
		$thisYear = $object['thisYear'];
		$thisMonth = $object['thisMonth'];
		$customerId  = empty($object['customerId']) ? null : ' and o.customerId='.$object['customerId'] ;

		$sql = "select
				o.id,o.contractCode as objCode,o.objCode as rObjCode
			from
				oa_contract_contract o
				inner join
					(select i.objId,i.objType,sum(invoiceMoney) as  thisInvoiceMoney from oa_finance_invoice i where i.objId != 0 and year(i.invoiceTime ) = $thisYear and month(i.invoiceTime ) = $thisMonth group by i.objType,i.objId) inv
					on o.id = inv.objId and 'KPRK-12' = inv.objType
			where 1=1 $customerId ";
		return $this->pageBySql($sql);
	}

	/**
	 * 根据合同信息获取开票记录
	 */
	function getInvoiceByOrder_d($object,$conditionArr){
		$thisYear = $conditionArr['thisYear'];
		$thisMonth = $conditionArr['thisMonth'];
		if(is_array($object)){
			$sql = "select
				o.id,o.contractCode as objCode,
		        o.contractMoney,'KPRK-12' AS objType,o.id as objId,o.objCode as rObjCode,
				if(invMoney.historyInvoiceMoney is null,0,invMoney.historyInvoiceMoney) as historyInvoiceMoney,
				invoiceDetail.thisInvoiceMoney,invoiceDetail.invoiceNo,invoiceDetail.productModel,invoiceDetail.isRed,
				invoiceDetail.id as invoiceId,invoiceDetail.detailId as invoiceDetailId
			from
				oa_contract_contract o
				inner join
					(select i.objId,i.objType,sum(invoiceMoney) as  thisInvoiceMoney from oa_finance_invoice i where i.objId != 0 and year(i.invoiceTime ) = $thisYear and month(i.invoiceTime ) = $thisMonth group by i.objType,i.objId) inv
					on o.id = inv.objId and 'KPRK-12' = inv.objType
				left join
					(select
						sum(if(i.isRed = 0,i.invoiceMoney,-i.invoiceMoney)) as historyInvoiceMoney,
						i.objId,i.objType
					from oa_finance_invoice i where i.objId != 0 group by i.objId,i.objType) invMoney
					on o.id =invMoney.objId and 'KPRK-12' = invMoney.objType
				left join
					(
						select c.id,c.invoiceNo,c.invoiceTime,c.objId,c.objType,c.isRed,d.id as detailId,d.productModel,
							if(c.isRed = 0,d.softMoney,-d.softMoney) as softMoney,if(c.isRed = 0,d.hardMoney,-d.hardMoney) as hardMoney,
							if(c.isRed = 0,d.repairMoney,-d.repairMoney) as repairMoney,if(c.isRed = 0,d.serviceMoney,-d.serviceMoney) as serviceMoney,
							if(c.isRed = 0,(d.softMoney + d.hardMoney + d.repairMoney + d.serviceMoney),-(d.softMoney + d.hardMoney + d.repairMoney + d.serviceMoney)) as thisInvoiceMoney
						from oa_finance_invoice c inner join oa_finance_invoice_detail d on c.id = d.invoiceId where c.objId <> 0
					) invoiceDetail
					on o.id =invoiceDetail.objId and 'KPRK-12' = invoiceDetail.objType
			where year(invoiceDetail.invoiceTime) = $thisYear and month(invoiceDetail.invoiceTime) = $thisMonth and (";
			foreach($object as $key => $val){
				if($key == 0){
					$sql .= " ( o.id = $val[id] )";
				}else{
					$sql .= " or ( o.id = $val[id] )";
				}
			}
			$sql .= " )";
//			echo $sql;
			$this->sort = 'o.id,invoiceDetail.invoiceTime,invoiceDetail.id,invoiceDetail.detailId';
			$invoiceArr = $this->listBySql($sql);
//			print_r($invoiceArr);
		}
		return $invoiceArr;
	}

	/**
	 * 根据合同信息获取出库单
	 */
	function getOutstockByOrder_d($object){
		//获取单纯的出库数组
		$stockoutDao = new model_stock_outstock_stockout();
		$outstockArr = $stockoutDao->findByContract('2011-11-01',$object['saleType'],$object['saleId']);



		//获取
		return $outstockArr;
	}

	/**
	 * 根据发票产品类型获取出库信息
	 */
	function getOutstockByInvoiceDetailId_d($object,$dataArr){
		$sysDate = $dataArr['thisYear'] .'-' .$dataArr['thisMonth'].'-01';  //财务日期
		$hookDate = $object['thisYear'] .'-' .$object['thisMonth'].'-01';  //选择钩稽期
		$invoiceDetailId = $object['invoiceDetailId'];
		$saleId = $object['saleId'];
		$invioceType = 'KPRK-12';
		$outstockType = 'oa_contract_contract';
		$sql ="
				select
					c.id ,c.docCode ,c.docType ,c.isRed ,c.contractId ,c.contractName ,c.contractCode ,c.contractType,c.docStatus,c.auditDate,
					sum(c.thisPeriodCarryMoney) as thisPeriodCarryMoney,
					round(sum(c.thisPeriodCarryMoney)/sum(c.subCost)*100,2) as thisPeriodCarryRate,
					sum(c.beforePeriodCarryMoney) as beforePeriodCarryMoney,
					round(sum(c.beforePeriodCarryMoney)/sum(c.subCost)*100,2) as beforePeriodCarryRate,
					sum(c.utilPeriodCarryMoney) as utilPeriodCarryMoney,
					round(sum(c.utilPeriodCarryMoney)/sum(c.subCost)*100,2) as utilPeriodCarryRate,
					sum(c.allPeriodCarryMoney) as allPeriodCarryMoney,
					round(sum(c.allPeriodCarryMoney)/sum(c.subCost)*100,2) as allPeriodCarryRate,
					sum(c.cannotCarryMoney) as cannotCarryMoney,
					round(sum(c.cannotCarryMoney)/sum(c.subCost)*100,2) as cannotCarryRate,
					round(sum(c.subCost) - sum(c.cannotCarryMoney) - sum(c.allPeriodCarryMoney)) as canCarryMoney,
					round((sum(c.subCost) - sum(c.cannotCarryMoney))/sum(c.subCost)*100 - sum(c.allPeriodCarryMoney)/sum(c.subCost)*100,2) as canCarryRate,
					sum(c.subCost) as subCost,c.hookStatus
				from
				(
					select
						c.id ,c.docCode ,c.docType ,c.isRed ,c.contractId ,c.contractName ,c.contractCode ,c.contractType,c.docStatus,c.auditDate,
						round(if(f.thisPeriodCarryRate is null,0,f.thisPeriodCarryRate)*i.subCost/100,2) as thisPeriodCarryMoney,
						round(if(f.beforePeriodCarryRate is null,0,f.beforePeriodCarryRate)*i.subCost/100,2) as beforePeriodCarryMoney,
						round(if(f.utilPeriodCarryRate is null,0,f.utilPeriodCarryRate)*i.subCost/100,2) as utilPeriodCarryMoney,
						round(if(f.allPeriodCarryRate is null,0,f.allPeriodCarryRate)*i.subCost/100,2) as allPeriodCarryMoney,
						round(if(g.cannotCarryRate is null,0,g.cannotCarryRate)*i.subCost/100,2) as cannotCarryMoney,
						i.subCost,f.hookStatus
					from
						oa_stock_outstock c
						inner join
						oa_stock_outstock_item i on c.id = i.mainId
						left join
						(
							select
								c.outStockDetailId,c.hookStatus,
								sum(if(date_format(c.thisDate,'%Y%m') = date_format('$sysDate','%Y%m'),c.carryRate,0)) as thisPeriodCarryRate,
								sum(if(date_format(c.thisDate,'%Y%m') < date_format('$sysDate','%Y%m'),c.carryRate,0)) as beforePeriodCarryRate,
								sum(if(date_format(c.thisDate,'%Y%m') <= date_format('$sysDate','%Y%m'),c.carryRate,0)) as utilPeriodCarryRate,
								sum(c.carryRate) as allPeriodCarryRate
							from
								oa_finance_carriedforward c
							where c.invoiceDetailId = $invoiceDetailId
							group by c.outStockDetailId,c.invoiceDetailId
						) f on f.outStockDetailId = i.id
						left join
						(
							select
								c.outStockDetailId,
								sum(ic.outstockDetailRate)/count(c.id) as cannotCarryRate
							from
								oa_finance_carriedforward c
								left join
								(select ic.outstockDetailId,sum(ic.carryRate) as outstockDetailRate
								from oa_finance_carriedforward ic  where  ic.invoiceDetailId <> $invoiceDetailId and ic.saleId = $saleId and ic.saleType = '$invioceType' group by ic.outstockId,ic.outstockDetailId) ic on c.outstockDetailId = ic.outstockdetailid
							where c.invoiceDetailId <> $invoiceDetailId and c.saleId = $saleId and c.saleType = '$invioceType'
							group by c.outStockDetailId
						) g on g.outStockDetailId = i.id
					where 1=1 and c.docType = 'CKSALES' and c.docStatus = 'YSH' and c.contractId = $saleId and c.contractType = '$outstockType' and date_format(c.auditDate,'%Y%m') <= date_format('$sysDate','%Y%m')
					order by c.auditDate,c.createTime
				) c
			group by c.id
			order by c.auditDate desc";
		return $this->_db->getArray($sql);
	}

	/**
	 * 获取已钩稽的出库单据id
	 */
	function getInvoiceHooked2_d($object,$dateArr,$contractIds = null){
		$hookDate =  $dateArr['thisYear'] .'-' .$dateArr['thisMonth'].'-01';  //财务日期
		$ids = 'none';
		$contractStr = empty($contractIds) ? "" : " and c.saleId in (" .$contractIds . ")";
		$sql = "select c.outStockId from ". $this->tbl_name . " c where DATE_FORMAT(hookDate,'%Y%m') <= DATE_FORMAT('$hookDate','%Y%m') $contractStr ";

		$this->searchArr = null;
		$this->sort = 'c.saleId';
		$this->groupBy = 'c.outStockId';
		$rows = $this->listBySql($sql);
		if(!empty($rows)){
			foreach($rows as $key => $val){
				if($ids != 'none'){
					$ids .= ','.$val['outStockId'] ;
				}else{
					$ids = $val['outStockId'];
				}
			}
		}
		return $ids;
	}

	/**
	 * 获取已钩稽出库单钩稽比例
	 */
	function getHookRate_d($object,$dateArr,$outstockIds = null){
		$sysDate =  $dateArr['thisYear'] .'-' .$dateArr['thisMonth'].'-01';  //财务日期
		$contractStr = empty($outstockIds) ? "" : " and c.outStockId in (" .$outstockIds . ")";
		$newArr = null;
		$sql = "select
					c.outStockId ,c.thisDate,c.thisYear,c.thisMonth,
					sum(c.carryRate) as carryRate,sum(if(DATE_FORMAT(c.hookDate,'%Y%m') < DATE_FORMAT('$sysDate','%Y%m'),c.carryRate,0)) as beforeCarry,sum(if(DATE_FORMAT(c.hookDate,'%Y%m') = DATE_FORMAT('$sysDate','%Y%m'),c.carryRate,0)) as thisCarry
				from oa_finance_carriedforward c where DATE_FORMAT(c.hookDate,'%Y%m') <= DATE_FORMAT('$sysDate','%Y%m') group by c.outStockId having carryRate <>  0 $contractStr ";
		$this->searchArr = null;
		$this->sort = 'c.saleId';
		$rows = $this->listBySql($sql);
		foreach($rows as $key => $val){
			$newArr[$val['outStockId']] = $val;
		}
		return $newArr;
	}

	/**
	 * 保存钩稽记录
	 */
	function carryInvoice2_d($object){
		$sysArr = array( "sysYear" => $object['sysYear'] ,"sysMonth" => $object['sysMonth'],
					"thisMonth" => $object['thisMonth'],"thisYear" => $object['thisYear']
		);
		try{
			$this->start_d();

//			if($object['thisYear'] != $object['sysYear'] || $object['thisMonth'] != $object['sysMonth']){
				//清除次月钩稽记录
				$this->deleteAfterHook2_d($object);
//			}

			//删除钩稽
			$this->invoiceUnHook2_d($object['delStr'],$sysArr);

			//新增钩稽
			$this->invoiceHook_d($object['newStr'],$sysArr);

			//修改钩稽
			$this->invoiceUpdateHook2_d($object['updateStr'],$sysArr);

			$this->commit_d();

			//获取钩稽
			return true;
//			return $this->getInvoiceHooked2_d($object,$sysArr);
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 清除次月钩稽记录
	 */
	function deleteAfterHook2_d($object){
		$sysYearMoneth =  $object['sysYear'] .$object['sysMonth'];  //财务日期
		if(!empty($object['delStr'])){
			foreach($object['delStr'] as $key => $val){
				$sql = "delete from ". $this->tbl_name . " where outStockId = $val[outStockId] and invoiceDetailId = $val[invoiceDetailId] and DATE_FORMAT(thisDate,'%Y%m') > '$sysYearMoneth' ";
				$this->_db->query($sql);
			}
		}

		if(!empty($object['newStr'])){
			foreach($object['newStr'] as $key => $val){
				$sql = "delete from ". $this->tbl_name . " where outStockId = $val[outStockId] and invoiceDetailId = $val[invoiceDetailId] and DATE_FORMAT(thisDate,'%Y%m') > '$sysYearMoneth' ";
				$this->_db->query($sql);
			}
		}

		if(!empty($object['updateStr'])){
			foreach($object['updateStr'] as $key => $val){
				$sql = "delete from ". $this->tbl_name . " where outStockId = $val[outStockId] and invoiceDetailId = $val[invoiceDetailId] and DATE_FORMAT(thisDate,'%Y%m') > '$sysYearMoneth' ";
				$this->_db->query($sql);
			}
		}
	}

	/**
	 * 删除钩稽
	 */
	function invoiceUnHook2_d($object,$sysArr){
		$thisYear = $sysArr['sysYear'] ;
		$thisMonth = $sysArr['sysMonth'] ;
		if($object){
			foreach($object as $key => $val){
				$this->delete(array('outStockId' => $val['outStockId'] ,'invoiceDetailId' => $val['invoiceDetailId'],
					'thisYear' => $thisYear,'thisMonth' =>$thisMonth
				));
			}
			return true;
		}
		return false;
	}

	/**
	 * 编辑钩稽
	 */
	function invoiceUpdateHook2_d($object,$sysArr){
		$thisYear = $sysArr['sysYear'] ;
		$thisMonth = $sysArr['sysMonth'] ;
		if($object){
			foreach($object as $key => $val){
				$conditionArr = array('outStockId' => $val['outStockId'],'invoiceDetailId' => $val['invoiceDetailId'],
					'thisYear' => $thisYear,'thisMonth' =>$thisMonth
				);

				$this->update($conditionArr,$val);
			}
			return true;
		}
		return false;
	}

	/**
	 * 出库单记录处理，将出库单数组专程出库单id串输出
	 */
	function outstockIdsDeal_d($outstockObj){
		$outstockArr = array();
		foreach($outstockObj as $key => $val){
			array_push($outstockArr,$val['id']);
		}
		return implode($outstockArr,',');
	}

	/**
	 * 获取出库单详细列表和钩稽关系
	 */
	function getOutstockDetailById_d($outstockId,$object){
		$invoiceDetailId = $object['invoiceDetailId'];
		if($this->hasHookInfo_d($object)){
			$sql = "select
						i.id as outstockDetailId,i.mainId as outstockId,i.productId,i.productName,i.productCode,i.subCost,i.pattern,ot.docCode as outstockCode,
						round(sum(if(c.carryRate is null,0,c.carryRate))) carryRate,round(sum(if(c.carryRate is null,0,c.carryRate))*i.subCost/100,2) as carryMoney,
						round(100 - if(cn.cannotCarryRate is null,0,cn.cannotCarryRate) - if(c.carryRate is null,0,c.carryRate)) as canCarryRate,
						cn.cannotCarryRate
					from
						oa_stock_outstock_item i

						inner join

						oa_stock_outstock ot on i.mainId = ot.id

						left join

						(select c.carryRate,c.outstockdetailId,c.outstockId,c.invoiceDetailId from oa_finance_carriedforward c where c.invoicedetailid = $invoiceDetailId) c on i.id = c.outstockdetailId

						left join

						(select
							c.outstockDetailId,sum(c.carryRate) as cannotCarryRate
						from
							oa_finance_carriedforward c
						where c.invoiceDetailId <> $invoiceDetailId and c.outstockId = $outstockId
						group by c.outstockDetailId ) cn on i.id = cn.outstockDetailId

					where i.mainId = $outstockId
					group by i.mainid,i.id";
		}else{
			$sql = "select
						i.id as outstockDetailId,i.mainId as outstockId,i.productId,i.productName,i.productCode,i.subCost,i.pattern,ot.docCode as outstockCode,
						0 carryRate, 0 as carryMoney,round( 100 - if(cn.cannotCarryRate is null,0,cn.cannotCarryRate)) as canCarryRate
					from
						oa_stock_outstock_item i

						inner join

						oa_stock_outstock ot on i.mainId = ot.id

						left join

						(select
							c.outstockDetailId,sum(c.carryRate) as cannotCarryRate
						from
							oa_finance_carriedforward c
						where c.invoiceDetailId <> $invoiceDetailId and c.outstockId = $outstockId
						group by c.outstockDetailId ) cn on i.id = cn.outstockDetailId
					where i.mainId = $outstockId
					group by i.mainid,i.id";
		}
		return $this->_db->getArray($sql);
	}

	/**
	 * 判断对应单据是否存在钩稽信息
	 */
	function hasHookInfo_d($object){
		$outstockId = $object['outstockId'];
		$invoiceDetailId = $object['invoiceDetailId'];
		$sql = "select id from oa_finance_carriedforward where outstockId = $outstockId and invoiceDetailId = $invoiceDetailId";
		$rows = $this->_db->getArray($sql);
		if(empty($rows)){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 详细钩稽
	 */
	function outstockDetailCarry_d($object){
		if(is_array($object)){
			$mainI = null;
			try{
				$this->start_d();

				//记录插入语句
				foreach($object as $key => $val){
					//如果数据无动作,则直接进入下次循环
					if($val['hookType'] == 0){
						continue;
					}

					if($val['hookType'] == 1){
						$val['hookDate'] = day_date;
						$val['outStockType'] = 'CKSALES';
						$val['periodNo'] = $val['thisYear'] . '.' .$val['thisMonth'];
						$val['thisDate'] = $val['thisYear'] . '-' .$val['thisMonth'] . '-01';
                        $val['hookStatus'] = 1;
						$this->add_d($val,true);
					}else if($val['hookType'] == 2){
						$this->update(array('outStockId' => $val['outStockId'],'invoiceDetailId' => $val['invoiceDetailId'],'outStockDetailId' => $val['outStockDetailId'],
							'thisYear' => $val['thisYear'],'thisMonth' =>$val['thisMonth']
						),$val);
					}else if($val['hookType'] == 3){
						$this->delete(array('outStockId' => $val['outStockId'] ,'invoiceDetailId' => $val['invoiceDetailId'],'outStockDetailId' => $val['outStockDetailId'],
							'thisYear' => $val['thisYear'],'thisMonth' =>$val['thisMonth']
						));
					}

					if(empty($mainI)){
						$mainI = $val['mainI'];
					}
				}
				$this->commit_d();
				return $mainI;
			}catch(exception $e){
				$this->rollBack();
				return false;
			}
		}else{
			return false;
		}
	}

    /************************出库结转部分************************/

    /**
     * 获取出库结转记录
     */
    function getOutStockDetail_d($object){
		//加密类
		$md5ConfigDao=new model_common_securityUtil ( "stockout" );

		$thisYear = $object['thisYear'];
		$thisMonth = $object['thisMonth'];
		$customerId  = empty($object['customerId']) ? null : ' customerId='.$object['customerId'] . ' and  ' ;
    	$sql = "select
					o.id,o.docCode,o.docType,o.isRed,o.contractId,
					o.contractName,o.contractCode,o.customerId,o.customerName,o.auditDate,
					os.outMoney,orderV.contractMoney,orderV.invoiceType,invoice.allInoivceMoney,invoice.psTypeNames, (orderV.contractMoney - invoice.allInoivceMoney) as notInvoiceMoney
				from
						oa_stock_outstock o
					left join
						(select o.id,sum(it.subcost) as outMoney from oa_stock_outstock o left join oa_stock_outstock_item it on o.id = it.mainId where docType = 'CKSALES' and docStatus = 'YSH' and $customerId year(o.auditDate) = $thisYear and month(o.auditDate) = $thisMonth  group by o.id) os
						on o.id = os.id
					left join
						(select contractCode,customerId,customerName,contractMoney,invoiceType,id from oa_contract_contract where ExaStatus in ( '完成','变更审批中')) orderV
						on o.contractId = orderV.id
					left join
						(
							select group_concat(dataName) as psTypeNames , sum( detailMoney ) as allInoivceMoney ,objId,objType

							from (
									select i.objId,i.objType,dd.dataName,sum(d.softMoney + d.hardMoney + d.serviceMoney + d.repairMoney )  as detailMoney
									from
										oa_finance_invoice_detail d
									right join oa_finance_invoice i on i.id = d.invoiceId
									left join (select dataName,dataCode from oa_system_datadict where parentCode = 'CWCPLX') dd on d.productModel = dd.dataCode
							where i.objType = 'KPRK-12'
							group by i.objId,i.objType,d.psType
						) invD group by objId,objType ) invoice
						on o.contractId = invoice.objId
				where
					o.docType = 'CKSALES' and o.docStatus = 'YSH' and
					year(o.auditDate) = $thisYear and
					month(o.auditDate) = $thisMonth and
					o.contractId is not null and o.contractId <> 0 and o.contractId <> '' and
					$customerId
					o.contractId not in
					(select objId from oa_finance_invoice where objId <> 0 and year(invoiceTime) = $thisYear and month(invoiceTime) = $thisMonth group by objId,objType )";
		$this->searchArr = null;
		$this->sort = 'o.contractId,o.id';
		$this->asc = false;
	    $rows = $this->listBySql($sql);

		return $md5ConfigDao->md5Rows ( $rows );
	}

	/**
	 * 获取已钩稽的出库单据id
	 */
	function getHooked_d($object){
		$ids = 'none';
		$sql = "select outStockId from ". $this->tbl_name . " where invoiceId is null ";

		$this->searchArr = null;
		$this->sort = 'saleId';
		$this->groupBy = 'outStockId';
		$rows = $this->listBySql($sql);
		if(!empty($rows)){
			foreach($rows as $key => $val){
				if($ids != 'none'){
					$ids .= ','.$val['outStockId'];
				}else{
					$ids = $val['outStockId'];
				}
			}
		}
		return $ids;
	}

	/**
	 * 保存钩稽记录
	 */
	function carryOutStock_d($object){
		try{
			$this->start_d();

			//删除取消钩稽的数据
			$this->unHook_d($object['unDataStr']);
			unset($object['unDataStr']);

			//钩稽新数据
			$this->hook_d($object['dataStr']);
			unset($object['dataStr']);

			$this->commit_d();

			return $this->getHooked_d($object);
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 钩稽操作
	 */
	function hook_d($dataStr){
		$dateArr = $this->getPeriod_d();
		$periodNo = $dateArr['thisYear'].'.'.$dateArr['thisMonth']*1;
		if($dataStr){
			$sql = "insert into oa_finance_carriedforward(
				outStockId ,outStockCode ,outStockType ,saleId ,saleCode ,saleType ,rObjCode,thisDate ,createId ,createName,carryType,thisYear,thisMonth,hookDate,periodNo,outStockDetailId
				)
				select
				o.id as outStockId,o.docCode as outStockCode,o.docType as outStockType,
				o.contractId as saleId,o.contractCode as saleCode,'KPRK-12' as saleType,o.contractObjCode as rObjCode,'$dateArr[thisDate]' as thisData,'$_SESSION[USER_ID]' as createId,'$_SESSION[USERNAME]' as createName,1 as carryType,
				'$dateArr[thisYear]' as thisYear, '$dateArr[thisMonth]' as thisMonth, now() as hookDate,$periodNo as periodNo,i.id as outStockDetailId
			 	from
			    oa_stock_outstock o left join oa_stock_outstock_item i on o.id = i.mainId
			    where o.id in ( ".$dataStr." )";
			return $this->_db->query($sql);
		}
		return false;
	}

	/**
	 * 取消钩稽
	 */
	function unHook_d($unDataStr){
		if($unDataStr){
			$sql = "delete from ". $this->tbl_name . " where outStockId in ($unDataStr)";
			return $this->_db->query($sql);
		}
		return false;
	}


	/**
	 * 判断发票是否被结转
	 */
	function invoiceIsCarried_d($invoiceId){
		$this->searchArr = array('invoiceId' => $invoiceId);
		$rs = $this->list_d();
		return $rs;
	}
}
?>