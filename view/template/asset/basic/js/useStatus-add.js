	function check_all() {
		if ($("#name").val() == '0') {
			$('#_dataName1').html('ʹ��״̬��ѡ��');
			return false;
		}
		if ($("#deprFlag").val() == 'choose') {
			$('#_dataName2').html('�����۾���ѡ��');
			return false;
		}
		return true;
	}