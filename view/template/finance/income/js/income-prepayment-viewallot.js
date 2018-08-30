var defaultCurrency = '�����'; // Ĭ�ϻ���
$(function(){
    // ��ȡ����
    var currency = $("#currency").val();

    // �������
    initAllot(currency);

    // ������ʾ
    if($("#currency").val() != defaultCurrency){
        $("#currencyInfo").show();
    }
});

// ���������ϸ
function initAllot(currency){
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : 'Ԥ�տ����',
        param : { 'incomeId' : $("#id").val()},
        tableClass : 'form_in_table',
        type : 'view',
        event : {
            'reloadData' : function(e, g, data){
                if(!data){
                    $("#allotTable").find('tbody').append('<tr class="tr_odd"><td colspan="6">-- ���޷������� --</td></tr>');
                }
            }
        },
        colModel : [{
            display : 'Դ������',
            name : 'objType',
            datacode : 'KPRK'
        }, {
            display : 'Դ�����',
            name : 'objCode'
        }, {
            display : '������',
            name : 'money',
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            display : '������('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            display : 'ҵ����',
            name : 'rObjCode'
        }, {
            display : '��������',
            name : 'allotDate'
        }]
    });
}