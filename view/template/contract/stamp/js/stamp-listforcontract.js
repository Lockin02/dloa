var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		title : '盖章记录',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		param : {
			"contractId" : $("#contractId").val(),
			"contractType" : $("#contractType").val()
		},
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'contractCode',
				display : '合同编号',
            	width : 130,
				sortable : true
			}, {
				name : 'contractName',
				display : '合同名称',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'contractType',
				display : '合同类型',
				sortable : true,
            	datacode : 'HTGZYD',
				hide : true
			}, {
				name : 'signCompanyName',
				display : '签约单位',
				sortable : true,
            	width : 130,
				hide : true
			}, {
				name : 'applyUserId',
				display : '申请人id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '申请人',
				sortable : true
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true
			}, {
				name : 'stampType',
				display : '盖章类型',
				sortable : true
			}, {
				name : 'stampUserId',
				display : '盖章人id',
				sortable : true,
				hide : true
			}, {
				name : 'stampUserName',
				display : '盖章人',
				sortable : true
			}, {
				name : 'stampDate',
				display : '盖章日期',
				sortable : true
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				process : function(v,row){
					if(v=="1"){
						return "已盖章";
					}else if(v=='2'){
						return "已关闭";
					}else{
						return "未盖章";
					}
				}
			}, {
				name : 'batchNo',
				display : '盖章批号',
				sortable : true
			}, {
				name : 'remark',
				display : '备注',
				width : 200,
				sortable : true
			}
		],

		// 扩展右键菜单
		menusEx : [{
				name : 'stamp',
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == "0" && row.applyUserId == $("#userId").val()) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
//					if(row.applyUserId != $("#userId").val()){
//						alert('非盖章申请人不能修改该记录');
//						return false;
//					}
					showThickboxWin('?model=contract_stamp_stamp&action=toEdit&id=' + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800"
					);
				}
			}
			,{
				name : 'close',
				text : '关闭',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.status == "0" && row.applyUserId == $("#userId").val()) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					if(confirm('关闭后不能重新开启，确认关闭吗？')){
						$.ajax({
							type : "POST",
							url : "?model=contract_stamp_stamp&action=close",
							data : {
								"id" : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('关闭成功!');
									show_page(1);
								}else{
									alert('关闭失败!');
								}
							}
						});
					}
				}
			}
		],
		toViewConfig : {
			action : 'toViewOnly'
		},
		searchitems : [{
			display : "合同编号",
			name : 'contractCodeSer'
		},{
			display : "申请人",
			name : 'applyUserNameSer'
		}],
		// 盖章状态数据过滤
		comboEx : [{
			text: "盖章状态",
			key: 'status',
			data : [{
				text : '未盖章',
				value : '0'
			}, {
				text : '已盖章',
				value : '1'
			}, {
				text : '已关闭',
				value : '2'
			}]
		}]
	});
});