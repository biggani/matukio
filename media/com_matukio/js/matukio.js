function changeLimitEventlist() {
  var lim = $('limit').get('value');
  var art = $('hidden_art').get('value');
  var search = $('search_field').get('value');
  var catid = $('catid').get('value');

  //alert(lim);
  //window.location="index.php?option=com_matukio&limit=" + lim + "&art=" + art + "&search=" +search+ "&catid=" + catid;

    var jsonRequest = new Request.JSON({url: 'index.php?option=com_matukio&view=requests&task=route_link',
        onSuccess: function(url){
        window.location= url.link;
    }}).get({'link': "index.php?option=com_matukio&view=eventlist&art=" + art + "&catid=" + catid + "&search=" +search + "&limit=" + lim});

}

function searchEventlist() {
   var search = $('search_field').get('value');
   var lim = $('limit').get('value');
   var art = $('hidden_art').get('value');
   var catid = $('catid').get('value');

    var jsonRequest = new Request.JSON({url: 'index.php?option=com_matukio&view=requests&task=route_link',
        onSuccess: function(url){
            window.location= url.link;
    }}).get({'link': "index.php?option=com_matukio&view=eventlist&art=" + art + "&catid=" + catid + "&search=" +search + "&limit=" + lim});

    return false;
}

function searchEventlistRet(e) {
    var key=e.keyCode || e.which;
    if (key==13){
        searchEventlist();
    }
}

function changeCategoryEventlist(){
    var search = $('search_field').get('value');
    var lim = $('limit').get('value');
    var art = $('hidden_art').get('value');
    var catid = $('catid').get('value');

    var jsonRequest = new Request.JSON({url: 'index.php?option=com_matukio&view=requests&task=route_link',
        onSuccess: function(url){
            window.location=url.link;
    }}).get({'link': "index.php?option=com_matukio&view=eventlist&art=" + art + "&catid=" + catid + "&search=" +search + "&limit=" + lim});

    //window.location="index.php?option=com_matukio&art=" + art + "&catid=" + catid + "&search=" +search + "&limit=" + lim;
}

function resetEventlist() {
    var jsonRequest = new Request.JSON({url: 'index.php?option=com_matukio&view=requests&task=route_link',
        onSuccess: function(url){
            window.location= url.link;    // alerts "25 years".
    }}).get({'link': "index.php?option=com_matukio&view=eventlist"});
}