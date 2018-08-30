var show_page = function() {
	$("#myresourceapplyGrid").yxgrid("reload");
};

$(function() {
	//检查当前员工是否存在设备申请锁定记录
	var lockLimit = false;
	$.ajax({
		type: "POST",
		url: "?model=engineering_resources_lock&action=checkLock",
		async: false,
		success: function(data){
			if(data == 1){
				lockLimit = true;
			}
		}
	});
	$("#myresourceapplyGrid").yxgrid({
		model : 'engineering_resources_resourceapply',
		action : 'myJson',
		title : '我的设备申请',
		isDelAction : false,
		isOpButton : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
//		}, {
//			name : 'confirmStatus',
//			display : '确认',
//			sortable : true,
//			width : 30,
//			align : 'center',
//			hide : true,
//			process : function(v,row) {
//				switch(v){
//					case '0' : return '';break;
//					case '1' : return '<img src="images/icon/ok3.png" title="正在确认"/>';break;
//					case '2' : return '<img src="images/icon/ok2.png" title="已确认,确认人[' + row.confirmName + '],确认时间[' + row.confirmTime + ']"/>';break;
//				}
//			}
//		}, {
//			name : 'status',
//			display : '处理',
//			sortable : true,
//			width : 30,
//			align : 'center',
//			hide : true,
//			process : function(v) {
//				switch(v){
//					case '0' : return '';break;
//					case '1' : return '<img src="images/icon/cicle_blue.png" title="处理中"/>';break;
//					case '2' : return '<img src="images/icon/cicle_green.png" title="已处理"/>';break;
//				}
//			}
		}, {
			name : 'formNo',
			display : '申请单编号',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
						+ row.id + '&skey=' + row.skey_ + "\",1,700,1100,"+row.id+")'>" + v + "</a>";
			}
		}, {
			name : 'applyUser',
			display : '申请人',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'applyUserId',
			display : '申请人id',
			sortable : true,
			hide : true
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 75
		}, {
			name : 'applyTypeName',
			display : '申请类型',
			sortable : true,
			width : 70
		}, {
			name : 'getTypeName',
			display : '领用方式',
			sortable : true,
			width : 70
		}, {
			name : 'place',
			display : '设备使用地',
			sortable : true
		}, {
			name : 'deptName',
			display : '所属部门',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true,
			width : 120
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 120
		}, {
			name : 'managerName',
			display : '项目经理',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'managerId',
			display : '项目经理id',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '备注信息',
			sortable : true,
			width : 130,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true,
			width : 75
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		}, {
			name : 'confirmStatus',
			display : '单据状态',
			sortable : true,
			width : 80,
			process : function(v) {
				switch(v){
					case '0' : return '保存';break;
					case '1' : return '部门检查';break;
					case '2' : return '检查完成';break;
					case '6' : return '打回';break;
					case '3' : return '等待发货';break;
					case '4' : return '发货中';break;
					case '7' : return '发货撤回';break;
					case '5' : return '完成';break;
				}
			}
		},{
			name : 'status',
			display : '下达状态',
			sortable : true,
			width : 80,
			process : function(v) {
				switch(v){
					case '0' : return '未下达';break;
					case '1' : return '部分下达';break;
					case '2' : return '已下达';break;
				}
			}
		}],
		toAddConfig : {
			toAddFn : function() {
				alert("您好，新OA已上线，请到新OA提交需求申请。谢谢！");
				return false;
				//设备申请锁定验证
				if(lockLimit){
					alert('您的设备借用申请权限暂时被锁定，请对锁定设备进行【归还】或【续借】或【转借】操作，详情请联系设备管理员');
					return false;
				}
				showOpenWin("?model=engineering_resources_resourceapply&action=toAdd",1,700,1100,'toRAdd');
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if ((row.confirmStatus == "0" || row.confirmStatus == "6") && (row.ExaStatus == '待提交' || row.ExaStatus == '打回')) {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toEdit&id="
					+ row.id + "&skey=" + row['skey_'],1,700,1100,row.id);
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
						+ row[p.keyField],1,700,1100,row.id);
			}
		},
		menusEx : [{
			text : '提交确认',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.confirmStatus == "0" || row.confirmStatus == "6") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				//设备申请锁定验证
				if(lockLimit){
					alert('您的设备借用申请权限暂时被锁定，请联系设备管理员');
					return false;
				}
				if (confirm('确定将单据提交确认吗？')) {
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_resources_resourceapply&action=ajaxConfirmStatus",
					    data: {
					    	'id' : row.id,
					    	'confirmStatus' : '1'
					    },
					    async: false,
					    success: function(data){
					   		if(data == '1'){
								alert('提交成功');
								show_page();
							}else{
								alert('提交失败');
							}
						}
					});
				}
			}
		}, {
		// 	text : '提交审批',
		// 	icon : 'add',
		// 	showMenuFn : function(row) {
		// 		if (row.confirmStatus == "2" && (row.ExaStatus == '待提交' || row.ExaStatus == '打回')) {
		// 			return true;
		// 		}
		// 		return false;
		// 	},
		// 	action : function(row, rows, grid) {
		// 		if (row) {
		// 			if(row.projectId != "0"){
		// 				var billArea = '';
		// 				$.ajax({
		// 				    type: "POST",
		// 				    url: "?model=engineering_project_esmproject&action=getRangeId",
		// 				    data: {'projectId' : row.projectId },
		// 				    async: false,
		// 				    success: function(data){
		// 				   		billArea = data;
		// 					}
		// 				});
		// 		   		if(billArea != ''){
		// 					showThickboxWin('controller/engineering/resources/ewf_project.php?actTo=ewfSelect&billId='
		// 						+ row.id + "&billArea=" + billArea + "&billDept=" + row.deptId
		// 						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 				}else{
		// 					showThickboxWin('controller/engineering/resources/ewf_project.php?actTo=ewfSelect&billId='
		// 						+ row.id + "&billDept=" + row.deptId
		// 						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 				}
		// 			}else{
		// 				var appendDept = "";
		// 				$.ajax({
		// 				    type: "POST",
		// 				    url: "?model=engineering_resources_resourceapply&action=checkIsEsmDept",
		// 				    data: {'deptId' : row.deptId },
		// 				    async: false,
		// 				    success: function(data){
		// 				   		appendDept = data;
		// 					}
		// 				});
		// 				if(appendDept != ""){
		// 					appendDept = row.deptId + "," + appendDept;
		// 				}else{
		// 					appendDept = row.deptId;
		// 				}
  //                       //区域
  //                       var billArea = '';
  //                       $.ajax({
  //                           type: "POST",
  //                           url: "?model=engineering_officeinfo_range&action=getRangeByProvinceAndDept",
  //                           data: {'provinceId' : row.placeId ,'deptId' : row.deptId},
  //                           async: false,
  //                           success: function(data){
  //                               billArea = data;
  //                           }
  //                       });
  //                       if(billArea != ''){
  //                           showThickboxWin('controller/engineering/resources/ewf_person.php?actTo=ewfSelect&billId='
  //                               + row.id + "&billArea=" + billArea
  //                               + "&billDept=" + appendDept
  //                               + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
  //                       }else{
  //                           showThickboxWin('controller/engineering/resources/ewf_person.php?actTo=ewfSelect&billId='
  //                               + row.id
  //                               + "&billDept=" + appendDept
  //                               + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
  //                       }
		// 			}
		// 		}
		// 	}
		// }, {
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.confirmStatus == "0" || row.confirmStatus == "6") && (row.ExaStatus == '待提交' || row.ExaStatus == '打回')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_resources_resourceapply&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							} else {
								alert("删除失败! ");
							}
						}
					});
				}
			}
//		}, {
//			text : "撤回检查",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.confirmStatus == "1" ) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (window.confirm(("确定要撤回?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=engineering_resources_resourceapply&action=checkBack",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('撤回成功！');
//								show_page(1);
//							} else {
//								alert("撤回失败! ");
//							}
//						}
//					});
//				}
//			}
		}],
        //过滤数据
		comboEx:[{
			text : '单据状态',
			key : 'confirmStatus',
			data : [{
					text : '保存',
					value : '0'
				}, {
					text : '部门检查',
					value : '1'
				}, {
					text : '检查完成',
					value : '2'
				},{
					text : '打回',
					value : '6'
				}, {
					text : '等待发货',
					value : '3'
				},{
					text : '发货中',
					value : '4'
				},{
					text : '发货撤回',
					value : '7'
				},{
					text : '完成',
					value : '5'
				}]
			},{
		     text:'审核状态',
		     key:'ExaStatus',
		     type : 'workFlow'
		}],
		searchitems : [{
			display : "申请单编号",
			name : 'formNoSch'
		},{
			display : "项目编号",
			name : 'projectCodeSch'
		},{
			display : "项目名称",
			name : 'projectNameSch'
		}]
	});
});