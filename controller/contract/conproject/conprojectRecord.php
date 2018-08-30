<?php
/**
 * @author hoajin
 * @Date 2017��7��17�� 17:05:09
 * @version 1.0
 * @description:��ͬ��Ŀ�� Controller��
 */
class controller_contract_conproject_conprojectRecord  extends controller_base_action {
    function __construct() {
        $this->objName = "conprojectRecord";
        $this->objPath = "contract_conproject";
        parent::__construct();
    }

    /**
     * ��ȡ�°汾��Ϣ
     */
    function c_getVersionInfo(){
        $versionInfo = $this->service->getVersionInfo_d();
        echo util_jsonUtil::encode ( $versionInfo );
    }

    /**
     * ���汾�Ƿ�����
     */
    function c_checkIsUse() {
        echo $this->service->checkIsUsing_d($_POST['storeYearMonth']);
    }

    /**
     * �������� - �����µ���Ŀ���ݸ��µ���ǰ�汾
     */
    function c_updateRecord() {
        set_time_limit(0);

        //��ղ��ر��������
        ob_end_clean();

        // �������ҳ��
        echo file_get_contents(TPL_DIR . '/engineering/records/esmrecord-update.htm');

        flush(); //��������͸��ͻ����������ʹ���������ִ�з������������ JavaScript ����

        // �����������
        $data = $this->service->getConProjectInfo_d();
        $dataLength = count($data);
        $dataKeyLength = $dataLength - 1;

        // �汾���ݻ�ȡ
        $versionInfo = $this->service->getVersionInfo_d();

        foreach ($data as $k => $v) {
            // �����ж� - 100������һ�����
            $isRealSave = ($k != 0 && $k % 50 == 0) || $k == $dataKeyLength;

            // ���벢�ҽ����ݲ����
            $this->service->saveRecord_d($v, $versionInfo, $isRealSave);

            if ($isRealSave) {

                $i = $k + 1;
                $length = round($i / $dataLength * 500);
                echo <<<E
                    <script type="text/javascript">
                        updateProgress("($i/$dataLength)������ɣ�", $length);
                    </script>
E;
                flush(); //��������͸��ͻ����������ʹ���������ִ�з������������ JavaScript ����
            }
        }
    }

    /**
     * ������ݻ�ȡ��Ӧ�İ汾��
     */
    function c_getVersionByYear(){
        $year = isset($_POST['storeYear'])? $_POST['storeYear'] : '';
        if($year != ''){
            $sql = "select version from oa_contract_project_record where storeYear = '{$year}' group by version order by version desc;";
            $result = $this->service->_db->getArray($sql);
            echo util_jsonUtil::encode ( $result );
        }else{
            echo "false";
        }
    }

    /**
     * ��ת���������
     */
    function c_toSetUsing() {
        $yearsOpts = "<option value=''>��ѡ��</option>";
        $thisYear = 2010;
        $currentYear = date("Y");
        while($thisYear <= $currentYear){
            $yearsOpts .= "<option value='{$thisYear}'>{$thisYear}</option>";
            $thisYear += 1;
        }
        $nowVersion = $this->service->getVersionInfo_d();
        $nowVersionCode = $nowVersion['maxVersion'];
        $this->assign('nowVersion', $nowVersionCode);
        $this->assign('yearsOpts', $yearsOpts);
        $this->view('setusing');
    }

    /**
     * ����ǰ�����ݱ���һ����Ϊ���·���������
     */
    function c_setUsing() {
        echo $this->service->setUsing_d($_POST['version'], $_POST['storeYearMonth']);
    }

}
?>