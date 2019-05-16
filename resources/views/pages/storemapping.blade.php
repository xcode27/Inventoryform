
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
<!--<script type="text/javascript" src="{asset("/dashboards/js/custom_js.js")}}"></script>-->
<script src="{{asset("/dashboards/select2s/dist/js/select2.js")}}"></script>
<link  href="{{asset("/dashboards/select2s/dist/css/select2.css")}}" rel="stylesheet">


<script type="text/javascript">
var identifier = '';
var handledisble = 0;
var isUpdated = '';

  $('document').ready(function(){
  
    $('#btnadd').focus();
    

     document.getElementById('plusdiser').onclick = null;
     document.getElementById('plusdate').onclick = null;
     $("#storehidden").hide();

     $('#store').prop('disabled', true);
     $('#storehidden').prop('disabled', true);
     $('#sup').prop('disabled', true);
     $('#promo').prop('disabled', true);
     $('#expdate').prop('disabled', true);
     $('#area').prop('disabled', true);
     $('#subdate').prop('disabled', true);
     $('#btnSave').prop('disabled', true);
     $('#btnDelete').prop('disabled', true);
     $('#btnUpdate').prop('disabled', true);
     $('#addformn').prop('disabled', true);
    
     $('#formcode').change(function(){

        $('#formname').val($("#formcode option:selected").text())

    });

  
    getSupervisor();
    checkUserControl();
    getStores();

    $('#sup').change(function(){

       $("#store").html("");
       getStores();

    });

    $("#store").select2();

    $('#formcode').change(function(){

        $('#formdes').val($("#formcode option:selected").text())

    });

     $('#store').change(function(){

        $('#store_name').val($("#store option:selected").text())

    });
    

  });


  function addnew(){

    identifier = 0;

     $('#store').prop('disabled', false);
     $('#sup').prop('disabled', false);
     $('#promo').prop('disabled', false);
     $('#expdate').prop('disabled', false);
     $('#area').prop('disabled', false);
     $('#subdate').prop('disabled', false);


     if($('#add').val() == 1){

       $('#btnSave').prop('disabled', false);

     }

      $('#btnUpdate').prop('disabled', true);
      $('#btnDelete').prop('disabled', true);
     
      $('#sup').focus();

      $('#store').val('');
      $('#sup').val('');
      $('#promo').val('');
      $('#area').val('');
      $('#expdate').val('');
      $("#textboxDiv").children().remove();
      $("#textboxDiv1").children().remove();
      $('#btnadd').prop('disabled', true);
     
      jQuery("#store").select2().next().show();
    // document.getElementById('plusdiser').onclick = '';
      $('#plusdiser').attr('onClick', 'addDiser();');
      $('#plusdate').attr('onClick', 'addDate();');

      if(isUpdated == 1 || isUpdated == 2){

        $('#sup').val('').trigger('change')
        $('#store').val('').trigger('change')

      }

     $('#rawdata tbody').empty();
     handledisble = 0;

     //alert(handledisble)
  }

function getStores(){

  $.ajax({
          type: "POST",
          dataType : "json",
         // beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url: 'http://192.168.1.55:8002/api/getStore?sup=' +  $('#sup').val(),
          url: 'http://192.168.1.55:805/api_cod/index.php?type=storepersup'+'&sup='+ $('#sup').val()+'&token='+ getToken,
          success: function( msg ) {
  
              var dets = '';
              dets +='<option></option>';

              $.each(msg, function(key, value){
                 
                dets += '<option value='+value.CONTACT_CODE+'>'+value.CONTACT_NAME+'</option>';

              });
              $('#store').append(dets);
          },  
         error: function (errormessage) { /*alert('Error sending request to the server');*/ }
       });

}



function getSupervisor(){

  $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getSupervisor",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=supervisor&token='+ getToken,
          success: function( msg ) {

            var dets = '';
            dets +='<option></option>';

            $.each(msg, function(key, value){
               
              dets += '<option value='+value.USER_SYS_CODE+'>'+value.USER_NAME+'</option>';
            });

            $('#sup').append(dets);

        },
         error: function (errormessage) { alert('Error'); }
       });

   
    $("#sup").select2();

}

function addform(){

  $('#addformsup').modal('show');

}

function searchdetails(){

  $('#btnSave').prop('disabled', true);
  $('#searchstore').modal('show');
  displayStoreMapped();

}

