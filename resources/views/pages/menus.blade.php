@extends('layout.app')

<script src="{{asset("/dashboards/js/jquery.min.js")}}"></script>
<script src="{{asset("/dashboards/js/custom_js.js")}}"></script>
<script type="text/javascript">

  var getToken = window.localStorage.getItem('access_token');
  var user = window.localStorage.getItem('name');

$(document).ready(function(){
    getMenuses();
});

function saveMenu(){

  var menu = $('#menuname').val();
  var url = $('#url').val();
  var parentmenu = $('#menuses').val()

  if(menu == ''){
    alert('Menu name is required')
    return false;
  }

  $.ajax({
          type: 'POST',
          dataType : 'json',
           beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          url:"http://192.168.1.55:8002/api/register?module=menus&module_name="+menu+"&module_url="+url+"&parentmenu="+parentmenu+"&sys=IFT",
          success: function( msg ) {
                   var data = jQuery.parseJSON(JSON.stringify(msg));
                   if(data.status != 'Authorization Token not found'){
                       if(data.status == 'success'){
                            alert(data.message);
                       }else{
                            alert(data.message);
                       }
                     }else{
                        alert('You are not allowed to create module.! Authorization Token not found.');
                     }
          },
         error: function (errormessage) { alert('Error'); }
       }); 

}

function getMenuses(){
  $.ajax({
          type: "GET",
          dataType : "json",
          beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          url:"http://192.168.1.55:8002/api/getMenus",
          success: function( msg ) {
          var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
              //alert(value.module_description);
            dets += '<option value='+value.id+'>'+value.module_description+'</option>';
          });
          $('#menuses').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });
}

</script>
@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Create Menus</a>
      </li>

  </ol>
 <table class="table">
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Menu Name:</td>
          <td>
            <div class="form-group">
                <input type="text" class="form-control" id="menuname" placeholder="Enter Menu name" style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Parent menu:</td>
          <td>
            <div class="form-group">
                <select class="form-control" id="menuses"  style="border-color:#ffffff;">
                </select>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Menu Url:</td>
          <td>
            <div class="form-group">
                <input type="text" class="form-control" id="url" placeholder="Enter Menu url" style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td colspan="2" align="right">
            <button class="btn btn-primary" id="btnSave"  onclick="saveMenu()"><i class="fa fa-save"></i>&nbsp;Save</button>
            <button class="btn btn-danger" id="btnClose"><i class="fa fa-window-close" onclick="window.location.href='{{ action("PagesController@home") }}' "></i>&nbsp;Close</button>
        </td>
    </tr>
 </table>
@endsection