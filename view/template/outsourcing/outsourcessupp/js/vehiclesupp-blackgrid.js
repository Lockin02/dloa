var show_page = function(page) {
	$("#vehiclesuppGrid").yxgrid("reload");
};
$(function() {
	var buttonsArr = []; //表头按钮数组
	var isAddArr = false; //新增权限
	var excelInArr = {
		name : 'exportIn',
		text : "导入",
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
			'limitArr' : '导入权限,新增权限'
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
        title : '车辆供应商',
        bodyAlign : 'center',
        isAddAction : isAddArr,
        isEditAction : false,
        isDelAction : false,
        showcheckbox : false,
        param:{'suppLevel':'0'},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'suppCode',
			display: '供应商编号',
			sortable: true,
			width: 70,
			process: function(v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcessupp_vehiclesupp&action=toViewTab&id=" + row.id + "\",1)'>" + v + "</a>";
			}
		},{
			name: 'suppName',
			display: '供应商名称',
			sortable: true,
			width: 150
		},{
			name: 'suppCategoryName',
			display: '供应商类型',
			sortable: true
		},{
			name: 'blackReason',
			display: '列入\\撤销黑名单原因',
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
				display : "供应商编号",
				name : 'suppCodeSea'
			},{
				display : "供应商名称",
				name : 'suppName'
			}]
 	});
 });