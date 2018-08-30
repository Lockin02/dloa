<?php
/**
 * @author show
 * @Date 2013年10月10日 17:07:13
 * @version 1.0
 * @description:外包合同整包分包表 Model层
 */
class model_contract_outsourcing_projectrental extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_projectrental";
		$this->sql_map = "contract/outsourcing/projectrentalSql.php";
		parent :: __construct();
	}
    public $datadictFieldArr = array('parent','costType');//数据字典内容

	/**
	 * 获取表格
	 */
	function getAddPage_d(){
		//获取模板
		$outtemplateDao = new model_contract_outsourcing_outtemplate();
		$outtemplateObj = $outtemplateDao->getTemplate_d();
		if($outtemplateObj){
			return $this->getAddPageTemplate_d($outtemplateObj);
		}else{
			return $this->getAddPageBase_d();
		}
	}

	/**
	 * 获取新增页面 - 有模板
	 */
	function getAddPageTemplate_d($outtemplateObj){
		$str = "";
        //获取第一个类型
        $datadictDao = new model_system_datadict_datadict();
        $parentArr = $datadictDao->getDatadictsByParentCodes('WBHTFYX');
        $tdStr = null;
		foreach($outtemplateObj as $key => $val){
	        $firstOption = $this->getOption_d($parentArr['WBHTFYX'],$val['parent']);//第一个选择项,用于加载首个明细分类
	        $parentOptionStr = $this->getDatadictsStr($parentArr['WBHTFYX'],$val['parent']);//加载父级选项

	        //根据数据字典扩展字段1判断二级选项是选项还是可填
			if($firstOption['expand1'] == "1"){//选项
		        $costTypeArr = $datadictDao->getDatadictsByParentCodes($val['parent']);//获取明细分类
		        $costTypeOptionStr = $this->getDatadictsStr($costTypeArr[$val['parent']],$val['costType']);//加载父级选项
		        $costTypeStr =<<<EOT
					<select name="outsourcing[projectRental][$key][costType]" id="costType$key" style="width:65px;">$costTypeOptionStr</select>
					<input type="hidden" name="outsourcing[projectRental][$key][isCustom]" id="isCustom$key" value="0"/>
EOT;
			}else{//可填
		        $costTypeStr =<<<EOT
					<input name="outsourcing[projectRental][$key][costTypeName]" id="costTypeName$key" value="{$val['costTypeName']}" class="rimless_textB" style="width:65px;"/>
					<input type="hidden" name="outsourcing[projectRental][$key][isCustom]" id="isCustom$key" value="1"/>
EOT;
			}

			//根据数据字典扩展字段2判断金额录入方式
			if($firstOption['expand2'] == "1"){
				$moneyStr =<<<EOT
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier1][price]" id="supplier1_price$key" onblur="countDetail($key,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier1][number]" id="supplier1_number$key" onblur="countDetail($key,1);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier1][period]" id="supplier1_period$key" onblur="countDetail($key,1);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="outsourcing[projectRental][$key][supplier1][amount]" id="supplier1_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier2][price]" id="supplier2_price$key" onblur="countDetail($key,2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier2][number]" id="supplier2_number$key" onblur="countDetail($key,2);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier2][period]" id="supplier2_period$key" onblur="countDetail($key,2);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="outsourcing[projectRental][$key][supplier2][amount]" id="supplier2_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier3][price]" id="supplier3_price$key" onblur="countDetail($key,3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier3][number]" id="supplier3_number$key" onblur="countDetail($key,3);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier3][period]" id="supplier3_period$key" onblur="countDetail($key,3);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="outsourcing[projectRental][$key][supplier3][amount]" id="supplier3_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier4][price]" id="supplier4_price$key" onblur="countDetail($key,4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier4][number]" id="supplier4_number$key" onblur="countDetail($key,4);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd"><input name="outsourcing[projectRental][$key][supplier4][period]" id="supplier4_period$key" onblur="countDetail($key,4);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd"><input name="outsourcing[projectRental][$key][supplier4][amount]" id="supplier4_amount$key" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td>
	                	<input name="outsourcing[projectRental][$key][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="outsourcing[projectRental][$key][isDetail]" id="isDetail$key" value="1"/>
					</td>
