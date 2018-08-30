<?php
class model_purchase_report_purchasereport extends model_base {

	function __construct() {
		//$this->tbl_name = "oa_stock_baseinfo";
		//$this->sql_map = "stock/stockinfo/stockinfoSql.php";
		parent::__construct ();
	}

	/**
	 * �۸񻺴����ݳ�ʼ��
	 */
	function initData_d(){
		//��ȡ�ϸ��µ�����
		$time=strtotime(day_date);
  		$previousDate=date('Y-m-01',strtotime(date('Y',$time).'-'.(date('m',$time)-1).'-01'));
		//�����ϸ����Ƿ��Ѿ�������
		$rs = $this->findSql("select id from oa_purch_product_price where thisDate = '$previousDate' limit 1,1");

		//���û�У�����������¼���ڿ�ʼ������֮ǰ�Ļ�������
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
	 * �ɹ��۸����ݻ�ȡ
	 *
	 */
	 function getEquPriceList_d($conditionArr){
	 	//��ѯ����
		$condition = " ";

		$year = date("Y");
		$beginYearMonth=$year.'01';
		$endYearMonth=$year.'12';
		$endMonth='12';

		//�߼���ѯ
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
					if($fieldArr[$key]=="searchYear"){//�ж��Ƿ���ݲɹ����ͽ�������
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
						switch($fieldArr[$key]){//�жϲ�ѯ�ֶ�
							case "productNumb":$fieldstr="ae.productNumb ";break;
							case "productName":$fieldstr="ae.productName ";break;
							default: $fieldstr=" ";break;
						}
						switch($relationArr[$key]){//�жϱȽϹ�ϵ
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

		//ê������
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

		//ƴװSQL
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
				where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '���') or (ab.state in (5,8,10)))) and ae.amountAll > 0 and date_format(ab.createTime,'%Y')=$year   $condition
				group by ae.productNumb,date_format(ab.createTime,'%Y%m')
		    )gs
		group by gs.productNumb
		order by yearMoney desc";
		$rows = $this->findSql($sql);
		return array($rows,$endMonth);
	 }

	 /**
	 * �۸��ģ��
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
	 * ��ѯ����ģ��
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
									'<option value="01">1�·�</option>'.
									'<option value="02">2�·�</option>'.
									'<option value="03">3�·�</option>'.
									'<option value="04">4�·�</option>'.
									'<option value="05">5�·�</option>'.
									'<option value="06">6�·�</option>'.
									'<option value="07">7�·�</option>'.
									'<option value="08">8�·�</option>'.
									'<option value="09">9�·�</option>'.
									'<option value="10">10�·�</option>'.
									'<option value="11">11�·�</option>'.
									'<option value="12">12�·�</option>'.
						'</select>';
					}else{
						$tdStr='<input type="text" id="values'.$key.'" class="txt value"  name="contract['.$key.'][values]" value="'.$valuesArr[$key].'" onblur="trimSpace(this);"/>';
					}
					$str .= <<<EOT
						<tr>
							<td>$i</td>
							<td>
								<select id="logic$key" class="selectshort logic" name="contract[$key][logic]">
									<option value="and">����</option>
									<option value="or">����</option>
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
										<option value="suppName">��Ӧ��</option>
										<option value="productName">��������</option>
										<option value="productNumb">���ϴ���</option>
										<option value="searchYear">��ѯ���</option>
										<option value="beginMonth">��ʼ�·�</option>
										<option value="endMonth">�����·�</option>
									<SCRIPT language=JavaScript>
									var field="$fieldArr[$key]";
									$("select[id='field$key'] option").each(function(){
												if($(this).val()==field){
													$(this).attr("selected","selected");
												}
											});

									$(function() {
										$("#field$key").bind("change",function(){
												if($(this).val()=="beginMonth"||$(this).val()=="endMonth"){//�жϲ�ѯ�ֶ��Ƿ�Ϊ���ɹ����͡����������׷��ѡ���
													var tdHtml='<select id="values$key" class="select field"  name="contract[$key][values]">'+
																	'<option value="01">1�·�</option>' +
																	'<option value="02">2�·�</option>' +
																	'<option value="03">3�·�</option>' +
																	'<option value="04">4�·�</option>' +
																	'<option value="05">5�·�</option>' +
																	'<option value="06">6�·�</option>' +
																	'<option value="07">7�·�</option>' +
																	'<option value="08">8�·�</option>' +
																	'<option value="09">9�·�</option>' +
																	'<option value="10">10�·�</option>' +
																	'<option value="11">11�·�</option>' +
																	'<option value="12">12�·�</option>' +
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
									<option value="in">����</option>
									<option value="equal">����</option>
									<option value="notequal">������</option>
									<option value="greater">����</option>
									<option value="less">С��</option>
									<option value="notin">������</option>
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
							<td><img title="ɾ����" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif"/></td>
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