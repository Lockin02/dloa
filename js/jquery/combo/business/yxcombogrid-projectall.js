/**
 * �����ͻ�������
 */
(function ($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_projectall', {
        isDown: true,
        setValue: function (rowData) {
            if (rowData) {
                var t = this, p = t.options, el = t.el;
                p.rowData = rowData;
                if (p.gridOptions.showcheckbox) {
                    if (p.hiddenId) {
                        p.idStr = rowData.idArr;
                        $("#" + p.hiddenId).val(p.idStr);
                        p.nameStr = rowData.text;
                        $(el).val(p.nameStr);
                        $(el).attr('title', p.nameStr);
                    }
                } else if (!p.gridOptions.showcheckbox) {
                    if (p.hiddenId) {
                        p.idStr = rowData[p.valueCol];
                        $("#" + p.hiddenId).val(p.idStr);
                        p.nameStr = rowData[p.nameCol];
                        $(el).val(p.nameStr);
                        $(el).attr('title', p.nameStr);
                    }
                }
            }
        },
        options: {
            hiddenId: 'projectId',
            nameCol: 'projectCode',
            searchName: 'projectCodeSearch',
            openPageOptions: {
                url: '?model=rdproject_project_rdproject&action=pageForAll',
                width: '750'
            },
            gridOptions: {
                showcheckbox: false,
                title: "��Ŀ��Ϣ",
                isTitle: true,
                model: 'rdproject_project_rdproject',
                action: 'pageJsonForAll',
                // ����Ϣ
                colModel: [
                    {
                        display: '��Ŀ����',
                        name: 'projectType',
                        width: 70,
                        process: function (v) {
                            if (v == 'esm') {
                                return '������Ŀ';
                            } else if (v == 'con') {
                                return '��Ʒ��Ŀ';
                            } else {
                                return '�з���Ŀ';
                            }
                        }
                    },
                    {
                        display: '��Ŀ���',
                        name: 'projectCode',
                        width: 150
                    },
                    {
                        display: '��Ŀ����',
                        name: 'projectName',
                        width: 180
                    },
                    {
                        display: '��Ŀ����',
                        name: 'managerName',
                        width: 90
                    }
                ],
                // ��������
                searchitems: [
                    {
                        display: '��Ŀ����',
                        name: 'projectNameSearch'
                    },
                    {
                        display: '��Ŀ���',
                        name: 'projectCodeSearch'
                    }
                ],
                // Ĭ�������ֶ���
                sortname: "id",
                // Ĭ������˳��
                sortorder: "DESC",
                //��������
                comboEx: [
                    {
                        text: '��Ŀ����',
                        key: 'projectType',
                        data: [
                            {
                                text: '�з���Ŀ',
                                value: 'rd'
                            },
                            {
                                text: '������Ŀ',
                                value: 'esm'
                            }
                        ]
                    }
                ]
            }
        }
    });
})(jQuery);