var show_page = function(page) {
	$("#entryNoticeGrid").yxgrid("reload");
};

$(function() {
	//表头按钮数组
	buttonsArr = [];

	excelOutArr2 = {
		name : 'expport',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_entryNotice&action=toExport&docType=RKPURCHASE"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
		}
	};

	excelOutSelect = {
		name : 'excelOutAllArr',
		text : "自定义导出信息",
		icon : 'excel',
		action : function() {
			if ($("#totalSize").val() < 1) {
				alert("没有可导出的记录");
			} else {
				document.getElementById("form2").submit();
			}
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_education&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr2);
				buttonsArr.push(excelOutSelect);
			}
		}
	});


	$("#entryNoticeGrid").yxgrid({
		model : 'hr_recruitment_entryNotice',
		title : '入职名单',
		bodyAlign : 'center',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton : false,
		param : {
//			deptId : $("#deptId").val(),
			state : 1,
			isSaveN : 1
		},
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},

		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id="
					+ row.id + "\",1)'>" + v + "</a>";
			},
			width : 120
		},{
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code="
					+ v + "\",1)'>" + v + "</a>";
			},
			width : 90
		},{
			name : 'hrSourceType2Name',
			display : '简历来源小类',
			sortable : true
		},{
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width : 70
		},{
			name : 'userName',
			display : '姓名',
			sortable : true,
			width : 60
		},{
			name : 'stateC',
			display : '状态',
			width : 80
		},{
			name : 'assistManName',
			display : '入职协助人',
			sortable : true,
			width : 60
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width : 60,
			hide : true
		},{
			name : 'phone',
			display : '联系电话',
			sortable : true,
			hide : true
		},{
			name : 'email',
			display : '电子邮箱',
			sortable : true,
			hide : true
		},{
			name : 'deptName',
			display : '用人部门',
			sortable : true,
			width : 80
		},{
			name : 'workPlace',
			display : '工作地点',
			sortable : true,
			width : 80,
			process : function (v ,row) {
				return row.workProvince + ' - ' + row.workCity;
			}
		},{
			name : 'socialPlace',
			display : '社保购买地',
			sortable : true,
			width : 60
		},{
			name : 'hrJobName',
			display : '录用职位',
			sortable : true,
			width : 80
		},{
			name : 'hrIsManageJob',
			display : '是否管理岗',
			sortable : true,
			hide : true,
			hide : true
		},{
			name : 'useHireTypeName',
			display : '录用形式',
			sortable : true,
			width : 60
		},{
			name : 'useAreaName',
			display : '归属区域或支撑中心',
			sortable : true
		},{
			name : 'sysCompanyName',
			display : '归属公司',
			sortable : true,
			width : 60
		},{
			name : 'personLevel',
			display : '技术等级',
			sortable : true,
			width : 60
		},{
			name : 'probation',
			display : '试用期(月)',
			sortable : true,
			width : 60
		},{
			name : 'contractYear',
			display : '合同期限(年)',
			sortable : true,
			width : 60
		},{
			name : 'useSign',
			display : '签订《竞业限制协议》',
			sortable : true,
			width : 110
		},{
			name : 'entryRemark',
			display : '入职进度备注',
			sortable : true
		},{
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width : 80,
			hide : true
		},{
			name : 'applyCode',
			display : '职位申请编号',
			sortable : true,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id="
					+ row.applyId + "\")'>" + v + "</a>";
			},
			hide : true
		},{
			name : 'developPositionName',
			display : '研发职位',
			sortable : true,
			width : 60,
			hide : true
		},{
			name : 'useDemandEqu',
			display : '需求设备',
			sortable : true,
			hide : true
		}],

		lockCol:['formCode','userName','stateC'],//锁定的列名

		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_recruitment_entryNotice&action=toView&id="
							+ rowData[p.keyField]);
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		comboEx:[{
			text:'状态',
			key:'stateSearch',
			data:[{
				text:'待入职',
				value:'1'
			},{
				text:'已建账号',
				value:'2'
			},{
				text:'已建档案',
				value:'3'
			},{
				text:'已签合同',
				value:'4'
			}]
		}],

		buttonsEx : buttonsArr,

		// 扩展右键
		menusEx : [{
			name : 'resume',
			text : '查看关联简历',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.resumeId > 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId ,'1');
				}
			}
		},{
			name : 'jobApply',
			text : '查看关联职位申请',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.applyId > 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId ,'1');
				}
			}
		},{
			name : 'apply',
			text : '查看关联增员申请',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.sourceId > 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_apply&action=toView&id=' + row.sourceId ,'1');
				}
			}
		},{
			name : 'interview',
			text : '查看关联面试评估',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.parentId > 0) {
					//判断是否有权限
					var interviewLimits = $.ajax({
							type : 'POST',
							url : '?model=hr_recruitment_entryNotice&action=getLimits',
							data : {
								'limitName' : '查看关联面试评估权限'
							},
							async : false
						}).responseText;

					if (interviewLimits == 1) {
						return true;
					}
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_interview&action=toView&id=' + row.parentId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800','1');
				}
			}
		},{
			text : '开通OA账号',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.accountState == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=user&action=adduser"
						+ "&oId="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
				} else {
					alert("请选择一条记录信息");
				}
			}
		},{
			text : '编辑员工档案',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.staffFileState == 0 && row.accountState == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=hr_personnel_personnel&action=toAddByEntryNotice"
						+ "&entryId="
						+ row.id
						+ "&applyId="
						+ row.applyId
						+ "&resumeId="
						+ row.resumeId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
				} else {
					alert("请选择一条记录信息");
				}
			}
		},{
			text : '签定合同',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.contractState == 0 && row.accountState == 1&& row.staffFileState == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_contract_contract&action=toAddByExternal"
						+ "&entryId="
						+ row.id
						+ "&jobName="
						+ row.hrJobName
						+ "&jobId="
						+ row.hrJobId
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
				} else {
					alert("请选择一条记录信息");
				}
			}
		},{
			text : '指定导师',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.staffFileState == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toSetEntryTutor&entryId="
						+ row.id
						+ "&entryDate="
						+ row.entryDate
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '完成入职',
			icon : 'add',
			action : function(row, rows, grid) {
				if (window.confirm(("确定要完成入职吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_entryNotice&action=doneEntry",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('完成入职成功！');
								show_page();
							}
						}
					});
				}
			}
		},{
			text : '入职进度备注',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toEntryRemark&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '关联职位申请表',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.applyId == 0 || row.applyId == '') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toLinkApply&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text:'修改入职时间',
			icon:'edit',
			action:function(row ,rows ,grid){
				showThickboxWin("?model=hr_recruitment_entryNotice&action=changeEntryDate&id="
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=800");
			}
		},{
			text:'放弃入职',
			icon:'delete',
			action:function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toCancelEntry&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
				}
			}
		}],

		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "姓名",
			name : 'userName'
		}],

		sortname : 'entryDate',
		sortorder : 'DESC'
	});
});