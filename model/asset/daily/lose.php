<?php

/**
 * �ʲ���ʧmodel����
 *@linzx
 */
class model_asset_daily_lose extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_lose";
		$this->sql_map = "asset/daily/loseSql.php";
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
	function add_d ($loseinfo) {
		try{
			$this->start_d();
			//$codeDao=new model_common_codeRule();
			//$scrapinfo['fillupCode']=$codeDao->stockCode("oa_stock_fillup","FILL");
			if(is_array($loseinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_lose";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$loseinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_lose", "YS" ,$thisDate,$loseinfo['applyCompanyCode'],'�̶��ʲ���ʧ��',true);
		       	}else{
					$loseinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_lose", "YS" ,$thisDate,$loseinfo['applyCompanyCode'],'�̶��ʲ���ʧ��',false);
		       	}
                 $id = parent :: add_d($loseinfo,true);
                 $loseDao = new model_asset_daily_loseitem();
                 //�������id�ʹӱ�id��������
                 $itemsArr = $this->setItemMainId ( "loseId", $id,$loseinfo['item']);
                 $itemsObj = $loseDao->saveDelBatch ( $itemsArr );
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
	function edit_d ($loseinfo) {
		try{
			$this->start_d();

			if(is_array($loseinfo['item'])){
				$id=parent :: edit_d($loseinfo,true);
			    $loseDao = new model_asset_daily_loseitem();
                $itemsArr = $this->setItemMainId ( "loseId",$loseinfo['id'],$loseinfo['item']);
                 $itemsObj = $loseDao->saveDelBatch ( $itemsArr );
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
     * �����ʲ���ID�����뵥���е��ʲ��ó���
     * @linzx
	 */
   function get_d($id){
		$loseitemDao = new model_asset_daily_loseitem();
		$loseitemDao->searchArr['loseId']=$id;
		$loseitem = $loseitemDao->listBySqlId();
		$loseiteminfo = parent :: get_d($id);
		$loseiteminfo['details'] = $loseitem;
		return $loseiteminfo;
	}


	/**
	 * �ʲ��黹�����������
	 * @author zengzx
	 * @since 1.0 - 2011-11-29
	 */
	function updateRelInfoAtAudit($objcet) {
		try {
			$this->start_d();
			$flag=parent::edit_d($objcet);
			$this->commit_d();
			return $flag;
		} catch (Exception $e) {
			$this->rollBack();
			return $flag;
		}
	}


	/**
	 * �ʲ���ʧ�����������
	 * @author zengzx
	 * @since 1.0 - 2011-11-29
	 */
	function dealRelInfoAtAudit($id,$relInfo){
		try{
			$this->start_d();
			$flag = false;
			$obj = $this->get_d($id);
			$details = $obj ['details'];
			if(array($details)){
				$cardDao = new model_asset_assetcard_assetcard();
				//��ȡ��Ӧ�ı䶯����
				foreach ( $details as $key=>$val ){
					$cardObjs = array();
					//�ʲ����롢ʹ�ò��š�ʹ����
					$cardObjs['oldId']=$val['assetId'];
					$cardObjs['assetCode']=$val['assetCode'];
					$cardObjs['useStatusCode']='SYZT-YS';
					$cardObjs['useStatusName']='��ʧ';
					//���뿨Ƭ�࣬��ӱ䶯��¼
					if($cardDao->changeByObj_d($cardObjs,$relInfo)){
						$flag = true;
					}else{
						throw new Exception("������Ϣ����������ȷ��!");
					}
				}
			}
			$this->commit_d();
			return $flag;
		}catch(Exception $e ){
			$this->rollBack();
			return $flag;
		}
	}



}
?>