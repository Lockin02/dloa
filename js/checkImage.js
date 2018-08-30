/*----------------------------------------
�ļ��ϴ�ǰ̨���Ƽ�����

Զ��ͼƬ��⹦��
����ϴ��ļ�����

�����ͼƬ�ļ���ʽ�Ƿ���ȷ
�����ͼƬ�ļ���С
�����ͼƬ�ļ����
�����ͼƬ�ļ��߶�
ͼƬԤ��
-----------------------------------------*/

var ImgObj = new Image();
//����һ��ͼ�����

var AllImgExt = ".jpg|.jpeg|.gif|.bmp|.png|";
//ȫ��ͼƬ��ʽ����
var FileObj,ImgFileSize,ImgWidth,ImgHeight,FileExt,ErrMsg,FileMsg,HasCheked,IsImg;
//ȫ�ֱ��� ͼƬ�������

/*����Ϊ���Ʊ���*/
var AllowExt = AllImgExt;
//�����ϴ����ļ�����,0Ϊ������
//ÿ����չ�����Ҫ��һ��"|",Сд��ĸ��ʾ
var AllowImgFileSize = 700;
//�����ϴ�ͼƬ�ļ��Ĵ�С,0Ϊ������,��λ:KB
var AllowImgWidth = 1500;
//�����ϴ���ͼƬ�Ŀ��,0Ϊ������,��λ:px(����)
var AllowImgHeight = 1500;
//�����ϴ���ͼƬ�ĸ߶�,0Ϊ������,��λ:px(����)

HasChecked = false;

function CheckProperty(obj) {                        //���ͼ������

    FileObj = obj;
    if (ErrMsg != "") {                                             //����Ƿ�Ϊ��ȷ��ͼ���ļ�,���س�����Ϣ������
        ShowMsg(ErrMsg, false);
        return false;
        //����
    }

    if (ImgObj.readyState != "complete") {          //���ͼ����δ������ɽ���ѭ�����
        setTimeout("CheckProperty(FileObj)", 500);
        return false;
    }

    ImgFileSize = Math.round(ImgObj.fileSize / 1024 * 100) / 100;
    //ȡ��ͼƬ�ļ��Ĵ�С
    ImgWidth = ImgObj.width;
    //ȡ��ͼƬ�Ŀ��
    ImgHeight = ImgObj.height;
    //ȡ��ͼƬ�ĸ߶�
    FileMsg = "\nͼƬ��С:" + ImgWidth + "*" + ImgHeight + "px";
    FileMsg = FileMsg + "\nͼƬ�ļ���С:" + ImgFileSize + "Kb";
    FileMsg = FileMsg + "\nͼƬ�ļ���չ��:" + FileExt;

    if (AllowImgWidth != 0 && AllowImgWidth < ImgWidth) {
        ErrMsg = ErrMsg + "\nͼƬ��ȳ�������.���ϴ����С��" + AllowImgWidth + "px���ļ�," +
                 "��ǰͼƬ���Ϊ" + ImgWidth + "px";
    }
    if (AllowImgHeight != 0 && AllowImgHeight < ImgHeight) {
        ErrMsg = ErrMsg + "\nͼƬ�߶ȳ�������.���ϴ��߶�С��" + AllowImgHeight + "px���ļ�," +
                 "��ǰͼƬ�߶�Ϊ" + ImgHeight + "px";
    }
    if (AllowImgFileSize != 0 && AllowImgFileSize < ImgFileSize) {
        ErrMsg = ErrMsg + "\nͼƬ�ļ���С��������.���ϴ�С��" + AllowImgFileSize + "KB���ļ�," +
                 "��ǰ�ļ���СΪ" + ImgFileSize + "KB";
    }
    if (ErrMsg != "") {
        ShowMsg(ErrMsg, false);
    }
    else {
        ShowMsg(FileMsg, true);
    }
}

ImgObj.onerror = function() {
    ErrMsg = '\nͼƬ��ʽ����ȷ����ͼƬ����!'
}

/*����Ϊ��ʾ��ʾ��Ϣ,tf=true��ʾ�ļ���Ϣ,tf=false��ʾ������Ϣ,msg-��Ϣ����*/
function ShowMsg(msg, tf) {
    msg = msg.replace("\n", "<li>");
    msg = msg.replace(/\n/gi, "<li>");
    if (!tf) {
        FileObj.outerHTML = FileObj.outerHTML;
        PreviewImg.innerHTML = msg;
        HasChecked = false;
    }
    else {
        if (IsImg) {
            PreviewImg.innerHTML = "<img src='" + ImgObj.src + "' width='"+ImgWidth+"' height='"+ImgHeight+"'>"
        }
        else {
            PreviewImg.innerHTML = "��ͼƬ�ļ�";
        }
        //MsgList.innerHTML = msg;
        HasChecked = true;
    }
}

function CheckExt(obj) {
    ErrMsg = "";
    FileMsg = "";
    FileObj = obj;
    IsImg = false;
    HasChecked = false;
    PreviewImg.innerHTML = "<div align=\"center\">Ԥ����</div>";
    if (obj.value == "") {
        return false;
    }
    PreviewImg.innerHTML = "�ļ���Ϣ������...";
    FileExt = obj.value.substr(obj.value.lastIndexOf(".")).toLowerCase();
    if (AllowExt != 0 && AllowExt.indexOf(FileExt + "|") == -1) {                 //�ж��ļ������Ƿ������ϴ�
        ErrMsg = "\n���ļ����Ͳ������ϴ�!\n���ϴ�" + AllowExt + "���͵��ļ� \n��ǰ�ļ�����Ϊ" + FileExt;
        ShowMsg(ErrMsg, false);
        return false;
    }

    if (AllImgExt.indexOf(FileExt + "|") != -1) {                   //�����ͼƬ�ļ�,�����ͼƬ��Ϣ����
        IsImg = true;
        ImgObj.src = obj.value;
        CheckProperty(obj);
        return false;
    }
    else {
        FileMsg = "\n�ļ���չ��:" + FileExt;
        ShowMsg(FileMsg, true);
    }
}