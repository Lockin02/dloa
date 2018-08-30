<?php
/**
 * ͼ�α�����Ʋ�
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
	 * ��ת������ҳ��
	 */
	function c_toReport() {
		$this->view('report');
	}

	/**
	 * ��ȡ��������
	 */
	function c_ajaxReport() {

		//��ȡ����
		$analysisData=$_POST['analysisData'];//ͳ������ֵ
		$analysisDataText=util_jsonUtil::iconvUTF2GB ($_POST['analysisDataText']);//ͳ����������
		$analysisConditionData=$_POST['analysisConditionData'];//ͳ������
		$analysisConditionValue=$_POST['analysisConditionValue'];//ͳ������ֵid
		$analysisTypeData=$_POST['analysisTypeData'];//ͳ�Ʒ���
		$analysisTypeValue=$_POST['analysisTypeValue'];//ͳ�Ʒ���ֵid

		$analysisCondition=$_POST['analysisCondition'];//ͳ������ֵ
		$analysisType=$_POST['analysisType'];//ͳ�Ʒ���ֵ

		$startTime=$_POST['startTime'];
		$endTime=$_POST['endTime'];
		$whereSqlPlus="";
		//ʱ�䴦��
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

		if(empty($analysisTypeData)||empty($analysisType)){//һά����
			$reRows=$this->getContractRows($analysisData,$analysisConditionArr,$whereSqlPlus);
		}else{
			$reRows=array();

			$array=explode(",",$analysisType);
			$varray=explode(",",$analysisTypeValue);
			//ͬ�����Դ���
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
		//����ǽ��,С����λ������ǿ�Ʋ�0
		if(strpos($analysisData,"Money")>0){
			$forceDecimals=1;
		}

		//����Ĭ������
		$chartConf = array (
			'caption' => "��".$analysisDataText."ͳ��",
			'forceDecimals'=>$forceDecimals
		);
		$charts = new model_common_fusionCharts(false);
		//��֮ǰ��������
//		ob_clean();
//		ob_end_flush();
		//echo $sql;
		//print_r($reRows);
		//�������ݵ���ȷ��


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
	 * ��ȡ��ͬ��������
	 * state:��ͬ״̬
	 *0 δ�ύ
	 *1 ������
	 *2 ִ����
	 *3 �ѹر�
	 *4 ��ִ��
	 *5 �Ѻϲ�
	 *6 �Ѳ��
	 *7 �쳣�ر�
	 */
	function getContractRows($analysisData,$analysisConditionArr,$whereSqlPlus){
		$analysisConditionData=$analysisConditionArr['data'];
		$analysisCondition=$analysisConditionArr['value'];
		$condition="(o.ExaStatus='���' or o.ExaStatus='���������')and(o.state!=0 and o.state!=5 and o.state!=6) and o.isTemp=0 $whereSqlPlus";
		$countSql="";//����ͳ��sql
		$sqlPlus="";//������������
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
		//��ͬ����
		if($analysisConditionData=='o.contractType'){
			$groupby="o.contractType";
			$analysisConditionData="case  " .
				"when o.contractType='HTLX-XSHT' then '���ۺ�ͬ' " .
				"when o.contractType='HTLX-FWHT' then '���޺�ͬ'" .
				"when o.contractType='HTLX-ZLHT' then '�����ͬ'" .
				"when o.contractType='HTLX-YFHT' then '�з���ͬ'" .
				"else '' end  " ;
		}

		//��������
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


		//��ȡ����
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
	 * ����excel
	 */
	function c_exportExcel(){
		$data=$_POST['data'];//һ����ά���飬��һά�У��ڶ�ά�У������ֵ
		//var_dump($data);
		$this->service->exportExcel($data);
	}

}
?>