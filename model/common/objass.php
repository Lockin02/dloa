<?php

/**
 * @description: �����ϵ��
 * @date 2010-12-18 ����10:11:42
 * @author oyzx
 * @version V1.0
 */
class model_common_objass extends model_base {

	private $objArr = array (
			//���ۺ�ͬϵͳ
	'sale' => array (
			array (
				'parentKey' => array (
					'contract_sales',
					'contract_sales',

				),
				'key' => 'contract_sales',
				'name' => '���ۺ�ͬ',
				'table' => 'oa_contract_sales'
			),
			array (
				'key' => 'contract_sales_equ',
				'name' => '���ۺ�ͬ�豸',
				'table' => 'oa_contract_sales_equ'
			)
		),
			//�ɹ�ϵͳ
	'purch' => array (
				"oa_purch_objass", //�ܹ�������
		//			array('planAssType','planAssCode','planAssName','planAssId','planAssEquId',
		//					'planCode','planId','planEquType','planEquId','taskCode','taskId','taskEquId',
		//			'inquiryCode','inquiryId','inquiryEquId','applyCode','applyId','applyEquId'),
	array (
				'key' => 'purch_plan',
				'name' => '�ɹ��ƻ�',
				'table' => 'oa_purch_plan_basic'
			),
			array (
				'key' => 'purch_plan_equ',
				'name' => '�ɹ��ƻ��豸',
				'table' => 'oa_purch_plan_equ'
			),
			array (
				'key' => 'purch_task',
				'name' => '�ɹ�����',
				'table' => 'oa_purch_task_basic'
			),
			array (
				'key' => 'purch_task_equ',
				'name' => '�ɹ������豸',
				'table' => 'oa_purch_task_equ'
			)
		),
			//�ִ�ϵͳ
	'stock' => array (
			'oa_stock_process'
		),
	//���� �� �������
	'financeIncome' => array(
			'oa_finance_process_income'
		),
    //��ǰ��Ŀ - ��Ŀ��ϵ��
      'projectInfo' => array(
            'oa_sale_relateinfo'
           )
	);

	private $objKey = null;
	private $objName = null;
	private $objTable = null;

	function __construct() {
		parent :: __construct();
	}

	/**
	* @desription ���ɶ���Ψһ���
	* @param $k ��������Key(��ʾ��)
	* @date 2010-12-18 ����10:25:49
	*/
	function codeC($key) {
		return $key . '-' . md5(uniqid(rand()));
	}

	/**
	 * @desription ���ģ���������
	 * @param $modelKey ��ģ��key����
	 * @param $objDataArr ��Ҫ��ӵ�������󣬿ɶ���
	 */
	function addModelObjs($modelKey, $objDataArr) {
		//ͨ��ģ��key��ȡģ���ܹ����������ֶ�����
		$this->setTableAndFieldsByModelKey($modelKey);
		$this->createBatch($objDataArr);
	}

	/**
	 * @desription ���ݴ���������ӻ��߸���ģ���������
	 * @param $objDataArr ���������������������
	 * @param $updateDataArr ��Ҫ���µĹ�����������
	 */
	function saveModelObjs($modelKey, $objDataArr, $updateDataArr) {
		$this->setTableAndFieldsByModelKey($modelKey);
		//�ȸ��£������������Ϊ0������и��Ʋ���
		$sql = "update " . $this->tbl_name . " set ";
		$plus = "";
		foreach ($updateDataArr as $k => $v) {
			$plus .= $k . '="' . $v . '",';
		}
		$sql .= substr($plus, 0, -1) . ' where 1=1';
		foreach ($objDataArr as $k => $v) {
			$sql .= ' and ' . $k . '=' . $v;
		}
		$indexKey = "";
		$i = 0;
		foreach ($updateDataArr as $k => $v) {
			if ($i++ == 0)
				$indexKey = $k;
			$sql .= ' and ' . $k . ' is null ';
		}
		$this->query($sql);
		$num = $this->_db->affected_rows();
		if ($num == 0) {
			//����һ���ϼ�����
			$dataArr = $this->getModelLastProcessObjs($modelKey, $objDataArr, $indexKey);
			if (is_array($dataArr)) {
				$data = $dataArr[0];
				unset ($data['id']);
			}
			$data = array_merge($data, $updateDataArr);
			$this->add_d($data);
		}

	}

