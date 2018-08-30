/**使用场合：当某个页面数据修改时，需要执行某些操作时
在页面的body加载事件（onload）中加上initFileds()就可以记录页面的初始数据
在需要判断页面数据是否改变时调用checkModification()方法就可判断数据是否改变
返回值为true就是已经改变
返回值为false就是没有改变
*/

    // 页面编辑数据
    var inputsData;
    var textareasData;
    var selectsData;
    // 记录下表单中的原始值
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
     * 判断表单中值是否被修改了
     * submitCommand 表单有改动时,执行的javascript代码
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
         	alert("提示：数据没有发生改变!");
         	return false;
         }
    }


	//包含忽略元素的判断 —————————————————————————— //
    // 记录下表单中的原始值
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
     * 判断表单中值是否被修改了
     * submitCommand 表单有改动时,执行的javascript代码
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
	         	alert("提示：数据没有发生改变!");
			}
         	return false;
		}
	}