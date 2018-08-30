<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/purchase/external/planBasic.php';

/**
 * @description: 生产采购model
 * @date 2010-12-17 下午04:28:25
 * @author oyzx
 * @version V1.0
 */
class model_purchase_external_produce extends planBasic {

	private $externalDao; //外部对象dao接口
	private $externalEquDao; //外部对象设备dao接口

	function __construct() {

		//调用初始化对象关联类
		$this->objass = new model_common_objass();
	}

	/***************************************************************************************************
	 * ------------------------------以下为页面模板显示调用方法------------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 添加采购计划-显示列表
	 * @param $equs
	 * @param $mainRows
	 * @date 2010-12-17 下午05:17:09
	 */
	function showAddList($equs,$mainRows) {
		//TODO:追加需求保存到采购计划关联表的信息，待生产模块完成后，再来补充
		$str.=<<<EOT
					<input type="hidden" name="basic[objAssId]" value="" />
					<input type="hidden" name="basic[objAssName]" value="生产采购" />
					<input type="hidden" name="basic[objAssType]" value="produce" />
					<input type="hidden" name="basic[objAssCode]" value="" />
					<input type="hidden" name="basic[equObjAssType]" value="produce"/>
					</td>
EOT;
		return $str;
	}

	/***************************************************************************************************
	 * ------------------------------以下接口方法,可供其他模块调用---------------------------------------*
	 **************************************************************************************************/

	/**
	 * @desription 通过生产申请单Id获取产品数据
	 * @param $parentId
	 * @date 2010-12-17 下午05:05:57
	 */
	function getItemsByParentId ($parentId) {
		//TODO:通过生产申请单Id获取产品数据
		return true;
	}

	/**
	 * 根据采购类型的ID，获取其信息
	 *
	 * @param $id
	 * @return
	 */
	function getInfoList ($id) {
		//TODO:目前没有生产的信息，待以后处理
		return true;
	}

	/**
	 * 根据不同的类型采购计划，进行业务处理
	 *
	 * @param $paramArr
	 */
	function dealInfoAtPlan ($paramArr){
		//TODO:对于生产的采购计划添加时的业务处理，有需求时可以在此添加
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
					msgGo('保存成功');
				}else{
					msgGo('物料信息不完整，没有物料或数量为0，下达失败');
				}
			}
	}
}
?>
