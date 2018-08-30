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
		title : '步骤1 : 产品信息选择',
		showcheckbox : true,
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		param : {
			useStatus : 'WLSTATUSKF'// 只显示开放状态的产品
		},
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'goodsTypeName',
					display : '所属分类名称',
					sortable : true
				}, {
					name : 'goodsCode',
					display : '产品编号',
					sortable : true,
					hide : true
				}, {
					name : 'goodsName',
					display : '产品名称',
					sortable : true,
					width : 200
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true
				}, {
					name : 'version',
					display : '版本',
					sortable : true,
					hide : true
				}, {
					name : 'useStatus',
					display : '发布状态',
                    datacode: 'WLSTATUS',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 250
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
				}],
		buttonsEx : [{
			name : 'Add',
			text : "确认",
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
					alert('请先选择记录');
				}
			}
		}],
		menusEx : [{
			text : '配置预览',
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
					display : "产品名称",
					name : 'goodsName'
				}]
	});
});