$(function(){
	$("#shipPlanDate").val(formatDate(new Date()));
	if( $('#pageAction').val() != 'feedback' ){
		$('tr.Feedback').hide();
	}
})

function checkThis( obj ){
	if( $('#number'+obj).val()*1 > $('#contRemain' + obj).val()*1 ){
		alert( '�˴η�������������ͬʣ�����������������룡' );
		$('#number' + obj).val('');
		$('#number' + obj+ '_v').val('');
	}
}

$(function(){
	$('#showHiddenItemBtn').hide();
	$("input[id^='isDelete']").each(function(){
		if($(this).val()==1){
			$(this).parent().parent().css('color','gray');
			$(this).parent().parent().hide();
			$('#showHiddenItemBtn').show();
		}
	})
	$('#changeTipsBtn').hide();
	if($('#changeTips').val()!=1){
		$('#changeTipsBtn').hide();
		$('#ifChange').hide();
	}
	$("input[id^='isRed']").each(function(){
		if($(this).val()==1){
			$(this).parent().parent().css('color','red');
		}
	})
})
function showHiddenItem(){
	$("input[id^='isDelete']").each(function(){
		if($(this).val()==1){
			if($(this).parent().parent().get(0).style.display=='none'){
				$(this).parent().parent().show();
				$("body").show();
			}else{
				$(this).parent().parent().hide();
			}
		}
	});
}
function cencleTips(){
	if (confirm('ȡ��������Ѻ󣬸÷����ƻ���ɫ����ָ�������ȷ��ȡ�����ѣ�')) {
		$.ajax({
			type : 'POST',
			url : '?model=stock_outplan_outplan&action=cancleTips',
			data : {
				id : $('#planId').val()
			},
			success : function(data) {
				if (data == 2) {
					alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
				} else {
					alert("���������ȡ����");
					location.reload();
				}
				return false;
			}
		});
	}
}