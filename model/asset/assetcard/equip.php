<?php

/**
 * 附属设备model层类
 *  @author chenzb
 */
class model_asset_assetcard_equip extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_equip";
		$this->sql_map = "asset/assetcard/equipSql.php";
		parent::__construct ();
	}


	/**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = false) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		//加入数据字典处理 add by chengl 2011-05-15
		$newId = $this->create ( $object );
		if( $newId ){
			$assetDao = new model_asset_assetcard_assetcard();
			$assetInfo['id']=$object['equipId'];
			$assetInfo['belongTo']=$object['assetId'];
			$assetInfo['belongToCode']=$object['assetCode'];
			$assetInfo['isBelong']='1';
			$assetDao->updateById($assetInfo);
			//发送邮件通知财务进行原值变动
			$this->mailDeal_d('assetOriginaChange',null,array('assetId' => $object['assetId']));
		}
		return $newId;
	}
	/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		try {
			$idArr = explode(',', $ids);
			//获取资产卡片id
			$rs = $this->find(array('id' => $idArr[0]),null,'assetId');
			$assetId = $rs['assetId'];
			//反写附属设备资产信息
			$assetDao = new model_asset_assetcard_assetcard();
			$assetInfo['belongTo'] = 0;
			$assetInfo['belongToCode'] = '';
			$assetInfo['isBelong'] = '0';
			foreach ($idArr as $id){
				$rs = $this->find(array('id' => $id),null,'equipId');
				$assetInfo['id'] = $rs['equipId'];
				$assetDao->updateById($assetInfo);
			}
			//删除附属设备
			$this->deletes($ids);
			//发送邮件通知财务进行原值变动
			$this->mailDeal_d('assetOriginaChange',null,array('assetId' => $assetId));
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
}