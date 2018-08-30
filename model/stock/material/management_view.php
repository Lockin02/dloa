<?php
require_once 'management_tools.php';
class model_stock_material_management_view extends model_stock_material_management_tools {
	private $table_stock_inventory_into;
    private $table_product_info;
    private $table_bom_detail;
    private $table_bom_config;
    private $table_bom_config_detail;
    private $table_bom_relevance;
    private $table_bom_relevance_bom;
    private $table_bom_detail_bom;
    private $table_bom_config_bom;
    private $table_bom_type;
    private $table_bom_detial_send;
    private $table_bom_inventory;

    function __construct() {
        $this->table_stock_inventory_into = 'oa_stock_inventory_info';
        $this->table_product_info = 'oa_stock_product_info';
        $this->table_bom_detail = 'oa_stock_product_bom_detail';
        $this->table_bom_config = 'oa_stock_product_bom_config';
        $this->table_bom_config_detail = 'oa_stock_product_bom_config_detail';
        $this->table_bom_relevance = 'oa_stock_product_bom_relevance';
        $this->table_bom_relevance_bom = 'oa_stock_product_bom_relevance_bom';
        $this->table_bom_detail_bom = 'oa_stock_product_bom_detail_bom';
        $this->table_bom_config_bom = 'oa_stock_product_bom_config_bom';
        $this->table_bom_type = 'oa_stock_product_type';
        $this->table_bom_detial_send = 'oa_stock_product_bom_detail_send';
        $this->table_bom_inventory = 'oa_stock_product_inventory';
        parent::__construct();
    }

    /* 获取产品名称 */
    public function getProductName($productId) {
        return $rs = $this->get_table_fields($this->table_bom_type, "id={$productId}", "proType");
    }

    /* 获取多产品配置名称 */
    public function getSendListConfigName($configId) {
    	return $this->get_table_fields($this->table_bom_detial_send, "id={$configId}", "stock_list_name");
    }

    /* 判断多产品配置是否已经存在 */
    public function existsSendListConfig($configName) {
    	return $this->get_table_fields($this->table_bom_detial_send, "`stock_list_name` = '{$configName}'", "id");
    }

    /* 获取某个sheet */
    public function getRelevanceName($rid) {
        return $rs = $this->get_table_fields($this->table_bom_relevance_bom, "id={$rid}", "sheet_name");
    }

