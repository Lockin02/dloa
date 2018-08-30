$(document).ready(function () {
    //负责人
    $("#chargerName").yxselect_user({
        hiddenId: 'chargerId',
        isGetDept: [true, "deptId", "deptName"],
        formCode: 'compensate'
    });

    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });
    var detailObj = $("#detail");
    // 产品清单
    detailObj.yxeditgrid({
        objName: 'compensate[detail]',
        url: '?model=finance_compensate_compensatedetail&action=listJson',
        tableClass: 'form_in_table',
        type: 'view',
        title: "物料清单",
        param: {
            mainId: $("#id").val()
        },
        event: {
            'reloadData': function (e, g, data) {
                if (data.length > 0) {
                    detailObj.find('tbody').after("<tr class='tr_count'>" +
                    "<td></td><td>合计</td><td colspan='4'></td>" +
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#formMoney").val()) +
                    "</td>" +
                    "<td></td>" +
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#compensateMoney").val()) +
                    "</td><td colspan='2'></td>" +
                    "</tr>");
                } else {
                    detailObj.find('tbody').after("<tr class='tr_odd'><td colspan='20'>-- 暂无内容 --</td></tr>");
                }
            }
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden',
            isSubmit: true
        }, {
            display: '物料Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '物料编号',
            name: 'productNo',
            width: 80
        }, {
            display: '物料名称',
            name: 'productName'
        }, {
            display: '规格型号',
            name: 'productModel'
        }, {
            display: '单位',
            name: 'unitName',
            width: 50
        }, {
            display: '数量',
            name: 'number',
            width: 70
        }, {
            display: '预计维修金额',
            name: 'money',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '净值',
            name: 'price',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '赔偿金额',
            name: 'compensateMoney',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '备注',
            name: 'remark',
            width: 70,
            align: 'left'
        }, {
            display: '序列号',
            name: 'serialNos',
            width: 150,
            align: 'left'
        }]
    });

    //表单验证
    validate({
        "formDate": {
            required: true
        },
        "chargerName": {
            required: true
        },
        "deptName": {
            required: true
        },
        "dutyObjName": {
            required: true
        }
    });

    $("#dutyType").change(function () {
        if ($(this).val() == "PCZTLX-01") {
            $("#dutyObjName").val($("#chargerName").val());
            $("#dutyObjId").val($("#chargerId").val());
        } else {
            $("#dutyObjName").val($("#deptName").val());
            $("#dutyObjId").val($("#deptId").val());
        }
    });

    //显示费用分摊明细
    var costshareGridObj = $("#costshareGrid");
    costshareGridObj.costShareGrid({
        objName: 'compensate[costshare]',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {'objType': 1, 'objId': $("#id").val()}
    });

    //绑定表单验证
    $("form").submit(function () {
        return costshareGridObj.costShareGrid('checkForm');
    });

    //显示质检情况
    $("#showQualityReport").showQualityDetail({
        tableClass: 'form_in_table',
        param: {
            "objId": $("#relDocId").val(),
            "objType": $("#qualityObjType").val()
        }
    });
});

//金额调整
function countMoney(rowNum) {
    var detailObj = $("#detail");
    //赋值
    var money = detailObj.yxeditgrid("getCmpByRowAndCol", rowNum, "money").val();
    //如果是正常赔偿
    var compensateType = detailObj.yxeditgrid("getCmpByRowAndCol", rowNum, "compensateType").val();//赔偿方式
    var compensateRate = detailObj.yxeditgrid("getCmpByRowAndCol", rowNum, "compensateRate").val();

    var compensateMoney = accDiv(accMul(money, compensateRate, 2), 100, 2);
    detailObj.yxeditgrid("setRowColValue", rowNum, "compensateMoney", compensateMoney, true);
}

//单据金额计算方法
function countForm() {
    var detailObj = $("#detail");

    //计算单据金额
    var moneyArr = detailObj.yxeditgrid("getCmpByCol", "money");
    var formMoney = 0;
    moneyArr.each(function () {
        formMoney = accAdd(formMoney, $(this).val(), 2);
    });
    setMoney('formMoney', formMoney);

    //计算赔偿金额
    var compensateMoneyArr = detailObj.yxeditgrid("getCmpByCol", "compensateMoney");
    var compensateMoney = 0;
    compensateMoneyArr.each(function () {
        compensateMoney = accAdd(compensateMoney, $(this).val(), 2);
    });
    setMoney('compensateMoney', compensateMoney);
}

//表单提交审批
function audit(thisVal) {
    $("#isSubAudit").val(thisVal);
}

// 选择序列号
function serialNum(rowNum, serialIds, serialNos, returnequId, number, detailId) {
    showThickboxWin('?model=finance_compensate_compensate&action=toSerialNos'
    + '&relDocId=' + $("#relDocId").val()
    + '&relDocType=' + $("#relDocType").val()
    + '&rowNum=' + rowNum
    + '&serialIds=' + serialIds
    + '&serialNos=' + serialNos
    + '&returnequId=' + returnequId
    + '&number=' + number
    + '&id=' + $("#id").val()
    + '&detailId='
    + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=350");
}