var show_page = function(page) {
	$("#outmailGrid").yxgrid("reload");
};
$(function() {
	$("#outmailGrid").yxgrid({
		model : 'stock_outplan_outmail',
		title : '发货计划邮寄接受人',
		isViewAction : false,
		isEditAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'mailmanId',
			display : '邮件接收人Id',
			hide : true,
			sortable : true
		}, {
			name : 'mailmanName',
			display : '邮件接收人',
			sortable : true
		}]
	});
});