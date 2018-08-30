/**
 * �ֿ������Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_require',{
		options : {
			hiddenId : 'requireId',
			nameCol : 'requireNo',
			gridOptions : {
				showcheckbox : false,
				model : 'flights_require_require',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'ticketMsg',
					display : '��Ʊ״̬',
					width:70,
					sortable : true,
					process : function(v, row) {
						if (v == "0" || v == "") {
							return "δ����";
						} else {
							return "������";
						}
					},
					hide : true
				},{
					name : 'requireNo',
					display : '��Ʊ�����',
					width:150,
					sortable : true
				}, {
					name : 'airName',
					display : '�˻���',
					sortable : true
				}, {
					name: 'ticketType',
					display: '��Ʊ����',
					sortable: true,
					process: function(v) {
						if (v == "10") {
							return '����';
						} else if (v == "11") {
							return '����';
						} else if (v == "12") {
							return '����';
						}
					},
					width : 80
				},{
					name : 'startPlace',
					display : '�����ص�',
					sortable : true
				},{
					name : 'endPlace',
					display : '����ص�',
					width:100,
					sortable : true
				},{
					name : 'startDate',
					display : '��������',
					sortable : true
				}],
				searchitems: [{
					display: "��Ʊ�����",
					name: 'requireNoSearch'
				},{
					display: "�˻���",
					name: 'airNameSearch'
				},{
					display: "��������",
					name: 'startPlaceSearch'
				},{
					display: "�������",
					name: 'endPlaceSearch'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);