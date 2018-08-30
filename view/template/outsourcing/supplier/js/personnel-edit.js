$(document).ready(function() {
	$("#bankListInfo").yxeditgrid({
		objName : 'personnel[areaSkill]',
		url : '?model=outsourcing_supplier_personLevel&action=listJson',
		param : {
			dir : 'ASC',
			parentId :$("#id").val()
		},
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

	validate({
				"userName" : {
					required : true
				},
				"identityCard" : {
					required : true
				}
			});
   })