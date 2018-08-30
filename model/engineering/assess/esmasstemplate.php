<?php

/**
 * @author Show
 * @Date 2012年11月27日 星期二 19:45:15
 * @version 1.0
 * @description:考核模板表 Model层
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
			return '是';
		}else{
			return '否';
		}
	}

	//渲染编辑
	function initEdit_d($object){
		$str = '';

		//实例化指标类
		$esmassindexDao = new model_engineering_assess_esmassindex();
		$esmassindexArr = $esmassindexDao->getIndexs_d($object['indexIds']);

		//必填选项
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

	//渲染指标查看信息
	function initDetail_d($object){
		$str = '';

		//实例化指标类
		$esmassindexDao = new model_engineering_assess_esmassindex();
		$esmassindexArr = $esmassindexDao->getIndexs_d($object['indexIds']);

		//必填选项
		$needIndexsArr = explode(',',$object['needIndexIds']);
		foreach($esmassindexArr as $key => $val){
			if(in_array($val['id'],$needIndexsArr)){
				$isNeed = '是';
			}else{
				$isNeed = '否';
			}
			$str .=<<<EOT
				<tr><td>$val[name]</td><td>$val[upperLimit]</td><td>$isNeed</td></tr>
EOT;
		}
		return $str;
	}
}
?>