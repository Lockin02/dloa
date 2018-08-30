<?php

/*
 * Created on 2010-7-20
 *  产品配置信息
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */	
class model_stock_productinfo_configurationAdd extends model_base {
	public $db;

	function __construct() {
		$this->tbl_name = "oa_stock_product_configuration_temp";
		$this->sql_map = "stock/productinfo/configurationAddSql.php";
		parent::__construct ();
	}

	/**
	 * 物料配置信息查看模板
	 * @param
	 */
	function showConfigItem($rows) {
		if ($rows) {
			$i = 1;
			foreach ( $rows as $key => $val ) {
				$str .= <<<EOT
					<tr align="center">
							<td>
								$i
							</td>
							<td>
								$val[configName]
							</td>
							<td>
								$val[configPattern]
							</td>
							<td>
								$val[configNum]
							</td>
							<td>
								$val[explains]
							</td>
				 		</tr>
EOT;
				$i ++;
			}
			return $str;
		} else {
			return "";
		}
	}

	/**
	 * 根据物料id删除配件与配置信息
	 */
	function deleteByHardWareId($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType<>'typeaccess'";
		parent::delete ( $conditions );
	}
	/**
	 * 根据物料id获取配件与配置信息
	 */
	function getConfigByHardWareId($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType<>'typeaccess'";
		return parent::findAll ( $conditions );
	}

	/**
	 * 根据物料id获取配件 及对应物料信息
	 */
	function getAccessForPro($hardWareId) {
		$sql = "select p.id as productId,p.*,c.* from `oa_stock_product_configuration` c
    				 inner join `oa_stock_product_info` p on(p.`id`=c.`configId`)
             			where c.`hardWareId`='$hardWareId' and c.`configType`='proaccess'";
		$arr= parent::findSql ( $sql );
		$rArr=array();
		foreach($arr as $key=>$val){
			unset($val['id']);
			$rArr[$key]=$val;
		}
		return $rArr;
	}

	/**
	 *
	 * 根据物料类型id获取配件模板信息
	 * @param $hardWareId
	 */
	function getAccessForType($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType='typeaccess'";
		return parent::findAll ( $conditions );
	}

	/**
	 *
	 * 根据物料类型id删除配件模板信息
	 * @param $hardWareId
	 */
	function deleteAccessForType($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType='typeaccess'";
		parent::delete ( $conditions );
	}

	/**
	 * 更新配件的数量
	 */
	function updateConfigNum($outArr) {
		foreach ( $outArr as $key => $outConfig ) {
			$config = $this->findBy ( "id", $outConfig ['productId'] );
			$nNum = $config ['configNum'] - $outConfig ['outstockNum'];
			if ($nNum >= 0) {
				$this->updateField ( array ("id" => $outConfig ['productId'] ), "configNum", $nNum );
			} else {
				throw new Exception ( "配件出库数量有误" );
			}

		}

	}
}
?>
