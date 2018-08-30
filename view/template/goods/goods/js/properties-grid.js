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
						title : '��Ʒ��������',
						param : {
							"mainId" : $('#goodsId').val()
						},
						isEditAction : false,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'parentName',
									display : '�ϼ���������',
									sortable : true
								}, {
									name : 'propertiesName',
									display : '��������',
									sortable : true
								}, {
									name : 'orderNum',
									display : '����',
									width : '30',
									sortable : true
								}, {
									name : 'propertiesType',
									display : '����������',
									sortable : true,
									width : '80',
									process : function(v) {
										if (v == "0") {
											return "����ѡ��";
										} else if (v == "1") {
											return "����ѡ��";
										} else if (v == "2") {
											return "�ı�����";
										} else {
											return v;

										}
									}
								}, {
									name : 'isLeast',
									display : '����ѡ��һ��',
									sortable : true,
									width : '90',
									align : 'center',
									process : function(v) {
										if (v == "on") {
											return "��";
										} else {
											return v;
										}
									}
								}, {
									name : 'isInput',
									align : 'center',
									display : '����ֱ������ֵ',
									sortable : true,
									width : '90',
									process : function(v) {
										if (v == "on") {
											return "��";
										} else {
											return v;
										}
									}
								}, {
									name : 'remark',
									display : '��ע',
									width : 300,
									sortable : true
								}], // ���ӱ������
						subGridOptions : {
							url : '?model=goods_goods_propertiesitem&action=pageJson',
							param : [{
										paramId : 'mainId',
										colId : 'id'
									}],
							colModel : [{
										name : 'itemContent',
										display : 'ֵ����',
										sortable : true
									}, {
										name : 'isNeed',
										display : '�Ƿ��ѡ',
										sortable : true,
										width : '50',
										process : function(v) {
											if (v == "on") {
												return "��";
											} else {
												return v;
											}
										}
									}, {
										name : 'isDefault',
										display : '�Ƿ�Ĭ��',
										width : '50',
										sortable : true,
										process : function(v) {
											if (v == "on") {
												return "��";
											} else {
												return v;
											}
										}
									}, {
										name : 'productCode',
										display : '��Ӧ���ϱ��',
										width : '80',
										sortable : true
									}, {
										name : 'productName',
										display : '��Ӧ��������',
										width : '200',
										sortable : true
									}, {
										name : 'pattern',
										display : '�ͺ�',
										width : '50',
										sortable : true
									}, {
										name : 'proNum',
										display : '����',
										width : '50',
										sortable : true
									}, {
										name : 'status',
										display : '״̬',
										sortable : true,
										width : '30',
										process : function(v) {
											if (v == "ZC") {
												return "�ڲ�";
											} else if (v == "TC") {
												return "ͣ��";
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
									display : "��������",
									name : 'propertiesName'
								}]
					});
		});