/**
 * 更新排名
 * @param ev
 * @param wordID
 * @param brLocation
 * @param timeLocation
 */
function updateBR(ev,wordID,brLocation,timeLocation){
    if (!brLocation || !timeLocation){
        alert('请求参数错误');
        return false;
    }
    brLocation = parseInt(brLocation-1);
    timeLocation = parseInt(timeLocation-1);
    var stemp = ev.parentNode.parentNode.children[brLocation].innerHTML;
    ev.parentNode.parentNode.children[brLocation].innerHTML='<img src="/style/images/loading.gif" width="40">';

    var xmlhttp;
    if (window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function(){
        console.log(xmlhttp.status);
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var res = JSON.parse(xmlhttp.responseText);

            if (res['newBR']) {
                ev.parentNode.parentNode.children[brLocation].innerHTML = res['newBR'];
            }else {
                ev.parentNode.parentNode.children[brLocation].innerHTML = stemp;
            }
            ev.parentNode.parentNode.children[timeLocation].innerHTML = res['newtime'];
        }
    }
    xmlhttp.open("GET","/api.php?op=updBr&wordID="+wordID,true);
    xmlhttp.send();

}

/**
 * 一键更新
 * @param className
 */
function autoUpdate(className){
    var updateDom = document.getElementsByClassName(className);
    var len = updateDom.length;

    for(i=0;i<len;i++){
        updateDom[i].click();
    }
}

/**
 * 重新统计数据
 * @param ev
 * @param $userID
 * @param wordLocation
 * @param brankedLocation
 * @returns {boolean}
 */
function reCount(ev,$userID,wordLocation,brankedLocation) {
    if (!$userID){
        alert('请求参数错误');
        return false;
    }
    userID = parseInt($userID);
    wordLocation = parseInt(wordLocation-1);
    brankedLocation = parseInt(brankedLocation-1);
    var stempWord = ev.parentNode.parentNode.children[wordLocation].innerHTML;
    var stempBranked = ev.parentNode.parentNode.children[brankedLocation].innerHTML
    ev.parentNode.parentNode.children[wordLocation].innerHTML = '<img src="/style/images/loading.gif" width="40">';
    ev.parentNode.parentNode.children[brankedLocation].innerHTML = '<img src="/style/images/loading.gif" width="40">';

    var xmlhttp;
    if (window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }else{
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange=function(){
        console.log(xmlhttp.status);
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            console.log(xmlhttp.responseText);
             var res = JSON.parse(xmlhttp.responseText);
            if (res) {
                ev.parentNode.parentNode.children[wordLocation].innerHTML = res['wordNum'];
                ev.parentNode.parentNode.children[brankedLocation].innerHTML = res['brankedNum'];
            }else {
                alert('重新统计失败');
                ev.parentNode.parentNode.children[wordLocation].innerHTML = stempWord;
                ev.parentNode.parentNode.children[brankedLocation].innerHTML = stempBranked;
            }
        }
    }
    xmlhttp.open("GET","/index.php?m=url&c=url&e=recount&userID="+userID,true);
    xmlhttp.send();
}