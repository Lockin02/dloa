<?php
/*
 * Created on 2012-7-30
 * Created by Show
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_baseinfo_esmcommon extends model_base {

    function __construct() {
//        $this->tbl_name = "";
//        $this->sql_map = "engineering/baseinfo/epersonSql.php";
        parent :: __construct();
    }

    /**
     * ��ʾ����
     */
    function toViewConfig_d(){
        include (WEB_TOR."model/common/commonConfig.php");
        echo ' =================��Ŀ���ò���================== <br/>';
        //����ģ������
        $COSTMODEL = isset($COSTMODEL) ? $COSTMODEL : '�޴��������';
    	echo '��ǰ����ģ��Ϊ: ',$COSTMODEL['name'] , ", id :" , $COSTMODEL['id'] , "<br/>";
        //���Կ��Ѷ�Ӧ�ķ�������
        $CARDCOSTTYPE = isset($CARDCOSTTYPE) ? $CARDCOSTTYPE : '�޴��������';
        echo '��ǰ���Կ��Ѷ�Ӧ�ķ�������Ϊ: ',$CARDCOSTTYPE['name'] , ", id :" , $CARDCOSTTYPE['id'] , "<br/>";
        //��Ա���޶�Ӧ�ķ�������
        $TEMPPERSONCOSTTYPE = isset($TEMPPERSONCOSTTYPE) ? $TEMPPERSONCOSTTYPE : '�޴��������';
        echo '��ǰ��Ա���޶�Ӧ�ķ�������Ϊ: ',$TEMPPERSONCOSTTYPE['name'] , ", id :" , $TEMPPERSONCOSTTYPE['id'] , "<br/>";
        //�⳵�Ѷ�Ӧ�ķ�������
        $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE : '�޴��������';
        echo '��ǰ�⳵�Ѷ�Ӧ�ķ�������Ϊ: ',$CARTRAVELFEECOSTTYPE['name'] , ", id :" , $CARTRAVELFEECOSTTYPE['id'] , "<br/>";
        //�ͷѶ�Ӧ�ķ�������
        $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE : '�޴��������';
        echo '��ǰ�ͷѶ�Ӧ�ķ�������Ϊ: ',$CARFUELFEECOSTTYPE['name'] , ", id :" , $CARFUELFEECOSTTYPE['id'] , "<br/>";
        //·�Ŷ�Ӧ�ķ�������
        $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE : '�޴��������';
        echo '��ǰ·�Ŷ�Ӧ�ķ�������Ϊ: ',$CARROADFEECOSTTYPE['name'] , ", id :" , $CARROADFEECOSTTYPE['id'] , "<br/>";
        //ͣ���ѵķ�������
        $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE : '�޴��������';
        echo '��ǰ·�Ŷ�Ӧ�ķ�������Ϊ: ',$CARPARKINGFEECOSTTYPE['name'] , ", id :" , $CARPARKINGFEECOSTTYPE['id'] , "<br/>";

        echo '<br/> =================���̹���������Ϣ================== <br/>';
        //�Ƿ�������Ա��Ŀ����
        $ISADDPERSONPROJECTRECORD = isset($ISADDPERSONPROJECTRECORD) && $ISADDPERSONPROJECTRECORD == 1 ? $ISADDPERSONPROJECTRECORD : 0;
        $thisResult = $ISADDPERSONPROJECTRECORD == 1? '��' : '��';
        echo '��Ŀ�ر�ʱ������Ա��Ŀ���� : ', $thisResult ," <span class='blue'>[����δ���]</span> <br/>";
    }
}
?>
