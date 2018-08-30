<?php
/**
 * @description: 供应商临时库评估信息
 * @date 2010-11-10 下午02:07:59
 * @author oyzx
 * @version V1.0
 */
class model_supplierManage_temporary_stasse extends model_base{

	function __construct(){
		$this->tbl_name = "oa_supp_asse_temp";
		$this->sql_map = "supplierManage/temporary/stasseSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

/*****************************************以下是关于供应商注册页面的评价********************************************/

	/**
	 * @desription 显示方法
	 * @param tags
	 * @date 2010-11-11 上午09:52:24
	 */
	function add_s ( $arr ) {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$str = "";
		$strOption = $this->xiala_s();
		if( is_array($arr) ){
			$arrCount = count( $arr );

			$dataArr3=end($arr);
			//原来的循环是$i<($arrCount-1);也没有$dataArr3这个变量;
			//这个循环的意思是，分别把几个评估选项的值赋给变量，变成数组。
			for($i=0;$i< ($arrCount);$i+=3 ){
				$i2=$i+1;
				$dataArr=$arr[$i];
				$dataArr2=$arr[$i+1];
				$dataArr3=$arr[$i+2];
				$str .=<<<EOT
		<tr>
			<td class="form_text_left">$dataArr[dataName]</td>
			<td class="form_text_right">
				<select class="select" id="$dataArr[dataCode]" name="temporary[typeCode][$dataArr[dataCode]]">
					$strOption
				</select>
			</td>
			<td class="form_text_left">$dataArr2[dataName]</td>
			<td class="form_text_right">
				<select class="select" id="$dataArr2[dataCode]" name="temporary[typeCode][$dataArr2[dataCode]]">
					$strOption
				</select>
			</td>
			<td class="form_text_left">$dataArr3[dataName]</td>
			<td class="form_text_right" colspan="3">
				<select class="select" id="$dataArr3[dataCode]" name="temporary[typeCode][$dataArr3[dataCode]]">
					$strOption
				</select>
			</td>
		</tr>

EOT;
			}
		}
		return $str;
	}

	/**
	 * @desription 下拉
	 * @param tags
	 * @date 2010-11-11 上午10:30:27
	 */
	function xiala_s () {
		return <<<EOT
		<option value="良">良</option>
		<option value="优">优</option>
		<option value="一般">一般</option>
		<option value="差">差</option>
EOT;
	}

/*****************************************以上是关于供应商注册页面的评价********************************************/



	/**
	 * @desription 显示查看初步评价列表
	 * @param tags
	 * @date 2010-11-24 上午10:39:42
	 */
	function showAsseList ($arr) {
		$str = "";
		if($arr){
			$str .=<<<EOT
				<tr>
EOT;
			foreach($arr as $key=>$val){
				$opinion = $val[opinion];
				$str .=<<<EOT
					<td>$val[typeCode]</td>
EOT;
			}
		}else{
			return $str = "<tr><td colspan='4'>暂无相关评价</td></tr>";
		}
		$str .=<<<EOT
			<td>$opinion</td>
			</tr>
EOT;
		return $str;
	}

/**#######################################以下是关于供应商注册页面的评价的编辑#######################################*/
	/**
	 * @desription 用于编辑评价页面显示
	 * @param tags
	 * @return return_type
	 * @date 2010-11-25 下午08:26:07
	 */
	 function addAsse_s ( $arr ) {
		$parentId = isset( $_GET['parentId'] )?$_GET['parentId']:null;
		$str = "";
		if( is_array($arr) ){
			$i = 0;
			//通过循环把评价的内容显示出来
			foreach( $arr as $key => $val ){
				$typeCodeVal = $this->editAsseSelect_s( $val['typeCode'] );
				if( $i%2 == 0 ){
					$str .=<<<EOT
					<tr>
						<td class="form_text_left">$val[typeNameC]</td>
						<td class="form_text_right">
							<select id="$val[typeName]" name="typeCode[$i][typeCode]">
								$typeCodeVal
							</select>
							<input type="hidden" name="typeCode[$i][id]" value="$val[id]" />
							<input type="hidden" name="typeCode[$i][opinion]" value="$val[opinion]" />
							<input type="hidden" name="typeCode[$i][parentId]" value="$val[parentId]" />
						</td>
EOT;
				}else{
					$str .=<<<EOT
						<td class="form_text_left">$val[typeNameC]</td>
						<td class="form_text_right">
							<select id="$val[typeName]" name="typeCode[$i][typeCode]">
								$typeCodeVal
							</select>
							<input type="hidden" name="typeCode[$i][id]" value="$val[id]" />
							<input type="hidden" name="typeCode[$i][opinion]" value="$val[opinion]" />
							<input type="hidden" name="typeCode[$i][parentId]" value="$val[parentId]" />
						</td>
					</tr>
EOT;
				}
				++$i;
			}
			if( $i%2 != 0 ){
				$str .=<<<EOT
					<td class="form_text_left"></td>
					<td class="form_text_right"></td>
				</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * @desription 用于编辑评价的下拉框
	 * @param tags
	 * @date 2010-11-25 下午07:35:47
	 */
	function editAsseSelect_s ( $val ) {
		$sel0 = "";
		$sel1 = "";
		$sel2 = "";
		$sel3 = "";
		switch($val){
			case '良':$sel0="selected";break;
			case '优':$sel1="selected";break;
			case '一般':$sel2="selected";break;
			case '差':$sel3="selected";break;
		}

		$str =<<<EOT
			   <option value='良' $sel0>良</option>
			   <option value='优' $sel1>优</option>
			   <option value='一般' $sel2>一般</option>
			   <option value='差' $sel3>差</option>
EOT;
		return $str;

	}

		/**
	 * @desription 编辑供应商评价的数据检索方法
	 * @param tags
	 * @date 2010-11-26 上午11:12:44
	 */
	function stsList_d () {
		$arr = $this->list_d();
		$datadictDao = new model_system_datadict_datadict ();
//		echo "<pre>";
//		print_r($arr);
		foreach( $arr as $key => $val ){
			$arr[$key]['typeNameC'] = $datadictDao->getDataNameByCode( $val['typeName'] );
		}
		return $arr;
	}



/**#######################################以上是关于供应商注册页面的评价的编辑#######################################*/



	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/
	 /**
	 * @desription 审批列表数据方法
	 * @param tags
	 * @date 2010-11-17 下午04:52:28
	 */
	function suppApprovalPage_d () {
		$this->groupBy = "c.parentId";
		$rows = $this->pageBySqlId('select_Approval');
		if( is_array($rows) ){

		}
		return $rows;
	}

	function rpApprovalNo_s($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" pjId="$val[id]">
						<td>
							$i
						</td>
						<td>
							$val[suppName]
						</td>
						<td>
							$val[busiCode]
						</td>
						<td>
							$val[products]
						</td>
						<td>
							$val[fax]
						</td>
						<td>
							$val[address]
						</td>
						<td>
							$val[ExaStatus]
						</td>
						<td>
							$val[createTime]
						</td>
						<td>
							<a href='controller/rdproject/project/ewf_index.php?actTo=ewfExam&taskId=$val[wTask]&spid=$val[pId]&billId=$val[id]'>审批</a> |
							<a href='javascript:showOpenWin("?model=rdproject_project_rdproject&action=rpRead&pjId=$val[id]")'>查看</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">暂无相关记录</td></tr>';
		}
		return $str;
	}



}
?>
