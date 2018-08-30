<?php
/**
 * @author Administrator
 * @Date 2013年11月20日 星期三 9:24:09
 * @version 1.0
 * @description:外包立项整包分包表 Model层
 */
 class model_outsourcing_approval_projectRental  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_approval_projectrental";
		$this->sql_map = "outsourcing/approval/projectRentalSql.php";
		parent::__construct ();
	}

		/**
	 * 获取数据
	 */
	function getProjectRentalInfo_d($mainId){
		$this->searchArr = array('mainId' => $mainId);
		$this->sort = "parentName,sysNo";
		$this->asc = false;
		return $this->list_d();
	}

	/**
	 * 数据字典返回查询到的值
	 */
	function getOption_d($parentArr,$dataCode){
		if(is_array($parentArr)){
			foreach($parentArr as $val){
				if($dataCode == $val['dataCode']){
					$obj = $val;
					break;
				}
			}

		}
		return $obj;
	}
	/**
	 * 获取表格
	 */
	function getAddPage_d($projectId){
		//获取项目预算信息
		$esmbudgetDao=new model_engineering_budget_esmbudget();//获取项目预算信息
		$esmbudgetRows=$esmbudgetDao->getAllBudgetDetail_d($projectId);
		return $this->getAddPageTemplate_d($esmbudgetRows);
	}

	/**
	 * 获取新增页面 - 有模板
	 */
	function getAddPageTemplate_d($outtemplateObj){
		$str = "";
        $tdStr = null;

        $datadictDao = new model_system_datadict_datadict();
		foreach($outtemplateObj as $key => $val){
				$moneyStr =<<<EOT
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier1][price]" id="supplier1_price$key" onblur="countDetail($key,1);" class="rimless_textB formatMoney" style="width:85px;" value="{$val['price']}"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier1][number]" id="supplier1_number$key" onblur="countDetail($key,1);" class="rimless_textB" style="width:35px;" value="{$val['numberOne']}"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier1][period]" id="supplier1_period$key" onblur="countDetail($key,1);" class="rimless_textB" style="width:35px;" value="{$val['numberTwo']}"></td>
	                <td class="amountTd"><input name="basic[projectRental][$key][supplier1][amount]" id="supplier1_amount$key" class="rimless_textB formatMoney" style="width:65px;" value="{$val['amount']}"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier2][price]" id="supplier2_price$key" onblur="countDetail($key,2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier2][number]" id="supplier2_number$key" onblur="countDetail($key,2);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier2][period]" id="supplier2_period$key" onblur="countDetail($key,2);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="basic[projectRental][$key][supplier2][amount]" id="supplier2_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier3][price]" id="supplier3_price$key" onblur="countDetail($key,3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier3][number]" id="supplier3_number$key" onblur="countDetail($key,3);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier3][period]" id="supplier3_period$key" onblur="countDetail($key,3);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="basic[projectRental][$key][supplier3][amount]" id="supplier3_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier4][price]" id="supplier4_price$key" onblur="countDetail($key,4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier4][number]" id="supplier4_number$key" onblur="countDetail($key,4);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="basic[projectRental][$key][supplier4][period]" id="supplier4_period$key" onblur="countDetail($key,4);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="basic[projectRental][$key][supplier4][amount]" id="supplier4_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td>
	                	<input name="basic[projectRental][$key][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$key][isDetail]" id="isDetail$key" value="1"/>
					</td>
EOT;

			//根据扩展字段3判断此费用是否为管理费用
			$isServerCost =  0;
			$isServerCostStr =<<<EOT
				<input type="hidden" name="basic[projectRental][$key][isServerCost]" id="isServerCost$key" value="$isServerCost"/>
EOT;
			if($val['budgetType']=='budgetEqu'){
				$val['parentName']='设备费用';
				$val['parentId']='';
			}
			$tdStr.=<<<EOT
                <tr id="tr$key" rowNum="$key">
                    <td><img src="images/removeline.png" onclick="delProjectRentalRow($key);" title="删除行"/>$isServerCostStr</td>
                    <td><input name="basic[projectRental][$key][parentName]" id="parent$key" value="{$val['parentName']}" style="width:55px;" class="rimless_textB"  title="{$val['parentName']}"/>
						<input type="hidden" name="basic[projectRental][$key][parentId]" id="parentId$key" value="{$val['parentId']}"/>
					</td>
                    <td>
						<input name="basic[projectRental][$key][costType]" id="costTypeName$key" value="{$val['budgetName']}" class="rimless_textB" style="width:65px;"  title="{$val['budgetName']}"/>
						<input type="hidden" name="basic[projectRental][$key][costTypeId]" id="costTypeId$key" value="{$val['budgetId']}"/>
						<input type="hidden" name="basic[projectRental][$key][isCustom]" id="isCustom$key" value="1"/>
					</td>
                    $moneyStr
                </tr>
