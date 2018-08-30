$(document).ready(function() {

	$("#equInfo").yxeditgrid({
		objName : 'encryption[equ]',
		url : '?model=stock_delivery_encryptionequ&action=listJson',
		param : {
			parentId : $("#id").val(),
		},

		type : 'view',

		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
			width : 80
		},{
			display : '物料名称',
			name : 'productName',
			width : 150
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '规格型号',
			name : 'pattern',
			width : 50
		},{
			display : '单位名称',
			name : 'unitName',
			width : 50
		},{
			display : '加密锁任务数量',
			name : 'produceNum',
			width : 80
		},{
			display : '预计完成时间',
			name : 'planFinshDate',
			width : 80
		},{
			display : '状态',
			name : 'state',
			width : 50,
			process : function ($input ,rowData) {
				if (rowData.state == 1) {
					return '已完成';
				} else {
					return '未完成';
				}
			}
		},{
			display : '实际完成时间',
			name : 'planFinshDate',
			width : 80
		},{
			display : '备注',
			name : 'remark',
			align : 'left'
		},{
			display : '加密配置',
			name : 'license',
			width : 50,
			process : function(v ,row) {
				if (row.license != "") {
					return "<a href='#' onclick='showLicense(\"" + row.license + "\")'>查看</a>";
				}
			}
		}]
	});
});

//license查看方法
function showLicense(thisVal){
	if( thisVal == 0 || thisVal=='' || thisVal=='undefined' ){
		alert('该物料无加密信息！');
		return false;
	}
	url = "?model=yxlicense_license_tempKey&action=toViewRecord"
		+ "&id=" + thisVal
	;

	var sheight = screen.height - 200;
	var swidth = screen.width - 70;
	var winoption = "dialogHeight:" + sheight+"px;dialogWidth:" + swidth + "px;status:yes;scroll:yes;resizable:yes;center:yes";

	showModalDialog(url, '',winoption);
}