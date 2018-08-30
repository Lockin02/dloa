// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".contracthistory").yxgrid("reload");
};
$(function() {
			$(".contracthistory").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url : "?model=purchase_change_contractchange&action=pageJsonHistory" ,
						model : 'purchase_change_contractchange',
						action : 'pageJsonHistory',

						param : {
							'applyNumb':$('#applyNumb').val()
						},

						title : '采购合同变更列表',
						isToolBar : false,
						showcheckbox : false,

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}
								, {
									display : '合同编号',
									name : 'applyNumb',
									sortable : true,
									width : 180
								}
								,{
									display : '预计完成时间',
									name : 'dateHope',
									sortable : true
								}, {
									display : '供应商名称',
									name : 'suppName',
									sortable : true
								}
								,{
									display : '付款类型',
									name : 'paymetType',
									datacode : 'fkfs',
									sortable : true,
									width : 60
								}
								,{
									display : '发票类型',
									name : 'billingType',
									datacode : 'FPLX',
									sortable : true,
									width : 80
								},{
									display : '版本号',
									name : "version",
									sortable : true
								}
								,  {
									display : '审核状态',
									name : 'ExaStatus',
									sortable : true,
									width : 80
								},{
									display : '备注',
									name : 'remark',
									sortable : true,
									width : 160
								}],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=purchase_change_contractchange&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 700 + "&width=" + 900);
									showOpenWin("?model=purchase_change_contractchange&action=init&perm=view&id="+row.id);
								}else{
									alert("请选中一条数据");
								}
							}

						}
						],
						//快速搜索
						searchitems : [
								{
									display : '合同编号',
									name : 'applyNumb'
								},
								{
									display : '鼎利合同号',
									name : 'hwapplyNumb'
								}
								],
						// title : '客户信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "id",
						//默认搜索顺序
						sortorder : "DESC",
						//显示查看按钮
						isViewAction : false,
//						isAddAction : true,
						isEditAction : false,
						isDelAction : false
					});

		});