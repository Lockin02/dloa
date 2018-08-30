<?php
/**
 * @author Show
 * @Date 2011年7月30日 15:23:12
 * @version 1.0
 * @description:合同结转表 Model层
 */
class model_projectmanagent_carriedforward_carriedforward extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_carriedforward";
		$this->sql_map = "projectmanagent/carriedforward/carriedforwardSql.php";
		parent::__construct ();
    }

    /**
     *  重写add
     */
    function add_d($object){
        $object['createId'] = $_SESSION['USER_ID'];
        $object['createName'] = $_SESSION['USERNAME'];
        return parent::add_d($object);
    }

    /**
     * 判断出库单据是否已结转
     */
    function isCarried_d($outStockId,$id = null){
        if($id){
            if($this->find(array('outStockId'=> $outStockId , 'id' => $id ),null,'id'))
                return false;
        }
        return $this->find(array('outStockId'=> $outStockId),null,'id');
    }
}
?>