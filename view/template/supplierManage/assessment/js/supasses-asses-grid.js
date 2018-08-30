var show_page = function (page) {
    $("#supassesMyGrid").yxgrid("reload");
};

//�鿴������ϸ��Ϣ
function viewDetail(id, skey) {
    showOpenWin("?model=supplierManage_assessment_supasses&action=toView&id=" + id + "&skey=" + skey);
}

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
    $("#supassesMyGrid").yxgrid({
        model: 'supplierManage_assessment_supasses',
        action: "assesListJson",
        title: '��Ӧ�̿�������',
        showcheckbox: false,
        isDelAction: false,
        isAddAction: false,
        isToolBar: false,
        bodyAlign: 'center',
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'formCode',
                display: '���ݱ��',
                sortable: true,
                width: 150,
                process: function (v, row) {
                    var skey = row['skey_'];
                    return "<a href='#' title='�鿴��ϸ��Ϣ' onclick='viewDetail(\""
                        + row.id
                        + "\",\""
                        + skey
                        + "\")' >"
                        + v
                        + "</a>";
                }
            },
            {
                name: 'assessTypeName',
                display: '��������',
                sortable: true
            },
            {
                name: 'suppId',
                display: '��Ӧ��ID',
                hide: true
            },
            {
                name: 'suppName',
                display: '��Ӧ������',
                sortable: true,
                width: 160
            },
            {
                name: 'isFirst',
                display: '�Ƿ�����',
                sortable: false,
                width: 60,
                process: function (v, row) {
                    if (row.assessType == "xgyspg") {
                        if (v == 1) {
                            return '����';
                        } else {
                            return "�ڶ�������";
                        }
                    } else {
                        return "";
                    }
                }
            },
            {
                name: 'assessName',
                display: '������������',
                width: 160,
                sortable: true
            },
            {
                name: 'processed',
                display: '����״̬',
                process: function (v, row) {
                    if (v == 0) {
                        return "δ����";
                    } else {
                        return "������";
                    }
                },
                width: 70
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                width: 70,
                process: function (v, row) {
                    if (v == "������") {
                        return "δ�ύ";
                    } else {
                        return v;
                    }
                }
            },
            {
                name: 'notDoneNames',
                display: 'δ���������',
                process: function (v, row) {
                    if (v == '') {
                        return "";
                    } else {
                        return v;
                    }
                },
                width: 130
            }
        ],
        comboEx: [
            {
                text: '��������',
                key: 'assessType',
                data: [
                    {
                        text: '�¹�Ӧ������',
                        value: 'xgyspg'
                    },
                    {
                        text: '��Ӧ�̼��ȿ���',
                        value: 'gysjd'
                    },
                    {
                        text: '��Ӧ����ȿ���',
                        value: 'gysnd'
                    }
                ]
            },
            {
                text: '����״̬',
                key: 'processed',
                data: [
                    {
                        text: 'δ����',
                        value: '0'
                    },
                    {
                        text: '������',
                        value: '1'
                    }
                ]
            },
            {
                text: '����״̬',
                key: 'ExaStatus',
                value: '������',
                data: [
                    {
                        text: 'δ�ύ',
                        value: '������'
                    },
                    {
                        text: '��������',
                        value: '��������'
                    },
                    {
                        text: '���',
                        value: '���'
                    },
                    {
                        text: '���',
                        value: '���'
                    }
                ]
            }
        ],
        //��չ�Ҽ�
        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                action: function (row, rows, grid) {
                    if (row) {
                        window.open("?model=supplierManage_assessment_supasses&action=toView&id=" + row.id + "&skey=" + row['skey_']);
                    }
                }
            },
            {
                name: 'aduit',
                text: '�������',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_supp_suppasses&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=800");
                    }
                }
            },
            {
                text: '����',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == "���" || row.ExaStatus == "��������" || row.processed == "1") {
                        return false;
                    }
                    return true;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        location = "?model=supplierManage_assessment_supasses&action=toAsses&id=" + row.id + "&skey=" + row['skey_'];
                    }
                }
            }
        ],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [
            {
                display: "��Ӧ������",
                name: 'suppName'
            }, {
                display: "���ݱ��",
                name: 'formCode'
            }
        ],
        // Ĭ�������ֶ���
        sortname: "c.formCode",
        // Ĭ������˳�� ����
        sortorder: "DESC"
    });
});