var show_page = function(page) {
	$("#coachplaninfoGrid").yxgrid("reload");
};
$(function() {
			$("#coachplaninfoGrid").yxgrid({
						model : 'hr_tutor_coachplaninfo',
						title : '员工辅导计划详细表',
						isOpButton : false,
						bodyAlign:'center',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'coachplanId',
									display : '辅导计划id',
									sortable : true
								}, {
									name : 'containMonth',
									display : '所属月份',
									sortable : true
								}, {
									name : 'fosterGoal',
									display : '培养目标',
									sortable : true
								}, {
									name : 'fosterMeasure',
									display : '具体培养措施',
									sortable : true
								}, {
									name : 'reachinfoStu',
									display : '达成情况（员工）',
									sortable : true
								}, {
									name : 'remarkStu',
									display : '补充说明（员工）',
									sortable : true
								}, {
									name : 'reachinfoTut',
									display : '达成情况（导师）',
									sortable : true
								}, {
									name : 'remarkTut',
									display : '补充说明（导师）',
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