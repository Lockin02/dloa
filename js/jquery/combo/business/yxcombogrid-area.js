/**
 * ���ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_area', {
		options : {
			hiddenId : 'areaPrincipalId',
			nameCol : 'areaName',
			//isFocusoutCheck:false,
			gridOptions : {
				showcheckbox : false,
				model : 'system_region_region',
				action : 'pageJson',
				param : {'isStart':'0'},
				pageSize : 10,
				// ����Ϣ
				colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'areaPrincipal',
                  					display : '��������',
                  					sortable : true

                              },{
                    					name : 'areaName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'areaCode',
                  					display : '�������',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'areaPrincipalId',
                  					display : '��������Id',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'remark',
                  					display : '��ע',
                  					sortable : true,
                  					width : 300,
                  					hide : false
                              }],
				// ��������
				searchitems : [{
							display : '��������',
							name : 'areaName'
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);