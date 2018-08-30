var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		action : 'myPageJson',
		title : '盖章记录',
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isAddAction : false,
		customCode : 'myStampGrid',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
            	width : 80
			}, {
				name : 'contractType',
				display : '合同类型',
				sortable : true,
            	width : 80,
            	datacode : 'HTGZYD'
			}, {
				name : 'contractId',
				display : '合同id',
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
            	width : 130
			}, {
				name : 'signCompanyName',
				display : '签约单位',
				sortable : true,
            	width : 130
			}, {
				name : 'contractMoney',
				display : '合同金额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'applyUserId',
				display : '申请人id',
				sortable : true,
				hide : true
			}, {
				name : 'applyUserName',
				display : '申请人',
				sortable : true,
            	width : 80,
				hide : true
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
				sortable : true,
            	width : 80
			}, {
				name : 'stampDate',
				display : '盖章日期',
				sortable : true,
            	width : 80
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 80,
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
				name : 'objCode',
				display : '业务编号',
				width : 120,
				sortable : true
			}, {
				name : 'batchNo',
				display : '盖章批号',
				sortable : true
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true
			}
		],
		// 扩展右键菜单
		menusEx : [{
				name : 'view',
				text : '查看',
				icon : 'view',
				action : function(row, rows, grid) {
					showModalWin('?model=contract_stamp_stamp&action=toView&id=' + row.id);
				}
			},{
				name : 'stamp',
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == "0") {
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
					if (row.status == "0") {
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
		searchitems : [{
			display : "合同编号",
			name : 'contractCodeSer'
		},{
			display : "申请人",
			name : 'applyUserNameSer'
		}],
		// 盖章状态数据过滤
		comboEx : [{
			text: "合同类型",
			key: 'contractType',
			datacode : 'HTGZYD'
		},{
			text: "盖章状态",
			key: 'status',
			value :'0',
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