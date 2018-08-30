var totalCompensateMoney = 0;
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
                var compensateMoney = priceMoney = 0;
                if(totalCompensateMoney == 0){
                    compensateMoney = moneyFormat2($("#compensateMoney").val());
                }else{
                    compensateMoney = totalCompensateMoney;
                    totalCompensateMoney = 0;
                }

                $.each(data,function(i,item){
                    priceMoney = accAdd(priceMoney, Number(item.price), 2);
                });

                priceMoney = moneyFormat2(priceMoney);

                compensateMoney = moneyFormat2(compensateMoney);
                if (data.length > 0) {
                    detailObj.find('tbody').after("<tr class='tr_count'>" +
                    "<td></td><td>合计</td><td colspan='4'></td>" +
                    "<td style='text-align:right;'>" +
                    moneyFormat2($("#formMoney").val()) +
                    "</td>" +"<td style='text-align:right;'>" +
                    moneyFormat2($("#unitPrice").val()) +
                    "</td>" +
                    "<td><input id='priceShow' style='width:70px;font-weight:100;text-align: right;' class='readOnlyTxtShortCount' readonly='readonly' value='"+priceMoney+"'/></td>" +
                    "<td style='text-align:right;'><input id='compensateMoneyShow' style='width:85px;' class='readOnlyTxtShortCount' readonly='readonly' value='" +
                        compensateMoney + "'/></td><td colspan='2'></td>" +
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
        },{
            display: '赔偿金额',
            name: 'compensateMoney',
            width: 90,
            process: function (v, row ,$tr, g, $input, rowNum) {
                var compensateMoney=v;
                if(row.money>0 || compensateMoney>0){
                    compensateMoney=v;
                }else{
                    compensateMoney=row.price;
                }
                totalCompensateMoney += Number(compensateMoney);// 统计赔偿金额合计
                countMoney();
                return "<input type='text' id='detailCompensateMoney" + row.id + "' data-defaultVal='" + compensateMoney + "' value='" + compensateMoney + "' onblur='changeCompensateMoney(" + row.id + ","+rowNum+");' class='txtshort dtlCmpMoney'/>";
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
    //$("#costshareGrid").costshareGrid({
    //    objName: 'compensate[costshare]',
    //    url: "?model=finance_cost_costshare&action=listjson",
    //    param: {objType: 1, objId: $("#id").val()},
    //    type: 'view',
    //    event: {
    //        'reloadData': function (e, g, data) {
    //            if (!data) {
    //                $("#costshareGrid").hide();
    //            }
    //        }
    //    }
    //});

    //显示质检情况
    $("#showQualityReport").showQualityDetail({
        param: {
            objId: $("#relDocId").val(),
            objType: $("#qualityObjType").val()
        }
    });

    $("#dutyType").change(function(){
        var dutyObjName;
        var dutyObjId;
        if($(this).val() == "PCZTLX-01"){
            dutyObjName = $("#chargerName").val();
            dutyObjId = $("#chargerId").val();
        }else{
            dutyObjName = $("#deptName").val();
            dutyObjId = $("#deptId").val();
        }
        $("#dutyObjName").val(dutyObjName);
        $("#dutyObjId").val(dutyObjId);

        // 一部更新
        $.ajax({
            type: "POST",
            url: "?model=finance_compensate_compensate&action=ajaxUpdateDutyInfo",
            data: {
                id: $("#id").val(),
                dutyObjName: dutyObjName,
                dutyObjId: dutyObjId,
                dutyType: $(this).val()
            },
            success: function (msg) {
                if (msg == 1) {
                    $("#updateResult").append('赔偿对象 ' + dutyObjName + ' 更新成功<br/>');
                } else {
                    $("#updateResult").append('赔偿对象 ' + dutyObjName + ' 更新失败<br/>');
                }
            }
        });
    });
});

//更新赔偿金额
function changeCompensateMoney(id,rowNum) {
    var detailObj = $("#detail");
    var money = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"money").val();
    var price = detailObj.yxeditgrid("getCmpByRowAndCol",rowNum,"price").val();
    var detailCompensateMoney = $("#detailCompensateMoney" + id).val();
    var priceMin=price*0.4*1;
    var flag=false;
    // if(money>0){
    //     flag=true;
    // }else{
        if(detailCompensateMoney == '' || (parseFloat(priceMin)-parseFloat(detailCompensateMoney)>0)||(parseFloat(detailCompensateMoney)-parseFloat(price)>0)){
            $("#detailCompensateMoney" + id).val($("#detailCompensateMoney" + id).attr("data-defaultVal"));
            alert("可修改范围为【净值的4折-净值】之间的数值，不允许为0");
        }else{
            flag=true;
        }
    // }
    //更新一下合计金额
    var compensateMoney = countMoney();
    if(flag){
        $.ajax({
            type: "POST",
            url: "?model=finance_compensate_compensatedetail&action=updateCompensateMoney",
            data: {
                id: id,
                detailCompensateMoney: detailCompensateMoney,
                mainId: $("#id").val(),
                compensateMoney: compensateMoney
            },
            success: function (msg) {
                if (msg == 1) {
                    $("#detailCompensateMoney" + id).attr("data-defaultVal",detailCompensateMoney);
                    $("#updateResult").append('赔偿金额 ' + detailCompensateMoney + ' 更新成功<br/>');
                } else {
                    $("#updateResult").append('赔偿金额 ' + detailCompensateMoney + ' 更新失败<br/>');
                }
            }
        });
    }
}

//金额调整
function countMoney() {
    //计算单据金额
    var moneyArr = $("input[id^='detailCompensateMoney']");
    var formMoney = 0;
    moneyArr.each(function () {
        formMoney = accAdd(formMoney, $(this).val(), 2);
    });
    $('#compensateMoneyShow').val(moneyFormat2(formMoney));
    return formMoney;
}