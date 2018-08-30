var show_page = function(page) {
	$("#goodsbaseinfoGrid").yxgrid("reload");
};
$(function() {
	$("#goodsTypeTree").yxtree({
				url : '?model=goods_goods_goodstype&action=getTreeData',
				event : {
					"node_click" : function(event, treeId, treeNode) {
						var goodsbaseinfoGrid = $("#goodsbaseinfoGrid")
								.data('yxgrid');
						goodsbaseinfoGrid.options.param['goodsTypeId'] = treeNode.id;
						$("#parentName").val(treeNode.name);
						$("#parentId").val(treeNode.id);
						goodsbaseinfoGrid.reload();
					}
				}
			});

	$("#goodsbaseinfoGrid").yxgrid({
		model : 'goods_goods_goodsbaseinfo',
		title : '����1 : ��Ʒ��Ϣѡ��',
		showcheckbox : true,
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		param : {
			useStatus : 'WLSTATUSKF'// ֻ��ʾ����״̬�Ĳ�Ʒ
		},
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'goodsTypeName',
					display : '������������',
					sortable : true
				}, {
					name : 'goodsCode',
					display : '��Ʒ���',
					sortable : true,
					hide : true
				}, {
					name : 'goodsName',
					display : '��Ʒ����',
					sortable : true,
					width : 200
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true
				}, {
					name : 'version',
					display : '�汾',
					sortable : true,
					hide : true
				}, {
					name : 'useStatus',
					display : '����״̬',
                    datacode: 'WLSTATUS',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 250
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '��������',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '�޸���',
					sortable : true,
					hide : true
				}, {
					name : 'updateTime',
					display : '�޸�����',
					sortable : true,
					hide : true
				}],
		buttonsEx : [{
			name : 'Add',
			text : "ȷ��",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
				if (rowData) {
					  var goodsTable = parent.document.getElementById("productList");
	                  var rows = goodsTable.rows.length;
				    $.ajax({
					    type : 'POST',
					    url : '?model=projectmanagent_chance_chance&action=toSetProductInfo',
					    data:{
							goodsIds : rowIds,
							chanceId : $("#chanceId").val(),
							rows : rows,
							productLen : $("#productLen").val()
					    },
					    async: false,
					    success : function(data){
//					    	var obj = eval("(" + data +")");
//					    	alert(data)
					    	parent.$("#productList").append(data);
					    	self.parent.tb_remove();
					    	parent.listNum();
						}
					});


				} else {
					alert('����ѡ���¼');
				}
			}
		}],
		menusEx : [{
			text : '����Ԥ��',
			icon : 'view',
			action : function(row, rows, grid) {

				showThickboxWin("?model=goods_goods_properties&action=toPreView&goodsId="
						+ row.id
						// + "&skey="
						// + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "��Ʒ����",
					name : 'goodsName'
				}]
	});
});