/**
 * ��ͬ��������ѡ��
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderNature', {
		options : {
			hiddenId : 'dataCode',
			nameCol : 'parentName',
			valueCol : 'dataCode',
			gridOptions : {
				showcheckbox : false,
				model : 'system_datadict_datadict',
				action : 'orderNaturePageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'parentName',
                  					display : '��������',
                  					sortable : true

                              },{
                    					name : 'dataName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'dataCode',
                  					display : '��������',
                  					sortable : true,
                  					hide : true
                              }],
				// ��������
				searchitems : [{
							display : '��������',
							name : 'dataName'
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);