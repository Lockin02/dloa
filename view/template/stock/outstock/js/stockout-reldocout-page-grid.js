var show_page = function(page) {
	$("#stockoutGrid").yxsubgrid("reload");
};

$(function() {
	var canPrintOutRecord = $("#canPrintOutRecord").val();
	var printBtn = (canPrintOutRecord == 1)? {
		name: 'print',
		text: "打单",
		icon: 'search',
		action: function () {
			var responseText = $.ajax({
				url:'index1.php?model=stock_outstock_stockout&action=relDocOutJson&relDocType='+$("#relDocType").val()+'&docId='+$("#docId").val()+'&docType='+$("#docType").val(),
				type : "POST",
				async : false
			}).responseText;
			var responseData = eval("(" + responseText + ")");
			if(responseData.collection.length > 0){
				var docId = $("#docId").val();
				showThickboxWin("?model=stock_outstock_stockout&action=toPrintShipList&docId="+docId
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700");
			}else{
				alert("暂无相关出库记录!");
			}
		}
	} : {};
	var buttonArr = [
		printBtn
	];
			$("#stockoutGrid").yxsubgrid({
				model : 'stock_outstock_stockout',
				action : 'relDocOutJson&relDocType=' + $("#relDocType").val()
						+ '&docId=' + $("#docId").val() + '&docType='
						+ $("#docType").val(),
				title : '出库记录',
				isAddAction : false,
				isViewAction : false,
				isEditAction : false,
				isDelAction : false,
				showcheckbox : false,
				isRightMenu : false,
				// 列信息
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'isRed',
							display : '红蓝类型',
							sortable : true,
							width : 50,
							align : 'center',
							process : function(v, row) {
								if (row.isRed == '0') {
									return "<img src='images/icon/hblue.gif' />";
								} else {
									return "<img src='images/icon/hred.gif' />";
								}
							}
						}, {
							name : 'docCode',
							display : '单据编号',
							sortable : true
						}, {
							name : 'auditDate',
							display : '单据日期',
							sortable : true
						}, {
							name : 'docType',
							display : '出库类型',
							sortable : true,
							hide : true
						}, {
							name : 'relDocType',
							display : '源单类型',
							sortable : true,
							datacode : "QTCKYDLX"
						}, {
							name : 'relDocId',
							display : '源单id',
							sortable : true,
							hide : true
						}, {
							name : 'relDocName',
							display : '源单名称',
							sortable : true,
							hide : true
						}, {
							name : 'relDocCode',
							display : '源单编号',
							sortable : true

						}, {
							name : 'contractId',
							display : '合同id',
							sortable : true,
							hide : true
						}, {
							name : 'contractName',
							display : '合同/订单名称',
							sortable : true,
							hide : true
						}, {
							name : 'contractCode',
							display : '合同/订单编号',
							sortable : true
						}, {
							name : 'stockId',
							display : '发料仓库id',
							sortable : true,
							hide : true
						}, {
							name : 'stockCode',
							display : '发料仓库编码',
							sortable : true,
							hide : true
						}, {
							name : 'stockName',
							display : '发料仓库',
							sortable : true
						}, {
							name : 'customerName',
							display : '客户(单位)名称',
							sortable : true
						}, {
							name : 'customerId',
							display : '客户(单位)id',
							sortable : true,
							hide : true
						}, {
							name : 'saleAddress',
							display : '发货地址',
							sortable : true,
							hide : true
						}, {
							name : 'linkmanId',
							display : '发货联系人id',
							sortable : true,
							hide : true
						}, {
							name : 'linkmanName',
							display : '发货联系人',
							sortable : true
						}, {
							name : 'linkmanTel',
							display : '发货联系人电话',
							sortable : true,
							hide : true
						}, {
							name : 'pickingType',
							display : '领料类型',
							sortable : true,
							hide : true
						}, {
							name : 'deptName',
							display : '领料部门名称',
							sortable : true,
							hide : true
						}, {
							name : 'deptCode',
							display : '领料部门编码',
							sortable : true,
							hide : true
						}, {
							name : 'toUse',
							display : '物料用途',
							sortable : true,
							hide : true
						}, {
							name : 'salesmanCode',
							display : '发货员编号',
							sortable : true,
							hide : true
						}, {
							name : 'salesmanName',
							display : '发货员',
							sortable : true
						}, {
							name : 'otherSubjects',
							display : '对方科目',
							sortable : true,
							hide : true
						}, {
							name : 'docStatus',
							display : '单据状态',
							sortable : true,
							process : function(v, row) {
								if (v == "WSH") {
									return "未审核";
								} else {
									return "已审核";
								}
							}
						}, {
							name : 'catchStatus',
							display : '钩稽状态',
							sortable : true,
							hide : true
						}, {
							name : 'remark',
							display : '备注',
							sortable : true,
							hide : true
						}, {
							name : 'pickName',
							display : '领料人名称',
							sortable : true,
							hide : true
						}, {
							name : 'pickCode',
							display : '领料人编码',
							sortable : true,
							hide : true

						}, {
							name : 'auditerCode',
							display : '审核人编号',
							sortable : true,
							hide : true
						}, {
							name : 'auditerName',
							display : '审核人名称',
							sortable : true,
							hide : true
						}, {
							name : 'custosCode',
							display : '保管人编号',
							sortable : true,
							hide : true
						}, {
							name : 'custosName',
							display : '保管人名称',
							sortable : true,
							hide : true
						}, {
							name : 'chargeCode',
							display : '负责人编号',
							sortable : true,
							hide : true
						}, {
							name : 'chargeName',
							display : '负责人名称',
							sortable : true,
							hide : true
						}, {
							name : 'acceptorCode',
							display : '验收人编号',
							sortable : true,
							hide : true
						}, {
							name : 'acceptorName',
							display : '验收人名称',
							sortable : true,
							hide : true
						}, {
							name : 'accounterCode',
							display : '记账人编号',
							sortable : true,
							hide : true
						}, {
							name : 'accounterName',
							display : '记账人名称',
							sortable : true,
							hide : true
						}, {
							name : 'createTime',
							display : '创建时间',
							sortable : true,
							hide : true
						}, {
							name : 'createName',
							display : '创建人名称',
							sortable : true,
							hide : true
						}, {
							name : 'createId',
							display : '创建人',
							sortable : true,
							hide : true,
							hide : true
						}, {
							name : 'updateName',
							display : '修改人名称',
							sortable : true,
							hide : true
						}, {
							name : 'updateTime',
							display : '修改时间',
							sortable : true,
							hide : true
						}, {
							name : 'updateId',
							display : '修改人',
							sortable : true,
							hide : true
						}],
				// 主从表格设置
				subGridOptions : {
					url : '?model=stock_outstock_stockoutitem&action=pageJson',
					param : [{
								paramId : 'mainId',
								colId : 'id'
							}],
					colModel : [{
								name : 'productCode',
								display : '物料编号'
							}, {
								name : 'productName',
								width : 200,
								display : '物料名称'
							}, {
								name : 'actOutNum',
								display : "实发数量"
							}, {
								name : 'serialnoName',
								display : "序列号",
								width : '500'
							}]
				},

				searchitems : [{
					display : '单据编号',
					name : 'docCode'
						// }, {
						// display : '仓库名称',
						// name : 'inStockName'
					}],
				buttonsEx: buttonArr,
				sortorder : "DESC"
			});
		});