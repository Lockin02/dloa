var show_page = function(page) {
	$("#ticketlist").yxgrid("reload");
};

$(function() {
	$("#ticketlist").yxgrid({
		model : 'flights_ticketagencies_ticket',
		title : '订票机构',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'institutionId',
			display : '机构编码',
			sortable : true,
			width : 100
		}, {
			name : 'institutionName',
			display : '机构名称',
			sortable : true,
			width : 200
		}, {
			name : 'agencyType',
			display : '机构类型',
			sortable : true,
			width : 150
		}, {
			name : 'bookingBusiness',
			display : '订票业务',
			sortable : true,
			width : 200
		}, {
			name : 'institutionBusiness',
			display : '机构描述',
			sortable : true,
			width : 300
		}],
		toAddConfig : {
			action : 'toAdd'
		},
		toEditConfig:{
			action : 'toEdit'
		},
		toViewConfig:{
			action : 'toView'
		},
		//过滤数据
		comboEx : [{
			text : '机构类型',
			key : 'agencyType',
			data : [{
				text : '票务',
				value : '票务'
			}, {
				text : '航空',
				value : '航空'
			}]
		}],
		searchitems : [{
			display : "机构编码",
			name : 'institutionIdSearch'
		},{
			display : "机构名称",
			name : 'institutionNameSearch'
		}]
	});
});