function savestore(){

  if(identifier == 0){

    // saved details
    var promos  ='';
      $( "[id='promo']" ) .each(function(){

          promos += "{" + " 'Name':"+ "'" + $(this).val() + "'" +"}" + ","; //convert to json format
           //promos += "'" + $(this).val() + "'" + ' ,';
          
      });

      var newstring = promos.substring(',', promos.length - 1);
      $('#promodiser').val("["+newstring+"]"); //string is converted to json
      //$('#promodiser').val(newstring);

      var expecteddate  ='';
      $( "[id='expdate']" ) .each(function(){

          expecteddate += "{" + " 'Date':"+ "'" + $(this).val() + "'" +"}" + ","; //convert to json format
           //promos += "'" + $(this).val() + "'" + ' ,';
          
      });

      var newstring1 = expecteddate.substring(',', expecteddate.length - 1);
      $('#expdate1').val("["+newstring1+"]"); //string is converted to json
    

      var storecode = $('#store').val();
      var store_name = $('#store_name').val();
      var supcode = $('#sup').val();
      var supervisor = $("#sup option:selected").text();
      var diser = $('#promodiser').val();
      var area = $('#area').val();
      var submisiondate = $('#expdate1').val();

      var datas = {
             _token: '{{csrf_token()}}',storecode:storecode,store_name:store_name,supcode:supcode,supervisor:supervisor,diser:diser,area:area,user:user,submisiondate:submisiondate
            }

          $.ajax({
              type: 'POST',
              dataType : 'json',
              data:datas,
              url: '{{URL::to('saveStore')}}',
          }).done(function( msg ) {
            //console.log(msg);
           // return false;
              var data = jQuery.parseJSON(JSON.stringify(msg));

              if(data.status == 'success'){
                  alert(data.message);
                  $('#addformn').prop('disabled', false);
                  $('#btnSave').prop('disabled', true);
                  $('#store').prop('disabled', true);
                  $('#sup').prop('disabled', true);
                  $('#promo').prop('disabled', true);
                  $('#area').prop('disabled', true);
                  $('#expdate').prop('disabled', true);
                  $('#addformn').focus();
                  isUpdated = 1;

                  document.getElementById('plusdiser').onclick = null;
                  document.getElementById('plusdate').onclick = null;
                  handledisble = 1;
                  $(".pp").prop('disabled', true);
                  $(".dd").prop('disabled', true);
              }else{

                alert(data.message);

              }
          
          });

  }else{
    //update items
     var promos  ='';

        $( "[id='promo']" ) .each(function(){
            promos += "{" + " 'Name':"+ "'" + $(this).val() + "'" +"}" + ","; //convert to json format
             //promos += "'" + $(this).val() + "'" + ' ,';
            
        });

        var newstring = promos.substring(',', promos.length - 1);

        var ret = newstring.replace("{ 'Name':''},",'');// remove some set of string
        $('#promodiser').val("["+ret+"]"); //string is converted to json
        //$('#promodiser').val(newstring);

        var expecteddate  ='';
        $( "[id='expdate']" ) .each(function(){
            expecteddate += "{" + " 'Date':"+ "'" + $(this).val() + "'" +"}" + ","; //convert to json format
             //promos += "'" + $(this).val() + "'" + ' ,';
            
        });

        var newstring1 = expecteddate.substring(',', expecteddate.length - 1);

        var ret1 = newstring1.replace("{ 'Date':''},",'');// remove some set of string
        $('#expdate1').val("["+ret1+"]"); //string is converted to json
        
        var recid = $('#recstore_id').val();
        var storecode = $('#store').val();
        var store_name = $('#store_name').val();
        var supcode = $('#sup').val();
        var supervisor = $("#sup option:selected").text();
        var diser = $('#promodiser').val();
        var area = $('#area').val();
        var submisiondate = $('#expdate1').val();


        var datas = {
               _token: '{{csrf_token()}}',recid:recid,storecode:storecode,store_name:store_name,supcode:supcode,supervisor:supervisor,diser:diser,area:area,user:user,submisiondate:submisiondate
              }

            $.ajax({
                type: 'POST',
                dataType : 'json',
                data:datas,
                url: '{{URL::to('updateStore')}}',
            }).done(function( msg ) {
              //console.log(msg);
             // return false;
                var data = jQuery.parseJSON(JSON.stringify(msg));
                if(data.status == 'success'){
                  alert(data.message);
                 // window.location.reload();
                 $('#store').prop('disabled', true);
                 $('#sup').prop('disabled', true);
                 $('#promo').prop('disabled', true);
                 $('#area').prop('disabled', true);
                 $('#expdate').prop('disabled', true);
                 $('#btnSave').prop('disabled', true);
                 $('#btnUpdate').prop('disabled', false);
                 $(".promoclass").prop("readonly", true);
                 $(".dateclass").prop("readonly", true);
                 $('#btnadd').prop('disabled', false);

                 
                
                 if($('.dis').val() == ''){
                    $('#pro').hide();
                 }
                
                 if($('.exp').val() == ''){
                     $('#exdate').hide();
                 }
                 handledisble = 1;
                 document.getElementById('plusdiser').onclick = null;
                 document.getElementById('plusdate').onclick = null;

                 isUpdated = 2;
                 
                }else{
                  alert(data.message);
                }

            });
  }
  
}


