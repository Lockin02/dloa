
// ���㷽��
function countAll(rowNum) {
	var beforeStr = "borrowConEquInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
			|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// ��ȡ��ǰ��
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// ��ȡ��ǰ����
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// ���㱾�н�� - ����˰
		thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney(beforeStr + "money" + rowNum, thisMoney);
	}
}
$(function() {
	$("#borrowConEquInfo").yxeditgrid({
		objName : 'contract[borrowtoCon]',
		url : '?model=projectmanagent_borrow_borrowequ&action=toContractPageJson',
		param : {
			'borrowId' : $("#borrowId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		isAddOneRow : false,
		isAdd : false,
		tableClass : 'form_in_table',
		colModel : [{
					display : '�ӱ�Id',
					name : 'id',
					tclass : 'txt',
					type : 'hidden'
				}, {
					display : '����Id',
					name : 'productId',
					tclass : 'txt',
					type : 'hidden'
				}, {
					display : '���ϱ��',
					name : 'productNo',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly'
				}, {
					display : '��������',
					name : 'productName',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly'
				}, {
					display : '�ͺ�/�汾',
					name : 'productModel',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly'
				}, {
					display : 'ת��������',
					name : 'toContractNum',
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					display : '���ɱ�����',
					name : 'maxNum',
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					display : '����',
					name : 'number',
					tclass : 'txtshort',
					event : {
						blur : function(e, rowNum, g) {
							countAll($(this).data("rowNum"));
							var rowNum = $(this).data("rowNum");
							thisNumber = $("#borrowConEquInfo_cmp_number"
									+ rowNum).val();
							maxNum = $("#borrowConEquInfo_cmp_maxNum" + rowNum)
									.val();
							if (thisNumber < 0 || thisNumber > maxNum) {
								alert("�������Ĵ���" + maxNum + ",��С��0 ");
								var g = $(this).data("grid");
								g
										.setRowColValue(rowNum, "number",
												maxNum, true);
							}

						}
					}
				}, {
					display : '����',
					name : 'price',
					tclass : 'txtshort',
					type : 'money',
					event : {
						blur : function() {
							countAll($(this).data("rowNum"));
						}
					}
				}, {
					display : '���',
					name : 'money',
					tclass : 'txtshort',
					type : 'money',
					event : {
						blur : function() {
							countAll($(this).data("rowNum"));
						}
					}
				}, {
					display : '������',
					name : 'warrantyPeriod',
					tclass : 'txtshort'
				}, {
					display : '��������Id',
					name : 'license',
					type : 'hidden'
				}, {
					name : 'licenseButton',
					display : '��������',
					type : 'statictext',
					process : function(v, row) {
						if (row.license != "") {
							return "<a href='#' onclick='showLicense(\""
									+ row.license + "\")'>�鿴</a>";
						} else {
							return "";
						}
					}
				}, {
					name : 'serialId',
					display : '���к�ID',
					type : 'hidden'
				}, {
					name : 'serialName',
					display : '���к�',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly',
					process : function($input, rowData, $tr , grid) {
						var inputId = $input.attr('id');
						var rownum = $input.data('rowNum');// �ڼ���
						var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
						var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='ѡ�����к�'>");
						$img.click(function(borrowId, productId, num, inputId,sid) {
									return function() {
										serialNum(borrowId, productId, num,inputId,sid);
									}
								}(rowData.borrowId, rowData.productId,rowData.number, inputId,sid));
						$input.before($img)
					},
					event : {
						dblclick  : function() {
							var serial = $(this).val();
							if(serial != ""){
								showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
									+ serial
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
							}
						}
					}
				}]

	});

})

// ѡ�����к�
function serialNum(borrowId, productId, num, inputId,sid) {
	var amount = $("#bornumber" + num).val();
	showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNum&borrowId='
			+ borrowId
			+ '&productId='
			+ productId
			+ '&num='
			+ num
			+ '&amount='
			+ num
			+ '&inputId='
			+ inputId
			+ '&sid='
			+ sid
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}