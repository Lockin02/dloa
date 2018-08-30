<?php
/**
 * @author Administrator
 * @Date 2011年6月2日 10:30:25
 * @version 1.0
 * @description:生产任务书 Model层
 */
 class model_produce_protask_protask  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_protask";
		$this->sql_map = "produce/protask/protaskSql.php";
		parent::__construct ();
		$this->relatedStrategyArr = array (//不同类型出库申请策略类,根据需要在这里进行追加
					"oa_sale_order" => "model_produce_protask_strategy_salesprotask", //销售发货
					"oa_sale_lease" => "model_produce_protask_strategy_rentalprotask", //租赁出库
					"oa_sale_service" => "model_produce_protask_strategy_serviceprotask", //服务合同出库
					"oa_sale_rdproject" => "model_produce_protask_strategy_rdprojectprotask", //研发合同出库
					"oa_borrow_borrow" => "model_produce_protask_strategy_borrowprotask", //借用发货
					"oa_present_present" => "model_produce_protask_strategy_presentprotask", //借用发货
				);
	}
	/*===================================页面模板======================================*/
	/**
	 * @description 生产任务列表显示模板
	 * @param $rows
	 */
	function showList($rows,taskstrategy $istrategy){
		$istrategy->showList($rows);
	}

	/**
	 * @description 新增生产任务时，清单显示模板
	 * @param $rows
	 */
	function showItemAdd($rows,taskstrategy $istrategy) {
		return $istrategy->showItemAdd($rows);
	}

	/**
	 * @description 修改生产任务时，清单显示模板
	 * @param $rows
	 */
	function showItemEdit($rows,taskstrategy $istrategy) {
		return $istrategy->showItemEdit($rows);
	}

	/**
	 * @description 查看生产任务时，清单显示模板
	 * @param $rows
	 */
	function showItemView($rows,taskstrategy $istrategy) {
		return $istrategy->showItemView($rows);
	}

	/**
	 * 查看相关业务信息
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr =false,$skey,taskstrategy $istrategy) {
		return $istrategy->viewRelInfo($paramArr,$skey);
	}

	/**
	 * 下推获取源单数据方法
	 */
	 function getDocInfo( $id, taskstrategy $istrategy ){
		$rows = $istrategy->getDocInfo($id);
		return $rows;
	 }

	/**
	 * 新增生产任务时源单据业务处理
	 * @param $istorageapply 策略接口
	 * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr 从表清单信息
	 */
	function ctDealRelInfoAtAdd(taskstrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}
	/**
	 * 修改生产任务时源单据业务处理
	 * @param $istorageapply 策略接口
	 * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
	 * @param  $relItemArr 从表清单信息
	 */
	function ctDealRelInfoAtEdit(taskstrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

	/**
	 * 添加对象
	 */
	 function add_d( $object ){
		try{
			$this->start_d();
			$id = parent::add_d( $object, true );
			$relDocType = isset ($object['relDocType']) ? $object['relDocType'] : null;
			if ($relDocType) { //存在发货计划类型
				$protaskStrategy = $this->relatedStrategyArr[$relDocType];
				if ($protaskStrategy) {
						$paramArr = array (//单据主表参数
							'mainId' => $id,
							'relDocId' =>  $object['relDocId'],
							'relDocCode' => $object['relDocCode'],
							'relDocType' => $object['relDocType'],
						); //...可以继续追加
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //单据清单信息
						$protaskequ= new model_produce_protask_protaskequ();
						$mainIdArr = array( 'mainId' => $paramArr['docId'] );
						$protaskequ->createBatch( $relItemArr,$paramArr );
					} else {
						throw new Exception("单据信息不完整，请确认!");
					}
					//统一选择策略，进入各自的业务处理
					$storageproId = $this->ctDealRelInfoAtAdd(new $protaskStrategy (), $paramArr, $relItemArr);
				} else {
					throw new Exception("该类型发货申请暂未开放，请联系开发人员!");
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			/*end:抽象关联单据业务处理,只负责把必要参数按照规则传到策略包装方法,以后是相对固定的代码*/

			$this->commit_d();
			return $id;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	 }



	/**
	 * 编辑对象
	 */
	 function edit_d( $object ){
		try{
			$this->start_d();
			$id = parent::edit_d( $object, true );
			$relDocType = isset ($object['relDocType']) ? $object['relDocType'] : null;
			if ($relDocType) { //存在发货计划类型
				$protaskStrategy = $this->relatedStrategyArr[$relDocType];
				if ($protaskStrategy) {
						$paramArr = array (//单据主表参数
							'mainId' => $object['id'],
							'relDocId' => $object['relDocId'],
							'relDocCode' => $object['relDocCode'],
							'relDocType' => $object['relDocType'],
						); //...可以继续追加
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //单据清单信息
						foreach( $relItemArr as $key => $val ){
							unset($relItemArr[$key]['issuedProNum']);
						}
						$prod = new model_produce_protask_protaskequ();
						$mainIdArr = array( 'mainId' => $object['id'] );
						$prod->delete($mainIdArr);
						$prod->createBatch( $relItemArr,$paramArr );
					} else {
						throw new Exception("单据信息不完整，请确认!");
					}
					//统一选择策略，进入各自的业务处理
					$relItemArr = $object['productsdetail']; //单据清单信息
					$storageproId = $this->ctDealRelInfoAtEdit(new $protaskStrategy (), $paramArr, $relItemArr);
				} else {
					throw new Exception("该类型出库申请暂未开放，请联系开发人员!");
				}
			} else {
				throw new Exception("单据信息不完整，请确认!");
			}
			/*end:抽象关联单据业务处理,只负责把必要参数按照规则传到策略包装方法,以后是相对固定的代码*/

			$this->commit_d();
			return $id;
		}catch( Exception $e ){
			$this->rollBack();
			return null;
		}
	 }


	/**
	 * 根据id获出库申请单所有信息
	 */
	function get_d($id) {
		$protaskInfo = parent :: get_d($id);
		$itemDao = new model_produce_protask_protaskequ();
		$searchArr = array(
			'mainId' => $id,
		 );
		$protaskInfo['details'] = $itemDao -> findAll( $searchArr );
		return $protaskInfo;
	}
 }
?>