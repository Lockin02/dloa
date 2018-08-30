var show_page = function () {
    $("#esmassresultGrid").yxgrid("reload");
};
$(function () {
    // �Ƿ������޸�
    var isEditAction = false;
    $.ajax({
        type: 'POST',
        url: '?model=engineering_project_esmproject&action=getLimits',
        data: {
            limitName: '��־��������'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                isEditAction = true;
            }
        }
    });

    $("#esmassresultGrid").yxgrid({
        model: 'engineering_assess_esmassresult',
        title: '��־���˽������',
        sortorder: 'ASC',
        isAddAction: false,
        isEditAction: isEditAction,
        isDelAction: false,
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'resultName',
                display: '��־/�ܱ�',
                sortable: true,
                width: 60
            },
//            {
//                name: 'resultVal',
//                display: '��Ӧֵ',
//                sortable: true,
//                width: 60
//            },
//            {
//                name: 'resultScore',
//                display: '���˷���',
//                sortable: true,
//                width: 60
//            },
            {
                name: 'score_10',
                display: '10',
                sortable: true,
                width: 60
            },
            {
                name: 'score_9',
                display: '9',
                sortable: true,
                width: 60
            },
            {
                name: 'score_8',
                display: '8',
                sortable: true,
                width: 60
            },
            {
                name: 'score_7',
                display: '7',
                sortable: true,
                width: 60
            },
            {
                name: 'score_6',
                display: '6',
                sortable: true,
                width: 60
            },
            {
                name: 'score_5',
                display: '5',
                sortable: true,
                width: 60
            },
            {
                name: 'score_4',
                display: '4',
                sortable: true,
                width: 60
            },
            {
                name: 'score_3',
                display: '3',
                sortable: true,
                width: 60
            },
            {
                name: 'score_2',
                display: '2',
                sortable: true,
                width: 60
            },
            {
                name: 'score_1',
                display: '1',
                sortable: true,
                width: 60
            },
            {
                name: 'score_0',
                display: '0',
                sortable: true,
                width: 60
            }
        ],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        }
    });
});