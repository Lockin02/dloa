<?php
/**
 * 图形报表控制层
 */
class controller_common_fusionCharts extends controller_base_action {

	function __construct() {
		$this->objName = "fusionCharts";
		$this->objPath = "common";
		parent :: __construct();
	}

	static $analysisConditionArr=array(

	);

	/**
	 * 跳转到报表页面
	 */
	function c_toReport() {
		$this->view('report');
	}

	/**
	 * 获取报表数据
	 */
	function c_ajaxReport() {

		//获取参数
		$analysisData=$_POST['analysisData'];//统计数据值
		$analysisDataText=util_jsonUtil::iconvUTF2GB ($_POST['analysisDataText']);//统计数据中文
		$analysisConditionData=$_POST['analysisConditionData'];//统计条件
		$analysisConditionValue=$_POST['analysisConditionValue'];//统计条件值id
		$analysisTypeData=$_POST['analysisTypeData'];//统计分类
		$analysisTypeValue=$_POST['analysisTypeValue'];//统计分类值id

		$analysisCondition=$_POST['analysisCondition'];//统计条件值
		$analysisType=$_POST['analysisType'];//统计分类值

		$startTime=$_POST['startTime'];
		$endTime=$_POST['endTime'];
		$whereSqlPlus="";
		//时间处理
		if(!empty($startTime)){
			$whereSqlPlus.=" and o.createTime>='".$startTime." 00:00:00.0'";
		}
		if(!empty($endTime)){
			$whereSqlPlus.=" and o.createTime<='".$endTime." 00:00:00.0'";
		}
		$analysisConditionArr=array("data"=>$analysisConditionData,"value"=>$analysisCondition);
		if($analysisConditionData=='customerType'){
			$analysisConditionArr=array("data"=>$analysisConditionData,"value"=>$analysisConditionValue);
		}
		//$analysisTypeArr=array("data"=>$analysisTypeData,"value"=>$analysisType);
		//$analysisConditionArr['type']=$analysisTypeData;

		if(empty($analysisTypeData)||empty($analysisType)){//一维处理
			$reRows=$this->getContractRows($analysisData,$analysisConditionArr,$whereSqlPlus);
		}else{
			$reRows=array();

			$array=explode(",",$analysisType);
			$varray=explode(",",$analysisTypeValue);
			//同类属性处理
			if($analysisTypeData=='sameAttribute'){
				$reRows[$analysisDataText]=$this->getContractRows($analysisData,$analysisConditionArr,$whereSqlPlus);
				foreach($varray as $key=>$value){
					$array[$key] = util_jsonUtil::iconvUTF2GB ( $array[$key] );
					$reRows[$array[$key]]=$this->getContractRows($value,$analysisConditionArr,$whereSqlPlus);
				}
			}else{
				if($analysisTypeData=='contractType'){
					$analysisTypeData='contractType';
				}else if($analysisTypeData=='customerType'){
					$array=$varray;
				}
				foreach($array as $key=>$value){
					$value = util_jsonUtil::iconvUTF2GB ( $value );
					if($analysisTypeData=='contractType'){
						$sqlPlus=$whereSqlPlus." and $analysisTypeData='$varray[$key]'";
					}else{
						$sqlPlus=$whereSqlPlus." and $analysisTypeData='$value'";
					}
					if($analysisTypeData=='customerType'){
						$datadictDao=new model_system_datadict_datadict();
						$value=$datadictDao->getDataNameByCode($value);
					}
					$reRows[$value]=$this->getContractRows($analysisData,$analysisConditionArr,$sqlPlus);
				}
			}
			//print_r($reRows);
//			$mergeArr=array();
//			foreach($reRows as $key=>$valArr){
//				$mergeArr=array_merge($mergeArr,$valArr);
//			}
//
//			foreach($reRows as $key=>$valArr){
//				//foreach($valArr as $k=>$v){
//				foreach($mergeArr as $k1=>$v1){
//					if(empty($valArr[$k1])){
//						$reRows[$key][$k1]=0;
//					}
//				}
//			}

		}

		$forceDecimals=0;
		//如果是金额,小数点位数不够强制补0
		if(strpos($analysisData,"Money")>0){
			$forceDecimals=1;
		}

		//区域默认设置
		$chartConf = array (
			'caption' => "按".$analysisDataText."统计",
			'forceDecimals'=>$forceDecimals
		);
		$charts = new model_common_fusionCharts(false);
		//把之前输出的清掉
//		ob_clean();
//		ob_end_flush();
		//echo $sql;
		//print_r($reRows);
		//处理数据的正确性


		$oneSize=count($reRows);
		$width=$oneSize*100;
		//print_r($reRows);
		if(is_array($varray)){
			$mergeArr=array();
			foreach($reRows as $key=>$valArr){
				$mergeArr=array_merge($mergeArr,$valArr);
			}
			$oneSize=count($mergeArr);
			$twoSize=count($varray);
			$width=$twoSize*($oneSize*50);
		}
		if($width<800){
			$width=800;
		}
		if($width>2880){
			$width=2880;
		}
		echo $width;
		echo util_jsonUtil::iconvGB2UTF($charts->getMixCharXml($reRows, $chartConf));
	}


