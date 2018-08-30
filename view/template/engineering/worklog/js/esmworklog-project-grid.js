var show_page = function(page) {
	$("#esmworklogGrid").yxgrid("reload");
};

$(function() {
	//表头按钮数组
	buttonsArr = [];
	

	batchUnauditArr = {
		name : 'batchUnaudit',
		text : '批量取消审核',
		icon : 'delete',
		action : function(row, rows, grid) {
			showOpenWin("?model=engineering_worklog_esmworklog&action=toBatchUnaudit&projectId="+$("#projectId").val(),1,300,700);
		}
	};
	if($("#unauditLimit").val() == "1"){
		buttonsArr.push(batchUnauditArr);
	}
	
	$("#esmworklogGrid").yxgrid({
		model : 'engineering_worklog_esmworklog',
		title : '工作日志',
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton :false,
		param : {
			'projectId' : $("#projectId").val()
		},
		// 列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'executionDate',
				display : '日期',
				sortable : true,
				width : 70,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
				}
			}, {
				name : 'createName',
				display : '填写人',
				sortable : true
			},{
				name : 'country',
				display : '国家',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'province',
				display : '省',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'city',
				display : '市',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'provinceCity',
				display : '所在地',
				sortable : true,
				width : 80
			}, {
				name : 'workStatus',
				display : '工作状态',
				sortable : true,
				width : 70,
				datacode : 'GXRYZT',
				hide : true
			}, {
				name : 'projectName',
				display : '项目',
				sortable : true,
				width : 140,
				hide : true
			}, {
				name : 'activityName',
				display : '任务',
				sortable : true,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showActivity(" + row.activityId + ")'>" + v + "</a>";
				}
			}, {
				name : 'workloadAndUnit',
				display : '完成量',
				sortable : true,
				width : 60,
				process : function(v,row){
					return v + " " + row.workloadUnitName;
				}
			}, {
				name : 'workloadDay',
				display : '完成量',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'workProcess',
				display : '进度',
				sortable : true,
				width : 70,
				process : function (v){
					if(v*1 == -1){
						return " -- ";
					}else{
						return v + " %";
					}
				}
			}, {
				name : 'description',
				display : '完成情况描述',
				sortable : true,
				width : 150
			},{
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			},{
				name : 'status',
				display : '周报状态',
				sortable : true,
				width : 60,
				process : function(v) {
					if (v == "WTJ") {
						return "未提交";
					} else if (v == "YTJ") {
						return "已提交";
					} else if(v == 'YQR'){
						return "已确认";
					} else {
						return "不通过";
					}
				},
				hide : true
			},{
				name : 'confirmStatus',
				display : '确认状态',
				sortable : true,
				width : 60,
				process : function(v) {
					if (v == "1") {
						return "已确认";
					}else{
						return "未确认";
					}
				},
				hide : true
			},{
				name : 'assessResultName',
				display : '审核结果',
				sortable : true,
				width : 70
			},{
				name : 'feedBack',
				display : '审核建议',
				sortable : true
			},{
				name : 'costMoney',
				display : '录入费用',
				sortable : true,
				width : 70,
				process : function (v,row){
					if(row.confirmStatus == '0'){
						return "<span class='green' title='未确认的费用'>" + moneyFormat2(v) + "</span>";
					}else{
						return "<span class='blue' title='已确认的费用'>" + moneyFormat2(v) + "</span>";
					}
				}
			},{
				name : 'confirmMoney',
				display : '确认费用',
				sortable : true,
				width : 70,
				process : function (v,row){
					if(row.confirmStatus == '1' && v != row.costMoney){
						return "<span class='blue'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				}
			},{
				name : 'backMoney',
				display : '打回费用',
				sortable : true,
				width : 70,
				process : function (v,row){
					if(v > 0){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
//						return "<a href='javascript:void(0)' style='color:red;' onclick='reeditCost(\"" + row.id + "\")' title='点击重新编辑费用'>" + moneyFormat2(v) + "</a>";
					}else{
						return moneyFormat2(v);
					}
				}
			},{
				name : 'inWorkRate',
				display : '投入工作比例',
				sortable : true,
				width : 70,
				process : function (v){
					return moneyFormat2(v)+'%';
				}				
			},{
				name : 'confirmName',
				display : '确认人',
				sortable : true,
				width : 80,
				hide : true
			},{
				name : 'confirmDate',
				display : '确认日期',
				sortable : true,
				width : 70,
				hide : true
			},{
				name : 'thisActivityProcess',
				display : '本次任务进度',
				sortable : true,
				width : 80,
				hide : true
			},{
				name : 'thisProjectProcess',
				display : '本次项目进度',
				sortable : true,
				width : 80,
				hide : true
			}
		],
		buttonsEx : buttonsArr,
		menusEx : [{
			text : '查看日志',
			icon : 'view',
			action : function(row, rows, grid) {
				showOpenWin("?model=engineering_worklog_esmworklog&action=toView&id=" + row.id + '&skey=' + row.skey_ ,1,750,1150);
			}
		}, {
			text : '查看周报',
			icon : 'view',
			action : function(row, rows, grid) {
				showOpenWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
						+ row.weekId );
			}
		}, {
			text : '取消审核',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.confirmStatus == "1" && $("#unauditLimit").val() == "1") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if(confirm('确定要取消日志的审核吗')){
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_worklog_esmworklog&action=unauditLog",
					    data: {"id" : row.id},
					    async: false,
					    success: function(data){
					   	   if(data == '1'){
								alert('操作成功');
								show_page();
							}else{
								alert(data);
							}
						}
					});
				}
			}
		}],
		//过滤数据
		comboEx : [{
			text : '审核状态',
			key : 'confirmStatus',
			data : [{
				text : '已审核',
				value : '1'
			}, {
				text : '未审核',
				value : '0'
			}]
		}],
		searchitems : [{
			display : '填报日期',
			name : 'executionDateSearch'
		}, {
			display : '任务名称',
			name : 'activityNameSearch'
		}, {
			display : '项目名称',
			name : 'projectNameSearch'
		}, {
			display : '填写人',
			name : 'createNameSearch'
		}],
		sortorder : "DESC",
		sortname : "executionDate desc,activityName"
	});
});