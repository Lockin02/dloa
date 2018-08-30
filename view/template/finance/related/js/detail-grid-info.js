var show_page = function(page) {
	$("#baseinfoGrid").yxgrid("reload");
};

$(function() {
	$("#baseinfoGrid").yxgrid({
		model: 'finance_related_baseinfo',
		param : {"id" : $('#relatedId').val()},
		title: '������־',
		showcheckbox: false,
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel: [{
			display: '�������',
			name: 'id',
			sortable: true,
			width : 60
		},{
			display: 'hookMainId',
			name: 'hookMainId',
			hide :true
		},
		{
			name: 'years',
			display: '�������',
			sortable: true,
			width : 60
		},
		{
			name: 'createTime',
			display: '����ʱ��',
			sortable: true,
			process : function(v){
				return formatDate(v);
			},
			width : 80
		},
		{
			name: 'createName',
			display: '������',
			sortable: true
		},
		{
			name: 'supplierName',
			display: '��Ӧ��',
			sortable: true,
			width : 150
		},{
			name: 'hookObj',
			display: ' ��������',
			sortable: true,
			process : function(v){
				if(v == 'invpurchase'){
					return '�ɹ���Ʊ';
				}else if(v == 'invcost'){
					return '���÷�Ʊ';
				}else{
					return '�ɹ���ⵥ';
				}
			},
			width : 80
		},{
			name: 'hookObjCode',
			display: '�����',
			sortable: true,
			width : 160
		},{
			name: 'productName',
			display: '��Ʒ����',
			sortable: true
		},{
			name: 'productNo',
			display: '���ϴ���',
			sortable: true
		},{
			name: 'number',
			display: '��������',
			sortable: true,
			width : 60
		},{
			name: 'amount',
			display: '�������',
			sortable: true,
			process : function(v){
				return moneyFormat2(v);
			}
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if(row.hookObj == 'invpurchase'){
					showThickboxWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.hookMainId +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=" +
						550 + "&width=" + 800);
				}
			}
		}]
	});
});