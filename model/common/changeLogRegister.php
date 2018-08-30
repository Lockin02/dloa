<?php
define('objName', 'objName_change');
define('objTable', 'objTable_change');
define('objDao', 'objDao_change');
//附件信息
$uploadFileArr = array(
    objName => "附件信息",
    objDao => "file_uploadfile_management",
    "relationField" => "serviceId",
    "originalName" => "附件名称"
);
//注册变更对象记录表信息
$logObjArr = array("purchasecontract" =>
    array(
        objName => "采购订单",
        //objTable=>"oa_purch_apply_basic",//变更主对象表
        objDao => "purchase_contract_purchasecontract",//dao路径注册
        "mainTable" => "oa_purchase_contract_changlog", //变更记录主表名称
        "detailTable" => "oa_purchase_contract_changedetail",//变更记录明细表名称
        //变更对象字段注册
        "register" => array(
            "dateHope" => "预计到货日期",
            "dateFact" => "期望完成日期",
            "allMoney" => "订单总金额",
            "billingType" => "code发票类型",
            "billingTypeName" => "发票类型",
            "paymentCondition" => "code付款条件",
            "paymentConditionName" => "付款条件",
            "paymentType" => "code付款类型",
            "paymentTypeName" => "付款类型",
            "payRatio" => "预付款条件",
            "signStatus" => "code合同签约状态",
            "signStatus_cn" => "合同签约状态",
            "instruction" => "采购说明 ",
            "suppName" => "供应商",
            "suppId" => "供应商ID",
            "suppTel" => "供应商银电话",
            "suppAccount" => "供应商银行账号",
            "suppBankName" => "开户银行",
            "remark" => "备注",
            "equs" => array(
                objName => "采购订单明细",
                //objTable=>"oa_purch_apply_equ",//变更对象明细对象表
                objDao => "purchase_contract_equipment",//dao路径注册
                objField => "productName",
                "relationField" => "basicId",//关联主表字段名
                "amountAll" => "采购订单数量",
                "productName" => "物料名称",
                "dateHope" => "期望到货时间 ",
                "dateIssued" => "到货时间 ",
                "applyPrice" => "含税单价",
                "price" => "单价",
                "taxRate" => "税率",
                "moneyAll" => "物料金额"
            ),
            "uploadFiles" => array(
                uploadFilesTypeArr => "'oa_purch_apply_basic'",
                objName => "附件信息",
                objDao => "file_uploadfile_management",
                "relationField" => "serviceId",
                "originalName" => "附件名称"
            )
        )
    ), "purchasesign" =>
    array(
        objName => "采购订单",
        //objTable=>"oa_purch_apply_basic",//变更主对象表
        objDao => "purchase_contract_purchasecontract",//dao路径注册
        "mainTable" => "oa_purchase_apply_signlog", //变更记录主表名称
        "detailTable" => "oa_purchase_apply_signdetail",//变更记录明细表名称
        //变更对象字段注册
        "register" => array(
            "dateHope" => "预计到货日期",
            "dateFact" => "期望完成日期",
            "allMoney" => "订单总金额",
            "billingType" => "code发票类型",
            "billingTypeName" => "发票类型",
            "paymentCondition" => "code付款条件",
            "paymentConditionName" => "付款条件",
            "paymentType" => "code付款类型",
            "paymentTypeName" => "付款类型",
            "payRatio" => "预付款条件",
            "signStatus" => "code合同签约状态",
            "signStatus_cn" => "合同签约状态",
            "instruction" => "采购说明 ",
            "suppAccount" => "供应商银行账号",
            "suppBankName" => "开户银行",
            "remark" => "备注",
            "equs" => array(
                objName => "采购订单明细",
                //objTable=>"oa_purch_apply_equ",//变更对象明细对象表
                objDao => "purchase_contract_equipment",//dao路径注册
                objField => "productName",
                "relationField" => "basicId",//关联主表字段名
                "amountAll" => "采购订单数量",
                "productName" => "物料名称",
                "dateHope" => "期望到货时间 ",
                "dateIssued" => "到货时间 ",
                "applyPrice" => "单价"
            ),
            "uploadFiles" => array(
                uploadFilesTypeArr => "'oa_purch_apply_basic'",
                objName => "附件信息",
                objDao => "file_uploadfile_management",
                "relationField" => "serviceId",
                "originalName" => "附件名称"
            )
        )
    ),
    "purchasetask" =>
        array(
            objName => "采购任务",
            objDao => "purchase_task_basic",//dao路径注册
            "mainTable" => "oa_purchase_task_changlog", //变更记录主表名称
            "detailTable" => "oa_purchase_task_changedetail",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "dateHope" => "希望完成日期",
                "sendName" => "负责人",
                "sendUserId" => "负责人ID",
                "instruction" => "采购说明 ",
                "remark" => "备注",
                "equment" => array(
                    objName => "采购任务清单",
                    objDao => "purchase_task_equipment",//dao路径注册
                    objField => "productName",
                    "relationField" => "basicId",//关联主表字段名
                    "amountAll" => "任务数量",
                    "productName" => "物料名称",
                    "dateHope" => "希望到货时间 "
                )
            )
        ),
    "purchaseplan" =>
        array(
            objName => "采购申请",
            objDao => "purchase_plan_basic",//dao路径注册
            "mainTable" => "oa_purchase_plan_changlog", //变更记录主表名称
            "detailTable" => "oa_purchase_plan_changedetail",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "dateHope" => "希望完成日期",
                "instruction" => "采购说明 ",
                "remark" => "备注",
                "equment" => array(
                    objName => "采购申请清单",
                    objDao => "purchase_plan_equipment",//dao路径注册
                    objField => "productName",
                    "relationField" => "basicId",//关联主表字段名
                    "amountAll" => "申请数量",
                    "productName" => "物料名称",
                    "dateHope" => "希望到货时间 "
                )
            )
        )