	/**
	 * 获取合同报表数据
	 * state:合同状态
	 *0 未提交
	 *1 审批中
	 *2 执行中
	 *3 已关闭
	 *4 已执行
	 *5 已合并
	 *6 已拆分
	 *7 异常关闭
	 */
	function getContractRows($analysisData,$analysisConditionArr,$whereSqlPlus){
		$analysisConditionData=$analysisConditionArr['data'];
		$analysisCondition=$analysisConditionArr['value'];
		$condition="(o.ExaStatus='完成' or o.ExaStatus='变更审批中')and(o.state!=0 and o.state!=5 and o.state!=6) and o.isTemp=0 $whereSqlPlus";
		$countSql="";//用于统计sql
		$sqlPlus="";//用于连接条件
		switch ($analysisData){
			case 'contractNum':
				$countSql="count(o.id)";
				break;
			case 'contractMoney':
				$countSql="sum(o.contractMoney)";//orderTempMoney
				break;
			case 'invoiceMoney':
				$countSql="sum(f.invoiceMoney)";
				$sqlPlus="left join financeview_is_03_sumorder f on o.id=f.objId ";
				break;
			case 'incomeMoney':
				$countSql="sum(f.incomeMoney)";
				$sqlPlus="left join financeview_is_03_sumorder f on o.id=f.objId ";
				break;
			case 'incomePercent':
				$countSql="sum(f.incomeMoney)/sum(o.contractMoney)*100";
				$sqlPlus="left join financeview_is_03_sumorder f on o.id=f.objId ";
				break;
			case 'invoicePercent':
				$countSql="sum(f.invoiceMoney)/sum(o.contractMoney)*100";
				$sqlPlus="left join financeview_is_03_sumorder f on o.id=f.objId";
				break;
		}
		$analysisConditionData="o.".$analysisConditionData;
		$groupby=$analysisConditionData;
		//合同性质
		if($analysisConditionData=='o.contractType'){
			$groupby="o.contractType";
			$analysisConditionData="case  " .
				"when o.contractType='HTLX-XSHT' then '销售合同' " .
				"when o.contractType='HTLX-FWHT' then '租赁合同'" .
				"when o.contractType='HTLX-ZLHT' then '服务合同'" .
				"when o.contractType='HTLX-YFHT' then '研发合同'" .
				"else '' end  " ;
		}

		//条件过滤
		$havingCondition="";
		if(!empty($analysisCondition)){
			if (util_jsonUtil::is_utf8 ( $analysisCondition )) {
				$analysisCondition = util_jsonUtil::iconvUTF2GB ( $analysisCondition );
			}
			$array=explode(",",$analysisCondition);
			foreach($array as $k=>$v){
				$array[$k]="'".$v."'";
			}
			$analysisCondition=implode(",",$array);
			$havingCondition=" having $analysisConditionData in ($analysisCondition)";
		}


		//获取数据
		$this->service->sort=$analysisConditionData;
		$sql="select $analysisConditionData as 'key',$countSql as 'val' from oa_contract_contract o " .
				" $sqlPlus where $condition  group by $groupby $havingCondition";
		echo $sql;
		$this->service->sort="val";
		$rows = $this->service->listBySql($sql);
		$reRows=array();
		$datadictDao=new model_system_datadict_datadict();
		foreach($rows as $key=>$val){
			if($analysisConditionData=='o.customerType'){
				$k=$datadictDao->getDataNameByCode($val['key']);
			}else{
				$k=$val['key'];
			}
			$reRows[$k]=$val['val'];
		}
		//print_r($rows);
		return $reRows;

	}


	/**
	 * 导出excel
	 */
	function c_exportExcel(){
		$data=$_POST['data'];//一个多维数组，第一维列，第二维行，最后是值
		//var_dump($data);
		$this->service->exportExcel($data);
	}

}
?>