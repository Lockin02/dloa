$(document).ready(function() {
    //�����ʼ����ݱ�
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'protemplate[items]',
        url: "?model=stock_template_protemplateitem&action=listJson",
        param: {
            "mainId": $("#id").val()
        },
        event: {
            'reloadData': function(e) {

},
            'removeRow': function() {

}
        },
        type: 'view',
        title: '�����嵥',
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        },
        {
            name: 'productId',
            display: '����Id',
            type: 'hidden'
        },
        {
            name: 'productCode',
            display: '���ϱ��'
        },
        {
            name: 'productName',
            display: '��������'
        },
        {
            name: 'pattern',
            display: '����',
        },
        {
            name: 'unitName',
            display: '��λ',
        },
        {
            name: 'actNum',
            display: '����',
        }]
    });
});