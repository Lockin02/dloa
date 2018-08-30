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
						title : '��Ʒ��������(����)',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'parentName',
									display : '����������',
									sortable : true
								}, {
									name : 'propertiesName',
									display : '��������',
									sortable : true
								}, {
									name : 'orderNum',
									display : '����',
									sortable : true
								}, {
									name : 'propertiesType',
									display : '����������',
									sortable : true
								}, {
									name : 'isLeast',
									display : '����ѡ��һ��',
									sortable : true
								}, {
									name : 'isInput',
									display : '����ֱ������ֵ',
									sortable : true
								}, {
									name : 'remark',
									display : '��ע',
									sortable : true
								}], // ���ӱ������
						subGridOptions : {
							url : '?model=goods_goods_propertiesitem&action=pageJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'XXX',
										display : '�ӱ��ֶ�'
									}]
						},

						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [{
									display : "�����ֶ�",
									name : 'XXX'
								}]
					})
		});