var show_page = function (page) {
    $("#supassesSuppGrid").yxgrid("reload");
};
//�鿴������ϸ��Ϣ
function viewDetail(id, skey) {
    showOpenWin("?model=supplierManage_assessment_supasses&action=toView&id=" + id + "&skey=" + skey);
}
$(function () {
    var suppId = $("#suppId").val();
    $("#supassesSuppGrid").yxgrid({
        model: 'supplierManage_assessment_supasses',
        title: '��Ӧ��������¼',
        showcheckbox: false,
        isDelAction: false,
        isAddAction: false,
        isToolBar: false,
        param: {"suppId": suppId},
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
            width: 160
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
        }, {
            name: 'assesManId',
            display: '������ԱID',
            hide: true
        }, {
            name: 'assesManName',
            display: '����������',
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
        }, {
            text: '����״̬',
            key: 'ExaStatus',
            data: [
                {
                    text: '������',
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
        }],

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
            }
            , {
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
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    }
                }
            }]
    });
});