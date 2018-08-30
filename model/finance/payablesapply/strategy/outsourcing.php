<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once( WEB_TOR . 'model/finance/payablesapply/ipayablesapply.php');

class model_finance_payablesapply_strategy_outsourcing extends model_base implements ipayablesapply{

	//策略对应类
	private $thisClass = 'model_contract_outsourcing_outsourcing';

	//扩展字段说明
	//expand1 : 外包类型
	//expand2 : 项目编号
	//expand3 : 项目id

	//对应编码
	private $thisCode = 'YFRK-03';

	//源单信息获取
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->getList_d($obj['objId']);

		$rtObj = $innerObj[0];

		//设置单据源单类型
		$rtObj['sourceType'] = $this->thisCode;

		$datadictDao = new model_system_datadict_datadict();
		$rtObj['sourceTypeCN'] = $datadictDao->getDataNameByCode($this->thisCode);

		//设置付款详细
		$rtObj['detail'] = $innerObj;

		//获取当前登录人部门
		$otherDataDao = new model_common_otherdatas();
		$deptRows = $otherDataDao->getUserDatas($rtObj['createId'],array('DEPT_NAME','DEPT_ID'));
		$rtObj['deptName'] = $deptRows['DEPT_NAME'];
		$rtObj['deptId'] = $deptRows['DEPT_ID'];

