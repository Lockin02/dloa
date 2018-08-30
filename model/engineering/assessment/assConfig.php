<?php
/*
 * Created on 2010-12-4
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 class model_engineering_assessment_assConfig extends model_base{
	function __construct() {
		$this->tbl_name = "oa_esm_ass_config";
		$this->sql_map = "engineering/assessment/assConfigSql.php";
		parent::__construct ();
	}

/**
 * @desription 根据id获取指标
 * @param tags
 * @date 2010-12-7 下午07:25:27
 * @qiaolong
 */
	function getConfigInfo ($parentId) {
//		echo $id;
		$this->searchArr['parentId'] = $parentId;
		return $this->pageBySqlId('assConfigInfo');
	}

	/**
	 * @desription 填加指标同时更改详细
	 * @param tags
	 * @date 2010-12-9 上午10:45:03
	 * @qiaolong
	 */
	function add_d($object) {

		//1.插入选项
		$newId = parent::add_d($object,true);

		//2.获取指标所有选项,生成字符串
		$rows = $this->findAll( array ('parentId' => $object['parentId']) );
//		echo "<pre>";
//		print_r($rows);
		$str = '';
		$i = 0;
		foreach($rows as $key => $val ){
			$i ++;
			$str .= ' ('.$i.')'. $val['name'] .' <font color="blue">( '. $val['score'] .' )</font> ; ';
		}
		//3.调用指标类,插入指标详细内容
		$assIndexDao = new model_engineering_assessment_assIndex();
		$assIndexDao->edit_d(array('id'=> $object['parentId'],'detail' => $str));
		return $newId;
	}
	/**
	 * @desription 修改指标同时更改详细
	 * @param tags
	 * @date 2010-12-9 上午10:45:03
	 * @qiaolong
	 */
	function edit_d($object) {

		//1.插入选项
		$newId = parent::edit_d($object,true);

		//2.获取指标所有选项,生成字符串
		$rows = $this->findAll( array ('parentId' => $object['parentId']) );
		$str = '';
		$i = 0;
		foreach($rows as $key => $val ){
			$i ++;
			$str .= ' ('.$i.')'. $val['name'] .' <font color="blue">( '. $val['score'] .' )</font> ; ';
		}
		//3.调用指标类,插入指标详细内容
		$assIndexDao = new model_engineering_assessment_assIndex();
		$assIndexDao->edit_d(array('id'=> $object['parentId'],'detail' => $str));
		return $this->updateById ( $object );
	}
	/**
	 * @desription删除指标同时更改详细
	 * @param tags
	 * @date 2010-12-9 上午10:45:03
	 * @qiaolong
	 */
	function delete($conditions) {
		alert($conditions);

	}
}
?>
