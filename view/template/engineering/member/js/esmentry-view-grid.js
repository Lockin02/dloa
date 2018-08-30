var show_page = function(page) {
	$("#esmentryGrid").yxgrid("reload");
};
$(function() {
	$("#esmentryGrid").yxgrid({
		model : 'engineering_member_esmentry',
		title : '人员出入表',
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		param : {
			projectId : $("#projectId").val()
		},
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '项目id',
			name : 'projectId',
			sortable : true,
			hide : true
		}, {
			name : 'memberName',
			display : '人员名称',
			sortable : true
		}, {
			name : 'memberId',
			display : '人员Id',
			sortable : true,
			hide : true
		}, {
			name : 'personLevel',
			display : '人员等级',
			sortable : true,
			process : function(v,row){
				if(row.memberId != 'SYSTEM') return v;
			}
		}, {
			name : 'beginDate',
			display : '加入项目',
			sortable : true
		}, {
			name : 'endDate',
			display : '离开项目',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : '200'
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toAddConfig : {
			action : 'toAdd',
			plusUrl : '&projectId='+$("#projectId").val()
		},
		searchitems : [ {
			display : "人员名称",
			name : 'memberName'
		}  ]
	});
});