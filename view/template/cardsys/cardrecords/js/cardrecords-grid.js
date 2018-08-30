var show_page = function(page) {
	$("#cardrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#cardrecordsGrid").yxgrid({
		model : 'cardsys_cardrecords_cardrecords',
		title : '���Կ�ʹ�ü�¼',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'useDate',
				display : 'ʹ������',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=cardsys_cardrecords_cardrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'cardNo',
				display : '���Կ���',
				sortable : true
			}, {
				name : 'openMoney',
				display : '�������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'rechargerMoney',
				display : '��ֵ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
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
				name : 'useAddress',
				display : 'ʹ�õص�',
				sortable : true,
				hide : true
			}, {
				name : 'useReson',
				display : '��;',
				sortable : true,
				width : 150
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
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "���Կ���",
			name : 'cardNoSearch'
		}]
	});
});