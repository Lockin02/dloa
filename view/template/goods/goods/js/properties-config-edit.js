var show_page = function(page) {
	$("#propertiesGrid").yxsubgrid("reload");
};
$(function() {
			validate({

						"propertiesName" : {
							required : true

						},
						"orderNum" : {
							required : true,
							custom : ['onlyNumber']
						}
					});
			$("#tree").yxtree({
				url : '?model=goods_goods_properties&action=getTreeData&goodsId='
						+ $("#goodsId").val(),
				param : {
					'goodsId' : $("#goodsId").val()
				},
				event : {
					"node_click" : function(event, treeId, treeNode) {
						$("#rightIfame").attr(
								"src",
								"?model=goods_goods_properties&action=toEdit&id="
										+ treeNode.id)
						// alert()
						// var propertiesGrid =
						// window.frames["rightIfame"]
						// .$("#propertiesGrid").data('yxsubgrid');
						// propertiesGrid.options.param['parentId'] =
						// treeNode.id;
						//
						// // propertiesGrid.options.param['parentId'] =
						// // treeNode.id;
						//
						// //
						// alert($(window.frames["rightIfame"].document
						// // .getElementById("parentId")).val())
						// $(window.frames["rightIfame"].document
						// .getElementById("parentId")).val(treeNode.id);
						// $(window.frames["rightIfame"].document
						// .getElementById("parentName"))
						// .val(treeNode.name);
						//
						// propertiesGrid.reload();
					}
				}
			});
			$("#propertiesGrid").yxsubgrid({
						model : 'goods_goods_properties',
						title : '产品属性配置(树形)',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'parentName',
									display : '父属性名称',
									sortable : true
								}, {
									name : 'propertiesName',
									display : '属性名称',
									sortable : true
								}, {
									name : 'orderNum',
									display : '排序',
									sortable : true
								}, {
									name : 'propertiesType',
									display : '配置项类型',
									sortable : true
								}, {
									name : 'isLeast',
									display : '至少选中一项',
									sortable : true
								}, {
									name : 'isInput',
									display : '允许直接输入值',
									sortable : true
								}, {
									name : 'remark',
									display : '备注',
									sortable : true
								}], // 主从表格设置
						subGridOptions : {
							url : '?model=goods_goods_propertiesitem&action=pageJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '从表字段'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "搜索字段",
									name : 'XXX'
								}]
					})
		});