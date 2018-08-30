
<?php


/**
 * @author zengzx
 * @Date 2012��3��14�� 9:36:12
 * @version 1.0
 * @description:��ͬ �����嵥 Model��
 */
class model_contract_contract_equ extends model_base {

    public $_defaultTaxRate;
	function __construct() {
		$this->tbl_name = "oa_contract_equ";
		$this->sql_map = "contract/contract/equSql.php";
		parent :: __construct();
        $this->_defaultTaxRate = 0.16;// ������ϢĬ����16%��˰�ʼ������ɱ� PMS 647��
	}


	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getDetail_d($contractId) {
		$this->searchArr['contractId'] = $contractId;
		$this->searchArr['isDel'] = 0;
		$this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}

	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����---��ȡ�����ʱ��¼��
	 */
	function getDetailTemp_d($contractId) {
		$this->searchArr['contractId'] = $contractId;
		$this->searchArr['isDel'] = 0;
//		$this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}


	/**
	 * ���ݺ�ͬID ��ȡ�ӱ�����
	 */
	function getByProId_d($contractId) {
		$this->searchArr['conProductId'] = $contractId;
		$this->searchArr['isDel'] = 0;
		$this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}
	function getByPcid_d($pid,$cid) {
		$this->searchArr['conProductId'] = $pid;
		$this->searchArr['contractId'] = $cid;
		$this->searchArr['isDel'] = 0;
		$this->searchArr['isTemp'] = 0;
		$this->asc = false;
		return $this->list_d();
	}

	/*�����������*/
	function configuration_d($proId, $Num, $rowNum, $itemNum) {
		//��ȡ����������Ϣ
		$configurationDao = new model_stock_productinfo_configuration();
		$configId = $configurationDao->getConfigByHardWareId($proId);
		if (!empty ($configId)) {
			foreach ($configId as $k => $v) {
				$configIdA[$k] = $v['configId'];
			}
			$configIdA = implode(",", $configIdA);
			//��ȡ������Ϣ
			$productInfoDao = new model_stock_productinfo_productinfo();
			$productInfoDao->searchArr['idArr'] = $configIdA;
			$infoArr = $productInfoDao->list_d();
			foreach ($infoArr as $key => $val) {
				foreach ($configId as $keyo => $valo) {
					if ($infoArr[$key]['id'] == $configId[$keyo]['configId']) {
						//����ԭ��������������������
						$infoArr[$key]['configNum'] = $configId[$keyo]['configNum'] * $Num;
					}
				}
			}
		}
		//��Ⱦ��Ʒ����
		$configArr = $this->showProductInfo($infoArr, $proId, $rowNum, $itemNum);
		return $configArr;
	}

	/**
	 * ��ȡ��Ʒ�µ�������Ϣ[�˷������ã����ƶ���allsource.php]
	 */
	function getProductEqu_d($id) {
		$productDao = new model_contract_contract_product();
		$goodsArr = $productDao->resolve_d($id);
		$licenseIdArr=array();
		foreach($goodsArr as $k=>$v){
			$a=$licenseIdArr[$v[0]];
			if(!is_array($a)){
				$a=array();
			}
			array_push($a,$v[2]);
			$licenseIdArr[$v[0]]=$a;
		}
		//print_r($licenseIdArr);
		$propertiesitemArr = array ();
		if ($goodsArr != 0) {
			$propertiesitemDao = new model_goods_goods_propertiesitem();
			$productInfoDao = new model_stock_productinfo_productinfo();
			$goodsInfoIdArr = array ();
			foreach ($goodsArr as $key => $val) {
				$goodsInfoIdArr[] = $val[0];
			}
			$goodsInfoIdStr = implode(',', $goodsInfoIdArr);
			$propertiesitemDao->searchArr['ids'] = $goodsInfoIdStr;
			$propertiesitemArr = $propertiesitemDao->list_d();
			$productIdArr = array ();
			$propertiesitemArrNew=array();
			foreach ($propertiesitemArr as $key => $val) {
				$productIdArr[] = $val['productId'];
				$propertiesitemArrNew[$val['id']]=$val;
			}
			$productIdStr = implode(',', $productIdArr);
			$productInfoDao->searchArr['idArr'] = $productIdStr;
			$productArr = $productInfoDao->list_d();
			$productArrNew=array();
			foreach ($productArr as $key => $val) {
				$productArrNew[$val['id']]=$val;
			}
			$equArrReturn=array();
			foreach($licenseIdArr as $key=>$val){
				foreach($val as $k=>$v){
					$item=$propertiesitemArrNew[$key];
					if(!empty($item['productId'])){
						$product=$productArrNew[$item['productId']];
						$item['productId'] = $product['id'];
						$item['productModel'] = $product['pattern'];
						$item['number'] = $item['defaultNum'];
						$item['license'] = $v;
						unset ($item['id']);
						$equArrReturn[]=$item;
					}
				}
			}
		}
		//print_r($equArrReturn);
		return $equArrReturn;
	}

	/**
	 * @description �������ϴ���ҳ����ʾ��Ʒ��Ϣ
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$i = 0; //�б��¼���
			foreach ($rows as $key => $val) {

                //�ж��Ƿ������������Ʒ
                $goodsTypeIds = $val['conProductId'];
                if (!empty ($goodsTypeIds)) {
                    $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                    //��һ�β��ң����˳������Ѿ��������������
                    $goodsTypeA = $this->_db->getArray($sqlA);
                    $goodsTypeStr="";
                    $goodsTypeStrTemp="";
                    foreach ($goodsTypeA as $k => $v) {
                        if ($v['parentId'] == "-1") {
                            $goodsTypeStr = $v['id'] ;
                        } else {
                            $goodsTypeStrTemp = $v['id'];
                        }
                    }
                }
                if (!empty ($goodsTypeStrTemp)) {
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "
					               select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    foreach ($goodsTypeB as $k => $v) {
                        $goodsTypeStr = $v['id'];
                    }
                }
                if($goodsTypeStr == '17' || $goodsTypeStr == '18'){
                    continue;
                }

				$deployShow = '';
				$deployShow = $goodDao -> showDeploy($val['deploy']);
				//��ͬ�豸�Ƿ�����
				$j = $i +1;
				if( $val['license']!=0 || $val['license']!='' ){
					$licenseHtml = "<input type='button'  value='��������'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				}else{
					$licenseHtml='��';
				}
				if($val['isDel']=="1"){
					$style.='<img title="���ɾ���Ĳ�Ʒ" src="images/box_remove.png" />';
				}
				if($val['changeTips']=="2"){
					$style.='<img title="��������Ĳ�Ʒ" src="images/new.gif" />';
				}
				if($val['changeTips']=="1"){
					$style.='<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />';
				}
				//class="tr_mouseover"
				// bgcolor='#ECFFFF'
				$str .=<<<EOT
						<tr height="30px">
							<td width="35px">$j</td>
<!--							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							$licenseHtml</td>
							<td width="8%"><input type="button"  value="��Ʒ����"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><span onclick="hideList('product$j');">�����嵥</span>
							  <img src="images/icon/info_right.gif" onclick="hideList('product$j');" title="չ��" alt="����ѡ��" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:35px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <span onclick="hideList('product$j');">�����嵥</span>
							    <img src="images/icon/info_up.gif" onclick="hideList('product$j');" title="����" alt="����ѡ��" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}

	/**
	 * @description ���/�༭ ���ϴ���ҳ����ʾ��Ʒ��Ϣ
	 * @param $rows
	 */
	function showItemChange($rows,$isSubAppChange) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array($rows)) {
			$goodDao = new model_goods_goods_goodscache();
			$equDao = new model_contract_contract_equ();
			$i = 0; //�б��¼���
			$style='';
			foreach ($rows as $key => $val) {
                //�ж��Ƿ������������Ʒ
                $goodsTypeIds = $val['conProductId'];
                if (!empty ($goodsTypeIds)) {
                    $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                    //��һ�β��ң����˳������Ѿ��������������
                    $goodsTypeA = $this->_db->getArray($sqlA);
                    $goodsTypeStr="";
                    $goodsTypeStrTemp="";
                    foreach ($goodsTypeA as $k => $v) {
                        if ($v['parentId'] == "-1") {
                            $goodsTypeStr = $v['id'] ;
                        } else {
                            $goodsTypeStrTemp = $v['id'];
                        }
                    }
                }
                if (!empty ($goodsTypeStrTemp)) {
                    //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                    $sqlB = "
					               select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                        "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                    $goodsTypeB = $this->_db->getArray($sqlB);
                    foreach ($goodsTypeB as $k => $v) {
                        $goodsTypeStr = $v['id'];
                    }
                }
                if($goodsTypeStr == '17' || $goodsTypeStr == '18'){
                    continue;
                }
				$deployShow = '';
				$deployShow = $goodDao -> showDeploy($val['deploy']);
                $deployShow = str_replace("\\","",$deployShow);
				if($isSubAppChange == '1'){
					$equArr = $equDao->getByProId_d($val['originalId']);
				}else{
					$equArr = $equDao->getByProId_d($val['id']);
				}
				if($val['isDel']==1){
					if(!(is_array($equArr)&&count($equArr)>0)){
//						continue;     ����û�㶮ΪʲôҪ continue ��ʱע�͵�
					}
				}
				if($val['isDel']=="1"){
					$style.='<img title="���ɾ���Ĳ�Ʒ" src="images/box_remove.png" />';
				}
				if($val['changeTips']=="2"){
					$style.='<img title="��������Ĳ�Ʒ" src="images/new.gif" />';
				}
				if($val['changeTips']=="1"){
					$style.='<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />';
				}
				$img = '';
				if( $val['license']!=0 || $val['license']!='' ){
					$licenseHtml = "<input type='button'  value='��������'  class='txt_btn_a' onclick='showLicense($val[license])'/>";
				}else{
					$licenseHtml='��';
				}
				if( $val['isDel']=='1' ){
					$trStyle = " bgcolor='#efefef'";
				}else{
					$trStyle = " bgcolor='#ECFFFF'";
				}
				$j = $i +1;
				$str .=<<<EOT
						<tr height="30px" $trStyle>
							<td width="8%">$style &nbsp;$j</td>
<!--<img src="images/changeAdd2.png"/>&nbsp;							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$img &nbsp;$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]</td>
							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="hidden" value="$val[originalId]" id="originalId$j"/>
							$licenseHtml</td>
							<td width="8%"><input type="button"  value="��Ʒ����"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>$deployShow
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">�����嵥</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="չ��" alt="����ѡ��" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:20px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">�����嵥</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="����" alt="����ѡ��" /></legend>
								<div id="productInfo$j"></div>
							</fieldset>
							</td>
						</tr>
EOT;
				$i++;
				$style='';
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
			}
		} else {
			$str = '<tr><td colspan="11">���������Ϣ</td></tr>';
		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";

		return $str;
	}

	/**
	 * ��Ⱦ��Ʒ����
	 */
	function showProductInfo($object, $id, $rowNum, $itemNum) {
		$name = 'contract[detail][' . $itemNum . ']';
		$trId = $id . "_" . $rowNum;
		$str = "<tr id='goodsDetail_$trId'><td class='innerTd' colspan='20'><table class='form_in_table' id='contractequ_$id'>";
		if (is_array($object)) {
			$titleStr = "<tr class='main_tr_header1'>";
			$infoStr = '';
			$titleStr .=<<<EOT
				<th width="30px"></th>
				<th width="35px">���</th>
				<th>���ϱ���</th>
				<th>��������</th>
				<th>�汾�ͺ�</th>
				<th>����</th>
				<th>������
EOT;
			foreach ($object as $key => $val) {
				$j = $key +1;
				$trName = $name . '[' . $rowNum . '0' . $j . ']';
				{
					$productId_n = $trName . '[productId]';
					$productCode_n = $trName . '[productCode]';
					$productName_n = $trName . '[productName]';
					$productpattern_n = $trName . '[pattern]';
					$productnumber_n = $trName . '[number]';
					$productarrivalPeriod_n = $trName . '[arrivalPeriod]';
					$infoStr .= "<tr class='tr_inner'>";
				}

				$infoStr .=<<<EOT
					<td><img src='images/removeline.png' onclick='mydel(this,"contractequ_$id")' title='ɾ����'></td>
					<td>$j</td>
					<td>$val[productCode]
						<input type="hidden" class="txtshort" id="inner_productCode$id-$key" name='$productCode_n' value="$val[productCode]"/>
						<input type="hidden" class="txtshort" id="inner_productId$id-$key" name='$productId_n' value="$val[id]"/>
						</td>
					<td>$val[productName]<input type="hidden" class="txtshort" id="inner_productName$id-$key" name='$productName_n' value="$val[productName]"/></td>
					<td>$val[pattern]<input type="hidden" class="txtshort" id="inner_pattern$id-$key" name='$productpattern_n' value="$val[pattern]"/></td>
					<td><input type="text" class="txtshort" id="inner_number$id-$key" name='$productnumber_n' value="$val[configNum]"/></td>
					<td>$val[arrivalPeriod]<input type="hidden" class="txtshort" id="inner_arrivalPeriod$id-$key" name='$productarrivalPeriod_n' value="$val[arrivalPeriod]"/>
EOT;
				$infoStr .= "</td></tr>";
			}
			$titleStr .= "</th></tr>";
			$infoStr .= "</td></tr>";
			$str .= $titleStr . $infoStr . "</table></td></tr>";
			return $str;
		} else {
			return '';
		}
	}

	/**
	 * ���Ϸ��� ����
	 */
	function equAdd_d($object,$audti=false) {
		try {
			$this->start_d();
			if ($object['id']) {
				$contDao = new model_contract_contract_contract();
				$linkDao = new model_contract_contract_contequlink();
				$linkDao->searchArr['contractId']=$object['id'];
				$linkInfo = $linkDao->list_d();
				$contObj = $contDao->get_d($object['id']);
				if($contObj['dealStatus']=='1' || !empty($linkInfo) ){
					echo "<script>alert('�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!')</script>";
					throw new Exception("�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!");
				}

				$equs = array ();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							if( !empty($value['productId']) ){
								$equs[] = $value;
							}
						}
					}
				}
				//���������
				$linkArr = array (
					"contractId" => $contObj['id'],
					"contractCode" => $contObj['contractCode'],
					"contractName" => $contObj['contractName'],
					"contractType" => 'oa_contract_contract',
					"contractTypeName" => $contObj['contractTypeName']
				);
				$dateObj = array(
					'id'=>$object['id'],
					'standardDate'=>$object['standardDate'],
				);
				if($audti){
					$dateObj['dealStatus']=1;
					$linkArr['ExaStatus']='���';
					$linkArr['ExaDT']=day_date;
					$linkArr['ExaDTOne']=day_date;
				}else{
					$linkArr['ExaStatus']='δ�ύ';
				}
				$contDao->updateById($dateObj);
				$linkId = $linkDao->add_d($linkArr, true);
				if($linkId){
					$linkDao->confirmAudit($linkId);
				}else{
					throw new Exception("������Ϣ����������ȷ��!");
				}
				$linkArr['linkId'] = $linkId;
				$contObj['linkId'] = $linkId;
				//������ͬ����
				$equs = $this->processEquCommonInfo($equs, $contObj);
				//�����ͬ�ɱ�
				$productInfoDao = new model_stock_productinfo_productinfo();
				$productIdArr = array();
				foreach( $equs as $key=>$val ){
					if( $val['productId']!='' && $val['productId']!=0 ){
						$productIdArr[]=$val['productId'];
					}
				}
				$productIdArr=array_flip($productIdArr);
				$productIdArr=array_flip($productIdArr);
				$productIdStr = implode(',', $productIdArr);
				$productInfoDao->searchArr['idArr'] = $productIdStr;
				$productArr = $productInfoDao->list_d();

				$sumMoney = 0;
				$sumMoneyTax = 0;
				//�������ϴ���
				$specialProIdArr = explode(',', specialProId);
				$lastProId = 0;
				foreach( $equs as $key=>$val ){
					foreach( $productArr as $index=>$value ){
						if( $val['productId']==$value['id'] ){
							//�������ϵ����������ɱ����㣬ֻ�������ϵ��ۣ����������Ϊ0
							if (empty ($val['configCode']) && in_array($lastProId, $specialProIdArr)) {
								$equs[$key]['price']=$value['priCost'];
// 								$equs[$key]['money']=$val['number']*$value['priCost'];
								// ��17%˰�ʣ�����˰����
// 								$priceTax = bcmul($value['priCost'], '1.17', 2);
// 								$equs[$key]['priceTax'] = $priceTax;
// 								$equs[$key]['moneyTax'] = bcmul($priceTax, $val['number'], 2);
								$equs[$key]['priceTax'] = $equs[$key]['moneyTax'] = $equs[$key]['money'] = 0;
							}else{
								if(!empty ($val['configCode']) && in_array($val['productId'], $specialProIdArr)){//�������ϸ���ȡҳ��ĳɱ�
									$equs[$key]['price']=bcdiv($val['costEstimate'], $val['number'],2);
									$equs[$key]['money']=$val['costEstimate'];
									$sumMoney+=$val['costEstimate'];
									// ��17%˰�ʣ�����˰����
									$moneyTax = bcmul($val['costEstimate'], bcadd($this->_defaultTaxRate,1,2), 2);
									$equs[$key]['priceTax'] = bcdiv($moneyTax, $val['number'],2);
									$equs[$key]['moneyTax'] = $moneyTax;
									$sumMoneyTax+=$moneyTax;
								}else{
									$equs[$key]['price']=$value['priCost'];
									$equs[$key]['money']=$val['number']*$value['priCost'];
									$sumMoney+=$val['number']*$value['priCost'];
									// ��17%˰�ʣ�����˰����
									$priceTax = bcmul($value['priCost'], bcadd($this->_defaultTaxRate,1,2), 2);
									$equs[$key]['priceTax'] = $priceTax;
									$equs[$key]['moneyTax'] = bcmul($priceTax, $val['number'], 2);
									$sumMoneyTax+=$equs[$key]['moneyTax'];
								}
								if (!empty ($val['configCode'])) {
									$lastProId = $val['productId'];
								}
							}
							break;
						}
					}
				}
