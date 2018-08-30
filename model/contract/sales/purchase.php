<?php
class model_contract_sales_purchase extends model_base{

	public static $pageArr=array();

	function __construct() {
		$this->tbl_name = "oa_contract_sales";
		$this->sql_map = "contract/sales/purchaseSql.php";
		parent :: __construct();
	}

	/**
	 * 设备-合同 显示列表
	 */
	public function showlist($rows,$showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$iClass = (($i%2) == 0)?"tr_even":"tr_odd";
				$strChild = "";
				$amountAll=0;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( !isset( $chdVal["amount"] ) || $chdVal["amount"]==0 || $chdVal["amount"]=="" ){
						$chdVal["amount"] = 0;
						continue;
					}

					if( !isset( $chdVal["beforeChangeAmount"] ) || $chdVal["beforeChangeAmount"]=="" || $chdVal["beforeChangeAmount"]==null){
						$chdVal["beforeChangeAmount"] = $chdVal["amount"];
					}

					if( !isset($chdVal["alreadyFlwgfollowing"]) ){
						$chdVal["alreadyFlwgfollowing"] = $chdVal["amount"];
					}else{
						$chdVal["alreadyFlwgfollowing"]= $chdVal["amount"] - $chdVal["alreadyFlwgfollowing"];
					}
					$amountAll += $chdVal["alreadyFlwgfollowing"];

					if( !isset( $chdVal["byWayAmount"] ) ){
						$chdVal["byWayAmount"]=0;
					}

					if( !isset( $chdVal["alreadyCarryAmount"] ) ){
						$chdVal["alreadyCarryAmount"]=0;
					}

					if( $chdVal['alreadyFlwgfollowing']==0 || $chdVal['alreadyFlwgfollowing']=="" ){
						$checkBoxStr =<<<EOT
				        	$chdVal[contNumber] <br> $chdVal[contName]
EOT;
					}else{
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild" value="123">
				            <input type="hidden" class="hidden" value="$chdVal[id]"/>
				            $chdVal[contNumber] <br> $chdVal[contName]
EOT;
					}

					$strChild.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				            $checkBoxStr
				        </td>
				        <td width="60">
				            $chdVal[beforeChangeAmount]
				        </td>
				        <td width="60">
				            $chdVal[amount]
				        </td>
				        <td width="60">
				            $chdVal[alreadyFlwgfollowing]
				        </td>
				        <td width="60">
				            $chdVal[byWayAmount]
				        </td>
				        <td width="60">
				            $chdVal[alreadyCarryAmount]
				        </td>
	        		</tr>
EOT;
				}

				$str .=<<<EOT
	<tr class="TableLine $iClass" >
		<td  >
			<p class="childImg">
            <img src="images/expanded.gif" />$i
        	</p>
        </td>
        <td >
            $val[productNumber]
        </td>
        <td  >
            $val[productName]
        </td>
        <td align="left" >
            <p class="checkChildAll"><input type="checkbox">$amountAll</p>
        </td>
        <td >
            $val[amountCan]
        </td>
        <td width="50%" class="tdChange td_table" >
			<table width="100%"  class="shrinkTable main_table_nested">
				$strChild
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
    </tr>
EOT;
			}
		}else {
			$str = '<tr align="center" height="28"><td colspan="9">暂无相关信息</td></tr>';
		}
