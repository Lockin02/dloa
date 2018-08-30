<?php
/**
 * @author Administrator
 * @Date 2013��10��8�� 10:16:55
 * @version 1.0
 * @description:��Ʒ��汨�������Ϣ Model��
 */
 class model_report_report_stockinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_report_stockinfo";
		$this->sql_map = "report/report/stockinfoSql.php";
		parent::__construct ();

		//
        $this->dataType = array (
				"0" => "��Ʒ�������",
				"1" => "��Ʒ���ϱ�����",
			);
	}


	/**
	 *  �������ֵ��ȡ֧�����粢����
	 */
	 function getNetWorkStr($row = null){
	 	 $str = ""; //���ص�ģ���ַ���
	 		$datadictDao = new model_system_datadict_datadict();
	 	    $arr = $datadictDao->getDatadictsByParentCodes("ZCWLLX");
	 	    $newWorkArr = explode(",",$row['netWork']);

	 	    if(!empty($arr['ZCWLLX'])){
	 	 	 foreach($arr['ZCWLLX'] as $k => $v){
	 	 	 	$checked = "";
	 	 	 	$tempName = $v["dataName"];
	 	 	 	if(in_array($v["dataName"],$newWorkArr)){
	 	 	 		$checked = "checked=checked";
	 	 	 	}
	 	 	 	$str .=<<<EOT
					<input type="checkbox" name="newWorkStr" value="$tempName" onclick="checkChooseNewWork();" $checked/>$tempName
EOT;
	 	 	 }
	 	 }

        return $str;
	 }
	 function getsoftWareStr($row = null){
	 	 $str = ""; //���ص�ģ���ַ���
	 	 $datadictDao = new model_system_datadict_datadict();
	 	 $arr = $datadictDao->getDatadictsByParentCodes("ZCRJZD");
	 	 $softwareArr = explode(",",$row['software']);

	 	 $checked = false;
	 	 if(!empty($arr['ZCRJZD'])){
	 	 	 foreach($arr['ZCRJZD'] as $k => $v){
	 	 	 	$checked = "";
	 	 	 	$tempName = $v["dataName"];
	 	 	 	if(in_array($v["dataName"],$softwareArr)){
	 	 	 		$checked = "checked=checked";
	 	 	 	}
	 	 	 	$str .=<<<EOT
					<input type="checkbox" name="softWareStr" value="$tempName" onclick="checkChoosesoftWare();" $checked/>$tempName
EOT;
	 	 	 }
	 	 }
        return $str;
	 }
 }
?>