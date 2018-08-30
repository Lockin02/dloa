<?php

/**
 * 费用发票控制层类
 */
class controller_finance_invcost_invcost extends controller_base_action {

	function __construct() {
		$this->objName = "invcost";
		$this->objPath = "finance_invcost";
		parent :: __construct();
	}

	/**
	 * 重写page
	 */
	function c_page() {
		$this->display('list');
	}

	/**
	 * 重写c_toAdd
	 */
	function c_toAdd(){
		$this->showDatadicts ( array ('purType' => 'cgfs' ) );
		$this->showDatadicts ( array ('subjects' => 'CWKM' ) );
		$this->display ( 'add' );
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$rows = $_POST[$this->objName];
		if (empty ($rows['payDate'])) {
			unset ($rows['payDate']);
		}

		$id = $this->service->add_d($rows);
		if ($id) {
			msg('添加费用成功！');
		}
	}

	/**
	 * 初始用费用发票页面
	 */
	function c_init() {
		$invcost = $this->service->get_d($_GET['id']);
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			foreach ($invcost as $key => $val) {
				if ($key == 'invcostDetail') {
					$str = $this->showDetaillistview($val);
					$this->assign('invcostDetail', $str[0]);
					$this->assign('invnumber', $str[1]);
				} else {
					$this->assign($key, $val);
				}
			}
			$this->assign('subjects', $this->getDataNameByCode($invcost['subjects']));
			$this->assign('purType', $this->getDataNameByCode($invcost['purType']));
			$this->display('view');
		} else {
			foreach ($invcost as $key => $val) {
				if ($key == 'invcostDetail') {
					$str = $this->showDetaillist($val);
					$this->assign('invcostDetail', $str[0]);
					$this->assign('invnumber', $str[1]);
				} else {
					$this->assign($key, $val);
				}
			}
			$this->showDatadicts ( array ('subjects' => 'CWKM' ) ,$invcost['subjects']);
			$this->showDatadicts ( array ('purType' => 'cgfs' ) ,$invcost['purType']);
			$this->display('edit');
		}
	}

	/**
	 * 页面显示动态费用条目调用方法,返回字符串给页面模板替换 -----编辑
	 */
	function showDetaillist($rows) {
		if ($rows) {
			$i = 1; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$j = $i;
				$str .=<<<EOT
						<tr>
							<td>$j</td>
							<td>
								<input type='text' class="txtshort" id='costCode$i' value='$val[costCode]' name='invcost[invcostDetail][$i][costCode]'/>
							</td>
							<td>
								<input type='text' size='15' value='$val[costName]' id='costName$i' name='invcost[invcostDetail][$i][costName]'/>
							</td>
							<td>
								<input type='text' class="txtshort" id='costType$i' value='$val[costType]' name='invcost[invcostDetail][$i][costType]'/>
							</td>
							<td>
								<input type='text' class="txtshort" id='unit$i' value='$val[unit]' name='invcost[invcostDetail][$i][unit]'/>
							</td>
							<td>
								<input type='text' class="txtshort" id='number$i' value='$val[number]' onclick='countDetail();' onblur="FloatMul('number$i','price$i','amount$i',2);countDetail();" name='invcost[invcostDetail][$i][number]'/>
							</td>
							<td>
								<input type='text' class="txtshort formatMoney" id='price$i' value='$val[price]' onclick='countDetail();' onblur="FloatMul('number$i','price$i','amount$i',2);countDetail();" name='invcost[invcostDetail][$i][price]'/>
							</td>
							<td>
								<input type='text' class="txtshort formatMoney" id='amount$i' value='$val[amount]' onclick='countDetail();' onblur="FloatMul('number$i','price$i','amount$i',2);countDetail();" name='invcost[invcostDetail][$i][amount]'/>
							</td>
								<td width="5%"><img src="images/closeDiv.gif" onclick="mydel(this,'invbody');" title="删除行"/>
							</td>
						</tr>
EOT;
				$i++;
			}

		}
		return array (
			$str,
			$j
		);
	}

	/**
	 * 页面显示动态费用条目调用方法,返回字符串给页面模板替换 ---- 查看
	 */
	function showDetaillistview($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$j = $i +1;
				$str .=<<<EOT
						<tr>
							<td>$j</td>
							<td>
								$val[costCode]
							</td>
							<td>
								$val[costName]
							</td>
							<td>
								$val[costType]
							</td>
							<td>
								$val[unit]
							</td>
							<td class="formatMoney">
								$val[number]
							</td>
							<td class="formatMoney">
								$val[price]
							</td>
							<td class="formatMoney">
								$val[amount]
							</td>
						</tr>
EOT;
				$i++;
			}

		}
		return array (
			$str,
			$j
		);
	}

	/**
	 * 下拉表格
	 */
	function c_pageJsonGrid() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->asc = false;
		$rows = $service->pageJsonGrid_d();
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * 审核
	 */
	function c_audit() {
		if ($this->service->audit_d($_GET['id'])) {
			msg('审核成功！');
		} else {
			msg('审核失败!');
		}
	}

	/**
	 * 反审核
	 */
	function c_unaudit() {
		if ($this->service->unaudit_d($_GET['id'])) {
			msg('反审核成功！');
		} else {
			msg('反审核失败!');
		}
	}

	/**
	 * 采购合同查看费用发票
	 */
	function c_showContractList() {
		$this->assign('applyId', $_GET['applyId']);
		$this->assign('applyCode', $_GET['applyCode']);
		$this->display('contractlist');
	}

	/**
	 * 根据XX合同，查看所有的费用发票
	 */
	function c_contractJson() {
		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
		$service->asc = false;
		$rows = $service->page_d();
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}
	/**
	 * 采购合同录入费用发票
	 */
	function c_toAddInPurCont() {
		$purAppId = $_GET['applyId'];
		$purAppCode = $_GET['applyCode'];
		$rows = $this->service->getContractinfoById($purAppId); //根据采购合同Id获取合同信息
		$this->assignFunc($rows);
		$this->showDatadicts ( array ('purType' => 'cgfs' ) );
		$this->showDatadicts ( array ('subjects' => 'CWKM' ) );
		$this->display('addinpurcon');
	}

}
?>