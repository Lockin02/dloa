var $thisCustomerId = ""; // ���ݿͻ�
var $thisCustomerName = ""; // ���ݿͻ�
var defaultCurrency = '�����'; // Ĭ�ϻ���

// ҵ����������
$(function(){
    //������˾
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId : 'businessBelong',
        height : 250,
        isFocusoutCheck : false,
        gridOptions : {
            showcheckbox : false
        }
    });

    // ��Ⱦ�ұ�
    var currencyObj = $("#currency");
    if(currencyObj.length > 0){
        // ���ұ�
        currencyObj.yxcombogrid_currency({
            hiddenId : 'currency',
            valueCol : 'currencyCode',
            isFocusoutCheck : false,
            gridOptions : {
                showcheckbox : false,
                event : {
                    'row_dblclick' : function(e, row, data) {
                        $("#rate").val(data.rate);
                        if(data.Currency != '�����'){
                            setMoney('incomeMoney',0,2);
                            setMoney('incomeCurrency',0,2);
                            $("#currencyInfo").show();
                            $("#currencyShow").text(data.Currency);
                            $("#incomeMoney_v").removeClass('txt').addClass('readOnlyTxtNormal');
                        }else{
                            setMoney('incomeMoney',$("#incomeCurrency").val(),2);
                            $("#currencyInfo").hide();
                            $("#incomeMoney_v").addClass('txt').removeClass('readOnlyTxtNormal');
                        }
                        // �������˱ұ�������˿�ӱ�
                        initAllot(data.Currency);
                    }
                }
            }
        });
    }

    // ��ȡ����
    var currency = currencyObj.val();

    // �������
    initAllot(currency);
});

// ���������ϸ
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '�������',
        param : { 'incomeId' : $("#id").val()},
        tableClass : 'form_in_table',
        event : {
            'reloadData' : function(e, g, data){
                if(!data){
                    g.addRow(0);
                    g.setRowColValue(0,'money',$("#incomeMoney").val(),true);
                    g.setRowColValue(0,'moneyCurrency',$("#incomeCurrency").val(),true);
                }
                // ͳһ����һ��
                countAll();
            }
        },
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        }, {
            display : 'Դ������',
            name : 'objType',
            type : 'select',
            options : objTypeOptions,
            event : {
                change : function(){
                    reloadCombo(this.value,$(this).data('rowNum'));
                }
            }
        }, {
            display : 'Դ��id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : 'Դ�����',
            name : 'objCode',
            tclass : 'txt',
            readonly : true,
            process : function($input) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var thisType = g.getCmpByRowAndCol(rowNum,'objType').val();
                reloadComboClear(thisType,rowNum);
            }
        }, {
            display : '������',
            name : 'money',
            type : 'money',
            readonly : currency == defaultCurrency ? false : true,
            tclass : currency == defaultCurrency ? 'txtmiddle' : 'readOnlyTxt',
            event : {
                blur : function(){
                    if(currency == defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : '������('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            event : {
                blur : function(){
                    if(currency != defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : 'ҵ����',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '��������',
            name : 'allotDate',
            tclass : 'txtmiddle Wdate',
            type : 'date'
        }]
    });
}

// �д���
function countRow(rowNum,currency){
    var objGrid = $("#allotTable"); // ����ӱ����
    var rate = $("#rate").val();
    if(currency == defaultCurrency){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'money',true).val();
        var moneyCurrency = accDiv(money,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"moneyCurrency",moneyCurrency,true);
    }else{
        var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'moneyCurrency',true).val();
        var money = accMul(moneyCurrency,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"money",money,true);
    }
}

// ���ϼ�
function countAll(){
    //��ȡ����ӱ�����
    var objGrid = $("#allotTable"); // ����ӱ����
    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
    var allAmount = 0; // ������
    var allCurrency = 0; // ������(��Ӧ�ұ�)
    objArr.each(function(){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'money',true).val();
        if(!isNaN(money)){
            allAmount = accAdd(money,allAmount,2);
            var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'moneyCurrency',true).val();
            allCurrency = accAdd(allCurrency,moneyCurrency,2);
        }
    });
    var allotAble = accSub($('#incomeMoney').val(),allAmount,2);
    $('#allotAble').val(allotAble);
    var allotCurrency = accSub($('#incomeCurrency').val(),allCurrency);
    $('#allotCurrency').val(allotCurrency);
}