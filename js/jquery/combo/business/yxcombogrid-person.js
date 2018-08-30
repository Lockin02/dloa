/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_eperson', {
		options : {
			hiddenId : 'id',
			nameCol : 'personLevel',
			gridOptions : {
				model : 'engineering_baseinfo_eperson',
				param : {'status' : '0'},
				// ��
				colModel : [{
                    display : 'id',
                    name : 'id',
                    sortable : true,
                    hide : true
                }, {
                    display : '��Ա�ȼ�',
                    name : 'personLevel',
                    sortable : true
                }, {
                    display : '�����Զ���',
                    name : 'customPrice',
                    sortable : true,
                    process : function(v){
                        return v == "1" ? "<span class='blue'>��</span>" : "��";
                    }
                }, {
                    name : 'remark',
                    display : '��ע',
                    sortable : true,
                    width : 200
                }],
				/**
				 * ��������
				 */
				searchitems : [{
					display : '��Ա�ȼ�',
					name : 'personLevel'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "orderNum asc,id",
				title : '����Ԥ��'
			}
		}
	});
})(jQuery);