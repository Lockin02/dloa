// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#managEndGrid").yxsubgrid("reload");
};

//查看订单信息
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+id+"&skey="+skey);
}

//查看付款记录
function viewPay(id,hwapplyNumb,suppId,suppName,skey) {
	showOpenWin("?model=finance_payables_payables&action=toHistory&obj[objId]="+id+"&obj[objCode]="+hwapplyNumb+"&obj[objType]=YFRK-01&obj[supplierId]="+suppId+"&obj[supplierName]="+suppName+"&skey="+skey);
}

//查看发票记录
function viewInvoice(id,hwapplyNumb,suppId,suppName,skey) {
	showOpenWin("?model=finance_invpurchase_invpurchase&action=toHistory&obj[objId]="+id+"&obj[objCode]="+hwapplyNumb+"&obj[objType]=YFRK-01&obj[supplierId]="+suppId+"&obj[supplierName]="+suppName+"&skey="+skey);
}

$(function() {
			$("#managEndGrid").yxsubgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'managEndPageJson',
						title : '执行完毕的订单',
						isToolBar : false,
						showcheckbox : false,
						param:{"stateArr":"5,6"},

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '订单编号',
									name : 'hwapplyNumb',
									sortable : true,
									width : '180',
									process : function(v, row) {
										var skey=row['skey_'];
										if(row.state==6){
											var vStr="<font color=red>" +v+"</font>";
										}else{
											var vStr=v;
										}
										return "<a href='#' title='查看订单详细信息' onclick='viewOrder(\""
												+ row.id
												+"\",\""
												+skey
												+ "\")' >"
												+ vStr
												+ "</a>";
									}
								}
								,{
									display : '采购类型',
									name : 'purchType',
									sortable : true,
									width:110
								},{
									display : '采购负责人',
									name : 'sendName',
									sortable : true,
									width:100
								},{
									display : '订单状态',
									name : 'stateC',
									sortable : true,
									hide:true,
									width:60
								},{
									display : '订单金额',
									name : 'allMoney',
									sortable :　true,
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '已付金额',
									name : 'payed',
									process : function(v, row) {
										if(v>0){
											var skey=row['skey_'];
											var payed=parseFloat(v);
											var allMoney=parseFloat(row.allMoney);
											var amountIssued=parseInt(row.amountIssued);
											var shallPay=parseFloat(row.shallPay);
											if(payed>allMoney){
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else if(amountIssued==0&&row.paymentCondition=="YFK"&&payed>parseFloat(row.YFPay)){//入库数量为0且付款条件为预付款时，对已付款进行判断
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else if(amountIssued==0&&row.paymentCondition=="HDFK"&&payed>0){//入库数量为0且付款条件为货到付款时，对已付款进行判断
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else if(amountIssued>0&&payed>(shallPay+parseFloat(row.YFPay))){//到货应付的金额与已付金额进行比较
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else{
												var vStr=moneyFormat2(parseFloat(v));
											}
											if(row.viewType==1){
												return "<a href='#' title='查看付款信息' onclick='viewPay(\""
														+ row.id
														+"\",\""
														+row.hwapplyNumb
														+"\",\""
														+row.suppId
														+"\",\""
														+row.suppName
														+"\",\""
														+skey
														+ "\")' >"
														+ vStr
														+ "</a>";
											}else{
												return vStr;
											}
										}else{
											return "0.00";
										}
									}
								},{
									display : '发票金额',
									name : 'handInvoiceMoney',
									process : function(v, row) {
										if(v>0){
											if(parseFloat(v)>parseFloat(row.allMoney)){//如果发票金额大于订单金额，则标为红色
												var vStr="<font color=red>" +moneyFormat2(parseFloat(v))+"</font>";
											}else{
												var vStr=moneyFormat2(parseFloat(v));
											}
											var skey=row['skey_'];
											if(row.viewType==1){
												return "<a href='#' title='查看发票信息' onclick='viewInvoice(\""
														+ row.id
														+"\",\""
														+row.hwapplyNumb
														+"\",\""
														+row.suppId
														+"\",\""
														+row.suppName
														+"\",\""
														+skey
														+ "\")' >"
														+ vStr
														+ "</a>";
											}else{
												return vStr;
											}
										}else{
											return "0.00";
										}
									}
								},{
									display : '订单总数量',
									name : 'amountAll',
									width : 60,
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '入库总数量',
									name : 'amountIssued',
									width : 60,
									process : function(v, row) {
										var v=parseInt(v);
										var amountAll=parseInt(row.amountAll);
										if(v>amountAll){
											return "<font color=red>" +moneyFormat2(v)+"</font>";
										}else{
											return moneyFormat2(v);
										}
									}
								}
								,{
									display : '付款时间',
									name : 'payFormDate',
									sortable :　true,
									width:80
								},{
									display : '入库时间',
									name : 'auditDate',
									sortable : true,
									width:80
								},{
									display : '发票时间',
									name : 'formDate',
									sortable :　true,
									width:80
								},{
						            name : 'isNeedStamp',
						            display : '已申请盖章',
						            sortable : true,
						            width : 60,
						            process : function(v,row){
										if(v=="0"){
											return "否";
										}else if( v== "1"){
											return "是";
										}
									}
						        },{
						            name : 'isStamp',
						            display : '是否已盖章',
						            sortable : true,
						            width : 60,
						            process : function(v,row){
										if(v=="0"){
											return "否";
										}else if( v== "1"){
											return "是";
										}
						            }
						        },{
						            name : 'stampType',
						            display : '盖章类型',
						            sortable : true,
						            width : 80
						        },{
									display : '单据日期',
									name : 'orderTime',
									sortable : true,
									width:80
								},{
									display : '供应商名称',
									name : 'suppName',
									sortable : true,
									width : '180'
								}
								],
							// 主从表格设置
							subGridOptions : {
								url : '?model=purchase_contract_equipment&action=managPageJson',
								param : [{
											paramId : 'basicId',
											colId : 'id'
										}],
								colModel : [ {
											name : 'productNumb',
											display : '物料编号'
										}, {
											name : 'productName',
											width : 200,
											display : '物料名称'
										},{
											name : 'amountAll',
											display : "订单数量",
											width : 60,
											process : function(v,row){
													return moneyFormat2(v);
											}
										}, {
											name : 'amountIssued',
											display : "已入库数量",
											width : 60,
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'applyPrice',
											display : "含税单价",
											process : function(v,row){
													return moneyFormat2(v,6);
											}
										},{
											name : 'moneyAll',
											display : "金额",
											process : function(v,row){
													return moneyFormat2(v);
											}
										},{
											name : 'applyDeptName',
											display : "申请部门"
										},{
											name : 'sourceNumb',
											display : "源单编号",
											width : 170
										}]
							},

							comboEx:[{
								text:'采购类型',
								key:'purchType',
								data:[{
								   text:'销售合同采购',
								   value:'HTLX-XSHT'
								},{
								   text:'服务合同采购',
								   value:'HTLX-FWHT'
								},{
								   text:'租赁合同采购',
								   value:'HTLX-ZLHT'
								},{
								   text:'研发合同采购',
								   value:'HTLX-YFHT'
								},{
								   text:'借试用采购',
								   value:'oa_borrow_borrow'
								},{
								   text:'赠送采购',
								   value:'oa_present_present'
								},{
								   text:'资产采购',
								   value:'assets'
								},{
								   text:'补库采购',
								   value:'stock'
								},{
								   text:'生产采购',
								   value:'produce'
								},{
								   text:'研发采购',
								   value:'rdproject'
								}]
							}],
				buttonsEx : [{
							name : 'expport',
							text : "导出物料信息",
							icon : 'excel',
							action : function(row) {
								window.open("?model=purchase_contract_purchasecontract&action=toExporttFilter",
												"", "width=800,height=400");
							}
						}, {
							name : 'Add',
							text : "下查",
							icon : 'search',
							action : function(row, rows, idArr) {
								if (row) {
									if (idArr.length > 1) {
										alert('一次只能对一条记录进行下查');
										return false;
									}

									$.ajax({
										type : "POST",
										url : "?model=common_search_searchSource&action=checkDown",
										data : {
											"objId" : row.id,
											"objType" : 'purchasecontract'
										},
										async : false,
										success : function(data) {
											if (data != "") {
												showModalWin("?model=common_search_searchSource&action=downList&objType=purchasecontract&orgObj="
														+ data + "&objId=" + row.id);
											} else {
												alert('没有相关联的单据');
											}
										}
									});
								} else {
									alert('请先选择记录');
								}
							}
						}],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+row.id+"&skey="+row['skey_']);
								}else{
									alert("请选中一条数据");
								}
							}

						}
						,{
							text : '审批情况',
							icon : 'view',
							action : function(row,rows,grid){
								if(row){
									showThickboxWin("controller/common/readview.php?itemtype=oa_purch_apply_basic&pid="
											+row.id
											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
								}
							}
						},{
							text : '中止原因',
							icon : 'view',
						   showMenuFn:function(row){
						   		if(row.state=="6"){
						   			return true;
						   		}
						   		return false;
						   },
							action : function(row,rows,grid){
								if(row){
									showThickboxWin("?model=purchase_contract_purchasecontract&action=toCloseRead&id="+row.id+"&skey="+row['skey_']
											+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
								}
							}
						},
						{
							text : '导出订单',
							icon : 'excel',
							action :function(row,rows,grid) {
								if(row){
									location="?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id="+row.id+"&skey="+row['skey_'];
								}else{
									alert("请选中一条数据");
								}
							}

						},{
							text : '附件上传',
							icon : 'add',
							action: function(row){
								     showThickboxWin ('?model=purchase_contract_purchasecontract&action=toUploadFile&id='
								                      + row.id
								                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=700');
							}
						   }

						],
						//快速搜索
						searchitems : [
								{
									display : '订单编号',
									name : 'hwapplyNumb'
								},
								{
									display : '单据日期',
									name : 'orderTime'
								},
								{
									display : '供应商名称',
									name : 'suppName'
								},
								{
									display : '采购负责人',
									name : 'sendName'
								},
								{
									display : '物料编号',
									name : 'productNumb'
								},
								{
									display : '物料名称',
									name : 'productName'
								}
								],
						// title : '客户信息',
						//业务对象名称
//						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "updateTime",
						//默认搜索顺序
						sortorder : "DESC",
						//显示查看按钮
						isViewAction : false,
						isAddAction : true,
						isEditAction : false,
						isDelAction : false
					});

		});