var show_page = function(page) {
	$("#verifyDetailGrid").yxgrid("reload");
};
$(function() {
	$("#verifyDetailGrid").yxgrid({
	model : 'outsourcing_workverify_verifyDetail',
				isEditAction:false,
				isDelAction:false,
				isAddAction:false,
				isViewAction:false,
				showcheckbox:false,
				bodyAlign:'center',
				param:{'parentStateArr':'1,2,3,4,5'},
               	title : '工作量确认单明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							  },{
                    					name : 'parentId',
                  					display : '父类id',
                  					width : 30,
                  					sortable : true,
         							hide : true
                              },{
                    					name : 'parentState',
                  					display : '父类状态',
                  					width : 30,
                  					sortable : true,
         							hide : true
                              },{
                    					name : 'parentCode',
                  					display : '单据编号',
                  					width : 150,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_workVerify&action=toView&id=" + row.parentId +"\")'>" + v + "</a>";
									}
                              },{
                    					name : 'officeName',
                  					display : '区域',
                  					width : 30,
                  					sortable : true
                              },{
                    					name : 'province',
                  					display : '省份',
                  					width : 35,
                  					sortable : true
                              },{
                    					name : 'outsourcingName',
                  					display : '类型',
                  					width : 75,
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '项目名称',
                  					sortable : true
                              },{
                    					name : 'projectCode',
                  					display : '项目编号',
                  					width : 120,
                  					sortable : true
                              },{
                    					name : 'outsourceContractCode',
                  					display : '外包合同编号',
                  					sortable : true
                              },{
                    					name : 'outsourceSupp',
                  					display : '外包公司',
                  					sortable : true
                              },{
                    					name : 'principal',
                  					display : '负责人',
                  					sortable : true
                              },{
                    					name : 'scheduleTotal',
                  					display : '总进度',
                  					width : 40,
                  					sortable : true
                              },{
                    					name : 'presentSchedule',
                  					display : '本期进度',
                  					width : 50,
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : '租赁人员',
                  					width : 80,
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '开始日期',
                  					width : 70,
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '结束日期',
                  					width : 70,
                  					sortable : true
                              },{
                    					name : 'feeDay',
                  					display : '计价天数',
                  					width : 50,
                  					sortable : true
                              },{
                    					name : 'beginDatePM',
                  					display : 'PM核对本期开始',
                  					sortable : true
                              },{
                    					name : 'endDatePM',
                  					display : 'PM核对本期结束',
                  					sortable : true
                              },{
                    					name : 'feeDayPM',
                  					display : 'PM核对计价天数',
                  					sortable : true
                              },{
                    					name : 'managerAuditState',
                  					display : '项目经理审批状态',
                  					sortable : true,
									process : function(v) {
										if (v == "1") {
											return "<span style='color:blue'>√</span>";
										} else {
											return "<span style='color:red'>-</span>";
										}
									}
                              },{
                    					name : 'serverAuditState',
                  					display : '服务经理审批状态',
                  					sortable : true,
									process : function(v) {
										if (v == "1") {
											return "<span style='color:blue'>√</span>";
										} else {
											return "<span style='color:red'>-</span>";
										}
									}
                              },{
                    					name : 'areaAuditState',
                  					display : '服务总监审批状态',
                  					sortable : true,
									process : function(v) {
										if (v == "1") {
											return "<span style='color:blue'>√</span>";
										} else {
											return "<span style='color:red'>-</span>";
										}
									}
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_workverify_NULL&action=pageItemJson',
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
					display : "区域",
					name : 'officeName'
				},{
    					name : 'province',
  					display : '省份'
              },{
    					name : 'outsourcingName',
  					display : '类型'
              },{
    					name : 'projecttName',
  					display : '项目名称'
              },{
    					name : 'projectCode',
  					display : '项目编号'
              },{
    					name : 'outsourceContractCode',
  					display : '外包合同编号'
              },{
    					name : 'outsourceSupp',
  					display : '外包公司'
              },{
    					name : 'principal',
  					display : '负责人'
              }]
 		});
 });