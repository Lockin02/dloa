var show_page = function(page) {
	$("#myreturnGrid").yxgrid("reload");
};
$(function() {
	$("#myreturnGrid").yxgrid({
		model : 'projectmanagent_borrowreturn_borrowreturn',
		title : '归还申请单',
		param : {'borrowId' : $("#borrowId").val()},
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'state',
			display : '赔偿状态',
			sortable : true,
			align : 'center',
			process : function(v) {
				switch(v){
					case '0' : return '<img src="images/icon/cicle_grey.png" title="暂无"/>';break;
					case '1' : return '<img src="images/icon/cicle_yellow.png" title="待生成赔偿单"/>';break;
					case '2' : return '<img src="images/icon/cicle_green.png" title="已生成赔偿单"/>';break;
				}
			},
			width : 50
		}, {
			name : 'borrowId',
			display : '借用单ID',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '单据编号',
			sortable : true,
			width : 130
		}, {
			name : 'borrowCode',
			display : '借用单编号',
			sortable : true,
			width : 100
		}, {
			name : 'borrowLimit',
			display : '借用类型',
			width : 60,
			sortable : true
		}, {
			name : 'applyTypeName',
			display : '申请类型',
			width : 60,
			sortable : true
		}, {
			name : 'disposeState',
			display : '处理状态',
			sortable : true,
			process : function(v) {
				switch(v){
					case '0' : return '待处理';break;
					case '1' : return '正在处理';break;
					case '2' : return '已处理';break;
					case '3' : return '保存';break;
					case '8' : return '打回';break;
					default : return '--';
				}
			},
			width : 70
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '申请人',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'deptName',
			display : '申请人部门',
			sortable : true,
			width : 80
		}, {
			name : 'createTime',
			display : '申请时间',
			sortable : true,
			width : 140
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '从表字段'
			}]
		},
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id="
							+ row.id + "&skey=" + row['skey_'],1);
				}
			}
		}],
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
	});
});