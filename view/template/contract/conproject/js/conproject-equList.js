$(function() {
        //��Ʒ�嵥
   $("#equList").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_conproject_conproject&action=equListJson',
        param: {
                'projectId': $("#projectId").val()
            },
        type: 'view',
        tableClass: 'form_in_table',
        colModel: [
            {
                display: '���ϱ��',
                name: 'productCode',
                tclass: 'txt'
            },
            {
                display: '��������',
                name: 'productName',
                tclass: 'txt'
            },
            {
                display: '����Id',
                name: 'productId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'number',
                tclass: 'txtshort'
            },
            {
                display: '��ִ������',
                name: 'executedNum',
                tclass: 'txtshort'
            },
            {
                display: '���˿�����',
                name: 'backNum',
                tclass: 'txtshort'
            },
            {
                display: 'ʵ��ִ������',
                name: 'actNum',
                process: function (v, row) {
                	return parseInt(row.number)-parseInt(row.executedNum)+parseInt(row.backNum);
                }
            }
        ]
    });
    });