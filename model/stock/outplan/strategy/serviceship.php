<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/stock/outplan/strategy/shipStrategy.php';

/**
 * @author zengzx
 * @Date 2011��5��5�� 17:03:12
 * @version 1.0
 * @description:���۷���������
 */
class model_stock_outplan_strategy_serviceship implements shipStrategy
{

    function __construct()
    {

    }

    /**
     * @description �������б���ʾģ��
     * @param $rows
     */
    function showList($rows)
    {

    }


    /**
     * @description ��������ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemAddByPlan($rows)
    {
        $j = 0;
        if (is_array($rows)) {
            $contProDao = new model_engineering_serviceContract_serviceequ();
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            foreach ($rows as $key => $val) {
                if ($rows[$key]['contEquId']) {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " .
                        $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.id = " . $rows[$key]['contEquId']);
                } else {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " .
                        $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.productId = " . $rows[$key]['productId']);
                }
                $contNum = $contNumArr[0]['number'];
                $number = $val['number'] - $val['executedNum'];
                $shipNum = $val['number'] - $val['shipNum'];
                if (empty($contNumArr[0]['isSell'])) {
                    $isPresent = "<font color='red'>��</font>";
                } else {
                    $isPresent = "<font>��</font>";
                }
                if ($shipNum == 0) {
                    continue;
                }
                $j = $i + 1;
                //if($val['notCarryAmount'] == 0) continue;
                $str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="ship[productsdetail][$j][productLine]" value="$val[productLine]"/>
							<input type="text" id="productLineName$j" readonly="true" name="ship[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem'/></td>
-->						<td>
							<input type="hidden" id="planId$j" name="ship[productsdetail][$j][planId]" value="$val[mainId]"/>
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="planEquId$j" name="ship[productsdetail][$j][planEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" id="productModel$j" readonly="true" name="ship[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<font color=green>$contNum</font>
						</td>
						<td>
							<input type="hidden" name="ship[productsdetail][$j][contNum]" id="contNum$j" value="$contNum" class='txtshort'/>
							<input type="hidden" id="canShipNum$j" value="$shipNum"/>
							<input type="text" name="ship[productsdetail][$j][number]" id="number$j" value="$shipNum" class='txtshort Num' onblur="checkThis('number$j','canShipNum$j');"/>
						</td>
						<td width="8%">$isPresent</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][remark]" id="remark" class='txtlong'/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
                $i++;
            }

        }
        $str .= '<input type="hidden" id="rowNum" value="' . $j . '"/>';
        return $str;
    }

    /**
     * @description ��������ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemAdd($rows)
    {
        $j = 0;
        if (is_array($rows)) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            foreach ($rows as $key => $val) {
                $j = $i + 1;
                //if($val['notCarryAmount'] == 0) continue;
                $str .= <<<EOT
					<tr><td>$j</td>
						<td>
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" name="ship[productsdetail][$j][applyNum]" id="shipNum$j" value="$val[number]" class='readOnlyTxtItem' onblur="checkThis($j)"/>
							<input type="hidden" id="canShip$j" value="$val[notCarryAmount]"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
                $i++;
            }

        }
        $str .= '<input type="hidden" id="rowNum" value="' . $j . '"/>';
        return $str;

    }

    /**
     * @description �޸ķ���ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemEdit($rows)
    {
        $j = 0;
        if (is_array($rows)) {
            $contProDao = new model_engineering_serviceContract_serviceequ();
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            foreach ($rows as $key => $val) {
                if ($rows[$key]['contEquId']) {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.id = " . $rows[$key]['contEquId']);
                } else {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.productId = " . $rows[$key]['productId']);
                }
                $contNum = $contNumArr[0]['number'];
                if (empty($contNumArr[0]['isSell'])) {
                    $isPresent = "<font color='red'>��</font>";
                } else {
                    $isPresent = "<font>��</font>";
                }
                $j = $i + 1;
                //if($val['notCarryAmount'] == 0) continue;
                $str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="ship[productsdetail][$j][productLine]" value="$val[productLine]"/>
							<input type="text" id="productLineName$j" readonly="true" name="ship[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem'/></td>
-->						<td>
							<input type="hidden" id="planId$j" name="ship[productsdetail][$j][planId]" value="$val[planId]"/>
							<input type="text" id="productNo$j" readonly="true" name="ship[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="hidden" id="planEquId$j" name="ship[productsdetail][$j][planEquId]" value="$val[planEquId]"/>
							<input type="hidden" id="productId$j" name="ship[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="ship[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtItem'/></td>
						<td>
							<input type="text" id="productModel$j" readonly="true" name="ship[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem'/>
						</td>
						<td>
							<font color=green>$contNum</font>
						</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td width="8%">$isPresent</td>
						<td>
							<input type="text" name="ship[productsdetail][$j][remark]" id="remark" value="$val[remark]" class='txtlong'/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='ɾ����'>
						</td>
					</tr>
EOT;
                $i++;
            }

        }
        $str .= '<input type="hidden" id="rowNum" value="' . $j . '"/>';
        return $str;
    }

    /**
     * @description �鿴����ʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemView($rows)
    {
        $j = 0;
        $str = ""; //���ص�ģ���ַ���
        $contProDao = new model_engineering_serviceContract_serviceequ();
        if (is_array($rows)) {
            $i = 0; //�б��¼���
            foreach ($rows as $key => $val) {
                $productNameStr = model_common_util::mb_str_split($val ['productName'], 20);
                $remarkStr = model_common_util::mb_str_split($val ['remark'], 40);
                if ($rows[$key]['contEquId']) {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.id = " . $rows[$key]['contEquId']);
                } else {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.productId = " . $rows[$key]['productId']);
                }
                $contNum = $contNumArr[0]['number'];
                if (empty($contNumArr[0]['isSell'])) {
                    $isPresent = "<font color='red'>��</font>";
                } else {
                    $isPresent = "<font>��</font>";
                }
                $j = $i + 1;
                $str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j</td>
<!--							<td>$val[productLineName]</td>-->
							<td>$val[productNo]</td>
							<td>$productNameStr</td>
							<td>$val[productModel]</td>
							<td>
								<font color=green>$contNum</font>
							</td>
							<td width="15%">$val[number]</td>
							<td width="8%">$isPresent</td>
							<td width="30%">$remarkStr</td>
						</tr>
EOT;
                $i++;
            }
        }
        $str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
        return $str;
    }


    /**
     * @description ��ӡʱ���嵥��ʾģ��
     * @param $rows
     */
    function showItemPrint($rows)
    {
        $j = 0;
        $str = ""; //���ص�ģ���ַ���
        $contProDao = new model_engineering_serviceContract_serviceequ();
        if (is_array($rows)) {
            $i = 0; //�б��¼���
            foreach ($rows as $key => $val) {
                $productNameStr = model_common_util::mb_str_split($val ['productName'], 20);
                $remarkStr = model_common_util::mb_str_split($val ['remark'], 40);
                if ($rows[$key]['contEquId']) {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.id = " . $rows[$key]['contEquId']);
                } else {
                    $contNumArr = $contProDao->_db->getArray(" select c.number,c.isSell from " . $contProDao->tbl_name . " c where c.orderId = " . $rows[$key]['docId'] . " and c.productId = " . $rows[$key]['productId']);
                }
                $contNum = $contNumArr[0]['number'];
                if (empty($contNumArr[0]['isSell'])) {
                    $isPresent = "<font color='red'>��</font>";
                } else {
                    $isPresent = "<font>��</font>";
                }
                $j = $i + 1;
                $str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j</td>
<!--							<td>$val[productLineName]</td>-->
							<td>$val[productNo]</td>
							<td>$productNameStr</td>
							<td>$val[productModel]</td>
							<td>
								<font color=green>$contNum</font>
							</td>
							<td width="15%">$val[number]</td>
							<td width="30%">$remarkStr</td>
						</tr>
EOT;
                $i++;
            }
        }
        $str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
        return $str;
    }

