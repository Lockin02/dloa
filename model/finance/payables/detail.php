<?php
/**
 * @author Show
 * @Date 2011年5月6日 星期五 16:22:12
 * @version 1.0
 * @description:明细表 Model层
 */
 class model_finance_payables_detail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_payables_detail";
		$this->sql_map = "finance/payables/detailSql.php";
		parent::__construct ();
	}

	/*************************模板生成部分************************/

	/**
	 * 查看
	 */
	function initView($rows) {
		$str = ""; //返回的模板字符串
		if ($rows) {
			$i = 0; //列表记录序号
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$objType = $datadictDao->getDataNameByCode($val['objType']);
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								$objType
							</td>
							<td>
								$val[objCode]
							</td>
							<td class='formatMoney'>
								$val[money]
							</td>
							<td>
								$val[payContent]
							</td>
						</tr>
EOT;
			}
		} else {
			$str = "<tr align='center'><td colspan='6'>没有详细信息</td></tr>";
		}
		return $str;
	}

	/**
	 * 编辑页，渲染分配详细模板变量。
	 */
	function initEdit($rows) {
		$str = ""; //返回的模板字符串
		$i = 0; //列表记录序号
		if ($rows) {
			$datadictArr = $this->getDatadicts ( 'YFRK' );
			foreach ($rows as $key => $val) {
				$i++;
				$objTypeArr = $this->getDatadictsStr ( $datadictArr ['YFRK'], $val ['objType'] );
				if(empty($val ['objType'])){
					$objTypeArr = '<option value="">请选择单据</option>'.$objTypeArr;
				}
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								<select class="selectmiddel" id="objType$i" onchange="initGrid($i)" value="$val[objType]" name="payables[detail][$i][objType]">
									$objTypeArr
								</select>
							</td>
							<td>
								<input type="text" class="txt" id="objCode$i" value="$val[objCode]" name="payables[detail][$i][objCode]"/>
								<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
							</td>
							<td>
								<input type="text" class="txtmiddle formatMoney" id="money$i" value="$val[money]" name="payables[detail][$i][money]" onblur="countAll()"/>
							</td>
							<td>
								<input type="text" class="txtlong" value="$val[payContent]" name="payables[detail][$i][payContent]"/>
								<input type="hidden" value="$val[expand1]" name="payables[detail][$i][expand1]"/>
								<input type="hidden" value="$val[expand2]" name="payables[detail][$i][expand2]"/>
							</td>
						 	<td width="5%">
						 		<img src="images/closeDiv.gif" onclick="mydel(this,'mytbody')" title="删除行"/>
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
	 * 渲染下推退款单从表
	 */
	function initRefund($rows) {
		$str = ""; //返回的模板字符串
		$i = 0; //列表记录序号
		if ($rows) {
			$datadictDao = new model_system_datadict_datadict();
			foreach ($rows as $key => $val) {
				$i++;
				$objType = $datadictDao->getDataNameByCode($val['objType']);
				$str .=<<<EOT
						<tr><td>$i</td>
							<td>
								$objType
							</td>
							<td>
								$val[objCode]
								<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
								<input type="hidden" id="objCode$i" value="$val[objCode]" name="payables[detail][$i][objCode]"/>
								<input type="hidden" id="objType$i" value="$val[objType]" name="payables[detail][$i][objType]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxtMiddle formatMoney" id="money$i" value="$val[money]" name="payables[detail][$i][money]" readonly="readonly"/>
								<input type="hidden" id="orgMoney$i" value="$val[money]"/>
							</td>
							<td>
								<input type="text" class="readOnlyTxtLong" readonly='readonly' value="$val[payContent]" name="payables[detail][$i][payContent]"/>
								<input type="hidden" value="$val[expand1]" name="payables[detail][$i][expand1]"/>
								<input type="hidden" value="$val[expand2]" name="payables[detail][$i][expand2]"/>
								<input type="hidden" value="$val[expand3]" name="payables[detail][$i][expand3]"/>
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

	/************************外部接口调用方法*************************/

	/*
	 * 根据到款获取到款分配
	 */
	function getDetail($advancesId) {
		$this->searchArr = array (
			'advancesId' => $advancesId
		);
		$this->asc = false;
		return $this->list_d();
	}

	/*
	 * 获取可退款的金额
	 */
	function getCanRefundDetail($advancesId) {
		$this->searchArr = array (
			'relatedId' => $advancesId
		);
		$this->groupBy = 'c.objId,c.objType having money <> 0';
		$this->asc = false;
		return $this->list_d('select_count');
	}

	/**
	 * 获取可退款金额 - 暂用与采购退款
	 */
	function getCanRefundDetailGE($advancesId) {
		$this->searchArr = array (
			'relatedId' => $advancesId
		);
		$this->groupBy = 'c.objId,c.objType,c.expand1 having money <> 0';
		$this->asc = false;
		return $this->list_d('select_count');
	}

	/*
	 * 根据到款删除到款分配
	 */
	function deleteDetail($advancesId) {
		$condition = array (
			'advancesId' => $advancesId
		);
		$this->delete($condition);
	}

	/**
	 * 根据物料清单id获取已付金额
	 */
	function getPayedMoney_d($expand1,$objType = 'YFRK-01'){
		$this->searchArr = array (
			'expand1' => $expand1,
			'objType' => $objType
		);
		$this->groupBy = 'c.objId,c.objType,c.expand1 having money <> 0';
		$this->asc = false;
		$rs = $this->list_d('select_count');
		if($rs){
			return $rs[0]['money'];
		}else{
			return 0;
		}
	}
}
?>