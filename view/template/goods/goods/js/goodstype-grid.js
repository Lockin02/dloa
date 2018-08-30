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
						title : '��Ʒ������Ϣ',
						isDelAction :��false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'goodsType',
									display : '��Ʒ��������',
									sortable : true,
									width : 200
								}, {
									name : 'parentName',
									display : '�ϼ�����',
									sortable : true,
									width : 200
								}, {
									name : 'orderNum',
									display : '����',
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
							text : 'ɾ��',
							icon : 'delete',
							action : function(row) {
								if (window.confirm(("ȷ��Ҫɾ��?"))) {
									$.ajax({
										type : "POST",
										url : "?model=goods_goods_goodstype&action=ajaxdeletes",
										data : {
											id : row.id
										},
										success : function(msg) {
											if (msg=='1') {
												alert('ɾ���ɹ���');
												show_page();
											}else if (msg=='2'){
											     alert("ɾ��ʧ�ܣ��ò�Ʒ�¼����ڹ�����Ʒ")
											}else
												alert('ɾ��ʧ�ܣ�');
										}
									});
								}
							}
						}],
						searchitems : [{
									display : "��Ʒ��������",
									name : 'goodsTypeSch'
								}, {
									display : "�ϼ�����",
									name : 'parentNameSch'
								}]
					});
		});