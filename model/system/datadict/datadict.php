<?php

/**
 * 数据字典model层类
 */
class model_system_datadict_datadict extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_system_datadict";
        $this->sql_map = "system/datadict/datadictSql.php";
        parent:: __construct();
        if (!file_exists(WEB_TOR . "cache/DATADICTARR.cache.php")) {
            $this->updateCache();
        }
        include("cache/DATADICTARR.cache.php");
        $this->dataDictArr = isset ($DATADICTARR) ? $DATADICTARR : "";

    }

    /***************************************************************************************************
     * ------------------------------以下为页面模板显示调用方法------------------------------
     *************************************************************************************************/

    /**
     * 页面显示调用方法,返回字符串给页面模板替换
     */
    function showlist($rows, $showpage) {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach ($rows as $key => $val) {
                $i++;
                $str .= <<<EOT
						<tr id="tr_$val[id]">
							<td align="center"><input type="checkbox" name="datacb"  value="$val[id]" onClick="checkOne();"></td>
							<td height="25" align="center">$i</td>
							<td align="center">$val[dataName]</td>
							<td align="center">$val[dataCode]</td>
							<td align="center">$val[parentName]</td>
							<td align="center">$val[orderNum]</td>
							<td align="center" id="m_$val[id] ">
								<a href="?model=system_datadict_datadict&action=init&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500" title="编辑<$val[dataName]>" class="thickbox">编辑</a>
							</td>
						</tr>
EOT;
            }
        }
        return $str . '<tr><td colspan="7" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';;
    }

    /***************************************************************************************************
     * ------------------------------以下为数据字典接口方法,可以为其他业务对象所调用------------------------------
     *************************************************************************************************/

    /**
     * 重写新增数据字典，新增的时候，如果有上级，需要修改上级叶子为false,并更新父节点parentCode及parentName
     */
    function add_d($object) {
        try {
            $this->start_d();
            //设置父节点叶子属性
            if ($object['parentId'] != PARENT_ID) {
                //echo $object['parentId'];
                $parent = array(
                    "id" => $object['parentId'],
                    "leaf" => 0
                );
                parent:: edit_d($parent);
            }
            //获取父节点code，并设置parentCode及parentName
            $parent = parent:: get_d($object['parentId']);
            $object['parentCode'] = $parent['dataCode'];
            $object['parentName'] = $parent['dataName'];

            $newId = parent:: add_d($object);
            $this->updateCache(); //更新缓存
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     * 重写修改数据字典，修改的时候，如果有上级，需要修改上级叶子为false,并更新父节点parentCode及parentName
     */
    function edit_d($object) {
        try {
            $this->start_d();
            //设置父节点叶子属性
            if ($object['parentId'] != PARENT_ID) {
                $parent = array(
                    "id" => $object['parentId'],
                    "leaf" => 0
                );
                parent:: edit_d($parent);
            }
            //获取父节点code，并设置parentCode及parentName
            $parent = parent:: get_d($object['parentId']);
            $object['parentCode'] = $parent['dataCode'];
            $object['parentName'] = $parent['dataName'];

            parent:: edit_d($object);

            //如果不是叶子节点，批量修改下级所有节点的parentName及parentCode
            if ($object['leaf'] == 0) {
                $children = array(
                    "parentName" => $object['dataName'],
                    "parentCode" => $object['dataCode']
                );
                $condition = array(
                    "parentId" => $object['id']
                );
                $this->update($condition, $children);
            }
            $this->updateCache(); //更新缓存
            $this->commit_d();
            return $object;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     * 批量删除对象
     */
    function deletes_d($ids) {
        try {
            $this->deletes($ids);
            $this->updateCache(); //更新缓存
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /*
     * 根据多个上级编码获取下属数据字典信息，并按照parentCode组成多维数组
     */
    function getDatadictsByParentCodes($parentCodes, $conditionArr = null) {
        $this->asc = false;
        $this->sort = 'orderNum';
        $this->searchArr = array(
            "parentCodes" => $parentCodes
        );
        if (is_array($conditionArr)) {
            foreach ($conditionArr as $key => $val) {
                $this->searchArr[$key] = $val;
            }
        }
        if ($parentCodes == 'KHLX'&&false) {
            $userId = $_SESSION['USER_ID'];
            $userDeptId = $_SESSION['DEPT_ID'];
            $this->groupBy = " c.dataName ";
            $this->searchArr['myCondition'] = "sql:
        	and  ( (  FIND_IN_SET('" . $userId . "',s.salesManIds)  and  " . $userDeptId . " in( 37,123 , 1 ) )
        	or " . $userDeptId . " not in ( 1 ) or  '" . $userId . "' in ('yingchao.zhang') )";
            $arr = $this->list_d('select_KHLX');
			
        } else {
            $arr = $this->list_d();
        }
        $newArr = array();
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                $newArr[$value['parentCode']][] = $value;
            }
        }
        return $newArr;
    }

    /**
     * 根据多个下级编码获取上级属数据字典信息，并按照上级编码组成一维数组
     * @author zengzx
     * @since 1.0 - 2012-2-8
     */
    function getDatadictsByChildCodes($codes, $conditionArr = null) {
        $this->asc = false;
        $this->sort = 'orderNum';
        $this->searchArr = array(
            "dataCodeArr" => $codes
        );
        if (is_array($conditionArr)) {
            foreach ($conditionArr as $key => $val) {
                $this->searchArr[$key] = $val;
            }
        }
        $arr = $this->list_d();
        //		print_r($arr);
        $newArr = array();
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                $newArr[$key] = $value['parentCode'];
            }
        }
        $newArr = array_flip($newArr);
        $newArr = array_flip($newArr);
        return $newArr;
    }

    /**
     * 根据数据字典code获取name
     */
    function getDataNameByCode($code, $returnType = 'code') {
        if (!empty ($code)) {
            $rsCode = $this->dataDictArr[$code];
            if (isset ($rsCode)) {
                return $this->dataDictArr[$code];
            } else {
                if ($returnType == 'code') {
                    return $code;
                } else {
                    return "";
                }
            }
        }
    }

    /**
     * 根据数据字典codes获取值,$codes以,隔开
     */
    function getDataByCodes($codes) {
        $codeArr = explode(",", $codes);
        $dataDictArr = array();
        foreach ($codeArr as $key => $value) {
            $arr = array();
            $name = $this->dataDictArr[$value];
            if (!empty ($name)) {
                $arr['name'] = $this->dataDictArr[$value];
                $arr['code'] = $value;
                $dataDictArr[] = $arr;
            }
        }
        return $dataDictArr;
    }

    /*
     * 更新数据字典缓存
     */
    function updateCache() {
        $datadictArr = $this->list_d();
        $arr = array();
        foreach ($datadictArr as $key => $value) {
            $arr[$value['dataCode']] = $value['dataName'];
        }
        util_cacheUtil:: set_cache('DATADICTARR', $arr);
    }

    /**
     * @desription 数组获取数据字典的方法
     * @param tags
     * @date 2010-10-6 上午11:56:10
     */
    function datadictArrName($objArr, $code, $codeC, $datadictCode) {
        $datadictArr = $this->getDatadictsByParentCodes($datadictCode);
        foreach ($objArr as $key => $val) {
            foreach ($datadictArr[$datadictCode] as $keyD => $valD) {
                if ($valD['dataCode'] == $objArr[$key][$code]) {
                    $objArr[$key][$codeC] = $valD['dataName'];
                }
            }
        }
        return $objArr;
    }

    /**
     * 根据名称获取第一个编码
     * @param $parentCode 上级编码
     * @param $name 名称
     */
    function getCodeByName($parentCode, $name) {
        $datadictArr = $this->getDatadictsByParentCodes($parentCode);
        $datadict = $datadictArr[$parentCode];
        foreach ($datadict as $key => $val) {
            if ($val['dataName'] == $name) {
                return $val['dataCode'];
            }
        }
        return null;
    }

    /**
     * 是否开启设备
     */
    function ajaxUseStatus_d($id, $flag) {
        try {
            $sql = "update oa_system_datadict set isUse = '" . $flag . "' where id = '" . $id . "'";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 根据名称获取第一个编码
     * @param $parentCode 上级编码
     * @param $name 名称
     */
    function getNameByCode($parentCode, $code) {
        $datadictArr = $this->getDatadictsByParentCodes($parentCode);
        $datadict = $datadictArr[$parentCode];
        foreach ($datadict as $key => $val) {
            if ($val['dataCode'] == $code) {
                return $val['dataName'];
            }
        }
        return null;
    }

    /**
     * 获取一个比较特殊的数据查询脚本
     */
    function getSpeDatadict_d($parentCode) {
        $sql = "select GROUP_CONCAT(dataCode) as dataCode,expand1 from oa_system_datadict where parentCode = '$parentCode' GROUP BY expand1; ";
        return $this->_db->getArray($sql);
    }

    /**
     * 字符串转换
     */
    function datadictShow_d($rows) {
        $str = null;
        if ($rows) {
            foreach ($rows as $val) {
                if ($val['expand1'])
                    $str .= "<option value='$val[dataCode]'>$val[expand1]</option>";
            }
        }
        return $str;
    }

    function getOAPro_d() {
        $typeSql = "SELECT id,goodsType as productType,parentName,parentId,orderNum,lft,rgt FROM oa_goods_type";
        $typeArr[0] = $this->_db->getArray($typeSql);
        $baseinfoSql = "SELECT id,goodsTypeId as productTypeId,goodsTypeName as productTypeName,goodsCode as productCode,goodsName as productName,unitName," .
            "version,useStatus,remark,createName,createId,createTime,updateTime,isEncrypt FROM oa_goods_base_info";
        $typeArr[1] = $this->_db->getArray($baseinfoSql);
        $propertiesSql = "SELECT * FROM oa_goods_properties";
        $typeArr[2] = $this->_db->getArray($propertiesSql);
        $propertiesItemSql = "SELECT * FROM oa_goods_properties_item";
        $typeArr[3] = $this->_db->getArray($propertiesItemSql);
        $assitemSql = "SELECT * FROM oa_goods_properties_assitem";
        $typeArr[4] = $this->_db->getArray($assitemSql);
        $asslistSql = "SELECT id,mainId,itemNames,itemIds,goodsId as productId FROM oa_goods_asslist";
        $typeArr[5] = $this->_db->getArray($asslistSql);
        $cacheSql = "SELECT id,goodsId as productId,goodsName as productName,fileName,filePath,goodsValue as productValue,goodsCache as productCache,goodsNewCache as productNewCache FROM oa_goods_cache";
        $typeArr[6] = $this->_db->getArray($cacheSql);
        return $typeArr;
    }

    function getOAMaterial_d() {
        $configurationSql = "SELECT * FROM oa_stock_product_configuration";
        $materialArr[0] = $this->_db->getArray($configurationSql);
        $infoSalSql = "SELECT id,proTypeId as materialTypeId,proType as materialType,productCode as materialCode,productName as materialName," .
            "pattern,priCost,unitName,aidUnit,converRate,warranty,arrivalPeriod ,accountingCode ,checkType ,properties ,supplier ,stockId ," .
            "stockName ,stockCode ,remark ,ext1 ,ext2 ,ext3 ,statType ,statTypeName ,encrypt ,allocation ,createName ,createId ,createTime ," .
            "updateName ,updateId ,updateTime ,changeProductId ,changeProductCode ,changeProductName ,closeReson ,leastPackNum ,leastOrderNum ," .
            "material ,brand ,color ,purchUserCode ,purchUserName ,purchPeriod,packageInfo,depreciation FROM oa_stock_product_info";
        $materialArr[1] = $this->_db->getArray($infoSalSql);
        $typeSqlSql = "SELECT id,proType as materialType,parentName,parentId,orderNum,lft,rgt,accountingCode,properties,submitDay,esmCanUse FROM oa_stock_product_type";
        $materialArr[2] = $this->_db->getArray($typeSqlSql);
        return $materialArr;
    }

    /**
     * @param $parentCode
     * @param null $condition
     * @param bool $reverse
     * @return array
     */
    function getDataDictList_d($parentCode, $condition = null, $reverse = false) {
        $condition['parentCode'] = $parentCode;
        $data = $this->findAll($condition, null, 'dataName,dataCode');
        $newData = array();
        foreach ($data as $v) {
            if ($reverse) {
                $newData[$v['dataName']] = $v['dataCode'];
            } else {
                $newData[$v['dataCode']] = $v['dataName'];
            }
        }
        return $newData;
    }
}