<?php
/**
 * @author show
 * @Date 2013��10��10�� 17:07:13
 * @version 1.0
 * @description:�����ͬ�����ְ��� Model��
 */
class model_contract_outsourcing_projectrental extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing_projectrental";
		$this->sql_map = "contract/outsourcing/projectrentalSql.php";
		parent :: __construct();
	}
    public $datadictFieldArr = array('parent','costType');//�����ֵ�����

	/**
	 * ��ȡ���
	 */
	function getAddPage_d(){
		//��ȡģ��
		$outtemplateDao = new model_contract_outsourcing_outtemplate();
		$outtemplateObj = $outtemplateDao->getTemplate_d();
		if($outtemplateObj){
			return $this->getAddPageTemplate_d($outtemplateObj);
		}else{
			return $this->getAddPageBase_d();
		}
	}

	/**
	 * ��ȡ����ҳ�� - ��ģ��
	 */
	function getAddPageTemplate_d($outtemplateObj){
		$str = "";
        //��ȡ��һ������
        $datadictDao = new model_system_datadict_datadict();
        $parentArr = $datadictDao->getDatadictsByParentCodes('WBHTFYX');
        $tdStr = null;
		foreach($outtemplateObj as $key => $val){
	        $firstOption = $this->getOption_d($parentArr['WBHTFYX'],$val['parent']);//��һ��ѡ����,���ڼ����׸���ϸ����
	        $parentOptionStr = $this->getDatadictsStr($parentArr['WBHTFYX'],$val['parent']);//���ظ���ѡ��

	        //���������ֵ���չ�ֶ�1�ж϶���ѡ����ѡ��ǿ���
			if($firstOption['expand1'] == "1"){//ѡ��
		        $costTypeArr = $datadictDao->getDatadictsByParentCodes($val['parent']);//��ȡ��ϸ����
		        $costTypeOptionStr = $this->getDatadictsStr($costTypeArr[$val['parent']],$val['costType']);//���ظ���ѡ��
		        $costTypeStr =<<<EOT
					<select name="outsourcing[projectRental][$key][costType]" id="costType$key" style="width:65px;">$costTypeOptionStr</select>
					<input type="hidden" name="outsourcing[projectRental][$key][isCustom]" id="isCustom$key" value="0"/>
EOT;
			}else{//����
		        $costTypeStr =<<<EOT
					<input name="outsourcing[projectRental][$key][costTypeName]" id="costTypeName$key" value="{$val['costTypeName']}" class="rimless_textB" style="width:65px;"/>
					<input type="hidden" name="outsourcing[projectRental][$key][isCustom]" id="isCustom$key" value="1"/>
EOT;
			}

			//���������ֵ���չ�ֶ�2�жϽ��¼�뷽ʽ
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

			//������չ�ֶ�3�жϴ˷����Ƿ�Ϊ�������
			$isServerCost = $firstOption['expand3'] == "1" ? 1 : 0;
			$isServerCostStr =<<<EOT
				<input type="hidden" name="outsourcing[projectRental][$key][isServerCost]" id="isServerCost$key" value="$isServerCost"/>
EOT;
			$tdStr.=<<<EOT
                <tr id="tr$key" rowNum="$key">
                    <td><img src="images/removeline.png" onclick="delProjectRentalRow($key);" title="ɾ����"/>$isServerCostStr</td>
                    <td><select name="outsourcing[projectRental][$key][parent]" id="parent$key" onchange="changeParentSelect($key);" style="width:55px;">$parentOptionStr</select></td>
                    <td>$costTypeStr</td>
                    $moneyStr
                </tr>
EOT;
		}

		//�б�
		$str =<<<EOT
			<table class="form_in_table">
				<thead>
					<tr class="main_tr_header">
	                    <th rowspan="2">
	                        <input type="hidden" id="projectRentalRowNum" value="$key"/>
	                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="�����"/>
	                    </th>
						<th rowspan="2">��Ŀ</th>
						<th rowspan="2">����</th>
						<th colspan="4">������<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="������"/></th>
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
						<th rowspan="2">��ע</th>
					</tr>
					<tr class="main_tr_header">
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
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
	 * �����ֵ䷵�ز�ѯ����ֵ
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
	 * ��ȡ����ҳ�� - ��ģ��
	 */
	function getAddPageBase_d(){
        //��ȡ��һ������
        $datadictDao = new model_system_datadict_datadict();
        $parentArr = $datadictDao->getDatadictsByParentCodes('WBHTFYX');
        $firstOption = $parentArr['WBHTFYX'][0];//��һ��ѡ����,���ڼ����׸���ϸ����
        $parentOptionStr = $this->getDatadictsStr($parentArr['WBHTFYX']);//���ظ���ѡ��

		//���������ֵ���չ�ֶ�1�ж϶���ѡ����ѡ��ǿ���
		if($firstOption['expand1'] == "1"){//ѡ��
	        $costTypeArr = $datadictDao->getDatadictsByParentCodes($firstOption['dataCode']);//��ȡ��ϸ����
	        $costTypeOptionStr = $this->getDatadictsStr($costTypeArr[$firstOption['dataCode']]);//���ظ���ѡ��
	        $costTypeStr =<<<EOT
				<select name="outsourcing[projectRental][0][costType]" id="costType0" style="width:65px;">$costTypeOptionStr</select>
				<input type="hidden" name="outsourcing[projectRental][0][isCustom]" id="isCustom0" value="0"/>
EOT;
		}else{//����
	        $costTypeStr =<<<EOT
				<input name="outsourcing[projectRental][0][costTypeName]" id="costTypeName0" class="rimless_textB" style="width:65px;"/>
				<input type="hidden" name="outsourcing[projectRental][0][isCustom]" id="isCustom0" value="1"/>
EOT;
		}

		//���������ֵ���չ�ֶ�2�жϽ��¼�뷽ʽ
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

		//������չ�ֶ�3�жϴ˷����Ƿ�Ϊ�������
		$isServerCost = $firstOption['expand3'] == "1" ? 1 : 0;
		$isServerCostStr =<<<EOT
			<input type="hidden" name="outsourcing[projectRental][0][isServerCost]" id="isServerCost0" value="$isServerCost"/>
EOT;

		//�б�
		$str =<<<EOT
			<table class="form_in_table">
				<thead>
					<tr class="main_tr_header">
	                    <th rowspan="2">
	                        <input type="hidden" id="projectRentalRowNum" value="0"/>
	                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="�����"/>
	                    </th>
						<th rowspan="2">��Ŀ</th>
						<th rowspan="2">����</th>
						<th colspan="4">������<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="������"/></th>
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
						<th rowspan="2">��ע</th>
					</tr>
					<tr class="main_tr_header">
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
					</tr>
				</thead>
				<tbody id="projectRentalTbody">
                    <tr id="tr0" rowNum="0">
                        <td><img src="images/removeline.png" onclick="delProjectRentalRow(0);" title="ɾ����"/>$isServerCostStr</td>
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
	 * ��ȡ�༭ҳ��
	 */
	function getEditPage_d($mainId){
		$str = "";
		$projectRentalInfo = $this->getProjectRentalInfo_d($mainId);
		if($projectRentalInfo){
			//����ʽ��
			$projectRentalInfo = $this->dataUnFormat_d($projectRentalInfo);

			//ȡ����Ӧ����Ϣ
			$supplierArr = $projectRentalInfo['supplier'];
			unset($projectRentalInfo['supplier']);
			//ȡ��������Ϣ
			$costTypeGroup = $projectRentalInfo['costTypeGroup'];
			unset($projectRentalInfo['costTypeGroup']);

			$tdStr = "";
			$i = 0;
			$costTypeMark = "";//������Ŀ��¼
			$serviceCostMark = 0;
		    //��ȡ��һ������
		    $datadictDao = new model_system_datadict_datadict();
			foreach($projectRentalInfo as $val){

				//����ѡ����Ⱦ
		        $parentArr = $datadictDao->getDatadictsByParentCodes('WBHTFYX');
		        $parentOptionStr = $this->getDatadictsStr($parentArr['WBHTFYX'],$val['supplier1']['parent']);//���ظ���ѡ��

				//���������ֵ���չ�ֶ�1�ж϶���ѡ����ѡ��ǿ���
				if($val['supplier1']['isCustom'] == "0"){//ѡ��
			        $costTypeArr = $datadictDao->getDatadictsByParentCodes($val['supplier1']['parent']);//��ȡ��ϸ����
			        $costTypeOptionStr = $this->getDatadictsStr($costTypeArr[$val['supplier1']['parent']],$val['supplier1']['costType']);//���ظ���ѡ��
			        $costTypeStr =<<<EOT
						<select name="outsourcing[projectRental][$i][costType]" id="costType0" style="width:65px;">$costTypeOptionStr</select>
						<input type="hidden" name="outsourcing[projectRental][$i][isCustom]" id="isCustom$i" value="0"/>
EOT;
				}else{//����
			        $costTypeStr =<<<EOT
						<input name="outsourcing[projectRental][$i][costTypeName]" id="costTypeName$i" class="rimless_textB" style="width:65px;" value="{$val['supplier1']['costTypeName']}"/>
						<input type="hidden" name="outsourcing[projectRental][$i][isCustom]" id="isCustom$i" value="1"/>
EOT;
				}

				//������չ�ֶ�3�жϴ˷����Ƿ�Ϊ�������
				$isServerCost = $val['supplier1']['isServerCost'] == "1" ? 1 : 0;
				$isServerCostStr =<<<EOT
					<input type="hidden" name="outsourcing[projectRental][$i][isServerCost]" id="isServerCost$i" value="$isServerCost"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier1][id]" id="supplier1_id$i" value="{$val['supplier1']['id']}"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier2][id]" id="supplier2_id$i" value="{$val['supplier2']['id']}"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier3][id]" id="supplier3_id$i" value="{$val['supplier3']['id']}"/>
					<input type="hidden" name="outsourcing[projectRental][$i][supplier4][id]" id="supplier4_id$i" value="{$val['supplier4']['id']}"/>
EOT;

				//�������
				if($val['supplier1']['isDetail'] == "1"){
					$tdStr .=<<<EOT
                    	<tr id="tr$i" rowNum="$i">
                        	<td><img src="images/removeline.png" onclick="delProjectRentalRow($i);" title="ɾ����"/>$isServerCostStr</td>
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
                        	<td><img src="images/removeline.png" onclick="delProjectRentalRow($i);" title="ɾ����"/>$isServerCostStr</td>
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

			//������� - ������ͷ
			$checked2 = $checked3 = $checked4 = '';
			switch ($supplierArr['checkSupplier']) {
				case 'supplier2': $checked2 = 'checked="checked"';break;
				case 'supplier3': $checked3 = 'checked="checked"';break;
				case 'supplier4': $checked4 = 'checked="checked"';break;
				default: break;
			}

			//�б�
			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
		                    <th rowspan="2">
		                        <input type="hidden" id="projectRentalRowNum" value="$i"/>
		                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="�����"/>
		                    </th>
							<th rowspan="2">��Ŀ</th>
							<th rowspan="2">����</th>
							<th colspan="4">������<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="������"/></th>
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
							<th rowspan="2">��ע</th>
						</tr>
						<tr class="main_tr_header">
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						</tr>
					</thead>
					<tbody id="projectRentalTbody">
	                    $tdStr
	                </tbody>
				</table>
EOT;
		}else{
			//�б�
			$str =<<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
		                    <th rowspan="2">
		                        <input type="hidden" id="projectRentalRowNum" value="-1"/>
		                        <img src="images/add_item.png" onclick="addProjectRentalRow();" title="�����"/>
		                    </th>
							<th rowspan="2">��Ŀ</th>
							<th rowspan="2">����</th>
							<th colspan="4">������<input type="hidden" id="supplier1" name="outsourcing[projectRental][supplier][supplier1]" value="������"/></th>
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
							<th rowspan="2">��ע</th>
						</tr>
						<tr class="main_tr_header">
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
						</tr>
					</thead>
					<tbody id="projectRentalTbody"></tbody>
				</table>
EOT;
		}
		return $str;
	}

	/**
	 * �鿴ҳ����Ⱦ
	 */
	function getViewPage_d($mainId){
		$str = "";
		$projectRentalInfo = $this->getProjectRentalInfo_d($mainId);
		if($projectRentalInfo){
			//����ʽ��
			$projectRentalInfo = $this->dataUnFormat_d($projectRentalInfo);

			//ȡ����Ӧ����Ϣ
			$supplierArr = $projectRentalInfo['supplier'];
			unset($projectRentalInfo['supplier']);
			//ȡ��������Ϣ
			$costTypeGroup = $projectRentalInfo['costTypeGroup'];
			unset($projectRentalInfo['costTypeGroup']);

			//������� - ������ͷ
			$supplier2 = $supplier3 = $supplier4 = '';
			switch ($supplierArr['checkSupplier']) {
				case 'supplier2': $supplier2 = 'font-weight:bold;';$appandInfo2 = '[ѡ��]';$checkSupplier2 = $supplierArr[$supplierArr['checkSupplier']];break;
				case 'supplier3': $supplier3 = 'font-weight:bold;';$appandInfo3 = '[ѡ��]';$checkSupplier3 = $supplierArr[$supplierArr['checkSupplier']];break;
				case 'supplier4': $supplier4 = 'font-weight:bold;';$appandInfo4 = '[ѡ��]';$checkSupplier4 = $supplierArr[$supplierArr['checkSupplier']];break;
				default: break;
			}

			$tdStr = "";
			$i = 0;
			$costTypeMark = "";//������Ŀ��¼
			$serviceCostMark = 0;
			foreach($projectRentalInfo as $val){
				//��ͷ
				if($costTypeMark != $val['supplier1']['parent']){
					$trClass = $i%2==0 ? 'tr_odd' : 'tr_even';
					$i++;
					$costTypeMark = $val['supplier1']['parent'];
					$rowLength = $costTypeGroup[$val['supplier1']['parent']]['rowLength'];
					$costTypeStr=<<<EOT
						<td style="text-align:left;" rowspan="$rowLength">{$val['supplier1']['parentName']}</td>
EOT;

					//����ɱ�����
					if(empty($serviceCostMark) && empty($val['supplier1']['isServerCost'])){
						$serviceCostMark = 1;
						//����ɱ�
						$tdStr.=<<<EOT
							<tr class="tr_count">
				            	<td style="text-align:left;" colspan="2">�� �� �� ��</td>
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
				//����ɱ�����
				if(empty($serviceCostMark) && empty($val['supplier1']['isServerCost'])){
					$serviceCostMark = 1;
					//����ɱ�
					$tdStr.=<<<EOT
						<tr class="tr_count">
			            	<td style="text-align:left;" colspan="2">�� �� �� ��</td>
			                <td class="formatMoney" style="text-align:right;" colspan="4">{$costTypeGroup['supplier1']['serviceCost']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier2" colspan="4">{$costTypeGroup['supplier2']['serviceCost']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier3" colspan="4">{$costTypeGroup['supplier3']['serviceCost']}</td>
			                <td class="formatMoney" style="text-align:right;$supplier4" colspan="4">{$costTypeGroup['supplier4']['serviceCost']}</td>
			                <td></td>
			            </tr>
EOT;
				}
			}

			//�ܳɱ�
			$allCost=<<<EOT
				<tr class="tr_count">
	            	<td style="text-align:left;" colspan="2">��Ŀ�ܳɱ�</td>
	                <td class="formatMoney" style="text-align:right;" colspan="4">{$costTypeGroup['supplier1']['allCost']}</td>
	                <td class="formatMoney" style="text-align:right;$supplier2" colspan="4">{$costTypeGroup['supplier2']['allCost']}</td>
	                <td class="formatMoney" style="text-align:right;$supplier3" colspan="4">{$costTypeGroup['supplier3']['allCost']}</td>
	                <td class="formatMoney" style="text-align:right;$supplier4" colspan="4">{$costTypeGroup['supplier4']['allCost']}</td>
	                <td></td>
	            </tr>
EOT;
			//����ѡ�ù�Ӧ��
			$supplierStr=<<<EOT
				<tr class="tr_odd" style="color:blue;">
	            	<td style="text-align:left;" colspan="2">����ѡ�ù�Ӧ��</td>
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
							<th rowspan="2">��Ŀ</th>
							<th rowspan="2">����</th>
							<th colspan="4">������</th>
							<th colspan="4" style="$supplier2">
								{$supplierArr['supplier2']}$appandInfo2
							</th>
							<th colspan="4" style="$supplier3">
								{$supplierArr['supplier3']}$appandInfo3
							</th>
							<th colspan="4" style="$supplier4">
								{$supplierArr['supplier4']}$appandInfo4
							</th>
							<th rowspan="2" width="80px">��ע</th>
						</tr>
						<tr class="main_tr_header">
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
							<th>�۸�</th><th>����</th><th>����</th><th>С��</th>
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
	 * ��ȡ����
	 */
	function getProjectRentalInfo_d($mainId){
		$this->searchArr = array('mainId' => $mainId);
		$this->sort = "parent,sysNo";
		$this->asc = false;
		return $this->list_d();
	}

	/**
	 * ��ʽ���������� - ת��������������
	 */
	function dataFormat_d($object){
		$newArr = array();
		if($object){
			$supplierArr = $object['supplier'];//��ȡ����Ӧ����Ϣ
			unset($object['supplier']);

			foreach($object as $val){
				$i = 1;
				$groupKey = md5(microtime());
				while($i < 5){
					$supplier = 'supplier'.$i;//��ǰѭ����Ӧ��
					$sysNo = $i;//ϵͳ���
					$i++;
					if(empty($supplierArr[$supplier]) || $val[$supplier]['amount'] == 0) continue;//�����ǰ��Ӧ��Ϊ�ջ��߽��Ϊ0,�����������

					$rentalDetail = array(//��������
						'isServerCost' => $val['isServerCost'],'parent' => $val['parent'],
						'costTypeName' => $val['costTypeName'],'costType' => $val['costType'],
						'isDetail' => $val['isDetail'],'isCustom' => $val['isCustom'],
						'price' => $val[$supplier]['price'],'number' => $val[$supplier]['number'],
						'period' => $val[$supplier]['period'],'amount' => $val[$supplier]['amount'],
						'remark' => $val['remark'],'supplierName' => $supplierArr[$supplier],'sysNo' => $sysNo,
						'groupKey' => $groupKey
					);

					if(isset($val[$supplier]['id'])) $rentalDetail['id'] = $val[$supplier]['id'];//����idʱ������
					if(isset($val['isDelTag'])) $rentalDetail['isDelTag'] = $val['isDelTag'];//����idʱ������

					if($i != 2) $rentalDetail['isSelf'] = 0;//�Ǳ���˾

					if($supplierArr['checkSupplier'] == $supplier){//����ѡ�й�Ӧ��
						$rentalDetail['isChoosed'] = 1;
					}

					array_push($newArr,$rentalDetail);
				}
			}
		}
		return $newArr;
	}

	/**
	 * ��ʽ���������� - ת����ʾ�õ�����
	 */
	function dataUnFormat_d($object){
		$newArr = array();
		if($object){
			$supplierArr = array();//��Ӧ����Ϣ
			$costTypeGroup = array();//���÷���

			foreach($object as $val){
				$supplierNo = 'supplier'.$val['sysNo'];
				//������Ӧ����Ϣ
				if(!isset($supplierArr[$supplierNo])){
					$supplierArr[$supplierNo] = $val['supplierName'];//��Ӧ�����ݸ�ֵ
					if($val['isChoosed'] == 1) $supplierArr['checkSupplier'] = $supplierNo;
				}
				//��������
				$newArr[$val['groupKey']][$supplierNo] = $val;

				//�������ɱ��������ɱ�
				if($val['isServerCost'] == 1){
					$costTypeGroup[$supplierNo]['serviceCost'] = isset($costTypeGroup[$supplierNo]['serviceCost']) ? bcadd($costTypeGroup[$supplierNo]['serviceCost'],$val['amount'],2) : $val['amount'];
				}else{
					$costTypeGroup[$supplierNo]['otherCost'] = isset($costTypeGroup[$supplierNo]['otherCost']) ? bcadd($costTypeGroup[$supplierNo]['otherCost'],$val['amount'],2) : $val['amount'];
				}
			}

			//�ܳɱ�
			$costTypeGroup['supplier1']['allCost'] = bcadd($costTypeGroup['supplier1']['serviceCost'],$costTypeGroup['supplier1']['otherCost'],2);
			$costTypeGroup['supplier2']['allCost'] = bcadd($costTypeGroup['supplier2']['serviceCost'],$costTypeGroup['supplier2']['otherCost'],2);
			$costTypeGroup['supplier3']['allCost'] = bcadd($costTypeGroup['supplier3']['serviceCost'],$costTypeGroup['supplier3']['otherCost'],2);
			$costTypeGroup['supplier4']['allCost'] = bcadd($costTypeGroup['supplier4']['serviceCost'],$costTypeGroup['supplier4']['otherCost'],2);

			//ѭ���������÷���
			foreach($newArr as $val){
				//������Ŀ��
				$costTypeGroup[$val['supplier1']['parent']]['rowLength'] = isset($costTypeGroup[$val['supplier1']['parent']]['rowLength']) ? $costTypeGroup[$val['supplier1']['parent']]['rowLength'] + 1 : 1;
			}

			$newArr['supplier'] = $supplierArr;
			$newArr['costTypeGroup'] = $costTypeGroup;
		}
		return $newArr;
	}

	//��дadd
	public function add_d($object){
		$object = $this->processDatadict($object);
		return parent::add_d($object);
	}

	//��дedit
	public function edit_d($object){
		$object = $this->processDatadict($object);
		return parent::edit_d($object);
	}

	//ɾ��������Ϣ
	function delItemInfo_d($id) {
		$sql = "delete from " . $this->tbl_name . " where mainId = '$id' ";
		return $this->query($sql);
	}
}
?>