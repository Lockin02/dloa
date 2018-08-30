/**待审批的采购询价单 列表
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquirysheetyNoGrid").yxsubgrid("reload");
};
$(function() {
	$("#inquirysheetyNoGrid").yxsubgrid({
		isTitle:true,
		title:'待审批的采购询价单',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:true,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'myAuditPj',

			// 列信息
			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '询价单编号',
				name : 'inquiryCode',
				sortable : true,
				width : 160
			}, {
				display : '采购员',
				name : 'purcherName',
				sortable : true
			}, {
				display : '审批状态',
				name : 'ExaStatus',
//				sortable : true,
				width:60
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
				sortable : true
			}, {
				display : '失效日期',
				name : 'expiryDate',
				sortable : true
			}],
			searchitems : [{
				display : '询价单编号',
				name : 'inquiryCode'
			},{
				display : '采购员',
				name : 'purcherName'
			},{
				display : '物料名称',
				name : 'productName'
			},{
				display : '物料编号',
				name : 'productNumb'
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

		//扩展按钮
		buttonsEx : [{
			name : 'sumbit',
			text : '审批',
			icon : 'edit',
			action : function(row, rows, grid) {
				if(row){
					parent.location = "controller/purchase/inquiry/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_inquiry";
				}else{
					alert("请选择一条记录");
				}

			}
		}],

		//扩展右键
		menusEx:[
			{  text:'查看',
			   icon:'view',
			   showMenuFn:function(row){
			   		if(row.state==0|row.state==1){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		if(row){
						 parent.location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},{
			name : 'sumbit',
			text : '审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
					parent.location = "controller/purchase/inquiry/ewf_index.php?actTo=ewfExam&taskId="+row.taskId+"&spid="+row.spid+"&billId="+row.id+"&examCode=oa_purch_inquiry";
			}
		}]
	});
});