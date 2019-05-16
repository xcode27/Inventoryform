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
<script src="{{asset("/dashboards/vendor/jquery/jquery.min.js")}}"></script>
<script src="{{asset("/dashboards/select2s/dist/js/select2.js")}}"></script>
<link  href="{{asset("/dashboards/select2s/dist/css/select2.css")}}" rel="stylesheet">
<script type="text/javascript">
  var identifier = '';
  var handledisble = 0;
  var api_url = 'http://192.168.1.55:8002/api';

  $('document').ready(function(){

    $('#btnadd').focus();
   
    $('#btnaddlist').prop('disabled', true);
    $('#btnDelete').prop('disabled', true);
    $('#btnUpdate').prop('disabled', true);
    $('#btnSave').prop('disabled', true);
    //$('#store').prop('disabled', true);
    $('#c_no').prop('disabled', true);

    $('#po_no').prop('disabled', true);
    $('#po_date').prop('disabled', true);
    $('#btnViewtemplate').prop('disabled', true);
    checkUserControl();
      getStores();
    
  });

 function addnew(){
    identifier = 0;
  
      if($('#add').val() == 1){
       $('#btnSave').prop('disabled', false);
     }
     
     $('#c_no').val('');
     $('#store').val('').trigger('change');
     $('#po_date').val('');
     $('#po_product_data').children().remove();
     $('#btnadd').prop('disabled', true);
     $('#invdate').prop('disabled', false);
     //$('#store').prop('disabled', false);
     $('#c_no').prop('disabled', false);
     $('#po_date').prop('disabled', false);
     $('#btnViewtemplate').prop('disabled', true);
     $('#btnDelete').prop('disabled', true);
     $('#btnUpdate').prop('disabled', true);
     $('#po_no').prop('disabled', false);
     $('#c_no').focus();

  }


  function getStores(){

  $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
         // url:"http://192.168.1.55:8002/api/allgetStore",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=allstore&token='+ getToken,
          success: function( msg ) {
            //console.log(msg);
           // return false;
          var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
             
            dets += '<option value='+value.CONTACT_CODE+'>'+value.CONTACT_NAME+'</option>';
          });
          $('#store').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });
   $("#store").select2();

}

function viewTemplate(){
  getProductGroup();
  $('#view_template').modal('show');
}

function saveHeadData(){

  if(identifier == 0){

           var data = {
                         _token: '{{csrf_token()}}',
                        store:$('#store').val(),
                        storename:$('#store option:selected').text(),
                        po_date: $('#po_date').val(),
                        controlno:$('#c_no').val(),
                        po_no:$('#po_no').val(),
                        user:user
                      }

                          $.ajax({
                              type: 'POST',
                              dataType : 'json',
                              data:data,
                              url: '{{URL::to('savePoHead')}}',
                          }).done(function( msg ) {
                            
                              var data = jQuery.parseJSON(JSON.stringify(msg));

                              if(data.status == 'success'){
                                  alert(data.message);
                                  $('#store').prop('disabled', true);
                                  $('#c_no').prop('disabled', true);
                                  $('#btnadd').prop('disabled', true);
                                  $('#po_date').prop('disabled', true);
                                  $('#po_no').prop('disabled', true);
                                  $('#btnViewtemplate').prop('disabled', false);
                                  $('#btnViewtemplate').focus();

                              }else{
                                alert(data.message);
                              }
                          });
           
        }else{
         
               var data = {
                         _token: '{{csrf_token()}}',
                         recid:$('#recid').val(),
                        store:$('#store').val(),
                        storename:$('#store option:selected').text(),
                        po_date: $('#po_date').val(),
                        c_no:$('#c_no').val(),
                        po_no:$('#po_no').val(),
                        user:user
                      }
                      $.ajax({
                              type: 'POST',
                              dataType : 'json',
                              data:data,
                              url: '{{URL::to('updatePoHead')}}',
                          }).done(function( msg ) {
                            
                              var data = jQuery.parseJSON(JSON.stringify(msg));

                              if(data.status == 'success'){
                                  alert(data.message);
                                  $('#store').prop('disabled', true);
                                  $('#c_no').prop('disabled', true);
                                  $('#btnadd').prop('disabled', true);
                                  $('#po_date').prop('disabled', true);
                                  $('#po_no').prop('disabled', true);
                                  $('#btnViewtemplate').prop('disabled', true);
                                  $('#btnSave').prop('disabled', true);
                                  $('#btnadd').prop('disabled', false);
                              }else{
                                alert(data.message);
                              }
                          });

      }

}

function getProductGroup(){
  $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getProdGroup",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=prodgroup&token='+ getToken,
          success: function( msg ) {
          var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
             
            dets += '<option value='+value.tran_sys_code+'>'+value.tran_name+'</option>';
          });
          $('#prodgrp').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });
   $("#prodgrp").select2();
}

