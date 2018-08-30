var show_page = function(page) {	   $("#exsummaryGrid").yxgrid("reload");};
$(function() {			$("#exsummaryGrid").yxgrid({				      model : 'finance_expense_exsummary',
               	title : '报销汇总主表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'BillNo',
                  					display : '单号',
                  					sortable : true
                              },{
                    					name : 'InputMan',
                  					display : '申请人',
                  					sortable : true
                              },{
                    					name : 'InputDate',
                  					display : '申请日期',
                  					sortable : true
                              },{
                    					name : 'CostMan',
                  					display : '报销人id',
                  					sortable : true
                              },{
                    					name : 'CostDepartID',
                  					display : '费用部门ID',
                  					sortable : true
                              },{
                    					name : 'Area',
                  					display : '区域id',
                  					sortable : true
                              },{
                    					name : 'ProjectNo',
                  					display : '项目编号',
                  					sortable : true
                              },{
                    					name : 'CostDates',
                  					display : '报销日期(从~到~)',
                  					sortable : true
                              },{
                    					name : 'CostMasterID',
                  					display : 'CostMasterID',
                  					sortable : true
                              },{
                    					name : 'CostBelongtoDeptIds',
                  					display : 'CostBelongtoDeptIds',
                  					sortable : true
                              },{
                    					name : 'CostClientType',
                  					display : '客户类型(中文)',
                  					sortable : true
                              },{
                    					name : 'CostClientArea',
                  					display : 'CostClientArea',
                  					sortable : true
                              },{
                    					name : 'CostClientName',
                  					display : '客户名称',
                  					sortable : true
                              },{
                    					name : 'ServiceQuantity',
                  					display : 'ServiceQuantity',
                  					sortable : true
                              },{
                    					name : 'Status',
                  					display : 'Status',
                  					sortable : true
                              },{
                    					name : 'UpdateDT',
                  					display : 'UpdateDT',
                  					sortable : true
                              },{
                    					name : 'isProject',
                  					display : 'isProject',
                  					sortable : true
                              },{
                    					name : 'xm_sid',
                  					display : 'xm_sid',
                  					sortable : true
                              },{
                    					name : 'RecInvoiceDT',
                  					display : 'RecInvoiceDT',
                  					sortable : true
                              },{
                    					name : 'isNotReced',
                  					display : 'isNotReced',
                  					sortable : true
                              },{
                    					name : 'Amount',
                  					display : '金额',
                  					sortable : true
                              },{
                    					name : 'Updator',
                  					display : 'Updator',
                  					sortable : true
                              },{
                    					name : 'PayDT',
                  					display : 'PayDT',
                  					sortable : true
                              },{
                    					name : 'IsFinRec',
                  					display : 'IsFinRec',
                  					sortable : true
                              },{
                    					name : 'FinRecDT',
                  					display : 'FinRecDT',
                  					sortable : true
                              },{
                    					name : 'SubDept',
                  					display : 'SubDept',
                  					sortable : true
                              },{
                    					name : 'ExamType',
                  					display : 'ExamType',
                  					sortable : true
                              },{
                    					name : 'CostBelongTo',
                  					display : 'CostBelongTo',
                  					sortable : true
                              },{
                    					name : 'CheckAmount',
                  					display : 'CheckAmount',
                  					sortable : true
                              },{
                    					name : 'isHandUp',
                  					display : 'isHandUp',
                  					sortable : true
                              },{
                    					name : 'HandUpDT',
                  					display : 'HandUpDT',
                  					sortable : true
                              },{
                    					name : 'Payee',
                  					display : 'Payee',
                  					sortable : true
                              },{
                    					name : 'rand_key',
                  					display : 'rand_key',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_expense_NULL&action=pageItemJson',
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