EOT;
		}

		//公用行：管理费用，其他费用
		$ofKey=$key+1;
		$mfKey=$key+2;
		$tfKey=$key+3;
		$imKey=$key+4;
		$key5=$key+5;
		$key6=$key+6;
		$key7=$key+7;

        $taxArr = $datadictDao->getDatadictsByParentCodes('WBZZSD');
        $taxOptionStr = $this->getDatadictsStr($taxArr['WBZZSD']);
		$otherStr.=<<<EOT
                <tr id="appendHtml">
                    <td  colspan="2"><input type="hidden" name="basic[projectRental][$ofKey][parentName]" id="" value="其他费用" style="width:55px;" class="rimless_textB" readonly  title="其他费用"/>
						<input type="hidden" name="basic[projectRental][$ofKey][parentId]" id="parentId$ofKey" value=""/><b>其他费用</b>
					</td>
                    <td>
						<input type="hidden" name="basic[projectRental][$ofKey][costType]" id="" value="其他费用" class="rimless_textB" style="width:65px;" readonly title="其他费用"/>
						<input type="hidden" name="basic[projectRental][$ofKey][costTypeId]" id="costTypeId$ofKey" value=""/>
						<input type="hidden" name="basic[projectRental][$ofKey][isOtherFee]" id="isOtherFee$ofKey" value="1"/><b>其他费用</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier1][amount]" id="otherfee1" onblur="countProjectCost(1);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier2][amount]" id="otherfee2" onblur="countProjectCost(2);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier3][amount]" id="otherfee3" onblur="countProjectCost(3);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier4][amount]" id="otherfee4" onblur="countProjectCost(4);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$ofKey][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$ofKey][isDetail]" id="isDetail$ofKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td  colspan="2"><input type="hidden" name="basic[projectRental][$mfKey][parentName]" id="" value="管理费用" style="width:55px;" class="rimless_textB" readonly  title="管理费用"/>
						<input type="hidden" name="basic[projectRental][$mfKey][parentId]" id="parentId$mfKey" value=""/><b>管理费用</b>
					</td>
                    <td>
						<input type="hidden" name="basic[projectRental][$mfKey][costType]" id="" value="管理费用" class="rimless_textB" style="width:65px;" readonly title="管理费用"/>
						<input type="hidden" name="basic[projectRental][$mfKey][costTypeId]" id="costTypeId$mfKey" value=""/>
						<input type="hidden" name="basic[projectRental][$mfKey][isManageFee]" id="isManageFee$mfKey" value="1"/><b>管理费用</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$mfKey][supplier1][amount]" id="mangerfee1" onblur="countProjectCost(1);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="" id="mangerfee2" readonly class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="" id="mangerfee3" readonly class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="" id="mangerfee4" readonly class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$mfKey][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$mfKey][isDetail]" id="isDetail$mfKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="hidden" name="basic[projectRental][$tfKey][parentName]" id="" value="项目总成本" style="width:55px;" class="rimless_textB" readonly  title="项目总成本"/>
						<input type="hidden" name="basic[projectRental][$tfKey][parentId]" id="parentId$tfKey" value=""/>
						<input type="hidden" name="basic[projectRental][$tfKey][costType]" id="" value="项目总成本" class="rimless_textB" style="width:65px;" readonly title="项目总成本"/>
						<input type="hidden" name="basic[projectRental][$tfKey][costTypeId]" id="costTypeId$tfKey" value=""/>
						<input type="hidden" name="basic[projectRental][$tfKey][isAllCost]" id="" value="1"/><b>项目总成本</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier1][amount]" readonly id="isAllCost1" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier2][amount]" readonly id="isAllCost2" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier3][amount]" readonly id="isAllCost3" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier4][amount]" readonly id="isAllCost4" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$tfKey][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$tfKey][isDetail]" id="isDetail$tfKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="hidden" name="basic[projectRental][$imKey][parentName]" id="" value="项目毛利率" style="width:55px;" class="rimless_textB" readonly  title="项目毛利率"/>
						<input type="hidden" name="basic[projectRental][$imKey][parentId]" id="parentId$imKey" value=""/>
						<input type="hidden" name="basic[projectRental][$imKey][costType]" id="" value="项目毛利率" class="rimless_textB" style="width:65px;" readonly title="项目毛利率"/>
						<input type="hidden" name="basic[projectRental][$imKey][costTypeId]" id="costTypeId$imKey" value=""/>
						<input type="hidden" name="basic[projectRental][$imKey][isProfit]" id="" value="1"/><b>项目毛利率</b>
					</td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier1][amount]" id="isProfit1" readonly class="rimless_textB" style="width:230px;"></td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier2][amount]" id="isProfit2" readonly class="rimless_textB" style="width:230px;"></td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier3][amount]" id="isProfit3" readonly class="rimless_textB" style="width:230px;"></td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier4][amount]" id="isProfit4" readonly class="rimless_textB" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$imKey][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$imKey][isDetail]" id="isDetail$imKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2"><input type="hidden"  name="basic[projectRental][$key5][parentName]" id="" value="税费" style="width:55px;" class="rimless_textB" readonly  title="税费"/>
						<input type="hidden" name="basic[projectRental][$key5][parentId]" id="parentId$key5" value=""/><b>税费</b>
					</td>
					<td>
						<input type="hidden" name="basic[projectRental][$key5][costType]" id="" value="增值税专用发票税点" class="rimless_textB" style="width:65px;" readonly title="增值税专用发票税点"/>
						<input type="hidden" name="basic[projectRental][$key5][costTypeId]" id="costTypeId$key5" value=""/>
						<input type="hidden" name="basic[projectRental][$key5][isPoint]" id="" value="1"/><b>增值税专用发票税点</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key5][supplier1][amount]" id="isPoint1" readonly value="6%" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><select name="basic[projectRental][$key5][supplier2][amount]" id="isPoint2" onchange="countDLTax(2);" class="rimless_textB" style="width:230px;"><option value=""></option>$taxOptionStr</select></td>
	                <td class="amountTd" colspan="4"><select name="basic[projectRental][$key5][supplier3][amount]" id="isPoint3" onchange="countDLTax(3);" class="rimless_textB" style="width:230px;"><option value=""></option>$taxOptionStr</select></td>
	                <td class="amountTd" colspan="4"><select name="basic[projectRental][$key5][supplier4][amount]" id="isPoint4" onchange="countDLTax(4);" class="rimless_textB" style="width:230px;"><option value=""></option>$taxOptionStr</select></td>
	                <td>
	                	<input name="basic[projectRental][$key5][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$key5][isDetail]" id="isDetail$key5" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td ><input type="hidden"  name="basic[projectRental][$key6][parentName]" id="" value="税费" style="width:55px;" class="rimless_textB" readonly  title="税费"/>
						<input type="hidden" name="basic[projectRental][$key6][parentId]" id="parentId$key6" value=""/>
						<input type="hidden" name="basic[projectRental][$key6][costType]" id="" value="税费" class="rimless_textB" style="width:65px;" readonly title="税费"/>
						<input type="hidden" name="basic[projectRental][$key6][costTypeId]" id="costTypeId$key6" value=""/>
						<input type="hidden" name="basic[projectRental][$key6][isTax]" id="" value="1"/><b>税费</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier1][amount]" readonly  id="isTax1" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier2][amount]" readonly  id="isTax2" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier3][amount]" readonly  id="isTax3" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier4][amount]" readonly  id="isTax4" class="rimless_textB" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$key6][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$key6][isDetail]" id="isDetail$key6" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="hidden" name="basic[projectRental][$key7][parentName]" id="" value="项目净利率" style="width:55px;" class="rimless_textB" readonly  title="项目净利率"/>
						<input type="hidden" name="basic[projectRental][$key7][parentId]" id="parentId$key7" value=""/>
						<input type="hidden" name="basic[projectRental][$key7][costType]" id="" value="项目净利率" class="rimless_textB" style="width:65px;" readonly title="项目净利率"/>
						<input type="hidden" name="basic[projectRental][$key7][costTypeId]" id="costTypeId$key7" value=""/>
						<input type="hidden" name="basic[projectRental][$key7][isNetProfit]" id="" value="1"/><b>项目净利率</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier1][amount]" readonly id="isNetProfit1" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier2][amount]" readonly  id="isNetProfit2" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier3][amount]" readonly  id="isNetProfit3" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier4][amount]" readonly id="isNetProfit4" class="rimless_textB" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$key7][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="basic[projectRental][$key7][isDetail]" id="isDetail$key7" value="0"/>
	            	</td>
                </tr>
EOT;

		$projectRentalRowNum=$key7+1;

		//列表
		$str =<<<EOT
			<table class="form_in_table">
				<thead>
					<tr class="main_tr_header">
	                    <th rowspan="2">
	                        <input type="hidden" id="projectRentalRowNum" value="$projectRentalRowNum"/>
	                        <img src="images/add_item.png" alt="#TB_inline?height=450&width=750&inlineId=costTypeInner" onclick="selectCostType2();" title="添加行"/>
	                    </th>
						<th rowspan="2">费用大类</th>
						<th rowspan="2">费用小类</th>
						<th colspan="4">鼎利服务线<input type="hidden" id="supplier1" name="basic[projectRental][supplier][supplier1]" value="服务线"/></th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio2" onclick="checkSupp(2);" name="basic[projectRental][supplier][checkSupplier]" value="2"/>
							<input type="hidden" id="supp2" name="basic[projectRental][supplier][supplier2]" class="rimless_textB"/>
							<input id="supplier2" name="basic[projectRental][supplier2][suppName]" class="txt"/>
							<input type="hidden" id="supplierId2" name="basic[projectRental][supplier2][suppId]" class=""/>
							<input type="hidden" id="supplierCode2" name="basic[projectRental][supplier2][suppCode]" class=""/>
						</th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio3"  onclick="checkSupp(3);" name="basic[projectRental][supplier][checkSupplier]" value="3"/>
							<input type="hidden" id="supp3" name="basic[projectRental][supplier][supplier3]" class="rimless_textB"/>
							<input id="supplier3" name="basic[projectRental][supplier3][suppName]" class="txt"/>
							<input type="hidden" id="supplierId3" name="basic[projectRental][supplier3][suppId]" class="rimless_textB"/>
							<input type="hidden" id="supplierCode3" name="basic[projectRental][supplier3][suppCode]" class="rimless_textB"/>
						</th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio4"  onclick="checkSupp(4);" name="basic[projectRental][supplier][checkSupplier]" value="4"/>
							<input type="hidden" id="supp4" name="basic[projectRental][supplier][supplier4]" class="rimless_textB"/>
							<input id="supplier4" name="basic[projectRental][supplier4][suppName]" class="txt"/>
							<input type="hidden" id="supplierId4" name="basic[projectRental][supplier4][suppId]" class="rimless_textB"/>
							<input type="hidden" id="supplierCode4" name="basic[projectRental][supplier4][suppCode]" class="rimless_textB"/>
						</th>
						<th rowspan="2">备注</th>
					</tr>
					<tr class="main_tr_header">
						<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
						<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
						<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
						<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
					</tr>
				</thead>
				<tbody id="projectRentalTbody">
					$tdStr
					$otherStr
                </tbody>
			</table>
EOT;
		return $str;
	}
	/**
	 * 获取编辑页面
	 */
	function getEditPage_d($mainId){
		$str = "";
		$projectRentalInfo = $this->getProjectRentalInfo_d($mainId);
//		echo "<pre>";
		if($projectRentalInfo){
			//反格式化
			$projectRentalInfo = $this->dataUnFormat_d($projectRentalInfo);
//			print_r($projectRentalInfo);

			//取出供应商
			$supplierArr = $projectRentalInfo['supplier'];
			unset($projectRentalInfo['supplier']);
			//取出供应商信息
			$supplierinfo = $projectRentalInfo['supplierinfo'];
			unset($projectRentalInfo['supplierinfo']);
			//取出分组信息
			$costTypeGroup = $projectRentalInfo['costTypeGroup'];
			unset($projectRentalInfo['costTypeGroup']);

			$tdStr = "";
			$i = 0;
			$costTypeMark = "";//父级项目记录
			$serviceCostMark = 0;
		    //获取第一个类型
		    $datadictDao = new model_system_datadict_datadict();
			foreach($projectRentalInfo as $val){
				//根据扩展字段3判断此费用是否为管理费用
				$isServerCost = $val['supplier1']['isServerCost'] == "1" ? 1 : 0;
				$isServerCostStr =<<<EOT
					<input type="hidden" name="basic[projectRental][$i][isServerCost]" id="isServerCost$i" value="$isServerCost"/>
					<input type="hidden" name="basic[projectRental][$i][supplier1][id]" id="supplier1_id$i" value="{$val['supplier1']['id']}"/>
					<input type="hidden" name="basic[projectRental][$i][supplier2][id]" id="supplier2_id$i" value="{$val['supplier2']['id']}"/>
					<input type="hidden" name="basic[projectRental][$i][supplier3][id]" id="supplier3_id$i" value="{$val['supplier3']['id']}"/>
					<input type="hidden" name="basic[projectRental][$i][supplier4][id]" id="supplier4_id$i" value="{$val['supplier4']['id']}"/>
EOT;

				//表格内容
				if($val['supplier1']['isDetail'] == "1"){
					$tdStr .=<<<EOT
                    	<tr id="tr$i" rowNum="$i">
                        	<td><img src="images/removeline.png" onclick="delProjectRentalRow($i);" title="删除行"/>$isServerCostStr</td>
		                    <td><input name="basic[projectRental][$i][parentName]" id="parent$i" value="{$val['supplier1']['parentName']}" style="width:55px;" class="rimless_textB"  title="{$val['supplier1']['parentName']}"/>
								<input type="hidden" name="basic[projectRental][$i][parentId]" id="parentId$i" value="{$val['supplier1']['parentId']}"/>
							</td>
		                    <td>
								<input name="basic[projectRental][$i][costType]" id="costTypeName$key" value="{$val['supplier1']['costType']}" class="rimless_textB" style="width:65px;"  title="{$val['supplier1']['costType']}"/>
								<input type="hidden" name="basic[projectRental][$i][costTypeId]" id="costTypeId$i" value="{$val['supplier1']['costTypeId']}"/>
							</td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier1][price]" id="supplier1_price$i" onblur="countDetail($i,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier1']['price']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier1][number]" id="supplier1_number$i" onblur="countDetail($i,1);" class="rimless_textB" style="width:35px;" value="{$val['supplier1']['number']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier1][period]" id="supplier1_period$i" onblur="countDetail($i,1);" class="rimless_textB" style="width:35px;" value="{$val['supplier1']['period']}"/></td>
			                <td class="amountTd"><input name="basic[projectRental][$i][supplier1][amount]" id="supplier1_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier1']['amount']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier2][price]" id="supplier2_price$i" onblur="countDetail($i,2,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier2']['price']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier2][number]" id="supplier2_number$i" onblur="countDetail($i,2);" class="rimless_textB" style="width:35px;" value="{$val['supplier2']['number']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier2][period]" id="supplier2_period$i" onblur="countDetail($i,2);" class="rimless_textB" style="width:35px;" value="{$val['supplier2']['period']}"/></td>
			                <td class="amountTd"><input name="basic[projectRental][$i][supplier2][amount]" id="supplier2_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier2']['amount']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier3][price]" id="supplier3_price$i" onblur="countDetail($i,3,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier3']['price']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier3][number]" id="supplier3_number$i" onblur="countDetail($i,3);" class="rimless_textB" style="width:35px;" value="{$val['supplier3']['number']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier3][period]" id="supplier3_period$i" onblur="countDetail($i,3);" class="rimless_textB" style="width:35px;" value="{$val['supplier3']['period']}"/></td>
			                <td class="amountTd"><input name="basic[projectRental][$i][supplier3][amount]" id="supplier3_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier3']['amount']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier4][price]" id="supplier4_price$i" onblur="countDetail($i,4,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier4']['price']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier4][number]" id="supplier4_number$i" onblur="countDetail($i,4);" class="rimless_textB" style="width:35px;" value="{$val['supplier4']['number']}"/></td>
			                <td class="detailTd"><input name="basic[projectRental][$i][supplier4][period]" id="supplier4_period$i" onblur="countDetail($i,4);" class="rimless_textB" style="width:35px;" value="{$val['supplier4']['period']}"/></td>
			                <td class="amountTd"><input name="basic[projectRental][$i][supplier4][amount]" id="supplier4_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier4']['amount']}"/></td>
			                <td>
			                	<input name="basic[projectRental][$i][remark]" class="rimless_textB" style="width:80px;" value="{$val['supplier1']['remark']}"/>
								<input type="hidden" name="basic[projectRental][$i][isDetail]" id="isDetail$i" value="1"/>
			            	</td>
			            </tr>
EOT;

				$i++;
				}else{
					if($val['supplier1']['isOtherFee']==1){
						$isOtherFee=$val;
					}
					if($val['supplier1']['isManageFee']==1){
						$isManageFee=$val;
					}
					if($val['supplier1']['isAllCost']==1){
						$isAllCost=$val;
					}
					if($val['supplier1']['isProfit']==1){
						$isProfit=$val;
					}
					if($val['supplier1']['isPoint']==1){
						$isPoint=$val;
					}
					if($val['supplier1']['isTax']==1){
						$isTax=$val;
					}
					if($val['supplier1']['isNetProfit']==1){
						$isNetProfit=$val;
					}
				}
			}
			//公用行：管理费用，其他费用
			$ofKey=$i+1;
			$mfKey=$i+2;
			$tfKey=$i+3;
			$imKey=$i+4;
			$key5=$i+5;
			$key6=$i+6;
			$key7=$i+7;

			//项目毛利率
			$isProfit2=isset($isProfit['supplier2']['amount'])?$isProfit['supplier2']['amount'].'%':'';
			$isProfit3=isset($isProfit['supplier3']['amount'])?$isProfit['supplier3']['amount'].'%':'';
			$isProfit4=isset($isProfit['supplier4']['amount'])?$isProfit['supplier4']['amount'].'%':'';

			//项目净利率
			$isNetProfit2=isset($isNetProfit['supplier2']['amount'])?$isNetProfit['supplier2']['amount'].'%':'';
			$isNetProfit3=isset($isNetProfit['supplier3']['amount'])?$isNetProfit['supplier3']['amount'].'%':'';
			$isNetProfit4=isset($isNetProfit['supplier4']['amount'])?$isNetProfit['supplier4']['amount'].'%':'';

	        $taxArr = $datadictDao->getDatadictsByParentCodes('WBZZSD');
	        $taxOptionStr2 = $this->getDatadictsStr($taxArr['WBZZSD'],$isPoint['supplier2']['amount']);
	        $taxOptionStr3 = $this->getDatadictsStr($taxArr['WBZZSD'],$isPoint['supplier3']['amount']);
	        $taxOptionStr4 = $this->getDatadictsStr($taxArr['WBZZSD'],$isPoint['supplier4']['amount']);
			$otherStr.=<<<EOT
                <tr id="appendHtml">
                    <td  colspan="2"><input type="hidden" name="basic[projectRental][$ofKey][parentName]" id="" value="{$isOtherFee['supplier1']['parentName']}" style="width:55px;" class="rimless_textB" readonly  title="其他费用"/>
						<input type="hidden" name="basic[projectRental][$ofKey][parentId]" id="parentId$ofKey" value="{$isOtherFee['supplier1']['parentId']}"/><b>其他费用</b>
						<input type="hidden" name="basic[projectRental][$ofKey][supplier1][id]" id="supplier1_id$ofKey" value="{$isOtherFee['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$ofKey][supplier2][id]" id="supplier2_id$ofKey" value="{$isOtherFee['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$ofKey][supplier3][id]" id="supplier3_id$ofKey" value="{$isOtherFee['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$ofKey][supplier4][id]" id="supplier4_id$ofKey" value="{$isOtherFee['supplier4']['id']}"/>
					</td>
                    <td>
						<input type="hidden" name="basic[projectRental][$ofKey][costType]" id="" value="{$isOtherFee['supplier1']['costType']}" class="rimless_textB" style="width:65px;" readonly title="其他费用"/>
						<input type="hidden" name="basic[projectRental][$ofKey][costTypeId]" id="costTypeId$ofKey" value="{$isOtherFee['supplier1']['costTypeId']}"/>
						<input type="hidden" name="basic[projectRental][$ofKey][isOtherFee]" id="isOtherFee$ofKey" value="1"/><b>其他费用</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier1][amount]" id="otherfee1" value="{$isOtherFee['supplier1']['amount']}" onblur="countProjectCost(1);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier2][amount]" id="otherfee2" value="{$isOtherFee['supplier2']['amount']}" onblur="countProjectCost(2);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier3][amount]" id="otherfee3" value="{$isOtherFee['supplier3']['amount']}" onblur="countProjectCost(3);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$ofKey][supplier4][amount]" id="otherfee4" value="{$isOtherFee['supplier4']['amount']}" onblur="countProjectCost(4);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$ofKey][remark]" class="rimless_textB" style="width:80px;" value="{$isOtherFee['supplier1']['remark']}" />
						<input type="hidden" name="basic[projectRental][$ofKey][isDetail]" id="isDetail$ofKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td  colspan="2"><input type="hidden" name="basic[projectRental][$mfKey][parentName]" id="" value="{$isManageFee['supplier1']['parentName']}" style="width:55px;" class="rimless_textB" readonly  title="管理费用"/>
						<input type="hidden" name="basic[projectRental][$mfKey][parentId]" id="parentId$mfKey" value="{$isManageFee['supplier1']['parentId']}"/><b>管理费用</b>
						<input type="hidden" name="basic[projectRental][$mfKey][supplier1][id]" id="supplier1_id$mfKey" value="{$isManageFee['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$mfKey][supplier2][id]" id="supplier2_id$mfKey" value="{$isManageFee['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$mfKey][supplier3][id]" id="supplier3_id$mfKey" value="{$isManageFee['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$mfKey][supplier4][id]" id="supplier4_id$mfKey" value="{$isManageFee['supplier4']['id']}"/>
					</td>
                    <td>
						<input type="hidden" name="basic[projectRental][$mfKey][costType]" id="" value="{$isManageFee['supplier1']['costType']}" class="rimless_textB" style="width:65px;" readonly title="管理费用"/>
						<input type="hidden" name="basic[projectRental][$mfKey][costTypeId]" id="costTypeId$mfKey" value=""/>
						<input type="hidden" name="basic[projectRental][$mfKey][isManageFee]" id="isManageFee$mfKey" value="1"/><b>管理费用</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$mfKey][supplier1][amount]" value="{$isManageFee['supplier1']['amount']}" id="mangerfee1" onblur="countProjectCost(1);" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="" id="mangerfee2" readonly class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="" id="mangerfee3" readonly class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="" id="mangerfee4" readonly class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$mfKey][remark]" class="rimless_textB" style="width:80px;" value="{$isManageFee['supplier1']['remark']}" />
						<input type="hidden" name="basic[projectRental][$mfKey][isDetail]" id="isDetail$mfKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="hidden" name="basic[projectRental][$tfKey][parentName]" id="" value="{$isAllCost['supplier1']['parentName']}" style="width:55px;" class="rimless_textB" readonly  title="项目总成本"/>
						<input type="hidden" name="basic[projectRental][$tfKey][parentId]" id="parentId$tfKey" value=""/>
						<input type="hidden" name="basic[projectRental][$tfKey][costType]" id="" value="{$isAllCost['supplier1']['costType']}" class="rimless_textB" style="width:65px;" readonly title="项目总成本"/>
						<input type="hidden" name="basic[projectRental][$tfKey][costTypeId]" id="costTypeId$tfKey" value=""/>
						<input type="hidden" name="basic[projectRental][$tfKey][isAllCost]" id="" value="1"/><b>项目总成本</b>
						<input type="hidden" name="basic[projectRental][$tfKey][supplier1][id]" id="supplier1_id$tfKey" value="{$isAllCost['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$tfKey][supplier2][id]" id="supplier2_id$tfKey" value="{$isAllCost['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$tfKey][supplier3][id]" id="supplier3_id$tfKey" value="{$isAllCost['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$tfKey][supplier4][id]" id="supplier4_id$tfKey" value="{$isAllCost['supplier4']['id']}"/>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier1][amount]" readonly id="isAllCost1" value="{$isAllCost['supplier1']['amount']}" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier2][amount]" readonly id="isAllCost2" value="{$isAllCost['supplier2']['amount']}" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier3][amount]" readonly id="isAllCost3" value="{$isAllCost['supplier3']['amount']}" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$tfKey][supplier4][amount]" readonly id="isAllCost4" value="{$isAllCost['supplier4']['amount']}" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$tfKey][remark]" class="rimless_textB" style="width:80px;" value="{$isAllCost['supplier1']['remark']}" />
						<input type="hidden" name="basic[projectRental][$tfKey][isDetail]" id="isDetail$tfKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="hidden" name="basic[projectRental][$imKey][parentName]" id="" value="项目毛利率" style="width:55px;" class="rimless_textB" readonly  title="项目毛利率"/>
						<input type="hidden" name="basic[projectRental][$imKey][parentId]" id="parentId$imKey" value=""/>
						<input type="hidden" name="basic[projectRental][$imKey][costType]" id="" value="项目毛利率" class="rimless_textB" style="width:65px;" readonly title="项目毛利率"/>
						<input type="hidden" name="basic[projectRental][$imKey][costTypeId]" id="costTypeId$imKey" value=""/>
						<input type="hidden" name="basic[projectRental][$imKey][isProfit]" id="" value="1"/><b>项目毛利率</b>
						<input type="hidden" name="basic[projectRental][$imKey][supplier1][id]" id="supplier1_id$imKey" value="{$isProfit['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$imKey][supplier2][id]" id="supplier2_id$imKey" value="{$isProfit['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$imKey][supplier3][id]" id="supplier3_id$imKey" value="{$isProfit['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$imKey][supplier4][id]" id="supplier4_id$imKey" value="{$isProfit['supplier4']['id']}"/>
					</td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier1][amount]" id="isProfit1" value="{$isProfit['supplier1']['amount']}%" readonly class="rimless_textB" style="width:230px;"></td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier2][amount]" id="isProfit2" value="$isProfit2" readonly class="rimless_textB" style="width:230px;"></td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier3][amount]" id="isProfit3" value="$isProfit3" readonly class="rimless_textB" style="width:230px;"></td>
	                <td class="" colspan="4"><input name="basic[projectRental][$imKey][supplier4][amount]" id="isProfit4" value="$isProfit4" readonly class="rimless_textB" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$imKey][remark]" class="rimless_textB" style="width:80px;" value="{$isProfit['supplier4']['remark']}"/>
						<input type="hidden" name="basic[projectRental][$imKey][isDetail]" id="isDetail$imKey" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="2"><input type="hidden"  name="basic[projectRental][$key5][parentName]" id="" value="税费" style="width:55px;" class="rimless_textB" readonly  title="税费"/>
						<input type="hidden" name="basic[projectRental][$key5][parentId]" id="parentId$key5" value=""/><b>税费</b>
						<input type="hidden" name="basic[projectRental][$key5][supplier1][id]" id="supplier1_id$key5" value="{$isPoint['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key5][supplier2][id]" id="supplier2_id$key5" value="{$isPoint['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key5][supplier3][id]" id="supplier3_id$key5" value="{$isPoint['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key5][supplier4][id]" id="supplier4_id$key5" value="{$isPoint['supplier4']['id']}"/>
					</td>
					<td>
						<input type="hidden" name="basic[projectRental][$key5][costType]" id="" value="增值税专用发票税点" class="rimless_textB" style="width:65px;" readonly title="增值税专用发票税点"/>
						<input type="hidden" name="basic[projectRental][$key5][costTypeId]" id="costTypeId$key5" value=""/>
						<input type="hidden" name="basic[projectRental][$key5][isPoint]" id="" value="1"/><b>增值税专用发票税点</b>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key5][supplier1][amount]" id="isPoint1" readonly value="6%" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><select name="basic[projectRental][$key5][supplier2][amount]" id="isPoint2" onchange="countDLTax(2);" class="rimless_textB" style="width:230px;"><option value=""></option>$taxOptionStr2</select></td>
	                <td class="amountTd" colspan="4"><select name="basic[projectRental][$key5][supplier3][amount]" id="isPoint3" onchange="countDLTax(3);" class="rimless_textB" style="width:230px;"><option value=""></option>$taxOptionStr3</select></td>
	                <td class="amountTd" colspan="4"><select name="basic[projectRental][$key5][supplier4][amount]" id="isPoint4" onchange="countDLTax(4);" class="rimless_textB" style="width:230px;"><option value=""></option>$taxOptionStr4</select></td>
	                <td>
	                	<input name="basic[projectRental][$key5][remark]" class="rimless_textB" style="width:80px;" value="{$isPoint['supplier1']['remark']}"/>
						<input type="hidden" name="basic[projectRental][$key5][isDetail]" id="isDetail$key5" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td ><input type="hidden"  name="basic[projectRental][$key6][parentName]" id="" value="税费" style="width:55px;" class="rimless_textB" readonly  title="税费"/>
						<input type="hidden" name="basic[projectRental][$key6][parentId]" id="parentId$key6" value=""/>
						<input type="hidden" name="basic[projectRental][$key6][costType]" id="" value="税费" class="rimless_textB" style="width:65px;" readonly title="税费"/>
						<input type="hidden" name="basic[projectRental][$key6][costTypeId]" id="costTypeId$key6" value=""/>
						<input type="hidden" name="basic[projectRental][$key6][isTax]" id="" value="1"/><b>税费</b>
						<input type="hidden" name="basic[projectRental][$key6][supplier1][id]" id="supplier1_id$key6" value="{$isTax['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key6][supplier2][id]" id="supplier2_id$key6" value="{$isTax['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key6][supplier3][id]" id="supplier3_id$key6" value="{$isTax['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key6][supplier4][id]" id="supplier4_id$key6" value="{$isTax['supplier4']['id']}"/>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier1][amount]" readonly  id="isTax1" value="{$isTax['supplier1']['amount']}" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier2][amount]" readonly  id="isTax2" value="{$isTax['supplier2']['amount']}" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier3][amount]" readonly  id="isTax3" value="{$isTax['supplier3']['amount']}" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key6][supplier4][amount]" readonly  id="isTax4" value="{$isTax['supplier4']['amount']}" class="rimless_textB" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$key6][remark]" class="rimless_textB" style="width:80px;" value="{$isTax['supplier1']['remark']}"/>
						<input type="hidden" name="basic[projectRental][$key6][isDetail]" id="isDetail$key6" value="0"/>
	            	</td>
                </tr>
                <tr>
                    <td colspan="3"><input type="hidden" name="basic[projectRental][$key7][parentName]" id="" value="项目净利率" style="width:55px;" class="rimless_textB" readonly  title="项目净利率"/>
						<input type="hidden" name="basic[projectRental][$key7][parentId]" id="parentId$key7" value=""/>
						<input type="hidden" name="basic[projectRental][$key7][costType]" id="" value="项目净利率" class="rimless_textB" style="width:65px;" readonly title="项目净利率"/>
						<input type="hidden" name="basic[projectRental][$key7][costTypeId]" id="costTypeId$key7" value=""/>
						<input type="hidden" name="basic[projectRental][$key7][isNetProfit]" id="" value="1"/><b>项目净利率</b>
						<input type="hidden" name="basic[projectRental][$key7][supplier1][id]" id="supplier1_id$key7" value="{$isNetProfit['supplier1']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key7][supplier2][id]" id="supplier2_id$key7" value="{$isNetProfit['supplier2']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key7][supplier3][id]" id="supplier3_id$key7" value="{$isNetProfit['supplier3']['id']}"/>
						<input type="hidden" name="basic[projectRental][$key7][supplier4][id]" id="supplier4_id$key7" value="{$isNetProfit['supplier4']['id']}"/>
					</td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier1][amount]" readonly id="isNetProfit1" value="{$isNetProfit['supplier1']['amount']}%" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier2][amount]" readonly  id="isNetProfit2" value="$isNetProfit2" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier3][amount]" readonly  id="isNetProfit3" value="$isNetProfit3" class="rimless_textB" style="width:230px;"></td>
	                <td class="amountTd" colspan="4"><input name="basic[projectRental][$key7][supplier4][amount]" readonly id="isNetProfit4" value="$isNetProfit4" class="rimless_textB" style="width:230px;"></td>
	                <td>
	                	<input name="basic[projectRental][$key7][remark]" class="rimless_textB" style="width:80px;" value="{$isNetProfit['supplier1']['remark']}"/>
						<input type="hidden" name="basic[projectRental][$key7][isDetail]" id="isDetail$key7" value="0"/>
	            	</td>
                </tr>
