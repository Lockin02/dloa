/** 资产遗失信息列表
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_lose',
		title : '资产遗失',
		isToolBar : true,
		//isViewAction : false,
		//isEditAction : false,
		//isAddAction : false,
		  isDelAction : false,
		  showcheckbox : false,

		colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            },{
                display : '遗失单编号',
                name : 'billNo',
                sortable : true,
				width : 120
            },{
                display : '申请日期',
                name : 'loseDate',
                sortable : true
            },{
                display : '申请部门id',
                name : 'deptId',
                sortable : true,
                hide : true
            },{
                display : '申请部门名称',
                name : 'deptName',
                sortable : true
            },{
                display : '申请人Id',
                name : 'applicatId',
                sortable : true,
                hide : true
            },{
                display : '申请人',
                name : 'applicat',
                sortable : true
            },{
                display : '应赔付数量',
                name : 'loseNum',
                sortable : true
            },{
                display : '应赔付金额',
                name : 'loseAmount',
                sortable : true,
                //列表格式化千分位
                process : function(v){
					return moneyFormat2(v);
				}
            },{
                display : '确认赔付金额',
                name : 'realAmount',
                sortable : true,
                //列表格式化千分位
                process : function(v){
					return moneyFormat2(v);
				}
            },{
                display : '遗失原因',
                name : 'reason',
                width : 200,
                hide : true,
                sortable : true
            },{
                display : '审批状态',
                name : 'ExaStatus',
                sortable : true
            },{
                display : '审批时间',
                name : 'ExaDT',
                sortable : true
            },{
                display : '备注',
                name : 'remark',
                sortable : true
            }],
            // 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_daily_loseitem&action=pageJson',
			param : [{
				paramId : 'loseId',
				colId : 'id'
			}],
			colModel : [
				{
					display:'卡片编号',
					name : 'assetCode',
					width : 130
				}, {
					display:'资产名称',
					name : 'assetName'
				}, {
					display : '规格型号',
					name : 'spec',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '购置日期',
					name : 'buyDate',
					//type : 'date',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '所属部门',//orgName
					name : 'orgName',
					tclass : 'txt',
					readonly:true
				}, {
					display : '使用部门',//useOrgName
					name : 'useOrgName',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'附属设备',
					name : 'equip',
					type:'statictext',
                    process : function(e, data){
                    return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='+data.assetCode+'\')">详细</a>'
				     }
				}, {
					display : '购进原值',
					name : 'origina',
					tclass : 'txt',
					readonly:true
				}, {
					display : '已经使用期间数',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				}, {
					display : '累计折旧金额',
					name : 'depreciation',
					tclass : 'txtmiddle',
					readonly:true,
               		 //列表格式化千分位
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '残值',
					name : 'salvage',
					tclass : 'txtmiddle',
					readonly:true,
               		 //列表格式化千分位
               		 process : function(v){
					return moneyFormat2(v);
					}
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
		},
            toAddConfig : {
								formWidth : 1050,
								formHeight :700
							},
			toEditConfig : {
								formWidth : 1050,
								formHeight : 700,
								showMenuFn : function(row) {
				   					 if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
										return true;
											}
				  						return false;
			      					}
							},
            toViewConfig : {
								formWidth : 1050,
								formHeight : 350
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
						showThickboxWin('controller/asset/daily/ewf_index_lose.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("请选中一条数据");
				}
			}

		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="完成"||row.ExaStatus=="打回" || row.ExaStatus == "部门审批")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_lose&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		},{
			name : 'aduit',
			text : '填写报废资产',
			icon : 'add',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="完成")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_disposal_scrap&action=toAdd&loseId="
							+ row.id
							+"&type=lose"
							+"&loseBillNo="
							+row.billNo
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000");
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
						url : "?model=asset_daily_lose&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '遗失单编号',
			name : 'billNo'
		},{
			display : '申请人',
			name : 'applicat'
		},{
			display : '申请部门',
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
		// 业务对象名称
		//	boName : '全部',
		// 默认搜索字段名
			sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
			sortorder : "DESC"


	});
});
