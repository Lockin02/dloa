/** 清理记录信息列表
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};
$(function() {
	$("#datadictList").yxgrid({
		model : 'asset_assetcard_clean',
		title : '清理记录',
		showcheckbox : false,
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '原单编号',
			name : 'businessNo',
			sortable : true,
			width : 120,
			process : function(v, row) {
				if (row.businessId) {
					switch (row.businessType) {
						case 'scrap' :
							return '<a href="#" onclick="javascript:window.open(\'?model=asset_disposal_scrap&action=init&perm=view&id='
									+ row.businessId
									+ '\')">'
									+ row.businessNo
									+ '</a>';
							break;
						case 'sell' :
							return '<a href="#" onclick="javascript:window.open(\'?model=asset_disposal_sell&action=init&perm=view&id='
									+ row.businessId
									+ '\')">'
									+ row.businessNo
									+ '</a>';
							break;
						default :
							break;
					}
				}
			}
		}, {
			display : '资产类别',
			name : 'assetType',
			sortable : true
		}, {

			display : '资产id',
			name : 'assetId',
			sortable : true,
			hide : true
		}, {
			display : '卡片编号',
			name : 'assetCode',
			sortable : true,
			width : 260,
			process : function(v, row) {
				return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.assetId + '\')">' + row.assetCode + '</a>';
			}
		}, {
			display : '资产名称',
			name : 'assetName',
			sortable : true
		}, {
			display : '业务类型',
			name : 'businessType',
			sortable : true,
			process : function(val) {
				if (val == "sell") {
					return "出售单资产";
				}
				if (val == "scrap") {
					return "报废单资产";
				}
			}
		}, {
			display : '业务单id',
			name : 'businessId',
			sortable : true,
			hide : true
		}, {
			display : '清理日期',
			name : 'cleanDate',
			sortable : true
		}, {
			display : '清理费用',
			name : 'cleanFee',
			sortable : true,
			//列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '残值收入',
			name : 'salvageFee',
			sortable : true,
			//列表格式化千分位
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '审批时间',
			name : 'ExaDT',
			sortable : true
		}, {
			display : '期间',
			name : 'period',
			sortable : true,
			hide : true
		}, {
			display : '年度',
			name : 'years',
			sortable : true,
			hide : true
		}, {
			display : '摘要',
			name : 'explanation',
			sortable : true
		}],

		// 扩展右键菜单
		menusEx : [{
			name : 'aduit',
			text : '查看清理信息',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_assetcard_clean&action=init&perm=view&id='
									+ row.id
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=900');
				}
			}
		},{
			name : 'aduit',
			text : '查看资产卡片',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					window
							.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
									+ row.assetId
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}
			}
		},{
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
						showThickboxWin('controller/asset/assetcard/ewf_index_clean.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}
		},{
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
							examCode : 'oa_asset_clean'
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
									    url: "controller/asset/assetcard/ewf_index_clean.php?actTo=delWork&billId=",
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_clean&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "完成" || row.ExaStatus == "待提交"|| row.ExaStatus == "打回")) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_assetcard_clean&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '原单编号',
			name : 'businessNo'
		}, {
			display : '卡片编号',
			name : 'assetCode'
		}, {
			display : '资产名称',
			name : 'assetName'
		}],
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC"

	});
});
