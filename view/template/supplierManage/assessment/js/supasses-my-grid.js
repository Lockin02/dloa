var show_page = function () {
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
        action: "myListJson",
        title: '�ҷ���Ĺ�Ӧ������',
        showcheckbox: false,
        isDelAction: false,
        isAddAction: false,
        isToolBar: false,
        bodyAlign: 'center',
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
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
        }, {
            name: 'assessTypeName',
            display: '��������',
            sortable: true
        }, {
            name: 'suppId',
            display: '��Ӧ��ID',
            hide: true
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
            name: 'totalNum',
            display: '�ܷ�',
            sortable: true,
            width: 60
        }, {
            name: 'suppGrade',
            display: '�����ȼ�',
            sortable: true,
            width: 60
        }, {
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
        }, {
            name: 'assessName',
            display: '������������',
            width: 160,
            sortable: true
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            width: 60,
            sortable: true
        }, {
            name: 'ExaDT',
            display: '����ʱ��',
            width: 130
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
            text: '����״̬',
            key: 'ExaStatus',
            data: [{
                text: 'δ�ύ',
                value: 'δ�ύ'
            }, {
                text: '��������',
                value: '��������'
            }, {
                text: '���',
                value: '���'
            }, {
                text: '���',
                value: '���'
            }]
        }],

        //��չ�Ҽ�
        menusEx: [{
            text: '�鿴',
            icon: 'view',
            action: function (row, rows, grid) {
                if (row) {
                    window.open("?model=supplierManage_assessment_supasses&action=toView&id=" + row.id + "&skey=" + row['skey_']);
                }
            }
        }, {
            text: '�޸�',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.ExaStatus == "δ�ύ" || row.ExaStatus == "���") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    location = "?model=supplierManage_assessment_supasses&action=toEdit&id=" + row.id + "&skey=" + row['skey_'];
                }
            }
        }, {
            name: 'sumbit',
            text: '�ύ����',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.ExaStatus == 'δ�ύ') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=supplierManage_assessment_supasses&action=sumbitAsses",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == 1) {
                                alert('�ύ�ɹ�!');
                                show_page();
                            }
                        }
                    });
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
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
        }, {
            text: '��������',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.ExaStatus == '���' && row.totalNum < 70 && row.assessType == "xgyspg" && row.assesState == 1 && row.isFirst == 1) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    location = "?model=supplierManage_assessment_supasses&action=toSecondAss&id=" + row.id + "&skey=" + row['skey_'];
                }
            }
        },
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���' || row.ExaStatus == "δ�ύ") {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        if (window.confirm("ȷ��Ҫɾ��?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=supplierManage_assessment_supasses&action=deletesInfo",
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
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [{
            display: "��Ӧ������",
            name: 'suppName'
        }]
    });
});