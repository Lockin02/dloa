$(function(){
   formworkLimit();
   formworkApply();
})
/**
 * ģ������
 */
function formworkDeploy(){
	var  deploy = $("#deploy").val();
     showThickboxWin("?model=hr_formwork_formwork&action=formworkdeploy&type="
                 + deploy
				 + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700");
}

/**
 * �ж�ģ��
 */
function formworkLimit(){
    var  deploy = $("#deploy").val();
    $.ajax({
		url:'?model=hr_formwork_formwork&action=formworkLimit',
		type:'POST',
		data:{type:deploy},
		async: false,
		success:function(data){
			$("#ids").val(data);
		}
	});
}

//��Ⱦģ��
function formworkApply(){
	$("#formwork").yxcombogrid_formwork({
		hiddenId : 'id',
		gridOptions : {
			showcheckbox : false,
//			param :{"ids":$('#ids').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#formwork").val(data.formworkName);
					//��ȡ ģ������
					$.ajax({
						url:'?model=hr_formwork_formwork&action=formworkContent',
						type:'POST',
						data:{id:data.id},
						success:function(data){
							$("#remark").val(data);
						}
					});
 				}
			}
		}
	});
}