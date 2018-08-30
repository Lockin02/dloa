// ��ͬ�����ӱ�
$(function() {
	// ��Ŀ����
	$("#projectList").yxeditgrid({
		objName : 'employment[project]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtmiddle',
			readonly : true,
			type : 'date'
		}, {
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtmiddle',
			readonly : true,
			type : 'date'
		}, {
			display : '��Ŀ����',
			name : 'projectName',
			tclass : 'txt'
		}, {
			display : '��Ҫ���ú��ּ���',
			name : 'projectSkill',
			tclass : 'txt'
		}, {
			display : '����Ŀ�еĽ�ɫ',
			name : 'projectRole',
			tclass : 'txt'
		}]
	});
	// ��������
	$("#work").yxeditgrid({
		objName : 'employment[work]',
		tableClass : 'form_in_table',
		isFristRowDenyDel : true,
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		}, {
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		}, {
			display : '��˾����',
			name : 'company',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'dept',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : 'ְλ',
			name : 'position',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'treatment',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '��ְԭ��',
			name : 'leaveReason',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '֤����/ְ��绰',
			name : 'prove',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	});
	// ��������
	$("#education").yxeditgrid({
		objName : 'employment[education]',
		tableClass : 'form_in_table',
		isFristRowDenyDel : true,
		colModel : [{
			display : '��ʼʱ��',
			name : 'beginDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		}, {
			display : '����ʱ��',
			name : 'closeDate',
			tclass : 'txtshort',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		}, {
			display : 'ѧУ����/��ѵ����',
			name : 'organization',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : 'רҵ����ѵ����',
			name : 'content',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '֤��',
			name : 'certificate',
			tclass : 'txt',
			validation : {
				required : true
			}
		}]
	});

	// ��ͥ��Ա
	$("#family").yxeditgrid({
		objName : 'employment[family]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '����',
			name : 'name',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'age',
			tclass : 'txtshort'
		}, {
			display : '�뱾�˹�ϵ',
			name : 'relation',
			tclass : 'txtshort'
		}, {
			display : '������λ',
			name : 'work',
			tclass : 'txtlong'
		}, {
			display : 'ְλ',
			name : 'post',
			tclass : 'txtshort'
		}, {
			display : '��ϵ��ʽ',
			name : 'information',
			tclass : 'txt'
		}]
	});
});
