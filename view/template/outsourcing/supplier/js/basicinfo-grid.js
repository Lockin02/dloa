var show_page = function(page) {
	$("#basicinfoGrid").yxgrid("reload");
};
$(function() {

			//表头按钮数组
	var buttonsArr = [
//        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
//			}
//        }
    ];
    //表头按钮数组
	var excelInArr = {
		name : 'exportIn',
		text : "导入外包供应商",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_basicinfo&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	var excelUpdateArr = {
		name : 'exportIn',
		text : "更新外包供应商",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_supplier_basicinfo&action=toExcelUpdate"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_supplier_basicinfo&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data ==1) {
				buttonsArr.push(excelInArr);
				buttonsArr.push(excelUpdateArr);
			}
		}
	});
	$("#basicinfoGrid").yxgrid({
		model : 'outsourcing_supplier_basicinfo',
		title : '外包供应商库',
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		param:{'isDel':0},
		bodyAlign:'center',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'suppCode',
			display : '供应商编号',
			width:70,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'suppName',
			display : '供应商名称',
			width:150,
			sortable : true
		}, {
			name : 'suppGrade',
			display : '认证等级',
			width:60,
			sortable : true,
			process:function(v){
					if(v=="1"){
						return "金 ";
					}else if(v=="2"){
						return "银";
					}else if(v=="3"){
						return "铜";
					}else if(v=="4"){
						return "黑名单";
					}else if(v=="0"){
						return "未认证";
					}
			}
		},  {
			name : 'officeName',
			display : '区域',
			width:50,
			sortable : true
		}, {
			name : 'province',
			display : '省份',
			width:50,
			sortable : true
		}, {
			name : 'suppTypeName',
			display : '供应商类型',
			width:60,
			sortable : true
		}, {
			name : 'registeredDate',
			display : '成立时间',
			width:70,
			sortable : true
		}, {
			name : 'registeredFunds',
			display : '注册资金(万元)',
			width:80,
			sortable : true
		},{
			name : 'mainBusiness',
			display : '主营业务',
			width:150,
			sortable : true
		}, {
			name : 'adeptNetType',
			display : '擅长网络类型',
			width:150,
			sortable : true
		}, {
			name : 'adeptDevice',
			display : '擅长厂家设备',
			width:150,
			sortable : true
		},  {
			name : 'certifyNumber',
			display : '认证人数',
			width:50,
			sortable : true
		} , {
			name : 'ExaStatus',
			display : '审批状态',
			width:50,
			sortable : true
		}, {
			name : 'createName',
			display : '录入人',
			width:90,
			sortable : true
		}, {
			name : 'createTime',
			display : '录入时间',
			width:120,
			sortable : true
		}],

		lockCol:['suppCode','suppName'],//锁定的列名


		buttonsEx : buttonsArr,
		//下拉过滤
		comboEx : [{
			text : '认证等级',
			key : 'suppGrade',
			data : [{
					text : '未认证',
					value : '0'
				},{
					text : '金',
					value : '1'
				},{
					text : '银',
					value : '2'
				},{
					text : '铜',
					value : '3'
				},{
					text : '黑名单',
					value : '4'
				}]
			},{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
					text : '未审批',
					value : '未审批'
				},{
					text : '部门审批',
					value : '部门审批'
				},{
					text : '完成',
					value : '完成'
				}]
			}
		],

		// 扩展右键菜单

		menusEx : [{
				text : '编辑',
				icon : 'edit',
				action : function(row) {
					if(row.status == '1'){
							$.ajax({
								type : 'POST',
								url : '?model=outsourcing_supplier_basicinfo&action=getLimits',
								data : {
									'limitName' : '修改权限'
								},
								async : false,
								success : function(data) {
									if (data ==1) {
										showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabEdit&id=" +row.id,'1');
									}else{
										alert('没有操作权限');
										$("#basicinfoGrid").yxgrid("reload");
									}
								}
							});
					}else{
						showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabEdit&id=" +row.id,'1');}
				}

			},{
				text : '提交审批',
				icon : 'add',
				showMenuFn : function(row) {
					if (row.ExaStatus == '未审批') {
						return true;
					}
					return false;
				},
				action : function(row) {
               	 showThickboxWin('controller/outsourcing/supplier/ewf_index.php?actTo=ewfSelect&billId='+ row.id+ '&flowMoney=0&billDept='+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");

				}

			},{
				text : '认证等级变更',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == '1'&&(row.changeExaStatus == '打回'||row.changeExaStatus == '未审批'||row.changeExaStatus == '完成')) {
						return true;
					}
					return false;
				},
				action : function(row) {
               	 	showThickboxWin("?model=outsourcing_supplier_basicinfo&action=toChangeSuppGrad&id=" +row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=750");
               	 	$.ajax({
							type : "POST",
							url : "?model=outsourcing_supplier_basicinfo&action=ajaxChange",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									$("#basicinfoGrid").yxgrid("reload");
								}
							}
						});
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
					action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcesupp_supplib&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
				},{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus == '未审批') {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_supplier_basicinfo&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#basicinfoGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},  {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status==1&&row.isDel==0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					$.ajax({
						type : 'POST',
						url : '?model=outsourcing_supplier_basicinfo&action=getLimits',
						data : {
							'limitName' : '删除权限'
						},
						async : false,
						success : function(data) {
							if (data ==1) {
								if (window.confirm(("确定要删除?"))) {
									$.ajax({
										type : "POST",
										url : "?model=outsourcing_supplier_basicinfo&action=deleteSupp",
										data : {
											id : row.id,
											isDel : 1
										},
										success : function(msg) {
											if (msg == 1) {
												alert('删除成功！');
												$("#basicinfoGrid").yxgrid("reload");
											}
										}
									});
								}
							}else{
								alert('没有操作权限');
								$("#basicinfoGrid").yxgrid("reload");
							}
						}
					});
				}
		},{
			name : 'view',
			text : "操作日志",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcesupp_supplib"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		} ],
		toAddConfig : {
			formWidth : 1000,
			formHeight : 500,
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_supplier_basicinfo&action=toAdd",'1');
			}
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_supplier_basicinfo&action=toTabView&id=" + get[p.keyField],1);
				}
			}
		},
		searchitems : [{
						display : "供应商编号",
						name : 'suppCode'
					},{
						display : "供应商名称",
						name : 'suppName'
					},{
						display : "区域",
						name : 'officeName'
					},{
						display : "省份",
						name : 'province'
					},{
						display : "供应商类型",
						name : 'suppTypeName'
					},{
						display : "成立时间",
						name : 'registeredDate'
					},{
						display : "法人代表",
						name : 'legalRepre'
					},{
						display : "主营业务",
						name : 'mainBusiness'
					},{
						display : "擅长网络类型",
						name : 'adeptNetType'
					},{
						display : "擅长厂家设备",
						name : 'adeptDevice'
					}],

				sortname : 'suppGrade',
				sortorder : 'ASC'
	});
});