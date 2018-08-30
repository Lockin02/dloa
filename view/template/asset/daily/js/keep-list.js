/** 资产维保信息列表
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_keep',
		title : '资产维保',
		showcheckbox : false,
//		isToolBar : true,
		//isViewAction : false,
		//isEditAction : false,
		//isAddAction : false,
		isDelAction:false,


		colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },
            {
                display : '维保单编号',
                name : 'billNo',
                sortable : true,
				width : 120
            },
//            {
//                display : '维保申请日期',
//                name : 'applyDate',
//                sortable : true
//            },
            {
                display : '维保部门Id',
                name : 'deptId',
                sortable : true,
                hide : true
            },
            {
                display : '维保部门名称',
                name : 'deptName',
                sortable : true
            },
            {
                display : '维保申请人Id',
                name : 'keeperId',
                sortable : true,
                hide : true
            },
            {
                display : '维保申请人',
                name : 'keeper',
                sortable : true
            },{
                display : '维保总金额',
                name : 'keepAmount',
                sortable : true,
                //列表格式化千分位
                process : function(v){
					return moneyFormat2(v);
				}
            },{
                display : '维保类型',
                name : 'keepType',
                sortable : true,
                 process : function(val) {
				  if (val == "1") {
					return "日常维修";
				   }
                  if(val=="2"){
					return "普通维修";
				   }
                   if(val=="3") {
				    return "重大维修";
				   }
			      }

            },{
                display : '维保时间',
                name : 'keepDate',
                sortable : true
            },{
                display : '审批状态',
                name : 'ExaStatus',
                sortable : true
            },
            {
                display : '审批时间',
                name : 'ExaDT',
                sortable : true
            },
            {
                display : '备注',
                name : 'remark',
                sortable : true
            }],
		// 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_daily_keepitem&action=pageJson',
			param : [{
				paramId : 'keepId',
				colId : 'id'
			}],
			colModel : [{
			display : '卡片编号',
			name : 'assetCode',
			width : 130
		}, {
			display : '资产名称',
			name : 'assetName'
		}, {
			display : '维修金额',
			name : 'amount',
			tclass : 'txtmiddle',
			type : 'money'
		}, {
			display : '使用人',
			name : 'userName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
		},
            toAddConfig : {
								formWidth : 900,
								formHeight : 400
							},
			toEditConfig : {
								formWidth : 900,
								formHeight : 400,
								showMenuFn : function(row) {
				   					 if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
										return true;
											}
				  						return false;
			      					}
							},
            toViewConfig : {
								formWidth : 900,
								formHeight : 300
							},
		// 扩展右键菜单
		menusEx : [{
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
						showThickboxWin('controller/asset/daily/ewf_index_keep.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			name : 'cancel',
			text : '撤消审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_keep'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤销审批！');
						    	show_page();
								return false;
							}else{
								if(confirm('确定要撤消审批吗？')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/daily/ewf_index_keep.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="完成"||row.ExaStatus=="打回"|| row.ExaStatus == "部门审批")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_keep&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "GET",
						url : "?model=asset_daily_keep&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '维保单编号',
			name : 'billNo'
		},{
			display : '维保申请人',
			name : 'keeper'
		},{
			display : '维保部门名称',
			name : 'deptName'
		}],
		comboEx : [{
					text : '审批状态',
					key : 'ExaStatus',
					data : [{
								text : '部门审批',
								value : '部门审批'
							}, {
								text : '待提交',
								value : '待提交'
							}, {
								text : '完成',
								value : '完成'
							}, {
								text : '打回',
								value : '打回'
							}]
				}],
		// 默认搜索字段名
			sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
			sortorder : "DESC"


	});
});
