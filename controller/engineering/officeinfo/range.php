<?php

/*���η�Χ���Ʋ���
 * Created on 2010-12-1
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_engineering_officeinfo_range extends controller_base_action
{
    function __construct()
    {
        $this->objName = "range";
        $this->objPath = "engineering_officeinfo";
        parent:: __construct();
    }

    //����ʡ�ݱ��뷵�ذ��´���Ϣ
    function c_getOfficeInfo()
    {
        echo util_jsonUtil::encode($this->service->find(array('proCode' => $_POST['proCode'])));
    }

    //����ʡ��id���ذ��´���Ϣ
    //�˷����Ѿ�ͣ��,��Ҫʹ�ô�esmproject getRangeId����
    function c_getOfficeInfoForId()
    {
        echo $this->service->getRangeId_d($_POST['provinceId'], $_POST['productLine'], $_POST['officeId']);
    }

    //����ʡ��ID�Ͳ���ID��ѯ������Ϣ
    function c_getRangeByProvinceAndDept()
    {
        echo $this->service->getRangeByProvinceAndDept_d($_POST['provinceId'], $_POST['deptId']);
    }

    /**
     * ���η�Χ��ѯ
     */
    function c_showRange()
    {
        // Ȩ�޻�ȡ
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        // ���´�Ȩ�޲���
        $sysLimit = $sysLimit['���´�'];
        // ʡ��Ȩ��
        $proLimit = $sysLimit['ʡ��Ȩ��'];
        // ��չ�Ĳ�ѯ����
        $whereSql = "";

        // ���û��ȫ��Ȩ�ޣ���ô����ǰӵ�е�Ȩ�޽�������
        if (strpos($sysLimit, ';;') !== false || strpos($proLimit, ';;') !== false) {

        } else {
            // û��ȫ��Ȩ�޵�ʱ����ƥ�����ñ��е�����
            $whereSql = "AND FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.mainManagerId)
                OR FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.managerId)
                OR FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.headId)";

            // ������´�Ȩ�޲�Ϊ�գ�������ⲿ�ֵ�����
            if (!empty($sysLimit)) {
                $whereSql .= " OR c.officeId IN(" . util_jsonUtil::strBuild($sysLimit) . ")";
            }

            // ���ʡ��Ȩ�޲�Ϊ�գ�������ⲿ�ֵ�����
            if (!empty($proLimit)) {
                $whereSql .= " OR c.proName IN(" . util_jsonUtil::strBuild($proLimit) . ")";
            }
        }

        // ��ѯ����
        $sql = "SELECT b.moduleName, b.feeDeptId, c.officeId, c.officeName, c.proName, c.productLine, c.productLineName
            FROM oa_esm_office_range c LEFT JOIN oa_esm_office_baseinfo b ON c.officeId = b.id
            WHERE c.state = 0
            $whereSql
            ORDER BY c.officeId, c.proName";
        $rows = $this->service->_db->getArray($sql);

        $rst = array(); // ��������

        if (!empty($rows) && isset($_REQUEST['t']) && $_REQUEST['t'] == 2) {
            $officeCache = array(); // �ϸ�����
            foreach ($rows as $v) {
                if ($v['proName'] && !in_array($v['officeId'], $officeCache)) {
                    $rst[] = array(
                        'officeId' => $v['officeId'],
                        'officeName' => $v['officeName'],
                        'feeDeptId' => $v['feeDeptId'],
                        'moduleName' => $v['moduleName'],
                        'productLineName' => $v['productLineName'],
                        'productLine' => $v['productLine'],
                        'ext' => 1
                    );
                    $officeCache[] = $v['officeId'];
                }
                $rst[] = $v;
            }
        } else {
            $rst = $rows;
        }

        if (isset($_REQUEST['needCountRow']) && $_REQUEST['needCountRow'] && !empty($rst)) {
            $rst[] = array(
                'officeName' => '�ϼ�',
                'officeId' => 'countRow'
            );
        }

        echo util_jsonUtil::encode($rst);
    }
}