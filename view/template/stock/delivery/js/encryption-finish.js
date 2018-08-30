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
			display : '���ϱ��',
			name : 'productCode',
			type : 'statictext',
			width : 100
		},{
			display : '��������',
			name : 'productName',
			type : 'statictext',
			width : 250
		},{
			display : '����ͺ�',
			name : 'pattern',
			type : 'statictext',
			width : 70
		},{
			display : '��λ����',
			name : 'unitName',
			type : 'statictext',
			width : 50
		},{
			display : '��������������',
			name : 'produceNum',
			type : 'statictext',
			width : 80
		},{
			display : '�����������������غ�̨��',
			name : 'produceNum',
			type : 'hidden'
		},{
			display : 'Ԥ�����ʱ��',
			name : 'planFinshDate',
			type : 'statictext',
			width : 80
		},{
			display : '�Ƿ����',
			name : 'state',
			width : 50,
			type : 'checkbox',
			checkVal : '1',
			process : function ($input ,rowData) {
				var rowNum = $input.data("rowNum");
				if (rowData.state == 1) {
					$("#equInfo_cmp_state" + rowNum).change(function () {
						alert("���ܸ�������ɵ�����");
						$(this).attr('checked' ,'checked')
					});
				}
			}
		},{
			display : 'ʵ���������',
			name : 'actualFinshDate',
			type : 'statictext',
			width : 80
		},{
			display : 'ʵ������������غ�̨��',
			name : 'actualFinshDate',
			type : 'hidden'
		},{
			display : '��ע',
			name : 'remark',
			type : 'statictext'
		},{
			display : '��������',
			name : 'license',
			type : 'statictext',
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