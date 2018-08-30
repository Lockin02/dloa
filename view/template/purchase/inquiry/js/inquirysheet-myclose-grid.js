/**我的询价单 保存列表
 *2010-12-28 can
 */

var show_page = function(page) {
	$("#inquirysheetyMyCloseGrid").yxsubgrid("reload");
};

//删除重复项（考虑IE的兼容性问题）
function uniqueArray(a){
	temp = new Array();
	for(var i = 0; i < a.length; i ++){
		if(!contains(temp, a[i])){
			temp.length+=1;
			temp[temp.length-1] = a[i];
		}
	}
	return temp;
	}
	function contains(a, e){
	for(j=0;j<a.length;j++)if(a[j]==e)return true;
	return false;
}

$(function() {
	$("#inquirysheetyMyCloseGrid").yxsubgrid({
		isTitle:true,
		title:'已关闭询价单',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'myPageJson',
		param:{states:'3,5'},

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
				process : function(v, row) {
					if(row.state=="4"||row.state=="3"){
						return v;
					}else{
						return "<font color=blue>" +v+"</font>";
					}
				}
			}, {
				display : '审批状态',
				name : 'ExaStatus',
				sortable : true,
				width:60
			}, {
				display : '询价单状态',
				name : 'stateName',
				sortable : false,
				width:65
			},{
				display : '指定供应商',
				name : 'suppName',
				sortable : true,
				width:180
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
			}, {
				display : '采购员',
				name : 'purcherName',
				sortable : true,
				hide : true
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
				display : '指定供应商',
				name : 'suppName'
			},{
				display : '物料名称',
				name : 'productName'
			},{
				display : '物料编号',
				name : 'productNumb'
			}],
			sortname : 'c.state asc,c.createTime',
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
		//扩展右键
		menusEx:[
			{  text:'查看',
			   icon:'view',
			   showMenuFn:function(row){
			   		if(row.ExaStatus=="未提交"|row.ExaStatus=="部门审批"|row.ExaStatus=="打回"){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		if(row){
						 parent.location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},
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
						parent.location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成'|| row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_inquiry&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
				}
			}
		},
			{
			    text:'退回任务',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.state==3&&row.ExaStatus=="打回"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("确认要退回?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=backToTask",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('退回成功!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
			    		}
			    	}
			    }
			}]
	});
});