//		return $str . '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
		return $str;
	}

	/**
	 * 新建采购计划示列表
	 */
	public function newPlan($listEqu){
		//print_r($listEqu);
		$str="";
		$i = $m = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				$allAmount = 0;
				foreach ($val['childArr'] as $chdKey1 => $chdVal1){
					//$allAmount += $chdVal1["alreadyFlwgfollowing"];
					$amountIss = $chdVal1["amount"] - $chdVal1["alreadyFlwgfollowing"];
					$allAmount += $amountIss;
				}
				$str.=<<<EOT
		<tr height="28" align="center">
			<td   width="5%">
				<p class="childImg">
					<image src="images/expanded.gif" />$i
				</p>
			</td>
			<td   width="16%">
				$val[productName]
			</td>
			<td   width="16%">
				$val[productNumber]
			</td>
			<td   width="6%">
				<p class="allAmount">$allAmount</p>
			</td>
			<td >
				<table class="shrinkTable" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
					$nowTime=date("Y-m-d H:i:s");
					$amountIss = $chdVal["amount"] - $chdVal["alreadyFlwgfollowing"];

					$str.=<<<EOT
				<tr align="center">
						<td width="30%">
							$chdVal[contNumber]<br>
							$chdVal[contName]
						</td>
						<td  width="12%">
							<input type="text" name="basic[equment][$m][amountAll]" id="amountAll$m" value="$amountIss" size=6 class="planAmount">
							<input type="hidden" name="amountAll" value="$amountIss"/>


							<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]"/>
							<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]"/>
							<input type="hidden" name="basic[equment][$m][productNumb]" value="$chdVal[productNumber]"/>
							<input type="hidden" name="basic[equment][$m][contNumb]" value="$chdVal[contNumber]"/>
							<input type="hidden" name="basic[equment][$m][contId]" value="$chdVal[contId]"/>
							<input type="hidden" name="basic[equment][$m][contName]" value="$chdVal[contName]"/>
							<input type="hidden" name="basic[equment][$m][contDeviceNumb]" value="$chdVal[equipListId]"/>
							<input type="hidden" name="basic[equment][$m][contDeviceId]" value="$chdVal[id]"/>
							<input type="hidden" name="basic[equment][$m][contDeviceOnlyId]" value="$chdVal[contOnlyId]"/>
							<input type="hidden" name="basic[equment][$m][amountIssued]" value="0"/>
						</td>
						<td width="20%">
							&nbsp;<input type="text" id="dateHope$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput" value="" onfocus="WdatePicker()" readonly />
						</td>
						<td>
							<textarea rows="2" cols="25" name="basic[equment][$m][remark]" id="remark$m"></textarea>
						</td>
					</tr>
EOT;
					++$m;
				}
				$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开本设备具体信息></div>
        </td>
    </tr>
EOT;
			}
		}
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下为合同设备接口方法,可以为其他模块所调用------------------------------
	 *************************************************************************************************/

	/**
	 * 合同设备-合同分页列表
	 */
	function page_d($showpage,$sql='list_page') {

		//$rows = $this->pageBySqlId("product_list_page");
		$rows = $this->pageBySqlId($sql);
		//分页
		$showpage->show_page(array (
			'total' => $this->count,
			'perpage' => pagenum
		));
		$i = 0;
		if($rows){
//			echo "<pre>";
//			print_r($rows);
			$stockDao = new model_stock_inventoryinfo_inventoryinfo();
			foreach($rows as $key => $val){
				$this->resetParam();
				$searchArr = array (
					"productNumber" => $val['productNumber'],
					"storageId" => $val['storageId']
				);
				$this->__SET('sort', "id");
				$this->__SET('searchArr', $searchArr);
				//$chiRows = $this->listBySqlId("product_list_page");
				$chiRows = $this->listBySqlId("list_page");
				//$val[byWayAmount]
				$rows[$i]['childArr']=$chiRows;

				$amountCan = $stockDao->getInventExeNum("contract",$val['productId']);
				$amountCan = isset($amountCan)?$amountCan:0;
				//$rows[$i]['amountCan'] = $amountCan+$rows[$i]['canCarryAmount'];
				$rows[$i]['amountCan'] = $amountCan;
				++$i;
			}
//			echo "<pre>";
//			print_r($rows);
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * 合同设备-合同不分页列表
	 */
//	function listEqu_d($str){
//		//echo $str;
//		$searchArr = array (
//			"selectEqu" => $str
//		);
//		$this->__SET('groupBy', "productNumber,storageId");
//		$this->__SET('sort', "id");
//		$this->__SET('searchArr', $searchArr);
//		$rows = $this->listBySqlId("product_list");
//		$i = 0;
//		foreach($rows as $key => $val){
//			$this->resetParam();
//			$searchArr = array (
//				"productNumber" => $val['productNumber'],
//				"storageId" => $val['storageId'],
//				"selectEqu" => $str
//			);
//			$this->__SET('sort', "id");
//			$this->__SET('searchArr', $searchArr);
//			$chiRows = $this->listBySqlId("product_list");
//			$rows[$i]['childArr']=$chiRows;
//			++$i;
//		}
//		return $rows;
//	}

	/**
	 * 保存生产计划
	 */
//	function addProductionPlan_d($object) {
//		$service = new model_production_plan_productplanbasic();
//		return $service->add_d($object);
//	}

	/**
	 * 保存采购计划
	 */
//	function addPurchPlan_d($object) {
//		try {
//			$this->start_d ();
//			$service = new model_purchase_plansales_purchsalesplanbasic();
//			$service->add_d($object);
//			$this->commit_d ();
//			return true;
//		} catch ( Exception $e ) {
//			$this->rollBack ();
//			echo "<pre>";
//			print_r( $e );
//			return false;
//		}
//	}

}
?>