var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};
// 查看员工档案
function viewPersonnel(id, userNo, userAccount) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=hr_personnel_personnel&action=md5RowAjax",
				data : {
					"id" : id
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin(
			"?model=hr_personnel_personnel&action=toDeptAssistantTabView&id="
					+ id + "&userNo=" + userNo + "&userAccount=" + userAccount
					+ "&skey=" + skey, 'newwindow1',
			'resizable=yes,scrollbars=yes');
}
// 更多按钮调用的方法，查看更多员工信息
function viewMore(id, userNo, userAccount) {
	var skey = "";
	$.ajax({
				type : "POST",
				url : "?model=hr_personnel_personnel&action=md5RowAjax",
				data : {
					"id" : id
				},
				async : false,
				success : function(data) {
					skey = data;
				}
			});
	showModalWin("?model=hr_personnel_personnel&action=toViewMoreTabView&id="
					+ id + "&userNo=" + userNo + "&userAccount=" + userAccount
					+ "&skey=" + skey, 'newwindow1',
			'resizable=yes,scrollbars=yes');
}
$(function() {
	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		action : 'pageJsonForRead',
		title : '部门助理列表',
		showcheckbox : false,
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		bodyAlign:'center',
      	event:{'afterload':function(data,g){
      		$("#listSql").val(g.listSql);
      		$("#totalSize").val(g.totalSize);
      	}},
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '员工编号',
					sortable : true,
					width : 60,
					process : function(v, row) {
						return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
								+ row.id
								+ "\",\""
								+ row.userNo
								+ "\",\""
								+ row.userAccount + "\")' >" + v + "</a>";
					}
				}, {
					name : 'staffName',
					display : '姓名',
					width : 60,
					sortable : true,
					process : function(v, row) {
						return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
								+ row.id
								+ "\",\""
								+ row.userNo
								+ "\",\""
								+ row.userAccount + "\")' >" + v + "</a>";
					}
				}, {
					name : 'sex',
					display : '性别',
					sortable : true,
					width : 40
				}, {
					name : 'companyName',
					display : '公司',
					width : 60,
					sortable : true
				}, {
					name : 'belongDeptName',
					display : '所属部门',
					width : 80,
					hide : true
				},
              	{
    					name : 'deptName',
  					display : '直属部门',
  					width:80,
  					sortable : true
              },
              	{
    					name : 'deptNameS',
  					display : '二级部门',
  					width:80,
  					sortable : true
              },{
    					name : 'deptNameT',
  					display : '三级部门',
  					width:80,
  					sortable : true
              },{
					name : 'jobName',
					display : '职位',
					width : 80,
					sortable : true
				}, {
					name : 'isNeedTutor',
					display : '导师状态',
					sortable : true,
					width : 90,
					process : function(v, row) {
						if (v == 1) {
							return "不需要指定导师";
						} else {
							if (row.isTut == 1) {
								return "已指定导师";
							} else {
								return "未指定导师";
							}
						}
					}
				}, {
					name : 'regionName',
					display : '在职区域',
					sortable : true,
					width : 60
				}, {
					name : 'employeesStateName',
					display : '员工状态',
					sortable : true,
					width : 60
				}, {
					name : 'personnelTypeName',
					display : '员工类型',
					sortable : true,
					width : 60
				}, {
					name : 'entryDate',
					display : '入职日期',
					sortable : true,
					width : 80
				}, {
					name : 'becomeDate',
					display : '转正日期',
					sortable : true,
					width : 80
				}, {
					name : 'entryPlace',
					display : '入职地点',
					sortable : true,
					width : 60
				}, {
					name : 'quitDate',
					display : '离职日期',
					sortable : true
				}, {
					name : 'more',
					display : '操作',
					width : 60,
					process : function(row) {
						return "<a href='#' title='点击查看更多信息' onclick='viewMore(\""
								+ row.id
								+ "\",\""
								+ row.userNo
								+ "\",\""
								+ row.userAccount + "\")' >" + "更多</a>";
					},
					sortable : true
				}],
		// 高级搜索
		// advSearchOptions : {
		// modelName : 'personnel',
		// searchConfig : [{
		// name : '员工编号',
		// value : 'c.userNo'
		// }, {
		// name : '姓名',
		// value : 'c.userName'
		// },{
		// name : "公司",
		// value : 'companyName'
		// },{
		// name : "部门",
		// value : 'deptSearch'
		// },{
		// name : "职位",
		// value : 'jobName'
		// },{
		// name : "区域",
		// value : 'regionName'
		// },{
		// name : "员工状态",
		// value : 'employeesStateName'
		// },{
		// name : "员工类型",
		// value : 'personnelTypeName'
		// },{
		// name : "岗位分类",
		// value : 'positionName'
		// },{
		// name : "人员分类",
		// value : 'personnelClassName'
		// },{
		// name : "职级",
		// value : 'jobLevel'
		// }]
		// },
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		comboEx:[{
			text:'导师状态',
			key:'tutorState',
			data:[{
			   text:'不需要指定导师',
			   value:'1'
			},{
			   text:'未指定导师',
			   value:'2'
			},{
			   text:'已指定导师',
			   value:'3'
			}]
		}],

		buttonsEx : [
			        {
						name : 'view',
						text : "高级查询",
						icon : 'view',
						action : function() {
							showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
						}
					 },
			        {
						name : 'excelOutAllArr',
						text : "导出档案信息",
						icon : 'excel',
						action : function() {
							if($("#totalSize").val()<1){
								alert("没有可导出的记录");
							}else{
								document.getElementById("form2").submit();
							}
						}
			        }
			    ],

		// 扩展右键
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
							"?model=hr_personnel_personnel&action=toDeptAssistantTabView&id="
									+ row.id + "&skey=" + row['skey_']
									+ "&userNo=" + row.userNo + "&userAccount="
									+ row.userAccount, 'newwindow1',
							'resizable=yes,scrollbars=yes');
				}
			}
		}, {
			text : '指定导师',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isNeedTutor != 1 && row.isTut == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toSetTutor&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&userNo="
							+ row.userNo
							+ "&userAccount="
							+ row.userAccount
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		}, {
			text : '不指定导师',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isNeedTutor != 1 && row.isTut == '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toUnsetTutor&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&userNo="
							+ row.userNo
							+ "&userAccount="
							+ row.userAccount
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		searchitems : [{
					display : "员工编号",
					name : 'userNoSearch'
				}, {
					display : "姓名",
					name : 'staffNameSearch'
				},{
					display : "性别",
					name : 'sex'
				}, {
					display : "公司",
					name : 'companyNameSearch'
				},{
						display : "直属部门",
						name : 'deptNameSearch'
					},{
						display : "二级部门",
						name : 'deptNameSSearch'
					},{
						display : "三级部门",
						name : 'deptNameTSearch'
					}, {
					display : "职位",
					name : 'jobNameSearch'
				}, {
					display : "区域",
					name : 'regionNameSearch'
				}, {
					display : "员工状态",
					name : 'employeesStateNameSearch'
				}, {
					display : "员工类型",
					name : 'personnelTypeNameSearch'
				}]
	});
});