var show_page = function(page) {	   $("#replacedinfoGrid").yxgrid("reload");};
$(function() {			$("#replacedinfoGrid").yxgrid({				      model : 'engineering_resources_replacedinfo',
               	title : '设备管理-可替换设备管理-可替换物料',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'deviceName',
                  					display : '设备名称',
                  					sortable : true
                              }                    ,{
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
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=engineering_resources_NULL&action=pageItemJson',
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