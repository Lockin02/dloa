<?php

/**
 * @description:采购合同变更，版本控制
 * @author qian
 * 2011-2-13 pm 13:47
 */

class model_purchase_change_contractchange extends model_base {
	/*
	 * @description 构造函数
	 */
	function __construct() {
		$this->tbl_name = "oa_purch_apply_basic";
		$this->sql_map = "purchase/change/contractchangeSql.php";
		//注册数据字典字段
		$this->datadictFieldArr = array ("billingType", "paymentType", "paymentCondition" );
		parent :: __construct();

	}

	/**
	 * @description 对合同的签约合同的值进行转换
	 * @author qian
	 * @date 2011-03-09 16:23
	 */
	function signStatus_d($signStatus){
		if($signStatus){
			switch($signStatus){
				case "0" : return "未签约";
				case "1" : return "已签约";
				case "2" : return "已拿到纸质合同";
				case "3" : return "已提交纸质合同";
			}
		}
		return null;
	}

	/**
	 * @description 编辑修改签约合同的状态位
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
			<input type="radio" name="sales[signStatus]" value="0" $val1>未签约
	 		<input type="radio" name="sales[signStatus]" value="1" $val2>已签约
	 		<input type="radio" name="sales[signStatus]" value="2" $val3>已拿到纸质合同
	 		<input type="radio" name="sales[signStatus]" value="3" $val4>已提交纸质合同
EOT;
		return $str;


	}

	/**
	 * @description 将变更的数据保存到版本表里去
	 * @author qian
	 * @date 2011-2-14 11:34
	 */
	function change_d($rows) {
		$contract = $_POST['contract'];

		//更新设备的版本表里的数据
		$id = $this->addVersion_d($contract);

		return $id;
	}

