$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */

			$("#attrType").val($("#attrType").attr('val'));
			if ($("#attrType").attr('val') == 1) {
				$("#attrVal").show();
			} else
				$("#attrVal").hide();
			$("#attrType").bind('change', function() {
						if ($("#attrType option:selected").val() == 1) {
							$("#attrVal").show();
						} else
							$("#attrVal").hide();
					})

			$("#attrValList").yxeditgrid({
						objName : 'attr[attrvals]',
						url : '?model=hr_inventory_attrval&action=listJson',
						param : {
							attrId : $("#id").val(),
							dir : 'ASC'
						},
						tableClass : 'form_in_table',
						colModel : [{
									display : 'id',
									name : 'id',
									type : 'hidden'
								}, {
									display : '����ֵ',
									name : 'valName',
									type : 'txt',
									validation : {
										required : true
									}
								}]
					});

			/**
			 * ��֤��Ϣ
			 */
			validate({
						"attrName" : {
							required : true
						}
					});

		})