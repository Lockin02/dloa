var show_page = function(page) {
	$("#rewardinfoGrid").yxgrid("reload");
};
$(function() {
			$("#rewardinfoGrid").yxgrid({
						model : 'hr_tutor_rewardinfo',
						title : '导师奖励管理--明细',
						isOpButton : false,
						bodyAlign:'center',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'userNo',
									display : '导师员工编号',
									sortable : true
								}, {
									name : 'userAccount',
									display : '导师员工账号',
									sortable : true
								}, {
									name : 'userName',
									display : '导师姓名',
									sortable : true
								}, {
									name : 'assessmentScore',
									display : '考核分数',
									sortable : true
								}, {
									name : 'studentNo',
									display : '学员员工编号',
									sortable : true
								}, {
									name : 'studentAccount',
									display : '学员员工账号',
									sortable : true
								}, {
									name : 'studentName',
									display : '学员名称',
									sortable : true
								}, {
									name : 'tryEndDate',
									display : '转正日期',
									sortable : true
								}, {
									name : 'rewardPrice',
									display : '奖品价格',
									sortable : true
								}, {
									name : 'situation',
									display : '辅导总体情况',
									sortable : true
								}],
						// 主从表格设置
						subGridOptions : {
							url : '?model=hr_tutor_NULL&action=pageItemJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '从表字段'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "搜索字段",
									name : 'XXX'
								}]
					});
		});