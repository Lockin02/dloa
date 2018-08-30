var show_page = function(page) {
	$("#trialplantemGrid").yxgrid("reload");
};
$(function() {
	$("#trialplantemGrid").yxgrid({
		model : 'hr_baseinfo_trialplantem',
		title : 'Ա��������ѵ�ƻ�ģ��',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'planName',
				display : '�ƻ�����',
				sortable : true,
				width : 150
			}, {
				name : 'weightsAll',
				display : '�������',
				sortable : true,
				process : function(v){
					return v + " %";
				},
				width : 80,
				hide : true
			}, {
				name : 'baseScore',
				display : '�ϸ����',
				sortable : true,
				width : 80
			}, {
				name : 'scoreAll',
				display : '�ܻ���',
				sortable : true,
				width : 80
			}, {
				name : 'description',
				display : '������Ϣ',
				sortable : true,
				width : 180
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createId',
				display : '������ID',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 130
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true
			}, {
				name : 'updateId',
				display : '�޸���ID',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				width : 130
			}],
			lockCol:['planName'],//����������
		toEditConfig : {
			toEditFn : function(p,g){
				action : showModalWin("?model=hr_baseinfo_trialplantem&action=toEdit&id=" + g.getSelectedRow().data('data')['id']);
			}
		},
		toViewConfig : {
			toViewFn : function(p,g){
				action : showModalWin("?model=hr_baseinfo_trialplantem&action=toView&id=" + g.getSelectedRow().data('data')['id']);
			}
		},
		toAddConfig : {
			toAddFn : function(p,g){
				showModalWin("?model=hr_baseinfo_trialplantem&action=toAdd");
			}
		},
		searchitems : [{
			display : "�ƻ�����",
			name : 'planSearch'
		}]
	});
});