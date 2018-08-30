var $thisCustomerId = ""; // ���ݿͻ�
var $thisCustomerName = ""; // ���ݿͻ�
var defaultCurrency = '�����'; // Ĭ�ϻ���

// ҵ����������
$(function(){
    // ��ȡ����
    var currency = $("#currency").val();

    // �������
    initAllot(currency);

    // ��ʼ������
    if($("#id").length > 0){
        if(currency != '�����'){
            $("#currencyInfo").show();
            $("#currencyShow").text(currency);
        }else{
            $("#currencyInfo").hide();
        }
    }
});

// ���������ϸ
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '�������',
        param : { 'incomeId' : $("#id").val()},
        isAddAndDel : false,
        tableClass : 'form_in_table',
        event : {
            'reloadData' : function(e, g, data){
                if(data){
                    var objKeyArr = {};
                    for(i in objTypeOptions){
                        if(objTypeOptions[i].value){
                            objKeyArr[objTypeOptions[i].value] = objTypeOptions[i].name;
                        }
                    }
                    for(var i = 0; i < data.length; i++){
                        g.setRowColValue(i,'objTypeView',objKeyArr[data[i].objType]);
                    }
                }
            }
        },
        colModel : [{
            display : 'Դ������',
            tclass : 'readOnlyTxt',
            name : 'objTypeView'
        }, {
            display : 'Դ������',
            name : 'objType',
            type : 'hidden'
        }, {
            display : 'Դ��id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : 'Դ�����',
            name : 'objCode',
            tclass : 'txt',
            tclass : 'readOnlyTxtNormal',
            readonly : true
        }, {
            display : '������',
            name : 'money',
            type : 'money',
            readonly : currency == defaultCurrency ? false : true,
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '������('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : 'ҵ����',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '��������',
            name : 'allotDate',
            tclass : 'readOnlyTxt',
            readonly : true
        }]
    });
}