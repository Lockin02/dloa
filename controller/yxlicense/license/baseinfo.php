<?php

/**
 * @author Show
 * @Date 2011��4��9�� ������ 10:51:50
 * @version 1.0
 * @description:license������Ϣ���Ʋ�
 */
class controller_yxlicense_license_baseinfo extends controller_base_action
{

    function __construct() {
        $this->objName = "baseinfo";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /**
     * ��ת��license������Ϣ
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��дtoAdd
     */
    function c_toAdd() {
        $this->view('add');
    }

    /**
     * ��ӷ���
     */
    function c_add() {
        $object = $_POST[$this->objName];
        $data = $this->service->add_d($object);
        if ($data) {
            msg('��ӳɹ�');
        } else {
            msg('���ʧ��');
        }
    }

    /**
     * ��ת������ҳ��
     */
    function c_toCategory() {
        $this->view('category');
    }

    /**
     * c_init
     */
    function c_init() {
        $obj = $this->service->get_d($_GET ['id']);
        $this->assignFunc($obj);
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->assign('isDefault', $this->getDataNameByCode($obj['isDefault']));
            switch ($obj['isUse']) {
                case 0 :
                    $this->assign('isUse', '��');
                    break;
                case 1 :
                    $this->assign('isUse', '��');
                    break;
            }
            $this->display('view');
        } else {
            $this->showDatadicts(array('isDefault' => 'YANDN'), $obj['isDefault']);
            $this->display('edit');
        }
    }

    /**
     * ��дajaxdelete
     */
    function c_ajaxdeletes() {
        echo $this->service->dels($_POST['id']) ? 1 : 0;
    }

