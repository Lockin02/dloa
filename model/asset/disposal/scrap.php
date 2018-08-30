<?php
/**
 * �ʲ�����model����
 *@linzx
 */
class model_asset_disposal_scrap extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_scrap";
		$this->sql_map = "asset/disposal/scrapSql.php";
		parent :: __construct();


	}


	/*===================================ҵ����======================================*/
	/**
	 * ���ù����ӱ�����뵥id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			//unset($value['id']);
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}


  	/**
	 * @desription ��ӱ��淽��
	 * @linzx
	 */
	function add_d ($scrapinfo) {
		try{
			$this->start_d();
			if(is_array($scrapinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_scrap";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$scrapinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_scrap", "BF" ,$thisDate,$scrapinfo['applyCompanyCode'],'�̶��ʲ����ϵ�',true);
		       	}else{
					$scrapinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_scrap", "BF" ,$thisDate,$scrapinfo['applyCompanyCode'],'�̶��ʲ����ϵ�',false);
		       	}
                 $id = parent :: add_d($scrapinfo,true);

                 $scrapDao = new model_asset_disposal_scrapitem();
                 //�������id�ʹӱ�id��������
                 $itemsArr = $this->setItemMainId ( "allocateID", $id,$scrapinfo['item']);
                 $itemsObj = $scrapDao->saveDelBatch ( $itemsArr );
                 //isDelTag=1 Ϊ�ӱ���ɾ��
                 foreach($scrapinfo['item'] as $key=>$val ){
                 	if($val['isDelTag']!=1){
	                 	$assetId=$val['assetId'];
	                 	//$loseBillNo=$val['loseBillNo'];
	                 	//������ʧ���������ʲ������䱨��״̬�ĳ��ѱ���
	                 	$loseId=$val['loseId'];
	                   	$changeStatus= new model_asset_daily_loseitem();
	                   	$changeStatus->setScrapStatus($loseId,$assetId);
	                   	//�ύ����ȷ�ϣ����ʲ���Ƭ״̬��Ϊ�������ϡ�
	                   	if($scrapinfo['financeStatus'] == "����ȷ��"){
	                   		$assetcardDao = new model_asset_assetcard_assetcard();
	                   		$assetcardDao->setToScrap($val['assetId']);
	                   	}
                 	}
                 }
	            //���������ƺ�Id
			     $this->updateObjWithFile($id);
			     //�ύ����ȷ�ϣ����͹̶��ʲ����������ʼ�
			     if($scrapinfo['financeStatus'] == "����ȷ��" && $scrapinfo['mailInfo']['issend'] == 'y'){
			     	$this->mailDeal_d('scrap',$scrapinfo['mailInfo']['TO_ID'],array(id => $id));
			     }
				/*e:2.����ӱ��ʲ���Ϣ*/
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
	function edit_d ($scrapinfo) {
		try{
			$this->start_d();

			if(is_array($scrapinfo['item'])){
				$id=parent :: edit_d($scrapinfo,true);
			    $scrapDao = new model_asset_disposal_scrapitem();
                $itemsArr = $this->setItemMainId ( "allocateID",$scrapinfo['id'],$scrapinfo['item']);
                $itemsObj = $scrapDao->saveDelBatch ( $itemsArr );
				//������ʧ���������ʲ����ڱ��ϵ��ӱ��������ɾ���˻��߲����ػ��߳��ؾͽ��䱨��״̬�ĳ�δ����
               foreach($scrapinfo['item'] as $key=>$val ){
                 	if($val['isDelTag'] == 1 || $scrapinfo['financeStatus'] == "���" || $scrapinfo['recallFlag'] == "y"){
	                 	$assetId=$val['assetId'];
	                 	$loseId=$val['loseId'];
	                   	$changeStatus= new model_asset_daily_loseitem();
	                   	$changeStatus->setNoScrapStatus($loseId,$assetId);
                 	}
                 	if($scrapinfo['financeStatus'] == "����ȷ��"){
                 		//�ύ����ȷ�ϣ����ʲ���Ƭ״̬��Ϊ�������ϡ�
                 		$assetcardDao = new model_asset_assetcard_assetcard();
                 		$assetcardDao->setToScrap($val['assetId']);
                 	}elseif($scrapinfo['financeStatus'] == "��ȷ��"){
                 		//����˶Ա������룬�����ʲ���Ƭ��ֵ����ֵ��Ϣ
                 		$assetcardDao = new model_asset_assetcard_assetcard();
                 		$assetcardDao->updateScrapcard($val);
                 	}elseif($scrapinfo['financeStatus'] == "���" || $scrapinfo['recallFlag'] == "y"){
                 		//�����ػ��߳��أ����ʲ���Ƭ״̬��Ϊ�����á�
                 		$assetcardDao = new model_asset_assetcard_assetcard();
                 		$assetcardDao->setNoScrap($val['assetId']);
                 	} 
                 }
                 if($scrapinfo['financeStatus'] == "����ȷ��" && $scrapinfo['mailInfo']['issend'] == 'y'){
                 	//�ύ����ȷ�ϣ����͹̶��ʲ����������ʼ�
                 	$this->mailDeal_d('scrap',$scrapinfo['mailInfo']['TO_ID'],array(id => $scrapinfo['id']));
                 }elseif($scrapinfo['financeStatus'] == "��ȷ��" && $scrapinfo['mailInfo']['issend'] == 'y'){
                 	//����˶ԣ����͹̶��ʲ���������ȷ��֪ͨ�ʼ�
                 	$this->mailDeal_d('scrapConfrim',$scrapinfo['mailInfo']['TO_ID'],array(id => $scrapinfo['id']));
                 }elseif($scrapinfo['financeStatus'] == "���" && $scrapinfo['mailInfo']['issend'] == 'y'){
                 	//�����أ����͹̶��ʲ�����������֪ͨ�ʼ�
                 	$this->mailDeal_d('scrapBack',$scrapinfo['mailInfo']['TO_ID'],array(id => $scrapinfo['id']));
                 }elseif($scrapinfo['recallFlag'] == "y"){
                 	//���أ����͹̶��ʲ��������볷��֪ͨ�ʼ����ռ���Ϊ�ɿ���
                 	$this->mailDeal_d('scrapRecall',$scrapinfo['payerId'],array(id => $scrapinfo['id']));
                 }
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
		$scrapitemDao = new model_asset_disposal_scrapitem();
		$scrapitemDao->searchArr['allocateID']=$id;
		$scrapitem = $scrapitemDao->listBySqlId();
		$scrapiteminfo = parent :: get_d($id);
		$scrapiteminfo['details'] = $scrapitem;
		return $scrapiteminfo;
	}

	   	/**
	 * ����Id �õ������ݵı��ϵ���ϸ���ʲ�id
     * @linzx
	 */
	function getAssetIdById_d($id)	{
	 	$dirObj = $this->get_d($id);
	 	foreach($dirObj['details'] as $key=>$val){
	 		$assetId=$val['assetId'];
	 		$this->setRelEquScrapStatus($assetId);
	 	}
	 }
	/**
	 * �����ʲ����޸Ĺ��������ʲ��嵥��״̬λ��
	 * @linzx
	 */
	function setRelEquScrapStatus($id){
		$scrapDao = new model_asset_assetcard_assetcard();
		return $scrapDao->setIsScrap($id);
	}
}