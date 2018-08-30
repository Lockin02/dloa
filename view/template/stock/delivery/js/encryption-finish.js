$(document).ready(function() {

	$("#equInfo").yxeditgrid({
		objName : 'encryption[equ]',
		url : '?model=stock_delivery_encryptionequ&action=listJson',
		param : {
			parentId : $("#id").val(),
		},

		isAddAndDel : false,

		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
			type : 'statictext',
			width : 100
		},{
			display : '物料名称',
			name : 'productName',
			type : 'statictext',
			width : 250
		},{
			display : '规格型号',
			name : 'pattern',
			type : 'statictext',
			width : 70
		},{
			display : '单位名称',
			name : 'unitName',
			type : 'statictext',
			width : 50
		},{
			display : '加密锁任务数量',
			name : 'produceNum',
			type : 'statictext',
			width : 80
		},{
			display : '加密锁任务数量隐藏后台用',
			name : 'produceNum',
			type : 'hidden'
		},{
			display : '预计完成时间',
			name : 'planFinshDate',
			type : 'statictext',
			width : 80
		},{
			display : '是否完成',
			name : 'state',
			width : 50,
			type : 'checkbox',
			checkVal : '1',
			process : function ($input ,rowData) {
				var rowNum = $input.data("rowNum");
				if (rowData.state == 1) {
					$("#equInfo_cmp_state" + rowNum).change(function () {
						alert("不能更改已完成的任务！");
						$(this).attr('checked' ,'checked')
					});
				}
			}
		},{
			display : '实际完成日期',
			name : 'actualFinshDate',
			type : 'statictext',
			width : 80
		},{
			display : '实际完成日期隐藏后台用',
			name : 'actualFinshDate',
			type : 'hidden'
		},{
			display : '备注',
			name : 'remark',
			type : 'statictext'
		},{
			display : '加密配置',
			name : 'license',
			type : 'statictext',
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