EOT;

			$projectRentalRowNum=$key7+1;

			//表格内容 - 包含表头
			$checked2 = $checked3 = $checked4 = '';
			switch ($supplierArr['checkSupplier']) {
				case 'supplier2': $checked2 = 'checked="checked"';break;
				case 'supplier3': $checked3 = 'checked="checked"';break;
				case 'supplier4': $checked4 = 'checked="checked"';break;
				default: break;
			}

			//列表
			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
		                    <th rowspan="2">
		                        <input type="hidden" id="projectRentalRowNum" value="$projectRentalRowNum"/>
	                        	<img src="images/add_item.png" alt="#TB_inline?height=450&width=750&inlineId=costTypeInner" onclick="selectCostType2();" title="添加行"/>
		                    </th>
							<th rowspan="2">费用大类</th>
							<th rowspan="2">费用小类</th>
							<th colspan="4">鼎利服务线<input type="hidden" id="supplier1" name="basic[projectRental][supplier][supplier1]" value="服务线"/></th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio2" onclick="checkSupp(2);" name="basic[projectRental][supplier][checkSupplier]" value="2" $checked2/>
								<input type="hidden" id="supp2" name="basic[projectRental][supplier][supplier2]" class="rimless_textB" value="{$supplierArr['supplier2']}"/>
								<input id="supplier2" name="basic[projectRental][supplier2][suppName]" value="{$supplierinfo[2]['suppName']}" class="txt"/>
								<input type="hidden" id="supplierId2" name="basic[projectRental][supplier2][suppId]" value="{$supplierinfo[2]['suppId']}" class=""/>
								<input type="hidden" id="supplierCode2" name="basic[projectRental][supplier2][suppCode]" value="{$supplierinfo[2]['suppCode']}" class=""/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio3" onclick="checkSupp(3);" name="basic[projectRental][supplier][checkSupplier]" value="3" $checked3/>
								<input type="hidden" id="supp3" name="basic[projectRental][supplier][supplier3]" class="rimless_textB" value="{$supplierArr['supplier3']}"/>
								<input id="supplier3" name="basic[projectRental][supplier3][suppName]" value="{$supplierinfo[3]['suppName']}" class="txt"/>
								<input type="hidden" id="supplierId3" name="basic[projectRental][supplier3][suppId]" value="{$supplierinfo[3]['suppId']}" class=""/>
								<input type="hidden" id="supplierCode3" name="basic[projectRental][supplier3][suppCode]" value="{$supplierinfo[3]['suppCode']}" class=""/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio4" onclick="checkSupp(4);" name="basic[projectRental][supplier][checkSupplier]" value="4" $checked4/>
								<input type="hidden" id="supp4" name="basic[projectRental][supplier][supplier4]" class="rimless_textB" value="{$supplierArr['supplier4']}"/>
								<input id="supplier4" name="basic[projectRental][supplier4][suppName]" value="{$supplierinfo[4]['suppName']}" class="txt"/>
								<input type="hidden" id="supplierId4" name="basic[projectRental][supplier4][suppId]" value="{$supplierinfo[4]['suppId']}" class=""/>
								<input type="hidden" id="supplierCode4" name="basic[projectRental][supplier4][suppCode]" value="{$supplierinfo[4]['suppCode']}" class=""/>
							</th>
							<th rowspan="2">备注</th>
						</tr>
						<tr class="main_tr_header">
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
						</tr>
					</thead>
					<tbody id="projectRentalTbody">
	                    $tdStr
	                    $otherStr
	                </tbody>
				</table>
