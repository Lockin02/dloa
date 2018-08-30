<?php

/**
 * �ʲ�ά��model����
 *@linzx
 */
class model_asset_daily_keep extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_keep";
		$this->sql_map = "asset/daily/keepSql.php";
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
	function add_d ($keepinfo) {
		try{
			$this->start_d();
			//$codeDao=new model_common_codeRule();
			//$scrapinfo['fillupCode']=$codeDao->stockCode("oa_stock_fillup","FILL");
			if(is_array($keepinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_keep";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$keepinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_keep", "WB" ,$thisDate,$keepinfo['applyCompanyCode'],'�̶��ʲ�ά����',true);
		       	}else{
					$keepinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_keep", "WB" ,$thisDate,$keepinfo['applyCompanyCode'],'�̶��ʲ�ά����',false);
		       	}
                 $id = parent :: add_d($keepinfo,true);
                 $keepDao = new model_asset_daily_keepitem();
                 //�������id�ʹӱ�id��������
                 $itemsArr = $this->setItemMainId ( "keepId", $id,$keepinfo['item']);
                 $itemsObj = $keepDao->saveDelBatch ( $itemsArr );
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
	function edit_d ($keepinfo) {
		try{
			$this->start_d();

			if(is_array($keepinfo['item'])){
				$id=parent :: edit_d($keepinfo,true);
			    $keepDao = new model_asset_daily_keepitem();
                $itemsArr = $this->setItemMainId ( "keepId",$keepinfo['id'],$keepinfo['item']);
                 $itemsObj = $keepDao->saveDelBatch ( $itemsArr );
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
     * ����ά���ʲ���ID�����뵥���е�ά���ʲ��ó���
     * @linzx
	 */
   function get_d($id){
		$keepitemDao = new model_asset_daily_keepitem();
		$keepitemDao->searchArr['keepId']=$id;
		$keepitem = $keepitemDao->listBySqlId();
		$keepiteminfo = parent :: get_d($id);
		$keepiteminfo['details'] = $keepitem;
		return $keepiteminfo;
	}


	/**
	 * �ʲ�ά�������������
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
					if( $val['amount']*1 >= 500 ){
						$cardObjs = array();
						//�ʲ����롢ʹ�ò��š�ʹ����
						$cardObjs['oldId']=$val['assetId'];
						$cardObjs['assetCode']=$val['assetCode'];
						$cardObjs['origina']=$val['amount'];
						//���뿨Ƭ�࣬��ӱ䶯��¼
						if($cardDao->changeByObj_d($cardObjs,$relInfo)){
							$flag = true;
						}else{
							throw new Exception("������Ϣ����������ȷ��!");
						}
					}else{
						continue;
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