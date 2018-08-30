$(document).ready(function() {

	$("#itemTable").yxeditgrid({
		objName : 'properties[items]',
		url : '?model=goods_goods_propertiesitem&action=pageItemJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [{
					name : 'itemContent',
					tclass : 'txt',
					display : '值内容',
					sortable : true
				}, {
					name : 'isNeed',
					display : '是否必选',
					type : 'checkbox',
					tclass : 'txtmin',
					process : function(v, row) {
						if (v == "on") {
							return "√";
						} else {
							return v;
						}

					},
					sortable : true
				}, {
					name : 'isDefault',
					display : '是否默认',
					type : 'checkbox',
					tclass : 'txtmin',
					sortable : true,
					process : function(v, row) {
						if (v == "on") {
							return "√";
						} else {
							return v;
						}

					}
				}, {
					name : 'defaultNum',
					display : '数量',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'productCode',
					display : '对应物料编号',
					sortable : true
				}, {
					name : 'productName',
					display : '对应物料名称',
					tclass : 'txt',
					sortable : true
				}, {
					name : 'pattern',
					display : '对应物料型号',
					sortable : true,
					tclass : 'readOnlyTxtItem'
				}, {
					name : 'proNum',
					display : '对应物料数量',
					sortable : true
				}, {
					name : 'status',
					display : '状态',
					type : 'select',
					tclass : 'txtshort',
					process : function(v, row) {
						if (v == "ZC") {
							return "在产";
						} else if (v == "TC") {
							return "停产";
						}

					},
					sortable : true
				}, {
					name : 'remark',
					display : '描述',
					type : "hidden"
				}, {
					name : 'rkey',
					display : '描述标识',
					type : "hidden"
				}, {
					name : 'staticRemark',
					display : '具体描述',
					type : 'statictext',
					event : {
						'click' : function(e) {
							var rowNum = $(this).data("rowNum");
							var g = $(this).data("grid");
							window
									.open(
											"?model=goods_goods_properties&action=toEditRemark&rowNum="
													+ rowNum
													+ "&id="
													+ g.getCmpByRowAndCol(
															rowNum, 'id').val()
													+ "&rkey="
													+ $("#itemTable_cmp_rkey"
															+ rowNum).val(),
											'描述信息编辑',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value=" 查 看 "  class="txt_btn_a"  />'
				}, {
					name : 'assitem',
					display : '数据项关联',
					type : "hidden"
				}, {
					name : 'assitemIdStr',
					display : '数据项Id关联',
					type : "hidden"
				}, {
					name : 'assitemTipStr',
					display : '数据项Tip关联',
					type : "hidden"
				}, {
					name : 'staticAssitem',
					display : '数据项关联',
					type : 'statictext',
					event : {
						'click' : function(e) {
							var rowNum = $(this).data("rowNum");
							var g = $(this).data("grid");
							var rowData = $(this).data("rowData");

							window
									.open(
											"?model=goods_goods_properties&action=toSetAssItem&goodsId="
													+ $("#mainId").val()
													+ "&orderNum="
													+ $("#orderNum").val()
													+ "&rowNum="
													+ rowNum
													+ "&assitem="
													+ $("#itemTable_cmp_assitem"
															+ rowNum).val()
													+ "&assItemIdStr="
													+ $("#itemTable_cmp_assitemIdStr"
															+ rowNum).val()
													+ "&assitemTipStr="
													+ $("#itemTable_cmp_assitemTipStr"
															+ rowNum).val(),
											'数据项关联',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value="查看"  class="txt_btn_a"  />'
				}]
	})
})
