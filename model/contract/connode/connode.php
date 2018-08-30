<?php

/**
 * Class model_contract_connode_connode
 */
class model_contract_connode_connode extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_node";
        $this->sql_map = "contract/connode/connodeSql.php";
        parent::__construct();
    }

    /**
     * �Զ�����ڵ㷽��
     * @param $contractId
     * @param $dataType
     * @param $process
     * @param string $date
     * @throws Exception
     */
    function autoNode_d($contractId, $dataType, $process, $date = "")
    {

        // ��ѯ�Ƿ��ѳ�ʼ����
        $nodes = $this->getContractNodes_d($contractId);

        // ���δ���ڵ�
        $obj = isset($nodes[$dataType]) ? $nodes[$dataType] : $obj = array(
            'contractId' => $contractId,
            'dataType' => $dataType,
            'lastProcess' => 0
        );

        $date = $date ? $date : day_date; // ����Ĭ��ֵ
        $obj = $this->setDate_d($obj, $process, $date); // ���ô�������

        // ����һ������ȷ��
        if ($dataType == 'esm') {
            $proProcess = $nodes['pro']['lastProcess'];
            $esmProcess = $process;
        } else {
            $proProcess = $process;
            $esmProcess = $nodes['esm']['lastProcess'];
        }

        // ��ͬ�ܽڵ㴦��
        $conNode = isset($nodes['con']) ? $nodes['con'] : array('contractId' => $contractId, 'dataType' => 'con', 'lastProcess');
        $conProcess = $this->calConProcess_d($conNode, $proProcess, $esmProcess); // ��ͬ�ܽڵ���Ȼ�ȡ
        $conNode = $this->setDate_d($conNode, $conProcess, $date); // ���ô�������

        // ��ʼ����
        try {
            // �ж����������Ǳ���
            $obj['id'] ? $this->edit_d($obj, true) : $this->add_d($obj, true);

            // �ж����������Ǳ���
            $conNode['id'] ? $this->edit_d($conNode, true) : $this->add_d($conNode, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $conNode
     * @param $proProcess
     * @param $esmProcess
     * @return float
     */
    function calConProcess_d($conNode, $proProcess, $esmProcess)
    {
        $productDao = new model_contract_contract_product();
        $productLines = $productDao->getProductLineDetails_d($conNode['contractId']);

        $esmRate = $proRate = 0; // ����ռ�ȡ���Ʒռ��
        foreach ($productLines as $v) {
            foreach ($v as $vi) {
                // ����ռ�ȵ���
                if ($vi['proTypeId'] == 17) {
                    $esmRate = bcadd($esmRate, $vi['productLineRate'], 2);
                } else {
                    $proRate = bcadd($proRate, $vi['productLineRate'], 2);
                }
            }
        }
        return round(bcadd(bcmul($proProcess, bcdiv($proRate, 100, 4), 4), bcmul($esmProcess, bcdiv($esmRate, 100, 4), 4), 4), 2);
    }

    /**
     * @param $contractId
     * @return array
     */
    function getContractNodes_d($contractId)
    {
        $obj = $this->findAll(array('contractId' => $contractId));

        if (empty($obj)) {
            return array();
        } else {
            $rs = array();
            foreach ($obj as $v) {
                $rs[$v['dataType']] = $v;
            }
            return $rs;
        }
    }

    /**
     * �ڵ�ʱ�丳ֵ
     * @param $obj
     * @param $process
     * @param $date
     * @return mixed
     */
    function setDate_d($obj, $process, $date)
    {
        // ����������ȶ�С��10����ʲô������
        if ($process < 10 && $obj['lastProcess'] < 10) {

        } else if ($process > $obj['lastProcess']) { // �����ǰ���ȴ����ϴθ��½���
            for ($i = 1; $i <= 10; $i++) {
                $nodeProcess = $i * 10;
                if ($nodeProcess <= $process && $nodeProcess >= $obj['lastProcess']) {
                    $obj['node' . $i] = $date;
                }
            }
        } else { // �������
            for ($i = 1; $i <= 10; $i++) {
                $nodeProcess = $i * 10;
                if ($nodeProcess <= $obj['lastProcess'] && $nodeProcess >= $process) {
                    $obj['node' . $i] = $date;
                }
            }
        }

        // ���ȸ�ֵ
        $obj['lastProcess'] = $process;

        return $obj;
    }

    /**
     * ���ؽڵ�����
     * @param $contractId
     * @param $process
     * @param string $dataType
     * @return string
     */
    function getTDay_d($contractId, $process, $dataType = 'con')
    {
        $obj = $this->find(array('contractId' => $contractId, 'dataType' => $dataType));
        $lastProcess = intval($obj['lastProcess'] / 10) * 10;

        if (empty($obj)) {
            return '';
        } else {
            if ($lastProcess <= 0) {
                return '';
            } else if ($lastProcess < $process && $lastProcess > 0) {// �����ȡ�ڵ�Ľ��Ȱٷֱȱ����°ٷֱȴ�,�򷵻ؿ�
//                $nodeNo = $lastProcess / 10; // ����ڵ��
//                return isset($obj['node' . $nodeNo]) ? $obj['node' . $nodeNo] : ""; // �������½ڵ�����
                return '';
            } else {// ������ѡ�İٷֱ������ؽڵ�����
                $nodeNo = $process / 10; // ����ڵ��
                return isset($obj['node' . $nodeNo]) ? $obj['node' . $nodeNo] : ""; // ���ؽڵ�����
            }
        }
    }
}