function addstoreform(){
  
  var supcode = $('#sup').val();
  var storecode = $('#store').val();
  var formcode = $('#formcode').val();
  var formname = $('#formname').val();
  var formdes = $('#formdes').val();
  var frequency = $('#frequency').val();

  var datas = {
         _token: '{{csrf_token()}}',supcode:supcode,storecode:storecode,formcode:formcode,formname:formname,formdes:formdes,frequency:frequency
        }

      $.ajax({
          type: 'POST',
          dataType : 'json',
          data:datas,
          url: '{{URL::to('saveForm')}}',
      }).done(function( msg ) {
        //console.log(msg);
       // return false;
        var data = jQuery.parseJSON(JSON.stringify(msg));
        if(data.status == 'success'){
          //alert(data.message);
         
         displayFormMapped();
         $('#btnadd').prop('disabled', false);
        }else{
          alert(data.message);
        }
      });


}

var promo_ctr = 0; // 
function addDiser(){

  var promo = $('#promo');

  if($('#promo').val() == ''){
    alert('Please input diser');
    return false;
  }


  var id = "promo-"+promo_ctr;

  $("#textboxDiv").append(
      "<div class='input-group' id='ptest'>"+
        "<input type='' class='form-control pp' id='promo'  style='border-color:#ffffff;' placeholder='ENTER DISER' />"+
          "<div class='input-group-prepend'>"+
            "<span class='input-group-text' title='Remove Promo' style='border-color:#ffffff; cursor:pointer;'> "+
              "<i class='fa fa-minus promo-remove' id='"+id+"' ></i>"+  // onclick='RemoveDiser()'
            "</span>"+
          "</div>"+
        "</div>");

  promo_ctr++;
  RemoveDiser(id);
}

var date_ctr = 0;
function addDate(){

  var expdate = $('#expdate');

  if($('#expdate').val() == ''){
    alert('Please input date');
    return false;
  }

  var id = "expdate-"+date_ctr;

  $("#textboxDiv1").append(
                            "<div class='input-group'>"+
                                "<input type='date' class='form-control dd' id='expdate'  style='border-color:#ffffff;' />"+
                                        "<div class='input-group-prepend'>"+
                                            "<span class='input-group-text' title='Remove Date' style='border-color:#ffffff; cursor:pointer;'>"+
                                                "<i class='fa fa-minus date-remove' id='"+id+"'  ></i>"+
                                             "</span>"+
                                          "</div>"+
                                "</div>"); //onclick='RemoveDate()'
    
    date_ctr++;
    RemoveDate(id);
  }

function RemoveDiser(id){

  $('.promo-remove#'+id).on('click', function(){

    if(handledisble == 1){
      return false;
    }
    var x = $(this).parent().parent().parent();
    var len =  $('#textboxDiv').length;
    x.remove();

  });
//$("#textboxDiv").children().last().remove();
}

function RemoveDate(id){
  $('.date-remove#'+id).on('click', function(){

    if(handledisble == 1){
      return false;
    }

    var x = $(this).parent().parent().parent();
     x.remove();

  });
  //$("#textboxDiv1").children().last().remove();
}

function displayFormMapped(){

  $("#rawdata").dataTable().fnDestroy();
  var storecode = '';

  if($('#storehidden').val() == ''){
      storecode = $('#store').val()
  }else{
      storecode = $('#storehidden').val();
  }

  var url = '{{ route("displayFormperOutlet", ":storecode") }}';
  var url1 = url.replace(':storecode', storecode);

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
              {"data" : "form_description"},
              {"data" : "frequency"},
              {"data" : "action", orderable:false, searchable: false}


            ]
      });

}

function closeFormmodal(){

    window.location.reload();
}

