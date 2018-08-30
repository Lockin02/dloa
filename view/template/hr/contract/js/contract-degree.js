var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {

	$("#contractGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '合同信息',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : '员工姓名',
			sortable : true
		}, {
			name : 'userNo',
			display : '员工编号',
			sortable : true
		}, {
			name : 'conNo',
			display : '合同编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_contract_contract&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'conName',
			display : '合同名称',
			sortable : true
		}, {
			name : 'conTypeName',
			display : '合同类型',
			sortable : true
		},  {
			name : 'conStateName',
			display : '合同状态',
			sortable : true
		}, {
			name : 'beginDate',
			display : '合同开始时间',
			sortable : true
		}, {
			name : 'closeDate',
			display : '合同结束时间',
			sortable : true
		}, {
			name : 'trialBeginDate',
			display : '试用开始时间',
			sortable : true
		}, {
			name : 'trialEndDate',
			display : '试用结束时间',
			sortable : true
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '员工姓名',
					name : 'userName'
				}, {
					display : '员工编号',
					name : 'userNo'
				}, {
					display : '合同编号',
					name : 'conNo'
				}, {
					display : '合同名称',
					name : 'conName'
				}, {
					display : '合同类型',
					name : 'conTypeName'
				}, {
					display : '合同状态',
					name : 'conStateName'
				}]
	});
});