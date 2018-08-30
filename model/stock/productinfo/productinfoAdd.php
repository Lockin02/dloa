<?php
/*
 * Created on 2010-7-17
 *	��Ʒ������ϢModel
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_stock_productinfo_productinfoAdd extends model_base {
	public $db;
	private $state=array('0'=>'����','1'=>'δȷ��','2'=>'��ȷ��','3'=>'���');
	function __construct() {
		include (WEB_TOR."model/common/mailConfig.php");
		$this->tbl_name = "oa_stock_product_info_temp";
		$this->sql_map = "stock/productinfo/productinfoAddSql.php";
		$this->mailArr=$mailUser[$this->tbl_name];
		$this->statusDao = new model_common_status();
		$this->statusDao->status = array (
			0 => array (
				'statusEName' => 'save',
				'statusCName' => 'δ�ύ',
				'key' => '0'
			),
			1 => array (
				'statusEName' => 'submit',
				'statusCName' => '�ύ',
				'key' => '1'
			)
		);
		parent::__construct ();
	}
	/**
	 *
	 * ����������嵥ģ��
	 * @param unknown_type $rows
	 */
	function showAccessAtAdd($rows) {
		$accessStr = ""; //�����Ϣ�ַ���
		$j = 0;
		$accessSeNum = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
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
							<input type="text" class="txtshort"  id="aConfigCode$j" value="$val[configCode]" name="productinfoAdd[accessItem][$j][configCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="productinfoAdd[accessItem][$j][configId]" />
							<input type="hidden" class="txt" id="aConfigType$j" value="proaccess" name="productinfoAdd[accessItem][$j][configType]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[configName]" name="productinfoAdd[accessItem][$j][configName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readOnly id="aConfigPattern$j" value="$val[configPattern]" name="productinfoAdd[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfoAdd[accessItem][$j][configNum]"  />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfoAdd[accessItem][$j][explains]" />
						</td>
				 	</tr>
EOT;
				$j ++;
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
		$accessSeNum = 0;
		$configSeNum = 0;
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				if (empty ( $val ['configId'] ) || $val ['configId'] == null) {
					$configSeNum = $i + 1;
					$configStr .= <<<EOT
					<tr align="center">
                        <td>
                       		 $configSeNum
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
					$i ++;
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
					$j ++;
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
		if (is_array ( $rows )) {
			foreach ( $rows as $key => $val ) {
				if (empty ( $val ['configId'] ) || $val ['configId'] == null) {
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
							<input type="text" class="txt"  id="cConfigName$i" value="$val[configName]" name="productinfoAdd[configItem][$i][configName]" />
							<input type="hidden" class="txt" id="cConfigId$i" value="$val[configId]" name="productinfoAdd[configItem][$i][configId]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfoAdd[configItem][$i][configNum]" />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfoAdd[configItem][$i][explains]" />
							<input type="hidden" value="$val[id]" name="productinfoAdd[configItem][$i][id]" />
						</td>
				 	</tr>
EOT;
					$i ++;
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
							<input type="text" class="txtshort"  id="aConfigCode$j" value="$val[configCode]" name="productinfoAdd[accessItem][$j][configCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[configId]" name="productinfoAdd[accessItem][$j][configId]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[configName]" name="productinfoAdd[accessItem][$j][configName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" id="aConfigPattern$j" value="$val[configPattern]" name="productinfoAdd[accessItem][$j][configPattern]" />
						</td>
						<td>
							<input type="text" class="txtshort" value="$val[configNum]" name="productinfoAdd[accessItem][$j][configNum]"  />
						</td>
						<td>
							<input type="text" class="txt" value="$val[explains]" name="productinfoAdd[accessItem][$j][explains]" />
							<input type="hidden" value="$val[id]" name="productinfoAdd[accessItem][$j][id]" />
						</td>
				 	</tr>
EOT;
					$j ++;
				}
			}
		}
		return array ($configStr, $accessStr, $configSeNum, $accessSeNum );
	}
	/**
	 * 
	 * ���������������ģ��
	 * @param $accessArr
	 */
	function showEditProAccessItem($productArr){
		$accessStr = ""; //�����Ϣ�ַ���
		$j = 0;
		$accessSeNum = 0;
		if (is_array ( $productArr )) {
			foreach ( $productArr as $key => $val ) {
				$accessSeNum = $j + 1;
				$accessStr .= <<<EOT
					<tr align="center">
						<td>
							<input type="checkbox" id="isDelTag$j" name="productinfoAdd[productItem][$j][isDelTag]"  />
			            </td>
                        <td>
                       		 $accessSeNum
                        </td>
						<td>
							<input type="text" class="txtshort"  id="productCode$j" value="$val[productCode]" name="productinfoAdd[productItem][$j][productCode]" />
							<input type="hidden" class="txt" id="aConfigId$j" value="$val[id]" name="productinfoAdd[productItem][$j][productId]" />
						</td>
						<td>
							<input type="text" class="txt" id="aConfigName$j" value="$val[productName]" name="productinfoAdd[productItem][$j][productName]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" readOnly id="aConfigPattern$j" value="$val[pattern]" name="productinfoAdd[productItem][$j][pattern]" />
						</td>
				 	</tr>
EOT;
				$j ++;
			}
		}
		return $accessStr;		
	}
	
	/*====================================================ҵ�����ݴ���=======================================*/
	/**
	 * ������Ʒ��Ϣʱ�������ƷΪӲ����ʱ��ͬʱ�������Ӧ�������Ϣ
	 */
	function add_d($productinfo,$isAddInfo=true,$stat='save') {
		try {	
			$this->start_d ();
			$status=$this->statusDao->statusEtoK($stat);
			$productinfo['status']=$status;
			$productinfo['state']=$this->state[$status];
			$datadictDao = new model_system_datadict_datadict ();
			$productinfo ['statTypeName'] = $datadictDao->getDataNameByCode ( $productinfo ['statType'] );
			//�ж��ظ�
			if (parent::isRepeat ( array ("productCodeEq" => $productinfo ['productCode'] ), '' )) {
				throw new Exception ( "���ϱ���Ѵ��ڣ�" );
			}
			$id = parent::add_d ( $productinfo, true );
			$this->updateObjWithFile ( $id );
								
			$configurationDao = new model_stock_productinfo_configurationAdd ();
			//����������Ϣ
			if (is_array ( $productinfo ['configItem'] )) {
				$configItemArr = $this->setItemMainId ( "hardWareId", $id, $productinfo ['configItem'] );
				
				$configItemObj = $configurationDao->saveDelBatch ( $configItemArr );
			}
			//��������嵥
			if (is_array ( $productinfo ['accessItem'] )) {
				$accessItemArr = $this->setItemMainId ( "hardWareId", $id, $productinfo ['accessItem'] );
				$accessItemObj = $configurationDao->saveDelBatch ( $accessItemArr );
			}

			//���²�����־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->addObjLog ( $this->tbl_name, $id, $productinfo );
			/*
			//�����ʼ�
			$wlstatus="����";
			if("WLSTATUSKF"!=$productinfo ['ext1']){
				$wlstatus="�ر�";
			}
			$dataDict=new model_system_datadict_datadict();
			$wlsx=$dataDict->getDataNameByCode($productinfo ['properties'] );
			$sendContent = "��λ��:<br/>OAϵͳ����������,��ϸ���£�.<br/>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr><td>��������:</td><td>" . $productinfo ['proType'] . "</td><td>״̬:</td><td>" . $wlstatus. "</td></tr>"
							."<tr><td>���ϱ���:</td><td>" . $productinfo ['productCode'] . "</td><td>�������ƣ�</td><td>" . $productinfo ['productName'] . "</td></tr>"
							."<tr><td>�ͺ�/�汾��:</td><td>" . $productinfo ['pattern'] . "</td><td>�����ڣ�</td><td>" . $productinfo ['warranty'] . "</td></tr>"
							."<tr><td>��������:</td><td>" . $productinfo ['arrivalPeriod'] . "</td><td>�ɹ����ڣ�</td><td>" . $productinfo ['purchPeriod'] . "</td></tr>"
							."<tr><td>�ɹ�������:</td><td>" . $productinfo ['purchUserName'] . "</td><td>�������ԣ�</td><td>" . $wlsx . "</td></tr>"
							."<tr><td>��λ:</td><td>" . $productinfo ['unitName'] . "</td><td>Ʒ�ƣ�</td><td>" . $productinfo ['brand'] . "</td></tr>"
							."<tr><td>K3����:</td><td>" . $productinfo ['ext2'] . "</td><td>���ϳɱ���</td><td>" . $productinfo ['priCost'] . "</td></tr>"
							."<tr><td>��װ:</td><td colspan='3'>" . $productinfo ['packageInfo'] . "</td></tr></table>";					
			$sendContent .= "</table>";
			$emailDao = new model_common_mail ();
			$emailDao->mailClear ( "��������", $this->mailArr['TO_ID'], $sendContent );*/
			
			$this->commit_d ();
	
			return $id;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	
	}
	/**
	 * �޸�Ӳ�����Ʒ��Ϣʱ��ͬʱ�޸�Ӳ������Ӧ�������Ϣ
	 */
	function edit_d($productinfo,$isAddInfo=true,$stat=null) {
		try {
			$this->start_d ();
			//�ж��ظ�
			if (parent::isRepeat ( array ("nproductCode" => $productinfo ['productCode'] ), $productinfo ['id'] )) {
				throw new Exception ( "���ϱ���Ѵ��ڣ�" );
			}
			if (! isset ( $productinfo ['encrypt'] )) { //�ж��Ƿ�Ϊ��������
				$productinfo ['encrypt'] = "";
			}
			if (! isset ( $productinfo ['esmCanUse'] )) { //�ж��Ƿ�Ϊ���̿���
				$productinfo ['esmCanUse'] = 0;
			}
			if (! isset ( $productinfo ['allocation'] )) { //�ж��Ƿ�Ϊ��������
				$productinfo ['allocation'] = "";
			}
			if($stat){
				$oldObj = $this->get_d ( $productinfo ['id'] );
				$status=$this->statusDao->statusEtoK($stat);
				$productinfo['status']=$status;
				$productinfo['state']=$this->state[$status];
			}
			parent::edit_d ( $productinfo, true );
			
			$configurationDao = new model_stock_productinfo_configurationAdd ();
			//����������Ϣ
			if (is_array ( $productinfo ['configItem'] )) {
				$configItemArr = $this->setItemMainId ( "hardWareId", $productinfo ['id'], $productinfo ['configItem'] );
				$configItemObj = $configurationDao->saveDelBatch ( $configItemArr );
			}
			//��������嵥
			if (is_array ( $productinfo ['accessItem'] )) {
				$accessItemArr = $this->setItemMainId ( "hardWareId", $productinfo ['id'], $productinfo ['accessItem'] );
				$accessItemObj = $configurationDao->saveDelBatch ( $accessItemArr );
			}
			
			//���²�����־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $productinfo );
			//
			$this->commit_d ();
			return $productinfo;
		} catch ( Exception $e ) {
			echo $e->getMessage();
			$this->rollBack ();
			return null;
		}
	
	}
	
	/**
	 * ��д����������ȡ��Ʒ��Ϣ������ȡӲ����Ӧ�������Ϣ
	 */
	function get_d($id) {
		$configurationDao = new model_stock_productinfo_configurationAdd ();
		$configurations = $configurationDao->getConfigByHardWareId ( $id );
		$productinfo = parent::get_d ( $id );
		$productinfo ['configurations'] = $configurations;
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
		$inventoryinfonewDao->searchArr ['stockId'] = $stockId;
		return $inventoryinfonewDao->pageBySqlId ( 'select_all' );
	}
	
	/**
	 *���ݱ�����Ҳ�Ʒ��Ϣ
	 */
	function getProByCode($proCode) {
		$this->searchArr ['nproductCode'] = $proCode;
		$proArr = $this->list_d ();
		if (is_array ( $proArr ))
			return $proArr [0];
		else
			return null;
	
	}
	
	/**
	 *
	 * ����Excel��Ϣ��������
	 * @param $objArr
	 */
	function importProInfo($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			$proTypeDao = new model_stock_productinfo_producttype ();
			$dataDictDao = new model_system_datadict_datadict ();
			$commonDataDao = new model_common_otherdatas ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes ( array ("WLSTATUS", "WLSX" ) );
			
			$propertiesArr = array ("�⹺��Ʒ" => "WLSXWG", "ԭ����" => "WLSXWG", "��װ��" => "WLSXWG", "��Ʒ" => "WLSXZZ", "���Ʒ" => "WLSXZZ" );
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					$ext1StatusCode = "WLSTATUSKF"; //״̬����
					$proTypeObj = $proTypeDao->getByTypeLevel ( $obj [0], $obj [1], $obj [2], $obj [3] );
					
					if (isset ( $dataDictOpt ['WLSTATUS'] )) { //����״̬
						foreach ( $dataDictOpt ['WLSTATUS'] as $key1 => $dataObj ) {
							if ($dataObj ['dataName'] == $obj [10]) {
								$ext1StatusCode = $dataObj ['dataCode'];
								break;
							}
						}
					}
					$propertiesCode = "";
					if (isset ( $dataDictOpt ['WLSX'] )) { //��������(���ơ��⹺)
						foreach ( $dataDictOpt ['WLSX'] as $key1 => $dataObj ) {
							if ($dataObj ['dataName'] == $obj [11]) {
								$propertiesCode = $dataObj ['dataCode'];
								break;
							}
						}
					}
					
					$purchUserCode = $commonDataDao->getUserID ( $obj [14] );
					$purchUserName = "";
					if ($purchUserCode != "") {
						$purchUserName = $obj [15];
					}
					
					$tempObj = array ("proTypeId" => $proTypeObj ['id'], "proType" => $proTypeObj ['proType'], //��������
"productCode" => $obj [5], //���ϱ���
"productName" => $obj [6], //��������
"ext2" => $obj [7], //k3����
"unitName" => $obj [8], "pattern" => $obj [9], "ext1" => $ext1StatusCode, //����״̬,
"properties" => $propertiesCode, "warranty" => $obj [12], //������
"arrivalPeriod" => $obj [13], //��������
"purchPeriod" => $obj [14], //�ɹ�����
"purchUserCode" => $purchUserCode, //�ɹ�������code
"purchUserName" => $purchUserName, //�ɹ�������Name
"brand" => $obj [16], //Ʒ��
"packageInfo" => $obj [17], //��װ
"material" => $obj [18], //����
"color" => $obj [19], //��ɫ
"leastPackNum" => $obj [20], //��С��װ��
"leastOrderNum" => $obj [21], //��С������
"supplier" => $obj [22], //������Ϣ
"remark" => $obj [23] );
					//�ж����ϱ����Ӧ��Ϣ�Ƿ����
					$this->searchArr = null;
					$this->searchArr ['productCode'] = $tempObj ['productCode'];
					$searchResult = $this->list_d ();
					if (is_array ( $searchResult )) {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "�����ϱ���Ѵ���" ) );
					} else { //�����ڵĽ��ж�������
						if (! empty ( $tempObj ['ext2'] )) { //k3���벻Ϊ��,��ʾ�Ǿ�����,�෴������ϵͳ�����õ�������Ϣ
							$this->searchArr = null;
							$this->searchArr ['ext2'] = $tempObj ['ext2'];
							
							if (is_array ( $this->list_d () )) {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "�����϶�Ӧ��k3�����Ѵ���,��ȷ��!" ) );
							} else {
								if ($this->add_d ( $tempObj )) {
									array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ɹ�" ) );
								} else {
									array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ʧ��,��ȷ�������Ϣ�Ƿ���ȷ!" ) );
								}
							}
						} else {
							if ($this->add_d ( $tempObj )) {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "���������ϳɹ�" ) );
							} else {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ʧ��,��ȷ����������Ϣ�Ƿ���ȷ!" ) );
							}
						}
					}
				}
			}
			
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 *
	 * ����Excel��Ϣ��������
	 * @param $objArr
	 */
	function updateProByExcel($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			$proTypeDao = new model_stock_productinfo_producttype ();
			$dataDictDao = new model_system_datadict_datadict ();
			$commonDataDao = new model_common_otherdatas ();
			$dataDictOpt = $dataDictDao->getDatadictsByParentCodes ( "WLSTATUS" );
			
			$propertiesArr = array ("�⹺��Ʒ" => "WLSXWG", "ԭ����" => "WLSXWG", "��װ��" => "WLSXWG", "��Ʒ" => "WLSXZZ", "���Ʒ" => "WLSXZZ" );
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					$ext1StatusCode = "WLSTATUSKF"; //״̬����
					

					if (isset ( $dataDictOpt ['WLSTATUS'] )) { //����״̬
						foreach ( $dataDictOpt ['WLSTATUS'] as $key1 => $dataObj ) {
							if ($dataObj ['dataName'] == $obj [5]) {
								$ext1StatusCode = $dataObj ['dataCode'];
								break;
							}
						}
					}
					
					$purchUserCode = $commonDataDao->getUserID ( $obj [9] );
					$purchUserName = "";
					if ($purchUserCode != "") {
						$purchUserName = $obj [9];
					}
					
					$tempObj = array ("productCode" => $obj [0], //���ϱ���
"productName" => $obj [1], //��������
"ext2" => $obj [2], //k3����
"unitName" => $obj [3], "pattern" => $obj [4], "ext1" => $ext1StatusCode, //����״̬,
"warranty" => $obj [6], //������
"arrivalPeriod" => $obj [7], //��������
"purchPeriod" => $obj [8], //�ɹ�����
"purchUserCode" => $purchUserCode, //�ɹ�������code
"purchUserName" => $purchUserName, //�ɹ�������Name
"brand" => $obj [10], //Ʒ��
"packageInfo" => $obj [11], //��װ
"material" => $obj [12], //����
"color" => $obj [13], //��ɫ
"leastPackNum" => $obj [14], //��С��װ��
"leastOrderNum" => $obj [15], //��С������
"supplier" => $obj [16], //������Ϣ
"remark" => $obj [17] );
					
					//�ж����ϱ����Ӧ��Ϣ�Ƿ����,������������
					$this->searchArr = null;
					$this->searchArr ['productCode'] = $tempObj ['productCode'];
					$searchResult = $this->list_d ();
					if (is_array ( $searchResult )) {
						$tempObj ['id'] = $searchResult [0] ['id'];
						if ($this->updateById ( $tempObj )) {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "������Ϣ���³ɹ�" ) );
						} else {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "������Ϣ����ʧ��" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ʧ��,�����ϱ��ϵͳ������!" ) );
					}
				}
			}
			
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 *
	 * ����Excel��Ϣ�������ϳɱ�
	 * @param $objArr
	 */
	function updatePriceByExcel($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					//	print_r($obj);
					$this->searchArr = null;
					$this->searchArr ['nproductCode'] = $obj ['0'];
					$tempObj ['productCode'] = $obj ['0'];
					$searchResult = $this->listBySqlId ();
					if (is_array ( $searchResult )) {
						$tempObj ['id'] = $searchResult [0] ['id'];
						$tempObj ['priCost'] = $obj [1];
						if ($this->updateById ( $tempObj )) {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "������Ϣ���³ɹ�" ) );
						} else {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "������Ϣ����ʧ��" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ʧ��,�����ϱ��ϵͳ������!" ) );
					}
				}
			
			}
			
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 *
	 * ����Excel��Ϣ��������K3����
	 * @param $objArr
	 */
	function updateK3ByExcel($objArr) {
		try {
			$this->start_d ();
			set_time_limit ( 0 );
			$resultArr = array ();
			
			foreach ( $objArr as $key => $obj ) {
				if (! empty ( $obj ['0'] )) {
					//	print_r($obj);
					$this->searchArr = null;
					$this->searchArr ['productCode'] = $obj ['0'];
					$tempObj ['productCode'] = $obj ['0'];
					$searchResult = $this->listBySqlId ();
					if (is_array ( $searchResult )) {
						if (count ( $searchResult ) == 1) {
							$tempObj ['id'] = $searchResult [0] ['id'];
							$tempObj ['ext2'] = $obj [1];
//							print_r($tempObj);
							if ($this->updateById ( $tempObj )) {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "������Ϣ���³ɹ�" ) );
							} else {
								array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "������Ϣ����ʧ��" ) );
							}
						
						} else {
							array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ʧ��,�����ϱ�Ŵ����ظ�!" ) );
						}
					} else {
						array_push ( $resultArr, array ("docCode" => $tempObj ['productCode'], "result" => "����ʧ��,�����ϱ��ϵͳ������!" ) );
					}
				}
			
			}
			
			$this->commit_d ();
			return $resultArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	
	/**
	 *
	 * ����K3�����ȡ������Ϣ
	 */
	function getProByExt2($proKCode) {
		$this->searchArr = array ("ext2" => $proKCode );
		$result = $this->list_d ();
		if (is_array ( $result )) {
			return $result [0];
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
		return $this->findSql ( $sql );
	
	}
	
	/**
	 * ���ݲ�Ʒ����id��ȡ�Ѿ��������Ϣ������
	 * @param $proTypeId
	 */
	function findAccessPro($proTypeId) {
		$sql = "select c.id from oa_stock_product_info c where exists (select 1 from oa_stock_product_configuration p where p.hardWareId=c.id  and p.configType='proaccess')  and c.proTypeId='$proTypeId'";
		return $this->findSql ( $sql );
	
	}
	
	/**********************************��ʱ������Ϣ����*******************by LiuB*******************2011��6��29��10:57:11****************************************/
	/**
	 * ������ʱ����ʱ������ʱ������������Ϣ��ͬʱ���Զ����嵥����ת����Ʒ�嵥
	 * bu LiuB
	 */
	function tempadd_d($productinfo) {
		try {
			$this->start_d ();
			$id = parent::add_d ( $productinfo );
			//���������Ϣ
			if (is_array ( $productinfo ['configurations'] )) {
				$configurationDao = new model_stock_productinfo_configurationAdd ();
				foreach ( $productinfo ['configurations'] as $key => $value ) {
					if (! empty ( $value ['configName'] )) {
						$value ['hardWareId'] = $id;
						$configurationDao->add_d ( $value );
					}
				}
			}
			//��ȡԭ��ͬ�Զ����嵥������
			$type = $productinfo ['type'];
			$rows = $this->cusInfo ( $type, $productinfo ['tempId'] );
			unset ( $rows ['id'] );
			$rows ['productName'] = $productinfo ['productName'];
			$rows ['productModel'] = $productinfo ['pattern'];
			$rows ['productId'] = $id;
			$rows ['productNo'] = $productinfo ['productCode'];
			$rows ['warrantyPeriod'] = $productinfo ['warranty'];
			$rows ['unitName'] = $productinfo ['unitName'];
			$rows ['projArraDate'] = $rows ['projArraDT'];
			$rows ['license'] = $rows ['license'];
			
			//ɾ���Զ����嵥�ڵ�������Ϣ
			if ($type == "oa_sale_order") {
				$tempProductDao = new model_projectmanagent_order_customizelist ();
				$tempProductDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_service") {
				$customizelistDao = new model_engineering_serviceContract_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_lease") {
				$customizelistDao = new model_contract_rental_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_rdproject") {
				$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			}
			
			//���Զ����嵥�����������Ʒ�嵥
			if ($type == "oa_sale_order") {
				$orderequDao = new model_projectmanagent_order_orderequ ();
				$orderequDao->add_d ( $rows );
			
		//             $orderequDao->updateUniqueCode_d($rows['orderId']);
			} else if ($type == "oa_sale_service") {
				$serviceequDao = new model_engineering_serviceContract_serviceequ ();
				$serviceequDao->add_d ( $rows );
			} else if ($type == "oa_sale_lease") {
				$equDao = new model_contract_rental_tentalcontractequ ();
				$equDao->add_d ( $rows );
			} else if ($type == "oa_sale_rdproject") {
				$equDao = new model_rdproject_yxrdproject_rdprojectequ ();
				$equDao->add_d ( $rows );
			}
			$this->commit_d ();
			
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	
	}
	
	/**
	 * ���Զ����嵥������д���Ʒ�嵥
	 */
	function tempedit_d($productinfo) {
		try {
			$this->start_d ();
			
			//��ȡԭ��ͬ�Զ����嵥������
			$type = $productinfo ['type'];
			$rows = $this->cusInfo ( $type, $productinfo ['tempId'] );
			$productinfo ['orderId'] = $rows ['orderId'];
			//ɾ���Զ����嵥�ڵ�������Ϣ
			if ($type == "oa_sale_order") {
				$tempProductDao = new model_projectmanagent_order_customizelist ();
				$tempProductDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_service") {
				$customizelistDao = new model_engineering_serviceContract_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_lease") {
				$customizelistDao = new model_contract_rental_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			} else if ($type == "oa_sale_rdproject") {
				$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
				$customizelistDao->delete ( array ('id' => $productinfo ['tempId'] ) );
			}
			//���Զ����嵥�����������Ʒ�嵥
			if ($type == "oa_sale_order") {
				$orderequDao = new model_projectmanagent_order_orderequ ();
				$orderequDao->add_d ( $productinfo );
			
		//             $orderequDao->updateUniqueCode_d($rows['orderId']);
			} else if ($type == "oa_sale_service") {
				$serviceequDao = new model_engineering_serviceContract_serviceequ ();
				$serviceequDao->add_d ( $productinfo );
			} else if ($type == "oa_sale_lease") {
				$equDao = new model_contract_rental_tentalcontractequ ();
				$equDao->add_d ( $productinfo );
			} else if ($type == "oa_sale_rdproject") {
				$equDao = new model_rdproject_yxrdproject_rdprojectequ ();
				$equDao->add_d ( $productinfo );
			}
			
			$this->commit_d ();
			//				$this->rollBack ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
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
			$rows = $tempProductDao->get_d ( $id );
		} else if ($type == "oa_sale_service") {
			$customizelistDao = new model_engineering_serviceContract_customizelist ();
			$rows = $customizelistDao->get_d ( $id );
		
		} else if ($type == "oa_sale_lease") {
			$customizelistDao = new model_contract_rental_customizelist ();
			$rows = $customizelistDao->get_d ( $id );
		} else if ($type == "oa_sale_rdproject") {
			$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
			$rows = $customizelistDao->get_d ( $id );
		
		}
		return $rows;
	}
	//���ݺ�ͬ���ͻ�ȡ���ֺ�ͬ ԭ�Զ����嵥����
	function cusInfo($type, $id) {
		if ($type == "oa_sale_order") {
			$tempProductDao = new model_projectmanagent_order_customizelist ();
			$rows = $tempProductDao->tempget_d ( $id );
		} else if ($type == "oa_sale_service") {
			$customizelistDao = new model_engineering_serviceContract_customizelist ();
			$rows = $customizelistDao->tempget_d ( $id );
		
		} else if ($type == "oa_sale_lease") {
			$customizelistDao = new model_contract_rental_customizelist ();
			$rows = $customizelistDao->tempget_d ( $id );
		} else if ($type == "oa_sale_rdproject") {
			$customizelistDao = new model_rdproject_yxrdproject_customizelist ();
			$rows = $customizelistDao->tempget_d ( $id );
		}
		return $rows;
	}
	/****************************************************************************************************************************/
	
	/**
	 * ��ȡ��Ʒ������Ҫ������
	 * by Liub
	 */
	function productPage($cont) {
		$arr = array ();
		foreach ( $cont as $k => $v ) {
			$sql = "select * from oa_stock_product_info where proTypeId in (select id from oa_stock_product_type where  lft >(select lft from oa_stock_product_type where proType = '$v' ) and rgt < (select  rgt from oa_stock_product_type where proType = '$v') and (rgt-lft=1))";
			$row = $this->_db->getArray ( $sql );
			foreach ( $row as $key => $val ) {
				array_push ( $arr, $val );
			}
		}
		return $arr;
	}
	
	/**
	 *
	 * ����������ص�ҵ�������Ϣ
	 * @param  $productinfo
	 * @param  $relationArr
	 */
	function updateProductinfo($productinfo, $relationArr) {
		try {
			$this->start_d ();
			$this->edit_d ( $productinfo );
			include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
			foreach ( $productRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							$sql = "update " . $key . " set ";
							foreach ( $value as $k => $v ) {
								if (! empty ( $v ) && ! empty ( $productinfo [$k] )) {
									$sql .= $v . "='" . $productinfo [$k] . "',";
								}
							}
							$sql = substr ( $sql, 0, - 1 );
							$sql .= " where " . $value ['id'] . " =" . $productinfo ['id'];
							if (! empty ( $productinfo ['updateStartDate'] )) {
								$sql .= " and createTime >= cast('" . $productinfo ['updateStartDate'] . "' as datetime)";
							}
							if (! empty ( $productinfo ['updateEndDate'] )) {
								$sql .= " and createTime <= cast('" . $productinfo ['updateEndDate'] . "' as datetime)";
							}
							if (isset ( $value ['condition'] )) { //��������
								$sql .= $value ['condition'];
							}
							//echo $sql;
							$this->query ( $sql );
						}
					}
				} else {
					throw new Exception ( "�ϲ�ʧ��,�����ļ���������." );
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
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
			$this->start_d ();
			$this->edit_d ( $productinfo );
			include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
			$table = $this->tbl_name;
			foreach ( $productRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							if (! empty ( $value ['productName'] )) {
								$sql = "update $key left join $table  on $key." . $value ['productName'] . "=$table.productName set $key." . $value ['id'] . "=$table.id";
								if (! empty ( $value ['productCode'] )) {
									$sql .= ",$key." . $value ['productCode'] . "=$table.productCode";
								}
								$sql .= " where $key." . $value ['productName'] . " ='" . $productinfo ['productName'] . "'";
								if (! empty ( $productinfo ['updateStartDate'] )) {
									$sql .= " and $key.createTime >= cast('$key." . $productinfo ['updateStartDate'] . "' as datetime)";
								}
								if (! empty ( $productinfo ['updateEndDate'] )) {
									$sql .= " and $key.createTime <= cast('$key." . $productinfo ['updateEndDate'] . "' as datetime)";
								}
								
								if (isset ( $value ['condition'] )) { //��������
									$sql .= $value ['condition'];
								}
								
								//echo $sql;
								$this->query ( $sql );
							}
						}
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
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
			$this->start_d ();
			$this->edit_d ( $productinfo );
			include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
			$table = $this->tbl_name;
			foreach ( $productRelationTableArr as $objArr ) {
				if (is_array ( $objArr )) {
					foreach ( $objArr as $key => $value ) {
						if (in_array ( $key, $relationArr )) {
							if (! empty ( $value ['productCode'] )) {
								$sql = "update $key left join $table  on $key." . $value ['productCode'] . "=$table.productCode set $key." . $value ['id'] . "=$table.id";
								if (! empty ( $value ['productName'] )) {
									$sql .= ",$key." . $value ['productName'] . "=$table.productName";
								}
								$sql .= " where $key." . $value ['productCode'] . " ='" . $productinfo ['productCode'] . "'";
								if (! empty ( $productinfo ['updateStartDate'] )) {
									$sql .= " and $key.createTime >= cast('$key." . $productinfo ['updateStartDate'] . "' as datetime)";
								}
								if (! empty ( $productinfo ['updateEndDate'] )) {
									$sql .= " and $key.createTime <= cast('$key." . $productinfo ['updateEndDate'] . "' as datetime)";
								}
								if (isset ( $value ['condition'] )) { //��������
									$sql .= $value ['condition'];
								}
								//echo $sql;
								$this->query ( $sql );
							}
						}
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}
	
	/**
	 * �ж�һ�������Ƿ��Ѿ���ҵ��������������Ѿ������������ع�����Ϣ
	 * @param  $id
	 */
	function isProductinfoRelated($id) {
		//����ɾ��У�飬����Ѿ�������ҵ�����������ɾ��
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		$this->tbl_name = "oa_stock_product_info";
		$productinfo = $this->get_d ( $id );
		foreach ( $productRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						throw new Exception ( "ɾ��ʧ��.���ϡ�" . $productinfo ['productName'] . "���Ѿ���" . $num . "����" . $value [0] . "������." );
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
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		$this->tbl_name = "oa_stock_product_info";
		$productinfo = $this->get_d ( $id );
		$msg = "";
		foreach ( $productRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
					if ($num > 0) {
						$msg .= $num . "����" . $value [0] . "������.</br>";
					}
				}
			}
		}
		if (empty ( $msg )) {
			$msg = "���ϡ�" . $productinfo ['productName'] . "��û�б��κ�ҵ��������.";
		} else {
			$msg = "���ϡ�" . $productinfo ['productName'] . "���Ѿ���</br>" . $msg;
		}
		return $msg;
	}
	
	/**
	 *����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$idArr = explode ( ",", $ids );
			$customerTable = $this->tbl_name;
			$logSettringDao = new model_syslog_setting_logsetting ();
			$configDao = new model_stock_productinfo_configurationAdd ();
			foreach ( $idArr as $id ) {
				$this->isProductinfoRelated ( $id );
				$this->tbl_name = $customerTable;
				
				//ɾ�����������־
				$logSettringDao->deleteObjLog ( $this->tbl_name, $this->get_d ( $id ) );
				
				$this->deleteByPk ( $id );
				
				//ɾ����Ӧ������ ���
				$configDao->deleteByHardWareId ( $id );
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}
	
	/**
	 *
	 * ��������id����Ƿ���ڹ�����ҵ����Ϣ
	 * @param $id
	 */
	function checkRelatedResult($id) {
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		//		$this->tbl_name = "oa_stock_product_info";
		foreach ( $productRelationTableArr as $objArr ) {
			if (is_array ( $objArr )) {
				foreach ( $objArr as $key => $value ) {
					$this->tbl_name = $key;
					$num = $this->findCount ( array ($value ['id'] => $id ) );
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
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			if (trim ( $value ['configName'] ) != "") {
				$value [$mainIdName] = $mainIdValue;
				array_push ( $resultArr, $value );
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
		$warr = $this->_db->getArray ( $sql );
		foreach ( $warr as $k => $v ) {
			$warranty = $warr [0] [warranty];
		}
		return $warranty;
	}
	
	/**
	 *
	 * ����ĳ�����͵��µ���ˮ��
	 * @param $proTypeId
	 */
	function produceSerialCode($proTypeId) {
		$resultArr = $this->listBySql ( "select ifnull(max(productCode),'') as maxProductCode from " . $this->tbl_name . " where proTypeId='$proTypeId'" );
		if (! empty ( $resultArr [0] ['maxProductCode'] )) {
			$maxCode = $resultArr [0] ['maxProductCode'];
			return $maxCode + 1;
		} else {
			return "";
		}
	}
	
	/**
	 * ��������������
	 * 
	 */
	function editAccess($productinfo){
		try {
			$this->start_d ();
			$accessDao=new model_stock_productinfo_configurationAdd();
		
			foreach ($productinfoAdd['accessItem'] as $k => $access ) {
				foreach ($productinfoAdd['productItem'] as $j => $productObj){
					if(isset($productObj['isDelTag'])){
					if($productinfoAdd['actType']=="add"){//���Ӹ������
						$editCondition=array("configType"=>"proaccess","hardWareId"=>$productObj['productId'],"configId"=>$access['productId']);
						
						$lastConfig=$accessDao->find($editCondition);
						if(is_array($lastConfig)){
							$lastConfig['configNum']=$access['proNum'];
							$lastConfig['explains']=$access['remark'];
							$accessDao->updateById($lastConfig);
						}else{
							$accessObj=array(
								"configType"=>"proaccess",
								"hardWareId"=>$productObj['productId'],
								"configId"=>$access['productId'],
								"configCode"=>$access['productCode'],
								"configName"=>$access['productName'],
								"configPattern"=>$access['pattern'],
								"configNum"=>$access['proNum'],
								"explains"=>$access['remark'],
							);
							$accessDao->add_d($accessObj);						
						}
					}else{//ɾ�����
						$conditions=array("configType"=>"proaccess","configId"=>$access['productId'],"hardWareId"=>$productObj['productId']);
						$accessDao->delete($conditions);
					}		
					}
				}
			}
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	
	/****************************** S ����ʹ�ò��� **************************************/
	
	/**
	 * ��������
	 */
	function openEsmCanUse_d($id, $typeId) {
		//ʵ������������
		$productTypeDao = new model_stock_productinfo_producttype ();
		try {
			$this->start_d ();
			
			//���±���������״̬
			$sql = "update " . $this->tbl_name . " set esmCanUse = 1 where id = $id";
			$this->_db->query ( $sql );
			
			//��ȡ�����ϼ�
			$typeParentIds = $productTypeDao->getParentIds_d ( $typeId );
			
			//���ϼ��ĳɹ�������״̬
			$productTypeDao->openEsmCanUse_d ( $typeParentIds );
			
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}
	
	/**
	 * ���̹ر�
	 */
	function closeEsmCanUse_d($id, $typeId) {
		//ʵ������������
		$productTypeDao = new model_stock_productinfo_producttype ();
		try {
			$this->start_d ();
			
			//���±���������״̬
			$sql = "update " . $this->tbl_name . " set esmCanUse = 0 where id = $id";
			$this->_db->query ( $sql );
			
			//��ȡ�����ϼ�
			//			$typeParentIds = $productTypeDao->getParentIds_d($typeId);
			

			//���ϼ��ĳɹ�������״̬
			$productTypeDao->closeProType_d ( $typeId, $this );
			
			$this->commit_d ();
			return true;
		} catch ( exception $e ) {
			$this->rollBack ();
			return false;
		}
	}

/****************************** E ����ʹ�ò��� **************************************/
	/**
	 * 
	 * �����ύ״̬
	 * @param $statusName
	 */
	function changeStatus($id,$statusName){
		$status=$this->statusDao->statusEtoK($statusName);
		$state=$this->state[$status];
		return $this->update ( array('id'=>$id), array ('status' => $status,'state'=>$state ) );
	}
	/**
	 * 
	 * ����ȷ��״̬
	 * @param $id
	 * @param $stateId
	 */
	function changeState($id,$stateId){
		$state=$this->state[$stateId];
		if(!empty($state)){
			try {
				$this->start_d ();
				if($state=='��ȷ��')
					$this->addProInfo($id);  //�����ȷ��������Ϣ�����ύ���ݵ�������Ϣ��
			    $this->update ( array('id'=>$id), array ('state'=>$state ) );
				$this->commit_d ();
				return true;
			} catch (Exception $e) {
				echo $e->getMessage();
				$this->rollBack ();
				return false;
			}
		}else{
			return false;
		}
		
	}
	/**
	 * 
	 * �����ύ״̬��ȷ��״̬
	 */
	function changeCommit($id,$statusName,$stateId){
		try{
			$this->start_d ();
			$this->changeStatus($id,$statusName);
			$this->changeState($id,$stateId);
			$this->commit_d ();
			return true;
		}catch (Exception $e){
			$this->rollBack ();
			return false;
		}
	}
	/**
	 * 
	 * ȷ�Ϻ��ύ���ݵ�������Ϣ��
	 * @param $id
	 */
	function addProInfo($id){
		$obj=$this->get_d($id);
		$productinfo=new model_stock_productinfo_productinfo();
		$uploadFile = new model_file_uploadfile_management ();
		$configItem=array();
		$accessItem=array();
		if(is_array($obj['configurations']))
		foreach ($obj['configurations'] as $val){
				unset($val['id']);
				unset($val['hardWareId']);
			if($val['configType']=='proconfig'){			
				$configItem[]=$val;
			}
		if($val['configType']=='proaccess'){
				$accessItem[]=$val;
			}
		}
		unset($obj['configurations']);
		if(!empty($configItem))
			$obj['configItem']=$configItem;
		if(!empty($accessItem))
			$obj['accessItem']=$accessItem;
		/*	echo '<pre>';
			print_r($obj);
			exit;*/
		$id=$obj['id'];
		unset($obj['id']);
		//��������ӵ�������Ϣ��
		$productinfoId=$productinfo->add_d($obj);
		if(!$productinfoId){
			throw new Exception ( "����ʧ�ܣ������Ѵ��ڻ�δ֪����" );
			return false;
		}
		//��������
		$uploadFileRow=$uploadFile->findAll(array('serviceType'=>$this->tbl_name,'serviceId'=>$id));
		if(is_array($uploadFileRow)&&!empty($uploadFileRow))
		foreach ($uploadFileRow as $row){
			unset($row['id']);
			$row['serviceType']=$productinfo->tbl_name;
			$row['serviceId']=$productinfoId;
			if(!$uploadFile->add_d($row)){
				throw new Exception ( "����ʧ�ܣ�������������" );
				return false;
			}
		}
		return true;
	}
	/**
	 * 
	 * ��д����ظ�����������������ϱ��������Ϣ��
	 * @param unknown_type $searchArr
	 * @param unknown_type $checkId
	 */
	function isRepeat($searchArr, $checkId=''){
		  if (parent::isRepeat ( $searchArr, $checkId )) {
				return true;
			}
			$productinfo_=new model_stock_productinfo_productinfo();  //������Ϣ���Ƿ����
			if ($productinfo_->isRepeat ( $searchArr, $checkId)) {
				
				return true;
		}

		return false;
	}
	
	/**
	 * �ʼ�����
	 */
	function thisMail_d($object) {
		include (WEB_TOR . "model/common/mailConfig.php");
		$emailArr = isset ($mailUser['oa_stock_product_info_temp']) ? $mailUser['oa_stock_product_info_temp'] : array (
			'TO_ID'=>'',
			'TO_NAME'=>''
			);
			$nameStr = $emailArr['TO_NAME'];
			$addMsg = $_SESSION['USERNAME'] .'<br>�����Ϲ���<br>
				 ���ϱ�� :��<font color="red">'.$object['productCode'].'</font>��<br>
				 �������� :��<font color="red">'.$object['productName'].'</font>��<br> 
		 		״̬ :��<font color="red">'.$object['state'].'</font> ��<br>';
			$emailDao = new model_common_mail();
			$emailDao->mailClear('���Ϲ���-��������Ϣ��', $emailArr['TO_ID'], $addMsg);
	}
}
?>
