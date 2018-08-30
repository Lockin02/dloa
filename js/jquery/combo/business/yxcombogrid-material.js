/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
    $.woo.yxcombogrid.subclass('woo.yxcombogrid_product', {
        isDown: false,
        setValue: function(rowData) {
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
            nameCol: 'productCode',
            openPageOptions: {
                url: '?model=stock_productinfo_productinfo&action=selectProduct',
                width: '750'
            },
            closeCheck: false, // �ر�״̬,����ѡ��
            closeAndStockCheck: false, //�ر���У����
            gridOptions: {
//                showcheckbox: false,
                model: 'stock_productinfo_productinfo',
                action: 'pageJson',
                param: {
                    // "ext1" : "WLSTATUSKF"
                },
                pageSize: 10,
                // ����Ϣ
                colModel: [{
                        display: '���ϱ���',
                        name: 'productCode',
                        width: 80
                    }, {
                        display: '��������',
                        name: 'productName',
                        width: 180
                    }, {
                        display: '��������',
                        name: 'proType'
                    }, {
                        display: '����ͺ�',
                        name: 'pattern',
                        width: 80
                    }, {
                        name: 'warranty',
                        display: '������(��)',
                        width: 60,
                        hide: true
                    }, {
                        display: '״̬',
                        name: 'ext1',
                        process: function(v) {
                            if (v == "WLSTATUSKF") {
                                return "����";
                            } else {
                                return "�ر�";
                            }
                        }
                    }, {
                        display: '��λ',
                        name: 'unitName',
                        hide: true
                    }, {
                        display: '������λ',
                        name: 'aidUnit',
                        hide: true
                    }, {
                        display: '������',
                        name: 'converRate',
                        hide: true
                    }],
                // ��������
                searchitems: [{
                        display: '���ϱ���',
                        name: 'productCode'
                    }, {
                        display: '��������',
                        name: 'productName'
                    }],
                // Ĭ�������ֶ���
                sortname: "ext1 desc,id",
                // Ĭ������˳��
                sortorder: "asc"
            }
        }
    });
})(jQuery);