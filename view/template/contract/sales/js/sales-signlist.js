var show_page = function(page) {
	$("#sales").yxgrid("reload");
};
$(function() {
	$("#sales").yxgrid({
        param : {"signStatusIn":"3,4","exaStatus":"完成"},
		model : 'contract_sales_sales',
		isViewAction : false,
		isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        showcheckbox : false,
		sortorder : "DESC",
		title : '合同签收列表',
		comboEx: [{
			text: "签约状态",
			key: 'signStatus',
			data : [{
				text : '已提交纸质合同',
				value : '3'
				},{
				text : '财务已签收',
				value : '4'
				}
			]
		}],
		menusEx : [
		{
			text : '合同信息',
			icon : 'view',
			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id);
			}
		},{
			text : '合同签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.signStatus == '3' ) {
					return true;
				}
				return false;
			},
			action: function(row){
				if(confirm('确认签收？')){
					$.ajax({
						type : "POST",
						url : "?model=contract_sales_sales&action=sign",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('签收成功！');
								$("#sales").yxgrid("reload");
							}else{
								alert('签收失败!');
							}
						}
					});
				}
			}
		}],
		// 表单
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '鼎利合同号',
				name : 'formalNo',
				sortable : true,
				width : 150,
				process : function(v,row){
					if(v != ""){
						return v;
					}else{
						return "<font color='blue'>"+ row.temporaryNo+ "</font>";
					}
				}
//			}, {
//				display : '系统合同号',
//				name : 'contNumber',
//				sortable : true,
//				width : 150
			}, {
				display : '合同名称',
				name : 'contName',
				sortable : true,
				width : 160
			}, {
				display : '客户名称',
				name : 'customerName',
				sortable : true,
				width : 150
			}, {
				display : '客户合同号',
				name : 'customerContNum',
				sortable : true
			}, {
				display : '客户类型',
				name : 'customerType',
				datacode : 'KHLX',
				sortable : true,
				width : 100
			}, {
				display : '客户所属省份',
				name : 'province',
				sortable : true
			}, {
				display : '负责人名称',
				name : 'principalName',
				sortable : true
			}, {
				display : '执行人名称',
				name : 'executorName',
				sortable : true
			}, {
				display : '签约状态',
				name : 'signStatus',
				sortable : true,
				process : function(v){
					if (v== 3){
						return "已提交纸质合同";
					} else if(v== 4){
						return "财务已签收";
					}
				}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'contName'
		}]
	});
});