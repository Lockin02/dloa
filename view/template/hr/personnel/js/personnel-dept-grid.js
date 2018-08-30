var show_page = function(page) {
	$("#deptGrid").yxgrid("reload");
};
$(function() {

			$("#deptGrid").yxgrid({
				model : 'hr_personnel_personnel',
               	title : '部门员工档案信息',
               	param:{
					companyName:$("#companyName").val(),
					deptIdS:$("#deptIdS").val(),
					deptIdT:$("#deptIdT").val()
               	},
				showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
               	isViewAction:false,
               	isOpButton:false,
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                					name : 'userNo',
                  					display : '员工编号',
                  					sortable : true
                              },{
                					name : 'staffName',
                  					display : '姓名',
                  					sortable : true
                              },{
                    					name : 'sex',
                  					display : '性别',
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'companyType',
                  					display : '公司类型',
                  					sortable : true
                              },{
                    					name : 'companyName',
                  					display : '公司',
                  					sortable : true
                              },{
                    					name : 'deptNameS',
                  					display : '二级部门',
                  					sortable : true
                              }                    ,{
                    					name : 'deptNameT',
                  					display : '三级部门',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : '职位',
                  					sortable : true
                              },{
                    					name : 'employeesStateName',
                  					display : '员工状态',
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'personnelTypeName',
                  					display : '员工类型',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : '岗位分类',
                  					sortable : true
                              },{
                    					name : 'personnelClassName',
                  					display : '人员分类',
                  					sortable : true
                              },{
                    					name : 'wageLevelName',
                  					display : '工资级别',
                  					sortable : true
                              },{
                    					name : 'jobLevel',
                  					display : '职级',
                  					sortable : true
                              }],
		// 高级搜索
//		advSearchOptions : {
//			modelName : 'personnel',
//			searchConfig : [{
//						name : '员工编号',
//						value : 'c.userNo'
//					}, {
//						name : '姓名',
//						value : 'c.userName'
//					},{
//						name : "公司",
//						value : 'companyName'
//					},{
//						name : "部门",
//						value : 'deptSearch'
//					},{
//						name : "职位",
//						value : 'jobName'
//					},{
//						name : "区域",
//						value : 'regionName'
//					},{
//						name : "员工状态",
//						value : 'employeesStateName'
//					},{
//						name : "员工类型",
//						value : 'personnelTypeName'
//					},{
//						name : "岗位分类",
//						value : 'positionName'
//					},{
//						name : "人员分类",
//						value : 'personnelClassName'
//					},{
//						name : "职级",
//						value : 'jobLevel'
//					}]
//		},

		searchitems : [{
						display : "员工编号",
						name : 'userNoSearch'
					},{
						display : "姓名",
						name : 'staffNameSearch'
					},{
						display : "公司",
						name : 'companyNameSearch'
					},{
						display : "部门",
						name : 'deptSearch'
					},{
						display : "职位",
						name : 'jobNameSearch'
					},{
						display : "区域",
						name : 'regionNameSearch'
					},{
						display : "员工类型",
						name : 'personnelTypeNameSearch'
					},{
						display : "岗位分类",
						name : 'positionNameSearch'
					},{
						display : "人员分类",
						name : 'personnelClassNameSearch'
					},{
						display : "职级",
						name : 'jobLevelSearch'
					}]
 		});
 });