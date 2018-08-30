var show_page = function(page) {
	$("#serialnoGrid").yxgrid("reload");
};
$(function() {
	//默认隐藏新增、编辑、删除按钮
	var addShow = false;
	var editShow = false;
	var deleteShow = false;
	//获取新增权限
	$.ajax({
		type : 'POST',
		url : '?model=stock_serialno_serialno&action=getLimits',
		data : {
			'limitName' : '新增权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				addShow = true;//显示新增按钮
			}
		}
	});
	//获取编辑权限
	$.ajax({
		type : 'POST',
		url : '?model=stock_serialno_serialno&action=getLimits',
		data : {
			'limitName' : '编辑权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				editShow = true;//显示编辑按钮
			}
		}
	});
	//获取删除权限
	$.ajax({
		type : 'POST',
		url : '?model=stock_serialno_serialno&action=getLimits',
		data : {
			'limitName' : '删除权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				deleteShow = true;//显示删除按钮
			}
		}
	});
	$("#serialnoGrid").yxgrid({
		model : 'stock_serialno_serialno',
		title : '物料序列号台账',
		isAddAction : addShow,
		isViewAction : false,
		isEditAction : editShow,
		isDelAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'sequence',
					display : '物料序列号',
					sortable : true,
					width : 150
				}, {
					name : 'seqStatus',
					display : '序列号状态',
					sortable : true,
					process : function(v, row) {
						if (v == "0") {
							return "库存中";
						} else if (v == "1") {
							return "已出库";
						}
					}
				}, {
					name : 'productId',
					display : '物料id',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '物料编号',
					sortable : true
				}, {
					name : 'productName',
					display : '物料名称',
					width : 200,
					sortable : true

				}, {
					name : 'pattern',
					display : '规格型号',
					sortable : true
				}, {
					name : 'stockId',
					display : '仓库id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '仓库名称',
					sortable : true
				}, {
					name : 'stockCode',
					display : '仓库代码',
					sortable : true,
					hide : true
				}, {
					name : 'batchNo',
					display : '批次号',
					sortable : true

				}, {
					name : 'shelfLife',
					display : '保质期',
					sortable : true,
					hide : true
				}, {
					name : 'prodDate',
					display : '生产（采购）日期',
					sortable : true,
					hide : true
				}, {
					name : 'validDate',
					display : '有效期至',
					sortable : true,
					hide : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true
				}, {
					name : 'inDocId',
					display : '入库单id',
					sortable : true,
					hide : true
				}, {
					name : 'inDocCode',
					display : '入库单编号',
					sortable : true
				}, {
					name : 'inDocItemId',
					display : '入库清单id',
					sortable : true,
					hide : true
				}, {
					name : 'outDocCode',
					display : '出库单编号',
					sortable : true
				}, {
					name : 'outDocId',
					display : '出库单id',
					sortable : true,
					hide : true
				}, {
					name : 'outDocItemId',
					display : '出库单清单id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocType',
					display : '源单类型',
					sortable : true,
					process : function(v) {
						if (v == 'oa_sale_order' || v == 'oa_contract_contract') {
							return "销售合同";
						} else if (v == 'oa_borrow_borrow') {
							return "借试用";
						} else if (v == 'oa_present_present') {
							return "赠送";
						} else if ((v == 'rdproject')) {
							return "研发采购";
						} else if ((v == 'oa_contract_exchangeapply')) {
							return "换货发货";
						}else if (v == 'stock'){
							return "补库";
						}else if (v == 'oa_service_accessorder'){
							return "零配件订单";
						}else if (v == 'independent'){
							return "独立发货";
						}
					}
				}, {
					name : 'relDocId',
					display : '源单id',
					sortable : true,
					hide : true
				}, {
					name : 'relDocCode',
					display : '源单编号',
					sortable : true,
					process : function(v, row) {
					   if(row.relDocType=='oa_contract_contract' || row.relDocType=='oa_borrow_borrow' || row.relDocType=='oa_present_present')
						return '<a href="javascript:void(0)" ' +
								'onclick="relDocView(\''+row.relDocType+'\',\''+v+'\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					   else
					    return v;
					}
				}, {
					name : 'relDocName',
					display : '源单名称',
					sortable : true,
					hide : true
				}],
		toEditConfig : {
			formWidth : 700,
			formHeight : 300
		},
		toAddConfig : {
			action : 'toAddBatch',
			formWidth : 800,
			formHeight : 500
		},
		menusEx : [{
			text : '删除',
			icon : 'delete',
			showMenuFn : function() {
				if (deleteShow) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认要删除?")) {
					$.ajax({
						type : "POST",
						url : "?model=stock_serialno_serialno&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('删除成功！');
							} else {
								alert('删除失败!');
							}
						}
					});
				}
			}
		}],
		searchitems : [{
					name : 'likesequence',
					display : '序列号'
				}, {
					name : 'relDocCode',
					display : '源单编号'
				}, {
					display : '批次号',
					name : 'batchNo'
				}, {
					name : 'productName',
					display : '物料名称'

				}, {
					name : 'productCode',
					display : '物料编号'

				}, {
					name : 'likeinDocCode',
					display : '入库单编号'

				}, {
					name : 'likeoutDocCode',
					display : '出库单编号'

				}]
	});
});

function relDocView(relDocType,relDocCode){
    //源单 查看菜单数组
	var viewArr = new Array();
	viewArr['oa_contract_contract'] = "?model=contract_contract_contract&action=toViewTab&id=";
	viewArr['oa_borrow_borrow_c'] = "index1.php?model=projectmanagent_borrow_borrow&action=toViewTab&id=";
	viewArr['oa_borrow_borrow_p'] = "index1.php?model=projectmanagent_borrow_borrow&action=proViewTab&id=";
	viewArr['oa_present_present'] = "index1.php?model=projectmanagent_present_present&action=viewTab&id=";
//   if(viewArr[relDocType]){
	 var relDocTypeRel = relDocType;//用于权限
	 if(viewArr[relDocType] || relDocType== "oa_borrow_borrow"){
      //ajax 更加编号获取源单id
		var relDocId = $.ajax({
			    type : 'POST',
			    url : "?model=stock_serialno_serialno&action=ajaxRelDocIdByCode",
			    data:{
			        relDocType : relDocType,
			        relDocCode : relDocCode
			    },
			    async: false,
			    success : function(data){
				}
			}).responseText;
		//处理员工/客户借用类型
		if(relDocType=='oa_borrow_borrow'){
		   var borrowType = $.ajax({
				    type : 'POST',
				    url : "?model=stock_serialno_serialno&action=ajaxGetLimitsById",
				    data:{
				        relDocId : relDocId
				    },
				    async: false,
				    success : function(data){
					}
				}).responseText;
		   relDocType = relDocType + "_" + borrowType;
		}
	    if(typeof(relDocId) != 'undefined' && relDocId != ""){
	    	//判断权限
	    	var limit = $.ajax({
				    type : 'POST',
				    url : "?model=stock_serialno_serialno&action=ajaxlimit",
				    data:{
				        relDocId : relDocId,
				        relDocType : relDocTypeRel
				    },
				    async: false,
				    success : function(data){
					}
				}).responseText;
			if(limit == '1'){
			   showModalWin(viewArr[relDocType]
				+ relDocId
				+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
			}else{
               alert("权限不足，请向管理员申请【"+limit+"】部门权限。");
			}
	    }else{
	       alert("未找到源单关联信息。");
	    }
   }else{
       alert("源单类型错误，请联系管理员")
   }
}