    /**
     * �鿴���ҵ����Ϣ
     * @param $paramArr
     */
    function viewRelInfo($paramArr = false)
    {
    }

    /**
     * ���ݷ����ƻ�����������ʱ�������ҵ��
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAddByPlan($paramArr = false, $relItemArr = false)
    {

    }

    /**
     * ��������ʱ�������ҵ��
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = false)
    {
        if ($paramArr['planId']) {
            $planDao = new model_stock_outplan_outplan();
            $planDao->updateBusinessByShip($paramArr['planId']);
        }
    }

    /**
     * �޸ķ���ʱ�������ҵ��
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false)
    {
        if ($paramArr['planId']) {
            $planDao = new model_stock_outplan_outplan();
            $planDao->updateBusinessByShip($paramArr['planId']);
        }
    }

    /**
     * ɾ������ʱ�������ҵ��
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtDel($paramArr = false)
    {
        if ($paramArr['planId']) {
            $planDao = new model_stock_outplan_outplan();
            $planDao->updateBusinessByShip($paramArr['planId']);
        }
    }

    /**
     * ���ƻ�ȡԴ�����ݷ���
     */
    function getDocInfo($id)
    {
        $docDao = new model_stock_outplan_outplan();
        $rows = $docDao->get_d($id);
        $docDao->setCompany(0);
        $proNumSql = "select s.planEquId,sum(s.number) as shipNum from oa_stock_ship_product s where s.planId=" . $id . " group by s.planEquId";
        $proNumArr = $docDao->listBySql($proNumSql);
        if (isset($proNumArr))
            foreach ($rows['details'] as $key => $val) {
                foreach ($proNumArr as $index => $value) {
                    if ($rows['details'][$key]['id'] == $proNumArr[$index]['planEquId']) {
                        $rows['details'][$key]['shipNum'] = $proNumArr[$index]['shipNum'];
                        break;
                    } else {
                        $rows['details'][$key]['shipNum'] = 0;
                    }
                }
            }
        return $rows;
    }

//	/**
//	 * ����ʱ�������ҵ�����
//	 */
//	 function dealOutStock_d( $rows ){
//		$outplanDao = new model_stock_outplan_outplan();
//		$detail = $rows['details'];
//		foreach ( $detail as $key => $val ){
//			//���º�ͬ�豸����
//			$contEquSql = "update oa_service_equ set executedNum=executedNum+" . $val['number']
//			  . " where orderId = " . $rows['docId'] . " and productId = " . $val['productId'];
//			$outplanDao->_db->query( $contEquSql );
//			//���¼ƻ��豸����
//			$outplanSql = "update oa_stock_outplan_product set executedNum=executedNum+" . $val['number']
//			  . " where mainId = " . $rows['planId'] . " and productId = " . $val['productId'];
//			$outplanDao->_db->query( $outplanSql );
//		}
//		$outplanDao->updatePlanStatus_d( $rows['planId'],$rows );
//	 }

    /**
     * ��ȡ��ͬ������Ա
     */
    function getSaleman($docId, $docType)
    {
        $planDao = new model_stock_outplan_outplan();
        $relatedStrategy = $planDao->relatedStrategyArr[$docType];
        $saleman = $planDao->getSaleman($docId, new $relatedStrategy);
        return $saleman;
    }


}

?>