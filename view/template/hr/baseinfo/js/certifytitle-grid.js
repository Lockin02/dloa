var show_page = function (page) {
	$("#certifytitleGrid").yxgrid("reload");
};
$(function () {
	$("#certifytitleGrid").yxgrid({
		model : 'hr_baseinfo_certifytitle',
		title : '任职资格称谓表',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'titleName',
				display : '头衔名称',
				sortable : true
			}, {
				name : 'careerDirectionName',
				display : '职业发展通道',
				sortable : true
			}, {
				name : 'baseLevelName',
				display : '申请级别',
				sortable : true
			}, {
				name : 'baseGradeName',
				display : '申请级等',
				sortable : true
			}, {
				name : 'remark',
				display : '备注',
				sortable : true
			}, {
				name : 'statusCN',
				display : '状态',
				sortable : true
			}
		],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_baseinfo_NULL&action=pageItemJson',
			param : [{
					paramId : 'mainId',
					colId : 'id'
				}
			],
			colModel : [{
					name : 'XXX',
					display : '从表字段'
				}
			]
		},
		
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		comboEx : [{
			text : '状态',
			key : 'status',
			data : [ {
						text : '关闭',
						value : '0'
					}, {
						text : '启用',
						value : '1'
					}]
		}],
		searchitems : [{
				display : "头衔名称",
				name : 'titleName'
			}, {
				display : "申请级等",
				name : 'baseGradeName'
			}, {
				display : "申请级别",
				name : 'baseLevelName'
			}, {
				display : "职业发展通道",
				name : 'careerDirectionName'
			}
		]
	});
});