// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".serviceContractCloseGrid").yxgrid("reload");
};
$(function() {
			$(".serviceContractCloseGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'engineering_serviceContract_serviceContract',
//						action : 'pageJson',
						action : 'pageJsonUnLimit',
						title : '已关闭的服务合同',
						isToolBar : false,
						showcheckbox : false,

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '合同名称',
									name : 'orderName',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.name;
									}
								},{
									display : '鼎利合同号',
									name : 'contractNo',
									sortable : true
								},{
									display : '合同签约方',
									name : 'cusName',
									sortable : true
								}, {
									display : '合同申请人',
									name : 'createName',
									sortable : true
								},{
									display : '销售负责人',
									name : 'salesLeader',
									sortable :　true
								}
								,{
									display : '技术负责人',
									name : 'techdirector',
									sortable : true
								}
								,{
									display : '部门负责人',
									name : 'depHeads',
									sortable : true
								},  {
									display : '审核状态',
									name : 'ExaStatus',
//									datacode : 'SHZT',
									sortable : true
								},{
									display : '合同执行状态',
									name : 'status',
//									datacode : 'HTZT',
									sortable : true
								}],
						param : {
							status : '完成合同'
						},
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="+row.id);
								}else{
									alert("请选中一条数据");
								}
							}

						}],
						//快速搜索
						searchitems : [
								{
									display : '合同名称',
									name : 'name'
								},{
									display : '合同编号',
									name : 'orderCodeOrTempSearch'
								}
								],
						// title : '客户信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "createTime",
						//显示查看按钮
						isViewAction : false
//						isAddAction : true,
//						isEditAction : false,
//						isDelAction : true,
//						param : { ExaStatus : 'WCHT'}
					});

		});