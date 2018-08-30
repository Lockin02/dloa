<?php
/**
 * @author LiuBo
 * @Date 2011年3月4日 15:13:34
 * @version 1.0
 * @description:订单收款计划 Model层
 */
 class model_projectmanagent_order_receiptplan  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_order_receiptplan";
		$this->sql_map = "projectmanagent/order/receiptplanSql.php";
		parent::__construct ();
	}


	/**
	 * 渲染查看页面内从表
	 */
	function initTableView($object){

		$str = "";
		$i = 0;
		foreach($object as $key => $val ){
			$i ++ ;
				$str .=<<<EOT
					<tr>
						<td width="5%">$i</td>
						<td class="formatMoney">$val[money]</td>
						<td>$val[payDT]</td>
						<td>$val[pType]</td>
						<td>$val[collectionTerms]</td>


					</tr>
EOT;
		}
		return $str;
	}
    /**
     * 渲染编辑页面从表
     */
     function initTableEdit($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$value1 = $value2 = $value3 = $value4 = $value5 = "";
				if($val['pType']=="电汇") {
					$value1 = "selected";
				}elseif($val['pType']=="现金"){
					$value2 = "selected";
				}elseif($val['pType']=="银行汇票") {
					$value3 = "selected";
				}else{
					$value4 = "selected";
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][money]" id="PayMoney$i" value="$val[money]" size="10" class="txtshort formatMoney" maxlength="40"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="order[receiptplan][$i][payDT]" id="PayDT$i"  size="12" onfocus="WdatePicker()" value="$val[payDT]">
					    </td>
					 	<td>
							<select name="order[receiptplan][$i][pType]" id="PayStyle$i" class="txtshort">
								<option value="电汇" $value1>电汇</option>
								<option value="现金" $value2>现金</option>
								<option value="银行汇票" $value3>银行汇票</option>
								<option value="商业汇票" $value4>商业汇票</option>
							</select>
					    </td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][collectionTerms]" id="collectionTerms$i" value="$val[collectionTerms]" size="70" maxlength="70" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mypay')" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}

	/**变更动态列表
	*author can
	*2011-6-1
	*/
     function initTableChange($rows){
		$i = 0;
		$str = "";
		if ($rows) {
			foreach ($rows as $val) {
				$i++;
				$value1 = $value2 = $value3 = $value4 = $value5 = "";
				if($val['pType']=="电汇") {
					$value1 = "selected";
				}elseif($val['pType']=="现金"){
					$value2 = "selected";
				}elseif($val['pType']=="银行汇票") {
					$value3 = "selected";
				}else{
					$value4 = "selected";
				}
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="order[receiptplan]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="order[receiptplan]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}
				$str .=<<<EOT
					<tr align="center">
					 	<td>$i
					 	</td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][money]" id="PayMoney$i" value="$val[money]" size="10" class="txtshort formatMoney" maxlength="40"/>
					 	</td>
					 	<td>
					        <input type="text" class="txtshort" name="order[receiptplan][$i][payDT]" id="PayDT$i"  size="12" onfocus="WdatePicker()" value="$val[payDT]">
					    </td>
					 	<td>
							<select name="order[receiptplan][$i][pType]" id="PayStyle$i" class="txtshort">
								<option value="电汇" $value1>电汇</option>
								<option value="现金" $value2>现金</option>
								<option value="银行汇票" $value3>银行汇票</option>
								<option value="商业汇票" $value4>商业汇票</option>
							</select>
					    </td>
					 	<td>
					 		<input type="text" name="order[receiptplan][$i][collectionTerms]" id="collectionTerms$i" value="$val[collectionTerms]" size="70" maxlength="70" class="txtlong"/>
					 	</td>
					 	<td width="5%">
					 		<img src="images/closeDiv.gif" onClick="mydel(this,'mypay','receiptplan')" title="删除行">
					 	</td>
					 </tr>
EOT;
			}
		}
		return array($i,$str);
	}
/*******************************页面显示层*********************************/

	/**
	 * 根据项目id获取产品列表
	 */
	function getDetail_d($orderId){
		$this->searchArr['orderID'] = $orderId;
		return $this->list_d();
	}
 }
?>