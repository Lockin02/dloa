<?php
header ( "Content-type: text/html; charset=GBK" );

//����ӿ�
include_once WEB_TOR . 'model/produce/apply/strategy/iproduceapply.php';
/**
 *
 * ���������������
 * @author huangzf
 *
 */
class model_produce_apply_strategy_presentapply extends model_base implements iproduceapply {
	function __construct() {
	}

	/**
	 * @description �´���������,�嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAtApply($obj) {
		//		echo "<pre>";
		//		print_r($obj);
		if ($obj ['equ']) {
			$i = 0; //�嵥��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ( $obj ['equ'] as $key => $val ) {
				$notExeNum = $val ['number'] - $val ['issuedProNum'];
				if ($notExeNum > 0 && $val ['isTemp'] != "1" && $val ['isDel'] != "1") {
					$seNum = $i + 1;
					$str .= <<<EOT
				<tr align="center" >
								<td>
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
	 * �������������Ϣ ģ�帳ֵ
	 *
	 * @param  $obj
	 */
	function assignBaseAtApply($obj, show $show) {
		$show->assign ( "relDocType", "PRESENT" ); //Դ������
		$show->assign ( "relDocTypeName", "����" ); //Դ����������
		$show->assign ( "relDocId", $obj ['id'] ); //Դ��id
		$show->assign ( "relDocCode", $obj ['objCode'] ); //Դ�����
		$show->assign ( "customerName", $obj ['customerName'] );
		$show->assign ( "customerId", $obj ['customerId'] );
		$show->assign ( "saleUserName", $obj ['salesName'] );
		$show->assign ( "saleUserCode", $obj ['salesNameId'] );
	}
	/**
	 * ������������ʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$presentEqDao = new model_projectmanagent_present_presentequ ();

		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update  oa_present_equ set  issuedProNum=issuedProNum+" . $value ['produceNum'] . " where id=" . $value ['relDocItemId'];
				//echo $sql;
				$presentEqDao->query ( $sql );
			}
		}
	}
	/**
	 * �޸���������ʱԴ����ҵ����
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false, $lastItemArr = FALSE) {
		$presentEqDao = new model_projectmanagent_present_presentequ ();

		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update  oa_present_equ set  issuedProNum=issuedProNum-".$lastItemArr[$key]['produceNum']."+" . $value ['produceNum'] . " where id=" . $value ['relDocItemId'];
//				echo $sql;
				$presentEqDao->query ( $sql );
			}
		}
	}

	/**
	 * ��ȡԴ���嵥��Ϣ
	 */
	function getRelDocInfo($id) {
		$presentDao = new model_projectmanagent_present_present ();
		return $presentDao->getPresentInfo ( $id, array ("equ" ) );
	}
}
?>
