$(function() {
	// 产品清单
	$("#productInfo").yxeditgrid({
		objName : 'borrowreturn[product]',
		url:'?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJson',
		tableClass : 'form_in_table',
		param:{
        	'returnId' : $("#id").val()
        },
        event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var i = 0; i < rowCount; i++) {
					var num = $("#productInfo_cmp_executedNum" + i).val();
					$("#productInfo_cmp_number" + i).val(num);
				}
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'equId',
			name : 'equId',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productNo',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '归还数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			name : 'serialId',
			display : '序列号ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '序列号',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly',
			process : function($input, rowData) {
				var rownum = $input.data('rowNum');// 第几行
				var $img = $("<img src='images/add_snum.png' align='absmiddle' title='选择序列号'>");
				$img.click(function(productId, rownum) {
                    return function() {
                        serialNum(productId, rownum);
                    }
                }(rowData.productId,rownum));
				$input.before($img);
			},
			event : {
				dblclick  : function() {
					var serial = $(this).val();
					if(serial != ""){
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
							+ serial
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=800");
					}
				}
			}
		}],
		isAddOneRow:false,
		isAdd : false
	});
});

// 选择序列号
function serialNum(productId, rownum) {
	var serialId = $("#productInfo_cmp_serialId"+rownum).val();
	var serialName = $("#productInfo_cmp_serialName"+rownum).val();
    var borrowCode = $("#borrowCode").val();
    var borrowId = $("#borrowId").val();
	showThickboxWin('?model=stock_serialno_serialno&action=toChooseFrameForRe'
        + '&productId=' + productId
        + '&elNum=' + rownum
        + '&serialId=' + serialId
        + '&serialName=' + serialName
        + '&relDocCode=' + borrowCode
        + '&relDocId=' + borrowId
        + '&relDocType=oa_borrow_borrow'
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=700");
}

/*
 * 提交确认
 */
function confirmSubmit() {
	if (confirm("你确定要提交吗?")) {
		$("#form1").submit();
	}else {
		return false;
	}
}

function checkform(){
	var num = $("#productInfo").yxeditgrid("getCurShowRowNum");
	for(var i=0; i<num; i++){
		var number = $("#productInfo_cmp_number"+i).val();
		var str = $("#productInfo_cmp_serialId"+i).val();
		var arr = str.split(",");
		if(number < arr.length){
			alert("所选序列号数量不能大于归还数量");
			return false;
		}
	}
	return true;
}