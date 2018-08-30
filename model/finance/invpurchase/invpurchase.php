<?php
/**
 * @author Show
 * @Date 2010��12��21�� ���ڶ� 15:52:09
 * @version 1.0
 * @description:�ɹ���Ʊ Model��
 */
 class model_finance_invpurchase_invpurchase  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invpurchase";
		$this->sql_map = "finance/invpurchase/invpurchaseSql.php";
		parent::__construct ();
	}

	/**
	 * �����ǻ��
	 */
	function returnZeroOrOne($val){
		if($val){
			return '�ѹ���';
		}else{
			return 'δ����';
		}
	}

	/**
	 * �ʼ����û�ȡ
	 */
	function getMailInfo_d(){

		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser['invpurchase']) ? $mailUser['invpurchase'] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}
	
	//��˾Ȩ�޴��� TODO
	protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
	

	/*******************************ҳ����ʾ*****************************/

	/**
	 * �б������ϸ
	 */
	function showlistDetail($rows){
		if($rows){
			$invDetailDao = new model_finance_invpurchase_invpurdetail();
			$i = 0;
			$str = null;
			$mark = null;
			$ids = null;
			$listNumber = 0;
			$listAmount = 0;
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$i++;
				$mark = 'inv_'.$val['id'];
				$ids .= $val['id'].',';
				$status = $this->returnZeroOrOne($val['status']);
				$id = $val['id'];
				if($val['formType'] == 'red'){
					$objCode = '<span class="red">'.$val['objCode'].'</sapn>';
				}else{
					$objCode = $val['objCode'];
				}
				$str.=<<<EOT
					<tr class="$classCss" title="$id">
						<td>
							<image src="images/collapsed.gif" id="changeTab$i" title="$i" />
							$i
							<input type="hidden" id="status_$id" value="$val[status]"/>
							<input type="hidden" id="belongId_$id" value="$val[belongId]"/>
							<input type="hidden" id="payStatus_$id" value="$val[payStatus]"/>
						</td>
						<td align="left">
							$objCode
						</td>
						<td>
							$val[supplierName]
						</td>
						<td>
							$val[payDate]
						</td>
						<td>
							$val[salesman]
						</td>
						<td>
							$status
						</td>
						<td width="45%" class="td_table">
							<table id="table$i" width="100%" class="main_table_nested">
								{{$mark}}
							</table>
							<div id="inputDiv$i" title="$i"><���չ�����豸������Ϣ>
						</td>
					</tr>
EOT;
			}
			$detailRows = $invDetailDao->getDetailByIds_d(substr($ids,0,-1));
			$deStr = null;
			$deArr = array();
			foreach($detailRows as $val){
				$listNumber += $val['number'];
				$listAmount += $val['amount'];
				$deStr =<<<EOT
					<tr align="left" height="30px">
						<td width="16%">
							$val[productNo]
						</td>
						<td width="25%">
							$val[productName]
						</td>
						<td width="12%">
							$val[unit]
						</td>
						<td width="15%">
							$val[number]
						</td>
						<td width="16%" class="formatMoney">
							$val[price]
						</td>
						<td width="16%" class="formatMoney">
							$val[amount]
						</td>
					</tr>
EOT;
				if(isset($deArr['{inv_'.$val['invPurId'].'}'])){
					$deArr['{inv_'.$val['invPurId'].'}'].= $deStr;
				}else{
					$deArr['{inv_'.$val['invPurId'].'}'] = $deStr;
				}
			}
			$str = strtr($str,$deArr);

			foreach($rows as $key => $val){
				$str = strtr($str,array('{inv_'.$val['id'].'}' => ''));
			}

			$resultStr =<<<EOT
				<tr class="$classCss">
							<td colspan="6"></td>
							<td width="45%" class="td_table">
								<table width="100%" class="main_table_nested">
									<tr align="left" height="30px">
										<td width="16%">�ܼ�:</td>
										<td width="25%"></td>
										<td width="12%"></td>
										<td width="15%">$listNumber</td>
										<td width="16%"></td>
										<td width="16%" class="formatMoney">$listAmount</td>
									</tr>
								</table>
							</td>
						</tr>
EOT;
			$str .=$resultStr;
		}else {
			$str = '<tr><td height="28px" colspan="20">���������Ϣ</td></tr>';
		}
		return $str;
	}

	/**
	 * �б������ϸ
	 */
	function showlistDetailInPurCont($rows){
		if($rows){
			$invDetailDao = new model_finance_invpurchase_invpurdetail();
			$i = 0;
			$str = null;
			$mark = null;
			$ids = null;
			$listNumber = 0;
			$listAmount = 0;
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$i++;
				$mark = 'inv_'.$val['id'];
				$ids .= $val['id'].',';
				$status = $this->returnZeroOrOne($val['status']);
				$id = $val['id'];
				if($val['formType'] == 'red'){
					$objCode = '<span class="red">'.$val['objCode'].'</sapn>';
				}else{
					$objCode = $val['objCode'];
				}
				$str.=<<<EOT
					<tr class="$classCss" title="$id">
						<td>
							<image src="images/collapsed.gif" id="changeTab$i" title="$i" />
							$i
							<input type="hidden" id="status_$id" value="$val[status]"/>
							<input type="hidden" id="belongId_$id" value="$val[belongId]"/>
						</td>
						<td align="left">
							$objCode
						</td>
						<td>
							$val[supplierName]
						</td>
						<td width="9%">
							$val[payDate]
						</td>
						<td width="8%">
							$status
						</td>
						<td width="45%" class="td_table">
							<table id="table$i" width="100%" class="main_table_nested">
								{{$mark}}
							</table>
							<div id="inputDiv$i" title="$i"><���չ�����豸������Ϣ>
						</td>
					</tr>
EOT;
			}
			$detailRows = $invDetailDao->getDetailByIds_d(substr($ids,0,-1));
			$deStr = null;
			$deArr = array();
			foreach($detailRows as $val){
				$listNumber += $val['number'];
				$listAmount += $val['amount'];
				$deStr =<<<EOT
					<tr align="left" height="30px">
						<td width="16%">
							$val[productNo]
						</td>
						<td width="25%">
							$val[productName]
						</td>
						<td width="12%">
							$val[unit]
						</td>
						<td width="15%">
							$val[number]
						</td>
						<td width="16%" class="formatMoney">
							$val[price]
						</td>
						<td width="16%" class="formatMoney">
							$val[amount]
						</td>
					</tr>
EOT;
				if(isset($deArr['{inv_'.$val['invPurId'].'}'])){
					$deArr['{inv_'.$val['invPurId'].'}'].= $deStr;
				}else{
					$deArr['{inv_'.$val['invPurId'].'}'] = $deStr;
				}
			}
			$str = strtr($str,$deArr);

			foreach($rows as $key => $val){
				$str = strtr($str,array('{inv_'.$val['id'].'}' => ''));
			}

			$resultStr =<<<EOT
				<tr class="$classCss">
					<td colspan="5"></td>
					<td width="45%" class="td_table">
						<table width="100%" class="main_table_nested">
							<tr align="left" height="30px">
								<td width="16%">�ܼ�:</td>
								<td width="25%"></td>
								<td width="12%"></td>
								<td width="15%">$listNumber</td>
								<td width="16%"></td>
								<td width="16%" class="formatMoney">$listAmount</td>
							</tr>
						</table>
					</td>
				</tr>
EOT;
			$str .=$resultStr;
		}else {
			$str = '<tr><td height="28px" colspan="20">���������Ϣ</td></tr>';
		}
		return $str;
	}

	/**
	 * ����ҳ��ѡ����Ӳɹ���Ʊ
	 */
	function showlistHook($rows){
		if($rows){
			$invDetailDao = new model_finance_invpurchase_invpurdetail();
			$i = 0;
			$str = null;
			$mark = null;
			$datamark = null;
			$ids = null;
			$listNumber = 0;
			$listAmount = 0;
			$keyToRows = array();
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$i++;
				$mark = 'inv_'.$val['id'];
				$datamark = 'indata_'.$val['id'];
				$ids .= $val['id'].',';
				$status = $this->returnZeroOrOne($val['status']);
				$id = $val['id'];
				if($val['formType'] == 'red'){
					$objCode = '<span class="red">'.$val['objCode'].'</sapn>';
				}else{
					$objCode = $val['objCode'];
				}
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							<input type="hidden" name="inproductdata" value={{$datamark}}/>
							<input type="checkbox" name="datacb" id="$val[id]">
							<input type="hidden" id="status_$id" value="$val[status]"/>
							<input type="hidden" id="belongId_$id" value="$val[belongId]"/>
						</td>
                        <td width="14%">
                            $objCode
                        </td>
                        <td width="14%">
							$val[objNo]
						</td>
						<td>
							$val[formDate]
						</td>
						<td>
							$status
						</td>
						<td width="50%" class="td_table">
							<table id="table$i" width="100%" class="main_table_nested">
								{{$mark}}
							</table>
						</td>
					</tr>
EOT;
				$keyToRows[$val['id']] = $key;
			}
			$detailRows = $invDetailDao->getDetailByIds_d(substr($ids,0,-1),2);
			$deStr = null;
			$deArr = array();
			foreach($detailRows as $key => $val){
				if($val['formType'] == 'blue'){
					$number = $val['number'];
					$amount = $val['amount'];
				}else{
					$number = - $val['number'];
					$amount = - $val['amount'];
				}
                $listNumber = bcadd($listNumber,$number);
				$listAmount = bcadd($listAmount,$amount,2);
				$deStr =<<<EOT
					<tr align="left" height="28px">
						<td width="16%">
							$val[productNo]
						</td>
						<td width="25%">
							$val[productName]
						</td>
						<td width="12%">
							$number
						</td>
						<td width="14%" class="formatMoneySix">
							$val[price]
						</td>
						<td width="14%" class="formatMoney">
							$amount
						</td>
					</tr>
EOT;
				if(isset($deArr['{inv_'.$val['invPurId'].'}'])){
					$deArr['{inv_'.$val['invPurId'].'}'].= $deStr;
				}else{
					$deArr['{inv_'.$val['invPurId'].'}'] = $deStr;
				}

				if(isset($rows[$keyToRows[$val['invPurId']]]['products'])){
					array_push($rows[$keyToRows[$val['invPurId']]]['products'],$val);
				}else{
					$rows[$keyToRows[$val['invPurId']]]['products'][0] = $val;
				}
			}
			$str = strtr($str,$deArr);

			foreach($rows as $key => $val){
				$val['status'] = $this->returnZeroOrOne($val['status']);
				$str = strtr($str,array('{inv_'.$val['id'].'}' => ''));
				$str = strtr($str,array('{indata_'.$val['id'].'}' => "'".util_jsonUtil::encode( $val)."'" ));
			}

			$resultStr =<<<EOT
				<tr class="$classCss">
					<td colspan="5"></td>
					<td width="50%" class="td_table">
						<table width="100%" class="main_table_nested">
							<tr align="left" height="30px">
								<td width="16%">��ҳС��:</td>
								<td width="25%"></td>
								<td width="12%">$listNumber</td>
								<td width="14%"></td>
								<td width="14%" class="formatMoney">$listAmount</td>
							</tr>
						</table>
					</td>
				</tr>
EOT;
			$str .=$resultStr;


			$rs = $this->list_d('count_list2');
//			print_r($rs);
			if(is_array($rs)){
				$allNumber = $rs[0]['allNumber'];
				$allAmount = $rs[0]['allAmount'];
				$resultStr =<<<EOT
					<tr class="$classCss">
						<td colspan="5"></td>
						<td width="50%" class="td_table">
							<table width="100%" class="main_table_nested">
								<tr align="left" height="30px">
									<td width="16%">ȫ���ϼ�:</td>
									<td width="25%"></td>
									<td width="12%">$allNumber</td>
									<td width="14%"></td>
									<td width="14%" class="formatMoney">$allAmount</td>
								</tr>
							</table>
						</td>
					</tr>
EOT;
			$str .=$resultStr;
			}
		}else {
			$str = '<tr><td height="28px" colspan="20">���������Ϣ</td></tr>';
		}
		return $str;
	}


	/**
	 * ������ʾ�ɹ���Ʊ����
	 *
	 */
	function showHookList($rows,$read = true){
		$str = null;
		$i = 0;
		$mark  = null;
		$readonly = null;
		foreach($rows as $val){
			$i++ ;
			if($val['formType'] == 'blue'){
				$price = $val['price'];
			}else{
				$price = -$val['price'];
			}
			$id = $val['id'];
			$val['status'] = $this->returnZeroOrOne($val['status']);
//			if(!$read) $readonly = 'readonly="readonly"';
			$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
			$str.=<<<EOT
				<tr class="invList_$id $classCss"  title="$id">
					<td width="7%">
						<input type="hidden" name="invpurdetail[$i][hookMainId]" id="hookMainId$id" value="$id"/>
						<input type="hidden" name="invpurdetail[$i][hookId]" value="$val[detailId]"/>
						<input type="text" class="readOnlyTxtShort" readonly="readonly" name="invpurdetail[$i][number]" id="number$i" onblur="checkInput('number$i','oldnumber$i');FloatMul('number$i','price$i','amount$i',1)" value="$val[unHookNumber]"/>
						<input type="hidden" id="oldnumber$i" value="$val[unHookNumber]"/>
						<input type="hidden" name="invpurdetail[$i][hookObjCode]" value="$val[objCode]"/>
						<input type="hidden" name="invpurdetail[$i][formType]" id="formType$i" value="$val[formType]"/>
						<input type="hidden" name="invpurdetail[$i][formDate]" id="invFormDate$i" value="$val[formDate]"/>
					</td>
EOT;
			//�ж��Ǹ���һ����¼�Ƿ�����ͬһ����Ʊ,�ǵĻ�����ʾ��������
			if($mark == $val['objCode']){
				$str.=<<<EOT
						<td colspan="3"></td>
EOT;
			}else{
				if($val['formType'] == 'blue'){
					$thisCode = $val['objCode'];
				}else{
					$thisCode = '<font color="red">'.$val['objCode'].'</font>';
				}
				$str.=<<<EOT
						<td>
							$val[formDate]
						</td>
						<td align="left" width="12%">
							$thisCode
							<img src="images/closeDiv.gif" onclick="delInvPur($id)" title="ɾ����"/>
						</td>
						<td width="6%">
							$val[status]
						</td>
EOT;
			$mark = $val['objCode'];
			}
			$str.=<<<EOT
					<td>
						$val[productNo]
						<input type="hidden" name="invpurdetail[$i][hookNumber]" value="$val[hookNumber]"/>
						<input type="hidden" name="invpurdetail[$i][hookAmount]" value="$val[hookAmount]"/>
						<input type="hidden" name="invpurdetail[$i][unHookNumber]" value="$val[unHookNumber]"/>
						<input type="hidden" name="invpurdetail[$i][unHookAmount]" value="$val[unHookAmount]"/>
					</td>
					<td>
						$val[productName]
						<input type="hidden" name="invpurdetail[$i][productName]" value="$val[productName]"/>
						<input type="hidden" name="invpurdetail[$i][productId]" id="invpurPN$i" value="$val[productId]"/>
						<input type="hidden" name="invpurdetail[$i][productNo]" value="$val[productNo]"/>
						<input type="hidden" name="invpurdetail[$i][price]" id="price$i" value="$price"/>
						<input type="hidden" name="invpurdetail[$i][amount]" id="amount$i" value="$val[unHookAmount]"/>
						<input type="hidden" name="invpurdetail[$i][objId]" id="objId$i" value="$val[objId]"/>
						<input type="hidden" name="invpurdetail[$i][contractId]" id="contractId$i" value="$val[contractId]"/>
						<input type="hidden" name="invpurdetail[$i][contractCode]" id="contractCode$i" value="$val[contractCode]"/>
					</td>
					<td>
						$val[number]
					</td>
					<td class="formatMoney">
						$val[hookNumber]
					</td>
					<td class="formatMoney">
						$val[hookAmount]
					</td>
					<td class="formatMoney">
						$val[unHookNumber]
					</td>
					<td class="formatMoney">
						$val[unHookAmount]
					</td>
					<td>
						$val[dObjCode]
					</td>
				</tr>
EOT;
		}
		return array($str,$i);
	}

	/**
	 * �ɹ���ͬ��Ӳɹ���Ʊҳ����ʾ
	 */
	function showPurchAppProInfo($rows,$object){
		$i = 0;
		$str = null;

        $purchaseContractDao = new model_purchase_contract_purchasecontract();
        $data = $purchaseContractDao->get_d($object['id']);
        $formBelong = $formBelongName = $businessBelong = $businessBelongName = '';
        if($data){
            $formBelong = $data['formBelong'];
            $formBelongName = $data['formBelongName'];
            $businessBelong = $data['businessBelong'];
            $businessBelongName = $data['businessBelongName'];
        }

		foreach($rows as $val){
			$i ++;
			$str .=<<<EOT
				<tr>
					<td>
						<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����"/>
					</td>
					<td>
						$i
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][productNo]" id="productNo$i" value="$val[productNumb]" class="readOnlyTxtMiddle" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][productId]" id="productId$i" value="$val[productId]"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][productModel]" value="$val[pattem]" class="readOnlyTxtShort" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][unit]" id="unit$i" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][number]" id="number$i" value="$val[amountAll]" onblur="FloatMul('price$i','number$i','amount$i');countAll();" class="txtshort"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][price]" id="price$i" value="$val[applyPrice]" onblur="FloatMul('price$i','number$i','amount$i');countAll('price');" class="txtshort formatMoneySix"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][taxPrice]" id="taxPrice$i" value="$val[applyPrice]" onblur="FloatMul('taxPrice$i','number$i','allCount$i');countAll('taxPrice');" class="txtshort formatMoneySix"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][amount]" id="amount$i" value="$val[moneyAll]" class="txtshort formatMoney" onblur="countAll('countForm');"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][assessment]" id="assessment$i" value="0" class="txtshort formatMoney" onblur="countAll('countForm');"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][allCount]" id="allCount$i" value="$val[moneyAll]" class="txtshort formatMoney" onblur="countAll('countForm');"/>
					</td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][objCode]" value="$object[hwapplyNumb]" class="readOnlyTxtNormal" readonly="readonly"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][objId]" value="$object[id]"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][objType]" value="CGFPYD-01"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][expand1]"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][expand2]"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][expand3]"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][contractCode]" id="contractCode$i" value="$object[hwapplyNumb]" class="readOnlyTxtNormal" readonly="readonly"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][contractId]"id="contractId$i" value="$object[id]" />
                    </td>
				</tr>
