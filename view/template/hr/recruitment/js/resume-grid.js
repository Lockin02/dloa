var show_page = function(page) {
	$("#resumeGrid").yxgrid("reload");
};

$(function() {
	//表头按钮数组
	buttonsArr = [{
		name : 'advancedsearch',
		text : "高级搜索",
		icon : 'search',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_resume&action=search&gridName=resumeGrid"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
		}
	},{
		name : 'add',
		text : "新增",
		icon : 'add',
		action : function(row) {
			showModalWin("?model=hr_recruitment_resume&action=toAdd" ,"1");
		}
	},{
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_resume&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	}];

	$("#resumeGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '简历管理',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		bodyAlign:'center',
		customCode : 'resumeGrid',

		// 扩展右键菜单
		menusEx : [{
			text : '查看简历',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toViewTab&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '编辑简历',
			icon : 'edit',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toEdit&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '打印简历',
			icon : 'edit',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '添加面试评估',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.resumeType == 2) {
					return false;
				}
				return true;
			},
			action : function(row) {
				$.ajax({
					type : "POST",
					url : "?model=hr_recruitment_interview&action=isAdded",
					data : {
						resumeId:row.id
					},
					success : function(msg){
						if(msg == 0) { //判断是否有面试评估
							if (window.confirm(("该简历已有面试评估表,是否继续添加?"))) {
								showModalWin('?model=hr_recruitment_interview&action=toAddByResume&resumeId='
									+ row.id + "&skey=" + row['skey_'],'1');
							}
						} else {
							showModalWin('?model=hr_recruitment_interview&action=toAddByResume&resumeId='
								+ row.id + "&skey=" + row['skey_'],'1');
						}
					}
				});
			}
		},{
			text : '发送录用通知',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.resumeType == 2) {
					return false;
				}
				return true;
			},
			action : function(row) {
				$.ajax({
					type : "POST",
					url : "?model=hr_recruitment_resume&action=checkInvitation",
					data : {
						resumeId:row.id
					},
					success:function(msg){
						if(msg == 0) {//判断是否有面试评估
							if (window.confirm(("此人尚未走面试评估审批流，请确认是否要发起录用通知？"))) {
								showModalWin('?model=hr_recruitment_resume&action=toSendNotifi&resumeId='
									+ row.id + "&skey=" + row['skey_'],'1');
							}
						} else {
							showModalWin('?model=hr_recruitment_resume&action=toSendNotifi&resumeId='
								+ row.id + "&skey=" + row['skey_'],'1');
						}
					}
				});
			}
		},{
			text : '转为在职简历',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.resumeType != 1 && row.resumeType != 5) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定转为在职简历?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxTurnType",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '转为储备简历',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType != 3) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定转为储备简历?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxReservelist",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '加入黑名单',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.resumeType!=2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定加入黑名单?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxBlacklist",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '转为公司简历',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType!=0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定转为公司简历?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxCompanyResume",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '转为淘汰简历',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType!=4) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定转为淘汰简历?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxChangeResume",
						data : {
							id : row.id,
							resumeType : 4
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '转为离职简历',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.resumeType!=6) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定转为离职简历?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxChangeResume",
						data : {
							id : row.id,
							resumeType : 6
						},
						success : function(msg) {
							if (msg == 1) {
								alert('操作成功！');
								$("#resumeGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isInform == 1 || row.resumeType == 1) {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_resume&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							//msg 1.成功，2-关联简历库
							if (msg == 1) {
								alert('删除成功！');
								$("#resumeGrid").yxgrid("reload");
							}else if(msg == 2){
								alert("该简历已关联”其他简历库“，禁止删除！")
							}
						}
					});
				}
			}
		}],

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v, row) {
					return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
			}
		},{
			name : 'applicantName',
			display : '应聘者姓名',
			sortable : true,
			width : 60
		},{
			name : 'post',
			display : '应聘职位',
			sortable : true,
			width:'60',
			datacode : 'YPZW'
		},{
			name : 'reserveA',
			display : '应聘职位(小类)',
			sortable : true,
			width : 100
		},{
			name : 'phone',
			display : '联系电话',
			sortable : true
		},{
			name : 'email',
			display : '电子邮箱',
			sortable : true,
			width : 150
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width : 40
		},{
			name : 'education',
			display : '学历',
			sortable : true,
			datacode:'HRJYXL',
			width : 60
		},{
			name : 'sourceA',
			display : '简历来源(大类)',
			sortable : true,
			datacode : 'JLLY',
			width : 100
		},{
			name : 'sourceB',
			display : '简历来源(小类)',
			sortable : true,
			width : 100
		},{
			name : 'resumeType',
			display : '简历类型',
			sortable : true,
			process : function(v,row){
				if(v == "0") {
					return "公司简历";
				}else if(v == "1") {
					return "在职简历";
				}else if(v == "2") {
					return "黑名单";
				}else if(v == "3") {
					return "储备简历";
				}else if(v == "4") {
					return "淘汰简历";
				}else if(v == "5") {
					return "在职简历";
				}else if(v == "6") {
					return "离职简历";
				}
			}
		},{
			name : 'birthdate',
			display : '出生日期',
			sortable : true,
			width : 90
		},{
			name : 'marital',
			display : '婚姻状况',
			sortable : true,
			width : 60
		},{
			name : 'wishAdress',
			display : '期望工作地点',
			sortable : true,
			width : 100
		},{
			name : 'graduateDate',
			display : '毕业时间',
			sortable : true,
			width : 80
		},{
			name : 'workSeniority',
			display : '工作年限',
			sortable : true,
			width : 60
		},{
			name : 'computerGrade',
			display : '计算机水平',
			sortable : true,
			datacode : 'JSJSP',
			width : 100
		},{
			name : 'language',
			display : '语种',
			sortable : true,
			datacode : 'HRYZ',
			width : 40
		},{
			name : 'languageGrade',
			display : '外语水平',
			sortable : true,
			datacode : 'WYSP',
			width : 60
		},{
			name : 'college',
			display : '毕业院校',
			sortable : true,
			width : 150
		},{
			name : 'major',
			display : '毕业专业',
			sortable : true,
			width : 100
		},{
			name : 'wishSalary',
			display : '期望薪水',
			sortable : true,
			width : 60
		},{
			name : 'prevCompany',
			display : '上家公司名称',
			sortable : true,
			width : 150
		},{
			name : 'hillockDate',
			display : '到岗时间',
			sortable : true,
			width : 80
		},{
			name : 'specialty',
			display : '特长',
			sortable : true,
			width : 200
		},{
			name : 'selfAssessment',
			display : '简历内容',
			sortable : true,
			hide:true,
			width : 200
		},{
			name : 'remark',
			display : '备注',
			sortable : true,
			hide:true,
			width : 200
		}],

		lockCol:['resumeCode','applicantName'],//锁定的列名

		comboEx : [{
			text : '简历类型',
			key : 'resumeTypeArr',
			value :　'0',
			data : [{
				text : '公司简历',
				value : '0'
			},{
				text : '黑名单',
				value : '2'
			},{
				text : '储备简历',
				value : '3'
			},{
				text : '淘汰简历',
				value : '4'
			},{
				text : '在职简历',
				value : '1,5'
			},{
				text : '离职简历',
				value : '6'
			}]
		}],

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "简历编号",
			name : 'resumeCode'
		},{
			display : "应聘者名称",
			name : 'applicantName'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "出生日期",
			name : 'birthdate'
		},{
			display : "联系电话",
			name : 'phone'
		},{
			display : "电子邮箱",
			name : 'email'
		},{
			display : "婚姻状况",
			name : 'marital'
		},{
			display : "期望工作地点",
			name : 'wishAdress'
		},{
			display : "应聘职位(大类)",
			name : 'post'
		},{
			display : "应聘职位(小类)",
			name : 'reserveA'
		},{
			display : "毕业时间",
			name : 'graduateDate'
		},{
			display : "工作年限",
			name : 'workSeniority'
		},{
			display : "计算机水平",
			name : 'computerGrade'
		},{
			display : "外语水平",
			name : 'languageGrade'
		},{
			display : "毕业院校",
			name : 'college'
		},{
			display : "毕业专业",
			name : 'major'
		},{
			display : "期望薪水",
			name : 'wishSalary'
		},{
			display : "上家公司名称",
			name : 'prevCompany'
		},{
			display : "到岗时间",
			name : 'hillockDate'
		},{
			display : "特长",
			name : 'specialty'
		},{
			display : "简历来源(大类)",
			name : 'sourceA'
		},{
			display : "简历来源(小类)",
			name : 'sourceB'
		},{
			display : "简历内容",
			name : 'selfAssessment'
		},{
			display : "备注",
			name : 'remark'
		}]
	});
});