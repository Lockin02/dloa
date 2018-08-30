// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".signGrid").yxgrid("reload");
};
$(function() {
			$(".signGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
//						action : 'pageJsonYes',
						title : '采购订单签收列表',
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
								},{
									display : '签收状态',
									name : 'signState',
									sortable :　true,
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
									sortable :　true
								},{
									display : '制单人',
									name : 'createName',
									sortable : true
								},{
									display : '预计完成时间',
									name : 'dateHope',
									hide : true
								},{
									display : '审批状态',
									name : 'ExaStatus',
									sortable :　true
								}
								,{
									display : '供应商名称',
									name : 'suppName',
									sortable : true
								}
								,{
									display : '发票类型',
									name : 'billingType',
									datacode : 'FPLX',		//数据字典编码
									sortable : true,
									hide:true
								}
								, {
									display : '付款方式',
									name : 'paymentType',
									datacode : 'fkfs',
									sortable : true,
									hide:true
								}
								],
							param : {"ExaStatus" : "打回,完成"},
//							param : {"ExaStatus" : "完成"},

							comboEx:[{
								text:'签收状态',
								key:'signState',
								data:[{
								   text:'未签收',
								   value:'0'
								},{
								   text:'已签收',
								   value:'1'
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
						}],
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
									showOpenWin("?model=purchase_contract_purchasecontract&action=init&perm=view&id="+row.id+"&skey="+row['skey_']);
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
						,{
							text : '订单签收',
							icon : 'edit',
						   showMenuFn:function(row){
						   		if(row.signState==0){
						   			return true;
						   		}
						   		return false;
						   },
							action : function(row,rows,grid){
								if(row){
									location="?model=purchase_contract_purchasecontract&action=toSign&id="+row.id
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

						},
						{
							text : '签收记录',
							icon : 'view',
						   showMenuFn:function(row){
						   		if(row.signState==1){
						   			return true;
						   		}
						   		return false;
						   },
							action :function(row,rows,grid) {
								if(row){
									showOpenWin("?model=common_changeLog&action=toPurchaseSign&logObj=purchasesign&objId=" + row.id );
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
									display : '单据编号',
									name : 'hwapplyNumb'
								},
								{
									display : '供应商名称',
									name : 'suppName'
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