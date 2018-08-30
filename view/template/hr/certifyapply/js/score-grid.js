var show_page = function(page) {
	$("#scoreGrid").yxgrid("reload");
};
$(function() {
	$("#scoreGrid").yxgrid({
		model : 'hr_certifyapply_score',
		title : '任职资格评委打分',
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '评价员工',
				sortable : true
			}, {
				name : 'userAccount',
				display : '评价员工帐号',
				sortable : true,
				hide : true
			}, {
				name : 'managerName',
				display : '评审人',
				sortable : true
			}, {
				name : 'managerId',
				display : '评审人员id',
				sortable : true,
				hide : true
			}, {
				name : 'assessDate',
				display : '评审日期',
				sortable : true
			}, {
				name : 'score',
				display : '加权得分',
				sortable : true
			}, {
				name : 'createId',
				display : '创建人Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 130
			}, {
				name : 'updateId',
				display : '修改人Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				width : 130
			}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "评审人",
			name : 'managerNameSearch'
		}]
	});
});