    /* 获取某个产品的所有sheet */
    public function getRelevanceByProductId($productId) {
        $query = $this->getTableFiledByConditions($this->table_bom_relevance, '', "product_id = {$productId}", 'position', 'ASC', '');
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[$rs['id']] = $rs['sheet_name'];
        }
        return $datas;
    }

    /* 产品信息  */
    function getProductDetail($productId) {
        $query = $this->getTableFiledByConditions($this->table_product_type, '', "id = '{$productId}'");
        $rs = $this->fetch_array($query);
        return $rs;
    }

    //	public function getDetailList($productId){
    //		$query = $this->getTableFiledByConditions($this->table_bom_detail, "", "product_id = {$productId} AND type='detail' ", "", "", "");
    //		$datas = array();
    //		while ( ($rs = $this->fetch_array ( $query )) != false ) {
    //			$datas[] = $rs;
    //		}
    //		return $datas;
    //	}

    /**
     *
     * 获取cofig表中的cofig配置
     * @param unknown_type $id
     * @param unknown_type $list
     */
    public function getConfig($id) {
        $query = $this->getTableFiledByConditions($this->table_bom_config, "", "product_id = {$id}", "", "", "");
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[] = $rs;
        }
        return $datas;
    }

    //	public function getConfigDetail($configId){
    //		$query = $this->getTableFiledByConditions($this->table_bom_detail, "", "product_id = {$configId} AND type='config'", "", "", "");
    //		$datas = array();
    //		while ( ($rs = $this->fetch_array ( $query )) != false ) {
    //			$datas[] = $rs;
    //		}
    //		return $datas;
    //	}

    public function getConfigTitle($productId) {
        $query = $this->getTableFiledByConditions($this->table_bom_config, "", "product_id = {$productId}", "", "", "");
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[] = $rs;
        }
        return $datas;
    }

    /* 依据类型获取物料详细 */
    public function getSubDetail($tag, $type) {
        $query = $this->getTableFiledByConditions($this->table_bom_detail, "", "product_id = {$tag} AND type = '{$type}'", "", "", "");
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[] = $rs;
        }

        return $datas;
    }

    /* 通过id获取发料信息  */
    public function getStockDetail($id) {
        $query = $this->getTableFiledByConditions("{$this->table_bom_detail_bom} as `detail`", '`detail`.*, `info`.`actNum`', "`detail`.`id` = {$id}", '', '', '1', '', "`{$this->table_stock_inventory_into}` AS `info` ON `detail`.`stock_code` = `info`.`productCode`");

        return $this->fetch_array($query);
    }

    //获取配置详细表的数据
    public function getDetailInfo($pid = false, $conditions = false) {
        $condition = '';
        if ($pid) {
            $condition = "pid={$pid}";
        }

        if ($conditions) {
            $condition = $condition ? $condition . ' AND ' . $conditions : $conditions;
        }

        $query = $this->getTableFiledByConditions("`{$this->table_bom_detail_bom}` AS `bom`", "`bom`.*, `info`.`actNum`, `info`.`safeNum`", $condition, '', '', '', '', "`{$this->table_stock_inventory_into}` AS `info` ON `bom`.`stock_code` = `info`.`productCode`");

        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[] = $rs;
        }
        return $datas;
    }

    public function getDetailInfoByRid($rid, $mNum, $ext = 'all') {
        $condition = "product_id={$rid}";
        $groupBy = "`bom`.`stock_code`";
        $fields = "`bom`.*, `info`.`actNum`, `info`.`safeNum`, `info`.`planInstockNum`, `productinfo`.`unitName`";
        $join = "`oa_stock_inventory_info` AS `info` ON `bom`.`stock_code` = `info`.`productCode` LEFT JOIN `oa_stock_product_info` AS `productinfo` ON `bom`.`stock_code` = `productinfo`.`productCode`";
        $query = $this->getTableFiledByConditions("`{$this->table_bom_detail_bom}` AS `bom`", $fields, $condition, "", "", "", $groupBy, $join);
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $rs['stock_total'] = $rs['stock_total'] * $mNum;
            $c = $rs['stock_total'] - $rs['actNum'];
            $rs['shortage'] = $c > 0 ? $c : 0;

            /* 筛选清单 */
            if ($ext == 'all') {
                $datas[] = $rs;
            } elseif ($ext == 'shortage' && $c > 0) {
                $datas[] = $rs;
            }

        }
        return $datas;
    }

    public function getIssueDetailInfoByRid($rid, $mNum, $ext = 'all') {
        $condition = "product_id={$rid}";
        $groupBy = "stock_code";
        $fields = "{$this->table_bom_detail_bom}.id,stock_code,stock_name,stock_model,stock_packaging,stock_serial_number,stock_factory,stock_total,sunhao,outStockNum,realOutNum,mustOutNum,count(*) as num,info.actNum,info.safeNum,info.planInstockNum";
        $join = "oa_stock_inventory_info as info on " . $this->table_bom_detail_bom . ".stock_code=info.productCode";
        $query = $this->getTableFiledByConditions($this->table_bom_detail_bom, $fields, $condition, "", "", "", $groupBy, $join);
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $rs['stock_total'] = $rs['stock_total'] * $mNum;
            //$c= $rs['stock_total']*$rs['num']-$rs['actNum'];
            $c = $rs['stock_total'] - $rs['actNum'];
            if ($c > 0)
                $rs['storeIssue'] = $rs['stock_total'] - $c;
            else
                $rs['storeIssue'] = $rs['stock_total'];
            $SR = $rs['storeIssue'] - $rs['stock_total'];
            $rs['shouldReturn'] = ($SR < 0) ? 0 : $SR;
            $rs['shortage'] = $c > 0 ? $c : 0;

            /* 筛选清单 */
            if ($ext == 'all' && $rs['actNum']) {
                $datas[] = $rs;
            } elseif ($ext == 'shortage' && $rs['actNum']) {
                if ($c > 0) {
                    $datas[] = $rs;
                }
            }
        }
        return $datas;
    }

    public function updateRepertory($rid) {
        $condition = "product_id={$rid}";
        $stockCode = $this->query("select * from {$this->table_bom_detail_bom} where {$condition}");

        print_r($stockCode);
        //		$groupBy = "stock_code";
        //		$fields = "{$this->table_bom_detail_bom}.id,stock_code,stock_name,stock_model,stock_packaging,stock_serial_number,stock_factory,stock_total,sunhao,outStockNum,realOutNum,mustOutNum,count(*) as num,info.actNum,info.safeNum,info.planInstockNum";
        //		$join = "oa_stock_inventory_info as info on ".$this->table_bom_detail_bom.".stock_code=info.productCode";
        //		$query = $this->getTableFiledByConditions($this->table_bom_detail_bom, $fields, $condition, "", "", "", $groupBy, $join);
        //		$datas = array();
        //		while(($rs = $this->fetch_array( $query )) != false){
        //			$rs['stock_total'] = $rs['stock_total']*$mNum;
        //			//$c= $rs['stock_total']*$rs['num']-$rs['actNum'];
        //			$c= $rs['stock_total']-$rs['actNum'];
        //			if ($c>0) $rs['storeIssue'] = $rs['stock_total']-$c; else $rs['storeIssue'] = $rs['stock_total'];
        //			$SR = $rs['storeIssue'] - $rs['stock_total'];
        //			$rs['shouldReturn'] = ($SR<0)?0:$SR;
        //			$rs['shortage'] = $c > 0 ? $c : 0;
        //
        //			/* 筛选清单 */
        //			if ($ext == 'all' && $rs[actNum]){
        //				$datas[]=$rs;
        //			}elseif ($ext == 'shortage' && $rs[actNum]){
        //				if ($c > 0){
        //					$datas[]=$rs;
        //				}
        //			}
        //
        //		}
        //		return $datas;
    }

    //获取配置表是否有pid
    function getProductId($product_id) {

        $query = $this->getTableFiledByConditions($this->table_bom_detail_bom, "pid", "pid = {$product_id}", "", "", "");

        $datas[] = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[] = $rs;
        }

        if (count($datas) > 1) {
            return $result = true;
        } else {
            return $result = false;
        }
    }

    function delBom($pid) {
        $condition = "`product_id`={$pid}";
        $rs = $this->delete($this->table_bom_relevance_bom, $condition);
        $condition = "`pid`={$pid}";
        $rs = $this->delete($this->table_bom_config_bom, $condition);
        $rs = $this->delete($this->table_bom_detail_bom, $condition . ' AND `status` = \'0\'');
    }

    //删除产品
    function deleteProduct($pid, $delete = true) {
        //删除产品表
        $SQL = "UPDATE `{$this->table_bom_type}` SET `deleted` = '1' WHERE `id` = '{$pid}' AND `parentId` != '-1'";
        $query = $this->query($SQL);

        return $query;
    }

    public function deleteMoreConfig($pid) {
    	$sql = "UPDATE `{$this->table_bom_detial_send}` SET `deleted` = '1' WHERE `id` = '{$pid}'";
    	$query = $this->query($sql);
    	return $query;
    }

    public function getBomRelevanceByProductId($id) {
        $query = $this->getTableFiledByConditions($this->table_bom_relevance_bom, '', "product_id = {$id}", 'id', 'ASC', '');
        $datas = array();
        while (($rs = $this->fetch_array($query)) != false) {
            $datas[$rs['id']] = $rs['sheet_name'];
        }
        return $datas;
    }

    /* 获取发料数据树状图  */
    public function getStockSendTree() {
        $datas = array();

        //获取单个产品的列表
        $query = $this->getTableFiledByConditions($this->table_bom_detail_bom . ' AS `bom`', '`bom`.`pid` AS `id`, `product`.`proType` AS `text`', '`bom`.`status` = \'1\' AND `bom`.`sendid` = \'0\'', '', '', '', '`bom`.`pid`', $this->table_bom_type . ' AS `product` ON `bom`.`pid` = `product`.`id`');

        while ($rs = $this->fetch_array($query)) {
            $dateQuery = $this->getTableFiledByConditions($this->table_bom_detail_bom, '`create_date` AS `id`, `sendid`', '`status` = \'1\' AND `pid` = \'' . $rs['id'] . '\' AND `sendid` = \'0\'', '', '', '', '`create_date`');
            $dateResult = array();
            while ($dateRs = $this->fetch_array($dateQuery)) {
                $dateRs['text'] = $dateRs['id'];
                $dateRs['id'] = $rs['id'] . '_' . $dateRs['id'] . '_' . $dateRs['sendid'];

                $dateResult[] = $dateRs;
            }

            $rs['children'] = $dateResult;

            $datas[] = $rs;
        }

        //获取多个产品的配置列表
        $moreDatas = array();
        $query = $this->getTableFiledByConditions($this->table_bom_detail_bom . ' AS `bom`', '`bom`.`pid` AS `id`, `bom`.`sendid`, `send`.`stock_list_name` AS `text`', '`bom`.`status` = \'1\' AND `bom`.`sendid` != \'0\'', '', '', '', '`bom`.`sendid`', $this->table_bom_detial_send . ' AS `send` ON `bom`.`sendid` = `send`.`id`');
        while ($rs = $this->fetch_array($query)) {
        	if(!isset($moreDatas[$rs['text']])) {
        		$moreDatas[$rs['text']] = $rs;
        	}

        	$children = &$moreDatas[$rs['text']]['children'];

            $dateQuery = $this->getTableFiledByConditions($this->table_bom_detail_bom, '`create_date` AS `id`, `sendid`', '`status` = \'1\' AND `sendid` = \'' . $rs['sendid'] . '\'', '', '', '', '`create_date`');

            while ($dateRs = $this->fetch_array($dateQuery)) {
                $dateRs['text'] = $dateRs['id'];
                $dateRs['id'] = $rs['id'] . '_' . $dateRs['id'] . '_' . $dateRs['sendid'];

                $children[] = $dateRs;
            }
        }

        //合并2个数组
        foreach($moreDatas as $data) {
        	$datas[] = $data;
        }

        return array_values($datas);
    }

    //更新发料信息
    public function updateStock($data) {
        $sql = "UPDATE `{$this->table_bom_detail_bom}` SET
					`type` = '{$data['type']}',
					`serial_number` = '{$data['serial_number']}',
					`stock_type` = '{$data['stock_type']}',
					`stock_code` = '{$data['stock_code']}',
					`stock_name` = '{$data['stock_name']}',
					`stock_model` = '{$data['stock_model']}',
					`stock_packaging` = '{$data['stock_packaging']}',
					`stock_total` = '{$data['stock_total']}',
					`stock_serial_number` = '{$data['stock_serial_number']}',
					`stock_factory` = '{$data['stock_factory']}',
					`stock_mark` = '{$data['stock_mark']}',
					`edit_date` = '{$data['edit_date']}',
					`edit_by` = '{$data['edit_by']}',
					`product_id` = '{$data['product_id']}',
					`sunhao` = '{$data['sunhao']}',
					`realOutNum` = '{$data['realOutNum']}',
					`outStockNum` = '{$data['outStockNum']}',
					`mustOutNum` = '{$data['mustOutNum']}',
					`pid` = '{$data['pid']}',
					`status` = '{$data['status']}',
					`version` = '{$data['version']}'
				WHERE
					`id` = '{$data['id']}'
			";

        $this->query($sql);
    }

    //更新退库信息
    public function updateOutNum($datas) {
        $info = new model_stock_inventoryinfo_inventoryinfo();

        foreach ($datas as $data) {
            //查询发料详细记录
            $stock = $this->getStockDetail($data['id']);

            //改变的出库数
            $changeOutNum = (int) $data['realOutNum'] - (int) $stock['realOutNum'];

            //更改库存
            $info->updateStockNum($stock['stock_code'], $changeOutNum, 'instock');

            //更改发料记录
            $stock['realOutNum'] = $data['realOutNum'];

            $this->updateStock($stock);
        }
    }

    //根据配置单id 获取产品名
    public function getProductNameForConfigId($config_id, $create_date = false) {
    	$where = "`sendid` = '{$config_id}'";
    	if($create_date) {
    		$where .= "AND `create_date` = '{$create_date}'";
    	}

    	$sql = "SELECT `pid` FROM {$this->table_bom_detail_bom} WHERE {$where} GROUP BY `pid`";
    	$data = array();
    	$query = $this->query($sql);
    	while($row = $this->fetch_array($query)) {
    		$pid = $row['pid'];
    		$data[] = "'{$pid}'";
    	}

    	if(empty($data)) {
    		return false;
    	}

    	$ids = implode(',', $data);
    	$sql = "SELECT `id`, `proType` FROM `{$this->table_bom_type}` WHERE `id` IN ({$ids})";
    	$result = array();
    	$query = $this->query($sql);
    	while($row = $this->fetch_array($query)) {
    		$result[$row['id']] = $row['proType'];
    	}

    	return $result;
    }

    //删除库存表
    public function deleteInventory($id) {
    	$sql = "DELETE FROM `{$this->table_bom_inventory}` WHERE `stock_code` = '{$id}'";
    	$query = $this->query($sql);
    	return $query == false ? false : true;
    }

    //编辑生产库存
    public function updateInventory($data) {
    	if(!isset($data['stock_code']) || !intval($data['stock_code'])) {
    		return '参数错误';
    	}

    	if(isset($data['actNum'])) {
    		$data['actNum'] = ($actNum = intval($data['actNum'])) > 0 ? $actNum : 0;
    	}

    	$fileds = array(
    		'stock_code',
    		'stock_model',
    		'stock_name',
    		'stock_packaging',
    		'actNum'
    	);

    	$update = array();
    	foreach($fileds as $filed) {
    		if(isset($data[$filed])) {
    			$update[] = "`{$filed}` = '{$data[$filed]}'";
    		}
    	}
    	$update = implode($update, ',');

    	$sql = "UPDATE `{$this->table_bom_inventory}` SET {$update} WHERE `stock_code` = '{$data['stock_code']}'";
    	$query = $this->query($sql);

    	$result = true;
    	if(!$query) {
    		$result = '未知错误, 请联系系统管理员';
    	}

    	return $result;
    }
}