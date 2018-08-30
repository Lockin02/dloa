$(function() {

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"taskNo" : {
			required : true

		},
		"beginDate" : {
			custom : ['date']
		},
		"endDate" : {
			custom : ['date']

		},
		"dept" : {
			required : true

		},
		"man" : {
			required : true

		}

	});

	//ѡ����Ա����������Ϣ
	$("#man").yxselect_user({
		hiddenId : 'manId'
//		isGetDept : [true, "deptId", "dept"]

	});

	//ѡ���̵�������
	$("#taskNo").yxcombogrid_checktask({
		hiddenId : 'taskId',
		width : 600,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#dept").val(data.deptName);
					$("#deptId").val(data.deptId);
				}
			}
		}
	});

});
//��֤��ʼʱ�����ʱ���Ⱥ�
function Check() {

	var start = $("#beginDate").val();
	var end = $("#endDate").val();

	if (start != '' && end != '') {
		start = start.split('-');
		end = end.split('-');
		var start1 = new Date(start[0], start[1] - 1, start[2]);
		var end1 = new Date(end[0], end[1] - 1, end[2]);

		if (start1 > end1) {
			alert("����ʱ�䲻���ڿ�ʼʱ��֮ǰ��");
			return false;
		}
	}
}