//				if($audti){    --add by zzx(2016-1-21 15:22:56)
					$conCostArr = array(
						'id'=>$object['id'],
						'saleCost'=>$sumMoney,
						'saleCostTax'=>$sumMoneyTax,
						'saleConfirm'=>'1',
						'saleConfirmName'=>$_SESSION['USERNAME'],
						'saleConfirmId'=>$_SESSION['USER_ID'],
						'saleConfirmDate'=>day_date,
                        'costRemark'=>$object['costRemark']
					);
					$contDao->updateById($conCostArr);
//				}

				$lastEquId = 0;
				//�������ϴ���
				$isSpecialPro = false;
				foreach ($equs as $key => $val) {
					if(empty($val['isDel'])){
						$val['isDel'] = '0';
					}
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
//						if($isSpecialPro){
//							if($val['isDelTag'] == 1){//������ҳ���������Ȼ��ɾ�������
//								continue;
//							}
//							//����������������´﷢��
//							$val['executedNum'] = $val['issuedShipNum'] = $val['number'];
//						}
					}

                    if ($val['isDelTag'] != 1){
                        if( isset($val['id']) && $val['id'] != '' ){
                            $this->edit_d($val);
                            if (!empty ($val['configCode'])) {
                                $lastEquId = $val['id'];
//							if(in_array($val['productId'], $specialProIdArr)){
//								$isSpecialPro = true;
//							}else{
//								$isSpecialPro = false;
//							}
                            }
                        }else{
                            $id = $this->add_d($val);
                            if (!empty ($val['configCode'])) {
                                $lastEquId = $id;
//							if(in_array($val['productId'], $specialProIdArr)){
//								$isSpecialPro = true;
//							}else{
//								$isSpecialPro = false;
//							}
                            }
                        }
                    }

				}
