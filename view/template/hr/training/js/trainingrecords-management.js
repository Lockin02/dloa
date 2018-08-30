var show_page = function(page) {
	$("#teacherGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
//        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        },
        	{
			name : 'exportIn',
			text : "导入",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_training_teacher&action=toExcelIn"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}
	];

	$("#teacherGrid").yxgrid({
		height:450,
		model : 'hr_training_teacher',
		title : '培训管理-讲师管理',
		param : {
//			'teacherName' : $('#userName').val(),
			'teacherNum' : $('#userNo').val()
		},
		isAddAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'teacherNum',
				display : '讲师编号',
				sortable : true
			},{
				name : 'teacherName',
				display : '讲师名称',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_training_teacher&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'teacherAccount',
				display : '讲师账号',
				sortable : true,
				hide : true
			},{
				name : 'belongDeptName',
				display : '讲师部门',
				sortable : true
			},{
				name : 'trainingAgency',
				display : '培训机构',
				sortable : true
			}, {
				name : 'belongDeptId',
				display : '讲师部门ID',
				sortable : true,
				hide : true
			},{
				name : 'lecturerPost',
				display : '讲师岗位',
				sortable : true
			},{
				name : 'lecturerPostId',
				display : '讲师岗位ID',
				sortable : true,
				hide : true
			},{
				name : 'lecturerCategory',
				display : '讲师类别',
				sortable : true
			},
//			{
//				name : 'isInner',
//				display : '是否内训师',
//				sortable : true,
//				process : function(v){
//					if(v == '1'){
//						return '是';
//					}else{
//						return '否';
//					}
//				}
//			},
			 {
				name : 'levelIdName',
				display : '内训师级别',
				sortable : true
			},
			{
				name : 'certifyDate',
				display : '认证日期',
				sortable : true,
				process : function(v,row){
					if(row.certifyDate == '0000-00-00'){
						return '';
					}else{
						return v;
					}
				}
			}, {
				name : 'scores',
				display : '认证分数',
				sortable : true
//				process : function(v,row){
//					if(row.isInner != '1'){
//						return '';
//					}else{
//						return v;
//					}
//				}
			}, {
				name : 'courses',
				display : '可授课程',
				sortable : true,
				width : 300
			}, {
				name : 'remark',
				display : '备注说明',
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
		lockCol:['teacherNum','teacherName','belongDeptName'],//锁定的列名
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
//		buttonsEx : buttonsArr,
		//下拉过滤
		lockCol:['teacherNum','teacherName','belongDeptName'],//锁定的列名
//		comboEx : [{
//			text : '讲师内别',
//			key : 'lecturerCategory',
//			data : [{
//					text : '内训师',
//					value :'内训师'
//				},{
//					text : '临时讲师',
//					value :'临时讲师'
//				},{
//					text : '外部讲师',
//					value :'外部讲师'
//				}]
//			},{
//				text : '内训师级别',
//				key : 'levelId',
//				datacode : 'HRNSSJB'
//			}
//		],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : "讲师名称",
			name : 'teacherNameM'
		}]
	});
});