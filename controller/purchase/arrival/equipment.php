<?php
/**
 * @author Administrator
 * @Date 2011��5��4�� 21:40:04
 * @version 1.0
 * @description:����֪ͨ�������嵥��Ϣ���Ʋ�
 */
class controller_purchase_arrival_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_arrival";
		parent::__construct ();
	 }

	/*
	 * ��ת������֪ͨ�������嵥��Ϣ
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * �������ϵ�id�жϸ����ϵ��Ƿ����ʼ��ص�����
	 */
	function c_getIsQualityBackByArrivalId() {
		$arrivalId = $_POST['arrivalId'];
		$objs = $this->service->findAll(array('arrivalId' => $arrivalId));
		$flag = 0;
		if (is_array($objs)) {
			foreach ($objs as $key => $val) {
				if ($val['isQualityBack'] > 0) {
					$flag = 1;
					break;
				}
			}
		}
		echo $flag;
	}

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        //��ȡ�ʼ�ϸ���
        if(is_array($rows)){
            $ualityereportequitemDao=new model_produce_quality_qualityereportequitem();
            $qualityapplyitemDao=new model_produce_quality_qualityapplyitem();
            foreach($rows as $key=>$val){
                $applyItemRow=$qualityapplyitemDao->getApplyItem_d($val['id']);
                if(is_array($applyItemRow)&&$applyItemRow['status']==0){
                    $rows[$key]['qualitedRate']='100%';
                }else{
                    $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['id'],'ZJSQYDSL');
                    if(is_array($rateRow)){
                        $rows[$key]['qualitedRate']=$rateRow[0]['qualitedRate']."%";
                    }else{
                        $rows[$key]['qualitedRate']='';
                    }
                }
            }
        }
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }
 }
?>