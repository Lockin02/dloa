<?php

/**
 * �����豸model����
 *  @author chenzb
 */
class model_asset_assetcard_equip extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_equip";
		$this->sql_map = "asset/assetcard/equipSql.php";
		parent::__construct ();
	}


	/**
	 * ��Ӷ���
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		//���������ֵ䴦�� add by chengl 2011-05-15
		$newId = $this->create ( $object );
		if( $newId ){
			$assetDao = new model_asset_assetcard_assetcard();
			$assetInfo['id']=$object['equipId'];
			$assetInfo['belongTo']=$object['assetId'];
			$assetInfo['belongToCode']=$object['assetCode'];
			$assetInfo['isBelong']='1';
			$assetDao->updateById($assetInfo);
			//�����ʼ�֪ͨ�������ԭֵ�䶯
			$this->mailDeal_d('assetOriginaChange',null,array('assetId' => $object['assetId']));
		}
		return $newId;
	}
	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$idArr = explode(',', $ids);
			//��ȡ�ʲ���Ƭid
			$rs = $this->find(array('id' => $idArr[0]),null,'assetId');
			$assetId = $rs['assetId'];
			//��д�����豸�ʲ���Ϣ
			$assetDao = new model_asset_assetcard_assetcard();
			$assetInfo['belongTo'] = 0;
			$assetInfo['belongToCode'] = '';
			$assetInfo['isBelong'] = '0';
			foreach ($idArr as $id){
				$rs = $this->find(array('id' => $id),null,'equipId');
				$assetInfo['id'] = $rs['equipId'];
				$assetDao->updateById($assetInfo);
			}
			//ɾ�������豸
			$this->deletes($ids);
			//�����ʼ�֪ͨ�������ԭֵ�䶯
			$this->mailDeal_d('assetOriginaChange',null,array('assetId' => $assetId));
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
}