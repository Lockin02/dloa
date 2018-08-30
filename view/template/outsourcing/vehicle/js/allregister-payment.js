$(document).ready(function() {

    $("#registerInfo").yxeditgrid({
        url : '?model=outsourcing_vehicle_register&action=statisticsJson',
        param : {
            dir : 'ASC',
            allregisterId : $("#id").val()
        },
        bodyAlign : 'center',
        type : 'view',
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        },{
            name : 'payment',
            display : 'ѡ��<input type="checkbox" id="payment" onclick="checkPayment(this);" style="width:60px;"/>',
            width : 60,
            process : function(v ,row) {
                return '<input type="checkbox" name="registerPayment[]" value="' + row.id + '"/>';
            }
        },{
            name : 'useCarDate',
            display : '�ó�����',
            width : 80,
            process : function (v) {
                return v.substr(0, 7);
            }
        },{
            name : 'createName',
            display : '¼����',
            width : 80
        },{
            name : 'carNum',
            display : '��  ��',
            width : 80,
            process : function(v ,row){
                return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_register&action=pageView"
                        + "&carNum=" + row.carNum
                        + "&allregisterId=" + $("#id").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false\",\"1\")'>" + v + "</a>";
            }
        },{
            name : 'carModel',
            display : '��  ��',
            width : 70,
            type : 'statictext'
        },{
            name : 'rentalProperty',
            display : '�⳵����',
            width : 70
        },{
            name : 'contractUseDay',
            display : '��ͬ�ó�����',
            width : 70,
            type : 'statictext',
            process : function (v ,row) {
                if (row.rentalPropertyCode == 'ZCXZ-02') {
                    return '';
                } else {
                    return v;
                }
            }
        },{
            name : 'registerNum',
            display : 'ʵ���ó�����',
            width : 70,
            type : 'statictext'
        },{
            name : 'suppName',
            display : '��Ӧ������',
            width : 130
        },{
            name : 'rentalContractCode',
            display : '�⳵��ͬ���',
            width : 120
        },{
            name : 'rentalContractId',
            display : '�⳵��ͬid',
            type : 'hidden'
        },{
            name : 'city',
            display : '����',
            width : 80
        },{
            name : 'effectMileage',
            display : '��Ч���',
            width : 80,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'reimbursedFuel',
            display : 'ʵ��ʵ���ͷѣ�Ԫ��',
            width : 120,
            type : 'statictext',
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'gasolineKMCost',
            display : '������Ƽ��ͷѣ�Ԫ��',
            width : 120,
            type : 'statictext',
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'parkingCost',
            display : 'ͣ���ѣ�Ԫ��',
            width : 120,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'tollCost',
            display : '·�ŷѣ�Ԫ��',
            width : 120,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'rentalCarCost',
            display : '�⳵�ѣ�Ԫ��',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'mealsCost',
            display : '�����ѣ�Ԫ��',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'accommodationCost',
            display : 'ס�޷ѣ�Ԫ��',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'overtimePay',
            display : '�Ӱ�ѣ�Ԫ��',
            type : 'statictext',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'specialGas',
            display : '�����ͷѣ�Ԫ��',
            type : 'statictext',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'allCost',
            display : '�ܷ��ã�Ԫ��',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'effectLogTime',
            width : 90,
            display : '��ЧLOGʱ��'
        },{
            name : 'deductInformation',
            display : '�ۿ���Ϣ',
            align : 'left',
            width : 200
        },{
            name : 'estimate',
            display : '����',
            align : 'left',
            width : 200
        }]
    });
});

//ȫѡorȫ��ѡ
function checkPayment(obj) {
    var checkVal =  $(obj).attr('checked') ? 'checked' : '';

    var objs = $("input[name^=registerPayment]");
    objs.each(function () {
        $(this).attr('checked' ,checkVal);
    });
}

//��ȡѡ���id
function getCheckIds() {
    var resultArr = [];
    var objs = $("input[name^=registerPayment]");
    objs.each(function () {
        if ($(this).attr('checked')) {
            resultArr.push($(this).val());
        }
    });
    return resultArr;
}

//���ñ���
function expenseAdd() {
    var checkIds = getCheckIds();
    if (checkIds.length > 0) {
        if (checkIds.length == 1) {
            showModalWin('?model=finance_expense_expense&action=toEsmExpenseAdd'
                + '&projectId=' + $('#projectId').val()
                + '&relDocType=1'
                + '&relDocId=' + checkIds.toString()
            ,1);
        } else {
            alert('�ݲ�֧�ֶ�����ͬдһ��������������');
        }
    } else {
        alert('����ѡ��һ�');
    }
}

//��ȡѡ��ĺ�ͬ
function getCheckRentcarIds() {
    var resultArr = [];
    var objs = $("#registerInfo").yxeditgrid('getCmpByCol' ,'rentalContractId');
    objs.each(function (i) {
        if ($("input[name^=registerPayment]:eq(" + i + ")").attr('checked')) {
            if (this.value) {
                resultArr.push($(this).val());
            }
        }
    });
    return resultArr;
}

//���븶��
function payablesApply() {
    var checkIds = getCheckIds();
    if (checkIds.length > 0) {
        var rentcarIds = getCheckRentcarIds();
        if (checkIds.length != rentcarIds.length) {
            alert('û�к�ͬ�޷����븶�');
        } else {
            showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType'
                + '&objId=' + rentcarIds.toString()
                + '&objType=YFRK-06'
                + '&expand1=' + checkIds.toString()
            ,1);
        }
    } else {
        alert('����ѡ��һ�');
    }
}