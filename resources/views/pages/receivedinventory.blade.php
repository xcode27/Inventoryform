@extends('layout.app')
<style type="text/css">

 fieldset.scheduler-border {
    border: 1px groove #ddd !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;
    -webkit-box-shadow:  0px 0px 0px 0px #000;
            box-shadow:  0px 0px 0px 0px #000;

}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<script src="{{asset("/dashboards/js/jquery.min.js")}}"></script>
<script src="{{asset("/dashboards/js/custom_js.js")}}"></script>
<script type="text/javascript">

  var identifier = '';
  var disableddeleteform = 0;

  $('document').ready(function(){

     $('#btnadd').focus();
     checkUserControl();
     $('#btnSave').prop('disabled', true);
     $('#btnDelete').prop('disabled', true);
     $('#btnUpdate').prop('disabled', true);
     $('#store').prop('disabled', true);
     $('#inv_date').prop('disabled', true);
     $('#remove').prop('disabled', true);
     $('#sup').prop('disabled', true);
     $('#store_name').prop('disabled', true);
     $('#c_no').prop('disabled', true);
     $('#remarks').prop('disabled', true);
     $('#addformn').prop('disabled', true);
     $('#form').prop('disabled', true);

      $('#store').change(function(){
        $('#store_name').val($("#store option:selected").text());
      });

      $('#store1').change(function(){
        $('#store_name1').val($("#store1 option:selected").text());
      });

      $('#storedetails').on('hide', function (e) {

        e.preventDefault();

      });

  });

function addnew(){

    identifier = 0;

     if($('#add').val() == 1){
       $('#btnSave').prop('disabled', false);
     }
     
      $('#store').val('');
      $('#inv_date').val('');

     $('#inv_date').prop('disabled', false);
      $('#addformn').prop('disabled', true);
     $('#form').prop('disabled', true);
     $('#inv_date').prop('disabled', true);
     $('#remarks').prop('disabled', true);
     $('#c_no').prop('disabled', false);
     $('#store').val('');
     $('#c_no').val('');
     $('#store_name').val('');
     $('#sup').val('');
     $('#inv_date').val('');
     $('#form').val('');
     $('#remarks').val('');
     $("#textboxDiv").children().remove();
     $("#form option").remove();
     $('#searchstore').modal('show');
     displayStoreMapped();


    $('#rawdata tbody').empty();

  }

function displaySaveItems(){

    $("#details").dataTable().fnDestroy();
    $('#details').DataTable({
          "bPaginate": true,
          "processing": true,
          "serverSide": true,
          "bFilter": true,
          "lengthChange": true,
          "pageLength": 10,
          "ajax":'{{ route("displayInventory") }}',
        "columns":[
            {"data" : "action", orderable:false, searchable: false},
            {"data" : "control_no"},
            {"data" : "store_name"},
            {"data" : "supervisor"},
            {"data" : "promo"},
            {"data" : "inventory_date"}

          ]
      });

}


function saveInventory(){

  var store = $('#store').val();
  var storename = $('#store_name').val();
  var supervisor = $('#sup').val();
  var inv_date = $('#inv_date').val();
  var c_no = $('#c_no').val();

  if(identifier == 0){
    //save
          var promos  ='';
          $( "[id='promo']" ) .each(function(){

              promos += $(this).val() + ","; //convert to json format
   
            });

            var newstring = promos.substring(',', promos.length - 1);
           

            var datas = {
                         _token: '{{csrf_token()}}',store:store,supervisor:supervisor,promo:newstring,inv_date:inv_date,user:user,storename:storename,control_no:c_no
                        }

              $.ajax({
                  type: 'POST',
                  dataType : 'json',
                  data:datas,
                  url: '{{URL::to('saveInventory')}}',
                }).done(function( msg ) {
            
                    var data = jQuery.parseJSON(JSON.stringify(msg));
                    if(data.status == 'success'){

                      alert(data.message);
                      $('#store_name').prop('disabled', true);
                      $('#inv_date').prop('disabled', true);
                      $('#btnSave').prop('disabled', true);
                      $('#btnadd').prop('disabled', false);
                      $('#btnadd').focus();
                      displayFormmapped(store);
                      $('#addformn').prop('disabled', false);
                      $('#c_no').prop('disabled', false);
                      $('#form').prop('disabled', false);
                      $('#remarks').prop('disabled', false);
                       $('#btnSave').prop('disabled', true);
                      $('#btnadd').prop('disabled', false);

                    }else{

                      alert(data.message);
                     
                    }
              });
    }else{
      //update
      var recid = $('#recid').val();
      var datas = {
                 _token: '{{csrf_token()}}',recid:recid,inv_date:inv_date,control_no:c_no
                }

              $.ajax({
                  type: 'POST',
                  dataType : 'json',
                  data:datas,
                  url: '{{URL::to('updatesubmittedInventory')}}',
                }).done(function( msg ) {
                
                  var data = jQuery.parseJSON(JSON.stringify(msg));
                  if(data.status == 'success'){

                    alert(data.message);
                    $('#store_name').prop('disabled', true);
                    $('#inv_date').prop('disabled', true);
                    $('#c_no').prop('disabled', true);
                    $('#btnSave').prop('disabled', true);
                    $('#btnadd').prop('disabled', false);
                    $('#btnadd').focus();
                    displayFormmapped(store);
                    $('#addformn').prop('disabled', true);
                    $('#form').prop('disabled', true);
                    $('#remarks').prop('disabled', true);
                    $('#btnSave').prop('disabled', true);
                    $('#btnDelete').prop('disabled', true);
                    
                  }else{

                    alert(data.message);

                  }
             });
    }
}


