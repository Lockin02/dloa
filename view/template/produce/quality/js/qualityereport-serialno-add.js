$(function() {
//	$("#serial").yxeditgrid({
//		title : '���кű���',
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
//			display : '���к�',
//			name : 'sequence',
//			validation : {
//				required : true
//			}
//		}, {
//			display : '˵��',
//			name : 'remark',
//			type : 'textarea'
//		}]
//	});
	$('#seridalNo').keydown(function(e){
		if(e.keyCode==13){
			if($(this)[0].scrollHeight > 94){
				$(this).height($(this)[0].scrollHeight + 20);
			}

			var resultShow = strDeal($(this).val());
			$("#numShow").html("��ǰ������" + resultShow);
		}
	}).click(function(){
		var resultShow = strDeal($(this).val());
		$("#numShow").html("��ǰ������" + resultShow);
	}).blur(function(){
		var resultShow = strDeal($(this).val());
		$("#numShow").html("��ǰ������" + resultShow);
	});
})

//��ȡ���鳤�� - ���������
function strDeal(thisVal){
	var strArr = thisVal.Trim().split("\n");

	for(var i = 0; i < strArr.length ;i++){
		if(strArr[i].Trim() == ""){
			strArr.splice(i,1);
		}
	}
	return strArr.length;
}

//ȥ���ո�
String.prototype.Trim = function(){
	return this.replace(/(^\s*)|(\s*$)/g, "");
}

//�ύ����֤
function checkSubmit() {
	var serialNum=$("#numShow").html()*1;
	if($("#productNum").val()!=serialNum){
		return confirm('¼�����к�������Ӧ���������ȣ�ȷ�ϱ�����');
	}
}

/**
 * �������к�
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
