<?php

/**
 *
 * 评估方案model
 * @author fengxw
 *
 */

class model_supplierManage_scheme_scheme extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_scheme";
		$this->sql_map = "supplierManage/scheme/schemeSql.php";
		parent :: __construct();
	}

    //公司权限处理 TODO
    protected $_isSetCompany = 0; # 单据是否要区分公司,1为区分,0为不区分

	/**
	 * 新建保存评估方案及明细单
	 */
	function add_d($object){
		try{
			$this->start_d();
			if(!is_array($object['schemeItem'])){
				msg ( '请填写好评估方案明细单的信息！' );
				throw new Exception('评估方案信息不完整，保存失败！');
			}
//			$codeDao=new model_common_codeRule();//生成业务编号
//			$object['formCode']=$codeDao->purchApplyCode("oa_purch_plan_basic","asset");
			$id=parent::add_d($object,true);
			//保存明细单
			$schemeItemDao=new model_supplierManage_scheme_schemeItem();
				foreach($object['schemeItem'] as $val){
					$val['parentId']=$id;
					$val['schemeCode']=$object['schemeCode'];
					$val['schemeName']=$object['schemeName'];
					$schemeItemDao->add_d($val);
				}
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['schemeItem'] )) {
				$id = parent::edit_d ( $object, true );
				$schemeItemDao=new model_supplierManage_scheme_schemeItem();
				$mainArr=array("parentId"=>$object ['id']);
				$itemsArr=util_arrayUtil::setArrayFn($mainArr,$object ['schemeItem']);
				$itemsObj = $schemeItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

    /**
     * 根据部门Id获取配置部门负责人
     * @param $deptId
     */
    function getDeptLeader($deptId){
        $detptManArr=unserialize(assesManArr);
         $returnRow=array();
        if(is_array($detptManArr)&&!empty($detptManArr)){
            foreach ($detptManArr as $key=>$val){
                if($key==$deptId){
                    $returnRow=$val;
                    break;
                }
            }
        }
        return $returnRow;
    }


}


?>