EOT;
			}else{
				$moneyStr =<<<EOT
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier1][price]" id="supplier1_price$key" onblur="countDetail($key,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier1][number]" id="supplier1_number$key" onblur="countDetail($key,1);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier1][period]" id="supplier1_period$key" onblur="countDetail($key,1);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$key][supplier1][amount]" id="supplier1_amount$key" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier2][price]" id="supplier2_price$key" onblur="countDetail($key,2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier2][number]" id="supplier2_number$key" onblur="countDetail($key,2);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier2][period]" id="supplier2_period$key" onblur="countDetail($key,2);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$key][supplier2][amount]" id="supplier2_amount$key" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier3][price]" id="supplier3_price$key" onblur="countDetail($key,3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier3][number]" id="supplier3_number$key" onblur="countDetail($key,3);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier3][period]" id="supplier3_period$key" onblur="countDetail($key,3);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$key][supplier3][amount]" id="supplier3_amount$key" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier4][price]" id="supplier4_price$key" onblur="countDetail($key,4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier4][number]" id="supplier4_number$key" onblur="countDetail($key,4);" class="rimless_textB" style="width:35px;"></td>
	                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$key][supplier4][period]" id="supplier4_period$key" onblur="countDetail($key,4);" class="rimless_textB" style="width:35px;"></td>
	                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$key][supplier4][amount]" id="supplier4_amount$key" class="rimless_textB formatMoney" style="width:230px;"></td>
	                <td>
	                	<input name="outsourcing[projectRental][$key][remark]" class="rimless_textB" style="width:80px;"/>
						<input type="hidden" name="outsourcing[projectRental][$key][isDetail]" id="isDetail$key" value="0"/>
	            	</td>
EOT;
			}

			//根据扩展字段3判断此费用是否为管理费用
			$isServerCost = $firstOption['expand3'] == "1" ? 1 : 0;
			$isServerCostStr =<<<EOT
				<input type="hidden" name="outsourcing[projectRental][$key][isServerCost]" id="isServerCost$key" value="$isServerCost"/>
EOT;
			$tdStr.=<<<EOT
                <tr id="tr$key" rowNum="$key">
                    <td><img src="images/removeline.png" onclick="delProjectRentalRow($key);" title="删除行"/>$isServerCostStr</td>
                    <td><select name="outsourcing[projectRental][$key][parent]" id="parent$key" onchange="changeParentSelect($key);" style="width:55px;">$parentOptionStr</select></td>
                    <td>$costTypeStr</td>
                    $moneyStr
                </tr>
EOT;
		}

		//列表
		$str =<<<EOT
			<table class="form_in_table">
				<thead>
					<tr class="main_tr_header">
	                    <th rowspan="2">
	                        <input type="hidden" id="projectRentalRowNum" value="$key"/>
	                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="添加行"/>
	                    </th>
						<th rowspan="2">项目</th>
						<th rowspan="2">分项</th>
						<th colspan="4">服务线<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="服务线"/></th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio2" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier2"/>
							<input id="supplier2" name="outsourcing[projectRental][supplier][supplier2]" class="rimless_textB"/>
						</th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio3" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier3"/>
							<input id="supplier3" name="outsourcing[projectRental][supplier][supplier3]" class="rimless_textB"/>
						</th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio4" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier4"/>
							<input id="supplier4" name="outsourcing[projectRental][supplier][supplier4]" class="rimless_textB"/>
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
                </tbody>
			</table>
