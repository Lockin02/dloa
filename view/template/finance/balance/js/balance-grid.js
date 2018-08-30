var show_page = function(page) {
	$("#balanceGrid").yxgrid("reload");
};

$(function() {
	var formTypeVal = $("#formTypeVal").val();
	var periodStr = "";

	$("#balanceGrid").yxgrid({
    	model : 'finance_balance_balance',
    	param : { "formType" : $("#formType").val()},
    	title : '期初余额表 - ' + $("#formTypeCN").val() ,
    	isEditAction :false,
    	isDelAction : false,
    	sortorder : 'asc',
    	//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'thisYear',
				display : '会计年度',
				sortable : true
			}, {
				name : 'thisMonth',
				display : '会计月份',
				sortable : true
			}, {
				name : 'objectName',
				display : '客户/供应商',
				sortable : true,
				width : 150
			}, {
				name : 'directionsName',
				display : '借贷方向',
				sortable : true
			}, {
				name : 'needPay',
				display : '本期应' + formTypeVal,
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'payed',
				display : '本期实' + formTypeVal,
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'balance',
				display : '期末余额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'thisDate',
				display : '日期',
				sortable : true
			}, {
				name : 'isUsing',
				display : '是否在使用',
				sortable : true,
				process : function(v,row){
					if(v == 1){
						if(periodStr == ""){
							if(row.thisMonth < 10){
								periodStr = row.thisYear + '0' + row.thisMonth;
							}else{
								periodStr = row.thisYear + row.thisMonth;
							}
							return '<input type="hidden" id="isUsing" value="' + periodStr + '"/><span class="red">是</span>';
						}
						return '<span class="red">是</span>';
					}else{
						return '否';
					}
				}
			}
		],
		toAddConfig : {
			plusUrl : '&formType=' + $("#formType").val()
		},
		menusEx : [
			{
				text : '编辑',
				icon : 'edit',
				showMenuFn : function(row) {
					isUsing = $("#isUsing").val();
					if(row.thisMonth < 10){
						thisVal = row.thisYear + '0' + row.thisMonth;
					}else{
						thisVal = row.thisYear + row.thisMonth;
					}
					if (isUsing*1 <= thisVal*1) {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (row)
						showThickboxWin("?model=finance_balance_balance"
								+ "&action=init"
								+ "&id="
								+ row.id
								+ "&formType" + $("formType").val()
								+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400"
								+ "&width=800");
				}
			}
			,{
				name : 'delete',
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					isUsing = $("#isUsing").val();
					if(row.thisMonth < 10){
						thisVal = row.thisYear + '0' + row.thisMonth;
					}else{
						thisVal = row.thisYear + row.thisMonth;
					}
					if (isUsing*1 < thisVal*1) {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (row) {
						if (window.confirm(("确定要删除?"))) {
							$.ajax({
								type : "POST",
								url : "?model=finance_balance_balance&action=ajaxdeletes",
								data : {
									id : row.id
								},
								success : function(msg) {
									if (msg == 1) {
										alert('删除成功！');
										$("#balanceGrid").yxgrid("reload");
									}else{
										alert('删除失败！');
									}
								}
							});
						}
					} else {
						alert("请选中一条数据");
					}
				}
			}
		],
		buttonsEx : [
			{
				text : '结算',
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("确定要结算?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_balance_balance&action=checkout",
							data :{ "formType" : $("#formType").val() },
							success : function(msg) {
								if (msg == 1) {
									alert('结算成功！');
									$("#balanceGrid").yxgrid("reload");
								}else if(msg = 2){
									alert('请先进行余额核算');
								}else{
									alert('结算失败！');
								}
							}
						});
					}
				}
			},
			{
				text : '余额核算',
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("确定进行余额核算吗?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_balance_balance&action=balanceCount",
							data :{ "formType" : $("#formType").val() },
							success : function(msg) {
								if (msg == 1) {
									alert('余额核算成功！');
									$("#balanceGrid").yxgrid("reload");
								}else{
									alert('余额核算失败!请检查是否已录入初始余额');
								}
							}
						});
					}
				}
			},
			{
				text : '反结算',
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("反结算会启用上期的期初余额，确定反结算吗?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_balance_balance&action=unCheckout",
							data :{ "formType" : $("#formType").val() },
							success : function(msg) {
								if (msg == 1) {
									alert('反结算成功！');
									$("#balanceGrid").yxgrid("reload");
								}else if(msg = 2){
									alert('当前财务期是初始化期或者系统未进行初始化，不能反结算！');
								}else{
									alert('反结算失败！');
								}
							}
						});
					}
				}
			}
		],
		comboEx:
		[
			{
				text: "使用状态",
				key: 'isUsing',
				data : [
					{
						text : '是',
						value : 1
					},
					{
						text : '否',
						value : 0
					}
				]
			}
		]
	});
});