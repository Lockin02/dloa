<?php
/*
 * Created on 2010-11-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_finance_invoiceapply_invoiceDetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invoiceapply_detail";
		$this->sql_map = "finance/invoiceapply/invoiceapplySql.php";
		parent::__construct ();
	}

	/******************************************界面显示***********************************************/
	/**
	 * 编辑列表显示
	 */
	function rowsToEdit($rows){
		$i = 0;
		$readonly = "";
		$allThisMoney = $softMoney = $hardMoney = $repairMoney = $serviceMoney = $equRentalMoney = $spaceRentalMoney = $otherMoney = 0;
		$str = "";
		if($rows){
			$dataDictDao = new model_system_datadict_datadict();
			$datadictArr = $this->getDatadicts ( 'CPFWLX' );
			foreach($rows as $val){
				$i ++;
				$softMoney = bcadd($softMoney,$val['softMoney'],2);
				$hardMoney = bcadd($hardMoney,$val['hardMoney'],2);
				$repairMoney = bcadd($repairMoney,$val['repairMoney'],2);
				$serviceMoney = bcadd($serviceMoney,$val['serviceMoney'],2);
				$equRentalMoney = bcadd($equRentalMoney,$val['equRentalMoney'],2);
				$spaceRentalMoney = bcadd($spaceRentalMoney,$val['spaceRentalMoney'],2);
				$otherMoney = bcadd($otherMoney,$val['otherMoney'],2);
				$productTypeStr = $this->getDatadictsStr ( $datadictArr ['CPFWLX'],$val['psTyle']);
				$rsCode = $dataDictDao->getDataNameByCode($val['productId'] ,'none');
				if($rsCode){
					$readonly = "readonly='readonly'";
				}
				$str .=<<<EOT
				<tr>
					<td>$i</td>
					<td>
						<input type="text" class="txtmiddle" name="invoiceapply[invoiceDetail][$i][productName]" id="invoiceEquName$i" value="$val[productName]" $readonly/>
						<input type="hidden" name="invoiceapply[invoiceDetail][$i][productId]" id="invoiceEquId$i" value="$val[productId]"/>
					</td>
					<td>
						<input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][amount]" id="amount$i" value="$val[amount]"  onblur="countDetail(this)" style="width:50px;"/>
					</td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][softMoney]" id="softMoney$i" value="$val[softMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][hardMoney]" id="hardMoney$i" value="$val[hardMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][repairMoney]" id="repairMoney$i" value="$val[repairMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][serviceMoney]" id="serviceMoney$i" value="$val[serviceMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][equRentalMoney]" id="equRentalMoney$i" value="$val[equRentalMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][spaceRentalMoney]" id="spaceRentalMoney$i" value="$val[spaceRentalMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][otherMoney]" id="otherMoney$i" value="$val[otherMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][dsEnergyCharge]" id="dsEnergyCharge$i" value="$val[dsEnergyCharge]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][dsWaterRateMoney]" id="dsWaterRateMoney$i" value="$val[dsWaterRateMoney]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][houseRentalFee]" id="houseRentalFee$i" value="$val[houseRentalFee]" onblur="countDetail(this)"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoiceapply[invoiceDetail][$i][installationCost]" id="installationCost$i" value="$val[installationCost]" onblur="countDetail(this)"/></td>
					<td>
						<select id="psType$i" name="invoiceapply[invoiceDetail][$i][psTyle]" class="txtmiddle" style="width:90px;">
							$productTypeStr
						</select></td>
					<td><input type="text" class="txtmiddle" name="invoiceapply[invoiceDetail][$i][remark]" value="$val[remark]" style="width:100px;"/></td>
					<td>
						<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行">
					</td>
				</tr>
