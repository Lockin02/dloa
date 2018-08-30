var totalCompensateMoney = 0;
$(document).ready(function () {
    var detailObj = $("#detail");
    // ��Ʒ�嵥
    detailObj.yxeditgrid({
        objName: 'compensate[detail]',
        url: '?model=finance_compensate_compensatedetail&action=listJson',
        tableClass: 'form_in_table',
        type: 'view',
        title: "�����嵥",
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
                    "<td></td><td>�ϼ�</td><td colspan='4'></td>" +
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
                    detailObj.find('tbody').after("<tr class='tr_odd'><td colspan='20'>-- �������� --</td></tr>");
                }
            }
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden',
            isSubmit: true
        }, {
            display: '����Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '���ϱ��',
            name: 'productNo',
            width: 80
        }, {
            display: '��������',
            name: 'productName'
        }, {
            display: '����ͺ�',
            name: 'productModel'
        }, {
            display: '��λ',
            name: 'unitName',
            width: 50
        }, {
            display: '����',
            name: 'number',
            width: 70
        }, {
            display: 'Ԥ��ά�޽��',
            name: 'money',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '����',
            name: 'unitPrice',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '��ֵ',
            name: 'price',
            width: 70,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        },{
            display: '�⳥���',
            name: 'compensateMoney',
            width: 90,
            process: function (v, row ,$tr, g, $input, rowNum) {
                var compensateMoney=v;
                if(row.money>0 || compensateMoney>0){
                    compensateMoney=v;
                }else{
                    compensateMoney=row.price;
                }
                totalCompensateMoney += Number(compensateMoney);// ͳ���⳥���ϼ�
                countMoney();
                return "<input type='text' id='detailCompensateMoney" + row.id + "' data-defaultVal='" + compensateMoney + "' value='" + compensateMoney + "' onblur='changeCompensateMoney(" + row.id + ","+rowNum+");' class='txtshort dtlCmpMoney'/>";
            }
        }, {
            display: '��ע',
            name: 'remark',
            width: 70,
            align: 'left'
        }, {
            display: '���к�',
            name: 'serialNos',
            width: 150,
            align: 'left'
        }]
    });

    //��ʾ���÷�̯��ϸ
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

    //��ʾ�ʼ����
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

        // һ������
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
                    $("#updateResult").append('�⳥���� ' + dutyObjName + ' ���³ɹ�<br/>');
                } else {
                    $("#updateResult").append('�⳥���� ' + dutyObjName + ' ����ʧ��<br/>');
                }
            }
        });
    });
});

//�����⳥���
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
            alert("���޸ķ�ΧΪ����ֵ��4��-��ֵ��֮�����ֵ��������Ϊ0");
        }else{
            flag=true;
        }
    // }
    //����һ�ºϼƽ��
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
                    $("#updateResult").append('�⳥��� ' + detailCompensateMoney + ' ���³ɹ�<br/>');
                } else {
                    $("#updateResult").append('�⳥��� ' + detailCompensateMoney + ' ����ʧ��<br/>');
                }
            }
        });
    }
}

//������
function countMoney() {
    //���㵥�ݽ��
    var moneyArr = $("input[id^='detailCompensateMoney']");
    var formMoney = 0;
    moneyArr.each(function () {
        formMoney = accAdd(formMoney, $(this).val(), 2);
    });
    $('#compensateMoneyShow').val(moneyFormat2(formMoney));
    return formMoney;
}