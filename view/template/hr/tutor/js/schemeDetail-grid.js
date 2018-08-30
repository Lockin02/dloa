var show_page = function(page) {
	$("#schemeDetailGrid").yxgrid("reload");
};
$(function() {
			$("#schemeDetailGrid").yxgrid({
						model : 'hr_tutor_schemeDetail',
						title : '导师考核模板明细',
						isOpButton : false,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'parentId',
									display : '方案ID',
									sortable : true
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
									name : 'createId',
									display : '创建人Id',
									sortable : true
								}, {
									name : 'createName',
									display : '创建人名称',
									sortable : true
								}, {
									name : 'createTime',
									display : '创建时间',
									sortable : true
								}, {
									name : 'updateId',
									display : '修改人Id',
									sortable : true
								}, {
									name : 'updateName',
									display : '修改人名称',
									sortable : true
								}, {
									name : 'updateTime',
									display : '修改时间',
									sortable : true
								}, {
									name : 'sysCompanyName',
									display : '系统公司名称',
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