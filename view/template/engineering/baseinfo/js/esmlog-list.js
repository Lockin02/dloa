$(function(){
    // ��ʼ����ѯ�б�
    initView();
});

// ��ѯ�б�
function initView() {
    // �����б��������Ͳ�ͬ���غ������
    $("#grid").yxeditgrid('remove').yxeditgrid({
        url: '?model=engineering_baseinfo_esmlog&action=listJson',
        param: {
            projectId: $("#projectId").val(),
            period: $("#period").val()
        },
        type: 'view',
        event: {
            reloadData: function(e, g, data) {
                if (data.length == 0) {
                    $("#grid tbody").append("<tr class='tr_even'><td colspan='6'>��������</td></tr>");
                }
            }
        },
        colModel: [{
            display: '������',
            name: 'userName',
            width: 80
        }, {
            display: '��������',
            name: 'operationType',
            width: 80
        }, {
            display: '����ʱ��',
            name: 'operationTime',
            align: 'left',
            width: 130
        }, {
            display: '��������',
            name: 'description',
            width: 600,
            align: 'left',
            process: function(v) {
                return "<p style='padding: 0 0 15px 0'>" + v + "</p>";
            }
        }, {
            display: '',
            name: ''
        }]
    });
}