EOT;
		}
        $str .= '<input type="hidden" name="invpurchase[formBelong]"id="formBelongHide" value="'.$formBelong.'" />';
        $str .= '<input type="hidden" name="invpurchase[formBelongName]"id="formBelongNameHide" value="'.$formBelongName.'" />';
        $str .= '<input type="hidden" name="invpurchase[businessBelong]"id="businessBelongHide" value="'.$businessBelong.'" />';
        $str .= '<input type="hidden" name="invpurchase[businessBelongName]"id="businessBelongNameHide" value="'.$businessBelongName.'" />';
		return array($i,$str);
	}

    /**
     * �⹺��ⵥ��������Ϣ��Ⱦ
     */
    function showInStockProInfo($rows){
        $i = 0;
        $str = null;
        foreach($rows as $val){
            $i ++;

            $amount = round($val['subPrice'],2);
            $str .=<<<EOT
                <tr>
					<td>
						<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����"/>
					</td>
                    <td>
                        $i
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][productNo]" id="productNo$i" value="$val[productCode]" class="readOnlyTxtMiddle" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][productId]" id="productId$i" value="$val[productId]"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][productModel]" value="$val[pattern]" class="readOnlyTxtShort" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][unit]" id="unit$i" value="$val[unitName]" class="readOnlyTxtShort" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][number]" id="number$i" value="$val[actNum]" onblur="checkProNum($i),FloatMul('price$i','number$i','amount$i');countAll();" class="txtshort"/>
                        <input type="hidden" id="orgNumber$i" value="$val[actNum]"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][price]" id="price$i" value="$val[price]" onblur="FloatMul('price$i','number$i','amount$i');countAll('price');" class="txtshort formatMoneySix"/>
                    </td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][taxPrice]" id="taxPrice$i" value="$val[price]" onblur="FloatMul('taxPrice$i','number$i','allCount$i');countAll('taxPrice');" class="txtshort formatMoneySix"/>
					</td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][amount]" id="amount$i" value="$amount" class="txtshort formatMoney" onblur="countAll('countForm');"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][assessment]" id="assessment$i" value="0" class="txtshort formatMoney" onblur="countAll('countForm');"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][allCount]" id="allCount$i" value="$amount" class="txtshort formatMoney" onblur="countAll('countForm');"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][objCode]" value="$val[docCode]" class="readOnlyTxtNormal" readonly="readonly"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][objId]" value="$val[mainId]"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][objType]" value="CGFPYD-02"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][expand1]" value="$val[id]"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][expand2]"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][expand3]"/>
                    </td>
                    <td>
                        <input type="text" name="invpurchase[invpurdetail][$i][contractCode]" id="contractCode$i" value="$val[purOrderCode]" class="readOnlyTxtNormal" readonly="readonly"/>
                        <input type="hidden" name="invpurchase[invpurdetail][$i][contractId]"id="contractId$i" value="$val[purOrderId]" />
                    </td>
                    </tr>
