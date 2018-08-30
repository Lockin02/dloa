<?php

class model_purchase_task_basic extends model_base{

	public static $pageArr=array();

	//状态位
	private $state;

	public $statusDao; //状态类

	function __construct() {
		$this->tbl_name = "oa_purch_task_basic";
		$this->sql_map = "purchase/task/basicSql.php";
		parent :: __construct();

		$this->state = array(
			0 => array(
				"stateEName" => "begin",
				"stateCName" => "待接收",
				"stateVal" => "0"
			),
			1 => array(
				"stateEName" => "execute",
				"stateCName" => "执行中",
				"stateVal" => "1"
			),
			2 => array(
				"stateEName" => "Locking",
				"stateCName" => "锁定",
				"stateVal" => "2"
			),
			3 => array(
				"stateEName" => "end",
				"stateCName" => "完成",
				"stateVal" => "3"
			),
			4 => array(
				"stateEName" => "close",
				"stateCName" => "关闭",
				"stateVal" => "4"
			),
			5 => array(
				"stateEName" => "change",
				"stateCName" => "待变更",
				"stateVal" => "5"
			)
		);

		$this->statusDao = new model_common_status();
		$this->statusDao->status = array(
			0 => array(
				"statusEName" => "begin",
				"statusCName" => "待接收",
				"key" => "0"
			),
			1 => array(
				"statusEName" => "execute",
				"statusCName" => "执行中",
				"key" => "1"
			),
			2 => array(
				"statusEName" => "Locking",
				"statusCName" => "锁定",
				"key" => "2"
			),
			3 => array(
				"statusEName" => "end",
				"statusCName" => "完成",
				"key" => "3"
			),
			4 => array(
				"statusEName" => "close",
				"statusCName" => "关闭",
				"key" => "4"
			),
			5 => array(
				"statusEName" => "change",
				"statusCName" => "待变更",
				"key" => "5"
			)
		);

		//调用初始化对象关联类
		parent::setObjAss();
	}

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分
/*****************************************页面模板显示开始********************************************/

	/**
	 * @desription 显示我的待接收列表
	 * @param $rows		采购任务数组
	 * @date 2010-12-23 下午04:07:16
	 */
	function showMyWaitlist_s($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				if($val['feedback']==1){
					$taskNumb="<font color='blue'>".$val['taskNumb']."</font>";
				}else{
					$taskNumb=$val['taskNumb'];
				}
				$str .=<<<EOT
	<tr class="$classCss"  title="双击查看采购任务" ondblclick="parent.location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
		<td  name="tdth01">
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td name="tdth02">
            <p class="checkChildAll"> $taskNumb </p>
        </td>
        <td name="tdth03">
            $val[sendTime]
        </td>
        <td name="tdth04">
            $val[dateHope]
        </td>
        <td name="tdth05">
            $val[createName]
        </td>
        <td name="tdth06">
            $val[stateC]
        </td>
        <td width="47%" class="tdChange td_table" >
			<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;

				foreach ($val['childArr'] as $chdKey => $chdVal){
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" name="tdth08">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb]<img src="images/icon/view.gif" title="查看物料详细信息" onclick="viewProduct('$chdVal[productId]');"/>
				        	 <br> $chdVal[productName]
				        </td>
				        <td width="20%" name="tdth09">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%" name="tdth10">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%" name="tdth11">
				            $chdVal[amountIssued]
				        </td>
				        <td width="12%" name="tdth12">
				           $chdVal[amountNotIssued]
				        </td>
				        <td width="19%" name="tdth13">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开物料具体信息></div>
        </td>
        <td name="tdth14">
        	<a target="_parent" href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">查看</a> |
			<a href="?model=purchase_task_basic&action=stateBegin&sid=$val[id]">接收</a> |
			<a target="_parent" href="?model=purchase_task_basic&action=toFeedBack&id=$val[id]&skey=$val[skey_]">反馈</a>
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * @desription 显示未执行采购任务列表
	 * @param $rows		采购任务数组
	 * @date 2010-12-23 下午04:07:16
	 */
	function showWaitlist_s($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str .=<<<EOT
	<tr class="$classCss"  title="双击查看采购任务" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
		<td  name="tdth01" >
			<p class="childImg">
            <image src="images/expanded.gif" />$i
        	</p>
        </td>
        <td  name="tdth02">
            <p class="checkChildAll"> $val[taskNumb] </p>
        </td>
        <td  name="tdth03">
            $val[sendTime]
        </td>
        <td  name="tdth04">
            $val[dateHope]
        </td>
        <td  name="tdth05">
            $val[createName]
        </td>
        <td  name="tdth06">
            $val[sendName]
        </td>
        <td  name="tdth07">
            $val[stateC]
        </td>
        <td width="47%" class="tdChange td_table" >
			<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;

				foreach ($val['childArr'] as $chdKey => $chdVal){
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left"  name="tdth08">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%"  name="tdth09">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%"  name="tdth10">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%"  name="tdth11">
				            $chdVal[amountNotIssued]
				        </td>
				        <td width="19%"  name="tdth12">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开物料具体信息></div>
        </td>
        <td  name="tdth13">
			<select class="myExecuteTask">
				<option>请选择操作</option>
				<option value="view">查看</option>
				<option value="change">重新分配</option>
			</select>
			<input type="hidden" value='$val[id]'/>
			<input type="hidden" value='$val[applyNumb]' />
			<input type="hidden" id="check$val[id]" value='$val[skey_]' />
			<!--
        	<a  href="?model=purchase_task_basic&action=read&id=$val[id]">查看</a> |
			<a href="?model=purchase_task_basic&action=stateBegin&sid=$val[id]">接收</a> -->
		</td>
    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * @desription 显示执行中列表
	 * @param $rows		采购任务数组
	 * @date 2010-12-23 下午05:00:53
	 */
	function showExecutionlist_s ($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$j=0;
				$i++;
				$orderEquDao=new model_purchase_contract_equipment();
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$str .=<<<EOT
			<tr class="$classCss" title="双击查看采购任务" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
				<td  name="tdth01" width="3%">
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td name="tdth02" width="9%">
		            <p class="checkChildAll"><input id="allCheckbox$i" type="checkbox"> $val[taskNumb] </p>
		        </td>
		        <td name="tdth03" width="7%">
		            $val[sendTime]
		        </td>
		        <td name="tdth04" width="7%">
		            $val[dateHope]
		        </td>
		        <td name="tdth05" width="4%">
		            $val[createName]
		        </td>
		        <td name="tdth06" width="4%">
		            $val[sendName]
		        </td>
		        <!--
		        <td name="tdth07">
		            $val[stateC]
		        </td>-->
		        <td width="40%" class="tdChange td_table" >
					<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( $chdVal['amountNotIssued'] == null || $chdVal['amountNotIssued']==0 || $chdVal['amountNotIssued']==""|| $chdVal['amountNotIssued']<0 ){

						$checkBoxStr =<<<EOT
				        	$chdVal[productNumb] <br> $chdVal[productName]
EOT;
					}else{
						$j++;
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
EOT;
					}
					$priceData=$orderEquDao->getHistoryInfo_d( $chdVal['productNumb'],$val['createTime']);//获取最新历史价格
						if(empty($priceData['applyPrice'])){
							$priceData['applyPrice'] = "无";
							$priceLink =<<<EOT
							$priceData[applyPrice]
EOT;
						}
						else{
							$priceData['applyPrice'] = sprintf("%.3f",$priceData['applyPrice']);	//保留三位小数
							$priceLink =<<<EOT
							$priceData[applyPrice]<img src="images/icon/view.gif" title="查看报价详情" onclick="showPrice('$chdVal[productNumb]','$val[createTime]');"/>
EOT;
						}
					//询价数量	$chdVal[amountIssued]
					$str.=<<<EOT
						<tr align="center">
		        			<td  align="left"  name="tdth08" width="20%">
					        	$checkBoxStr
					        </td>
					        <td width="12%" name="tdth09">
					        	$chdVal[purchTypeCName]
					        </td>
					        <td width="8%" name="tdth10">
					            $chdVal[amountAll]
					        </td>
					        <td width="9%" name="tdth12">
					          $chdVal[amountNotIssued]
					        </td>
					        <td width="12%" name="tdth13">
					            $chdVal[dateHope]
					        </td>
					        <td width="14%" name="tdth11">
					          $priceLink
					        </td>
		        		</tr>
EOT;
				}
				$val['instruction'] = htmlspecialchars($val['instruction']);	//把一些预定义的字符转换为 HTML 实体 
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
			        <td width="13%">
	            		$val[instruction]
	        		</td>
			        <td  name="tdth14" width="11%">

						<SCRIPT language=JavaScript>
							if($j==0){
								jQuery("#allCheckbox$i").hide();
							}
						</SCRIPT>
						<select class="myExecuteTask">
							<option>请选择操作</option>
							<option value="view">查看</option>
							<option value="change">变更</option>
							<option value="finish">完成</option>
							<option value="close">关闭</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
						<input type="hidden" id="check$val[id]" value='$val[skey_]' />
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}
	/**
	 * @desription 显示我的执行中列表
	 * @param $rows		采购任务数组
	 * @date 2010-12-23 下午05:00:53
	 */
	function showMyExecutionlist_s ($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			$orderEquDao=new model_purchase_contract_equipment();
			foreach ($rows as $key => $val) {
				$j=0;
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				if($val['feedback']==1){
					$taskNumb="<font color='blue'>".$val['taskNumb']."</font>";
				}else{
					$taskNumb=$val['taskNumb'];
				}
				$str .=<<<EOT
			<tr class="$classCss"  title="双击查看采购任务" ondblclick="parent.location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
				<td   name="tdth01" width="3%">
					<p class="childImg">
		            <image src="images/expanded.gif" />$i
		        	</p>
		        </td>
		        <td name="tdth02" width="9%">
		            <p class="checkChildAll"><input id="allCheckbox$i" type="checkbox"> $taskNumb </p>
		        </td>
		        <td name="tdth03" width="7%">
		            $val[sendTime]
		        </td>
		        <td name="tdth04" width="7%">
		            $val[dateHope]
		        </td>
		        <td name="tdth05" width="4%">
		            $val[createName]
		        </td>
		        <td name="tdth06" width="4%">
		            $val[stateC]
		        </td>
		        <td width="40%" class="tdChange td_table" >
					<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( $chdVal['amountAll']-$chdVal['contractAmount']<0||$chdVal['amountAll']-$chdVal['contractAmount']==0 ){
						if($chdVal['amountNotIssued']!=$chdVal['amountAll']){//用颜色区分物料是否进行询价
							$checkBoxStr =<<<EOT
					        	<font color="blue">$chdVal[productNumb]<img src="images/icon/view.gif" title="查看物料详细信息" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]</font>
EOT;
						}else{
							$checkBoxStr =<<<EOT
					        	$chdVal[productNumb]<img src="images/icon/view.gif" title="查看物料详细信息" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]
EOT;
						}
					}else{
						$j++;
						if($chdVal['amountNotIssued']!=$chdVal['amountAll']){//用颜色区分物料是否进行询价
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	<font color="blue">$chdVal[productNumb]<img src="images/icon/view.gif" title="查看物料详细信息" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]</font>
EOT;
						}else{
						$checkBoxStr =<<<EOT
							<input type="checkbox" class="checkChild">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb]<img src="images/icon/view.gif" title="查看物料详细信息" onclick="viewProduct('$chdVal[productId]');"/> <br> $chdVal[productName]
EOT;
						}
					}
						$priceData=$orderEquDao->getHistoryInfo_d( $chdVal['productNumb'],$val['createTime']);//获取最新历史价格
						if(empty($priceData['applyPrice'])){
							$priceData['applyPrice'] = "无";
							$priceLink =<<<EOT
							$priceData[applyPrice]
EOT;
						}
						else{
							$priceData['applyPrice'] = sprintf("%.3f",$priceData['applyPrice']);	//保留三位小数
							$priceLink =<<<EOT
							$priceData[applyPrice]<img src="images/icon/view.gif" title="查看报价详情" onclick="showPrice('$chdVal[productNumb]','$val[createTime]');"/>
EOT;
						}
					//询价数量	$chdVal[amountIssued]
					$str.=<<<EOT
						<tr align="center">
		        			<td  align="left"   name="tdth08" width="20%">
					        	$checkBoxStr
					        </td>
					        <td width="12%"   name="tdth09">
					        	$chdVal[purchTypeCName]
					        </td>
					        <td width="8%"   name="tdth10">
					            $chdVal[amountAll]
					        </td>
					        <td width="9%"   name="tdth12">
					           $chdVal[amountNotIssued]
					        </td>
					        <td width="12%"   name="tdth13">
					            $chdVal[dateHope]
					        </td>
					        <td width="14%"   name="tdth11">	
					           $priceLink	
					        </td>
		        		</tr>
EOT;
				}
				$val['instruction'] = htmlspecialchars($val['instruction']);	//把一些预定义的字符转换为 HTML 实体 
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
		        	<td width="13%">
	            		$val[instruction]
	        		</td>
			        <td  name="tdth14" width="11%">

