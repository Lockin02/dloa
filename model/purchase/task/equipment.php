<?php
/**
 * @exclude �ɹ������豸��
 * @abstract oae/model/purchase/task
 * @author ouyang
 * @version 2010-8-10 ����09:57:22
 */
class model_purchase_task_equipment extends model_base{

	//״̬λ
	private $status;

	public $statusDao; //״̬��

	function __construct() {
		$this->tbl_name = "oa_purch_task_equ";
		$this->sql_map = "purchase/task/equipmentSql.php";
		parent :: __construct();
		$this->status = array(
			0 => array(
				"stateEName" => "execution",
				"stateCName" => "ִ��",
				"stateVal" => "1"
			),
			1 => array(
				"stateEName" => "locking",
				"stateCName" => "����",
				"stateVal" => "2"
			),
			2 => array(
				"stateEName" => "end",
				"stateCName" => "���",
				"stateVal" => "3"
			),
			3 => array(
				"stateEName" => "close",
				"stateCName" => "�ر�",
				"stateVal" => "4"
			)
		);

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array(
				"statusEName" => "execution",
				"statusCName" => "ִ��",
				"key" => "1"
			),
			1 => array(
				"statusEName" => "locking",
				"statusCName" => "����",
				"key" => "2"
			),
			2 => array(
				"statusEName" => "end",
				"statusCName" => "���",
				"key" => "3"
			),
			3 => array(
				"statusEName" => "close",
				"statusCName" => "�ر�",
				"key" => "4"
			)
		);

		//���ó�ʼ�����������
		parent::setObjAss();

	}

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 0; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

