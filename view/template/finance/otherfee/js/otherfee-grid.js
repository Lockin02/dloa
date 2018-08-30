var show_page = function(page) {
	$("#otherfeeGrid").yxgrid("reload");
};
$(function() {
	

	$("#otherfeeGrid").yxgrid({
		model : 'finance_otherfee_otherfee',
		title : '�Ǳ��������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'accountYear',
			display : '������',
			sortable : true
		}, {
			name : 'accountPeriod',
			display : '����ڼ�',
			sortable : true
		}, {
			name : 'summary',
			display : 'ժҪ',
			sortable : true
		}, {
			name : 'subjectName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'debit',
			display : '�跽���',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
				}
		}, {
			name : 'chanceCode',
			display : '�̻����',
			sortable : true
		}, {
			name : 'trialProjectCode',
			display : '������Ŀ���',
			sortable : true
		}, {
			name : 'feeDeptName',
			display : '���ù�������',
			sortable : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true
		}, {
			name : 'province',
			display : 'ʡ��',
			sortable : true
		}],

		buttonsEx : [{
			name : 'exportIn',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=finance_otherfee_otherfee&action=toEportExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "������",
			name : 'accountYear'
		},{
			display : "����ڼ�",
			name : 'accountPeriod'
		},{
			display : "��Ŀ����",
			name : 'subjectName'
		},{
			display : "�̻����",
			name : 'chanceCode'
		},{
			display : '������Ŀ���',
			name : 'trialProjectCode'
		},{
			display : "���ù�������",
			name : 'feeDeptName'
		},{
			display : "��ͬ���",
			name : 'contractCode'
		},{ 
			display : "ʡ��",
			name : 'province'
		}]
	});
});