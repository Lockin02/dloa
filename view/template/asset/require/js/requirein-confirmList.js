var show_page = function(page) {
	$("#requireinGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireinGrid").yxsubgrid({
		model : 'asset_require_requirein',
		title : '待确认资产出库',
		param : {'status':'待确认'},
		isToolBar : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'billNo',
			display : '单据编号',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requirein&action=toView&id=" + row.id
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			display : '需求id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '需求编号',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requirement&action=toView&id=" + row.requireId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'applyName',
			display : '申请人',
			sortable : true,
			width : 100
		}, {
			name : 'applyDeptName',
			display : '申请部门',
			sortable : true,
			width : 100
		}, {
			name : 'applyDate',
			display : '申请日期',
			sortable : true,
			width : 80
		}, {
			name : 'status',
			display : '单据状态',
			sortable : true,
			width : 60
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 250
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_require_requireinitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'name',
				display : '设备名称',
				width : 120
			}, {
				name : 'description',
				display : '设备描述',
				width : 120
			}, {
				name : 'productName',
				display : '物料名称',
				width : 120
			}, {
				name : 'productCode',
				display : '物料编号',
				width : 120
			}, {
				name : 'productPrice',
				display : '物料金额',
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'brand',
				display : '物料品牌',
				width : 80
			}, {
				name : 'spec',
				display : '规格型号',
				width : 80
			}, {
				name : 'number',
				display : '数量',
				width : 60
			}]
		},
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toView&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '下推仓库出库',
			icon : 'edit',
			action : function(row) {
				if (confirm('确定要下推给仓库进行资产出库？')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_require_requirein&action=ajaxConfirm',
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if( data==1 ){
								alert('下推成功')
								show_page();
							}else{
								alert('下推失败')
							}
							return false;
						}
					});
				}
			}
		},{
			text : '打回',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requirein&action=toBack&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		searchitems : [{
			display : "单据编号",
			name : 'billNo'
		}, {
			display : "需求编号",
			name : 'requireCode'
		}, {
			display : "申请人",
			name : 'applyName'
		}, {
			display : "申请部门",
			name : 'applyDeptName'
		}]
	});
});