		return $rtObj;
	}

	/**
	 * 渲染新增页面从表
	 */
	function initAdd_d($object,$mainObj){
		$str = null;
		$i = 0;
		if(is_array($object['detail'])){
//			print_r($object['detail']);
			$datadictDao = new model_system_datadict_datadict();
			foreach($object['detail'] as $key => $val){
				$outsourcingType = $datadictDao->getDataNameByCode($val['outsourceType']);
				//获取单据已申请金额
				$canApply = bcsub(bcsub($val['orderMoney'],$val['initPayMoney'],2),$mainObj->getApplyMoneyByPur_d($val['id'],$this->thisCode),2);

				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							<input type="text" id="contractCode$i" value="$object[sourceTypeCN]" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[orderCode]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[id]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$canApply" class="txtmiddle formatMoney"/>
							<input type="hidden" id="oldMoney$i" value="$canApply"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[orderMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][expand1]" id="expand1$i" value="$outsourcingType" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][expand2]" id="expand2$i" value="$val[projectCode]" class="readOnlyTxtMiddle" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][expand3]" value="$val[projectId]"/>
							<input type="hidden" name="payablesapply[detail][$i][orgFormType]" value="$val[projectName]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][payDesc]" id="payDesc$i" class="txtlong"/>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * 渲染查看页面从表
	 */
	function initView_d($object){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
			foreach($object as $key => $val){
				$i++;
				if(empty($val['expand3'])){
					$projectStr =<<<EOT
						$val[expand2]
EOT;
				}else{
					$projectStr =<<<EOT
						<a href="#" onclick="openObject($val[expand3],'$val[expand1]')">$val[expand2]</a>
EOT;
				}
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							$sourceTypeCN
						</td>
						<td>
							<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
						</td>
						<td class="formatMoney">$val[money]</td>
						<td class="formatMoney">$val[purchaseMoney]</td>
						<td>
							$val[expand1]
						</td>
						<td>
							$projectStr
						</td>
						<td>
							$val[payDesc]
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}



	/**
	 * 渲染查看页面从表
	 */
	function initAudit_d($object){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
			foreach($object as $key => $val){
				$i++;
				if(empty($val['expand3'])){
					$projectStr =<<<EOT
						$val[expand2]
EOT;
				}else{
					$projectStr =<<<EOT
						<a href="#" onclick="openObject($val[expand3],'$val[expand1]')">$val[expand2]</a>
EOT;
				}
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							$sourceTypeCN
						</td>
						<td>
							<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
						</td>
						<td class="formatMoney">$val[money]</td>
						<td class="formatMoney">$val[purchaseMoney]</td>
						<td>
							$val[expand1]
						</td>
						<td>
							$projectStr
						</td>
						<td>
							$val[payDesc]
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * 渲染编辑页面从表
	 */
	function initEdit_d($object,$mainObj){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
			//实例化业务
			$objDao = new $this->thisClass();
			foreach($object as $key => $val){
				//获取其他合同id
				$obj = $objDao->find(array('id' => $val['objId']),null,'orderMoney,initPayMoney');
				//获取单据已申请金额
				$canApply = bcsub(bcsub($obj['orderMoney'],$obj['initPayMoney'],2),$mainObj->getApplyMoneyByPur_d($val['objId'],$val['objType']),2);
				//本单据可申请金额 = 剩余可申请金额 + 本单据金额
				$canApply = bcadd($canApply,$val['money'],2);

				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							<input type="text" id="contractCode$i" value="$sourceTypeCN" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[objCode]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[objId]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$val[money]" class="txtmiddle formatMoney"/>
							<input type="hidden" id="oldMoney$i" value="$canApply"/>
							<input type="hidden" id="orgMoney$i" value="$val[money]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[purchaseMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][expand1]" id="expand1$i" value="$val[expand1]" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][expand2]" id="expand2$i" value="$val[expand2]" class="readOnlyTxtMiddle" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][payDesc]" id="payDesc$i" value="$val[payDesc]" class="txtlong"/>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * 渲染打印页面从表
	 */
	function initPrint_d($object){
		$str = null;
		$i = 0;
		if(is_array($object)){
			$datadictDao = new model_system_datadict_datadict();
			$sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
			//源单缓存数组
			$sourceCodeArr = array();

			foreach($object as $key => $val){

				//如果源单号不存在，则填充进源单缓存
				if(!in_array($val['objCode'],$sourceCodeArr)){
					array_push($sourceCodeArr,$val['objCode']);
				}

				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$sourceTypeCN
						</td>
						<td>
							$val[objCode]
						</td>
						<td class="formatMoney">$val[money]</td>
						<td class="formatMoney">$val[purchaseMoney]</td>
						<td>
							$val[expand1]
						</td>
						<td>
							$val[expand2]
						</td>
					</tr>
EOT;
			}
		}

		if(!empty($sourceCodeArr)){
			return array('detail' => $str , 'sourceCode' => implode($sourceCodeArr,', '));
		}else{
			return $str;
		}
	}

	/**
	 * 增加附属信息
	 */
	function initAddInfo_d($object){
		return "";
	}



	/******************************** 付款申请下推付款部分 *******************************/
	/**
	 * 付款申请下推付款部分
	 */
	function initPayablesAdd_i($object){
		$str = ""; //返回的模板字符串
		$i = 0; //列表记录序号
		$firstOption = null;
		if ($object) {
			$datadictArr = $this->getDatadicts ( 'YFRK' );
			foreach ($object as $key => $val) {
				$i++;
				if(empty($val['objCode'])){
					$firstOption = "<option value=''></option>";
				}
				$objTypeArr = $this->getDatadictsStr ( $datadictArr ['YFRK'], $val ['objType'] );
				$payContent = $this->initPayContent_i($val);
				$str .=<<<EOT
					<tr><td>$i</td>
						<td>
							<select class="selectmiddel" id="objTypeList$i"  disabled='true' value="$val[objType]" name="payables[detail][$i][objType]">
								$firstOption
								$objTypeArr
							</select>
						</td>
						<td>
							<input type="text" class="readOnlyTxtNormal" id="objCode$i" readonly='readonly' value="$val[objCode]" name="payables[detail][$i][objCode]"/>
							<input type="hidden" id="objType$i" value="$val[objType]" name="payables[detail][$i][objType]"/>
							<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxt formatMoney" readonly='readonly' id="money$i" value="$val[money]" name="payables[detail][$i][money]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtLong" readonly='readonly' value="$payContent" name="payables[detail][$i][payContent]"/>
							<input type="hidden" name="payables[detail][$i][expand1]" value="$val[expand2]"/>
							<input type="hidden" name="payables[detail][$i][expand2]" value="$val[expand1]"/>
							<input type="hidden" name="payables[detail][$i][payContent]" value="$payContent"/>
						</td>
					</tr>
EOT;
			}
		}
		return array (
			$str,
			$i
		);
	}

	/**
	 * 初始化付款详细
	 */
	function initPayContent_i($object){
//		print_r($object);
		$str = null;
		if(is_array($object)){
			$str = "" . $object['payDesc'];
		}
		return $str;
	}

	/**
	 * 批量确认付款重新组织扩展字段
	 */
	function rebuildExpandArr_i($object){
		$rebuildArr['expand1'] = $object['expand2'];
		$rebuildArr['expand2'] = $object['expand1'];
		$rebuildArr['payContent'] = $this->initPayContent_i($object);

		return $rebuildArr;
	}
}
?>