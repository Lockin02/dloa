var show_page = function(page) {
	$("#worklogGrid").yxgrid("reload");
};
var date1;

$(function() {
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=produce_log_weeklog&action=getByAjax",
		data : {
			id : $('#weekid')[0].value
		},
		success : function(result) {
			result = eval("(" + result + ")");
			date1 = result['weekBeginDate'] + "~" + result['weekEndDate'];
		}
	})
	$("#worklogGrid").yxgrid({
		model : 'produce_log_worklog',
		title : "�܈�����:(" + date1 + ")",// date1.getFullYear()+"-"+(date1.getMonth()+1)+"-"+date1.getDate()+"~"+date2.getFullYear()+"-"+(date2.getMonth()+1)+"-"+date2.getDate(),
		// ����Ϣ
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		buttonsEx : [ {
			text : '����',
			icon : 'view',
			action : function(row) {
				history.back();
			}
		} ],
		param : {
			weekId : $('#weekid')[0].value
		},

		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'produceTaskCode',
			display : '������',
			sortable : true
		}, {
			name : 'executionDate',
			display : 'ִ������',
			sortable : true
		}, {
			name : 'effortRate',
			display : '�����',
			sortable : true
		}, {
			name : 'warpRate',
			display : 'ƫ����',
			sortable : true
		}, {
			name : 'workloadDay',
			display : '����Ͷ�빤����',
			sortable : true
		}, {
			name : 'workloadSurplus',
			display : 'Ԥ��ʣ�๤����',
			sortable : true
		}, {
			name : 'planEndDate',
			display : 'Ԥ�����ʱ��',
			sortable : true
		}, {
			name : 'description',
			display : '����',
			sortable : true
		}, {
			name : 'problem',
			display : '��������',
			sortable : true
		}, {
			name : 'createName',
			display : 'Ա������',
			sortable : true
		} ],
		showcheckbox : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		toViewConfig : {
			action : 'toView'
		},
		rchitems : [ {
			display : "ִ������",
			name : 'executionDate'
		} ]
	});
});