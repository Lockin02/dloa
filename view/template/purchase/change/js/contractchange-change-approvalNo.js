// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".approvalNoGrid").yxgrid("reload");
};
$(function() {
			$(".approvalNoGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_change_contractchange',
						action : 'pageJsonNo',
						title : '待审批的采购订单',
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
									datacode : 'FPLX',
									sortable : true
								},{
									display : '版本号',
									name : 'version',
									width : 70,
									sortable : true
								}
								, {
									display : '付款类型',
									name : 'paymetType',
									datacode : 'fkfs',
									sortable : true
								}
								],
						//扩展按钮
						param : {
							ExaStatus : '部门审批'
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
//										+ 500 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=init&perm=view&id="+row.id+"&skey="+row['skey_']);
//									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="+row.id+"&applyNumb="+row.applyNumb);
								}else{
									alert("请选中一条数据");
								}
							}

						},
//						{
//							text : '查看历史版本',
//							icon : 'view',
//							action : function(row,rows,grid){
//								if(row){
//									location = "?model=purchase_change_contractchange&action=toViewHistory&id=" + row.id;
//								}
//							}
//						},
						{
							text : '审批',
							icon : 'edit',
							showMenuFn : function(row){
								if(row.ExaStatus == '部门审批'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									location = "controller/purchase/change/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_apply_basic&skey="+row['skey_'];
//									parent.location = "controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=" +row.id + "&examCode=oa_purch_apply_basic_version&formName=采购合同审批";
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
									display : '供应商名称',
									name : 'suppName'
								}
								],
						// title : '客户信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "id",
						//默认搜索顺序
						sortorder : "ASC",
						//显示查看按钮
						isViewAction : false,
						isAddAction : false,
						isEditAction : false,
						isDelAction : false
					});

		});