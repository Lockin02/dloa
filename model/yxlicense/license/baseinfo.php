<?php

/**
 * @author Show
 * @Date 2011��4��9�� ������ 10:51:50
 * @version 1.0
 * @description:license������Ϣ Model��
 */
class model_yxlicense_license_baseinfo extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_license_baseinfo";
        $this->sql_map = "yxlicense/license/baseinfoSql.php";
        parent::__construct();
    }

    /**
     * ��д��������
     * @param $object
     * @return mixed
     */
    public function add_d($object) {
        // ������ʱ�����һ����������ļ�����
        $object['oXmlFileName'] = md5($object.day_date.rand());
        return parent::add_d($object, true);
    }

    /**
     * ���Ʒ���
     * @param $object
     * @return bool
     */
    function copy_d($object) {
        try {
            $this->start_d();
            $id = $object['id'];
            unset($object['id']);
            $newId = $this->add_d($object, true);

            $categoryDao = new model_yxlicense_license_category();
            $categoryItemDao = new model_yxlicense_license_categoryitem();
            $categoryFormDao = new model_yxlicense_license_categoryform();
            $categoryOptionsDao = new model_yxlicense_license_categoryoptions();
            $categoryTitleDao = new model_yxlicense_license_categorytitle();
            $categoryTipsDao = new model_yxlicense_license_categorytips();

            $data = $categoryDao->getCategotyId_d($id);
            foreach ($data as $k => $v) {
                $itemDatas = $categoryItemDao->getCategoryItemInfo_d($v['id']);

                unset($v['id']);
                $v['licenseId'] = $newId;
                $categoryId = $categoryDao->add_d($v, true);

                foreach ($itemDatas as $k => $v) {

                    $formDatas = $categoryFormDao->getFormData_d($v['id']);

                    unset($v['id']);
                    $v['licenseId'] = $newId;
                    $v['categoryId'] = $categoryId;
                    $itemId = $categoryItemDao->add_d($v, true);

                    foreach ($formDatas as $k => $v) {

                        $optionsDatas = $categoryOptionsDao->getOptionsName_d($v['id']);
                        $titleDatas = $categoryTitleDao->getTitleName_d($v['id']);
                        $tipsDatas = $categoryTipsDao->findAll(array('formId' => $v['id']));

                        unset($v['id']);
                        $v['licenseId'] = $newId;
                        $v['itemId'] = $itemId;
                        $formId = $categoryFormDao->add_d($v, true);

                        $optionIdArr = array();
                        $titleIdArr = array();

                        $tOpId = null;
                        foreach ($optionsDatas as $k => $v) {
                            $tOpId = $v['id'];
                            unset($v['id']);
                            $v['formId'] = $formId;
                            $optionId = $categoryOptionsDao->add_d($v, true);
                            $optionIdArr[$tOpId] = $optionId;
                        }
                        $tTiId = null;
                        foreach ($titleDatas as $k => $v) {
                            $tTiId = $v['id'];
                            unset($v['id']);
                            $v['formId'] = $formId;
                            $titleId = $categoryTitleDao->add_d($v, true);
                            $titleIdArr[$tTiId] = $titleId;
                        }

                        foreach ($tipsDatas as $k => $v) {
                            unset($v['id']);
                            $v['formId'] = $formId;
                            $v['optionId'] = $optionIdArr[$v['optionId']];
                            $v['titleId'] = $titleIdArr[$v['titleId']];
                            $categoryTipsDao->add_d($v, true);
                        }
                    }
                }
            }
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ����id����xml��Ӧ����������
     * @param null $id
     * @return string
     */
    public function getContent_d($id = null) {
        if ($id) {
            $rs = $this->find(array('id' => $id), null, 'phpFileName');
        } else {
            $rs = $this->find(array('isDefault' => '1Y'), null, 'phpFileName');
        }
        if (empty($rs)) {
            return false;
        } else {
            $tmp = UPLOADPATH . $this->tbl_name . '/' . $rs['phpFileName'];
            if ($file = fopen($tmp, 'r')) {
                $str = fread($file, filesize($tmp));
            }
            fclose($file);
            return util_jsonUtil::iconvGB2UTF($str);
        }
    }

    /**
     * ����idɾ������ͬʱɾ���ļ�
     * @param $ids
     * @throws Exception
     */
    public function dels($ids) {
        try {
            $this->delFile_d($ids);
            $this->deletes($ids);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �ļ�ɾ������
     * @param $ids
     */
    function delFile_d($ids) {
        $this->searchArr['ids'] = $ids;
        $rs = $this->listBySqlId('easy_select');
        foreach ($rs as $key => $val) {
            $phpStr = UPLOADPATH . $this->tbl_name . '/' . $val['phpFileName'];
            $xmlStr = UPLOADPATH . $this->tbl_name . '/' . $val['nXmlFileName'];
            unlink($phpStr);
            unlink($xmlStr);
        }
    }

    /**
     * ��ȡ������������
     * @param $obj
     * @return mixed
     */
    function getLicenseInfo_d($obj) {
        $arr = array();
        $categoryItemDao = new model_yxlicense_license_categoryitem();
        foreach ($obj as $v) {
            $data = $categoryItemDao->getCategoryItemInfo_d($v['id']);
            array_push($arr, $data);
        }
        return $arr;
    }

    /**
     * ��ȡ����ID
     * @param $obj
     * @return mixed
     */
    function getCategotyId_d($obj) {
        $categoryDao = new model_yxlicense_license_category();
        return $categoryDao->getCategotyId_d($obj);
    }

    /**
     * ��ȡbaseinfo����
     * @param $id
     * @return mixed
     */
    function getBaseinfo_d($id) {
        return $this->_db->getArray("select name from " . $this->tbl_name . " where id = '$id'");
    }

    /**
     * ��ȡ������������
     * @param $obj
     * @return array
     */
    function getCategoryItemCount_d($obj) {
        $arr = array();
        $categoryItemDao = new model_yxlicense_license_categoryitem();
        foreach ($obj as $k => $v) {
            $data = $categoryItemDao->getCategoryItemCount_d($v['id']);
            array_push($arr, $data);
        }
        return $arr;
    }

    /**
     * ��ȡ��category_form����
     * @param $id
     * @return array
     */
    function getFormData_d($id) {
        $categoryFormDao = new model_yxlicense_license_categoryform();
        return $categoryFormDao->getFormData_d($id);
    }

    /**
     * ��ȡ��category_title����
     * @param $id
     * @return array
     */
    function getTitleName_d($id) {
        $categoryTitleDao = new model_yxlicense_license_categorytitle();
        return $categoryTitleDao->getTitleName_d($id);
    }

    /**
     * @param $id
     * @return array
     */
    function getOptionsName_d($id) {
        $categoryOptionsDao = new model_yxlicense_license_categoryoptions();
        return $categoryOptionsDao->getOptionsName_d($id);
    }

    /**
     * �ж�options���ӦID���ݵ�����
     * @param $id
     * @return array
     **/
    function getOptionsNum_d($id) {
        $categoryFormDao = new model_yxlicense_license_categoryoptions();
        return $categoryFormDao->getOptionsNum_d($id);
    }

    /**
     * ����ʾ
     * @param $obj �ӱ�item����
     * @param $category ��category����
     * @return string
     */
    function getFormView_d($obj, $category) {
        $itemNum = 0;
        foreach ($obj as $k => $v) {                   //��ȡ����ID
            $categoryID = $v['categoryId'];
        }
        foreach ($obj as $kItem => $vItem) {
            $itemNum++;
        }
        $itemWidth = 100 / $itemNum;
        $string = "";
        foreach ($category as $v) {           //��������ͷ
            if ($categoryID == $v['id']) {
                if ($v['isHideTitle'] != '1') {      //����������
                    $string .= <<<EOT
						<table class="form_in_table">
							<tr class="main_tr_header">
								<th align="center" colspan="$itemNum" class="main_tr_header">{$v['categoryName']}</th>
							</tr>
							<tr>
EOT;
                }
            }
        }

        //��ȡForm��ID���ó���Ӧ�ӱ�options������Ŀ
        $string1 = $string2 = '';
        foreach ($obj as $kItem => $vItem) {
            $formData = $this->getFormData_d($vItem['id']);
            $allLength = 1;
            foreach ($formData as $vForm) {
                $allLength = max($allLength, $this->getOptionsNum_d($vForm['id']) + 1);
            }
            $width = 100 / $allLength;   //������  ����ռ�ٷֱ�
            $optionWidth = (100 - $width) / ($allLength - 1);
            $string1 .= <<<EOT
				<td id="left" valign="top" class="innerTd" style="width:$itemWidth%;">
					<table class="form_in_table">
						<tr class="main_tr_header">
							<td colspan="$allLength">{$vItem['itemName']}</td>
						</tr>
EOT;
            //��ʼѭ��������
            foreach ($formData as $kForm => $vForm) {
                //��ȡTips����
                $tipsDatas = $this->getTipsData_d($vForm['id']);
                if ($vForm['isHideTitle'] != '1') {
                    $string2 .= <<<EOT
					<tr class="main_tr_header">
						<td align="left" style="width:$width%;"><font size="2">{$vForm['formName']}</font></td>
EOT;
                }
                $optionsData = $this->getOptionsName_d($vForm['id']);
                foreach ($optionsData as $kOptions => $vOptions) {       //��������formID��ȡ�ӱ�options����
                    if ($vForm['isHideTitle'] != '1') {
                        $string2 .= <<<EOT
							<td align="center" ><font size="2">{$vOptions['optionName']}</font></td>
EOT;
                    }
                }
                $string2 .= <<<EOT
					</tr>
EOT;
                $titleData = $this->getTitleName_d($vForm['id']);//��������formID��ȡ�ӱ�titles[titleName]����
                foreach ($titleData as $kTitle => $vTitle) {

                    $string2 .= <<<EOT
						<tr class="tr_odd">
							<td align="left" style="width:$width%;">{$vTitle['titleName']}</td>
EOT;
                    foreach ($optionsData as $kOptions => $vOptions) {//��������formID��ȡ�ӱ�options����
                        $typeChoose = $vOptions['type'];        //����ѡ�����ͽ��в�ͬ����(��ѡ���ı�)
                        $tempId = $vTitle['id'] . '0000' . $vOptions['id'];#����4��0,��ֹ�����ص�
                        if ($typeChoose == '1') {                     // ��ѡ����
                            if (isset($tipsDatas[$vTitle['id']][$vOptions['id']])) {
                                $tipsMessage = $tipsDatas[$vTitle['id']][$vOptions['id']]['tips'];
                                if (empty($tipsMessage)) {
                                    if ($tipsDatas[$vTitle['id']][$vOptions['id']]['isDisable'] == "1") {
                                        $string2 .= <<<EOT
											<td style="background-color:#cdcdcd;" id="$tempId"><span id="$tempId"></span></td>
EOT;
                                    } else {
                                        $string2 .= <<<EOT
											<td onclick="dis($tempId)" id="$tempId"><span id="$tempId"></span></td>
EOT;
                                    }
                                } else {
                                    if ($tipsDatas[$vTitle['id']][$vOptions['id']]['isDisable'] == "1") {
                                        $string2 .= <<<EOT
											<td id="$tempId" style="background-color:#cdcdcd;border:1px solid #DC143C;" title="$tipsMessage"><span id="$tempId"></span></td>
EOT;
                                    } else {
                                        $string2 .= <<<EOT
											<td onclick="dis($tempId)" id="$tempId" style="border:1px solid #DC143C;" title="$tipsMessage"><span id="$tempId"></span></td>
EOT;
                                    }
                                }
                            } else {
                                $string2 .= <<<EOT
									<td onclick="dis($tempId)" id="$tempId" style="width:$optionWidth%;"><span id="$tempId"></span></td>
EOT;
                            }
                        } else {                                      //  �ı�����
                            if (isset($tipsDatas[$vTitle['id']][$vOptions['id']])) {
                                $tipsMessage = $tipsDatas[$vTitle['id']][$vOptions['id']]['tips'];
                                if (empty($tipsMessage)) {
                                    if ($tipsDatas[$vTitle['id']][$vOptions['id']]['isDisable'] == "1") {
                                        $string2 .= <<<EOT
											<td style="background-color:#cdcdcd;" id="$tempId"><span id="$tempId"></span></td>
EOT;
                                    } else {
                                        $string2 .= <<<EOT
											<td onclick="dis($tempId)" id="$tempId"><span id="$tempId"></span></td>
EOT;
                                    }
                                } else {
                                    if ($tipsDatas[$vTitle['id']][$vOptions['id']]['isDisable'] == "1") {
                                        $string2 .= <<<EOT
											<td id="$tempId" style="background-color:#cdcdcd;border:1px solid #DC143C;" title="$tipsMessage"><span id="$tempId"></span></td>
EOT;
                                    } else {
                                        $string2 .= <<<EOT
											<td onclick="dis($tempId)" id="$tempId" style="border:1px solid #DC143C;" title="$tipsMessage"><span id="$tempId"></span></td>
EOT;
                                    }
                                }
                            } else {
                                $temp['id'] = $tempId;
                                $string2 .= <<<EOT
									<td ondblclick="disAndfocus('GMS-$temp[id]')">
										<span id="GMS-$temp[id]_v"></span>
     									<input type="text" class="txtmiddle" id="GMS-$temp[id]" onblur="changeInput('GMS-$temp[id]')" style="display:none"/>
     								</td>
EOT;
                            }
                        }
                    }
                    $string2 .= <<<EOT
						</tr>
EOT;
                }
            }
            $string2 .= <<<EOT
					</table>
				</td>
EOT;
            $string1 .= $string2;
            $string2 = "";
        }
        $string .= $string1;
        $string .= <<<EOT
				</tr>
			</table><br><br>
EOT;
        return $string;
    }

    /**
     * ��������Ƿ����
     * @param $name
     * @return array
     */
    function checkName_d($name) {
        return $this->_db->getArray("select name from " . $this->tbl_name . " where name='$name'");
    }

    /**
     * ���ģ���Ƿ�ʹ��
     */
    function checkExists_d($id) {
        $obj = $this->find(array('id' => $id));
        //licenseģ������
        $templateDao = new model_yxlicense_license_template();
        //license��������
        $tempkeyDao = new model_yxlicense_license_tempKey();
        if ($templateDao->checkExists_d($obj['oXmlFileName']) || $tempkeyDao->checkExists_d($obj['oXmlFileName'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * �ر�ģ��
     * @param $id
     * @return mixed
     */
    function closeTemp_d($id) {
        return $this->query("update " . $this->tbl_name . " set isUse = 0 where id = '$id'");
    }

    /**
     * ����ģ��
     * @param $id
     * @return mixed
     */
    function openTemp_d($id) {
        try {
            $this->start_d();

            // ����
            $this->update(array('id' => $id), array('isUse' => 1));

            // ����ԭlicense�Ĺ�������
            $obj = $this->find(array('id' => $id),null,'name,oXmlFileName,nXmlFileName');

            // ���²�Ʒ�����ڵ�license
            $this->_db->query("UPDATE oa_goods_properties_item SET licenseTypeCode = '" . $obj['oXmlFileName'] .
                "',licenseTypeName = '" . $obj['name'] . "' WHERE licenseTypeCode = '" . $obj['nXmlFileName'] .
                "'");

            $this->commit_d();
            return true;
        } catch (Exception $E) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ȡlicense�����õ�����
     */
    function getLicense_d() {
        return $this->findAll(array('isUse' => 1), null, 'id,name,oXmlFileName');
    }

    /**
     * ��ȡlicense�����õ�����
     */
    function getLicenseAll_d() {
        return $this->findAll(null, null, 'id,name,oXmlFileName');
    }

    /**
     * ͨ��ID��ȡ��license����
     * @param $id
     * @return mixed
     */
    function getLicenseNames_d($id) {
        return $this->_db->getArray("select name from " . $this->tbl_name . " where id = '$id'");
    }

    /**
     * ��ȡtips���������,ͨ��titleId,optionId�õ���ע�������Ϣ
     * @param $formId
     * @return array
     */
    function getTipsData_d($formId) {
        $categoryFormDao = new model_yxlicense_license_categorytips();
        return $categoryFormDao->getTipsData_d($formId);
    }

    /**
     * ����Excel
     * @param $dataArr
     * @param $cate
     * @param $append
     * @param $lineFeed
     * @param $showType
     * @param $isHideTitle
     * @param $type
     * @param $category
     * @throws Exception
     */
    function exportLicense($dataArr, $cate, $append, $lineFeed, $showType, $isHideTitle, $type, $category) {
        include WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
        include WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
        include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
        include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
        include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
        include WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
        include WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";
        include WEB_TOR . "model/yxlicense/license/categoryform.php";
        include WEB_TOR . "model/yxlicense/license/categoryoptions.php";
        include WEB_TOR . "model/yxlicense/license/categorytitle.php";
        include WEB_TOR . "model/yxlicense/license/categorytips.php";

        $objReader = PHPExcel_IOFactory::createReader('Excel5'); //use excel2007 for 2007 format
        $objPHPExcel = $objReader->load("upfile/����-license����ģ��.xls"); //��ȡģ��
        $excelActiveSheet = $objPHPExcel->getActiveSheet();

        $num = 0;
        $rowNum = 1;
        foreach ($dataArr as $key => $val) {
            switch ($showType[$num]) {
                case '1' :
                    if (!empty($append[$num]))
                        $str = $cate[$num] . " [ " . $append[$num] . " ] :";
                    else
                        $str = $cate[$num] . " : ";
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("00FFFF00");
                    $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $str));
                    $lineLimit = $lineFeed[$num];
                    $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowNum, $lineLimit - 1, $rowNum);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getBorders()->getTop()->getColor()->setARGB('FFFF0000');
                    $rowNum++;
                    $lineNum = 0;
                    foreach ($val as $k => $v) {
                        if ($lineNum == $lineLimit) {
                            $lineNum = 0;
                            $rowNum += 2;
                        }
                        $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                        $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->getStartColor()->setARGB("003399FF");
                        $excelActiveSheet->setCellValueByColumnAndRow($lineNum, $rowNum, iconv("GBK", "utf-8", $v['itemName']));
                        $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                        $lineNum++;
                    }
                    $rowNum += 5;
                    $num++;
                    break;
                case '2' :
                    if ($isHideTitle[$num] != '1') {
                        if ($append[$num] == '') {
                            $str = $cate[$num] . " : ";
                        } else {
                            $str = $cate[$num] . " [ " . $append[$num] . " ] : ";
                        }
                    }
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("00FFFF00");
                    $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $str));
                    if ($type[$num] == '1') {
                        $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowNum, 3, $rowNum);
                    } else {
                        $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowNum, 4, $rowNum);
                    }
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                    $rowNum++;
                    $keyArr = array();
                    foreach ($val as $k => $v) {
                        if (isset($keyArr[$v['groupName']])) {
                            array_push($keyArr[$v['groupName']], $v);
                        } else {
                            $keyArr[$v['groupName']][0] = $v;
                        }
                    }
                    foreach ($keyArr as $ke => $va) {
                        $rowStart = $rowNum;
                        foreach ($va as $k => $v) {
                            if ($type[$num] == '1') {
                                if (!$k) {
                                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("003399FF");
                                    $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $v['groupName']));
                                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    $excelActiveSheet->getStyleByColumnAndRow(2, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(2, $rowNum)->getFill()->getStartColor()->setARGB("0033FF66");
                                    $excelActiveSheet->setCellValueByColumnAndRow(2, $rowNum, iconv("GBK", "utf-8", $v['itemName']));
                                    $excelActiveSheet->getStyleByColumnAndRow(2, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    $excelActiveSheet->setCellValueByColumnAndRow(3, $rowNum, iconv("GBK", "utf-8", $v['appendShow']));

                                    $rowNum++;
                                } else {
                                    $excelActiveSheet->getStyleByColumnAndRow(2, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(2, $rowNum)->getFill()->getStartColor()->setARGB("0033FF66");
                                    $excelActiveSheet->setCellValueByColumnAndRow(2, $rowNum, iconv("GBK", "utf-8", $v['itemName']));
                                    $excelActiveSheet->getStyleByColumnAndRow(2, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    $excelActiveSheet->setCellValueByColumnAndRow(3, $rowNum, iconv("GBK", "utf-8", $v['appendShow']));
                                    $rowNum++;
                                }
                            } else if ($type[$num] == '2') {
                                if (!$k) {
                                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("003399FF");
                                    $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $v['groupName']));
                                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getFill()->getStartColor()->setARGB("0033FF66");
                                    $excelActiveSheet->setCellValueByColumnAndRow(1, $rowNum, iconv("GBK", "utf-8", $v['itemName']));
                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    $excelActiveSheet->getStyleByColumnAndRow(3, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(3, $rowNum)->getFill()->getStartColor()->setARGB("00FFCC99");
                                    $excelActiveSheet->setCellValueByColumnAndRow(3, $rowNum, iconv("GBK", "utf-8", $v['appendShow']));
                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    $rowNum++;
                                } else {
                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getFill()->getStartColor()->setARGB("0033FF66");
                                    $excelActiveSheet->setCellValueByColumnAndRow(1, $rowNum, iconv("GBK", "utf-8", $v['itemName']));
                                    $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                                    if ($v['appendShow'] != null) {
                                        $excelActiveSheet->getStyleByColumnAndRow(3, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                        $excelActiveSheet->getStyleByColumnAndRow(3, $rowNum)->getFill()->getStartColor()->setARGB("00FFCC99");
                                        $excelActiveSheet->setCellValueByColumnAndRow(3, $rowNum, iconv("GBK", "utf-8", $v['appendShow']));
                                        $excelActiveSheet->getStyleByColumnAndRow(1, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                                    }
                                    $rowNum++;
                                }
                            }
                        }
                        $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowStart, 0, $rowNum - 1);
                    }
                    $rowNum += 3;
                    $num++;
                    break;
                case '3' :
                    foreach ($val as $k => $v) {                      //��ȡ����ID
                        $categoryID = $v['categoryId'];
                    }

                    $rowStart = $rowNum;//��¼һ��ʼ����������
                    foreach ($category as $k => $v) {           //��������ͷ
                        if ($categoryID == $v['id']) {
                            $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                            $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("00FFFF00");
                            $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $v['categoryName']));
                            $rowNum++;
                        }

                    }
                    //��ȡForm��ID���ó���Ӧ�ӱ�options������Ŀ
                    $lineNum = 0;
                    $line = 0;
                    $rowAll = 0;//��¼item�ܹ�ռ������
                    $rowMax = 0;//���item�е��������
                    $linecount = 0;//��¼�ܹ���������
                    $count = 0;//��¼����itemname ����

                    foreach ($val as $kItem => $vItem) {
                        $formData = self::getFormData_d($vItem['id']);//��������ID��ȡ�ӱ�form������

                        if (!empty($formData)) {
                            foreach ($formData as $kForm => $vForm) {
                                $numArr = self::getOptionsNum_d($vForm['id']);
                            }
                            if (!empty($numArr)) {
                                foreach ($numArr as $kNum => $vNum) {
                                    foreach ($vNum as $kNum2 => $vNum2) {
                                        $line = $vNum2[$kNum2];
                                    }
                                }
                                $line++;
                                $linecount += $line;
                            }      //$line+1Ϊ�����������
                            $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                            $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->getStartColor()->setARGB("0033FF66");
                            $excelActiveSheet->setCellValueByColumnAndRow($lineNum, $rowNum, iconv("GBK", "utf-8", $vItem['itemName']));
                            $excelActiveSheet->mergeCellsByColumnAndRow($lineNum, $rowNum, $lineNum + $line - 1, $rowNum);
                            $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);

                            $rowNum++;
                            $rowAll++;
                            $formCol = 0;
                            foreach ($formData as $kForm => $vForm) {
                                //��ȡTips����
                                $tipsDatas = $this->getTipsData_d($vForm['id']);
                                if ($vForm['isHideTitle'] != '1') {
                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $formCol, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $formCol, $rowNum)->getFill()->getStartColor()->setARGB("003399FF");
                                    $excelActiveSheet->setCellValueByColumnAndRow($lineNum + $formCol, $rowNum, iconv("GBK", "utf-8", $vForm['formName']));
                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $formCol, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                                    $formCol++;
                                    $optionsData = self::getOptionsName_d($vForm['id']);

                                    foreach ($optionsData as $kOptions => $vOptions) {       //��������formID��ȡ�ӱ�options����
                                        $excelActiveSheet->getStyleByColumnAndRow($lineNum + $formCol, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                        $excelActiveSheet->getStyleByColumnAndRow($lineNum + $formCol, $rowNum)->getFill()->getStartColor()->setARGB("003399FF");
                                        $excelActiveSheet->setCellValueByColumnAndRow($lineNum + $formCol, $rowNum, iconv("GBK", "utf-8", $vOptions['optionName']));
                                        $excelActiveSheet->getStyleByColumnAndRow($lineNum + $formCol, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                                        $formCol++;
                                    }
                                    $rowNum++;
                                    $rowAll++;
                                }
                                $titleData = self::getTitleName_d($vForm['id']);     //��������formID��ȡ�ӱ�titles[titleName]����

                                foreach ($titleData as $kTitle => $vTitle) {
                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->getStartColor()->setARGB("00FFCC99");
                                    $excelActiveSheet->setCellValueByColumnAndRow($lineNum, $rowNum, iconv("GBK", "utf-8", $vTitle['titleName']));

                                    $col = 1;
                                    foreach ($optionsData as $kOptions => $vOptions) {
                                        $tempId = $vTitle['id'] . '0000' . $vOptions['id'];#����4��0,��ֹ�����ص�
                                        if (isset($tipsDatas[$vTitle['id']][$vOptions['id']])) {
                                            $tipsMessage = $tipsDatas[$vTitle['id']][$vOptions['id']]['tips'];
                                            if (empty($tipsMessage)) {
                                                if ($tipsDatas[$vTitle['id']][$vOptions['id']]['isDisable'] == "1") {
                                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $col, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $col, $rowNum)->getFill()->getStartColor()->setARGB("00666666");
                                                }
                                            } else {
                                                if ($tipsDatas[$vTitle['id']][$vOptions['id']]['isDisable'] == "1") {
                                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $col, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                                                    $excelActiveSheet->getStyleByColumnAndRow($lineNum + $col, $rowNum)->getFill()->getStartColor()->setARGB("00666666");
                                                    $excelActiveSheet->getCommentByColumnAndRow($lineNum + $col, $rowNum)->setAuthor('PHPExcel');
                                                    $excelActiveSheet->getCommentByColumnAndRow($lineNum + $col, $rowNum)->getText()->createTextRun($tipsMessage);
                                                } else {
                                                    $excelActiveSheet->getCommentByColumnAndRow($lineNum + $col, $rowNum)->setAuthor('PHPExcel');
                                                    $excelActiveSheet->getCommentByColumnAndRow($lineNum + $col, $rowNum)->getText()->createTextRun($tipsMessage);
                                                }
                                            }
                                        }
                                        $col++;
                                    }
                                    $rowNum++;
                                    $rowAll++;
                                }
                                $formCol = 0;
                            }
                            $rowNum -= $rowAll;
                            $rowMax = max($rowMax, $rowAll);
                            $lineNum += $line;
                        } else {
                            $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                            $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->getStartColor()->setARGB("0033FF66");
                            $excelActiveSheet->setCellValueByColumnAndRow($lineNum, $rowNum, iconv("GBK", "utf-8", $vItem['itemName']));
                            $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                            $lineNum++;
                        }
                        $count++;
                    }
                    //�ϲ���ͷ��Ԫ�񲢾���
                    if ($linecount != 0) {
                        $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowStart, $linecount - 1, $rowStart);
                        $excelActiveSheet->getStyleByColumnAndRow(0, $rowStart)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                    } else {
                        $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowStart, lineNum, $rowStart);
                        $excelActiveSheet->getStyleByColumnAndRow(0, $rowStart)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                    }
                    $rowNum += $rowMax;
                    $rowNum += 3;
                    $num++;
                    break;
                case '4' :
                    if (!empty($append[$num]))
                        $str = $cate[$num] . " [ " . $append[$num] . " ] : ( )";
                    else
                        $str = $cate[$num] . " : ( )";
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("00FFFF00");
                    $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $str));
                    $lineLimit = $lineFeed[$num];
                    $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowNum, $lineLimit - 1, $rowNum);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getBorders()->getTop()->getColor()->setARGB('FFFF0000');
                    $rowNum++;

                    $rowNum += 3;
                    $num++;
                    break;
                case '5' :
                    if (!empty($append[$num]))
                        $str = $cate[$num] . " [ " . $append[$num] . " ] :";
                    else
                        $str = $cate[$num] . " : ";
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getFill()->getStartColor()->setARGB("00FFFF00");
                    $excelActiveSheet->setCellValueByColumnAndRow(0, $rowNum, iconv("GBK", "utf-8", $str));
                    $lineLimit = $lineFeed[$num];
                    $excelActiveSheet->mergeCellsByColumnAndRow(0, $rowNum, $lineLimit - 1, $rowNum);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
                    $excelActiveSheet->getStyleByColumnAndRow(0, $rowNum)->getBorders()->getTop()->getColor()->setARGB('FFFF0000');
                    $rowNum++;
                    $lineNum = 0;
                    foreach ($val as $k => $v) {
                        if ($lineNum == $lineLimit) {
                            $lineNum = 0;
                            $rowNum += 2;
                        }
                        $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->setFillType(PHPExcel_Style_Fill :: FILL_SOLID);
                        $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getFill()->getStartColor()->setARGB("003399FF");
                        $excelActiveSheet->setCellValueByColumnAndRow($lineNum, $rowNum, iconv("GBK", "utf-8", $v['itemName']));
                        $excelActiveSheet->getStyleByColumnAndRow($lineNum, $rowNum)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment :: HORIZONTAL_CENTER);
                        $lineNum++;
                    }
                    $rowNum += 5;
                    $num++;
                    break;
            }
        }
        $objWriter = new PHPExcel_Writer_Excel5 ($objPHPExcel);
        //�������
        ob_end_clean();
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="' . "licenseԤ��ģ��.xls" . '"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }
}