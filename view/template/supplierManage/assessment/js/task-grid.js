var show_page = function (page) {
    $("#taskGrid").yxgrid("reload");
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
    $("#taskGrid").yxgrid({
        model: 'supplierManage_assessment_task',
        title: '��Ӧ����������',
        showcheckbox: false,
        isDelAction: false,
        isEditAction: false,
        param: {stateArr: '0,1'},
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
                return "<a href='#' onclick='showThickboxWin(\"?model=supplierManage_assessment_task&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800\")'>" + v + "</a>";
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
            name: 'assesManName',
            display: '��������',
            sortable: true
        }, {
            name: 'purchManName',
            display: '�����´���',
            sortable: true
        }],
        menusEx: [{
            text: '���',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.state == 0 || row.state == 1) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=supplierManage_assessment_task&action=toEdit&id="
                        + row.id + "&skey=" + row['skey_'] + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
                } else {
                    alert("��ѡ��һ������");
                }
            }

        }, {
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.state == 0) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    if (window.confirm("ȷ��Ҫɾ��?")) {
                        $.ajax({
                            type: "POST",
                            url: "?model=supplierManage_assessment_task&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ�!');
                                    show_page();
                                }
                            }
                        });
                    }
                }
            }
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
        }, {
            text: '״̬',
            key: 'state',
            data: [{
                text: '������',
                value: '0'
            }, {
                text: '�ѽ���',
                value: '1'
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
        }, {
            display: "��������",
            name: 'assesManName'
        }]
    });
});