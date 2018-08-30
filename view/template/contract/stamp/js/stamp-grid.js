var show_page = function(page) {
	$("#stampGrid").yxgrid("reload");
};
$(function() {
	$("#stampGrid").yxgrid({
		model : 'contract_stamp_stamp',
		action : 'pageJsonForStampType',
		title : '盖章记录',
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		customCode : 'stampGrid',
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
            	width : 80
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
				name : 'stampCompany',
				display : '公司名',
				sortable : true,
				width : 80
			},  {
				name : 'stampCompanyId',
				display : '公司ID',
				sortable : true,
				hide : true
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
		/**
		 * 扩展按钮
		 */
		buttonsEx : [
			{
				text : "批量确认",
				icon : 'edit',
                name : 'batchStamp',
                action : function(row, rows, rowIds, grid) {
                	if(rows != null){
                		if(confirm('确认盖章?')){
							for(var i=0; i<rows.length; i++){
								if(rows[i]['status'] == 1 ){
									alert("请选择未盖章的数据！");
									return false;
								}
							}
							$.ajax({
							    type: "POST",
							    url: "?model=contract_stamp_stamp&action=batchStamp",
							    data: {'rowIds' : rowIds },
							    async: false,
							    success: function(data){
							    	if(data==1){
										alert('批量确认成功！');
										show_page();
									}else{
										alert('批量确认失败！');
									}
								}
							});
						}
                	}else{
                		alert("请至少选择一条数据！");
                	}
                }
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
				text : '确认盖章',
				icon : 'edit',
				showMenuFn : function(row) {
					if ((row.status == "0")) {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showModalWin('?model=contract_stamp_stamp&action=toConfirmStamp&id=' + row.id);
				}
			}
		],
		searchitems : [{
			display : "合同编号",
			name : 'contractCodeSer'
		},{
			display : "申请人",
			name : 'applyUserNameSer'
		},{
			display : "公司",
			name : "stampCompanySearch"
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