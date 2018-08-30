<?php
/*
 * Created on 2010-7-17
 * 产品类型Model
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_produce_document_documenttype extends model_treeNode {
	public $db;

	function __construct() {
		$this->tbl_name = "oa_produce_document_type";
		$this->sql_map = "produce/document/documenttypeSql.php";
		parent::__construct ();
	}

	/**
	 * 新增保存
	 * @see model_treeNode::add_d()
	 */
	public function add_d($node) {
		try {
			$this->start_d ();
			//调用树工具产生左右节点id
//			$node = $this->createNode ( $node );
			$newId = parent::add_d ( $node, true );

			$this->commit_d ();
			return $newId;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 修改编辑
	 * @see model_treeNode::edit_d()
	 */
	function edit_d($node) {
		try {
			$this->start_d ();
			//调用树工具产生左右节点id
//			$node = $this->moveNode ( $node );
			if (!isset ($node['isUse'])) { //判断是否启用
				$node['isUse'] = '0';
			}
			$editResult = parent::edit_d ( $node, true );

			//更新下级类型的parentName
			$this->update(array('parentId' => $node['id']),array('parentName' => $node['type']));

			//更新分类的物料信息
// 			$productinfoDao = new model_stock_productinfo_productinfo();
// 			$productinfoDao->update(array('typeId' => $node['id']),array('type' => $node['type']));

			$this->commit_d ();
			return $editResult;
		} catch ( Exception $e ) {
			msg ( $e->getMessage () );
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 删除产品类别的时候先删除该类别下其它产品类型信息
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$idArr = explode ( ',', $ids );
			$configDao = new model_stock_productinfo_configuration ();
			foreach ( $idArr as $parentId ) {
				$parent = array ("parentId" => $parentId );
				parent::delete ( $parent );
				//删除对应的配件模板信息
				$configDao->deleteAccessForType ( $parentId );
			}
			parent::deletes_d ( $ids );

			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 *
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
		$configurationDao = new model_stock_productinfo_configuration ();
		$configCondition = array ("hardWareId" => $id, "configType" => "typeaccess" );
		$configArr = $configurationDao->findAll ( $configCondition );
		if (is_array ( $configArr )) {
			$object ['accessItem'] = $configArr;
		}
		return $object;
	}

	/**
	 * 根据id获取产品类型的编码
	 */
	function gettypeCodeById($id) {
		return parent::get_table_fields ( $this->tbl_name, "id=" . $id, "typecode" );
	}

	/**
	 * 异步修改分类类型
	 */
	function ajaxEdit($id, $name) {
		$object = array ('id' => $id, 'type' => util_jsonUtil::iconvUTF2GB ( $name ) );
		return parent::edit_d ( $object );
	}

	/**
	 * 异步修改树关系(拖拽)
	 */
	function ajaxDrop($id, $newParentId, $newParentName, $oldParentId) {
		$object = array ('id' => $id, 'parentId' => util_jsonUtil::iconvUTF2GB ( $newParentId ), 'parentName' => util_jsonUtil::iconvUTF2GB ( $newParentName ) );
		$newParentObject = array ('id' => util_jsonUtil::iconvUTF2GB ( $newParentId ), 'leaf' => '0' );
		try {
			$this->start_d ();
			parent::edit_d ( $object );
			parent::edit_d ( $newParentObject );
			if (! $this->isParent ( $oldParentId )) {
				parent::edit_d ( array ('id' => $oldParentId, 'leaf' => '1' ) );
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 验证是否还有其他子节点
	 */
	function isParent($parentId) {
		return $this->find ( array ('parentId' => $parentId ), null, 'id' );
	}

	/**
	 * 异步添加节点
	 */
	function ajaxAdd($object) {
		$newParentObject = array ('id' => util_jsonUtil::iconvUTF2GB ( $object ['parentId'] ), 'leaf' => '0' );
		try {
			$this->start_d ();
			$newId = parent::add_d ( $object );
			parent::edit_d ( $newParentObject );
			$this->commit_d ();
			return $newId;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

	/**
	 * 异步删除节点
	 */
	function ajaxDelete($id, $parentId) {
		try {
			$this->start_d ();
			$this->deletes ( $id );
			if (! $this->isParent ( $parentId )) {
				parent::edit_d ( array ('id' => $parentId, 'leaf' => '1' ) );
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

    private $childrenArr = array();

    /**
     * 查询 字节点
     * @param $ids
     * @return array
     */
    function getChildrenNodes($ids){
        $this->searchChildNodes($ids);
        return $this->childrenArr;
    }

    /**
     * 查询下级节点
     * @param $ids
     * @return bool
     */
    function searchChildNodes($ids){
        $this->searchArr['parentIdArr'] = $ids;
        $rows = $this->list_d();
        if($rows){
            $nextSearch = array(); // 下一个循环查找内容
            foreach($rows as $v){
                array_push($this->childrenArr,$v['id']);
                array_push($nextSearch,$v['id']);
            }
            $this->searchChildNodes(implode(',',$nextSearch));
        }else{
            return false;
        }
    }
}