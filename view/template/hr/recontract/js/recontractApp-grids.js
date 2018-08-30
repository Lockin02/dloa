var show_page = function(page) {
	$("#recontractGrid").yxgrid("reload");
};
$(function() {
	// 表头按钮数组
	buttonsArr = [];

	// 表头按钮数组
	excelOutArr1 = {
		name : 'exportIn',
		text : "操作",
		icon : 'edit',
		action : function(row,rowdata,ids) {
			$.ajax( {
				type : 'POST',
				url : '?model=hr_recontract_recontract&action=option',
				data : {
					'limitName' : ids
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(excelOutArr);
					}
				}
			});
		}
	},excelOutArr2 = {
		name : 'exportIns',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recontract_recontract&action=toExcelIn"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	buttonsArr.push(excelOutArr1);
	buttonsArr.push(excelOutArr2);
	
	$.ajax( {
		type : 'POST',
		url : '?model=hr_recontract_recontract&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});
	$("#recontractGrid")
			.yxsubgrid(
					{
						model : 'hr_recontract_recontractapproval',
						action:'pageJsonAppList',
						title : '合同信息',
						//isAddAction : true,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						// 列信息
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'userName',
									display : '员工姓名',
									sortable : true
								},
								{
									name : 'userNo',
									display : '员工编号',
									sortable : true
								},
								{
									name : 'conNo',
									display : '合同编号',
									sortable : true,
									process : function(v, row) {
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_recontract_recontract&action=toView&id="
												+ row.id
												+ '&skey='
												+ row.skey_
												+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
												+ v + "</a>";
									}
								}, {
									name : 'conName',
									display : '合同名称',
									sortable : true
								}, {
									name : 'conTypeName',
									display : '合同类型',
									sortable : true
								}, {
									name : 'conStateName',
									display : '合同状态',
									sortable : true
								}, {
									name : 'beginDate',
									display : '开始时间',
									sortable : true
								}, {
									name : 'closeDate',
									display : '结束时间',
									sortable : true
								}, {
									name : 'conNumName',
									display : '合同次数',
									sortable : true
								}, {
									name : 'conContent',
									display : '合同内容',
									sortable : true
								} ],
						buttonsEx : buttonsArr,
						
						// 主从表格设置
						subGridOptions : {
							url : '?model=hr_recontract_recontractApproval&action=pageJson',// 获取从表数据url
							// 传递到后台的参数设置数组
							param : [ {
								paramId : 'recontractId',// 传递给后台的参数名称
								colId : 'id'// 获取主表行数据的列名称
							} ],

							// 显示的列
							colModel : [ {
								name : 'createName',
								display : '审批人'
							},{
								name : 'isFlagName',
								display : '审批结果',
								
							}, {
								name : 'conNumName',
								display : '用工方式'
							}, {
								name : 'beginDate',
								display : '合同开始日期',
								
							}, {
								name : 'closeDate',
								display : '合同结束日期',
								
							}, {
								name : 'conContent',
								display : '审批意见',
								

							} ]
						},
						// 扩展右键菜单
						menusEx : [{
							text : '添加仲裁',
							icon : 'add',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									if(row.ExaStatus == '待提交' || row.ExaStatus == '部门审批'){
										showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&skey=' + row.skey_ +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
									}else{
										showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&skey=' + row.skey_ +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
									}
								} else {
									alert("请选中一条数据");
								}
							}
						},{
							text : '查看明细',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									if(row.ExaStatus == '待提交' || row.ExaStatus == '部门审批'){
										showThickboxWin("?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ );
									}else{
										showThickboxWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
									}
								} else {
									alert("请选中一条数据");
								}
							}
						},{
							text : '修改',
							icon : 'edit',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUpdateInfo&id="
										+ row.id
										+ "&skey=" + row.skey_
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
								} else {
									alert("请选中一条数据");
								}
							}
						},{
							text : '提交审批',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									showThickboxWin("?model=hr_recontract_recontract&action=toApprove&id=" + row.id + '&skey=' + row.skey_ +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');									
								} else {
									alert("请选中一条数据");
								}
							}
						}],
						/**
						 * 快速搜索
						 */
						searchitems : [ {
							display : '员工姓名',
							name : 'userName'
						}, {
							display : '员工编号',
							name : 'userNo'
						}, {
							display : '合同编号',
							name : 'conNo'
						}, {
							display : '合同名称',
							name : 'conName'
						}, {
							display : '合同类型',
							name : 'conTypeName'
						}, {
							display : '合同状态',
							name : 'conStateNames'
						} ],
						// 审批状态数据过滤
						comboEx : [ {
							text : '合同类型',
							key : 'statusTYPE',
							data : [ {
								text : '实习协议',
								value : '1'
							}, {
								text : '培训协议 ',
								value : '2'
							}, {
								text : '保密协议 ',
								value : '3'
							}, {
								text : '竞业协议 ',
								value : '4'
							}, {
								text : '劳动合同 ',
								value : '5'
							} ]
						}, {
							text : '报销单状态',
							key : 'status',
							data : [ {
								text : '未提交',
								value : '0'
							}, {
								text : '审批中',
								value : '1'
							}, {
								text : '执行中',
								value : '2'
							}, {
								text : '付款',
								value : '3'
							}, {
								text : '已关闭',
								value : '4'
							} ]
						}, {
							text : '审批状态',
							key : 'ExaStatus',
							value : '完成',
							data : [ {
								text : '待提交',
								value : '待提交'
							}, {
								text : '审批中',
								value : '审批中'
							}, {
								text : '打回',
								value : '打回'
							}, {
								text : '完成',
								value : '完成'
							} ]
						} ]

					});
});