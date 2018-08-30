<?php
/**
 *
 * �ʲ�����������ϸmodel
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
	 * �������ɿ�Ƭ����
	 */
	function updateCardNum($id,$cardNum){
		$sql = "update ".$this->tbl_name." set cardNum = cardNum + " . $cardNum . " where id = ".$id;
		$this->_db->query($sql);
	}
	
	/**
	 * �ı����ɿ�Ƭ��״̬
	 */
	function changeCardStatus($id){
		$rs = $this->find(array('id' => $id),null,'checkAmount,cardNum');
		$cardStatus = 0;//Ĭ��Ϊδ����
		if($rs['cardNum'] == $rs['checkAmount']){//������
			$cardStatus = 2;
		}else if($rs['cardNum'] > 0 && $rs['cardNum'] < $rs['checkAmount']){//��������
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
	 * ��ȡ��Ƭ��Ӧ���ʲ���������id
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
	 * ��ȡĳ���ʲ��������������ɿ�Ƭ״̬Ϊ����ɵļ�¼��
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
