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
					+ "&userNo=" + userNo + "&userAccount=" + userAccount
					+ "&skey=" + skey, 'newwindow1',
			'resizable=yes,scrollbars=yes');
}
$(function() {
	var buttonsArr = [{
		name : 'view',
		text : "查询",
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
        }];

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '离职员工档案信息',
		param : {
			"employeesState":"YGZTLZ"
	}	,
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
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
  					width:60,
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
					name : 'staffName',
					display : '姓名',
  					width:60,
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
				}, {
                    name : 'belongDeptName',
                  	display : '所属部门',
  					width:80,
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
                    name : 'deptNameF',
                    display : '四级部门',
                    width:80,
                    sortable : true
                },
					{
					name : 'jobName',
					display : '职位',
  					width:80,
					sortable : true
				}, {
					name : 'quitDate',
					display : '离职时间',
					sortable : true
				}, {
					name : 'quitTypeCode',
					display : '离职类型',
  					width:60,
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
		buttonsEx : buttonsArr,

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
				}, {
						display : "直属部门",
						name : 'deptNameSearch'
					},{
						display : "二级部门",
						name : 'deptNameSSearch'
					},{
						display : "三级部门",
						name : 'deptNameTSearch'
					}, {
                        display : "四级部门",
                        name : 'deptNameFSearch'
                    },{
					display : "职位",
					name : 'jobNameSearch'
				}]
	});
});