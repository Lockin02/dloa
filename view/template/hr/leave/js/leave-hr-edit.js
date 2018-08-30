      $(function(){
  		//办公软件状态
		$('select[name="leave[softSate]"] option').each(function() {
			if( $(this).val() == $("#softSateSelect").val() ){
				$(this).attr("selected","selected'");
			}
		});

		//用工终止
		$('select[name="leave[employmentEnd]"] option').each(function() {
			if( $(this).val() == $("#employmentEndSelect").val() ){
				$(this).attr("selected","selected'");
			}
		});

			// 验证信息
			validate({
				"comfirmQuitDate" : {
					required : true
				},
				"salaryEndDate" : {
					required : true
				}
			});
       });
