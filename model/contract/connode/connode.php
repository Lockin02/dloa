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
     * 自动处理节点方法
     * @param $contractId
     * @param $dataType
     * @param $process
     * @param string $date
     * @throws Exception
     */
    function autoNode_d($contractId, $dataType, $process, $date = "")
    {

        // 查询是否已初始化过
        $nodes = $this->getContractNodes_d($contractId);

        // 本次处理节点
        $obj = isset($nodes[$dataType]) ? $nodes[$dataType] : $obj = array(
            'contractId' => $contractId,
            'dataType' => $dataType,
            'lastProcess' => 0
        );

        $date = $date ? $date : day_date; // 日期默认值
        $obj = $this->setDate_d($obj, $process, $date); // 设置处理日期

        // 另外一个类型确定
        if ($dataType == 'esm') {
            $proProcess = $nodes['pro']['lastProcess'];
            $esmProcess = $process;
        } else {
            $proProcess = $process;
            $esmProcess = $nodes['esm']['lastProcess'];
        }

        // 合同总节点处理
        $conNode = isset($nodes['con']) ? $nodes['con'] : array('contractId' => $contractId, 'dataType' => 'con', 'lastProcess');
        $conProcess = $this->calConProcess_d($conNode, $proProcess, $esmProcess); // 合同总节点进度获取
        $conNode = $this->setDate_d($conNode, $conProcess, $date); // 设置处理日期

        // 开始处理
        try {
            // 判定是新增还是保存
            $obj['id'] ? $this->edit_d($obj, true) : $this->add_d($obj, true);

            // 判定是新增还是保存
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

        $esmRate = $proRate = 0; // 服务占比、产品占比
        foreach ($productLines as $v) {
            foreach ($v as $vi) {
                // 服务占比叠加
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
     * 节点时间赋值
     * @param $obj
     * @param $process
     * @param $date
     * @return mixed
     */
    function setDate_d($obj, $process, $date)
    {
        // 如果两个进度都小于10，则什么都不做
        if ($process < 10 && $obj['lastProcess'] < 10) {

        } else if ($process > $obj['lastProcess']) { // 如果当前进度大于上次更新进度
            for ($i = 1; $i <= 10; $i++) {
                $nodeProcess = $i * 10;
                if ($nodeProcess <= $process && $nodeProcess >= $obj['lastProcess']) {
                    $obj['node' . $i] = $date;
                }
            }
        } else { // 其他情况
            for ($i = 1; $i <= 10; $i++) {
                $nodeProcess = $i * 10;
                if ($nodeProcess <= $obj['lastProcess'] && $nodeProcess >= $process) {
                    $obj['node' . $i] = $date;
                }
            }
        }

        // 进度赋值
        $obj['lastProcess'] = $process;

        return $obj;
    }

    /**
     * 返回节点日期
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
            } else if ($lastProcess < $process && $lastProcess > 0) {// 如果获取节点的进度百分比比最新百分比大,则返回空
//                $nodeNo = $lastProcess / 10; // 计算节点号
//                return isset($obj['node' . $nodeNo]) ? $obj['node' . $nodeNo] : ""; // 返回最新节点日期
                return '';
            } else {// 否则按所选的百分比来返回节点日期
                $nodeNo = $process / 10; // 计算节点号
                return isset($obj['node' . $nodeNo]) ? $obj['node' . $nodeNo] : ""; // 返回节点日期
            }
        }
    }
}