//				if($audti){   --add by zzx(2016-1-21 15:22:56)
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
                    //����������Ʒ�� �ɱ�ȷ����ϸ��¼
//                    $conDao = new model_contract_contract_contract();
                    $lineStr = $contDao->hadleSaleLineCostInfo($conCostArr,'add',$audti);
                    //�ύ״̬�Ĳ���Ҫ���ʼ�
                    if($audti){
                    	$this->sendMailAtAudit($object,'�ύ');
                    	$handleDao = new model_contract_contract_handle();
                    	$handleDao->handleAdd_d(array(
                    			"cid"=> $object['id'],
                    			"stepName"=> "����ȷ��",
                    			"isChange"=> 0,
                    			"stepInfo"=> $lineStr,
                    	));
                    }
//				}
				$contDao->countCost($object['id']);
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			$this->commit_d();
			return $linkId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ȡĳ�������嵥�������Ϣ
	 * add by chengl
	 */
	function getEquByParentEquId_d($parentEquId,$isDel) {
		if($isDel===0){
			$this->searchArr['isDel'] = 0;
		}
		$this->searchArr['parentEquId'] = $parentEquId;
		return $this->list_d();
	}
	/**
	 * ���Ϸ��� �༭
	 */
	function equEdit_d($object,$audti=false) {
		try {
			$this->start_d();
			if ($object['id']) {
				$contDao = new model_contract_contract_contract();
				$linkDao = new model_contract_contract_contequlink();
				$linkObj = $linkDao->findBy('contractId', $object['id']);
				$contObj = $contDao->get_d($object['id']);

				if($contObj['dealStatus']=='1'){
					echo "<script>alert('�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!')</script>";
					throw new Exception("�������Ѿ�ȷ�ϣ���ˢ��ԭ�б�ҳ���鿴�õ��ݴ���״̬!");
				}
				$dateObj = array(
					'id'=>$object['id'],
					'standardDate'=>$object['standardDate'],
				);
				if($audti){
					$dateObj['dealStatus']=1;
					$linkObj['ExaStatus']='���';
					$linkObj['ExaDT']=day_date;
					$linkObj['ExaDTOne']=day_date;
					$linkDao->edit_d($linkObj);
				}
				$contDao->updateById($dateObj);
//				$this->delete(array (
//					'contractId' => $object['id']
//				));
				$equs = array ();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							if( !empty($value['productId']) ){
								$equs[] = $value;
							}
						}
					}
				}
				$contObj['linkId'] = $linkObj['id'];
				//������ͬ����
				$equs = $this->processEquCommonInfo($equs, $contObj);
				//�����ͬ�ɱ�
				$productInfoDao = new model_stock_productinfo_productinfo();
				$productIdArr = array();
				foreach( $equs as $key=>$val ){
					if( $val['productId']!='' && $val['productId']!=0 ){
						$productIdArr[]=$val['productId'];
					}
				}
		//		echo "<pre>";
		//		print_R($productIdArr);
				$productIdArr=array_flip($productIdArr);
				$productIdArr=array_flip($productIdArr);
				$productIdStr = implode(',', $productIdArr);
				$productInfoDao->searchArr['idArr'] = $productIdStr;
				$productArr = $productInfoDao->list_d();
		//		print_R($productArr);
				$sumMoney = 0;
				$sumMoneyTax = 0;
				//�������ϴ���
				$specialProIdArr = explode(',', specialProId);
				$lastProId = 0;
				foreach( $equs as $key=>$val ){
					if($val['isDelTag'] != 1){
						foreach( $productArr as $index=>$value ){
							if( $val['productId']==$value['id'] ){
								//�������ϵ����������ɱ����㣬ֻ�������ϵ��ۣ����������Ϊ0
								if (empty ($val['configCode']) && in_array($lastProId, $specialProIdArr)) {
									$equs[$key]['price']=$value['priCost'];
// 									$equs[$key]['money']=$val['number']*$value['priCost'];
									// ��17%˰�ʣ�����˰����
// 									$priceTax = bcmul($value['priCost'], '1.17', 2);
// 									$equs[$key]['priceTax'] = $priceTax;
// 									$equs[$key]['moneyTax'] = bcmul($priceTax, $val['number'], 2);
									$equs[$key]['priceTax'] = $equs[$key]['moneyTax'] = $equs[$key]['money'] = 0;
								}else{
									if(!empty ($val['configCode']) && in_array($val['productId'], $specialProIdArr)){//�������ϸ���ȡҳ��ĳɱ�
										$equs[$key]['price']=bcdiv($val['costEstimate'], $val['number'],2);
										$equs[$key]['money']=$val['costEstimate'];
										$sumMoney+=$val['costEstimate'];
										// ��17%˰�ʣ�����˰����
										$moneyTax = bcmul($val['costEstimate'], bcadd($this->_defaultTaxRate,1,2), 2);
										$equs[$key]['priceTax'] = bcdiv($moneyTax, $val['number'],2);
										$equs[$key]['moneyTax'] = $moneyTax;
										$sumMoneyTax+=$moneyTax;
									}else{
										$equs[$key]['price']=$value['priCost'];
										$equs[$key]['money']=$val['number']*$value['priCost'];
										$sumMoney+=$val['number']*$value['priCost'];
										// ��17%˰�ʣ�����˰����
										$priceTax = bcmul($value['priCost'], bcadd($this->_defaultTaxRate,1,2), 2);
										$equs[$key]['priceTax'] = $priceTax;
										$equs[$key]['moneyTax'] = bcmul($priceTax, $val['number'], 2);
										$sumMoneyTax+=$equs[$key]['moneyTax'];
									}
									if (!empty ($val['configCode'])) {
										$lastProId = $val['productId'];
									}
								}
								break;
							}
						}
					}
				}