EOT;
		return $str;
	}

	/**
	 * 数据字典返回查询到的值
	 */
	function getOption_d($parentArr,$dataCode){
		foreach($parentArr as $val){
			if($dataCode == $val['dataCode']){
				$obj = $val;
				break;
			}
		}
		return $obj;
	}

	/**
	 * 获取新增页面 - 无模板
	 */
	function getAddPageBase_d(){
        //获取第一个类型
        $datadictDao = new model_system_datadict_datadict();
        $parentArr = $datadictDao->getDatadictsByParentCodes('WBHTFYX');
        $firstOption = $parentArr['WBHTFYX'][0];//第一个选择项,用于加载首个明细分类
        $parentOptionStr = $this->getDatadictsStr($parentArr['WBHTFYX']);//加载父级选项

		//根据数据字典扩展字段1判断二级选项是选项还是可填
		if($firstOption['expand1'] == "1"){//选项
	        $costTypeArr = $datadictDao->getDatadictsByParentCodes($firstOption['dataCode']);//获取明细分类
	        $costTypeOptionStr = $this->getDatadictsStr($costTypeArr[$firstOption['dataCode']]);//加载父级选项
	        $costTypeStr =<<<EOT
				<select name="outsourcing[projectRental][0][costType]" id="costType0" style="width:65px;">$costTypeOptionStr</select>
				<input type="hidden" name="outsourcing[projectRental][0][isCustom]" id="isCustom0" value="0"/>
EOT;
		}else{//可填
	        $costTypeStr =<<<EOT
				<input name="outsourcing[projectRental][0][costTypeName]" id="costTypeName0" class="rimless_textB" style="width:65px;"/>
				<input type="hidden" name="outsourcing[projectRental][0][isCustom]" id="isCustom0" value="1"/>
EOT;
		}

		//根据数据字典扩展字段2判断金额录入方式
		if($firstOption['expand2'] == "1"){
			$moneyStr =<<<EOT
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier1][price]" id="supplier1_price0" onblur="countDetail(0,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier1][number]" id="supplier1_number0" onblur="countDetail(0,1);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier1][period]" id="supplier1_period0" onblur="countDetail(0,1);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd"><input name="outsourcing[projectRental][0][supplier1][amount]" id="supplier1_amount0" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier2][price]" id="supplier2_price0" onblur="countDetail(0,2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier2][number]" id="supplier2_number0" onblur="countDetail(0,2);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier2][period]" id="supplier2_period0" onblur="countDetail(0,2);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd"><input name="outsourcing[projectRental][0][supplier2][amount]" id="supplier2_amount0" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier3][price]" id="supplier3_price0" onblur="countDetail(0,3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier3][number]" id="supplier3_number0" onblur="countDetail(0,3);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier3][period]" id="supplier3_period0" onblur="countDetail(0,3);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd"><input name="outsourcing[projectRental][0][supplier3][amount]" id="supplier3_amount0" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier4][price]" id="supplier4_price0" onblur="countDetail(0,4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier4][number]" id="supplier4_number0" onblur="countDetail(0,4);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd"><input name="outsourcing[projectRental][0][supplier4][period]" id="supplier4_period0" onblur="countDetail(0,4);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd"><input name="outsourcing[projectRental][0][supplier4][amount]" id="supplier4_amount0" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td>
                	<input name="outsourcing[projectRental][0][remark]" class="rimless_textB" style="width:80px;"/>
					<input type="hidden" name="outsourcing[projectRental][0][isDetail]" id="isDetail0" value="1"/>
				</td>
EOT;
		}else{
			$moneyStr =<<<EOT
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier1][price]" id="supplier1_price0" onblur="countDetail(0,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier1][number]" id="supplier1_number0" onblur="countDetail(0,1);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier1][period]" id="supplier1_period0" onblur="countDetail(0,1);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][0][supplier1][amount]" id="supplier1_amount0" class="rimless_textB formatMoney" style="width:230px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier2][price]" id="supplier2_price0" onblur="countDetail(0,2,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier2][number]" id="supplier2_number0" onblur="countDetail(0,2);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier2][period]" id="supplier2_period0" onblur="countDetail(0,2);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][0][supplier2][amount]" id="supplier2_amount0" class="rimless_textB formatMoney" style="width:230px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier3][price]" id="supplier3_price0" onblur="countDetail(0,3,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier3][number]" id="supplier3_number0" onblur="countDetail(0,3);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier3][period]" id="supplier3_period0" onblur="countDetail(0,3);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][0][supplier3][amount]" id="supplier3_amount0" class="rimless_textB formatMoney" style="width:230px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier4][price]" id="supplier4_price0" onblur="countDetail(0,4,1);" class="rimless_textB formatMoney" style="width:65px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier4][number]" id="supplier4_number0" onblur="countDetail(0,4);" class="rimless_textB" style="width:35px;"></td>
                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][0][supplier4][period]" id="supplier4_period0" onblur="countDetail(0,4);" class="rimless_textB" style="width:35px;"></td>
                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][0][supplier4][amount]" id="supplier4_amount0" class="rimless_textB formatMoney" style="width:230px;"></td>
                <td>
                	<input name="outsourcing[projectRental][0][remark]" class="rimless_textB" style="width:80px;"/>
					<input type="hidden" name="outsourcing[projectRental][0][isDetail]" id="isDetail0" value="0"/>
            	</td>
EOT;
		}

		//根据扩展字段3判断此费用是否为管理费用
		$isServerCost = $firstOption['expand3'] == "1" ? 1 : 0;
		$isServerCostStr =<<<EOT
			<input type="hidden" name="outsourcing[projectRental][0][isServerCost]" id="isServerCost0" value="$isServerCost"/>
