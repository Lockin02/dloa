<?php
define('objName', 'objName_change');
define('objTable', 'objTable_change');
define('objDao', 'objDao_change');
//������Ϣ
$uploadFileArr = array(
    objName => "������Ϣ",
    objDao => "file_uploadfile_management",
    "relationField" => "serviceId",
    "originalName" => "��������"
);
//ע���������¼����Ϣ
$logObjArr = array("purchasecontract" =>
    array(
        objName => "�ɹ�����",
        //objTable=>"oa_purch_apply_basic",//����������
        objDao => "purchase_contract_purchasecontract",//dao·��ע��
        "mainTable" => "oa_purchase_contract_changlog", //�����¼��������
        "detailTable" => "oa_purchase_contract_changedetail",//�����¼��ϸ������
        //��������ֶ�ע��
        "register" => array(
            "dateHope" => "Ԥ�Ƶ�������",
            "dateFact" => "�����������",
            "allMoney" => "�����ܽ��",
            "billingType" => "code��Ʊ����",
            "billingTypeName" => "��Ʊ����",
            "paymentCondition" => "code��������",
            "paymentConditionName" => "��������",
            "paymentType" => "code��������",
            "paymentTypeName" => "��������",
            "payRatio" => "Ԥ��������",
            "signStatus" => "code��ͬǩԼ״̬",
            "signStatus_cn" => "��ͬǩԼ״̬",
            "instruction" => "�ɹ�˵�� ",
            "suppName" => "��Ӧ��",
            "suppId" => "��Ӧ��ID",
            "suppTel" => "��Ӧ�����绰",
            "suppAccount" => "��Ӧ�������˺�",
            "suppBankName" => "��������",
            "remark" => "��ע",
            "equs" => array(
                objName => "�ɹ�������ϸ",
                //objTable=>"oa_purch_apply_equ",//���������ϸ�����
                objDao => "purchase_contract_equipment",//dao·��ע��
                objField => "productName",
                "relationField" => "basicId",//���������ֶ���
                "amountAll" => "�ɹ���������",
                "productName" => "��������",
                "dateHope" => "��������ʱ�� ",
                "dateIssued" => "����ʱ�� ",
                "applyPrice" => "��˰����",
                "price" => "����",
                "taxRate" => "˰��",
                "moneyAll" => "���Ͻ��"
            ),
            "uploadFiles" => array(
                uploadFilesTypeArr => "'oa_purch_apply_basic'",
                objName => "������Ϣ",
                objDao => "file_uploadfile_management",
                "relationField" => "serviceId",
                "originalName" => "��������"
            )
        )
    ), "purchasesign" =>
    array(
        objName => "�ɹ�����",
        //objTable=>"oa_purch_apply_basic",//����������
        objDao => "purchase_contract_purchasecontract",//dao·��ע��
        "mainTable" => "oa_purchase_apply_signlog", //�����¼��������
        "detailTable" => "oa_purchase_apply_signdetail",//�����¼��ϸ������
        //��������ֶ�ע��
        "register" => array(
            "dateHope" => "Ԥ�Ƶ�������",
            "dateFact" => "�����������",
            "allMoney" => "�����ܽ��",
            "billingType" => "code��Ʊ����",
            "billingTypeName" => "��Ʊ����",
            "paymentCondition" => "code��������",
            "paymentConditionName" => "��������",
            "paymentType" => "code��������",
            "paymentTypeName" => "��������",
            "payRatio" => "Ԥ��������",
            "signStatus" => "code��ͬǩԼ״̬",
            "signStatus_cn" => "��ͬǩԼ״̬",
            "instruction" => "�ɹ�˵�� ",
            "suppAccount" => "��Ӧ�������˺�",
            "suppBankName" => "��������",
            "remark" => "��ע",
            "equs" => array(
                objName => "�ɹ�������ϸ",
                //objTable=>"oa_purch_apply_equ",//���������ϸ�����
                objDao => "purchase_contract_equipment",//dao·��ע��
                objField => "productName",
                "relationField" => "basicId",//���������ֶ���
                "amountAll" => "�ɹ���������",
                "productName" => "��������",
                "dateHope" => "��������ʱ�� ",
                "dateIssued" => "����ʱ�� ",
                "applyPrice" => "����"
            ),
            "uploadFiles" => array(
                uploadFilesTypeArr => "'oa_purch_apply_basic'",
                objName => "������Ϣ",
                objDao => "file_uploadfile_management",
                "relationField" => "serviceId",
                "originalName" => "��������"
            )
        )
    ),
    "purchasetask" =>
        array(
            objName => "�ɹ�����",
            objDao => "purchase_task_basic",//dao·��ע��
            "mainTable" => "oa_purchase_task_changlog", //�����¼��������
            "detailTable" => "oa_purchase_task_changedetail",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "dateHope" => "ϣ���������",
                "sendName" => "������",
                "sendUserId" => "������ID",
                "instruction" => "�ɹ�˵�� ",
                "remark" => "��ע",
                "equment" => array(
                    objName => "�ɹ������嵥",
                    objDao => "purchase_task_equipment",//dao·��ע��
                    objField => "productName",
                    "relationField" => "basicId",//���������ֶ���
                    "amountAll" => "��������",
                    "productName" => "��������",
                    "dateHope" => "ϣ������ʱ�� "
                )
            )
        ),
    "purchaseplan" =>
        array(
            objName => "�ɹ�����",
            objDao => "purchase_plan_basic",//dao·��ע��
            "mainTable" => "oa_purchase_plan_changlog", //�����¼��������
            "detailTable" => "oa_purchase_plan_changedetail",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "dateHope" => "ϣ���������",
                "instruction" => "�ɹ�˵�� ",
                "remark" => "��ע",
                "equment" => array(
                    objName => "�ɹ������嵥",
                    objDao => "purchase_plan_equipment",//dao·��ע��
                    objField => "productName",
                    "relationField" => "basicId",//���������ֶ���
                    "amountAll" => "��������",
                    "productName" => "��������",
                    "dateHope" => "ϣ������ʱ�� "
                )
            )
        )
