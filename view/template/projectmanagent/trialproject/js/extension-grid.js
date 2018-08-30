var show_page = function(page) {	   $("#extensionGrid").yxgrid("reload");};
$(function() {			$("#extensionGrid").yxgrid({				      model : 'projectmanagent_trialproject_extension',
               	title : '延期申请',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'trialprojectCode',
                  					display : '试用项目编号',
                  					sortable : true
                              },{
                    					name : 'extensionDate',
                  					display : '延期日期',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人Id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人ID',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '审批状态',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '审批日期',
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