						<SCRIPT language=JavaScript>
							if($j==0){
								jQuery("#allCheckbox$i").hide();
							}
						</SCRIPT>
						<select class="myExecuteTask">
							<option>请选择操作</option>
							<option value="view">查看</option>
							<option value="export">导出任务</option>
							<option value="finish">完成</option>
							<option value="close">关闭</option>
							<option value="feedback">任务反馈</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
						<input type="hidden" id="check$val[id]" value='$val[skey_]' />
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**显示我的已关闭列表
	*author can
	*2011-1-5
	 * @param $rows		采购任务数组
	*/
		function showMyCloselist($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
							$str .=<<<EOT
				<tr class="$classCss" align="center" title="双击查看采购任务"  ondblclick="parent.location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
					<td   name="tdth01">
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left"  name="tdth02">
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			       <td   name="tdth03">
			            $val[sendTime]
			        </td>
			        <td   name="tdth04">
			            $val[dateHope]
			        </td>
			        <td   name="tdth05">
			            $val[createName]
			        </td>
			        <td   name="tdth06">
			            $val[stateC]
			        </td>
			        <td width="47%" class="tdChange td_table" >
						<table class="shrinkTable main_table_nested" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left"  name="tdth08">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%"  name="tdth09">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%"  name="tdth10">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%"  name="tdth11">
				            $chdVal[amountIssued]
				        </td>
				        <td width="12%"  name="tdth12">
				           $chdVal[amountNotIssued]
				        </td>
				        <td width="19%"  name="tdth13">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
			        <td  name="tdth14">
						<a target="_parent" href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">查看</a>｜
						<img src="images/icon/view.gif" title="重新启动任务" onclick="startTask($val[id]);"/>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**
	 * @exclude 显示变更通知单列表
	 * @author ouyang
	 * @param $rows		采购任务数组
	 * @param	$showpage
	 * @return
	 * @version 2010-8-17 下午08:51:37
	 */
	function showChangeNotice($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				$iClass = $i%2+1;
				$stateName=$this->service->stateToVal(  $val['state'] );
				$str .=<<<EOT
				<tr class="TableLine$iClass" align="center">
					<td  >
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left" >
			            $val[taskNumb]
			        </td>
			        <td  >
			            $val[createName]
			        </td>
			        <td  >
			            $stateName
			        </td>
			        <td width="50%" class="tdChange" >
						<table width="100%"  class="shrinkTable">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){

					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
						$chdVal["amountAll"] = 0;
						continue;
					}

					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
					}else{
						$amountOk = $chdVal['amountAll'];
					}

					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="15%" >
				            $chdVal[purchTypeCName]
				        </td>
				        <td width="12%">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%">
				            $amountOk
				        </td>
				        <td width="20%">
				            $chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
			        <td >
						<select onchange=" selectButton( this , '$val[id]', '$val[taskNumb]' ) " >
						    <option value="value1">请选择</option>
						    <option value="readReceive">查看接收</option>
					  	</select>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
		return $str . '<tr><td colspan="15" class="pageTd" style="text-align:center;">' . $showpage->show(7) . '</td></tr>';
	}

	/**
	 * 显示执行中列表
	 * @param $rows		采购任务数组
	 */
	function showExecutionlist($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
				$str .=<<<EOT
				<tr class="TableLine$iClass" align="center"  title="双击查看采购任务" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
					<td    name="tdth01">
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left"   name="tdth02">
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			        <td    name="tdth03">
			            $val[sendTime]
			        </td>
			        <td    name="tdth04">
			            $val[dateHope]
			        </td>
			        <td    name="tdth05">
			            $val[createName]
			        </td>
			        <td    name="tdth06">
			            $val[sendName]
			        </td>
			        <td width="42%" class="tdChange td_table" >
						<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				if(is_array($val['childArr'])){
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left"   name="tdth07">
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%"   name="tdth08">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%"   name="tdth09">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%"   name="tdth10">
				            $chdVal[amountIssued]
				        </td>
				        <td width="12%"   name="tdth11">
				            $chdVal[amountNotIssued]
				        </td>
				        <td width="19%"   name="tdth12">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}

				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
			        <td   name="tdth13">
						<a  href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">查看</a>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";}
		return $str;
	}

	/**
	 * 我分配的待接收采购任务列表
	 * @param $rows		采购任务数组
	 * @param $showpage
	 */
	function showWaitlist($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
							$str .=<<<EOT
				<tr class="TableLine$iClass" align="center">
					<td  >
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left" >
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			        <td  >
			            $val[sendTime]
			        </td>
			        <td  >
			            $val[dateHope]
			        </td>
			        <td  >
			            $val[createName]
			        </td>
			        <td  >
			            $val[sendName]
			        </td>
			        <td width="42%" class="tdChange td_table" >
						<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="12%">
				            $chdVal[amountAll]
				        </td>
				        <td width="12%">
				            $chdVal[amountNotIssued]
				        </td>
				        <td width="16%">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
			        <td >
						<a target="_parent" href="?model=purchase_task_basic&action=read&id=$val[id]">查看</a>
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}

	/**分配执行中的任务列表
	*author can
	*2011-3-23
	 * @param $rows		采购任务数组
	 * @param $showpage
	*/

	function showAllotExecutionlist($rows, $showpage){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$i++;
				 $iClass = $i%2+1;
				$str .=<<<EOT
				<tr class="TableLine$iClass" align="center"  title="双击查看采购任务" ondblclick="location='index1.php?model=purchase_task_basic&action=read&id=$val[id]&contNumber=$val[applyNumb]&skey=$val[skey_]'">
					<td>
						<p class="childImg">
			            <image src="images/expanded.gif" />$i
			        	</p>
			        </td>
			        <td align="left" >
			            <p class="checkChildAll">$val[taskNumb] </p>
			        </td>
			        <td  >
			            $val[sendTime]
			        </td>
			        <td  >
			            $val[dateHope]
			        </td>
			        <td  >
			            $val[createName]
			        </td>
			        <td  >
			            $val[sendName]
			        </td>
			        <td width="50%" class="tdChange td_table" >
						<table width="100%"  class="shrinkTable main_table_nested">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
//					if( !isset( $chdVal["amountAll"] )||$chdVal["amountAll"]==0 ||$chdVal["amountAll"]=="" ){
//						$chdVal["amountAll"] = 0;
//						continue;
//					}
					$str.=<<<EOT
					<tr align="center">
	        			<td  align="left" >
				        	<input type="hidden" class="hidden" value="$chdVal[id]"/>
				        	$chdVal[productNumb] <br> $chdVal[productName]
				        </td>
				        <td width="20%">
				        	$chdVal[purchTypeCName]
				        </td>
				        <td width="8%">
				            $chdVal[amountAll]
				        </td>
				        <td width="8%">
				            $chdVal[amountIssued]
				        </td>
				        <td width="8%">
				            $chdVal[contractAmount]
				        </td>
				        <td width="15%">
				          	$chdVal[dateHope]
				        </td>
	        		</tr>
EOT;
				}
	        	$str.=<<<EOT
			        	</table>
			        	<div class="readThisTable"><单击展开物料具体信息></div>
			        </td>
			        <td >
						<a   href="?model=purchase_task_basic&action=read&id=$val[id]&skey=$val[skey_]">查看</a>&nbsp;
					<!--	<a target="_parent" href="?model=purchase_task_basic&action=toChange&id=$val[id]&numb=$val[taskNumb]">变更</a>-->
					</td>
			    </tr>
