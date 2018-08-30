<?php
/**
 * @description: 项目角色类
 * @date 2010-9-28 上午10:40:47
 */
class model_engineering_manage_rditemrole extends model_base {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-9-28 上午10:42:06
	 */
	function __construct () {
		$this->tbl_name = "oa_rd_itemrole";
		$this->sql_map = "engineering/manage/rditemroleSql.php";
		parent::__construct();
	}


	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/
	function showitemrolelist($rows){
		if($rows){
			$i = 0;
			$str = "";
			$datadictDao = new model_system_datadict_datadict();
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2)==0)?"tr_even":"tr_odd";
				$typeOne = $datadictDao->getDataNameByCode($val['itemType']);
				$str .=<<<EOT
					<tr class="$classCss" id="tr_$val[id]">
						<td>$i</td>
						<td class="main_td_align_left">$val[roleName]</td>
						<td>$val[createName]</td>
						<td>$val[createDate]</td>
						<td class="main_td_align_left">$val[roleDescription]</td>
					</tr>
EOT;
			}
		}else{
			$str = "<tr><td colspan='5'>暂无相关信息</tr></td>";
		}

		return $str;

	}





	/***************************************************************************************************
	 * ------------------------------以下为接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 添加保存角色
	 * @param tags
	 * @date 2010-9-28 下午06:44:43
	 */
	function addrole_d ($objinfo) {
		try{
			if($objinfo['itemType']=="")
				unset($objinfo['itemType']);

			$id = parent::add_d($objinfo,true);


			$this->commit_d();
			return $id;
		}catch(Exception $e){
//			echo "<pre>";
//			print_r($e);
			$this->rollBack();
			return null;
		}
	}

	/**
	 * @desription 根据项目类型对列表进行过滤
	 * @param tags
	 * @date 2010-9-28 下午08:16:05
	 */
	function filterpage_d ($sql) {
		if( !isset($this->sql_arr) ){
			return $this->listBySqlId($sql);

		}
//		$rows = $this->listBySqlId($sql);
//		echo "<pre>";
//		print_r($rows);
		parent::page_d();
	}


}


?>