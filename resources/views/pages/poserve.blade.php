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
var handledisble = 0;
  $('document').ready(function(){

    $('#btnadd').focus();
    $('#po_no').prop('disabled', true);
    $('#drsi').prop('disabled', true);
    $('#btnSave').prop('disabled', true);
    $('#btnDelete').prop('disabled', true);
    $('#btnUpdate').prop('disabled', true);

     checkUserControl();
     
     $('#po_no').change(function(){
     
      //must get the PO created upon change
        getPoCreatred($('#po_no').val());

     });

     $('#drsi').change(function(){

        getDrSi($('#drsi').val());

     });


  });

  function addnew(){
    identifier = 0;
    $('#po_no').prop('disabled', false);
     $('#drsi').prop('disabled', false);
   
     

     if($('#add').val() == 1){
       $('#btnSave').prop('disabled', false);
     }
     
      $('#po_no').focus();

     
     $('#btnadd').prop('disabled', true);
   
  }

  function searchdetails(){
       displayPOserve();
       $('#viewservepo').modal('show');
  }

function getPoCreatred(po){

     $.ajax({
          type: 'GET',
          url: '{{URL::to('getPoCreate')}}'+'/' + po,
      }).done(function( msg ) {
        if(msg == ''){
          alert('PO # not found');

          return false;
        }
        $('#drsi').focus();
           $.each(msg, function(key, value){
             $('#storecode').val(value.store);
             $('#store').val(value.store_name);
             $('#po_date').val(value.po_date);
          });
      });
}

function getDrSi(dr){

    $.ajax({
          type: "POST",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getDrSr?dr="+dr,
          url: 'http://192.168.1.55:805/api_cod/index.php?type=drsi'+'&doc_no='+dr+'&token='+ getToken,
          success: function( msg ) {
           
          var dets = '';
          dets +='<option></option>';

          $.each(msg, function(key, value){

             $('#tranno').val(value.TRAN_NO);
             $('#trandate').val(value.TRAN_DATE);
            //dets += '<option value='+value.PROD_SYS_CODE+'>'+value.PROD_DESC+' - ' +value.PROD_COLOR_CODE+'</option>';
             displayRawdata($('#tranno').val());
             
          });
          //$('#product').append(dets);  
      },
         error: function (errormessage) { alert('Error'); }
       });

    
}

function displayRawdata(tranno){
  //Display list of PO transacted
 // var tranno = $('#').val();
  $("#rawdata").dataTable().fnDestroy();
  var tran_token =  tranno +'@'+getToken;
  var url = '{{ route("displayraw", ":tranno") }}';
  var url1 = url.replace(':tranno', tran_token);

     $('#rawdata').DataTable({
          "bPaginate": true,
          "processing": true,
          "serverSide": true,
          "bFilter": false,
          "lengthChange": false,
          "pageLength": 10,
          "ajax":url1,
        "columns":[
            {"data" : "HEAD_TRAN_NO"},
            {"data" : "PROD_SYS_CODE"},
            {"data" : "PROD_DESC"},
            {"data" : "QTY"}


          ]
      });

}

function savePoServe(){

  var po_no = $('#po_no').val();
  var storecode = $('#storecode').val();
  var store = $('#store').val();
  var po_date = $('#po_date').val();
  var drsi = $('#drsi').val();
  var trandate = $('#trandate').val();
  var tranno = $('#tranno').val();

  var datas = {
         _token: '{{csrf_token()}}',po_no:po_no,storecode:storecode,store:store,po_date:po_date,drsi:drsi,trandate:trandate,tranno:tranno,user:user,token:getToken
        }

      $.ajax({
          type: 'POST',
          dataType : 'json',
          data:datas,
          url: '{{URL::to('savePoServe')}}',
      }).done(function( msg ) {
        //console.log(msg);
       // return false;
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
          alert(data.message);
          $('#po_no').val('');
          $('#storecode').val('');
          $('#store').val('');
          $('#po_date').val('');
          $('#drsi').val('');
          $('#trandate').val('');
          $('#tranno').val('');
        displayRawdata($('#tranno').val(''));
       //   location.reload();
        }else{
          alert(data.message);
        }
      });

}