function updateData(){

    var storeid = $('#storeid').val();
    var store = $('#store1').val();
    var storename = $('#store_name1').val();
    var inv_date = $('#inv_date1').val();

    var datas = {
         _token: '{{csrf_token()}}',storeid:storeid,store:store,inv_date:inv_date,storename:storename
        }

      $.ajax({
          type: 'POST',
          dataType : 'json',
          data:datas,
          url: '{{URL::to('updateInventory')}}',
      }).done(function( msg ) {
       
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
          alert(data.message);
          $('#rawdata').DataTable().ajax.reload();
        }else{
          alert(data.message);
        }

      });
      
}

function removeData(){

  if(confirm('Are you sure you want to remove this data. ?') == true){

        $.ajax({
          type: 'GET',
          url: '{{URL::to('deleteInventory')}}'+'/' + $('#storeid').val(),
      }).done(function( msg ) {
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
          alert(data.message);
          $('#rawdata').DataTable().ajax.reload();
        }
      });

  }
}




function displayStoreMapped(){

  $("#storedata").dataTable().fnDestroy();
 

     $('#storedata').DataTable({
          "bPaginate": true,
          "processing": true,
          "serverSide": true,
          "bFilter": true,
          "lengthChange": true,
          "pageLength": 10,
          "ajax":'{{ route("displayStoreMapped") }}',
        "columns":[
            {"data" : "action", orderable:false, searchable: false},
            {"data" : "supervisor"},
            {"data" : "store_code"},
            {"data" : "promodiser"},
            {"data" : "area"}

          ]
      });

}

function getDetails(id){


  $("#textboxDiv").children().remove();
   

  var data = id.split('@');
  var promodiser = data[3];
  var newStr = promodiser.replace(/'/g, '"');

    var xx = JSON.parse(newStr)
    var dets = '';

    $('#sup').val(data[2]);
    $('#store_name').val(data[5]);
    $('#store').val(data[1]);
    $.each(xx, function(key, value){

            //dets += value.Name ;
        $("#textboxDiv").append("<div><input type='text' class='form-control' id='promo'  style='border-color:#ffffff;' placeholder='ENTER DISER' value='"+value.Name+"' readonly /></div>");
        $('#searchstore').modal('hide');
    });

    $('#inv_date').focus();
    $('#inv_date').prop('disabled', false);

}

function searchdetails(){
  //for searching records
  
   $('#storedetails').modal('show');
   displaySaveItems();
  // $('#btnadd').prop('disabled', true);

}

function displayFormmapped($store){
   $.ajax({
          type: 'GET',
          url: '{{URL::to('getForm')}}'+'/' + $store,
      }).done(function( msg ) {
         var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
             
            dets += '<option value='+value.formcode+'>'+value.formname+'</option>';
          });
          $('#form').append(dets);
         
      });
}

function addform(){

  var form = $('#form').val();
  var inv_date = $('#inv_date').val();
  var store = $('#store').val();
  var remarks = $('#remarks').val();
 
 
  var datas = {
         _token: '{{csrf_token()}}',store:store,form:form,remarks:remarks,inv_date:inv_date
        }

      $.ajax({
          type: 'POST',
          dataType : 'json',
          data:datas,
          url: '{{URL::to('addform')}}',
      }).done(function( msg ) {
        //console.log(msg);
        //return false;
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
         // alert(data.message);
          displayformsubmitted(store,inv_date)
        }else{
          alert(data.message);
        }
      });

}

function displayformsubmitted(store,inventorydate){


  $("#rawdata").dataTable().fnDestroy();
  var param = store + '@' + inventorydate;
  var url = '{{ route("displayformsubmitted", ":param") }}';
  var url1 = url.replace(':param', param);

     $('#rawdata').DataTable({
          "bPaginate": false,
          "processing": true,
          "serverSide": true,
          "bFilter": false,
          "lengthChange": false,
          "pageLength": 10,
          "bInfo" : false,
          "ajax":url1,
        "columns":[
            {"data" : "formname"},
            {"data" : "action", orderable:false, searchable: false}


          ]
      });

}

function removeform(id){

  if(disableddeleteform == 1){
    return false;
  }
  if(confirm('Are you sure you want to remove this data. ?') == true){

        $.ajax({
          type: 'GET',
          url: '{{URL::to('removeForm')}}'+'/' + id,
      }).done(function( msg ) {
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
         // alert(data.message);
          $('#rawdata').DataTable().ajax.reload();
        }
      });

  }
}


