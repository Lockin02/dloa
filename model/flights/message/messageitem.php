<?php
/**
 * @author sony
 * @Date 2013年7月10日 17:37:38
 * @version 1.0
 * @description:改签子表字段 Model层
 */
class model_flights_message_messageitem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_message_item";
		$this->sql_map = "flights/message/messageitemSql.php";
		parent::__construct ();
	}
	function mychange_d($mainId, $itemsArr) {
		$changCount = $this->get_table_fields ( "oa_flights_message", "id=$mainId", "changCount" );
		if ($changCount == 3) {
			msg ( "改签次数已满三次" );
			break;
		}
		$returnObjs = array ();
		$val = $this->addCreateInfo ( $itemsArr );
		$id = $this->add_d ( $val );
		$val ['id'] = $id;
		$val ['isAddAction'] = true; //标识是新增的
		array_push ( $returnObjs, $val );
		if ($id) {
			$messageDao = new model_flights_message_message ();
			$messageDao->update ( array ("id" => $mainId ), array ("businessState" => 1 ) );
			$this->update(array ("id" => $id ), array ("profession" => "1" ));
//			$sql = "update oa_flights_message set actualCost= actualCost +" . $itemsArr ['changeCost'] . " where id=" . $mainId;
			//改签次数加1
//			$this->query ( $sql );
			$sql1 = "update oa_flights_message set changCount= changCount + " . 1 . "  where id=" . $mainId;
			$this->query ( $sql1 );

		}
		return $id;
	}
	function myticket_d($mainId, $itemsArr) {
		$returnObjs = array ();
		$val = $this->addCreateInfo ( $itemsArr );
		$id = $this->add_d ( $val );
		$val ['id'] = $id;
		$val ['isAddAction'] = true; //标识是新增的
		array_push ( $returnObjs, $val );
		if ($id) {
			$messageDao = new model_flights_message_message ();
			$messageDao->update ( array ("id" => $mainId ), array ("businessState" => 2 ) );
			$this->update(array ("id" => $id ), array ("profession" => "2" ));

		}
		return $id;
	}
/**
	 * 过滤查询
	 */
	function filterMesItem_d($itemIds){
		$sql = "select oa_flights_message_item.*,oa_flights_message.airName,oa_flights_message.flightNumber,oa_flights_message.airline from oa_flights_message_item left outer join oa_flights_message on oa_flights_message_item.mainId = oa_flights_message.id where oa_flights_message_item.id in($itemIds)";
		return $this->_db->getArray($sql);
	}

	/**
	 * 更新核算状态
	 */
	function updateAuditState_d($id,$auditState){
		$object = array(
			'id' => $id,
			'icondition' => $auditState
		);
		try{
			return parent::edit_d($object);
		}catch(exception $e){
			throw $e;
			return false;
		}
	}
}
?>