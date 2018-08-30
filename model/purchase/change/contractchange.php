<?php

/**
 * @description:�ɹ���ͬ������汾����
 * @author qian
 * 2011-2-13 pm 13:47
 */

class model_purchase_change_contractchange extends model_base {
	/*
	 * @description ���캯��
	 */
	function __construct() {
		$this->tbl_name = "oa_purch_apply_basic";
		$this->sql_map = "purchase/change/contractchangeSql.php";
		//ע�������ֵ��ֶ�
		$this->datadictFieldArr = array ("billingType", "paymentType", "paymentCondition" );
		parent :: __construct();

	}

	/**
	 * @description �Ժ�ͬ��ǩԼ��ͬ��ֵ����ת��
	 * @author qian
	 * @date 2011-03-09 16:23
	 */
	function signStatus_d($signStatus){
		if($signStatus){
			switch($signStatus){
				case "0" : return "δǩԼ";
				case "1" : return "��ǩԼ";
				case "2" : return "���õ�ֽ�ʺ�ͬ";
				case "3" : return "���ύֽ�ʺ�ͬ";
			}
		}
		return null;
	}

	/**
	 * @description �༭�޸�ǩԼ��ͬ��״̬λ
	 * @author qian
	 * @date 2011-03-09
	 */
	function editSignStatus_d($value){
		if($value == "0"){
			$val1 = "checked";
			$val2 = "";
			$val3 = "";
			$val4 = "";
		}elseif($value == "1"){
			$val1 = "";
			$val2 = "checked";
			$val3 = "";
			$val4 = "";
		}elseif($value == "2"){
			$val1 = "";
			$val2 = "";
			$val3 = "checked";
			$val4 = "";
		}elseif($value == "3") {
			$val1 = "";
			$val2 = "";
			$val3 = "";
			$val4 = "checked";
		}
		$str=<<<EOT
			<input type="radio" name="sales[signStatus]" value="0" $val1>δǩԼ
	 		<input type="radio" name="sales[signStatus]" value="1" $val2>��ǩԼ
	 		<input type="radio" name="sales[signStatus]" value="2" $val3>���õ�ֽ�ʺ�ͬ
	 		<input type="radio" name="sales[signStatus]" value="3" $val4>���ύֽ�ʺ�ͬ
EOT;
		return $str;


	}

	/**
	 * @description ����������ݱ��浽�汾����ȥ
	 * @author qian
	 * @date 2011-2-14 11:34
	 */
	function change_d($rows) {
		$contract = $_POST['contract'];

		//�����豸�İ汾���������
		$id = $this->addVersion_d($contract);

		return $id;
	}

