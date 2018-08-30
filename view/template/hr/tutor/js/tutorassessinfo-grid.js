var show_page = function(page) {
	$("#tutorassessinfoGrid").yxgrid("reload");
};
$(function() {
			$("#tutorassessinfoGrid").yxgrid({
						model : 'hr_tutor_tutorassessinfo',
						title : '导师考核表----考核明细',
						isOpButton : false,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'appraisal',
									display : '考评项目',
									sortable : true
								}, {
									name : 'coefficient',
									display : '权重系数',
									sortable : true
								}, {
									name : 'scaleA',
									display : '考评尺度（优秀）',
									sortable : true
								}, {
									name : 'scaleB',
									display : '考评尺度（良好）',
									sortable : true
								}, {
									name : 'scaleC',
									display : '考评尺度（一般）',
									sortable : true
								}, {
									name : 'scaleD',
									display : '考评尺度（较差）',
									sortable : true
								}, {
									name : 'scaleE',
									display : '考评尺度（极差）',
									sortable : true
								}, {
									name : 'selfgraded',
									display : '导师自评分',
									sortable : true
								}, {
									name : 'superiorgraded',
									display : '直接上级评分',
									sortable : true
								}, {
									name : 'staffgraded',
									display : '员工评分',
									sortable : true
								}, {
									name : 'assistantgraded',
									display : '部门助理评分',
									sortable : true
								}, {
									name : 'hrgraded',
									display : 'HR评分',
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