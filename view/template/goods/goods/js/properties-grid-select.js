var show_page = function () {
    $("#propertiesGrid").yxgrid("reload");
};
$(function () {
    // �̳�������¼�
    var combogrid = window.dialogArguments[0];
    var p = combogrid.options;
    var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
    if (eventStr.row_dblclick) {
        var dbclickFunLast = eventStr.row_dblclick;
        eventStr.row_dblclick = function(e, row, data) {
            dbclickFunLast(e, row, data);
            window.returnValue = row.data('data');
            window.close();
        };
    } else {
        eventStr.row_dblclick = function(e, row, data) {
            window.returnValue = row.data('data');
            window.close();
        };
    }

    var gridOptions = combogrid.options.gridOptions;

    $("#propertiesGrid").yxgrid({
        model: 'goods_goods_properties',
        action : gridOptions.action,
        param : gridOptions.param,
        title: '˫��ѡ���Ʒ����',
        isAddAction : false,
        isEditAction: false,
        isViewAction : false,
        isDelAction : false,
        isOpButton : false,
        showcheckbox : false,
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'parentName',
            display: '�ϼ���������',
            sortable: true,
            hide: true
        }, {
            name: 'propertiesName',
            display: '��������',
            width: 120,
            sortable: true
        }, {
            name: 'orderNum',
            display: '����',
            width: '30',
            sortable: true
        }, {
            name: 'propertiesType',
            display: '����������',
            sortable: true,
            width: '80',
            process: function (v) {
                if (v == "0") {
                    return "����ѡ��";
                } else if (v == "1") {
                    return "����ѡ��";
                } else if (v == "2") {
                    return "�ı�����";
                } else {
                    return v;
                }
            }
        }, {
            name: 'isLeast',
            display: '����ѡ��һ��',
            sortable: true,
            width: '90',
            align: 'center',
            process: function (v) {
                if (v == "on") {
                    return "��";
                } else {
                    return v;
                }
            }
        }, {
            name: 'isInput',
            align: 'center',
            display: '����ֱ������ֵ',
            sortable: true,
            width: '90',
            process: function (v) {
                if (v == "on") {
                    return "��";
                } else {
                    return v;
                }
            }
        }, {
            name: 'remark',
            display: '��ע',
            width: 200,
            sortable: true
        }],
        searchitems: [{
            display: "��������",
            name: 'propertiesName'
        }],
        sortname : gridOptions.sortname,
        sortorder : gridOptions.sortorder,
        // ���¼����ƹ���
        event : eventStr
    });
});