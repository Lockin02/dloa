var show_page = function(page) {
	$("#exceptionapplyGrid").yxgrid("reload");
};
$(function() {

	//表头按钮数组
	buttonsArr = [{
			name : 'add',
			text : "新增",
			icon : 'add',
			items : [{
				text : '借款类',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-01"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			},{
				text : '报销类',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-02"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			},{
				text : '请购类',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-03"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			},{
				text : '租车类',
				icon : 'add',
				action : function(v){
					showThickboxWin('?model=engineering_exceptionapply_exceptionapply&action=toAdd'
						+ "&applyType=GCYCSQ-04"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
				}
			}]
		}
	];

	$("#exceptionapplyGrid").yxgrid({
		model : 'engineering_exceptionapply_exceptionapply',
		title : '工程超权限申请',
		isDelAction : false,
		isAddAction : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formNo',
				display : '申请单号',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_exceptionapply_exceptionapply&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=480&width=800\")'>" + v + "</a>";
				},
				width : 80
			}, {
				name : 'applyTypeName',
				display : '申请类型',
				sortable : true,
				width : 65
			}, {
				name : 'applyDate',
				display : '申请日期',
				sortable : true,
				width : 80
			}, {
				name : 'applyMoney',
				display : '申请金额',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'useRangeName',
				display : '适用范围',
				sortable : true,
				width : 80
			}, {
				name : 'projectCode',
				display : '归属项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '归属项目',
				sortable : true,
				width : 120
			}, {
				name : 'applyReson',
				display : '申请原因',
				sortable : true,
				width : 120
			}, {
				name : 'products',
				display : '物件',
				sortable : true,
				hide : true
			}, {
				name : 'rentalType',
				display : '租车性质',
				sortable : true,
				hide : true
			}, {
				name : 'rentalTypeName',
				display : '租车性质名称',
				sortable : true,
				hide : true
			}, {
				name : 'area',
				display : '使用区域',
				sortable : true,
				hide : true
			}, {
				name : 'carNumber',
				display : '车辆数',
				sortable : true,
				hide : true
			}, {
				name : 'remark',
				display : '备注信息',
				sortable : true,
				width : 120
			}, {
				name : 'ExaStatus',
				display : '审核状态',
				sortable : true,
				width : 80
			}, {
				name : 'ExaDT',
				display : '审核日期',
				sortable : true,
				width : 80
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		buttonsEx : buttonsArr,
		menusEx : [{
//			text : '提交审批',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				showThickboxWin('controller/engineering/exceptionapply/ewf_index.php?actTo=ewfSelect&billId='
//					+ row.id
//					+ "&flowMoney=" + row.applyMoney
//					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//			}
//		},{
			text: "删除",
			icon: 'delete',
			showMenuFn : function(row){
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action: function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_exceptionapply_exceptionapply&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
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
		toEditConfig : {
			action : 'toEdit',
			formHeight : 480,
			showMenuFn : function(row){
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 480
		},
        //过滤数据
		comboEx:[{
		     text:'申请类型',
		     key:'applyType',
		     datacode : 'GCYCSQ'
		},{
		     text:'审核状态',
		     key:'ExaStatus',
		     data : [{
		     	'text' : '完成',
		     	'value' : '完成'
		     },{
		     	'text' : '审核中',
		     	'value' : '审核中'
		     },{
		     	'text' : '待提交',
		     	'value' : '待提交'
		     },{
		     	'text' : '打回',
		     	'value' : '打回'
		     }]
		}],
		searchitems : [{
			display : "申请单号",
			name : 'formNoSearch'
		},{
			display : "申请日期",
			name : 'applyDateSearch'
		},{
			display : "归属项目",
			name : 'projectNameSearch'
		},{
			display : "申请金额",
			name : 'applyMoney'
		}],
		sortname : 'createTime'
	});
});