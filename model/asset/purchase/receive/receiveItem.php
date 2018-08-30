<?php
/**
 *
 * 资产验收申请明细model
 * @author fengxw
 *
 */
class model_asset_purchase_receive_receiveItem extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_receiveItem";
		$this->sql_map = "asset/purchase/receive/receiveItemSql.php";
		parent::__construct ();
	}

	/**
	 * 更新生成卡片数量
	 */
	function updateCardNum($id,$cardNum){
		$sql = "update ".$this->tbl_name." set cardNum = cardNum + " . $cardNum . " where id = ".$id;
		$this->_db->query($sql);
	}
	
	/**
	 * 改变生成卡片的状态
	 */
	function changeCardStatus($id){
		$rs = $this->find(array('id' => $id),null,'checkAmount,cardNum');
		$cardStatus = 0;//默认为未生成
		if($rs['cardNum'] == $rs['checkAmount']){//已生成
			$cardStatus = 2;
		}else if($rs['cardNum'] > 0 && $rs['cardNum'] < $rs['checkAmount']){//部分生成
			$cardStatus = 1;
		}
		$statusInfo = array(
			'id' => $id,
			'cardStatus' => $cardStatus
		);
		$this->updateById( $statusInfo );
	}

	function deleteByFk($id){
		if (! mysql_query ( "delete from " . $this->tbl_name . " where receiveId =" . $id  )) {
			throw new Exception ( mysql_error () );
		}
		return true;
	}
	
	/**
	 * 获取卡片对应的资产需求申请id
	 */
	function getRequirementInfo($id){
		$sql = "
			SELECT
				p.relDocId,p.relDocCode
			FROM ".$this->tbl_name." rm
			LEFT JOIN oa_asset_purchase_apply p ON rm.applyId = p.id
			WHERE
				rm.id = '".$id."'";
		$rs = $this->_db->get_one($sql);
	
		return $rs;
	}
	
	/**
	 * 获取某个资产需求申请下生成卡片状态为非完成的记录数
	 */
	function countIsCard($relDocId){
		$sql = "
			SELECT
				COUNT(*) as isCardAmount
			FROM ".$this->tbl_name."
			WHERE
				applyId IN (
					SELECT
						id
					FROM
						oa_asset_purchase_apply
					WHERE
						relDocId = '".$relDocId."'
					)
				AND cardStatus <> 2";
		$rs = $this->_db->get_one($sql);

		return $rs['isCardAmount'];
	}
}
