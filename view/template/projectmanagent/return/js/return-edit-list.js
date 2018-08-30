// 表单收缩
function hideList(listId) {
	var temp = document.getElementById(listId);
	var tempH = document.getElementById(listId + "H");
	if (temp.style.display == '') {
		temp.style.display = "none";
		tempH.style.display = "";
	} else if (temp.style.display == "none") {
		temp.style.display = '';
		tempH.style.display = 'none';
	}
}
$(function() {
	// 产品清单
	$("#equinfo").yxeditgrid({
		objName : 'return[equ]',
		url:'?model=projectmanagent_return_returnequ&action=listJson',
    	param:{
        	'returnId' : $("#returnId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		isAddOneRow:false,
		isAdd : false,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 200
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '从表id',
			name : 'contractequId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '最大可执行数量',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '退货数量',
			name : 'number',
			tclass : 'txtshort',
			width : 100,
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = $("#equinfo_cmp_number" + rowNum).val()*1;
					maxNum = $("#equinfo_cmp_maxNum" + rowNum).val();
					if(!isNum(thisNumber)){
                         alert("请输入正整数")
                         var g = $(this).data("grid");
                         g.setRowColValue(rowNum, "number",maxNum, true);
                     }
					if(thisNumber <= 0 || thisNumber > maxNum){
                        alert("数量不得大于"+maxNum+",或小于等于0 ");
                       var g = $(this).data("grid");
                        g.setRowColValue(rowNum, "number",maxNum, true);
					}

				}
			}
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	});
});
