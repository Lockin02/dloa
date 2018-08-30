$(document).ready(function() {

});

//更新产品缓存数据
function updateFun(){
	$.ajax({
	    type: "POST",
	    url: "?model=goods_goods_goodscache&action=updateCache",
	    async: false,
	    success: function(data){
			if(data){
				alert('更新成功');
			}else{
				alert('更新失败');
			}
		}
	});
}