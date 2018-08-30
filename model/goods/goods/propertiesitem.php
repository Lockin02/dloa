<?php
/**
 * @author huangzf
 * @Date 2012��3��1�� 20:13:15
 * @version 1.0
 * @description:���������� Model��
 */
class model_goods_goods_propertiesitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_goods_properties_item";
		$this->sql_map = "goods/goods/propertiesitemSql.php";
		parent::__construct ();
	}

	/**
	 * ��������id������������Ϣ
     * @param $mainId
     * @return array
	 */
	function getProItems($mainId) {
		$this->searchArr = array ("mainId" => $mainId );
		$propertiesItems = $this->listBySqlId ();
		$resultArr = array ();
		$assListDao=new model_goods_goods_asslist();
		foreach ( $propertiesItems as $value ) {
            $assListDao->searchArr = array ("mainId" => $value ['id'] );
            $assListDao->asc = "false";
			$assItemArr = $assListDao->listBySqlId ();
			if (is_array ( $assItemArr )) {
				$value ['assItems'] = $assItemArr;
			}
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/**
	 *
	 * ����ids��ȡ����ֵ���ݼ���Ӧ��������
     * @param $idStr
     * @return mixed
	 */
	function getItemProperty($idStr) {
		if($idStr){
            $idStr = util_jsonUtil::strBuild($idStr);
			$sql = "select  i.id,i.itemContent,p.existNum,p.propertiesName
			    from oa_goods_properties_item i left join oa_goods_properties p on(p.id=i.mainId)  where i.id in($idStr)";
			return $this->findSql ( $sql );
		}else{
			return "";
		}
	}
}