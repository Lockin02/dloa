<?php
header ( "Content-type: text/html; charset=GBK" );

//引入接口
include_once WEB_TOR . 'model/produce/apply/strategy/iproduceapply.php';
/**
 *
 * 借用生产申请策略
 * @author huangzf
 *
 */
class model_produce_apply_strategy_borrowapply extends model_base implements iproduceapply {
	function __construct() {
	}

	/**
	 * @description 下达生产申请,清单显示模板
	 * @param $rows
	 */
	function showItemAtApply($obj) {
		//		echo "<pre>";
		//		print_r($obj);
		if ($obj ['borrowequ']) {
			$i = 0; //清单记录序号
			$str = ""; //返回的模板字符串
			foreach ( $obj ['borrowequ'] as $key => $val ) {
				$notExeNum = $val ['number'] - $val ['issuedProNum'];
				if ($notExeNum > 0 && $val ['isTemp'] != "1" && $val ['isDel'] != "1") {
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
                                    <input type="text" name="produceapply[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" readOnly value="{$val['productNo']}" />
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
		//		echo "<pre>";
		//		print_r($obj);
		$show->assign ( "relDocType", "BORROW" ); //源单类型
		$show->assign ( "relDocTypeName", "借试用" ); //源单类型中文
		$show->assign ( "relDocId", $obj ['id'] ); //源单id
		$show->assign ( "relDocCode", $obj ['objCode'] ); //源单编号
		$show->assign ( "customerName", $obj ['customerName'] );
		$show->assign ( "customerId", $obj ['customerId'] );
		$show->assign ( "saleUserName", $obj ['createName'] );
		$show->assign ( "saleUserCode", $obj ['createId'] );
	}
	/**
	 * 新增生产申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$borrowEqDao = new model_projectmanagent_borrow_borrowequ ();

		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update  oa_borrow_equ set  issuedProNum=issuedProNum+" . $value ['produceNum'] . " where id=" . $value ['relDocItemId'];
				//echo $sql;
				$borrowEqDao->query ( $sql );
			}
		}
	}
	/**
	 * 修改生产申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE) {
			$borrowEqDao = new model_projectmanagent_borrow_borrowequ ();

		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update  oa_borrow_equ set  issuedProNum=issuedProNum-".$lastItemArr[$key]['produceNum']."+" . $value ['produceNum'] . " where id=" . $value ['relDocItemId'];
				//echo $sql;
				$borrowEqDao->query ( $sql );
			}
		}
	}

	/**
	 * 获取源单清单信息
	 */
	function getRelDocInfo($id) {
		$borrowDao = new model_projectmanagent_borrow_borrow ();
		return $borrowDao->get_d ( $id, array ("borrowequ" ) );
	}
}
?>
