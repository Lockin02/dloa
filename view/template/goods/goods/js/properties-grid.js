var show_page = function(page) {
	//parent.$("#tree").yxtree("reload");
	
	$("#propertiesGrid").yxsubgrid("reload");

};
$(function() {
	$("#categoryTree").yxtree({
		url : '?model=yxlicense_license_category&action=getTreeData',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var categoryGrid = $("#categoryGrid").data('yxgrid');
				$("#categoryName").val(treeNode.name);
				categoryGrid.reload();
			}
		}
	});
			$("#propertiesGrid").yxsubgrid({
						model : 'goods_goods_properties',
						title : '产品属性配置',
						param : {
							"mainId" : $('#goodsId').val()
						},
						isEditAction : false,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'parentName',
									display : '上级属性名称',
									sortable : true
								}, {
									name : 'propertiesName',
									display : '属性名称',
									sortable : true
								}, {
									name : 'orderNum',
									display : '排序',
									width : '30',
									sortable : true
								}, {
									name : 'propertiesType',
									display : '配置项类型',
									sortable : true,
									width : '80',
									process : function(v) {
										if (v == "0") {
											return "单项选择";
										} else if (v == "1") {
											return "多项选择";
										} else if (v == "2") {
											return "文本输入";
										} else {
											return v;

										}
									}
								}, {
									name : 'isLeast',
									display : '至少选中一项',
									sortable : true,
									width : '90',
									align : 'center',
									process : function(v) {
										if (v == "on") {
											return "√";
										} else {
											return v;
										}
									}
								}, {
									name : 'isInput',
									align : 'center',
									display : '允许直接输入值',
									sortable : true,
									width : '90',
									process : function(v) {
										if (v == "on") {
											return "√";
										} else {
											return v;
										}
									}
								}, {
									name : 'remark',
									display : '备注',
									width : 300,
									sortable : true
								}], // 主从表格设置
						subGridOptions : {
							url : '?model=goods_goods_propertiesitem&action=pageJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'itemContent',
										display : '值内容',
										sortable : true
									}, {
										name : 'isNeed',
										display : '是否必选',
										sortable : true,
										width : '50',
										process : function(v) {
											if (v == "on") {
												return "√";
											} else {
												return v;
											}
										}
									}, {
										name : 'isDefault',
										display : '是否默认',
										width : '50',
										sortable : true,
										process : function(v) {
											if (v == "on") {
												return "√";
											} else {
												return v;
											}
										}
									}, {
										name : 'productCode',
										display : '对应物料编号',
										width : '80',
										sortable : true
									}, {
										name : 'productName',
										display : '对应物料名称',
										width : '200',
										sortable : true
									}, {
										name : 'pattern',
										display : '型号',
										width : '50',
										sortable : true
									}, {
										name : 'proNum',
										display : '数量',
										width : '50',
										sortable : true
									}, {
										name : 'status',
										display : '状态',
										sortable : true,
										width : '30',
										process : function(v) {
											if (v == "ZC") {
												return "在产";
											} else if (v == "TC") {
												return "停产";
											}
										}
									}]
						},
						toAddConfig : {
							formWidth : '1200px',
							formHeight : '600px'
						},
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "属性名称",
									name : 'propertiesName'
								}]
					});
		});