EOT;
		}else{
			//列表
			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
		                    <th rowspan="2">
		                        <input type="hidden" id="projectRentalRowNum" value="8"/>
	                       		 <img src="images/add_item.png" alt="#TB_inline?height=450&width=750&inlineId=costTypeInner" onclick="selectCostType2();" title="添加行"/>
		                    </th>
							<th rowspan="2">费用大类</th>
							<th rowspan="2">费用小类</th>
							<th colspan="4">鼎利服务线<input type="hidden" id="supplier1" name="basic[projectRental][supplier][supplier1]" value="服务线"/></th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio2" onclick="checkSupp(2);" name="basic[projectRental][supplier][checkSupplier]" value="2" $checked2/>
								<input type="hidden" id="supp2" name="basic[projectRental][supplier][supplier2]" class="rimless_textB" value="{$supplierArr['supplier2']}"/>
								<input id="supplier2" name="basic[projectRental][supplier2][suppName]" value="{$supplierinfo[2]['suppName']}" class="txt"/>
								<input type="hidden" id="supplierId2" name="basic[projectRental][supplier2][suppId]" value="{$supplierinfo[2]['suppId']}" class=""/>
								<input type="hidden" id="supplierCode2" name="basic[projectRental][supplier2][suppCode]" value="{$supplierinfo[2]['suppCode']}" class=""/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio3" onclick="checkSupp(3);" name="basic[projectRental][supplier][checkSupplier]" value="3" $checked3/>
								<input type="hidden" id="supp3" name="basic[projectRental][supplier][supplier3]" class="rimless_textB" value="{$supplierArr['supplier3']}"/>
								<input id="supplier3" name="basic[projectRental][supplier3][suppName]" value="{$supplierinfo[3]['suppName']}" class="txt"/>
								<input type="hidden" id="supplierId3" name="basic[projectRental][supplier3][suppId]" value="{$supplierinfo[3]['suppId']}" class=""/>
								<input type="hidden" id="supplierCode3" name="basic[projectRental][supplier3][suppCode]" value="{$supplierinfo[3]['suppCode']}" class=""/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio4" onclick="checkSupp(4);" name="basic[projectRental][supplier][checkSupplier]" value="4" $checked4/>
								<input type="hidden" id="supp4" name="basic[projectRental][supplier][supplier4]" class="rimless_textB" value="{$supplierArr['supplier4']}"/>
								<input id="supplier4" name="basic[projectRental][supplier4][suppName]" value="{$supplierinfo[4]['suppName']}" class="txt"/>
								<input type="hidden" id="supplierId4" name="basic[projectRental][supplier4][suppId]" value="{$supplierinfo[4]['suppId']}" class=""/>
								<input type="hidden" id="supplierCode4" name="basic[projectRental][supplier4][suppCode]" value="{$supplierinfo[4]['suppCode']}" class=""/>
							</th>
							<th rowspan="2">备注</th>
						</tr>
						<tr class="main_tr_header">
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
						</tr>
					</thead>
					<tbody id="projectRentalTbody"></tbody>
				</table>
EOT;
		}
		return $str;
	}
		/**
	 * 查看页面渲染
	 */
	function getViewPage_d($mainId){
		$str = "";
		$projectRentalInfo = $this->getProjectRentalInfo_d($mainId);

		if($projectRentalInfo){
			//反格式化
			$projectRentalInfo =$this->dataUnFormat_d($projectRentalInfo);

			//取出供应商信息
			$supplierArr = $projectRentalInfo['supplier'];
			unset($projectRentalInfo['supplier']);
//			//取出分组信息
			$costTypeGroup = $projectRentalInfo['costTypeGroup'];
			unset($projectRentalInfo['costTypeGroup']);

			//表格内容 - 包含表头
			$supplier2 = $supplier3 = $supplier4 = '';
			switch ($supplierArr['checkSupplier']) {
				case 'supplier2': $supplier2 = 'font-weight:bold;';$appandInfo2 = '[选用]';$checkSupplier2 = $supplierArr[$supplierArr['checkSupplier']];break;
				case 'supplier3': $supplier3 = 'font-weight:bold;';$appandInfo3 = '[选用]';$checkSupplier3 = $supplierArr[$supplierArr['checkSupplier']];break;
				case 'supplier4': $supplier4 = 'font-weight:bold;';$appandInfo4 = '[选用]';$checkSupplier4 = $supplierArr[$supplierArr['checkSupplier']];break;
				default: break;
			}

			$tdStr = "";
			$i = 0;
			$costTypeMark = "";//父级项目记录
			$serviceCostMark = 0;
			foreach($projectRentalInfo as $val){
				//表头
				if($costTypeMark != $val['supplier1']['parentName']){
					$trClass = $i%2==0 ? 'tr_odd' : 'tr_even';
					$i++;
					$costTypeMark = $val['supplier1']['parentName'];
					$rowLength = $costTypeGroup[$val['supplier1']['parentName']]['rowLength'];
					$costTypeStr=<<<EOT
						<td style="text-align:center;" rowspan="$rowLength">{$val['supplier1']['parentName']}</td>
EOT;
				}else{
					$costTypeStr = "";
				}

				if($val['supplier1']['isDetail'] == "1"){
					$tdStr .=<<<EOT
						<tr class="$trClass">
		                	$costTypeStr
		                	<td style="text-align:center;">{$val['supplier1']['costType']}</td>
			                <td class="formatMoney" style="text-align:right;">{$val['supplier1']['price']}</td>
			                <td>{$val['supplier1']['number']}</td>
			                <td>{$val['supplier1']['period']}</td>
			                <td id="td_supplier1" class="formatMoney" style="text-align:right;">{$val['supplier1']['amount']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier2">{$val['supplier2']['price']}</td>
			                <td style="$supplier2">{$val['supplier2']['number']}</td>
			                <td style="$supplier2">{$val['supplier2']['period']}</td>
			                <td id="td_supplier2" class="formatMoney" style="text-align:right;$supplier2">{$val['supplier2']['amount']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier3">{$val['supplier3']['price']}</td>
			                <td style="$supplier3">{$val['supplier3']['number']}</td>
			                <td style="$supplier3">{$val['supplier3']['period']}</td>
			                <td id="td_supplier3" class="formatMoney" style="text-align:right;$supplier3">{$val['supplier3']['amount']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier4">{$val['supplier4']['price']}</td>
			                <td style="$supplier4">{$val['supplier4']['number']}</td>
			                <td style="$supplier4">{$val['supplier4']['period']}</td>
			                <td id="td_supplier4" class="formatMoney" style="text-align:right;$supplier4">{$val['supplier4']['amount']}</td>
			                <td style="text-align:left;">{$val['supplier1']['remark']}</td>
			            </tr>
EOT;
				}else{
					if($val['supplier1']['isOtherFee']==1){
						$isOtherFee=$val;
					}
					if($val['supplier1']['isManageFee']==1){
						$isManageFee=$val;
					}
					if($val['supplier1']['isAllCost']==1){
						$isAllCost=$val;
					}
					if($val['supplier1']['isProfit']==1){
						$isProfit=$val;
					}
					if($val['supplier1']['isPoint']==1){
						$isPoint=$val;
					}
					if($val['supplier1']['isTax']==1){
						$isTax=$val;
					}
					if($val['supplier1']['isNetProfit']==1){
						$isNetProfit=$val;
					}
				}
			}
			//项目毛利率
			$isProfit2=isset($isProfit['supplier2']['amount'])?$isProfit['supplier2']['amount'].'%':'';
			$isProfit3=isset($isProfit['supplier3']['amount'])?$isProfit['supplier3']['amount'].'%':'';
			$isProfit4=isset($isProfit['supplier4']['amount'])?$isProfit['supplier4']['amount'].'%':'';

			//增值税专用发票税点
			$isPoint2=isset($isPoint['supplier2']['amount'])?$isPoint['supplier2']['amount'].'%':'';
			$isPoint3=isset($isPoint['supplier3']['amount'])?$isPoint['supplier3']['amount'].'%':'';
			$isPoint4=isset($isPoint['supplier4']['amount'])?$isPoint['supplier4']['amount'].'%':'';

			//项目净利率
			$isNetProfit2=isset($isNetProfit['supplier2']['amount'])?$isNetProfit['supplier2']['amount'].'%':'';
			$isNetProfit3=isset($isNetProfit['supplier3']['amount'])?$isNetProfit['supplier3']['amount'].'%':'';
			$isNetProfit4=isset($isNetProfit['supplier4']['amount'])?$isNetProfit['supplier4']['amount'].'%':'';



			$otherStr.=<<<EOT
                <tr class="tr_odd">
                    <td><b>其他费用</b></td>
                    <td><b>其他费用</b></td>
	                <td id="td_supplier1" class="formatMoney" style="text-align:center;"colspan="4">{$isOtherFee['supplier1']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier2" colspan="4">{$isOtherFee['supplier2']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier3" colspan="4">{$isOtherFee['supplier3']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier4" colspan="4">{$isOtherFee['supplier4']['amount']}</td>
	                <td style="text-align:left;">{$isOtherFee['supplier1']['remark']}</td>
                </tr>
                <tr class="tr_even">
                    <td><b>管理费用</b></td>
                    <td><b>管理费用</b></td>
	                <td class="formatMoney" colspan="4">{$isManageFee['supplier1']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier2"  colspan="4"></td>
	                <td class="formatMoney" style="text-align:center;$supplier3"  colspan="4"></td>
	                <td class="formatMoney" style="text-align:center;$supplier4"  colspan="4"></td>
	                <td style="text-align:left;">{$isManageFee['supplier1']['remark']}</td>
                </tr>
                <tr class="tr_odd">
                    <td colspan="2"><b>项目总成本</b></td>
	                <td class="formatMoney" colspan="4">{$isAllCost['supplier1']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier2" colspan="4">{$isAllCost['supplier2']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier3" colspan="4">{$isAllCost['supplier3']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier4" colspan="4">{$isAllCost['supplier4']['amount']}</td>
	                <td style="text-align:left;">{$isAllCost['supplier1']['remark']}</td>
                </tr>
                <tr class="tr_even">
                    <td colspan="2"><b>项目毛利率</b>
					</td>
	                <td class="amountTd" colspan="4">{$isProfit['supplier1']['amount']}%</td>
	                <td class="amountTd" style="text-align:center;$supplier2"  colspan="4">{$isProfit2}</td>
	                <td class="amountTd" style="text-align:center;$supplier3"  colspan="4">{$isProfit3}</td>
	                <td class="amountTd" style="text-align:center;$supplier4"  colspan="4">{$isProfit4}</td>
	                <td style="text-align:left;">{$isProfit['supplier1']['remark']}</td>
                </tr>
                <tr class="tr_odd">
                    <td  rowspan="2"><b>税费</b>
					</td>
					<td><b>增值税专用发票税点</b>
					</td>
	                <td class="amountTd" colspan="4">{$isPoint['supplier1']['amount']}%</td>
	                <td class="amountTd" style="text-align:center;$supplier2" colspan="4">{$isPoint2}</td>
	                <td class="amountTd" style="text-align:center;$supplier3" colspan="4">{$isPoint3}</td>
	                <td class="amountTd" style="text-align:center;$supplier4" colspan="4">{$isPoint4}</td>
	                <td style="text-align:left;">{$isPoint['supplier1']['remark']}</td>
                </tr>
                <tr class="tr_odd">
                    <td ><b>税费</b>
					</td>
	                <td class="formatMoney" colspan="4">{$isTax['supplier1']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier2" colspan="4">{$isTax['supplier2']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier3" colspan="4">{$isTax['supplier3']['amount']}</td>
	                <td class="formatMoney" style="text-align:center;$supplier4" colspan="4">{$isTax['supplier4']['amount']}</td>
	                <td style="text-align:left;">{$isTax['supplier1']['remark']}</td>
                </tr>
                <tr class="tr_even">
                    <td colspan="2"><b>项目净利率</b>
					</td>
	                <td class="amountTd" colspan="4">{$isNetProfit['supplier1']['amount']}%</td>
	                <td class="amountTd" style="text-align:center;$supplier2" colspan="4">{$isNetProfit2}</td>
	                <td class="amountTd" style="text-align:center;$supplier3" colspan="4">{$isNetProfit3}</td>
	                <td class="amountTd" style="text-align:center;$supplier4" colspan="4">{$isNetProfit4}</td>
	                <td style="text-align:left;">{$isNetProfit['supplier1']['remark']}</td>
                </tr>
EOT;
			//最终选用供应商
			$supplierStr=<<<EOT
				<tr class="tr_odd" style="color:blue;">
	            	<td style="text-align:left;" colspan="2"><b>最终选用供应商</b></td>
	                <td colspan="4"></td>
	                <td colspan="4"><span style="$supplier2">{$checkSupplier2}</span></td>
	                <td colspan="4"><span style="$supplier3">{$checkSupplier3}</span></td>
	                <td colspan="4"><span style="$supplier4">{$checkSupplier4}</span></td>
	                <td></td>
	            </tr>
EOT;

			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
						<th rowspan="2">费用大类</th>
						<th rowspan="2">费用小类</th>
							<th colspan="4">鼎利服务线</th>
							<th colspan="4" style="$supplier2">
								{$supplierArr['supplier2']}
							</th>
							<th colspan="4" style="$supplier3">
								{$supplierArr['supplier3']}
							</th>
							<th colspan="4" style="$supplier4">
								{$supplierArr['supplier4']}
							</th>
							<th rowspan="2" width="80px">备注</th>
						</tr>
						<tr class="main_tr_header">
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
							<th>价格</th><th>数量</th><th>周期</th><th>小计</th>
						</tr>
					</thead>
					<tbody id="projectRentalTbody">
	                    $tdStr
	                    $otherStr
	                    $supplierStr
	                </tbody>
				</table>
EOT;
		}

		return $str;
	}

		/**
	 * 格式化传入数据 - 转成正常可用数据
	 */
	function dataFormat_d($object){
		$newArr = array();
		if($object){
			$supplierArr = $object['supplier'];//先取出供应商信息
			unset($object['supplier']);

			foreach($object as $val){
				$i = 1;
				$groupKey = md5(microtime());
				while($i < 5){
					$supplier = 'supplier'.$i;//当前循环供应商
					$sysNo = $i;//系统序号
					$i++;
					if(empty($supplierArr[$supplier])|| $val[$supplier]['amount'] == 0) continue;//如果当前供应商为空或者金额为0,则此数据作废
					$rentalDetail = array(//载入数据
						'isServerCost' => $val['isServerCost'],'parentName' => $val['parentName'],
						'costTypeId' => $val['costTypeId'],'costType' => $val['costType'],
						'isDetail' => $val['isDetail'],'isCustom' => $val['isCustom'],
						'price' => $val[$supplier]['price'],'number' => $val[$supplier]['number'],
						'period' => $val[$supplier]['period'],'amount' => $val[$supplier]['amount'],
						'remark' => $val['remark'],'suppName' => $supplierArr[$supplier],'suppId' => $object[$supplier]['suppId'],'suppCode' => $object[$supplier]['suppCode'],'sysNo' => $sysNo,
						'groupKey' => $groupKey
					);

					if(isset($val[$supplier]['id'])) $rentalDetail['id'] = $val[$supplier]['id'];//存在id时才载入
					if(isset($val['isDelTag'])) $rentalDetail['isDelTag'] = $val['isDelTag'];//存在id时才载入
					if(isset($val['isOtherFee'])) $rentalDetail['isOtherFee'] = $val['isOtherFee'];//是否其他费用
					if(isset($val['isManageFee'])) $rentalDetail['isManageFee'] = $val['isManageFee'];//是否管理费用
					if(isset($val['isAllCost'])) $rentalDetail['isAllCost'] = $val['isAllCost'];//是否项目总成本
					if(isset($val['isProfit'])) $rentalDetail['isProfit'] = $val['isProfit'];//是否项目毛利率
					if(isset($val['isPoint'])) $rentalDetail['isPoint'] = $val['isPoint'];//是否增值税专用发票税点
					if(isset($val['isNetProfit'])) $rentalDetail['isNetProfit'] = $val['isNetProfit'];//是否项目净利率
					if(isset($val['isTax'])) $rentalDetail['isTax'] = $val['isTax'];//是否税费

					if($i != 2) $rentalDetail['isSelf'] = 0;//非本公司
					$checkSupplier='supplier'.$supplierArr['checkSupplier'];
					if($checkSupplier == $supplier){//设置选中供应商
						$rentalDetail['isChoosed'] = 1;
					}

					array_push($newArr,$rentalDetail);
				}
			}
		}
		return $newArr;
	}
 	/**
	 * 格式化传出数据 - 转成显示用的数据
	 */
	function dataUnFormat_d($object){
		$newArr = array();
		if($object){
			$supplierArr = array();//供应商信息
			$costTypeGroup = array();//费用分组
			$suppinfoArr=array();

			foreach($object as $val){
				$supplierNo = 'supplier'.$val['sysNo'];
				//构建供应商信息
				if(!isset($supplierArr[$supplierNo])){
					$supplierArr[$supplierNo] = $val['suppName'];//供应商数据赋值
					$suppinfoArr[$val['sysNo']]['suppName'] = $val['suppName'];//供应商数据赋值
					$suppinfoArr[$val['sysNo']]['suppId'] = $val['suppId'];//供应商数据赋值
					$suppinfoArr[$val['sysNo']]['suppCode'] = $val['suppCode'];//供应商数据赋值
					if($val['isChoosed'] == 1) $supplierArr['checkSupplier'] = $supplierNo;
				}
				//数组载入
				$newArr[$val['groupKey']][$supplierNo] = $val;
			}

			//循环构建费用分组
			foreach($newArr as $val){
				//费用项目行
				if($val['supplier1']['isDetail']==1){
					$costTypeGroup[$val['supplier1']['parentName']]['rowLength'] = isset($costTypeGroup[$val['supplier1']['parentName']]['rowLength']) ? $costTypeGroup[$val['supplier1']['parentName']]['rowLength'] + 1 : 1;
				}
			}

			$newArr['supplier'] = $supplierArr;
			$newArr['costTypeGroup'] = $costTypeGroup;
			$newArr['supplierinfo'] = $suppinfoArr;
		}
		return $newArr;
	}

 }


?>