
// 计算方法
function countAll(rowNum) {
	var beforeStr = "borrowConEquInfo_cmp_";
	if ($("#" + beforeStr + "number" + rowNum).val() == ""
			|| $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
		return false;
	} else {
		// 获取当前数
		thisNumber = $("#" + beforeStr + "number" + rowNum).val();
		// alert(thisNumber)

		// 获取当前单价
		thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
		// alert(thisPrice)

		// 计算本行金额 - 不含税
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
					display : '从表Id',
					name : 'id',
					tclass : 'txt',
					type : 'hidden'
				}, {
					display : '物料Id',
					name : 'productId',
					tclass : 'txt',
					type : 'hidden'
				}, {
					display : '物料编号',
					name : 'productNo',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly'
				}, {
					display : '物料名称',
					name : 'productName',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly'
				}, {
					display : '型号/版本',
					name : 'productModel',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly'
				}, {
					display : '转销售数量',
					name : 'toContractNum',
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					display : '最大可变数量',
					name : 'maxNum',
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					display : '数量',
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
								alert("数量不的大于" + maxNum + ",或小于0 ");
								var g = $(this).data("grid");
								g
										.setRowColValue(rowNum, "number",
												maxNum, true);
							}

						}
					}
				}, {
					display : '单价',
					name : 'price',
					tclass : 'txtshort',
					type : 'money',
					event : {
						blur : function() {
							countAll($(this).data("rowNum"));
						}
					}
				}, {
					display : '金额',
					name : 'money',
					tclass : 'txtshort',
					type : 'money',
					event : {
						blur : function() {
							countAll($(this).data("rowNum"));
						}
					}
				}, {
					display : '保修期',
					name : 'warrantyPeriod',
					tclass : 'txtshort'
				}, {
					display : '加密配置Id',
					name : 'license',
					type : 'hidden'
				}, {
					name : 'licenseButton',
					display : '加密配置',
					type : 'statictext',
					process : function(v, row) {
						if (row.license != "") {
							return "<a href='#' onclick='showLicense(\""
									+ row.license + "\")'>查看</a>";
						} else {
							return "";
						}
					}
				}, {
					name : 'serialId',
					display : '序列号ID',
					type : 'hidden'
				}, {
					name : 'serialName',
					display : '序列号',
					tclass : 'readOnlyTxtNormal',
					readonly : 'readonly',
					process : function($input, rowData, $tr , grid) {
						var inputId = $input.attr('id');
						var rownum = $input.data('rowNum');// 第几行
						var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
						var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='选择序列号'>");
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

// 选择序列号
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