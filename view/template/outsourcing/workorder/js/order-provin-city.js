$(document).ready(function() {
	var provinceUrl = "?model=system_procity_province&action=pageJson"; // ��ȡʡ��URL
	$('#province').append($("<option value=''>").html("��ѡ��ʡ��"));
	/* ��ȡʡ�ķ��� */
	function getProvinces(data) {
		var o = eval("(" + data + ")");
		var provinceArr = o.collection;
		for (var i = 0, l = provinceArr.length; i < l; i++) {
			var province = provinceArr[i];
			var option = $("<option title='"+province.provinceName+"'>").val(province.id)
					.text(province.provinceName);
			$('#province').append(option)
		}
	}
	// ʡ��ѡ��ı�
	$('#province').change(function() {
				$('#provinceName').val($(this).find("option:selected").text());
				var provinceId = $(this).val();
				if(provinceId==""){    //�ж��Ƿ�ѡ����ʡ�ݣ����û��ѡ�У���ʡ������Ϊ��   add by suxc 2011-08-22
					$('#provinceName').val("");
				}
			});
        //��ȡʡ��
                  $.ajax({
					    type : 'POST',
					    url : provinceUrl,
					    data:{
					        countryId : 1,
							pageSize : 999
					    },
					    async: false,
					    success : function(data){
							$('#province').children()
									.remove("option[value!='']");
							getProvinces(data);
							$('#provinceName').val("");
							if ($('#province').attr('val')) {
//									alert($('#province').attr('val'));
//								$('#province').val($('#province').attr('val'));
								$("#province option[title='"+$('#province').attr('val')+"']").attr("selected", true);
								$('#province').trigger('change');
							}
						}
					});
});