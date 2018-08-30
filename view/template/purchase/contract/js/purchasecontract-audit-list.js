// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#listGrid").yxsubgrid("reload");
};

//查看订单信息
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&id="+id+"&skey="+skey);
}
$(function() {
			$("#listGrid").yxsubgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
//						action : 'pageJsonYes',
						title : '审批中的采购订单',
						isToolBar : false,
						showcheckbox : false,
						param : {"ExaStatus" : "部门审批"},

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},{
									display : '单据日期',
									name : 'orderTime',
									sortable : true,
									width:80
								}, {
									display : '订单编号',
									name : 'hwapplyNumb',
									sortable : true,
									width : '200',
									process : function(v, row) {
										var skey=row['skey_'];
											return "<a href='#' title='查看订单详细信息' onclick='viewOrder(\""
													+ row.id
													+"\",\""
													+skey
													+ "\")' >"
													+v
													+ "</a>";
									}
								}
								,{
									display : '供应商名称',
									name : 'suppName',
									sortable : true,
									width : '200'
								}
								,{
									display : '业务员',
									name : 'sendName',
									sortable : true
								},{
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
								},{
									display : '预计完成时间',
									name : 'dateHope',
									sortable :　true
								},{
									display : '审批状态',
									name : 'ExaStatus',
									sortable :　true
								}
								],
						// 主从表格设置
						subGridOptions : {
							url : '?model=purchase_contract_equipment&action=pageJson',
							param : [{
										paramId : 'basicId',
										colId : 'id'
									}],
							colModel : [ {
											name : 'productNumb',
											display : '物料编号'
										}, {
											name : 'productName',
											width : 200,
											display : '物料名称'
										},{
											name : 'amountAll',
											display : "订单数量",
											width : 60
										},{
											name : 'applyPrice',
											display : "含税单价",
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'moneyAll',
											display : "金额",
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'dateHope',
											display : "期望到货时间"
										},{
											name : 'applyDeptName',
											display : "申请部门"
										},{
											name : 'sourceNumb',
											display : "源单编号",
											width : 170
										}]
						},
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
									showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&perm=view&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("请选中一条数据");
								}
							}

						}
						,{
							text : '审批情况',
							icon : 'view',
							action : function(row,rows,grid){
								if(row){
									showThickboxWin("controller/common/readview.php?itemtype=oa_purch_apply_basic&pid="
											+row.id
											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
								}
							}
						}
						],
						//快速搜索
						searchitems : [
								{
									display : '订单编号',
									name : 'hwapplyNumb'
								},
								{
									display : '单据日期',
									name : 'orderTime'
								},
								{
									display : '供应商名称',
									name : 'suppName'
								},
								{
									display : '业务员',
									name : 'sendName'
								},
								{
									display : '物料编号',
									name : 'productNumb'
								},
								{
									display : '物料名称',
									name : 'productName'
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