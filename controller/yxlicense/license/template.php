<?php

/**
 * @author Show
 * @Date 2011��10��9�� ������ 14:39:27
 * @version 1.0
 * @description:(new)licenseģ ����Ʋ�
 */
class controller_yxlicense_license_template extends controller_base_action
{

    function __construct() {
        $this->objName = "template";
        $this->objPath = "yxlicense_license";
        parent::__construct();
    }

    /**
     * ��ת��(new)licenseģ ��
     */
    function c_page() {
        $this->display('list');
    }

    /**
     * ��ת��addҳ��
     */
    function c_toAdd() {
        $licenseInfo = $this->service->getLicense_d();
        $arr = '';
        foreach ($licenseInfo as $k => $v) {
            $arr .= "<option id='$v[id]' value='$v[oXmlFileName]'>" . $v[name] . "</option>";
        }
        $this->assign('objType', $arr);
        $this->view('add');
    }

    /**
     * ��д��ӷ���
     */
    function c_add() {
        if ($this->service->add_d($_POST[$this->objName])) {
            msgRf('��ӳɹ�');
        } else {
            msgRf('���ʧ��');
        }
    }

    /**
     * ��д�༭����
     */
    function c_edit() {
        if ($this->service->edit_d($_POST[$this->objName])) {
            msgRf('����ɹ�');
        } else {
            msgRf('����ʧ��');
        }
    }

    /**
     * ��ʼ������
     */
    function c_init() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        $obj['extVal'] = stripslashes($obj['extVal']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $licenseInfo = $this->service->getLicenseAll_d();
        $arr = '';
        foreach ($licenseInfo as $k => $v) {
            $arr .= "<option id='$v[id]' value='$v[oXmlFileName]'>" . $v[name] . "</option>";
        }
        $this->assign('objType', $arr);
        if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->display('view');
        } else {
            $this->display('edit');
        }
    }

    /**
     * ��ʼ������
     */
    function c_toViewOnly() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        $obj['extVal'] = stripslashes($obj['extVal']);
        $this->assignFunc($obj);
        $this->display('viewonly');
    }

    /**
     * ����license���ͻ�ȡlicenseģ��
     */
    function c_getTemplateByType() {
        $this->service->getParam($_POST);
        $rows = $this->service->list_d();
        if (!is_array($rows)) return '';
        foreach ($rows as $key => $val) {
            if ($val['extVal']) {
                $rows[$key]['extVal'] = stripslashes($rows[$key]['extVal']);
            }
            if ($val['rowVal']) {
                $rows[$key]['rowVal'] = stripslashes($rows[$key]['rowVal']);
            }
        }
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ����ģ��id��ȡģ��ֵ
     */
    function c_getTemplate() {
        $rs = $this->service->find(array('id' => $_POST['id']));
        if (is_array($rs)) {
            echo util_jsonUtil::encode($rs);
        } else {
            echo 0;
        }
    }
}