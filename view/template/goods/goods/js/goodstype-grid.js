var show_page = function(page) {
	$("#goodstypeGrid").yxgrid("reload");
};
var $parentId = "" ;
$(function() {
			$("#goodsTypeTree").yxtree({
						url : '?model=goods_goods_goodstype&action=getTreeData',
						event : {
							"node_click" : function(event, treeId, treeNode) {
								var goodstypeGrid = $("#goodstypeGrid")
										.data('yxgrid');
								goodstypeGrid.options.param['parentId'] = treeNode.id;
								$("#parentName").val(treeNode.name);
								$("#parentId").val(treeNode.id);
								$parentId = treeNode.id;
								goodstypeGrid.reload();
							}
						}
					});
			$("#goodstypeGrid").yxgrid({
						model : 'goods_goods_goodstype',
						title : '产品分类信息',
						isDelAction :　false,
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'goodsType',
									display : '产品分类名称',
									sortable : true,
									width : 200
								}, {
									name : 'parentName',
									display : '上级类型',
									sortable : true,
									width : 200
								}, {
									name : 'orderNum',
									display : '排序',
									sortable : true
								}],

						toAddConfig : {
							toAddFn : function(p ,treeNode , treeId) {
							//	alert(treeNode.data('data')['id']);
							var c = p.toAddConfig;
							var w = c.formWidth ? c.formWidth : p.formWidth;
							var h = c.formHeight ? c.formHeight : p.formHeight;
							showThickboxWin("?model="
									+ p.model
									+ "&action="
									+ c.action
									+ c.plusUrl
									+ "&parentId=" + $parentId
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
									+ h + "&width=" + w);
							}
						},
						toEditConfig : {
							action : 'toEdit',
							formHeight : 300,
							formWidth : 500
						},
						toViewConfig : {
							action : 'toView',
							formHeight : 300,
							formWidth : 500
						},
						
						menusEx : [{
							text : '删除',
							icon : 'delete',
							action : function(row) {
								if (window.confirm(("确定要删除?"))) {
									$.ajax({
										type : "POST",
										url : "?model=goods_goods_goodstype&action=ajaxdeletes",
										data : {
											id : row.id
										},
										success : function(msg) {
											if (msg=='1') {
												alert('删除成功！');
												show_page();
											}else if (msg=='2'){
											     alert("删除失败！该产品下级存在关联产品")
											}else
												alert('删除失败！');
										}
									});
								}
							}
						}],
						searchitems : [{
									display : "产品分类名称",
									name : 'goodsTypeSch'
								}, {
									display : "上级类型",
									name : 'parentNameSch'
								}]
					});
		});