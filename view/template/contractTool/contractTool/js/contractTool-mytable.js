var tempEvent;

//��Ӧ�¼�
window.addEventListener('message',function(event) {
	// if(event.origin !== 'http://172.16.13.33:8088') return; // ������֤����
	console.log('message received:  ' + event.data, event);
	event.source.postMessage(event.data, event.origin);
	tempEvent = event;
},false);

function openTab(url, title) {
    // �����ǰ����ߴ��ڣ���ֱ�Ӵ�һ������
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
                    alert('������δ׼���ã����Ժ����Ի��ߵ��������ˢ�¡�');
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
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=buildContract&finishStatus='+fstate+'&date='+date,'��ͬ��Ϣ���')
}

function deliveryRrl(time,num){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(num==null){
        num = '2,4';
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=deliveryContract&date='+date+'&state='+num,'��ͬ����')
}

function waitRrl(time,checkStatus){

    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(checkStatus==null){
        checkStatus = '';
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=waitingAccept&date='+date+'&checkStatus='+checkStatus,'��ͬ����')
}

function invoiceRrl(time,finishStatus){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(finishStatus == null){
        finishStatus = "all";
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=invoiceContract&date='+date+'&finishStatus='+finishStatus,'��ͬ��Ʊ�տ�')
}

function closeRrl(time){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=closeContract&date='+date,'��ͬ�ر�')
}

function archiveRrl(time,signStatusArr){
    var date = '';
    if(time!=null){
        date = getTimes(time);
    }
    if(signStatusArr == null){
        signStatusArr = "0,2";
    }
    openTab('index1.php?model=contractTool_contractTool_contractTool&action=contractArchive&date='+date+'&signStatusArr='+signStatusArr,'��ͬ�ı��鵵')
}

//��������-��ͬ����
function toWaitTask(){
    var date = '';
    openTab('index1.php?model=contract_checkaccept_checkaccept&identify=contractTool','��ͬ������')
}

//��������-��Ʊ�տ�ȷ��
function toInvoiceTask(){
    var date = '';
    openTab('index1.php?model=contract_contract_contract&action=financialTdayList&identify=contractTool','��Ʊ�տ��ȷ��')
}

//��������-��ͬ���鵵
function toArchiveTask(){
    var date = '';
    openTab('index1.php?model=contract_contract_contract&action=Signin&identify=contractTool','��ͬ���鵵')
}

//��ȡ��Ӧʱ��
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