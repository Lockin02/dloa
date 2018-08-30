var show_page = function(page) {
	$("#ticketlist").yxgrid("reload");
};

$(function() {
	$("#ticketlist").yxgrid({
		model : 'flights_ticketagencies_ticket',
		title : '��Ʊ����',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'institutionId',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'institutionName',
			display : '��������',
			sortable : true,
			width : 200
		}, {
			name : 'agencyType',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'bookingBusiness',
			display : '��Ʊҵ��',
			sortable : true,
			width : 200
		}, {
			name : 'institutionBusiness',
			display : '��������',
			sortable : true,
			width : 300
		}],
		toAddConfig : {
			action : 'toAdd'
		},
		toEditConfig:{
			action : 'toEdit'
		},
		toViewConfig:{
			action : 'toView'
		},
		//��������
		comboEx : [{
			text : '��������',
			key : 'agencyType',
			data : [{
				text : 'Ʊ��',
				value : 'Ʊ��'
			}, {
				text : '����',
				value : '����'
			}]
		}],
		searchitems : [{
			display : "��������",
			name : 'institutionIdSearch'
		},{
			display : "��������",
			name : 'institutionNameSearch'
		}]
	});
});