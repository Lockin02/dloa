<?php
/**
 * @author Show
 * @Date 2013年12月10日 星期二 17:12:50
 * @version 1.0
 * @description:物料协议价明细表 Model层
 */
 class model_purchase_material_materialequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purchase_material_equ";
		$this->sql_map = "purchase/material/materialequSql.php";
		parent::__construct ();
	}

	/*
	 * 根据物料id和数量获取对应价格
	 */
	function getPrice($productId ,$amonut) {
		$this->searchArr['productId'] = $productId;
		$this->searchArr['isEffective'] = 'on';
	 	$this->sort='';
	 	$nowDate = date("Y-m-d");
	 	$this->searchArr['isValidDate'] = $nowDate;
		$obj = $this->listBySqlId('select_default');

		if (!$obj) {
			return '0';
		}

    	foreach($obj as $k => $v) {
    		if(($v['lowerNum'] <= $amonut && $v['ceilingNum'] >= $amonut)
    			|| ($v['lowerNum'] == 0 && $v['ceilingNum'] == 0)) {
    				return $v;
    		}
    	}
    	//如果没有符合的条件价格就取最接近下限数量对应价格
    	//但数量比最大区间的下限数量小
    	$a = $amonut;         //最接近下限数量对应价格
    	$b = 0;               //符合条件的下标
    	$c = 99999;           //判断下限数量跟对应数量的差距
    	$tmp = 0;             //中间变量，做为转接用
    	$judge = 0;           //判断是哪个区间合适
    	foreach($obj as $k => $v) {
    		if($v['lowerNum'] > $a) {
				$tmp = $v['lowerNum'] - $a;
				if ($tmp < $c) {
					$b = $k;
					$c = $tmp;
					$judge = 1;
				}
    		}
    	};
    	if ($judge == 1) {
    		return $obj[$b];
    	}

    	//但数量比最大区间的上限数量大
    	$a = $obj[0]['ceilingNum'];
    	$b = 0;
    	foreach($obj as $k => $v) {
    		if($v['ceilingNum'] > $a) {
				$b = $k;
				$a = $v['ceilingNum'];
    		}
    	}
    	return $obj[$b];
	}

 }
?>