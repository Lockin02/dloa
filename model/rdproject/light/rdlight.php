<?php

class model_rdproject_light_rdlight extends model_base{

	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-10-24 上午11:22:05
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_light";
		$this->sql_map = "rdproject/light/rdlightSql.php";
		$this->pk = "id";
		parent::__construct();

	}
	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------
	 *************************************************************************************************/
	 /**
	 * 页面显示调用方法,返回字符串给页面模板替换
	 */
	function showlist($rows) {
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2)==0)?"tr_even":"tr_odd";
				$str .=<<<EOT
					<tr class="$classCss" id="tr_$val[id]" pid="$val[id]">
						<td>$i</td>
						<td>$val[lightcol]</td>
						<td>$val[Max]%</td>
						<td>$val[Min]%</td>
EOT;
				}return $str;
			}else
			return	$str = "<tr><td colspan='10'>暂无相关信息</tr></td>";
	}


/**
 * @desription TODO
 * @param tags
 * @date 2010-10-25 上午10:31:05
 */
function c_returnArr () {

}













}
?>
