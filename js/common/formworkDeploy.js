$(function(){
   formworkLimit();
   formworkApply();
})
/**
 * 模板配置
 */
function formworkDeploy(){
	var  deploy = $("#deploy").val();
     showThickboxWin("?model=hr_formwork_formwork&action=formworkdeploy&type="
                 + deploy
				 + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700");
}

/**
 * 判断模板
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

//渲染模板
function formworkApply(){
	$("#formwork").yxcombogrid_formwork({
		hiddenId : 'id',
		gridOptions : {
			showcheckbox : false,
//			param :{"ids":$('#ids').val()},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#formwork").val(data.formworkName);
					//获取 模板内容
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