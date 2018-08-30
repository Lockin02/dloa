var show_page = function(page) {	   $("#persronGrid").yxgrid("reload");};
$(function() {			$("#persronGrid").yxgrid({				      model : 'outsourcing_account_persron',
               	title : '外包结算人员租赁',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'personLevel',
                  					display : '人员级别',
                  					sortable : true
                              },{
                    					name : 'personLevelName',
                  					display : '人员级别名称',
                  					sortable : true
                              },{
                    					name : 'pesonName',
                  					display : '姓名',
                  					sortable : true
                              },{
                    					name : 'userAccount',
                  					display : '姓名账号',
                  					sortable : true
                              },{
                    					name : 'userNo',
                  					display : '员工编号',
                  					sortable : true
                              },{
                    					name : 'suppName',
                  					display : '归属外包供应商',
                  					sortable : true
                              }                    ,{
                    					name : 'beginDate',
                  					display : '租赁开始日期',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '租赁结束日期',
                  					sortable : true
                              }                    ,{
                    					name : 'inBudgetPrice',
                  					display : '服务线人力成本单价',
                  					sortable : true
                              },{
                    					name : 'selfPrice',
                  					display : '服务线人力成本',
                  					sortable : true
                              },{
                    					name : 'outBudgetPrice',
                  					display : '外包人力单价',
                  					sortable : true
                              },{
                    					name : 'rentalPrice',
                  					display : '外包价格',
                  					sortable : true
                              },{
                    					name : 'trafficMoney',
                  					display : '交通费',
                  					sortable : true
                              },{
                    					name : 'otherMoney',
                  					display : '其他费用',
                  					sortable : true
                              },{
                    					name : 'customerDeduct',
                  					display : '客户扣款',
                  					sortable : true
                              },{
                    					name : 'examinDuduct',
                  					display : '考核扣款',
                  					sortable : true
                              },{
                    					name : 'skillsRequired',
                  					display : '工作技能要求',
                  					sortable : true
                              },{
                    					name : 'interviewResults',
                  					display : '技术面试结果',
                  					sortable : true
                              },{
                    					name : 'interviewName',
                  					display : '面试人员',
                  					sortable : true
                              },{
                    					name : 'interviewId',
                  					display : '面试人员id',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'changeTips',
                  					display : '变更标志',
                  					sortable : true
                              },{
                    					name : 'isTemp',
                  					display : '是否临时对象',
                  					sortable : true
                              }                    ,{
                    					name : 'isDel',
                  					display : '假删除标志位',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_account_NULL&action=pageItemJson',
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