EOT;

		//列表
		$str =<<<EOT
			<table class="form_in_table">
				<thead>
					<tr class="main_tr_header">
	                    <th rowspan="2">
	                        <input type="hidden" id="projectRentalRowNum" value="0"/>
	                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="添加行"/>
	                    </th>
						<th rowspan="2">项目</th>
						<th rowspan="2">分项</th>
						<th colspan="4">服务线<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="服务线"/></th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio2" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier2"/>
							<input id="supplier2" name="outsourcing[projectRental][supplier][supplier2]" class="rimless_textB"/>
						</th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio3" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier3"/>
							<input id="supplier3" name="outsourcing[projectRental][supplier][supplier3]" class="rimless_textB"/>
						</th>
						<th colspan="4" style="valign:middle;">
							<input type="radio" id="supplierRadio4" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier4"/>
							<input id="supplier4" name="outsourcing[projectRental][supplier][supplier4]" class="rimless_textB"/>
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
                    <tr id="tr0" rowNum="0">
                        <td><img src="images/removeline.png" onclick="delProjectRentalRow(0);" title="删除行"/>$isServerCostStr</td>
                        <td><select name="outsourcing[projectRental][0][parent]" id="parent0" onchange="changeParentSelect(0);" style="width:55px;">$parentOptionStr</select></td>
                        <td>$costTypeStr</td>
                        $moneyStr
                    </tr>
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
		if($projectRentalInfo){
			//反格式化
			$projectRentalInfo = $this->dataUnFormat_d($projectRentalInfo);

			//取出供应商信息
			$supplierArr = $projectRentalInfo['supplier'];
			unset($projectRentalInfo['supplier']);
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

				//父级选项渲染
		        $parentArr = $datadictDao->getDatadictsByParentCodes('WBHTFYX');
		        $parentOptionStr = $this->getDatadictsStr($parentArr['WBHTFYX'],$val['supplier1']['parent']);//加载父级选项

				//根据数据字典扩展字段1判断二级选项是选项还是可填
				if($val['supplier1']['isCustom'] == "0"){//选项
			        $costTypeArr = $datadictDao->getDatadictsByParentCodes($val['supplier1']['parent']);//获取明细分类
			        $costTypeOptionStr = $this->getDatadictsStr($costTypeArr[$val['supplier1']['parent']],$val['supplier1']['costType']);//加载父级选项
			        $costTypeStr =<<<EOT
						<select name="outsourcing[projectRental][$i][costType]" id="costType0" style="width:65px;">$costTypeOptionStr</select>
						<input type="hidden" name="outsourcing[projectRental][$i][isCustom]" id="isCustom$i" value="0"/>
EOT;
				}else{//可填
			        $costTypeStr =<<<EOT
						<input name="outsourcing[projectRental][$i][costTypeName]" id="costTypeName$i" class="rimless_textB" style="width:65px;" value="{$val['supplier1']['costTypeName']}"/>
						<input type="hidden" name="outsourcing[projectRental][$i][isCustom]" id="isCustom$i" value="1"/>
EOT;
				}

				//根据扩展字段3判断此费用是否为管理费用
				$isServerCost = $val['supplier1']['isServerCost'] == "1" ? 1 : 0;
				$isServerCostStr =<<<EOT
					<input type="hidden" name="outsourcing[projectRental][$i][isServerCost]" id="isServerCost$i" value="$isServerCost"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier1][id]" id="supplier1_id$i" value="{$val['supplier1']['id']}"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier2][id]" id="supplier2_id$i" value="{$val['supplier2']['id']}"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier3][id]" id="supplier3_id$i" value="{$val['supplier3']['id']}"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier4][id]" id="supplier4_id$i" value="{$val['supplier4']['id']}"/>
