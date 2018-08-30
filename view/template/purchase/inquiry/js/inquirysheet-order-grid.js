/**采购询价单 列表
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquiryOrderGrid").yxsubgrid("reload");
};
$(function() {
	var idArr=$("#idArr").val();
	$("#inquiryOrderGrid").yxsubgrid({
		isTitle:true,
		title:'采购询价单',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		param:{"idArr":idArr},
		action : 'pageJson',

			// 列信息
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '询价单编号',
				name : 'inquiryCode',
				sortable : true
			}, {
				display : '审批状态',
				name : 'ExaStatus',
//				sortable : true,
				width:60
			}, {
				display : '询价单状态',
				name : 'stateName',
				sortable : true,
				width:60
			}, {
				display : '采购员',
				name : 'purcherName',
				sortable : true
			}, {
				display : '询价日期',
				name : 'inquiryBgDate',
				sortable : true
			}, {
				display : '报价截止日期',
				name : 'inquiryEndDate',
				sortable : true
			}, {
				display : '生效日期',
				name : 'effectiveDate',
				sortable : true,
				hide : true
			}, {
				display : '失效日期',
				name : 'expiryDate',
				sortable : true,
				hide : true
			}, {
				display : '指定供应商',
				name : 'suppName',
				sortable : true
			},
			{
				display : '供应商ID',
				name : 'suppId',
				sortable : true,
				hide : true
			},
			{
				display : '指定人名称',
				name : 'amaldarName',
				sortable : true
			}],
			// 主从表格设置
			subGridOptions : {
				url : '?model=purchase_inquiry_equmentInquiry&action=pageJson',
				param : [{
							paramId : 'parentId',
							colId : 'id'
						}],
				colModel : [{
							name : 'productNumb',
							display : '物料编号'
						}, {
							name : 'productName',
							width : 200,
							display : '物料名称'
						},{
							name : 'pattem',
							display : "规格型号"
						},{
							name : 'units',
							display : "单位"
						},{
							name : 'amount',
							display : "询价数量"
						},{
							name : 'purchTypeCn',
							display : "采购类型"
						}]
			},
			searchitems : [{
				display : '询价单编号',
				name : 'inquiryCode'
			}],

		//扩展右键
		menusEx:[
			{  text:'查看',    //已指定的查看
			   icon:'view',
			   showMenuFn:function(row){
					if(row.ExaStatus=="完成"){
						return true;
					}
					return false;
				},
			   action:function(row,rows,grid){
			   		if(row){
						showThickboxWin("?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_']
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
			   		}
			   }
			},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}
		]
	});
});