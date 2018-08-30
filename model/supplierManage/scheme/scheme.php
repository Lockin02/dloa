<?php

/**
 *
 * ��������model
 * @author fengxw
 *
 */

class model_supplierManage_scheme_scheme extends model_base {

	function __construct() {
		$this->tbl_name = "oa_supp_scheme";
		$this->sql_map = "supplierManage/scheme/schemeSql.php";
		parent :: __construct();
	}

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 0; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**
	 * �½�����������������ϸ��
	 */
	function add_d($object){
		try{
			$this->start_d();
			if(!is_array($object['schemeItem'])){
				msg ( '����д������������ϸ������Ϣ��' );
				throw new Exception('����������Ϣ������������ʧ�ܣ�');
			}
//			$codeDao=new model_common_codeRule();//����ҵ����
//			$object['formCode']=$codeDao->purchApplyCode("oa_purch_plan_basic","asset");
			$id=parent::add_d($object,true);
			//������ϸ��
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
	 * �޸ı���
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
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

    /**
     * ���ݲ���Id��ȡ���ò��Ÿ�����
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
