var show_page = function(page) {
	$("#trialplantemGrid").yxgrid("reload");
};
$(function() {
	$("#trialplantemGrid").yxgrid({
		model : 'hr_baseinfo_trialplantem',
		title : '员工试用培训计划模板',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'planName',
				display : '计划名称',
				sortable : true,
				width : 150
			}, {
				name : 'weightsAll',
				display : '任务比例',
				sortable : true,
				process : function(v){
					return v + " %";
				},
				width : 80,
				hide : true
			}, {
				name : 'baseScore',
				display : '合格积分',
				sortable : true,
				width : 80
			}, {
				name : 'scoreAll',
				display : '总积分',
				sortable : true,
				width : 80
			}, {
				name : 'description',
				display : '描述信息',
				sortable : true,
				width : 180
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createId',
				display : '创建人ID',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 130
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true
			}, {
				name : 'updateId',
				display : '修改人ID',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				width : 130
			}],
			lockCol:['planName'],//锁定的列名
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
			display : "计划名称",
			name : 'planSearch'
		}]
	});
});