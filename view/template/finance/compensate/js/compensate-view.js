$(document).ready(function () {
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
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#unitPrice").val()) +
                    "</td>" +
                        "<td style='text-align:right;'>" +
                    moneyFormat2($("#price").val()) +
                    "</td>" +
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
        },  {
            display: '单价',
            name: 'unitPrice',
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

    //显示费用分摊明细
    $("#costshareGrid").costshareGrid({
        objName: 'compensate[costshare]',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {'objType': 1, 'objId': $("#id").val()},
        type: 'view',
        event: {
            'reloadData': function (e, g, data) {
                if (!data) {
                    $("#costshareGrid").hide();
                }
            }
        }
    });

    //显示质检情况
    $("#showQualityReport").showQualityDetail({
        param: {
            "objId": $("#relDocId").val(),
            "objType": $("#qualityObjType").val()
        }
    });
});