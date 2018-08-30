<?php

/**
 * Created on 2010-7-17
 *    ��Ʒ������ϢModel
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_stock_productinfo_productinfo extends model_base
{

	function __construct() {
		include(WEB_TOR . "model/common/mailConfig.php");
		$this->tbl_name = "oa_stock_product_info";
		$this->sql_map = "stock/productinfo/productinfoSql.php";
		$this->mailArr = isset($mailUser[$this->tbl_name]) ? $mailUser[$this->tbl_name] : '';
		parent::__construct();
	}

	/**
	 * ����������嵥ģ��
	 * @param unknown_type $rows
	 */
	function showAccessAtAdd($rows) {
		$accessStr = ""; //�����Ϣ�ַ���
		$j = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="ɾ����" />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="aConfigCode$j" value="$val[configCode]" name="productinfo[accessItem][$j][configCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="productinfo[accessItem][$j][configId]" />
							<input type="hidden" class="txt" id="aConfigType$j" value="proaccess" name="productinfo[accessItem][$j][configType]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[configName]" name="productinfo[accessItem][$j][configName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readOnly id="aConfigPattern$j" value="$val[configPattern]" name="productinfo[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfo[accessItem][$j][configNum]"  />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfo[accessItem][$j][explains]" />
						</td>
				 	</tr>
EOT;
				$j++;
			}
		}
		return $accessStr;
	}

	/**
	 * �鿴ҳ����ʾӲ����������Ϣģ��
	 */
	function showItemAtView($rows) {
		$configStr = ""; //������Ϣ�ַ���
		$accessStr = ""; //������Ϣ�ַ���
		$str = "";
		$i = 0; //�б��¼���
		$j = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				if ($val['configType'] == 'proconfig') {
					$configSeNum = $i + 1;
					$configStr .= <<<EOT
					<tr align="center">
                        <td>
                       		 $configSeNum
                        </td>
                        <td>
							$val[configCode]
						</td>
						<td>
							$val[configName]

						</td>
						<td>
							$val[configNum]
						</td>
						<td>
							$val[explains]
						</td>
				 	</tr>
EOT;
					$i++;
				} else {
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
					$j++;
				}
			}
		}
		if ($configStr != "") {
			$str .= <<<EOT
			<table id="itemConfigTable" class='form_main_table'>
				<tr>
					<td colspan="4" align="left" class="form_header">&nbsp; ����������Ϣ:&nbsp;</td>
				</tr>
				<tr class="main_tr_header">
					<th>���</th>
					<th>���ñ���</th>
					<th>��������</th>
					<th>����</th>
					<th>˵��</th>
				</tr>
				<tbody id="itemConfigBody">
				$configStr
				</tbody>
			</table>
EOT;
		}
		if ($accessStr != "") {
			$str .= <<<EOT
				<table id="itemAccessTable" class='form_main_table'>
					<tr>
						<td colspan="6" align="left" class="form_header">&nbsp; ��������嵥:&nbsp;</td>
					</tr>
					<tr class="main_tr_header">
						<th>���</th>
						<th>�������</th>
						<th>�������</th>
						<th>�ͺ�/�汾��</th>
						<th>����</th>
						<th>˵��</th>
					</tr>
					<tbody id="itemAccessBody">
					$accessStr
					</tbody>
			</table>
EOT;
		}
		return $str;
	}

	/**
	 * �޸�ҳ����ʾӲ����������Ϣģ��
	 */
	function showItemAtEdit($rows) {
		$configStr = ""; //������Ϣ�ַ���
		$accessStr = ""; //������Ϣ�ַ���

		$i = 0; //�б��¼���
		$j = 0;
		$accessSeNum = 0;
		$configSeNum = 0;
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				if ($val['configType'] == 'proconfig') {
					$configSeNum = $i + 1;
					$configStr .= <<<EOT
					<tr align="center">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delConfigItem(this);" title="ɾ����" />
			            </td>
                        <td>
                       		 $configSeNum
                        </td>
                        <td>
							<input type="text" class="txtshort"  id="cConfigCode$i" value="$val[configCode]" name="productinfo[configItem][$i][configCode]" />
							<input type="hidden" class="txt" id="cConfigId$i" value="$val[configId]" name="productinfo[configItem][$i][configId]" />
						</td>
						<td>
							<input type="text" class="txt"  id="cConfigName$i" value="$val[configName]" name="productinfo[configItem][$i][configName]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfo[configItem][$i][configNum]" />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfo[configItem][$i][explains]" />
							<input type="hidden" value="$val[id]" name="productinfo[configItem][$i][id]" />
						</td>
				 	</tr>
EOT;
					$i++;
				} else {
					$accessSeNum = $j + 1;
					$accessStr .= <<<EOT
					<tr align="center">
						<td>
			                 <img align="absmiddle" src="images/removeline.png" onclick="delAccessItem(this);" title="ɾ����" />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="aConfigCode$j" value="$val[configCode]" name="productinfo[accessItem][$j][configCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="productinfo[accessItem][$j][configId]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[configName]" name="productinfo[accessItem][$j][configName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" id="aConfigPattern$j" value="$val[configPattern]" name="productinfo[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfo[accessItem][$j][configNum]"  />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfo[accessItem][$j][explains]" />
							<input type="hidden" value="$val[id]" name="productinfo[accessItem][$j][id]" />
						</td>
				 	</tr>
EOT;
					$j++;
				}
			}
		}
		return array($configStr, $accessStr, $configSeNum, $accessSeNum);
	}

	/**
	 * ���������������ģ��
	 * @param $accessArr
	 */
	function showEditProAccessItem($productArr) {
		$accessStr = ""; //�����Ϣ�ַ���
		$j = 0;
		if (is_array($productArr)) {
			foreach ($productArr as $key => $val) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center">
						<td>
							<input type="checkbox" id="isDelTag$j" name="productinfo[productItem][$j][isDelTag]"  />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="productCode$j" value="$val[productCode]" name="productinfo[productItem][$j][productCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[id]" name="productinfo[productItem][$j][productId]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[productName]" name="productinfo[productItem][$j][productName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readOnly id="aConfigPattern$j" value="$val[pattern]" name="productinfo[productItem][$j][pattern]" />
						</td>
				 	</tr>
EOT;
				$j++;
			}
		}
		return $accessStr;
	}

	/*====================================================ҵ�����ݴ���=======================================*/
	/**
	 * ������Ʒ��Ϣʱ�������ƷΪӲ����ʱ��ͬʱ�������Ӧ�������Ϣ
	 */
	function add_d($productinfo) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict ();
			$productinfo['statTypeName'] = $datadictDao->getDataNameByCode($productinfo['statType']);

			//�ж��ظ�
			if (parent::isRepeat(array("productCodeEq" => $productinfo['productCode']), '')) {
				throw new Exception ("���ϱ���Ѵ��ڣ�");
			}

			$id = parent::add_d($productinfo, true);
			$this->updateObjWithFile($id);
			$configurationDao = new model_stock_productinfo_configuration ();
			//����������Ϣ
			if (is_array($productinfo['configItem'])) {
				$configItemArr = $this->setItemMainId("hardWareId", $id, $productinfo['configItem']);
				$configurationDao->saveDelBatch($configItemArr);
			}
			//��������嵥
			if (is_array($productinfo['accessItem'])) {
				$accessItemArr = $this->setItemMainId("hardWareId", $id, $productinfo['accessItem']);
				$configurationDao->saveDelBatch($accessItemArr);
			}

			// ������Ʒ����
			if ($productinfo['relGoodsPro'] && $productinfo['relGoodsId']) {
				$productinfo['id'] = $id;
				$this->setGoodsProperties_d($productinfo);
			}

			//���²�����־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->addObjLog($this->tbl_name, $id, $productinfo);

			//�����ʼ�
			$wlstatus = "����";
			if ("WLSTATUSKF" != $productinfo['ext1']) {
				$wlstatus = "�ر�";
			}
			$dataDict = new model_system_datadict_datadict();
			$wlsx = $dataDict->getDataNameByCode($productinfo['properties']);
			$sendContent = "��λ��:<br/>OAϵͳ����������,��ϸ���£�.<br/>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>��������:</td><td>" . $productinfo['proType'] . "</td><td>״̬:</td><td>" . $wlstatus . "</td></tr>"
				. "<tr><td>���ϱ���:</td><td>" . $productinfo['productCode'] . "</td><td>�������ƣ�</td><td>" . $productinfo['productName'] . "</td></tr>"
				. "<tr><td>�ͺ�/�汾��:</td><td>" . $productinfo['pattern'] . "</td><td>�����ڣ�</td><td>" . $productinfo['warranty'] . "</td></tr>"
				. "<tr><td>��������:</td><td>" . $productinfo['arrivalPeriod'] . "</td><td>�ɹ����ڣ�</td><td>" . $productinfo['purchPeriod'] . "</td></tr>"
				. "<tr><td>�ɹ�������:</td><td>" . $productinfo['purchUserName'] . "</td><td>�������ԣ�</td><td>" . $wlsx . "</td></tr>"
				. "<tr><td>��λ:</td><td>" . $productinfo['unitName'] . "</td><td>Ʒ�ƣ�</td><td>" . $productinfo['brand'] . "</td></tr>"
				. "<tr><td>K3����:</td><td>" . $productinfo['ext2'] . "</td><td>���ϳɱ���</td><td>" . $productinfo['priCost'] . "</td></tr>"
				. "<tr><td>��װ:</td><td colspan='3'>" . $productinfo['packageInfo'] . "</td></tr></table>";
			$sendContent .= "</table>";
			$emailDao = new model_common_mail ();
			$emailDao->mailClear("��������", $this->mailArr['TO_ID'], $sendContent);
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * �޸�Ӳ�����Ʒ��Ϣʱ��ͬʱ�޸�Ӳ������Ӧ�������Ϣ
	 * @param $productInfo
	 * @return mixed
	 */
	function edit_d($productInfo) {
		try {
			$this->start_d();
			//�ж��ظ�
			if (parent::isRepeat(array("productCodeEq" => $productInfo['productCode']), $productInfo['id'])) {
				throw new Exception ("���ϱ���Ѵ��ڣ�");
			}

			if (!isset ($productInfo['encrypt'])) { //�ж��Ƿ�Ϊ��������
				$productInfo['encrypt'] = "";
			}
			if (!isset ($productInfo['allocation'])) { //�ж��Ƿ�Ϊ��������
				$productInfo['allocation'] = "";
			}
			if (!isset ($productInfo['priceLock'])) { //�ж��Ƿ�Ϊ��������
				$productInfo['priceLock'] = "";
			}
			if (!isset ($productInfo['encryptionLock'])) { //�ж��Ƿ�Ϊ������
				$productInfo['encryptionLock'] = "";
			}
			$oldObj = $this->get_d($productInfo['id']);
			parent::edit_d($productInfo, true);

			// ������Ʒ����
			if ($productInfo['relGoodsPro'] || $oldObj['relGoodsPro']) {
				$this->setGoodsProperties_d($productInfo, $oldObj);
			}

			$configurationDao = new model_stock_productinfo_configuration ();
			//����������Ϣ
			if (is_array($productInfo['configItem'])) {
				$configItemArr = $this->setItemMainId("hardWareId", $productInfo['id'], $productInfo['configItem']);
				$configurationDao->saveDelBatch($configItemArr);
			}
			//��������嵥
			if (is_array($productInfo['accessItem'])) {
				$accessItemArr = $this->setItemMainId("hardWareId", $productInfo['id'], $productInfo['accessItem']);
				$configurationDao->saveDelBatch($accessItemArr);
			}

			//���²�����־
			$logSettingDao = new model_syslog_setting_logsetting ();
			$logSettingDao->compareModelObj($this->tbl_name, $oldObj, $productInfo);
			//
			$this->commit_d();
			return $productInfo;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д����������ȡ��Ʒ��Ϣ������ȡӲ����Ӧ�������Ϣ
	 */
	function get_d($id) {
		$configurationDao = new model_stock_productinfo_configuration ();
		$configurations = $configurationDao->getConfigByHardWareId($id);
		$productinfo = parent::get_d($id);
		$productinfo['configurations'] = $configurations;
		return $productinfo;
	}

	/**
	 * @desription ���ݲֿ�Id�Ҳֿ��еĲ�Ʒ
	 * @param tags
	 * @date 2010-12-25 ����05:12:55
	 * @qiaolong
	 */
	function getPdinfoByStockId($stockId) {
		$inventoryinfonewDao = new model_stock_inventoryinfo_inventoryinfo ();
		$inventoryinfonewDao->searchArr['stockId'] = $stockId;
		return $inventoryinfonewDao->pageBySqlId('select_all');
	}

	/**
	 *���ݱ�����Ҳ�Ʒ��Ϣ
	 */
	function getProByCode($proCode) {
		$this->searchArr['nproductCode'] = $proCode;
		$proArr = $this->list_d();
		if (is_array($proArr))
			return $proArr[0];
		else
			return null;
	}

	/**
	 * ����Excel��Ϣ��������
	 * @param $objArr
	 */
	function importProInfo($objArr) {
		try {
			$this->start_d();
			set_time_limit(0);
			$resultArr = array();
			$proTypeDao = new model_stock_productinfo_producttype ();
			$dataDictDao = new model_system_datadict_datadict ();
			$commonDataDao = new model_common_otherdatas ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes(array("WLSTATUS", "WLSX"));
			foreach ($objArr as $key => $obj) {
				$actNum = $key + 2;
				if (!empty($obj[0])) {
					if (empty($obj[5])) {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!���ϱ��벻��Ϊ��"));
						continue;
					}
					if (empty($obj[6])) {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!�������Ʋ���Ϊ��"));
						continue;
					}
					if (!empty($obj[10])) {
						if (!is_numeric($obj[10])) {
							array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!���ϳɱ����ʹ���"));
							continue;
						} elseif ($obj[10] <= 0) {
							array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!���ϳɱ��������0"));
							continue;
						}
					}
					$ext1StatusCode = "WLSTATUSKF"; //״̬����
					$proTypeObj = $proTypeDao->getByTypeLevel($obj[0], $obj[1], $obj[2], $obj[3]);

					if (isset ($dataDictOpt['WLSTATUS'])) { //����״̬
						foreach ($dataDictOpt['WLSTATUS'] as $key1 => $dataObj) {
							if ($dataObj['dataName'] == $obj[13]) {
								$ext1StatusCode = $dataObj['dataCode'];
								break;
							}
						}
					}
					$propertiesCode = "";
					if (isset ($dataDictOpt['WLSX'])) { //��������(���ơ��⹺)
						foreach ($dataDictOpt['WLSX'] as $key1 => $dataObj) {
							if ($dataObj['dataName'] == $obj[14]) {
								$propertiesCode = $dataObj['dataCode'];
								break;
							}
						}
					}
					//��ȡ�ɹ���������Ϣ
					$purchUserName = $obj[18];
					$purchUserCode = "";
					if (!empty($purchUserName)) {
						$rs = $commonDataDao->getUserID($purchUserName);
						$purchUserCode = $rs[0]['USER_ID'];
					}
					if (!empty($obj[7]) && !empty($obj[8])) {
						//��ȡ��������id
						$productInfo = $this->find(array('productCode' => $obj[7], 'productName' => $obj[8]), null, 'id');
						if (empty($productInfo['id'])) {
							array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!�������ϱ���������������Ʋ�ƥ��"));
							continue;
						}
					}
					$tempObj = array("proTypeId" => $proTypeObj['id'], "proType" => $proTypeObj['proType'], //��������
						"productCode" => $obj[5], //���ϱ���
						"productName" => $obj[6], //��������
						"relProductId" => $productInfo['id'], //��������id
						"relProductCode" => $obj[7], //�������ϱ���
						"relProductName" => $obj[8], //������������
						"ext2" => $obj[9], //k3����
						'priCost' => $obj[10],//���ϳɱ�
						"unitName" => $obj[11], "pattern" => $obj[12], "ext1" => $ext1StatusCode, //����״̬,
						"properties" => $propertiesCode, "warranty" => $obj[15], //������
						"arrivalPeriod" => $obj[16], //��������
						"purchPeriod" => $obj[17], //�ɹ�����
						"purchUserCode" => $purchUserCode, //�ɹ�������code
						"purchUserName" => $purchUserName, //�ɹ�������Name
						"brand" => $obj[19], //Ʒ��
						"packageInfo" => $obj[20], //��װ
						"material" => $obj[21], //����
						"color" => $obj[22], //��ɫ
						"leastPackNum" => $obj[23], //��С��װ��
						"leastOrderNum" => $obj[24], //��С������
						"supplier" => $obj[25], //������Ϣ
						"remark" => $obj[26],    //��ע
						"priceLock" => $obj[27], //��������
					);
					if ($tempObj['priceLock'] == "��")
						$tempObj['priceLock'] = 1;
					else if ($tempObj['priceLock'] == "��")
						$tempObj['priceLock'] = 0;

                    if ($tempObj['properties'] == 'WLSXCJ') {
                        array_push($resultArr, array("docCode" => $tempObj['productCode'],
                            "result" => "���ܵ���������"));
                        continue;
                    }

					//�ж����ϱ����Ӧ��Ϣ�Ƿ����,������������
					$this->searchArr = null;
					$this->searchArr['productCode'] = $tempObj['productCode'];
					$searchResult = $this->list_d();
					if (is_array($searchResult)) {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!�����ϱ���Ѵ���"));
					} else { //�����ڵĽ��ж�������
						if (!empty ($tempObj['ext2'])) { //k3���벻Ϊ��,��ʾ�Ǿ�����,�෴������ϵͳ�����õ�������Ϣ
							$this->searchArr = null;
							$this->searchArr['ext2'] = $tempObj['ext2'];

							if (is_array($this->list_d())) {
								array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!�����϶�Ӧ��k3�����Ѵ���,��ȷ��!"));
							} else {
								if ($this->add_d($tempObj)) {
									array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ɹ�"));
								} else {
									array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��,��ȷ�������Ϣ�Ƿ���ȷ!"));
								}
							}
						} else {
							if ($this->add_d($tempObj)) {
								array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "���������ϳɹ�"));
							} else {
								array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��,��ȷ����������Ϣ�Ƿ���ȷ!"));
							}
						}
					}
				}
			}

			$this->commit_d();
			return $resultArr;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ����Excel��Ϣ��������
	 * @param $objArr
	 */
	function updateProByExcel($objArr) {
		try {
			$this->start_d();
			set_time_limit(0);
			$resultArr = array();
			$dataDictDao = new model_system_datadict_datadict ();
			$commonDataDao = new model_common_otherdatas ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes("WLSTATUS");
			//������������
			foreach ($objArr as $key => $obj) {
				$flag = true;
				foreach ($obj as $k => $v) {
					if (!empty($v)) {
						$flag = false;
						break;
					}
				}
				if ($flag) {
					unset($objArr[$key]);
				}
			}
			foreach ($objArr as $key => $obj) {
				$actNum = $key + 2;
				$ext1StatusCode = "WLSTATUSKF"; //״̬����
				if (empty($obj[0])) {
					array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!���ϱ��벻��Ϊ��"));
					continue;
				}
				if (empty($obj[1])) {
					array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!�������Ʋ���Ϊ��"));
					continue;
				}
				if (!empty($obj[5])) {
					if (!is_numeric($obj[5])) {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!���ϳɱ����ʹ���"));
						continue;
					} else {
						if ($obj[5] <= 0) {
							array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!���ϳɱ��������0"));
							continue;
						}
					}
				}
				if (isset ($dataDictOpt['WLSTATUS'])) { //����״̬
					foreach ($dataDictOpt['WLSTATUS'] as $key1 => $dataObj) {
						if ($dataObj['dataName'] == $obj[8]) {
							$ext1StatusCode = $dataObj['dataCode'];
							break;
						}
					}
				}
				//��ȡ�ɹ���������Ϣ
				$purchUserName = $obj[12];
				$purchUserCode = "";
				if (!empty($purchUserName)) {
					$rs = $commonDataDao->getUserID($purchUserName);
					$purchUserCode = $rs[0]['USER_ID'];
				}
				if (!empty($obj[2]) && !empty($obj[3])) {
					//��ȡ��������id
					$productInfo = $this->find(array('productCode' => $obj[2], 'productName' => $obj[3]), null, 'id');
					if (empty($productInfo['id'])) {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��!�������ϱ���������������Ʋ�ƥ��"));
						continue;
					}
				}
				$tempObj = array("productCode" => $obj[0], //���ϱ���
					"productName" => $obj[1], //��������
					"relProductId" => $productInfo['id'], //��������id
					"relProductCode" => $obj[2], //�������ϱ���
					"relProductName" => $obj[3], //������������
					"ext2" => $obj[4], //k3����
					'priCost' => $obj[5],//���ϳɱ�
					"unitName" => $obj[6], "pattern" => $obj[7], "ext1" => $ext1StatusCode, //����״̬,
					"warranty" => $obj[9], //������
					"arrivalPeriod" => $obj[10], //��������
					"purchPeriod" => $obj[11], //�ɹ�����
					"purchUserCode" => $purchUserCode, //�ɹ�������code
					"purchUserName" => $purchUserName, //�ɹ�������Name
					"brand" => $obj[13], //Ʒ��
					"packageInfo" => $obj[14], //��װ
					"material" => $obj[15], //����
					"color" => $obj[16], //��ɫ
					"leastPackNum" => $obj[17], //��С��װ��
					"leastOrderNum" => $obj[18], //��С������
					"supplier" => $obj[19], //������Ϣ
					"remark" => $obj[20],//��ע
					"priceLock" => $obj[21]//��������
				);
				if ($tempObj['priceLock'] == "��")
					$tempObj['priceLock'] = 1;
				else if ($tempObj['priceLock'] == "��")
					$tempObj['priceLock'] = 0;

				//�ж����ϱ����Ӧ��Ϣ�Ƿ����,������������
				$this->searchArr = null;
				$this->searchArr['productCode'] = $tempObj['productCode'];
				$searchResult = $this->list_d();
				if (is_array($searchResult)) {
                    if ($searchResult[0]['properties'] == 'WLSXCJ') {
                        array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "������͵����ϲ��ܽ���EXCEL���²���"));
                        continue;
                    }
					$tempObj['id'] = $searchResult[0]['id'];
					if ($this->updateById($tempObj)) {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "������Ϣ���³ɹ�"));
					} else {
						array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "������Ϣ����ʧ��"));
					}
				} else {
					array_push($resultArr, array("docCode" => '��' . $actNum . '������', "result" => "����ʧ��,�����ϱ��ϵͳ������!"));
				}
			}

			$this->commit_d();
			return $resultArr;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

    /**
     * ����Excel��Ϣ�������ϳɱ�
     * @param $objArr
     * @return array|null
     */
    function updatePriceByExcel($objArr) {
		set_time_limit(0);
		try {
			$this->start_d();
			$resultArr = array();

			foreach ($objArr as $obj) {
				if (!empty ($obj['0'])) {
					if (!is_numeric($obj[1])) {
						array_push($resultArr, array("docCode" => $obj['0'], "result" => "���ϳɱ����ʹ���"));
						continue;
					} else {
						if ($obj[1] <= 0) {
							array_push($resultArr, array("docCode" => $obj['0'], "result" => "���ϳɱ��������0"));
							continue;
						}
					}
					$this->searchArr = null;
					$this->searchArr['nproductCode'] = $obj['0'];
					$tempObj['productCode'] = $obj['0'];
					$searchResult = $this->listBySqlId();
					if (is_array($searchResult)) {
                        if ($searchResult[0]['properties'] == 'WLSXCJ') {
                            array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "������͵����ϲ��ܽ��м۸����"));
                            continue;
                        }
						$tempObj['id'] = $searchResult[0]['id'];
						$tempObj['priCost'] = $obj[1];
						if ($this->updateById($tempObj)) {
							array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "������Ϣ���³ɹ�"));
						} else {
							array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "������Ϣ����ʧ��"));
						}
					} else {
						array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "����ʧ��,�����ϱ��ϵͳ������!"));
					}
				}
			}

			$this->commit_d();
			return $resultArr;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ����Excel��Ϣ��������K3����
	 * @param $objArr
	 */
	function updateK3ByExcel($objArr) {
		try {
			$this->start_d();
			set_time_limit(0);
			$resultArr = array();

			foreach ($objArr as $key => $obj) {
				if (!empty ($obj['0'])) {
					$this->searchArr = null;
					$this->searchArr['productCode'] = $obj['0'];
					$tempObj['productCode'] = $obj['0'];
					$searchResult = $this->listBySqlId();
					if (is_array($searchResult)) {
						if (count($searchResult) == 1) {
							$tempObj['id'] = $searchResult[0]['id'];
							$tempObj['ext2'] = $obj[1];
							if ($this->updateById($tempObj)) {
								array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "������Ϣ���³ɹ�"));
							} else {
								array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "������Ϣ����ʧ��"));
							}

						} else {
							array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "����ʧ��,�����ϱ�Ŵ����ظ�!"));
						}
					} else {
						array_push($resultArr, array("docCode" => $tempObj['productCode'], "result" => "����ʧ��,�����ϱ��ϵͳ������!"));
					}
				}
			}

			$this->commit_d();
			return $resultArr;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ����K3�����ȡ������Ϣ
	 */
	function getProByExt2($proKCode) {
		$this->searchArr = array("ext2" => $proKCode);
		$result = $this->list_d();
		if (is_array($result)) {
			return $result[0];
		} else {
			return null;
		}
	}

	/**
	 * ���ݲ�Ʒ����id��ȡû�������Ϣ������
	 * @param $proTypeId
	 */
	function findNotAccessPro($proTypeId) {
		$sql = "select c.id from oa_stock_product_info c where not exists (select 1 from oa_stock_product_configuration p where p.hardWareId=c.id  and p.configType='proaccess')  and c.proTypeId='$proTypeId'";
		return $this->findSql($sql);
	}

	/**
	 * ���ݲ�Ʒ����id��ȡ�Ѿ��������Ϣ������
	 * @param $proTypeId
	 */
	function findAccessPro($proTypeId) {
		$sql = "select c.id from oa_stock_product_info c where exists (select 1 from oa_stock_product_configuration p where p.hardWareId=c.id  and p.configType='proaccess')  and c.proTypeId='$proTypeId'";
		return $this->findSql($sql);
	}

	/**
	 * ���ݲ�Ʒ����id��ȡ�Ѿ��������Ϣ������
	 * @param $proTypeId
	 */
	function findArrPro($proTypeId) {
		$sql = "select c.* from oa_stock_product_info c where exists (select 1 from oa_stock_product_configuration p where p.hardWareId=c.id  and p.configType='proaccess')  and c.proTypeId='$proTypeId'";
		return $this->findSql($sql);
	}

	/**********************************��ʱ������Ϣ����*******************by LiuB*******************2011��6��29��10:57:11****************************************/
	/**
	 * ������ʱ����ʱ������ʱ������������Ϣ��ͬʱ���Զ����嵥����ת����Ʒ�嵥
	 * bu LiuB
	 */
	function tempadd_d($productinfo) {
		try {
			$this->start_d();
			$id = parent::add_d($productinfo);
			//���������Ϣ
			if (is_array($productinfo['configurations'])) {
				$configurationDao = new model_stock_productinfo_configuration ();
				foreach ($productinfo['configurations'] as $key => $value) {
					if (!empty ($value['configName'])) {
						$value['hardWareId'] = $id;
						$configurationDao->add_d($value);
					}
				}
			}
			//��ȡԭ��ͬ�Զ����嵥������
			$type = $productinfo['type'];
			$rows = $this->cusInfo($type, $productinfo['tempId']);
			unset ($rows['id']);
			$rows['productName'] = $productinfo['productName'];
			$rows['productModel'] = $productinfo['pattern'];
			$rows['productId'] = $id;
			$rows['productNo'] = $productinfo['productCode'];
			$rows['warrantyPeriod'] = $productinfo['warranty'];
			$rows['unitName'] = $productinfo['unitName'];
			$rows['projArraDate'] = $rows['projArraDT'];

			//ɾ���Զ����嵥�ڵ�������Ϣ
			if ($type == "oa_sale_order") {
				$tempProductDao = new model_projectmanagent_order_customizelist ();
				$tempProductDao->delete(array('id' => $productinfo['tempId']));
			} else if ($type == "oa_sale_service") {
				$customizelistDao = new model_engineering_serviceContract_customizelist ();
				$customizelistDao->delete(array('id' => $productinfo['tempId']));
			} else if ($type == "oa_sale_lease") {
				$customizelistDao = new model_contract_rental_customizelist ();
				$customizelistDao->delete(array('id' => $productinfo['tempId']));
			} else if ($type == "oa_sale_rdproject") {
				$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
				$customizelistDao->delete(array('id' => $productinfo['tempId']));
			}

			//���Զ����嵥�����������Ʒ�嵥
			if ($type == "oa_sale_order") {
				$orderequDao = new model_projectmanagent_order_orderequ ();
				$orderequDao->add_d($rows);
			} else if ($type == "oa_sale_service") {
				$serviceequDao = new model_engineering_serviceContract_serviceequ ();
				$serviceequDao->add_d($rows);
			} else if ($type == "oa_sale_lease") {
				$equDao = new model_contract_rental_tentalcontractequ ();
				$equDao->add_d($rows);
			} else if ($type == "oa_sale_rdproject") {
				$equDao = new model_rdproject_yxrdproject_rdprojectequ ();
				$equDao->add_d($rows);
			}
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ���Զ����嵥������д���Ʒ�嵥
	 */
	function tempedit_d($productinfo) {
		try {
			$this->start_d();

			//��ȡԭ��ͬ�Զ����嵥������
			$type = $productinfo['type'];
			$rows = $this->cusInfo($type, $productinfo['tempId']);
			$productinfo['orderId'] = $rows['orderId'];
			//ɾ���Զ����嵥�ڵ�������Ϣ
			if ($type == "oa_sale_order") {
				$tempProductDao = new model_projectmanagent_order_customizelist ();
				$tempProductDao->delete(array('id' => $productinfo['tempId']));
			} else if ($type == "oa_sale_service") {
				$customizelistDao = new model_engineering_serviceContract_customizelist ();
				$customizelistDao->delete(array('id' => $productinfo['tempId']));
			} else if ($type == "oa_sale_lease") {
				$customizelistDao = new model_contract_rental_customizelist ();
				$customizelistDao->delete(array('id' => $productinfo['tempId']));
			} else if ($type == "oa_sale_rdproject") {
				$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
				$customizelistDao->delete(array('id' => $productinfo['tempId']));
			}
			//���Զ����嵥�����������Ʒ�嵥
			if ($type == "oa_sale_order") {
				$orderequDao = new model_projectmanagent_order_orderequ ();
				$orderequDao->add_d($productinfo);
			} else if ($type == "oa_sale_service") {
				$serviceequDao = new model_engineering_serviceContract_serviceequ ();
				$serviceequDao->add_d($productinfo);
			} else if ($type == "oa_sale_lease") {
				$equDao = new model_contract_rental_tentalcontractequ ();
				$equDao->add_d($productinfo);
			} else if ($type == "oa_sale_rdproject") {
				$equDao = new model_rdproject_yxrdproject_rdprojectequ ();
				$equDao->add_d($productinfo);
			}

			$this->commit_d();
			//				$this->rollBack ();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}

	/**
	 * ���ݺ�ͬ���� ��ȡ���ֺ�ͬ�Զ����嵥��Ϣ
	 * by LiuB
	 */
	function typeInfo($type, $id) {
		if ($type == "oa_sale_order") {
			$tempProductDao = new model_projectmanagent_order_customizelist ();
			$rows = $tempProductDao->get_d($id);
		} else if ($type == "oa_sale_service") {
			$customizelistDao = new model_engineering_serviceContract_customizelist ();
			$rows = $customizelistDao->get_d($id);

		} else if ($type == "oa_sale_lease") {
			$customizelistDao = new model_contract_rental_customizelist ();
			$rows = $customizelistDao->get_d($id);
		} else if ($type == "oa_sale_rdproject") {
			$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
			$rows = $customizelistDao->get_d($id);

		}
		return $rows;
	}

	//���ݺ�ͬ���ͻ�ȡ���ֺ�ͬ ԭ�Զ����嵥����
	function cusInfo($type, $id) {
		if ($type == "oa_sale_order") {
			$tempProductDao = new model_projectmanagent_order_customizelist ();
			$rows = $tempProductDao->tempget_d($id);
		} else if ($type == "oa_sale_service") {
			$customizelistDao = new model_engineering_serviceContract_customizelist ();
			$rows = $customizelistDao->tempget_d($id);

		} else if ($type == "oa_sale_lease") {
			$customizelistDao = new model_contract_rental_customizelist ();
			$rows = $customizelistDao->tempget_d($id);
		} else if ($type == "oa_sale_rdproject") {
			$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
			$rows = $customizelistDao->tempget_d($id);
		}
		return $rows;
	}
	/****************************************************************************************************************************/

	/**
	 * ��ȡ��Ʒ������Ҫ������
	 * by Liub
	 */
	function productPage($cont) {
		$arr = array();
		foreach ($cont as $k => $v) {
			$sql = "select * from oa_stock_product_info where proTypeId in (select id from oa_stock_product_type where  lft >(select lft from oa_stock_product_type where proType = '$v' ) and rgt < (select  rgt from oa_stock_product_type where proType = '$v') and (rgt-lft=1))";
			$row = $this->_db->getArray($sql);
			foreach ($row as $key => $val) {
				array_push($arr, $val);
			}
		}
		return $arr;
	}

	/**
	 * ����������ص�ҵ�������Ϣ
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateProductinfo($productinfo, $relationArr) {
		try {
			$this->start_d();
			$this->edit_d($productinfo);
			include_once("model/stock/productinfo/productinfoRelationTableArr.php");
			$productRelationTableArr = isset($productRelationTableArr) ? $productRelationTableArr : array();
			foreach ($productRelationTableArr as $objArr) {
				if (is_array($objArr)) {
					foreach ($objArr as $key => $value) {
						if (in_array($key, $relationArr)) {
							$sql = "update " . $key . " set ";
							foreach ($value as $k => $v) {
								if (!empty ($v) && !empty ($productinfo[$k])) {
									$sql .= $v . "='" . $productinfo[$k] . "',";
								}
							}
							$sql = substr($sql, 0, -1);
							$sql .= " where " . $value['id'] . " =" . $productinfo['id'];
							if (!empty ($productinfo['updateStartDate'])) {
								$sql .= " and createTime >= cast('" . $productinfo['updateStartDate'] . "' as datetime)";
							}
							if (!empty ($productinfo['updateEndDate'])) {
								$sql .= " and createTime <= cast('" . $productinfo['updateEndDate'] . "' as datetime)";
							}
							if (isset ($value['condition'])) { //��������
								$sql .= $value['condition'];
							}
							//echo $sql;
							$this->query($sql);
						}
					}
				} else {
					throw new Exception ("�ϲ�ʧ��,�����ļ���������.");
				}
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * �������Ƹ���id������
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateBusProductinfoByName($productinfo, $relationArr) {
		try {
			$this->start_d();
			$this->edit_d($productinfo);
			include_once("model/stock/productinfo/productinfoRelationTableArr.php");
			$productRelationTableArr = isset($productRelationTableArr) ? $productRelationTableArr : array();
			$table = $this->tbl_name;
			foreach ($productRelationTableArr as $objArr) {
				if (is_array($objArr)) {
					foreach ($objArr as $key => $value) {
						if (in_array($key, $relationArr)) {
							if (!empty ($value['productName'])) {
								$sql = "update $key left join $table  on $key." . $value['productName'] . "=$table.productName set $key." . $value['id'] . "=$table.id";
								if (!empty ($value['productCode'])) {
									$sql .= ",$key." . $value['productCode'] . "=$table.productCode";
								}
								$sql .= " where $key." . $value['productName'] . " ='" . $productinfo['productName'] . "'";
								if (!empty ($productinfo['updateStartDate'])) {
									$sql .= " and $key.createTime >= cast('$key." . $productinfo['updateStartDate'] . "' as datetime)";
								}
								if (!empty ($productinfo['updateEndDate'])) {
									$sql .= " and $key.createTime <= cast('$key." . $productinfo['updateEndDate'] . "' as datetime)";
								}

								if (isset ($value['condition'])) { //��������
									$sql .= $value['condition'];
								}

								//echo $sql;
								$this->query($sql);
							}
						}
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ���ݱ������id������
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateBusProductinfoByCode($productinfo, $relationArr) {
		try {
			$this->start_d();
			$this->edit_d($productinfo);
			include_once("model/stock/productinfo/productinfoRelationTableArr.php");
			$productRelationTableArr = isset($productRelationTableArr) ? $productRelationTableArr : array();
			$table = $this->tbl_name;
			foreach ($productRelationTableArr as $objArr) {
				if (is_array($objArr)) {
					foreach ($objArr as $key => $value) {
						if (in_array($key, $relationArr)) {
							if (!empty ($value['productCode'])) {
								$sql = "update $key left join $table  on $key." . $value['productCode'] . "=$table.productCode set $key." . $value['id'] . "=$table.id";
								if (!empty ($value['productName'])) {
									$sql .= ",$key." . $value['productName'] . "=$table.productName";
								}
								$sql .= " where $key." . $value['productCode'] . " ='" . $productinfo['productCode'] . "'";
								if (!empty ($productinfo['updateStartDate'])) {
									$sql .= " and $key.createTime >= cast('$key." . $productinfo['updateStartDate'] . "' as datetime)";
								}
								if (!empty ($productinfo['updateEndDate'])) {
									$sql .= " and $key.createTime <= cast('$key." . $productinfo['updateEndDate'] . "' as datetime)";
								}
								if (isset ($value['condition'])) { //��������
									$sql .= $value['condition'];
								}
								//echo $sql;
								$this->query($sql);
							}
						}
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * �ж�һ�������Ƿ��Ѿ���ҵ��������������Ѿ������������ع�����Ϣ
	 * @param  $id
	 */
	function isProductinfoRelated($id) {
		//����ɾ��У�飬����Ѿ�������ҵ�����������ɾ��
		include_once("model/stock/productinfo/productinfoRelationTableArr.php");
		$this->tbl_name = "oa_stock_product_info";
		$productinfo = $this->get_d($id);
		$productRelationTableArr = isset($productRelationTableArr) ? $productRelationTableArr : array();
		foreach ($productRelationTableArr as $objArr) {
			if (is_array($objArr)) {
				foreach ($objArr as $key => $value) {
					$this->tbl_name = $key;
					$num = $this->findCount(array($value['id'] => $id));
					if ($num > 0) {
						throw new Exception ("ɾ��ʧ��.���ϡ�" . $productinfo['productName'] . "���Ѿ���" . $num . "����" . $value[0] . "������.");
						break;
					}
				}
			}
		}
		return false;
	}

	/**
	 * �ж�һ�������Ƿ��Ѿ���ҵ��������������Ѿ����������׳�������Ϣ�쳣���׳�����
	 * @param unknown_type $id
	 */
	function productRelateMsg($id) {
		//����ɾ��У�飬����Ѿ�������ҵ�����������ɾ��
		include_once("model/stock/productinfo/productinfoRelationTableArr.php");
		$this->tbl_name = "oa_stock_product_info";
		$productinfo = $this->get_d($id);
		$msg = "";
		$productRelationTableArr = isset($productRelationTableArr) ? $productRelationTableArr : array();
		foreach ($productRelationTableArr as $objArr) {
			if (is_array($objArr)) {
				foreach ($objArr as $key => $value) {
					$this->tbl_name = $key;
					$num = $this->findCount(array($value['id'] => $id));
					if ($num > 0) {
						$msg .= $num . "����" . $value[0] . "������.</br>";
					}
				}
			}
		}
		if (empty ($msg)) {
			$msg = "���ϡ�" . $productinfo['productName'] . "��û�б��κ�ҵ��������.";
		} else {
			$msg = "���ϡ�" . $productinfo['productName'] . "���Ѿ���</br>" . $msg;
		}
		return $msg;
	}

	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->start_d();
			$idArr = explode(",", $ids);
			$customerTable = $this->tbl_name;
			$logSettringDao = new model_syslog_setting_logsetting ();
			$configDao = new model_stock_productinfo_configuration ();
			foreach ($idArr as $id) {
				$this->isProductinfoRelated($id);
				$this->tbl_name = $customerTable;

				//ɾ�����������־
				$logSettringDao->deleteObjLog($this->tbl_name, $this->get_d($id));

				$this->deleteByPk($id);

				//ɾ����Ӧ������ ���
				$configDao->deleteByHardWareId($id);
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ��������id����Ƿ���ڹ�����ҵ����Ϣ
	 * @param $id
	 */
	function checkRelatedResult($id) {
		include_once("model/stock/productinfo/productinfoRelationTableArr.php");
		$productRelationTableArr = isset($productRelationTableArr) ? $productRelationTableArr : array();
		foreach ($productRelationTableArr as $objArr) {
			if (is_array($objArr)) {
				foreach ($objArr as $key => $value) {
					$this->tbl_name = $key;
					$num = $this->findCount(array($value['id'] => $id));
					if ($num > 0) {
						return "0";
					}
				}
			}
		}
		return "1";
	}

	/**
	 * ���ù����嵥�Ĵӱ������id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array();
		foreach ($iteminfoArr as $key => $value) {
			if (trim($value['configName']) != "") {
				$value[$mainIdName] = $mainIdValue;
				array_push($resultArr, $value);
			}
		}
		return $resultArr;
	}

	/**
	 * �жϺ�ͬ���ϱ����������������Ϣ�����ڵĴ�С
	 * by Liubo   2011��11��7��
	 */
	function compareWarranty($proCode, $orderWrr) {
		$sql = "select warranty from " . $this->tbl_name . " where productCode = $proCode";
		$warr = $this->_db->getArray($sql);
		foreach ($warr as $k => $v) {
			$warranty = $warr[0][warranty];
		}
		return $warranty;
	}

	/**
	 * ����ĳ�����͵��µ���ˮ��
	 * @param $proTypeId
	 */
	function produceSerialCode($proTypeId) {
		$resultArr = $this->listBySql("select ifnull(max(productCode),'') as maxProductCode from " . $this->tbl_name . " where proTypeId='$proTypeId'");
		if (!empty ($resultArr[0]['maxProductCode'])) {
			$maxCode = $resultArr[0]['maxProductCode'];
			return $maxCode + 1;
		} else {
			return "";
		}
	}

	/**
	 * ��������������
	 *
	 */
	function editAccess($productinfo) {
		try {
			$this->start_d();
			$accessDao = new model_stock_productinfo_configuration();
			foreach ($productinfo['accessItem'] as $k => $access) {
				foreach ($productinfo['productItem'] as $j => $productObj) {
					if (isset($productObj['isDelTag'])) {
						if ($productinfo['actType'] == "add") {//���Ӹ������
							$editCondition = array("configType" => "proaccess", "hardWareId" => $productObj['productId'], "configId" => $access['productId']);

							$lastConfig = $accessDao->find($editCondition);
							if (is_array($lastConfig)) {
								$lastConfig['configNum'] = $access['proNum'];
								$lastConfig['explains'] = $access['remark'];
								$accessDao->updateById($lastConfig);
							} else {
								$accessObj = array(
									"configType" => "proaccess",
									"hardWareId" => $productObj['productId'],
									"configId" => $access['productId'],
									"configCode" => $access['productCode'],
									"configName" => $access['productName'],
									"configPattern" => $access['pattern'],
									"configNum" => $access['proNum'],
									"explains" => $access['remark'],
								);
								$accessDao->add_d($accessObj);
							}
						} else {//ɾ�����
							$conditions = array("configType" => "proaccess", "configId" => $access['productId'], "hardWareId" => $productObj['productId']);
							$accessDao->delete($conditions);
						}
					}
				}
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * k3Code formatter
	 * @param array $waitList
	 * @param string $k3Code default k3Code
	 * @param string $productId default productId
	 * @return array $waitList gain k3Code
	 */
	function k3CodeFormatter_d($waitList, $k3Code = 'k3Code', $productId = 'productId') {
		$idList = array();
		foreach ($waitList as $k => $v) {
			if (!isset($v[$k3Code]) || empty($v[$k3Code])) {
				array_push($idList, $v[$productId]);
			}
		}
		if (!empty($idList)) {
			$this->searchArr = array('idArr' => implode(',', $idList));
			$productList = $this->list_d();
			$k3Map = array();
			if (!empty($productList)) {
				foreach ($productList as $v) {
					$k3Map[$v['id']] = $v['ext2'];
				}
				foreach ($waitList as $k => $v) {
					$waitList[$k][$k3Code] = isset($k3Map[$v[$productId]]) ? $k3Map[$v[$productId]] : $waitList[$k][$k3Code];
				}
			}
		}
		return $waitList;
	}

	/**
	 * update goods properties
	 * @param $obj
	 * @param $oldObj
	 * @return true
	 * @throws $e
	 */
	function setGoodsProperties_d($obj, $oldObj = null) {
		$propertiesItemDao = new model_goods_goods_propertiesitem();
		$propertiesItem = $propertiesItemDao->find(array(
			'productId' => $obj['id'],
			'mainId' => $obj['relGoodsPro']
		), null, 'id');

		try {
			if ($obj['relGoodsPro']) {
				if ($propertiesItem) {
					$propertiesItemDao->update(array('id' => $propertiesItem['id']), array(
						'mainId' => $obj['relGoodsPro'],
						'status' => $obj['ext1'] == 'WLSTATUSKF' ? 'ZC' : 'TC'
					));
				} else {
					$propertiesItemDao->add_d(array(
						'mainId' => $obj['relGoodsPro'],
						'itemContent' => $obj['productName'],
						'productId' => $obj['id'],
						'productCode' => $obj['productCode'],
						'productName' => $obj['productName'],
						'pattern' => $obj['pattern'],
						'defaultNum' => 1,
						'proNum' => 1,
						'status' => $obj['ext1'] == 'WLSTATUSKF' ? 'ZC' : 'TC'
					));
				}
			}
			// if change relGoodsPro, delete old info
			if ($oldObj['relGoodsPro'] != $obj['relGoodsPro'] && $oldObj['relGoodsPro']) {
				$propertiesItemDao->delete(array(
					'productId' => $oldObj['id'],
					'mainId' => $oldObj['relGoodsPro']
				));
			}

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * get relate goods properties product
	 * @param $propertiesId
	 * @return array[productId => propertiesId]
	 */
	function getRelProduct_d($propertiesId) {
		$arr = $this->findAll(array('relGoodsPro' => $propertiesId), null, 'id,relGoodsPro');
		if ($arr) {
			$new = array();
			foreach ($arr as $k => $v) {
				$new[$v['id']] = $v['relGoodsPro'];
			}
			return $new;
		} else {
			return false;
		}
	}

	/**
	 * update product relGoodsProName
	 * @param string $ids 1,2,3,4
	 * @param $relGoodsProName
	 * @return mixed
	 * @throws $e
	 */
	function updateRelGoodsProName_d($ids, $relGoodsProName) {
		try {
			return $this->_db->query("UPDATE " . $this->tbl_name . " SET relGoodsProName = '" . $relGoodsProName
				. "' WHERE id IN(" . $ids . ")");
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * clear product relGoodsPro info
	 * @param $id
	 * @return mixed
	 * @throws $e
	 */
	function clearRelGoodsPro_d($id) {
		try {
			return $this->_db->query("UPDATE " . $this->tbl_name . " SET relGoodsProName = '',relGoodsPro = ''"
				. " WHERE id = " . $id);
		} catch (Exception $e) {
			throw $e;
		}
	}

    /**
     * ��������ID��ȡ����һ������
     * @param $productId ����ID
     */
    function getParentType($productId){
        $typeRow=array();
        $row=$this->get_d($productId);
        if($row['proTypeId']>0){
            $productTypeDao = new model_stock_productinfo_producttype ();
            $proTypeObj = $productTypeDao->get_d($row['proTypeId']);
            $productTypeDao->searchArr = array("xlft" => $proTypeObj['lft'], "drgt" => $proTypeObj['rgt']);
            $productTypeDao->sort = "c.lft";
            $productTypeDao->asc = false;
            $proTypeParent = $productTypeDao->list_d();
            if(!empty($proTypeParent)){
                $typeRow=$proTypeParent[0];
            }else{
                $typeRow=$proTypeObj;
            }
        }
        return $typeRow;
    }
}