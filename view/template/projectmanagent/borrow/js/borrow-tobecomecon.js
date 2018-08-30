var show_page = function(page) {
	$("#borrowGrid").yxsubgrid("reload");
};
$(function() {
	 if($("#ids").val() != ''){
	    var ids = $("#ids").val();
	 }else{
	    var ids = '0';
	 }

	$("#borrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'pageJsonWithChance',
		param : {
			"ids" : ids
		},
		title : '借试用',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'chanceId',
					display : '商机Id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '编号',
					sortable : true
				}, {
					name : 'Type',
					display : '类型',
					sortable : true,
					hide : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'salesName',
					display : '销售负责人',
					sortable : true
				}, {
					name : 'beginTime',
					display : '开始日期',
					width : 80,
					sortable : true
				}, {
					name : 'closeTime',
					display : '截止日期',
					width : 80,
					sortable : true
				}, {
					name : 'scienceName',
					display : '技术负责人',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 80
				},{
					name : 'DeliveryStatus',
					display : '发货状态',
					sortable : true,
					width : 80,
					process : function(v){
		  				if( v == 'WFH'){
		  					return "未发货";
		  				}else if(v == 'YFH'){
		  					return "已发货";
		  				}else if(v == 'BFFH'){
			                return "部分发货";
			            }else if(v == 'TZFH'){
			                return "停止发货";
			            }
		  			}
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true,
					hide :true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 120
				}, {
					name : 'chanceCode',
					display : '商机编号',
					width : 120,
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
							+ row.chanceId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1,'+ row.chanceId +')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
					}
				},{
					name : 'chanceStatus',
					display : '商机状态',
					sortable : true,
					width : 80,
					process : function(v){
						if (v == 0) {
							return "跟踪中";
						} else if (v == 3) {
							return "关闭";
						} else if (v == 4) {
							return "已生成合同";
						} else if (v == 5) {
							return "跟踪中"
						} else if (v == 6) {
							return "暂停"
						}
		  			}
				}],
				comboEx : [{
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
						text : '未审批',
						value : '未审批'
					}, {
						text : '部门审批',
						value : '部门审批'
					}, {
						text : '完成',
						value : '完成'
					}]
				},{
					text : '发货状态',
					key : 'DeliveryStatus',
					data : [{
						text : '未发货',
						value : '0'
					}, {
						text : '已发货',
						value : '1'
					}, {
						text : '部分发货',
						value : '2'
					}]
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson&isTemp=0',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'borrowId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
						name : 'productNo',
						width : 200,
						display : '产品编号',
						process : function(v,row){
							return v+"&nbsp;&nbsp;K3:"+row['productNoKS'];
						}
					},{
						name : 'productName',
						width : 200,
						display : '产品名称',
						process : function(v,row){
							return v+"&nbsp;&nbsp;K3:"+row['productNameKS'];
						}
					}, {
					    name : 'number',
					    display : '申请数量',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '已执行数量',
						width : 80
					}, {
					    name : 'backNum',
					    display : '已归还数量',
						width : 80
					}]
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '编号',
					name : 'Code'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '业务编号',
					name : 'objCode'
				},{
				    display : '销售负责人',
				    name : 'salesName'
				},{
				    display : '申请人',
				    name : 'createNmae'
				},{
					display : '申请日期',
					name : 'createTime'
				},{
				    display : 'K3物料名称',
				    name : 'productNameKS'
				},{
				    display : '系统物料名称',
				    name : 'productName'
				},{
				    display : 'K3物料编码',
				    name : 'productNoKS'
				},{
				    display : '系统物料编码',
				    name : 'productNo'
				}],
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id="
							+ row.id + "&skey=" + row['skey_']);

				}
			}

		}]

	});

});