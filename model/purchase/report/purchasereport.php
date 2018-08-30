<?php
class model_purchase_report_purchasereport extends model_base {

	function __construct() {
		//$this->tbl_name = "oa_stock_baseinfo";
		//$this->sql_map = "stock/stockinfo/stockinfoSql.php";
		parent::__construct ();
	}

	/**
	 * 价格缓存数据初始化
	 */
	function initData_d(){
		//获取上个月的日期
		$time=strtotime(day_date);
  		$previousDate=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)-1).'-01'));
		//查找上个月是否已经有数据
		$rs = $this->findSql("select id from oa_purch_product_price where thisDate = '$previousDate' limit 1,1");

		//如果没有，则插入从最后记录日期开始到本月之前的缓存数据
		if(!is_array($rs)){
			$sql = "select thisDate from oa_purch_product_price order by thisDate desc limit 1,1 ";
			$inRs = $this->findSql($sql);

//			print_r($inRs);
			if(is_array($inRs)){
				$beginDate = $inRs[0]['thisDate'];
			}else{
				$beginDate = '2011-07-01';
			}

			$initDateSql = " insert into oa_purch_product_price (suppName,suppId,productId,productNumb,productName,amountAll,moneyAll,prePrice,thisDate)
				select
					c.suppName,c.suppId,e.productId,e.productNumb,e.productName,
					sum(e.amountAll) as amountAll,
					sum(e.moneyAll) as moneyAll,
					round(sum(e.moneyAll) / sum(e.amountAll),6) as prePrice,
					concat(date_format(c.createTime ,'%Y-%m'),'-01') as thisDate
				from
					oa_purch_apply_basic c
					inner join
					oa_purch_apply_equ e
						on c.id = e.basicId
				where
					c.isTemp = 0 and c.state in(4,5,6,7,8) and e.amountAll > 0 and date_format(c.createTime,'%Y%m') > date_format('".$beginDate ."','%Y%m') and date_format(c.createTime,'%Y%m') < date_format('".day_date."','%Y%m')
				group by
					c.suppName,e.productNumb,date_format(c.createTime ,'%Y%m')
				order by c.createTime";

			$rs = $this->_db->getArray($initDateSql);
		}
	}

	/**
	 * 采购价格数据获取
	 *
	 */
	 function getEquPriceList_d($conditionArr){
	 	//查询条件
		$condition = " ";

		$year = date("Y");
		$beginYearMonth=$year.'01';
		$endYearMonth=$year.'12';
		$endMonth='12';

		//高级查询
		if(!empty($conditionArr)){
			foreach($conditionArr as $key=>$val){
				$logic.=$val['logic'].",";
				$field.=$val['field'].",";
				$relation.=$val['relation'].",";
				$values.=$val['values'].",";
			}
			$logicArr=explode(',',$logic);
			$fieldArr=explode(',',$field);
			$relationArr=explode(',',$relation);
			$valuesArr=explode(',',$values);
			$fieldstr="";
			$relationstr="";
			$i=0;
			foreach($logicArr as $key=>$val){
				if(!empty($val)){
					if($fieldArr[$key]=="searchYear"){//判断是否根据采购类型进行搜索
						if($valuesArr[$key]!=''){
							$year=$valuesArr[$key];
							$beginYearMonth=$year.'01';
							$endYearMonth=$year.'12';
						}
					}else if($fieldArr[$key]=="endMonth"){
						if($valuesArr[$key]!=''){
							$endYearMonth=$year.$valuesArr[$key];
							$endMonth=$valuesArr[$key];
						}
					}else{
						switch($fieldArr[$key]){//判断查询字段
							case "productNumb":$fieldstr="ae.productNumb ";break;
							case "productName":$fieldstr="ae.productName ";break;
							default: $fieldstr=" ";break;
						}
						switch($relationArr[$key]){//判断比较关系
							case "equal":$relationstr=" ='".$valuesArr[$key]."'";break;
							case "notequal":$relationstr=" !='".$valuesArr[$key]."'";break;
							case "greater":$relationstr=" >'".$valuesArr[$key]."'";break;
							case "less":$relationstr=" < '".$valuesArr[$key]."'";break;
							case "in":$relationstr=" like CONCAT('%','".$valuesArr[$key]."','%')";break;
							case "notin":$relationstr=" not like CONCAT('%','".$valuesArr[$key]."','%')";break;
						}
						$condition .=" ".$val." ( ".$fieldstr.$relationstr.") ";
					}
				}
				$i++;
			}
		}
//		echo $condition;

		//锚定日期
		$January = $year . "01";
		$February = $year . "02";
		$March = $year . "03";
		$April = $year . "04";
		$May = $year . "05";
		$June = $year. "06";
		$July = $year . "07";
		$August = $year. "08";
		$September = $year. "09";
		$October = $year. "10";
		$November = $year . "11";
		$December = $year . "12";

		//拼装SQL
	 	$sql="select
			gs.productNumb,gs.productName,sum(gs.moneyAll) as yearMoney,
			if( $January < $beginYearMonth or $January > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $January,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $January,gs.amountAll,0)),6)) as month1Price,
			if( $February < $beginYearMonth or $February > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $February,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $February,gs.amountAll,0)),6)) as month2Price,
			if( $March < $beginYearMonth or $March > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $March,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $March,gs.amountAll,0)),6)) as month3Price,
			if( $April < $beginYearMonth or $April > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $April,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $April,gs.amountAll,0)),6)) as month4Price,
			if( $May < $beginYearMonth or $May > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $May,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $May,gs.amountAll,0)),6)) as month5Price,
			if( $June < $beginYearMonth or $June > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $June,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $June,gs.amountAll,0)),6)) as month6Price,
			if( $July < $beginYearMonth or $July > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $July,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $July,gs.amountAll,0)),6)) as month7Price,
			if( $August < $beginYearMonth or $August > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $August,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $August,gs.amountAll,0)),6)) as month8Price,
			if( $September < $beginYearMonth or $September > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $September,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $September,gs.amountAll,0)),6)) as month9Price,
			if( $October < $beginYearMonth or $October > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $October,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $October,gs.amountAll,0)),6)) as month10Price,
			if( $November < $beginYearMonth or $November > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $November,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $November,gs.amountAll,0)),6)) as month11Price,
			if( $December < $beginYearMonth or $December > $endYearMonth,'',round(sum(if(gs.thisYearMonth = $December,gs.moneyAll,0))/sum(if(gs.thisYearMonth = $December,gs.amountAll,0)),6)) as month12Price
		from
		  	(
				select
					sum(ae.moneyAll) as moneyAll,sum(ae.amountAll) as amountAll,date_format(ab.createTime,'%Y%m') as thisYearMonth,$year as thisYear,
					ae.productNumb,ae.productName
				from
					oa_purch_apply_equ ae
					inner join
					oa_purch_apply_basic ab
						on ae.basicId = ab.id
				where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5,8,10)))) and ae.amountAll > 0 and date_format(ab.createTime,'%Y')=$year   $condition
				group by ae.productNumb,date_format(ab.createTime,'%Y%m')
		    )gs
		group by gs.productNumb
		order by yearMoney desc";
		$rows = $this->findSql($sql);
		return array($rows,$endMonth);
	 }

	 /**
	 * 价格表模板
	 *
	 */
	 function getPriceHtml_d($rows){
		$str = "";
		$i = 0;
		if(is_array($rows)){
			foreach($rows as $key=>$val){
				$i ++;
				$str .= <<<EOT
					<tr id="$iClass" class="$iClass">
						<td   width="50" name="tdth13">$i</td>
		                <td width="" name="tdth14">$val[productNumb]<br>$val[productName]</td>
		                <td class="formatMoney"  name="tdth15" width="10%">$val[yearMoney]</td>
		                <td class="formatMoneySix" name="tdth12" >$val[month12Price]</td>
		                <td class="formatMoneySix" name="tdth11" width="80">$val[month11Price]</td>
		                <td class="formatMoneySix" name="tdth10">$val[month10Price]</td>
		                <td class="formatMoneySix" name="tdth09">$val[month9Price]</td>
		                <td class="formatMoneySix" name="tdth08">$val[month8Price]</td>
		                <td class="formatMoneySix" name="tdth07">$val[month7Price]</td>
		                <td class="formatMoneySix" name="tdth06">$val[month6Price]</td>
		                <td class="formatMoneySix" name="tdth05">$val[month5Price]</td>
		                <td class="formatMoneySix" name="tdth04">$val[month4Price]</td>
		                <td class="formatMoneySix" name="tdth03">$val[month3Price]</td>
		                <td class="formatMoneySix" name="tdth02">$val[month2Price]</td>
		                <td class="formatMoneySix" name="tdth01">$val[month1Price]</td>
EOT;
			}

		}else{

		}
		return $str;
	 }

	 	/**
	 * 查询条件模板
	 *
	 */
	function selectList($logicArr,$fieldArr,$relationArr,$valuesArr){
		$str="";
		if(is_array($logicArr)){
			$i=0;
			foreach($logicArr as $key=>$val){
				$i++;
				if($val!=""){
					if($fieldArr[$key]=="beginMonth"||$fieldArr[$key]=="endMonth"){
						$tdStr='<select id="values'.$key.'" class="select field"  name="contract['.$key.'][values]">'.
									'<option value="01">1月份</option>'.
									'<option value="02">2月份</option>'.
									'<option value="03">3月份</option>'.
									'<option value="04">4月份</option>'.
									'<option value="05">5月份</option>'.
									'<option value="06">6月份</option>'.
									'<option value="07">7月份</option>'.
									'<option value="08">8月份</option>'.
									'<option value="09">9月份</option>'.
									'<option value="10">10月份</option>'.
									'<option value="11">11月份</option>'.
									'<option value="12">12月份</option>'.
						'</select>';
					}else{
						$tdStr='<input type="text" id="values'.$key.'" class="txt value"  name="contract['.$key.'][values]" value="'.$valuesArr[$key].'" onblur="trimSpace(this);"/>';
					}
					$str .= <<<EOT
						<tr>
							<td>$i</td>
							<td>
								<select id="logic$key" class="selectshort logic" name="contract[$key][logic]">
									<option value="and">并且</option>
									<option value="or">或者</option>
									<SCRIPT language=JavaScript>
									var logic="$val";
									$("select[id='logic$key'] option").each(function(){
												if($(this).val()==logic){
													$(this).attr("selected","selected");
												}
											});
									</SCRIPT>
								</select>
							</td>
							<td>
								<select id="field$key" class="selectmiddel field"  name="contract[$key][field]">
										<option value="suppName">供应商</option>
										<option value="productName">物料名称</option>
										<option value="productNumb">物料代码</option>
										<option value="searchYear">查询年份</option>
										<option value="beginMonth">起始月份</option>
										<option value="endMonth">结束月份</option>
									<SCRIPT language=JavaScript>
									var field="$fieldArr[$key]";
									$("select[id='field$key'] option").each(function(){
												if($(this).val()==field){
													$(this).attr("selected","selected");
												}
											});

									$(function() {
										$("#field$key").bind("change",function(){
												if($(this).val()=="beginMonth"||$(this).val()=="endMonth"){//判断查询字段是否为“采购类型”，如果是则追加选择框
													var tdHtml='<select id="values$key" class="select field"  name="contract[$key][values]">'+
																	'<option value="01">1月份</option>' +
																	'<option value="02">2月份</option>' +
																	'<option value="03">3月份</option>' +
																	'<option value="04">4月份</option>' +
																	'<option value="05">5月份</option>' +
																	'<option value="06">6月份</option>' +
																	'<option value="07">7月份</option>' +
																	'<option value="08">8月份</option>' +
																	'<option value="09">9月份</option>' +
																	'<option value="10">10月份</option>' +
																	'<option value="11">11月份</option>' +
																	'<option value="12">12月份</option>' +
																'</select>';
													$("#type$key").html("");
													$("#type$key").html(tdHtml);
													$("#relation$key").val("equal");
												}else {
													var tdHtml='<input type="text" id="value$key" class="txt value"  name="contract[$key][values]" value="" onblur="trimSpace(this);"/>';
													$("#type$key").html(tdHtml);
													$("#relation$key").val("in");
												}
										});
									});
									</SCRIPT>
								</select>
							</td>
							<td>
								<select id="relation$key" class="selectshort relation"  name="contract[$key][relation]">
									<option value="in">包含</option>
									<option value="equal">等于</option>
									<option value="notequal">不等于</option>
									<option value="greater">大于</option>
									<option value="less">小于</option>
									<option value="notin">不包含</option>
									<SCRIPT language=JavaScript>
									var relation="$relationArr[$key]";
									$("select[id='relation$key'] option").each(function(){
												if($(this).val()==relation){
													$(this).attr("selected","selected");
												}
										});
									</SCRIPT>
								</select>
							</td>
							<td>
								<div id="type$key">$tdStr</div>
								<SCRIPT language=JavaScript>
									var values="$valuesArr[$key]";
									$("select[id='values$key'] option").each(function(){
												if($(this).val()==values){
													$(this).attr("selected","selected");
												}
											});
									</SCRIPT>
							</td>
							<td><img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif"/></td>
						</tr>
EOT;

				}

			}
		}else{
			$str.="";
		}
		return $str;
	}
}