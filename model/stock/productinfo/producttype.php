<?php

/*
 * Created on 2010-7-17
 * 产品类型Model
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_stock_productinfo_producttype extends model_treeNode {
	public $db;

	function __construct() {
		$this->tbl_name = "oa_stock_product_type";
		$this->sql_map = "stock/productinfo/producttypeSql.php";
		parent::__construct ();
	}

	/**
	 * 查看页面显示硬件的配置信息模板
	 */
	function showItemAtView($rows) {
		$accessStr = ""; //配件信息字符串
		$j = 0;
		$accessSeNum = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center">
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							$val[configCode]
						</td>
						<td>
							$val[configName]
						</td>
						<td>
							$val[configPattern]
						</td>
						<td>
							$val[configNum]
						</td>
						<td>
							$val[explains]
						</td>
				 	</tr>
EOT;
				$j ++;
			}
		}
		return $accessStr;
	}

	/**
	 * 修改页面显示硬件的配置信息模板
	 */
	function showItemAtEdit($rows,$type) {
		$accessStr = ""; //配件信息字符串
		$j = 0;
		$accessSeNum = 0;
		$normalClass = 'txt';
		$shortClass = 'txtshort';
		$readOnly = '';
		if($type == '4'){//部分更新的时候，不允许修改物料信息
			$normalClass = 'readOnlyTxtNormal';
			$shortClass = 'readOnlyTxtShort';
			$readOnly = 'readonly';
		}
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center" class="itembody">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="删除行" />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="$shortClass"  id="aConfigCode$j" value="$val[configCode]" name="producttype[accessItem][$j][configCode]" $readOnly/>
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="producttype[accessItem][$j][configId]" />
						</td>
						<td>
							<input type="text" class="$normalClass" id="aConfigName$j" value="$val[configName]" name="producttype[accessItem][$j][configName]" $readOnly/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readonly id="aConfigPattern$j" value="$val[configPattern]" name="producttype[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="$shortClass" id="configNum$j" value="$val[configNum]" name="producttype[accessItem][$j][configNum]" $readOnly/>
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="producttype[accessItem][$j][explains]" />
							<input type="hidden" value="$val[id]" name="producttype[accessItem][$j][id]" />
						</td>
				 	</tr>
EOT;
				$j ++;
			}
		}
		return $accessStr;
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
			$configurationDao = new model_stock_productinfo_configuration ();
			//新增配置模板信息
			if (is_array ( $node ['accessItem'] )) {
				$configItemArr = util_arrayUtil::setItemMainId ( "hardWareId", $newId, $node ['accessItem'] );
				$configItemObj = $configurationDao->saveDelBatch ( $configItemArr );
			}
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
			if (! isset ( $productinfo ['esmCanUse'] )) { //判断是否为工程可用
				$productinfo ['esmCanUse'] = 0;
			}
			$editResult = parent::edit_d ( $node, true );
			$configurationDao = new model_stock_productinfo_configuration ();

			//更新下级类型的parentName
			$this->update(array('parentId' => $node['id']),array('parentName' => $node['proType']));

			//更新分类的物料信息
			$productinfoDao = new model_stock_productinfo_productinfo();
			$productinfoDao->update(array('proTypeId' => $node['id']),array('proType' => $node['proType']));

			//配置模板信息
			if (is_array ( $node ['accessItem'] )) {
				$configItemArr = util_arrayUtil::setItemMainId ( "hardWareId", $node ['id'], $node ['accessItem'] );
				$mainConfigArr = $configurationDao->findMianArr ( $node ['id'] );
				$configItemObjs = $configurationDao->saveDelBatch ( $configItemArr );

				/*s:---给对应类型的物料信息增加配件清单---*/
				$productinfoDao = new model_stock_productinfo_productinfo ();
				$configurationDao = new model_stock_productinfo_configuration ();
				$productArr = $productinfoDao->findNotAccessPro ( $node ['id'] );
				if (is_array ( $productArr ) && is_array ( $configItemObjs ) && $node ['type'] == "1") {
					foreach ( $productArr as $key => $proObj ) {
						$configArr = array ();
						foreach ( $configItemObjs as $configObj ) {
							$configObj ['hardWareId'] = $proObj ['id'];
							$configObj ['configType'] = "proaccess";
							unset ( $configObj ['id'] );
// 							unset ( $configObj ['createId'] );
// 							unset ( $configObj ['createName'] );
// 							unset ( $configObj ['createTime'] );
// 							unset ( $configObj ['updateId'] );
// 							unset ( $configObj ['updateName'] );
// 							unset ( $configObj ['updateTime'] );
							array_push ( $configArr, $configObj );
						}
						$configurationDao->addBatch_d ( $configArr );
					}
				}

				if ($node ['type'] == "2") { //进行配件覆盖
					$productAcessArr = $productinfoDao->findArrPro ( $node ['id'] );
					if (is_array ( $productAcessArr ) && is_array ( $configItemObjs )) {
						foreach ( $productAcessArr as $key => $proAccessObj ) {
							$configItem = $configurationDao->findArrByPro ( $proAccessObj ['id'] );
							$configArr = array ();
							foreach ( $configItem as $k => $v ){
								foreach ( $configItemObjs as $configObj ) {
									if($configObj['configCode'] == $v['configCode']){
										$configObj ['hardWareId'] = $proAccessObj ['id'];
										$configObj ['configType'] = "proaccess";
										unset ( $configObj ['id'] );
										array_push ( $configArr, $configObj );
										$configurationDao->delete ( array ("configCode" => $v ['configCode'],"hardWareId"=>$v['hardWareId'], "configType" => "proaccess" ) );
									}
								}
							}
							$configurationDao->addBatch_d ( $configArr );
						}
					}
				}else if($node ['type'] == "3"){
					$productAcessArr = $productinfoDao->findArrPro ( $node ['id'] );
					if (is_array ( $productAcessArr ) && is_array ( $configItemObjs )) {
						foreach ( $productAcessArr as $key => $proAccessObj ) {
							$configItem = $configurationDao->findArrByPro ( $proAccessObj ['id'] );
							$configArr = array ();
							foreach ( $configItem as $k => $v ){
								foreach ( $configItemObjs as $x => $configObj ) {
									if($mainConfigArr[$x]['configCode'] == $v['configCode']){
										$configObj ['hardWareId'] = $proAccessObj ['id'];
										$configObj ['configType'] = "proaccess";
										unset ( $configObj ['id'] );
										array_push ( $configArr, $configObj );
										$configurationDao->delete ( array ("configCode" => $v ['configCode'],"hardWareId"=>$v['hardWareId'], "configType" => "proaccess" ) );
									}
								}
							}
							$configurationDao->addBatch_d ( $configArr );
						}
					}
				}else if($node ['type'] == "4"){
					$productAcessArr = $productinfoDao->findAll(array('proTypeId' => $node ['id']));
					if (is_array ( $productAcessArr ) && is_array ( $configItemArr )) {
						//新增物料
						$addConfigArr = array();
						foreach($configItemArr as $k => $v){
							if(empty($v['id'])){
								$v ['configType'] = "proaccess";
								array_push ( $addConfigArr, $v );
								unset($configItemArr['$k']);
							}
						}
						foreach ( $productAcessArr as $key => $proAccessObj ) {
							$configItem = $configurationDao->findArrByPro ( $proAccessObj ['id'] );
							$configArr = array ();
							foreach ( $configItem as $k => $v ){
								foreach ( $configItemArr as $x => $configObj ) {
									if($configObj['isDelTag'] == 1){
										$configurationDao->delete ( array ("configCode" => $configObj ['configCode'],"hardWareId"=>$v['hardWareId'], "configType" => "proaccess" ) );
									}else if($mainConfigArr[$x]['configCode'] == $v['configCode'] && $mainConfigArr[$x]['configNum'] == $v['configNum']){
										$configObj ['hardWareId'] = $proAccessObj ['id'];
										$configObj ['configType'] = "proaccess";
										unset ( $configObj ['id'] );
										array_push ( $configArr, $configObj );
										$configurationDao->delete ( array ("configCode" => $v ['configCode'],"hardWareId"=>$v['hardWareId'], "configType" => "proaccess" ) );
									}
								}
							}
							//合并新增数组
							if(!empty($addConfigArr)){
								$addConfigArr = util_arrayUtil::setItemMainId ( "hardWareId", $proAccessObj ['id'], $addConfigArr );
								$configArr = array_merge($configArr,$addConfigArr);
							}
							$configurationDao->addBatch_d ( $configArr );
						}
					}
				}else{
					$productAcessArr = $productinfoDao->findArrPro ( $node ['id'] );
					if (is_array ( $productAcessArr ) && is_array ( $configItemObjs )) {
						foreach ( $productAcessArr as $key => $proAccessObj ) {
							$configArr = array ();
							$configurationDao->delete ( array ("hardWareId" => $proAccessObj ['id'], "configType" => "proaccess" ) );

							foreach ( $configItemObjs as $configObj ) {
								$configObj ['hardWareId'] = $proAccessObj ['id'];
								$configObj ['configType'] = "proaccess";
								unset ( $configObj ['id'] );
								array_push ( $configArr, $configObj );
							}
							$configurationDao->addBatch_d ( $configArr );
						}
					}
				}

			/*e:---给对应类型的物料信息增加配件清单---*/
			}
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
	function getProTypeCodeById($id) {
		return parent::get_table_fields ( $this->tbl_name, "id=" . $id, "typecode" );
	}

	/**
	 * 异步修改分类类型
	 */
	function ajaxEdit($id, $name) {
		$object = array ('id' => $id, 'proType' => util_jsonUtil::iconvUTF2GB ( $name ) );
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

	/**
	 *
	 * 根据产品类型层次查找产品类型信息,没有的就新增
	 */
	function getByTypeLevel($proType1, $proType2 = FALSE, $proType3 = FALSE, $proType4 = FALSE) {
		$this->searchArr = NULL;
		$this->searchArr ['nproType'] = $proType1;
		$this->searchArr ['parentId'] = "-1";
		$searchData = array ();
		$searchData = $this->list_d ();
		$parentId = "-1";
		$parentName = "物料分类";

		$propertiesArr = array ("外购商品" => "WLSXWG", "原材料" => "WLSXWG", "包装物" => "WLSXWG", "成品" => "WLSXZZ", "半成品" => "WLSXZZ" );

		//第一层产品类型处理
		if (is_array ( $searchData )) {
			$parentId = $searchData [0] ['id'];
			$parentName = $searchData [0] ['proType'];
		} else {
			$newProTypeObj = array ("proType" => $proType1, "parentName" => $parentName, "parentId" => $parentId, "accountingCode" => "KJKM1", "properties" => $propertiesArr [$proType1] );
			if ($this->add_d ( $newProTypeObj )) {
				$searchData = $this->list_d ();
			}
			$parentId = $searchData [0] ['id'];
			$parentName = $searchData [0] ['proType'];
		}
		//
		//第二层产品类型处理
		if ($proType2 && is_array ( $searchData )) {
			$this->searchArr ['nproType'] = $proType2;
			$this->searchArr ['parentId'] = $searchData [0] ['id'];
			$searchData = $this->list_d ();
			if (! is_array ( $searchData )) {
				$newProTypeObj = array ("proType" => $proType2, "parentName" => $parentName, "parentId" => $parentId, "accountingCode" => "KJKM1", "properties" => $propertiesArr [$proType1] );
				if ($this->add_d ( $newProTypeObj )) {
					$searchData = $this->list_d ();
				}
			}
			$parentId = $searchData [0] ['id'];
			$parentName = $searchData [0] ['proType'];

		}
		//第三层产品类型处理
		if ($proType3 && is_array ( $searchData )) {
			$this->searchArr ['nproType'] = $proType3;
			$this->searchArr ['parentId'] = $searchData [0] ['id'];
			$searchData = $this->list_d ();
			if (! is_array ( $searchData )) {
				$newProTypeObj = array ("proType" => $proType3, "parentName" => $parentName, "parentId" => $parentId, "accountingCode" => "KJKM1", "properties" => $propertiesArr [$proType1] );
				if ($this->add_d ( $newProTypeObj )) {
					$searchData = $this->list_d ();
				}
			}
			$parentId = $searchData [0] ['id'];
			$parentName = $searchData [0] ['proType'];

		}
		//第四层产品类型处理
		if ($proType4 && is_array ( $searchData )) {
			$this->searchArr ['nproType'] = $proType4;
			$this->searchArr ['parentId'] = $searchData [0] [id];
			$searchData = $this->list_d ();
			if (! is_array ( $searchData )) {
				$newProTypeObj = array ("proType" => $proType4, "parentName" => $parentName, "parentId" => $parentId, "accountingCode" => "KJKM1", "properties" => $propertiesArr [$proType1] );
				if ($this->add_d ( $newProTypeObj )) {
					$searchData = $this->list_d ();
				}
			}
			$parentId = $searchData [0] ['id'];
			$parentName = $searchData [0] ['proType'];

		}
		//返回最终结果
		if (is_array ( $searchData )) {
			return $searchData [0];
		} else {
			return null;
		}

	}
	/**
	 * 查找物料下拉列表需要过滤的数据
	 * by Liub 2011年8月30日10:46:27
	 */
	function productArr_d($cont) {
		$this->searchArr = array ("proTypeArr" => $cont, "parentId" => "-1" );
		$proTypeArr = $this->listBySqlId ();

		$this->searchArr = array ("dlft" => $proTypeArr [0] ['lft'], "xrgt" => $proTypeArr [0] ['rgt'] );
		$proTypeAllArr1 = $this->listBySqlId ();
		$this->searchArr = array ("dlft" => $proTypeArr [1] ['lft'], "xrgt" => $proTypeArr [1] ['rgt'] );
		$proTypeAllArr2 = $this->listBySqlId ();
		$this->searchArr = array ("dlft" => $proTypeArr [2] ['lft'], "xrgt" => $proTypeArr [2] ['rgt'] );
		$proTypeAllArr3 = $this->listBySqlId ();
		$proTypeAllArr = array_merge ( $proTypeAllArr1, $proTypeAllArr2, $proTypeAllArr3 );
		return $proTypeAllArr;
	}


	/****************************** S 工程使用部分 **************************************/

	/**
	 * 工程启用
	 */
	function openEsmCanUse_d($ids,$esmCanUse = 1){
		$sql = "update ".$this->tbl_name . " set esmCanUse = $esmCanUse where id in ($ids)";
		return $this->_db->query($sql);
	}

	/**
	 * 工程启用
	 * 返回的是id串
	 */
	function getParentIds_d($id,$type = 'ids'){
		//先去自身左右值
		$rs = $this->find(array('id' => $id ),null,'lft,rgt');
		//查询上级id
		$sql = "select id from ".$this->tbl_name. " where id <> -1 and lft <= ".$rs['lft'] . " and rgt >= ". $rs['rgt'] ;
		$idArr = $this->_db->getArray($sql);
		if(!is_array($idArr)){
			return null;
		}else{
			if($type == 'ids'){
				//新id数组
				$newIdArr = array();
				foreach($idArr as $key => $val){
					if(!in_array($val['id'],$newIdArr)){
						array_push($newIdArr,$val['id']);
					}
				}
				return implode($newIdArr,',');
			}else {
				return $idArr;
			}
		}
	}

	/**
	 * 获取子节点 - 只一级
	 */
	function getChildren($parentId,$type = 'ids'){
		//查询上级id
		$sql = "select id from ".$this->tbl_name. " where esmCanUse = 1 and id <> -1 and parentId = ".$parentId ;
		$idArr = $this->_db->getArray($sql);
		if(!is_array($idArr)){
			return null;
		}else{
			if($type == 'ids'){
				//新id数组
				$newIdArr = array();
				foreach($idArr as $key => $val){
					if(!in_array($val['id'],$newIdArr)){
						array_push($newIdArr,$val['id']);
					}
				}
				return implode($newIdArr,',');
			}else {
				return $idArr;
			}
		}
	}

	/**
	 * 递归关闭物料类型 - 传入类型id和物料信息实例对象
	 */
	function closeProType_d($id,$productinfoDao){
//		echo $id . '---';
		//如果是根节点，直接返回
		if($id == -1){
			return true;
		}

		try{
			//获取本物料类型下的物料信息
			$productArr = $productinfoDao->find(array('proTypeId' => $id,'esmCanUse' => 1),null,'id');
			//如果有对应物料，返回true
			if(is_array($productArr)){
				return true;
			}else{//没有物料，先更新本节点状态，然后继续调用上一层
				//更新本层节点为工程不启用
				$this->update(array('id' => $id),array('esmCanUse'=> 0));

				//获取上级id
				$rs = $this->find(array('id'=>$id),null,'parentId');

				//继续调用本方法
				if($rs['parentId'] != -1){
					//获取统计工程启用的id
					$childrens = $this->getChildren($rs['parentId']);
//					print_r($childrens);
					if($childrens){
						return true;
					}else{
						//更新本
						$this->closeProType_d($rs['parentId'],$productinfoDao);
					}
				}else{
					return true;
				}
			}
		}catch(exception $e){
			throw $e;
		}
	}
	/****************************** E 工程使用部分 **************************************/

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