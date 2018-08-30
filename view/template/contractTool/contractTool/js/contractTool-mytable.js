var tempEvent;

//响应事件
window.addEventListener('message',function(event) {
	// if(event.origin !== 'http://172.16.13.33:8088') return; // 这里验证域名
	console.log('message received:  ' + event.data, event);
	event.source.postMessage(event.data, event.origin);
	tempEvent = event;
},false);

function openTab(url, title) {
    // 如果当前是最高窗口，则直接打开一个窗口
    if (window.parent==window) {
        window.open(url);
    } else {
        try {
            var openTabParent = parent.parent.openTab;

            openTabParent(url, title);
        } catch (e) {
            if (tempEvent) {
                tempEvent.source.postMessage({url: url, title: title}, tempEvent.origin);
            } else {
                try {
                    window.open(url);
                } catch (e) {
                    alert('功能尚未准备好，请稍候重试或者点击导航栏刷新。');
                }
            }
        }
    }
}

function conRrl(time,fstate){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=buildContract&finishStatus='+fstate+'&date='+date,'合同信息解读')
}

function deliveryRrl(time,num){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(num==null){
        num = '2,4';
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=deliveryContract&date='+date+'&state='+num,'合同交付')
}

function waitRrl(time,checkStatus){

    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(checkStatus==null){
        checkStatus = '';
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=waitingAccept&date='+date+'&checkStatus='+checkStatus,'合同验收')
}

function invoiceRrl(time,finishStatus){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(finishStatus == null){
        finishStatus = "all";
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=invoiceContract&date='+date+'&finishStatus='+finishStatus,'合同开票收款')
}

function closeRrl(time){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=closeContract&date='+date,'合同关闭')
}

function archiveRrl(time,signStatusArr){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(signStatusArr == null){
        signStatusArr = "0,2";
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=contractArchive&date='+date+'&signStatusArr='+signStatusArr,'合同文本归档')
}

//待办任务-合同验收
function toWaitTask(){
    var date = '';
    openTab('index1.php?model=contract_checkaccept_checkaccept&identify=contractTool','合同待验收')
}

//待办任务-开票收款确认
function toInvoiceTask(){
    var date = '';
    openTab('index1.php?model=contract_contract_contract&action=financialTdayList&identify=contractTool','开票收款待确认')
}

//待办任务-合同待归档
function toArchiveTask(){
    var date = '';
    openTab('index1.php?model=contract_contract_contract&action=Signin&identify=contractTool','合同待归档')
}

//获取对应时间
function getTimes(time){
    var date = '';
    var d = '';
    if(time=='month'){
        d = new Date();
        var month = d.getMonth()+1;
        if(month<10){
            month = '0'+month;
        }
        date = d.getFullYear()+'-'+month;
    }else{
        d = new Date();
        date = d.getFullYear();
    }
    return date;
}