//				if($audti){
					$conCostArr = array(
						'id'=>$object['id'],
						'saleCost'=>$sumMoney,
						'saleCostTax'=>$sumMoneyTax,
						'saleConfirm'=>'1',
						'saleConfirmName'=>$_SESSION['USERNAME'],
						'saleConfirmId'=>$_SESSION['USER_ID'],
						'saleConfirmDate'=>day_date,
                        'costRemark'=>$object['costRemark']
					);
					$contDao->updateById($conCostArr);
//				}

				$lastEquId = 0;
				//�������ϴ���
				$isSpecialPro = false;
				foreach ($equs as $key => $val) {
					if(empty($val['isDel'])){
						$val['isDel'] = '0';
					}
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
//						if($isSpecialPro){
//							//����������������´﷢��
//							$val['executedNum'] = $val['issuedShipNum'] = $val['number'];
//						}
					}
					if ($val['isDelTag'] != 1) {
                         if(empty($val['id'])){
                             $id = $this->add_d($val);
                         }else{
                             $this->edit_d($val);
                             $id = $val['id'];
                         }

						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
//							if(in_array($val['productId'], $specialProIdArr)){
//								$isSpecialPro = true;
//							}else{
//								$isSpecialPro = false;
//							}
						}
					}else if($val['isDelTag'] == '1' && !empty($val['id'])){
                        $this->delete(array (
                            'id' => $val['id']
                        ));
                    }
				}
//				if($audti){
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
                    //����������Ʒ�� �ɱ�ȷ����ϸ��¼
