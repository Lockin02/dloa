var show_page = function(page) {
	$("#changeapplyGrid").yxgrid("reload");
};
$(function() {
			$("#changeapplyGrid").yxgrid({
						model : 'service_change_changeapply',
						param : {'relDocId' : $('#relDocId').val(),'relDocType' : 'WXSQD'},
						title : '物料更换申请单',
						isDelAction : false,
						isAddAction : false,
						isEditAction : false,
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
									name : 'rObjCode',
									display : '源单业务编号',
									sortable : true,
									hide : true
								}, {
									name : 'relDocCode',
									display : '源单编号',
									sortable : true
								}, {
									name : 'relDocName',
									display : '源单名称',
									sortable : true,
									hide : true
								}, {
									name : 'relDocType',
									display : '源单类型',
									sortable : true,
									process : function(v) {
										if (v == '') {
											return "";
										} else if (v == 'WXSQD') {
											return "维修申请单";
										}
									}
								}, {
									name : 'customerName',
									display : '客户名称',
									sortable : true,
									width:200
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
									name : 'createId',
									display : '创建人id',
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
									name : 'updateId',
									display : '修改人id',
									sortable : true,
									hide : true
								}, {
									name : 'updateTime',
									display : '修改日期',
									sortable : true,
									hide : true
								}],

								menusEx : [{
									text : '查看',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row.ExaStatus == "完成" || row.ExaStatus == "打回") {
											if (row) {
												showOpenWin("?model=service_change_changeapply&action=viewTab&id="
														+ row.id
														+ "&skey="
														+ row['skey_']
														+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
											}
										} else {
											if (row) {
												showOpenWin("?model=service_change_changeapply&action=toView&id="
														+ row.id
														+ "&skey="
														+ row['skey_']
														+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
											}
										}
									}
								}],


						subGridOptions : {
							url : '?model=service_change_changeitem&action=pageJson',
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
								width:200
							}, {
								name : 'pattern',
								display : '规格型号'
							}, {
								name : 'unitName',
								display : '单位'
							}, {
								name : 'serilnoName',
								display : '序列号'
							}, {
								name : 'remark',
								display : '变更原因',
								width:200
							}]
						},
						comboEx : [{
							text : '审批状态',
							key : 'ExaStatus',
							data : [{
										text : '待提交',
										value : '待提交'
									},{
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
							display : '源单编号',
							name : 'relDocCode'
						}, {
							display : '源单类型',
							name : 'relDocType'
						}]
					});
		});