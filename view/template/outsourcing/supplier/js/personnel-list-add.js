$(document).ready(function() {
		$("#bankListInfo").yxeditgrid({
		objName : 'personnel[areaSkill]',
		dir : 'ASC',
		colModel : [{
        				name : 'skillTypeCode',
      					display : '��������',
      					type:'select',
      					datacode:'WBJNLX',
      					width:200,
      					sortable : true
                  },{
        				name : 'levelCode',
      					display : '����',
      					type:'select',
      					datacode:'WBRYJB',
      					sortable : true
                  },{
        				name : 'remark',
      					display : '��ע',
      					width:300,
      					sortable : true
                  }]
	});

		 /**
			 * ����Ψһ����֤
			 */

			var url = "?model=outsourcing_supplier_personnel&action=checkRepeat";
//			if ($("#id").val()) {
//				url += "&id=" + $("#id").val();
//			}
			$("#identityCard").ajaxCheck({
						url : url,
						alertText : "* ����Ա��Ϣ�Ѵ���",
						alertTextOk : "* OK"
					});
	//��ѡ����
		$("#suppName").yxcombogrid_outsupplier({
			hiddenId : 'suppId',
			gridOptions : {
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e,row,data) {
	                                    $("#suppCode").val(data.suppCode);
					}
				}
			}
		});

	validate({
				"userName" : {
					required : true
				}
			});
 })