EOT;

				//表格内容
				if($val['supplier1']['isDetail'] == "1"){
					$tdStr .=<<<EOT
                    	<tr id="tr$i" rowNum="$i">
                        	<td><img src="images/removeline.png" onclick="delProjectRentalRow($i);" title="删除行"/>$isServerCostStr</td>
                        	<td><select name="outsourcing[projectRental][$i][parent]" id="parent$i" onchange="changeParentSelect($i);" style="width:55px;">$parentOptionStr</select></td>
			                <td>$costTypeStr</td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier1][price]" id="supplier1_price$i" onblur="countDetail($i,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier1']['price']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier1][number]" id="supplier1_number$i" onblur="countDetail($i,1);" class="rimless_textB" style="width:35px;" value="{$val['supplier1']['number']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier1][period]" id="supplier1_period$i" onblur="countDetail($i,1);" class="rimless_textB" style="width:35px;" value="{$val['supplier1']['period']}"/></td>
			                <td class="amountTd"><input name="outsourcing[projectRental][$i][supplier1][amount]" id="supplier1_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier1']['amount']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier2][price]" id="supplier2_price$i" onblur="countDetail($i,2,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier2']['price']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier2][number]" id="supplier2_number$i" onblur="countDetail($i,2);" class="rimless_textB" style="width:35px;" value="{$val['supplier2']['number']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier2][period]" id="supplier2_period$i" onblur="countDetail($i,2);" class="rimless_textB" style="width:35px;" value="{$val['supplier2']['period']}"/></td>
			                <td class="amountTd"><input name="outsourcing[projectRental][$i][supplier2][amount]" id="supplier2_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier2']['amount']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier3][price]" id="supplier3_price$i" onblur="countDetail($i,3,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier3']['price']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier3][number]" id="supplier3_number$i" onblur="countDetail($i,3);" class="rimless_textB" style="width:35px;" value="{$val['supplier3']['number']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier3][period]" id="supplier3_period$i" onblur="countDetail($i,3);" class="rimless_textB" style="width:35px;" value="{$val['supplier3']['period']}"/></td>
			                <td class="amountTd"><input name="outsourcing[projectRental][$i][supplier3][amount]" id="supplier3_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier3']['amount']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier4][price]" id="supplier4_price$i" onblur="countDetail($i,4,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier4']['price']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier4][number]" id="supplier4_number$i" onblur="countDetail($i,4);" class="rimless_textB" style="width:35px;" value="{$val['supplier4']['number']}"/></td>
			                <td class="detailTd"><input name="outsourcing[projectRental][$i][supplier4][period]" id="supplier4_period$i" onblur="countDetail($i,4);" class="rimless_textB" style="width:35px;" value="{$val['supplier4']['period']}"/></td>
			                <td class="amountTd"><input name="outsourcing[projectRental][$i][supplier4][amount]" id="supplier4_amount$i" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier4']['amount']}"/></td>
			                <td>
			                	<input name="outsourcing[projectRental][$i][remark]" class="rimless_textB" style="width:80px;" value="{$val['supplier1']['remark']}"/>
								<input type="hidden" name="outsourcing[projectRental][$i][isDetail]" id="isDetail$i" value="1"/>
			            	</td>
			            </tr>
EOT;
				}else{
					$tdStr .=<<<EOT
                    	<tr id="tr$i" rowNum="$i">
                        	<td><img src="images/removeline.png" onclick="delProjectRentalRow($i);" title="删除行"/>$isServerCostStr</td>
                       		<td><select name="outsourcing[projectRental][$i][parent]" id="parent$i" onchange="changeParentSelect($i);" style="width:55px;">$parentOptionStr</select></td>
			                <td>$costTypeStr</td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier1][price]" id="supplier1_price$i" onblur="countDetail($i,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier1']['price']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier1][number]" id="supplier1_number$i" onblur="countDetail($i,1);" class="rimless_textB" style="width:35px;" value="{$val['supplier1']['number']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier1][period]" id="supplier1_period$i" onblur="countDetail($i,1);" class="rimless_textB" style="width:35px;" value="{$val['supplier1']['period']}"/></td>
			                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$i][supplier1][amount]" id="supplier1_amount$i" class="rimless_textB formatMoney" style="width:230px;" value="{$val['supplier1']['amount']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier2][price]" id="supplier2_price$i" onblur="countDetail($i,2,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier2']['price']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier2][number]" id="supplier2_number$i" onblur="countDetail($i,2);" class="rimless_textB" style="width:35px;" value="{$val['supplier2']['number']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier2][period]" id="supplier2_period$i" onblur="countDetail($i,2);" class="rimless_textB" style="width:35px;" value="{$val['supplier2']['period']}"/></td>
			                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$i][supplier2][amount]" id="supplier2_amount$i" class="rimless_textB formatMoney" style="width:230px;" value="{$val['supplier2']['amount']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier3][price]" id="supplier3_price$i" onblur="countDetail($i,3,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier3']['price']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier3][number]" id="supplier3_number$i" onblur="countDetail($i,3);" class="rimless_textB" style="width:35px;" value="{$val['supplier3']['number']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier3][period]" id="supplier3_period$i" onblur="countDetail($i,3);" class="rimless_textB" style="width:35px;" value="{$val['supplier3']['period']}"/></td>
			                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$i][supplier3][amount]" id="supplier3_amount$i" class="rimless_textB formatMoney" style="width:230px;" value="{$val['supplier3']['amount']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier4][price]" id="supplier4_price$i" onblur="countDetail($i,4,1);" class="rimless_textB formatMoney" style="width:65px;" value="{$val['supplier4']['price']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier4][number]" id="supplier4_number$i" onblur="countDetail($i,4);" class="rimless_textB" style="width:35px;" value="{$val['supplier4']['number']}"/></td>
			                <td class="detailTd" style="display:none;"><input name="outsourcing[projectRental][$i][supplier4][period]" id="supplier4_period$i" onblur="countDetail($i,4);" class="rimless_textB" style="width:35px;" value="{$val['supplier4']['period']}"/></td>
			                <td class="amountTd" colspan="4"><input name="outsourcing[projectRental][$i][supplier4][amount]" id="supplier4_amount$i" class="rimless_textB formatMoney" style="width:230px;" value="{$val['supplier4']['amount']}"/></td>
			                <td>
			                	<input name="outsourcing[projectRental][$i][remark]" class="rimless_textB" style="width:80px;" value="{$val['supplier1']['remark']}"/>
								<input type="hidden" name="outsourcing[projectRental][$i][isDetail]" id="isDetail$i" value="0"/>
			            	</td>
			            </tr>
