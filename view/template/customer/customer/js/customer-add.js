$(function() {

			/* ѡ���������� */
			$("#AreaName").yxcombogrid_area({
				nameCol : 'AreaName',
				hiddenId : 'AreaId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#customer_areaLeader_id")
									.val(data.areaPrincipalId);
							$("#AreaName").val(data.areaName);
							$("#customer_areaLeader").val(data.areaPrincipal);
							$("#AreaId").val(data.id);
						}
					}
				}
			});
			// ��֯����ѡ��
			$("#customer_sellMan").yxselect_user({
						hiddenId : 'customer_sellMan_id',
						mode : 'check'
					});

			if ($("#isRelated").val()== 1) {
				$("#name").attr('readonly',true);
				$('#msg').html("����ʾ:�ÿͻ��Ѿ���ҵ��������,�޷��޸Ŀͻ�����.��");
			}

		});
function checkName() {
	if ($('#name').val() == '') {
		$('#icon').html('�ͻ����Ʋ���Ϊ�գ�');
		$("#NameOne").val(0);
	} else if ($('#name').val() != '') {
		var param = {
			model : 'customer_customer_customer',
			action : 'checkRepeat',
			ajaxCusName : $('#name').val()
		};
		if ($("#customerId").val() != '') {
			param.id = $("#customerId").val();
		}
		$.get('index1.php', param, function(data) {
					if (data == '1') {
						$('#icon').html('�Ѵ��ڵĿͻ����ƣ�');
						$("#NameOne").val(1);
					} else {
						$('#icon').html('�ͻ����ƿ��ã�');
						$("#NameOne").val(2);
					}
				})
	}
}
function check_all() {
	var cusName = $("#NameOne").val();
	if (cusName == 0) {
		alert("�ͻ����Ʋ���Ϊ�գ�")
		return false;
	} else if (cusName == 1) {
		alert("�ͻ������Ѵ��ڣ�")
		return false;
	}
	if ($('#countryName').val() == "�й�" && $('#cityName').val() == "") {
		alert("��ѡ�����");
		$("#cityName").focus();
		return false;
	}
	if ($("#AreaId").val() == ''){
	    alert("����ȷѡ������");
	    return false;
	}
	return true;
}
// ��֤�ֻ�
function mob() {
	var tel = $("#Mobile").val();
	var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
	if (t.test(tel) == false) {
		alert("����ȷ��д�绰��Ϣ��");
		$("#Mobile").val("");
		$("#Mobile").focus();
	}

}
// ��֤�̶��绰
function tell() {
	var tel = $('#Tell')
			.val()
			.replace(/(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/)
			* 1;
	if (!tel) {
		alert("����ȷ��д�绰��Ϣ��");
		$("#Tell").val("");
		$("#Tell").focus();
	}

}

// ��֤����
function email() {
	var Email = $("#Email").val();
	var E = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (E.test(Email) == false) {
		alert("����д��ȷ��������Ϣ");
		$("#Email").val("");
		$("#Email").focus();
	}
}
// ��֤�ʱ�
function Post() {

	var post = $('#PostalCode').val();
	var P = /^[0-9]{6}$/;
	if (P.test(post) == false) {
		alert("����ȷ��д�ʱ���Ϣ��");
		$("#PostalCode").val("");
		$("#PostalCode").focus();
	}

}

// ��֤����
function fax() {
	var faxx = $('#Fax')
			.val()
			.replace(/(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/)
			* 1;
	if (!faxx) {
		alert("����ȷ��д������Ϣ��");
		$("#Fax").val("");
		$("#Fax").focus();
	}

}
