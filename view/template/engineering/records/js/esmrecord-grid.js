var show_page = function() {
	$("#esmrecordGrid").yxgrid("reload");
};

$(function() {
	var buttonsArr = [];

	// 数据操作
	$.ajax({
		type: 'POST',
		url: '?model=engineering_project_esmproject&action=getLimits',
		data: {
			limitName: '数据表-操作'
		},
		async: false,
		success: function(data) {
			if (data == 1) {
				buttonsArr.push({
					name: 'edit',
					text: "数据操作",
					icon: 'copy',
					items: [{
						text: '数据更新',
						icon: 'copy',
						action: function() {
							if (confirm('确认更新数据吗？')) {
								showThickboxWin('?model=engineering_records_esmrecord&action=updateRecord'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
							}
						}
					}, {
						text: "保存版本",
						icon: 'save',
						action: function() {
							showThickboxWin('?model=engineering_records_esmrecord&action=toSetUsing'
							+ '&nowVersion=' + $("#nowVersion").val()
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=280&width=550');
						}
					}]
				});
			}
		}
	});

	// 数据导出
	$.ajax({
		type: 'POST',
		url: '?model=engineering_project_esmproject&action=getLimits',
		data: {
			limitName: '数据表-导出'
		},
		async: false,
		success: function(data) {
			if (data == 1) {
				buttonsArr.push({
					name: 'export',
					text: "列表数据导出",
					icon: 'excel',
					action: function() {
						var gridObject = $("#esmrecordGrid");
						var searchConditionKey = "";
						var searchConditionVal = "";
						for (var t in gridObject.data('yxgrid').options.searchParam) {
							if (t != "") {
								searchConditionKey += t;
								searchConditionVal += gridObject.data('yxgrid').options.searchParam[t];
							}
						}
						var searchSql = gridObject.data('yxgrid').getAdvSql();
						var searchArr = [];
						searchArr[0] = searchSql;
						searchArr[1] = searchConditionKey;
						searchArr[2] = searchConditionVal;
						window.open("?model=engineering_records_esmrecord&action=exportExcel"
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal=" + searchConditionVal
							+ "&version="
							+ $("#nowVersion").val()
						);
					}
				});
			}
		}
	});

	buttonsArr.push({
		text: "重置",
		icon: 'delete',
		action: function() {
			history.go(0)
		}
	});

	$("#esmrecordGrid").yxgrid({
		model: 'engineering_records_esmrecord',
		title: '项目汇总表-数据表',
		param: {version: $("#nowVersion").val()},
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		isEditAction: false,
		showcheckbox: false,
		customCode: 'esmrecordGrid',
		isOpButton: false,
		leftLayout: true,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectName',
			display: '项目名称',
			sortable: true,
			width: 140,
			process: function(v, row) {
				return (row.contractId == "0" || row.contractId == "") && row.contractType != 'GCXMYD-04' ?
				"<span style='color:blue' title='未关联合同号的项目'>" + v + "</span>" : v;
			}
		}, {
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width: 120,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='toView(" + row.projectId + ")'>" + v + "</a>";
			}
		}, {
			name: 'statusName',
			display: '项目状态',
			sortable: true,
			width: 60
		}, {
			name: 'productLineName',
			display: '执行区域',
			sortable: true,
			width: 60
		}, {
			name: 'officeName',
			display: '区域',
			sortable: true,
			width: 70
		}, {
			name: 'province',
			display: '省份',
			sortable: true,
			width: 70
		}, {
			name: 'exgross',
			display: '毛利率',
			process: function(v, row) {
				if (row.contractType == 'GCXMYD-04') {
					return '--';
				} else {
					if (v == '') return '暂无';
					if (v * 1 >= 0) {
						return v + " %";
					} else {
						return "<span class='red'>" + v + " %</span>";
					}
				}
			},
			width: 60
		}, {
			name: 'projectProcess',
			display: '工程进度',
			process: function(v) {
				return formatProgress(v);
			},
			width: 60
		}, {
			name: 'feeAllProcess',
			display: '费用进度',
			process: function(v) {
				return formatProgress(v);
			},
			width: 60
		}, {
			name: 'feeAll',
			display: '总决算',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 70
		}, {
			name: 'budgetAll',
			display: '总预算',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'planBeginDate',
			display: '预计开始',
			sortable: true,
			width: 80
		}, {
			name: 'planEndDate',
			display: '预计结束',
			sortable: true,
			width: 80
		}, {
			name: 'country',
			display: '国家',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'city',
			display: '城市',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'attributeName',
			display: '项目属性',
			width: 70,
			process: function(v, row) {
				switch (row.attribute) {
					case 'GCXMSS-01' :
						return "<span class='red'>" + v + "</span>";
					case 'GCXMSS-02' :
						return "<span class='blue'>" + v + "</span>";
					case 'GCXMSS-03' :
						return "<span class='green'>" + v + "</span>";
					default :
						return v;
				}
			},
			hide: true
		}, {
			name: 'budgetField',
			display: '现场预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetPerson',
			display: '人力预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetEqu',
			display: '设备预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetOutsourcing',
			display: '外包预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetOther',
			display: '其他预算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeField',
			display: '现场决算',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feePerson',
			display: '人力决算',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeEqu',
			display: '设备决算',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeOutsourcing',
			display: '外包决算',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeOther',
			display: '其他决算',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeFieldProcess',
			display: '现场费用进度',
			process: function(v) {
				return formatProgress(v);
			},
			width: 110,
			hide: true
		}, {
			name: 'contractTypeName',
			display: '源单类型',
			sortable: true,
			hide: true
		}, {
			name: 'contractId',
			display: '鼎利合同id',
			sortable: true,
			hide: true
		}, {
			name: 'contractCode',
			display: '鼎利合同编号(源单编号)',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'rObjCode',
			display: '业务编号',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'contractMoney',
			display: '合同金额',
			sortable: true,
			process: function(v, row) {
				if (row.contractType == 'GCXMYD-04') {
					return '--';
				} else {
					return moneyFormat2(v);
				}
			},
			width: 80,
			hide: true
		}, {
			name: 'customerName',
			display: '客户名称',
			sortable: true,
			hide: true
		}, {
			name: 'depName',
			display: '所属部门',
			sortable: true,
			hide: true
		}, {
			name: 'actBeginDate',
			display: '实际开始',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'actEndDate',
			display: '实际完成',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'managerName',
			display: '项目经理',
			sortable: true,
			hide: true
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'ExaDT',
			display: '审批日期',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'categoryName',
			display: '项目类别',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'updateTime',
			display: '最近更新',
			sortable: true,
			width: 120,
			hide: true
		}],
		lockCol: ['projectName', 'projectCode'],//锁定的列名
		buttonsEx: buttonsArr,
		searchitems: [{
			display: '办事处',
			name: 'officeName'
		}, {
			display: '项目编号',
			name: 'projectCodeSearch'
		}, {
			display: '项目名称',
			name: 'projectName'
		}, {
			display: '项目经理',
			name: 'managerName'
		}, {
			display: '业务编号',
			name: 'rObjCodeSearch'
		}, {
			display: '鼎利合同号',
			name: 'contractCodeSearch'
		}],
		// 审批状态数据过滤
		comboEx: [{
			text: "项目属性",
			key: 'attribute',
			datacode: 'GCXMSS'
		}, {
			text: "项目状态",
			key: 'status',
			datacode: 'GCXMZT'
		}]
	});

	var M = new Date();
	var Year = M.getFullYear();
	var Year2 = Year - 2;
	var Year1 = Year - 1;
	$("#view").append("<br/><div id='versionNum' class='red'>最新版本号: V<span>" + $("#maxVersion").val() + "</span></div>").
		append("<tr><td class='form_text_left'>版本年份</td>" +
		"<td class='form_view_right'>" +
		"<select class='selectauto' id='storeYear' style='width: 100%;' onchange='createVersionNum()'>" +
		"<option value='0'>" + "...选择..." + "</option>" +
		"<option value='" + Year + "'>" + Year + "年</option>" +
		"<option value='" + Year1 + "'>" + Year1 + "年</option>" +
		"<option value='" + Year2 + "'>" + Year2 + "年</option>" +
		"</select></td></tr>").
		append("<tr><td class='form_text_left'>版本月份</td>" +
		"<td class='form_view_right'>" +
		"<select class='selectauto' id='storeMonth' style='width: 100%;' onchange='createVersionNum()'>" +
		"<option value='0'>" + "...选择..." + "</option>" +
		"<option value='1'>1月</option><option value='2'>2月</option><option value='3'>3月</option><option value='4'>4月</option>" +
		"<option value='5'>5月</option><option value='6'>6月</option><option value='7'>7月</option><option value='8'>8月</option>" +
		"<option value='9'>9月</option><option value='10'>10月</option><option value='11'>11月</option><option value='12'>12月</option>" +
		"</select></td></tr>");
});

