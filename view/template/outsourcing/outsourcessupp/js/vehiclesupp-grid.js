var show_page = function(page) {
	$("#vehiclesuppGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	var buttonsArr = [];
	var addArr = false;
	var editArr = false;
	var limit = new Array(); //权限数组
	var excelInArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600");
		}
	};
	var excelOutCustomArr = {
		name : 'exportOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toExcelOutCustom"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
		}
	};
	var viewArr = {
		name : 'view',
		text : "高级查询",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toSearch&"
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800');
		}
    };
	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_outsourcessupp_vehiclesupp&action=getLimits',
		data : {
			'limitArr' : '导入权限,导出权限,新增权限,编辑权限,查看权限,删除权限,黑名单权限'
		},
		async : false,
		success : function(data) {
			limit = data.replace('"','').split(',');
			if (limit[0] == 1) {
				buttonsArr.push(excelInArr);
			}
			if (limit[1] == 1) {
				buttonsArr.push(excelOutCustomArr);
			}
			if (limit[2] == 1) {
				addArr = true;
			}
			if (limit[3] == 1) {
				editArr = true;
			}
		}
	});

	buttonsArr.push(viewArr);

	$("#vehiclesuppGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehiclesupp',
        title : '车辆供应商',
        bodyAlign : 'center',
        isDelAction : false,
        showcheckbox : false,
        isAddAction : addArr,
        isEditAction : editArr,
		//列信息
		colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
    					name : 'suppCode',
  					display : '供应商编号',
  					sortable : true,
						width : 70,
					process : function(v,row){
							return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + row.id +"\",1)'>" + v + "</a>";
					}
              },{
    					name : 'suppName',
  					display : '供应商名称',
  					sortable : true,
					width : 150,
					process : function(v,row){
						if (row.suppLevel == '0') {
							return "<span style='color:red'>" + v + "</span>";
						}else {
							return v;
						}
					}
              },{
    					name : 'province',
  					display : '省份',
  					sortable : true,
  						width : 40
              },{
    					name : 'city',
  					display : '城市',
  					sortable : true,
  						width : 80
              },{
    					name : 'suppCategory',
  					display : '供应商类型',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppCategoryName',
  					display : '供应商类型名称',
  					sortable : true,
 						hide : true
              },{
    					name : 'suppLevel',
  					display : '供应商级别',
  					sortable : true,
 						hide : true
              },{
    					name : 'registeredDate',
  					display : '成立时间',
  					sortable : true,
  						width : 80
              },{
    					name : 'registeredFunds',
  					display : '注册资金(万元)',
  					sortable : true
              },{
    					name : 'businessDistribute',
  					display : '业务分布',
  					sortable : true
              },{
    					name : 'businessDistributeId',
  					display : '业务分布Id',
  					sortable : true,
 						hide : true
              },{
    					name : 'carAmount',
  					display : '车辆数量',
  					sortable : true,
  						width : 50
              },{
    					name : 'driverAmount',
  					display : '司机数量',
  					sortable : true,
  						width : 50
              },{
    					name : 'tentativeTalk',
  					display : '初步商谈意向',
  					sortable : true,
  						width : 250,
  						align : 'left'
              },{
    					name : 'invoice',
  					display : '发票属性',
  					sortable : true,
 						hide : true
              },{
    					name : 'invoiceCode',
  					display : '发票属性编码',
  					sortable : true,
 						hide : true
              },{
    					name : 'taxPoint',
  					display : '发票税点',
  					sortable : true,
 						hide : true
              },{
    					name : 'isEquipDriver',
  					display : '能否配备司机',
  					sortable : true,
 						hide : true
              },{
    					name : 'isDriveTest',
  					display : '有无路测经验',
  					sortable : true,
 						hide : true
              },{
    					name : 'companyProfile',
  					display : '公司简介',
  					sortable : true,
 						hide : true
              },{
    					name : 'linkmanName',
  					display : '联系人姓名',
  					sortable : true,
  						width : 80
              },{
    					name : 'linkmanJob',
  					display : '联系人职务',
  					sortable : true,
  						width : 80
              },{
    					name : 'linkmanPhone',
  					display : '联系人电话',
  					sortable : true,
  						width : 80
              },{
    					name : 'linkmanMail',
  					display : '联系人邮箱',
  					sortable : true,
  						width : 150
              },{
    					name : 'ExaStatus',
  					display : '审批状态',
  					sortable : true,
 						hide : true
              },{
    					name : 'ExaDT',
  					display : '审批日期',
  					sortable : true,
 						hide : true
              }],
		lockCol:['suppCode','suppName'],//锁定的列名
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_outsourcessupp_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},

		menusEx : [{
			text : '删除',
			icon : 'delete',
			showMenuFn : function() {
				if (limit[5] == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_outsourcessupp_vehiclesupp&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#vehiclesuppGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '打入黑名单',
			icon : 'delete',
			showMenuFn : function(row) {
				if (limit[6] == 1 && row.suppLevel != '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_outsourcessupp_vehiclesupp&action=toBlacklistView&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		},{
			text : '撤销黑名单',
			icon : 'edit',
			showMenuFn : function(row) {
				if (limit[6] == 1 && row.suppLevel == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=outsourcing_outsourcessupp_vehiclesupp&action=toUndoBlackView&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
			}
		},{
			name : 'view',
			text : "操作日志",
			icon : 'view',
			showMenuFn : function() {
				if (limit[4] == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcessupp_vehiclesupp"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],

		buttonsEx : buttonsArr,

		toAddConfig : {
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toAdd",'1');
			}
		},
		toEditConfig : {
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toEditTab&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems : [{
				display : "供应商编号",
				name : 'suppCodeSea'
			},{
				display : "供应商名称",
				name : 'suppName'
			},{
				display : "省份",
				name : 'provinceSea'
			},{
				display : "城市",
				name : 'citySea'
			},{
				display : "成立时间",
				name : 'registeredDateSea'
			},{
				display : "业务分布",
				name : 'businessDistribute'
			},{
				display : "初步商谈意向",
				name : 'tentativeTalk'
			},{
				display : "联系人姓名",
				name : 'linkmanName'
			},{
				display : "联系人职务",
				name : 'linkmanJob'
			},{
				display : "联系人电话",
				name : 'linkmanPhone'
			},{
				display : "联系人邮箱",
				name : 'linkmanMail'
			}],

		sortname : 'suppLevel ASC,id'
 	});
 });