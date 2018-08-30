/* ----------------------------------------------------------------
 IsEmail: Returns a Boolean if the specified Expression is a
          valid e-mail address. If Expression is null, false
          is returned.

 Parameters:
      Expression = e-mail to validate.

 Returns: Boolean
---------------------------------------------------------------- */
function IsEmail(Expression)
{
        if (Expression == null)
                return (false);

        var supported = 0;
        if (window.RegExp)
        {
                var tempStr = "a";
                var tempReg = new RegExp(tempStr);
                if (tempReg.test(tempStr)) supported = 1;
        }
        if (!supported)
                return (Expression.indexOf(".") > 2) && (Expression.indexOf("@") > 0);
        var r1 = new RegExp("(@.*@)|(\\.\\.)|(@\\.)|(^\\.)");
        var r2 = new RegExp("^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$");
        return (!r1.test(Expression) && r2.test(Expression));
}

/* ----------------------------------------------------------------
 hasSpace: Returns a Boolean if the specified Expression has 
          spaces. If Expression is null, false
          is returned.

 Parameters:
      Expression = string to validate.

 Returns: Boolean
---------------------------------------------------------------- */
function hasSpace(Expression)
{
        if (Expression == null)
                return (false);

        var supported = 0;
        if (window.RegExp)
        {
                var tempStr = "a";
                var tempReg = new RegExp(tempStr);
                if (tempReg.test(tempStr)) supported = 1;
        }
        if (!supported)
                return (Expression.indexOf(" ")!=-1);
        var r1 = new RegExp("\\s+");
        return (r1.test(Expression));
}

/* ----------------------------------------------------------------
 HasSpace: Returns a Boolean if start is no latter than the end.

 Parameters:
      Start = the begin date.
      End = the end date.

 Returns: Boolean
---------------------------------------------------------------- */
function checkTwoDates(Start,End)
{
    var start = Start;
    var end = End;    
    var aDate, oDate1, oDate2, iDays
    aDate = start.split("-")
    oDate1 = new Date(aDate[1] + - + aDate[2] + - + aDate[0]) //ת��Ϊ12-18-2002��ʽ
    aDate = end.split("-")
    oDate2 = new Date(aDate[1] + - + aDate[2] + - + aDate[0])
    days = (oDate2-oDate1)/1000/60/60/24+1;
    if(days<=0)
    {
        alert("��ʼ���ڲ��ܴ��ڽ�ֹ����!");
        return false;
    }
}
/* ----------------------------------------------------------------
 HasSpace: Returns a Boolean if start is no latter than the end.

 Parameters:
      Start = the begin datetime.
      End = the end datetime.

 Returns: Boolean
---------------------------------------------------------------- */
function checkTwoDateTimes(Start,End)
{
    var start = Start;
    var end = End;    
    var aDate, oDate1, oDate2,oTime,oTime1,oDateTime;
    oDateTime = start.split(" ");
    aDate = oDateTime[0].split("-");
    oTime = oDateTime[1].split(":");
    oDate1 = new Date(aDate[0],aDate[1],aDate[2],oTime[0],oTime[1],oTime[2]) //ת��Ϊ12-18-2002��ʽ
    oDateTime = end.split(" ");
    aDate = oDateTime[0].split("-");
    oTime = oDateTime[1].split(":");
    oDate2 = new Date(aDate[0],aDate[1],aDate[2],oTime[0],oTime[1],oTime[2])
    days = (oDate2-oDate1)/1000;
    if(days<0)
    {
        alert("��ʼʱ�䲻�ܴ��ڽ���ʱ��!");
        return false;
    }
}
//��֤�ϴ�����
function checkType(obj,typeStr){
    var FileExt = obj.value.substr(obj.value.lastIndexOf(".")).toLowerCase();
    if (typeStr != 0 && typeStr.indexOf(FileExt) == -1) {                 //�ж��ļ������Ƿ������ϴ�
        ErrMsg = "\n���ļ����Ͳ������ϴ�!\n���ϴ�" + typeStr + "���͵��ļ� \n��ǰ�ļ�����Ϊ" + FileExt;
        obj.outerHTML=obj.outerHTML;
        alert(ErrMsg);           
        return false;
    }
}
//��֤�Ƿ���^%&',;=?$[ !@~"���ַ�
function checkExpStr(str){
    var erg= new RegExp("[-_\~!@#\$%\^&\*\.\(\)\[\{\}<>\?\\\/\'\"��]","gi");
    return erg.test(str);
}
//�Ƿ��������ַ�
function hasChineseChar(s){ 
    var p = /[^\x00-\xff]/; 
    return p.test(s); 
} 
//��������
function checkDT(str)     
{     
    var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);     
    if(r==null)
    {
        return false;     
    }
    else
    {
       var d= new Date(r[1], r[3]-1, r[4]);     
       return (d.getFullYear()==r[1]&&(d.getMonth()+1)==r[3]&&d.getDate()==r[4]); 
    }    
}