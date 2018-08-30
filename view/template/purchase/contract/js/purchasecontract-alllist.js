// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#listGrid").yxsubgrid("reload");
};
$(function() {
			$("#listGrid").yxsubgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
//						action : 'pageJsonYes',
						title : '采购订单列表',
						isToolBar : false,
						showcheckbox : false,
						param:{"stateArr":"4,5,6,7"},

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
									width : '200'
								}
								,{
									display : '供应商名称',
									name : 'suppName',
									sortable : true,
									width : '200'
								}
								,{
									display : '发票类型',
									name : 'billingType',
									datacode : 'FPLX',		//数据字典编码
									sortable : true
								}
								, {
									display : '付款方式',
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true
								},{
									display : '签收状态',
									name : 'signState',
									sortable :　true,
									hide:true,
									process:function(v){
										if(v==0){
											return "未签收";
										}else{
											return "已签收";
										}
									}
								},{
									display : '签收时间',
									name : 'signTime',
									hide:true,
									sortable :　true
								},{
									display : '业务员',
									name : 'sendName',
									sortable : true
								},{
									display : '预计完成时间',
									name : 'dateHope',
									sortable :　true
								},{
									display : '审批状态',
									name : 'ExaStatus',
									sortable :　true
								}
								],
							param : {"ExaStatus" : "打回,完成"},
//							param : {"ExaStatus" : "完成"},
						// 主从表格设置
						subGridOptions : {
							url : '?model=purchase_contract_equipment&action=pageJson',
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
										name : 'pattem',
										display : "规格型号"
									},{
										name : 'units',
										display : "单位"
									},{
										name : 'applyPrice',
										display : "含税单价"
									}, {
										name : 'amountAll',
										display : "订单数量"
									},{
										name : 'amountIssued',
										display : "已入库数量"
									}]
						},
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
		}],
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
									display : '业务员',
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