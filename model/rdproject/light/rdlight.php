<?php

class model_rdproject_light_rdlight extends model_base{

	/**
	 * @desription ���캯��
	 * @param tags
	 * @date 2010-10-24 ����11:22:05
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_light";
		$this->sql_map = "rdproject/light/rdlightSql.php";
		$this->pk = "id";
		parent::__construct();

	}
	/***************************************************************************************************
	 * ------------------------------����Ϊҳ��ģ����ʾ���÷���------------------------------
	 *************************************************************************************************/
	 /**
	 * ҳ����ʾ���÷���,�����ַ�����ҳ��ģ���滻
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
			return	$str = "<tr><td colspan='10'>���������Ϣ</tr></td>";
	}


/**
 * @desription TODO
 * @param tags
 * @date 2010-10-25 ����10:31:05
 */
function c_returnArr () {

}













}
?>
