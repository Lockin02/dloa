var show_page = function(page) {
	$("#hardwarelistGrid").yxgrid("reload");
};
$(function() {
	$("#hardwarelistGrid").yxgrid({
		model : 'projectmanagent_hardware_hardware',
		title : '设备硬件选择',
		param : {'isUse' : '0'},
		showcheckbox : true,
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'hardwareName',
					display : '设备硬件名称',
					sortable : true,
					width : 400
				}],
		buttonsEx : [{
			name : 'Add',
			text : "确认",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
				if (rowData) {
					  var goodsTable = parent.document.getElementById("hardwareList");
	                  var rows = goodsTable.rows.length;
				    $.ajax({
					    type : 'POST',
					    url : '?model=projectmanagent_chance_chance&action=toSetHardwareInfo',
					    data:{
							goodsIds : rowIds,
							chanceId : $("#chanceId").val(),
							rows : rows
					    },
					    async: false,
					    success : function(data){
//					    	var obj = eval("(" + data +")");
//					    	alert(data)
					    	parent.$("#hardwareList").append(data);
					    	self.parent.tb_remove();
						}
					});


				} else {
					alert('请先选择记录');
				}
			}
		}],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}]
	});
});