function displayPOserve(){

   $("#listpo").dataTable().fnDestroy();

    var url = '{{ route("displayPOservecreated", ":user") }}';
    var url1 = url.replace(':user', user);

       $('#listpo').DataTable({
                "bPaginate": true,
                "processing": true,
                "serverSide": true,
                "bFilter": true,
                "lengthChange": true,
                "pageLength": 10,
                "ajax":url1,
              "columns":[
                  {"data" : "action", orderable:false, searchable: false},
                  {"data" : "po_no"},
                  {"data" : "store_code"},
                  {"data" : "store"},
                  {"data" : "po_date"},
                  {"data" : "dr_sr"},
                  {"data" : "po_serve_date"},
                  {"data" : "date_created"}
                ]
        });
}

function getInfo(data){
  //alert(data)
  var info = data.split('@');

  $('#recid').val(info[0]);
  $('#po_no').val(info[1]);
  $('#storecode').val(info[2]);
  $('#store').val(info[3]);
  $('#po_date').val(info[4]);
  $('#drsi').val(info[5]);
  $('#trandate').val(info[6]);

  $('#btnadd').prop('disabled', true);

   if($('#upd').val() == 1){

      $('#btnUpdate').prop('disabled', false);

    }

  if($('#del').val() == 1){

      $('#btnDelete').prop('disabled', false);

    }
  
  getDrSi(info[5]);

  identifier = 1;

  $('#viewservepo').modal('hide');

}

function removePoServe(){

  if(confirm('Are you sure you want to remove this data. ?') == true){

        $.ajax({
          type: 'GET',
          url: '{{URL::to('removePoServe')}}'+'/' + $('#recid').val(),
      }).done(function( msg ) {
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
          alert(data.message);
         location.reload();
          
        }
      });

  }
}

</script>

@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">P.O Serve</a>
      </li>
  </ol>
  <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <input type="hidden" id="store_name">
  <input type="hidden" id="store_name1">
  <input type="hidden" id="poid">
  <input type="hidden" id="tranno">
  <input type="hidden" id="recid">
  <button class="btn btn-primary" class="btnSearch" id="btnSearch" onclick="searchdetails()"><i class="fa fa-search"></i>&nbsp;Search</button>
  <br><br>
 <table class="table">
   <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;PO #:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="po_no" style="border-color:#ffffff;" placeholder="ENTER PO #"></input>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Store Code:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="storecode" style="border-color:#ffffff;" readonly></input>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Store Name:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="store" style="border-color:#ffffff;" readonly></input>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;PO Date:</td>
          <td>
            <div class="form-group">
                <input type="date" class="form-control" id="po_date"  style="border-color:#ffffff;" readonly>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;DR/SI #:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="drsi" style="border-color:#ffffff;" placeholder="ENTER DR/SI #"></input>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Transaction Date:</td>
          <td>
            <div class="form-group">
                <input type="date" class="form-control" id="trandate"  style="border-color:#ffffff;" readonly>
             </div>     
          </td>
    </tr>
    
 </table>
<fieldset class="scheduler-border" >
        <legend class="scheduler-border">Product PO Details</legend>
          <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="rawdata">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>HEAD TRAN NO</th>
                  <th>PRODUCT SYSTEM CODE</th>
                  <th>PRODUCT</th>
                  <th>QUANTITY</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
</fieldset>

  <div align="right">
        <button class="btn btn-primary"  id="btnadd" onclick="addnew()"><i class="fa fa-plus"></i>&nbsp;Add</button>
        <button class="btn btn-primary" class="btnSaved" id="btnSave" onclick="savePoServe()"><i class="fa fa-save"></i>&nbsp;Save</button>
        <button class="btn btn-primary"  id="btnUpdate" onclick="updateStore()"><i class="fa fa-edit"></i>&nbsp;Update</button>
        <button class="btn btn-danger"  id="btnDelete" onclick="removePoServe()"><i class="fa fa-trash"></i>&nbsp;Delete</button>
  </div>

@endsection

<div class="modal fade" id="viewservepo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PO Serve Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="listpo">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>ACTION #</th>
                  <th>PO #</th>
                  <th>STORE CODE</th>
                  <th>STORE</th>
                  <th>PO DATE</th>
                  <th>DR/SI</th>
                  <th>TRANSACTION DATE</th>
                  <th>DATE CREATED</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
      </div>
    </div>
  </div>
</div>