//                    $conDao = new model_contract_contract_contract();
                    $lineStr=$contDao->hadleSaleLineCostInfo($conCostArr,'add',$audti);
                    //�ύ״̬�Ĳ���Ҫ���ʼ�
                    if($audti){
                    	$this->sendMailAtAudit($object,'�ύ');
                    	$handleDao = new model_contract_contract_handle();
                    	$handleDao->handleAdd_d(array(
                    			"cid"=> $object['id'],
                    			"stepName"=> "����ȷ��",
                    			"isChange"=> 0,
                    			"stepInfo"=> $lineStr,
                    	));
                    }
//				}
				$contDao->countCost($object['id']);
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}

			$this->commit_d();
			return $linkObj['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���Ϸ��� ���
	 */
	function equChange_d($object,$audti=false) {
		try {
			$this->start_d();
			$contDao = new model_contract_contract_contract();
			$contract = $contDao->get_d($object['id']);
			$linkDao = new model_contract_contract_contequlink();
			if(!empty($object['oldId']) && $object['oldId'] != "undefined"){
		  	  	  $cid = $object['oldId'];
		  	  }else{
		  	  	  $cid = $object['id'];
		  	  }

			$linkObj = $linkDao->findBy('contractId', $cid);
			//�������������changeTipsΪ2������������Ϊ0���ᵼ��������ͨ���������parentEquId������ȷ���µ�������
			$updateTipsSql = " update oa_contract_equ set changeTips='0' where changeTips <> '2' and contractId='".$object['id']."'";
			$this->_db->query( $updateTipsSql );

			$linkObj['ExaStatus']='���';
			$linkObj['ExaDT']=day_date;
			$linkDao->edit_d($linkObj);

         if($audti){
         	$dateObj = array(
				'id'=>$object['id'],
				'standardDate'=>$object['standardDate'],
				'dealStatus'=>'1'
			);
         }else{
         	$dateObj = array(
				'id'=>$object['id'],
				'standardDate'=>$object['standardDate']
			);
         }

			$contDao->updateById($dateObj);
			$equs = array ();
			foreach ($object['detail'] as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $key => $value) {
//						if (empty ($value['configCode'])) { //������ת���ϵĸ�����
//							$value['parentRowNum'] = 0;
//							$value['parentEquId'] = 0;
//						}
						if (isset ($value['rowNum_'])) { //����
							$value['isCon'] = $value['productId'] . "_" . $value['rowNum_'];
						}
						$value['isConfig'] = $value['parentRowNum'];
						if (!empty ($value['id'])) {
							$value['oldId'] = $value['id'];
							unset ($value['id']);
						}
						if(empty($value['isDel'])){
                            if( empty($value['isDelTag'] )){
							    $value['isDel'] = 0;
							}else{
								$value['isDel'] = $value['isDelTag'];
							}
						}

						$equs[] = $value;
					}
				}
			}
		  if(empty($linkObj['id'])){
		  	//���������
		  	  if(!empty($object['oldId'])){
		  	  	  $cid = $object['oldId'];
		  	  }else{
		  	  	  $cid = $object['id'];
		  	  }
				$linkArr = array (
					"contractId" => $cid,
					"contractCode" => $object['contractCode'],
					"contractName" => $object['contractName'],
					"contractType" => 'oa_contract_contract',
					"contractTypeName" => $object['contractTypeName']
				);
				 $linkArr['ExaStatus']='���';
				 $linkArr['ExaDT']=day_date;
				 $linkArr['ExaDTOne']=day_date;
				 $linkId = $linkDao->add_d($linkArr, true);
		  }else{
		    $linkObj['equs'] = $equs;
			$linkObj['oldId'] = $linkObj['id'];
			$changeLogDao = new model_common_changeLog('contractequ',false);
			$tempObjId = $changeLogDao->addLog($linkObj);
		  }
			$equs = $this->processEquCommonInfo($equs, $contract);
			$equArr = $equs;
// 			foreach( $equs as $key=>$val ){
// 				if($val['isCon']!=''&&$val['isDel']==1){
// 					foreach($equArr as $index => $value){
// 						if($val['isCon']==$value['isConfig']){
// 							$equArr[$index]['isConfig']='';
// 							$equArr[$index]['parentEquId']=0;
// 						}
// 					}
// 				}
// 			}
				//�����ͬ�ɱ�
				$productInfoDao = new model_stock_productinfo_productinfo();
				$productIdArr = array();
				foreach( $equArr as $key=>$val ){
					if( $val['productId']!='' && $val['productId']!=0 ){
						$productIdArr[]=$val['productId'];
					}
				}
				$productIdArr=array_flip($productIdArr);
				$productIdArr=array_flip($productIdArr);
				$productIdStr = implode(',', $productIdArr);
				$productInfoDao->searchArr['idArr'] = $productIdStr;
				$productArr = $productInfoDao->list_d();
				$sumMoney = 0;
				$sumMoneyTax = 0;
				//�������ϴ���
				$specialProIdArr = explode(',', specialProId);
				$lastProId = 0;
				foreach( $equArr as $key=>$val ){
					if($val['isDelTag'] != 1){
						foreach( $productArr as $index=>$value ){
							if( $val['productId']==$value['id'] ){
								//�������ϵ����������ɱ����㣬ֻ�������ϵ��ۣ����������Ϊ0
								if (empty ($val['configCode']) && in_array($lastProId, $specialProIdArr)) {
									$equArr[$key]['price']=$value['priCost'];
// 									$equArr[$key]['money']=$val['number']*$value['priCost'];
									// ��17%˰�ʣ�����˰����
// 									$priceTax = bcmul($value['priCost'], '1.17', 2);
// 									$equArr[$key]['priceTax'] = $priceTax;
// 									$equArr[$key]['moneyTax'] = bcmul($priceTax, $val['number'], 2);
									$equArr[$key]['priceTax'] = $equArr[$key]['moneyTax'] = $equArr[$key]['money'] = 0;
								}else{
									if(!empty ($val['configCode']) && in_array($val['productId'], $specialProIdArr)){//�������ϸ���ȡҳ��ĳɱ�
										$equArr[$key]['price']=bcdiv($val['costEstimate'], $val['number'],2);
										$equArr[$key]['money']=$val['costEstimate'];
										$sumMoney+=$val['costEstimate'];
										// ��17%˰�ʣ�����˰����
										$moneyTax = bcmul($val['costEstimate'], bcadd($this->_defaultTaxRate,1,2), 2);
										$equArr[$key]['priceTax'] = bcdiv($moneyTax, $val['number'],2);
										$equArr[$key]['moneyTax'] = $moneyTax;
										$sumMoneyTax+=$moneyTax;
									}else{
                                        $price = $value['priCost'];
                                        $originalId = (isset($val['originalId']))? $val['originalId'] : (isset($val['oldId'])? $val['oldId'] : 0);

									    if($originalId > 0){// ����Ǳ���Ļ�,����û�������¶�ԭ��������ϵ���
                                            if(!empty($object['oldId'])){
                                                $cid = $object['oldId'];
                                            }else{
                                                $cid = $object['id'];
                                            }

                                            if($cid != $val['contractId'] && !isset($val['originalId'])){
                                                $originalIdObj = $this->find(array("id"=>$originalId),null,"originalId");
                                                $originalId = (isset($originalIdObj['originalId']))? $originalIdObj['originalId'] : $originalId;
                                            }

                                            $equsql = "select id,productCode,number,productId,price,priceTax from oa_contract_equ where contractId = '". $cid ."' and id = '" . $originalId . "'";
                                            $equChkArr = $this->_db->getArray($equsql);
                                            if($equChkArr && isset($equChkArr[0]) && isset($equChkArr[0]['id'])){
                                                if($equChkArr[0]['number'] == $val['number']){
                                                    $equCost = $equChkArr[0]['price'];
                                                    $price = ($equCost > 0)? $equCost : $price;
                                                }
                                            }
                                        }

										$equArr[$key]['price']=$price;
										$equArr[$key]['money']=$val['number']*$price;
										$sumMoney+=$val['number']*$price;
										// ��17%˰�ʣ�����˰����
										$priceTax = bcmul($price, bcadd($this->_defaultTaxRate,1,2), 2);
										$equArr[$key]['priceTax'] = $priceTax;
										$equArr[$key]['moneyTax'] = bcmul($priceTax, $val['number'], 2);
										$sumMoneyTax+=$equArr[$key]['moneyTax'];
									}
									if (!empty ($val['configCode'])) {
										$lastProId = $val['productId'];
									}
								}
								break;
							}
						}
					}
				}
				$conCostArr = array(
					'id'=>$object['id'],
					'oldId'=>$object['oldId'],
					'saleCost'=>$sumMoney,
					'saleCostTax'=>$sumMoneyTax,
					'saleConfirm'=>'1',
					'saleConfirmName'=>$_SESSION['USERNAME'],
					'saleConfirmId'=>$_SESSION['USER_ID'],
					'saleConfirmDate'=>day_date,
                    'costRemark'=>$object['costRemark']
				);
				$contDao->updateById($conCostArr);
				$lastEquId = 0;
				//�������ϴ���
				$isSpecialPro = false;
				foreach($equArr as $key => $val){
					if ( $val['productId'] == '' ){
						unset($equArr[$key]);
						continue;
					}
					//������������0��ɾ���ƻ��豸�嵥
					if ($val ['isDel'] == 1) {
						$val['id']= $val ['oldId'];
						$val['isDel']= 1;
						$val['changeTips'] = 3;
						$this->edit_d ( $val );
	//					$outplanequDao->deletes_d ( $val ['oldId'] );
					} elseif($val ['oldId']) {
						$val['id']= $val ['oldId'];
						$this->edit_d ( $val );
						if (!empty ($val['configCode'])) {
							//��ȡԴ������id
							if(isset($val ['originalId'])){
								$lastEquId = $val['originalId'];
							}else{
								$rs = $this->find(array('id' => $val['id']),null,'originalId');
								$lastEquId = $rs['originalId'];
							}
							if(empty($lastEquId)){//���ڱ���������ϼ����
								$lastEquId = $val ['oldId'];
							}
							if (empty ($val['configCode'])||$val['configCode']===0) {
//								if($isSpecialPro){
//									//����������������´﷢��
//									$val['executedNum'] = $val['issuedShipNum'] = $val['number'];
//								}
							}
//							if(in_array($val['productId'], $specialProIdArr)){
//								$isSpecialPro = true;
//							}else{
//								$isSpecialPro = false;
//							}
						}
					} else {
						$val['linkId']=$linkObj['id'];
						$val['changeTips'] = 2;
						if (empty ($val['configCode'])||$val['configCode']===0) {
							$val['parentEquId'] = $lastEquId;
//							if($isSpecialPro){
//								//����������������´﷢��
//								$val['executedNum'] = $val['issuedShipNum'] = $val['number'];
//							}
						}
						$id = $this->add_d ( $val );
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
//							if(in_array($val['productId'], $specialProIdArr)){
//								$isSpecialPro = true;
//							}else{
//								$isSpecialPro = false;
//							}
						}
					}
				}
