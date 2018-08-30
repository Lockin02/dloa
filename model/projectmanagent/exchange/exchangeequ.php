<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:11
 * @version 1.0
 * @description:���������嵥 Model��
 */
 class model_projectmanagent_exchange_exchangeequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_exchange_equ";
		$this->sql_map = "projectmanagent/exchange/exchangeequSql.php";
		parent::__construct ();
	}
   	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($exchangeId) {
		$this->searchArr['exchangeId'] = $exchangeId;
		$this->searchArr['isDel']=0;
		return $this->list_d();
	}

/***************************************����ȷ�� start*****************************************/
	/**
	 * ��ȡ��Ʒ�µ�������Ϣ
	 */
	function getProductEqu_d($id) {
		$productDao = new model_projectmanagent_borrow_product();
		$goodsArr = $productDao->resolve_d($id);
		$equArr = array ();
		if ($goodsArr != 0) {
			$equItemDao = new model_goods_goods_propertiesitem();
			$productInfoDao = new model_stock_productinfo_productinfo();
			$goodsInfoIdArr = array ();
			foreach ($goodsArr as $key => $val) {
				$goodsInfoIdArr[] = $key;
			}
			$goodsInfoIdStr = implode(',', $goodsInfoIdArr);
			$equItemDao->searchArr['ids'] = $goodsInfoIdStr;
			$productArr = $equItemDao->list_d();
			$productIdArr = array ();
			foreach ($productArr as $key => $val) {
				$productIdArr[] = $val['productId'];
			}
			$productIdStr = implode(',', $productIdArr);
			$productInfoDao->searchArr['idArr'] = $productIdStr;
			$equArr = $productInfoDao->list_d();
			foreach ($productArr as $row => $index) {
				foreach ($equArr as $key => $val) {
					if ($index['productId'] == $val['id']) {
						$equArr[$key]['productId'] = $val['id'];
						$equArr[$key]['productNo'] = $val['productCode'];
						$equArr[$key]['productModel'] = $val['pattern'];
						$equArr[$key]['number'] = $index['defaultNum'];
						unset ($equArr[$key]['id']);
					}
				}
			}
		}
		return $equArr;
	}

	/**
	 * @description �������ϴ���ҳ����ʾ��Ʒ��Ϣ
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array($rows)) {
			$i = 0; //�б��¼���
			foreach ($rows as $key => $val) {
				//��ͬ�豸�Ƿ�����
				$j = $i +1;
				$str .=<<<EOT
						<tr height="30px" class="tr_mouseover">
							<td width="35px">$j</td>
<!--							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="button"  value="��������"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>
							<td width="8%"><input type="button"  value="��Ʒ����"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>
						<tr id="product$j.H" style="display:none;text-align:left;">
							<td colspan="8"><b onclick="hideList('product$j');">�����嵥</b>
							  <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="չ��" alt="����ѡ��" /></td>
						</tr>
						<tr id="product$j">
							<td colspan="8" style="padding-left:35px">
							<fieldset style="width:200"><legend style="text-align:left">
							  <b onclick="hideList('product$j');">�����嵥</b>
							    <img src="images/icon/icon002.gif" onclick="hideList('product$j');" title="����" alt="����ѡ��" /></legend>
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
	function showItemChange($rows) {
		$j = 0;
		$str = ""; //���ص�ģ���ַ���
		if (is_array($rows)) {
			$i = 0; //�б��¼���
			foreach ($rows as $key => $val) {
				$img = '';
				//��ͬ�豸�Ƿ�����
				$j = $i +1;
				$str .=<<<EOT
						<tr height="30px" $trStyle >
							<td width="8%">$j</td>
<!--<img src="images/changeAdd2.png"/>&nbsp;							<td>$val[conProductCode]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>-->
							<td>$img &nbsp;$val[conProductName]<input type="hidden" id="conProductId$j" value="$val[id]"/></td>
							<td>$val[conProductDes]</td>
							<td width="8%">$val[number]<input type="hidden" id="number$j" value="$val[number]"/></td>
							<td width="8%">$val[unitName]</td>
							<td width="8%">
							<input type="hidden" value="$val[deploy]" id="deploy$j"/>
							<input type="hidden" value="$val[license]" id="license$j"/>
							<input type="button"  value="��������"  class="txt_btn_a" onclick="showLicense($val[license])"/></td>
							<td width="8%"><input type="button"  value="��Ʒ����"  class="txt_btn_a" onclick="showGoods('$val[deploy]','$val[conProductName]')" /></td>
						</tr>
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
		$name = 'exchange[detail][' . $itemNum . ']';
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
				$contDao = new model_projectmanagent_exchange_exchange();
				$linkDao = new model_projectmanagent_exchange_exchangeequlink();
				$linkDao->searchArr['exchangeId']=$object['id'];
				$contObj = $contDao->get_d($object['id']);
				$linkInfo = $linkDao->list_d();
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
					"exchangeId" => $contObj['id'],
					"exchangeCode" => $contObj['exchangeCode'],
					"exchangeType" => 'oa_contract_exchangeapply',
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
				$linkArr['linkId']=$linkId;
				//������ͬ����
				$equs = $this->processEquCommonInfo($equs, $linkArr);
				$lastEquId = 0;
				foreach ($equs as $key => $val) {
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
					}
//					$val['productNo'] = $val['productCode'];

					//������������0��ɾ���ƻ��豸�嵥
					if ($val ['id']) {
						if( $val ['isDelTag']==1 ){
							$val['isDel']= 1;
						}
						$this->edit_d ( $val );
					} else {
						$val['linkId']=$linkId;
						$id=$this->add_d ( $val );
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
						}
					}
				}
				if($audti){
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object,'�ύ');
				}
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
	function getEquByParentEquId_d($parentEquId) {
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
				$contDao = new model_projectmanagent_exchange_exchange();
				$linkDao = new model_projectmanagent_exchange_exchangeequlink();
				$linkObj = $linkDao->findBy('exchangeId', $object['id']);
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
				$this->delete(array (
					'exchangeId' => $object['id']
				));
				$equs = array ();
				foreach ($object['detail'] as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $key => $value) {
							unset($value['id']);
							$equs[] = $value;
						}
					}
				}
				$contObj['linkId'] = $linkObj['id'];
				//������ͬ����
//				echo "<pre>";
//				print_R($equs);
				$contObj['exchangeId']=$contObj['id'];
				$equs = $this->processEquCommonInfo($equs, $contObj);
				$lastEquId = 0;
				foreach ($equs as $key => $val) {
					if (empty ($val['configCode'])) {
						$val['parentEquId'] = $lastEquId;
					}
					if ($val['isDelTag'] != 1) {
						$id = $this->add_d($val);
						if (!empty ($val['configCode'])) {
							$lastEquId = $id;
						}
					}
				}
				if($audti){
					$contDao->updateShipStatus_d($object['id']);
					$contDao->updateOutStatus_d($object['id']);
					$this->sendMailAtAudit($object,'�ύ');
				}
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
	function equChange_d($object) {
		try {
			$this->start_d();
			$contDao = new model_projectmanagent_exchange_exchange();
			$linkDao = new model_projectmanagent_exchange_exchangeequlink();
			$contract = $contDao->get_d($object['id']);
			$linkObj = $linkDao->findBy('exchangeId', $object['id']);

			$updateTipsSql = " update oa_contract_exchange_equ set changeTips='0' where exchangeId='".$object['id']."'";
			$this->_db->query( $updateTipsSql );
			$dateObj = array(
				'id'=>$object['id'],
				'standardDate'=>$object['standardDate'],
				'dealStatus'=>'1'
			);
			$contDao->updateById($dateObj);
			$equs = array ();
			//echo "<pre>";print_r($object['detail']);
			foreach ($object['detail'] as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $key => $value) {
						if (!empty ($value['configCode'])) { //������ת���ϵĸ�����
							$value['parentRowNum'] = 0;
							$value['parentEquId'] = 0;
						}
						if (isset ($value['rowNum_'])) { //����
							$value['isCon'] = $value['productId'] . "_" . $value['rowNum_'];
						}
						$value['isConfig'] = $value['parentRowNum'];
						if (!empty ($value['id'])) {
							$value['oldId'] = $value['id'];
							unset ($value['id']);
						}
						$value['isDel'] = !empty($value['isDelTag'])?$value['isDelTag']:0;
						$equs[] = $value;
					}
				}
			}
			$linkObj['equs'] = $equs;
			$linkObj['oldId'] = $linkObj['id'];
			$changeLogDao = new model_common_changeLog('exchangeequ',false);
			$tempObjId = $changeLogDao->addLog($linkObj);
			$contract['exchangeId']=$contract['id'];
			$equs = $this->processEquCommonInfo($equs, $contract);
			foreach($equs as $key => $val){
				if ( $val['productId'] == '' ){
					unset($equs[$key]);
					continue;
				}
				//������������0��ɾ���ƻ��豸�嵥
				if ($val ['isDel'] == 1) {
					$val['id']= $val ['oldId'];
					$val['isDel']= 1;
					$val['changeTips'] = 3;
					$this->edit_d ( $val );
				} elseif($val ['oldId']) {
					$val['id']= $val ['oldId'];
					$this->edit_d ( $val );
				} else {
					$val['linkId']=$linkObj['id'];
					$val['changeTips'] = 2;
					$this->add_d ( $val );
				}
			}
//			unset ($linkObj['id']);
//			unset ($linkObj['isTemp']);
//			unset ($linkObj['originalId']);
//			$linkDao->confirmChange($linkObj['id']);
			$this->sendMailAtAudit($object,'���');
			$contDao->updateShipStatus_d($object['id']);
			$contDao->updateOutStatus_d($object['id']);
//			//������������Ϲ���
//			$sql = "update oa_contract_exchange_equ o1 left join oa_contract_exchange_equ o2 on " .
//			"o1.exchangeId=o2.exchangeId and o1.id!=o2.id and o1.linkId=o2.linkId and o2.isDel=0 SET o1.parentEquId=o2.id " .
//			"where o1.isConfig=o2.isCon and o1.isConfig!='' and o1.isConfig is not null";
//			$this->query($sql);
			$this->commit_d();
			return $linkObj['id'];
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
			"exchangeId" => $contract['exchangeId'],
			"exchangeCode" => $contract['exchangeCode'],
			"exchangeType" => 'oa_contract_exchangeapply',
		);
		if (!empty ($contract['linkId'])) {
			$mainArr["linkId"] = $contract['linkId'];
		}
		$equs = util_arrayUtil :: setArrayFn($mainArr, $equs);
		return $equs;
	}
/****************************************����ȷ�� end*********************************************************/
	/**
	 * ����ȷ�� ������������
	 */
	function getNoProductEqu_d($contractId){
		$this->searchArr['exchangeId'] = $contractId;
		$this->searchArr['noContProductId'] = 0;
//		$this->searchArr['isTemp'] = 0;
//		$this->searchArr['notDel'] = 0;
		return $rows = $this->list_d();
	}

	/**
	 * ����ȷ���ʼ�֪ͨ
	 */
	 function sendMailAtAudit($object,$title ){
	 	$mainDao = new model_projectmanagent_exchange_exchange();
	 	$mainObj = $mainDao->getDetailInfo($object['id']);
	 	$outmailArr=array(
	 		$mainObj['saleUserId']
	 	);
	 	$outmailStr = implode(',',$outmailArr);
		$addMsg = $this->sendMesAsAdd($mainObj);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $title, $mainObj['exchangeCode'], $outmailStr, $addMsg, '1');
	 }


	/**
	 * �ʼ��и���������Ϣ
	 */
	 function sendMesAsAdd($object){
			if(is_array($object ['equ'])){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>���ϱ��</td><td>��������</td><td>����ͺ�</td><td>��λ</td><td>����</td></tr>";
				foreach($object ['equ'] as $key => $equ ){
					$j++;
					$productCode=$equ['productNo'];
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
}
?>