	/**
	 * @desription ��ȡģ��������󼯺�(ֻ��ȡ�������ֶ�����ǰ�����ݽ��и���)
	 * @param $modelKey ��ģ��key����
	 * @param $objData �ֶ�����������������,��:array("planAssType"=>'contract_sales')
	 * @param $indexKey ���ĸ��ֶο�ʼ�󲻻�ȡ����
	 */
	function getModelLastProcessObjs($modelKey, $objData, $indexKey) {
		$this->setTableAndFieldsByModelKey($modelKey);
		$sql = "select ";
		$plus = "";
		foreach ($this->field_process as $v) {
			if ($v['Field'] == $indexKey)
				break;
			$plus .= 'c.' . $v['Field'] . ',';
		}
		$sql .= substr($plus, 0, -1);
		$sql .= " from " . $this->tbl_name . " c where 1=1 ";
		$condition = "";
		foreach ($objData as $key => $val) {
			$condition .= 'and ' . $key . '=' . $val;
		}

		$sql .= $condition;
		//echo $sql;
		return $this->findSql($sql);
	}

	/**
	 * @desription ��ȡģ��������󼯺�
	 * @param $modelKey ��ģ��key����
	 * @param $objData �ֶ�����������������,��:array("planAssType"=>'contract_sales')
	 */
	function getModelObjs($modelKey, $objData) {
		$this->setTableAndFieldsByModelKey($modelKey);
		$sql = "select * from " . $this->tbl_name . " where 1=1 ";
		$condition = "";
		foreach ($objData as $key => $val) {
			$condition .= 'and ' . $key . '=' . $val;
		}
		//		if(is_array($nullKeys)){
		//			foreach( $nullKeys as $key => $val ){
		//				$condition.='and'.$key.' is null';
		//			}
		//		}
		$sql .= $condition;
		return $this->findSql($sql);
	}

	/**
	 * ����ģ��key��ȡ������ģ���������
	 */
	function setTableAndFieldsByModelKey($modelKey) {
		if (!isset ($this->tbl_name)) {
			$this->tbl_name = $this->objArr[$modelKey][0];
			//$this->field_process=$this->objArr[$modelKey][1];
			$this->field_process = $this->_db->getTable($this->tbl_name);
		}
		if (empty ($this->tbl_name)) {
			throw new Exception("����ģ��key��ȡ����ģ���ܹ�����");
		}
	}

	/*
	 * ���ݲɹ���ͬ�豸id���ҹ�����Ϣ
	 */
	function getByApplyEquId($purchProId){
			return $this->get_table_fields("oa_purch_objass","applyEquId='$purchProId'","id");
	}

