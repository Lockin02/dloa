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
	showModalWin("?model=hr_personnel_personnel&action=toTabView&id=" + id
			+ "&userNo=" + userNo + "&userAccount=" + userAccount + "&skey="
			+ skey, 'newwindow1', 'resizable=yes,scrollbars=yes');
}
$(function() {
	// 表头按钮数组
	buttonsArr = [{
		name : 'view',
		text : "高级查询",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
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
        },{
			name : 'excelOutOtherSelect',
			text : "导出其他信息",
			icon : 'excel',
			action : function() {
				if($("#totalSize").val()<1){
					alert("没有可导出的记录");
				}else{
					document.getElementById("form3").submit();
				}
			}
        }];
	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '人员档案信息',
		action : 'pageJsonForRead',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton:false,
		bodyAlign:'center',
	    event:{'afterload':function(data,g){
	         $("#listSql").val(g.listSql);
          	 $("#totalSize").val(g.totalSize);
          	$("#otherListSql").val(g.listSql);
          	$("#otherTotalSize").val(g.totalSize);
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
  			width:60,
			process : function(v, row) {
				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
						+ row.id + "\",\"" + row.userNo + "\",\""
						+ row.userAccount + "\")' >" + v + "</a>";
			}
		}, {
			name : 'staffName',
			display : '姓名',
  			width:60,
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
						+ row.id + "\",\"" + row.userNo + "\",\""
						+ row.userAccount + "\")' >" + v + "</a>";
			}
		}, {
			name : 'sex',
			display : '性别',
			sortable : true,
			width : 60
		}, {
			name : 'companyType',
			display : '公司类型',
  			width:60,
			sortable : true
		}, {
			name : 'companyName',
			display : '公司',
  			width:60,
			sortable : true
		},{
			name : 'belongDeptName',
  			display : '所属部门',
  			width:80,
  			hide : true,
  			sortable : true
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
            name : 'deptNameF',
            display : '四级部门',
            width:80,
            sortable : true
        },{
			name : 'jobName',
			display : '职位',
  			width:80,
			sortable : true
		},{
			name : 'isNeedTutor',
			display : '导师状态',
			sortable : true,
			width :90,
			process : function(v, row) {
				if(v==1){
					return "不需要指定导师";
				}else{
					if(row.isTut==1){
						return "已指定导师";
					}else{
						return "未指定导师";
					}
				}
			}
		}, {
			name : 'employeesStateName',
			display : '员工状态',
			sortable : true,
			width : 60
		}, {
			name : 'personnelTypeName',
			display : '员工类型',
  			width:60,
			sortable : true
		}, {
			name : 'positionName',
			display : '岗位分类',
  			width:60,
			sortable : true
		}, {
			name : 'personnelClassName',
			display : '人员分类',
  			width:60,
			sortable : true
		}, {
			name : 'wageLevelName',
			display : '工资级别',
  			width:70,
			sortable : true
		}, {
			name : 'jobLevel',
			display : '职级',
  			width:60,
			sortable : true
		}],
		lockCol:['userNo','staffName'],//锁定的列名
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

		buttonsEx : buttonsArr,

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		comboEx:[{
			text:'导师状态',
			key:'personnelTutorState',
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

		// 扩展右键
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
							"?model=hr_personnel_personnel&action=toTabView&id="
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
		},{
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
		}, {
			display : "性别",
			name : 'sex'
		},{
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
		},{
            display : "四级部门",
            name : 'deptNameFSearch'
        },  {
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
		}, {
			display : "岗位分类",
			name : 'positionNameSearch'
		}, {
			display : "人员分类",
			name : 'personnelClassNameSearch'
		}, {
			display : "职级",
			name : 'jobLevelSearch'
		}]
	});
});