// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".approvalNoGrid").yxgrid("reload");
};
$(function() {
			$(".approvalNoGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
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
									display : '起草人名称',
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
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true
								}
								],

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
										showOpenWin("?model=purchase_contract_purchasecontract&action=toReadTab&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("请选中一条数据");
								}
							}

						},
						{
							text : '审批',
							icon : 'edit',
							action : function(row,rows,grid){
								if(row){
									if(row.isTemp=="0"){//采购订单审批
										location = "controller/purchase/contract/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_apply_basic&skey="+row['skey_'];
									}else{//采购订单变更审批
										location = "controller/purchase/change/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_apply_basic&skey="+row['skey_'];
									}
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