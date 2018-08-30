/** 到款列表* */
var show_page = function(page) {
	$("#payablesGrid").yxgrid("reload");
};

$(function() {
	$("#payablesGrid").yxgrid({
		model : 'finance_payables_payables',
		param : {"formType" : $("#formType").val()},
		title : '应付预付款管理',
		isToolBar : true,
		showcheckbox : true,
//		isDelAction : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '单据编号',
			name : 'formNo',
			sortable : true,
			width : 120,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=finance_payables_payables&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			display : '单据日期',
			name : 'formDate',
			width : 80
		}, {
			display : '供应商名称',
			name : 'supplierName',
			width : 160
		}, {
			display : '单据金额',
			name : 'amount',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '本位币金额',
			name : 'amountCur',
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
            name: 'currency',
            display: '付款币种',
            sortable: true,
            width: 60
        }, {
            name: 'rate',
            display: '汇率',
            sortable: true,
            width: 60
        }, {
			display : '结算方式',
			name : 'payType',
			datacode : 'CWFKFS',
			width : 70
		}, {
			display : '录入人',
			name : 'createName',
			width : 80
		}, {
			display : '录入时间',
			name : 'createTime',
			width : 140
		}, {
			display : '状态',
			name : 'status',
			datacode : 'YFDZT',
			width : 90,
			hide : true
		}, {
			display : '关联付款申请号',
			name : 'payApplyNo',
			width : 140
		}, {
			display : '归属公司',
			name : 'businessBelongName',
			width : 80
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '下推退款单',
			icon : 'edit',
			action : function(row, rows, grid) {
		   		if(row.status == 1){
		   			alert('已经全部下推，不能继续进行该操作');
		   			return false;
		   	    }else{
					showThickboxWin("?model=finance_payables_payables"
						+ "&action=toAddRefund"
						+ "&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
						+ "&width=900");
		   	    }
			}
		}],
		toAddConfig : {
			formWidth : 900,
			formHeight : 500,
			plusUrl : '&formType=' + $("#formType").val()
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 500,
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//添加编辑限制
					if(rowData['payApplyNo'] != ''){
						alert('付款申请下推的付款单不能进行编辑操作');
						return false;
					}
					//编辑操作
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&id="
							+ rowData[p.keyField]
							+ keyUrl
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 500
		},
		toDelConfig : {
			text : '删除',
			/**
			 * 默认点击删除按钮触发事件
			 */
			toDelFn : function(p, g) {
				var rowIds = g.getCheckedRowIds();
				var rowObj = g.getFirstSelectedRow();
				var key = "";
				if (rowObj) {
					var rowData = rowObj.data('data');
					if (rowData['skey_']) {
						key = rowData['skey_'];
					}
				}
				if (rowIds[0]) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=" + p.model + "&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds()
										.toString(),
								skey : key
							},
							success : function(msg) {
								if(msg == 1){
									alert('删除成功');
									show_page();
								}else if(msg == 0){
									alert('删除失败');
								}else{
									alert('单据：' + msg + '已下推生成退款单，不能删除');
									show_page();
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			},
			action : 'ajaxDelForPayment'
		},
		searchitems : [{
			display : '供应商名称',
			name : 'supplierName'
		},{
			display : '付款申请单号',
			name : 'payApplyNoSearch'
		},{
			display : '单据编号',
			name : 'formNoSearch'
		},{
			display : '单据日期',
			name : 'formDateSearch'
		},{
			display : '源单编号',
			name : 'objCodeSearchDetail'
		},{
			display : '单据金额',
			name : 'amount'
		}],
		sortname : 'updateTime'
	});
});