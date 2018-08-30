$(function () {
    $("#grid").yxeditgrid({
        title: '�۾ɷ�̯�嵥',
        tableClass: 'form_in_table',
        url: "?model=bi_deptFee_assetShare&action=listJson",
        param: {
            deprId: $("#id").val()
        },
        type: 'view',
        colModel: [
            {
                display: '��̯����',
                name: 'officeName'
            },
            {
                display: '��̯����ID',
                name: 'officeId',
                type: 'hidden'
            },
            {
                display: '��������',
                name: 'deptName'
            },
            {
                display: '��Ŀ�۾�',
                name: 'projectDepr',
                align: 'right',
                process: function(v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '�۾ɱ�����%��',
                name: 'projectDeprRate',
                process: function(v) {
                    return v + " %";
                }
            },
            {
                display: '�е����',
                name: 'feeIn',
                align: 'right',
                process: function(v) {
                    return moneyFormat2(v);
                }
            }
        ]
    });
});