    /**
     * ����fleet
     */
    function c_returnHtml() {
        $id = $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $baseinfo = $this->service->getBaseinfo_d($id);
        $dataArr = "";
        foreach ($baseinfo as $v) {
            $name[] = $v['name'];
        }
        $categoryId = $this->service->getCategotyId_d($id);
        foreach ($categoryId as $v) {
            $cate[] = $v['categoryName'];
            $append[] = $v['appendDesc'];
            $lineFeed[] = $v['lineFeed'];
            $showType[] = $v['showType'];
            $isHideTitle[] = $v['isHideTitle'];
            $type[] = $v['type'];
        }

        $object = $this->service->getCategoryItemCount_d($categoryId);
        foreach ($object as $v) {
            foreach ($v as $iv) {
                $count[] = $iv['num'];
            }
        }
        $data = $this->service->getLicenseInfo_d($categoryId);

        $num2 = 0;
        $string = null;
        $strarr2 = <<<EOT
			<table class="form_in_table">
                <tr class="main_head">
                    <td colspan="14">$name[0]</td>
                </tr>
EOT;
        foreach ($data as $val) {
            switch ($showType[$num2]) {
                case '1' :
                    $num = 0;
                    $string = <<<EOT
    					<table class="form_in_table">
								<tr class="main_tr_header">
									<td colspan="14"></td>
								</tr>
EOT;
                    if ($isHideTitle[$num] != '1') {
                        $string .= <<<EOT
    							<tr class="form_text_right">
    								<td colspan="14" id="GSM">$cate[$num2]
EOT;
                        if ($append[$num2] != '') {
                            $string .= <<<EOT
	    								 [ $append[$num2] ] : </td>
	    							</tr>
EOT;
                        } else {
                            $string .= <<<EOT
	    							 : 
	    							</tr>
EOT;
                        }
                    }
                    $string1 = '<tr class="tr_odd">';
                    $string2 = '<tr class="tr_even">';
                    $strArr = null;
                    foreach ($val as $v) {
                        $width = 100;
                        $width = $width / $lineFeed[$num2];
                        $num++;
                        if ($num % $lineFeed[$num2]) {
                            $string1 .= <<<EOT
								<td style="width:$width%">$v[itemName]</td>
EOT;
                            $string2 .= <<<EOT
								<td onclick="dis($v[id])" id="$v[id]"></td>
								<td style="display:none" onclick="dis($v[id])">
								    <span id="$v[id]"></span>
								</td>
EOT;
                        } else {
                            $string1 .= <<<EOT
								<td>$v[itemName]</td>
EOT;
                            $string1 .= '</tr>';
                            $string2 .= <<<EOT
								<td onclick="dis($v[id])" id="$v[id]"></td>
								<td style="display:none" onclick="dis($v[id])">
								    <span id="$v[id]"></span>
								</td>
EOT;
                            $string2 .= '</tr>';
                            $string .= $string1 . $string2;
                            $string1 = '<tr class="tr_odd">';
                            $string2 = '<tr class="tr_even">';
                        }
                    }
                    $num2++;
                    $string1 .= '</tr>';
                    $string2 .= '</tr></table>';
                    $strArr .= $string1;
                    $strArr .= $string2;
                    $string1 = null;
                    $string2 = null;
                    $string .= $strArr;
                    $dataArr .= $string;
                    break;
                case '2' :
                    $string = <<<EOT
    				<table class="form_in_table">
							<tr class="main_tr_header">
								<td colspan="14"></td>
							</tr>
EOT;
                    if ($isHideTitle[$num2] != '1') {
                        $string .= <<<EOT
    						<tr class="form_text_right">
    							<td colspan="14" id="GSM">$cate[$num2] 
EOT;
                        if ($append[$num2] != '') {
                            $string .= <<<EOT
    								 [ $append[$num2] ] : </td>
    							</tr>
EOT;
                        } else {
                            $string .= <<<EOT
    							 : 
    							</tr>    									    							
EOT;
                        }
                    }
                    $keyArr = array();
                    $strArr3 = null;
                    foreach ($val as $v) {
                        if (isset($keyArr[$v['groupName']])) {
                            array_push($keyArr[$v['groupName']], $v);
                        } else {
                            $keyArr[$v['groupName']][0] = $v;
                        }
                    }
                    foreach ($keyArr as $va) {
                        $strlen = count($va);
                        foreach ($va as $k => $v) {
                            if ($type[$num2] == '1') {
                                if (!$k) {
                                    $string1 .= <<<EOT
	    							<tr class="tr_odd">
									<td style="width:10%" rowspan="$strlen">$v[groupName]</td>
EOT;
                                    $string1 .= <<<EOT
									<td style="width:10%" onclick="dis($v[id])" id="$v[id]"></td>
									<td style="display:none" onclick="dis($v[id])">
									    <span id="$v[id]"></span>
									</td>
									<td style="text-align:left;width:10%">$v[itemName]</td>
									<td style="text-align:left;width:70%">$v[appendShow]</td>
		    						</tr>
EOT;
                                } else {
                                    $string1 .= <<<EOT
	    							<tr class="tr_odd">
		    							<td style="width:10%" onclick="dis($v[id])" id="$v[id]"></td>
										<td style="display:none" onclick="dis($v[id])">
									    	<span id="$v[id]"></span>
										</td>
										<td style="text-align:left;width:10%">$v[itemName]</td>
										<td style="text-align:left;width:70%">$v[appendShow]</td>
	    							</tr>
EOT;
                                }
                            } else if ($type[$num2] == '2') {              //��������ı�������ʽ
                                if (!$k) {
                                    $string1 .= <<<EOT
	    							<tr class="tr_odd">
										<td style="width:10%" rowspan="$strlen">$v[groupName]</td>
EOT;
                                    $v[ids] = $v[id] . '0000';
                                    $string1 .= <<<EOT
										<td style="text-align:left;width:25%">$v[itemName]</td>
										<td ondblclick="disAndfocus('GMS-$v[id]')" style="width:20%">
											<span id="GMS-$v[id]_v"></span>
     										<input type="text" class="txtmiddle" id="GMS-$v[id]" onblur="changeInput('GMS-$v[id]')" style="display:none"/>
     									</td>
EOT;
                                    if ($v['appendShow'] != null)     //������չ������ʱ��չ����������Ӧ��д�ı���
                                    {
                                        $string1 .= <<<EOT
										<td style="text-align:left;width:10%">$v[appendShow]</td>
										<td ondblclick="disAndfocus('GMS-$v[ids]')" style="width:35%">
											<span id="GMS-$v[ids]_v"></span>
     										<input type="text" class="txtmiddle" id="GMS-$v[ids]" onblur="changeInput('GMS-$v[ids]')" style="display:none"/>
     									</td>
		    						</tr>
EOT;
                                    } else {                         //����չ��հ��в�����
                                        $string1 .= <<<EOT
										<td></td>
										<td></td>
		    						</tr>
EOT;
                                    }
                                } else {
                                    $v[ids] = $v[id] . '0000';      //���������ı���id��Ϊ��������id+'0000'
                                    $string1 .= <<<EOT
	    							<tr class="tr_odd">		    							
										<td style="text-align:left;width:25%">$v[itemName]</td>
										<td ondblclick="disAndfocus('GMS-$v[id]')" style="width:20%">
											<span id="GMS-$v[id]_v"></span>
     										<input type="text" class="txtmiddle" id="GMS-$v[id]" onblur="changeInput('GMS-$v[id]')" style="display:none"/>
     									</td>
EOT;
                                    if ($v['appendShow'] != null) {
                                        $string1 .= <<<EOT
										<td style="text-align:left;width:10%">$v[appendShow]</td>
										<td ondblclick="disAndfocus('GMS-$v[ids]')" style="width:35%">
											<span id="GMS-$v[ids]_v"></span>
     										<input type="text" class="txtmiddle" id="GMS-$v[ids]" onblur="changeInput('GMS-$v[ids]')" style="display:none"/>
     									</td>
	    							</tr>
EOT;
                                    } else {
                                        $string1 .= <<<EOT
                                            <td></td>
                                            <td></td>
    									</tr>
EOT;
                                    }
                                }
                            }
                        }
                        $strArr3 .= $string1;
                        $string1 = null;
                    }
                    $num2++;
                    $strArr3 .= "<tr class='tr_odd'></tr></table>";
                    $string .= $strArr3;
                    $dataArr .= $string;
                    break;
                case '3' :
                    $dataArr .= $this->service->getFormView_d($val, $categoryId);
                    $num2++;
                    break;
                case '4' :
                    $num = 0;
                    foreach ($val as $v) {
                        $num++;
                    }
                    $string = <<<EOT
    					<table class="form_in_table">
							<tr class="main_tr_header">
								<td colspan="14"></td>
							</tr>
    						<tr class="form_text_right" style="height:35px;">
    							<td ondblclick="disAndfocus('INT-$v[id]')">
    							$cate[$num2]
EOT;
                    if ($append[$num2] != null) {
                        $string1 = <<<EOT
    						 [ $append[$num2] ] :
EOT;
                    } else
                        $string1 = <<<EOT
    						:
EOT;
                    $string2 = <<<EOT
                            ( <span id="INT-$v[id]_v"></span>
                            <input type="text" class="txtmiddle" id="INT-$v[id]" onblur="changeInput('INT-$v[id]')" style="display:none"/> )
                            </td>
                            </td>
                            </tr>
EOT;
                    $strArr = null;
                    $num2++;
                    $string = $string . $string1 . $string2;
                    $string .= $strArr;
                    $dataArr .= $string;
                    break;

                case '5' :
                    $num = 0;
                    $string = <<<EOT
    					<table class="form_in_table" id="tableHead">
							<tr class="main_tr_header">
								<td colspan="14"></td>
							</tr>
EOT;
                    if ($isHideTitle[$num] != '1') {
                        $string .= <<<EOT
    						<tr class="form_text_right">
    							<td colspan="14" id="GSM">$cate[$num2]  							
    						</tr>
EOT;
                    }
                    $string .= <<<EOT
     						<input type="hidden" class="clickTime" value="0"/>     					
EOT;
                    $string1 = '<tr class="tr_odd">';
                    $string2 = '<tr class="tr_even">';
                    $strArr = null;
                    $buttonWidth = 2;
                    $width = 100;
                    $width = ($width - $buttonWidth) / $lineFeed[$num2];
                    $count = $lineFeed[$num2] - 1;
                    $string1 .= <<<EOT
     						<input type="hidden" class="lineFeeds" value="$count" />
     						<td style="width:$buttonWidth%" class="clickBtn"><img id="button1" onclick="addNew();" 
     							src="images/add_item.png" /></td>     							
EOT;
                    foreach ($val as $v) {
                        $string1 .= <<<EOT
     						<input type="hidden" class="tempLine" value="$v[id]" />     							
EOT;
                        $num++;
                        if (($num % $lineFeed[$num2])) {
                            $string1 .= <<<EOT
								<td style="width:$width%">$v[itemName]</td>										
EOT;
                        } else {
                            $string1 .= <<<EOT
								<td>$v[itemName]</td>
EOT;
                            $string1 .= '</tr>';
                            $string2 = '</tr>';
                        }
                    }
                    $string .= $string1 . $string2;
                    $string1 = '<tr>';
                    $string2 = '<tr>';
                    $num2++;
                    $string1 .= '</tr>';
                    $string2 .= '</tr></table>';
                    $strArr .= $string1;
                    $strArr .= $string2;
                    $string1 = null;
                    $string2 = null;
                    $string .= $strArr;
                    $dataArr .= $string;
                    break;

            }
        }
        return util_jsonUtil::iconvGB2UTF($strarr2 .= $dataArr);
    }

