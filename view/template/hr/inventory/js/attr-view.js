$(document).ready(function() {

			/*
			 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' }
			 * });
			 */
			var attrType = $("#attrType").text().replace(/\s+/g, "");

			if (attrType == "������") {
				$("#attrVal").show();
			} else
				$("#attrVal").hide();

			$("#attrValList").yxeditgrid({
						objName : 'attr[attrvals]',
						type : 'view',
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
									type : 'txt'
								}]
					});
		})