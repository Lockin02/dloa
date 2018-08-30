var show_page = function(page) {	   $("#projectGrid").yxgrid("reload");};
$(function() {			$("#projectGrid").yxgrid({				      model : 'hr_recruitment_project',
               	title : '职位申请表-项目经历',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'language',
                  					display : '编程语言',
                  					sortable : true
                              },{
                    					name : 'system',
                  					display : '操作系统',
                  					sortable : true
                              },{
                    					name : 'dataBank',
                  					display : '数据库',
                  					sortable : true
                              },{
                    					name : 'newSkill',
                  					display : '目前业内新技术',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '开始时间',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '截止时间',
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '项目名称',
                  					sortable : true
                              },{
                    					name : 'projectSkill',
                  					display : '主要运用何种技术',
                  					sortable : true
                              },{
                    					name : 'projectRole',
                  					display : '在项目中的角色',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_recruitment_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}]
 		});
 });