//构建查看版本号
function createVersionNum() {
	var storeYear = $("#storeYear").val();
	var storeMonth = $("#storeMonth").val();

	$.ajax({
		type: "POST",
		url: "?model=engineering_records_esmrecord&action=getVersionArr",
		data: {storeYear: storeYear, storeMonth: storeMonth},
		async: false,
		success: function(data) {
			$("#view").append("<div id='verSelect'></div>");
			if (data != 0) {
				$("#verSelect").html("<tr><td class='form_text_left'>版本号</td>" +
				"<td class='form_view_right'>" +
				"<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
				data +
				"</select></td></tr>");
			} else {
				$("#verSelect").html("<tr><td class='form_text_left'>版本号</td>" +
				"<td class='form_view_right'>" +
				"<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
				"<option>暂无数据</option>" +
				"</select></td></tr>");
			}
		}
	});
}

//重置查询版本数据
function setVersion() {
	var version = $("#version").val();
	if (version != '0') {
		$("#versionNum").html("<div id='versionNum' class='red'>最新版本号: V<span>" + $("#maxVersion").val() +
		"</span></div>" + "<div id='versionNum' class='blue'>当前版本号: V<span>" + version + "</span></div>");
	}

	$("#nowVersion").val(version);
	var listGrid = $("#esmrecordGrid").data('yxgrid');
	listGrid.options.extParam['version'] = version;
	listGrid.reload();
}

// 项目查看
function toView(projectId) {
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=md5RowAjax",
		data: {id: projectId},
		async: false,
		success: function(data) {
			showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId + '&skey=' + data);
		}
	});
}

//用于列表进度显示
function formatProgress(value) {
	if (value) {
		return '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
			+ '<div style="width:'
			+ value
			+ '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
			+ value + '%' + '</div>'
			+ '</div>';
	} else {
		return '';
	}
}