//			$linkDao->confirmChange($linkObj['id']);
//          if($audti){
			 $contDao->updateShipStatus_d($object['id']);
			 $contDao->updateOutStatus_d($object['id']);
            if($object['isSubAppChange'] == '1'){
           	   //����������Ʒ�� �ɱ�ȷ����ϸ��¼
//			   $conDao = new model_contract_contract_contract();
                $lineStr=$contDao->hadleSaleLineCostInfo($conCostArr,"change",$audti);
            }else{
                $lineStr=$contDao->hadleSaleLineCostInfo($conCostArr,"change",$audti);
            }
            //�ύ״̬�Ĳ���Ҫ���ʼ�
            if($audti){
                $this->sendMailAtAudit($object,'���');
                if($object['isSubAppChange'] == '1'){
                	$handleDao = new model_contract_contract_handle();
                	$handleDao->handleAdd_d(array(
                			"cid"=> $object['oldId'],
                			"stepName"=> "����ȷ��",
                			"isChange"=> 2,
                			"stepInfo"=> $lineStr,
                	));
                }else{
                	$handleDao = new model_contract_contract_handle();
                	$handleDao->handleAdd_d(array(
                			"cid"=> $object['oldId'],
                			"stepName"=> "����ȷ�ϱ��",
                			"isChange"=> 1,
                			"stepInfo"=> "",
                	));
                }
            }
//          }
            //����������Ʒ�� �ɱ�ȷ����ϸ��¼
//            $conDao = new model_contract_contract_contract();
//            $conDao->hadleSaleLineCostInfo($conCostArr,"change");

