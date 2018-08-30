(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_msginfo', {
		options : {
			hiddenId : 'id',
			nameCol : 'airName',
			gridOptions : {
				showcheckbox : false,
				model : 'flights_message_message',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'msgType',
					display : '��������',
					sortable : true,
					width : 50,
					process : function(v){
						switch(v){
							case "0" : return '����';break;
							case "1" : return '��ǩ';break;
							case "2" : return '��Ʊ';break;
						}
					}
				},{
					name : 'airName',
					display : '�˻���',
					sortable : true,
					width : 80
				},{
		            name: 'startDate',
		            display: '�˻�����',
		            sortable: true,
					width : 70
		        },{
		            name: 'flightNumber',
		            display: '����/�����',
		            width: 120,
		            sortable: true
		        },{
		            name: 'airline',
		            display: '���չ�˾',
		            sortable: true
		        },
		        {
		            name: 'costPay',
		            display: 'ʵ���踶���',
		            sortable: true,
					width : 70,
					process : function(v){
						return moneyFormat2(v);
					}
		        }],
				// ��������
				searchitems : [{
					display : '�����',
					name : 'flightNumberSearch'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);