var show_page = function(page) {	   $("#esmroleGrid").yxgrid("reload");};
$(function() {			$("#esmroleGrid").yxgrid({				      model : 'engineering_role_esmrole',
               	title : '项目角色(oa_esm_project_role)',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'roleName',
                  					display : '角色名称',
                  					sortable : true
                              }                    ,{
                    					name : 'projectCode',
                  					display : '项目编号',
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '项目名称',
                  					sortable : true
                              },{
                    					name : 'jobDescription',
                  					display : '工作说明',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人Id',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人Id',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
                  					sortable : true
                              }                    ,{
                    					name : 'parentName',
                  					display : '上级名称',
                  					sortable : true
                              }                                        ,{
                    					name : 'isLeaf',
                  					display : '是否叶子节点',
                  					sortable : true
                              },{
                    					name : 'activityName',
                  					display : '活动名称',
                  					sortable : true
                              },{
                    					name : 'activityId',
                  					display : '活动id',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
					display : "搜索字段",
					name : 'XXX'
				}
 		});
 });