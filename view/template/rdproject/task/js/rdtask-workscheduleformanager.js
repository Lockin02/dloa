
$().ready(function() {
	//��Ŀ��ʼ��
    $("#projectNames").yxcombogrid_rdProject({
		hiddenId :  'projectIds',
		height : 300,
		gridOptions : {
			isTitle : true,
			param : { "managerId" : $("#userId").val() },
			event : {
				'after_row_check' : function(e, row, data){
					initGrid();
				}
			}
		}
	});

	//���ڳ�ʼ��
	function thisMonth(){
		 var d, s;

	    // ���� Date ����
	    d = new Date();
	    s = d.getFullYear() + "-";
	    s += ("0"+(d.getMonth()+1)).slice(-2) + "-01";

	    return s;
    }

    $("#beginDate").val(thisMonth());
    $("#overDate").val(formatDate(new Date()));

	//����֤
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            alert(msg);
        },
        onsuccess: function() {
            if ($("#overDate").val() < $("#beginDate").val()) {
            	alert('��ʼʱ�䲻�ܴ�����ֹʱ��');
                return false;
            } else {
                return true;
            }
        }
    });

    $("#beginDate").formValidator({
        onshow: "��ѡ��ƻ���ʼ����",
        onfocus: "��ѡ������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }); //.defaultPassed();

    $("#overDate").formValidator({
        onshow: "��ѡ��ƻ���������",
        onfocus: "��ѡ������",
        oncorrect: "OK"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }); //.defaultPassed();

    $("#projectNames").formValidator({
        onshow: "��ѡ����Ŀ",
        onfocus: "��ѡ����Ŀ",
        oncorrect: "OK"
    }).inputValidator({
		min: 2,
		max: 300,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "��ѡ����Ŀ"
		},
		onerror: "��ѡ����Ŀ"
    }); //.defaultPassed();

	//�����Ŀ��Ա��Ϊ�գ�����Ⱦ��Ա�������
	$("#memberNames").val("");
	$("#memberIds").val("");
	$("#projectNames").val("");
	$("#projectIds").val("");
})

//��ʼ�����
function initGrid(){
	var projectIds = $("#projectIds").val();
	$("#memberNames").yxcombogrid_rdmember('remove');
	$("#memberNames").val('');
	$("#memberIds").val('');

	if(projectIds ==""){
		return false;
	}

	//��Ŀ��Ա��ʼ��
    $("#memberNames").yxcombogrid_rdmember({
		hiddenId :  'memberIds',
		gridOptions : {
			param : { "isInternal" : 1 ,"projectIds" : projectIds },
			colModel : [{
					display : 'id',
					name : 'id',
					hide : true
				}, {
					display : '��Ŀ����',
					name : 'projectName',
					width : 180,
					hide : true
				}, {
					display : '��Ŀ���',
					name : 'projectCode',
					width : 130,
					hide : true
				}, {
					display : '��Ա����',
					name : 'memberName'
				}, {
					display : '��Ա����id',
					name : 'memberId',
					hide : true
				}, {
					display : '������Ϣ',
					name : 'description',
					width : 150,
					hide : true
				}
			]
		}
	});
}