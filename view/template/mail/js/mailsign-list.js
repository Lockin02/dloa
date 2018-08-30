// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".mailsignGrid").yxgrid("reload");
};
$(function() {
	$(".mailsignGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'mail_mailsign',
		//action : 'mylogpageJson',
		showcheckbox:false,
		title : "邮寄信息",
//			 */
			isToolBar : false,
			/**
			 * 表单默认宽度
			 */
//			formWidth : 900,
			/**
			 * 表单默认宽度
			 */
//			formHeight : 550,

			/**
			 * 是否显示添加按钮/菜单
			 *
			 * @type Boolean
			 */
			isAddAction: false,

			/**
			 * 是否显示查看按钮/菜单
			 *
			 * @type Boolean
			 */
			isViewAction : false,

			/**
			 * 是否显示修改按钮/菜单
			 *
			 * @type Boolean
			 */
			isEditAction : false,



		menusEx : [{
				name : 'edit',
				text : "修改",
				icon : 'edit',
				action : function(row,rows,grid) {
							showThickboxWin("?model=mail_mailsign&action=init&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}, {
				name : 'readMailMessage',
				text : "邮寄信息",
				icon : 'view',
				action : function(row,rows,grid) {
							showThickboxWin("?model=mail_mailinfo&action=init&perm=view&id=" + row.mailInfoId + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
				}
			}],


		// 列信息
		colModel : [
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '邮寄单号',
					name : 'mailNo',
					sortable : true
				}, {
					display : '签收日期',
					name : 'signDate',
					sortable : true,
					width : '130',
					align : 'center'
				}, {
					display : '客户签收人',
					name : 'signMan',
					sortable : true
				}, {
					display : '备注',
					name : 'remark',
					sortable : true,
					width : '200'
				}],
						// 快速搜索
				searchitems : [{
					display : '客户签收人',
					name : 'signMan'
				}],
				// 默认搜索顺序
				sortorder : "DESC"

			});
});