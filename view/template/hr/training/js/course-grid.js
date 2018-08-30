var show_page = function(page) {
	$("#courseGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        },{
			name : 'exportIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_training_course&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
    ];

	$("#courseGrid").yxgrid({
		model : 'hr_training_course',
		title : '培训课程',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'courseName',
				display : '课程名称',
				sortable : true,
				width : 120,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_training_course&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'courseType',
				display : '课程类别',
				sortable : true,
				hide : true
			}, {
				name : 'courseTypeName',
				display : '课程类别',
				sortable : true
			}, {
				name : 'agency',
				display : '培训机构',
				sortable : true
			}, {
				name : 'teacherName',
				display : '培训讲师',
				sortable : true
			}, {
				name : 'teacherId',
				display : '培训讲师id',
				sortable : true,
				hide : true
			}, {
				name : 'courseDate',
				display : '培训日期',
				sortable : true
			}, {
				name : 'address',
				display : '培训地点',
				sortable : true,
				hide : true
			}, {
				name : 'lessons',
				display : '培训课时',
				sortable : true
			}, {
				name : 'fee',
				display : '培训费用',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'outline',
				display : '课程大纲',
				sortable : true,
				hide : true
			}, {
				name : 'forWho',
				display : '适用对象',
				sortable : true
			}, {
				name : 'status',
				display : '课程状态',
				sortable : true,
				datacode : 'HRKCZT'
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			}, {
				name : 'personsListName',
				display : '参训人员名单',
				sortable : true,
				hide : true
			}, {
				name : 'personsListAccount',
				display : '参训人员账号',
				sortable : true,
				hide : true
			}, {
				name : 'personsListNo',
				display : '参训人员编号',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人名称',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_training_course&action=viewTab&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		buttonsEx : buttonsArr,
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : "课程名称",
			name : 'courseNameM'
		},{
			display : "课程类别",
			name : 'courseTypeNameM'
		},{
			display : "培训机构",
			name : 'agencyM'
		},{
			display : "培训讲师",
			name : 'teacherNameM'
		}]
	});
});