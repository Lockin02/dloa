<?php
/**
 * @description: 询价单供应商产品Model
 * @date 2010-12-24 上午10:08:29
 * @author oyzx
 * @version V1.0
 */
 header("Content-type: text/html; charset=gb2312");
class model_purchase_inquiry_inquirysupppro extends model_base{

	function __construct() {
		$this->tbl_name = "oa_purch_inquiry_suppequ";
		$this->sql_map = "purchase/inquiry/inquirysuppproSql.php";
		parent :: __construct();
	}

	/**供应商_产品清单
	*author can
	*2010-12-31
	 * @param $list--询价物料数组
	*/
	function productAddList($list,$uniqueList){
		$str="";
		$i=0;
		$j=0;
		if($list){
			foreach($uniqueList as $uqKey=>$uqVal){
				$j++;
				$strTab="";
				$totalNum=0;
				foreach($list as $key=>$val){
					if($uqVal['productNumb']==$val['productNumb']){
						$deliveryDate= date("Y-m-d");
						$i++;
						$totalNum=$totalNum+$val[amount];
						$strTab.=<<<EOT
							<tr>
								<td  width="10%">
									$val[amount]
									<input type="hidden"  id="" name="inquirysupp[inquirysupppro][$i][productNumb]" value="$val[productNumb]"/>
								    <input type="hidden" class="" id="" name="inquirysupp[inquirysupppro][$i][productName]" value="$val[productName]"/>
								    <input type="hidden" class="txtshort" id="" name="inquirysupp[inquirysupppro][$i][productId]" value="$val[productId]"/>
									<input type="hidden" class="readOnlyTxt" id="" name="inquirysupp[inquirysupppro][$i][units]" value="$val[units]"/>
									<input type="hidden" class="readOnlyTxt" id="" name="inquirysupp[inquirysupppro][$i][pattem]" value="$val[pattem]"/>
									<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][auxiliary]" value="$val[auxiliary]"/>
									<input type="hidden" class="readOnlyTxt" id="amount$i" name="inquirysupp[inquirysupppro][$i][amount]" onblur="countDetail(this)" value="$val[amount]"/>
								</td>
								<td  width="20%">
									<input type="hidden" class="txtshort  price$j" id="price$i" onblur="countDetail(this)" name="inquirysupp[inquirysupppro][$i][price]"/>
									<input type="text" class="txtshort formatMoneySix priceView$j" id="price$i"  onblur="countDetail(this)" />
								</td>
								<td  width="20%">

									<select type="text" class="taxRate" id="taxRate$i"  name="inquirysupp[inquirysupppro][$i][taxRate]" >{taxRate}</select>
									<input type="hidden" class="txtshort formatMoney" id="tax$i" onblur="countDetail(this)" name="inquirysupp[inquirysupppro][$i][tax]"/>
								</td>
								<td  width="20%"><input type="text" class="txtshort deliveryDate" id="" name="inquirysupp[inquirysupppro][$i][deliveryDate]"  value="$deliveryDate" onfocus="WdatePicker()" readonly/></td>
								<td  width="20%">
									<select id="transportation$i" class="txtshort" name="inquirysupp[inquirysupppro][$i][transportation]">{transportation}</select>
									<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][inquiryEquId]" value="$val[id]"/>
									<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][takeEquId]" value="$val[taskEquId]"/>
									<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][batchNumb]" value="$val[batchNumb]"/>
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

	/**新增报价单，再选择查看或修改
	*author can
	*2011-1-3
	 * @param $list--供应商报价物料数组
	*/
	function prosEditList($list){
	$str="";
	$i=0;
	if($list){
		foreach($list as $key=>$val){
			$i++;
			$str.=<<<EOT
				<tr>
					<td>$i</td>
					<td>
						$val[productNumb]
						<input type="hidden" class="txtshort" id="" name="inquirysupp[inquirysupppro][$i][productNumb]" value="$val[productNumb]" readonly/>
					</td>
				    <td>
				    	$val[productName]
					    <input type="hidden" class="" id="" name="inquirysupp[inquirysupppro][$i][productName]" value="$val[productName]" readonly/>
						<input type="hidden" class="txtshort" id="" name="inquirysupp[inquirysupppro][$i][productId]" value="$val[productId]"/>
				    </td>
					<td>
						$val[pattem]
						<input type="hidden" class="readOnlyTxt" id="" name="inquirysupp[inquirysupppro][$i][pattem]" value="$val[pattem]" readonly/>
					</td>
					<td>
						$val[units]
						<input type="hidden" class="readOnlyTxt" id="" name="inquirysupp[inquirysupppro][$i][units]" value="$val[units]"/>
					</td>
					<td>
						$val[amount]
						<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][auxiliary]" value="$val[auxiliary]"/>
						<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][units]" value="$val[units]"/>
						<input type="hidden" class="readOnlyTxt" id="amount$i" name="inquirysupp[inquirysupppro][$i][amount]" onblur="countDetail(this)" value="$val[amount]"/>
					</td>
					<td><input type="text" class="txtshort formatMoneySix" id="price$i" name="inquirysupp[inquirysupppro][$i][price]" value="$val[price]" onblur="countDetail(this)"/></td>
					<td>
						<select class="taxRate" name="inquirysupp[inquirysupppro][$i][taxRate]">{taxRate$i}</select>
					</td>
					<td><input type="text" class="txtshort deliveryDate" id="" name="inquirysupp[inquirysupppro][$i][deliveryDate]"  onfocus="WdatePicker()" value="$val[deliveryDate]" readonly/></td>
					<td>
						<select  name="inquirysupp[inquirysupppro][$i][transportation]">{transportation$i}</select>
						<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][inquiryEquId]" value="$val[inquiryEquId]"/>
						<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][takeEquId]" value="$val[takeEquId]"/>
					</td>
					<!--
					<td>
						<img src="images/closeDiv.gif" onclick="mydel(this , 'mytable')" title="删除行" />
					</td>
					 -->
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
								<input type="hidden" class="readOnlyTxt" id="" name="inquirysupp[inquirysupppro][$i][units]" value="$val[units]"/>
								<input type="hidden" class="readOnlyTxt" id="" name="inquirysupp[inquirysupppro][$i][pattem]" value="$val[pattem]" readonly/>
							    <input type="hidden" class="" id="" name="inquirysupp[inquirysupppro][$i][productName]" value="$val[productName]" readonly/>
								<input type="hidden" class="txtshort" id="" name="inquirysupp[inquirysupppro][$i][productId]" value="$val[productId]"/>
								<input type="hidden" class="txtshort" id="" name="inquirysupp[inquirysupppro][$i][productNumb]" value="$val[productNumb]" readonly/>
								<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][auxiliary]" value="$val[auxiliary]"/>
								<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][units]" value="$val[units]"/>
								<input type="hidden" class="readOnlyTxt" id="amount$i" name="inquirysupp[inquirysupppro][$i][amount]" onblur="countDetail(this)" value="$val[amount]"/>
							</td>
							<td>
								<input type="hidden" class="txtshort  price$j" id="price$i" onblur="countDetail(this)" name="inquirysupp[inquirysupppro][$i][price]"  value="$val[price]"/>
								<input type="text" class="txtshort formatMoneySix priceView$j" id="price$i"  value="$val[price]"  onblur="countDetail(this)"/>
							</td>
							<td>
								<select class="taxRate" name="inquirysupp[inquirysupppro][$i][taxRate]">{taxRate$i}</select>
							</td>
							<td><input type="text" class="txtshort deliveryDate" id="" name="inquirysupp[inquirysupppro][$i][deliveryDate]"  onfocus="WdatePicker()" value="$val[deliveryDate]" readonly/></td>
							<td>
								<select  name="inquirysupp[inquirysupppro][$i][transportation]">{transportation$i}</select>
								<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][inquiryEquId]" value="$val[inquiryEquId]"/>
								<input type="hidden" id="" name="inquirysupp[inquirysupppro][$i][takeEquId]" value="$val[takeEquId]"/>
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
		$this->__SET('sort', "p.productName");
		$this->__SET('groupBy',"p.productNumb");
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list");
		return $rows;
	 }


	/*
	 * @desription 获取供应商的报价
	 * @param $suppId	供应商ID
	 * @author qian
	 * @date 2011-1-10 下午04:35:58
	 */
	function getSuppInquiry_d ($suppId) {
		$condiction = array('parentId'=>$suppId);
		$this->searchArr = $condiction;
		$arr = $this->list_d( "list_basicEqu" );
		foreach( $arr as $key => $val ){
			$arr[$key]['taskEquId'] = $arr[$key]['takeEquId'];
		}
		return $arr;

	}

	 /**
	 * 获取询价物料ID信息
	 *@param $ieId 询价物料Id
	 */
	 function getSuppEqu_d($ieId){
			$this->searchArr ['ieId'] =$ieId;
			$this->groupBy="ise.id";
			$suppEqu=$this->listBySqlId("inquiry_suppequ_list");//获取报价信息
			return $suppEqu;
	 }
}
?>
