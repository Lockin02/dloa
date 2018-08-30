/**
 * ����������Ϣ
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_carinfo', {
		options : {
			hiddenId : 'carId',
			nameCol : 'carNo',
			gridOptions : {
				showcheckbox : true,
				model : 'carrental_carinfo_carinfo',
				// ����Ϣ
				colModel : [{
						display : '����id',
						name : 'carId',
						hide: true
					},  {
						display : '���ƺ�',
						name : 'carNo',
			  			width : 70
					},{
						display : '����',
        				name : 'carTypeName',
			  			width : 70
                  	},{
			        	name : 'brand',
			  			display : 'Ʒ��',
			  			sortable : true,
			  			width : 80
			        },{
			        	name : 'displacement',
			  			display : '����',
			  			sortable : true,
			  			width : 60
			        },{
						name : 'owners',
			  			display : '����',
			  			sortable : true,
			  			width : 70
			        },{
						name : 'driver',
			  			display : '˾��',
			  			sortable : true,
			  			width : 70
			        },{
						name : 'linkPhone',
			  			display : '��ϵ��ʽ',
			  			sortable : true,
			  			width : 80
			        }],
				// ��������
				searchitems : [{
					display : '���ƺ�',
					name : 'carNo'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);