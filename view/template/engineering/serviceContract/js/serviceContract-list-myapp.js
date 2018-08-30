// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".serviceContractMyAppGrid").yxgrid("reload");
};
$(function() {
			$(".serviceContractMyAppGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'engineering_serviceContract_serviceContract',
//						action : 'toUnDoContractList&contractID='+$("#contractID").val()+"&systemCode="+$("#systemCode").val(),
						action : 'pageJson',
//						action : 'toMyApplicationTab',
						title : '我申请的服务合同',
						isToolBar : false,
						showcheckbox : false,
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									display : '合同签订日期',
									name : 'signDate',
									sortable : true,
									hide : true
								},
								{
									display : '合同名称',
									name : 'orderName',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.name;
									},
									width : '170'
								},{
									display : '鼎利合同号',
									name : 'contractNo',
									sortable : true,
									width : '110'
								},{
									display : '合同签约方',
									name : 'cusName',
									sortable : true,
									process : function(v,row){
										return row.cusName;
									},
									width : '110'
								}
//								, {
//									display : '申请人名称',
//									name : 'createName',
//									sortable : true
//								}
								,{
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
								}
								,
								{
									display : 'createId',
									name : 'createId',
									sortable : true,
									hide : true
								},
								{
									display : '审核状态',
									name : 'ExaStatus',
//									datacode : 'SHZT',
									sortable : true,
									width : '80'
								},{
									display : '执行状态',
									name : 'status',
//									datacode : 'SHZT',
									sortable : true,
									width : '80'
								},{
									display : '任务书状态',
									name : 'missionStatus',
									sortable : true,
									width : '60'
								}],
//						param : {
//							status : '合同未启动'
//						},
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

						},
						{
							text : '编辑',
							icon : 'edit',
							showMenuFn : function(row){
								if(row.ExaStatus == '未审核' | row.ExaStatus == '打回'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=engineering_serviceContract_serviceContract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 825);
									showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&id="+row.id)
								}else{
									alert("请选中一条数据");
								}
							}
						},
						{
							text : '提交审核',
							icon : 'add',
							showMenuFn : function(row){
								if(row.ExaStatus == '未审核' && row.status == '合同未启动' || row.ExaStatus == '打回'){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									if(row.ExaStatus == '未审核' && row.status == '合同未启动' || row.ExaStatus == '打回'){
										if(confirm("确定要将服务合同【" + row.name + "】提交审核吗？")){
											location = 'controller/engineering/serviceContract/ewf_index.php?actTo=ewfSelect&billId='+row.id+'&examCode=oa_contract_service&formName=服务合同审批'
										}
									}
								}else{
									alert("请选择“未审核”且是“未启动”状态的合同进行提交");
								}

							}
						},
						{
							text : '启动',
							icon : 'add',
							showMenuFn : function(row){
								if((row.ExaStatus == '已审核' && row.status == '合同未启动')|(row.ExaStatus == '完成' && row.status == '合同未启动')){
									return true;
								}
								return false;
							},
							action : function(row,rows,grid){
								if(row){
									if((row.ExaStatus == '已审核' && row.status == '合同未启动')|(row.ExaStatus == '完成' && row.status == '合同未启动')){
										if(confirm("确定要启动服务合同【" + row.name + "】吗？")){
											location = '?model=engineering_serviceContract_serviceContract&action=putContractStart&contractId='+ row.id
										}
									}
								}
							}
						},
						{
							text : '删除',
							icon : 'delete',
							showMenuFn : function(row){
								if(row.ExaStatus == '未审核' && row.status == '合同未启动' || row.ExaStatus == '打回'){
									return true;
								}
								return false;
							}
							,
							action : function(row){
								showThickboxWin('?model=engineering_serviceContract_serviceContract&action=deletesInfo&id='
										+row.id+
										"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600"
								);
							}

						}
						],
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
//						 title : '合同信息',
						//业务对象名称
						boName : '服务合同',
						sortname : "createTime",
						//显示查看按钮
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : false
//						//查看扩展信息
//						toViewConfig : {
//							action : 'toRead',
//							formWidth : 400,
//							formHeight : 340
//						},
						//由于涉及到工作流的跳转问题，于2010年12月27日注释
						//在弹出的窗口对审批工作流提交审批后，与在主窗口提交，在跳转后的处理上暂时无法兼容处理。
//						toAddConfig : {
//									text : '新建',
//									icon : '',
//									/**
//									 * 默认点击新增按钮触发事件
//									 */
//
//									toAddFn : function(p) {
//										showOpenWin("?model=engineering_serviceContract_serviceContract&action=toAddContract2");
//
//									}
//						}

					});

		});