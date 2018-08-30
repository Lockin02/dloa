var show_page = function(page) {	   $("#planfeedbackGrid").yxgrid("reload");};
$(function() {			$("#planfeedbackGrid").yxgrid({				      model : 'produce_plan_planfeedback',
               	title : '生产计划进度反馈',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'feedbackNum',
                  					display : '反馈次数',
                  					sortable : true
                              },{
                    					name : 'feedbackDate',
                  					display : '反馈日期',
                  					sortable : true
                              },{
                    					name : 'feedbackName',
                  					display : '反馈者',
                  					sortable : true
                              },{
                    					name : 'feedbackId',
                  					display : '反馈者Id',
                  					sortable : true
                              },{
                    					name : 'process',
                  					display : '工序',
                  					sortable : true
                              },{
                    					name : 'processName',
                  					display : '项目名称',
                  					sortable : true
                              },{
                    					name : 'processTime',
                  					display : '工序时间（秒）',
                  					sortable : true
                              },{
                    					name : 'recipient',
                  					display : '接收人',
                  					sortable : true
                              },{
                    					name : 'recipientId',
                  					display : '接收人ID',
                  					sortable : true
                              },{
                    					name : 'recipientNum',
                  					display : '接收数量',
                  					sortable : true
                              },{
                    					name : 'recipientTime',
                  					display : '接收时间',
                  					sortable : true
                              },{
                    					name : 'finishTime',
                  					display : '完成时间',
                  					sortable : true
                              },{
                    					name : 'qualifiedNum',
                  					display : '合格数量',
                  					sortable : true
                              },{
                    					name : 'unqualifiedNum',
                  					display : '不合格数量',
                  					sortable : true
                              },{
                    					name : 'productBatch',
                  					display : '物料批次号',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_plan_NULL&action=pageItemJson',
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