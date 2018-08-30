var show_page = function(page) {
	$("#borrowReportGrid").yxsubgrid("reload");
};
$(function() {
	$("#borrowReportGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'borrowReportJson',
//		param : {
//			'limits' : '客户'
//////			'statusArr' : '0,1'
//		},
		title : '借试用报表',
		//按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,


		//列信息
		colModel : [{
			display : 'id',
			name : 'userId',
			sortable : true,
			hide : true
		}, {
			name : 'dept',
			display : '部门',
			width : 150,
			sortable : true
		}, {
			name : 'user',
			display : '员工',
			width : 150,
			sortable : true
		}, {
			name : 'userId',
			display : '员工Id',
			sortable : true,
			hide : true
		}, {
			name : 'allMoney',
			display : '总借用金额',
			width : 150,
			sortable : true,
			process : function(v) {
					return moneyFormat2(v);
				     }
		}, {
			name : 'moneyLimit',
			display : '借用金额额度',
			width : 150,
			sortable : false,
			process : function(v) {
					return moneyFormat2(v);
				     }
		}, {
			name : 'isOverrun',
			display : '是否超限',
			width : 150,
			sortable : false
		}, {
			name : 'overrunMoeny',
			display : '超限金额',
			width : 150,
			sortable : false,
			process : function(v) {
					return moneyFormat2(v);
				     }
		}],
//		//扩展右键菜单
//		menusEx : [
//			{
//				text : '123',
//				icon : 'view',
//				showMenuFn : function(row){
//					alert(row);
//				}
//			}
//		],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '员工',
			name : 'user'
		},{
			display : '部门',
			name : 'dept'
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrow&action=borrowreportTable',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'createId',// 传递给后台的参数名称
				colId : 'userId'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
						name : 'productNo',
						width : 80,
						display : '产品编码'
					},{
						name : 'productName',
						width : 150,
						display : '物料名称'
					},{
						name : 'productModel',
						width : 200,
						display : '规格型号'
					},{
						name : 'number',
						width : 40,
						display : '数量'
					}, {
					    name : 'price',
					    display : '单价',
						width : 70,
							process : function(v) {
									return moneyFormat2(v);
								     }
					}, {
					    name : 'money',
					    display : '总金额',
						width : 70,
						process : function(v) {
							return moneyFormat2(v);
					    }
					},{
					    name : 'beginTime',
					    display : '起始时间',
						width : 80
					},{
					    name : 'endTime',
					    display : '预计归还时间',
						width : 80
					},{
					    name : 'isOvertime',
					    display : '是否超时',
						width : 50
					},{
					    name : 'overtimeNum',
					    display : '超时天数',
						width : 60
					}, {
						name : 'renewNum',
						display : '续借数量',
						width : 80,
						sortable : false
					}, {
						name : 'renewDate',
						display : '续借截止日期',
						width : 80,
						sortable : false
					}]

		},
		sortname : 'user',
        sortorder : 'ASC'

	});

});