	function check_all() {
		if ($("#name").val() == '0') {
			$('#_dataName1').html('使用状态请选择！');
			return false;
		}
		if ($("#deprFlag").val() == 'choose') {
			$('#_dataName2').html('计提折旧请选择！');
			return false;
		}
		return true;
	}