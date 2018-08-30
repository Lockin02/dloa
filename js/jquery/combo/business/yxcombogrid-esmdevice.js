/**
 * ���ϻ�����Ϣ����������
 */
(function ($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_esmdevice', {
        isDown: false,
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
            hiddenId: 'id',
            nameCol: 'device_name',
            openPageOptions: {
                url: '?model=engineering_device_esmdevice&action=selectDevice',
                width: '750'
            },
            checkNum: true,//��֤�豸�������
            gridOptions: {
                isTitle: true,
                title: '�豸ѡ��',
                showcheckbox: false,
                model: 'engineering_device_esmdevice',
                action: 'selectJson',
                pageSize: 10,
                // ����Ϣ
                colModel: [
                    {
                        display: 'id',
                        name: 'id',
                        sortable: true,
                        hide: true
                    },
                    {
                        name: 'deviceType',
                        display: '�豸����',
                        sortable: true,
                        width: 150
                    },
                    {
                        name: 'device_name',
                        display: '�豸����',
                        sortable: true,
                        width: 150
                    },
                    {
                        name: 'unit',
                        display: '��λ',
                        sortable: true,
                        width: 60
                    }
                ],
                // ��������
                searchitems: [
                    {
                        display: '�豸����',
                        name: 'device_nameSearch'
                    }
                ],
                // Ĭ�������ֶ���
                sortname: "typeid,id",
                // Ĭ������˳��
                sortorder: "asc"
            }
        }
    });
})(jQuery);