//			//������������Ϲ���
//			$sql = "update oa_contract_equ o1 left join oa_contract_equ o2 on " .
//			"o1.contractId=o2.contractId and o1.id!=o2.id and o1.linkId=o2.linkId  SET o1.parentEquId=o2.id " .
//			"where o1.isConfig=o2.isCon and o1.isConfig!='' and o1.isConfig is not null";
//			$this->query($sql);
			//���º�ͬ�ɱ�����
            $contDao->countCost($object['id'],"change");
			$this->commit_d();
			return $linkObj;
		} catch (Exception $e) {
			echo $e->getMessage();
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ���������嵥��ͬ����
	 */
	function processEquCommonInfo($equs, $contract) {
		$mainArr = array (
			"contractId" => $contract['id'],
			"contractCode" => $contract['contractCode'],
			"contractName" => $contract['contractName'],
			"contractType" => $contract['contractType'],
			"contractTypeName" => $contract['contractTypeName']
		);
		if (!empty ($contract['linkId'])) {
			$mainArr["linkId"] = $contract['linkId'];
		}
		$equs = util_arrayUtil :: setArrayFn($mainArr, $equs);
		return $equs;
	}
	/**
	 * ��ȡ������ת������������
	 */
	function getBorrowToContractNum($borrowequId) {
		$sql = "select sum(number) as Num from oa_contract_equ where isBorrowToorder=1 and isTemp=0 and isDel=0 and toBorrowequId=$borrowequId";
		$num = $this->_db->getArray($sql);
		if (empty ($num[0]['Num'])) {
			return 0;
		} else {
			return $num[0]['Num'];
		}
	}

	/*********** ����Ϊ����������**************/
	/**
	 * ��ȡ�����豸��Ϣ
	 */
	function getLockEqus($contractId, $docType) {
		$this->searchArr=array("contractId"=>$contractId,"isTemp"=>0);
		$equs=$this->list_d();
		$lockDao = new model_stock_lock_lock();
		foreach ($equs as $key => $val) {
			$lockNum = $lockDao->getEquStockLockNum($val['id'], null, $docType);
			$equs[$key]['lockNum'] = $lockNum;
		}

		return $equs;
	}

	/**
	 * �����ͬ����ʱ��ʾ�豸
	 */
	function showLockEqusByContract($rows) {
		$i = 0;
		if ($rows) {
			$str = "";
			foreach ($rows as $key => $val) {
				$val['lockNum'] = isset ($val['lockNum']) ? $val['lockNum'] : 0;
				$canUseNum = $val['exeNum'] + $val['lockNum'];
				$proId = $val['productId'];
				$equId = $val['id'];
				if ($val['isDel'] == 1) {
					$productNo = "<font color=red>" . $val[productCode] . "</font>";
					$productName = "<font color=red>" . $val[productName] . "</font>";
				} else {
					$productNo = $val[productCode];
					$productName = $val[productName];
				}
				$lockNum = $val['number'] - $val['lockNum'];
				$str .=<<<EOT
							<tr align="center">
							<td>
						<input type="hidden" id="productId$i" value="$val[productId]" />
								$productNo/<br/>
								$productName

							<input type="hidden" equId="$equId" proId="$proId" value="$val[contractId]" name="lock[$i][objId]"/>

							<input type="hidden" equId="$equId" proId="$proId" value="$val[id]" name="lock[$i][objEquId]" id="equId$i"/>
							<input type="hidden" equId="$equId" proId="$proId" value="oa_contract_contract" name="lock[$i][objType]"/></td>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productId]" name="lock[$i][productId]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productName]" name="lock[$i][productName]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="$val[productCode]" name="lock[$i][productNo]"/>
							<input type="hidden" equId="$equId" proId="$proId" value="" id="inventoryId$i" name="lock[$i][inventoryId]"/>
								</td>

							<td width="8%"><div equId="$equId" proId="$proId" id="amount$i">$val[number]</div></td>
							<td width="8%">$val[executedNum]</td>

							<td width="8%"><font color="red"><div equId="$equId" proId="$proId" id="actNum$i">0</div></td>
							<td width="8%"><font color="red"><div equId="$equId" proId="$proId" id="exeNum$i">0</div></font></td>
							<td width="8%" title="��ǰ�ֿ����������">
								<font color="red">
							     	<a  href="javascript:toLockRecordsPage('$val[id]',true)" >
							     		<div equId="$equId" proId="$proId"  id="stockLockNum$i"></div>
							     	</a>
							     </font>
							</td>
							<td width="8%" title="���вֿ�����������ܺ�">
								<font color="red">
							     	<a href="javascript:toLockRecordsPage('$val[id]',false)">
							     		<div equId="$equId" proId="$proId"  id="lockNum$i"> $val[lockNum]</div>
							     	</a>
							     </font>
							</td>
							<td width="8%">0</td>
							<td width="8%">$val[issuedPurNum]</td>
							<td width="8%">$val[purchasedNum]</td>
							<td width="8%"><input type="text" equId="$equId" proId="$proId"  value="$lockNum" id="lockNumber$i" name="lock[$i][lockNum]" class="txtshort" onclick="$(this).css({'color':'black'})" onblur="checkLockNum(this,$i)"/></td>
							</tr>
EOT;
				$i++;

			}
			$str .= "<input type='hidden' id='rowNum' value='$i'/>";
		}

		return $str;
	}
