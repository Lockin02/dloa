// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".approvalYesGrid").yxgrid("reload");
};
$(function() {
			$(".approvalYesGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'pageJsonYes',
						title : '已审核的采购订单',
						isToolBar : false,
						showcheckbox : false,

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},  {
									display : '审批类型',
									name : 'isTemp',
									sortable : true,
									process : function(v, row) {
										if (row.isTemp == '0') {
											return "采购订单审批";
										} else {
											return "采购订单变更审批";
										}
									}
								},{
									display : '订单编号',
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
									datacode : 'FPLX',		//数据字典编码
									sortable : true
								}
								, {
									display : '付款方式',
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true
								}
								],
							param : {"ExaStatus" : "打回,完成"},
//							param : {"ExaStatus" : "完成"},

						comboEx:[{
							text:'审批类型',
							key:'isTemp',
							data:[{
							   text:'采购订单审批',
							   value:'0'
							},{
							   text:'采购订单变更审批',
							   value:'1'
							}]
						}],
						//扩展按钮
						buttonsEx : [],
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
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("请选中一条数据");
								}
							}

						}
//						,{
//							text : '审批情况',
//							icon : 'view',
//							action : function(row,rows,grid){
//								if(row){
//									showThickboxWin("controller/common/readview.php?itemtype=oa_purchase_apply_basic&pid="
//											+row.id
//											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
//								}
//							}
//						}
						],
						//快速搜索
						searchitems : [
								{
									display : '订单编号',
									name : 'hwapplyNumb'
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
						isAddAction : true,
						isEditAction : false,
						isDelAction : false
					});

		});