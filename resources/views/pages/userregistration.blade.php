@extends('layout.app')

<script src="{{asset("/dashboards/vendor/jquery/jquery.min.js")}}"></script>
<script type="text/javascript">

  var getToken = window.localStorage.getItem('access_token');
  var user = window.localStorage.getItem('name');

$(document).ready(function(){

});

function saveMenu(){

  var name = $('#name').val();
  var email = $('#email').val();
  var username = $('#un').val();
  var password = $('#password').val();
  var ugroup = $('#ugroup').val();

  $.ajax({
          type: 'POST',
          dataType : 'json',
           beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          url:"http://192.168.1.55:8002/api/register?module=user&name="+name+"&email="+email+"&username="+username+"&password="+password+"&usergroup="+ugroup,

         success: function( msg ) {
                   var data = jQuery.parseJSON(JSON.stringify(msg));

                   if(data.status != 'Authorization Token not found'){

                        if(data.errors){
                            alert(data.errors);
                            return false;
                        }

                       if(data.status == 'error'){
                            alert(data.message);
                       }else{
                            alert('User successfully saved.');
                       }
                     }else{
                        alert('You are not allowed to create module.! Authorization Token not found.');
                     }
          }
       }); 

}

</script>
@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">User Registration</a>
      </li>

  </ol>
 <table class="table">
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Name:</td>
          <td>
            <div class="form-group">
                <input type="text" class="form-control" id="name" placeholder="Enter name" style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Email:</td>
          <td>
            <div class="form-group">
                <input type="email" class="form-control" id="email" placeholder="Enter email" style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Username:</td>
          <td>
            <div class="form-group">
                <input type="text" class="form-control" id="un" placeholder="Enter username" style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Password:</td>
          <td>
            <div class="form-group">
                <input type="password" class="form-control" id="password" placeholder="Enter password" style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;User group:</td>
          <td>
            <div class="form-group">
                <select class="form-control" id="ugroup"  style="border-color:#ffffff;">
                    <option></option>
                    <option value="0">Super Admin</option>
                    <option value="1">Admin</option>
                    <option value="2">Guest</option>
                </select>
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