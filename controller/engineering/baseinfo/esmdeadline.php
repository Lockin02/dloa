<?php
/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��־�����
 */
class controller_engineering_baseinfo_esmdeadline extends controller_base_action
{

    function __construct() {
        $this->objName = "esmdeadline";
        $this->objPath = "engineering_baseinfo";
        parent::__construct();
    }

    /**
     * ��ת����Ŀ������¼�б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * �༭
     */
    function c_toEdit() {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * ��ȡ��־���ֹ��ʾ
     */
    function c_getTips() {
        // ��ȡ��ǰʱ��
        $now = microtime(true);
        $day = date('d', $now);

        // ��ȡ��ǰ������
        $thisMonth = $this->service->find(array('month' => date('n', $now)));

        if ($thisMonth['day'] >= $day) {
            $range = $thisMonth['useRange'];
            $startMonth = date('n', strtotime("last month", $now));
            $endMonth = $thisMonth['month'];
            $deadlineDay = $thisMonth['day'];
        } else {
            // ��ȡ�¸�������
            $nextMonth = $this->service->find(array('month' => date('n', strtotime("next month", $now))));
            $range = $nextMonth['useRange'];
            $startMonth = $thisMonth['month'];
            $endMonth = $nextMonth['month'];
            $deadlineDay = $nextMonth['day'];
        }
        if ($range) {
            // �����ǰ�·���1�£�����ݿ�1
            $year = $endMonth == 1 ? date('Y', $now) - 1 : date('Y', $now);

            echo util_jsonUtil::iconvGB2UTF('��ǰ��С������־�����ǡ�' .
                $year . '-' . $startMonth . '-1' . '����' . $startMonth . "�·ݣ�" . $range .
                "����־��ֹ������� " . $endMonth . "��" . $deadlineDay . "��");
        } else {
            echo "";
        }
    }

    /**
     * ���Ի�ȡ��ֹ���ڽӿ�
     */
    function c_getDeadlineInfo() {
        print_r($this->service->getDeadlineInfo_d());
    }

    /**
     * ���Ի�ȡ��ֹ���ڽӿ�
     */
    function c_getDeadMonth() {
        print_r($this->service->getDeadMonth_d());
    }

    /**
     * ���¹�������
     */
    function c_updateESMData() {
        $category = isset($_GET['category']) ? $_GET['category'] : "";
        echo $this->service->updateESMData_d($category);
    }
}