    /**
     * �򿪾�̬HTMLҳ��
     */
    function c_returnToHtml() {
        $obj = $this->service->get_d($_POST['id']);
        $phpStr = UPLOADPATH . 'oa_license_baseinfo/' . $obj['oXmlFileName'] . ".html";
        if ($phpStr) {
            $fileSize = filesize($phpStr);
            if ($file = fopen($phpStr, 'r')) {
                $str = util_jsonUtil::iconvGB2UTF(stripslashes(fread($file, $fileSize)));
            } else {
                $str = util_jsonUtil::iconvUTF2GB('�Ҳ����ļ�');
            }
            fclose($file);
        } else {
            $str = util_jsonUtil::iconvUTF2GB('�����ڵ�����');
        }
        echo $str;
    }

    /**
     * ���浽����ҳ��
     */
    function c_saveHtm() {
        $obj = $this->service->get_d($_GET['id']);
        $content = $this->c_returnHtml();
        $content = util_jsonUtil::iconvUTF2GB($content);
        $dir_name = UPLOADPATH . 'oa_license_baseinfo';
        if (!file_exists($dir_name))//�ж��ļ����Ƿ����
        {
            mkdir($dir_name, 0777);
            @chmod($dir_name, 0777);
        }
        $filename = UPLOADPATH . 'oa_license_baseinfo/' . $obj['oXmlFileName'] . ".html";
        file_put_contents($filename, $content);
    }

