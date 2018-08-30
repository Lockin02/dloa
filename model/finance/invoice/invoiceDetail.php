<?php
/*
 * Created on 2010-12-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_finance_invoice_invoiceDetail extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoice_detail";
        $this->sql_map = "finance/invoice/invoiceDetailSql.php";
        parent::__construct();
    }

    /******************************************界面显示***********************************************/

    /**开票详细编辑列表
     *author can
     *2010-12-24
     */
    function detailEdit($rows) {
        $i = 0;
        $str = "";
        if ($rows) {
            $datadictArr = $this->getDatadicts('CPFWLX');
            foreach ($rows as $val) {
                $i++;
                $productTypeStr = $this->getDatadictsStr($datadictArr ['CPFWLX'], $val['psType']);
                $str .= <<<EOT
				<tr align="center">
				   <td>$i</td>
				   <td>
				       <input type="hidden" name="invoice[invoiceDetail][$i][productId]" id="invoiceEquId$i" value="$val[productId]"/>
				       <input type="text" class="txtmiddle" name="invoice[invoiceDetail][$i][productName]" id="invoiceEquName$i" value="$val[productName]"/>
				   </td>
					<td>
						<input type="text" class="txtshort" name="invoice[invoiceDetail][$i][amount]" id="amount$i" value="$val[amount]" style="width:50px;" onblur="countDetail(this)"/>
					</td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][softMoney]" id="softMoney$i" value="$val[softMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][hardMoney]" id="hardMoney$i" value="$val[hardMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][repairMoney]" id="repairMoney$i" value="$val[repairMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][serviceMoney]" id="serviceMoney$i" value="$val[serviceMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][equRentalMoney]" id="equRentalMoney$i" value="$val[equRentalMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][spaceRentalMoney]" id="spaceRentalMoney$i" value="$val[spaceRentalMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][otherMoney]" id="otherMoney$i" value="$val[otherMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][dsEnergyCharge]" id="dsEnergyCharge$i" value="$val[dsEnergyCharge]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][dsWaterRateMoney]" id="dsWaterRateMoney$i" value="$val[dsWaterRateMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][houseRentalFee]" id="houseRentalFee$i" value="$val[houseRentalFee]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][installationCost]" id="installationCost$i" value="$val[installationCost]" onblur="countDetail(this)"/></td>
					<td>
						<select id="psType$i" name="invoice[invoiceDetail][$i][psType]" class="txtmiddle" style="width:70px;">
							$productTypeStr
						</select>
					</td>
					<td>
						<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行">
					</td>
				</tr>
EOT;
            }
        } else {
            $str = '<tr align="center"><td colspan="40">没有发票详细</td></tr>';
        }
        return array($str, $i);
    }

    /**
     * 开票详细从表查看
     */
    function detailView($rows) {
        $i = 0;
        $str = "";
        if ($rows) {
            $dataDictDao = new model_system_datadict_datadict();
            foreach ($rows as $val) {
                $i++;
                $psType = $dataDictDao->getDataNameByCode($val['psType']);
                $str .= <<<EOT
					<tr align="center">
						<td>$i</td>
						<td>$val[productName]</td>
						<td>$val[amount]</td>
						<td class="formatMoney">$val[softMoney]</td>
						<td class="formatMoney">$val[hardMoney]</td>
						<td class="formatMoney">$val[repairMoney]</td>
						<td class="formatMoney">$val[serviceMoney]</td>
						<td class="formatMoney">$val[equRentalMoney]</td>
						<td class="formatMoney">$val[spaceRentalMoney]</td>
						<td class="formatMoney">$val[otherMoney]</td>
						<td class="formatMoney">$val[dsEnergyCharge]</td>
                        <td class="formatMoney">$val[dsWaterRateMoney]</td>
                        <td class="formatMoney">$val[houseRentalFee]</td>
                        <td class="formatMoney">$val[installationCost]</td>
						<td>$psType</td>
					</tr>
EOT;
            }
        } else {
            $str = '<tr align="center"><td colspan="40">没有发票详细</td></tr>';
        }
        return array($str, $i);

    }

    /**
     * 生成红字发票时从表
     */
    function detailRed($rows) {
        $i = 0;
        $str = "";
        if ($rows) {
            $dataDictDao = new model_system_datadict_datadict();
            foreach ($rows as $val) {
                $i++;
                $psType = $dataDictDao->getDataNameByCode($val['psType']);
                $str .= <<<EOT
                    <tr align="center">
                        <td>$i</td>
                        <td>$val[productName]</td>
                        <td>$val[amount]</td>
                        <td class="formatMoney">$val[softMoney]</td>
                        <td class="formatMoney">$val[hardMoney]</td>
                        <td class="formatMoney">$val[repairMoney]</td>
                        <td class="formatMoney">$val[serviceMoney]</td>
                        <td class="formatMoney">$val[equRentalMoney]</td>
                        <td class="formatMoney">$val[spaceRentalMoney]</td>
                        <td class="formatMoney">$val[otherMoney]</td>
                        <td class="formatMoney">$val[dsEnergyCharge]</td>
                        <td class="formatMoney">$val[dsWaterRateMoney]</td>
                        <td class="formatMoney">$val[houseRentalFee]</td>
                        <td class="formatMoney">$val[installationCost]</td>
                        <td>$psType
                            <input type="hidden" name="invoice[invoiceDetail][$i][productId]" value="$val[productId]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][productName]" value="$val[productName]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][amount]" value="$val[amount]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][softMoney]" value="$val[softMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][hardMoney]" value="$val[hardMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][repairMoney]" value="$val[repairMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][serviceMoney]" value="$val[serviceMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][equRentalMoney]" value="$val[equRentalMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][spaceRentalMoney]" value="$val[spaceRentalMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][otherMoney]" value="$val[otherMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][dsEnergyCharge]" value="$val[dsEnergyCharge]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][dsWaterRateMoney]" value="$val[dsWaterRateMoney]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][houseRentalFee]" value="$val[houseRentalFee]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][installationCost]" value="$val[installationCost]"/>
                            <input type="hidden" name="invoice[invoiceDetail][$i][psType]" value="$val[psType]"/>
                        </td>
                    </tr>
EOT;
            }
        } else {
            $str = '<tr align="center"><td colspan="40">没有发票详细</td></tr>';
        }
        return array($str, $i);

    }

    /********************************************业务操作*********************************************/

    /**显示开票详细
     *2010-12-25
     */
    function getDetailByInvoiceId($invoiceId) {
        return $this->findAll(array('invoiceId' => $invoiceId));
    }

    /**显示开票详细
     *2010-12-25
     */
    function getDetailByInvoiceIds($invoiceIds) {
        $this->searchArr['invoiceIds'] = $invoiceIds;
        return $this->listBySqlId('select_easy');
    }

    /**显示开票详细
     *  2011-10-08
     */
    function getDetailByGroup_d($invoiceIds) {
        $this->searchArr['invoiceIds'] = $invoiceIds;
        $this->searchArr['parentCode'] = 'CPFWLX';
        $this->groupBy = 'c.invoiceId';
        return $this->listBySqlId('select_group');
    }
}