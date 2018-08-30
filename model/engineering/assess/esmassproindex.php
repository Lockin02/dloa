<?php
/**
 * @author Show
 * @Date 2012年12月10日 星期一 14:20:05
 * @version 1.0
 * @description:项目指标明细 Model层
 */
class model_engineering_assess_esmassproindex extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_project_assindex";
		$this->sql_map = "engineering/assess/esmassproindexSql.php";
		parent :: __construct();
	}

	/**
	 * 根据传入的对象数组自动进行新增，修改，删除(主要用于解决主从表中对从表对象的批量操作)
	 * 判断规则：
	 * 1.如果id为空且isDelTag属性为1（这种情况属于如界面上添加后删除情况,后台啥都不做）
	 * 2.如果id为空，则新增
	 * 3.如果isDelTag属性为1，则删除
	 * 4.否则修改
	 * @param Array $objs
	 */
	function saveDelBatch($objs) {
		//实例化发票明细
		$esmassprooptionDao = new model_engineering_assess_esmassprooption();

		try{
			$returnObjs = array ();
			foreach ( $objs as $key => $val ) {
				$isDelTag=isset($val ['isDelTag'])?$val ['isDelTag']:NULL;

				//选项数据获取
				$options = $val['options'];
				unset($val['options']);

				if ((empty ( $val ['id'] ) && $isDelTag== 1)) {

				} else if (empty ( $val ['id'] )) {
					if($val['isUse'] == "0"){
						continue;
					}
					//缓存数组
					foreach($options as $k => $v){
						$val['detail'] .= $v['optionName'] . "<span class=\"blue\">( ".$v['score'] ." )</span> ; ";
					}
					//新增指标
					$id = $this->add_d ( $val );
					$val ['id'] = $id;
					array_push ( $returnObjs, $val );

					//再新增发票部分
					$addArr = array(
						'detailId' =>$id
					);
					$options = util_arrayUtil::setArrayFn($addArr,$options);
					$esmassprooptionDao->saveDelBatch($options);
				} else if ($isDelTag == 1) {
					//删除指标
					$this->deletes ( $val ['id'] );
				} else {
					if($val['isUse'] == "0"){
						//删除指标
						$this->deletes ( $val ['id'] );
					}else{
						//缓存数组
						foreach($options as $k => $v){
							$val['detail'] .= $v['optionName'] . "<span class=\"blue\">( ".$v['score'] ." )</span> ; ";
						}
						//先编辑费用部分
						$this->edit_d ( $val );
						array_push ( $returnObjs, $val );

						//再新增发票部分
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