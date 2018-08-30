<?php
/**
 * @author Show
 * @Date 2011年11月25日 星期五 13:59:48
 * @version 1.0
 * @description:资源目录(oa_esm_baseinfo_resource) Model层 status
                                                      0 启用
                                                      1.禁用
 */
class model_engineering_baseinfo_resource extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_baseinfo_resource";
		$this->sql_map = "engineering/baseinfo/resourceSql.php";
		parent::__construct ();
    }

    /**
     * 验证是否存在根节点，不存在则新增
     */
    function checkParent_d(){
    	$rs = $this->find(array('id'=> -1),null,'id');
		if(is_array($rs)){
			return true;
		}else{
			$this->create(array('id' => -1,'resourceCode' => 'root' , 'resourceName' => '资源目录'));
			return false;
		}
    }

    /**
     * 重写add_d
     */
    function add_d($object){
		try {
			$this->start_d();
			//设置父节点叶子属性
			if ($object['parentId'] != PARENT_ID) {
				$parent = array (
					"id" => $object['parentId'],
					"isLeaf" => 0
				);
				parent :: edit_d($parent);
			}
			//插入数据
			$newId = parent :: add_d($object);

			$this->commit_d();
			return $newId;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
    }

    /**
     * 删除时判断是否被引用
     */
     function deleteCheck_d($rowData){
     	$id = $rowData['id'];
     	$esmresourcesDao = new model_engineering_resources_esmresources();//实例化资源计划model类
     	$conditions = array('resourceId'=>$id);
     	if($esmresourcesDao->find($conditions , $sort = null, $fields = null, $limit = null)){
     		return 1;
     	}else{
     		return 0;
     	}
     }
}
?>