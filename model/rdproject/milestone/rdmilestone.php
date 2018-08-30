<?php
/**
 * @description: 里程碑计划Model
 * @date 2010-9-18 上午11:23:53
 * @author oyzx
 * @version V1.0
 */
class model_rdproject_milestone_rdmilestone extends model_base {

	/**
	 * @desription 构造函数
	 * @date 2010-9-18 上午11:23:53
	 */
	function __construct() {
		$this->tbl_name = "oa_rd_milestone_plan";
		$this->sql_map = "rdproject/milestone/rdmilestoneSql.php";
		parent::__construct ();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

		/**
		 * @desription 里程碑列表
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmList_s ( $arr ) {
			$pointDao = new model_rdproject_milestone_rdmilespoint();
			return $pointDao->rmList_s($arr);
		}

		/**
		 * @desription 里程碑计划列表
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmListCenter_s ( $arr ) {
			$pointDao = new model_rdproject_milestone_rdmilespoint();
			return $pointDao->rmListCenter_s($arr);
		}

		/** @desription 里程碑计划列表
		 * @param tags
		 * @date 2010-10-3 下午08:13:42
		 */
		function rmListRead_s ( $arr ) {
			$pointDao = new model_rdproject_milestone_rdmilespoint();
			return $pointDao->rmListRead_s($arr);
		}

	/***************************************************************************************************
	 * ------------------------------以下为数据字典接口方法,可以为其他模块所调用--------------------------*
	 **************************************************************************************************/

	public function rmAdd($milArr,$pjArr){
		try {
			$this->start_d ();
			$addArr = array();
			$addArr['milestoneCode'] = "rdmilestone" . date ( "YmdHis" ) . rand ( 10, 99 );
			$addArr[ 'milestoneName'] = "里程碑计划";
			$addArr['projectId'] = $pjArr['id'];
			$addArr['projectCode'] = $pjArr['projectCode'];
			$addArr['projectName'] = $pjArr['projectName'];
			$mId = $this->add_d($addArr,true);
			$milespointDao = new model_rdproject_milestone_rdmilespoint();
            $thisParentId = null;
			if( is_array($milArr) ){
				$milpointArr = $milArr;
				foreach( $milpointArr as $key => $val){
					$pointArr = array();
					$pointArr['pointName'] = $val['milestoneName'];
					$pointArr['projectId'] = $pjArr['id'];
					$pointArr['projectCode'] = $pjArr['projectCode'];
					$pointArr['projectName'] = $pjArr['projectName'];
					$pointArr['milestoneId'] = $mId;
					$pointArr['milestoneCode'] = $addArr['milestoneCode'];
					$pointArr['code'] = $val['numb'];
					$pointArr['frontCode'] = $val['frontNumb'];
                    //添加父节点Id
                    $pointArr['parentId'] = $thisParentId;
					if( $val['frontNumb']=="-1" ){
						$pointArr['status'] = $milespointDao->statusDao->statusEtoK ( "execute" );
						$pointArr['realBeginDate'] = day_date;
					}
					$thisParentId = $milespointDao->add_d($pointArr,true);
				}
			}
			$this->commit_d ();
			return $mId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			throw $e;
			return null;
		}
	}

	/**
	 * @desription 获取里程碑数据
	 * @param tags
	 * @date 2010-10-3 下午07:39:54
	 */
	public function rmArrBypjId_d ($pjId) {
		$searchArr = array (
					'pjId' => $pjId
				);
		$this->__SET('searchArr', $searchArr);
		$rows = $this->listBySqlId('select_readCenter');
		if( is_array($rows) ){
			$pointDao = new model_rdproject_milestone_rdmilespoint();
			$searchArrRm = array (
				'milestonId' => $rows['0']['id']
			);
			$this->asc = false;
			$pointArr = $pointDao->rmArrList_d($searchArrRm);

			if( is_array($pointArr) ){
				$rows['0']['pointArr'] = $pointArr;
			}
//			echo "<pre>";
//			print_r($rows);
			return $rows;
		}else{
			return null;
		}
	}
}
?>
