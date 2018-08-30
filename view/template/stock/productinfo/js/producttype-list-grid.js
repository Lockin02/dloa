/** 物料类型信息列表* */

var show_page = function(page) {
	$("#proTypeTree").yxtree("reload");
	$("#producttypeGrid").yxgrid("reload");

};

$(function() {
	$("#proTypeTree").yxtree({
		url : '?model=stock_productinfo_producttype&action=getTreeDataByParentId',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var producttypeGrid = $("#producttypeGrid").data('yxgrid');
				producttypeGrid.options.param['parentId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				$("#submitDay").val(treeNode.submitDay);
				producttypeGrid.reload();
			}
		}
	});

	$("#producttypeGrid").yxgrid({
		model : 'stock_productinfo_producttype',
		title : '物料分类信息',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		showcheckbox : true,

		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '物料类型',
					name : 'proType',
					width : 200,
					sortable : true
				}, {
					display : '物料属性',
					name : 'properties',
					width : 100,
					sortable : true,
					datacode : 'WLSX'
				}, {
					display : '会计科目',
					name : 'accountingCode',
					width : 150,
					sortable : true,
					datacode : 'KJKM'
				}, {
					display : '所属分类',
					name : 'parentName',
					width : 150,
					sortable : true
				}, {
					display : '排序',
					name : 'orderNum',
					sortable : true
				}, {
					display : '工程启用',
					name : 'esmCanUse',
					width : 80,
					process : function(v) {
						if (v == "1") {
							return "是";
						} else {
							return "否";
						}
					},
					sortable : true
				}],

		searchitems : [{
					display : '物料类型',
					name : 'proType'
				}, {
					display : '所属分类',
					name : 'parentName'
				}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=stock_productinfo_producttype&action=toAdd&parentName="
						+ $("#parentName").val()
						+ "&parentId="
						+ $("#parentId").val()
						+ "&arrivalPeriod="
						+$("#arrivalPeriod").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=850");
			}
		},
		menusEx : [{
			name : 'edit',
			text : "修改",
			icon : 'edit',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_productinfo_producttype&action=setEditType&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=850");
			}

		}, {
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_productinfo_producttype&action=view&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=850");
			}
		}],
		sortname : 'id',
		sortorder : 'DESC'
	});
});