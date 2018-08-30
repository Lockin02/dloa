<?php
/**
 * @author Administrator
 * @Date 2012年12月14日 星期五 15:18:00
 * @version 1.0
 * @description:订单供应商_报价单物料清单 Model层
 */
 class model_purchase_contract_applysuppequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purch_apply_suppequ";
		$this->sql_map = "purchase/contract/applysuppequSql.php";
		parent::__construct ();
	}

	/**供应商_产品清单
	 * @param $list--询价物料数组
	*/
	function productAddList($list,$uniqueList){
		$str="";
		$i=0;
		$j=0;
		if($list){
			foreach($uniqueList as $uqKey=>$uqVal){
				if(!isset($uqVal['productNumb'])) continue;// 过滤掉物料为空的数据
				$j++;
				$strTab="";
				$totalNum=0;
				foreach($list as $key=>$val){
					if($uqVal['productNumb']==$val['productNumb']){
						$deliveryDate= date("Y-m-d");
						$i++;
						$totalNum=$totalNum+$val[amountAll];
						$strTab.=<<<EOT
							<tr>
								<td  width="10%">
									$val[amountAll]
									<input type="hidden"  id="" name="applysupp[applysuppequ][$i][productNumb]" value="$val[productNumb]"/>
								    <input type="hidden" class="" id="" name="applysupp[applysuppequ][$i][productName]" value="$val[productName]"/>
								    <input type="hidden" class="txtshort" id="" name="applysupp[applysuppequ][$i][productId]" value="$val[productId]"/>
									<input type="hidden" class="readOnlyTxt" id="" name="applysupp[applysuppequ][$i][units]" value="$val[units]"/>
									<input type="hidden" class="readOnlyTxt" id="" name="applysupp[applysuppequ][$i][pattem]" value="$val[pattem]"/>
									<input type="hidden" id="" name="applysupp[applysuppequ][$i][purchType]" value="$val[purchType]"/>
									<input type="hidden" class="readOnlyTxt" id="amount$i" name="applysupp[applysuppequ][$i][amount]" onblur="countDetail(this)" value="$val[amountAll]"/>
								</td>
								<td  width="20%">
									<input type="hidden" class="txtshort  price$j" id="price$i" onblur="countDetail(this)" name="applysupp[applysuppequ][$i][price]"/>
									<input type="text" class="txtshort formatMoneySix priceView$j" id="price$i"  onblur="countDetail(this)" />
								</td>
								<td  width="20%">

									<select type="text" class="taxRate" id="taxRate$i"  name="applysupp[applysuppequ][$i][taxRate]" >{taxRate}</select>
									<input type="hidden" class="txtshort formatMoney" id="tax$i" onblur="countDetail(this)" name="applysupp[applysuppequ][$i][tax]"/>
								</td>
								<td  width="20%"><input type="text" class="txtshort deliveryDate" id="" name="applysupp[applysuppequ][$i][deliveryDate]"  value="$deliveryDate" onfocus="WdatePicker()" readonly/></td>
								<td  width="20%">
									<select id="transportation$i" class="txtshort" name="applysupp[applysuppequ][$i][transportation]">{transportation}</select>
									<input type="hidden" id="" name="applysupp[applysuppequ][$i][applyEquId]" value="$val[id]"/>
									<input type="hidden" id="" name="applysupp[applysuppequ][$i][takeEquId]" value="$val[taskEquId]"/>
									<input type="hidden" id="" name="applysupp[applysuppequ][$i][batchNumb]" value="$val[batchNumb]"/>
								</td>
				</tr>

EOT;

					}
				}
				$str.=<<<EOT
					<tr>
						<td>$j</td>
						<td>
							$uqVal[productNumb]<br>
					    	$uqVal[productName]
						</td>
						<td>
							$uqVal[units]
						</td>
						<td>
							$totalNum
						</td>
						<td>
							已下订单数量：<span style='color:blue'>$uqVal[amount]</span><br>
							$uqVal[referPrice]
						</td>
					    <td>
					    	<input type="text" class="txtshort formatMoneySix" id="$j" onblur="setTaxPrice(this,$j);countDetail(this)" />
					    </td>
				        <td width="50%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
						</td>
		</tr>

EOT;


			}
		}
		return $str.'|'.$i;
	}
	/**新增报价单，再选择查看或修改,新模板
	 * @param $list--供应商报价物料数组
	*/
	function prosEditNewList($list,$uniqueList){
		$str="";
		$i=0;
		$j=0;
		if($list){
			foreach($uniqueList as $uqKey=>$uqVal){
				if(!isset($uqVal['productNumb'])) continue;// 过滤掉物料为空的数据
				$j++;
				$strTab="";
				$totalNum=0;
				foreach($list as $key=>$val){
					if($uqVal['productNumb']==$val['productNumb']){
						$i++;
						$totalNum=$totalNum+$val[amount];
						$strTab.=<<<EOT
						<tr>
							<td>
								$val[amount]
								<input type="hidden" class="readOnlyTxt" id="" name="applysupp[applysuppequ][$i][units]" value="$val[units]"/>
								<input type="hidden" class="readOnlyTxt" id="" name="applysupp[applysuppequ][$i][pattem]" value="$val[pattem]" readonly/>
							    <input type="hidden" class="" id="" name="applysupp[applysuppequ][$i][productName]" value="$val[productName]" readonly/>
								<input type="hidden" class="txtshort" id="" name="applysupp[applysuppequ][$i][productId]" value="$val[productId]"/>
								<input type="hidden" class="txtshort" id="" name="applysupp[applysuppequ][$i][productNumb]" value="$val[productNumb]" readonly/>
								<input type="hidden" id="" name="applysupp[applysuppequ][$i][purchType]" value="$val[purchType]"/>
								<input type="hidden" id="" name="applysupp[applysuppequ][$i][units]" value="$val[units]"/>
								<input type="hidden" class="readOnlyTxt" id="amount$i" name="applysupp[applysuppequ][$i][amount]" onblur="countDetail(this)" value="$val[amount]"/>
							</td>
							<td>
								<input type="hidden" class="txtshort  price$j" id="price$i" onblur="countDetail(this)" name="applysupp[applysuppequ][$i][price]"  value="$val[price]"/>
								<input type="text" class="txtshort formatMoneySix priceView$j" id="price$i"  value="$val[price]"  onblur="countDetail(this)"/>
							</td>
							<td>
								<select class="taxRate" name="applysupp[applysuppequ][$i][taxRate]">{taxRate$i}</select>
							</td>
							<td><input type="text" class="txtshort deliveryDate" id="" name="applysupp[applysuppequ][$i][deliveryDate]"  onfocus="WdatePicker()" value="$val[deliveryDate]" readonly/></td>
							<td>
								<select  name="applysupp[applysuppequ][$i][transportation]">{transportation$i}</select>
								<input type="hidden" id="" name="applysupp[applysuppequ][$i][applyEquId]" value="$val[applyEquId]"/>
								<input type="hidden" id="" name="applysupp[applysuppequ][$i][takeEquId]" value="$val[takeEquId]"/>
								<input type="hidden" id="" name="applysupp[applysuppequ][$i][batchNumb]" value="$val[batchNumb]"/>
							</td>
			</tr>
EOT;

					}
				}
				$str.=<<<EOT
					<tr>
						<td>$j</td>
						<td>
							$uqVal[productNumb]<br>
					    	$uqVal[productName]
						</td>
						<td>
							$uqVal[units]
						</td>
						<td>
							$totalNum
						</td>
						<td>
							已下订单数量：<span style='color:blue'>$uqVal[amount]</span><br>
							$uqVal[referPrice]
						</td>
					    <td>
					    	<input type="text" class="txtshort formatMoneySix" id="$j" onblur="setTaxPrice(this,$j);countDetail(this)" value="$uqVal[price]"/>
					    </td>
				        <td width="50%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
						</td>
		</tr>

EOT;


			}
		}
		return $str.'|'.$i;
	}

   /**供应商_产品清单
	*author can
	*2010-12-31
	* @param $list--供应商报价物料数组
	*/
	function productViewList($list,$uniqueList){
		$datadictDao=new model_system_datadict_datadict ();
		$str="";
		$i=0;
		$j=0;
		if($list){
			foreach($uniqueList as $uqKey=>$uqVal){
				if(!isset($uqVal['productNumb'])) continue;// 过滤掉物料为空的数据
				$j++;
				$strTab="";
				$totalNum=0;
				foreach($list as $key=>$val){
					if($uqVal['productNumb']==$val['productNumb']){
		    			$val['transportation']= $datadictDao->getDataNameByCode($val['transportation']);
						$deliveryDate= date("Y-m-d");
						$i++;
						$totalNum=$totalNum+$val[amount];
						$strTab.=<<<EOT
							<tr>
								<td  width="10%">
									$val[amount]
								</td>
								<td  width="20%" class="formatMoneySix">$val[price]</td>
								<td width="20%" class="formatMoney">$val[taxRate]</td>
								<td  width="20%">$val[deliveryDate]</td>
								<td  width="20%">
									$val[transportation]
								</td>
						</tr>

EOT;

					}
				}
				$str.=<<<EOT
					<tr>
						<td>$j</td>
						<td>
							$uqVal[productNumb]<br>
					    	$uqVal[productName]
						</td>
						<td>
							$uqVal[units]
						</td>
						<td>
							$totalNum
						</td>
						<td>
							已下订单数量：<span style='color:blue'>$uqVal[amount]</span><br>
							$uqVal[referPrice]
						</td>
						<td class="formatMoneySix">
							$uqVal[price]
						</td>
				        <td width="50%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
						</td>
		</tr>

EOT;
			}
		}else{
			$str="<tr><td colspan='11'>暂无报价物料</td></tr>";
		}
		return $str;
	}
	/*****************************************显示分割线********************************************/
	/**获取供应商物料信息
	*author can
	*2011-1-1
	 * @param $parentId--供应商ID
	*/
	function getProByParentId($parentId){
	 	return $this->findAll(array('parentId'=>$parentId));
	 }
	/**
	 * 根据询价单id获取询价单设备清单,去除重复
	 **/
	 function getUniqueByParentId($parentId){
		$searchArr = array (
			"parentId" => $parentId
		);
		$this->__SET('sort', "c.id");
		$this->__SET('asc', false);
		$this->__SET('groupBy',"c.productNumb");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("");
		return $rows;
	 }

	 /*
	  * 获取物料的价格相关字符串
	  */
	function getPriceArr($materialObj ,$materialequObj ,$materialequObj1) {
		if ($materialObj['protocolType'] == '协议价格') {
			return $materialObj['protocolType']."：<span style='color:blue'>".$materialequObj['taxPrice']."</span>";
		}else if ($materialequObj['lowerNum'] == 0 && $materialequObj['ceilingNum'] == 0){
			return '暂无参考价格';
		}else {
			if ($materialequObj['id'] == $materialequObj1['id']) {
				return "数量范围：".$materialequObj['lowerNum']." ~ ".$materialequObj['ceilingNum']."：<span style='color:blue'>".$materialequObj['taxPrice']."</span>";
			}else {
				return "数量范围：".$materialequObj['lowerNum']." ~ ".$materialequObj['ceilingNum']."：<span style='color:blue'>".$materialequObj['taxPrice']."</span><br>数量范围：".$materialequObj1['lowerNum']." ~ ".$materialequObj1['ceilingNum']."：<span style='color:blue'>".$materialequObj1['taxPrice']."</span>";
			}
		}
	}
 }
?>