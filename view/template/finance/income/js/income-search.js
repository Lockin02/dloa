function toSupport() {
    var beginYear = $("#beginYear").val();
    var beginMonth = $("#beginMonth").val();
    var endYear = $("#endYear").val();
    var endMonth = $("#endMonth").val();

    var province = $("#province").val();
    var incomeDate = $("#incomeDate").val();
    var incomeMoney = $("#incomeMoney").val();

    var incomeUnitId = $("#incomeUnitId").val();
    var incomeUnitName = $("#incomeUnitName").val();
    var incomeUnitType = $("#incomeUnitType").val();

    var contractUnitId = $("#contractUnitId").val();
    var contractUnitName = $("#contractUnitName").val();

    var objCode = $("#objCode").val();

    //���б�����ȡ
    var listGrid = parent.$("#incomeGrid").data('yxsubgrid');

    setVal(listGrid, 'beginYear', beginYear);
    setVal(listGrid, 'beginMonth', beginMonth);

    //����ֵ�Լ������б����
    var beginYearMonth = "";
    if (beginMonth != "" && beginYear != "") {
        beginYearMonth = beginMonth >= 10 ? beginYear + "" + beginMonth : beginYear + "0" + beginMonth;
    }
    setVal(listGrid, 'beginYearMonth', beginYearMonth);

    setVal(listGrid, 'endYear', endYear);
    setVal(listGrid, 'endMonth', endMonth);

    var endYearMonth = "";
    if (endYear != "" && endMonth != "") {
        endYearMonth = endMonth >= 10 ? endYear + "" + endMonth : endYear + "0" + endMonth;
    }
    setVal(listGrid, 'endYearMonth', endYearMonth);

    setVal(listGrid, 'province', province);

    setVal(listGrid, 'incomeDate', incomeDate);
    setVal(listGrid, 'incomeMoney', incomeMoney);

    setVal(listGrid, 'incomeUnitId', incomeUnitId);
    setVal(listGrid, 'incomeUnitName', incomeUnitName);
    setVal(listGrid, 'incomeUnitType', incomeUnitType);

    setVal(listGrid, 'contractUnitId', contractUnitId);
    setVal(listGrid, 'contractUnitName', contractUnitName);

    setVal(listGrid, 'objCode', objCode);

    //ˢ���б�
    listGrid.reload();
    closeFun();
}

function setVal(obj, thisKey, thisVal) {
    if (thisVal == "") {
        delete obj.options.extParam[thisKey];
    } else {
        return obj.options.extParam[thisKey] = thisVal;
    }
}

$(function () {
    // �ͻ�����
    addDataToSelect(getData('KHLX'), 'incomeUnitType');

    //ʡ����Ⱦ
    var province = $("#province");
    province.yxcombogrid_province({
        height: 200,
        width: 400,
        gridOptions: {
            showcheckbox: false
        }
    });

    //���λ
    $("#incomeUnitName").yxcombogrid_customer({
        hiddenId: 'incomeUnitId',
        height: 200,
        width: 500,
        isShowButton: false,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {

                }
            }
        }
    });

    //��ͬ��Ϊ
    $("#contractUnitName").yxcombogrid_customer({
        hiddenId: 'contractUnitId',
        height: 200,
        width: 500,
        isShowButton: false,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {

                }
            }
        }
    });

    //��ͬ���
    $("#orderCode").yxcombogrid_allorderforincome({
        searchName: 'orderCodeOrTempSearch',
        isFocusoutCheck: false,
        height: 230,
        gridOptions: {
            param: {"ExaStatus": "���"},
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    if (data.orderCode == "") {
                        $("#orderCode").yxcombogrid_allorderforincome('setText', data.orderTempCode);
                    }
                }
            }
        }
    });

    //���ݳ�ʼ������
    //���б�����ȡ
    var listGrid = parent.$("#incomeGrid").data('yxsubgrid');

    $("#beginYear").val(filterUndefined(listGrid.options.extParam.beginYear));
    $("#beginMonth").val(filterUndefined(listGrid.options.extParam.beginMonth));
    $("#endYear").val(filterUndefined(listGrid.options.extParam.endYear));
    $("#endMonth").val(filterUndefined(listGrid.options.extParam.endMonth));
    $("#incomeDate").val(filterUndefined(listGrid.options.extParam.incomeDate));
    $("#incomeMoney").val(filterUndefined(listGrid.options.extParam.incomeMoney));

    //�ͻ���Ϣ
    $("#incomeUnitId").val(filterUndefined(listGrid.options.extParam.incomeUnitId));
    $("#incomeUnitName").val(filterUndefined(listGrid.options.extParam.incomeUnitName));
    $("#incomeUnitType").val(filterUndefined(listGrid.options.extParam.incomeUnitType));

    $("#contractUnitId").val(filterUndefined(listGrid.options.extParam.contractUnitId));
    $("#contractUnitName").val(filterUndefined(listGrid.options.extParam.contractUnitName));

    $("#objCode").val(filterUndefined(listGrid.options.extParam.objCode));

    province.val(filterUndefined(listGrid.options.extParam.province));
});


//���
function toClear() {
    $(".toClear").val('');
}
