@extends('layout.app')

<script src="{{asset("/dashboards/vendor/jquery/jquery.min.js")}}"></script>
<script src="{{asset("/dashboards/js/custom_js.js")}}"></script>
<script type="text/javascript">

  var getToken = window.localStorage.getItem('access_token');
  var user = window.localStorage.getItem('name');
  var addcontrols = 0;
  var deletecontrols = 0;
  var readcontrols = 0;
  var updatecontrols = 0;
  var identifier = 0;
  var recid = '';
  var url_app = 'http://192.168.1.55:8002/api/';
$(document).ready(function(){

  $('#btnSave').prop('disabled', true);
  $('#menuses').prop('disabled', true);
  $('#users').prop('disabled', true);
  $('#add').click(function(){
        
      if($(this).prop("checked") == true){
              addcontrols = 1;
          }
          else if($(this).prop("checked") == false){
             addcontrols = 0;
          }
  });

   $('#delete').click(function(){
       
         if($(this).prop("checked") == true){
              deletecontrols = 1;
          }
          else if($(this).prop("checked") == false){
             deletecontrols = 0;
          }
  });

   $('#edit').click(function(){
         
        if($(this).prop("checked") == true){
              updatecontrols = 1;
          }
          else if($(this).prop("checked") == false){
             updatecontrols = 0;
          }
  });


   $('#read').click(function(){
       
        if($(this).prop("checked") == true){
              readcontrols = 1;
          }
          else if($(this).prop("checked") == false){
             readcontrols = 0;
          }
  });
   checkUserControl();
   getMenuses();
   getUser();
   displayUserAccess()

});


function addnew(){

    identifier = 0;
    addcontrols = 0;
    deletecontrols = 0;
    readcontrols = 0;
    updatecontrols = 0;

     $('#btnadd').prop('disabled', true);
     $('#btnSave').prop('disabled', false);
     $('#users').val('');
     $('#menuses').val('');
     $('#add').prop("checked",false);
     $('#delete').prop("checked",false);
     $('#read').prop("checked",false);
     $('#edit').prop("checked",false);
     $('#menuses').prop('disabled', false);
     $('#users').prop('disabled', false);
  }

