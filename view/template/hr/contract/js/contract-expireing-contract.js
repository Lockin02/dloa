var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {
	$("#contractGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '��ͬ��Ϣ',
		isAddAction:false,
		isEditAction:false,
		isDelAction : false,
		showcheckbox:false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			'closeContract' : $('#date').val()
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'Ա������',
			width:80,
			sortable : true
		}, {
			name : 'userNo',
			display : 'Ա�����',
			width:80,
			sortable : true
		}, {
			name : 'conNo',
			display : '��ͬ���',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_contract_contract&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'conName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'conTypeName',
			display : '��ͬ����',
			sortable : true
		},  {
			name : 'conStateName',
			display : '��ͬ״̬',
			sortable : true
		}, {
			name : 'beginDate',
			display : '��ʼʱ��',
			sortable : true
		}, {
			name : 'closeDate',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'conNumName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'conContent',
			display : '��ͬ����',
			sortable : true
		},{
			name : 'fileExist',
			display : '�Ƿ��и���',
			process : function(row,v){
					if(v['files']==0){
						return v="��";
					}else{
						return v="��";
					}
			}
		}],
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * ��������
		 */
		searchitems : [{
					display : 'Ա������',
					name : 'userName'
				}, {
					display : 'Ա�����',
					name : 'userNo'
				}, {
					display : '��ͬ���',
					name : 'conNo'
				}, {
					display : '��ͬ����',
					name : 'conName'
				}, {
					display : '��ͬ����',
					name : 'conTypeName'
				}, {
					display : '��ͬ״̬',
					name : 'conStateName'
				}]
	});
});