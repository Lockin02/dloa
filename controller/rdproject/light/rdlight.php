<?php


class controller_rdproject_light_rdlight extends controller_base_action {

	 /**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-10-24 上午11:04:34
	 */
	function __construct () {
		$this->objName = "rdlight";
		$this->objPath = "rdproject_light";
		parent::__construct();
	}
/**
 * @desription 跳转到列表
 * @param tags
 * @date 2010-10-19 上午10:14:02
 */
	function c_tolightlist () {
			$service = $this->service; //设置前台获取的参数信息
			$service->getParam ( $_GET );


			$rows = $service->page_d();
			//print_r($rows);
			//分页显示，默认需要在Htm 加{pageDiv}
			$this->pageShowAssign ();

			$this->show->assign ( 'list', $service->showlist ( $rows ) );
			$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
/**
 * @desription 跳转到修改页面
 * @param tags
 * @date 2010-10-19 下午08:45:31
 */
	function c_toEdit () {
		$rdlight = $this->service->get_d($_GET['id']);
		if($rdlight[lightcol]=="绿色"){
			$yellowlRows=$this->service->find(array('lightcol'=>'黄色'));
			$this->assign('max',$yellowlRows['Min']);
			$this->assign('min',"");
		}else if($rdlight[lightcol]=="黄色"){
			$greenRows=$this->service->find(array('lightcol'=>'绿色'));
			$redRows=$this->service->find(array('lightcol'=>'红色'));
			$this->assign('max',$redRows['Min']);
			$this->assign('min',$greenRows['Max']);

		}else{
			$yellowlRows=$this->service->find(array('lightcol'=>'黄色'));
			$this->assign('min',$yellowlRows['Max']);
			$this->assign('max',"99999999999999");
		}
		foreach( $rdlight as $key => $val){
			$this->show->assign( $key, $val);
		}
		$this->show->display($this->objPath.'_'.$this->objName.'-edit');

	}


/**
 * @desription 保存修改后的信息
 * @param tags
 * @date 2010-10-19 下午08:50:55
 */
	function c_editlightinfo () {
		$rdlight = $_POST[$this->objName];
		//print_r($rdlight);
		$id = $this->service->edit_d($rdlight,true);
		if ($id){
			msg ( '修改成功' );
		}
	}

	/**
	 * 检验是否在范围中
	 */

	function c_ajaxRange(){
		$this->service->searchArr['rangeKey'] = isset($_GET['Max']) ? $_GET['Max'] : $_GET['Min'] ;
		$this->service->searchArr['notId'] = $_GET['notId'];
		$rs = $this->service->listBySqlId();
		if(empty($rs)){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>
