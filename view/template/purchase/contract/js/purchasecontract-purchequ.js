// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#equListGrid").yxgrid("reload");
};

//查看订单信息
function viewOrder(id,skey) {
	showOpenWin("?model=purchase_contract_purchasecontract&action=toTabRead&id="+id+"&skey="+skey);
}
$(function() {
    var assessType=$("#assessType").val();
    var suppId=$("#suppId").val();
    var assesYear=$("#assesYear").val();
    var assesQuarter=$("#assesQuarter").val();
			$("#equListGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
//						 url :
						model : 'purchase_contract_purchasecontract',
						action : 'purchEquJson',
						title : '采购订单明细',
						isToolBar : false,
						showcheckbox : false,
						param:{"suppId":suppId,"assessType":assessType,"assesYear":assesYear,"assesQuarter":assesQuarter},

						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '单据日期',
									name : 'createTime',
									sortable : true,
									width:80
								}, {
									display : '单据编号',
									name : 'hwapplyNumb',
									sortable : true,
									width : '150'
								}
								,{
									display : '供应商',
									name : 'suppName',
									sortable : true,
									width : '200'
								}
                            ,{
                                display : '物料编码',
                                name : 'productNumb',
                                width : '65',
                                sortable : true
                            }
								, {
									display : '物料名称',
									name : 'productName',
									sortable : true,
									width : '200',
									process : function(v,row){
											if((DateDiff(row.today,row.dateHope)<2||DateDiff(row.today,row.dateHope)==2)&&DateDiff(row.today,row.dateHope)>0){
												return "<font color=red>"+v+"</font>";;
											}else{
												return v;
											}
									}
								},{
									display : '订单数量',
									name : 'amountAll',
									sortable :　true,
									width : '65',
									process : function(v,row){
										if(parseInt(row.amountAll)!=parseInt(row.amountIssued)){
											return "<font color=blue>"+moneyFormat2(v)+"</font>";
										}else{
											return moneyFormat2(v);
										}
									}
								},{
									display : '入库数量',
									name : 'amountIssued',
									sortable :　true,
									width : '65',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '收料数量',
									name : 'arrivalNum',
									sortable :　true,
									width : '65',
									process : function(v,row){
											return moneyFormat2(v);
									}
								},{
									display : '质检方式',
									name : 'checkTypeName',
									sortable :　false,
									width : '60',
									process : function(v,row){
											if(v=="全检"||v=="抽检"){
												return "<font color=red>"+v+"</font>";;
											}else{
												return v;
											}
									}
								},{
									display : '预计到货时间',
									name : 'dateHope',
									sortable : true
								},{
									display : '采购类型',
									name : 'purchType',
									sortable : true
								}
								],
						//扩展右键菜单
						menusEx : [
						],
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
									display : '物料编码',
									name : 'searchProductNumb'
								},
								{
									display : '物料名称',
									name : 'searchPproductName'
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