, "order" =>
        array(
            objName => "销售合同",
            objDao => "projectmanagent_order_order",//dao路径注册
            "mainTable" => "oa_sale_order_changlog", //变更记录主表名称
            "detailTable" => "oa_sale_order_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderCode" => "鼎力合同号",
                "orderstate" => "纸质合同状态",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "parentOrder" => "父合同名称",
                "parentOrderId" => "父合同ID",
                "orderTempMoney" => "预计合同金额",
                "prinvipalName" => "合同负责人",
                "prinvipalId" => "合同负责人ID",
                "orderMoney" => "签约合同金额",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "customerProvince" => "客户所属省份",
                "address" => "客户地址",
                "deliveryDate" => "交货日期",
                "district" => "归属区域 ",
                "districtId" => "归属区域Id",
                "saleman" => " 销售员",
                "warrantyClause" => "保修条款 ",
                "afterService" => "售后要求",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "signIn" => "签收状态",
                "shipCondition" => "发货条件",
                "remark" => "备注",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "orderequ" => array(
                    objName => "产品清单",
                    objDao => "projectmanagent_order_orderequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "projArraDate" => "计划交货日期 ",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "linkman" => array(
                    objName => "客户联系人",
                    objDao => "projectmanagent_order_linkman",//dao路径注册
                    objField => "linkman",
                    "relationField" => "orderId",//关联主表字段名
                    "linkman" => "客户联系人",
                    "telephone" => "电话",
                    "Email" => "邮件",
                    "remark" => "联系人备注 "
                ),
                "invoice" => array(
                    objName => "开票计划",
                    objDao => "projectmanagent_order_invoice",//dao路径注册
                    objField => "remark",
                    "relationField" => "orderId",//关联主表字段名
                    "money" => "开票金额 ",
                    "softM" => "软件金额 ",
                    "iType" => "开票类型",
                    "invDT" => "开票日期 ",
                    "remark" => "开票内容 "
                ),
                "receiptplan" => array(
                    objName => "收款计划 ",
                    objDao => "projectmanagent_order_receiptplan",//dao路径注册
                    objField => "collectionTerms",
                    "relationField" => "orderID",//关联主表字段名
                    "money" => "收款金额 ",
                    "payDT" => "收款日期",
                    "pType" => "收款方式",
                    "collectionTerms" => "开收款条件"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "projectmanagent_order_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            ))
, "orderSignin" =>
        array(
            objName => "销售合同签收",
            objDao => "projectmanagent_order_order",//dao路径注册
            "mainTable" => "oa_sale_order_signin", //变更记录主表名称
            "detailTable" => "oa_sale_order_signininfo",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderstate" => "纸质合同状态",
                "orderCode" => "鼎力合同号",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "parentOrder" => "父合同名称",
                "parentOrderId" => "父合同ID",
                "orderTempMoney" => "预计合同金额",
                "prinvipalName" => "合同负责人",
                "prinvipalId" => "合同负责人ID",
                "orderMoney" => "签约合同金额",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "customerProvince" => "客户所属省份",
                "address" => "客户地址",
                "deliveryDate" => "交货日期",
                "district" => "归属区域 ",
                "districtId" => "归属区域Id",
                "saleman" => " 销售员",
                "warrantyClause" => "保修条款 ",
                "afterService" => "售后要求",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "remark" => "备注",
                "customerName" => "客户名称",
                "customerId" => "客户Id",
                "signinType" => "签收类型",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "areaName" => "合同所属区域",
                "areaCode" => "合同所属区域Id",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",
                "orderequ" => array(
                    objName => "产品清单",
                    objDao => "projectmanagent_order_orderequ",//dao路径注册
                    objField => "productName",
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "projArraDate" => "计划交货日期 ",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "linkman" => array(
                    objName => "客户联系人 ",
                    objDao => "projectmanagent_order_linkman",//dao路径注册
                    objField => "linkman",
                    "relationField" => "orderId",//关联主表字段名
                    "linkman" => "客户联系人",
                    "telephone" => "电话",
                    "Email" => "邮件",
                    "remark" => "联系人备注 "
                ),
                "invoice" => array(
                    objName => "开票计划  ",
                    objDao => "projectmanagent_order_invoice",//dao路径注册
                    objField => "remark",
                    "relationField" => "orderId",//关联主表字段名
                    "money" => "开票金额 ",
                    "softM" => "软件金额 ",
                    "iType" => "开票类型",
                    "invDT" => "开票日期 ",
                    "remark" => "开票内容 "
                ),
                "receiptplan" => array(
                    objName => "收款计划 ",
                    objDao => "projectmanagent_order_receiptplan",//dao路径注册
                    objField => "collectionTerms",
                    "relationField" => "orderID",//关联主表字段名
                    "money" => "收款金额 ",
                    "payDT" => "收款日期",
                    "pType" => "收款方式",
                    "collectionTerms" => "开收款条件"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "projectmanagent_order_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )),
    "servicecontract" =>
        array(
            objName => "服务合同",
            objDao => "engineering_serviceContract_serviceContract",//dao路径注册
            "mainTable" => "oa_sale_service_changlog", //变更记录主表名称
            "detailTable" => "oa_sale_service_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderstate" => "纸质合同状态",
                "orderCode" => "鼎力合同号",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "orderTempMoney" => "预计合同金额",
                "customerLinkman" => "签约方联系人",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "linkmanNo" => "联系人电话",
                "orderPrincipal" => "销售负责人",
                "orderPrincipalId" => "销售负责人Id",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "timeLimit" => "交货日期",
                "sectionPrincipal" => "部门负责人",
                "sectionPrincipalId" => "部门负责人Id",
                "sciencePrincipal" => "技术负责人",
                "sciencePrincipalId" => "技术负责人Id",
                "saleman" => " 销售员",
                "orderMoney" => "签约合同金额",
                "contractPeriod" => "合同期限",
                "district" => "归属区域",
                "districtId" => "归属区域Id",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "isShipments" => "是否发货",
                "signIn" => "签收状态",
                "shipCondition" => "发货条件",
                "remark" => "备注",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "serviceequ" => array(
                    objName => "设备清单",
                    objDao => "engineering_serviceContract_serviceequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,//假删除，业务对象通过isDel标志
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "projArraDate" => "计划交货日期 ",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "servicelist" => array(
                    objName => "配置清单/服务内容",
                    objDao => "engineering_serviceContract_servicelist",//dao路径注册
                    objField => "serviceItem",
                    "relationField" => "orderId",//关联主表字段名
                    "serviceItem" => "服务内容",
                    "serviceNo" => "数量/人数",
                    "serviceRemark" => "服务详细",
                    "license" => "服务加密信息 "
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "engineering_serviceContract_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "serviceSignin" =>
        array(
            objName => "服务合同签收",
            objDao => "engineering_serviceContract_serviceContract",//dao路径注册
            "mainTable" => "oa_sale_service_signin", //变更记录主表名称
            "detailTable" => "oa_sale_service_signininfo",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderstate" => "纸质合同状态",
                "orderCode" => "鼎力合同号",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "orderTempMoney" => "预计合同金额",
                "customerLinkman" => "签约方联系人",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "linkmanNo" => "联系人电话",
                "orderPrincipal" => "销售负责人",
                "orderPrincipalId" => "销售负责人Id",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "timeLimit" => "交货日期",
                "sectionPrincipal" => "部门负责人",
                "sectionPrincipalId" => "部门负责人Id",
                "sciencePrincipal" => "技术负责人",
                "sciencePrincipalId" => "技术负责人Id",
                "saleman" => " 销售员",
                "orderMoney" => "签约合同金额",
                "contractPeriod" => "合同期限",
                "district" => "归属区域",
                "districtId" => "归属区域Id",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "isShipments" => "是否发货",
                "remark" => "备注",
                "cusName" => "客户名称",
                "cusNameId" => "客户Id",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "areaName" => "合同所属区域",
                "areaCode" => "合同所属区域Id",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",
                "serviceequ" => array(
                    objName => "设备清单",
                    objDao => "engineering_serviceContract_serviceequ",//dao路径注册
                    objField => "productName",
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "projArraDate" => "计划交货日期 ",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "servicelist" => array(
                    objName => "配置清单/服务内容",
                    objDao => "engineering_serviceContract_servicelist",//dao路径注册
                    objField => "serviceItem",
                    "relationField" => "orderId",//关联主表字段名
                    "serviceItem" => "服务内容",
                    "serviceNo" => "数量/人数",
                    "serviceRemark" => "服务详细",
                    "license" => "服务加密信息 "
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "engineering_serviceContract_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rentalcontract" =>
        array(
            objName => "租赁合同",
            objDao => "contract_rental_rentalcontract",//dao路径注册
            "mainTable" => "oa_sale_lease_changlog", //变更记录主表名称
            "detailTable" => "oa_sale_lease_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderstate" => "纸质合同状态",
                "orderCode" => "鼎力合同号",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "orderTempMoney" => "预计合同金额",
                "tenantName" => "承租方负责人",
                "tenantId" => "承租方负责人Id",
                "hiresName" => "出租方负责人",
                "hiresId" => "出租方负责人ID",
                "timeLimit" => "期限",
                "beginTime" => "承租开始日期",
                "closeTime" => "承租截止日期",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "details" => "支付详情",
                "remark" => "备注",
                "scienceMan" => "技术负责人",
                "scienceManId" => "技术负责人Id",
                "saleman" => " 销售员",
                "orderMoney" => "签约合同金额",
                "district" => "归属区域",
                "districtId" => "归属区域Id",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "signIn" => "签收状态",
                "shipCondition" => "发货条件",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "rentalcontractequ" => array(
                    objName => "产品清单",
                    objDao => "contract_rental_tentalcontractequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "proBeginTime" => "承租开始日期",
                    "proEndTime" => "承租开始日期",
                    "license" => "加密配置"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "contract_rental_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rentalcontractSignin" =>
        array(
            objName => "租赁合同签收",
            objDao => "contract_rental_rentalcontract",//dao路径注册
            "mainTable" => "oa_sale_lease_signin", //变更记录主表名称
            "detailTable" => "oa_sale_lease_signininfo",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderstate" => "纸质合同状态",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "orderTempMoney" => "预计合同金额",
                "tenantName" => "承租方负责人",
                "hiresName" => "出租方负责人",
                "hiresId" => "出租方负责人ID",
                "timeLimit" => "期限",
                "beginTime" => "承租开始日期",
                "closeTime" => "承租截止日期",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "details" => "支付详情",
                "remark" => "备注",
                "scienceMan" => "技术负责人",
                "scienceManId" => "技术负责人Id",
                "saleman" => " 销售员",
                "orderMoney" => "签约合同金额",
                "district" => "归属区域",
                "districtId" => "归属区域Id",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "tenant" => "客户名称",
                "tenantId" => "客户ID",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "areaName" => "合同所属区域",
                "areaCode" => "合同所属区域Id",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",
                "rentalcontractequ" => array(
                    objName => "产品清单",
                    objDao => "contract_rental_tentalcontractequ",//dao路径注册
                    objField => "productName",
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "contract_rental_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rdproject" =>
        array(
            objName => "研发合同",
            objDao => "rdproject_yxrdproject_rdproject",//dao路径注册
            "mainTable" => "oa_sale_rdproject_changlog", //变更记录主表名称
            "detailTable" => "oa_sale_rdproject_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderCode" => "鼎利合同号",
                "orderstate" => "纸质合同状态",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "orderTempMoney" => "预计合同金额",
                "customerLinkman" => "签约方联系人",
                "parentOrder" => "父合同名称",
                "parentOrderId" => "父合同ID",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "linkmanNo" => "联系人电话",
                "orderPrincipal" => "销售负责人",
                "orderPrincipalId" => "销售负责人Id",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "timeLimit" => "交货日期",
                "sectionPrincipal" => "部门负责人",
                "sectionPrincipalId" => "部门负责人Id",
                "sciencePrincipal" => "技术负责人",
                "sciencePrincipalId" => "技术负责人Id",
                "saleman" => " 销售员",
                "orderMoney" => "签约合同金额",
                "contractPeriod" => "合同期限",
                "district" => "归属区域",
                "districtId" => "归属区域Id",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "signIn" => "签收状态",
                "shipCondition" => "发货条件",
                "remark" => "备注",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "rdprojectequ" => array(
                    objName => "设备清单",
                    objDao => "rdproject_yxrdproject_rdprojectequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "rdproject_yxrdproject_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ),
    "rdprojectSignin" =>
        array(
            objName => "研发合同签收",
            objDao => "rdproject_yxrdproject_rdproject",//dao路径注册
            "mainTable" => "oa_sale_rdproject_signin", //变更记录主表名称
            "detailTable" => "oa_sale_rdproject_signininfo",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "issign" => "是否签约",
                "orderstate" => "纸质合同状态",
                "orderNature" => "code合同属性",
                "orderNatureName" => "合同属性",
                "orderTempMoney" => "预计合同金额",
                "customerLinkman" => "签约方联系人",
                "parentOrder" => "父合同名称",
                "parentOrderId" => "父合同ID",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "linkmanNo" => "联系人电话",
                "orderPrincipal" => "销售负责人",
                "orderPrincipalId" => "销售负责人Id",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "timeLimit" => "交货日期",
                "sectionPrincipal" => "部门负责人",
                "sectionPrincipalId" => "部门负责人Id",
                "sciencePrincipal" => "技术负责人",
                "sciencePrincipalId" => "技术负责人Id",
                "saleman" => " 销售员",
                "orderMoney" => "签约合同金额",
                "contractPeriod" => "合同期限",
                "district" => "归属区域",
                "districtId" => "归属区域Id",
                "rate" => "汇率",
                "currency" => "币别",
                "orderTempMoneyCur" => "预计金额（原币）",
                "orderMoneyCur" => "签约金额（原币）",
                "remark" => "备注",
                "cusName" => "客户名称",
                "cusNameId" => "客户ID",
                "orderProvince" => "所属省份",
                "orderProvinceId" => "所属省份ID",
                "orderCity" => "所属城市",
                "orderCityId" => "所属城市ID",
                "areaName" => "合同所属区域",
                "areaCode" => "合同所属区域Id",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",
                "rdprojectequ" => array(
                    objName => "设备清单",
                    objDao => "rdproject_yxrdproject_rdprojectequ",//dao路径注册
                    objField => "productName",
                    "relationField" => "orderId",//关联主表字段名
                    "number" => "数量",
                    "productName" => "产品名称",
                    "price" => "单价",
                    "money" => "金额 ",
                    "warrantyPeriod" => "保修期 ",
                    "license" => "加密配置"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "rdproject_yxrdproject_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "orderId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => $uploadFileArr
            )
        ), "contractequ" =>
        array(
            objName => "合同发货清单",
            objTable => "oa_contract_equ_link",//变更主对象表
            objDao => "contract_contract_contequlink",//dao路径注册
            "mainTable" => "oa_contract_changlog", //变更记录主表名称
            "detailTable" => "oa_contract_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "equs" => array(
                    objName => "合同发货清单",
                    //objTable=>"oa_purch_apply_equ",//变更对象明细对象表
                    objDao => "contract_contract_equ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    //"contractId"=>"合同id",//关联主表字段名
                    "productName" => "物料名称",
                    "productCode" => "物料编号",
                    //"conProductName"=>"产品编号",
                    "unitName" => "单位",
                    "number" => "数量",
                    "arrivalPeriod" => "交货期",
                    "productModel" => "规格型号"
                    //"remark"=>"备注 "
                ),
            )
        ), "borrowequ" =>
        array(
            objName => "借试用发货清单",
            objTable => "oa_borrow_equ_link",//变更主对象表
            objDao => "projectmanagent_borrow_borrowequlink",//dao路径注册
            "mainTable" => "oa_borrow_changlog", //变更记录主表名称
            "detailTable" => "oa_borrow_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "equs" => array(
                    objName => "借试用发货清单",
                    objTable=>"oa_borrow_equ",//变更对象明细对象表
                    objDao => "projectmanagent_borrow_borrowequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    "relationFieldAsset" => array(
                        array("borrowId" => "borrowId")// 静态字段关联如果是数组,则查询表的字段对应tempObj的字段
                    ),
                    //"contractId"=>"合同id",//关联主表字段名
                    "productName" => "物料名称",
                    "productNo" => "物料编号",
                    //"conProductName"=>"产品编号",
                    "unitName" => "单位",
                    "number" => "数量",
                    "arrivalPeriod" => "交货期",
                    "productModel" => "规格型号"
                    //"remark"=>"备注 "
                ),
            )
        ), "presentequ" =>
        array(
            objName => "赠送发货清单",
            objTable => "oa_present_equ_link",//变更主对象表
            objDao => "projectmanagent_present_presentequlink",//dao路径注册
            "mainTable" => "oa_present_changlog", //变更记录主表名称
            "detailTable" => "oa_present_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "equs" => array(
                    objName => "赠送发货清单",
                    //objTable=>"oa_purch_apply_equ",//变更对象明细对象表
                    objDao => "projectmanagent_present_presentequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    //"contractId"=>"合同id",//关联主表字段名
                    "productName" => "物料名称",
                    "productNo" => "物料编号",
                    //"conProductName"=>"产品编号",
                    "unitName" => "单位",
                    "number" => "数量",
                    "arrivalPeriod" => "交货期",
                    "productModel" => "规格型号"
                    //"remark"=>"备注 "
                ),
            )
        ), "exchangeequ" =>
        array(
            objName => "换货发货清单",
            objTable => "oa_exchange_equ_link",//变更主对象表
            objDao => "projectmanagent_exchange_exchangeequlink",//dao路径注册
            "mainTable" => "oa_exchange_changlog", //变更记录主表名称
            "detailTable" => "oa_exchange_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "equs" => array(
                    objName => "换货发货清单",
                    //objTable=>"oa_purch_apply_equ",//变更对象明细对象表
                    objDao => "projectmanagent_exchange_exchangeequ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "linkId",
                    //"contractId"=>"合同id",//关联主表字段名
                    "productName" => "物料名称",
                    "productNo" => "物料编号",
                    //"conProductName"=>"产品编号",
                    "unitName" => "单位",
                    "number" => "数量",
                    "arrivalPeriod" => "交货期",
                    "productModel" => "规格型号"
                    //"remark"=>"备注 "
                ),
            )
        ), "outplan" =>
        array(
            objName => "发货计划",
            //objTable=>"oa_purch_apply_basic",//变更主对象表
            objDao => "stock_outplan_outplan",//dao路径注册
            "mainTable" => "oa_outplan_changlog", //变更记录主表名称
            "detailTable" => "oa_outplan_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "shipPlanDate" => "计划发货日期",
                "stockName" => "发货仓库",
                "address" => "发货地址",
                "purConcern" => "采购人员关注重点",
                "shipConcern" => "发货人员关注",
                "details" => array(
                    objName => "发货计划明细",
                    //objTable=>"oa_purch_apply_equ",//变更对象明细对象表
                    objDao => "stock_outplan_outplanProduct",//dao路径注册
                    objField => "productName",
                    "relationField" => "mainId",//关联主表字段名
                    "productName" => "产品名称",
                    "productNo" => "产品编号",
                    "unitName" => "单位",
                    "stockName" => "出货仓库名称",
                    "number" => "本次计划发货数量 "
                ),
            )
        ),
    "borrow" =>
        array(
            objName => "借试用",
            objDao => "projectmanagent_borrow_borrow",
            "mainTable" => "oa_borrow_changlog",
            "detailTable" => "oa_borrow_changedetail",
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "beginTime" => "开始日期",
                "closeTime" => "截止日期",
                "deliveryDate" => "交货日期",
                "salesName" => "销售负责人",
                "salesNameId" => "销售负责人ID",
                "scienceName" => "技术负责人",
                "scienceNameId" => "技术负责人ID",
                "shipaddress" => "发货地址",
                "status" => "单据状态",
                "reason" => "申请理由",
                "remark" => "申请原因",
                "remarkapp" => "备注",
                "module" => "所属板块",
                "newProLineStr" => "产品线冗余",
                "borrowequ" => array(
                    objName => "产品清单",
                    objDao => "projectmanagent_borrow_borrowequ",
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "borrowId",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "license" => "加密配置",
                ),
                "product" => array(
                    objName => "产品清单",
                    objDao => "projectmanagent_borrow_product",//dao路径注册
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "borrowId",//关联主表字段名
                    "conProductName" => "产品名称",
                    "conProductId" => "产品ID",
                    "conProductDes" => "产品描述",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "deploy" => "产品配置",
                    "newProLineCode" => "产品线编号 ",
                    "newProLineName" => "产品线名称"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "projectmanagent_borrow_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "borrowId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
            ),
        ),
    "present" =>
        array(
            objName => "赠送",
            objDao => "projectmanagent_present_present",
            "mainTable" => "oa_present_changlog",
            "detailTable" => "oa_present_changedetail",
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "customerName" => "客户名称",
                "customerNameId" => "客户名称ID",
                "areaName" => "归属区域",
                "areaCode" => "归属区域编号",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",
                "customTypeName" => "客户类型",
                "customTypeId" => "客户类型ID",
                "province" => "省份",
                "provinceId" => "省份ID",
                "moduleName" => "所属板块",
                "module" => "所属板块ID",
                "businessBelongName" => "归属公司",
                "businessBelong" => "归属公司ID",
                "feeMan" => "费用承担人",
                "feeManId" => "费用承担人ID",
                "deliveryDate" => "交货日期",
                "reason" => "申请理由",
                "remark" => "备注",
                //变更对象字段注册
                "product" => array(
                    objName => "产品清单",
                    objDao => "projectmanagent_present_product",//dao路径注册
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "presentId",//关联主表字段名
                    "conProductName" => "产品名称",
                    "conProductId" => "产品ID",
                    "conProductDes" => "产品描述",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "deploy" => "产品配置",
                    "costEstimates" => "成本概算"
                ),
                "presentequ" => array(
                    objName => "产品清单",
                    objDao => "projectmanagent_present_presentequ",
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "presentId",
                    "productName" => "物料名称",
                    "productNo" => "物料编号",
                    "prodcutId" => "物料ID",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "license" => "加密配置",
                ),
            ),
        ),
    "outsourcing" =>
        array(
            objName => "外包合同",
            objDao => "contract_outsourcing_outsourcing",
            "mainTable" => "oa_sale_outsourcing_changelog",
            "detailTable" => "oa_sale_outsourcing_changelogdetail",
            //变更对象字段注册
            "register" => array(
                "orderName" => "合同名称",
                "outContractCode" => "外包合同号",
                "signCompanyName" => "签约公司",
                "payCondition" => "付款条件",
                "proName" => "公司省份",
                "proCode" => "公司省份编码",
                "address" => "联系地址",
                "phone" => "联系电话",
                "linkman" => "联系人",
                "orderMoney" => "合同金额",
                "signDate" => "签约日期",
                "beginDate" => "开始日期",
                "endDate" => "结束日期",
                "principalName" => "负责人名称",
                "principalId" => "负责人ID",
                "deptId" => "部门id",
                "deptName" => "部门名称",
                "outsourceType" => "外包类型",
                "outsourceTypeName" => "外包类型名",
                "payType" => "付款类型",
                "payTypeName" => "付款类型名",
                "outsourcing" => "外包方式",
                "outsourcingName" => "外包方式名",
                "projectCode" => "项目编号",
                "projectName" => "项目名称",
                "projectType" => "项目类型",
                "projectTypeName" => "项目类型名",
                "projectId" => "项目id",
                "description" => "合同内容描述",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_outsourcing'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            )
        ),
    "outsourcingSign" =>
        array(
            objName => "外包合同",
            objDao => "contract_outsourcing_outsourcing",
            "mainTable" => "oa_sale_outsourcing_signlog",
            "detailTable" => "oa_sale_outsourcing_signlogdetail",
            //变更对象字段注册
            "register" => array(
                "orderName" => "合同名称",
                "outContractCode" => "外包合同号",
                "signCompanyName" => "签约公司",
                "payCondition" => "付款条件",
                "proName" => "公司省份",
                "proCode" => "公司省份编码",
                "address" => "联系地址",
                "phone" => "联系电话",
                "linkman" => "联系人",
                "orderMoney" => "合同金额",
                "signDate" => "签约日期",
                "beginDate" => "开始日期",
                "endDate" => "结束日期",
                "principalName" => "负责人名称",
                "principalId" => "负责人ID",
                "deptId" => "部门id",
                "deptName" => "部门名称",
                "outsourceType" => "外包类型",
                "outsourceTypeName" => "外包类型名",
                "payType" => "付款类型",
                "payTypeName" => "付款类型名",
                "outsourcing" => "外包方式",
                "outsourcingName" => "外包方式名",
                "projectCode" => "项目编号",
                "projectName" => "项目名称",
                "projectType" => "项目类型",
                "projectTypeName" => "项目类型名",
                "projectId" => "项目id",
                "description" => "合同内容描述",

                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_outsourcing'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            )
        ),
    "other" =>
        array(
            objName => "其他合同",
            objDao => "contract_other_other",
            "mainTable" => "oa_sale_other_changelog",
            "detailTable" => "oa_sale_other_changelogdetail",
            //变更对象字段注册
            "register" => array(
                "orderName" => "合同名称",
                "signCompanyName" => "签约公司",
                "fundCondition" => "款项条件描述",
                "proName" => "公司省份",
                "proCode" => "公司省份编码",
                "address" => "联系地址",
                "phone" => "联系电话",
                "linkman" => "联系人",
                "orderMoney" => "合同金额",
                "taxPoint" => "合同税率",
                "moneyNoTax" => "合同金额(不含税)",
                "invoiceType" => "发票类型",
                "invoiceTypeName" => "发票类型名称",
                "signDate" => "签约日期",
                "principalName" => "负责人名称",
                "principalId" => "负责人ID",
                "deptId" => "部门id",
                "deptName" => "部门名称",
                "projectCode" => "项目编号",
                "projectId" => "项目id",
                "projectName" => "项目名称",
                "projectType" => "项目类型值",
                "projectTypeName" => "项目类型",
                "feeDeptId" => "费用归属部门值",
                "feeDeptName" => "费用归属部门",
                "description" => "合同内容",
                "currency" => "币种",
                "chanceCode" => '商机编号',
                "chanceId" => '商机ID',
                "prefBidDate" => '预计投标日期',
                "contractCode" => '销售合同编号',
                "contractId" => '销售合同ID',
                "projectPrefEndDate" => '销售合同关联项目最晚的预计结束日期',
                "delayPayDays" => '延后回款天数',
                "isBankbackLetter" => '是否是银行保函',
                "backLetterEndDate" => '银行保函结束日期',
                "hasRelativeContract" => '是否有保证金关联其他类合同',
                "relativeContract" => '关联其他类合同号',
                "prefPayDate" => '预计回款时间',
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_other'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                ),
                "costshare" => array(
                    objName => "费用分摊",
                    objDao => "finance_cost_costshare",//dao路径注册
                    "isFalseDel" => true,
                    "relationField" => "objId",//关联主表字段名
                    "relationFieldAsset" => array('objType' => "2"), // 静态关联字段
                    "objCode" => "源单编号",
                    "objType" => "源单类型",
                    "companyName" => "公司主体名称",
                    "company" => "公司主体",
                    "inPeriod" => "入账期间",
                    "belongPeriod" => "归属期间",
                    "shareObjType" => "分摊对象类型",
                    "detailType" => "费用类型",
                    "belongCompany" => "费用归属公司",
                    "belongCompanyName" => "费用归属公司名称",
                    "belongDeptName" => "费用归属部门名称",
                    "belongDeptId" => "费用归属部门Id",
                    "headDeptName" => "二级部门",
                    "headDeptId" => "二级部门Id",
                    "belongId" => "归属负责人id",
                    "belongName" => "归属负责人名称",
                    "chanceCode" => "商机号",
                    "chanceId" => "商机id",
                    "province" => "省份",
                    "customerId" => "客户id",
                    "customerName" => "客户名称",
                    "customerType" => "客户类型",
                    "contractCode" => "销售合同号",
                    "contractId" => "销售合同id",
                    "projectId" => "项目id",
                    "projectCode" => "项目编号",
                    "projectName" => "项目名称",
                    "projectType" => "项目类型",
                    "parentTypeId" => "上级明细id",
                    "parentTypeName" => "上级明细名称",
                    "costTypeId" => "费用明细id",
                    "costTypeName" => "费用明细名称",
                    "costMoney" => "分摊金额",
                    "module" => "所属板块编码",
                    "moduleName" => "所属板块",
                    "supplierName" => "供应商"
                )
            )
        ),
    "otherSign" =>
        array(
            objName => "其他合同",
            objDao => "contract_other_other",
            "mainTable" => "oa_sale_other_signlog",
            "detailTable" => "oa_sale_other_signlogdetail",
            //变更对象字段注册
            "register" => array(
                "orderName" => "合同名称",
                "signCompanyName" => "签约公司",
                "fundCondition" => "款项条件描述",
                "proName" => "公司省份",
                "proCode" => "公司省份编码",
                "address" => "联系地址",
                "phone" => "联系电话",
                "linkman" => "联系人",
                "orderMoney" => "合同金额",
                "taxPoint" => "合同税率",
                "moneyNoTax" => "合同金额(不含税)",
                "invoiceType" => "发票类型",
                "invoiceTypeName" => "发票类型名称",
                "signDate" => "签约日期",
                "principalName" => "负责人名称",
                "principalId" => "负责人ID",
                "deptId" => "部门id",
                "deptName" => "部门名称",
                "projectCode" => "项目编号",
                "projectId" => "项目id",
                "projectName" => "项目名称",
                "projectType" => "项目类型值",
                "projectTypeName" => "项目类型",
                "feeDeptId" => "费用归属部门值",
                "feeDeptName" => "费用归属部门",
                "description" => "合同内容",
                "currency" => "币种",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_other'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                ),
                "costshare" => array(
                    objName => "费用分摊",
                    objDao => "finance_cost_costshare",//dao路径注册
                    "isFalseDel" => true,
                    "relationField" => "objId",//关联主表字段名
                    "relationFieldAsset" => array('objType' => "2"), // 静态关联字段
                    "objCode" => "源单编号",
                    "objType" => "源单类型",
                    "companyName" => "公司主体名称",
                    "company" => "公司主体",
                    "inPeriod" => "入账期间",
                    "belongPeriod" => "归属期间",
                    "shareObjType" => "分摊对象类型",
                    "detailType" => "费用类型",
                    "belongCompany" => "费用归属公司",
                    "belongCompanyName" => "费用归属公司名称",
                    "belongDeptName" => "费用归属部门名称",
                    "belongDeptId" => "费用归属部门Id",
                    "belongId" => "归属负责人id",
                    "belongName" => "归属负责人名称",
                    "chanceCode" => "商机号",
                    "chanceId" => "商机id",
                    "province" => "省份",
                    "customerId" => "客户id",
                    "customerName" => "客户名称",
                    "customerType" => "客户类型",
                    "contractCode" => "销售合同号",
                    "contractId" => "销售合同id",
                    "projectId" => "项目id",
                    "projectCode" => "项目编号",
                    "projectName" => "项目名称",
                    "projectType" => "项目类型",
                    "parentTypeId" => "上级明细id",
                    "parentTypeName" => "上级明细名称",
                    "costTypeId" => "费用明细id",
                    "costTypeName" => "费用明细名称",
                    "costMoney" => "分摊金额",
                    "supplierName" => "供应商"
                )
            )
        )
    //新合同管理 变更
, "contract" =>
        array(
            objName => "合同管理",
            objDao => "contract_contract_contract",//dao路径注册
            "mainTable" => "oa_contract_changlog", //变更记录主表名称
            "detailTable" => "oa_contract_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "sign" => "是否签约",
                "winRate" => "合同赢率",
                "signDate" => "签约时间",
                "contractType" => "code合同类型",
                "contractTypeName" => "合同类型名称",
                "contractNature" => "Code合同属性",
                "contractNatureName" => "合同属性名称",
                "contractCode" => "合同编号",
                "contractName" => "合同名称",
                "prinvipalName" => "合同负责人",
                "prinvipalId" => "合同负责人ID",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "added" => "增值费发票",
                "addedMoney" => "增值费发票金额",
                "exportInv" => "出口发票",
                "exportInvMoney" => "出口发票金额",
                "serviceInv" => "服务发票",
                "serviceInvMoney" => "服务发票金额",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "address" => "客户地址",
                "contractCountry" => "所属国家",
                "contractCountryId" => "所属国家ID",
                "contractProvince" => "所属省份",
                "contractProvinceId" => "所属省份ID",
                "contractCity" => "所属城市",
                "contractCityId" => "所属城市Id",
                "contractSigner" => "合同签署人",
                "contractSignerId" => "合同签署人ID",
                "beginDate" => "合同开始日期",
                "endDate" => "合同结束日期",
                "deliveryDate" => "交货日期",
                "district" => "归属区域 ",
                "districtId" => "归属区域Id",
                "saleman" => " 销售员",
                "goodsTypeStr" => "产品类别",
                "signSubject" => "签约公司",
                "signSubjectName" => "签约公司名称",
                "customerName" => "客户名称",
                "customerId" => "客户id",

                "areaName" => "合同所属区域",
                "areaCode" => "合同所属区域Id",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",

                "rate" => "汇率",
                "currency" => "币别",
                "contractTempMoney" => "预计金额",
                "contractMoney" => "签约金额",
                "contractTempMoneyCur" => "预计金额（原币）",
                "contractMoneyCur" => "签约金额（原币）",

                "paymentterm" => "支付条款",
                "warrantyClause" => "保修条款",
                "afterService" => "售后服务",
                "shipCondition" => "发货条件",
                "remark" => "备注",

                "advance" => "预付款",
                "delivery" => "货到付款",
                "progresspayment" => "按进度付款",
                "progresspaymentterm" => "按进度付款条件",
                "initialpayment" => "初验通过付款",
                "finalpayment" => "终验通过付款",
                "otherpaymentterm" => "其他付款条件",
                "otherpayment" => "其他付款占比",
                "Maintenance" => "维保时间",

                "isSell" => "产品类型标识",
                "signStatus" => "变更签收标识",

                "costEstimates" => "成本概算",
                "costEstimatesTax" => "成本概算（含税）",
                "exgross" => "预计毛利率",
                "saleCost" => "销售确认概算",
                "serCost" => "服务确认概算",
                "rdCost" => "研发确认概算",
                "rlCost" => "租赁确认概算",
                "engConfirm" => "服务概算表示",
                "engConfirmName" => "服务概算确认人",
                "engConfirmId" => "",
                "engConfirmDate" => "服务概算确认时间",
                "saleConfirm" => "销售概算确认标识",
                "saleConfirmName" => "销售概算确认人",
                "saleConfirmId" => "",
                "saleConfirmDate" => "销售概算确认时间",
                "rdproConfirm" => "研发确认概算标识",
                "rdproConfirmName" => "研发确认概算确认人",
                "rdproConfirmId" => "",
                "rdproConfirmDate" => "研发确认概算时间",
                "formBelong" => "数据归属公司",
                "formBelongName" => "数据归属公司名称",
                "businessBelong" => "业务归属公司",
                "businessBelongName" => "业务归属公司名称",
                "invoiceValue" => "开票金额",
                "invoiceCode" => "开票类型编码",
                "exeDeptStr" => "执行区域id串",
                "newProLineStr" => "产品线id串",
                "module" => "所属板块编码",
                "moduleName" => "所属板块",
                "isFrame" => "是否框架合同",
                "newExeDeptStr" => "执行区域id串(传统服务)",
                "xfProLineStr" => "产品线id串(区分销售/服务产品)",
                "paperContract" => "纸质合同",
                "paperContractRemark" => "无纸质合同原因",
                "partAContractCode" => "甲方合同编号",
                "partAContractName" => "甲方合同名称",
                "paperSignTime" => "纸质合同签订时间",
                'payment' => array(
                    objName => "付款条件",
                    objDao => "contract_contract_receiptplan",//dao路径注册
                    objField => "money",
                    isFalseDel => true,
                    "relationField" => "contractId",//关联主表字段名
                    'paymentterm' => '付款条件',
                    'paymentPer' => '付款百分比',
                    'money' => '计划付款金额',
                    'payDT' => '计划付款日期',
                    'remark' => '备注'
                ),
                "product" => array(
                    objName => "产品清单",
                    objDao => "contract_contract_product",//dao路径注册
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "contractId",//关联主表字段名
                    "conProductName" => "产品名称",
                    "conProductId" => "产品ID",
                    "conProductDes" => "产品描述",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "deploy" => "产品配置",
                    "proType" => "产品类型",
                    "proTypeId" => "产品类型id",
                    "exeDeptId" => "执行区域id",
                    "exeDeptName" => "执行区域",
                    "newProLineCode" => "产品线编码",
                    "newProLineName" => "产品线"
                ),
                "financialplan" => array(
                    objName => "收开计划",
                    objDao => "contract_contract_financialplan",//dao路径注册
                    objField => "planDate",
                    isFalseDel => true,
                    "relationField" => "contractId",//关联主表字段名
                    "planDate" => "日期",
                    "invoiceMoney" => "开票金额",
                    "incomeMoney" => "收款金额",
                    "remark" => "备注"
                ),
                "equ" => array(
                    objName => "物料清单",
                    objDao => "contract_contract_equ",//dao路径注册
                    objField => "productName",
                    isFalseDel => true,
                    "relationField" => "contractId",//关联主表字段名
                    "productName" => "物料名称",
                    "prodcutId" => "物料ID",
                    "number" => "数量",
                    "price" => "单价",
                    "priceTax" => "税后单价",
                    "money" => "金额",
                    "moneyTax" => "税后金额",
                    "license" => "加密信息",
                    "toBorrowId" => "借试用id",
                    "toBorrowequId" => "借试用从表ID",
                    "serialId" => "序列号id",
                    "serialName" => "序列号名称",
                    "isBorrowToorder" => "转销售标识",
                    "arrivalPeriod" => "交货期",
                    "warrantyPeriod" => "保修期"
                ),
                "linkman" => array(
                    objName => "客户联系人",
                    objDao => "contract_contract_linkman",//dao路径注册
                    objField => "linkmanName",
                    "relationField" => "contractId",//关联主表字段名
                    "linkmanName" => "客户联系人",
                    "linkmanId" => "联系人ID",
                    "telephone" => "电话",
                    "QQ" => "QQ",
                    "Email" => "邮件",
                    "remark" => "联系人备注 "
                ),
                "invoice" => array(
                    objName => "开票计划",
                    objDao => "contract_contract_invoice",//dao路径注册
                    objField => "remark",
                    "relationField" => "contractId",//关联主表字段名
                    "money" => "开票金额 ",
                    "softM" => "软件金额 ",
                    "iType" => "开票类型",
                    "invDT" => "开票日期 ",
                    "remark" => "开票内容 "
                ),
                "train" => array(
                    objName => "培训计划",
                    objDao => "contract_contract_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "contractId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_contract1','oa_contract_contract2'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            ))
    //新合同签收变更字段注册
, "contractSign" =>
        array(
            objName => "合同管理",
            objDao => "contract_contract_contract",//dao路径注册
            "mainTable" => "oa_contract_signin", //变更记录主表名称
            "detailTable" => "oa_contract_signininfo",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "sign" => "是否签约",
                "signDate" => "签约时间",
                "contractType" => "code合同类型",
                "contractTypeName" => "合同类型名称",
                "contractNature" => "Code合同属性",
                "contractNatureName" => "合同属性名称",
                "contractCode" => "合同编号",
                "contractName" => "合同名称",
                "prinvipalName" => "合同负责人",
                "prinvipalId" => "合同负责人ID",
                "invoiceType" => "code发票类型",
                "invoiceTypeName" => "发票类型",
                "customerName" => "客户名称",
                "customerType" => "code客户类型",
                "customerTypeName" => "客户类型",
                "address" => "客户地址",
                "contractCountry" => "所属国家",
                "contractCountryId" => "所属国家ID",
                "contractProvince" => "所属省份",
                "contractProvinceId" => "所属省份ID",
                "contractCity" => "所属城市",
                "contractCityId" => "所属城市Id",
                "contractSigner" => "合同签署人",
                "contractSignerId" => "合同签署人ID",
                "beginDate" => "合同开始日期",
                "endDate" => "合同结束日期",
                "deliveryDate" => "交货日期",
                "district" => "归属区域 ",
                "districtId" => "归属区域Id",
                "saleman" => " 销售员",

                "areaName" => "合同所属区域",
                "areaCode" => "合同所属区域Id",
                "areaPrincipal" => "区域负责人",
                "areaPrincipalId" => "区域负责人ID",

                "rate" => "汇率",
                "currency" => "币别",
                "contractTempMoney" => "预计金额",
                "contractMoney" => "签约金额",
                "contractTempMoneyCur" => "预计金额（原币）",
                "contractMoneyCur" => "签约金额（原币）",

                "paymentterm" => "支付条款",
                "warrantyClause" => "保修条款",
                "afterService" => "售后服务",
                "shipCondition" => "发货条件",
                "remark" => "备注",
                "formBelong" => "数据归属公司",
                "formBelongName" => "数据归属公司名称",
                "businessBelong" => "业务归属公司",
                "businessBelongName" => "业务归属公司名称",
                "exeDeptStr" => "执行部门id串",
                "product" => array(
                    objName => "产品清单",
                    objDao => "contract_contract_product",//dao路径注册
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "contractId",//关联主表字段名
                    "conProductName" => "产品名称",
                    "conProductId" => "产品ID",
                    "conProductDes" => "产品描述",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "deploy" => "产品配置",
                    "proType" => "产品类型",
                    "proTypeId" => "产品类型id",
                    "exeDeptId" => "执行部门id",
                    "exeDeptName" => "执行部门"
                ),
                "linkman" => array(
                    objName => "客户联系人",
                    objDao => "contract_contract_linkman",//dao路径注册
                    objField => "linkmanName",
                    "relationField" => "contractId",//关联主表字段名
                    "linkmanName" => "客户联系人",
                    "linkmanId" => "联系人ID",
                    "telephone" => "电话",
                    "QQ" => "QQ",
                    "Email" => "邮件",
                    "remark" => "联系人备注 "
                ),
                "invoice" => array(
                    objName => "开票计划",
                    objDao => "contract_contract_invoice",//dao路径注册
                    objField => "remark",
                    "relationField" => "contractId",//关联主表字段名
                    "money" => "开票金额 ",
                    "softM" => "软件金额 ",
                    "iType" => "开票类型",
                    "invDT" => "开票日期 ",
                    "remark" => "开票内容 "
                ),
                "receiptplan" => array(
                    objName => "收款计划 ",
                    objDao => "contract_contract_receiptplan",//dao路径注册
                    objField => "collectionTerms",
                    "relationField" => "contractId",//关联主表字段名
                    "money" => "收款金额 ",
                    "payDT" => "收款日期",
                    "pType" => "收款方式",
                    "collectionTerms" => "开收款条件"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "contract_contract_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "contractId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_contract1','oa_contract_contract2'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            )),
    "produceapply" => array(
        objName => "生产申请单",
        objDao => "produce_apply_produceapply",//dao路径注册
        "mainTable" => "oa_produce_changelog_apply", //变更记录主表名称
        "detailTable" => "oa_produce_changelog_applyitem",//变更记录明细表名称
        "changeTagField" => 'changeTips',//变更提醒标识
        //变更对象字段注册
        "register" => array(
            "items" => array(
                objName => "物料清单",
                objDao => "produce_apply_produceapplyitem",//dao路径注册
                objField => "productName",
                isFalseDel => true,
                "productCode" => "物料编号",
                "productName" => "物料名称",
                "pattern" => "规格型号",
                "unitName" => "单位",
                "produceNum" => "申请数量",
                "planEndDate" => "计划交货时间"
            )
        )
    ),
    "producetask" => array(
        objName => "生产任务",
        objDao => "produce_task_producetask",//dao路径注册
        "mainTable" => "oa_produce_changelog_task", //变更记录主表名称
        "detailTable" => "oa_produce_changelog_taskitem",//变更记录明细表名称
        "changeTagField" => 'changeTips',//变更提醒标识
        //变更对象字段注册
        "register" => array(
            "planStartDate" => "计划开始时间",
            "planEndDate" => "计划结束时间",
            "estimateHour" => "估计工作量(小时)",
            "estimateDay" => "计划工期(天)",
            "taskNum" => "任务数量"
        )
    )
    //商机变更
, "chance" =>
        array(
            objName => "商机管理",
            objDao => "projectmanagent_chance_chance",//dao路径注册
            "mainTable" => "oa_chance_changlog", //变更记录主表名称
            "detailTable" => "oa_chance_changedetail",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "chanceCode" => "商机编号",
                "chanceName" => "商机名称",
                "chanceLevel" => "商机等级",
                "chanceStage" => "商机阶段",
                "winRate" => "商机盈率",
                "chanceType" => "商机类型",
                "chanceTypeName" => "商机类型名称",
                "chanceNature" => "类型属性",
                "chanceNatureName" => "类型属性名称",
                "chanceMoney" => "商机金额",
                "predictContractDate" => "预计签订合同日期",
                "predictExeDate" => "预计合同执行日期",
                "contractPeriod" => "合同执行周期长度",
                "newUpdateDate" => "最近更新时间",
                "customerName" => "客户名称",
                "customerType" => "客户类型",
                "remark" => "商机备注",
                "progress" => "项目进展描述",
                "Country" => "所属国家",
                "Province" => "所属省份",
                "City" => "所属城市",
                "areaName" => "合同归属区域",
                "areaPrincipal" => "区域负责人",
                "prinvipalName" => "商机负责人",
                "signSubject" => "签约主体",
                "signSubjectName" => "签约主体名称",
                "formBelong" => "数据归属公司",
                "formBelongName" => "数据归属公司名称",
                "businessBelong" => "业务归属公司",
                "businessBelongName" => "业务归属公司名称",
                "module" => "所属板块",
                "product" => array(
                    objName => "产品清单",
                    objDao => "contract_contract_product",//dao路径注册
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "contractId",//关联主表字段名
                    "conProductName" => "产品名称",
                    "conProductId" => "产品ID",
                    "conProductDes" => "产品描述",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "deploy" => "产品配置",
                    "newProLineCode" => "产品线编号 ",
                    "newProLineName" => "产品线名称"
                ),
                "linkman" => array(
                    objName => "客户联系人",
                    objDao => "contract_contract_linkman",//dao路径注册
                    objField => "linkmanName",
                    "relationField" => "contractId",//关联主表字段名
                    "linkmanName" => "客户联系人",
                    "linkmanId" => "联系人ID",
                    "telephone" => "电话",
                    "QQ" => "QQ",
                    "Email" => "邮件",
                    "remark" => "联系人备注 "
                ),
                "invoice" => array(
                    objName => "开票计划",
                    objDao => "contract_contract_invoice",//dao路径注册
                    objField => "remark",
                    "relationField" => "contractId",//关联主表字段名
                    "money" => "开票金额 ",
                    "softM" => "软件金额 ",
                    "iType" => "开票类型",
                    "invDT" => "开票日期 ",
                    "remark" => "开票内容 "
                ),
                "receiptplan" => array(
                    objName => "收款计划 ",
                    objDao => "contract_contract_receiptplan",//dao路径注册
                    objField => "collectionTerms",
                    "relationField" => "contractId",//关联主表字段名
                    "money" => "收款金额 ",
                    "payDT" => "收款日期",
                    "pType" => "收款方式",
                    "collectionTerms" => "开收款条件"
                ),
                "trainingplan" => array(
                    objName => "培训计划",
                    objDao => "contract_contract_trainingplan",//dao路径注册
                    objField => "content",
                    "relationField" => "contractId",//关联主表字段名
                    "beginDT" => "培训开始时间 ",
                    "endDT" => "培训结束时间",
                    "traNum" => "参与人数",
                    "adress" => "培训地点",
                    "content" => "培训内容",
                    "trainer" => "培训工程师要求"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_contract1','oa_contract_contract2'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            )),
    "produceapply" => array(
        objName => "生产申请单",
        objDao => "produce_apply_produceapply",//dao路径注册
        "mainTable" => "oa_produce_changelog_apply", //变更记录主表名称
        "detailTable" => "oa_produce_changelog_applyitem",//变更记录明细表名称
        "changeTagField" => 'changeTips',//变更提醒标识
        //变更对象字段注册
        "register" => array(
            "hopeDeliveryDate" => "期望交货日期",
            "relDocType" => "源单类型",
            "relDocCode" => "源单编号",
            "projectName" => "项目名称",
            "remark" => "备注",
            "items" => array(
                objName => "物料信息",
                objDao => "produce_apply_produceapplyitem",//dao路径注册
                objField => "productCode",
                "relationField" => "mainId",//关联主表字段名
                "productCode" => "物料编号",
                "productName" => "物料名称",
                // "pattern" => "规格型号",
                "unitName" => "单位",
                "produceNum" => "申请数量",
                "planEndDate" => "期望交货时间",
                "remark" => "备注"
            )
        )
    ),
    "producetask" => array(
        objName => "生产任务",
        objDao => "produce_task_producetask",//dao路径注册
        "mainTable" => "oa_produce_changelog_task", //变更记录主表名称
        "detailTable" => "oa_produce_changelog_taskitem",//变更记录明细表名称
        "changeTagField" => 'changeTips',//变更提醒标识
        //变更对象字段注册
        "register" => array(
            "planStartDate" => "计划开始时间",
            "planEndDate" => "计划结束时间",
            "estimateHour" => "估计工作量(小时)",
            "estimateDay" => "计划工期(天)",
            "taskNum" => "任务数量"
        )
    )
    //商机变更
, "chance" =>
        array(
            objName => "商机管理",
            objDao => "projectmanagent_chance_chance",//dao路径注册
            "mainTable" => "oa_chance_changlog", //变更记录主表名称
            "detailTable" => "oa_chance_changedetail",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "chanceCode" => "商机编号",
                "chanceName" => "商机名称",
                "chanceLevel" => "商机等级",
                "chanceStage" => "商机阶段",
                "winRate" => "商机盈率",
                "chanceType" => "商机类型",
                "chanceTypeName" => "商机类型名称",
                "chanceNature" => "类型属性",
                "chanceNatureName" => "类型属性名称",
                "chanceMoney" => "商机金额",
                "predictContractDate" => "预计签订合同日期",
                "predictExeDate" => "预计合同执行日期",
                "contractPeriod" => "合同执行周期长度",
                "newUpdateDate" => "最近更新时间",
                "customerName" => "客户名称",
                "customerType" => "客户类型",
                "remark" => "商机备注",
                "progress" => "项目进展描述",
                "Country" => "所属国家",
                "Province" => "所属省份",
                "City" => "所属城市",
                "areaName" => "合同归属区域",
                "areaPrincipal" => "区域负责人",
                "prinvipalName" => "商机负责人",
                "signSubject" => "签约主体",
                "signSubjectName" => "签约主体名称",
                "formBelong" => "数据归属公司",
                "formBelongName" => "数据归属公司名称",
                "businessBelong" => "业务归属公司",
                "businessBelongName" => "业务归属公司名称",

                "product" => array(
                    objName => "详细产品清单",
                    objDao => "projectmanagent_chance_product",//dao路径注册
                    objField => "conProductName",
                    isFalseDel => true,
                    "relationField" => "chanceId",//关联主表字段名
                    "conProductName" => "产品名称",
                    "conProductId" => "产品ID",
                    "conProductDes" => "产品描述",
                    "number" => "数量",
                    "price" => "单价",
                    "money" => "金额 ",
                    "deploy" => "产品配置"
                ),
                "linkman" => array(
                    objName => "客户联系人",
                    objDao => "projectmanagent_chance_linkman",//dao路径注册
                    objField => "linkmanName",
                    "relationField" => "chanceId",//关联主表字段名
                    "linkmanName" => "客户联系人",
                    "linkmanId" => "联系人ID",
                    "telephone" => "电话",
                    "QQ" => "QQ",
                    "Email" => "邮件",
                    "remark" => "联系人备注 "
                ),
                "goods" => array(
                    objName => "产品",
                    objDao => "projectmanagent_chance_goods",//dao路径注册
                    objField => "goodsName",
                    "relationField" => "chanceId",//关联主表字段名
                    "goodsId" => "产品id",
                    "goodsTypeId" => "产品类型id",
                    "goodsTypeName" => "产品类型",
                    "goodsName" => "产品名称",
                    "number" => "数量",
                    "money" => "金额 "
                ),
                "hardware" => array(
                    objName => "设备硬件",
                    objDao => "projectmanagent_chance_hardware",//dao路径注册
                    objField => "hardwareName",
                    "relationField" => "chanceId",//关联主表字段名
                    "hardwareId" => "设备硬件id",
                    "hardwareName" => "设备硬件",
                    "number" => "数量",
                    "money" => "金额 "
                ),
                "competitor" => array(
                    objName => "竞争对手",
                    objDao => "projectmanagent_chance_competitor",//dao路径注册
                    objField => "competitor",
                    "relationField" => "chanceId",//关联主表字段名
                    "competitor" => "竞争对手",
                    "superiority" => "竞争优势",
                    "disadvantaged" => "竞争劣势",
                    "remark" => "备注"
                ),
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_sale_chance'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            )),
    "outsourcingapproval" =>
        array(
            objName => "立项基本信息",
            objDao => "outsourcing_approval_basic",//dao路径注册
            "mainTable" => "oa_outsourcing_approval_changlog", //变更记录主表名称
            "detailTable" => "oa_outsourcing_approval_changedetail",//变更记录明细表名称
            "changeTagField" => 'changeTips',//变更提醒标识
            //变更对象字段注册
            "register" => array(
                "projectCode" => "项目编号",
                "projectName" => "项目名称",
                "projectTypeName" => "项目类型",
                "projectAddress" => "项目实施地",
                "outsourcingName" => "外包方式",
                "outContractCode" => "外包编号",
                "projectManangerName" => "项目经理",
                "saleManangerName" => "销售负责人",
                "suppName" => "外包供应商",
                "beginDate" => "开始日期",
                "endDate" => "结束日期",
                "orderMoney" => "合同金额",
                "outSuppMoney" => "外包金额",
                "grossProfit" => "项目毛利",
                "payTypeName" => "付款方式",
                "taxPoint" => "增值税专用发票税点",
                "remark" => "备注说明",
                "projectRental" => array(
                    objName => "整包/分包",
                    objDao => "outsourcing_approval_projectRental",//dao路径注册
                    objField => "productName",
                    "relationField" => "mainId",//关联主表字段名
                    "parentName" => "费用大类",
                    "costType" => "费用小类",
                    "suppName" => "供应商",
                    "price" => "价格",
                    "number" => "数量",
                    "period" => "周期",
                    "amount" => "小计",
                    "price" => "备注"
                ),
                "personList" => array(
                    objName => "人员租赁",
                    objDao => "outsourcing_approval_persronRental",//dao路径注册
                    objField => "pesonName",
                    "relationField" => "mainId",//关联主表字段名
                    "personLevelName" => "级别",
                    "pesonName" => "姓名",
                    "suppName" => "归属外包供应商",
                    "beginDate" => "租赁开始日期",
                    "endDate" => "租赁结束日期",
                    "totalDay" => "天数",
                    "inBudgetPrice" => "服务人力成本单价(元/天)",
                    "selfPrice" => "服务人力成本",
                    "outBudgetPrice" => "外包单价(元/天)",
                    "rentalPrice" => "外包价格",
                    "skillsRequired" => "工作技能要求",
                    "remark" => "备注"
                )
            )
        )
    //租车合同变更
, "rentcar" =>
        array(
            objName => "租车合同",
            objDao => "outsourcing_contract_rentcar",
            "mainTable" => "oa_contract_rentcar_changelog",
            "detailTable" => "oa_contract_rentcar_changelogdetail",
            //变更对象字段注册
            "register" => array(
                "orderName" => "合同名称",
                "principalName" => "负责人名称",
                "principalId" => "负责人ID",
                "signCompany" => "签约公司",
                "signCompanyId" => "签约公司ID",
                "companyProvince" => "公司省份",
                "companyProvinceCode" => "公司省份编码",
                "companyCity" => "公司城市",
                "companyCityCode" => "公司城市编码",
                "deptId" => "负责人部门id",
                "deptName" => "负责人部门名称",
                "orderMoney" => "合同金额",
                "linkman" => "联系人",
                "isNeedStamp" => "是否需要盖章",
                "stampType" => "盖章类型",
                "phone" => "联系电话",
                "ownCompany" => "归属公司",
                "ownCompanyId" => "归属公司id",
                "signDate" => "签约日期",
                "contractStartDate" => "合同开始日期",
                "contractEndDate" => "合同结束日期",
                "contractUseDay" => "合同用车天数",
                "address" => "联系地址",
                "rentUnitPrice" => "租赁单价",
                "oilPrice" => "油价",
                "fuelCharge" => "燃油费单价",
                "fundCondition" => "款项内容",
                "contractContent" => "合同内容",
                "isUseOilcard" => "是否使用油卡",
                "oilcardMoney" => "油卡金额",
                "projectId" => "项目ID",
                "projectManagerId" => "项目经理帐号",
                "projectManager" => "项目经理",
                "officeName" => "区域名称",
                "officeId" => "区域ID",
                "projectCode" => "项目编号",
                "projectName" => "项目名称",
                "projectType" => "项目类型",
                "rentUnitPriceCalWay" => "计费方式",
//				"payBankName" => "付款银行",
//				"payBankNum" => "付款账号",
//				"payMan" => "付款人",
//				"payConditions" => "付款条件",
//				"payType" => "付款方式",
//				"payApplyMan" => "付款申请人",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_rentcar'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                ),
                "vehicle" => array(
                    objName => "租赁车辆状况",
                    objDao => "outsourcing_contract_vehicle",//dao路径注册
                    objField => "carNumber",
                    "relationField" => "contractId",//关联主表字段名
                    "carModel" => "车型",
                    "carNumber" => "车牌号",
                    "driver" => "驾驶员",
                    "idNumber" => "驾驶员身份证号",
                    "displacement" => "排量、使用何种汽油",
                    "oilCarUse" => '油卡抵充',
                    "oilCarAmount" => '油卡金额'
                ),
                "fee" => array(
                    objName => "合同附加费用",
                    objDao => "outsourcing_contract_rentcarfee",//dao路径注册
                    objField => "feeName",
                    "relationField" => "contractId",//关联主表字段名
                    "feeName" => "费用名称",
                    "feeAmount" => "费用金额"
                ),
                "payInfo" => array(
                    objName => "付款信息",
                    objDao => "outsourcing_contract_payInfo",//dao路径注册
                    //objField => "feeName",
                    isFalseDel => true,
                    "relationField" => "mainId",//关联主表字段名
                    // "includeFeeTypeCode" => "包含费用项编码",
                    "includeFeeType" => "包含费用项",
                    "bankReceiver" => "收款人",
                    "bankAccount" => "收款账号",
                    "bankName" => "收款银行",
                    "bankInfoId" => "收款信息ID",
                    "payTypeCode" => "支付方式编码",
                    "payType" => "支付方式名称",
                    "remark" => "备注"
                )
            )
        ),
    //租车合同签收
    "rentcarSign" =>
        array(
            objName => "租车合同",
            objDao => "outsourcing_contract_rentcar",
            "mainTable" => "oa_contract_rentcar_signlog",
            "detailTable" => "oa_contract_rentcar_signlogdetail",
            //签收对象字段注册
            "register" => array(
                "orderName" => "合同名称",
                "principalName" => "负责人名称",
                "principalId" => "负责人ID",
                "signCompany" => "签约公司",
                "companyProvince" => "公司省份",
                "companyProvinceCode" => "公司省份编码",
                "companyCity" => "公司城市",
                "companyCityCode" => "公司城市编码",
                "deptId" => "负责人部门id",
                "deptName" => "负责人部门名称",
                "orderMoney" => "合同金额",
                "linkman" => "联系人",
                "phone" => "联系电话",
                "ownCompany" => "归属公司",
                "ownCompanyId" => "归属公司id",
                "signDate" => "签约日期",
                "contractStartDate" => "合同开始日期",
                "contractEndDate" => "合同结束日期",
                "contractUseDay" => "合同用车天数",
                "address" => "联系地址",
                "rentUnitPrice" => "租赁单价",
                "oilPrice" => "油价",
                "fuelCharge" => "燃油费单价",
                "fundCondition" => "款项内容",
                "contractContent" => "合同内容",
                "isUseOilcard" => "是否使用油卡",
                "oilcardMoney" => "油卡金额",
                "projectId" => "项目ID",
                "projectCode" => "项目编号",
                "projectName" => "项目名称",
                "projectType" => "项目类型",
                "payBankName" => "付款银行",
                "payBankNum" => "付款账号",
                "payMan" => "付款人",
                "payConditions" => "付款条件",
                "payType" => "付款方式",
                "payApplyMan" => "付款申请人",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'oa_contract_rentcar'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                ),
                "vehicle" => array(
                    objName => "租赁车辆状况",
                    objDao => "outsourcing_contract_vehicle",//dao路径注册
                    objField => "carNumber",
                    "relationField" => "contractId",//关联主表字段名
                    "carModel" => "车型",
                    "carNumber" => "车牌号",
                    "driver" => "驾驶员",
                    "idNumber" => "驾驶员身份证号",
                    "displacement" => "排量、使用何种汽油",
                    "oilCarUse" => '油卡抵充',
                    "oilCarAmount" => '油卡金额'
                )
            )
        ),
    // 借款单变更
    "loanList" =>
        array(
            objName => "借款单",
            objDao => "loan_loan_loan",//dao路径注册
            "mainTable" => "loan_list_change", //变更记录主表名称
            "detailTable" => "loan_list_changedetail",//变更记录明细表名称
            //变更对象字段注册
            "register" => array(
                "PrepaymentDate" => "预计归还日期",
                "ProjectNo" => "项目编号",
                "rendHouseStartDate" => "租房开始时间",
                "rendHouseEndDate" => "租房结束时间",
                "isBeyondBudget" => "是否超过续借项目预算",
                "hasFilesNum" => "上传附件数量",
                "uploadFiles" => array(
                    uploadFilesTypeArr => "'Loan_list'",
                    objName => "附件信息",
                    objDao => "file_uploadfile_management",
                    "relationField" => "serviceId",
                    "originalName" => "附件名称"
                )
            )
        )
);