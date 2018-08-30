// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#equListGrid").yxgrid("reload");
};

//查看订单信息
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+id+"&skey="+skey);
}
$(function() {
			$("#equListGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'orderEquJson',
						title : '采购订单列表',
						isToolBar : false,
						showcheckbox : false,
						usepager : false, // 是否分页
						param:{'beginTime':$("#beginTime").val(),'endTime':$("#endTime").val(),'csuppId':$("#suppId").val(),'searchProductNumb':$("#productNumb").val(),'searchPproductName':$("#productName").val(),'sendUserId':$("#sendUserId").val()},

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '日期',
									name : 'createTime',
									sortable : true
								}
								,{
									display : '供应商',
									name : 'suppName',
									sortable : true,
									width : '200'
								}, {
									display : '单据编号',
									name : 'hwapplyNumb',
									sortable : true,
									width : '150',
									process : function(v, row) {
										var skey=row['skey_'];
											return "<a href='#' title='查看订单详细信息' onclick='viewOrder(\""
													+ row.id
													+"\",\""
													+skey
													+ "\")' >"
													+ v
													+ "</a>";
									}
								}
								,{
									display : '物料代码',
									name : 'productNumb',
									sortable : true
								}
								, {
									display : '物料名称',
									name : 'productName',
									sortable : true,
									width : '200'
								},{
									display : '规格型号',
									name : 'pattem',
									sortable :　true
								},{
									display : '业务员',
									name : 'sendName',
									sortable : true
								},{
									display : '数量',
									name : 'amountAll',
									sortable :　true,
									width : '60',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '单价',
									name : 'price',
									sortable :　true,
									process : function(v,row){
											return moneyFormat2(v,6,6);
									}
								},{
									display : '税率',
									name : 'taxRate',
									sortable :　true,
									width : '60'
								},{
									display : '金额',
									name : 'noTaxMoney',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '含税单价',
									name : 'applyPrice',
									sortable :　true,
									process : function(v,row){
											return moneyFormat2(v,6,6);
									}
								},{
									display : '价税合计',
									name : 'moneyAll',
									sortable :　true,
									process : function(v,row){
											return moneyFormat2(v);
									}
								}
								],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
//									showThickboxWin("?model=purchase_contract_purchasecontract&action=init"
//										+ "&id="
//										+ row.id
//										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//										+ 400 + "&width=" + 700);
									showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&perm=view&id="+row.id+"&skey="+row['skey_']);
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
						}
						],
		buttonsEx : [ {
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
		},
			{
				text : '返回',
				icon : 'view',
				action : function(row, rows, grid) {
					location = "?model=purchase_contract_purchasecontract&action=toAllList"
				}
			}],
						//快速搜索
						searchitems : [
								{
									display : '单据编号',
									name : 'hwapplyNumb'
								},
								{
									display : '单据日期',
									name : 'orderTime'
								},
								{
									display : '供应商',
									name : 'suppName'
								},
								{
									display : '业务员',
									name : 'sendName'
								},
								{
									display : '物料代码',
									name : 'searchProductNumb'
								},
								{
									display : '物料名称',
									name : 'searchPproductName'
								},
								{
									display : '规格型号',
									name : 'searchPattem'
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