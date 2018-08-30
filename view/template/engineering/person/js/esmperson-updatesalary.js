$(document).ready(function() {
	//绑定点击事件
	$("#confirmBtn").click(function(){
		if(confirm('更新数据会需要花费较长时间，确定进行此操作吗？')){
			$("#showMsg").text('数据更新中......');
			//显示进度图
			var imgObj = $("#imgLoading");
			imgObj.show();

			//禁用按钮
			var btnObj = $(this);
			btnObj.attr('disabled',true);

			setTimeout(function(){
				//调用更新功能
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_person_esmperson&action=updateSalary",
				    data : {"thisYear" : $("#year").val(),"thisMonth" : $("#month").val()},
				    async: false,
				    success: function(data){
				    	if(data=='0'){
							$("#showMsg").text('无数据更新');
				    	}else{
				    		if(data == "1"){
								$("#showMsg").text('更新成功');
				    		}else{
				    			$("#showMsg").text(data);
				    		}
				    	}
						imgObj.hide();
						btnObj.attr('disabled',false);
					}
				});
			},200);
		}
	});
});
