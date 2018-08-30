var show_page = function(page) {
	$("#reduceapplyGrid").yxgrid("reload");
};
$(function() {
	$("#reduceapplyGrid").yxgrid({
		model : 'service_reduce_reduceapply',
		param : {
			'applyId' : $('#applyId').val()
		},
		title : '维修费用减免申请单',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '单据编号',
					sortable : true

				}, {
					name : 'applyCode',
					display : '维修申请单编号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 200
				}, {
					name : 'adress',
					display : '客户地址',
					sortable : true,
					hide : true
				}, {
					name : 'applyUserName',
					display : '申请人名称',
					sortable : true
				}, {
					name : 'applyUserCode',
					display : '申请人账号',
					sortable : true,
					hide : true
				}, {
					name : 'subCost',
					display : '维修费用',
					sortable : true,
					hide : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'subReduceCost',
					display : '减免费用',
					sortable : true,
					process : function(v, row) {
						return moneyFormat2(v);
					}
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true
				}, {
					name : 'ExaDT',
					display : '审批时间',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'createName',
					display : '创建人',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '创建日期',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '修改日期',
					sortable : true,
					hide : true
				}], // 主从表格设置

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row.ExaStatus == "完成" || row.ExaStatus == "打回") {
					if (row) {
						showThickboxWin("?model=service_reduce_reduceapply&action=viewTab&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				} else {
					if (row) {
						showThickboxWin("?model=service_reduce_reduceapply&action=toView&id="
								+ row.id
								+ "&skey="
								+ row['skey_']
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
					}
				}
			}
		}],

		subGridOptions : {
			url : '?model=service_reduce_reduceitem&action=pageJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCode',
						display : '物料编号'
					}, {
						name : 'productName',
						display : '物料名称',
						width : 200
					}, {
						name : 'pattern',
						display : '规格型号'
					}, {
						name : 'productType',
						display : '设备类型'
					}, {
						name : 'unitName',
						display : '单位'
					}, {
						name : 'serilnoName',
						display : '序列号'
					}, {
						name : 'fittings',
						display : '配件信息',
						hide : true
					}, {
						name : 'serilnoName',
						display : '收取费用',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}, {
						name : 'reduceCost',
						display : '减免费用',
						process : function(v, row) {
							return moneyFormat2(v);
						}
					}]
		},
		comboEx : [{
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
								text : '待提交',
								value : '待提交'
							}, {
								text : '打回',
								value : '打回'
							}, {
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '完成',
								value : '完成'
							}]
				}],
		searchitems : [{
					display : '单据编号',
					name : 'docCode'
				}, {
					display : '维修申请单编号',
					name : 'applyCode'
				}, {
					display : '客户名称',
					name : 'customerName'
				}]
	});
});