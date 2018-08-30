var show_page = function () {
    $("#officeinfoGrid").yxsubgrid("reload");
};

$(function () {
    $("#officeinfoGrid").yxsubgrid({
        model: 'engineering_officeinfo_officeinfo',
        showcheckbox: false,
        title: '������Ϣ',
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '�������',
            name: 'module',
            width: 70,
            sortable: true,
            datacode: 'HTBK'
        }, {
            display: 'ִ������',
            name: 'productLine',
            width: 100,
            sortable: true,
            datacode: 'GCSCX'
        }, {
            display: 'ִ����������',
            name: 'head',
            sortable: true
        }, {
            display: '��������',
            name: 'officeName',
            width: 80,
            sortable: true
        }, {
            display: '��������',
            name: 'feeDeptName',
            sortable: true
        }, {
            display: '���ù�����˾',
            name: 'businessBelongName',
            width: 75,
            sortable: true
        }, {
            display: '�����ܼ�',
            name: 'mainManager',
            width: 120,
            sortable: true
        }, {
            display: '������',
            name: 'managerName',
            sortable: true,
            width: 200
        }, {
            display: '���η�Χ',
            name: 'rangeName',
            width: 200,
            sortable: true
        }, {
            display: '��̨��Ա',
            name: 'assistant',
            sortable: true
        }, {
            display: '״̬',
            name: 'state',
            sortable: true,
            width: 75,
            process: function(v) {
                return v == "0" ? "����" : "�ر�";
            }
        }, {
            display: '��ע',
            name: 'remark',
            sortable: true,
            width: 200
        }],
        // ���ӱ������
        subGridOptions: {
            url: '?model=engineering_officeinfo_range&action=pageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'officeId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������
                }
            ],
            // ��ʾ����
            colModel: [{
                name: 'proName',
                display: 'ʡ��',
                width: 120
            }, {
                name: 'managerName',
                display: '������',
                width: 140
            }
            ]
        },
        menusEx: [
            {
                text: "ɾ��",
                icon: 'delete',
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {

                        var isProjected = false;
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_officeinfo_officeinfo&action=isProjected",
                            data: {id: row.id},
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    isProjected = true;
                                }
                            }
                        });

                        if (isProjected == false) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_officeinfo_officeinfo&action=ajaxdeletes",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('ɾ���ɹ���');
                                        show_page();
                                    } else {
                                        alert("ɾ��ʧ��! ");
                                    }
                                }
                            });
                        } else {
                            alert('�����Ѿ�����Ŀ����������ɾ��');
                        }
                    }
                }
            }
        ],
        isDelAction: false,
        /**��������**/
        searchitems: [{
            display: '��������',
            name: 'officeName'
        }, {
            display: '������',
            name: 'managerName'
        }, {
            display: '���η�Χ',
            name: 'rangeName'
        }, {
            display: "ִ������",
            name: 'productLineNameSch'
        }],
        //ִ���������
        comboEx: [{
            text: "ִ������",
            key: 'productLine',
            datacode: 'GCSCX'
        }, {
            text: '״̬',
            key: 'state',
            value: '0',
            data: [
                {
                    text: '����',
                    value: '0'
                }, {
                    text: '�ر�',
                    value: '1'
                }
            ]
        }],
        sortorder: "ASC"
    });
});