EOT;
			}
		}
		$allThisMoney = bcadd($softMoney,$allThisMoney,2);
		$allThisMoney = bcadd($hardMoney,$allThisMoney,2);
		$allThisMoney = bcadd($repairMoney,$allThisMoney,2);
		$allThisMoney = bcadd($serviceMoney,$allThisMoney,2);
		$allThisMoney = bcadd($equRentalMoney,$allThisMoney,2);
		$allThisMoney = bcadd($spaceRentalMoney,$allThisMoney,2);
		$allThisMoney = bcadd($otherMoney,$allThisMoney,2);
		return array($str,$i,$allThisMoney,$softMoney,$hardMoney,$repairMoney,$serviceMoney,$equRentalMoney,$spaceRentalMoney,$otherMoney);
	}

	/**
	 * 发票登记时填写从表
	 */
	function rowsToRegister($rows){
		$i = 0;
		$allThisMoney = $softMoney = $hardMoney = $repairMoney = $serviceMoney = $equRentalMoney = $spaceRentalMoney = $otherMoney = $dsEnergyCharge= $dsWaterRateMoney= $houseRentalFee= $installationCost = 0;
		$str = "";
		if($rows){
			$datadictArr = $this->getDatadicts ( 'CPFWLX' );
			foreach($rows as $val){
				$i ++;
				$softMoney = bcadd($softMoney,$val['softMoney'],2);
				$hardMoney = bcadd($hardMoney,$val['hardMoney'],2);
				$repairMoney = bcadd($repairMoney,$val['repairMoney'],2);
				$serviceMoney = bcadd($serviceMoney,$val['serviceMoney'],2);
				$equRentalMoney = bcadd($equRentalMoney,$val['equRentalMoney'],2);
				$spaceRentalMoney = bcadd($spaceRentalMoney,$val['spaceRentalMoney'],2);
				$otherMoney = bcadd($otherMoney,$val['otherMoney'],2);
                $dsEnergyCharge = bcadd($dsEnergyCharge,$val['dsEnergyCharge'],2);
                $dsWaterRateMoney = bcadd($dsWaterRateMoney,$val['dsWaterRateMoney'],2);
                $houseRentalFee = bcadd($houseRentalFee,$val['houseRentalFee'],2);
                $installationCost = bcadd($installationCost,$val['installationCost'],2);
				$productTypeStr = $this->getDatadictsStr ( $datadictArr ['CPFWLX'],$val['psTyle']);
				$str .=<<<EOT
				<tr><td>$i</td>
					<td>
						<input type="text" class="txtmiddle" readonly="readonly" name="invoice[invoiceDetail][$i][productName]" id="invoiceEquName$i" value="$val[productName]"/>
						<input type="hidden" name="invoice[invoiceDetail][$i][productId]" id="invoiceEquId$i" value="$val[productId]"/>
					</td>
					<td>
						<input type="text" class="txtshort formatMoney" style="width:50px;" name="invoice[invoiceDetail][$i][amount]" id="amount$i" value="$val[amount]"  onblur="countDetail()"/>
					</td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][softMoney]" id="softMoney$i" value="$val[softMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][hardMoney]" id="hardMoney$i" value="$val[hardMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][repairMoney]" id="repairMoney$i" value="$val[repairMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][serviceMoney]" id="serviceMoney$i" value="$val[serviceMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][equRentalMoney]" id="equRentalMoney$i" value="$val[equRentalMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][spaceRentalMoney]" id="spaceRentalMoney$i" value="$val[spaceRentalMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][otherMoney]" id="otherMoney$i" value="$val[otherMoney]" onblur="countDetail()"/></td>
					
                    <td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][dsEnergyCharge]" id="dsEnergyCharge$i" value="$val[dsEnergyCharge]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][dsWaterRateMoney]" id="dsWaterRateMoney$i" value="$val[dsWaterRateMoney]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][houseRentalFee]" id="houseRentalFee$i" value="$val[houseRentalFee]" onblur="countDetail()"/></td>
					<td><input type="text" class="txtshort formatMoney" name="invoice[invoiceDetail][$i][installationCost]" id="installationCost$i" value="$val[installationCost]" onblur="countDetail()"/></td>
					<td>
						<select id="psType$i" name="invoice[invoiceDetail][$i][psType]" class="txtmiddle" style="width:70px;">
							$productTypeStr
						</select>
					</td>
					<td>
						<img src="images/closeDiv.gif" onclick="mydel(this,'invbody')" title="删除行"/>
					</td>
				</tr>
EOT;
			}
		}
		$allThisMoney = bcadd($softMoney,$allThisMoney,2);
		$allThisMoney = bcadd($hardMoney,$allThisMoney,2);
		$allThisMoney = bcadd($repairMoney,$allThisMoney,2);
		$allThisMoney = bcadd($serviceMoney,$allThisMoney,2);
		$allThisMoney = bcadd($equRentalMoney,$allThisMoney,2);
		$allThisMoney = bcadd($spaceRentalMoney,$allThisMoney,2);
		$allThisMoney = bcadd($otherMoney,$allThisMoney,2);
        $allThisMoney = bcadd($dsEnergyCharge,$allThisMoney,2);
        $allThisMoney = bcadd($dsWaterRateMoney,$allThisMoney,2);
        $allThisMoney = bcadd($houseRentalFee,$allThisMoney,2);
        $allThisMoney = bcadd($installationCost,$allThisMoney,2);
		return array($str,$i,$allThisMoney,$softMoney,$hardMoney,$repairMoney,$serviceMoney,$equRentalMoney,$spaceRentalMoney,$otherMoney,$dsEnergyCharge,$dsWaterRateMoney,$houseRentalFee,$installationCost);
	}

	/**
	 * 查看列表显示
	 */
	function rowsToView($rows){
		$i = 0;
		$str = null;
		if($rows){
			$dataDictDao = new model_system_datadict_datadict();
			foreach($rows as $val){
				$i ++;
				$psTyle = $dataDictDao->getDataNameByCode($val['psTyle']);
				$str .=<<<EOT
				<tr>
					<td>$i</td>
					<td>
						{$val['productName']}
					</td>
					<td class='formatMoney'>
						{$val['amount']}
					</td>
					<td class='formatMoney'>
						{$val['softMoney']}
					</td>
					<td class='formatMoney'>
						{$val['hardMoney']}
					</td>
					<td class='formatMoney'>
						{$val['repairMoney']}
					</td>
					<td class='formatMoney'>
						{$val['serviceMoney']}
					</td>
					<td class='formatMoney'>
						{$val['equRentalMoney']}
					</td>
					<td class='formatMoney'>
						{$val['spaceRentalMoney']}
					</td>
					<td class='formatMoney'>
						{$val['otherMoney']}
					</td>
					<td class='formatMoney'>
						{$val['dsEnergyCharge']}
					</td>
					<td class='formatMoney'>
						{$val['dsWaterRateMoney']}
					</td>
					<td class='formatMoney'>
						{$val['houseRentalFee']}
					</td>
					<td class='formatMoney'>
						{$val['installationCost']}
					</td>
					<td>$psTyle</td>
					<td>{$val['remark']}</td>
				</tr>
EOT;
			}
		}else{
			$str= '<tr align="center"><td colspan="20">没有开票详细信息</td></tr>';
		}
		return array($str,$i);
	}

	/********************************************业务操作*********************************************/
	/**
	 *  显示开票信息详情
	 */
	function getDetailByInvoiceApplyId($applyId){
		return $this->findAll(array('invoiceApplyId' => $applyId));
	}
}