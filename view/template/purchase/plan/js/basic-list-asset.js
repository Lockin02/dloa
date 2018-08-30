// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#assetApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#assetApplyGrid").yxsubgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		model : 'purchase_plan_basic',
		action : 'assetListPageJson',
		title : '资产采购申请列表',
		isToolBar : false,
		showcheckbox : false,
		param:{'purchType':'assets',"ExaStatusArr":"未下达,完成"},

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '采购类型',
					name : 'purchTypeCName',
					sortable : false
				}, {
					display : '采购申请编号',
					name : 'planNumb',
					sortable : true,
					width : 150
				},  {
					display : '状态',
					name : 'ExaStatus',
					sortable : true,
					process : function(v, row) {
						if (row.ExaStatus == '完成') {
							return "已下达";
						} else {
							return "未下达";
						}
					}
				},{
					display : '申请源单据号',
					name : 'sourceNumb',
					sortable : true,
					width:180
				},{
					display : '是否预算内',
					name : 'isPlan',
					sortable : true,
					process : function(v, row) {
						if (row.isPlan == '0') {
							return "否";
						} else {
							return "是";
						}
					}
				}, {
					display : '申请人',
					name : 'createName',
					sortable : true
				}, {
					display : '申请时间 ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : '希望完成时间 ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [  {
						name : 'productCategoryName',
						display : '物料类别',
						width:50
					},{
						name : 'productNumb',
						display : '物料编号'
					}, {
						name : 'productName',
						width : 200,
						display : '物料名称',
						process:function(v,data){
							if(v==""){
								return data.inputProductName;
							}
							return v;
						}
					},{
						name : 'pattem',
						display : "规格型号"
					},{
						name : 'unitName',
						display : "单位",
						width : 50
					},{
						name : 'amountAll',
						display : "申请数量",
						width : 70
					}, {
						name : 'dateIssued',
						display : "申请日期"
					},{
						name : 'dateHope',
						display : "希望完成日期"
					}]
		},

		comboEx:[{
			text:'采购申请状态',
			key:'ExaStatus',
			data:[{
			   text:'未下达',
			   value:'未下达'
			},{
			   text:'已下达',
			   value:'完成'
			}]
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location="?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
				} else {
					alert("请选中一条数据");
				}
			}

		},{
			    text:'下达采购',
			    icon:'add',
			    showMenuFn:function(row){
			    	if(row.ExaStatus=="未下达"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("确认要下达吗?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_plan_basic&action=pushPurch",
			    		         data:{
			    		         	id:row.id,
			    		         	applyNumb:row.planNumb
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('下达成功!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="完成"||row.ExaStatus=="打回")&&(row.purchType=="assets"||row.purchType=="rdproject"||row.purchType=="produce")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// 快速搜索
		searchitems : [{
					display : '采购申请编号',
					name : 'seachPlanNumb'
				},{
					display : '申请人',
					name : 'createName'
				},{
					display : '物料编号',
					name : 'productNumb'
				},{
					display : '物料名称',
					name : 'productName'
				}
		],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "updateTime",
		// 默认搜索顺序
		sortorder : "DESC",
		// 显示查看按钮
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});