<?php
/*ѯ�۵�_��Ʒ�嵥
 * Created on 2010-12-29
 * can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_purchase_inquiry_equmentInquiry extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purch_inquiry_equ";
		$this->sql_map = "purchase/inquiry/inquiryproSql.php";
		parent::__construct ();
	}

/*****************************************��ʾ�ָ���********************************************/
	/**�����б�
	*author can
	*2010-12-27
	*/
	function equList ($listEqu) {
		$productInfoDao=new model_stock_productinfo_productinfo();
		$str="";
		$i=0;
		if($listEqu){
			foreach($listEqu as $key=>$val){
//				$productInfoRows=$productInfoDao->get_d($val[productId]);
				$i++;
				$amount=$val['amountAll']-$val['amountIssued'];
				if($amount>1){
					$amount=$amount;
				}else{
					$amount=$val['amountAll'];
				}
				$str.=<<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[productNumb]
							<input type="hidden" class="txt" id="" name="inquirysheet[equmentInquiry][$i][productNumb]" value="$val[productNumb]" readonly>
						</td>
						<td>
							$val[productName]
							<input type="hidden" class="txt" id="productName" name="inquirysheet[equmentInquiry][$i][productName]" value="$val[productName]" readonly>
							<input type="hidden" id="productId" name="inquirysheet[equmentInquiry][$i][productId]" value="$val[productId]">
							<input type="hidden" id="taskEquId" name="inquirysheet[equmentInquiry][$i][taskEquId]" value="$val[id]">
							<input type="hidden" id="purchType" name="inquirysheet[equmentInquiry][$i][purchType]" value="$val[purchType]">
						</td>
						<td>
							$val[pattem]
							<input type="hidden" class="txtshort" id="pattem" name="inquirysheet[equmentInquiry][$i][pattem]" value="$val[pattem]" >
						</td>
						<td>
							$val[unitName]
							<input type="hidden"  id="units" name="inquirysheet[equmentInquiry][$i][units]" value="$val[unitName]">
						</td>
						<!--
						<td><input type="text" class="txtshort" id="auxiliary" name="inquirysheet[equmentInquiry][$i][auxiliary]"></td> -->
						<td><input type="text" class="txtshort amountAll" id="amountAll" name="inquirysheet[equmentInquiry][$i][amount]" value="$amount" onblur="countDetail(this);myload();">
						    <input type="hidden" value="$amount">
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * ����ѯ�۵������嵥��ģ��
	 *
	 */
	function addEquList($listEqu,$uniqueEquList){
		$interfObj = new model_common_interface_obj();
		$i=0;
		$j=0;
		foreach($uniqueEquList as $key=> $val){
				$i++;
				$strTab="";
				$inquiryTotalNum=0;
				foreach($listEqu as $chdKey=>$chdVal){
					if($val['productNumb']==$chdVal['productNumb']){
							$amount=$chdVal['amountAll']-$chdVal['amountIssued'];
						if($amount>0){
							$amount=$amount;
						}else{
							$amount=0;
						}
						$purchTypeCName = $interfObj->typeKToC( $chdVal['purchType'] );		//��������
						$inquiryTotalNum=$inquiryTotalNum+$amount;
						$strTab.=<<<EOT
							<tr>
								<td  width="30%">
									$chdVal[basicNumb]
									<input type="hidden" class="txt" id="" name="inquirysheet[equmentInquiry][$j][productNumb]" value="$chdVal[productNumb]" readonly>
								</td>
								<td  width="30%">
									$purchTypeCName
									<input type="hidden" class="txt" id="productName" name="inquirysheet[equmentInquiry][$j][productName]" value="$chdVal[productName]" readonly>
									<input type="hidden" id="productId" name="inquirysheet[equmentInquiry][$j][productId]" value="$chdVal[productId]">
									<input type="hidden" id="taskEquId" name="inquirysheet[equmentInquiry][$j][taskEquId]" value="$chdVal[id]">
									<input type="hidden" id="purchType" name="inquirysheet[equmentInquiry][$j][purchType]" value="$chdVal[purchType]">
									<input type="hidden" class="txtshort" id="pattem" name="inquirysheet[equmentInquiry][$j][pattem]" value="$chdVal[pattem]" >
									<input type="hidden"  id="units" name="inquirysheet[equmentInquiry][$j][units]" value="$chdVal[unitName]">
									<input type="hidden"  id="" name="inquirysheet[equmentInquiry][$j][batchNumb]" value="$chdVal[batchNumb]">
								</td>
								<td  width="20%"><input type="text" class="txtshort amountAll" id="amountAll" name="inquirysheet[equmentInquiry][$j][amount]" value="$amount" onblur="countDetail(this);myload();">
								    <input type="hidden" value="$amount">
								</td>
								<td  width="20%">
									<input type="text" class="txtshort" name="inquirysheet[equmentInquiry][$j][remark]">
								</td>
							</tr>
EOT;

					}
					$j++;
				}
				$str.=<<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[productNumb]<br>
							$val[productName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$inquiryTotalNum
						</td>
				        <td width="50%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
						</td>
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
						</td>
					</tr>
EOT;

		}
		return $str;

	}

	/**ѯ�۵���Ʒ�嵥�༭ҳ��*/
	function rowsEdit($rows){
		$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$i++;
				$str.=<<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[productNumb]
							<input type="hidden" class="txt" id="" name="inquirysheet[equmentInquiry][$i][productNumb]" value="$val[productNumb]" readonly>
							<input type="hidden"  name="inquirysheet[equmentInquiry][$i][id]" value="$val[id]" readonly>
						</td>
						<td>
							$val[productName]
							<input type="hidden" class="txt" id="productName" name="inquirysheet[equmentInquiry][$i][productName]" value="$val[productName]" readonly/>
							<input type="hidden" id="productId" name="inquirysheet[equmentInquiry][$i][productId]" value="$val[productId]">
							<input type="hidden" id="taskEquId" name="inquirysheet[equmentInquiry][$i][taskEquId]" value="$val[taskEquId]">
						</td>
						<td>
							$val[pattem]
							<input type="hidden" class="txtshort" id="pattem" name="inquirysheet[equmentInquiry][$i][pattem]" value="$val[pattem]">
						</td>
						<!--
						<td>
							$val[auxiliary]
							<input type="hidden" class="txtshort" id="auxiliary" name="inquirysheet[equmentInquiry][$i][auxiliary]"  value="$val[auxiliary]">
						</td>-->
						<td>
							$val[units]
							<input type="hidden" class="txtshort" id="units" name="inquirysheet[equmentInquiry][$i][units]"  value="$val[units]">
						</td>
						<td>
							$val[amount]
							<input type="hidden" class="txtshort" id="amountAll" name="inquirysheet[equmentInquiry][$i][amount]" value="$val[amount]" onblur="countDetail(this)">
							<input type="hidden" name="" value="$val[amount]">
							<input type="hidden" name="inquirysheet[equmentInquiry][$i][amountOld]" value="$val[amount]">
						</td>
						<td>$val[purchTypeCn]</td>
						<!--
						<td>
							<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="ɾ����" />
						</td>
						 -->
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**ѯ�۵���Ʒ�嵥�鿴ҳ��*/
//	function rowsShow($rows){
//		$str="";
//		$i=0;
//		if($rows){
//			foreach($rows as $key=>$val){
//				$i++;
//				$str.=<<<EOT
//					<tr>
//					    <td>$i</td>
//						<td>$val[productNumb]</td>
//						<td>$val[productName]</td>
//						<td>$val[pattem]</td>
//						<!--
//						<td>$val[auxiliary]</td> -->
//						<td>$val[units]</td>
//						<td>$val[amount]</td>
//						<td>$val[purchTypeCn]</td>
//					</tr>
//EOT;
//			}
//		}
//		return $str;
//	}
	function rowsShow($listEqu,$uniqueEquList){
		$interfObj = new model_common_interface_obj();
		$taskEquDao=new model_purchase_task_equipment();
		$i=0;
		$j=0;
		foreach($uniqueEquList as $key=> $val){
				$i++;
				$strTab="";
				$inquiryTotalNum=0;
				foreach($listEqu as $chdKey=>$chdVal){
					if($val['productNumb']==$chdVal['productNumb']){
						$purchTypeCName = $interfObj->typeKToC( $chdVal['purchType'] );		//��������
						$taskEquRow=$taskEquDao->get_d($chdVal['taskEquId']);
						$inquiryTotalNum=$inquiryTotalNum+$chdVal['amount'];
						$strTab.=<<<EOT
							<tr>
								<td  width="30%">
									$taskEquRow[basicNumb]
								</td>
								<td  width="30%">
									$purchTypeCName
								</td>
								<td  width="10%"> $chdVal[amount]
								</td>
								<td  width="20%">
									$chdVal[remark]
								</td>
							</tr>
EOT;

					}
					$j++;
				}
				$str.=<<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[productNumb]<br>
							$val[productName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[units]
						</td>
						<td  width="10%">
							$inquiryTotalNum
						</td>
				        <td width="50%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
						</td>
					</tr>
EOT;

		}
		return $str;

	}

	//�ҵ���ָ��ѯ��������Ϣ����
	function showEquList($list,$uniqueEquList){
		$interfObj = new model_common_interface_obj();
		$str = "";
		$i = 0;
		if (is_array($list)) {
			foreach ($uniqueEquList as $key => $val) {
				$purchTypeCName = $interfObj->typeKToC( $val['purchType'] );		//��������
				$i++;
				$addAllAmount = 0;
				$strTab="";
				foreach ($list as $chdKey => $chdVal){
					if($chdVal['suppId']==$val['suppId']&&$chdVal['purchType']==$val['purchType']){
					$iClass = (($i%2)==0)?"tr_even":"tr_odd";
					$addAllAmount=$addAllAmount+$chdVal['notOrderAmount'];
						if( $chdVal['notOrderAmount'] == null || $chdVal['notOrderAmount']==0 || $chdVal['notOrderAmount']==""|| $chdVal['notOrderAmount']<0 ){
							$checkBoxStr =<<<EOT
					    	<input type="hidden" class="hidden" value="$chdVal[seId]"/>
							 $chdVal[productNumb]<br>$chdVal[productName]
EOT;
						}else{
							$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild" >
					    	<input type="hidden" class="hidden" value="$chdVal[seId]"/>
					    	$chdVal[productNumb]<br>$chdVal[productName]
EOT;
						}

						$strTab.=<<<EOT
							<tr align="center" height="28" class="$iClass">
				        		<td  align="left"  name="tdth05">
							    	$checkBoxStr
						        </td>
						        <td   width="15%" name="tdth06">
						             $chdVal[inquiryCode]
						        </td>
						        <td   width="10%"  name="tdth07">
						            $chdVal[amount]
						        </td>
						        <td   width="13%"  name="tdth08">
						             $chdVal[notOrderAmount]
						        </td>
						        <td    width="16%"  name="tdth09">
						           $chdVal[price]
						        </td>
			            	</tr>
EOT;
					}
				}
				$str .=<<<EOT
					<tr class="$iClass">
				        <td   name="tdth01">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td  name="tdth02">
				            $val[suppName]
				        </td>
				        <td   width="11%"  name="tdth03">
							$purchTypeCName
				        </td>
				        <td  width="6%" name="tdth04">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><input id="allCheckbox$i" type="checkbox">$addAllAmount</p>
				            </p>
							<SCRIPT language=JavaScript>
								if($addAllAmount<1){
									jQuery("#allCheckbox$i").hide();
								}
							</SCRIPT>
				        </td>
				        <td width="60%" class="tdChange td_table" >
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


	/**�鿴������ϸ
	*author can
	*2011-1-17
	*/
	function quoteDetail($rows){
		$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$i++;
		$str.=<<<EOT
					<tr>
						<td>
							$val[productNumb]<br/>$val[productName]
							<input type="hidden" id="productId$i"  value="$val[productId]">
							<input type="hidden" id="taskEquId"  value="$val[taskEquId]">
						</td>
						<td><div id="$val[taskEquId]1">$i</div></td>
						<td><div id="$val[taskEquId]2">$i</div></td>
						<td><div id="$val[taskEquId]3">$i</div></td>
					</tr>
EOT;
			}
		}
		return $str;
	}
	/********************************************ҵ�����*********************************************/
	/**
	 * ����ѯ�۵�id��ȡѯ�۵��豸�嵥
	 **/
	 function getEqusByParentId($parentId){
	 	return $this->findAll(array('parentId'=>$parentId));
	 }
	/**
	 * ����ѯ�۵�id��ȡѯ�۵��豸�嵥,ȥ���ظ�
	 **/
	 function getUniqueByParentId($parentId){
		$searchArr = array (
			"parentId" => $parentId
		);
		$this->__SET('sort', "c.id");
		$this->__SET('groupBy',"c.productNumb");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list");
		return $rows;
	 }
	/**
	 * ������ָ����Ӧ�̸���ѯ�۵�������Ϣ
	 **/
	 function getEquList_d($parentId){
//		$searchArr = array (
//			"state" =>2,
//			"purcherId" => $_SESSION ['USER_ID']
//		);
//		$this->__SET('sort', "i.suppId");
//		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list");
		return $rows;
	 }
	/**
	 * ������ָ����Ӧ�̸���ѯ�۵�������Ϣ
	 **/
	 function getEquPageList_d($parentId){
//		$searchArr = array (
//			"state" =>2,
//			"purcherId" => $_SESSION ['USER_ID']
//		);
//		$this->__SET('sort', "i.suppId");
//		$this->__SET('searchArr', $searchArr);
		$rows = $this->pageBySqlId("equ_list");
		return $rows;
	 }
	/**
	 * ������ָ����Ӧ��ѯ�۵�������Ϣ
	 **/
	 function getEquInfoList_d($parentId){
		$searchArr = array (
			"idArr" =>$parentId
		);
		$this->__SET('sort', "c.id");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->pageBySqlId("equ_list");
		return $rows;
	 }	/**
	 * ������������ID��ȡѯ�۵�������Ϣ
	 **/
	 function getEqusByTaskEquId_d($taskEquId){
		$searchArr = array (
			"taskEquId" =>$taskEquId
		);
		$this->__SET('sort', "c.id");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("equ_list_progress");
		return $rows;
	 }

	 /**ɾ���ɹ�ѯ�۵������豸��ά���ɹ������豸�´�����
	*author can
	*2011-1-6
	*/
	function del_d($id){
		$rows=$this->getEqusByParentId($id);
		if($rows){
			$taskEquDao=new model_purchase_task_equipment();
			foreach($rows as $key=>$val){
				if(!isset($val['amount'])){
					$val['amount']=0;
				}
				$taskEquDao->updateAmountIssued($val['taskEquId'],0,$val['amount']);
			}
		}

	}

	/**
	 * ���²ɹ�ѯ�۵��豸��ѯ������,��ά����Ӧ�̲�Ʒ�嵥������
	 *
	 * @param $equId �ɹ�ѯ���豸id
	 * @param
	 * @return return_type
	 */
	function updateAmount ($equId,$issuedNum,$lastIssueNum=false) {
		if(isset($lastIssueNum)&&$issuedNum==$lastIssueNum){
			return true;
		}else{
			if($lastIssueNum){
				$sql=" update".$this->tbl_name." set amount=$issuedNum where id=$equId";
				$suppproDao=new model_purchase_inquiry_inquirysupppro();
				$suppproDao->updateAmount();
			}
		}
		return $this->query($sql);
	}

	/**
	 *
	 *��ȡ�ɹ�����������*/
	function getPurchName($rows){
		$interfObj = new model_common_interface_obj();
		$orderEquDao=new model_purchase_contract_equipment();
		$newRows=array();
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				if($val['purchType']!=""){
					$val['purchTypeCn']=$interfObj->typeKToC($val['purchType']);
				}else{
					//��ȡ�ɹ����뵥��Ϣ
					$applyRows=$orderEquDao->getApplyRows($val['taskEquId']);
					$val['purchTypeCn']=$interfObj->typeKToC($applyRows['purchType'] ); //��������
				}
			array_push($newRows,$val);
			}
		}
		return $newRows;
	}
	/**
	 *�ж����е����ϵĲɹ������Ƿ�Ϊͬһ�ɹ�����
	 *
	 */
	 function isSameType_d($ids){
		$this->searchArr['parentIdArr']=$ids;
		$equRows=$this->list_d();
		$purcType=array();
		if(is_array($equRows)){
			foreach($equRows as $key=>$val){
					//Ŀǰ�ͻ���Ҫ�����Ͳɹ�������òɹ���Ϊ����ɹ�
					if($val['purchType']=="oa_borrow_borrow"||$val['purchType']=="oa_present_present"){
						$val['purchType']="stock";
					}
					array_push($purcType,$val['purchType']);
			}
		}
		$num=count(array_unique($purcType));
		if($num==1){
			return 1;
		}else{
			return 0;
		}

	 }
	/**
	 *�ж����е����ϵĲɹ������Ƿ�Ϊͬһ�ɹ��������Ƿ�ͬһ��Ӧ��
	 *
	 */
	 function isSameTypeByOrder_d($ids){
		$searchArr = array (
			"idArr" =>$ids
		);
		$this->__SET('sort', "c.id");
		$this->__SET('searchArr', $searchArr);
		$equRows = $this->list_d("equ_list");
		$purcType=array();
		$supplier=array();
		if(is_array($equRows)){
			foreach($equRows as $key=>$val){
					//Ŀǰ�ͻ���Ҫ�����Ͳɹ�������òɹ���Ϊ����ɹ�
					if($val['purchType']=="oa_borrow_borrow"||$val['purchType']=="oa_present_present"){
						$val['purchType']="stock";
					}
					array_push($purcType,$val['purchType']);
					array_push($supplier,$val['suppId']);
			}
		}
		$num=count(array_unique($purcType));
		$suppIdnum=count(array_unique($supplier));
		if($num==1&&$suppIdnum==1){
			return 1;
		}else{
			return 0;
		}

	 }


}
?>