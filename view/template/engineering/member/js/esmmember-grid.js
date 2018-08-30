var show_page = function(page) {
	$("#esmmemberGrid").yxgrid("reload");
};

$(function() {

	var projectId = $("#projectId").val();
	$("#esmmemberGrid").yxgrid({
		model : 'engineering_member_esmmember',
		param : {
			"projectId" : projectId
		},
		title : '项目成员',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
		isOpButton : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				hide : true
			}, {
				name : 'memberName',
				display : '姓名',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId' || row.memberId == 'SYSTEM') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_member_esmmember&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'memberId',
				display : '成员id',
				sortable : true,
				hide : true
			}, {
				name : 'personLevel',
				display : '级别',
				sortable : true,
				width : 50,
				process : function(v,row){
					if(row.memberId != 'SYSTEM') return v;
				}
			}, {
				name : 'beginDate',
				display : '加入项目',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'endDate',
				display : '离开项目',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'roleName',
				display : '角色',
				sortable : true,
				width : 70
			}, {
				name : 'activityName',
				display : '工作内容',
				width : 150,
				sortable : true,
				hide : true
			}, {
				name : 'feeDay',
				display : '参与天数',
				sortable : true,
				process : function(v,row){
					if(row.beginDate == ''){
						return '0.00';
					}
					if(row.endDate == ''){
						return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 70
			}, {
				name : 'status',
				display : '状态',
				process : function(v,row){
					if(row.id != 'noId'){
						if(v == 1){
							return '离开项目';
						}else {
							return '正常';
						}
					}
				},
				width : 70
			}, {
				name : 'feePeople',
				display : '人力成本(天)',
				sortable : true,
				process : function(v,row){
					if(row.beginDate == ''){
						return '0.00';
					}
					if(row.endDate == ''){
						return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 70,
				hide : true
			}, {
				name : 'feePerson',
				display : '人力成本(确认)',
				sortable : true,
				process : function(v,row){
					if(row.memberId == 'SYSTEM'){
						return moneyFormat2(v);
					}else{
						if(row.beginDate == ''){
							return '0.00';
						}
						if(row.endDate == ''){
							return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width : 80
			}, {
				name : 'feePersonCount',
				display : '人力成本(实时)',
				sortable : true,
				process : function(v,row){
					if(row.beginDate == ''){
						return '0.00';
					}
					if(row.endDate == ''){
						return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'costMoney',
				display : '录入费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unconfirmMoney',
				display : '未确认费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'confirmMoney',
				display : '已确认费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'backMoney',
				display : '打回费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unexpenseMoney',
				display : '未报销费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expensingMoney',
				display : '在报销费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expenseMoney',
				display : '已报销费用',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			}
		],
//		menusEx : [{
//			text : '删除',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if(row.isManager == "1"){
//					return false;
//				}
//				if (row.status == "0") {
//					return true;
//				}
//				return false;
//			},
//			action : function(rowData, rows, rowIds, g) {
//				if(rowData.memberType == 0){
//					$.ajax({
//						type : "POST",
//						url : "?model=engineering_worklog_esmworklog&action=checkExistLogPro",
//						data : {
//							"projectId" : projectId,
//							"userId" : rowData.memberId
//						},
//				    	async: false,
//						success : function(msg) {
//							if (msg == '1') {
//								alert('该成员已对项目发生业务操作，不能删除！');
//							} else {
//								if(confirm('确定要删除项目成员吗？')){
//									$.ajax({
//										type : "POST",
//										url : "?model=engineering_member_esmmember&action=ajaxdeletes",
//										data : {
//											id : rowData.id
//										},
//					    				async: false,
//										success : function(msg) {
//											if (msg == 1) {
//												alert('删除成功！');
//												show_page(1);
//											}else{
//												alert("删除失败! ");
//											}
//										}
//									});
//								}
//							}
//						}
//					});
//				}else{
//					g.options.toDelConfig.toDelFn(g.options, g);
//				}
//			}
//		}],
		toAddConfig : {
			formWidth : 950,
			formHeight : 500,
			plusUrl : "&id="
					+ projectId
		},
		toEditConfig : {
			formWidth : 950,
			formHeight : 500,
			action : 'toEdit',
			showMenuFn : function(row){
				if(row.memberId != 'SYSTEM' && row.status != 1){
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			action : 'toView',
			showMenuFn : function(row){
				if(row.memberId != 'SYSTEM'){
					return true;
				}
				return false;
			}
		},
		searchitems : [{
			display : "成员名称",
			name : 'memberNameSearch'
		},{
			display : "成员等级",
			name : 'personLevelSearch'
		}],
		comboEx :[{
			text : "状态",
			key : 'status',
			value : '0',
 			data : [{
                text : '正常',
                value : 0
            }, {
                text : '离开项目',
                value : 1
            }]
		}],
		sortorder : 'ASC',
		sortname : 'c.isManager desc,c.personLevel'
	});
});