EOT;
				}
				$i++;
			}

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
		                        <input type="hidden" id="projectRentalRowNum" value="$i"/>
		                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="添加行"/>
		                    </th>
							<th rowspan="2">项目</th>
							<th rowspan="2">分项</th>
							<th colspan="4">服务线<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="服务线"/></th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio2" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier2" $checked2/>
								<input id="supplier2" name="outsourcing[projectRental][supplier][supplier2]" class="rimless_textB" value="{$supplierArr['supplier2']}"/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio3" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier3" $checked3/>
								<input id="supplier3" name="outsourcing[projectRental][supplier][supplier3]" class="rimless_textB" value="{$supplierArr['supplier3']}"/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio4" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier4" $checked4/>
								<input id="supplier4" name="outsourcing[projectRental][supplier][supplier4]" class="rimless_textB" value="{$supplierArr['supplier4']}"/>
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
		                        <input type="hidden" id="projectRentalRowNum" value="-1"/>
		                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="添加行"/>
		                    </th>
							<th rowspan="2">项目</th>
							<th rowspan="2">分项</th>
							<th colspan="4">服务线<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="服务线"/></th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio2" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier2"/>
								<input id="supplier2" name="outsourcing[projectRental][supplier][supplier2]" class="rimless_textB"/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio3" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier3"/>
								<input id="supplier3" name="outsourcing[projectRental][supplier][supplier3]" class="rimless_textB"/>
							</th>
							<th colspan="4" style="valign:middle;">
								<input type="radio" id="supplierRadio4" name="outsourcing[projectRental][supplier][checkSupplier]" value="supplier4"/>
								<input id="supplier4" name="outsourcing[projectRental][supplier][supplier4]" class="rimless_textB"/>
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
			$projectRentalInfo = $this->dataUnFormat_d($projectRentalInfo);

			//取出供应商信息
			$supplierArr = $projectRentalInfo['supplier'];
			unset($projectRentalInfo['supplier']);
			//取出分组信息
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
				if($costTypeMark != $val['supplier1']['parent']){
					$trClass = $i%2==0 ? 'tr_odd' : 'tr_even';
					$i++;
					$costTypeMark = $val['supplier1']['parent'];
					$rowLength = $costTypeGroup[$val['supplier1']['parent']]['rowLength'];
					$costTypeStr=<<<EOT
						<td style="text-align:left;" rowspan="$rowLength">{$val['supplier1']['parentName']}</td>
EOT;

					//服务成本载入
					if(empty($serviceCostMark) && empty($val['supplier1']['isServerCost'])){
						$serviceCostMark = 1;
						//服务成本
						$tdStr.=<<<EOT
							<tr class="tr_count">
				            	<td style="text-align:left;" colspan="2">服 务 成 本</td>
				                <td class="formatMoney" style="text-align:right;" colspan="4">{$costTypeGroup['supplier1']['serviceCost']}</td>
				                <td class="formatMoney" style="text-align:right;$supplier2" colspan="4">{$costTypeGroup['supplier2']['serviceCost']}</td>
				                <td class="formatMoney" style="text-align:right;$supplier3" colspan="4">{$costTypeGroup['supplier3']['serviceCost']}</td>
				                <td class="formatMoney" style="text-align:right;$supplier4" colspan="4">{$costTypeGroup['supplier4']['serviceCost']}</td>
				                <td></td>
				            </tr>
EOT;
					}
				}else{
					$costTypeStr = "";
				}

				if($val['supplier1']['isDetail'] == "1"){
					$tdStr .=<<<EOT
						<tr class="$trClass">
							$costTypeStr
		                	<td style="text-align:left;">{$val['supplier1']['costTypeName']}</td>
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
					$tdStr .=<<<EOT
						<tr class="$trClass">
			                $costTypeStr
		                	<td style="text-align:left;">{$val['supplier1']['costTypeName']}</td>
			                <td style="display:none;">{$val['supplier1']['price']}</td>
			                <td style="display:none;">{$val['supplier1']['number']}</td>
			                <td style="display:none;">{$val['supplier1']['period']}</td>
			                <td id="td_supplier1" class="formatMoney" style="text-align:right;" colspan="4">{$val['supplier1']['amount']}</td>
			                <td style="display:none;">{$val['supplier2']['price']}</td>
			                <td style="display:none;">{$val['supplier2']['number']}</td>
			                <td style="display:none;">{$val['supplier2']['period']}</td>
			                <td id="td_supplier2" class="formatMoney" style="text-align:right;$supplier2" colspan="4">{$val['supplier2']['amount']}</td>
			                <td style="display:none;">{$val['supplier3']['price']}</td>
			                <td style="display:none;">{$val['supplier3']['number']}</td>
			                <td style="display:none;">{$val['supplier3']['period']}</td>
			                <td id="td_supplier3" class="formatMoney" style="text-align:right;$supplier3" colspan="4">{$val['supplier3']['amount']}</td>
			                <td style="display:none;">{$val['supplier4']['price']}</td>
			                <td style="display:none;">{$val['supplier4']['number']}</td>
			                <td style="display:none;">{$val['supplier4']['period']}</td>
			                <td id="td_supplier4" class="formatMoney" style="text-align:right;$supplier4" colspan="4">{$val['supplier4']['amount']}</td>
			                <td style="text-align:left;">{$val['supplier1']['remark']}</td>
			            </tr>
EOT;
				}
				//服务成本载入
				if(empty($serviceCostMark) && empty($val['supplier1']['isServerCost'])){
					$serviceCostMark = 1;
					//服务成本
					$tdStr.=<<<EOT
						<tr class="tr_count">
			            	<td style="text-align:left;" colspan="2">服 务 成 本</td>
			                <td class="formatMoney" style="text-align:right;" colspan="4">{$costTypeGroup['supplier1']['serviceCost']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier2" colspan="4">{$costTypeGroup['supplier2']['serviceCost']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier3" colspan="4">{$costTypeGroup['supplier3']['serviceCost']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier4" colspan="4">{$costTypeGroup['supplier4']['serviceCost']}</td>
			                <td></td>
			            </tr>
EOT;
				}
			}

			//总成本
			$allCost=<<<EOT
				<tr class="tr_count">
	            	<td style="text-align:left;" colspan="2">项目总成本</td>
	                <td class="formatMoney" style="text-align:right;" colspan="4">{$costTypeGroup['supplier1']['allCost']}</td>
	                <td class="formatMoney" style="text-align:right;$supplier2" colspan="4">{$costTypeGroup['supplier2']['allCost']}</td>
	                <td class="formatMoney" style="text-align:right;$supplier3" colspan="4">{$costTypeGroup['supplier3']['allCost']}</td>
	                <td class="formatMoney" style="text-align:right;$supplier4" colspan="4">{$costTypeGroup['supplier4']['allCost']}</td>
	                <td></td>
	            </tr>
EOT;
			//最终选用供应商
			$supplierStr=<<<EOT
				<tr class="tr_odd" style="color:blue;">
	            	<td style="text-align:left;" colspan="2">最终选用供应商</td>
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
							<th rowspan="2">项目</th>
							<th rowspan="2">分项</th>
							<th colspan="4">服务线</th>
							<th colspan="4" style="$supplier2">
								{$supplierArr['supplier2']}$appandInfo2
							</th>
							<th colspan="4" style="$supplier3">
								{$supplierArr['supplier3']}$appandInfo3
							</th>
							<th colspan="4" style="$supplier4">
								{$supplierArr['supplier4']}$appandInfo4
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
	                    $allCost
	                    $supplierStr
	                </tbody>
				</table>
EOT;
		}
		return $str;
	}

	/**
	 * 获取数据
	 */
	function getProjectRentalInfo_d($mainId){
		$this->searchArr = array('mainId' => $mainId);
		$this->sort = "parent,sysNo";
		$this->asc = false;
		return $this->list_d();
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
					if(empty($supplierArr[$supplier]) || $val[$supplier]['amount'] == 0) continue;//如果当前供应商为空或者金额为0,则此数据作废

					$rentalDetail = array(//载入数据
						'isServerCost' => $val['isServerCost'],'parent' => $val['parent'],
						'costTypeName' => $val['costTypeName'],'costType' => $val['costType'],
						'isDetail' => $val['isDetail'],'isCustom' => $val['isCustom'],
						'price' => $val[$supplier]['price'],'number' => $val[$supplier]['number'],
						'period' => $val[$supplier]['period'],'amount' => $val[$supplier]['amount'],
						'remark' => $val['remark'],'supplierName' => $supplierArr[$supplier],'sysNo' => $sysNo,
						'groupKey' => $groupKey
					);

					if(isset($val[$supplier]['id'])) $rentalDetail['id'] = $val[$supplier]['id'];//存在id时才载入
					if(isset($val['isDelTag'])) $rentalDetail['isDelTag'] = $val['isDelTag'];//存在id时才载入

					if($i != 2) $rentalDetail['isSelf'] = 0;//非本公司

					if($supplierArr['checkSupplier'] == $supplier){//设置选中供应商
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

			foreach($object as $val){
				$supplierNo = 'supplier'.$val['sysNo'];
				//构建供应商信息
				if(!isset($supplierArr[$supplierNo])){
					$supplierArr[$supplierNo] = $val['supplierName'];//供应商数据赋值
					if($val['isChoosed'] == 1) $supplierArr['checkSupplier'] = $supplierNo;
				}
				//数组载入
				$newArr[$val['groupKey']][$supplierNo] = $val;

				//计算服务成本和其他成本
				if($val['isServerCost'] == 1){
					$costTypeGroup[$supplierNo]['serviceCost'] = isset($costTypeGroup[$supplierNo]['serviceCost']) ? bcadd($costTypeGroup[$supplierNo]['serviceCost'],$val['amount'],2) : $val['amount'];
				}else{
					$costTypeGroup[$supplierNo]['otherCost'] = isset($costTypeGroup[$supplierNo]['otherCost']) ? bcadd($costTypeGroup[$supplierNo]['otherCost'],$val['amount'],2) : $val['amount'];
				}
			}

			//总成本
			$costTypeGroup['supplier1']['allCost'] = bcadd($costTypeGroup['supplier1']['serviceCost'],$costTypeGroup['supplier1']['otherCost'],2);
			$costTypeGroup['supplier2']['allCost'] = bcadd($costTypeGroup['supplier2']['serviceCost'],$costTypeGroup['supplier2']['otherCost'],2);
			$costTypeGroup['supplier3']['allCost'] = bcadd($costTypeGroup['supplier3']['serviceCost'],$costTypeGroup['supplier3']['otherCost'],2);
			$costTypeGroup['supplier4']['allCost'] = bcadd($costTypeGroup['supplier4']['serviceCost'],$costTypeGroup['supplier4']['otherCost'],2);

			//循环构建费用分组
			foreach($newArr as $val){
				//费用项目行
				$costTypeGroup[$val['supplier1']['parent']]['rowLength'] = isset($costTypeGroup[$val['supplier1']['parent']]['rowLength']) ? $costTypeGroup[$val['supplier1']['parent']]['rowLength'] + 1 : 1;
			}

			$newArr['supplier'] = $supplierArr;
			$newArr['costTypeGroup'] = $costTypeGroup;
		}
		return $newArr;
	}

	//重写add
	public function add_d($object){
		$object = $this->processDatadict($object);
		return parent::add_d($object);
	}

	//重写edit
	public function edit_d($object){
		$object = $this->processDatadict($object);
		return parent::edit_d($object);
	}

	//删除租赁信息
	function delItemInfo_d($id) {
		$sql = "delete from " . $this->tbl_name . " where mainId = '$id' ";
		return $this->query($sql);
	}
}
?>