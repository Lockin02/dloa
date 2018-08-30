// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".pcGrid").yxgrid("reload");
};
$(function() {
			$(".pcGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'pageJson',
						title : '采购合同',
						formHeight : 600,
						isToolBar : false,
						showcheckbox : false,

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '合同编号',
									name : 'hwapplyNumb',
									sortable : true,
									width : '200'
								},{
									display : '申请人名称',
									name : 'createName',
									sortable : true
								},{
									display : '预计完成时间',
									name : 'dateHope',
									sortable : true
								},{
									display : '审批状态',
									name : 'ExaStatus',
									sortable :　true
								}
								,{
									display : '供应商名称',
									name : 'suppName',
									sortable : true
								}
								,{
									display : '发票类型',
									name : 'billingType',
									datacode : 'FPLX',
									sortable : true
								}
								, {
									display : '付款类型',
									name : 'paymetType',
									datacode : 'fkfs',
									sortable : true
								}
								],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=purchase_contract_purchasecontract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 500 + "&width=" + 700);
//									showOpenWin("?model=purchase_contract_purchasecontract&action=init&perm=view&id="+row.id);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="+row.id+"&applyNumb="+row.applyNumb);
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
									name : 'seachApplyNumb'
								},
								{
									display : '供应商名称',
									name : 'suppName'
								}
								],
						// title : '客户信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "updateTime",
						//默认搜索顺序
						sortorder : "DESC",
						//显示查看按钮
						isViewAction : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false
					});

		});