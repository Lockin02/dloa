// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".serviceContractDoGrid").yxgrid("reload");
};
$(function() {
			$(".serviceContractDoGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						model : 'engineering_serviceContract_serviceContract',
//						action : 'pageJson',
						action : 'pageJsonUnLimit',
						title : '执行中的服务合同',
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
									},
									width : '180'
								},{
									display : '鼎利合同号',
									name : 'contractNo',
									sortable : true,
									width : '140'
								},{
									display : '合同签约方',
									name : 'cusName',
									sortable : true,
									width : '120'
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
								},
									{
									display : '合同执行状态',
									name : 'status',
//									datacode : 'HTZT',
									sortable : true
								},
								{
									display : '任务书状态',
									name : 'missionStatus',
									sortable : true
								}],
						param : {
							status : '合同执行中'
						},
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action : function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
									showOpenWin('?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='+row.id)
								}else{
									alert("请选中一条数据");
								}
							}

						},
						{
							text : '下达任务书',
							icon : 'add',
							showMenuFn : function(row){
								if(row.ExaStatus == '完成' && row.status == '合同执行中' && row.missionStatus == '未下达' ){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
//									if((row.ExaStatus == '已审核' && row.status == '合同执行中')|(row.ExaStatus == '完成' && row.status == '合同执行中')){
									if(row.ExaStatus == '完成' && row.status == '合同执行中' && row.missionStatus == '未下达' ){
										showOpenWin('?model=engineering_prjMissionStatement_esmmission&action=issueMissionStatement&contractId='+row.id );
//										showThickboxWin("?model=engineering_prjMissionStatement_esmmission&action=issueMissionStatement&contractId="
//										+ row.id
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 500 + "&width=" + 800);
									}
								}
							}
						},
						{
							text : '关闭合同',
							icon : 'delete',
							action : function(row,rows,grid){
								if(row){
									if((row.ExaStatus == '已审核' && row.status == '合同执行中')|(row.ExaStatus == '完成' && row.status == '合同执行中')){
										if(confirm("确定要关闭服务合同【" + row.name + "】吗？")){
											location = '?model=engineering_serviceContract_serviceContract&action=putContractClose&contractId='+ row.id
										}
									}
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
//						isEditAction : false
					});

		});