/**
 * ������Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_goodsproperties', {
        isDown : false,
        setValue : function(rowData) {
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
		options : {
			hiddenId : 'id',
			nameCol : 'propertiesName',
            openPageOptions : {
                url : '?model=goods_goods_properties&action=pageSelect',
                width : '750'
            },
			gridOptions : {
				showcheckbox : false,
				model : 'goods_goods_properties',
				pageSize : 10,
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
				searchitems : [{
					display : '��Ʒ����',
					name : 'goodsName'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);