	/**
	 * @description 合同变更时，传入合同的数据，并将合同及设备的数据复制到对应的版本表中，
	 * 				版本表会先判断是否是第一次变更。
	 * 				是，则存储合同的原始数据，并自动生成一条新的有版本号的合同记录；
	 * 				否，则生成版本记录的数据。
	 * @param $applyNumb 合同编号
	 * @author qian
	 * 2011-2-13 pm 14:05
	 */
	function addVersion_d($rows) {
		if (is_array($rows)) {
			//使用PHP队列，弹出第一个数值ID，即缩小数组
			$rows2 = array_shift($rows);

			//根据合同编号判断访合同是否存在版本表里
			$condiction = array (
				"applyNumb" => $rows['applyNumb']
			);
			$count = $this->findCount($condiction);
			$equipmentDao = new model_purchase_change_equipmentchange();


			//根据传入的合同编号判断版本表里是否存在此合同数据
			//无，则是第一次变更
			//执行的操作是将合同的数据复制到版本表里，并再生成一条新的版本数据
			if (!$count) {
				try {
					$this->start_d();
					//合同
					//采购合同原始数据的默认版本号是0
					$rows['version'] = 0;
					$id = $this->add_d($rows,true);
					//设备
					//设备清单的数据也会默认保存进设备的版本表中，默认的版本号为0
					$rows['equs']['isChanged'] = 0;
					foreach($rows['equs'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['basicId'] = $id;
						$equipmentDao-> addEquVersion_d($val);
					}

					//除了原始版本，还要再新增一条版本记录,用于合同变更
					$rows['version'] = 1;
					$id = $this->add_d($rows,true);
					//然后再保存一份数据，用于变更审批
					foreach($rows['equs'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['isChanged'] = 0;
						//如果采购数量为0，则将采购类型设置为1
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
					//如果合同不是第一次变更，则将合同现在的数据复制到版本到里，找到版本号最大的数据，并将版本号+1
					$this->searchArr = array (
						"applyNumb" => $rows['applyNumb']
					);
					//根据版本号，拿到MAX值，即版本的最大值。此值代表最新的合同版本数据
					$version = $this->listBySqlId("select_max1");
					$rows['version'] = (int)++$version[0]['version'];

					$id = $this->add_d($rows,true);

					$equipmentRows = $equipmentDao->listBySqlId("select_max");

					//获取设备的最高版本号，并自增1，生成新的版本号，赋予新变更的设备
					$equCondiction = array(
						"version"=>"select max(version) from oa_purch_apply_equ_version",
						"applyNumb"=>$rows['applyNumb']
					);
//					$equVersion = $equipmentDao->listBySqlId("version_max");

					//将设备的信息保存
					foreach($rows['equs'] as $key => $val){
						$val['version'] = $rows['version'];
						$val['basicId'] = $id;
						$equipmentDao->addEquVersion_d($val);
					}

					//将临时设备的信息保存
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
	 * @description 查看历史版本的文本替换
	 * @author qian
	 * @date 2011-2-17 17:08
	 */
	function toViewHistory_d($applyNumb){
		$equipmentDao = new model_purchase_change_equipmentchange();
		//根据编码获取合同的数据
		$condiction = array('applyNumb' => $applyNumb);
		$versionRows = $this->findAll($condiction,null,'version,applyNumb,id');
		$str = "";
		//分别替换版本的链接
		foreach($versionRows as $key => $val){
			$url = "?model=purchase_change_contractchange&action=toViewVersion&id=".$val['id'];
			$str .= "<a href='$url'>"."版本".$val['version']."</a><br>";
		}
		return $str;
	}


	/**
	 * @description 通过版本表里的ID来获取版本合同及设备的数据
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
	 		//按字段查找一条记录
	 		$equCondiction = array("basicId" => $id);
	 		$rows['equs'] = $equDao->findAll($equCondiction,null);

	 		return $rows;
	 	}
	 }


	/**
	 * @description 审批通过之后，将版本表里的数据覆盖到采购合同表里。
	 * @author qian
	 * @date 2011-2-15 14:36
	 */
	function coverChange_d($rows){
		//根据合同ID及编码来确定需要覆盖的合同、设备
		$condiction1 = array("applyNumb"=>$rows['applyNumb']);

		try{
			$this->start_d();

			$equipmentDao = new model_purchase_contract_equipment();
			$inquiryEquDao=new model_purchase_inquiry_equmentInquiry();
			$taskEquDao=new model_purchase_task_equipment();

			//覆盖设备
			foreach($rows['equs'] as $key => $val){
				unset($val['id']);

				//更新采购任务的合同数量
				$inquiryEquRows=$inquiryEquDao->get_d($val['inquiryEquId']);
				$equipmentRows=$equipmentDao->get_d($val['oldId']);
				$taskEquDao->updateContractAmount($inquiryEquRows['taskEquId'],0,$equipmentRows['amountAll']-$val['amountAll']);


				//如果字段‘oldId’不为空，则表明此设备是原来的设备，而非临时采购设备
				if($val['oldId']){
					$condiction = array("id"=>$val['oldId']);
					//先删除设备表里面的设备信息

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

			//先摧毁设备的数组，再覆盖合同
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
	 * @desription 获取合同设备信息
	 * @param tags
	 * @author qian
	 * @date 2011-1-8 下午05:32:30
	 */
	function getEquipments_d ($rows) {
		if(is_array($rows)){
			//通过合同的ID及版本号来获取数据
			$equDao = new model_purchase_change_equipmentchange();
			$equDao->searchArr = array(
				'basicId'=>$rows['id'],
				'version'=>$rows['version']
			);
			$rows = $equDao->listBySqlId("select_default");
			$interfObj = new model_common_interface_obj();
			foreach( $rows as $key => $val ){
				$rows[$key]['purchTypeC'] = $interfObj->typeKToC( $val['planEquType'] );		//类型名称
			}
		}
		return $rows;
	}


	/*
	 * @desription 合同设备--添加页
	 * @param tags
	 * @author qian
	 * @date 2011-1-5 下午07:45:52
	 */
	function addContractEquList_s ($listEqu) {
		$str="";
		$i=0;
		//采购类型
	    $interfObj = new model_common_interface_obj();

		if($listEqu){
			foreach($listEqu as $key=>$val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$listEqu[$key]['purchTypeC'] = $interfObj->typeKToC( $val['planEquType'] );		//类型名称

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
	 * @desription 产品清单--编辑页
	 * @param tags
	 * @author qian
	 * @date 2011-1-12 下午03:18:01
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
				$listEqu[$key]['purchTypeC'] = $interfObj->typeKToC( $val['oPurchType'] );		//类型名称
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
	 * @description 通过签约状态的值来获取其对应的值
	 * @author qian
	 * @date 2011-2-28
	 */
	function getSignStatus_d($value){
		if ($value == 0) {
			$value = "未签约";
		}else if ($value == 1) {
			$value = "已签约";
		} else if($value== 2){
			$value = "已拿到纸质合同";
		} else if($value== 3){
			$value = "已提交纸质合同";
		}else{
			$value = "财务已签收";
		}
		return $value;
	}


}
?>