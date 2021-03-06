<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

/**
 * @description: 研发采购入库model
 * @date 2010-12-17 下午04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_rdproject extends planBasic {

	private $externalDao; //外部对象dao接口
	private $externalEquDao; //外部对象设备dao接口

	function __construct() {
		$this->externalDao = new model_rdproject_project_rdproject();
		$this->externalEquDao = new model_rdproject_project_rdproject();

		//调用初始化对象关联类
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 添加采购计划-显示列表
	 * @param tags
	 * @date 2010-12-17 下午05:17:09
	 */
	function showAddList($equs,$mainRows) {
		$str.=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="$mainRows[id]" />
					<input type="hidden" name="basic[objAssName]" value="$mainRows[projectName]" />
					<input type="hidden" name="basic[objAssType]" value="rdproject" />
					<input type="hidden" name="basic[objAssCode]" value="$mainRows[projectCode]" />
					<input type="hidden" name="basic[equObjAssType]" value="rdproject_equ" />
					</td>
EOT;
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 通过研发项目单Id获取产品数据
	 * @param tags
	 * @date 2010-12-17 下午05:05:57
	 */
	function getItemsByParentId ($parentId) {
		return true;
	}

	/**
	 * 根据采购类型的单据ID，获取其信息
	 *
	 * @param $id
	 * @return return_type
	 */
	function getInfoList ($id) {
		$mainRows=$this->externalDao->get_d($id);
		return $mainRows;
	}

	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		//TODO:
		return true;
	}

	/**更新已下达采购数量
	*author can
	*2011-3-22
	*/
	function updateAmountIssued($id,$issuedNum,$lastIssueNum){
		return true;
	}

	/**
	 * 下达采购计划后页面原跳转
	 *
	 * @param $id	采购申请保存ID
	 */
	function toShowPage ($id,$type=null) {
			if($type){
				if($id){
					msg('保存成功');
				}else{
					msg('物料信息不完整，没有物料或数量为0，下达失败');
				}
			}else{		//从统一接口下面采购计划后的跳转页面
				if($id){
					msgGo('保存成功','index1.php?model=purchase_external_external&action=toRdprojectAdd');
				}else{
					msgGo('物料信息不完整，没有物料或数量为0，下达失败','index1.php?model=purchase_external_external&action=toRdprojectAdd');
				}
			}
	}

}
?>
