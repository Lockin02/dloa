<?php

/**
 * �ʲ�����model����
 *@linzx
 */
class model_asset_disposal_sell extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_sell";
		$this->sql_map = "asset/disposal/sellSql.php";
		parent :: __construct();


	}


	/*===================================ҵ����======================================*/
	/**
	 * ���ù����ӱ�����뵥id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}


  	/**
	 * @desription ��ӱ��淽��
	 * @linzx
	 */
	function add_d ($sellinfo) {
		try{
			$this->start_d();
			if(is_array($sellinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_sell";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$sellinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_sell", "CS" ,$thisDate,$sellinfo['applyCompanyCode'],'�̶��ʲ����۵�',true);
		       	}else{
					$sellinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_sell", "CS" ,$thisDate,$sellinfo['applyCompanyCode'],'�̶��ʲ����۵�',false);
		       	}
                 $id = parent :: add_d($sellinfo,true);
                 $sellDao = new model_asset_disposal_sellitem();
                 //�������id�ʹӱ�id��������
                 $itemsArr = $this->setItemMainId ( "sellID", $id,$sellinfo['item']);
                 $itemsObj = $sellDao->saveDelBatch ( $itemsArr );

			     $this->commit_d();
			     return $id;
			}else {
				throw new Exception ( "������Ϣ������!" );
				}

		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}

 /**
	 * @desription �޸ı��淽��
	 * @linzx
	 */
	function edit_d ($sellinfo) {
		try{
			$this->start_d();

			if(is_array($sellinfo['item'])){
				$id=parent :: edit_d($sellinfo,true);
			    $sellDao = new model_asset_disposal_sellitem();
                $itemsArr = $this->setItemMainId ( "sellID",$sellinfo['id'],$sellinfo['item']);
                 $itemsObj = $sellDao->saveDelBatch ( $itemsArr );
			}else {
				throw new Exception ( "������Ϣ������!" );
				}
			$this->commit_d();
			return true;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

   	/**
	 * ��дget_d����
     * ���ݱ����ʲ���ID�����뵥���еı����ʲ��ó���
     * @linzx
	 */
   function get_d($id){
		$sellitemDao = new model_asset_disposal_sellitem();
		$sellitemDao->searchArr['sellID']=$id;
		$sellitem = $sellitemDao->listBySqlId();
		$selliteminfo = parent :: get_d($id);
		$selliteminfo['details'] = $sellitem;
		return $selliteminfo;
	}

   	/**
	 * ����Id �õ������ݵı��ϵ���ϸ���ʲ�id
     * @linzx
	 */
	function getCardIdById_d($id)	{
	 	$dirObj = $this->get_d($id);
	 	foreach($dirObj['details'] as $key=>$val){
	 		$assetId=$val['assetId'];
	 	$this->setRelEquSellStatus($assetId);
	 	}
	 }
	/**
	 * �����ʲ����޸Ĺ��������ʲ��嵥��״̬λ��
	 * @linzx
	 */
	function setRelEquSellStatus($id){
		//$scrapDao = new model_asset_disposal_scrapitem();
		$sellDao = new model_asset_assetcard_assetcard();
		return $sellDao->setIsSell($id);
	}

}
?>