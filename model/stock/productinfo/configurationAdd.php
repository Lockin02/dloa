<?php

/*
 * Created on 2010-7-20
 *  ��Ʒ������Ϣ
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
	 * ����������Ϣ�鿴ģ��
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
	 * ��������idɾ�������������Ϣ
	 */
	function deleteByHardWareId($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType<>'typeaccess'";
		parent::delete ( $conditions );
	}
	/**
	 * ��������id��ȡ�����������Ϣ
	 */
	function getConfigByHardWareId($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType<>'typeaccess'";
		return parent::findAll ( $conditions );
	}

	/**
	 * ��������id��ȡ��� ����Ӧ������Ϣ
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
	 * ������������id��ȡ���ģ����Ϣ
	 * @param $hardWareId
	 */
	function getAccessForType($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType='typeaccess'";
		return parent::findAll ( $conditions );
	}

	/**
	 *
	 * ������������idɾ�����ģ����Ϣ
	 * @param $hardWareId
	 */
	function deleteAccessForType($hardWareId) {
		$conditions = "hardWareId='$hardWareId' and configType='typeaccess'";
		parent::delete ( $conditions );
	}

	/**
	 * �������������
	 */
	function updateConfigNum($outArr) {
		foreach ( $outArr as $key => $outConfig ) {
			$config = $this->findBy ( "id", $outConfig ['productId'] );
			$nNum = $config ['configNum'] - $outConfig ['outstockNum'];
			if ($nNum >= 0) {
				$this->updateField ( array ("id" => $outConfig ['productId'] ), "configNum", $nNum );
			} else {
				throw new Exception ( "���������������" );
			}

		}

	}
}
?>
