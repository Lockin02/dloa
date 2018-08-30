var show_page = function(page) {
	$("#basicGrid").yxgrid("reload");
};
$(function() {
	$("#basicGrid").yxgrid({
		model : 'outsourcing_account_basic',
		title : '外包结算',
        isViewAction:false,
        isAddAction:false,
        isDelAction:false,
        isEditAction:false,
        isOpAction:false,
        showcheckbox:false,
		bodyAlign:'center',
        param:{ExaStatusArr:'部门审批,完成,打回'},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
            width:155,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_account_basic&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'approvalCode',
			display : '外包立项编号',
            width:155,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_approval_basic&action=toViewTab&id=" + row.approvalId +"\",1)'>" + v + "</a>";
			}
		},  {
			name : 'projectCode',
			display : '项目编号',
            width:125,
			sortable : true
		}, {
			name : 'projectName',
			display : '项目名称',
            width:125,
			sortable : true
		},{
			name : 'outsourcingName',
			display : '外包方式',
            width:65,
			sortable : true
		},{
			name : 'outContractCode',
			display : '外包编号',
            width:125,
			sortable : true
		},  {
			name : 'suppName',
			display : '外包供应商',
            width:125,
			sortable : true
		},{
			name : 'projectTypeName',
			display : '项目类型',
            width:55,
			sortable : true
		},  {
			name : 'saleManangerName',
			display : '销售负责人',
            width:105,
			sortable : true
		}, {
			name : 'projectManangerName',
			display : '项目经理',
            width:105,
			sortable : true
		},  {
			name : 'payTypeName',
			display : '付款方式',
            width:55,
			sortable : true
		}, {
			name : 'taxPoint',
			display : '增值税专用发票税点',
            width:105,
			sortable : true
		},  {
			name : 'ExaStatus',
			display : '审批状态',
            width:65,
			sortable : true
		}, {
			name : 'createName',
			display : '申请人',
            width:55,
			sortable : true
		}, {
			name : 'createTime',
			display : '录入日期',
            width:120,
			sortable : true
		}],
				//下拉过滤
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
					text : '部门审批',
					value : '部门审批'
				},{
					text : '完成',
					value : '完成'
				},{
					text : '打回',
					value : '打回'
				}]
			},{
			text : '外包方式',
			key : 'outsourcing',
			datacode : 'HTWBFS'
			}
		],
	//表头按钮
	buttonsEx : [{
		name : 'exportOut',
		text : '导出',
		icon : 'excel',
		action : function(){
				showThickboxWin("?model=outsourcing_account_basic&action=toExportOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	}],
    menusEx:[{
            text : "查看",
            icon : 'view',
            action : function(row) {
                    showModalWin("?model=outsourcing_account_basic&action=toView&id="+row.id,'1');
             }
        },{
					name : 'aduit',
					text : '审批情况',
					icon : 'view',
					showMenuFn : function(row) {
						if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row) {
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_account&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
		}
//		,{
//				name : 'payapply',
//				text : '申请付款',
//				icon : 'add',
//				showMenuFn : function(row) {
//					if (row.ExaStatus == "完成"){
//						return true;
//					}
//					else
//						return false;
//				},
//				action : function(row, rows, grid) {
//					$.ajax({
//						type : "POST",
//						url : "?model=outsourcing_account_basic&action=getCanApply",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 0) {
//								alert('可申请金额为0,不能再申请付款');
//							    return false;
//							}else{
//								showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-05&objId=" + row.id);
//							}
//						}
//					});
//				}
//
//		}
	],
		searchitems : [{
							display : "单据编号",
							name : 'formCode'
						},{
							display : "外包立项编号",
							name : 'approvalCode'
						},{
							display : "项目编号",
							name : 'projectCode'
						},{
							display : "项目名称",
							name : 'projectName'
						},{
							display : "外包编号",
							name : 'outContractCode'
						},{
							display : "外包供应商",
							name : 'suppName'
						},{
							display : "项目类型",
							name : 'projectTypeName'
						},{
							display : "销售负责人",
							name : 'saleManangerName'
						},{
							display : "项目经理",
							name : 'projectManangerName'
						}]
	});
});