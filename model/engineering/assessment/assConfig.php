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
 * @desription ����id��ȡָ��
 * @param tags
 * @date 2010-12-7 ����07:25:27
 * @qiaolong
 */
	function getConfigInfo ($parentId) {
//		echo $id;
		$this->searchArr['parentId'] = $parentId;
		return $this->pageBySqlId('assConfigInfo');
	}

	/**
	 * @desription ���ָ��ͬʱ������ϸ
	 * @param tags
	 * @date 2010-12-9 ����10:45:03
	 * @qiaolong
	 */
	function add_d($object) {

		//1.����ѡ��
		$newId = parent::add_d($object,true);

		//2.��ȡָ������ѡ��,�����ַ���
		$rows = $this->findAll( array ('parentId' => $object['parentId']) );
//		echo "<pre>";
//		print_r($rows);
		$str = '';
		$i = 0;
		foreach($rows as $key => $val ){
			$i ++;
			$str .= ' ('.$i.')'. $val['name'] .' <font color="blue">( '. $val['score'] .' )</font> ; ';
		}
		//3.����ָ����,����ָ����ϸ����
		$assIndexDao = new model_engineering_assessment_assIndex();
		$assIndexDao->edit_d(array('id'=> $object['parentId'],'detail' => $str));
		return $newId;
	}
	/**
	 * @desription �޸�ָ��ͬʱ������ϸ
	 * @param tags
	 * @date 2010-12-9 ����10:45:03
	 * @qiaolong
	 */
	function edit_d($object) {

		//1.����ѡ��
		$newId = parent::edit_d($object,true);

		//2.��ȡָ������ѡ��,�����ַ���
		$rows = $this->findAll( array ('parentId' => $object['parentId']) );
		$str = '';
		$i = 0;
		foreach($rows as $key => $val ){
			$i ++;
			$str .= ' ('.$i.')'. $val['name'] .' <font color="blue">( '. $val['score'] .' )</font> ; ';
		}
		//3.����ָ����,����ָ����ϸ����
		$assIndexDao = new model_engineering_assessment_assIndex();
		$assIndexDao->edit_d(array('id'=> $object['parentId'],'detail' => $str));
		return $this->updateById ( $object );
	}
	/**
	 * @desriptionɾ��ָ��ͬʱ������ϸ
	 * @param tags
	 * @date 2010-12-9 ����10:45:03
	 * @qiaolong
	 */
	function delete($conditions) {
		alert($conditions);

	}
}
?>
