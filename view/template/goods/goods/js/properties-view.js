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
					display : 'ֵ����',
					sortable : true
				}, {
					name : 'isNeed',
					display : '�Ƿ��ѡ',
					type : 'checkbox',
					tclass : 'txtmin',
					process : function(v, row) {
						if (v == "on") {
							return "��";
						} else {
							return v;
						}

					},
					sortable : true
				}, {
					name : 'isDefault',
					display : '�Ƿ�Ĭ��',
					type : 'checkbox',
					tclass : 'txtmin',
					sortable : true,
					process : function(v, row) {
						if (v == "on") {
							return "��";
						} else {
							return v;
						}

					}
				}, {
					name : 'defaultNum',
					display : '����',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'productCode',
					display : '��Ӧ���ϱ��',
					sortable : true
				}, {
					name : 'productName',
					display : '��Ӧ��������',
					tclass : 'txt',
					sortable : true
				}, {
					name : 'pattern',
					display : '��Ӧ�����ͺ�',
					sortable : true,
					tclass : 'readOnlyTxtItem'
				}, {
					name : 'proNum',
					display : '��Ӧ��������',
					sortable : true
				}, {
					name : 'status',
					display : '״̬',
					type : 'select',
					tclass : 'txtshort',
					process : function(v, row) {
						if (v == "ZC") {
							return "�ڲ�";
						} else if (v == "TC") {
							return "ͣ��";
						}

					},
					sortable : true
				}, {
					name : 'remark',
					display : '����',
					type : "hidden"
				}, {
					name : 'rkey',
					display : '������ʶ',
					type : "hidden"
				}, {
					name : 'staticRemark',
					display : '��������',
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
											'������Ϣ�༭',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value=" �� �� "  class="txt_btn_a"  />'
				}, {
					name : 'assitem',
					display : '���������',
					type : "hidden"
				}, {
					name : 'assitemIdStr',
					display : '������Id����',
					type : "hidden"
				}, {
					name : 'assitemTipStr',
					display : '������Tip����',
					type : "hidden"
				}, {
					name : 'staticAssitem',
					display : '���������',
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
											'���������',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value="�鿴"  class="txt_btn_a"  />'
				}]
	})
})
