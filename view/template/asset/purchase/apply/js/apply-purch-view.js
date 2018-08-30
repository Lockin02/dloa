$(document).ready(function () {
    $("#purchaseProductTable").yxeditgrid({
        objName: 'apply[applyItem]',
        url: '?model=asset_purchase_apply_applyItem&action=purchListJson',
        delTagName: 'isDelTag',
        type: 'view',
        param: {
            applyId: $("#applyId").val(),
            "isDel": '0'
        },
        colModel: [{
            display: '确认物料名称',
            name: 'inputProductName',
            tclass: 'readOnlyTxtItem',
            width: 200
        }, {
            display: '确认物料编号',
            name: 'productCode',
            tclass: 'readOnlyTxtItem',
            width: 80
        }, {
            display: '确认物料名称',
            name: 'productName',
            tclass: 'readOnlyTxtItem',
            width: 200
        }, {
            display: '规格',
            name: 'pattem',
            tclass: 'readOnlyTxtItem',
            width: 100
        }, {
            display: '申请数量',
            name: 'applyAmount',
            tclass: 'txtshort',
            width: 70
        }, {
            display: '供应商',
            name: 'supplierName',
            tclass: 'txtmiddle',
            width: 100
        }, {
            display: '单位',
            name: 'unitName',
            tclass: 'readOnlyTxtItem',
            width: 60
        }, {
            display: '采购数量',
            name: 'purchAmount',
            tclass: 'txtshort',
            width: 70
        }, {
            display: '单价',
            name: 'price',
            tclass: 'txtshort',
            width: 80,
            // type : 'money'
            // 列表格式化千分位
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '金额',
            name: 'moneyAll',
            tclass: 'txtshort',
            // 列表格式化千分位
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            display: '希望交货日期',
            name: 'dateHope',
            type: 'date',
            width: 70
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt',
            width: 120
        }]
    })

    // 根据采购类型来判断是否显示部分的字段
    // alert($("#purchaseType").text());
    if ($("#purchaseType").text() != "计划内 ") {
        $("#hiddenA").hide();
        // $("#hiddenB").hide();
    }

    // 根据采购种类为“研发类”时来显示部分字段（采购分类、重大专项名称、募集资金项目、其它研发项目）
    // alert($("#purchCategory").text());
    if ($("#purchCategory").text() == "研发类") {
        $("#hiddenC").hide();
    } else {
        $("#hiddenD").hide();
        $("#hiddenE").hide();
    }

});