<?php
/**
 * @author Show
 * @Date 2013��12��10�� ���ڶ� 17:12:50
 * @version 1.0
 * @description:����Э�����ϸ�� Model��
 */
 class model_purchase_material_materialequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_purchase_material_equ";
		$this->sql_map = "purchase/material/materialequSql.php";
		parent::__construct ();
	}

	/*
	 * ��������id��������ȡ��Ӧ�۸�
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
    	//���û�з��ϵ������۸��ȡ��ӽ�����������Ӧ�۸�
    	//������������������������С
    	$a = $amonut;         //��ӽ�����������Ӧ�۸�
    	$b = 0;               //�����������±�
    	$c = 99999;           //�ж�������������Ӧ�����Ĳ��
    	$tmp = 0;             //�м��������Ϊת����
    	$judge = 0;           //�ж����ĸ��������
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

    	//��������������������������
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