EOT;
			}
		}else {
			$str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
		}
		return $str;
	}
		/**
	 * 查看采购任务示列表
	 * @param $listEqu		采购任务物料数组
	 */
	function showTaskRead($listEqu){
		$planDao=new model_purchase_plan_basic();
		$applyDao=new model_asset_purchase_apply_apply();
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$planRows=$planDao->get_d($val['planId']);
				if($val['purchType']==""){      //判断采购类型是否为空
					$val['purchType']=$planRows['purchType'];
				}
				switch($val['purchType']){
					case "oa_sale_order":$objModel='model_projectmanagent_order_order';$objName='order';break;
					case "oa_sale_lease":$objModel='model_contract_rental_rentalcontract';$objName='rentalcontract';break;
					case "oa_sale_service":$objModel='model_engineering_serviceContract_serviceContract';$objName='serviceContract';break;
					case "oa_sale_rdproject":$objModel='model_rdproject_yxrdproject_rdproject';$objName='rdproject';break;
					case "oa_borrow_borrow":$objModel='model_projectmanagent_borrow_borrow';$objName='borrow';break;
					case "oa_present_present":$objModel='model_projectmanagent_present_present';$objName='present';break;
					case "stock":$objModel='model_stock_fillup_fillup';$objName='fillup';break;
					case "HTLX-XSHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-ZLHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-FWHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-YFHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					default:$objModel='';$objName='';break;
				}
					$securityDao=new model_common_securityUtil($objName);
					if($planRows['sourceID']!=""&&$objModel!=''){
						$sourceDao=new $objModel();
						$sourceRow=$sourceDao->find(array ("id" =>$planRows['sourceID'] ) );
						$skey=$securityDao->md5Row($sourceRow,'id',$objModel);
					}
				//根据不同的采购类型，查看不同的源单信息
				switch($val['purchType']){
					case "oa_sale_order":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_order_order&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_lease":$souceNum='<a target="_bank" href="index1.php?model=contract_rental_rentalcontract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_service":$souceNum='<a target="_bank" href="index1.php?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_rdproject":$souceNum='<a target="_bank" href="index1.php?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "stock":$souceNum='<a target="_bank" href="index1.php?model=stock_fillup_fillup&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看补库信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_borrow_borrow":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_borrow_borrow&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看借试用信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_present_present":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_present_present&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看赠送信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-XSHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-ZLHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-FWHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-YFHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					default:$souceNum=$planRows[sourceNumb];break;
				}
				if($val['purchType']=="oa_asset_purchase_apply"){
					$applyRow=$applyDao->get_d($val['applyId']);
					$souceNum='';
					$planRows['rObjCode']='';
					$planRows['projectName']='';
					$planRows['sendName']=$applyRow['applicantName'];
					$planRows['department']=$applyRow['applyDetName'];

				}

				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[qualityName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[contractAmount]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$souceNum
						</td>
						<td>
							$planRows[contractName]
						</td>
						<td>
							$planRows[projectName]
						</td>
						<td>
							$planRows[sendName]
						</td>
						<td>
							$planRows[department]
						</td>
						<td>
							<textarea rows="2" cols="23" class="textarea_read_blue" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * 查看采购任务示列表
	 * @param $listEqu		采购任务物料数组
	 */
	function showRead($listEqu){
		$planDao=new model_purchase_plan_basic();
		$applyDao=new model_asset_purchase_apply_apply();
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$planRows=$planDao->get_d($val['planId']);
				if($val['purchType']==""){      //判断采购类型是否为空
					$val['purchType']=$planRows['purchType'];
				}
				switch($val['purchType']){
					case "oa_sale_order":$objModel='model_projectmanagent_order_order';$objName='order';break;
					case "oa_sale_lease":$objModel='model_contract_rental_rentalcontract';$objName='rentalcontract';break;
					case "oa_sale_service":$objModel='model_engineering_serviceContract_serviceContract';$objName='serviceContract';break;
					case "oa_sale_rdproject":$objModel='model_rdproject_yxrdproject_rdproject';$objName='rdproject';break;
					case "oa_borrow_borrow":$objModel='model_projectmanagent_borrow_borrow';$objName='borrow';break;
					case "oa_present_present":$objModel='model_projectmanagent_present_present';$objName='present';break;
					case "stock":$objModel='model_stock_fillup_fillup';$objName='fillup';break;
					case "HTLX-XSHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-ZLHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-FWHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					case "HTLX-YFHT":$objModel='model_contract_contract_contract';$objName='contract';break;
					default:$objModel='';$objName='';break;
				}
					$securityDao=new model_common_securityUtil($objName);
					if($planRows['sourceID']!=""&&$objModel!=''){
						$sourceDao=new $objModel();
						$sourceRow=$sourceDao->find(array ("id" =>$planRows['sourceID'] ) );
						$skey=$securityDao->md5Row($sourceRow,'id',$objModel);
					}
				//根据不同的采购类型，查看不同的源单信息
				switch($val['purchType']){
					case "oa_sale_order":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_order_order&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_lease":$souceNum='<a target="_bank" href="index1.php?model=contract_rental_rentalcontract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_service":$souceNum='<a target="_bank" href="index1.php?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_sale_rdproject":$souceNum='<a target="_bank" href="index1.php?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "stock":$souceNum='<a target="_bank" href="index1.php?model=stock_fillup_fillup&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看补库信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_borrow_borrow":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_borrow_borrow&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看借试用信息">'.$planRows[sourceNumb].'</a>';break;
					case "oa_present_present":$souceNum='<a target="_bank" href="index1.php?model=projectmanagent_present_present&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看赠送信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-XSHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-ZLHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-FWHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					case "HTLX-YFHT":$souceNum='<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id='.$planRows[sourceID].'&skey='.$skey.'" title="查看合同信息">'.$planRows[sourceNumb].'</a>';break;
					default:$souceNum=$planRows[sourceNumb];break;
				}
				if($val['purchType']=="oa_asset_purchase_apply"){
					$applyRow=$applyDao->get_d($val['applyId']);
					$souceNum='';
					$planRows['rObjCode']='';
					$planRows['projectName']='';
					$planRows['sendName']=$applyRow['applicantName'];
					$planRows['department']=$applyRow['applyDetName'];

				}

				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>
						<td>
							$val[pattem]
						</td>
						<td>
							$val[unitName]
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[contractAmount]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$souceNum
						</td>
						<td>
							$planRows[rObjCode]
						</td>
						<td>
							$planRows[contractName]
						</td>
						<td>
							$planRows[projectName]
						</td>
						<td>
							$planRows[sendName]
						</td>
						<td>
							$planRows[department]
						</td>
						<td>
							<textarea rows="2" cols="23" class="textarea_read_blue" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * 反馈采购任务示列表
	 * @param $listEqu		采购任务物料数组
	 */
	function showFeedbackList($listEqu){
		$planDao=new model_purchase_plan_basic();
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$planRows=$planDao->get_d($val['planId']);
				if($val['purchType']==""){      //判断采购类型是否为空
					$val['purchType']=$planRows['purchType'];
				}

				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
							<input type="hidden" name="basic[equment][$i][productNumb]" value="$val[productNumb]" />
							<input type="hidden" name="basic[equment][$i][productName]" value="$val[productName]" />
							<input type="hidden" name="basic[equment][$i][purchType]" value="$val[purchType]" />
							<input type="hidden" name="basic[equment][$i][planId]" value="$val[planId]" />
							<input type="hidden" name="basic[equment][$i][sourceID]" value="$planRows[sourceID]" />
						</td>
						<td>
							$val[pattem]
							<input type="hidden" name="basic[equment][$i][pattem]" value="$val[pattem]" />
						</td>
						<td>
							$val[unitName]
							<input type="hidden" name="basic[equment][$i][unitName]" value="$val[unitName]" />
						</td>
						<td>
							$val[purchTypeCName]
							<input type="hidden" name="basic[equment][$i][purchTypeCName]" value="$val[purchTypeCName]" />
						</td>
						<td>
							$val[amountAll]
							<input type="hidden" name="basic[equment][$i][amountAll]" value="$val[amountAll]" />
						</td>
						<td>
							$val[dateHope]
							<input type="hidden" name="basic[equment][$i][dateHope]" value="$val[dateHope]" />
						</td>
						<td>
							$planRows[sourceNumb]
							<input type="hidden" name="basic[equment][$i][sourceNumb]" value="$planRows[sourceNumb]" />
						</td>
						<td>
							$planRows[contractName]
							<input type="hidden" name="basic[equment][$i][contractName]" value="$planRows[contractName]" />
						</td>
						<td>
							$planRows[projectName]
							<input type="hidden" name="basic[equment][$i][projectName]" value="$planRows[projectName]" />
						</td>
						<td>
							$planRows[sendName]
							<input type="hidden" name="basic[equment][$i][sendName]" value="$planRows[sendName]" />
							<input type="hidden" name="basic[equment][$i][planNumb]" value="$planRows[planNumb]" />
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * 新建采购申请单示列表
	 * @param $listEqu		采购任务物料数组
	 */
	function newApply($listEqu){
		$str="";
		$i = $m = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;

				$str.=<<<EOT
				<tr height="28" align="center">
					<td>
						<p class="childImg">
							<image src="images/expanded.gif" />$i
						</p>
					</td>
					<td>
						$val[productNumb]/$val[productName]
					</td>
					<td>
						<p class="allAmount">$val[allAmount]</p>
					</td>
					<td width="75%">
						<table class="shrinkTable" width="100%" border="0" cellspacing="1" cellpadding="0">
EOT;
				foreach ($val['childArr'] as $chdKey => $chdVal){
					$nowTime=date("Y-m-d H:i:s");
					$deviceNumb = "paequ-".date("YmdHis").rand(10,99);
					if( !isset( $chdVal["amountNotIssued"] )||$chdVal["amountNotIssued"]==0 ||$chdVal["amountNotIssued"]=="" ){
						$chdVal["amountNotIssued"] = 0;
						continue;
					}
					//下面这一行代码放在第941行
					//<input type="hidden" name="basic[equment][$m][deviceIsUse]" value="1" />
					$str.=<<<EOT
				<tr align="center">
						<td>
							$chdVal[basicNumb]
						</td>
						<td width="14%">
							$chdVal[purchTypeCName]
						</td>
						<td  width="12%">
							<input type="text" name="basic[equment][$m][amountAll]" id="amountAll$m" value="$chdVal[amountNotIssued]" size=6 class="taskAmount">
							<input type="hidden" name="amountAll" value="$chdVal[amountNotIssued]" />


							<input type="hidden" name="basic[equment][$m][applyEquOnlyId]" value="$deviceNumb" />
							<input type="hidden" name="basic[equment][$m][deviceNumb]" value="$deviceNumb" />
							<input type="hidden" name="basic[equment][$m][basicVersionNumb]" value="1" />
							<input type="hidden" name="basic[equment][$m][objectsNumb]" value="$chdVal[objectsNumb]" />
							<input type="hidden" name="basic[equment][$m][typeTabName]" value="$chdVal[typeTabName]" />
							<input type="hidden" name="basic[equment][$m][typeTabId]" value="$chdVal[typeTabId]" />
							<input type="hidden" name="basic[equment][$m][typeEquTabName]" value="$chdVal[typeEquTabName]" />
							<input type="hidden" name="basic[equment][$m][typeEquTabId]" value="$chdVal[typeEquTabId]" />
							<input type="hidden" name="basic[equment][$m][productName]" value="$chdVal[productName]" />
							<input type="hidden" name="basic[equment][$m][productId]" value="$chdVal[productId]" />
							<input type="hidden" name="basic[equment][$m][productNumb]" value="$chdVal[productNumb]" />
							<input type="hidden" name="basic[equment][$m][amountIssued]" value="0" />
							<input type="hidden" name="basic[equment][$m][dateIssued]" value="$nowTime" />
							<input type="hidden" name="basic[equment][$m][planId]" value="$chdVal[planId]" />
							<input type="hidden" name="basic[equment][$m][plantNumb]" value="$chdVal[plantNumb]" />
							<input type="hidden" name="basic[equment][$m][planEquId]" value="$chdVal[planEquId]" />
							<input type="hidden" name="basic[equment][$m][planEquNumb]" value="$chdVal[planEquNumb]" />

							<input type="hidden" name="basic[equment][$m][taskId]" value="$chdVal[basicId]" />
							<input type="hidden" name="basic[equment][$m][taskNumb]" value="$chdVal[basicNumb]" />
							<input type="hidden" name="basic[equment][$m][taskEquId]" value="$chdVal[id]" />
							<input type="hidden" name="basic[equment][$m][taskEquNumb]" value="$chdVal[deviceNumb]" />
						</td>
						<td width="14%">
							&nbsp;<input type="text" id="hopeTime$m" name="basic[equment][$m][dateHope]" size="9" maxlength="12" class="BigInput" value=""  onfocus="WdatePicker()"  readonly />
						</td>
						<td width="12%">
							&nbsp;<input type="text" id="applyPrice$m" name="basic[equment][$m][applyPrice]" size="9" maxlength="12" value=""/>
						</td>
						<td width="20%">
							<textarea rows="2" cols="18" name="basic[equment][$m][remark]" id="remark$m"></textarea>
						</td>
					</tr>
EOT;
					++$m;
				}
				$str.=<<<EOT
        	</table>
        	<div class="readThisTable"><单击展开物料具体信息></div>
        </td>
    </tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @exclude 变更采购任务列表
	 * @author ouyang
	 * @param $listEqu		采购任务物料数组
	 * @return
	 * @version 2010-8-17 下午04:50:46
	 */
	function showChange($listEqu){
		$str="";
		$i = $m = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				if(empty($val['originalId'])){
					$str.='<input type="hidden" name="basic[equment]['.$i.'][oldId]" value="'.$val['id'].'" />';
				}else{
					$str.='<input type="hidden" name="basic[equment]['.$i.'][oldId]" value="'.$val['originalId'].'" />';
				}

				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb]<br>$val[productName]
							<input type="hidden" size="10" id="productNumb$i" name="basic[equment][$i][productNumb]" value="$val[productNumb]"/>
							<input type="hidden" size="10" id="productName$i" name="basic[equment][$i][productName]" value="$val[productName]"/>
							<input type="hidden" size="10" id="productId$i" name="basic[equment][$i][productId]" value="$val[productId]"/>
							<input type="hidden" size="10" id="planId$i" name="basic[equment][$i][planId]" value="$val[planId]"/>
							<input type="hidden" size="10" id="planId$i" name="basic[equment][$i][purchType]" value="$val[purchType]"/>
						</td>
						<td>
							$val[pattem]
							<input type="hidden" id="pattem$i" name="basic[equment][$i][pattem]" value="$val[pattem]"/>
						</td>
						<td>
							$val[unitName]
							<input type="hidden" id="unitName$i" name="basic[equment][$i][unitName]" value="$val[unitName]"/>
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[amountIssued]
							<input type="hidden" id="amountIssued$i" name="basic[equment][$i][amountIssued]" value="$val[amountIssued]"/>
						</td>
						<td>
							<input type="text" size="6" id="amountAll$i" name="basic[equment][$i][amountAll]" value="$val[amountAll]" class="taskAmount" />

							<input type="hidden" name="basic[equment][$i][maxAmount]"  value="$val[amountAll]" />
							<input type="hidden"   value="$val[amountIssued]" />
							<!--
							<input type="hidden" name="basic[equment][$i][id]"  value="$val[id]" /> -->
						</td>
						<td>
							&nbsp;<input type="text" id="dateHope$i" name="basic[equment][$i][dateHope]" size="9" maxlength="12" class="BigInput" value="$val[dateHope]"  onfocus="WdatePicker()"  readonly />
						</td>
						<td>
							<input id="remark$i" class="txtmiddle" name="basic[equment][$i][remark]" value="$val[remark]"></input>
						</td>
						<td>
							<input type="checkbox" class="" name="basic[equment][$i][isClose]" ></input>
						</td>
			    	</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @exclude 变更接收查看列表
	 * @author ouyang
	 * @param $listEqu		采购任务物料数组
	 * @return
	 * @version 2010-8-17 上午09:58:46
	 */
	function showChangeRead($listEqu){
		$str="";
		$i = 0;
		if($listEqu){
			foreach ($listEqu as $key => $val) {
				$i++;
				$str.=<<<EOT
					<tr height="28" align="center">
						<td>
							$i
						</td>
						<td>
							$val[productNumb] <br/> $val[productName]
						</td>
						<td>
							$val[objectsNumb]
						</td>
						<td>
							$val[purchTypeCName]
						</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[amountIssued]
						</td>
						<td>
							$val[dateIssued]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							<textarea rows="2" cols="23" readOnly>$val[remark]</textarea>
						</td>
					</tr>
EOT;
			}
		}
		return $str;
	}


/*****************************************页面模板显示结束********************************************/


/*****************************************显示分割线********************************************/

	/**
	 * 通过value查找状态
	 */
	function stateToVal($stateVal){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateVal']== $stateVal ){
				$returnVal = $val['stateCName'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

	/**
	 * 通过状态查找value
	 */
	function stateToSta($stateSta){
		$returnVal = false;
		foreach( $this->state as $key => $val ){
			if( $val['stateEName']== $stateSta ){
				$returnVal = $val['stateVal'];
			}
		}
		//TODO:添加异常操作
		return $returnVal;
	}

/*****************************************显示分割线********************************************/

	/**
	 * 采购任务-设备分页显示列表
	 */
	function pageList_d(){
		$rows = $this->pageBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_task_equipment();
			$planDao = new model_purchase_plan_basic();
			//调用接口组合数组将获取一个objAss的子数组
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					//"basicId" => $val['id']
					"basicNumb" => $val['taskNumb'],
//					"deviceIsUse" => "1",
					"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.id");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list_plan");
				if(is_array($chiRows)){
					foreach ($chiRows as $chdKey => $chdVal){
						//返回采购类型名称
						//$chiRows[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
						//$chiRows[$chdKey]['purchTypeCName'] = '销售采购';
						$chiRows[$chdKey]['purchTypeCName'] = $interfObj->typeKToC( $chdVal['ePurchType'] );		//类型名称

						if( !isset( $chdVal["amountAll"]) ||$chdVal["amountAll"]==""){
							$chiRows[$chdKey]["amountAll"] =  0;
						}

						if( !isset( $chdVal["amountIssued"]) ||$chdVal["amountIssued"]==""){
							$chiRows[$chdKey]["amountNotIssued"] =  $chiRows[$chdKey]["amountAll"];
						}else{
							$chiRows[$chdKey]["amountNotIssued"] =  $chiRows[$chdKey]["amountAll"] -  $chdVal["amountIssued"];
						}
					}
				}
				$rows[$i]['stateCName'] = $this->stateToVal( $rows[$i]['state'] );
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			$rows = $planDao->purchTypArrayCName($rows);
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * 采购设备-任务列表
	 */
	function listEqu_d(){
		$rows = $this->pageBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_task_equipment();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					//"stockTaskId" => $val['id']
					"basicNumb" => $val['taskNumb'],
					"deviceIsUse" => "1",
					//"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.id");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list");
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * @exclude 通过Id返回采购任务列表
	 * @author ouyang
	 * @param	$id 采购任务ID
	 * @return
	 * @version 2010-8-18 上午09:44:16
	 */
	function readTaskByBasicId_d ($id) {
		$searchArr = array (
					'id' => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('list_page');
		$i = 0;
		if($rows){
			//设置采购类型
			$planDao = new model_purchase_plan_basic();
			$equipment = new model_purchase_task_equipment();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					'basicId' => $val['id']
					//'status' => $equipment->statusToSta('execution')
				);
				$equipment->__SET('sort', 'p.productId');
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId('equipment_list');
				$chiRows = $planDao->purchTypArrayCName($chiRows);
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * 获取采购任务详细信息
	 * @param	$id 采购任务ID
	 */
	function readTask_d($id){
        $this->_isSetCompany =0;//是否单据是否要区分公司,1为区分,0为不区分
		$searchArr = array (
					"id" => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		$i = 0;
		if($rows){
			$equipment = new model_purchase_task_equipment();
			$planDao = new model_purchase_plan_basic();
			$planEquDao=new model_purchase_plan_equipment();
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
				$equipment->resetParam();
				$searchArr = array (
					"basicId" => $val['id'],
					"basicNumb" => $val['taskNumb']
//					"deviceIsUse" => "1",
					 //"status" => $equipment->statusToSta("execution")
				);
				$equipment->__SET('groupBy', "p.id");
				$equipment->__SET('sort', "p.productId");
				$equipment->__SET('searchArr', $searchArr);
				$chiRows = $equipment->listBySqlId("equipment_list");
				if(is_array($chiRows)){
					foreach ($chiRows as $keyChild => $valChild){
						 //获取采购计划的设备信息
					    $planEquRows=$planEquDao->get_d($valChild['planEquId']);
						//返回采购类型名称
						$chiRows[$keyChild]['purchTypeCName'] = $interfObj->typeKToC( $valChild['purchType'] );
					}
				}
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * @exclude 关闭采购任务
	 * @author ouyang
	 * @param	$numb 采购任务编号
	 * @return
	 * @version 2010-8-10 下午04:30:12
	 */
	function close_d($numb){
		$equDao = new model_purchase_task_equipment();
//		$equDao->closeTaskEqu_d($numb);
		$condition = array(
					"taskNumb" => $numb
				);
		$obj = array(
					"state" => $this->stateToSta('close'),
					"dateFact" => date("Y-m-d"),
					"ExaStatus" => "关闭"
				);
		return parent::update ( $condition, $obj );
	}

	/**
	 * @exclude 重新启动采购任务
	 * @param	$尖 采购任务ID
	 */
	function startTask_d($id){
		$condition = array(
					"id" => $id
				);
		$obj = array(
					"state" => $this->stateToSta('execute')
				);
		return parent::update ( $condition, $obj );
	}

	/**
	 * @exclude 完成采购任务控制器方法
	 * @author ouyang
	 * @param 采购任务Id
	 * @return 0:异常，1:完成,2：不可完成
	 * @version 2010-8-10 下午04:26:05
	 */
	function end_d ($id) {
		 $numb = $this->findNumbById_d($id);
		$equDao = new model_purchase_task_equipment();
		if( $equDao->endTaskByBasicId_d($numb) ){
			$obj = array(
					"id" => $id,
					"state" => $this->stateToSta('end'),
					"dateFact" => date("Y-m-d")
			);
			if( parent::updateById($obj) ){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 2;
		}
	}

	/**
	 * 批量采购任务状态的状态为完成
	 */
	function updateData_d() {
		try {
			$this->start_d ();
			$searchArr ['state'] = $this->stateToSta('execute');
			$this->searchArr=$searchArr;
			$rows = $this->listBySqlId ( 'list_page' );
			if ($rows) {
				foreach ( $rows as $key =>$val) {
					$this->end_d($val['id']);
				}
			}
			$this->commit_d ();
			return 1;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return 0;
		}
	}

	/**
	 * @exclude 通过Id找编号
	 * @author ouyang
	 * @param $id 业务Id
	 * @return 业务编号
	 * @version 2010-8-10 下午07:06:51
	 */
	function findNumbById_d ($id) {
		$searchArr = array (
					"id" => $id
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			return $rows['0']['taskNumb'];
		}else{
			return false;
		}
	}

	/**
	 * @exclude 通过采购任务编号查询状态
	 * @author ouyang
	 * @param	$basicNumb 采购任务编号
	 * @return
	 * @version 2010-8-10 下午11:46:07
	 */
	function getStateByNumb ($basicNumb) {
		$searchArr = array (
					"taskNumbMy" => $basicNumb,
//					"isUse" => "1"
		);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId("list_page");
		if($rows){
			return $rows['0']['state'];
		}else{
			return false;
		}
	}



	/**
	 * @exclude 变更获取采购计划详细信息
	 * @author ouyang
	 * @param	$id  采购任务ID
	 * @param	$basicNumb 采购任务编号
	 * @return
	 * @version 2010-8-17 下午06:26:56
	 */
	function toChange_d($id,$numb){
		$searchArr = array (
					'id' => $id
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('list_page');
		$i = 0;
		if($rows){
			//设置采购类型
			$planDao = new model_purchase_plan_basic();
			$rows = $planDao->purchTypArrayCName($rows);
			$equDao = new model_purchase_task_equipment();
			$interfObj = new model_common_interface_obj();
			foreach($rows as $key => $val){
				$equDao->resetParam();
				$searchArr = array (
					//'basicId' => $val['id']
					'basicNumb' => $val['taskNumb'],
					'deviceIsUse' => '1',
					'status' => $equDao->statusToSta('execution')
				);
				$equDao->__SET('searchArr', $searchArr);
				$chiRows = $equDao->listBySqlId('equipment_list_plan');
				foreach($chiRows as $chdKey => $chdVal){
					//$chiRows[$chdKey]['purchTypeCName'] = $planDao->purchTypeToVal( $chdVal['typeTabName'] );
					//$chiRows[$chdKey]['purchTypeCName'] = '销售采购';
					$chiRows[$chdKey]['purchTypeCName'] = $interfObj->typeKToC( $chdVal['ePurchType'] );		//类型名称
				}
				$rows[$i]['childArr']=$chiRows;
				++$i;
			}
			return $rows;
		}
		else {
			return false;
		}
	}

	/**
	 * 申请变更添加方法
	 *author can
	 *2011-1-12
	 */
	function change_d($obj) {
		$this->start_d ();
			$taskEquDao = new model_purchase_task_equipment();
		$changeLogDao = new model_common_changeLog ( 'purchasetask',false );
		//变更记录,拿到变更的临时主对象id
		$tempObjId = $changeLogDao->addLog ( $obj );

		$oldTask=parent::get_d($obj['oldId']);
		$obj['id']=$obj['oldId'];
		$obj['isTemp']=1;

		$emailArr = $obj['email'];
		unset($obj['email']);
		$flag=parent::edit_d ( $obj );

		$supDao = new model_purchase_plan_equipment();	//实例化对象
		$planDao=new model_purchase_plan_basic();
		$applyEquDao=new model_asset_purchase_apply_applyItem();
		$applyDao=new model_asset_purchase_apply_apply();
		foreach($obj['equment'] as $key => $val){
			$oldEqu=$taskEquDao->get_d( $val ['oldId'] );

			//判断数量是否合适
			$amountJudge = ( $oldEqu['amountAll']>=$val['amountAll'] && $oldEqu['contractAmount']<=$val['amountAll'] );
			if( !$amountJudge ){
				throw new Exception('变更后的数量不能小于已下达数量！');
			}
				$val['id']= $val ['oldId'];
				$taskEquDao->edit_d ( $val );

			//变更处理
			if($oldEqu['amountAll']!=$val['amountAll']&&$val['isClose']=="on" ){
				if($oldEqu['purchType']=='oa_asset_purchase_apply'){
					//更新采购申请状态为执行中
					$applyDao->updateField('id='.$oldEqu['applyId'],'purchState','0');
					//更新上级下达数量
					$applyEquDao->updateAmountIssued ( $oldEqu ['applyEquId'], 0,$oldEqu['amountAll']-$val['amountAll'] );
				}else{
					//更新采购申请状态为执行中
					$planDao->updateField('id='.$val['planId'],'state','0');
					//更新上级下达数量
					$supDao->updateAmountIssued ( $oldEqu ['planEquId'], 0,$oldEqu['amountAll']-$val['amountAll'] );
					$supDao->updateField('id='.$oldEqu['planEquId'],'isTask','0');//更新物料的状态为已下达任务
				}
			}
		}

		//发送邮件 ,当操作为提交时才发送
		if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,'变更',$obj['taskNumb'],$emailArr['TO_ID'],$addmsg=null,1);
		}
		$this->commit_d ();
		return $flag;
	}

	/**
	 * @exclude 接收变更
	 * @author ouyang
	 * @param	$id  采购任务ID
	 * @param	$numb 采购任务编号
	 * @version 2010-8-18 上午10:14:37
	 */
	function receiveChange_d ($id,$numb) {
		try {
			$this->start_d ();
			$oldCondition = array(
				'taskNumb' => $numb,
				'isUse' => '1'
			);
			$oldObject = array(
				'state' => $this->stateToSta('close')
			);
			$this->update($oldCondition,$oldObject);

			$equDao = new model_purchase_task_equipment();
			$oldConditionEqu = array(
				'basicNumb' => $numb,
				'deviceIsUse' => '1'
			);
			$oldObjectEqu = array(
				'deviceIsUse' => '0'
			);
			$equDao->update($oldConditionEqu,$oldObjectEqu);

			$newCondition = array(
				'taskNumb' => $numb,
				'state' => $this->stateToSta('change')
			);
			$newObject = array(
				'state' => $this->stateToSta('execute'),
				'isUse' => '1'
			);
			$this->update($newCondition,$newObject);

			$newConditionEqu = array(
				'basicId' => $id
			);
			$newObjectEqu = array(
				'deviceIsUse' => '1'
			);
			$equDao->update($newConditionEqu,$newObjectEqu);

			//TODO:采购计划变更接收 更新合同下达数量
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * @exclude 退回变更
	 * @author ouyang
	 * @param	$id  采购任务ID
	 * @param	$numb 采购任务编号
	 * @version 2010-8-18 上午10:14:52
	 */
	function returnChange_d ($id,$numb) {
		try {
			$this->start_d ();
			$oldCondition = array(
				'taskNumb' => $numb,
				'isUse' => '1'
			);
			$oldObject = array(
				'state' => $this->stateToSta('execute')
			);
			$this->update($oldCondition,$oldObject);

			$newCondition = array(
				'taskNumb' => $numb,
				'state' => $this->stateToSta('change')
			);
			$newObject = array(
				'state' => $this->stateToSta('close'),
				'isUse' => '0'
			);
			$this->update($newCondition,$newObject);

			//TODO:采购计划变更拒绝 更新合同下达数量
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	/**
	 * @desription 采购任务-设备分页显示列表(方法名别用大写)
	 * @param tags
	 * @date 2010-12-23 下午02:31:45
	 */
	function PageTask_d(){
		$rows = $this->pageBySqlId("list_page");
		if( is_array( $rows ) ){
			$equipment = new model_purchase_task_equipment();
			$rows = $equipment->getTaskAsEqu_d( $rows );
			foreach($rows as $key => $val){
				$rows[$key]['stateC'] = $this->statusDao->statusKtoC( $rows[$key]['state'] );  //状态中文
			}
			return $rows;
		}else{
			return false;
		}
	}

	/**
	 * 重写新增采购任务
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			$object['state'] = $this->statusDao->statusEtoK('begin');
			$object['objCode'] = $this->objass->codeC( "purch_task" );
			$codeDao=new model_common_codeRule();
			$object['taskNumb'] = $codeDao->purchTaskCode($this->tbl_name,$object['sendUserId']);
			$object['isTemp'] = 0;
			$taskId = parent::add_d ( $object,true );

			$equipmentDao = new model_purchase_task_equipment();
			$applyEquDao=new model_asset_purchase_apply_applyItem();
			$applyDao=new model_asset_purchase_apply_apply();
			$emailEquArr=array();//下达数量与申请数量不等的物料
			$planIds=array();
			$assetIds=array();//固定资产申请ID
			foreach( $object['equment'] as $key => $val ){
//				if($val['amountAll']!==$val['planAmonut']){//判断是否全部下达任务
//					array_push($emailEquArr,$object['equment'][$key]);
//				}
				$val['objCode'] = $this->objass->codeC( 'purch_task_equ' );
				$val['basicNumb'] = $object["taskNumb"];
				$val['basicId'] = $taskId;
				$val['status'] = $equipmentDao->statusDao->statusEtoK('execution');
				$equId=$equipmentDao->add_d( $val );
				if($val['purchType']!="oa_asset_purchase_apply"){//区分是否由固定资产过来的采购任务
					//设置总关联表数据，这里根据设备清单id去找总关联表，如果要更新的数据为空，则进行update操作，否则复制不为空的数据进行新增操作
					$this->objass->saveModelObjs("purch",array("planEquId"=>$val['planEquId']),array("taskCode"=>$object['taskNumb'],"taskId"=>$taskId,"taskEquId"=>$equId));
					//更新采购计划设备的已下达数量
					$planEquDao=new model_purchase_plan_equipment();
					$planEquDao->updateAmountIssued($val['planEquId'],$val['amountAll']);
					$planEquDao->updateField('id='.$val['planEquId'],'isTask','1');//更新物料的状态为已下达任务
					array_push($planIds,$val['planId']);//采购申请id
				}else{
					//更新采购计划设备的已下达数量
					$applyEquDao->updateAmountIssued($val['applyEquId'],$val['amountAll']);
					$applyDao->updatePurchState_d($val['applyId']);

				}
			}
			$planIdArr=array_unique($planIds);
			if(!empty($planIdArr)){//如果采购申请的物料已下达完成，则更新状态为“已关闭”
				$planArrStr=implode(',',$planIdArr);
				$planDao=new model_purchase_plan_basic();
				$planDao->updateData_d($planArrStr);
			}

			//发送邮件通知采购负责人
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['sendUserId'];
			$emailArr['TO_NAME']=$object['sendName'];
			if(is_array($object ['equment'])){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编码</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>任务数量</b></td><td><b>采购申请编号</b></td><td><b>采购类型</b></td><td><b>源单编号 </b></td><td><b>希望完成时间</b></td><td><b>备注</b></td></tr>";
				foreach($object ['equment'] as $key => $equ ){
					$j++;
					$productNumb=$equ['productNumb'];
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$amountAll=$equ ['amountAll'];
					$applyNum=$equ ['applyNum'];
					$purchTypeCName=$equ['purchTypeCName'];
					$sourceNum=$equ['sourceNum'];
					$dateHope=$equ['dateHope'];
					$remark=$equ['remark']." ";
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productNumb</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$applyNum</td>
							<td>$purchTypeCName</td>
							<td>$sourceNum</td>
							<td>$dateHope</td>
							<td>$remark </td>
						</tr>
EOT;
					}
					$addmsg.="</table>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,',采购任务单据号为：<font color=red><b>'.$object["taskNumb"].'</b></font>','',$emailArr['TO_ID'],$addmsg,1);
            //再发送一份邮件邮件给申请人
            if(is_array($object ['equment']) && $object ['equment'][0]['sendUserId'] != '') {
                $emailArr['TO_ID2'] = $object ['equment'][0]['sendUserId'];
                $emailInfo2 = $emailDao->purchTaskEmail($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, ',采购任务单据号为：<font color=red><b>' . $object["taskNumb"] . '</b></font>', '', $emailArr['TO_ID2'], $addmsg, 1);
            }

		/*	if(!empty($emailEquArr)){
				//发送邮件通知采购申请人
				$emailArr=array();
				$emailSendIdArr=array();
				$emailArr['SENDID']="";
				$emailArr['SENDNAME']="";
					$k=0;
					$equaddmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>采购申请数量</b></td><td><b>下达任务数量</b></td><td><b>采购申请编号</b></td><td><b>采购类型</b></td><td><b>源单编号 </b></td><td><b>希望完成时间</b></td><td><b>备注</b></td></tr>";
					foreach($emailEquArr as $key => $equ ){
						array_push($emailSendIdArr,$equ['sendUserId']);
						$k++;
						$productName=$equ['productName'];
						$pattem=$equ ['pattem'];
						$unitName=$equ ['unitName'];
						$planAmount=$equ ['planAmonut'];
						$amountAll=$equ ['amountAll'];
						$applyNum=$equ ['applyNum'];
						$purchTypeCName=$equ['purchTypeCName'];
						$sourceNum=$equ['sourceNum'];
						$dateHope=$equ['dateHope'];
						$remark=$equ['remark']." ";
						$equaddmsg .=<<<EOT
						<tr align="center" >
								<td>$k</td>
								<td>$productName</td>
								<td>$pattem</td>
								<td>$unitName</td>
								<td>$planAmount</td>
								<td>$amountAll</td>
								<td>$applyNum</td>
								<td>$purchTypeCName</td>
								<td>$sourceNum</td>
								<td>$dateHope</td>
								<td>$remark </td>
							</tr>
EOT;
						}
						$equaddmsg.="</table>";
				$emailArr['SENDID']=implode(',',$emailSendIdArr);
				$emailInfo = $emailDao->purchTaskFeedbackEmail('y',$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchPlanFeedback','','',$emailArr['SENDID'],$equaddmsg,1);

			}*/
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

	/**
	 * @desription 接收采购任务
	 * @param	$id  采购任务ID
	 * @date 2010-12-23 下午04:28:54
	 */
	function stateBegin_d($id){
		$obj = array(
					"id" => $id,
					"state" => $this->statusDao->statusEtoK("execute"),
					"dateReceive" => date("Y-m-d"),
					"updateTime" => date ( "Y-m-d H:i:s" )
				);
		return parent::edit_d($obj,true);
	}

	/**获取采购申请人
	 * @author Administrator
	 *
	 */
	 function getApplyMan_d($equRows){
	 	$applyArr=array();
	 	if(is_array($equRows)){
			$planDao=new model_purchase_plan_basic();
			$interfObj = new model_common_interface_obj();
//			$orderContractDao = new model_projectmanagent_order_order();
	 		foreach($equRows as $key=>$val){
	 			if($val['planId']){
	 				$sendIdSource=array();
	 				$sendNameSource=array();
					$planRows=$planDao->get_d($val['planId']);
					$applyArr['sendId'][$key]=$planRows['sendUserId'];
					$applyArr['sendName'][$key]=$planRows['sendName'];
					if($planRows['purchType']=="oa_sale_order"||$planRows['purchType']=="oa_sale_lease"||$planRows['purchType']=="oa_sale_service"||$planRows['purchType']=="oa_sale_rdproject"){
						$supDaoName = $interfObj->typeKToObj( $planRows['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						$sourceRow=$orderContractDao->getCusinfoByorder($planRows['purchType'],$planRows['sourceID']);
						array_push($sendIdSource,$sourceRow['prinvipalId']);
						array_push($sendNameSource,$sourceRow['prinvipalName']);
					}else if($planRows['purchType']=="oa_borrow_borrow"||$planRows['purchType']=="oa_present_present"){
						$supDaoName = $interfObj->typeKToObj( $planRows['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						$sourceRow=$supDao->getInfoList($planRows['sourceID']);
						array_push($sendIdSource,$sourceRow['createId']);
						array_push($sendNameSource,$sourceRow['createName']);
					}
					if($planRows['purchType']=="HTLX-XSHT"||$planRows['purchType']=="HTLX-ZLHT"||$planRows['purchType']=="HTLX-FWHT"||$planRows['purchType']=="HTLX-YFHT"){
						$supDaoName = $interfObj->typeKToObj( $planRows['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						$sourceRow=$supDao->getInfoList($planRows['sourceID']);
						array_push($sendIdSource,$sourceRow['prinvipalId']);
						array_push($sendNameSource,$sourceRow['prinvipalName']);
					}
	 			}
	 		}
	 		if(!empty($sendIdSource)){
	 			foreach($sendIdSource as $sKey=>$sVal){
					array_push($applyArr['sendId'],$sVal);
					array_push($applyArr['sendName'],$sendNameSource[$sKey]);
	 			}
	 		}
	 	}
	 	return $applyArr;
	 }

	 /**采购任务反馈
	 * @author Administrator
	 *
	 */
	 function feedback_d($object){
		try {
			$this->start_d ();
			//更新采购任务为已反馈
			$flag=$this->updateField('id='.$object['id'],'feedback','1');
			//发送邮件
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$object['email']['TO_ID'];
			$emailArr['TO_NAME']=$object['email']['TO_NAME'];
			if(is_array($object ['equment'])){
				$interfObj = new model_common_interface_obj();
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>任务数量</b></td><td><b>希望完成时间</b></td><td><b>采购申请编号</b></td><td><b>采购类型</b></td><td><b>源单编号 </b></td><td><b>源单名称 </b></td><td><b>项目名称 </b></td><td><b>申请/使用人</b></td><td><b>申请原因</b></td></tr>";
				foreach($object ['equment'] as $key => $equ ){
					$j++;
					$productName=$equ['productName'];
					$pattem=$equ ['pattem'];
					$unitName=$equ ['unitName'];
					$amountAll=$equ ['amountAll'];
					$applyNum=$equ ['planNumb'];
					$purchTypeCName=$equ['purchTypeCName'];
					$sourceNum=$equ['sourceNumb'];
					$dateHope=$equ['dateHope'];
					$contractName=$equ['contractName'];
					$projectName=$equ['projectName'];
					$sendName=$equ['sendName'];
					if($equ['purchType']=="oa_sale_order"||$equ['purchType']=="oa_sale_lease"||$equ['purchType']=="oa_sale_service"||$equ['purchType']=="oa_sale_rdproject"){
						$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						$sourceRow=$orderContractDao->getCusinfoByorder($equ['purchType'],$equ['sourceID']);
						$sendName=$sourceRow['prinvipalName'];
					}else if($equ['purchType']=="oa_borrow_borrow"||$equ['purchType']=="oa_present_present"){
						$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						$sourceRow=$supDao->getInfoList($equ['sourceID']);
						$sendName=$sourceRow['createName'];
					}
					if($equ['purchType']=="HTLX-XSHT"||$equ['purchType']=="HTLX-ZLHT"||$equ['purchType']=="HTLX-FWHT"||$equ['purchType']=="HTLX-YFHT"){
						$supDaoName = $interfObj->typeKToObj( $equ['purchType'] );  //获取采购类型对象名称
						$supDao = new $supDaoName();	//实例化对象
						$sourceRow=$supDao->getInfoList($equ['sourceID']);
						$sendName=$sourceRow['prinvipalName'];
					}
					$applyReason='';
					if($equ['planId']>0){
						$applyReason=$this->get_table_fields('oa_purch_plan_basic','id='.$equ['planId'],'applyReason');
					}
					$addmsg .=<<<EOT
					<tr align="center" >
							<td>$j</td>
							<td>$productName</td>
							<td>$pattem</td>
							<td>$unitName</td>
							<td>$amountAll</td>
							<td>$dateHope</td>
							<td>$applyNum</td>
							<td>$purchTypeCName</td>
							<td>$sourceNum</td>
							<td>$contractName</td>
							<td>$projectName</td>
							<td>$sendName</td>
							<td align='left'>$applyReason</td>
					</tr>
EOT;
					}
					$addmsg.="</table><br/>";
					$addmsg.="反馈内容：<br/>     ";
					$addmsg.="<font color='blue'>".$object['feedbackRemark']."</font>";
			}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->purchTaskFeedbackEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'purchTaskFeedback','','',$emailArr['TO_ID'],$addmsg,1);
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	 }

}
?>
