var show_page = function() {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {
	$("#esmprojectGrid").yxgrid({
		model: 'engineering_project_esmproject',
		action: 'myProjectListPageJson',
		title: '我的工程项目',
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		showcheckbox: false,
		isOpButton: false,
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
				if (row.isManager == "1") {
					return "<span style='color:blue' title='我负责的项目'>" + v + "</span>";
				} else {
					return v;
				}
			}
		}, {
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width: 120,
			process: function(v, row) {
				if (row.status == 'GCXMZT01' && row.ExaStatus == '部门审批') {
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				} else {
					switch (row.status) {
						case 'GCXMZT01' :
							return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=editTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						case 'GCXMZT02' :
							return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=manageTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						case 'GCXMZT03' :
						case 'GCXMZT04' :
						case 'GCXMZT05' :
                        case 'GCXMZT00' :
                            return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						default :
							return v;
					}
				}
			}
		}, {
			name: 'newProLineName',
			display: '执行区域',
			sortable: true,
			width: 80
		}, {
			name: 'officeId',
			display: '区域ID',
			sortable: true,
			hide: true
		}, {
			name: 'officeName',
			display: '区域',
			width: 70,
			sortable: true
		}, {
			name: 'country',
			display: '国家',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'province',
			display: '省份',
			sortable: true,
			width: 70
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
			}
		}, {
			name: 'categoryName',
			display: '项目类别',
			sortable: true,
			width: 50
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
			name: 'customerId',
			display: '客户id',
			sortable: true,
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
			name: 'planBeginDate',
			display: '预计启动日期',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'planEndDate',
			display: '预计结束日期',
			sortable: true,
			width: 80
		}, {
			name: 'actBeginDate',
			display: '实际开始时间',
			sortable: true,
			width: 80
		}, {
			name: 'actEndDate',
			display: '实际完成时间',
			sortable: true,
			width: 80
		}, {
			name: 'projectProcess',
			display: '工程进度',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 70
		}, {
			name: 'statusName',
			display: '项目状态',
			sortable: true,
			width: 70
		}, {
			name: 'ExaStatus',
			display: '审批状态',
			sortable: true,
			width: 70
		}, {
			name: 'ExaDT',
			display: '审批日期',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'updateTime',
			display: '最近更新',
			sortable: true,
			width: 120
		}],
		lockCol: ['projectName', 'projectCode'],//锁定的列名
		toEditConfig: {
			showMenuFn: function(row) {
				return (row.ExaStatus == "待提交" || row.ExaStatus == "打回");
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showModalWin("?model=engineering_project_esmproject&action=editTab&id=" + row.id + "&skey=" + row.skey_, 1, row.id);
			}
		},
        buttonsEx : [{
            name : 'import',
            text : '<a style="color: red" href="#" title="项目经理手册V2.1" taget="_blank" id="fileId" onclick="window.open(\'upfile/项目经理手册V2.1.pdf\')">项目经理手册V2.1</a>',
            icon : 'view',
            action : function(row) {

            }
        }],
		// 扩展右键菜单
		menusEx: [{
			text: '查看项目',
			icon: 'view',
			showMenuFn: function(row) {
				return row.status == 'GCXMZT00' || row.status == 'GCXMZT03' ||
                    row.status == 'GCXMZT05' || (row.status == 'GCXMZT01' && row.ExaStatus == '部门审批');
			},
			action: function(row) {
				showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
				+ row.id
				+ "&skey=" + row.skey_, 1, row.id);
			}
		}, {
			text: '管理项目',
			icon: 'view',
			showMenuFn: function(row) {
				return row.status == 'GCXMZT02' || row.status == 'GCXMZT04';
			},
			action: function(row) {
				showModalWin("?model=engineering_project_esmproject&action=manageTab&id=" + row.id + "&skey=" + row.skey_, 1, row.id);
			}
		}, {
			text: '提交审批',
			icon: 'add',
			showMenuFn: function(row) {
				return row.ExaStatus == "待提交" || row.ExaStatus == "打回";
			},
			action: function(row) {
				if (row) {
					if (row.outsourcing == "") {
						alert('请补齐项目概况中的必填信息后再提交申请');
						return false;
					}
					if (row.budgetAll * 1 == 0) {
						alert('项目总预算不能为0，请先填写项目预算');
						return false;
					}
					$.ajax({
						type: "POST",
						url: "?model=engineering_project_esmproject&action=submitCheck",
						data: {id: row.id},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.pass == "1") {
								showThickboxWin('controller/engineering/project/ewf_index.php?actTo=ewfSelect&billId='
								+ row.id + "&billArea=" + data.rangeId
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							} else {
								alert(data.msg);
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
//		}, {
//            text: '提交完工审批',
//            icon: 'add',
//            showMenuFn: function(row) {
//                return row.ExaStatus == "待提交" || row.ExaStatus == "打回";
//            },
//            action: function(row) {
//                if (row) {
//                    if (row.outsourcing == "") {
//                        alert('请补齐项目概况中的必填信息后再提交申请');
//                    } else {
//                        $.ajax({
//                            type: "POST",
//                            url: "?model=engineering_project_esmproject&action=getRangeId",
//                            data: {projectId: row.id},
//                            async: false,
//                            success: function(data) {
//                                if (data == "") {
//                                    alert('没有匹配到项目所在区域，请补充相关信息后再提交');
//                                } else {
//                                    showThickboxWin('controller/engineering/project/ewf_index_completed.php?actTo=ewfSelect&billId='
//                                        + row.id + "&billArea=" + data
//                                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//                                }
//                            }
//                        });
//                    }
//                } else {
//                    alert("请选中一条数据");
//                }
//            }
        }, {
			text: '审批情况',
			icon: 'view',
			showMenuFn: function(row) {
				return row.ExaStatus != "待提交";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_esm_project&pid="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		}, {
			text: '暂停项目',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.status == "GCXMZT02";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("?model=engineering_project_esmproject&action=toStop&id="
					+ row.id + "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			text: '取消暂停',
			icon: 'add',
			showMenuFn: function(row) {
				return row.status == "GCXMZT05";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("?model=engineering_project_esmproject&action=toCancelStop&id="
					+ row.id + "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			text: '完成项目',
			icon: 'edit',
			showMenuFn: function(row) {
				return row.status == "GCXMZT02";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("?model=engineering_project_esmproject&action=toFinish&id="
					+ row.id + "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			text: '关闭项目',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.status == "GCXMZT04" || row.status == "GCXMZT00" || row.status == "GCXMZT01";
			},
			action: function(row) {
				if (row) {
					showOpenWin("?model=engineering_close_esmclose&action=toClose&projectId="
					+ row.id + "&skey=" + row.skey_);
				}
			}
		}],
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
		}, {
			display: '临时合同号',
			name: 'contractTempCodeSearch'
		}],
		// 默认搜索字段名
		sortname: "c.updateTime",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder: "DESC",
		// 审批状态数据过滤
		comboEx: [{
			text: "审批状态",
			key: 'ExaStatus',
			type: 'workFlow'
		}, {
			text: "项目状态",
			key: 'status',
			datacode: 'GCXMZT'
		}]
	});
});