function loadProductData(transyscode){
  $.ajax({
          type: "POST",
          dataType : "json",
         // beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
         // url:"http://192.168.1.55:8002/api/loadProductFromGroup?trancode="+transyscode,
          url: 'http://192.168.1.55:805/api_cod/index.php?type=loadProductFromGroup'+'&trancode='+transyscode+'&token='+ getToken,
          success: function( msg ) {
          
          var dets = '';
          $.each(msg, function(key, value){
            
            dets += '<tr>';
            dets += '<td>'+value.sortorder+'</td>';
            dets += '<td>'+value.PROD_SYS_CODE+'</td>';
            dets += '<td>'+value.PROD_DESC+'</td>';
            dets += '<td><input type="number" alt='+value.tran_sys_code+' placeholder="'+value.PROD_DESC+'"  title='+value.PROD_SYS_CODE+' id='+value.tran_no+' name="tranno" /></td>';
            dets += '</tr>';
            
          });
          $('#loadData').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });
   
}

 function loadProd(trancode){
  $('#loadData').children().remove();
  loadProductData(trancode);
 }

 function addData(){

       var proddata =  document.getElementsByName("tranno");
        var x = '';
        var xx = '';
        var xxx = '';

        for(a=0; a<proddata.length; a++)
        {
         if(proddata[a].value != ''){
          if(x != ''){
          
          xxx = xxx.concat(',');
          }
      
          xxx = xxx.concat("{"+'"Id"'+':'+'"'+proddata[a].id+'",'+'"Head_id"'+':'+'"'+proddata[a].alt+'",'+'"Controlno"'+':'+'"'+$('#c_no').val()+'",'+'"Productcode"'+':'+'"'+proddata[a].title+'",'+'"Productname"'+':'+'"'+proddata[a].placeholder+'",'+'"Qty"'+':'+'"'+proddata[a].value+'"'+"}")+","
           }
        }

        var newstring = xxx.substring(',', xxx.length - 1);

         $('#prodcode').val("{"+'"data"'+':'+"["+newstring+"]"+"}")
          var data = {
                         _token: '{{csrf_token()}}',
                        info: $('#prodcode').val()
                      }

                          $.ajax({
                              type: 'POST',
                              dataType : 'json',
                              data:data,
                              url: '{{URL::to('addToList')}}',
                          }).done(function( msg ) {
              
                              var data = jQuery.parseJSON(JSON.stringify(msg));

                              if(data.status == 'success'){

                                  displayPOproductDetails($('#c_no').val())

                              }else{
                                alert(data.message);
                              }
                          
                          });

 }

 function displayPOproductDetails(controlno){
  $('#po_product_data').children().remove();
    var data = {
                   _token: '{{csrf_token()}}',
                 controlno:controlno
                }

                    $.ajax({
                        type: 'POST',
                        dataType : 'json',
                        data:data,
                        url: '{{URL::to('displayOrderProductDetails')}}',
                    }).done(function( msg ) {
                      var dets = '';
         
                        $.each(msg, function(key, value){
                          
                          dets += '<tr>';
                          dets += '<td><button class="btn btn-danger" title='+value.id+' id='+value.id+' onclick="removeProduct(this.id)"><i class="fa fa-trash"></i></button></td>';
                          dets += '<td>'+value.product_code+'</td>';
                          dets += '<td>'+value.product_name+'</td>';
                          dets += '<td>'+value.qty+'</td>';
                          dets += '</tr>';
                          
                        });
                        $('#po_product_data').append(dets);
                    });

 }

function removeProduct(id){
if(confirm('Are you sure you want to remove this data') == true){
      
        $.ajax({
          type: 'GET',
          url: '{{URL::to('removeProductDetails')}}'+'/' + id,
          }).done(function( msg ) {
            var data = jQuery.parseJSON(JSON.stringify(msg));
            if(data.status == 'success'){
              displayPOproductDetails($('#c_no').val())
            }
          });

  }
}

function closemod(){

      if(identifier == 1){

         $('#btnadd').prop('disabled', true);
         $('#btnSave').prop('disabled', false);

      }else{

         $('#btnadd').prop('disabled', false);
         $('#btnSave').prop('disabled', true);

    }
}

function searchdetails(){
  displayHeaderDetails();
  $('#view_header').modal('show');
}

function displayHeaderDetails(){
$("#loadHeader").dataTable().fnDestroy();
    $('#loadHeader').DataTable({
                "bPaginate": true,
                "processing": true,
                "serverSide": true,
                "bFilter": true,
                "lengthChange": true,
                "pageLength": 10,
                "ajax":'{{ route("displayHeader") }}',
              "columns":[
                  {"data" : "action", orderable:false, searchable: false},
                  {"data" : "po_no"},
                  {"data" : "controlno"},
                  {"data" : "store_name"},
                  {"data" : "po_date"}
                ]
        });

}

function getInfo(id){

  var data = id.split('@');
  $('#recid').val(data[0]);
  $('#c_no').val(data[5]);
  $('#store').val(data[2]).trigger('change');
  $('#po_date').val(data[4]);
  $('#po_no').val(data[1]);
  displayPOproductDetails(data[5]);

  if($('#upd').val() == 1){
      $('#btnUpdate').prop('disabled', false);
     }else{
      $('#btnUpdate').prop('disabled', true);
     }

     if($('#del').val() == 1){
      $('#btnDelete').prop('disabled', false);
     }else{
      $('#btnDelete').prop('disabled', true);
     }

  $('#view_header').modal('hide');

}

function deleteHeader(){

  var id = $('#recid').val();

  if(confirm('Are you sure you want to remove this data') == true){
      
        $.ajax({
          type: 'GET',
          url: '{{URL::to('removeHeaders')}}'+'/' + id,
          }).done(function( msg ) {
            var data = jQuery.parseJSON(JSON.stringify(msg));
            if(data.status == 'success'){
              alert(data.message);
             $('#po_product_data').children().remove();
             $('#c_no').val('');
             $('#store').val('').trigger('change');
             $('#po_date').val('');
            }
          });

  }

}

function updateHeader(){
  identifier = 1;

  $('#btnadd').prop('disabled', true);
  $('#btnDelete').prop('disabled', true);
  $('#btnUpdate').prop('disabled', true);
  $('#btnSave').prop('disabled', false);
  $('#store').prop('disabled', false);
  $('#c_no').prop('disabled', false);
  $('#po_date').prop('disabled', false);
  $('#po_no').prop('disabled', false);
  $('#btnViewtemplate').prop('disabled', false);

}

function getControlNo(controlno){

    var data = {
                   _token: '{{csrf_token()}}',
                 controlno:controlno
                }
                    $.ajax({
                        type: 'POST',
                        dataType : 'json',
                        data:data,
                        url: '{{URL::to('getDataFromInventory')}}',
                    }).done(function( msg ) {
                    
                       $.each(msg, function(key, value){
                            $('#store').val(value.store).trigger('change');
                        });
                       
                    });

}
</script>
@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Create P.O</a>
      </li>
  </ol>
   <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <input type="hidden" id="store_name">
  <input type="hidden" id="prod_name">
  <input type="hidden" id="prod_name1">
  <input type="hidden" id="store_name1">
  <input type="hidden" id="poid">
  <input type="hidden" id="prodcode">
  <input type="hidden" id="recid">
  <button class="btn btn-primary" class="btnSearch" id="btnSearch" onclick="searchdetails()"><i class="fa fa-search"></i>&nbsp;<u>S</u>earch</button>
  <br><br>
 <table class="table">
  <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Control #:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="c_no" style="border-color:#ffffff;" onchange ="getControlNo(this.value)"></input>
             </div>     
          </td>
  </tr>
  <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Customer:</td>
          <td>
            <div class="form-group">
                <select  class="form-control" id="store" style="border-color:#ffffff;"></select>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;PO #:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="po_no" style="border-color:#ffffff;"></input>
             </div>     
          </td>
  </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;PO Date:</td>
          <td>
            <div class="form-group">
                <input type="date"  class="form-control" id="po_date" style="border-color:#ffffff;"></input>
             </div>     
          </td>
    </tr>
 </table>

  <div align="right">
       <button class="btn btn-primary"  id="btnadd" onclick="addnew()"><i class="fa fa-plus"></i>&nbsp;<u>A</u>dd</button>
        <button class="btn btn-primary"  id="btnSave" onclick="saveHeadData()"><i class="fa fa-save"></i>&nbsp;<u>S</u>ave</button>
        <button class="btn btn-primary"  id="btnUpdate" onclick="updateHeader()"><i class="fa fa-edit"></i>&nbsp;<u>U</u>pdate</button>
        <button class="btn btn-danger"  id="btnDelete" onclick="deleteHeader()"><i class="fa fa-trash"></i>&nbsp;<u>D</u>elete</button>
 </div>
<fieldset class="scheduler-border" >
        <legend class="scheduler-border">Product Template</legend>
        <button class="btn btn-primary" id="btnViewtemplate" id="addformn" onclick="viewTemplate()"><i class="fa fa-plus"></i>&nbsp;<u>V</u>iew Template</button><br>
          <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; ">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>Action</th>
                  <th>Product Code</th>
                  <th>Product name</th>
                  <th>Qty</th>
                </tr>
              </thead>
              <tbody id="po_product_data" style="height: 90px;overflow-y: scroll;" >
              </tbody>
          </table>
</fieldset>
@endsection

<div class="modal fade" id="view_template"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Product Template</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closemod()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <table>
                <tr>
                    <td style="width: 145px;">Product Group :&nbsp;</td><td><select  class="form-control" id="prodgrp" style="border-color:#ffffff; width: 450px;" onchange="loadProd(this.value)"></select> &nbsp;<button class="btn btn-primary"  id="btnAddData" onclick="addData()"><i class="fa fa-plus"></i>&nbsp;Add</button></td><td></td>
                </tr>
           </table><br>
           <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; ">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>Id</th>
                  <th>Product Code</th>
                  <th>Product name</th>
                  <th>Qty</th>
                </tr>
              </thead>
              <tbody id="loadData">
              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view_header" tabindex="-1"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search Header Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closemod()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; " id="loadHeader">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>Action</th>
                  <th>PO #</th>
                  <th>Control #</th>
                  <th>Customer</th>
                  <th>PO Date</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>
    </div>
  </div>
</div>