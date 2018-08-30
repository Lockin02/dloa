$(document).ready(function() {

    $("#registerInfo").yxeditgrid({
        url : '?model=outsourcing_vehicle_register&action=statisticsJson',
        param : {
            dir : 'ASC',
            allregisterId : $("#id").val()
        },
        bodyAlign : 'center',
        type : 'view',
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        },{
            name : 'payment',
            display : '选择<input type="checkbox" id="payment" onclick="checkPayment(this);" style="width:60px;"/>',
            width : 60,
            process : function(v ,row) {
                return '<input type="checkbox" name="registerPayment[]" value="' + row.id + '"/>';
            }
        },{
            name : 'useCarDate',
            display : '用车日期',
            width : 80,
            process : function (v) {
                return v.substr(0, 7);
            }
        },{
            name : 'createName',
            display : '录入人',
            width : 80
        },{
            name : 'carNum',
            display : '车  牌',
            width : 80,
            process : function(v ,row){
                return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_register&action=pageView"
                        + "&carNum=" + row.carNum
                        + "&allregisterId=" + $("#id").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false\",\"1\")'>" + v + "</a>";
            }
        },{
            name : 'carModel',
            display : '车  型',
            width : 70,
            type : 'statictext'
        },{
            name : 'rentalProperty',
            display : '租车性质',
            width : 70
        },{
            name : 'contractUseDay',
            display : '合同用车天数',
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
            display : '实际用车天数',
            width : 70,
            type : 'statictext'
        },{
            name : 'suppName',
            display : '供应商名称',
            width : 130
        },{
            name : 'rentalContractCode',
            display : '租车合同编号',
            width : 120
        },{
            name : 'rentalContractId',
            display : '租车合同id',
            type : 'hidden'
        },{
            name : 'city',
            display : '城市',
            width : 80
        },{
            name : 'effectMileage',
            display : '有效里程',
            width : 80,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'reimbursedFuel',
            display : '实报实销油费（元）',
            width : 120,
            type : 'statictext',
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'gasolineKMCost',
            display : '按公里计价油费（元）',
            width : 120,
            type : 'statictext',
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'parkingCost',
            display : '停车费（元）',
            width : 120,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'tollCost',
            display : '路桥费（元）',
            width : 120,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'rentalCarCost',
            display : '租车费（元）',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'mealsCost',
            display : '餐饮费（元）',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'accommodationCost',
            display : '住宿费（元）',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'overtimePay',
            display : '加班费（元）',
            type : 'statictext',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'specialGas',
            display : '特殊油费（元）',
            type : 'statictext',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'allCost',
            display : '总费用（元）',
            width : 90,
            process : function (v) {
                return moneyFormat2(v ,2);
            }
        },{
            name : 'effectLogTime',
            width : 90,
            display : '有效LOG时长'
        },{
            name : 'deductInformation',
            display : '扣款信息',
            align : 'left',
            width : 200
        },{
            name : 'estimate',
            display : '评价',
            align : 'left',
            width : 200
        }]
    });
});

//全选or全不选
function checkPayment(obj) {
    var checkVal =  $(obj).attr('checked') ? 'checked' : '';

    var objs = $("input[name^=registerPayment]");
    objs.each(function () {
        $(this).attr('checked' ,checkVal);
    });
}

//获取选择的id
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

//费用报销
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
            alert('暂不支持多辆车同写一个报销报销单！');
        }
    } else {
        alert('至少选择一项！');
    }
}

//获取选择的合同
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

//申请付款
function payablesApply() {
    var checkIds = getCheckIds();
    if (checkIds.length > 0) {
        var rentcarIds = getCheckRentcarIds();
        if (checkIds.length != rentcarIds.length) {
            alert('没有合同无法申请付款！');
        } else {
            showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType'
                + '&objId=' + rentcarIds.toString()
                + '&objType=YFRK-06'
                + '&expand1=' + checkIds.toString()
            ,1);
        }
    } else {
        alert('至少选择一项！');
    }
}