	//
	//  	/**
	//	 * @desription ���ö���
	//	 * @param $key ��������Key(��ʾ��)
	//	 * @param $model ��ģ��key���ƣ�Ĭ��Ϊnull
	//	 * ����
	//	 * setObj ( 'contract_sales' , 'sale' )�����Ĳ�ѯ�����ۺ�ͬ������ص��������
	//	 * setObj ( 'contract_sales' )�����淽�����һ�£���Ч�������в�𣬽���������ķ�ʽ
	//	 * @date 2010-12-18 ����10:33:50
	//	 */
	//	function setObj ( $key , $model=null ) {
	//		foreach( $this->objArr as $k => $v ){
	//
	//			//��������ģ����ʱ��ѭ��2ά����ȫ������
	//			if(  !$model  ){
	//				foreach( $v as $k2 => $v2 ){
	//					if( $v2['key'] == $key ){
	//						$this->objKey = $v2['key'];
	//						$this->objName = $v2['name'];
	//						$this->objTable = $v2['table'];
	//						break;
	//					}
	//				}
	//			}
	//			//������ģ����ʱ��ֱ��ѭ��ģ������
	//			else if( $model == $k ){
	//				foreach( $v as $k2 => $v2 ){
	//					if( $v2['key'] == $key ){
	//						$this->objKey = $v2['key'];
	//						$this->objName = $v2['name'];
	//						$this->objTable = $v2['table'];
	//						break;
	//					}
	//				}
	//			}
	//		}
	//		return isset( $this->objKey )? array( 'key'=>$this->objKey , 'name'=>$this->objName , 'table'=>$this->objTable ) : false ;
	//	}
	//
	//
	//
	//
	//
	//	/**
	//	 * @desription ��Ӷ���
	//	 * @param $arr ��Ҫ��ӵ�������󣬿ɶ���
	//	 * @param $k ��������Key(��ʾ��)
	//	 * @param $model ��ģ��key���ƣ�Ĭ��Ϊnull
	//	 * @date 2010-12-18 ����03:55:37
	//	 */
	//	function addObj ( $arr , $k , $model=null ) {
	//
	//		$objArr = $this->setObj( $k , $model );
	//		$sqlResult = '';
	//		foreach( $arr as $key => $val ){
	//			$val['objAssCode'] = isset( $val['objAssCode'] )? $val['objAssCode'] : '' ;
	//			$val['objAssName'] = isset( $val['objAssName'] )? $val['objAssName'] : '' ;
	//			$sqlResult .= '("'.$val['objAssId'].'","'.$val['objAssCode'].'","'.$val['objAssName'].'","'.$val['objAssType'].'","'.$val['objId'].'"),';
	//		}
	//		$sql = substr('insert into '.$objArr['table'].'_objass(objAssId,objAssCode,objAssName,objAssType,objId) values '.$sqlResult , 0 , -1 );
	//		return $this->querySql($sql);
	//		unset($arr);
	//	}
	//
	//	/**
	//	 * @desription ��Ӷ���-ͨ������Id
	//	 * @param $arr ��ȡ�ϼ���༶������
	//	 * @param $obj ��ӷ�������������
	//	 * @param $k ��������Key(��ʾ��)
	//	 * @param $model ��ģ��key���ƣ�Ĭ��Ϊnull
	//	 * @date 2010-12-20 ����11:36:15
	//	 */
	//	function addObjByArr( $arr , $obj , $k , $model=null ){
	//		foreach( $arr as $key => $val ){
	//			$arr[$key]['objId'] = $obj['objId'];
	//		}
	//		$arr[] = $obj;
	//		return $this->addObj($arr , $k , $model );
	//	}
	//
	//
	//
	//
	//
	//
	//	/**
	//	 * @desription ͨ��Id��ȡ��ع���������
	//	 * @param $id Id
	//	 * @param $k ��������Key(��ʾ��)
	//	 * @date 2010-12-21 ����05:14:37
	//	 */
	//	function getArrById_d ( $id , $k , $model=null ) {
	//		$this->setObj( $k , $model );
	//		if( isset( $this->objTable ) && $this->objTable!= '' ){
	//			$sql = ' select * from '.$this->objTable.'_objass where objId=\''.$id.'\' ';
	//			return $this->findBySql( $sql );
	//		}else{
	//			return false;
	//		}
	//	}
	//
	//	/**
	//	 * @desription ͨ��Ids(���Ÿ���)��ȡ��ع���������
	//	 * @param $id Ids
	//	 * @param $k ��������Key(��ʾ��)
	//	 * @date 2010-12-21 ����05:14:37
	//	 */
	//	function getArrByIds_d ( $ids , $k , $model=null ) {
	//		$this->setObj( $k , $model );
	//		if( isset( $this->objTable ) && $this->objTable!= '' && $ids!='' ){
	//			$ids = substr($ids,-1)==',' ? str_replace( ",","','", substr($ids,0,-1)) : str_replace( ",","','", $ids );
	//			$sql = " select * from ".$this->objTable."_objass where objId in ('".$ids."') ";
	//			return $this->findBySql( $sql );
	//		}else{
	//			return false;
	//		}
	//	}
	//
	//
	//	/**
	//	 * @desription ͨ��ԭ�������ȡ�齨��ϵ����������
	//	 * @param tags
	//	 * @date 2010-12-21 ����09:21:57
	//	 */
	//	function getArrAsAss_d ( $rows ,$k , $model=null ) {
	//		$ids = '';
	//		foreach($rows as $key => $val){
	//			$ids .= $rows[$key]['id'].',';
	//		}
	//		$objassArr = $this->getArrByIds_d( $ids , $k , $model );
	//		foreach( $objassArr as $keyAss => $valAss ){
	//			foreach( $rows as $key => $val ){
	//				if( $val['id'] == $valAss['objId'] ){
	//					$rows[$key]['objAss'][] = $valAss;
	//				}
	//			}
	//		}
	//		return $rows;
	//	}
	//
	//
	//	/**
	//	 * @desription �������ݿⷽ��
	//	 * @param $sql sql���ִ��
	//	 * @date 2010-12-20 ����02:20:45
	//	 */
	//	function querySql ( $sql ) {
	//		$_db = new mysql ();
	//		return $_db->query( $sql );
	//		unset( $_db );
	//	}
	//
	//	/**
	//	 * @desription ��ȡ���ݷ���
	//	 * @param tags
	//	 * @date 2010-12-21 ����05:22:50
	//	 */
	//	function findBySql ( $sql ) {
	//		$_db = new mysql ();
	//		return $_db->getArray( $sql );
	//		unset( $_db );
	//	}

}
?>
