$(function () {
    $("#grid").yxgrid({
        model : 'engineering_baseinfo_esmlog',
        title: '���ݸ��¼�¼',
        param: {
            projectId: $("#projectId").val()
        },
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        colModel: [{
            display: 'id',
            name: 'id',
            width: 80,
            hide : true
        }, {
            display: '������',
            name: 'operationType',
            width: 120
        }, {
            display: '���¼�¼��',
            name: 'description',
            align: 'center',
            process: function (v) {
                var detail = v.split('|');
                return detail[0];
            }
        }, {
            display: '����ʱ��',
            name: 'operationTime',
            align: 'left',
            width: 130
        }, {
            display: '���·�Χ',
            name: 'description',
            align: 'center',
            process: function (v) {
                var detail = v.split('|');
                return detail[1] + '��';
            }
        },{
            display: '������',
            name: 'userName',
            align: 'left',
            width: 100
        }],
        searchitems : [{
            display : "������",
            name : 'operationTypeSearch'
        }, {
            display : "����ʱ��",
            name : 'operationTimeSearch'
        }]
    });
});