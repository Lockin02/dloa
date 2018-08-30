var show_page = function(page) {
	$("#basicGrid").yxgrid("reload");
};
$(function() {
	$("#basicGrid").yxgrid({
		model : 'outsourcing_approval_basic',
		title : '外包立项',
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
			name : 'isAllAddContract',
			display : '生成合同',
            width:65,
			sortable : true,
			process : function(v){
				if(v == 1){
					return '是';
				}else{
					return '否';
				}
			}
		},	{
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
            width:80,
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
		toViewConfig : {
			action : 'toView'
		},

    menusEx:[{
            text : "查看",
            icon : 'view',
            action : function(row) {
                    showModalWin("?model=outsourcing_approval_basic&action=toViewTab&id="+row.id,'1');
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_approval&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
            text: '生成合同',
            icon: 'add',
            showMenuFn: function(row) {
                if (row.isAllAddContract != 0) {
                    return false;
                }else
                	return true;
            },
            action: function(row) {
            	if(row.outsourcingName == '整包' || row.outsourcingName == 'HTWBFS-03'){
                        showModalWin("?model=contract_outsourcing_outsourcing&action=toAddForApproval&projectId="
                        	+row.id
                        	+"&projectCode="
                        	+row.projectCode
                        	+"&projectName="
                        	+row.projectName
                        	+"&orderMoney="
                        	+row.outSuppMoney
                        	+"&signCompanyName="
                        	+row.suppName
                        	+"&projectType="
                        	+row.projectType
                        	+"&outsourcing="
                        	+row.outsourcing
                        	+"&outsourceType=HTWB03"
                        	+"&payType="
                        	+row.payType
                    	);
            	}else{
            		showModalWin("?model=contract_outsourcing_outsourcing&action=toChooseAdd&projectId="
            				+row.id+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false");
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