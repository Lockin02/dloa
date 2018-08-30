<?php
header ( "Content-type: text/html; charset=GBK" );

//引入接口
include_once WEB_TOR . 'model/produce/apply/strategy/iproduceapply.php';
/**
 *
 * 合同生产申请策略
 * @author huangzf
 *
 */
class model_produce_apply_strategy_contractapply extends model_base implements iproduceapply {
	function __construct() {
		//		$this->relDocTypeArr = array ("RSLTZD" => array ("name" => "收料通知单", "mainModel" => "model_purchase_arrival_arrival", "dealMainFun" => "updateInStock" ), "RTLTZD" => array ("name" => "收料通知单", "mainModel" => "model_purchase_delivered_delivered", "dealMainFun" => "updateInStock" ) );
	//		$this->cancelDocTypeArr = array ("RSLTZD" => array ("name" => "收料通知单", "mainModel" => "model_purchase_arrival_arrival", "dealMainFun" => "updateInStockCancel" ), "RTLTZD" => array ("name" => "退料通知单", "mainModel" => "model_purchase_delivered_delivered", "dealMainFun" => "updateInStockCancel" ) );
	}

	/**
	 * @description 下达生产申请,清单显示模板
	 * @param $rows
	 */
	function showItemAtApply($obj) {
//		echo "<pre>";
//		print_r($obj);


		if ($obj ['equ']) {
			$i = 0; //清单记录序号
			$str = ""; //返回的模板字符串
			foreach ( $obj ['equ'] as $key => $val ) {
				$notExeNum = $val ['number'] - $val ['issuedProNum'];
				if ($notExeNum > 0&&$val['isTemp']!="1"&&$val['isDel']!="1") {
					$seNum = $i + 1;
					$str .= <<<EOT
				<tr align="center" >
								<td>
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
			                    </td>
                               <td>
                                    $seNum
                                   </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" readOnly value="{$val['productCode']}" />
                                    <input type="hidden" name="produceapply[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                                    <input type="hidden" name="produceapply[items][$i][relDocItemId]" id="relDocItemId$i" value="{$val['id']}"  />
                                    <input type="hidden" name="produceapply[items][$i][goodsConfigId]" id="goodsConfigId$i" value="{$val['deploy']}"  />
                                    <input type="hidden" name="produceapply[items][$i][licenseConfigId]" id="licenseConfigId$i" value="{$val['license']}"  />
                                </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readOnly value="{$val['productName']}" />
                                </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" readOnly value="{$val['productModel']}" />
                                </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" readOnly value="{$val['unitName']}" />
                                </td>
                                <td>
                                    {$val['number']}
                                </td>
                                <td>
                                    {$val['executedNum']}
                                </td>
                                <td>
                                    {$val['issuedProNum']}
                                </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][produceNum]" id="produceNum$i"  class="txtshort" value="$notExeNum" />
                                    <input type="hidden"  id="notExeNum$i"  class="txtshort" value="$notExeNum" />
                                </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][planEndDate]"  id="planEndDate$i" class="txtshort" onfocus="WdatePicker()" value="{$val['planEndDate']}" />
                                </td>
                                <td>
                                    <input type="text" name="produceapply[items][$i][remark]" id="storageNum$i" class="txt" value="{$val['storageNum']}" />
                                </td>
                </tr>
EOT;
					$i ++;
				}
			}
			return $str;
		}
	}
	/**
	 * 生产申请基本信息 模板赋值
	 *
	 * @param  $obj
	 */
	function assignBaseAtApply($obj, show $show) {
		$show->assign ( "relDocType", "CONTRACT" ); //源单类型
		$show->assign ( "relDocTypeName", "合同" ); //源单类型中文
		$show->assign ( "relDocId", $obj ['id'] ); //源单id
		$show->assign ( "relDocCode", $obj ['objCode'] ); //源单编号
		$show->assign ( "customerName", $obj ['customerName'] );
		$show->assign ( "customerId", $obj ['customerId'] );
		$show->assign ( "saleUserName", $obj ['prinvipalName'] );
		$show->assign ( "saleUserCode", $obj ['prinvipalId'] );
	}
	/**
	 * 新增生产申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$contractEqDao = new model_contract_contract_equ ();

		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update  oa_contract_equ set  issuedProNum=issuedProNum+" . $value ['produceNum'] . " where id=" . $value ['relDocItemId'];
				//echo $sql;
				$contractEqDao->query ( $sql );
			}
		}

	}
	/**
	 * 修改生产申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE) {
		$contractEqDao = new model_contract_contract_equ ();
		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update  oa_contract_equ set  issuedProNum=issuedProNum-".$lastItemArr[$key]['produceNum']."+". $value ['produceNum'] . " where id=" . $value ['relDocItemId'];
//echo $sql;
				$contractEqDao->query ( $sql );
			}
		}
	}

	/**
	 * 获取源单清单信息
	 */
	function getRelDocInfo($id) {
		$contractDao = new model_contract_contract_contract ();
		return $contractDao->getContractInfo ( $id, array ("equ" ) );
	}
}
?>
