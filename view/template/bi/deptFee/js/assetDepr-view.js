$(function () {
    $("#grid").yxeditgrid({
        title: '折旧分摊清单',
        tableClass: 'form_in_table',
        url: "?model=bi_deptFee_assetShare&action=listJson",
        param: {
            deprId: $("#id").val()
        },
        type: 'view',
        colModel: [
            {
                display: '分摊区域',
                name: 'officeName'
            },
            {
                display: '分摊区域ID',
                name: 'officeId',
                type: 'hidden'
            },
            {
                display: '归属部门',
                name: 'deptName'
            },
            {
                display: '项目折旧',
                name: 'projectDepr',
                align: 'right',
                process: function(v) {
                    return moneyFormat2(v);
                }
            },
            {
                display: '折旧比例（%）',
                name: 'projectDeprRate',
                process: function(v) {
                    return v + " %";
                }
            },
            {
                display: '承担差额',
                name: 'feeIn',
                align: 'right',
                process: function(v) {
                    return moneyFormat2(v);
                }
            }
        ]
    });
});