function getInfo(id){
  var info = id.split('@');
  var globalid = info[0];
  var storecode = info[1];
  var storename = info[3];
  var supervisor = info[4];
  var promo = info[5];
  var inv_date = info[2];
  var control_no = info[7];
  
   $('#sup').val(supervisor);
   $('#store_name').val(storename);
   $('#inv_date').val(inv_date);
   $('#c_no').val(control_no);
   
   $('#storedetails').modal('hide');
   $('#inv_date').focus();

    var promodiser = info[6];
    var newStr = promodiser.replace(/'/g, '"');

    var xx = JSON.parse(newStr)
    
    $.each(xx, function(key, value){

            //dets += value.Name ;
        $("#textboxDiv").append("<div><input type='text' class='form-control' id='promo'  style='border-color:#ffffff;' placeholder='ENTER DISER' value='"+value.Name+"' readonly /></div>");
    });

   if($('#del').val() == 1){
       $('#btnDelete').prop('disabled', false);
     }

     if($('#upd').val() == 1){
       $('#btnUpdate').prop('disabled', false);
     }

     displayformsubmitted(storecode,inv_date);
     displayFormmapped(storecode);
     
     
     $('#recid').val(globalid);
     disableddeleteform = 1;

     $('#store').val(storecode);
  
}


function deleteStore(){

  if(confirm('Are you sure you want to remove this data. ?') == true){

        $.ajax({
          type: 'GET',
          url: '{{URL::to('deleteInventorySubmitted')}}'+'/' + $('#recid').val(),
      }).done(function( msg ) {
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
          alert(data.message);
          location.reload();
        }
      });

  }
}


function updateStore(){
  identifier = 1;
  $('#inv_date').prop('disabled', false);
  $('#remarks').prop('disabled', false);
  $('#c_no').prop('disabled', false);
  $('#form').prop('disabled', false);
  $('#addformn').prop('disabled', false);
  $('#btnUpdate').prop('disabled', true);
  $('#btnSave').prop('disabled', false);
  $('#btnDelete').prop('disabled', true);
  disableddeleteform = 0;
}

</script>

@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Inventory Entry</a>
      </li>
  </ol>
  <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <input type="hidden" id="store">
  <input type="hidden" id="store_name1">
  <input type="hidden" id="storeid">
  <input type="hidden" id="recid">
  <button class="btn btn-primary" class="btnSearch" id="btnSearch" onclick="searchdetails()"><i class="fa fa-search"></i>&nbsp;Search</button>
  <br><br>
 <table class="table">
  <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Control #:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="c_no" style="border-color:#ffffff;"></input>
             </div>     
          </td>
    </tr>
   <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Store:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="store_name" style="border-color:#ffffff;"></input>
             </div>     
          </td>
    </tr>
    
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Supervisor:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="sup" style="border-color:#ffffff;"></input>
             </div>     
          </td>
    </tr>
     <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Promo:</td>
          <td>
            <div class="input-group" id="pro">
                <div id="textboxDiv" style="width: 100%;"></div>
            </div>
           
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Inventory Date:</td>
          <td>
            <div class="form-group">
                <input type="date" class="form-control" id="inv_date"  style="border-color:#ffffff;">
             </div>     
          </td>
    </tr>
    <tr>
        <td colspan="2" align="right">
            <button class="btn btn-primary"  id="btnadd" onclick="addnew()"><i class="fa fa-plus"></i>&nbsp;Add</button>
            <button class="btn btn-primary" class="btnSaved" id="btnSave" onclick="saveInventory()"><i class="fa fa-save"></i>&nbsp;Save</button>
            <button class="btn btn-primary"  id="btnUpdate" onclick="updateStore()"><i class="fa fa-edit"></i>&nbsp;Update</button>
            <button class="btn btn-danger"  id="btnDelete" onclick="deleteStore()"><i class="fa fa-trash"></i>&nbsp;Delete</button>
        </td>
    </tr>

 </table>
 <fieldset class="scheduler-border" >
        <legend class="scheduler-border">Form Details</legend>
        <table class="table">
          <tr>
              <td style="width:120px;">Select Form Type:</td>
              <td>
                  <select  class="form-control" id="form">
                  </select>
              </td>
              <td>
                  <input type="text" class="form-control" id="remarks" placeholder="Remarks"></input>
              </td>
              <td>
                  <button class="btn btn-primary" class="btnSaved" id="addformn" onclick="addform()"><i class="fa fa-plus"></i>&nbsp;</button>
              </td>
          </tr>
        </table>     
       
          <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="rawdata">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>FORM NAME</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
</fieldset>


@endsection



<div class="modal fade" id="searchstore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Store Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closemod()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="storedata">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>ACTION</th>
                  <th>SUPERVISOR</th>
                  <th>STORE NAME</th>
                  <th>PROMO</th>
                  <th>AREA</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="storedetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closemod()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="details">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>ACTION</th>
                  <th>CONTROL NO</th>
                  <th>STORE NAME</th>
                  <th>SUPERVISOR</th>
                  <th>PROMO</th>
                  <th>INVENTORY DATE</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>