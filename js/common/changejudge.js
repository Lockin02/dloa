/**ʹ�ó��ϣ���ĳ��ҳ�������޸�ʱ����Ҫִ��ĳЩ����ʱ
��ҳ���body�����¼���onload���м���initFileds()�Ϳ��Լ�¼ҳ��ĳ�ʼ����
����Ҫ�ж�ҳ�������Ƿ�ı�ʱ����checkModification()�����Ϳ��ж������Ƿ�ı�
����ֵΪtrue�����Ѿ��ı�
����ֵΪfalse����û�иı�
*/

    // ҳ��༭����
    var inputsData;
    var textareasData;
    var selectsData;
    // ��¼�±��е�ԭʼֵ
    function initFileds() {
        var inputs = document.getElementsByTagName("input");
        var textareas = document.getElementsByTagName("textarea");
        var selects = document.getElementsByTagName("select");
        inputsData = new Array(inputs.length);
        for (var i=0;i<inputs.length;i++) {
            inputsData[i] = inputs[i].value;
            if (inputs[i].type=="radio") {
                inputsData[i]=inputs[i].checked;
            }
        }
        textareasData = new Array(textareas.length);
        for (var i=0;i<textareas.length;i++) {
            textareasData[i] = textareas[i].value;
        }
        selectsData = new Array(selects.length);
        for (var i=0;i<selects.length;i++) {
            selectsData[i] = selects[i].value;
        }
    }
    /*
     * �жϱ���ֵ�Ƿ��޸���
     * submitCommand ���иĶ�ʱ,ִ�е�javascript����
     */
    function checkModification(submitCommand) {
        var inputs = document.getElementsByTagName("input");
        var textareas = document.getElementsByTagName("textarea");
        var selects = document.getElementsByTagName("select");
        var hasBeenChanged = false;
        for (var i=0;i<inputs.length;i++) {
            if (inputs[i].type=="radio"&&(inputs[i].checked!=inputsData[i])) {
                hasBeenChanged = true;
                inputsData[i]=inputs[i].checked;
            }
            if (inputs[i].type!="radio"&&inputsData[i]!=inputs[i].value) {
             if(inputs[i].name!="actionType"){
                 hasBeenChanged = true;
                }
                inputsData[i]=inputs[i].value;
            }
        }
        for (var i=0;i<textareas.length;i++) {
            if (textareasData[i]!=textareas[i].value) {
                hasBeenChanged = true;
                textareasData[i]=textareas[i].value;
            }
        }
        for (var i=0;i<selects.length;i++) {
            if (selectsData[i]!=selects[i].value) {
                hasBeenChanged = true;
                selectsData[i]=selects[i].value;
            }
        }
         if (hasBeenChanged) {{
              return true;
         	}
         }else{
         	alert("��ʾ������û�з����ı�!");
         	return false;
         }
    }


	//��������Ԫ�ص��ж� ���������������������������������������������������� //
    // ��¼�±��е�ԭʼֵ
    function initWithoutIgnore() {
        var inputs = document.getElementsByTagName("input");
        var textareas = document.getElementsByTagName("textarea");
        var selects = document.getElementsByTagName("select");
        inputsData = new Array(inputs.length);
        for (var i=0;i<inputs.length;i++) {
        	if(inputs[i].className.indexOf('ignore') != -1) continue;

        	inputsData[i] = inputs[i].value;
            if (inputs[i].type=="radio") {
                inputsData[i]=inputs[i].checked;
            }
        }
        textareasData = new Array(textareas.length);
        for (var i=0;i<textareas.length;i++) {
            if(textareas[i].className.indexOf('ignore') == -1){
            	textareasData[i] = textareas[i].value;
            }
        }
        selectsData = new Array(selects.length);
        for (var i=0;i<selects.length;i++) {
        	if(selects[i].className.indexOf('ignore') != -1) continue;
			selectsData[i] = selects[i].value;
        }
    }

    /*
     * �жϱ���ֵ�Ƿ��޸���
     * submitCommand ���иĶ�ʱ,ִ�е�javascript����
     */
    function checkWithoutIgnore(submitCommand) {
        var inputs = document.getElementsByTagName("input");
        var textareas = document.getElementsByTagName("textarea");
        var selects = document.getElementsByTagName("select");
        var hasBeenChanged = false;
        for (var i=0;i<inputs.length;i++) {
        	if(inputs[i].className.indexOf('ignore') != -1 || inputs[i].value == undefined) continue;

            if (inputs[i].type=="radio"&&(inputs[i].checked!=inputsData[i])) {
                hasBeenChanged = true;
            }
            if (inputs[i].type!="radio"&&inputsData[i]!=inputs[i].value) {
				if(inputs[i].name!="actionType"){
					hasBeenChanged = true;
                }
            }
        }
        for (var i=0;i<textareas.length;i++) {
            if(textareas[i].className.indexOf('ignore') == -1){
	            if (textareasData[i]!=textareas[i].value) {
	                hasBeenChanged = true;
	            }
            }
        }
        for (var i=0;i<selects.length;i++) {
        	if(selects[i].className.indexOf('ignore') != -1) continue;

            if (selectsData[i]!=selects[i].value) {
                hasBeenChanged = true;
            }
        }
		if (hasBeenChanged) {
			{
				return true;
			}
		}else{
			if(submitCommand != undefined){
	         	alert(submitCommand);
			}else{
	         	alert("��ʾ������û�з����ı�!");
			}
         	return false;
		}
	}