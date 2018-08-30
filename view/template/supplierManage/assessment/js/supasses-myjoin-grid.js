var show_page = function (page) {
    $("#supassesMyjoinGrid").yxgrid("reload");
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
    $("#supassesMyjoinGrid").yxgrid({
        model: 'supplierManage_assessment_supasses',
        action: "myJoinListJson",
        title: '�Ҳ���Ĺ�Ӧ������',
        showcheckbox: false,
        isDelAction: false,
        isAddAction: false,
        isToolBar: false,
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
            name: 'assessType',
            display: '��������',
            sortable: true,
            datacode: 'FALX'
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
            name: 'assesState',
            display: '����״̬',
            hide: true
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
            name: 'assessId',
            display: '��������ID',
            hide: true
        }, {
            name: 'assessName',
            display: '������������',
            sortable: true
        }, {
            name: 'assessCode',
            display: '�����������',
            hide: true
        }, {
            name: 'ExaStatus',
            display: '����״̬'
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
            name: 'aduit',
            text: '�������',
            icon: 'view',
            showMenuFn: function (row) {
                if (row.ExaStatus == '���' || row.ExaStatus == '���') {
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