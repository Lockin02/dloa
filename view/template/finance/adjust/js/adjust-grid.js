var show_page = function(page) {
	$("#adjustGrid").yxgrid("reload");
};
$(function() {
	$("#adjustGrid").yxgrid({
		model: 'finance_adjust_adjust',
		title: '补差单',
		showcheckbox : false,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'adjustCode',
			display: '补差单编号',
			sortable: true,
			width : 120
		}, {
			name: 'supplierName',
			display: '供应商名称',
			sortable: true,
			width : 130
		}, {
			name: 'formDate',
			display: '单据日期',
			sortable: true
		}, {
			name: 'relatedId',
			display: '钩稽序号',
			sortable: true
		}, {
			name: 'amount',
			display: '补差金额',
			sortable: true,
			process : function (v){
				return moneyFormat2(v);
			}
		}, {
			name: 'createName',
			display: '创建人',
			sortable: true
		}, {
			name: 'createTime',
			display: '创建时间',
			sortable: true,
			width : 130
		}],
		buttonsEx : [{
			name : 'add',
			text : "打开列表",
			icon : 'search',
			action : function() {
				showModalWin('?model=finance_adjust_adjust&action=listInfo',1);
			}
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=finance_adjust_adjust"
					+ "&action=init"
					+ "&id="
					+ row.id
					+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		}, {
			text: "删除",
			icon: 'delete',
			action: function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_adjust_adjust&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == "1") {
								alert('删除成功！');
								show_page(1);
							}else{
								alert("删除失败! ");
							}
						}
					});
				}
			}
		}],
		searchitems:[{
			display:'供应商名称',
			name:'supplierName'
		}, {
			display:'钩稽序号',
			name:'relatedId'
		}, {
			display:'单据日期',
			name:'formDate'
		}, {
			display:'创建人',
			name:'createName'
		}]
	});
});