var show_page = function(page) {
	$("#contractToolGrid").yxgrid("reload");
};
$(function() {
	$("#contractToolGrid").yxgrid({
				model : 'contractTool_contractTool_authorize',
				action : "pagejsons",
				title : '权限设置',
				param :{
					'dir' : 'ASC'
				},
				isViewAction : false,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'userCode',
							display : '用户编号',
							sortable : true,
							width : 200,
							hide : true
						}, {
							name : 'userName',
							display : '用户名字',
							sortable : true,
							width : 100
						}, {
							name : 'limitInfo',
							display : '赋予权限编码',
							sortable : true,
							width : 200,
							hide : true
						}, {
							name : 'limitInfos',
							display : '赋予权限',
							sortable : true,
							width : 400
						}],
				searchitems : [{
					display : "用户名字",
					name : 'userName'
				},{
					display : "赋予权限",
					name : 'limitInfo'
				}]
			});
});