EOT;
        }
        return array($i,$str);
    }

	/*******************************ҳ����ʾ*****************************/

	/**
	 * ��д��ӷ���
	 */
	function add_d($object) {
		$codeRuleDao = new model_common_codeRule();
		$invDetailDao = new model_finance_invpurchase_invpurdetail();
		//�Զ�����ϵͳ��źͱ�״̬

		$object['objCode'] = $codeRuleDao->purchInvoiceCode($this->tbl_name,$object['salesmanId']);

//		$object['status'] = 'CGFPZT-WSH';
		$object['payStatus'] = 'δ����';

		//��ȡ�豸��Ϣ
		$detailObj = $object['invpurdetail'];
		unset($object['invpurdetail']);

		//�ʼ�
		if(isset($object['mail'])){
			$emailArr = $object['mail'];
			unset($object['mail']);
		}

		try{
			$this->start_d();

			//�����������
			$newId = parent::add_d ( $object ,true);

			//��Ӵӱ�����
			$invDetailDao->batchAdd_d($newId,$detailObj);

			$this->commit_d();

			//�ʼ�����
			if($emailArr){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}

			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr,$object,$thisAct = '����'){
		$thisMoney = empty($object['formCount']) ? $object['amount'] : $object['formCount'];
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = $_SESSION['USERNAME'].' ��¼�뷢Ʊ '.$object['invoiceNo'].' ,��Ӧ������Ϊ: '.$object['supplierName'].' ,���Ϊ��'.$thisMoney;

		$emailDao = new model_common_mail();
		$emailDao->mailClear('�ɹ���Ʊ',$emailArr['TO_ID'],$addMsg);
	}

	/**
	 * �ӱ�������ʾ
	 */
	function getInfo_d( $id,$perm = null ){
		$rows = $this->get_d($id);
		$invDetailDao = new model_finance_invpurchase_invpurdetail();
		if(empty($perm)){
			$editArr = $invDetailDao->showDetailEdit($invDetailDao->getDetail_d($id));
			$rows['invpurdetail'] = $editArr[0];
			$rows['invnumber'] = $editArr[1];

		}else if($perm == 'view'){
			$viewArr = $invDetailDao->showDetailView($invDetailDao->getDetail_d($id),$rows['formType']);
			$rows['invpurdetail'] = $viewArr[0];
			$rows['invnumber'] = $viewArr[1];

		}else if($perm == 'break'){
			$editArr =$invDetailDao->showDetailBreak($invDetailDao->getDetail_d($id));
			$rows['invpurdetail'] = $editArr[0];
			$rows['invnumber'] = $editArr[1];
		}
		return $rows;
	}

	/**
	 * ��дedit_d����
	 */
	function edit_d($object) {
		$invDetailDao = new model_finance_invpurchase_invpurdetail();

		$detailObj = $object['invpurdetail'];
		unset($object['invpurdetail']);
		try{
			$this->start_d();

			$rs = $this->find(array('id' => $object['id']),null,'ExaStatus');
			if(!empty($rs['ExaStatus'])){
				throw new Exception('�����Ѿ���ˣ����ܽ��б༭����');
				exit();
			}

			//�޸���������
			parent::edit_d ( $object ,true);

			//ɾ���ɴӱ�����,����´ӱ�����
			$invDetailDao->delDetail_d($object['id']);
			$invDetailDao->batchAdd_d($object['id'],$detailObj);

			$this->commit_d();
			return $object['id'];
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���޸�
	 */
	function editEasy_d($object){
		return parent::edit_d ( $object ,true);
	}

	/**
	 * ��ֵ���
	 */
	function break_d($object){
		$invDetailDao = new model_finance_invpurchase_invpurdetail();

		$object['objCode'] = $object['objCode'].'^'.($this->getBreakInv($object['id'])+1);
		$object['belongId'] = $object['id'];
		unset($object['id']);

		$detailObj = $object['invpurdetail'];
		unset($object['invpurdetail']);

		try{
			$this->start_d();

			//��Ӳ�ֱ�����
			$newId = parent::add_d ( $object ,true);

			$this->breakDeal_d(array(
								'id' => $object['belongId'],
								'formAssessment' => $object['formAssessment'],
								'formCount' => $object['formCount'],
								'amount' => $object['amount'],
								'formNumber' => $object['formNumber']
								));

			//��ִӱ�
			$invDetailDao->batchBreak_d($object['belongId'],$newId,$detailObj);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ֵ���ʱ�����
	 */
	function breakDeal_d($object,$formType = 'blue'){
//		if($formType == 'blue'){
			$sql = 'update '.$this->tbl_name . ' set amount = amount - '.$object['amount'] .',formAssessment = formAssessment - '.$object['formAssessment'] .',formCount = formCount - '.$object['formCount'].',formNumber = formNumber - '.$object['formNumber'] .' where id = '.$object['id'] ;
//		}else{
//			$sql = 'update '.$this->tbl_name . ' set amount = amount + '.$object['amount'] .',formAssessment = formAssessment + '.$object['formAssessment'] .',formCount = formCount + '.$object['formCount'] .' where id = '.$object['id'] ;
//		}
		return $this->query($sql);
	}

	/**
	 * ����belongId��ѯ��ȡ��Ʊ�м�����ֱ�
	 */
	function getBreakInv($belongId){
		return $this->findCount(array('belongId' => $belongId));
	}

	/**
	 * �ϲ�����
	 */
	function merge_d($id,$belongId){
		$invDetailDao = new model_finance_invpurchase_invpurdetail();

		try{
			$this->start_d();

			//��Ŀ����
			$invDetailDao->mergeDetail_d($id,$belongId);

			$rows = $this->find(array('id' => $id),null,'amount,belongId,formAssessment,formCount,formNumber');

			$this->mergeDeal_d(array(
								'id' => $rows['belongId'],
								'formAssessment' => $rows['formAssessment'],
								'formCount' => $rows['formCount'],
								'amount' => $rows['amount'],
								'formNumber' => $rows['formNumber']
								));

			//ɾ����ֱ�
			$this->deletes_d($id);

//			$this->rollBack();
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ϲ�����
	 */
	function mergeDeal_d($object,$formType = 'blue'){
//		if($formType == 'blue'){
			$sql = 'update '.$this->tbl_name . ' set amount = amount + '.$object['amount'] .',formAssessment = formAssessment + '.$object['formAssessment'] .',formCount = formCount + '.$object['formCount'] .',formNumber = formNumber  + '.$object['formNumber'] .' where id = '.$object['id'] ;
//		}else{
//			$sql = 'update '.$this->tbl_name . ' set amount = amount - '.$object['amount'] .',formAssessment = formAssessment - '.$object['formAssessment'] .',formCount = formCount - '.$object['formCount'] .' where id = '.$object['id'] ;
//		}
		return $this->query($sql);
	}

	/**
	 * �Ƿ񱻲�ֵ���
	 */
	function isBreak_d($id){
		return $this->find(array('belongId' => $id ),null,'id');
	}

	/**
	 * ���
	 */
	function audit_d($id){
		return parent::edit_d(array( 'id' => $id , 'ExaStatus' => 1,'exaMan' => $_SESSION['USERNAME'],'exaManId' => $_SESSION['USER_ID']
			,'ExaDT' => day_date
		),true);
	}

	/**
	 * �����
	 */
	function unaudit_d($id){
		return parent::edit_d(array( 'id' => $id , 'ExaStatus' => 0,'exaMan' => null,'exaManId' => null
			,'ExaDT' => null
		),true);
	}

	/**
	 * �����ӻ�ȡ��Ʊ������Ŀ
	 */
	function getInvByLeft_d($id){
		$this->searchArr['id'] = $id;
		$this->searchArr['notEqual'] = 0;
		return $this->listBySqlId('hook_list');
	}

	/**
	 * �����ӻ�ȡ��Ʊ������Ŀ (�෢Ʊ)
	 */
	function getInvsByLeft_d($ids){
		$this->searchArr['ids'] = $ids;
		$this->searchArr['notEqual'] = 0;
		return $this->listBySqlId('hook_list');
	}

	/**
	 * ��ȡ��������
	 */
	function hookRows_d($id,$read = true){
		//��ȡ�ɹ���Ʊ����ʹӱ���Ϣ
		$rows = $this->getInvByLeft_d($id);
		$str = $this->showHookList($rows,$read);

		//��ȡ���÷�Ʊ����ʹӱ���Ϣ
		return array($str[0],$str[1]);
	}


	/**
	 * �ɹ���Ʊ�������������
	 */
	function pageJsonGrid_d(){
		$rows = $this->pageBySqlId('easy_list');
		if(!empty($rows)){
			//��ȡ�б��id��
			$ids = null;
			foreach( $rows as $key => $val){
				if($key){
					$ids .= ','.$val['id'];
				}else{
					$ids .= $val['id'];
				}

			}
			$outDetail = array();
			$i = 0;
			//����id����ȡ��Ʊ����Ŀ
			$detailRows = $this->getInvsByLeft_d($ids);
			foreach($detailRows as $val){
				if(isset($outDetail[$val['id']])){
					$outDetail[$val['id']] .=  '/'.$val['productName'];
					$outDetail[$val['id'].'_Instr'][$i]=  $val;
				}else{
					$i = 0;
					$outDetail[$val['id']] = $val['productName'];
					$outDetail[$val['id'].'_Instr'][$i] = $val;
				}
				$i ++;
			}

			//�����ϵķ�Ʊ��Ŀ������ӵ�������
			foreach($rows as $key => $val){
				$rows[$key]['listProduct'] = $outDetail[$val['id']];
				$rows[$key]['listStr'] = $outDetail[$val['id'].'_Instr'];
			}
		}
		return $rows;
	}

	/**
	 * ����ʱ�ı�ɹ���Ʊ״̬
	 */
	function hookChangeStatus_d($ids,$status){
		$sql = " update ".$this->tbl_name." set status = '" . $status . "' where id in (" . $ids . ")" ;
		return $this->query($sql);
	}

	/**
     * ���ݲɹ�����id��ȡδ��Ʊ�������嵥
     */
    function getNotArrPurchPros($purAppId){
        $equDao = new model_purchase_contract_equipment();
        $equRows=$equDao->getEqusForInvpurchase($purAppId);
        return $equRows;
    }

    /**
     * @desription ���ݲɹ���ͬId��ȡ�����Ϣ����
     */
    function getContractinfoById($purAppId){
        $purchasecontract = new model_purchase_contract_purchasecontract();
        $rows = $purchasecontract->find(array('id'=> $purAppId),null,'id,suppName,suppId,suppAddress,createName,createId,hwapplyNumb,suppBankName,billingType');

		//��ȡ��ǰ��¼�˲���
		$otherDataDao = new model_common_otherdatas();
		$deptRows = $otherDataDao->getUserDatas($rows['createId'],array('DEPT_NAME','DEPT_ID'));
		$rows['deptName'] = $deptRows['DEPT_NAME'];
		$rows['deptId'] = $deptRows['DEPT_ID'];

        return $rows;
    }

    /**
     * �����⹺��ⵥid��ȡ������Ϣ
     */
    function getInStock_d($instockId,$objType = 'CGFPYD-02'){

    	//��ȡ�⹺��ⵥ����
        $instockDao = new model_stock_instock_stockin();
        $rows = $instockDao->findAllItemByIds($instockId);

		//��ȡ�ѹ�������
        $invDetailDao = new model_finance_invpurchase_invpurdetail();
        $pushedRows = $invDetailDao->findAllItemByObjIdAndType_d($instockId);

        if($pushedRows){
	        $productRows = null;
			//����ת��
	        foreach($pushedRows as $key => $val){
	        	if(!empty($val['expand1'])){
					$productRows[$val['objType']][$val['objId']]['e'][$val['expand1']] = $val['allnumber'];
	        	}else{
					$productRows[$val['objType']][$val['objId']]['p'][$val['productId']] = $val['allnumber'];
	        	}
	        }

//			echo "<pre>";
//	     	print_r($productRows);

	        //���Ƶ��ݹ���
	        foreach($rows as $key => $val){
	        	if(isset($productRows[$objType][$val['mainId']]['e'][$val['id']])){
					$rows[$key]['actNum'] = $rows[$key]['actNum'] - $productRows[$objType][$val['mainId']]['e'][$val['id']];
	        	}else{
					$rows[$key]['actNum'] = $rows[$key]['actNum'] - $productRows[$objType][$val['mainId']]['p'][$val['productId']];
	        	}
				if($rows[$key]['actNum'] <= 0){
					unset($rows[$key]);
				}
	        }

        }
        return $rows;
    }

	/*
	 * ���ݲɹ����뵥��ȡ��Ʊ��Ϣ
	 */
	function getInvpurchaseByPurconId( $purconId) {
		$this-> searchArr['purcontId'] = $purconId;
		$this-> searchArr['payStatus'] = "δ����";
		$rows = $this->pageBySqlId ();
		return $rows;
	}

	/*
	 * ���븶�����뵥����Ʊ�Ĺ���
	 */
	function addApplyInvpur($apply) {
		if ($apply ['aboutInvpur']) {
			$aboutInvpurIdArr = explode ( ',', $apply ['aboutInvpur'] );
			foreach ( $aboutInvpurIdArr as $invpurId ) {
				if($apply['ExaStatus'] != AUDITED ){
					$sql = "update oa_finance_invpurchase c set c.payStatus = '������' where c.id = " . $invpurId;
				}
				else{
					$sql = "update oa_finance_invpurchase c set c.payStatus = '�Ѹ���' where c.id = " . $invpurId;
				}
//				echo $sql;
				$this->query ( $sql );
			}
		}
	}

	/*
	 * ���븶�����뵥����Ʊ�Ĺ���
	 */
	function addPaymentInvpur( $payment ) {
		if ($payment ['aboutInvpur']) {
			$aboutInvpurIdArr = explode ( ',', $payment ['aboutInvpur'] );
			foreach ( $aboutInvpurIdArr as $invpurId ) {
				$sql = "update oa_finance_invpurchase c set c.payStatus = '�Ѹ���' where c.id = " . $invpurId;
				$this->query ( $sql );
			}
		}
	}

	/**
	 * ��gird��ȡ�ɹ���Ʊ�嵥 ���� ��ⵥ��
	 */
	function getEquList_d($id){
		$invpurdetail = new model_finance_invpurchase_invpurdetail();
		$rows= $invpurdetail->getDetail_d($id);
		$list = $invpurdetail->showAddList($rows);
		return $list;
	}

     /**
      * ��gird��ȡ�ɹ���Ʊ�嵥 ���� ��ⵥ��
      */
     function getEquListJson_d($id){
         $invpurdetail = new model_finance_invpurchase_invpurdetail();
         $rows= $invpurdetail->getDetail_d($id);
         // k3������ش���
         $productinfoDao = new model_stock_productinfo_productinfo();
         $rows = $productinfoDao->k3CodeFormatter_d($rows);
         $list = $invpurdetail->showAddListJson($rows);
         return $list;
     }

	/**
	 * ��ȡ��ǰ������Ϣ
	 */
	function rtThisPeriod_d(){
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
    }

    /**
	 * ���ݶ���id��ȡ���շ�Ʊ���
	 */
	function getInvMoneyByPur_d($objId,$objType = 'CGFPYD-01'){
		$this->searchArr['dcontractId'] = $objId;
		$this->groupBy = 'd.contractId';
		$rows = $this->list_d('sum_list');
		if(is_array($rows)){
			return $rows[0]['handInvoiceMoney'];
		}else{
			return 0;
		}

	}

	 /**
	 * ���ݶ���id��ȡ���շ�Ʊ
	 */
	function getInvByPur_d($objId,$objType = 'CGFPYD-01'){
		$this->searchArr['dcontractId'] = $objId;
		$this->groupBy = 'c.id';
		return $this->list_d('history');
	}


	/**
	 * ��ҳС�ƴ���
	 */
	function pageCount_d($object){
		if(is_array($object)){
			$newArr = array('amount' => 0,'formAssessment'=>0,'formCount'=>0 );
			foreach($object as $key => $val){
				if($val['formType'] == 'blue'){
					$newArr['formNumber'] = bcadd($newArr['formNumber'],$val['formNumber'],2);
					$newArr['amount'] = bcadd($newArr['amount'],$val['amount'],2);
					$newArr['formAssessment'] = bcadd($newArr['formAssessment'],$val['formAssessment'],2);
					$newArr['formCount'] = bcadd($newArr['formCount'],$val['formCount'],2);
				}else{
					$newArr['formNumber'] = bcsub($newArr['formNumber'],$val['formNumber'],2);
					$newArr['amount'] = bcsub($newArr['amount'],$val['amount'],2);
					$newArr['formAssessment'] = bcsub($newArr['formAssessment'],$val['formAssessment'],2);
					$newArr['formCount'] = bcsub($newArr['formCount'],$val['formCount'],2);
				}
			}
			$newArr['objCode'] = '��ҳС��';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/*********************�ϲ��²�**************************/
	/**
	 * �ж�����Դ����Ϣ�Ƿ������ƵĲɹ���Ʊ
	 */
	function hasSource($objId,$objType){
		$this->searchArr = array('dobjId' => $objId, 'dobjType' => $objType);
		if(is_array($this->listBySqlId('hook_list'))){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * �жϵ����Ƿ��а�����Դ����Ϣ
	 * �����ݴ���Դ������ʱ�����ص�ֵΪarray(Դ������ => Դ����)
	 */
	function hasSourceInfo($id){
		$this->searchArr = array('id' => $id);
		$rows = $this->listBySqlId('hook_list');
		$sourceIdArr = array();
		$sourceTypeArr = array();
		if(is_array($rows)){
			foreach($rows as $key => $val){
				if(!empty($val['objId'])){
					array_push($sourceIdArr,$val['objId']);
					array_push($sourceTypeArr,$val['objType']);
				}
			}
			if(!empty($sourceIdArr)){
				$sourceTypeArr = array_unique($sourceTypeArr);
				$sourceIdArr = array_unique($sourceIdArr);
				return array(implode($sourceTypeArr) => implode($sourceIdArr,','));
			}else{
				return false;
			}
		}else{
			return false;
		}
	}


	/************************* �����ݴ��� **************************/

	/**
	 * �������ݴ�����
	 */
	function deal_d($object){
//		print_r($object);
//		die();
		if(is_array($object)){
			//ʵ�����ɹ���Ʊ��ϸ��Ϣ
			$invDetailDao = new model_finance_invpurchase_invpurdetail();
			try{
				$this->start_d();
				foreach($object as $key => $val){
					$invDetailDao->edit_d($val);
				}
				$this->commit_d();
				return true;
			}catch(exception $e){
				$this->rollBack();
				return $e->getMessage();
			}
		}else{
			return '��Ӧ��������';
		}
	}

	/**
	 * ��ȡ���ݴӱ���Ϣ
	 */
	function getDetail($id){
		//ʵ�����ɹ���Ʊ��ϸ��Ϣ
		$invDetailDao = new model_finance_invpurchase_invpurdetail();
		return $invDetailDao->getDetail_d($id);
	}

	//��ȡ��Ӧ�����Ϣ
	function getStockInfo_d($object){
		//Դ��id����
		$objIdArr = array();
		foreach($object as $key => $val){
			if(!in_array($val['objId'],$objIdArr)){
				array_push($objIdArr,$val['objId']);
			}
		}
		$ids = implode($objIdArr,',');
		$sql = "select c.id,c.docCode,i.id as detailId,i.productId,i.productCode,i.productName,i.actNum,i.pattern,i.price from oa_stock_instock c inner join oa_stock_instock_item i on c.id = i.mainId where c.id in ( $ids )";
		return $this->_db->getArray($sql);
	}
}
?>