	/**
	 * @description ��ͬ���ʱ�������ͬ�����ݣ�������ͬ���豸�����ݸ��Ƶ���Ӧ�İ汾���У�
	 * 				�汾������ж��Ƿ��ǵ�һ�α����
	 * 				�ǣ���洢��ͬ��ԭʼ���ݣ����Զ�����һ���µ��а汾�ŵĺ�ͬ��¼��
	 * 				�������ɰ汾��¼�����ݡ�
	 * @param $applyNumb ��ͬ���
	 * @author qian
	 * 2011-2-13 pm 14:05
	 */
	function addVersion_d($rows) {
		if (is_array($rows)) {
			//ʹ��PHP���У�������һ����ֵID������С����
			$rows2 = array_shift($rows);

			//���ݺ�ͬ����жϷú�ͬ�Ƿ���ڰ汾����
			$condiction = array (
				"applyNumb" => $rows['applyNumb']
			);
			$count = $this->findCount($condiction);
			$equipmentDao = new model_purchase_change_equipmentchange();


			//���ݴ���ĺ�ͬ����жϰ汾�����Ƿ���ڴ˺�ͬ����
			//�ޣ����ǵ�һ�α��
			//ִ�еĲ����ǽ���ͬ�����ݸ��Ƶ��汾�����������һ���µİ汾����
			if (!$count) {
				try {
					$this->start_d();
					//��ͬ
					//�ɹ���ͬԭʼ���ݵ�Ĭ�ϰ汾����0
					$rows['version'] = 0;
					$id = $this->add_d($rows,true);
					//�豸
					//�豸�嵥������Ҳ��Ĭ�ϱ�����豸�İ汾���У�Ĭ�ϵİ汾��Ϊ0
					$rows['equs']['isChanged'] = 0;
					foreach($rows['equs'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['basicId'] = $id;
						$equipmentDao-> addEquVersion_d($val);
					}

					//����ԭʼ�汾����Ҫ������һ���汾��¼,���ں�ͬ���
					$rows['version'] = 1;
					$id = $this->add_d($rows,true);
					//Ȼ���ٱ���һ�����ݣ����ڱ������
					foreach($rows['equs'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['isChanged'] = 0;
						//����ɹ�����Ϊ0���򽫲ɹ���������Ϊ1
						if($val['amountAll'] == 0){
							$val['changeType'] = 1;
						}
						$val['basicId'] = $id;
						$equipmentDao-> addEquVersion_d($val);
					}

					$this->commit_d();
					return $id;
				} catch (Exception $e) {
					$this->rollBack();
					return null;
				}
			} else {
				try{
					$this->start_d();
					//�����ͬ���ǵ�һ�α�����򽫺�ͬ���ڵ����ݸ��Ƶ��汾����ҵ��汾���������ݣ������汾��+1
					$this->searchArr = array (
						"applyNumb" => $rows['applyNumb']
					);
					//���ݰ汾�ţ��õ�MAXֵ�����汾�����ֵ����ֵ�������µĺ�ͬ�汾����
					$version = $this->listBySqlId("select_max1");
					$rows['version'] = (int)++$version[0]['version'];

					$id = $this->add_d($rows,true);

					$equipmentRows = $equipmentDao->listBySqlId("select_max");

					//��ȡ�豸����߰汾�ţ�������1�������µİ汾�ţ������±�����豸
					$equCondiction = array(
						"version"=>"select max(version) from oa_purch_apply_equ_version",
						"applyNumb"=>$rows['applyNumb']
					);
//					$equVersion = $equipmentDao->listBySqlId("version_max");

					//���豸����Ϣ����
					foreach($rows['equs'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['basicId'] = $id;
						$equipmentDao->addEquVersion_d($val);
					}

					//����ʱ�豸����Ϣ����
					foreach($rows['temp'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['basicId'] = $id;
						$equipmentDao->addEquVersion_d($val);
					}

					return $id;

					$this->commit_d();
				}catch(Exception $e){
					$this->rollBack();
					return null;
				}


			}
		}
	}


	/**
	 * @description �鿴��ʷ�汾���ı��滻
	 * @author qian
	 * @date 2011-2-17 17:08
	 */
	function toViewHistory_d($applyNumb){
		$equipmentDao = new model_purchase_change_equipmentchange();
		//���ݱ����ȡ��ͬ������
		$condiction = array('applyNumb' => $applyNumb);
		$versionRows = $this->findAll($condiction,null,'version,applyNumb,id');
		$str = "";
		//�ֱ��滻�汾������
		foreach($versionRows as $key => $val){
			$url = "?model=purchase_change_contractchange&action=toViewVersion&id=".$val['id'];
			$str .= "<a href='$url'>"."�汾".$val['version']."</a><br>";
		}
		return $str;
	}


	/**
	 * @description ͨ���汾�����ID����ȡ�汾��ͬ���豸������
	 * @author qian
	 * @date 2011-2-18 10:04
	 */
	 function getRows_d($id){
	 	if($id){
	 		$rows = array();
	 		$equDao = new model_purchase_change_equipmentchange();
//	 		$rows = $this->get_d($id);
			$contractCondiction = array("id" => $id);
			$temp = $this->findAll($contractCondiction,null,"dateHope,applyNumb,dateFact,instruction,remark,billingType,paymetType,updateId,updateName,updateTime");
			$rows = $temp[0];
	 		//���ֶβ���һ����¼
	 		$equCondiction = array("basicId" => $id);
	 		$rows['equs'] = $equDao->findAll($equCondiction,null);

	 		return $rows;
	 	}
	 }


	/**
	 * @description ����ͨ��֮�󣬽��汾��������ݸ��ǵ��ɹ���ͬ���
	 * @author qian
	 * @date 2011-2-15 14:36
	 */
	function coverChange_d($rows){
		//���ݺ�ͬID��������ȷ����Ҫ���ǵĺ�ͬ���豸
		$condiction1 = array("applyNumb"=>$rows['applyNumb']);

		try{
			$this->start_d();

			$equipmentDao = new model_purchase_contract_equipment();
			$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
			$taskEquDao=new model_purchase_task_equipment();

			//�����豸
			foreach($rows['equs'] as $key => $val){
				unset($val['id']);

				//���²ɹ�����ĺ�ͬ����
				$inquiryEquRows=$inquiryEquDao->get_d($val['inquiryEquId']);
				$equipmentRows=$equipmentDao->get_d($val['oldId']);
				$taskEquDao->updateContractAmount($inquiryEquRows['taskEquId'],0,$equipmentRows['amountAll']-$val['amountAll']);


				//����ֶΡ�oldId����Ϊ�գ���������豸��ԭ�����豸��������ʱ�ɹ��豸
				if($val['oldId']){
					$condiction = array("id"=>$val['oldId']);
					//��ɾ���豸��������豸��Ϣ

					unset($val['basicId']);
					$equipmentDao->update($condiction,$val);

				}else if($val['oldId'] == null){
					unset($val['productId']);
					unset($val['oldId']);
					unset($val['applyFactPrice']);
					unset($val['amountIssued']);
					unset($val['dateIssued']);
					unset($val['dateEnd']);
					$equipmentDao->add_d($val,true);

				}
			}

			//�ȴݻ��豸�����飬�ٸ��Ǻ�ͬ
			unset($rows['equs']);

			$contractDao = new model_purchase_contract_purchasecontract();
			$contract = $contractDao->update($condiction1,$rows);

//			$this->rollBack();
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	}



	/*
	 * @desription ��ȡ��ͬ�豸��Ϣ
	 * @param tags
	 * @author qian
	 * @date 2011-1-8 ����05:32:30
	 */
	function getEquipments_d ($rows) {
		if(is_array($rows)){
			//ͨ����ͬ��ID���汾������ȡ����
			$equDao = new model_purchase_change_equipmentchange();
			$equDao->searchArr = array(
				'basicId'=>$rows['id'],
				'version'=>$rows['version']
			);
			$rows = $equDao->listBySqlId("select_default");
			$interfObj = new model_common_interface_obj();
			foreach( $rows as $key => $val ){
				$rows[$key]['purchTypeC'] = $interfObj->typeKToC( $val['planEquType'] );		//��������
			}
		}
		return $rows;
	}


	/*
	 * @desription ��ͬ�豸--���ҳ
	 * @param tags
	 * @author qian
	 * @date 2011-1-5 ����07:45:52
	 */
	function addContractEquList_s ($listEqu) {
		$str="";
		$i=0;
		//�ɹ�����
	    $interfObj = new model_common_interface_obj();

		if($listEqu){
			foreach($listEqu as $key=>$val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$listEqu[$key]['purchTypeC'] = $interfObj->typeKToC( $val['planEquType'] );		//��������

				$str.=<<<EOT
					<tr class="$classCss">
					    <td>$i</td>
						<td>
						<input type="hidden" name="contract[equs][$i][inquiryEquId]" value="$val[id]"/>
						<input type="hidden" name="contract[equs][$i][taskEquId]" value="$val[taskEquId]"/>
						<input type="hidden" name="contract[equs][$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							$val[productNumb]<br>
							$val[productName]
						</td>
						<td>$val[basicNumb]</td>
						<td>$val[purchTypeC]</td>
						<td>
							$val[amountAll]
						</td>
						<td>$val[amountIssued]</td>
						<td>
							$val[dateHope]
						</td>
						<td class="formatMoney">
							$val[applyPrice]
						</td>
						<td >
							<textarea class="textarea_read" readonly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}


	/*
	 * @desription ��Ʒ�嵥--�༭ҳ
	 * @param tags
	 * @author qian
	 * @date 2011-1-12 ����03:18:01
	 */
	function editContractEquList_s ($listEqu) {
		$taskEquDao=new model_purchase_task_equipment();
		$interfObj = new model_common_interface_obj();
		$str="";
		$i=0;
		if($listEqu){
			foreach($listEqu as $key=>$val){
				$rows=$taskEquDao->get_d($val['taskEquId']);
				$conNumUse=$rows['amountAll']-$rows['contractAmount']+$val['amountAll'];
				$listEqu[$key]['purchTypeC'] = $interfObj->typeKToC( $val['oPurchType'] );		//��������
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str.=<<<EOT
					<tr class="$classCss">
					    <td>$i</td>
						<td>
						<input type="hidden" name="equs[$i][id]" value="$val[id]" />
						<input type="hidden" name="equs[$i][basicId]" value="$val[basicId]" />
						<input type="hidden" name="equs[$i][taskEquId]" value="$val[taskEquId]"/>
						<input type="hidden" name="equs[$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" name="equs[$i][productId]" value="$val[productId]"/>
							$val[productNumb]
						</td>
						<td>
							$val[productName]
						</td>
						<td>
							$val[basicNumb]
						</td>
						<td>
							$val[purchTypeC]
						</td>
						<td>
							<input type="text" class="txtshort" id="amountAll" name="equs[$i][amountAll]" value="$val[amountAll]">
							<input type="hidden" name="amountAll" value="$conNumUse">
							<input type="hidden"  id="amountOld" name="equs[$i][amountOld]" value="$val[amountAll]">
						</td>
						<td>
							$val[amountIssued]
						</td>
						<td>
							<input type="text" class="txtshort" id="dateHope" name="equs[$i][dateHope]" value="$val[dateHope]" onfocus="WdatePicker()" readonly>
						</td>
						<td>
							<input type="text" class="txtshort " id="applyPrice" name="equs[$i][applyPrice]" value="$val[applyPrice]">
						</td>
						<td>
							<textarea rows="2" id="remark" name="equs[$i][remark]" >$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @description ͨ��ǩԼ״̬��ֵ����ȡ���Ӧ��ֵ
	 * @author qian
	 * @date 2011-2-28
	 */
	function getSignStatus_d($value){
		if ($value == 0) {
			$value = "δǩԼ";
		}else if ($value == 1) {
			$value = "��ǩԼ";
		} else if($value== 2){
			$value = "���õ�ֽ�ʺ�ͬ";
		} else if($value== 3){
			$value = "���ύֽ�ʺ�ͬ";
		}else{
			$value = "������ǩ��";
		}
		return $value;
	}


}
?>