function saveMenu(){

  var menu = $('#menuname').val();
  var url = $('#url').val();
  var group = $('#usergroup').val();
  var menus = $('#menuses').val();
  var users = $('#users').val();

  if(menu == ''){
    alert('Menu name is required')
    return false;
  }

    if( identifier == 0){

          $.ajax({
                  type: 'POST',
                  dataType : 'json',
                   beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                  url:"http://192.168.1.55:8002/api/register?module=menu_auth_mapping&add="+addcontrols+"&read="+readcontrols+"&update="+updatecontrols+"&delete="+deletecontrols+"&user="+users+"&moduleid="+menus,
                 success: function( msg ) {
                  //console.log(msg);
                 // return false;
                           var data = jQuery.parseJSON(JSON.stringify(msg));
                           if(data.status != 'Authorization Token not found'){
                               if(data.status == 'success'){
                                    alert(data.message);
                                    $('#menuses').prop('disabled', true);
                                    $('#users').prop('disabled', true);
                                    $('#btnadd').prop('disabled', false);
                                    $('#btnSave').prop('disabled', true);
                                    $('#userdata').DataTable().ajax.reload();
                               }else{
                                    alert(data.message);
                               }
                             }else{
                                alert('You are not allowed to create module.! Authorization Token not found.');
                             }
                  },
                 error: function (errormessage) { alert('Error'); }
               });

    }else{

        $.ajax({
                  type: 'POST',
                  dataType : 'json',
                   beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                  url:"http://192.168.1.55:8002/api/register?module=edit_menu_auth_mapping&add="+addcontrols+"&read="+readcontrols+"&update="+updatecontrols+"&delete="+deletecontrols+"&moduleid="+recid,
                 success: function( msg ) {
                           var data = jQuery.parseJSON(JSON.stringify(msg));
                           if(data.status != 'Authorization Token not found'){
                               if(data.status == 'success'){
                                    alert(data.message);
                                    $('#userdata').DataTable().ajax.reload();
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


function getUser(){
  $.ajax({
          type: "GET",
          dataType : "json",
          beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          url:"http://192.168.1.55:8002/api/getUser",
          success: function( msg ) {
          var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
              //alert(value.module_description);
            dets += '<option value='+value.id+'>'+value.name+'</option>';
          });
          $('#users').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });
}

function displayUserAccess(){

  $("#userdata").dataTable().fnDestroy();
  
      $('#userdata').DataTable({
            "processing": true,
            "serverSide": true,
            "bFilter": true,
            "lengthChange": true,
            "pageLength": 10,
            "ajax":{
                        url: 'http://192.168.1.55:8002/api/getUserAccess',
                        type: "GET",
                        beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                    },
            "columns":[
                {"data" : "action", orderable:false, searchable: false},
                {"data" : "name"},
                {"data" : "module_description"},
                {"data" : "module_url"},
                {"data" : "CreateMenu"},
                {"data" : "DeleteMenu"},
                {"data" : "UpdateMenu"},
                {"data" : "ReadMenu"}
              ]
          });

}

function removeMenuMapped(id){
 
   if(confirm('Are you sure do you want to remove this access ?') == true){
      $.ajax({
            type: "POST",
            dataType : "json",
            beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
            url:"http://192.168.1.55:8002/api/deleteUserMapped?id="+id,
            success: function( msg ) {

                var data = jQuery.parseJSON(JSON.stringify(msg));
                if(data.status == 'success'){

                  alert(data.message)
                  $('#userdata').DataTable().ajax.reload();

                }else{

                   alert(data.message);

                }
        },
           error: function (errormessage) { alert('Error'); }
         });
   }
}

function getDetails(id){
  identifier = 1;
  var data = id.split('@');
  
  $('#users').val(data[0]);
  $('#menuses').val(data[6]);

  recid = data[1];
  var canadd = data[2];
  var candelete = data[5];
  var canread = data[3];
  var canupdate = data[4];


  if(canadd == 'YES'){
      $('#add').prop("checked",true);
      addcontrols = 1;
  }else{
      $('#add').prop("checked",false);
      addcontrols = 0;
  }

  if(candelete == 'YES'){
      $('#delete').prop("checked",true);
      deletecontrols = 1;
  }else{
      $('#delete').prop("checked",false);
      deletecontrols = 0;
  }

  if(canread == 'YES'){
      $('#read').prop("checked",true);
      readcontrols = 1;
  }else{
      $('#read').prop("checked",false);
      readcontrols = 0;
  }

  if(canupdate == 'YES'){
      $('#edit').prop("checked",true);
      updatecontrols = 1;
  }else{
      $('#edit').prop("checked",false);
      updatecontrols = 0;
  }

  $('#btnadd').prop('disabled', true);
  $('#btnSave').prop('disabled', false);
  $('#menuses').prop('disabled', false);
  $('#users').prop('disabled', false);
 
}

</script>
@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">User access rights</a>
      </li>

  </ol>
 <table class="table">
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;User:</td>
          <td>
            <div class="form-group">
                <select  class="form-control" id="users" style="border-color:#ffffff;">
                </select>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Menus:</td>
          <td>
            <div class="form-group">
                <select  class="form-control" id="menuses" style="border-color:#ffffff;">
                </select>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Controls:</td>
          <td>
            <div class="form-group">
                <input type="checkbox" id="add">&nbsp;Add
                <input type="checkbox" id="edit">&nbsp;Edit
                <input type="checkbox" id="delete">&nbsp;Delete
                <input type="checkbox" id="read">&nbsp;Read
             </div>     
          </td>
    </tr>
    <tr>
        <td colspan="2" align="right">
            <button class="btn btn-primary"  id="btnadd" onclick="addnew()"><i class="fa fa-plus"></i>&nbsp;<u>A</u>dd</button>
            <button class="btn btn-primary" id="btnSave"  onclick="saveMenu()"><i class="fa fa-save"></i>&nbsp;<u>S</u>ave</button>
        </td>
    </tr>
 </table>
 <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="userdata">
      <thead>
          <tr style="background-color:#737373; color:white;">
              <th>ACTION</th>
              <th>USER</th>
              <th>MENU NAME</th>
              <th>MENU ROUTE</th>
              <th>CAN ADD ?</th>
              <th>CAN DELETE ?</th>
              <th>CAN EDIT ?</th>
              <th>CAN SEARCH ?</th>
          </tr>
      </thead>
    <tbody></tbody>
</table>
@endsection