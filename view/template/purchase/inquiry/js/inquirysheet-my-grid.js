/**我的询价单 保存列表
 *2010-12-28 can
 */

var show_page = function(page) {
	$("#inquirysheetyMyGrid").yxsubgrid("reload");
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
	$("#inquirysheetyMyGrid").yxsubgrid({
		isTitle:true,
		title:'我的采购询价单',
		isToolBar : true,
		isAddAction:false,
		isViewAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:true,
		menuWidth : 130,
		model : 'purchase_inquiry_inquirysheet',
		action : 'myPageJson',
		param:{states:'0,1,2,4'},


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

		comboEx:[{
			text:'询价单状态',
			key:'state',
			data:[{
			   text:'保存',
			   value:'0'
			},{
			   text:'待指定',
			   value:'1'
			},{
			   text:'已指定',
			   value:'2'
			},{
			   text:'已关闭',
			   value:'3'
			},{
			   text:'已生成订单',
			   value:'4'
			}]
		},{
			text:'审批状态',
			key:'ExaStatus',
			data:[{
			   text:'未提交',
			   value:'未提交'
			},{
			   text:'部门审批',
			   value:'部门审批'
			},{
			   text:'完成',
			   value:'完成'
			}]
		}],

		//扩展按钮
		buttonsEx : [{
			name : 'return',
			text : '生成订单',
			icon : 'add',
			action : function(row, rows, grid) {
				if(rows){
					var checkedRowsIds=$("#inquirysheetyMyGrid").yxsubgrid("getCheckedRowIds");  //获取选中的id
//					$.showDump(rows);
					var states=[];   //采购询价单状态数组
					var suppIds=[];//指定供应商数组
					$.each(rows,function(i,n){
	        			var o = eval( n );
						suppIds.push(o.suppId);
						states.push(o.state);
					});
					suppIds.sort();
					var uniqueId=uniqueArray(suppIds);
					var idLength=uniqueId.length;
					states.sort();
					var uniqueState=uniqueArray(states);
					var stateLength=uniqueState.length;
					if(stateLength==1&&uniqueState[0]==2){  //判断单据的状态是否为“已指定”并且只有一种状态
						if(idLength==1&&uniqueId[0]!=""){ //判断是否为同一供应商
							$.ajax({				//ajax判断下达的物料是否为同一采购类型
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_equmentInquiry&action=isSameType",
			    		         data:{
			    		         	parentIds:checkedRowsIds
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
										parent.location = "index1.php?model=purchase_contract_purchasecontract&action=toAddByMore&inquiryId="+checkedRowsIds+ "&suppId=" +uniqueId[0];
			    		            }else{
			    		            	alert("不能同时下达不同类型的采购");
			    		            }
			    		         }
			    		     });
						}else{
							alert("请选择供应商相同的的单据");
						}
					}else{
						alert("请选择询价单状态为'已指定'的单据");
					}


				}else{
					alert("请选择需要生成订单的单据。");
				}

			}
		},{
			name : 'return',
			text : '关闭',
			icon : 'delete',
			action : function(row, rows, grid) {
				if(rows){
					var checkedRowsIds=$("#inquirysheetyMyGrid").yxsubgrid("getCheckedRowIds");  //获取选中的id
						if(confirm("确定要关闭吗？")){
							$.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=closeBatch",
			    		         data:{
			    		         	ids:checkedRowsIds
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
			    		                alert('关闭成功!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
						}
				}else{
					alert("请选择要关闭的单据");
				}

			}
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
						 parent.location = "?model=purchase_inquiry_inquirysheet&action=toRead&id="+ row.id+"&skey="+row['skey_'];
			   		}
			   }
			},
			{
			 	text:'修改',
			 	icon:'edit',
			 	showMenuFn:function(row){
			 	   if(row.state==0||row.state==1&&row.ExaStatus=="未提交"||row.ExaStatus=="打回"){
			 	   		return true;
			 	   }
			 	   return false;
			 	},
			 	action:function(row,rows,grid){
			 	   if(row){
						parent.location = "?model=purchase_inquiry_inquirysheet&action=init&id="+ row.id+"&skey="+row['skey_'];
			 	   }
			 	}
			},
			{
			    text:'删除',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if(row.state==0||row.state==1&&row.ExaStatus=="未提交"){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("确认要删除?")){
			    		     $.ajax({
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_inquirysheet&action=deletesInfo",
			    		         data:{
			    		         	id:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
//			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
			    		                alert('删除成功!');
			    		                show_page();
			    		            }
			    		         }
			    		     });
//			    		     location='?model=purchase_inquiry_inquirysheet&action=deletesInfo&id='+row.id;
			    		}
			    	}
			    }
			},
			{
			    text:'退回任务',
			    icon:'delete',
			    showMenuFn:function(row){
			    	if((row.state==1&&row.ExaStatus=="打回")||(row.state==2)){
			    	   return true;
			    	}
			    	return false;
			    },
			    action:function(row,rows,grid){
			    	if(row){
			    		if(window.confirm("确定要退回?")){
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
			},
//				{
//				text:'提交',
//				icon:'edit',
//				showMenuFn:function(row){
//					if(row.state==0){
//						return true;
//					}
//					return false;
//				},
//				action:function(row,rows,grid){
//					if(row){
//						if(confirm("确定要提交吗？")){
//							 $.ajax({
//			    		         type:"POST",
//			    		         url:"?model=purchase_inquiry_inquirysheet&action=putInquiry",
//			    		         data:{
//			    		         	parentId:row.id
//			    		         },
//			    		         success:function(msg){
//			    		            if(msg==1){
////			    		                $("#inquirysheetyMyGrid").yxgrid("reload");
//			    		                alert('提交成功!');
//			    		                show_page();
//			    		            }
//			    		         }
//			    		     });
////						 	location = "?model=purchase_inquiry_inquirysheet&action=putInquiry&parentId="+ row.id;
//						}
//					}
//				}
//			},
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
			},
				{
			   text:'关闭',       //在保存和待指定的状态下关闭询价单，还原采购任务设备数量
			   icon:'delete',
			   showMenuFn:function(row){
			   		if(row.state!=3&&row.ExaStatus!="部门审批"&&row.ExaStatus!="未提交"){
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
			},
				{
			name : 'sumbit',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未提交'&&row.state!=3 ||row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					parent.location = 'controller/purchase/inquiry/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ '&examCode=oa_purch_inquiry&formName=采购询价单审批';
				} else {
					alert("请选中一条数据");
				}
			}
		},
				{
			name : 'sumbit',
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
					if(confirm('确定要撤消审批吗？')){
					parent.location = 'controller/purchase/inquiry/ewf_index.php?actTo=delWork&billId='
							+ row.id
							+ '&examCode=oa_purch_inquiry&formName=采购询价单审批';
					}
				} else {
					alert("请选中一条数据");
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
		},{
				text:'生成采购订单',
				icon:'add',
				showMenuFn:function(row){

					//格式化日期时间方法
//					Date.prototype.format = function(format){
//							var o = {
//								"M+" : this.getMonth()+1, //month
//								"d+" : this.getDate(), //day
//								"h+" : this.getHours(), //hour
//								"m+" : this.getMinutes(), //minute
//								"s+" : this.getSeconds(), //second
//								"q+" : Math.floor((this.getMonth()+3)/3), //quarter
//								"S" : this.getMilliseconds() //millisecond
//							}
//							if(/(y+)/.test(format)) {
//								format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
//							}
//
//							for(var k in o) {
//								if(new RegExp("("+ k +")").test(format)) {
//									format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
//								}
//							}
//							return format;
//
//					}
//
//				    var date1=new Date();
//					var date2=date1.format("yyyy-MM-dd");
					if(row.ExaStatus=="完成"&&row.state==2){
					   return true;
					}
					return false;
				},
				action:function(row,rows,grid){

				   if(row){
							$.ajax({				//ajax判断下达的物料是否为同一采购类型
			    		         type:"POST",
			    		         url:"?model=purchase_inquiry_equmentInquiry&action=isSameType",
			    		         data:{
			    		         	parentIds:row.id
			    		         },
			    		         success:function(msg){
			    		            if(msg==1){
										parent.location = "?model=purchase_contract_purchasecontract&action=toAddPurchaseContract&inquiryId="+ row.id + "&suppId=" + row.suppId+"&skey="+row['skey_'];
			    		            }else{
			    		            	alert("不能同时下达不同类型的采购");
			    		            }
			    		         }
			    		     });
				   }
				}
			}]
	});
});