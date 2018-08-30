<?php

/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 19:45:15
 * @version 1.0
 * @description:����ģ��� Model��
 */
class model_engineering_assess_esmasstemplate extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_ass_template";
		$this->sql_map = "engineering/assess/esmasstemplateSql.php";
		parent :: __construct();
	}

	//yn
	function rtYesOrNo_d($thisVal){
		if($thisVal == 1){
			return '��';
		}else{
			return '��';
		}
	}

	//��Ⱦ�༭
	function initEdit_d($object){
		$str = '';

		//ʵ����ָ����
		$esmassindexDao = new model_engineering_assess_esmassindex();
		$esmassindexArr = $esmassindexDao->getIndexs_d($object['indexIds']);

		//����ѡ��
		$needIndexsArr = explode(',',$object['needIndexIds']);
		foreach($esmassindexArr as $key => $val){
			if(in_array($val['id'],$needIndexsArr)){
				$checked = 'checked="checked"';
			}else{
				$checked = '';
			}
			$str .=<<<EOT
				<tr id="tr$val[id]"><td>$val[name]</td><td>$val[upperLimit]</td><td><input type="checkbox" id="chk$val[id]" $checked onclick="checkNeeds();" score="$val[upperLimit]" indexName="$val[name]" indexId="$val[id]"/></td></tr>
EOT;
		}
		return $str;
	}

	//��Ⱦָ��鿴��Ϣ
	function initDetail_d($object){
		$str = '';

		//ʵ����ָ����
		$esmassindexDao = new model_engineering_assess_esmassindex();
		$esmassindexArr = $esmassindexDao->getIndexs_d($object['indexIds']);

		//����ѡ��
		$needIndexsArr = explode(',',$object['needIndexIds']);
		foreach($esmassindexArr as $key => $val){
			if(in_array($val['id'],$needIndexsArr)){
				$isNeed = '��';
			}else{
				$isNeed = '��';
			}
			$str .=<<<EOT
				<tr><td>$val[name]</td><td>$val[upperLimit]</td><td>$isNeed</td></tr>
EOT;
		}
		return $str;
	}
}
?>