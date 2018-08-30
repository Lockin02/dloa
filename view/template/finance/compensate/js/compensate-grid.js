var show_page = function(page) {
	$("#compensateGrid").yxsubgrid("reload");
};
$(function() {
	$("#compensateGrid").yxsubgrid({
		model : 'finance_compensate_compensate',
		title : '赔偿单列表',
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width : 110,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=finance_compensate_compensate&action=toView&id="+row.id+"\",1,600,1000,"+row.id+")'>"+v+"</a>";
			}
		}, {
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width : 80
		}, {
			name : 'objId',
			display : '业务单id',
			sortable : true,
			hide : true
		}, {
			name : 'objType',
			display : '业务类型',
			sortable : true,
			width : 60,
			datacode : 'PCYWLX'
		}, {
			name : 'objCode',
			display : '业务单编号',
			sortable : true
		}, {
			name : 'dutyTypeName',
			display : '赔偿主体',
			sortable : true,
			width : 60,
			process : function(v){
				return (v == "NULL")? "" : v;
			}
		}, {
			name : 'dutyObjName',
			display : '赔偿对象',
			sortable : true
		}, {
			name : 'formMoney',
			display : '单据金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'compensateMoney',
			display : '确认赔偿金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'deductMoney',
			display : '已收赔偿金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'formStatus',
			display : '单据状态',
			sortable : true,
			process : function(v){
				switch(v){
					case '0' : return '待确认';break;
					case '1' : return '金额已确认';break;
					case '2' : return '待赔偿确认';break;
					case '4' : return '已完成';break;
					case '5' : return '关闭';break;
					default : return v;
				}
			},
			width : 70
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true,
			width : 70
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
			name : 'confirmName',
			display : '金额确认人',
			sortable : true,
			width : 80
		}, {
			name : 'confirmTime',
			display : '金额确认时间',
			sortable : true,
			width : 120
		}, {
			name : 'comConfirmName',
			display : '赔偿确认人',
			sortable : true,
			width : 80
		}, {
			name : 'comConfirmTime',
			display : '赔偿确认时间',
			sortable : true,
			width : 120
		}, {
//			name : 'auditorName',
//			display : '财务经理审核',
//			sortable : true,
//			width : 80
//		}, {
//			name : 'auditTime',
//			display : '审核时间',
//			sortable : true,
//			width : 120
//		}, {
			name : 'closerName',
			display : '关闭人',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'closeTime',
			display : '关闭时间',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'relDocType',
			display : '源单类型',
			sortable : true,
			width : 60,
			datacode : 'PCYDLX'
		}, {
			name : 'relDocCode',
			display : '源单编号',
			sortable : true,
			width : 120
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_compensate_compensatedetail&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productNo',
				display : '物料编号',
				width : 80
			},{
				name : 'productName',
				display : '物料名称',
				width : 140
			},{
				name : 'productModel',
				display : '规格型号',
				width : 140
			},{
				name : 'unitName',
				display : '单位',
				width : 60
			},{
				name : 'number',
				display : '数量',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'price',
				display : '单价',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'money',
				display : '金额',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'compensateRate',
				display : '赔偿比例',
				process : function(v){
					return v + " %" ;
				},
				width : 80
			},{
				name : 'compensateMoney',
				display : '赔偿金额',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}]
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin(
					"?model=finance_compensate_compensate&action=toView&id=" + row[p.keyField]
					,1,600,1100,row.id);
			}
		},
		//加一个确认、关闭菜单
		menusEx : [{
			text : '确认金额',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.formStatus == "0" || row.formStatus == "1") && (row.ExaStatus == '待提交' || row.ExaStatus == "打回")) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin("?model=finance_compensate_compensate&action=toEdit&id="
					+ row.id
					+ "&isConfirm=1"
					+ "&skey=" + row.skey_ ,1,700,1100,row.id);
			}
		},{
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.formStatus == "1" && (row.ExaStatus == '待提交' || row.ExaStatus == "打回")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/finance/compensate/ewf_compensate.php?actTo=ewfSelect&billId='
						+ row.id
						+ "&flowMoney=" + row.formMoney
						+ "&billDept=" + row.deptId
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '撤销审批',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "部门审批") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAuditedContract",
						data : {
							billId : row.id,
							examCode : 'oa_finance_compensate'
						},
						success : function(msg) {
							//alert(msg);exit;
							if (msg == '1') {
								alert('操作失败，原因：\n1.已撤销申请,不能重复撤销\n2.单据已经存在审批信息，不能撤销审批');
								return false;
							}else{
								$.ajax({
								    type: "GET",
								    url: 'controller/finance/compensate/ewf_compensate.php?actTo=delWork',
								    data: {"billId" : row.id },
								    async: false,
								    success: function(data){
								    	alert(data);
								    	show_page();
									}
								});
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			text : '确认赔偿',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dutyType == 'PCZTLX-02' && row.formStatus == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_compensate_compensate&action=toDeduct&id="
	                    + row.id
	                    + "&skey=" + row.skey_
	                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
//		},{
//			text : '取消赔偿确认',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.formStatus == "3") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('确认进行 取消赔偿确认 操作吗？')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=ajaxUnComConfirm',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('取消确认成功');
//					    	}else{
//					    		alert('取消确认失败');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
//		},{
//			text : '经理审核',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.formStatus == "3") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('确认进行 经理审核 操作吗？')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=ajaxAudit',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('审核成功');
//					    	}else{
//					    		alert('审核失败');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
//		},{
//			text : '取消经理审核',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if(row.auditTime != ""){
//					var auditDate = row.auditTime.substring(0,10);
//					var thisDate = $("#thisDate").val();
//					if(DateDiff(auditDate,thisDate) > 1){
//						return false;
//					}
//				}
//				if (row.formStatus == "4") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('确认进行 取消经理审核 操作吗？')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=ajaxUnAudit',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('取消审核成功');
//					    	}else{
//					    		alert('取消审核失败');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
//		},{
//			text : '关闭',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if ((row.formStatus == "0" || row.formStatus == "1") && (row.ExaStatus == '待提交' || row.ExaStatus == "打回")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('确认进行 关闭 操作吗？')){
//					$.ajax({
//					    type: "POST",
//					    url: '?model=finance_compensate_compensate&action=close',
//					    data: {"id" : row.id},
//					    async: false,
//					    success: function(data){
//					    	if(data == "1"){
//								alert('关闭成功');
//					    	}else{
//					    		alert('关闭失败');
//					    	}
//					    	show_page();
//						}
//					});
//				}
//			}
		}],
		buttonsEx : [{
			text : '打印',
			icon : 'print',
			action : function(row,rows,idArr) {
				if(row){
					idStr = idArr.toString();
					showModalWin("?model=finance_compensate_compensate&action=toBatchPrintAlone&id=" + idStr ,1);
				}else{
					alert('请至少选择一张单据打印');
				}
			}
		}],
		//过滤数据
		comboEx : [{
			text : '单据状态',
			key : 'formStatus',
			data : [{
				text : '待确认',
				value : '0'
			}, {
				text : '金额已确认',
				value : '1'
			}, {
				text : '待赔偿确认',
				value : '2'
			}, {
				text : '已完成',
				value : '4'
			}, {
				text : '关闭',
				value : '5'
			}]
		},{
		     text:'审批状态',
		     key:'ExaStatus',
		     type : 'workFlow'
		},{
		     text:'赔偿主体',
		     key:'dutyType',
		     datacode : 'PCZTLX'
		}],
		searchitems : [{
			display : "单据编号",
			name : 'formCodeSearch'
		},{
			display : "业务单编号",
			name : 'objCodeSearch'
		},{
			display : "源单编号",
			name : 'relDocCodeSearch'
		},{
			display : "设备负责人",
			name : 'chargerNameSearch'
		}]
	});
});