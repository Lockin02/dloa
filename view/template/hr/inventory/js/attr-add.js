$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */
			$("#attrType").bind('change', function() {
						if ($("#attrType option:selected").val() == 1) {
							$("#attrVal").show();
						} else
							$("#attrVal").hide();
					})

			$("#attrValList").yxeditgrid({
						objName : 'attr[attrvals]',
						tableClass : 'form_in_table',
						colModel : [{
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