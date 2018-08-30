<?php
/**
 * @author huangzf
 * @Date 2012年3月1日 20:13:15
 * @version 1.0
 * @description:配置项内容 Model层
 */
class model_goods_goods_propertiesitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_properties_item";
		$this->sql_map = "goods/goods/propertiesitemSql.php";
		parent::__construct ();
	}

	/**
	 *
	 * 根据属性id查找配置项信息
	 */
	function getProItems($mainId) {
		$this->searchArr = array ("mainId" => $mainId );
		$propertiesItems = $this->listBySqlId ();
		$resultArr = array ();
//		$assItemDao = new model_goods_goods_assitem ();
		$asslistDao=new model_goods_goods_asslist();
		foreach ( $propertiesItems as $key => $value ) {
			$asslistDao->searchArr = array ("mainId" => $value ['id'] );
			$asslistDao->asc = "false";
			$assItemArr = $asslistDao->listBySqlId ();
			if (is_array ( $assItemArr )) {
				$value ['assItems'] = $assItemArr;
			}
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/**
	 *
	 * 根据ids获取所有值内容及对应属性名称
	 */
	function getItemProperty($idStr) {
		if($idStr){
			$idStr = util_jsonUtil::strBuild($idStr);
			$sql = "select  i.id,i.itemContent,p.existNum,p.propertiesName from oa_goods_properties_item i left join oa_goods_properties p on(p.id=i.mainId)  where i.id in($idStr)";
			return $this->findSql ( $sql );
		}else{
			return "";
		}
	}
}
?>