<?php
class model_rdproject_projecttype_projecttype extends model_base{
	function __construct(){
		$this->tbl_name = "oa_rd_projecttype";
		$this->sql_map = "rdproject/projecttype/projecttypeSql.php";
		parent::__construct();
	}


	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/



	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/
	/*
	 * 项目类型的保存方法
	 */
	function addProjectType_d($typeObj){
		try{
			$this->start_d();

			$newType = parent::add_d($typeObj,true);

			$this->commit_d();
			return $newType;
		}catch(Exception $e){
			throw $e;
			$this->rollBack();
			return null;
		}
	}

	/*
	 * 项目类型的修改方法
	 */
	function editProjectType_d($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key=>$val){
				$i++;
				$classCss = (($i%2)==0)?"tr_even":"tr_odd";
				$str.=<<<EOT
				<tr class="$classCss" id="tr_$val[id]">
					<td><input type="checkbox" name="datacb"  value="$val[id]" onclick="checkOne();"></td>
					<td>$i</td>
					<td>$val[projectType]</td>
					<td>$val[creater]</td>
					<td>$val[createTime]</td>
					<td>$val[typeDescription]</td>
					<td>
						<a href="?model=rdproject_projecttype_projecttype&action=toEditProjectType&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=195&width=750" class="thickbox">编辑</a>
					</td>
EOT;
			}

		}else{
			$str = "<tr><td colspan='7'>暂无相关数据</td></tr>";
		}
		return $str;
	}

	/*
	 * 列表的显示方法
	 */
	function showProjectType_d(){

	}
}
?>
