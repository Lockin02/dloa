<?php
/**
 * Created on 2011-2-15 13:45
 */
class model_purchase_change_equipmentchange extends model_base {
	/*
	 * @description 构造函数
	 */
	function __construct() {
		$this->tbl_name = "oa_purch_apply_equ";
		$this->sql_map = "purchase/change/equipmentchangeSql.php";
		parent :: __construct();

	}

	/**
	 * @description 将合同的采购设备的数据保存到设备的版本表中
	 * @author qian
	 * @date 2011-2-16 16:22
	 */
	function addEquVersion_d($rows){
		try{
			$this->start_d();
			//字段“changeType”为0时表示修改，为1时表示删除
			if($rows['amountAll']==0){
				$rows['changeType'] = 1;
			}else{
				$rows['changeType'] = 0;
			}

			$id = $this->add_d($rows);
			$this->commit_d();
		}catch(Exception $e){
			$this->rollBack();
			return null;
		}
	}


	/**
	 * @description 更新设备版本表里的数据
	 * @author qian
	 * @date 2011-2-19 12:18
	 */
	function toEquChangeVersion_d($equipment){

	}
}

?>
