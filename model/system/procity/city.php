<?php

/*
 * Created on 2010-7-17
 *	���л�����ϢModel
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_system_procity_city extends model_base {
	public $db;

	function __construct() {
		$this->tbl_name = "oa_system_city_info";
		$this->sql_map = "system/procity/citySql.php";
		parent :: __construct();
	}
	/**
	 * �б�ģ��
	 */
	function showlist($rows, $showpage) {
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $rs) {
				$i++;
				$str .=<<<EOT
								<tr id="tr">
								<td><input type="checkbox" name="datacb"  value=$rs[id]  onClick="checkOne();"></td>
								<td height="25" align="center">$i</td>

                                <td align="center" >$rs[provinceCode]</td>
								<td align="center" >$rs[cityName]</td>
								<td align="center" >$rs[cityCode]</td>
								<td align="center" >
								<p>
								<a href="?model=system_procity_city&action=init&id=$rs[id]&typecode=$rs[typecode]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600" title="�޸�<$rs[provinceId]>" class="thickbox">�޸�</a>
								</p>
								</td>
								</tr>
EOT;
			}

		}else {
			$str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";}return $str . '<tr><td colspan="7" style="text-align:center;">' . $showpage->show(6) . '</td></tr>';
	}


	/*====================================================ҵ�����ݴ���=======================================*/

	/**

	 */
//	function add_d($productinfo) {
//		try {
//			$this->start_d();
//			$id = parent :: add_d($productinfo);
//			//������Ϣ
//			if (is_array($productinfo['configurations'])) {
//				$configurationDao = new model_stock_productinfo_configuration();
//				foreach ($productinfo['configurations'] as $key => $value) {
//					if (!empty ($value['configName'])) {
//						$value['hardWareId'] = $id;
//						$configurationDao->add_d($value);
//					}
//				}
//			}
//			$this->commit_d();
//			return $id;
//		} catch (Exception $e) {
//			$this->rollBack();
//			return null;
//		}
//
//	}
	/**
	 *
	 */
//	function edit_d($productinfo) {
//		echo "<pre>";
//		print_r($productinfo);
//		try {
//			$this->start_d();
//			parent :: edit_d($productinfo);
//			$configurationDao = new model_stock_productinfo_configuration();
//			//ɾ��������Ϣ
//			$configurationDao->deleteByHardWareId($productinfo['id']);
//			//����������Ϣ
//			if (is_array($productinfo['configurations'])) {
//				foreach ($productinfo['configurations'] as $key => $value) {
//					if (!empty ($value['configName'])) {
//						$value['hardWareId'] = $productinfo['id'];
//						$configurationDao->add_d($value);
//					}
//				}
//			}
//
//			$this->commit_d();
//			return $productinfo;
//		} catch (Exception $e) {
//			$this->rollBack();
//			return null;
//		}
//	}
//	/**
//	 * ��ȡ�����ҳ�б�����
//	 */
//	function page_d() {
//		//$this->echoSelect();
//		$this->searchArr=array();
//		return parent::page_d();
//
//	}


	/**
	 *
	 */
	function get_d($id) {
		$configurationDao = new model_stock_productinfo_configuration();

		$configurations = $configurationDao->getConfigByHardWareId($id);
		$productinfo = parent :: get_d($id);
		$productinfo['configurations'] = $configurations;
		return $productinfo;
	}



	/**
	 * ����id��ȡ��Ϣ
	 */
	function c_getProductInfoByTypeId($proTypeId){

	}
/*************************************************************************************************/
     /**
      * g����Code��ȡName
      */
     function getDataNameByCode ($code) {
     	return $this->dataDictArr [$code];
     }

     /**
      * ����ʡ�ݻ�ȡ����
      */
     function getCitysByProviceId($proId){
     	$this->searchArr=array("provinceId"=>$proId);
     	return $this->list_d();
     }
}
?>