, "order" =>
        array(
            objName => "���ۺ�ͬ",
            objDao => "projectmanagent_order_order",//dao·��ע��
            "mainTable" => "oa_sale_order_changlog", //�����¼��������
            "detailTable" => "oa_sale_order_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderCode" => "������ͬ��",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "parentOrder" => "����ͬ����",
                "parentOrderId" => "����ͬID",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "prinvipalName" => "��ͬ������",
                "prinvipalId" => "��ͬ������ID",
                "orderMoney" => "ǩԼ��ͬ���",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "customerProvince" => "�ͻ�����ʡ��",
                "address" => "�ͻ���ַ",
                "deliveryDate" => "��������",
                "district" => "�������� ",
                "districtId" => "��������Id",
                "saleman" => " ����Ա",
                "warrantyClause" => "�������� ",
                "afterService" => "�ۺ�Ҫ��",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "signIn" => "ǩ��״̬",
                "shipCondition" => "��������",
                "remark" => "��ע",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "orderequ" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "projectmanagent_order_orderequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "projArraDate" => "�ƻ��������� ",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "linkman" => array(
                    objName => "�ͻ���ϵ��",
                    objDao => "projectmanagent_order_linkman",//dao·��ע��
                    objField => "linkman",
                    "relationField" => "orderId",//���������ֶ���
                    "linkman" => "�ͻ���ϵ��",
                    "telephone" => "�绰",
                    "Email" => "�ʼ�",
                    "remark" => "��ϵ�˱�ע "
                ),
                "invoice" => array(
                    objName => "��Ʊ�ƻ�",
                    objDao => "projectmanagent_order_invoice",//dao·��ע��
                    objField => "remark",
                    "relationField" => "orderId",//���������ֶ���
                    "money" => "��Ʊ��� ",
                    "softM" => "������ ",
                    "iType" => "��Ʊ����",
                    "invDT" => "��Ʊ���� ",
                    "remark" => "��Ʊ���� "
                ),
                "receiptplan" => array(
                    objName => "�տ�ƻ� ",
                    objDao => "projectmanagent_order_receiptplan",//dao·��ע��
                    objField => "collectionTerms",
                    "relationField" => "orderID",//���������ֶ���
                    "money" => "�տ��� ",
                    "payDT" => "�տ�����",
                    "pType" => "�տʽ",
                    "collectionTerms" => "���տ�����"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "projectmanagent_order_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            ))
, "orderSignin" =>
        array(
            objName => "���ۺ�ͬǩ��",
            objDao => "projectmanagent_order_order",//dao·��ע��
            "mainTable" => "oa_sale_order_signin", //�����¼��������
            "detailTable" => "oa_sale_order_signininfo",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderCode" => "������ͬ��",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "parentOrder" => "����ͬ����",
                "parentOrderId" => "����ͬID",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "prinvipalName" => "��ͬ������",
                "prinvipalId" => "��ͬ������ID",
                "orderMoney" => "ǩԼ��ͬ���",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "customerProvince" => "�ͻ�����ʡ��",
                "address" => "�ͻ���ַ",
                "deliveryDate" => "��������",
                "district" => "�������� ",
                "districtId" => "��������Id",
                "saleman" => " ����Ա",
                "warrantyClause" => "�������� ",
                "afterService" => "�ۺ�Ҫ��",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "remark" => "��ע",
                "customerName" => "�ͻ�����",
                "customerId" => "�ͻ�Id",
                "signinType" => "ǩ������",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "areaName" => "��ͬ��������",
                "areaCode" => "��ͬ��������Id",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",
                "orderequ" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "projectmanagent_order_orderequ",//dao·��ע��
                    objField => "productName",
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "projArraDate" => "�ƻ��������� ",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "linkman" => array(
                    objName => "�ͻ���ϵ�� ",
                    objDao => "projectmanagent_order_linkman",//dao·��ע��
                    objField => "linkman",
                    "relationField" => "orderId",//���������ֶ���
                    "linkman" => "�ͻ���ϵ��",
                    "telephone" => "�绰",
                    "Email" => "�ʼ�",
                    "remark" => "��ϵ�˱�ע "
                ),
                "invoice" => array(
                    objName => "��Ʊ�ƻ�  ",
                    objDao => "projectmanagent_order_invoice",//dao·��ע��
                    objField => "remark",
                    "relationField" => "orderId",//���������ֶ���
                    "money" => "��Ʊ��� ",
                    "softM" => "������ ",
                    "iType" => "��Ʊ����",
                    "invDT" => "��Ʊ���� ",
                    "remark" => "��Ʊ���� "
                ),
                "receiptplan" => array(
                    objName => "�տ�ƻ� ",
                    objDao => "projectmanagent_order_receiptplan",//dao·��ע��
                    objField => "collectionTerms",
                    "relationField" => "orderID",//���������ֶ���
                    "money" => "�տ��� ",
                    "payDT" => "�տ�����",
                    "pType" => "�տʽ",
                    "collectionTerms" => "���տ�����"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "projectmanagent_order_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )),
    "servicecontract" =>
        array(
            objName => "�����ͬ",
            objDao => "engineering_serviceContract_serviceContract",//dao·��ע��
            "mainTable" => "oa_sale_service_changlog", //�����¼��������
            "detailTable" => "oa_sale_service_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderCode" => "������ͬ��",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "customerLinkman" => "ǩԼ����ϵ��",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "linkmanNo" => "��ϵ�˵绰",
                "orderPrincipal" => "���۸�����",
                "orderPrincipalId" => "���۸�����Id",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "timeLimit" => "��������",
                "sectionPrincipal" => "���Ÿ�����",
                "sectionPrincipalId" => "���Ÿ�����Id",
                "sciencePrincipal" => "����������",
                "sciencePrincipalId" => "����������Id",
                "saleman" => " ����Ա",
                "orderMoney" => "ǩԼ��ͬ���",
                "contractPeriod" => "��ͬ����",
                "district" => "��������",
                "districtId" => "��������Id",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "isShipments" => "�Ƿ񷢻�",
                "signIn" => "ǩ��״̬",
                "shipCondition" => "��������",
                "remark" => "��ע",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "serviceequ" => array(
                    objName => "�豸�嵥",
                    objDao => "engineering_serviceContract_serviceequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,//��ɾ����ҵ�����ͨ��isDel��־
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "projArraDate" => "�ƻ��������� ",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "servicelist" => array(
                    objName => "�����嵥/��������",
                    objDao => "engineering_serviceContract_servicelist",//dao·��ע��
                    objField => "serviceItem",
                    "relationField" => "orderId",//���������ֶ���
                    "serviceItem" => "��������",
                    "serviceNo" => "����/����",
                    "serviceRemark" => "������ϸ",
                    "license" => "���������Ϣ "
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "engineering_serviceContract_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "serviceSignin" =>
        array(
            objName => "�����ͬǩ��",
            objDao => "engineering_serviceContract_serviceContract",//dao·��ע��
            "mainTable" => "oa_sale_service_signin", //�����¼��������
            "detailTable" => "oa_sale_service_signininfo",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderCode" => "������ͬ��",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "customerLinkman" => "ǩԼ����ϵ��",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "linkmanNo" => "��ϵ�˵绰",
                "orderPrincipal" => "���۸�����",
                "orderPrincipalId" => "���۸�����Id",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "timeLimit" => "��������",
                "sectionPrincipal" => "���Ÿ�����",
                "sectionPrincipalId" => "���Ÿ�����Id",
                "sciencePrincipal" => "����������",
                "sciencePrincipalId" => "����������Id",
                "saleman" => " ����Ա",
                "orderMoney" => "ǩԼ��ͬ���",
                "contractPeriod" => "��ͬ����",
                "district" => "��������",
                "districtId" => "��������Id",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "isShipments" => "�Ƿ񷢻�",
                "remark" => "��ע",
                "cusName" => "�ͻ�����",
                "cusNameId" => "�ͻ�Id",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "areaName" => "��ͬ��������",
                "areaCode" => "��ͬ��������Id",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",
                "serviceequ" => array(
                    objName => "�豸�嵥",
                    objDao => "engineering_serviceContract_serviceequ",//dao·��ע��
                    objField => "productName",
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "projArraDate" => "�ƻ��������� ",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "servicelist" => array(
                    objName => "�����嵥/��������",
                    objDao => "engineering_serviceContract_servicelist",//dao·��ע��
                    objField => "serviceItem",
                    "relationField" => "orderId",//���������ֶ���
                    "serviceItem" => "��������",
                    "serviceNo" => "����/����",
                    "serviceRemark" => "������ϸ",
                    "license" => "���������Ϣ "
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "engineering_serviceContract_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rentalcontract" =>
        array(
            objName => "���޺�ͬ",
            objDao => "contract_rental_rentalcontract",//dao·��ע��
            "mainTable" => "oa_sale_lease_changlog", //�����¼��������
            "detailTable" => "oa_sale_lease_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderCode" => "������ͬ��",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "tenantName" => "���ⷽ������",
                "tenantId" => "���ⷽ������Id",
                "hiresName" => "���ⷽ������",
                "hiresId" => "���ⷽ������ID",
                "timeLimit" => "����",
                "beginTime" => "���⿪ʼ����",
                "closeTime" => "�����ֹ����",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "details" => "֧������",
                "remark" => "��ע",
                "scienceMan" => "����������",
                "scienceManId" => "����������Id",
                "saleman" => " ����Ա",
                "orderMoney" => "ǩԼ��ͬ���",
                "district" => "��������",
                "districtId" => "��������Id",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "signIn" => "ǩ��״̬",
                "shipCondition" => "��������",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "rentalcontractequ" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "contract_rental_tentalcontractequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "proBeginTime" => "���⿪ʼ����",
                    "proEndTime" => "���⿪ʼ����",
                    "license" => "��������"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "contract_rental_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rentalcontractSignin" =>
        array(
            objName => "���޺�ͬǩ��",
            objDao => "contract_rental_rentalcontract",//dao·��ע��
            "mainTable" => "oa_sale_lease_signin", //�����¼��������
            "detailTable" => "oa_sale_lease_signininfo",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "tenantName" => "���ⷽ������",
                "hiresName" => "���ⷽ������",
                "hiresId" => "���ⷽ������ID",
                "timeLimit" => "����",
                "beginTime" => "���⿪ʼ����",
                "closeTime" => "�����ֹ����",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "details" => "֧������",
                "remark" => "��ע",
                "scienceMan" => "����������",
                "scienceManId" => "����������Id",
                "saleman" => " ����Ա",
                "orderMoney" => "ǩԼ��ͬ���",
                "district" => "��������",
                "districtId" => "��������Id",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "tenant" => "�ͻ�����",
                "tenantId" => "�ͻ�ID",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "areaName" => "��ͬ��������",
                "areaCode" => "��ͬ��������Id",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",
                "rentalcontractequ" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "contract_rental_tentalcontractequ",//dao·��ע��
                    objField => "productName",
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "contract_rental_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rdproject" =>
        array(
            objName => "�з���ͬ",
            objDao => "rdproject_yxrdproject_rdproject",//dao·��ע��
            "mainTable" => "oa_sale_rdproject_changlog", //�����¼��������
            "detailTable" => "oa_sale_rdproject_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderCode" => "������ͬ��",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "customerLinkman" => "ǩԼ����ϵ��",
                "parentOrder" => "����ͬ����",
                "parentOrderId" => "����ͬID",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "linkmanNo" => "��ϵ�˵绰",
                "orderPrincipal" => "���۸�����",
                "orderPrincipalId" => "���۸�����Id",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "timeLimit" => "��������",
                "sectionPrincipal" => "���Ÿ�����",
                "sectionPrincipalId" => "���Ÿ�����Id",
                "sciencePrincipal" => "����������",
                "sciencePrincipalId" => "����������Id",
                "saleman" => " ����Ա",
                "orderMoney" => "ǩԼ��ͬ���",
                "contractPeriod" => "��ͬ����",
                "district" => "��������",
                "districtId" => "��������Id",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "signIn" => "ǩ��״̬",
                "shipCondition" => "��������",
                "remark" => "��ע",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "rdprojectequ" => array(
                    objName => "�豸�嵥",
                    objDao => "rdproject_yxrdproject_rdprojectequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "rdproject_yxrdproject_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rdprojectSignin" =>
        array(
            objName => "�з���ͬǩ��",
            objDao => "rdproject_yxrdproject_rdproject",//dao·��ע��
            "mainTable" => "oa_sale_rdproject_signin", //�����¼��������
            "detailTable" => "oa_sale_rdproject_signininfo",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "issign" => "�Ƿ�ǩԼ",
                "orderstate" => "ֽ�ʺ�ͬ״̬",
                "orderNature" => "code��ͬ����",
                "orderNatureName" => "��ͬ����",
                "orderTempMoney" => "Ԥ�ƺ�ͬ���",
                "customerLinkman" => "ǩԼ����ϵ��",
                "parentOrder" => "����ͬ����",
                "parentOrderId" => "����ͬID",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "linkmanNo" => "��ϵ�˵绰",
                "orderPrincipal" => "���۸�����",
                "orderPrincipalId" => "���۸�����Id",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "timeLimit" => "��������",
                "sectionPrincipal" => "���Ÿ�����",
                "sectionPrincipalId" => "���Ÿ�����Id",
                "sciencePrincipal" => "����������",
                "sciencePrincipalId" => "����������Id",
                "saleman" => " ����Ա",
                "orderMoney" => "ǩԼ��ͬ���",
                "contractPeriod" => "��ͬ����",
                "district" => "��������",
                "districtId" => "��������Id",
                "rate" => "����",
                "currency" => "�ұ�",
                "orderTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "orderMoneyCur" => "ǩԼ��ԭ�ң�",
                "remark" => "��ע",
                "cusName" => "�ͻ�����",
                "cusNameId" => "�ͻ�ID",
                "orderProvince" => "����ʡ��",
                "orderProvinceId" => "����ʡ��ID",
                "orderCity" => "��������",
                "orderCityId" => "��������ID",
                "areaName" => "��ͬ��������",
                "areaCode" => "��ͬ��������Id",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",
                "rdprojectequ" => array(
                    objName => "�豸�嵥",
                    objDao => "rdproject_yxrdproject_rdprojectequ",//dao·��ע��
                    objField => "productName",
                    "relationField" => "orderId",//���������ֶ���
                    "number" => "����",
                    "productName" => "��Ʒ����",
                    "price" => "����",
                    "money" => "��� ",
                    "warrantyPeriod" => "������ ",
                    "license" => "��������"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "rdproject_yxrdproject_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "orderId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ), "contractequ" =>
        array(
            objName => "��ͬ�����嵥",
            objTable => "oa_contract_equ_link",//����������
            objDao => "contract_contract_contequlink",//dao·��ע��
            "mainTable" => "oa_contract_changlog", //�����¼��������
            "detailTable" => "oa_contract_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "equs" => array(
                    objName => "��ͬ�����嵥",
                    //objTable=>"oa_purch_apply_equ",//���������ϸ�����
                    objDao => "contract_contract_equ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    //"contractId"=>"��ͬid",//���������ֶ���
                    "productName" => "��������",
                    "productCode" => "���ϱ��",
                    //"conProductName"=>"��Ʒ���",
                    "unitName" => "��λ",
                    "number" => "����",
                    "arrivalPeriod" => "������",
                    "productModel" => "����ͺ�"
                    //"remark"=>"��ע "
                ),
            )
        ), "borrowequ" =>
        array(
            objName => "�����÷����嵥",
            objTable => "oa_borrow_equ_link",//����������
            objDao => "projectmanagent_borrow_borrowequlink",//dao·��ע��
            "mainTable" => "oa_borrow_changlog", //�����¼��������
            "detailTable" => "oa_borrow_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "equs" => array(
                    objName => "�����÷����嵥",
                    objTable=>"oa_borrow_equ",//���������ϸ�����
                    objDao => "projectmanagent_borrow_borrowequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    "relationFieldAsset" => array(
                        array("borrowId" => "borrowId")// ��̬�ֶι������������,���ѯ����ֶζ�ӦtempObj���ֶ�
                    ),
                    //"contractId"=>"��ͬid",//���������ֶ���
                    "productName" => "��������",
                    "productNo" => "���ϱ��",
                    //"conProductName"=>"��Ʒ���",
                    "unitName" => "��λ",
                    "number" => "����",
                    "arrivalPeriod" => "������",
                    "productModel" => "����ͺ�"
                    //"remark"=>"��ע "
                ),
            )
        ), "presentequ" =>
        array(
            objName => "���ͷ����嵥",
            objTable => "oa_present_equ_link",//����������
            objDao => "projectmanagent_present_presentequlink",//dao·��ע��
            "mainTable" => "oa_present_changlog", //�����¼��������
            "detailTable" => "oa_present_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "equs" => array(
                    objName => "���ͷ����嵥",
                    //objTable=>"oa_purch_apply_equ",//���������ϸ�����
                    objDao => "projectmanagent_present_presentequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    //"contractId"=>"��ͬid",//���������ֶ���
                    "productName" => "��������",
                    "productNo" => "���ϱ��",
                    //"conProductName"=>"��Ʒ���",
                    "unitName" => "��λ",
                    "number" => "����",
                    "arrivalPeriod" => "������",
                    "productModel" => "����ͺ�"
                    //"remark"=>"��ע "
                ),
            )
        ), "exchangeequ" =>
        array(
            objName => "���������嵥",
            objTable => "oa_exchange_equ_link",//����������
            objDao => "projectmanagent_exchange_exchangeequlink",//dao·��ע��
            "mainTable" => "oa_exchange_changlog", //�����¼��������
            "detailTable" => "oa_exchange_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "equs" => array(
                    objName => "���������嵥",
                    //objTable=>"oa_purch_apply_equ",//���������ϸ�����
                    objDao => "projectmanagent_exchange_exchangeequ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    //"contractId"=>"��ͬid",//���������ֶ���
                    "productName" => "��������",
                    "productNo" => "���ϱ��",
                    //"conProductName"=>"��Ʒ���",
                    "unitName" => "��λ",
                    "number" => "����",
                    "arrivalPeriod" => "������",
                    "productModel" => "����ͺ�"
                    //"remark"=>"��ע "
                ),
            )
        ), "outplan" =>
        array(
            objName => "�����ƻ�",
            //objTable=>"oa_purch_apply_basic",//����������
            objDao => "stock_outplan_outplan",//dao·��ע��
            "mainTable" => "oa_outplan_changlog", //�����¼��������
            "detailTable" => "oa_outplan_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "shipPlanDate" => "�ƻ���������",
                "stockName" => "�����ֿ�",
                "address" => "������ַ",
                "purConcern" => "�ɹ���Ա��ע�ص�",
                "shipConcern" => "������Ա��ע",
                "details" => array(
                    objName => "�����ƻ���ϸ",
                    //objTable=>"oa_purch_apply_equ",//���������ϸ�����
                    objDao => "stock_outplan_outplanProduct",//dao·��ע��
                    objField => "productName",
                    "relationField" => "mainId",//���������ֶ���
                    "productName" => "��Ʒ����",
                    "productNo" => "��Ʒ���",
                    "unitName" => "��λ",
                    "stockName" => "�����ֿ�����",
                    "number" => "���μƻ��������� "
                ),
            )
        ),
    "borrow" =>
        array(
            objName => "������",
            objDao => "projectmanagent_borrow_borrow",
            "mainTable" => "oa_borrow_changlog",
            "detailTable" => "oa_borrow_changedetail",
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "beginTime" => "��ʼ����",
                "closeTime" => "��ֹ����",
                "deliveryDate" => "��������",
                "salesName" => "���۸�����",
                "salesNameId" => "���۸�����ID",
                "scienceName" => "����������",
                "scienceNameId" => "����������ID",
                "shipaddress" => "������ַ",
                "status" => "����״̬",
                "reason" => "��������",
                "remark" => "����ԭ��",
                "remarkapp" => "��ע",
                "module" => "�������",
                "newProLineStr" => "��Ʒ������",
                "borrowequ" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "projectmanagent_borrow_borrowequ",
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "borrowId",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "license" => "��������",
                ),
                "product" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "projectmanagent_borrow_product",//dao·��ע��
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "borrowId",//���������ֶ���
                    "conProductName" => "��Ʒ����",
                    "conProductId" => "��ƷID",
                    "conProductDes" => "��Ʒ����",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "deploy" => "��Ʒ����",
                    "newProLineCode" => "��Ʒ�߱�� ",
                    "newProLineName" => "��Ʒ������"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "projectmanagent_borrow_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "borrowId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
            ),
        ),
    "present" =>
        array(
            objName => "����",
            objDao => "projectmanagent_present_present",
            "mainTable" => "oa_present_changlog",
            "detailTable" => "oa_present_changedetail",
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "customerName" => "�ͻ�����",
                "customerNameId" => "�ͻ�����ID",
                "areaName" => "��������",
                "areaCode" => "����������",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",
                "customTypeName" => "�ͻ�����",
                "customTypeId" => "�ͻ�����ID",
                "province" => "ʡ��",
                "provinceId" => "ʡ��ID",
                "moduleName" => "�������",
                "module" => "�������ID",
                "businessBelongName" => "������˾",
                "businessBelong" => "������˾ID",
                "feeMan" => "���óе���",
                "feeManId" => "���óе���ID",
                "deliveryDate" => "��������",
                "reason" => "��������",
                "remark" => "��ע",
                //��������ֶ�ע��
                "product" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "projectmanagent_present_product",//dao·��ע��
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "presentId",//���������ֶ���
                    "conProductName" => "��Ʒ����",
                    "conProductId" => "��ƷID",
                    "conProductDes" => "��Ʒ����",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "deploy" => "��Ʒ����",
                    "costEstimates" => "�ɱ�����"
                ),
                "presentequ" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "projectmanagent_present_presentequ",
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "presentId",
                    "productName" => "��������",
                    "productNo" => "���ϱ��",
                    "prodcutId" => "����ID",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "license" => "��������",
                ),
            ),
        ),
    "outsourcing" =>
        array(
            objName => "�����ͬ",
            objDao => "contract_outsourcing_outsourcing",
            "mainTable" => "oa_sale_outsourcing_changelog",
            "detailTable" => "oa_sale_outsourcing_changelogdetail",
            //��������ֶ�ע��
            "register" => array(
                "orderName" => "��ͬ����",
                "outContractCode" => "�����ͬ��",
                "signCompanyName" => "ǩԼ��˾",
                "payCondition" => "��������",
                "proName" => "��˾ʡ��",
                "proCode" => "��˾ʡ�ݱ���",
                "address" => "��ϵ��ַ",
                "phone" => "��ϵ�绰",
                "linkman" => "��ϵ��",
                "orderMoney" => "��ͬ���",
                "signDate" => "ǩԼ����",
                "beginDate" => "��ʼ����",
                "endDate" => "��������",
                "principalName" => "����������",
                "principalId" => "������ID",
                "deptId" => "����id",
                "deptName" => "��������",
                "outsourceType" => "�������",
                "outsourceTypeName" => "���������",
                "payType" => "��������",
                "payTypeName" => "����������",
                "outsourcing" => "�����ʽ",
                "outsourcingName" => "�����ʽ��",
                "projectCode" => "��Ŀ���",
                "projectName" => "��Ŀ����",
                "projectType" => "��Ŀ����",
                "projectTypeName" => "��Ŀ������",
                "projectId" => "��Ŀid",
                "description" => "��ͬ��������",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_outsourcing'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            )
        ),
    "outsourcingSign" =>
        array(
            objName => "�����ͬ",
            objDao => "contract_outsourcing_outsourcing",
            "mainTable" => "oa_sale_outsourcing_signlog",
            "detailTable" => "oa_sale_outsourcing_signlogdetail",
            //��������ֶ�ע��
            "register" => array(
                "orderName" => "��ͬ����",
                "outContractCode" => "�����ͬ��",
                "signCompanyName" => "ǩԼ��˾",
                "payCondition" => "��������",
                "proName" => "��˾ʡ��",
                "proCode" => "��˾ʡ�ݱ���",
                "address" => "��ϵ��ַ",
                "phone" => "��ϵ�绰",
                "linkman" => "��ϵ��",
                "orderMoney" => "��ͬ���",
                "signDate" => "ǩԼ����",
                "beginDate" => "��ʼ����",
                "endDate" => "��������",
                "principalName" => "����������",
                "principalId" => "������ID",
                "deptId" => "����id",
                "deptName" => "��������",
                "outsourceType" => "�������",
                "outsourceTypeName" => "���������",
                "payType" => "��������",
                "payTypeName" => "����������",
                "outsourcing" => "�����ʽ",
                "outsourcingName" => "�����ʽ��",
                "projectCode" => "��Ŀ���",
                "projectName" => "��Ŀ����",
                "projectType" => "��Ŀ����",
                "projectTypeName" => "��Ŀ������",
                "projectId" => "��Ŀid",
                "description" => "��ͬ��������",

                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_outsourcing'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            )
        ),
    "other" =>
        array(
            objName => "������ͬ",
            objDao => "contract_other_other",
            "mainTable" => "oa_sale_other_changelog",
            "detailTable" => "oa_sale_other_changelogdetail",
            //��������ֶ�ע��
            "register" => array(
                "orderName" => "��ͬ����",
                "signCompanyName" => "ǩԼ��˾",
                "fundCondition" => "������������",
                "proName" => "��˾ʡ��",
                "proCode" => "��˾ʡ�ݱ���",
                "address" => "��ϵ��ַ",
                "phone" => "��ϵ�绰",
                "linkman" => "��ϵ��",
                "orderMoney" => "��ͬ���",
                "taxPoint" => "��ͬ˰��",
                "moneyNoTax" => "��ͬ���(����˰)",
                "invoiceType" => "��Ʊ����",
                "invoiceTypeName" => "��Ʊ��������",
                "signDate" => "ǩԼ����",
                "principalName" => "����������",
                "principalId" => "������ID",
                "deptId" => "����id",
                "deptName" => "��������",
                "projectCode" => "��Ŀ���",
                "projectId" => "��Ŀid",
                "projectName" => "��Ŀ����",
                "projectType" => "��Ŀ����ֵ",
                "projectTypeName" => "��Ŀ����",
                "feeDeptId" => "���ù�������ֵ",
                "feeDeptName" => "���ù�������",
                "description" => "��ͬ����",
                "currency" => "����",
                "chanceCode" => '�̻����',
                "chanceId" => '�̻�ID',
                "prefBidDate" => 'Ԥ��Ͷ������',
                "contractCode" => '���ۺ�ͬ���',
                "contractId" => '���ۺ�ͬID',
                "projectPrefEndDate" => '���ۺ�ͬ������Ŀ�����Ԥ�ƽ�������',
                "delayPayDays" => '�Ӻ�ؿ�����',
                "isBankbackLetter" => '�Ƿ������б���',
                "backLetterEndDate" => '���б�����������',
                "hasRelativeContract" => '�Ƿ��б�֤������������ͬ',
                "relativeContract" => '�����������ͬ��',
                "prefPayDate" => 'Ԥ�ƻؿ�ʱ��',
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_other'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                ),
                "costshare" => array(
                    objName => "���÷�̯",
                    objDao => "finance_cost_costshare",//dao·��ע��
                    "isFalseDel" => true,
                    "relationField" => "objId",//���������ֶ���
                    "relationFieldAsset" => array('objType' => "2"), // ��̬�����ֶ�
                    "objCode" => "Դ�����",
                    "objType" => "Դ������",
                    "companyName" => "��˾��������",
                    "company" => "��˾����",
                    "inPeriod" => "�����ڼ�",
                    "belongPeriod" => "�����ڼ�",
                    "shareObjType" => "��̯��������",
                    "detailType" => "��������",
                    "belongCompany" => "���ù�����˾",
                    "belongCompanyName" => "���ù�����˾����",
                    "belongDeptName" => "���ù�����������",
                    "belongDeptId" => "���ù�������Id",
                    "headDeptName" => "��������",
                    "headDeptId" => "��������Id",
                    "belongId" => "����������id",
                    "belongName" => "��������������",
                    "chanceCode" => "�̻���",
                    "chanceId" => "�̻�id",
                    "province" => "ʡ��",
                    "customerId" => "�ͻ�id",
                    "customerName" => "�ͻ�����",
                    "customerType" => "�ͻ�����",
                    "contractCode" => "���ۺ�ͬ��",
                    "contractId" => "���ۺ�ͬid",
                    "projectId" => "��Ŀid",
                    "projectCode" => "��Ŀ���",
                    "projectName" => "��Ŀ����",
                    "projectType" => "��Ŀ����",
                    "parentTypeId" => "�ϼ���ϸid",
                    "parentTypeName" => "�ϼ���ϸ����",
                    "costTypeId" => "������ϸid",
                    "costTypeName" => "������ϸ����",
                    "costMoney" => "��̯���",
                    "module" => "����������",
                    "moduleName" => "�������",
                    "supplierName" => "��Ӧ��"
                )
            )
        ),
    "otherSign" =>
        array(
            objName => "������ͬ",
            objDao => "contract_other_other",
            "mainTable" => "oa_sale_other_signlog",
            "detailTable" => "oa_sale_other_signlogdetail",
            //��������ֶ�ע��
            "register" => array(
                "orderName" => "��ͬ����",
                "signCompanyName" => "ǩԼ��˾",
                "fundCondition" => "������������",
                "proName" => "��˾ʡ��",
                "proCode" => "��˾ʡ�ݱ���",
                "address" => "��ϵ��ַ",
                "phone" => "��ϵ�绰",
                "linkman" => "��ϵ��",
                "orderMoney" => "��ͬ���",
                "taxPoint" => "��ͬ˰��",
                "moneyNoTax" => "��ͬ���(����˰)",
                "invoiceType" => "��Ʊ����",
                "invoiceTypeName" => "��Ʊ��������",
                "signDate" => "ǩԼ����",
                "principalName" => "����������",
                "principalId" => "������ID",
                "deptId" => "����id",
                "deptName" => "��������",
                "projectCode" => "��Ŀ���",
                "projectId" => "��Ŀid",
                "projectName" => "��Ŀ����",
                "projectType" => "��Ŀ����ֵ",
                "projectTypeName" => "��Ŀ����",
                "feeDeptId" => "���ù�������ֵ",
                "feeDeptName" => "���ù�������",
                "description" => "��ͬ����",
                "currency" => "����",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_other'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                ),
                "costshare" => array(
                    objName => "���÷�̯",
                    objDao => "finance_cost_costshare",//dao·��ע��
                    "isFalseDel" => true,
                    "relationField" => "objId",//���������ֶ���
                    "relationFieldAsset" => array('objType' => "2"), // ��̬�����ֶ�
                    "objCode" => "Դ�����",
                    "objType" => "Դ������",
                    "companyName" => "��˾��������",
                    "company" => "��˾����",
                    "inPeriod" => "�����ڼ�",
                    "belongPeriod" => "�����ڼ�",
                    "shareObjType" => "��̯��������",
                    "detailType" => "��������",
                    "belongCompany" => "���ù�����˾",
                    "belongCompanyName" => "���ù�����˾����",
                    "belongDeptName" => "���ù�����������",
                    "belongDeptId" => "���ù�������Id",
                    "belongId" => "����������id",
                    "belongName" => "��������������",
                    "chanceCode" => "�̻���",
                    "chanceId" => "�̻�id",
                    "province" => "ʡ��",
                    "customerId" => "�ͻ�id",
                    "customerName" => "�ͻ�����",
                    "customerType" => "�ͻ�����",
                    "contractCode" => "���ۺ�ͬ��",
                    "contractId" => "���ۺ�ͬid",
                    "projectId" => "��Ŀid",
                    "projectCode" => "��Ŀ���",
                    "projectName" => "��Ŀ����",
                    "projectType" => "��Ŀ����",
                    "parentTypeId" => "�ϼ���ϸid",
                    "parentTypeName" => "�ϼ���ϸ����",
                    "costTypeId" => "������ϸid",
                    "costTypeName" => "������ϸ����",
                    "costMoney" => "��̯���",
                    "supplierName" => "��Ӧ��"
                )
            )
        )
    //�º�ͬ���� ���
, "contract" =>
        array(
            objName => "��ͬ����",
            objDao => "contract_contract_contract",//dao·��ע��
            "mainTable" => "oa_contract_changlog", //�����¼��������
            "detailTable" => "oa_contract_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "sign" => "�Ƿ�ǩԼ",
                "winRate" => "��ͬӮ��",
                "signDate" => "ǩԼʱ��",
                "contractType" => "code��ͬ����",
                "contractTypeName" => "��ͬ��������",
                "contractNature" => "Code��ͬ����",
                "contractNatureName" => "��ͬ��������",
                "contractCode" => "��ͬ���",
                "contractName" => "��ͬ����",
                "prinvipalName" => "��ͬ������",
                "prinvipalId" => "��ͬ������ID",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "added" => "��ֵ�ѷ�Ʊ",
                "addedMoney" => "��ֵ�ѷ�Ʊ���",
                "exportInv" => "���ڷ�Ʊ",
                "exportInvMoney" => "���ڷ�Ʊ���",
                "serviceInv" => "����Ʊ",
                "serviceInvMoney" => "����Ʊ���",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "address" => "�ͻ���ַ",
                "contractCountry" => "��������",
                "contractCountryId" => "��������ID",
                "contractProvince" => "����ʡ��",
                "contractProvinceId" => "����ʡ��ID",
                "contractCity" => "��������",
                "contractCityId" => "��������Id",
                "contractSigner" => "��ͬǩ����",
                "contractSignerId" => "��ͬǩ����ID",
                "beginDate" => "��ͬ��ʼ����",
                "endDate" => "��ͬ��������",
                "deliveryDate" => "��������",
                "district" => "�������� ",
                "districtId" => "��������Id",
                "saleman" => " ����Ա",
                "goodsTypeStr" => "��Ʒ���",
                "signSubject" => "ǩԼ��˾",
                "signSubjectName" => "ǩԼ��˾����",
                "customerName" => "�ͻ�����",
                "customerId" => "�ͻ�id",

                "areaName" => "��ͬ��������",
                "areaCode" => "��ͬ��������Id",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",

                "rate" => "����",
                "currency" => "�ұ�",
                "contractTempMoney" => "Ԥ�ƽ��",
                "contractMoney" => "ǩԼ���",
                "contractTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "contractMoneyCur" => "ǩԼ��ԭ�ң�",

                "paymentterm" => "֧������",
                "warrantyClause" => "��������",
                "afterService" => "�ۺ����",
                "shipCondition" => "��������",
                "remark" => "��ע",

                "advance" => "Ԥ����",
                "delivery" => "��������",
                "progresspayment" => "�����ȸ���",
                "progresspaymentterm" => "�����ȸ�������",
                "initialpayment" => "����ͨ������",
                "finalpayment" => "����ͨ������",
                "otherpaymentterm" => "������������",
                "otherpayment" => "��������ռ��",
                "Maintenance" => "ά��ʱ��",

                "isSell" => "��Ʒ���ͱ�ʶ",
                "signStatus" => "���ǩ�ձ�ʶ",

                "costEstimates" => "�ɱ�����",
                "costEstimatesTax" => "�ɱ����㣨��˰��",
                "exgross" => "Ԥ��ë����",
                "saleCost" => "����ȷ�ϸ���",
                "serCost" => "����ȷ�ϸ���",
                "rdCost" => "�з�ȷ�ϸ���",
                "rlCost" => "����ȷ�ϸ���",
                "engConfirm" => "��������ʾ",
                "engConfirmName" => "�������ȷ����",
                "engConfirmId" => "",
                "engConfirmDate" => "�������ȷ��ʱ��",
                "saleConfirm" => "���۸���ȷ�ϱ�ʶ",
                "saleConfirmName" => "���۸���ȷ����",
                "saleConfirmId" => "",
                "saleConfirmDate" => "���۸���ȷ��ʱ��",
                "rdproConfirm" => "�з�ȷ�ϸ����ʶ",
                "rdproConfirmName" => "�з�ȷ�ϸ���ȷ����",
                "rdproConfirmId" => "",
                "rdproConfirmDate" => "�з�ȷ�ϸ���ʱ��",
                "formBelong" => "���ݹ�����˾",
                "formBelongName" => "���ݹ�����˾����",
                "businessBelong" => "ҵ�������˾",
                "businessBelongName" => "ҵ�������˾����",
                "invoiceValue" => "��Ʊ���",
                "invoiceCode" => "��Ʊ���ͱ���",
                "exeDeptStr" => "ִ������id��",
                "newProLineStr" => "��Ʒ��id��",
                "module" => "����������",
                "moduleName" => "�������",
                "isFrame" => "�Ƿ��ܺ�ͬ",
                "newExeDeptStr" => "ִ������id��(��ͳ����)",
                "xfProLineStr" => "��Ʒ��id��(��������/�����Ʒ)",
                "paperContract" => "ֽ�ʺ�ͬ",
                "paperContractRemark" => "��ֽ�ʺ�ͬԭ��",
                "partAContractCode" => "�׷���ͬ���",
                "partAContractName" => "�׷���ͬ����",
                "paperSignTime" => "ֽ�ʺ�ͬǩ��ʱ��",
                'payment' => array(
                    objName => "��������",
                    objDao => "contract_contract_receiptplan",//dao·��ע��
                    objField => "money",
                    isFalseDel => true,
                    "relationField" => "contractId",//���������ֶ���
                    'paymentterm' => '��������',
                    'paymentPer' => '����ٷֱ�',
                    'money' => '�ƻ�������',
                    'payDT' => '�ƻ���������',
                    'remark' => '��ע'
                ),
                "product" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "contract_contract_product",//dao·��ע��
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "contractId",//���������ֶ���
                    "conProductName" => "��Ʒ����",
                    "conProductId" => "��ƷID",
                    "conProductDes" => "��Ʒ����",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "deploy" => "��Ʒ����",
                    "proType" => "��Ʒ����",
                    "proTypeId" => "��Ʒ����id",
                    "exeDeptId" => "ִ������id",
                    "exeDeptName" => "ִ������",
                    "newProLineCode" => "��Ʒ�߱���",
                    "newProLineName" => "��Ʒ��"
                ),
                "financialplan" => array(
                    objName => "�տ��ƻ�",
                    objDao => "contract_contract_financialplan",//dao·��ע��
                    objField => "planDate",
                    isFalseDel => true,
                    "relationField" => "contractId",//���������ֶ���
                    "planDate" => "����",
                    "invoiceMoney" => "��Ʊ���",
                    "incomeMoney" => "�տ���",
                    "remark" => "��ע"
                ),
                "equ" => array(
                    objName => "�����嵥",
                    objDao => "contract_contract_equ",//dao·��ע��
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "contractId",//���������ֶ���
                    "productName" => "��������",
                    "prodcutId" => "����ID",
                    "number" => "����",
                    "price" => "����",
                    "priceTax" => "˰�󵥼�",
                    "money" => "���",
                    "moneyTax" => "˰����",
                    "license" => "������Ϣ",
                    "toBorrowId" => "������id",
                    "toBorrowequId" => "�����ôӱ�ID",
                    "serialId" => "���к�id",
                    "serialName" => "���к�����",
                    "isBorrowToorder" => "ת���۱�ʶ",
                    "arrivalPeriod" => "������",
                    "warrantyPeriod" => "������"
                ),
                "linkman" => array(
                    objName => "�ͻ���ϵ��",
                    objDao => "contract_contract_linkman",//dao·��ע��
                    objField => "linkmanName",
                    "relationField" => "contractId",//���������ֶ���
                    "linkmanName" => "�ͻ���ϵ��",
                    "linkmanId" => "��ϵ��ID",
                    "telephone" => "�绰",
                    "QQ" => "QQ",
                    "Email" => "�ʼ�",
                    "remark" => "��ϵ�˱�ע "
                ),
                "invoice" => array(
                    objName => "��Ʊ�ƻ�",
                    objDao => "contract_contract_invoice",//dao·��ע��
                    objField => "remark",
                    "relationField" => "contractId",//���������ֶ���
                    "money" => "��Ʊ��� ",
                    "softM" => "������ ",
                    "iType" => "��Ʊ����",
                    "invDT" => "��Ʊ���� ",
                    "remark" => "��Ʊ���� "
                ),
                "train" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "contract_contract_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "contractId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_contract1','oa_contract_contract2'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            ))
    //�º�ͬǩ�ձ���ֶ�ע��
, "contractSign" =>
        array(
            objName => "��ͬ����",
            objDao => "contract_contract_contract",//dao·��ע��
            "mainTable" => "oa_contract_signin", //�����¼��������
            "detailTable" => "oa_contract_signininfo",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "sign" => "�Ƿ�ǩԼ",
                "signDate" => "ǩԼʱ��",
                "contractType" => "code��ͬ����",
                "contractTypeName" => "��ͬ��������",
                "contractNature" => "Code��ͬ����",
                "contractNatureName" => "��ͬ��������",
                "contractCode" => "��ͬ���",
                "contractName" => "��ͬ����",
                "prinvipalName" => "��ͬ������",
                "prinvipalId" => "��ͬ������ID",
                "invoiceType" => "code��Ʊ����",
                "invoiceTypeName" => "��Ʊ����",
                "customerName" => "�ͻ�����",
                "customerType" => "code�ͻ�����",
                "customerTypeName" => "�ͻ�����",
                "address" => "�ͻ���ַ",
                "contractCountry" => "��������",
                "contractCountryId" => "��������ID",
                "contractProvince" => "����ʡ��",
                "contractProvinceId" => "����ʡ��ID",
                "contractCity" => "��������",
                "contractCityId" => "��������Id",
                "contractSigner" => "��ͬǩ����",
                "contractSignerId" => "��ͬǩ����ID",
                "beginDate" => "��ͬ��ʼ����",
                "endDate" => "��ͬ��������",
                "deliveryDate" => "��������",
                "district" => "�������� ",
                "districtId" => "��������Id",
                "saleman" => " ����Ա",

                "areaName" => "��ͬ��������",
                "areaCode" => "��ͬ��������Id",
                "areaPrincipal" => "��������",
                "areaPrincipalId" => "��������ID",

                "rate" => "����",
                "currency" => "�ұ�",
                "contractTempMoney" => "Ԥ�ƽ��",
                "contractMoney" => "ǩԼ���",
                "contractTempMoneyCur" => "Ԥ�ƽ�ԭ�ң�",
                "contractMoneyCur" => "ǩԼ��ԭ�ң�",

                "paymentterm" => "֧������",
                "warrantyClause" => "��������",
                "afterService" => "�ۺ����",
                "shipCondition" => "��������",
                "remark" => "��ע",
                "formBelong" => "���ݹ�����˾",
                "formBelongName" => "���ݹ�����˾����",
                "businessBelong" => "ҵ�������˾",
                "businessBelongName" => "ҵ�������˾����",
                "exeDeptStr" => "ִ�в���id��",
                "product" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "contract_contract_product",//dao·��ע��
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "contractId",//���������ֶ���
                    "conProductName" => "��Ʒ����",
                    "conProductId" => "��ƷID",
                    "conProductDes" => "��Ʒ����",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "deploy" => "��Ʒ����",
                    "proType" => "��Ʒ����",
                    "proTypeId" => "��Ʒ����id",
                    "exeDeptId" => "ִ�в���id",
                    "exeDeptName" => "ִ�в���"
                ),
                "linkman" => array(
                    objName => "�ͻ���ϵ��",
                    objDao => "contract_contract_linkman",//dao·��ע��
                    objField => "linkmanName",
                    "relationField" => "contractId",//���������ֶ���
                    "linkmanName" => "�ͻ���ϵ��",
                    "linkmanId" => "��ϵ��ID",
                    "telephone" => "�绰",
                    "QQ" => "QQ",
                    "Email" => "�ʼ�",
                    "remark" => "��ϵ�˱�ע "
                ),
                "invoice" => array(
                    objName => "��Ʊ�ƻ�",
                    objDao => "contract_contract_invoice",//dao·��ע��
                    objField => "remark",
                    "relationField" => "contractId",//���������ֶ���
                    "money" => "��Ʊ��� ",
                    "softM" => "������ ",
                    "iType" => "��Ʊ����",
                    "invDT" => "��Ʊ���� ",
                    "remark" => "��Ʊ���� "
                ),
                "receiptplan" => array(
                    objName => "�տ�ƻ� ",
                    objDao => "contract_contract_receiptplan",//dao·��ע��
                    objField => "collectionTerms",
                    "relationField" => "contractId",//���������ֶ���
                    "money" => "�տ��� ",
                    "payDT" => "�տ�����",
                    "pType" => "�տʽ",
                    "collectionTerms" => "���տ�����"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "contract_contract_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "contractId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_contract1','oa_contract_contract2'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            )),
    "produceapply" => array(
        objName => "�������뵥",
        objDao => "produce_apply_produceapply",//dao·��ע��
        "mainTable" => "oa_produce_changelog_apply", //�����¼��������
        "detailTable" => "oa_produce_changelog_applyitem",//�����¼��ϸ������
        "changeTagField" => 'changeTips',//������ѱ�ʶ
        //��������ֶ�ע��
        "register" => array(
            "items" => array(
                objName => "�����嵥",
                objDao => "produce_apply_produceapplyitem",//dao·��ע��
                objField => "productName",
                isFalseDel => true,
                "productCode" => "���ϱ��",
                "productName" => "��������",
                "pattern" => "����ͺ�",
                "unitName" => "��λ",
                "produceNum" => "��������",
                "planEndDate" => "�ƻ�����ʱ��"
            )
        )
    ),
    "producetask" => array(
        objName => "��������",
        objDao => "produce_task_producetask",//dao·��ע��
        "mainTable" => "oa_produce_changelog_task", //�����¼��������
        "detailTable" => "oa_produce_changelog_taskitem",//�����¼��ϸ������
        "changeTagField" => 'changeTips',//������ѱ�ʶ
        //��������ֶ�ע��
        "register" => array(
            "planStartDate" => "�ƻ���ʼʱ��",
            "planEndDate" => "�ƻ�����ʱ��",
            "estimateHour" => "���ƹ�����(Сʱ)",
            "estimateDay" => "�ƻ�����(��)",
            "taskNum" => "��������"
        )
    )
    //�̻����
, "chance" =>
        array(
            objName => "�̻�����",
            objDao => "projectmanagent_chance_chance",//dao·��ע��
            "mainTable" => "oa_chance_changlog", //�����¼��������
            "detailTable" => "oa_chance_changedetail",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "chanceCode" => "�̻����",
                "chanceName" => "�̻�����",
                "chanceLevel" => "�̻��ȼ�",
                "chanceStage" => "�̻��׶�",
                "winRate" => "�̻�ӯ��",
                "chanceType" => "�̻�����",
                "chanceTypeName" => "�̻���������",
                "chanceNature" => "��������",
                "chanceNatureName" => "������������",
                "chanceMoney" => "�̻����",
                "predictContractDate" => "Ԥ��ǩ����ͬ����",
                "predictExeDate" => "Ԥ�ƺ�ִͬ������",
                "contractPeriod" => "��ִͬ�����ڳ���",
                "newUpdateDate" => "�������ʱ��",
                "customerName" => "�ͻ�����",
                "customerType" => "�ͻ�����",
                "remark" => "�̻���ע",
                "progress" => "��Ŀ��չ����",
                "Country" => "��������",
                "Province" => "����ʡ��",
                "City" => "��������",
                "areaName" => "��ͬ��������",
                "areaPrincipal" => "��������",
                "prinvipalName" => "�̻�������",
                "signSubject" => "ǩԼ����",
                "signSubjectName" => "ǩԼ��������",
                "formBelong" => "���ݹ�����˾",
                "formBelongName" => "���ݹ�����˾����",
                "businessBelong" => "ҵ�������˾",
                "businessBelongName" => "ҵ�������˾����",
                "module" => "�������",
                "product" => array(
                    objName => "��Ʒ�嵥",
                    objDao => "contract_contract_product",//dao·��ע��
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "contractId",//���������ֶ���
                    "conProductName" => "��Ʒ����",
                    "conProductId" => "��ƷID",
                    "conProductDes" => "��Ʒ����",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "deploy" => "��Ʒ����",
                    "newProLineCode" => "��Ʒ�߱�� ",
                    "newProLineName" => "��Ʒ������"
                ),
                "linkman" => array(
                    objName => "�ͻ���ϵ��",
                    objDao => "contract_contract_linkman",//dao·��ע��
                    objField => "linkmanName",
                    "relationField" => "contractId",//���������ֶ���
                    "linkmanName" => "�ͻ���ϵ��",
                    "linkmanId" => "��ϵ��ID",
                    "telephone" => "�绰",
                    "QQ" => "QQ",
                    "Email" => "�ʼ�",
                    "remark" => "��ϵ�˱�ע "
                ),
                "invoice" => array(
                    objName => "��Ʊ�ƻ�",
                    objDao => "contract_contract_invoice",//dao·��ע��
                    objField => "remark",
                    "relationField" => "contractId",//���������ֶ���
                    "money" => "��Ʊ��� ",
                    "softM" => "������ ",
                    "iType" => "��Ʊ����",
                    "invDT" => "��Ʊ���� ",
                    "remark" => "��Ʊ���� "
                ),
                "receiptplan" => array(
                    objName => "�տ�ƻ� ",
                    objDao => "contract_contract_receiptplan",//dao·��ע��
                    objField => "collectionTerms",
                    "relationField" => "contractId",//���������ֶ���
                    "money" => "�տ��� ",
                    "payDT" => "�տ�����",
                    "pType" => "�տʽ",
                    "collectionTerms" => "���տ�����"
                ),
                "trainingplan" => array(
                    objName => "��ѵ�ƻ�",
                    objDao => "contract_contract_trainingplan",//dao·��ע��
                    objField => "content",
                    "relationField" => "contractId",//���������ֶ���
                    "beginDT" => "��ѵ��ʼʱ�� ",
                    "endDT" => "��ѵ����ʱ��",
                    "traNum" => "��������",
                    "adress" => "��ѵ�ص�",
                    "content" => "��ѵ����",
                    "trainer" => "��ѵ����ʦҪ��"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_contract1','oa_contract_contract2'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            )),
    "produceapply" => array(
        objName => "�������뵥",
        objDao => "produce_apply_produceapply",//dao·��ע��
        "mainTable" => "oa_produce_changelog_apply", //�����¼��������
        "detailTable" => "oa_produce_changelog_applyitem",//�����¼��ϸ������
        "changeTagField" => 'changeTips',//������ѱ�ʶ
        //��������ֶ�ע��
        "register" => array(
            "hopeDeliveryDate" => "������������",
            "relDocType" => "Դ������",
            "relDocCode" => "Դ�����",
            "projectName" => "��Ŀ����",
            "remark" => "��ע",
            "items" => array(
                objName => "������Ϣ",
                objDao => "produce_apply_produceapplyitem",//dao·��ע��
                objField => "productCode",
                "relationField" => "mainId",//���������ֶ���
                "productCode" => "���ϱ��",
                "productName" => "��������",
                // "pattern" => "����ͺ�",
                "unitName" => "��λ",
                "produceNum" => "��������",
                "planEndDate" => "��������ʱ��",
                "remark" => "��ע"
            )
        )
    ),
    "producetask" => array(
        objName => "��������",
        objDao => "produce_task_producetask",//dao·��ע��
        "mainTable" => "oa_produce_changelog_task", //�����¼��������
        "detailTable" => "oa_produce_changelog_taskitem",//�����¼��ϸ������
        "changeTagField" => 'changeTips',//������ѱ�ʶ
        //��������ֶ�ע��
        "register" => array(
            "planStartDate" => "�ƻ���ʼʱ��",
            "planEndDate" => "�ƻ�����ʱ��",
            "estimateHour" => "���ƹ�����(Сʱ)",
            "estimateDay" => "�ƻ�����(��)",
            "taskNum" => "��������"
        )
    )
    //�̻����
, "chance" =>
        array(
            objName => "�̻�����",
            objDao => "projectmanagent_chance_chance",//dao·��ע��
            "mainTable" => "oa_chance_changlog", //�����¼��������
            "detailTable" => "oa_chance_changedetail",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "chanceCode" => "�̻����",
                "chanceName" => "�̻�����",
                "chanceLevel" => "�̻��ȼ�",
                "chanceStage" => "�̻��׶�",
                "winRate" => "�̻�ӯ��",
                "chanceType" => "�̻�����",
                "chanceTypeName" => "�̻���������",
                "chanceNature" => "��������",
                "chanceNatureName" => "������������",
                "chanceMoney" => "�̻����",
                "predictContractDate" => "Ԥ��ǩ����ͬ����",
                "predictExeDate" => "Ԥ�ƺ�ִͬ������",
                "contractPeriod" => "��ִͬ�����ڳ���",
                "newUpdateDate" => "�������ʱ��",
                "customerName" => "�ͻ�����",
                "customerType" => "�ͻ�����",
                "remark" => "�̻���ע",
                "progress" => "��Ŀ��չ����",
                "Country" => "��������",
                "Province" => "����ʡ��",
                "City" => "��������",
                "areaName" => "��ͬ��������",
                "areaPrincipal" => "��������",
                "prinvipalName" => "�̻�������",
                "signSubject" => "ǩԼ����",
                "signSubjectName" => "ǩԼ��������",
                "formBelong" => "���ݹ�����˾",
                "formBelongName" => "���ݹ�����˾����",
                "businessBelong" => "ҵ�������˾",
                "businessBelongName" => "ҵ�������˾����",

                "product" => array(
                    objName => "��ϸ��Ʒ�嵥",
                    objDao => "projectmanagent_chance_product",//dao·��ע��
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "chanceId",//���������ֶ���
                    "conProductName" => "��Ʒ����",
                    "conProductId" => "��ƷID",
                    "conProductDes" => "��Ʒ����",
                    "number" => "����",
                    "price" => "����",
                    "money" => "��� ",
                    "deploy" => "��Ʒ����"
                ),
                "linkman" => array(
                    objName => "�ͻ���ϵ��",
                    objDao => "projectmanagent_chance_linkman",//dao·��ע��
                    objField => "linkmanName",
                    "relationField" => "chanceId",//���������ֶ���
                    "linkmanName" => "�ͻ���ϵ��",
                    "linkmanId" => "��ϵ��ID",
                    "telephone" => "�绰",
                    "QQ" => "QQ",
                    "Email" => "�ʼ�",
                    "remark" => "��ϵ�˱�ע "
                ),
                "goods" => array(
                    objName => "��Ʒ",
                    objDao => "projectmanagent_chance_goods",//dao·��ע��
                    objField => "goodsName",
                    "relationField" => "chanceId",//���������ֶ���
                    "goodsId" => "��Ʒid",
                    "goodsTypeId" => "��Ʒ����id",
                    "goodsTypeName" => "��Ʒ����",
                    "goodsName" => "��Ʒ����",
                    "number" => "����",
                    "money" => "��� "
                ),
                "hardware" => array(
                    objName => "�豸Ӳ��",
                    objDao => "projectmanagent_chance_hardware",//dao·��ע��
                    objField => "hardwareName",
                    "relationField" => "chanceId",//���������ֶ���
                    "hardwareId" => "�豸Ӳ��id",
                    "hardwareName" => "�豸Ӳ��",
                    "number" => "����",
                    "money" => "��� "
                ),
                "competitor" => array(
                    objName => "��������",
                    objDao => "projectmanagent_chance_competitor",//dao·��ע��
                    objField => "competitor",
                    "relationField" => "chanceId",//���������ֶ���
                    "competitor" => "��������",
                    "superiority" => "��������",
                    "disadvantaged" => "��������",
                    "remark" => "��ע"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_chance'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            )),
    "outsourcingapproval" =>
        array(
            objName => "���������Ϣ",
            objDao => "outsourcing_approval_basic",//dao·��ע��
            "mainTable" => "oa_outsourcing_approval_changlog", //�����¼��������
            "detailTable" => "oa_outsourcing_approval_changedetail",//�����¼��ϸ������
            "changeTagField" => 'changeTips',//������ѱ�ʶ
            //��������ֶ�ע��
            "register" => array(
                "projectCode" => "��Ŀ���",
                "projectName" => "��Ŀ����",
                "projectTypeName" => "��Ŀ����",
                "projectAddress" => "��Ŀʵʩ��",
                "outsourcingName" => "�����ʽ",
                "outContractCode" => "������",
                "projectManangerName" => "��Ŀ����",
                "saleManangerName" => "���۸�����",
                "suppName" => "�����Ӧ��",
                "beginDate" => "��ʼ����",
                "endDate" => "��������",
                "orderMoney" => "��ͬ���",
                "outSuppMoney" => "������",
                "grossProfit" => "��Ŀë��",
                "payTypeName" => "���ʽ",
                "taxPoint" => "��ֵ˰ר�÷�Ʊ˰��",
                "remark" => "��ע˵��",
                "projectRental" => array(
                    objName => "����/�ְ�",
                    objDao => "outsourcing_approval_projectRental",//dao·��ע��
                    objField => "productName",
                    "relationField" => "mainId",//���������ֶ���
                    "parentName" => "���ô���",
                    "costType" => "����С��",
                    "suppName" => "��Ӧ��",
                    "price" => "�۸�",
                    "number" => "����",
                    "period" => "����",
                    "amount" => "С��",
                    "price" => "��ע"
                ),
                "personList" => array(
                    objName => "��Ա����",
                    objDao => "outsourcing_approval_persronRental",//dao·��ע��
                    objField => "pesonName",
                    "relationField" => "mainId",//���������ֶ���
                    "personLevelName" => "����",
                    "pesonName" => "����",
                    "suppName" => "���������Ӧ��",
                    "beginDate" => "���޿�ʼ����",
                    "endDate" => "���޽�������",
                    "totalDay" => "����",
                    "inBudgetPrice" => "���������ɱ�����(Ԫ/��)",
                    "selfPrice" => "���������ɱ�",
                    "outBudgetPrice" => "�������(Ԫ/��)",
                    "rentalPrice" => "����۸�",
                    "skillsRequired" => "��������Ҫ��",
                    "remark" => "��ע"
                )
            )
        )
    //�⳵��ͬ���
, "rentcar" =>
        array(
            objName => "�⳵��ͬ",
            objDao => "outsourcing_contract_rentcar",
            "mainTable" => "oa_contract_rentcar_changelog",
            "detailTable" => "oa_contract_rentcar_changelogdetail",
            //��������ֶ�ע��
            "register" => array(
                "orderName" => "��ͬ����",
                "principalName" => "����������",
                "principalId" => "������ID",
                "signCompany" => "ǩԼ��˾",
                "signCompanyId" => "ǩԼ��˾ID",
                "companyProvince" => "��˾ʡ��",
                "companyProvinceCode" => "��˾ʡ�ݱ���",
                "companyCity" => "��˾����",
                "companyCityCode" => "��˾���б���",
                "deptId" => "�����˲���id",
                "deptName" => "�����˲�������",
                "orderMoney" => "��ͬ���",
                "linkman" => "��ϵ��",
                "isNeedStamp" => "�Ƿ���Ҫ����",
                "stampType" => "��������",
                "phone" => "��ϵ�绰",
                "ownCompany" => "������˾",
                "ownCompanyId" => "������˾id",
                "signDate" => "ǩԼ����",
                "contractStartDate" => "��ͬ��ʼ����",
                "contractEndDate" => "��ͬ��������",
                "contractUseDay" => "��ͬ�ó�����",
                "address" => "��ϵ��ַ",
                "rentUnitPrice" => "���޵���",
                "oilPrice" => "�ͼ�",
                "fuelCharge" => "ȼ�ͷѵ���",
                "fundCondition" => "��������",
                "contractContent" => "��ͬ����",
                "isUseOilcard" => "�Ƿ�ʹ���Ϳ�",
                "oilcardMoney" => "�Ϳ����",
                "projectId" => "��ĿID",
                "projectManagerId" => "��Ŀ�����ʺ�",
                "projectManager" => "��Ŀ����",
                "officeName" => "��������",
                "officeId" => "����ID",
                "projectCode" => "��Ŀ���",
                "projectName" => "��Ŀ����",
                "projectType" => "��Ŀ����",
                "rentUnitPriceCalWay" => "�Ʒѷ�ʽ",
//				"payBankName" => "��������",
//				"payBankNum" => "�����˺�",
//				"payMan" => "������",
//				"payConditions" => "��������",
//				"payType" => "���ʽ",
//				"payApplyMan" => "����������",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_rentcar'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                ),
                "vehicle" => array(
                    objName => "���޳���״��",
                    objDao => "outsourcing_contract_vehicle",//dao·��ע��
                    objField => "carNumber",
                    "relationField" => "contractId",//���������ֶ���
                    "carModel" => "����",
                    "carNumber" => "���ƺ�",
                    "driver" => "��ʻԱ",
                    "idNumber" => "��ʻԱ���֤��",
                    "displacement" => "������ʹ�ú�������",
                    "oilCarUse" => '�Ϳ��ֳ�',
                    "oilCarAmount" => '�Ϳ����'
                ),
                "fee" => array(
                    objName => "��ͬ���ӷ���",
                    objDao => "outsourcing_contract_rentcarfee",//dao·��ע��
                    objField => "feeName",
                    "relationField" => "contractId",//���������ֶ���
                    "feeName" => "��������",
                    "feeAmount" => "���ý��"
                ),
                "payInfo" => array(
                    objName => "������Ϣ",
                    objDao => "outsourcing_contract_payInfo",//dao·��ע��
                    //objField => "feeName",
                    isFalseDel => true,
                    "relationField" => "mainId",//���������ֶ���
                    // "includeFeeTypeCode" => "�������������",
                    "includeFeeType" => "����������",
                    "bankReceiver" => "�տ���",
                    "bankAccount" => "�տ��˺�",
                    "bankName" => "�տ�����",
                    "bankInfoId" => "�տ���ϢID",
                    "payTypeCode" => "֧����ʽ����",
                    "payType" => "֧����ʽ����",
                    "remark" => "��ע"
                )
            )
        ),
    //�⳵��ͬǩ��
    "rentcarSign" =>
        array(
            objName => "�⳵��ͬ",
            objDao => "outsourcing_contract_rentcar",
            "mainTable" => "oa_contract_rentcar_signlog",
            "detailTable" => "oa_contract_rentcar_signlogdetail",
            //ǩ�ն����ֶ�ע��
            "register" => array(
                "orderName" => "��ͬ����",
                "principalName" => "����������",
                "principalId" => "������ID",
                "signCompany" => "ǩԼ��˾",
                "companyProvince" => "��˾ʡ��",
                "companyProvinceCode" => "��˾ʡ�ݱ���",
                "companyCity" => "��˾����",
                "companyCityCode" => "��˾���б���",
                "deptId" => "�����˲���id",
                "deptName" => "�����˲�������",
                "orderMoney" => "��ͬ���",
                "linkman" => "��ϵ��",
                "phone" => "��ϵ�绰",
                "ownCompany" => "������˾",
                "ownCompanyId" => "������˾id",
                "signDate" => "ǩԼ����",
                "contractStartDate" => "��ͬ��ʼ����",
                "contractEndDate" => "��ͬ��������",
                "contractUseDay" => "��ͬ�ó�����",
                "address" => "��ϵ��ַ",
                "rentUnitPrice" => "���޵���",
                "oilPrice" => "�ͼ�",
                "fuelCharge" => "ȼ�ͷѵ���",
                "fundCondition" => "��������",
                "contractContent" => "��ͬ����",
                "isUseOilcard" => "�Ƿ�ʹ���Ϳ�",
                "oilcardMoney" => "�Ϳ����",
                "projectId" => "��ĿID",
                "projectCode" => "��Ŀ���",
                "projectName" => "��Ŀ����",
                "projectType" => "��Ŀ����",
                "payBankName" => "��������",
                "payBankNum" => "�����˺�",
                "payMan" => "������",
                "payConditions" => "��������",
                "payType" => "���ʽ",
                "payApplyMan" => "����������",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_rentcar'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                ),
                "vehicle" => array(
                    objName => "���޳���״��",
                    objDao => "outsourcing_contract_vehicle",//dao·��ע��
                    objField => "carNumber",
                    "relationField" => "contractId",//���������ֶ���
                    "carModel" => "����",
                    "carNumber" => "���ƺ�",
                    "driver" => "��ʻԱ",
                    "idNumber" => "��ʻԱ���֤��",
                    "displacement" => "������ʹ�ú�������",
                    "oilCarUse" => '�Ϳ��ֳ�',
                    "oilCarAmount" => '�Ϳ����'
                )
            )
        ),
    // �����
    "loanList" =>
        array(
            objName => "��",
            objDao => "loan_loan_loan",//dao·��ע��
            "mainTable" => "loan_list_change", //�����¼��������
            "detailTable" => "loan_list_changedetail",//�����¼��ϸ������
            //��������ֶ�ע��
            "register" => array(
                "PrepaymentDate" => "Ԥ�ƹ黹����",
                "ProjectNo" => "��Ŀ���",
                "rendHouseStartDate" => "�ⷿ��ʼʱ��",
                "rendHouseEndDate" => "�ⷿ����ʱ��",
                "isBeyondBudget" => "�Ƿ񳬹�������ĿԤ��",
                "hasFilesNum" => "�ϴ���������",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'Loan_list'",
                    objName => "������Ϣ",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "��������"
                )
            )
        )
);