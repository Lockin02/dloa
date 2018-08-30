<?php
require_once 'management_tools.php';
/**
 * @author Administrator
 * @Date 2013��5��30�� ������ 15:57:29
 * @version 1.0
 * @description:����BOM�嵥 Model��
 */
 class model_stock_material_material  extends model_stock_material_management_tools {

	function __construct() {
		$this->material_type 		= "oa_stock_material_type";
		$this->material_parts 		= "oa_stock_material_parts";
		$this->material_SF 			= "oa_stock_material_semiFinished";
		$this->material_config 		= "oa_stock_material_config";
		$this->material_finished 	= "oa_stock_material_finished";
		$this->material_details 	= "oa_stock_material_details";
		parent::__construct ();
	}

  /**
     * ���ṹ���ݼ�
     * @param Int $parentId
     * @return Array
     */
    public function loadTree($parentId=null, $type = '') {
        $conditions = " `deleted` = '0'";

		if($type == 'SF'){
			$DBName	= $this->material_SF;
			$conditions .= " AND `parentId`=$parentId";
		}else{
			$DBName	= $this->material_type;
		}
        $query = $this->getTableFiledByConditions( $DBName , ' id,name as text ', $conditions);
        $treeData = array();
	    while (($rs = $this->fetch_array($query)) != false) {
	        $datas = array();
	        $datas = $this->loadTree($rs['id'],'SF');
	        $state = 'open';
	        if (count($datas) == 0) {
	            $state = 'open';
	        }
	        $rs['state'] = $state;
	        $rs['children'] = $datas;
	        $treeData[] = $rs;
	    }

        return $treeData;
    }

 	public function loadSFTree() {
        $conditions = " `deleted` = '0'";

        $query = $this->getTableFiledByConditions( $this->material_type , ' id,name as text ', $conditions);
        $treeData = array();
	    while (($rs = $this->fetch_array($query)) != false) {
	        $treeData[] = $rs;
	    }

        return $treeData;
    }

    /**
     * ��ȡ�����б�����
     * */
    function loadConfig($semifinishedId){

    	$conditions = " `deleted` = '0' AND `semifinishedId`=$semifinishedId";
        $query = $this->getTableFiledByConditions( $this->material_config , '', $conditions);

        $conditions .= " AND `configId` = '0'";
        $queryParts = $this->getTableFiledByConditions( $this->material_parts , 'count(*) as num', $conditions);
        $partsArray = $this->fetch_array($queryParts);
		$treeData = array();
    	if(!empty($partsArray[num])) {
	    	$treeData[0] = array('id'=>0,'semifinishedId'=>$semifinishedId,'name'=>'�����嵥');
	    }
	    while (($rs = $this->fetch_array($query)) != false) {
//	        $datas = array();
//	        $datas = self::loadTree($rs['id'],'SF');
//	        $state = 'open';
//	        if (count($datas) == 0) {
//	            $state = 'open';
//	        }
//	        $rs['state'] = $state;
//	        $rs['children'] = $datas;
	        $treeData[] = $rs;
	    }

        return $treeData;
    }
 	/**
     * ��ȡԪ��������
     * */
    function loadParts($semifinishedId,$configId = '',$listType){

    	$conditions = " `deleted` = '0' AND `semifinishedId`=$semifinishedId ";
    	if ($configId != ''){
    		$conditions.= " AND `configId`=$configId ";
    	}
		if($listType == ''){
			$conditions.= " AND `code` <> '' AND `code` <> 0 AND `code` IS NOT NULL ";
		}
        $query = $this->getTableFiledByConditions( $this->material_parts , '', $conditions);
        $treeData = array();
	    while (($rs = $this->fetch_array($query)) != false) {
//	        $datas = array();
//	        $datas = self::loadTree($rs['id'],'SF');
//	        $state = 'open';
//	        if (count($datas) == 0) {
//	            $state = 'open';
//	        }
//	        $rs['state'] = $state;
//	        $rs['children'] = $datas;
			$rs['semiF'.$semifinishedId] = $rs['id'];
	        $treeData[] = $rs;

	    }

        return $treeData;
    }

 	/**
     * ��ȡԪ��������
     * */
    function insertDetails($datas,$FName,$code){
		$datasArray = explode("#",$datas);
		$ins = $name = $id = $ins_sql = $ins_sqlD = '';
    	foreach ($datasArray as $key => $val){
    		if($val){
	    		$fDatas = explode("^",$val);
	    		$idDatas = explode(",",$fDatas[0]);
	    		$ins .= '#'.$idDatas[0].'^'.$fDatas[1];
//	    		$name .= '+ '.$fDatas[2].' ';
	    		$place = stripos($fDatas[0],',');
	    		if($place) $id .= substr($fDatas[0],$place);
    		}
    	}

    	$ins_sql = "INSERT INTO ".$this->material_finished."(`sfIdNum`,`name`,`code`) VALUES ('".substr($ins,1)."','".un_iconv($FName,'utf-8','gbk')."','".$code."')";
    	$this->query($ins_sql);
        $insertID = mysql_insert_id();
        if ($insertID){
        	$query = $this->getTableFiledByConditions( $this->material_parts , '', "id IN (".substr($id,1).")");
        	$ins_sqlA = " INSERT INTO ".$this->material_details."(";
    		$ins_sqlA .= "`finishedId`,`semifinishedId`,`configId`,`type`,`code`,`name`,`model`,`packaging`,`total`,`serial_number`,`factory`,`description`";
    		$ins_sqlA .= ") VALUES";
	        while (($rs = $this->fetch_array($query)) != false) {
	        	$ins_sqlB .= ",(";
    			$ins_sqlB .= "'".$insertID."',";
    			$ins_sqlB .= "'".$rs['semifinishedId']."',";
    			$ins_sqlB .= "'".$rs['configId']."',";
    			$ins_sqlB .= "'".$rs['type']."',";
    			$ins_sqlB .= "'".$rs['code']."',";
    			$ins_sqlB .= "'".$rs['name']."',";
    			$ins_sqlB .= "'".$rs['model']."',";
    			$ins_sqlB .= "'".$rs['packaging']."',";
    			$ins_sqlB .= "'".$rs['total']."',";
    			$ins_sqlB .= "'".$rs['serial_number']."',";
    			$ins_sqlB .= "'".$rs['factory']."',";
    			$ins_sqlB .= "'".$rs['description']."'";
    			$ins_sqlB .= ")";
		    }
		    $this->query($ins_sqlA.substr($ins_sqlB,1));
		    return $insertID;
        }
        return false;
    }
    /**
     * ͳ������ʱ����Ԫ��������
     * */
    function updateDetails($id,$val){  
    	$sql = "UPDATE ".$this->material_finished." SET sfIdNum = '".$val."' WHERE id = ".$id;
    	return $this->query($sql);
    }
    /**
     * ��ȡͳ����������
     * */
    function loadStatistical($finishedId){
    	$sql = "SELECT details.*, info.actNum, info.safeNum, info.planInstockNum, productInfo.unitName FROM ";
    	$sql .= $this->material_details ." AS details LEFT JOIN ";
    	$sql .= " oa_stock_inventory_info AS info ON `details`.`code` = `info`.`productCode` LEFT JOIN ";
    	$sql .= " oa_stock_product_info AS productInfo ON `details`.`code` = `productInfo`.`productCode`";
    	$sql .= " WHERE details.finishedId =".$finishedId;
    	$sql .= " ORDER BY details.id";
    	$datas = $this->query($sql);
		$Fdata = $this->loadFinished($finishedId);
    	$d_list = array();
	    while (($rs = $this->fetch_array($datas)) != false){
	    	$rs['actTotal'] = $rs['total'] * $Fdata[$rs['semifinishedId']];
	    	$rs['allTotal'] = ceil($rs['total'] + ($rs['total'] /100 * 5 ));
	    	if($rs['actTotal'] < $rs['allTotal']){
	    		$rs['stockIssueNum'] = $rs['actTotal'];
	    		$rs['stockOutNum'] = $rs['allTotal'] - $rs['actTotal'];
	    	}else{
	    		$rs['stockIssueNum'] = $rs['allTotal'];
	    		$rs['stockOutNum'] = 0;
	    	}
			$d_list[] = $rs;
	    }
    	return $d_list;
    }
     /**
     * ��ȡ��Ʒ����
     * */
    function loadFinished($finishedId){
    	$datas = $this->getTableFiledByConditions( $this->material_finished , 'sfIdNum', 'id='.$finishedId );
    	$rs = $this->fetch_array($datas);
    	$id_num = explode('#', $rs[sfIdNum]);
    	foreach ($id_num as $key => $val) {
    		$A_data = explode('^', $val);
    		$AIdNum[$A_data[0]] = $A_data[1];
    	}
    	return $AIdNum;
    }

    function getFinishedData(){
    	$SQL = "SELECT * FROM $this->material_type WHERE `deleted` = '0' ";
    	$datas = $this->query($SQL);
    	$list = array();
    	while(($rs = $this->fetch_array($datas))!=false){
    		$list[] = $rs;
    	}
    	return $list;
    }

    function delete_SF($id){
    	$ids =  substr($id, 0, -1);
    	$SQL = "UPDATE  $this->material_SF SET `deleted` = '1' WHERE id in ({$ids})";
    	return $this->query($SQL);
    }

  	function delete_finished($id){
    	$ids =  substr($id, 0, -1);
    	$SQL = "UPDATE  $this->material_type SET `deleted` = '1' WHERE id in ({$ids})";
    	return $this->query($SQL);
    }
    function loadPartsSF($id){
    	$SQL = "SELECT * FROM {$this->material_SF} WHERE `deleted` = '0' AND `id` = '{$id}'";
    	$datas = $this->query($SQL);
    	$list = array();
   		while(($rs = $this->fetch_array($datas))!=false){
    		$list[] = $rs;
    	}
    	return $list;
    }
 	function update_sf($data){
    	$SQL = "UPDATE  $this->material_SF SET `name` = '{$data['name']}',`pickingInfo` = '".un_iconv($data['info'],'utf-8','gbk')."',`code` = '{$data['code']}' WHERE `id` = '{$data['id']}'";
    	return $this->query($SQL);
    }
	/**��������*/
	/*function add_d($object){
		try {
			$this->start_d ();
			$productinfoDao=new model_stock_productinfo_productinfo();
			$productinfoRow=$productinfoDao->get_d($object['parentProductID']);//��ȡ
			$row=$this->get_d($object['parentId']);//��ȡ������������
			$object['parentProductName']=$productinfoRow['productName'];
			$object['parentProductCode']=$productinfoRow['productCode'];
			$object['lowLevelCode']=$row['lowLevelCode']+1;
			//����
			$newId = parent::add_d ( $object, true );
			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}


	}*/

		/**
	 * @desription �༭���ڵ�
	 * @param $node:�༭�����ڵ�
	 * @return $node:�༭������ڵ�
	 */
	/*function edit_d($node) {
		try {
			$this->start_d ();
			$node = $this->updateField ('id='.$node['id'], 'materialNum',$node['materialNum'] );
//			$id = $this->updateField ('id='.$node['id'], 'remark',$node['remark'] );
			$this->commit_d ();
			return $node;
		} catch ( Exception $e ) {
			msg ( $e->getMessage () );
			$this->rollBack ();
			return null;
		}
	}*/


    /**
     * ��֤�Ƿ���ڸ��ڵ㣬������������
     */
    /*function checkParent_d(){
    	$this->searchArr['id'] = -1;
    	$rs = $this->list_d('select_default');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'productCode' => 'root' , 'productName' => '�����','materialNum'=>1,'lowLevelCode'=> 0 ,'lft'=> 1 , 'rgt' =>2));
			return false;
		}
    }*/

    	/**
	 * @desription ɾ�����ڵ㼰�����ڵ�
	 *
	 */
	/*function deletes_d($id) {
		try {
			$this->start_d ();
			//��ȡ�ӽڵ�
			$node=$this->get_d($id);
			$childNodes=$this->getChildrenByNode ( $node );
            if($childNodes){
                foreach($childNodes as $key=>$val){
                    $this->deleteNodes ( $val['id'] );
                    parent::deletes ($val['id']);
                }
            }
			parent::deletes ( $id );
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw new Exception ( $e->getMessage () );
			return false;
		}
	}*/

	/**�������ϵĸ�BOM�����ݣ�����������桢��;�ɹ�������*/
	/*function treeCondList($arr,$parentId=-1,$neednum=1,$purchNum=1){
        $ret = array();
		$equipDao = new model_purchase_contract_equipment();
		$inventoryinfoDao = new model_stock_inventoryinfo_inventoryinfo();
        foreach($arr as $k => $v) {
			if($v['_parentId'] == $parentId) {
				$row = $v;
				$row['onwaynum'] = $equipDao->getEqusOnway(array("productId"=>$v['productId']));//��;����
				$row['materialNum'] = $row['materialNum']*$neednum;
				$row['storagenum'] = $inventoryinfoDao->getProActNum($v['productId']);//�������
				$tmpdata = $inventoryinfoDao->findBy("productId",$v['productId']);
				if(isset($tmpdata['havepartednum'])){//�ѷ�������
					$row['havepartednum'] = $tmpdata['lockedNum'];
				}else{
					$row['havepartednum'] = 0;
				}
				$row['canusenum'] = $row['storagenum'] - $row['havepartednum'];//��ʹ������
				$row['storagenum'] = $inventoryinfoDao->getProActNum($v['productId']);
				if(!isset($row['materialNum'])){
					$row['materialNum'] = 0;
				}
				if(!isset($row['storagenum'])){
					$row['storagenum'] = 0;
				}
				if(!isset($row['canusenum'])){
					$row['canusenum'] = 0;
				}
				if(!isset($row['onwaynum'])){
					$row['onwaynum'] = 0;
				}
				$leafRow=$this->getLeafProduct($v['productId'],$v['parentProductID']);
				$listRow=array();
				if(empty($leafRow)){
					$listRow=$this->getProductList($v['productId'],$v['parentProductID']);
				}
				if($v['lowLevelCode']==1){
					if($leafRow['0']['id']==$v['id']){
						$result =$row['materialNum']- $row['onwaynum'] - $row['canusenum'];//����ɹ�����
					}else{
						if($listRow['0']['id']==$v['id']&&empty($leafRow)){
							$result =$row['materialNum']- $row['onwaynum'] - $row['canusenum'];//����ɹ�����
						}else{
							$result =$row['materialNum'];//����ɹ�����
						}
					}
				}else{
					if($leafRow['0']['id']==$v['id']){
						$purchTotal=$purchNum*$v['materialNum'];
						$result =$purchTotal- $row['onwaynum'] - $row['canusenum'];//����ɹ�����
					}else{
						if($listRow['0']['id']==$v['id']&&empty($leafRow)){
							$purchTotal=$purchNum*$v['materialNum'];
							$result =$purchTotal- $row['onwaynum'] - $row['canusenum'];//����ɹ�����
						}else{
							$purchTotal=$purchNum*$v['materialNum'];
							$result =$purchTotal;//����ɹ�����
						}
					}
				}
				if($result<0){
					$row['purchNum'] = 0;
				}else{
					$row['purchNum'] = $result;
				}
				if($v['rgt']-$v['lft']==1){//�Ƿ�Ҷ�ӽڵ�
					$row['isTheLeaf']=1;
				}else{
					$row['isTheLeaf']=0;
				}
	    	 	$row['children'] = $this->treeCondList($arr,$v['id'],$v['materialNum']*$neednum,$row['purchNum']);
	            $ret[] = $row;
			}
        }
        return $ret;
	}*/

//	private $result_array=array();

	/**
	 *�������淽��������ҳ�����Ҷ�ӽڵ�
	 *
	 */
//	 function dealMaterialList($array){
//		foreach($array as $key=>$value)
//		{
//			if(is_array($value)){
//				if($value['isTheLeaf']==0)
//				{
//					$this->dealMaterialList($value);
//				}else
//					array_push($this->result_array,$value);
//			}
//		}
//		return $this->result_array;
//	}

	/**
	 *��ѯҶ�ӽڵ�
	 *
	 */
//	 function getLeafProduct($productId,$parentProductId){
//	 	$sql="SELECT c.id,c.productId,c.productName,c.parentProductName,c.lft from (select id,productId,productName,parentProductName,lft,lowLevelCode from oa_stock_product_material
//				where parentProductID='".$parentProductId."' and productId='".$productId."' and rgt-lft=1 order by lowLevelCode DESC) c group by c.productId";
//		return $this->_db->getArray ( $sql );
//	 }

	/**
	 *��ѯ
	 *
	 */
//	 function getProductList($productId,$parentProductId){
//	 	$sql="select id,productId,productName,parentProductName,lft,lowLevelCode from oa_stock_product_material
//				where parentProductID='".$parentProductId."' and productId='".$productId."'  order by lowLevelCode DESC";
//		return $this->_db->getArray ( $sql );
//	 }

	 /**
	 * ����������ѯ����
	 *
	 */
//	 function getMaterialList($parentProductID){
//	 	$this->searchArr['parentProductID'] = $parentProductID;
//		$this->sort = 'c.lft';
//		$this->asc = false;
//		 $rows= $this->listBySqlId('treelist');
//		 return $rows;
//	 }

	 /**
	 * ����������ѯ����
	 *
	 */
//	 function getMaterialLeafList($parentProductID){
//	 	$this->searchArr['parentProductID'] = $parentProductID;
//		$this->sort = 'c.lft';
//		$this->groupBy='c.productId';
//		$this->asc = false;
//		 $rows= $this->listBySqlId('leaflist');
//		 return $rows;
//	 }

	 /**
	 * ���ɲɹ���������鴦��
	 *
	 */
//	 function dealPurchList($leafRows,$allLeafList){
//		if(is_array($allLeafList)){
//			foreach($allLeafList as $key=>$val){
//				$allLeafList[$key]['purchTotalNum']=0;
//				foreach($leafRows as $k=>$v){
//					if($val['productId']==$v['productId']&&$val['parentProductID']=$val['parentProductID']){
//						$allLeafList[$key]['purchTotalNum']=$allLeafList[$key]['purchTotalNum']+$v['purchNum'];
//					}
//				}
//			}
//			return $allLeafList;
//		}
//	 }


 }
?>