<?php
/*
 *评估供应商controller类方法
 * */
class controller_supplierManage_assess_suppassess extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-10 下午04:33:17
	 */
	function __construct () {
		$this->objName = "suppassess";
		$this->objPath = "supplierManage_assess";
		parent::__construct();
	}

	/**
	 * @desription 供应商添加列表
	 * @param tags
	 * @date 2010-11-11 下午05:39:22
	 */
	function c_sasAddList () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add-list' );
	}

	/**
	 * @desription 跳转添加方法
	 * @param tags
	 * @date 2010-11-11 下午07:52:57
	 */
	function c_sasToAdd () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * @desription 添加供应商
	 * @param tags
	 * @date 2010-11-11 下午08:30:27
	 */
	function c_sasAdd () {
		$objArr = $_POST [$this->objName];
		$this->service->pk = "id";
//		$aa = $this->service->findCount( array( "assesId"=>$objArr['assesId'],"suppId"=>$objArr['suppId'] ) );
//		if($aa>0){
//			msgGo("已存在供应商，添加失败！");
//		}else{
			$suppIdArr = explode(",", $objArr['suppId']);
			$suppNameArr = explode(",", $objArr['suppName']);
			$suppArr=array();
			foreach ($suppIdArr as $key => $value) {
				$suppArr[$value]['assesId']=$objArr['assesId'];
				$suppArr[$value]['suppId']=$value;
				$suppArr[$value]['suppName']=$suppNameArr[$key];
			}
			$tag = $this->service->addBatch_d ( $suppArr );
			if ($tag) {
				//$objArr ['id'] = $id;
				//$objArr ['operType_'] = "添加评估供应商【" . $objArr ['suppName'] . "】";
				//$this->behindMethod ( $objArr );
				msg ( '添加成功' );
			}else{
				msg ( '添加失败！可能选取的供应商已经存在！' );
			}
		//}

	}

	/**
	 * @desription 供应商gridjson数据
	 * @param tags
	 * @date 2010-11-11 下午07:11:00
	 */
	function c_sasPageJson () {
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr = array( "assesId"=>$assId );
		$rows = $service->sasPage_d ();
		$resultArr=array();
		foreach($rows as $key=>$val){
			$val['skey_']=$this->md5Row($val['suppId'],'supplierManage_formal_flibrary');
			array_push($resultArr,$val);
		}
		$arr = array ();
		$arr ['collection'] = $resultArr;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 查看
	 * @param tags
	 * @date 2010-11-16 下午07:26:28
	 */
	function c_sasView () {
		$this->permCheck ($_GET['assId'],'supplierManage_assess_assessment');//安全校验
		$assId = isset( $_GET['assId'] )?$_GET['assId']:exit;
		$this->show->assign ( 'assId', $assId );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read-list' );
	}


}
?>
