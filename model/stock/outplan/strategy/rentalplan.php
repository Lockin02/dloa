<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/stock/outplan/strategy/planStrategy.php';
/**
 * @author zengzx
 * @Date 2011年5月5日 17:03:12
 * @version 1.0
 * @description:销售发货通知单策略
 */
class model_stock_outplan_strategy_rentalplan  implements planStrategy {

	function __construct() {

	}
		/**
	 * @description 发货通知单列表显示模板
	 * @param $rows
	 */
	function showList($rows){

	}

	/**
	 * @description 新增发货通知时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				if(empty($val['isSell'])){
					$isPresent = "<font color='red'>是</font>";
				}else{
					$isPresent = "<font>否</font>";
				}
				$j = $i + 1;
				//已下达数量
				$planNum = $rows[$key]['issuedShipNum'];
				//合同数量
				$contNum = $rows[$key]['number'];
				$contRemain = $contNum - $planNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="outplan[productsdetail][$j][productLine]" value="$val[productLine]" class='readOnlyTxtItem' readonly/>
							<input type="text" id="productLineName$j" name="outplan[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem' readonly/></td>
-->						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[id]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<input type="hidden" id="stockId$j" name="outplan[productsdetail][$j][stockId]" class='txtmiddle'/>
							<input type="hidden" id="stockCode$j" name="outplan[productsdetail][$j][stockCode]" class='txtmiddle'/>
							<input type="text" id="stockName$j" name="outplan[productsdetail][$j][stockName]" class='txtmiddle'/></td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="contNum$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$planNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][number]" id="number$j" value="$contRemain" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td width="8%">$isPresent</td>
						<!--td>
							<input type="text" id="lockNum" name="outplan[productsdetail][$j][lockNum]" class='txtshort' value="$contRemain"/>
						</td-->
                        <td>
							<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>即时库存</font></a>
                        </td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="13"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}
		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;

	}

	/**
	 * @description 修改发货通知时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//合同数量
				if( $rows[$key]['contEquId'] ){
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_lease_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_lease_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				if(empty($contNumArr[0]['isSell'])){
					$isPresent = "<font color='red'>是</font>";
				}else{
					$isPresent = "<font>否</font>";
				}
				$contNum = $contNumArr[0]['contNum'];//合同数量
				$planNum =  $contNumArr[0]['issuedShipNum'];//已下发发货数量
				$planNum = $planNum - $val['number'];//减去本次发货的数量
				$contRemain = $contNum - $planNum;
				if( $contRemain <= 0 ){
					continue;
				}
				$str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="outplan[productsdetail][$j][productLine]" value="$val[productLine]" class='readOnlyTxtItem' readonly/>
							<input type="text" id="productLineName$j" name="outplan[productsdetail][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem' readonly/></td>
-->						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<input type="hidden" id="stockId$j" name="outplan[productsdetail][$j][stockId]" value="$val[stockId]" class='txtmiddle'/>
							<input type="hidden" id="stockCode$j" name="outplan[productsdetail][$j][stockCode]" value="$val[stockCode]" class='txtmiddle'/>
							<input type="text" id="stockName$j" name="outplan[productsdetail][$j][stockName]" value="$val[stockName]" class='txtmiddle'/></td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="remain$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$planNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j"  name="outplan[productsdetail][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td width="8%">$isPresent</td>
						<!--td>
							<input type="text" id="lockNum" name="outplan[productsdetail][$j][lockNum]" class='txtshort' value="$val[lockNum]"/>
						</td-->
                        <td>
							<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>即时库存</font></a>
                        </td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}


		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}


	/**
	 * @description 修改发货通知时，清单显示模板
	 * @param $rows
	 */
	function showItemChange($rows) {
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//合同数量
				if( $rows[$key]['contEquId'] ){
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_lease_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_lease_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				if(empty($contNumArr[0]['isSell'])){
					$isPresent = "<font color='red'>是</font>";
				}else{
					$isPresent = "否";
				}
				$contNum = $contNumArr[0]['contNum'];//合同数量
				$planNum =  $contNumArr[0]['issuedShipNum'];//已下发发货数量
				$planNum = $planNum - $val['number'];//减去本次发货的数量
				$contRemain = $contNum - $planNum;
//				if( $contRemain <= 0 ){
//					continue;
//				}
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="outplan[details]['.$j.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="outplan[details]['.$j.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .= <<<EOT
					<tr><td>$j</td>
<!--						<td>
							<input type="hidden" id="productLine$j" name="outplan[details][$j][productLine]" value="$val[productLine]" class='readOnlyTxtItem' readonly/>
							<input type="text" id="productLineName$j" name="outplan[details][$j][productLineName]" value="$val[productLineName]" class='readOnlyTxtItem' readonly/></td>
-->						<td>
							<input type="text" id="productNo$j" readonly="true" name="outplan[details][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="hidden" id="contEquId$j" name="outplan[details][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[details][$j][productId]" value="$val[productId]"/>
							<input type="text" id="productName$j" name="outplan[details][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>
							<input type="text" id="productModel$j" name="outplan[details][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>
							<input type="text" name="outplan[details][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>
							<input type="hidden" id="stockId$j" name="outplan[details][$j][stockId]" value="$val[stockId]" class='txtmiddle'/>
							<input type="hidden" id="stockCode$j" name="outplan[details][$j][stockCode]" value="$val[stockCode]" class='txtmiddle'/>
							<input type="text" id="stockName$j" name="outplan[details][$j][stockName]" value="$val[stockName]" class='txtmiddle'/></td>
						<td>
							<font color=green>$contNum</font>
							<input type="hidden" id="remain$j" value='$contNum' class="txtmiddle"/>
						</td>
						<td>
							<font color=green>$planNum</font>
							<input type="hidden" id="contRemain$j" value='$contRemain' class="txtmiddle"/>
							<input type="hidden" id="planNum$j"  name="outplan[details][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>
							<input type="text" name="outplan[details][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<font color=green>$val[executedNum]</font></td>
						<td width="4%">$isPresent</td>
						<!--td>
							<input type="text" id="lockNum" name="outplan[details][$j][lockNum]" class='txtshort' value="$val[lockNum]"/>
						</td-->
                        <td>
							<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>即时库存</font></a>
                        </td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}


		}
		$str .='<input type="hidden" id="rowNum" value="'.$j.'"/>';
		return $str;
	}

	/**
	 * @description 查看发货通知时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$planDao = new model_stock_outplan_outplan();
			foreach ( $rows as $key => $val ) {
				if( $rows[$key]['contEquId'] ){
					$isSellSql = " select isSell from oa_lease_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$isSellSql = " select isSell from oa_lease_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$isSellArr = $planDao->_db->getArray( $isSellSql );
				$isPresent = $isSellArr[0]['isSell'];
				if(empty($isPresent)){
					$isPresent = "<font color='red'>是</font>";
				}else{
					$isPresent = "<font>否</font>";
				}
				$j = $i + 1;
				$str .= <<<EOT
						<tr height="30px">
							<td width="8%">$j<input type='hidden' name='isRed' id='isRed$j' value="$val[isRed]"/></td>
<!--							<td>$val[productLineName]</td>-->
							<td>$val[productNo]<input type='hidden' name='isDelete' id='isDelete$j' value="$val[isDelete]"/></td>
							<td>$val[productName]</td>
							<td>$val[productModel]</td>
							<td>$val[stockName]</td>
							<td width="8%">$val[number]</td>
							<td width="8%">$val[unitName]</td>
							<td><font color=green>$val[executedNum]</font></td>
							<td width="8%">$isPresent</td>
							<!--td width="15%">$val[lockNum]</td-->
	                        <td>
								<a href="#" onclick="showOpenWin('index1.php?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700');"><font color=blue>即时库存</font></a>
	                        </td>
						</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11">暂无相关信息</td></tr>';
			}

		}
		$str .= "<input type='hidden' id='rowNum' value='" . $j . "'/>";
		return $str;
	}


	/**
	 * 借用转销售发货计划从表渲染
	 */
	 function shwoBToOEqu($rows,$rowNum){
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//合同数量 及 合同已下达发货数量
				if( $rows[$key]['contEquId'] ){
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				$contNum = $contNumArr[0]['contNum'];//合同数量
				$planNum =  $contNumArr[0]['issuedShipNum'];//已下发发货数量
				$planNum = $planNum - $val['number'];//减去本次发货的数量
				$contRemain = $contNum - $planNum;
//				if( $contRemain <= 0 ){
//					continue;
//				}
				$oldIdStr='';
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo_$j" readonly="true" name="outplan[bItem][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[productName]
							<input type="hidden" id="contEquId_$j" name="outplan[bItem][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId_$j" name="outplan[bItem][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName_$j" name="outplan[bItem][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel_$j" name="outplan[bItem][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[bItem][$j][unitName]" id="unitName_$j" value="$val[unitName]" class='readOnlyTxtShort' readonly/>
							<input type="hidden" id="planNum_$j"  name="outplan[bItem][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[bItem][$j][number]" id="number_$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydelBorrow(this,"borrowbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}else{
				$str .='<input type="hidden" id="borrowNum" value="'.$j.'"/>';
			}

		}
		return $str;
	}


	/**
	 * 借用转销售发货计划从表渲染
	 */
	 function shwoBToOEquChange($rows,$rowNum){
		$j = 0;
		if (is_array ( $rows )) {
			$planDao = new model_stock_outplan_outplan();
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				//合同数量 及 合同已下达发货数量
				if( $rows[$key]['contEquId'] ){
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and id=" . $rows[$key]['contEquId'];
				}else{
					$contNumSql = " select number as contNum,issuedShipNum,isSell from oa_sale_order_equ where orderId=" . $rows[$key]['docId']
						. " and productId=" . $rows[$key]['productId'];
				}
				$contNumArr = $planDao->_db->getArray( $contNumSql );
				$contNum = $contNumArr[0]['contNum'];//合同数量
				$planNum =  $contNumArr[0]['issuedShipNum'];//已下发发货数量
				$planNum = $planNum - $val['number'];//减去本次发货的数量
				$contRemain = $contNum - $planNum;
//				if( $contRemain <= 0 ){
//					continue;
//				}
				$oldIdStr='';
				if(empty($val['originalId'])){
					$oldIdStr.='<input type="hidden" name="outplan[bItem]['.$j.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$oldIdStr.='<input type="hidden" name="outplan[bItem]['.$j.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .= <<<EOT
					<tr><td>$j</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo_$j" readonly="true" name="outplan[bItem][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[productName]
							<input type="hidden" id="contEquId_$j" name="outplan[bItem][$j][contEquId]" value="$val[contEquId]"/>
							$oldIdStr
							<input type="hidden" id="productId_$j" name="outplan[bItem][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName_$j" name="outplan[bItem][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel_$j" name="outplan[bItem][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[bItem][$j][unitName]" id="unitName_$j" value="$val[unitName]" class='readOnlyTxtShort' readonly/>
							<input type="hidden" id="planNum_$j"  name="outplan[bItem][$j][issuedShipNum]" value='$planNum' class="txtmiddle"/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[bItem][$j][number]" id="number_$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydelBorrow(this,"invbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}else{
				$str .='<input type="hidden" id="borrowNum" value="'.$j.'"/>';
			}

		}
		return $str;
	}


	/**
	 * 借用转销售发货计划从表渲染
	 */
	 function shwoBToOEquView($rows,$rowNum){
		$j = 0;
		if (is_array ( $rows )) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$j = $i + 1;
				$line=$j-$rowNum;
				$str .= <<<EOT
					<tr><td>$i</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo_$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
							<input type="hidden" id="BToOTips_$j" name="outplan[productsdetail][$j][BToOTips]" value='1' class="txtmiddle"/></td>
						<td>$val[productName]
							<input type="hidden" id="contEquId_$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId_$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName_$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel_$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[productsdetail][$j][unitName]" id="unitName_$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[productsdetail][$j][number]" id="number_$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
					</tr>
EOT;
				$i ++;
			}
			if( $i == 0 ){
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}else{
				$str .='<input type="hidden" id="borrowNum" value="'.$j.'"/>';
			}
		}
		return $str;
	}

/*************************************************************************************************************************************************************/



	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo( $paramArr,$skey ) {
		$str .= ' <img src="images\icon\view.gif" onclick="showOpenWin(\'?model=contract_rental_rentalcontract&action=toViewTab&id='.$paramArr['docId'].'&perm=view&skey='.$skey.'\')">';
		return $str;
	}

	/**
	 * 新增发货通知时处理相关业务
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$outplanDao = new model_stock_outplan_outplan();
		foreach ( $relItemArr as $key=>$vqal ){
			$sql = " update oa_lease_equ set issuedShipNum = issuedShipNum+" . $relItemArr[$key]['number'] . " where id=" . $relItemArr[$key]['contEquId'] . " and orderId=" . $paramArr['docId'];
			$outplanDao->_db->query( $sql );
		}
	}
	/**
	 * 修改发货通知时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
	}

	/**
	 * 删除发货通知时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtDel($id) {
		$outplanDao = new model_stock_outplan_outplan();
		$sql = "update oa_lease_equ oe left join oa_stock_outplan_product op on oe.id = op.contEquId ".
				"set oe.issuedShipNum=oe.issuedShipNum-op.number where op.mainId=" . $id;
		$outplanDao->_db->query( $sql );
	}


	/**
	 * 变更发货通知时处理相关业务
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtChange($paramArr = false, $relItemArr = false) {
		$outplanDao = new model_stock_outplan_outplan();
		foreach ( $relItemArr as $key=>$vqal ){
			if( $vqal['isDel']==1 ){
				$sql = " update oa_lease_equ set issuedShipNum = " . $relItemArr[$key]['issuedShipNum'] . " where id=" . $relItemArr[$key]['contEquId'] . " and orderId=" . $paramArr['docId'];
				$outplanDao->_db->query( $sql );
			}else{
				$issuedShipNum = $relItemArr[$key]['issuedShipNum'] + $relItemArr[$key]['number'];
				$sql = " update oa_lease_equ set issuedShipNum = " . $issuedShipNum . " where id=" . $relItemArr[$key]['contEquId'] . " and orderId=" . $paramArr['docId'];
				$outplanDao->_db->query( $sql );
			}
		}
	}


	/**
	 * 下推获取源单数据方法
	 */
	 function getDocInfo( $id ){
	 	$docDao = new model_contract_rental_rentalcontract();
	 	$rows = $docDao -> getShip_d( $id );
	 	if(!isset($rows['address'])){
	 		$rows['address'] = '';
	 	}
		if( trim($rows['orderCode']) == '' ){
			$rows['planDocCode'] = $rows['orderTempCode'];
			$rows['orderequ'] = $rows['rentalcontractequ'];
			unset( $rows['rentalcontractequ'] );
		}else{
			$rows['planDocCode'] = $rows['orderCode'];
			$rows['orderequ'] = $rows['rentalcontractequ'];
			unset( $rows['rentalcontractequ'] );
		}
		if(isset($rows['tenant'])){
			$rows['customerName'] = $rows['tenant'];
			$rows['customerId'] = $rows['tenantId'];
			unset( $rows['tenant'] );
			unset( $rows['tenantId'] );
		}
		$rows['docApplicant'] = $rows['hiresName'];
		$rows['docApplicantId'] = $rows['hiresId'];
		$rows['planDocName'] = $rows['orderName'];
		$rows['planDocId'] = $rows['id'];
		return $rows;
	 }

	/**
	 * 获取合同负责人方法
	 */
	 function getSaleman( $id ){
	 	$docDao = new model_contract_rental_rentalcontract();
	 	$tablename=$docDao->tbl_name;
	 	$condition="id=".$id;
	 	$salemanKey = "hiresName as responsible,hiresId as responsibleId";
	 	$sql="select $salemanKey from $tablename where $condition";
	 	$salemanArr = $docDao->_db->getArray($sql);
		return $salemanArr[0];
	 }


	 /**
	  * 根据发货情况改变合同状态
	  */
	  function dealupdateOrderShipStatus_d( $id ){
	  	$docDao = new model_contract_rental_rentalcontract();
	  	$docDao->updateOrderShipStatus_d( $id );
	  }


	  function updateContNumAsOut($planInfo,$rows){
	  	$outplan = new model_stock_outplan_outplan();
	  	$contDao = new model_contract_rental_rentalcontract();
	  	//更新合同已执行数量
		$contSql = " update oa_lease_equ set executedNum = executedNum+" . $rows['outNum'] . " where orderId= " . $planInfo['docId'] . " and id= " . $rows['contEquId'];
		$contDao->_db->query( $contSql );

	  	//查看发货计划未发货数量
	 	$planRemainSql = "select count(*) as countNum,(select  sum(o.executedNum) from oa_stock_outplan_product o
		where o.mainId=" .$rows['relDocId']. " and o.isDelete=0) as executedNum from (select  (o.number-o.executedNum)
		 as remainNum,o.executedNum from oa_stock_outplan_product o  where o.mainId=" .$rows['relDocId']. " and o.isDelete=0) c where c.remainNum>0";
	 	$remainNum = $contDao->_db->getArray( $planRemainSql );
	  	//查看计划发货日期
	 	$shipPlanDateSql = " select shipPlanDate from oa_stock_outplan where id=" . $rows['relDocId'];
	 	$shipPlanDate = $contDao->_db->getArray( $shipPlanDateSql );
	 	if( $remainNum[0]['countNum']>0 && $remainNum[0]['executedNum']==0 ){
	 		$docStatus = 'WFH';
	 		$shipPlanDateTemp = strtotime($shipPlanDate[0]['shipPlanDate'])*1;
	 		$shipDateTemp = strtotime("now")*1;
	 		if( $shipPlanDateTemp - $shipDateTemp >= 0 ){
	 			$isShipped = 1;
	 			$isOnTime = 1;
	 		}else{
	 			$isShipped = 0;
	 			$isOnTime = 0;
	 		}
	 	}elseif( $remainNum[0]['countNum'] <= 0 ){
	 		$docStatus = 'YWC';
	 		$shipPlanDateTemp = strtotime($shipPlanDate[0]['shipPlanDate'])*1;
	 		$shipDateTemp = strtotime("now")*1;
	 		if( $shipPlanDateTemp - $shipDateTemp >= 0 ){
	 			$isShipped = 1;
	 			$isOnTime = 1;
	 		}else{
	 			$isShipped = 0;
	 			$isOnTime = 0;
	 		}
	 	}else{
	 		$docStatus = 'BFFH';
	 	}
		// 根据合同Id修改合同状态。
		$contDao->updateOrderShipStatus_d( $planInfo['docId'] );
	 	$statusInfo = array(
	 		'id' => $rows['relDocId'],
	 		'docStatus' => $docStatus,
	 		'isShipped' => $isShipped,
	 		'isOnTime' => $isOnTime
	 	);
	 	$outplan->updateById( $statusInfo );
	  }


	/**
	 * 红色蓝色出库审核/反审统一接口
	 */
	  function updateContNumAsOutRed($contProInfo,$rows){
	  	$contDao = new model_contract_rental_rentalcontract();
	  	//更新合同已执行数量
		$contSql = " update oa_lease_equ set executedNum = executedNum+" . $rows['outNum'] . " ,backNum=backNum-".$rows['outNum']." where orderId= " . $contProInfo['docId'] . " and id= " . $rows['contEquId'];
		$contDao->_db->query( $contSql );
		// 根据合同Id修改合同状态。
		$contDao->updateOrderShipStatus_d( $contProInfo['docId'] );
	  }


}
?>