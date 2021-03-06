var show_page = function(page) {
	$("#basicGrid").yxgrid("reload");
};
$(function() {
	$("#basicGrid").yxgrid({
		model : 'outsourcing_approval_basic',
		title : '外包立项',
        isAddAction:false,
        isDelAction:false,
        isEditAction:false,
        isViewAction:false,
        isOpAction:false,
        showcheckbox:false,
		bodyAlign:'center',
		param:{"createId":$("#createId").val()},
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
					return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_approval_basic&action=toViewTab&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'applyCode',
			display : '外包申请编号',
            width:155,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.applyId +"\",1)'>" + v + "</a>";
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
					text : '未审批',
					value : '未审批'
				},{
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

    menusEx:[{
            text : "查看",
            icon : 'view',
            action : function(row) {
                    showModalWin("?model=outsourcing_approval_basic&action=toViewTab&id="+row.id,'1');
             }
        },
			{
            text : "编辑",
            icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus =='未审批'||row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
            action : function(row) {
                    showModalWin("?model=outsourcing_approval_basic&action=toEdit&id="+row.id,'1');
           }
        },{
				text : '删除',
				icon : 'delete',
				showMenuFn : function(row) {
					if (row.ExaStatus =='未审批'||row.ExaStatus == '打回') {
						return true;
					}
					return false;
				},
				action : function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_approval_basic&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									$("#basicGrid").yxgrid("reload");
								}
							}
						});
					}
				}

			},{
            text : "提交审批",
            icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus =='未审批'||row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
            action : function(row) {
                showThickboxWin('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId='+ row.id+ '&flowMoney=0&billDept='+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
            }
        },
		        	{
		            text : "立项变更",
		            icon : 'delete',
					showMenuFn : function(row) {
						if (row.ExaStatus == '完成') {
							return true;
						}
						return false;
					},
		            action : function(row) {
		                    showModalWin("?model=outsourcing_approval_basic&action=toChange&id="+row.id,'1');
		             }
		        },
		        	{
		            text : "外包結算",
		            icon : 'add',
					showMenuFn : function(row) {
						if (row.ExaStatus == '完成') {
							return true;
						}
						return false;
					},
		            action : function(row) {
		                    showModalWin("?model=outsourcing_account_basic&action=toAdd&appId="+row.id,'1');
		             }
		        },
		        	{
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
							showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_approval&pid="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
						}
					}
				}
	],
		searchitems : [{
							display : "单据编号",
							name : 'formCode'
						},{
							display : "外包申请编号",
							name : 'applyCode'
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