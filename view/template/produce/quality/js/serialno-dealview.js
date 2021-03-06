$(function() {
//	$("#serial").yxeditgrid({
//		title : '序列号编制',
//		objName : 'serialno[items]',
//		url : '?model=produce_quality_serialno&action=listJson',
//		param : {
//			relDocId : $("#relDocId").val(),
//			relDocType : 'oa_produce_quality_serialno'
//		},
//		isAddOneRow : true,
//		colModel : [{
//			display : 'id',
//			name : 'id',
//			sortable : true,
//			type : 'hidden'
//
//		}, {
//			display : '序列号',
//			name : 'sequence',
//			validation : {
//				required : true
//			}
//		}, {
//			display : '说明',
//			name : 'remark',
//			type : 'textarea'
//		}]
//	});
	var sequencesObj = $('#sequences');
	$('#sequences').keydown(function(e){
		if(e.keyCode==13){
			if($(this)[0].scrollHeight > 94){
				$(this).height($(this)[0].scrollHeight + 20);
			}

			var resultShow = strDeal($(this).val());
			$("#numShow").html(resultShow);
		}
	}).click(function(){
		var resultShow = strDeal($(this).val());
		$("#numShow").html(resultShow);
	}).blur(function(){
		var resultShow = strDeal($(this).val());
		$("#numShow").html(resultShow);
	});

	var t= sequencesObj.val();
	if(t != ""){
		sequencesObj.val("").focus().val(t + "\n");
	}else{
		sequencesObj.focus();
	}
})

//获取数组长度 - 清楚空数组
function strDeal(thisVal){
	var strArr = thisVal.Trim().split("\n");

	for(var i = 0; i < strArr.length ;i++){
		if(strArr[i].Trim() == ""){
			strArr.splice(i,1);
		}
	}
	return strArr.length;
}

//去除空格
String.prototype.Trim = function(){
	return this.replace(/(^\s*)|(\s*$)/g, "");
}

//提交表单验证
function checkSubmit() {
	var serialNum=$("#numShow").text();
	var productNum = $("#productNum").val()*1;
	if(productNum != serialNum){
		return confirm('录入序列号数量与应填数量不等，确认保存吗');
	}
	if(serialNum == 0){
		return confirm('当前没有录入序列号，如保存会清空原序列号信息，确认保存吗？');
	}
}

/**
 * 导入序列号
 */
function importSequence() {
	var productCode = $("#productCode").val();
	var productName = $("#productName").val();
	var productId = $("#productId").val();
	var pattern = $("#pattern").val();
	var relDocId = $("#relDocId").val();
	var productNum = $("#productNum").val();
	showThickboxWin("?model=produce_quality_serialno&action=toImportSerialno"
			+ "&productCode="
			+ productCode
			+ "&productName="
			+ productName
			+ "&productId="
			+ productId
			+ "&pattern="
			+ pattern
			+ "&relDocId="
			+ relDocId
			+ "&productNum="
			+ productNum
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500");
}

function show_page() {
	location.reload();
}
