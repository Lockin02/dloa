var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};
$(function() {
	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		action : 'myPageJson',
		title: '我的采购发票',
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox :false,
		//列信息
		colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true,
				process : function(v,row){
					return v + "<input type='hidden' id='isBreak"+ row.id+"' value='unde'>";
				}
			},
			{
				name: 'objCode',
				display: '单据编号',
				sortable: true,
				width : 130,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else{
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'objNo',
				display: '发票号码',
				sortable: true
			},
			{
				name: 'supplierName',
				display: '供应商名称',
				sortable: true,
				width : 150
			},
			{
				name: 'invType',
				display: '发票类型',
				sortable: true,
				width : 80,
				datacode : 'FPLX'
			},
			{
				name: 'taxRate',
				display: '税率(%)',
				sortable: true,
				width : 60
			},
			{
				name: 'formAssessment',
				display: '单据税额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'amount',
				display: '总金额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formCount',
				display: '价税合计',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formDate',
				display: '单据日期',
				sortable: true,
				width : 80
			},
			{
				name: 'payDate',
				display: '付款日期',
				sortable: true,
				width : 80
			},{
				name : 'purcontCode',
				display : '采购订单编号',
				width : 130,
				hide : true
			},
			{
				name: 'departments',
				display: '部门',
				sortable: true,
				width : 80
			},
			{
				name: 'salesman',
				display: '业务员',
				sortable: true,
				width : 80
			},
			{
				name : 'businessBelongName',
				display : '归属公司',
				sortable : true,
				width : 80
			},
			{
				name: 'ExaStatus',
				display: '审核状态',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '已审核';
					}else{
						return '未审核';
					}
				}
			},
			{
				name: 'exaMan',
				display: '审核人',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaDT',
				display: '审核日期',
				sortable: true,
				width : 80
			},
			{
				name: 'status',
				display: '钩稽状态',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '已钩稽';
					}else{
						return '未钩稽';
					}
				}
			},{
				name : 'createName',
				display : '创建人',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '所属原发票id',
				hide: true
			}
		],

		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [
				{
					paramId : 'invPurId',// 传递给后台的参数名称
					colId : 'id'// 获取主表行数据的列名称
				}
			],
			// 显示的列
			colModel : [{
					name : 'productNo',
					display : '物料编号',
					width : 80
				},{
					name : 'productName',
					display : '物料名称',
					width : 140
				},{
				    name : 'number',
				    display : '数量',
				    width : 50
				},{
					name : 'price',
					display : '单价',
					process : function(v,row,parentRow){
						return moneyFormat2(v,6,6);
					}
				},{
					name : 'taxPrice',
					display : '含税单价',
					process : function(v){
						return moneyFormat2(v,6,6);
					}
				},{
				    name : 'assessment',
				    display : '税额',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 70
				},{
				    name : 'amount',
				    display : '金额',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'allCount',
				    display : '价税合计',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'objCode',
				    display : '源单编号',
				    width : 120
				},{
				    name : 'contractCode',
				    display : '订单编号',
				    width : 120
				}
			]
		},
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=finance_invpurchase_invpurchase&action=toAdd");
			}
		},
		menusEx : [
			{
				text: "查看",
				icon: 'view',
				action: function(row) {
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id + '&skey=' + row.skey_ );
				}
			},
			{
				text: "修改",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&id=' + row.id + '&skey=' + row.skey_ );
				}
			},
			{
				text: "删除",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){
						return true;
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if(msg * 1 == msg){
									if (msg == 1) {
										alert('删除成功！');
										show_page(1);
									}else{
										alert("删除失败! ");
									}
								}else{
									alert(msg);
									show_page(1);
								}
							}
						});
					}
				}
			}
		],
		comboEx:
		[
			{
				text: "审核状态",
				key: 'ExaStatus',
				data: [{
					text : '已审核',
					value : '1'
				},{
					text : '未审核',
					value : '0'
				}]
			},{
				text: "钩稽状态",
				key: 'status',
				data: [{
					text : '已钩稽',
					value : '1'
				},{
					text : '未钩稽',
					value : '0'
				}]
			},{
				text: "发票类型",
				key: 'invType',
				datacode : 'FPLX'
			}
		],
		searchitems:[
	        {
	            display:'发票号码',
	            name:'objNo'
	        },
	        {
	            display:'供应商名称',
	            name:'supplierName'
	        },
	        {
	            display:'单据编号',
	            name:'objCodeSearch'
	        },
	        {
	            display:'源单编号',
	            name:'objCodeSearchDetail'
	        },
	        {
	            display:'采购订单编号',
	            name:'contractCodeSearch'
	        }
        ],
        sortname : 'updateTime'
	});
});