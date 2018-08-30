var show_page = function(page) {
	$("#cardrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#cardrecordsGrid").yxgrid({
		model : 'cardsys_cardrecords_cardrecords',
		title : '���Կ�ʹ�ü�¼',
		param : { 'projectId' : $("#projectId").val() },
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'cardNo',
				display : '���Կ���',
				sortable : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				width : 140,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 150,
				hide : true
			}, {
				name : 'ownerId',
				display : 'ʹ����',
				sortable : true,
				hide : true
			}, {
				name : 'ownerName',
				display : 'ʹ����',
				sortable : true
			}, {
				name : 'useDate',
				display : 'ʹ������',
				sortable : true
			}, {
				name : 'useAddress',
				display : 'ʹ�õص�',
				sortable : true
			}, {
				name : 'rechargerMoney',
				display : '��ֵ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'useReson',
				display : '��;',
				sortable : true,
				width : 120
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				hide : true
			}, {
				name : 'createId',
				display : '¼����Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '¼����',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '¼��ʱ��',
				sortable : true,
				hide : true
			}
		],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "����",
			name : 'cardNoSearch'
		}]
	});
});