var show_page = function() {
	$("#incomeGrid").yxgrid("reload");
}

$(function() {
	$("#incomeGrid").yxgrid({
		model : 'finance_income_income',
		action : 'pageJsonList',
		param : {"formType" : "YFLX-TKD"},
		title : '应收退款管理',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
        isOpButton : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '进账单号',
			name : 'inFormNum',
			sortable : true,
			width : 110,
            hide : true
		}, {
			display : '系统单据号',
			name : 'incomeNo',
			sortable : true,
			width : 120,
            process : function(v,row){
                if(row.id == 'noId') return v;
                return "<a href='#' onclick='showOpenWin(\"?model=finance_income_income&action=toAllot&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
		}, {
			display : '客户名称',
			name : 'incomeUnitName',
			sortable : true,
			width : 130
		}, {
			display : '省份',
			name : 'province',
			sortable : true,
			width : 80
		}, {
			display : '单据日期',
			name : 'incomeDate',
			sortable : true,
            width : 80
		}, {
			display : '结算类型',
			name : 'incomeType',
			datacode : 'DKFS',
			sortable : true,
			width : 80
		}, {
			display : '单据金额',
			name : 'incomeMoney',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 90
		}, {
			name : 'businessBelongName',
			display : '归属公司',
			sortable : true,
			width : 80
		}, {
			display : '录入人',
			name : 'createName',
			sortable : true
		}, {
			display : '状态',
			name : 'status',
			datacode : 'DKZT',
			sortable : true,
			width : 80
		}, {
			display : '录入时间',
			name : 'createTime',
			sortable : true,
			width : 120
		}],
        buttonsEx : [{
			name : 'Add',
			text : "导入",
			icon : 'edit',
			action : function(row) {
				showThickboxWin("?model=finance_income_income&action=toExcel"
			          + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600")
			}
		}],
		toAddConfig:{
			toAddFn : function(p) {
				showOpenWin("?model=finance_income_income&action=toAdd&formType=YFLX-TKD");
			}
		},
		// 扩展右键菜单
		menusEx : [
			{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row)
					showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + "&perm=view"
                        + '&skey=' + row['skey_'] );
			}
		}, {
			text : '编辑',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row)
					showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_'] );
			}
		}, {
			name : 'delete',
			text : '删除',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_income_income&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#incomeGrid").yxgrid("reload");
								}
							}
						});
					}
				} else {
					alert("请选中一条数据");
				}
			}
		}],
        // 过滤数据
        comboEx : [{
            text : '状态',
            key : 'status',
            datacode : 'DKZT'
        }],
		searchitems : [{
			display : '客户名称',
			name : 'incomeUnitName'
		},{
			display : '客户省份',
			name : 'province'
		},{
			display : '进账单号',
			name : 'inFormNum'
		}]
	});
});