/*****************************************ҳ��ģ����ʾ********************************************/
	/*�ɹ̶��ʲ��������ɲɹ�����*/
	function showAddTask_s($listEqu){
//		echo "<pre>";
//		print_r($listEqu);
		$interfObj = new model_common_interface_obj();
		$str="";
		$i = $m = 0;
		if( is_array( $listEqu ) ){
			foreach ($listEqu as $key => $val) {
				$val['purchTypeCName'] = $interfObj->typeKToC("oa_asset_purchase_apply");		//��������
				$i++;
				++$m;
				if($val['supplierName']!=""){
					$remark="��Ӧ��:".$val['supplierName'].","."����:".$val['price'];
				}else{
					$remark="����:".$val['price'];
				}
				$taskAmount=$val['purchAmount']-$val['issuedAmount'];

				$str.=<<<EOT
					<tr height="28" align="center">
						<td   width="5%">$i
							</p>
						</td>
						<td>
							<input type="text" class="readOnlyText" name="basic[equment][$m][productName]" value="$val[productName]" readonly/>
							<input type="hidden" name="basic[equment][$m][productId]" value="$val[productId]" />
							<input type="hidden" name="basic[equment][$m][productNumb]" value="$val[productCode]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort " name="basic[equment][$m][pattem]" value="$val[pattem]" readonly/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort " name="basic[equment][$m][unitName]" value="$val[unitName]" readonly/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$val[purchTypeCName]" readonly/>
						</td>
						<td >
							<input type="text" name="basic[equment][$m][amountAll]" id="taskAmount$m" value="$taskAmount"  class="taskAmount txtshort">
							<input type="hidden" name="myAmount" value="$taskAmount" />
							<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
							<input type="hidden" name="basic[equment][$m][contractAmount]" value="0" />
							<input type="hidden" name="basic[equment][$m][purchType]" value="oa_asset_purchase_apply" />
							<input type="hidden" name="basic[equment][$m][purchTypeCName]" value="�ʲ��ɹ�" />
							<input type="hidden" name="basic[equment][$m][applyNum]" value="$val[applyCode]" />
							<input type="hidden" name="basic[equment][$m][applyId]" value="$val[applyId]" />
							<input type="hidden" name="basic[equment][$m][applyEquId]" value="$val[id]" />
						</td>
						<td>
							<input class='txtshort datehope' type="text" id="dateHope$m" name="basic[equment][$m][dateHope]"  value="$val[dateHope]" onfocus="WdatePicker()"  readonly />
						</td>
						<td>
							<input class="txt" name="basic[equment][$m][remark]" id="remark$m" value=""/>
						</td>
				        <td>
							 <img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
					    </td>
			    </tr>
EOT;
				}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**
	 * @desription �½�ѯ�۵��б�
	 * @param $listEqu	�ɹ�������������
	 * @date 2010-12-24 ����02:53:57
	 */
	function newInquiry_s($listEqu){
		$str="";
		$i = $m = 0;
		if( is_array( $listEqu ) ){
			$i = $m = 0;
			foreach ($listEqu as $key => $val) {
				$i++;
				$str.=<<<EOT
				<tr height="28" align="center">
					<td>
						<p class="childImg">
							<image src="images/expanded.gif" />$i
						</p>
					</td>
					<td>
						$val[productNumb]/$val[productName]
					</td>
					<td>
						<p class="allAmount">$val[allAmount]</p>
					</td>
					<td width="75%" class="td_table">
						<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
					$str.=<<<EOT
						<tr align="center">
								<td>
									$chdVal[basicNumb]
								</td>
								<td width="14%">
									$chdVal[purchTypeCName]
								</td>
								<td  width="12%">
									<input type="text" name="basic[equment][$m][amountAll]" id="amountAll$m" value="$chdVal[amountNotIssued]" size=6 class="taskAmount">
									<input type="hidden" name="amountAll" value="$chdVal[amountNotIssued]" />

									<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]" />
									<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]" />
									<input type="hidden" name="basic[equment][$m][productNumb]" value="$chdVal[productNumb]" />

									<input type="hidden" name="basic[equment][$m][taskId]" value="$chdVal[basicId]" />
									<input type="hidden" name="basic[equment][$m][taskNumb]" value="$chdVal[basicNumb]" />
									<input type="hidden" name="basic[equment][$m][taskEquId]" value="$chdVal[id]" />
								</td>
								<td width="14%">
									&nbsp;<input type="text" id="hopeTime$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput" value=""  onfocus="WdatePicker()"  readonly />
								</td>
								<td width="12%">
									&nbsp;<input type="text" id="applyPrice$m" name="basic[equment][$m][applyPrice]" size="9" maxlength="12" value=""/>
								</td>
								<td width="20%">
									<textarea rows="2" cols="18" name="basic[equment][$m][remark]" id="remark$m"></textarea>
								</td>
							</tr>
EOT;
					++$m;
				}
				$str.=<<<EOT
        					</table>
				        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
				        </td>
				    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}

	/**�ɹ�����ִ���������嵥��ʾģ��
	*author can
	 * @param	$rows �ɹ���������
	*/
	function showEqulist_s($rows){

		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$systeminfoDao=new model_stock_stockinfo_systeminfo();
		$stockSysObj=$systeminfoDao->get_d("1");
		$saleStockId=$stockSysObj['salesStockId'];
		$str = "";
		$i = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				$i++;
//				$iClass = $i%2+1;
				$addAllAmount = 0;
				$strTab="";
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					$i++;
					$iClass = (($i%2)==0)?"tr_even":"tr_odd";
//					if( isset( $chdVal['contractAmount']) && $chdVal['contractAmount']!="" ){
//						$amountOk = $chdVal['amountAll'] - $chdVal['contractAmount'];
//					}else{
//						$amountOk = $chdVal['amountAll'];
//					}
//					$addAllAmount += $amountOk;
					if($chdVal['amountNotIssued']!=$chdVal['amountAll']){
						$basicNumb="<font color='blue'>".$chdVal['basicNumb']."</font>";
					}else{
						$basicNumb=$chdVal['basicNumb'];
					}

					if(  $chdVal['amountAll']-$chdVal['contractAmount']<0||$chdVal['amountAll']-$chdVal['contractAmount']==0){
						$checkBoxStr =<<<EOT
				    	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$basicNumb
EOT;
					}else{
						$checkBoxStr =<<<EOT
						<input type="checkbox" class="checkChild" >
				    	<input type="hidden" class="hidden" value="$chdVal[id]"/>$basicNumb
EOT;
					}

					$strTab.=<<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td  align="left"  name="tdth05">
						    	$checkBoxStr
					        </td>
					        <td  width="18%"  name="tdth06">
					             $chdVal[purchTypeCName]
					        </td>
					        <td  width="12%"  name="tdth07">
					            $chdVal[amountAll]
					        </td>
					        <td  width="12%"  name="tdth08">
					             $chdVal[amountIssued]
					        </td>
					        <td  width="12%"  name="tdth09">
					           $chdVal[amountNotIssued]
					        </td>
					        <td  width="16%"  name="tdth10">
					            $chdVal[dateHope]
					        </td>
		            	</tr>
EOT;
				}

				$val['actNum']= $inventoryDao->getActNumByProId( $val['productId']);

				$inventoryDao->searchArr=array("stockId"=>$saleStockId,"productId"=>$chdVal['productId']);
				$inventoryArr=$inventoryDao->listBySqlId();
				$stockNum=$inventoryArr[0]['exeNum'];
				if(!is_array($inventoryArr)){
					$stockNum="0";
				}


				$str .=<<<EOT
					<tr class="$iClass">
				        <td    height="30"  name="tdth01">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td  name="tdth02">
				            $val[productNumb]<br>$val[productName]
				        </td>
				        <td  name="tdth03">
				            <a target="_blank" title="�鿴��ʱ���" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
				        </td>
				        <td   width="8%"  name="tdth04">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><input id="allCheckbox$i" type="checkbox">$val[notContractAll]</p>
				            </p>
							<SCRIPT language=JavaScript>
								if($val[notContractAll]==0){
									jQuery("#allCheckbox$i").hide();
								}
							</SCRIPT>
				        </td>
				        <td width="50%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
				        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
				        </td>
				    </tr>
EOT;

			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;
	}
	/*
	 * �����������ϻ�ȡ��������
	 */
	 function getReceiveNum($rows){
	 	if (is_array($rows)) {
			foreach ( $rows as $key => $val ) {
					$arrivalNum=0;
					//��ȡ����������Ϣ
					$this->searchArr=array("getPlanEquId"=>$val['id']);
					$arrivalEquRows = $this->listBySqlId("select_arrivalNum");
							if(is_array($arrivalEquRows)){   //��ȡĳ���ϵ��������
								foreach($arrivalEquRows as $arrKey=>$arrVal){
									$arrivalNum=$arrivalNum+$arrVal['arrivalNum'];		//��������
								}
							}
					$rows[$key]['arrivalNum'] = $arrivalNum;
			}
			return $rows;
	 	}
	 	return $rows;
	 }
	/**
	 * �ɹ���������ִ�����
	 *
	 */
	 function showEquProgressList($listEqu){
		$str = "";
		$i = 0;
		if (is_array($listEqu)) {
			$orderEquDao=new model_purchase_contract_equipment();
			$orderDao=new model_purchase_contract_purchasecontract();
			$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
			$inquiryDao=new model_purchase_inquiry_inquirysheet();
			$arrivalEquDao=new model_purchase_arrival_equipment();
			$payDao=new model_finance_payables_detail();
			$taskDao=new model_purchase_task_basic();
			$applyDao=new model_asset_purchase_apply_apply();
			foreach ( $listEqu as $key => $val ) {
				$i ++;
				$taskState=$taskDao->statusDao->statusKtoC($val['state']);
				$iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
				if($val['state']=='4'){
					$iClass="tr_black";
					$closeClass="tr_black";
				}else{
					$closeClass="";
				}
				if($val['purchType']=='oa_asset_purchase_apply'){
					$planNumb=$this->get_table_fields('oa_asset_purchase_apply', "id='".$val['applyId']."'", 'formCode');
				}else{
					$planNumb=$val['planNumb'];
				}
				$str .= <<<EOT
					<tr id="$iClass" class="$iClass" onclick="changeClass(this);">
						<td   name="tdth01">$i</td>
		                <td  width="7%"   name="tdth02">$val[sendTime]</td>
		                <td  width="7%"   name="tdth03">$val[dateReceive]</td>
						<td  width="20%"   name="tdth04">$val[productNumb]<img src="images/icon/view.gif" title="�鿴������ϸ��Ϣ" onclick="viewProduct('$val[productId]');"/><br>$val[productName]</td>
						<td  width="11%"   name="tdth05">$planNumb</td>
						<td  width="5%"   name="tdth06">$val[sendName]</td>
						<td  width="5%"   name="tdth07">$val[amountAll]</td>
						<td  width="5%"   name="tdth08">$taskState</td>
				        <td width="38%" class="tdChange td_table" colspan="6">
							<table width="100%"  class="shrinkTable main_table_nested">
EOT;
						//��ȡ����������Ϣ
						$orderEquRows=$orderEquDao->getProgressEqusByTequId($val['id']);
						if(is_array($orderEquRows)){
							foreach($orderEquRows as $oKey=>$oVal){
//									echo"<pre>";
//								print_r($oVal['id']);
								$arrivalEquRows=$arrivalEquDao->getItemByContractEquId_d($oVal['id']);

								$payed=$payDao->getPayedMoney_d($oVal['id'],$objType = 'YFRK-01');
								$stateName=$orderDao->stateToVal ($oVal ['state']);
								if($payed==0){
									$payStr='δ��';
								}else if($payed==$oVal['moneyAll']){
									$payStr='�Ѹ�';
								}else{
									$payStr='����';
								}
								$arrivalNum=0;
								if(is_array($arrivalEquRows)){   //��ȡĳ���ϵ��������
									foreach($arrivalEquRows as $arrKey=>$arrVal){
										$arrivalNum=$arrivalNum+$arrVal['arrivalNum'];
									}
								}
								$str .= <<<EOT
									<tr  class="childrenTr $closeClass">
										<td width="16%"   name="tdth12">$oVal[amountAll]</td>
										<td width="18%"   name="tdth13">$oVal[ExaStatus]</td>
										<td width="18%"   name="tdth14">$stateName</td>
										<td width="16%"   name="tdth15">$arrivalNum</td>
										<td width="16%"   name="tdth16">$oVal[amountIssued]</td>
										<td width="16%"   name="tdth17">$payStr</td>
									</tr>
EOT;

							}
						}else{
								$str .= <<<EOT
									<tr  class="childrenTr $closeClass">
										<td width="16%"   name="tdth12">0</td>
										<td width="18%"   name="tdth13">--</td>
										<td width="18%"   name="tdth14">--</td>
										<td width="16%"   name="tdth15">0</td>
										<td width="16%"   name="tdth16">0</td>
										<td width="16%"   name="tdth17">--</td>
									</tr>
EOT;

						}
				$str .= <<<EOT
        				</table>
					</td>
					</tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
		}
		return $str;


	 }

/*****************************************��ʾ�ָ���********************************************/

	/**
	 * ͨ��value����״̬
	 * $stateVal Keyֵ
	 * return ��������
	 */
	function statusToVal($stateVal){
		$returnVal = false;
		foreach( $this->status as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/**
	 * ͨ��״̬����value
	 * $stateSta Ӣ����
	 * return keyֵ
	 */
	function statusToSta($stateSta){
		$returnVal = false;
		foreach( $this->status as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

/*****************************************��ʾ�ָ���********************************************/

/*****************************************ҵ����********************************************/
    /**
     *��ʱδ����÷�������;
	 * @param $planId	�ɹ�����ID
     */
	function canChange($planId){
		$searchArr = array (
			"planId" => $planId,
			"deviceIsUse" => "1"
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		if($rows){
			$applyEquDao = new model_purchase_apply_applyequipment();
			$returnVal = false;
			foreach( $rows as $key => $val ){
				$returnVal = $applyEquDao->canChange($val['deviceNumb']);
			}
			return false;
		}else{
			return true;
		}
	}

	/**
	 * ͨ���ɹ��ƻ���ż���
	 * @param $planNumb	�ɹ�������
	 *
	 */
	function lockingByPlan_d($planNumb){
		try {
			$this->start_d ();
			$updateArr = array(
				"status" => $this->statusToSta('locking')
			);
			$whereArr = array(
				"plantNumb" => $planNumb,
				"deviceIsUse" => "1",
				"status" => $this->statusToSta('execution')
			);
			$this->update($whereArr, $updateArr);
			$this->commit_d ();
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception($e);
		}
	}


	/**
	 * @exclude �ر����вɹ�����
	 * @author ouyang
	 * @param $planNumb	�ɹ�������
	 * @return
	 * @version 2010-8-11 ����10:04:08
	 */
	function closeAllTask_d ($planNumb) {
		$searchArr = array (
					"plantNumb" => $planNumb,
					"deviceIsUse" => "1",
					//"statusArr" => $this->statusToSta('execution')
					"status" => $this->statusToSta('execution')
		);
		$this->__SET('searchArr', $searchArr);
		$arr = $this->listBySqlId("equipment_list");
		if($arr){
			$basicDao = new model_purchase_task_basic();
			foreach ( $arr as $key => $val ){
				$basicDao->close_d($val['basicNumb'],false);
			}
		}
	}

	/**
	 * @exclude ͨ���ƻ�id�ж��Ƿ�ȫ���ɹ�������ɻ�ر�
	 * @author ouyang
	 * @param $planId	�ɹ�����ID
	 * @return
	 * @version 2010-8-10 ����11:27:17
	 */
	function findEndObj_d ( $planId ) {
		$searchArr = array (
			"planId" => $planId
		);
		$rows = $this->listBySqlId("equipment_list");
		$returnVal = true;
		$basicDao = new model_purchase_task_basic();
		foreach($rows as $key => $val){
			$state = $basicDao->getStateByNumb($val['basicNumb']);
			if( $state != $basicDao->stateToSta('close') && $state != $basicDao->stateToSta('end') ){
				$returnVal = false;
			}
		}
		return $returnVal;
	}

	/**
	 * @desription ����豸
	 * @param $arr
	 * @param $basicId	�ɹ�����ID
	 * @param $basicNumb	�ɹ�������
	 * @date 2010-12-22 ����05:25:13
	 */
	function pteAdd_d ( $arr , $basicId , $basicNumb ) {
		foreach( $arr as $key => $val ){
			$val['objCode'] = $this->objass->codeC( 'purch_task_equ' );
			$val['basicNumb'] = $basicNumb;
			$val['basicId'] = $basicId;
			$val['status'] = $this->statusDao->statusEtoK('execution');
			$id = parent::add_d( $val );
		}
	}

	/*****************************************�������ݷ���********************************************/

	/**
	 *
	 */
	function pageEqu_d(){
		$searchArr = $this->__GET("searchArr");
		$this->__SET('searchArr', $searchArr);
		$this->groupBy = 'p.productName';
		$rows = $this->pageBySqlId("equpment_basic");
		$i = 0;
		if( is_array( $rows ) ){
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
//				$this->resetParam();
				$searchArr = $this->__GET("searchArr");
				$searchArr['sendUserId'] = $_SESSION['USER_ID'];
				$searchArr['productNumb'] = $val['productNumb'];
//				$searchArr['productName'] =$val['productName'];
				$this->__SET('groupBy', "p.id");
				$this->__SET('sort', "p.id");
				$this->__SET('searchArr', $searchArr );
				$chiRows = $this->listBySqlId("equpment_basic");
				$rows[$key]['purchAll']=0;//δ�´����������ܺ�
				foreach ( $chiRows as $chiKey => $chiVal ){
					$chiRows[$chiKey]['purchTypeCName'] = $interfObj->typeKToC( $chiVal['purchType'] );		//��������
					$rows[$key]['purchAll']=$rows[$key]['purchAll']+$chiVal['amountAll']-$chiVal['contractAmount'];
					$rows[$key]['notInquiryAll']=$rows[$key]['notInquiryAll']+$chiVal['amountAll']-$chiVal['amountIssued'];
					$rows[$key]['notContractAll']=$rows[$key]['notContractAll']+$chiVal['amountAll']-$chiVal['contractAmount'];

					if($chiVal['amountAll']-$chiVal['contractAmount']>0){
						$unPurchSum[$chiKey]=1;
					}else{
						$unPurchSum[$chiKey]=0;
					}
					if( isset( $chiVal['contractAmount']) && $chiVal['contractAmount']!='' ){
						$chiRows[$chiKey]['amountNotIssued'] = $chiVal['amountAll'] - $chiVal['contractAmount'];
					}else{
						$chiRows[$chiKey]['amountNotIssued'] = $chiVal['amountAll'];
					}
					$equIds[$chiKey]=$chiVal['id'];
					$equPurchType[$chiKey]=$chiVal['purchType'];
				}
				array_multisort($unPurchSum,SORT_DESC,$equPurchType,SORT_DESC,$equIds,SORT_DESC,$chiRows);// �����Ͻ�������
				$rows[$i]['childArr']=$chiRows;
				++$i;
				$purchAll[$key]=$rows[$key]['notInquiryAll'];
				$purchContractAll[$key]=$rows[$key]['notContractAll'];
				$ids[$key]=$val['id'];
			}
			//�������ϵ�δ�´�����֮����id��������
			array_multisort($purchContractAll,SORT_DESC,$ids,SORT_DESC,$rows);
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 *��ȡ��������
	 *
	 */
	 function getAllEqus_d(){
		$searchArr = $this->__GET("searchArr");
		$this->__SET('searchArr', $searchArr);
		$this->groupBy = 'p.id';
		$rows = $this->pageBySqlId("equpment_basic_progress");
		return $rows;
	 }

	/**
	 *��ȡ��������
	 *
	 */
	 function getAllEqusByList_d(){
		$searchArr = $this->__GET("searchArr");
		$this->__SET('searchArr', $searchArr);
		$this->groupBy = 'p.id';
		$rows = $this->listBySqlId("equpment_basic_progress");
		return $rows;
	 }

	/**
	 * @desription ͨ�������������ȡ���ݺϲ�����
	 * @param $rows �ɹ���������
	 * @date 2010-12-23 ����02:47:36
	 */
	function getTaskAsEqu_d ( $rows ) {
		//һ���Ի�ȡ���и���Id�ļ���
		$ids = '';
		foreach($rows as $key => $val){
			$ids .= $rows[$key]['id'].',';
		}
		$ids = ($ids=='') ? null : substr($ids,0,-1);
		if($ids){
			$this->resetParam();
			$searchArr = array (
				'basicIds' => $ids
			);
			$this->__SET('groupBy', 'p.id');
			$this->__SET('sort', 'p.id');
			$this->__SET('searchArr', $searchArr);
			$chiRows = $this->listBySqlId('equipment_list_plan');

			$interfObj = new model_common_interface_obj();
//			echo "<pre>";
//			print_r($chiRows);

			//ѭ�����飬������Ӧ��ҵ�����������������ڸ�������
			foreach( $chiRows as $keyC => $valC ){
				//$chiRows[$keyC]['objNameC'] = $interfObj->typeArrToC( $valC['objAss'] );  //��������

				if( !isset( $valC['amountAll'] )||$valC['amountAll']==0 ||$valC['amountAll']=='' ){
					$chiRows[$keyC]['amountAll'] = 0;
					continue;
				}

				if( isset( $valC['contractAmount']) && $valC['contractAmount']!='' ){
					$chiRows[$keyC]['amountNotIssued'] = $valC['amountAll'] - $valC['contractAmount'];
				}else{
					$chiRows[$keyC]['amountNotIssued'] = $valC['amountAll'];
				}

				//$chiRows[$keyC]['purchTypeCName'] = '���۲ɹ�';
				$chiRows[$keyC]['purchTypeCName'] = $interfObj->typeKToC( $valC['purchType'] );		//��������

				//ƥ�丸�࣬�������Id��Id��ͬ�򽫱������ݹ��ڴ˸������棬�γ��µ����ӹ�ϵ����
				foreach( $rows as $key => $val ){
					if( $valC['basicId'] == $val['id'] ){
						$rows[$key]['childArr'][] = $chiRows[$keyC];
					}
				}
			}
		}
		return $rows;
	}

	/**
	 * @desription �ɹ������豸-�豸��ϸ����
	 * @param $str	�ɹ�����ID����
	 * @date 2010-12-24 ����10:35:19
	 */
	function getTaskEquAsEqu_d($str){
		$searchArr = array (
			"arrayIds" => $str
		);
		$this->__SET('sort', "p.id");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		if($rows){
			$retRows = array();
			foreach( $rows as $key => $val ){
				$i = true;
				foreach( $retRows as $retKey => $retVal ){
					if( $val['productId'] == $retVal['productId'] ){

						$retRows[$retKey]['childArr'][] = $val;
						$i = false;
					}
				}
				if( $i ){
					$val['childArr'][] = $rows[$key];
					$retRows[] = $val;
				}
			}

			foreach( $retRows as $retKey => $retVal ){
				$retRows[$retKey]['allAmount'] = 0;
				foreach( $retVal['childArr'] as $cKey => $cVal ){
					$retRows[$retKey]['childArr'][$cKey]['amountIssued'] = $cVal['amountIssued']
						= isset( $cVal['amountIssued'] )?$cVal['amountIssued']:0;
					$retRows[$retKey]['childArr'][$cKey]['amount'] = $cVal["amount"] =
						$cVal["amountAll"] - $cVal["amountIssued"];
					$retRows[$retKey]['allAmount'] += $cVal["amount"];
				}
			}
			return $retRows;
		}
		else {
			return false;
		}
	}

	/**��ȡ�ɹ������豸�嵥
	*author can
	*2011-1-8
	 * @param $str	�ɹ�����ID����
	*/
    function getTaskEqu_d($str){
		$searchArr = array (
			"arrayIds" => $str
		);
		$this->__SET('sort', "p.id");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
        foreach ($rows as $k => $v){
            $mainId = (isset($v['applyId']) && $v['applyId'] > 0)? $v['applyId'] : $v['basicId'];
            $sql = "select * from purchase_asset_union where id = {$mainId} and purchType = '{$v['purchType']}';";
            $applyData = $this->_db->getArray($sql);
            $applyData = ($applyData && !empty($applyData))? $applyData[0] : array();

            $rows[$k]['formBelong'] = isset($applyData['formBelong'])? $applyData['formBelong'] : '';
            $rows[$k]['formBelongName'] = isset($applyData['formBelongName'])? $applyData['formBelongName'] : '';
            $rows[$k]['businessBelong'] = isset($applyData['businessBelong'])? $applyData['businessBelong'] : '';
            $rows[$k]['businessBelongName'] = isset($applyData['businessBelongName'])? $applyData['businessBelongName'] : '';
        }
		return $rows;

	}

	/**��ȡ�ɹ������豸,ȥ���ظ���¼
	*author can
	*2011-1-8
	 * @param $str	�ɹ�����ID����
	*/
		function getUniqueTaskEqu_d($str){
		$searchArr = array (
			"arrayIds" => $str
		);
		$this->__SET('sort', "p.id");
		$this->__SET('groupBy',"p.productNumb");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		return $rows;

	}

	/*****************************************�ر����********************************************/

	/**
	 * @exclude �رղɹ�������Ӧ�豸
	 * @author ouyang
	 * @param	$basicNumb �ɹ�������
	 * @version 2010-8-10 ����07:52:10
	 */
	function closeTaskEqu_d ($basicNumb) {
		$searchArr = array (
					"basicNumb" => $basicNumb
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		if($rows){
			$planEquDao = new model_purchase_plan_equipment();
			foreach($rows as $key =>$val){
				$val['amountAll'] = isset( $val['amountAll'] )? $val['amountAll'] : 0 ;
				$val['amountIssued'] = isset( $val['amountIssued'] )? $val['amountIssued'] : 0 ;
				//�����ϼ��´�����
				if( $val['amountAll']>$val['amountIssued'] ){
					$planEquDao->updateAmountIssued ( $val ['planEquId'], 0,$val['amountAll']-$val['amountIssued'] );
				}
			}
		}
	}

	/**
	 * @exclude �ж��豸�Ƿ����ɴ�����
	 * @author ouyang
	 * @param	$basicNumb �ɹ�������
	 * @version 2010-8-10 ����07:52:24
	 */
	function endTaskByBasicId_d ($basicNumb) {
		$searchArr = array (
					"basicNumb" => $basicNumb
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equipment_list");
		$returnVal = false;
		if( is_array( $rows ) ){
			$planEquDao = new model_purchase_plan_equipment();
			foreach($rows as $key =>$val){
				$val['amountAll'] = isset( $val['amountAll'] )? $val['amountAll'] : 0 ;
				$val['contractAmount'] = isset( $val['contractAmount'] )? $val['contractAmount'] : 0 ;
				//�ж��Ƿ�������
				if( $val['amountAll']==$val['contractAmount'] ){
					$returnVal=true;
				}else{//������δ��ɵ�����ʱ������ѭ��
					$returnVal=false;
					break;
				}
			}
		}
		return $returnVal;
	}


	/**���²ɹ���ͬ���豸����
	*author can
	*2011-1-13
	 * @param $equId �ɹ������豸id
	 * @param $issuedNum �ɹ������豸�´�����
	 * @param $lastIssueNum �ɹ������豸��ԭ����
	*/
	function updateContractAmount($equId,$contractNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$contractNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set contractAmount=(ifnull(contractAmount,0) + $contractNum - $lastIssueNum) where id=$equId  ";
			}else{
				$sql = " update ".$this->tbl_name." set contractAmount=(ifnull(contractAmount,0) + $contractNum) where id=$equId ";
			}
			return $this->query($sql);
		}
	}


	/*****************************************�ⲿ�ӿ�********************************************/

	/**
	 * ͨ���ɹ��ƻ����豸ID����ѯ��ID��Ӧ�µ������豸�Ƿ������
	 *
	 * @param $equsId -- �ƻ����豸ID
	 * @return true/false
	 */
	function checkTaskEqus_d ($equsId) {
		//�����豸ID�����ɹ������豸�������
		$this->searchArr = array('planEquId' => $equsId);
		$rows = $this->listBySqlId("equs_planTotask");

		$returnVal = false;
		if( is_array( $rows ) ){
			$taskDao = new model_purchase_task_basic();
			foreach($rows as $key => $val){
				//�жϺ�ͬ��״̬�Ƿ�Ϊ����ɡ����ߡ��رա�
				//�ж���������ȥ���´�������ֵ�Ƿ�Ϊ0
				$val['amountAll'] = isset( $val['amountAll'] )? $val['amountAll'] : 0 ;
				$val['amountIssued'] = isset( $val['amountIssued'] )? $val['amountIssued'] : 0 ;

				if( $val['bState']==$taskDao->statusDao->statusEtoK ( "close" ) ||
					$val['bState'] == $taskDao->statusDao->statusEtoK ( "end" ) ||
					$val['amountAll'] == $val['amountIssued']  ){
					$returnVal = true;
				}
			}
		}
		return $returnVal;
	}

	/**
	 * @exclude ���²ɹ������豸����������
	 * @author ouyang
	 * @param $equId �ɹ������豸id
	 * @param $issuedNum �ɹ������豸�´�����
	 * @param $lastIssueNum �ɹ������豸��ԭ����
	 * @return
	 * @version 2010-8-10 ����06:27:28
	 */
	function updateAmountIssued($equId,$issuedNum,$lastIssueNum=false){
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql = " update ".$this->tbl_name." set amountIssued=(ifnull(amountIssued,0) + $issuedNum - $lastIssueNum) where id=$equId  ";
			}else{
				$sql = " update ".$this->tbl_name." set amountIssued=(ifnull(amountIssued,0) + $issuedNum) where id=$equId ";
			}
			return $this->query($sql);
		}
	}

	/**���ݲɹ��ƻ�Id���Ҳɹ������豸
	*author can
	*2011-2-14
	 * @param	$planId �ɹ�����ID
	*/
	function findByPlanId($planId){
		return $this->findAll(array('planId'=>$planId));
	}

	/**���ݲɹ���������Id���Ҳɹ������豸
	*author can
	*2011-2-14
	 * @param	$planEquId �ɹ���������ID
	*/
	function findByPlanEquId($planEquId){
		$this->searchArr=array("searchPlanEquId"=>$planEquId);
		$this->groupBy="p.id";
		$rows = $this->listBySqlId("equpment_execute");
		return $rows;
	}
	/**
	 * �´�ɹ�ѯ�۵����߲ɹ�����ʱ���ж����ϵĲɹ������Ƿ���ͬ
	 *@param	$ids �ɹ���������ID�ַ���
	 */
	 function isSameType_d($ids){
		$idsArr=explode(',',$ids);
		$purcType=array();
		foreach($idsArr as $key=>$val){
			if($val!=""){
				$row=$this->get_d($val);
				//Ŀǰ�ͻ���Ҫ�����Ͳɹ�������òɹ���Ϊ����ɹ�
				if($row['purchType']=="oa_borrow_borrow"||$row['purchType']=="oa_present_present"){
					$row['purchType']="stock";
				}
				array_push($purcType,$row['purchType']);
			}
		}
		$num=count(array_unique($purcType));
		if($num==1){
			return 1;
		}else{
			return 0;
		}

	 }
}
?>