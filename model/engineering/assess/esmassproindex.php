<?php
/**
 * @author Show
 * @Date 2012��12��10�� ����һ 14:20:05
 * @version 1.0
 * @description:��Ŀָ����ϸ Model��
 */
class model_engineering_assess_esmassproindex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_assindex";
		$this->sql_map = "engineering/assess/esmassproindexSql.php";
		parent :: __construct();
	}

	/**
	 * ���ݴ���Ķ��������Զ������������޸ģ�ɾ��(��Ҫ���ڽ�����ӱ��жԴӱ�������������)
	 * �жϹ���
	 * 1.���idΪ����isDelTag����Ϊ1����������������������Ӻ�ɾ�����,��̨ɶ��������
	 * 2.���idΪ�գ�������
	 * 3.���isDelTag����Ϊ1����ɾ��
	 * 4.�����޸�
	 * @param Array $objs
	 */
	function saveDelBatch($objs) {
		//ʵ������Ʊ��ϸ
		$esmassprooptionDao = new model_engineering_assess_esmassprooption();

		try{
			$returnObjs = array ();
			foreach ( $objs as $key => $val ) {
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;

				//ѡ�����ݻ�ȡ
				$options = $val['options'];
				unset($val['options']);

				if ((empty ( $val ['id'] ) && $isDelTag== 1)) {

				} else if (empty ( $val ['id'] )) {
					if($val['isUse'] == "0"){
						continue;
					}
					//��������
					foreach($options as $k => $v){
						$val['detail'] .= $v['optionName'] . "<span class=\"blue\">( ".$v['score'] ." )</span> ; ";
					}
					//����ָ��
					$id = $this->add_d ( $val );
					$val ['id'] = $id;
					array_push ( $returnObjs, $val );

					//��������Ʊ����
					$addArr = array(
						'detailId' =>$id
					);
					$options = util_arrayUtil::setArrayFn($addArr,$options);
					$esmassprooptionDao->saveDelBatch($options);
				} else if ($isDelTag == 1) {
					//ɾ��ָ��
					$this->deletes ( $val ['id'] );
				} else {
					if($val['isUse'] == "0"){
						//ɾ��ָ��
						$this->deletes ( $val ['id'] );
					}else{
						//��������
						foreach($options as $k => $v){
							$val['detail'] .= $v['optionName'] . "<span class=\"blue\">( ".$v['score'] ." )</span> ; ";
						}
						//�ȱ༭���ò���
						$this->edit_d ( $val );
						array_push ( $returnObjs, $val );

						//��������Ʊ����
						$addArr = array(
							'detailId' =>$val ['id']
						);
						$options = util_arrayUtil::setArrayFn($addArr,$options);
						$esmassprooptionDao->saveDelBatch($options);
					}
				}
			}
			return $returnObjs;
		}catch(exception $e){
			echo $e->getMessage();
			throw $e;
			return false;
		}
	}
}
?>