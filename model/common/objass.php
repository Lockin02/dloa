<?php

/**
 * @description: 对象关系类
 * @date 2010-12-18 上午10:11:42
 * @author oyzx
 * @version V1.0
 */
class model_common_objass extends model_base {

	private $objArr = array (
			//销售合同系统
	'sale' => array (
			array (
				'parentKey' => array (
					'contract_sales',
					'contract_sales',

				),
				'key' => 'contract_sales',
				'name' => '销售合同',
				'table' => 'oa_contract_sales'
			),
			array (
				'key' => 'contract_sales_equ',
				'name' => '销售合同设备',
				'table' => 'oa_contract_sales_equ'
			)
		),
			//采购系统
	'purch' => array (
				"oa_purch_objass", //总关联表名
		//			array('planAssType','planAssCode','planAssName','planAssId','planAssEquId',
		//					'planCode','planId','planEquType','planEquId','taskCode','taskId','taskEquId',
		//			'inquiryCode','inquiryId','inquiryEquId','applyCode','applyId','applyEquId'),
	array (
				'key' => 'purch_plan',
				'name' => '采购计划',
				'table' => 'oa_purch_plan_basic'
			),
			array (
				'key' => 'purch_plan_equ',
				'name' => '采购计划设备',
				'table' => 'oa_purch_plan_equ'
			),
			array (
				'key' => 'purch_task',
				'name' => '采购任务',
				'table' => 'oa_purch_task_basic'
			),
			array (
				'key' => 'purch_task_equ',
				'name' => '采购任务设备',
				'table' => 'oa_purch_task_equ'
			)
		),
			//仓存系统
	'stock' => array (
			'oa_stock_process'
		),
	//财务 － 到款分配
	'financeIncome' => array(
			'oa_finance_process_income'
		),
    //售前项目 - 项目关系表
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
	* @desription 生成对象唯一编号
	* @param $k 关联对象Key(标示名)
	* @date 2010-12-18 上午10:25:49
	*/
	function codeC($key) {
		return $key . '-' . md5(uniqid(rand()));
	}

	/**
	 * @desription 添加模块关联对象
	 * @param $modelKey 大模块key名称
	 * @param $objDataArr 需要添加的数组对象，可多条
	 */
	function addModelObjs($modelKey, $objDataArr) {
		//通过模块key获取模块总关联表名及字段流程
		$this->setTableAndFieldsByModelKey($modelKey);
		$this->createBatch($objDataArr);
	}

	/**
	 * @desription 根据传入条件添加或者更新模块关联对象
	 * @param $objDataArr 传入的条件关联对象数组
	 * @param $updateDataArr 需要更新的关联对象数组
	 */
	function saveModelObjs($modelKey, $objDataArr, $updateDataArr) {
		$this->setTableAndFieldsByModelKey($modelKey);
		//先更新，如果更新数量为0，则进行复制插入
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
			//复制一条上级数据
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
	 * @desription 获取模块关联对象集合(只获取到传入字段流程前的数据进行复制)
	 * @param $modelKey 大模块key名称
	 * @param $objData 字段数据搜索条件对象,如:array("planAssType"=>'contract_sales')
	 * @param $indexKey 从哪个字段开始后不获取数据
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
	 * @desription 获取模块关联对象集合
	 * @param $modelKey 大模块key名称
	 * @param $objData 字段数据搜索条件对象,如:array("planAssType"=>'contract_sales')
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
	 * 根据模块key获取并设置模块关联表名
	 */
	function setTableAndFieldsByModelKey($modelKey) {
		if (!isset ($this->tbl_name)) {
			$this->tbl_name = $this->objArr[$modelKey][0];
			//$this->field_process=$this->objArr[$modelKey][1];
			$this->field_process = $this->_db->getTable($this->tbl_name);
		}
		if (empty ($this->tbl_name)) {
			throw new Exception("根据模块key获取不到模块总关联表！");
		}
	}

	/*
	 * 根据采购合同设备id查找关联信息
	 */
	function getByApplyEquId($purchProId){
			return $this->get_table_fields("oa_purch_objass","applyEquId='$purchProId'","id");
	}

	//
	//  	/**
	//	 * @desription 设置对象
	//	 * @param $key 关联对象Key(标示名)
	//	 * @param $model 大模块key名称，默认为null
	//	 * 例：
	//	 * setObj ( 'contract_sales' , 'sale' )能最快的查询到销售合同对象相关的数组对象。
	//	 * setObj ( 'contract_sales' )与上面方法结果一致，但效率明显有差别，建议用上面的方式
	//	 * @date 2010-12-18 上午10:33:50
	//	 */
	//	function setObj ( $key , $model=null ) {
	//		foreach( $this->objArr as $k => $v ){
	//
	//			//当不存在模块名时，循环2维数组全部数据
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
	//			//当存在模块名时，直接循环模块数据
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
	//	 * @desription 添加对象
	//	 * @param $arr 需要添加的数组对象，可多条
	//	 * @param $k 关联对象Key(标示名)
	//	 * @param $model 大模块key名称，默认为null
	//	 * @date 2010-12-18 下午03:55:37
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
	//	 * @desription 添加对象-通过关联Id
	//	 * @param $arr 获取上级或多级的数组
	//	 * @param $obj 添加方法生产的数据
	//	 * @param $k 关联对象Key(标示名)
	//	 * @param $model 大模块key名称，默认为null
	//	 * @date 2010-12-20 上午11:36:15
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
	//	 * @desription 通过Id获取相关关联表数据
	//	 * @param $id Id
	//	 * @param $k 关联对象Key(标示名)
	//	 * @date 2010-12-21 下午05:14:37
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
	//	 * @desription 通过Ids(逗号隔开)获取相关关联表数据
	//	 * @param $id Ids
	//	 * @param $k 关联对象Key(标示名)
	//	 * @date 2010-12-21 下午05:14:37
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
	//	 * @desription 通过原本数组获取组建关系对象新数组
	//	 * @param tags
	//	 * @date 2010-12-21 下午09:21:57
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
	//	 * @desription 操作数据库方法
	//	 * @param $sql sql语句执行
	//	 * @date 2010-12-20 下午02:20:45
	//	 */
	//	function querySql ( $sql ) {
	//		$_db = new mysql ();
	//		return $_db->query( $sql );
	//		unset( $_db );
	//	}
	//
	//	/**
	//	 * @desription 获取数据方法
	//	 * @param tags
	//	 * @date 2010-12-21 下午05:22:50
	//	 */
	//	function findBySql ( $sql ) {
	//		$_db = new mysql ();
	//		return $_db->getArray( $sql );
	//		unset( $_db );
	//	}

}
?>
