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
			display : '���ϱ��',
			name : 'productCode',
			width : 80
		},{
			display : '��������',
			name : 'productName',
			width : 150
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '����ͺ�',
			name : 'pattern',
			width : 50
		},{
			display : '��λ����',
			name : 'unitName',
			width : 50
		},{
			display : '��������������',
			name : 'produceNum',
			width : 80
		},{
			display : 'Ԥ�����ʱ��',
			name : 'planFinshDate',
			width : 80
		},{
			display : '״̬',
			name : 'state',
			width : 50,
			process : function ($input ,rowData) {
				if (rowData.state == 1) {
					return '�����';
				} else {
					return 'δ���';
				}
			}
		},{
			display : 'ʵ�����ʱ��',
			name : 'planFinshDate',
			width : 80
		},{
			display : '��ע',
			name : 'remark',
			align : 'left'
		},{
			display : '��������',
			name : 'license',
			width : 50,
			process : function(v ,row) {
				if (row.license != "") {
					return "<a href='#' onclick='showLicense(\"" + row.license + "\")'>�鿴</a>";
				}
			}
		}]
	});
});

//license�鿴����
function showLicense(thisVal){
	if( thisVal == 0 || thisVal=='' || thisVal=='undefined' ){
		alert('�������޼�����Ϣ��');
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