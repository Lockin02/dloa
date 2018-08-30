<?php
/**
 * @author Show
 * @Date 2011��7��30�� 15:23:12
 * @version 1.0
 * @description:��ͬ��ת�� Model��
 */
class model_projectmanagent_carriedforward_carriedforward extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_carriedforward";
		$this->sql_map = "projectmanagent/carriedforward/carriedforwardSql.php";
		parent::__construct ();
    }

    /**
     *  ��дadd
     */
    function add_d($object){
        $object['createId'] = $_SESSION['USER_ID'];
        $object['createName'] = $_SESSION['USERNAME'];
        return parent::add_d($object);
    }

    /**
     * �жϳ��ⵥ���Ƿ��ѽ�ת
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