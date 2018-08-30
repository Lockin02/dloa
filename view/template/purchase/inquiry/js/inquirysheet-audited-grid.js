/**已审批的采购询价单 列表
 *2010-5-14 can
 */

var show_page = function(page) {
	$("#inquiryAuditedGrid").yxsubgrid("reload");
};
$(function() {
	$("#inquiryAuditedGrid").yxsubgrid({
		isTitle:true,
		title:'已审批的采购询价单',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		param:{"ExaStatus":"完成"},
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
//				sortable : true,
				width:60
			}, {
				display : '询价单状态',
				name : 'stateName',
				sortable : true,
				width:60
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
				sortable : true
			}, {
				display : '失效日期',
				name : 'expiryDate',
				sortable : true
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

		comboEx:[{
			text:'询价单状态',
			key:'state',
			data:[{
			   text:'已指定',
			   value:'2'
			},{
			   text:'已关闭',
			   value:'3'
			},{
			   text:'已生成订单',
			   value:'4'
			}]
		}],

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
						 location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
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
						location = "?model=purchase_inquiry_inquirysheet&action=toView&id="+ row.id+"&skey="+row['skey_'];
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
		},
				{
				text:'指定供应商',
				icon:'edit',
				showMenuFn:function(row){
					if(row.state=="2"||row.state=="1"&&row.ExaStatus == '完成'){//row.suppId==""&&
						return true;
					}
					return false;
				},
				action:function(row,rows,grid){
					if(row){
						 	location = "?model=purchase_inquiry_inquirysheet&action=toAssignSupp&id="+ row.id+"&type=todiff";
						}
				}
			},
			/**	{
				text:'生成采购订单',
				icon:'add',
				showMenuFn:function(row){

					//格式化日期时间方法
					Date.prototype.format = function(format){
							var o = {
								"M+" : this.getMonth()+1, //month
								"d+" : this.getDate(), //day
								"h+" : this.getHours(), //hour
								"m+" : this.getMinutes(), //minute
								"s+" : this.getSeconds(), //second
								"q+" : Math.floor((this.getMonth()+3)/3), //quarter
								"S" : this.getMilliseconds() //millisecond
							}
							if(/(y+)/.test(format)) {
								format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
							}

							for(var k in o) {
								if(new RegExp("("+ k +")").test(format)) {
									format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
								}
							}
							return format;

					}

				    var date1=new Date();
					var date2=date1.format("yyyy-MM-dd");
					if(row.ExaStatus=="完成"&&row.state==2){
					   return true;
					}
					return false;
				},
				action:function(row,rows,grid){

				   if(row){
	                    location = "?model=purchase_contract_purchasecontract&action=toAddPurchaseContract&inquiryId="+ row.id + "&suppId=" + row.suppId+"&skey="+row['skey_'];
				   }
				}
			},*/
				{
			   text:'关闭',       //在保存和待指定的状态下关闭询价单，还原采购任务设备数量
			   icon:'delete',
			   showMenuFn:function(row){
			   		if(row.state!=3&&row.ExaStatus!="部门审批"){
			   			return true;
			   		}
			   		return false;
			   },
			   action:function(row,rows,grid){
			   		 if(row){
						if(confirm("确定要关闭吗？")){
							$.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=closeMyInquiry",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('关闭成功!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
//						 	location = "?model=purchase_inquiry_inquirysheet&action=closeMyInquiry&id="+ row.id;
						}
					}
			   }
			}
		]
	});
});