/**************************************************************************************/

	/**
	 * �������´�ɹ�����
	 */
	function updateAmountIssued($id, $issuedNum, $lastIssueNum = false) {
		if (isset ( $lastIssueNum ) && $issuedNum == $lastIssueNum) {
			return true;
		} else {
			if ($lastIssueNum) {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=(ifnull(issuedPurNum,0)  + $issuedNum - $lastIssueNum) where id='$id'  ";
			} else {
				$sql = " update " . $this->tbl_name . " set issuedPurNum=ifnull(issuedPurNum,0) + $issuedNum where id='$id' ";
			}
			return $this->query ( $sql );
		}
	}
	/**
	 * ������;����
	 * $temId ��ͬ�ӱ�ID
	 * $num ����
	 * $type +/-  ��addΪ + /subtractionΪ����
	 */
	function updateOnWayNum($temId,$num,$type){
        if($type == "add"){
            $onWayNum = $num;
        }else{
            $onWayNum = $num * (-1);
        }
        $sql = "update " . $this->tbl_name . " set onWayNum = onWayNum + $onWayNum where id=".$temId."";
        $this->query($sql);
	}

	/**
	 * ��������id ��ȡ������Ϣ
	 */
	function getequInfoByids($equids){
        if(!empty($equids)){
          $sql = "select * from " . $this->tbl_name . " where id in ($equids)";
          $equinfoarr = $this->_db->getArray($sql);
          return $equinfoarr;
        }
          return "";
	}
	/**
	 * ����ȷ�� ������������
	 */
	function getNoProductEqu_d($contractId){
		$this->searchArr['contractId'] = $contractId;
		$this->searchArr['noContProductId'] = 0;
		$rows = $this->list_d();
		$goodsDao = new model_goods_goods_goodsbaseinfo();
		foreach($rows as $k=>$v){
			$proArr = $goodsDao->get_d($v['proId']);
			if(!empty($proArr)){
				unset($rows[$k]['proId']);
				$rows[$k]['proId'] = $proArr['goodsName'];
			}
		}
// 		echo "<pre>";
// 		print_R($rows);
		return $rows;
	}

	/**
	 * ���ݺ�ͬID ��ȡ������ת���۵�Դ��id
	 */
	function getBorrowIds($contractId){
       $findSql = "select toBorrowId from oa_contract_equ where isBorrowToorder = 1 and contractId = ".$contractId."";
       $borrowIds = $this->_db->getArray($findSql);
       return $borrowIds;
	}

	/**
	 * ��ִͬ��״���������
	 */
	function exeEqulist($contractId){
		$sql ="select productCode,productName,sum(number) as number,sum(executedNum) as executedNum,sum(costAct) as costAct " .
				"from oa_contract_equ where isDel = 0 and isTemp = 0 and contractId = '".$contractId."' GROUP BY productCode";
     	$rows = $this->_db->getArray($sql);
		if ($rows) {
			$i = 0; //�б��¼���
			$sNum = $i + 1;
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$unExeNum = $val['number']-$val['executedNum'];
				$str .= <<<EOT
                    <tr>
						<td>$val[productCode]</td>
						<td>$val[productName]</td>
						<td>$val[number]</td>
						<td>$val[executedNum]</td>
						<td>$unExeNum</td>
						<td>$val[costAct]</td>
					</tr>
EOT;
				$i ++;
		  }
		return $str;
	  }
	}

	function exeEqulistCost($contractId,$istemp){
		if($istemp == '1'){
            $rows = $this->getDetailTemp_d($contractId);
		}else{
			$rows = $this->getDetail_d($contractId);
		}
		if ($rows) {
			$i = 0; //�б��¼���
			$sNum = $i + 1;
			$str = ""; //���ص�ģ���ַ���
			foreach ( $rows as $key => $val ) {
				$moneyAll += $val['moneyTax'];
				$str .= <<<EOT
                    <tr>
						<td>$val[productCode]</td>
						<td>$val[productName]</td>
						<td>$val[number]</td>
						<td  class="formatMoney">$val[priceTax]</td>
						<td  class="formatMoney">$val[moneyTax]</td>
					</tr>
EOT;
				$i ++;
		  }
		      $str .= <<<EOT
                    <tr>
						<td></td>
						<td></td>
						<td></td>
						<td>�ϼ�</td>
						<td class="formatMoney">$moneyAll</td>
					</tr>
EOT;

		return $str;
	  }

	}


	/**
	 * ����ȷ���ʼ�֪ͨ
	 */
	 function sendMailAtAudit($object,$title){
	 	$mainDao = new model_contract_contract_contract();
	 	$mainObj = $mainDao->getContractInfo($object['id']);
	 	$outmailArr=array(
	 		$mainObj['prinvipalId'],
	 		$mainObj['createId']
	 	);
	 	$outmailStr = implode(',',$outmailArr);
	 	$outmailStr .= ",".EQUCONFIRMUSER;
		$addMsg = $this->sendMesAsAdd($mainObj);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['contractCode'], $outmailStr, $addMsg, '1');
	 }


	/**
	 * �ʼ��и���������Ϣ
	 */
	 function sendMesAsAdd($object){
			if(is_array($object ['equ'])){
				$j=0;
				$addmsg .= "</br></br><b>Ϊ���ⵢ���ͬ����ʱ�䣬���ͬ���۸����˳�պ˶ԣ�������������ȷ�ϡ���������������Ȩ����ϵ��</b></br></br>
				            </br></br><b>OAȷ�ϵ�ַ�����롾��ͬ����->���ҵĺ�ͬ��->�Ҽ����С���������ȷ�ϡ�</b></br></br>";
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>���ϱ��</td><td>��������</td><td>����ͺ�</td><td>��λ</td><td>����</td></tr>";
				foreach($object ['equ'] as $key => $equ ){
					$j++;
					$productCode=$equ['productCode'];
					$productName=$equ['productName'];
					$productModel=$equ ['productModel'];
					$unitName=$equ ['unitName'];
					$number=$equ ['number'];
					$addmsg .=<<<EOT
						<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$unitName</td><td>$number</td></tr>
EOT;
					}
//					$addmsg.="</table>" .
//							"<br><span color='red'>�����б����б���ɫΪ��ɫ�����ϣ�˵���������ǽ�����ת���۵ġ�</span></br>";
			}
			return $addmsg;
	 }

	 /**
	  * ��������ID ��ȡδִ������
	  * @param $productId ����id
	  */
	 function getExeNum($productIds){
	 	if(!empty($productId)){
	 		$sql="select SUM(number)-SUM(executedNum) as num from oa_contract_equ where productId in ($productIds)";
            $arr = $this->_db->getArray($sql);
             if(is_array($arr)){
				 return $arr[0]['num'];
			 }else{
				 return 0 ;
			 }
	 	}else{
	 		return 0;
	 	}
	 }

//��ȡ��ͬ�Ѿ�ȷ���˵�����
	function getConMat_d($cid,$pid){
		if(!empty($cid)){
			$sql = "select * from ".$this->tbl_name." where contractId = '".$cid."' and conProductId = '".$pid."' and isDel = 0 and isTemp = 0";
			$resultArr = $this->_db->getArray($sql);
			return $resultArr;
		}else{
			return 0;
		}
	}

	function closeopen_d($objArr){
		try{
			$this->start_d();
			$cid = $objArr['contractId'];
			$docType = $objArr['docType'];
			foreach($objArr['productsdetail'] as $k=>$v){
				if($docType == "oa_contract_contract"){
					$val = isset($v['isClose'])?$v['isClose']:0;
					if(!empty($val)||$val == 0){
						$sql = "update oa_contract_equ set isDel = '$val' where id='".$v['id']."' and contractId = '".$cid."'";
						$this->_db->query( $sql );
					}
				}else if($docType == "oa_borrow_borrow"){
					$val = isset($v['isClose'])?$v['isClose']:0;
					if(!empty($val)||$val == 0){
						$sql = "update oa_borrow_equ set isDel = '$val' where id='".$v['id']."' and borrowId = '".$cid."'";
						$this->_db->query( $sql );
					}
				}else if($docType == "oa_present_present"){
					$val = isset($v['isClose'])?$v['isClose']:0;
					if(!empty($val)||$val == 0){
						$sql = "update oa_present_equ set isDel = '$val' where id='".$v['id']."' and presentId = '".$cid."'";
						$this->_db->query( $sql );
					}
				}
			}
			if($docType == "oa_contract_contract"){
				$conDao = new model_contract_contract_contract();
				$conDao->updateContractState($cid);
			}
			$this->commit_d();
			return 1;
		}catch (Exception $e) {
			echo $e->getMessage();
			$this->rollBack();
			return null;
		}
	}

    /**
     * ������� - ͬ���ı����ݲ�����ʾ
     */
    function filterRows_d($object) {
        if ($object) {
            $markId = null;
            foreach ($object as $key => $val) {
                if ($markId == $val['conProductId']) {
                    unset ($object[$key]['conProductName']);
                } else {
                    $markId = $val['conProductId'];
                }
            }
        }
        return $object;
    }

    //��ȡ�˻���������
	function getBackNum($mid){
		$sql = "select SUM(number) as num from oa_contract_return_equ where contractequId='$mid'";
		$tempArr = $this->_db->getArray($sql);

		return $tempArr[0]['num'];
	}

}
?>