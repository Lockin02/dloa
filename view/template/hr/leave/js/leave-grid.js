var show_page = function(page) {
	$("#leaveGrid").yxgrid("reload");
};

$(function() {
	$("#leaveGrid").yxgrid({
		model : 'hr_leave_leave',
		action : 'pageJsonLeave',
		param:{
			state:'1,2,3,4'
		},
		title : '离职管理',
		showcheckbox : true,
		isDelAction : false,
		isEditAction : false,
		isAddAction : true,
		isOpButton : false,
		isAdvanceSearch : false,
		bodyAlign : 'center',

		buttonsEx : [{
			name : 'printLeave',
			text : "打印离职证明",
			icon : 'print',
			action :function(row ,rows ,idArr) {
				if(row){
					for(var i = 0 ;i < rows.length ;i++) {
						if(rows[i].ExaStatus != '完成' || rows[i].comfirmQuitDate == '' || rows[i].state == '4') {
							alert('有无效数据，请重新选择！');
							return false;
						}
					}
					idStr = idArr.toString();
				} else {
					idStr = '';
				}
				showThickboxWin("?model=hr_leave_leave&action=toConfirmation&idStr=" + idStr
					+"&type=prove"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=650");
			}
		},{
			name : 'printOrder',
			text : "打印交接清单",
			icon : 'print',
			action :function(row ,rows ,idArr){
				if(row){
					for(var i = 0 ;i < rows.length ;i++) {
						if(rows[i].isHandover != '1') {
							alert('存在未发起的交接清单，请重新选择！');
							return false;
						}
						if(rows[i].state == '4') {
							alert('存在已关闭的离职申请，请重新选择！');
							return false;
						}
					}
					idStr = idArr.toString();
				} else {
					idStr = '';
				}
				showThickboxWin("?model=hr_leave_leave&action=toConfirmation&idStr=" + idStr
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=650");
			}
		},{
			name : 'expport',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_leave_leave&action=toExport&docType=RKPURCHASE"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950");
			}
		},{
			name : 'expportunconfirm',
			text : '未确认信息导出',
			icon : 'excel',
			action : function(row) {
				window.open("?model=hr_leave_leave&action=toExpportunconfirm"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950");
			}
		},{
			name : 'updateArchives',
			text : '更新员工档案',
			icon : 'edit',
			action : function(row ,rows ,grid) {
				if(rows){
					var checkedRowsIds = $("#leaveGrid").yxgrid("getCheckedRowIds");  //获取选中的id
					var num = 0;
					var states = [] ,UpdateStates = [];
					$.each(rows,function(i,n) {
						var o = eval( n );
						states.push(o.ExaStatus);
						UpdateStates.push(o.state);
						if(o.nowDate<=o.comfirmQuitDate){
							num++;
						}
					});
					var uniqueState = $.unique(states);
					var stateLength = uniqueState.length;
					var uniqueUpdateStates = $.unique(UpdateStates);
					var UpdateStatesLength = uniqueUpdateStates.length;
					if(stateLength == 1 && UpdateStatesLength == 1 && uniqueState[0] == '完成'
						&& uniqueUpdateStates[0] != '3' && num == 0) {
						if(window.confirm("确认更新?")){
							$.ajax({
								type:"POST",
								url:"?model=hr_leave_leave&action=updatePersonInfo",
								data:{
									id : checkedRowsIds
								},
								success:function(msg){
									if(msg == 1){
										alert('更新成功!');
										show_page();
									}else{
										alert('更新失败!');
										show_page();
									}
								}
							});
						}
					} else {
						alert("请选择状态为'档案待更新'的单据");
					}
				} else {
					alert("请选择单据！");
				}
			}
		},{
			name : 'editLeaveInfoExcel',
			text : '修改离职信息',
			icon : 'edit',
			action : function() {
				showThickboxWin("?model=hr_leave_leave&action=toEditLeaveInfoExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=650");
			}
		}],

		event : {
			row_dblclick : function(e, row, data) {
				showThickboxWin("?model=hr_leave_leave&action=toView&id="
					+ data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},

		// 扩展右键菜单
		menusEx : [{
			text : '确认离职类型',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toEditType&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
			}
		},{
			name : 'sumbit',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.ExaStatus != '完成' && row.ExaStatus!= '部门审批') && row.state == 2) {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					var auditType = '';
					switch(row.wageLevelCode){
						case "GZJBFGL" : auditType = '5';break;//非管理层
						case "GZJBJL" : auditType = '15';break;//经理
						case "GZJBZG" : auditType = '25';break;//主管
						case "GZJBZJ" : auditType = '35';break;//总监
						case "GZJBFZ" : auditType = '45';break;//副总
						case "GZJBZJL" : auditType = '75';break;//总经理
					}

					showThickboxWin("controller/hr/leave/ewf_index1.php?actTo=ewfSelect&billId=" + row.id
						+ "&billDept=" + row.deptId
						+ "&flowMoney=" + auditType
						+ "&proSid=" + row.projectManagerId
						+ "&eUserId=" + row.userAccount
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=600"');
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_leave&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			name : 'realReason',
			text : '真实离职原因',
			icon : 'edit',
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin('?model=hr_leave_leave&action=toEditReal&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '面谈记录',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_interview&action=InterviewNotice&leaveId=' + row.id);
			}
		},{
			text : '离职交接清单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=handoverlist&leaveId=' + row.id);
			}
		},{
			text : '修改交接人',
			icon : 'edit',
			showMenuFn : function(row) {
				//添加判断是否已经确认完事项且单据未关闭
				if (row.isHandover != '0' && row.isAffirmAll != '0' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toAlterHand&leaveId=' + row.id);
			}
		},{
			text : '重启离职交接清单',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isHandover != '0' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toRestart&leaveId='+ row.id);
			}
		},{
			text : '确认交接清单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.isHandover == '1' && row.userSelfCstatus != 'YQR' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=handoverProlist&leaveId=' + row.id );
			}
		},{
			text : '发送邮件',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toSendEmail&leaveId='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '发送离职指引',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toSendEmailguide&leaveId='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '打印离职证明',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.comfirmQuitDate != '' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toLeaveProof&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800");
			}
		},{
			text : '备注进度',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toEditRemark&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '修改员工状态信息',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=user&action=edit&userid='
					+ row.userAccount
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '修改离职信息',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toEditLeaveInfo&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '打回申请',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toBack&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text:"更新员工档案",
			icon :'edit',
			showMenuFn : function(row){
				if (row.ExaStatus == '完成' && row.nowDate > row.comfirmQuitDate
						&& (row.state != "3" && row.state != '4')){
					return true;
				}
				else return false;
			},
			action : function(row ,rows ,grid) {
				if(window.confirm("确认更新?")) {
					$.ajax({
						type:"POST",
						url:"?model=hr_leave_leave&action=updatePersonInfo",
						data:{
							id : row.id
						},
						success:function(msg){
							if(msg == 1) {
								alert('更新成功!');
								show_page();
							}else{
								alert('更新失败!');
								show_page();
							}
						}
					});
				}
			}
		},{
			name : 'cancel',
			text : '撤回申请',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '未提交' && row.state == '2') || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if(row.ExaStatus == "未提交"){
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=backSubmit",
						data : {
							id : row.id,
							state:1
						},
						success : function(msg) {
							if (msg == 1) {
								alert('撤回成功！');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				} else {
					var ewfurl = 'controller/hr/leave/ewf_index1.php?actTo=delWork&billId=';
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_hr_leave'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤回审批！');
								$("#leaveGrid").yxgrid("reload");
								return false;
							} else {
								if(confirm('确定要撤回审批吗？')){
									$.ajax({
										type: "GET",
										url: ewfurl,
										data: {"billId" : row.id },
										async: false,
										success: function(data){
											$.ajax({
												type : "POST",
												url : "?model=hr_leave_leave&action=backSubmit",
												data : {
													id : row.id,
													state : 1
												},
												success : function(msg) {
													if (msg == 1) {
														alert('撤回成功！');
														$("#leaveGrid").yxgrid("reload");
													}
												}
											});
										}
									});
								}
							}
						}
					});
				}
			}
		},{
			name : 'close',
			text : '关闭原因',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toCloseReason&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
			}
		},{
			name : 'close',
			text : '关闭',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toClose&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
			}
		}],

		// 列信息
		colModel : [{
			display: '打印',
			name: 'id',
			width : 30,
			align : 'center',
			sortable: true,
			process : function(v,row){
				if(row.ExaStatus == '完成' && row.comfirmQuitDate !='' && row.state != '4') {
					return '<img src="images/icon/print.gif" />';
				}
			}
		},{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'leaveCode',
			display : '单据编号',
			sortable : true,
			process : function(v, row) {
				var date = new Date();
				var leaveDate = new Date(row.comfirmQuitDate);
				var ms = (leaveDate.valueOf() - date.valueOf()) / 1000;
				// 离当前时间不到两天，且通过审批，但是未发起交接清单的，单据编号显示为红色
				if (ms < 172800 && ms > 0 && row.ExaStatus == '完成' && row.handoverId == '') {
					if (row.ExaStatus=='完成' && row.nowDate > row.comfirmQuitDate && row.state != "3") {
						return '<img src="images/icon/icon139.gif"/><a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#CE0000'>"
							+ v + "</font>" + '</a>';
					} else {
						return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#CE0000'>"
							+ v + "</font>" + '</a>';
					}
				} else {
					if (row.ExaStatus=='完成' && row.nowDate > row.comfirmQuitDate && row.state != "3") {
						return '<img src="images/icon/icon139.gif"/><a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v + "</font>" + '</a>';
					} else {
						return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v + "</font>" + '</a>';
					}
				}
			}
		},{
			name : 'userNo',
			display : '员工编号',
			width : 80,
			sortable : true,
			process : function(v,row){
				if (row.emailSate == 1) {
					return "<img src='images/icon/icon144.gif'/>"+ v;
				} else {
					return  v ;
				}
			}
		},{
			name : 'userName',
			display : '员工姓名',
			width : 60,
			sortable : true
		},{
			name : 'state',
			display : '单据状态',
			sortable : true,
			width : 70,
			process:function(v ,row) {
				if (v == "1") {
					return "未确认类型 ";
				}else if (v == "2") {
					if(row.ExaStatus == '完成' && row.nowDate > row.comfirmQuitDate) {
						return "档案待更新";
					} else {
						return "已确认类型";
					}
				} else if (v == '4'){
					return "已关闭";
				} else {
					return "已更新档案";
				}
			}
		},{
			name : 'ExaStatus',
			display : '审批状态',
			width : 60,
			sortable : true
		},{
			name : 'userSelfCstatus',
			display : '员工确认状态',
			sortable : true,
			width : 70,
			process:function(v) {
				if(v == "WQR") {
					return "未确认 ";
				} else {
					return "已确认";
				}
			}
		},{
			name : 'handoverCstatus',
			display : '交接清单状态',
			width : 70,
			sortable : true,
			process:function(v ,row) {
				if (row.isHandover == 0) {
					return "未发起 ";
				} else {
					if (v == "WQR") {
						return "未确认 ";
					} else {
						return "已确认";
					}
				}
			}
		},{
			name : 'companyName',
			display : '公司',
			width : 60,
			sortable : true
		},{
			name : 'personnelTypeName',
			display : '员工类型',
			width : 60,
			sortable : true
		},{
			name : 'deptName',
			display : '部门',
			width : 80,
			sortable : true
		},{
			name : 'deptNameS',
			display : '二级部门',
			width : 80,
			sortable : true
		},{
			name : 'deptNameT',
			display : '三级部门',
			width : 80,
			sortable : true
		},{
            name : 'deptNameF',
            display : '四级部门',
            width:80,
            sortable : true
        },{
			name : 'jobName',
			display : '职位',
			width : 80,
			sortable : true
		},{
			name : 'workProvince',
			display : '省份',
			width : 80,
			sortable : true,
			hide : true
		},{
			name : 'entryDate',
			display : '入职日期',
			width : 70,
			sortable : true
		},{
			name : 'quitTypeName',
			display : '离职类型',
			width : 100,
			sortable : true
		},{
			name : 'leaveApplyDate',
			display : '离职申请日期',
			width : 80,
			sortable : true,
			process : function (v) {
				return v.substr(0 ,10);
			}
		},{
			name : 'requireDate',
			display : '期望离职日期',
			width : 80,
			sortable : true
		},{
			name : 'comfirmQuitDate',
			display : '离职日期',
			width : 80,
			sortable : true
		},{
			name : 'salaryEndDate',
			display : '工资结算截止日期',
			width : 90,
			sortable : true
		},{
			name : 'salaryPayDate',
			display : '工资支付日期',
			width : 80,
			sortable : true
		},{
			name : 'pensionReduction',
			display : '社保减员',
			width : 90,
			sortable : true
		},{
			name : 'fundReduction',
			display : '公积金减员',
			width : 90,
			sortable : true
		},{
			name : 'employmentEnd',
			display : '用工终止',
			width : 50,
			sortable : true
		},{
			name : 'softSate',
			display : '办公软件状态',
			width : 80,
			sortable : true,
			process:function(v){
				if(v == "1"){
					return "已关闭 ";
				} else {
					return "未关闭";
				}
			}
		},{
			name : 'createName',
			display : '申请人',
			width : 60,
			sortable : true
		},{
			name : 'remark',
			display : '进度备注',
			width : 200,
			sortable : true,
			align : 'left'
		},{
			name : 'mobile',
			display : '联系电话',
			width : 100,
			sortable : true
		},{
			name : 'personEmail',
			display : '私人邮箱',
			width : 120,
			sortable : true
		},{
			name : 'postAddress',
			display : '邮寄地址',
			width : 150,
			sortable : true,
			align : 'left'
		},{
			name : 'quitReson',
			display : '离职原因',
			width : 350,
			sortable : true,
			align : 'left',
			process :　function(v){
				//提取离职原因，替换特殊字符
				var str = v.substring(-5);
				if (str == "^nbsp") { //没有包含其他原因
					v = v.replace(/\^nbsp/g,"；");
				} else {
					var num =  v.split("^nbsp").length - 1;
					for (var i = 0; i < num - 1; i++) {
						v = v.replace(/\^nbsp/,"；");
					}
					v = v.replace(/\^nbsp/,":"); //最后一个为其他
				}

				return v;
			}
		},{
			name : 'isBack',
			display : '是否黑名单',
			width : 70,
			sortable : true,
			process : function (v) {
				if (v == 1) {
					return  '是';
				} else {
					return '否';
				}
			}
		},{
			name : 'realReason',
			display : '真实离职原因',
			width : 350,
			sortable : true,
			align : 'left'
		}],

		lockCol:['leaveCode','userNo','userName'],//锁定的列名

		toEditConfig : {
			action : 'toEdit'
		},
		toAddConfig : {
			formHeight : 700
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin('?model=hr_leave_leave&action=toViewTab&id=' + rowData['id']);
			}
		},

		//下拉过滤
		comboEx : [{
			text : '单据状态',
			key : 'stateS',
			data : [{
				text : '未确认类型',
				value : '1'
			},{
				text : '已确认类型',
				value : '2_1'
			},{
				text : '档案待更新',
				value : '2_2'
			},{
				text : '已更新档案',
				value : '3'
			},{
				text : '已关闭',
				value : '4'
			}]
		},{
			text : '离职类型',
			key : 'quitTypeCode',
			datacode : 'YGZTLZ'
		},{
			text : '交接清单状态',
			key : 'handoverCstatusS',
			data : [{
				text : '未发起',
				value : '1'
			},{
				text : '未确认',
				value : 'WQR'
			},{
				text : '已确认',
				value : 'YQR'
			}]
		},{
			text : '审批状态',
			key : 'spzt',
			data : [{
				text : '完成',
				value : '完成'
			},{
				text : '部门审批',
				value : '部门审批'
			},{
				text : '未提交',
				value : '未提交'
			},{
				text : '打回',
				value : '打回'
			}]
		},{
			text : '黑名单',
			key : 'isBack',
			data : [{
				text : '是',
				value : '1'
			},{
				text : '否',
				value : '0'
			}]
		}],

		searchitems : [{
			display : "单据编号",
			name : 'leaveCode'
		},{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "员工姓名",
			name : 'userName'
		},{
			display : "部门",
			name : 'deptName'
		},{
			display : "职位",
			name : 'jobName'
		},{
			display : "入职日期",
			name : 'entryDate'
		},{
			display : "期望离职日期",
			name : 'requireDate'
		},{
			display : "离职日期",
			name : 'comfirmQuitDate'
		},{
			display : "工资结算截止日期",
			name : 'salaryEndDate'
		},{
			display : "工资支付日期",
			name : 'salaryPayDate'
		},{
			display : "社保减员",
			name : 'pensionReduction'
		},{
			display : "公积金减员",
			name : 'fundReduction'
		},{
			display : "进度备注",
			name : 'remark'
		},{
			display : "离职原因",
			name : 'lzyy'
		},{
			display : "真实离职原因",
			name : 'realReason'
		}]
	});
});