$(document).ready(function() {
	$("#bankListInfo").yxeditgrid({
		objName : 'personnel[areaSkill]',
		url : '?model=outsourcing_supplier_personLevel&action=listJson',
		param : {
			dir : 'ASC',
			parentId :$("#id").val()
		},
		type : 'view',
		dir : 'ASC',
		colModel : [{
        				name : 'skillTypeName',
      					display : '��������',
      					width:200,
      					sortable : true
                  },{
        				name : 'levelName',
      					display : '����',
      					width:80,
      					sortable : true
                  },{
        				name : 'remark',
      					display : '��ע',
      					width:300,
      					sortable : true
                  }]
	});

    })