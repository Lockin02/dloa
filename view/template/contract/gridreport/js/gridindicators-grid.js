var show_page = function(page) {
	$("#gridindicatorsGrid").yxgrid("reload");
};
$(function() {
	$("#gridindicatorsGrid").yxgrid({
		model : 'contract_gridreport_gridindicators',
		title : '���ָ���',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'objCode',
			display : '�������',
			sortable : true
		},{
			name : 'objName',
			display : '��������',
			sortable : true
		},{
			name : 'createId',
			display : '������Id',
			hide : true
		},{
			name : 'createName',
			display : '������',
			sortable : true
		},{
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 150
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : "������÷�Χ",
			icon : 'add',
			action : function(row ,rows ,grid) {
				if(row) {
					showModalWin("?model=contract_gridreport_indicators&action=toAdd&gridId=" + row.id ,1);
				}
			}
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});