function removeForm(id){
  if(confirm('Are you sure you want to remove this form') == true){

       $.ajax({
          type: 'GET',
          url: '{{URL::to('removeform')}}'+'/' +id,
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

  $('#store').prop('disabled', true);
  $('#sup').prop('disabled', true);
  $('#promo').prop('disabled', true);
  $('#textboxDiv').prop('disabled', true);
  $('#area').prop('disabled', true);
  $("#textboxDiv").children().remove();
  $("#textboxDiv1").children().remove();
   

  var data = id.split('@');
  var promodiser = data[3];
  var newStr = promodiser.replace(/'/g, '"');
  var expecteddates = data[7];
  var newStr1 = expecteddates.replace(/'/g, '"');

    var xx = JSON.parse(newStr)
    var xxx = JSON.parse(newStr1)
    

    $('#recstore_id').val(data[0]);
    $('#sup').val(data[2]);
    $('#store').val(data[1]);
    $('#area').val(data[4]);
   
    $('#sup').val(data[6]).trigger('change')
    $('#store2').val(data[1]).trigger('change')
    $('#storehidden').val(data[1]);
     
    var promo_ctr1 = 0;
    var id = "promo-"+promo_ctr;
    $.each(xx, function(key, value){
   
        $("#textboxDiv").append("<div class='input-group'><input type='text' class='form-control promoclass' id='promo'  style='border-color:#ffffff;' placeholder='ENTER DISER' value='"+value.Name+"' readonly /><div class='input-group-prepend'><span class='input-group-text' title='Remove Promo' style='border-color:#ffffff; cursor:pointer;' > <i class='fa fa-minus promo-remove' id='"+id+"' onclick='RemoveDiser()'></i></span></div></div>");

         promo_ctr1++;
         //console.log(promo_ctr1)
    });

    RemoveDiser(id);


    var date_ctr1 = 0;
    var id1 = "expdate-"+date_ctr1;
     $.each(xxx, function(key, value){

        $("#textboxDiv1").append("<div class='input-group'><input type='date' class='form-control dateclass' id='expdate'  style='border-color:#ffffff;' value='"+value.Date+"' readonly /><div class='input-group-prepend'><span class='input-group-text' title='Remove Date' style='border-color:#ffffff; cursor:pointer;' > <i class='fa fa-minus date-remove' id='"+id1+"'></i></span></div></div>");
        
      date_ctr1++;
    });
     RemoveDate(id1);

    $('#addformn').prop('disabled', false);
    displayFormMapped();

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
      
    //$('#promo').val(dets);
    document.getElementById('plusdiser').onclick = null;
    document.getElementById('plusdate').onclick = null;
    //document.getElementById('revdiser').onclick = null;
    handledisble = 1;

    
    jQuery("#store").select2().next().hide();
    $("#storehidden").show();
     //$("#store2").select2();
    
    $('#searchstore').modal('hide');
    $('#pro').hide();
    $('#exdate').hide();

}

function updateStore(){

     handledisble = 0;
     $('#store').prop('disabled', false);
     $('#sup').prop('disabled', false);
     $('#promo').prop('disabled', false);
     $('#area').prop('disabled', false);
     $('#expdate').prop('disabled', false);

     identifier = 1;
     $('#btnSave').prop('disabled', false);
     $('#btnUpdate').prop('disabled', true);
     $('#btnadd').prop('disabled', true);
     $('#plusdiser').attr('onClick', 'addDiser();');
     $('#plusdate').attr('onClick', 'addDate();');
     $(".promoclass").removeAttr("readonly");
     $(".dateclass").removeAttr("readonly");
  
     $('#pro').show();
     $('#exdate').show();
     $('#store').val($('#storehidden').val()).trigger('change');
     jQuery("#store").select2().next().show();
     $("#storehidden").hide();

    return false;

}

function deleteStore(){

  var recid = $('#recstore_id').val();
  if(confirm('Are you sure you want to remove this form') == true){

       $.ajax({
          type: 'GET',
          url: '{{URL::to('removestore')}}'+'/' +recid,
        }).done(function( msg ) {
       // console.log(msg);
      //  return false;
          var data = jQuery.parseJSON(JSON.stringify(msg));

          if(data.status == 'success'){

            alert(data.message);
            $('#rawdata').DataTable().ajax.reload();
             $('#sup').val('');
             $('#promo').val('');
             $('#area').val('');
             $("#textboxDiv").children().remove();
             $('#store').val('').trigger('change')
          
          }
      });
  }
}


</script>

@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Store Mapping</a>
      </li>
  </ol>
  
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <input type="hidden" id="store_name">
  <input type="hidden" id="store_name1">
  <input type="hidden" id="poid">
  <input type="hidden" id="tranno">
  <input type="hidden" id="promodiser">
  <input type="hidden" id="expdate1">
  <input type="hidden" id="recstore_id">
  <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="storecodehidden">
  <button class="btn btn-primary" class="btnSearch" id="btnSearch" onclick="searchdetails()"><i class="fa fa-search"></i>&nbsp;Search</button>
  <br><br>
 <table class="table">
  <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Supervisor:</td>
          <td>
            <div class="form-group">
                <select  class="form-control" id="sup" style="border-color:#ffffff; width: 100%;" data-search="true" >
                </select>
             </div>     
          </td>
    </tr>
   <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Outlet / Customer:</td>
          <td>
            <div class="form-group">
                <select  class="form-control" id="store" style="border-color:#ffffff;" data-search="true">
                </select>
                <input type="text" class="form-control" id="storehidden" readonly="">
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Promo:</td>
          <td>
            <div class="input-group" id="pro">
             
              <input type="text" class="form-control dis" id="promo"  style="border-color:#ffffff;" placeholder="ENTER DISER"></input>
               <div class="input-group-prepend">
                <span class="input-group-text" title="Add Promo" style="border-color:#ffffff; cursor:pointer;" ><i class="fa fa-plus"  onclick="addDiser()" id="plusdiser"></i></span>
              </div>
            </div>
            <div id="textboxDiv"></div>
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Area:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="area" style="border-color:#ffffff;" placeholder="ENTER AREA"></input>
             </div>     
          </td>
    </tr>
     <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Date submission:</td>
          <td>
            <div class="input-group" id="exdate">
             
              <input type="date" class="form-control exp" id="expdate"  style="border-color:#ffffff;"></input>
               <div class="input-group-prepend">
                <span class="input-group-text" title="Add date" style="border-color:#ffffff; cursor:pointer;" ><i class="fa fa-plus"  onclick="addDate()" id="plusdate"></i></span>
              </div>
            </div>
            <div id="textboxDiv1"></div>
          </td>
    </tr>
 </table>
  <div align="right">
        <button class="btn btn-primary"  id="btnadd" onclick="addnew()"><i class="fa fa-plus"></i>&nbsp;Add</button>
        <button class="btn btn-primary"  id="btnSave" onclick="savestore()"><i class="fa fa-save"></i>&nbsp;Save</button>
        <button class="btn btn-primary"  id="btnUpdate" onclick="updateStore()"><i class="fa fa-edit"></i>&nbsp;Update</button>
        <button class="btn btn-danger"  id="btnDelete" onclick="deleteStore()"><i class="fa fa-trash"></i>&nbsp;Delete</button>
  </div>
<fieldset class="scheduler-border" >
        <legend class="scheduler-border">Form Details</legend>
        <button class="btn btn-primary" class="btnSaved" id="addformn" onclick="addform()"><i class="fa fa-plus"></i>&nbsp;Add Form</button><br>
          <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="rawdata">
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>FORM NAME</th>
                  <th>FORM DESCRIPTION</th>
                  <th>FREQUENCY</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
          </table>
</fieldset>

@endsection

<div class="modal fade" id="addformsup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add new form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
           <table class="table" style="width: 100%; overflow-x: scroll; font-size: 12px;">
               <tr>
                    <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Form name:</td>
                      <td>
                        <div class="form-group">
                            <select  class="form-control" id="formcode" style="border-color:#ffffff;">
                                <option></option>
                                <option value="ORO-FO">ORO FORM</option>
                                <option value="OMG-NA">OMG NAIL FORM</option>
                                <option value="OMG-HA">OMG HAIR FORM</option>
                            </select>
                             <input type="hidden" id="formname">
                         </div>     
                      </td>
                </tr>
                 <tr>
                      <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Form Description:</td>
                        <td>
                          <div class="form-group">
                              <textarea  class="form-control" id="formdes" style="border-color:#ffffff;"></textarea>
                           </div>     
                        </td>
                  </tr>
                  <tr>
                      <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Form Submission Frequency:</td>
                        <td>
                          <div class="form-group">
                              <input type="number" class="form-control" id="frequency"  style="border-color:#ffffff;">
                           </div>     
                        </td>
                  </tr>
                  <tr>
                      <td colspan="2" align="right">
                          <button class="btn btn-primary"  id="addstoreform" onclick="addstoreform()"><i class="fa fa-plus"></i>&nbsp;Add</button>
                      </td>
                  </tr>
            </table>
      </div>
    </div>
  </div>
</div>

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