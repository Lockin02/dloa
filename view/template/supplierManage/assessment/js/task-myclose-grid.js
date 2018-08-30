var show_page = function (page) {
    $("#taskMyCloseGrid").yxgrid("reload");
};

/**
 * �鿴��Ӧ����Ϣ
 */
function viewSupplier(suppId) {
    var skey = "";
    $.ajax({
        type: "POST",
        url: "?model=supplierManage_formal_flibrary&action=md5RowAjax",
        data: {
            "id": suppId
        },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    showModalWin("?model=supplierManage_formal_flibrary&action=toRead&id="
        + suppId
        + "&skey="
        + skey
        + "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
}
$(function () {
    $("#taskMyCloseGrid").yxgrid({
        model: 'supplierManage_assessment_task',
        title: '�������������',
        action: 'myPageJson',
        param: {stateArr: '2,3'},
        showcheckbox: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'formCode',
            display: '����������',
            sortable: true,
            width: 120,
            process: function (v, row) {
                return "<a href='#' onclick='showThickboxWin(\"?model=supplierManage_assessment_task&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
            }
        }, {
            name: 'stateName',
            display: '״̬'
        }, {
            name: 'assessTypeName',
            display: '��������',
            sortable: true
        }, {
            name: 'suppName',
            display: '��Ӧ������',
            sortable: true,
            width: 160,
            process: function (v, row) {
                return "<a href='#' onclick='viewSupplier("
                    + row.suppId
                    + ")' >"
                    + v
                    + "</a>";
            }
        }, {
            name: 'formDate',
            display: '�����´�ʱ��',
            sortable: true
        }, {
            name: 'hopeDate',
            display: '�������ʱ��',
            sortable: true
        }, {
            name: 'purchManName',
            display: '�����´���',
            sortable: true
        }],

        comboEx: [{
            text: '��������',
            key: 'assessType',
            data: [{
                text: '�¹�Ӧ������',
                value: 'xgyspg'
            }, {
                text: '��Ӧ�̼��ȿ���',
                value: 'gysjd'
            }, {
                text: '��Ӧ����ȿ���',
                value: 'gysnd'
            }]
        }],

        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [{
            display: "����������",
            name: 'formCode'
        }, {
            display: "��Ӧ������",
            name: 'suppName'
        }, {
            display: "�����´�ʱ��",
            name: 'formDateSear'
        }]
    });
});