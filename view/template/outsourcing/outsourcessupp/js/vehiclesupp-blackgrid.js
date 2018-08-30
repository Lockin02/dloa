var show_page = function(page) {
	$("#vehiclesuppGrid").yxgrid("reload");
};
$(function() {
	var buttonsArr = []; //��ͷ��ť����
	var isAddArr = false; //����Ȩ��
	var excelInArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toExcelInBlack"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600");
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=outsourcing_outsourcessupp_vehiclesupp&action=getLimits',
		data : {
			'limitArr' : '����Ȩ��,����Ȩ��'
		},
		async : false,
		success : function(data) {
			var limit = data.replace('"','').split(',');
			if (limit[0] == 1) {
				buttonsArr.push(excelInArr);
			}
			if (limit[1] == 1) {
				isAddArr = true;
			}
		}
	});

	$("#vehiclesuppGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehiclesupp',
        title : '������Ӧ��',
        bodyAlign : 'center',
        isAddAction : isAddArr,
        isEditAction : false,
        isDelAction : false,
        showcheckbox : false,
        param:{'suppLevel':'0'},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'suppCode',
			display: '��Ӧ�̱��',
			sortable: true,
			width: 70,
			process: function(v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'suppName',
			display: '��Ӧ������',
			sortable: true,
			width: 150
		},{
			name: 'suppCategoryName',
			display: '��Ӧ������',
			sortable: true
		},{
			name: 'blackReason',
			display: '����\\����������ԭ��',
			sortable: true,
			width: 400,
			align: 'left'
		}],

		buttonsEx : buttonsArr,

		toAddConfig : {
			toAddFn : function(p, g) {
				showThickboxWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toAddBlacklist"
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=850");
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems : [{
				display : "��Ӧ�̱��",
				name : 'suppCodeSea'
			},{
				display : "��Ӧ������",
				name : 'suppName'
			}]
 	});
 });