    /**
     * ��ת������ҳ��
     */
    function c_toCopy() {
        $obj = $this->service->get_d($_GET ['id']);
        $this->assignFunc($obj);
        $this->assign('name', $obj['name']);
        $this->view('copy');
    }

    /**
     * ���Ʒ���
     */
    function c_copy() {
        set_time_limit(0);
        $object = $_POST[$this->objName];
        $fileName = $object[fileName];
        $name = $object[name];
        $name = WEB_TOR . 'oa_attachment/oa_license_baseinfo/' . $name . ".html";
        $filename = WEB_TOR . 'oa_attachment/oa_license_baseinfo/' . $fileName . ".html";
        unset($object['fileName']);
        $data = $this->service->copy_d($object);
        if ($data) {
            copy($filename, $name);
            msg('���Ƴɹ�');
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ajax�ж������Ƿ����
     */
    function c_ajaxDataCode() {
        $searchArr = array("name" => isset ($_GET ['name']) ? $_GET ['name'] : false);
        echo $this->service->isRepeat($searchArr, "") ? 1 : 0;
    }

    /**
     * �ж�ģ���Ƿ�ʹ��
     */
    function c_checkExists() {
        echo $this->service->checkExists_d(util_jsonUtil::iconvUTF2GB($_POST['id'])) ? 1 : 0;
    }

    /**
     * �ر�ģ��
     */
    function c_closeTemp() {
        echo $this->service->closeTemp_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ����ģ��
     */
    function c_openTemp() {
        echo $this->service->openTemp_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ��ȡ���õ�license
     */
    function c_getLicense() {
        $data = $this->service->getLicense_d();
        foreach ($data as $k => $v) {
            $data[$k]['value'] = $v['oXmlFileName'];
        }
        echo util_jsonUtil::encode($data);
    }

    /**
     * ��ȡlicense
     */
    function c_getLicenseAll() {
        $data = $this->service->getLicenseAll_d();
        foreach ($data as $k => $v) {
            $data[$k]['value'] = $v['oXmlFileName'];
        }
        echo util_jsonUtil::encode($data);
    }

    /**
     * ����excel
     */
    function c_exportExcel() {
        $id = $_REQUEST['id'] ? $_REQUEST['id'] : null;
        $baseinfo = $this->service->getBaseinfo_d($id);
        foreach ($baseinfo as $k => $v) {
            $name[] = $v['name'];
        }
        $categoryId = $this->service->getCategotyId_d($id);
        foreach ($categoryId as $k => $v) {
            $cate[] = $v['categoryName'];
            $append[] = $v['appendDesc'];
            $lineFeed[] = $v['lineFeed'];
            $showType[] = $v['showType'];
            $isHideTitle[] = $v['isHideTitle'];
            $type[] = $v['type'];
        }

        $object = $this->service->getCategoryItemCount_d($categoryId);
        foreach ($object as $k => $v) {
            foreach ($v as $k => $v) {
                $count[] = $v['num'];
            }
        }
        $data = $this->service->getLicenseInfo_d($categoryId);
        return $this->service->exportLicense($data, $cate, $append, $lineFeed, $showType, $isHideTitle, $type, $categoryId);
    }
}