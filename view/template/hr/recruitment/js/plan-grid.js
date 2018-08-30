var show_page = function(page) {
	$("#planGrid").yxgrid("reload");
};

$(function() {

	buttonsArr = [{
		name : 'exportOut',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_plan&action=toExcelIn"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	}];
	$("#planGrid").yxgrid({
				model : 'hr_recruitment_plan',
				title : '招聘计划',
				isDelAction : false,
				isAddAction : false,
				isEditAction : false,
				isOpButton : false,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'formCode',
							display : '单据编号',
							sortable : true
						}, {
							name : 'formManName',
							display : '填表人',
							sortable : true
						}, {
							name : 'deptName',
							display : '需求部门',
							sortable : true
						},{
							name : 'postTypeName',
							display : '职位类型',
							sortable : true
						},{
							name : 'positionName',
							display : '需求职位',
							sortable : true
						}, {
							name : 'isEmergency',
							display : '是否紧急',
							sortable : true
						}, {
							name : 'hopeDate',
							display : '希望到岗时间',
							sortable : true
						}, {
							name : 'addType',
							display : '增员类型',
							sortable : true
						}, {
							name : 'needNum',
							display : '需求人数',
							sortable : true
						}, {
							name : 'entryNum',
							display : '已入职人数',
							sortable : true
						}, {
							name : 'beEntryNum',
							display : '待入职人数',
							sortable : true
						}, {
							name : 'ExaStatus',
							display : '审核状态',
							sortable : true
						}],
				buttonsEx : buttonsArr,
				toViewConfig : {

				},
				searchitems : [{
							display : "单据编号",
							name : 'formCode'
						}, {
							display : "填表人",
							name : 'formManName'
						}, {
							display : "需求部门",
							name : 'deptName'
						}]
			});
});