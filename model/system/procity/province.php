<?php

/**
 * Created on 2010-7-17
 * 省份Model
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_system_procity_province extends model_base
{
	public $db;

	function __construct() {
		$this->tbl_name = "oa_system_province_info";
		$this->sql_map = "system/procity/provinceSql.php";
		parent:: __construct();
	}

	/**
	 * 根据id获取编码
	 */
	function getProTypeCodeById($id) {
		return parent:: get_table_fields($this->tbl_name, "id=" . $id, "provincecode");
	}

	/**
	 * 异步修改分类类型
	 */
	function ajaxEdit($id, $name) {
		$object = array('id' => $id, 'proType' => util_jsonUtil::iconvUTF2GB($name));
		return parent::edit_d($object);
	}

	/**
	 * 异步修改树关系(拖拽)
	 */
	function ajaxDrop($id, $newParentId, $newParentName, $oldParentId) {
		$object = array('id' => $id, 'parentId' => util_jsonUtil::iconvUTF2GB($newParentId), 'parentName' => util_jsonUtil::iconvUTF2GB($newParentName));
		$newParentObject = array('id' => util_jsonUtil::iconvUTF2GB($newParentId), 'leaf' => '0');
		try {
			$this->start_d();
			parent::edit_d($object);
			parent::edit_d($newParentObject);
			if (!$this->isParent($oldParentId)) {
				parent::edit_d(array('id' => $oldParentId, 'leaf' => '1'));
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 验证是否还有其他子节点
	 */
	function isParent($parentId) {
		return $this->find(array('parentId' => $parentId), null, 'id');
	}

	/**
	 * 异步添加节点
	 */
	function ajaxAdd($object) {
		$newParentObject = array('id' => util_jsonUtil::iconvUTF2GB($object['parentId']), 'leaf' => '0');
		try {
			$this->start_d();
			$newId = parent::add_d($object);
			parent::edit_d($newParentObject);
			$this->commit_d();
			return $newId;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 异步删除节点
	 */
	function  ajaxDelete($id, $parentId) {
		try {
			$this->start_d();
			$this->deletes($id);
			if (!$this->isParent($parentId)) {
				parent::edit_d(array('id' => $parentId, 'leaf' => '1'));
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据省份名称获取编号
	 */
	function getCodeByName($name) {
		$rs = $this->find(array('provinceName' => $name), 'provinceCode');
		return $rs['provinceCode'];
	}

	/**
	 * 获取省份与城市集合
	 */
	function getProAndCity() {
		$provinces = $this->list_d();
		$provinceArr = array();
		foreach ($provinces as $pro) {
			$pro['name'] = $pro['provinceName'];
			$pro['isParent'] = 1;
			$pro['nodes'] = array();
			$provinceArr[$pro['id']] = $pro;
		}
		$cityModel = new model_system_procity_city();
		$cities = $cityModel->list_d();
		foreach ($cities as $city) {
			$city['isParent'] = 0;
			$city['name'] = $city['cityName'];
			array_push($provinceArr[$city['provinceId']]['nodes'], $city);
		}
		$rProvinces = array();
		foreach ($provinceArr as $pro) {
			array_push($rProvinces, $pro);
		}
		return $rProvinces;
	}

	/**
	 * 获取省份城市数组 - 工程用
	 * create by kuangzw
	 * create on 2012/8/8
	 */
	function getProCity() {
		$sql = "select p.id as provinceId,p.provinceName,c.id as cityId,c.cityName,concat(p.provinceName,c.cityName) as provinceCity from oa_system_province_info p inner join oa_system_city_info c on p.id = c.provinceId";
		$rs = $this->_db->getArray($sql);
		if ($rs) {
			return $rs;
		} else {
			return array();
		}
	}

	/**
	 * 获取省份信息
	 */
	function getProvinces_d($userId = null) {
		$userId = empty($userId) ? $_SESSION['USER_ID'] : $userId;
		$this->searchArr = array('esmManagerIdFind' => $userId);
		$rs = $this->list_d();
		if (is_array($rs)) {
			$provinceNameArr = array();
			foreach ($rs as $val) {
				array_push($provinceNameArr, $val['provinceName']);
			}
			return implode($provinceNameArr, ',');
		} else {
			return '';
		}
	}

	/**
	 * @author 根据负责人ID查找省份
	 *
	 */
	function getProvinceByUser_d($userId) {
		$this->searchArr = array('esmManagerIdArr' => $userId);
		$row = $this->listBySqlId();
		$provinceStr = '';
		$provinceArr = array();
		if (is_array($row)) {
			foreach ($row as $key => $val) {
				$provinceArr[$key] = $val['provinceName'];
			}
			$provinceStr = implode(',', $provinceArr);
		}
		return $provinceStr;
	}
}