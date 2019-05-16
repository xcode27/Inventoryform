@extends('layout.app')
<style type="text/css">

#scroll {
    width: 716px; /* 140px * 5 column + 16px scrollbar width */
    border-spacing: 0;
    border: 1px solid black;
}

#scroll tbody,
#scroll thead tr { display: block;}

#scroll tbody {
    height: 350px;
    overflow-y: auto;
    overflow-x: hidden;
}

#scroll tbody td,
#scroll thead th {
    width: 300px;
    text-align: center;
}

#scroll thead th:last-child {
    width: 156px; /* 140px + 16px scrollbar width */
}

#scroll thead tr th { 
    height: 30px;
    text-align: center;
    /*text-align: left;*/
}

/*#scroll tbody { border-top: 2px solid black; }*/

#scroll tbody td:last-child, thead th:last-child {
    border-right: none !important;
}

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
  var disableddeleteform = 0;
  var api_url = 'http://192.168.1.55:8002/api';
  $('document').ready(function(){

     $('#btnadd').focus();
     $('#updateprod').hide()
     checkUserControl();
     $('#btnSave').prop('disabled', true);
     $('#btnDelete').prop('disabled', true);
     $('#btnUpdate').prop('disabled', true);
     $('#adj_no').prop('disabled', true);
     $('#invdate').prop('disabled', true);
     $('#product').prop('disabled', true);
     $('#remarks').prop('disabled', true);
     $('#remove').prop('disabled', true);
     $('#sup').prop('disabled', true);
     $('#warehouse').prop('disabled', true);
     $('#remarks').prop('disabled', true);
     $('#addformn').prop('disabled', true);
     $('#form').prop('disabled', true);
     $('#addprod').prop('disabled', true);
      getProducts();
      getWarehouse();

       
  });

  function getProducts(){
  $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getAllProducts",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=allproducts&token='+ getToken,
          success: function( msg ) {
          var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
             
            dets += '<option value='+value.PROD_SYS_CODE+'>'+value.PROD_DESC+' - ' +value.PROD_COLOR_CODE+'</option>';
          });
          $('#product').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });
   $('#product').select2({closeOnSelect:false});
}

function addnew(){

    identifier = 0;

     if($('#add').val() == 1){
       $('#btnSave').prop('disabled', false);
     }
     
     $('#adj_no').val('');
     $('#invdate').val('');
     $('#product').val('');
     $('#qty').val('');
     $('#warehouse').val('');
     $('#remarks').val('');
     $('#adj_no').prop('disabled', false);
     $('#invdate').prop('disabled', false);
    // $('#product').prop('disabled', false);
     $('#qty').prop('disabled', false);
     $('#remarks').prop('disabled', false);
     //$('#btnSearch').prop('disabled', true);
     $('#prodcode').val('');
     $('#prodcode').focus();
     $('.forhide').show();
     $('#warehouse').prop('disabled', false);
    
    
      $('#temp_data').children().remove();

  }

function saveAdjustment(){

    if(identifier == 0){

        
               var docno = $('#adj_no').val();
               var entrydate = $('#invdate').val();
               var warehouse = $('#warehouse').val();
               var remarks = $('#remarks').val();
             
                      

            if(docno == ''){

                alert('Document no is required');
                return false;
            }

            if(entrydate == ''){

                alert('Inventory date is required');
                return false;
            }

            if(warehouse == ''){
              alert('Warehouse is required');
              return false;
            }
        
            $.ajax({
              type: "POST",
              //data : request,
             // dataType : "json",
              url: 'http://192.168.1.55:805/api_cod/index.php?type=saveAdjustmentEntry&token='+ getToken +'&docno='+docno+'&entrydate='+entrydate+'&warehouse='+warehouse+'&remarks='+remarks+'&user='+user,
              //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
              success: function(msg){
                  if(msg == 'saved'){
                    alert('Inventory main detials saved. Please add products for inventory entry.');
                      $('#adj_no').prop('disabled', true);
                      $('#invdate').prop('disabled', true);
                      $('#remarks').prop('disabled', true);
                      $('#warehouse').prop('disabled', true);
                      $('#btnSave').prop('disabled', true);
                      $('#addprod').prop('disabled', false);
                      $('#addprod').focus();
                  }else{
                    alert(msg)
                  }
              }
            });

          

        }else{

            
                var recid = $('#recid').val();
                 var docno = $('#adj_no').val();
                 var entrydate = $('#invdate').val();
                 var warehouse = $('#warehouse').val();
                  var remarks = $('#remarks').val();
                       
                      

                 if(docno == ''){

                     alert('Document no is required');
                     return false;
                  }

                  if(entrydate == ''){

                      alert('Inventory date is required');
                      return false;
                  }

                  if(warehouse == ''){
                    alert('Warehouse is required');
                    return false;
                  }
              
                  $.ajax({
                    type: "POST",
                    //data : request,
                   // dataType : "json",
                    url: 'http://192.168.1.55:805/api_cod/index.php?type=updateAdjustmentEntry&token='+ getToken +'&docno='+docno+'&entrydate='+entrydate+'&warehouse='+warehouse+'&remarks='+remarks+'&user='+user+'&id='+recid,
                    //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                    success: function(msg){
                        if(msg == 'saved'){
                          alert('Inventory main detials updated.');
                        }else{
                          alert(msg)
                        }
                    }
                  });

      }

}



function searchdetails(){

  displayAdjusmentData($('#search').val());
  $('#searchbox').modal('show');

}

function getHeaderBySearch(val){
    displayAdjusmentData(val)
}

function displayAdjusmentData(val){

    $('#rawdata').children().remove();

    $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getAllProducts",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=displayHeader&token='+ getToken+'&param='+val,
          success: function( msg ) {

                var dets = '';

                $.each(msg, function(key, value){

                 var param =value.id +'/'+value.doc_no+'/'+value.remarks+'/'+value.inv_date+'/'+value.warehouse;

                   dets += '<tr>';
                   dets += '<td><button class="btn btn-primary"   id="'+param+'" onclick="getDetails(this.id)"   style="cursor:pointer;"><i class="fa fa-edit"></i></button></td>';
                   dets += '<td>'+value.doc_no+'</td>';
                   dets += '<td>'+value.inv_date+'</td>';
                   dets += '<td>'+value.warehouse+'</td>';
                 //alert(value.id)
                });
                $('#rawdata').append(dets);
            }
       });

}

function getDetails(id){
  
  var data = id.split('/');

     $('#recid').val(data[0]);
     $('#adj_no').val(data[1]);
     $('#invdate').val(data[3]);
    // $('#product').val(data[2]).trigger('change')
     $('#warehouse').val(data[4]);
     $('#remarks').val(data[2]);

     $('#searchbox').modal('hide');

      $('#addformn').prop('disabled', false);
   

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

       $('#btnSave').prop('disabled', true);
       $('#addprod').prop('disabled', false);
       displayProdlist(data[1]);

}

function updateAdjustment(){
  identifier = 1;
   $('#btnSave').prop('disabled', false);
   $('#btnUpdate').prop('disabled', true);
   $('#btnadd').prop('disabled', true);
   $('#btnDelete').prop('disabled', true);

   $('#adj_no').prop('disabled', false);
   $('#invdate').prop('disabled', false);
   $('#remarks').prop('disabled', false);
    $('#warehouse').prop('disabled', false);
}

function getProduct(){
   $('#product').val($('#prodcode').val()).trigger('change')
}

function displayProdlist(){

    $('#temp_data').children().remove();
    var docno = $('#adj_no').val();

      $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getAllProducts",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=displayProd&token='+ getToken +'&docno='+docno,
          success: function( msg ) {

                var dets = '';
              
                $.each(msg, function(key, value){

                 var param = value.id +'/'+value.prod_sys_codes+'/'+value.qty;

                 dets += '<tr>';
                 dets += '<td>'+value.prod_sys_codes+'</td>';
                 dets += '<td>'+value.product_desc+'</td>';
                 dets += '<td>'+value.qty+'</td>';
                 dets += '<td><button class="btn btn-primary"   id="'+param+'" onclick="getListinfo(this.id)"   style="cursor:pointer;"><i class="fa fa-edit"></i></button>'+
                                          '&nbsp;<button class="btn btn-danger"   id="'+value.id+'" onclick="deleteProd(this.id)"   style="cursor:pointer;"><i class="fa fa-trash"></i></button></td>';

                });
                $('#temp_data').append(dets);
            }
       });

}

function addProduct(){
  //$('#prodcode').focus();
  $('#productbox').modal('show');

}

function addtolist(){

    var    docno =  $('#adj_no').val();
    var    product =  $('#product').val();
    var    product_name = $("#product option:selected").text();
    var    qty =  $('#qty').val();
  

     $.ajax({
              type: "POST",
              //data : request,
             // dataType : "json",
              url: 'http://192.168.1.55:805/api_cod/index.php?type=addProd&token='+ getToken +'&docno='+docno+'&product='+product+'&product_name='+product_name+'&qty='+qty,
              //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
              success: function(msg){
                  if(msg == 'saved'){
                    //alert('Inventory main detials saved. Please add products for inventory entry.');
                    displayProdlist();
                  }else{
                    alert(msg)
                  }
              }
            });


}

function closeproductform(){
    $('#productbox').modal('hide');
}

function deleteEntry(){


 var recid = $('#recid').val();

    if(confirm('Are you sure you want to remove this Data') == true){

                  $.ajax({
                    type: "POST",
                    //data : request,
                   // dataType : "json",
                    url: 'http://192.168.1.55:805/api_cod/index.php?type=deleteHeader&token='+ getToken +'&id='+recid,
                    //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                    success: function(msg){
                     
                        if(msg == 'success'){
                          alert('Header details successfully deleted.');
                           $('#adj_no').val('');
                           $('#invdate').val('');
                           $('#remarks').val('');
                           $('#warehouse').val('');
                           displayProdlist();
                        }else{
                          alert('error');
                        }
                    }
                  });

    }

  
}

function getListinfo(id){
  var data = id.split('/');
  $('#prodid').val(data[0])
  $('#product').val(data[1]).trigger('change');
  $('#qty').val(data[2]);
  $('#updateprod').show();
  $('#addtolist').hide();
  
  addProduct();
}

function updateprod(){


    var    prodid = $('#prodid').val();
    var    qty  = $('#qty').val();
  

$.ajax({
            type: "POST",
            //data : request,
           // dataType : "json",
            url: 'http://192.168.1.55:805/api_cod/index.php?type=updateProdut&token='+ getToken +'&id='+prodid+'&qty='+qty,
            //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
            success: function(msg){
                if(msg == 'yes'){
                  alert('Product updated');
                  displayProdlist()
                }else{
                  alert('Error')
                }
            }
          });
}

function deleteProd(id){

 

    if(confirm('Are you sure you want to remove this Data') == true){

          $.ajax({
                    type: "POST",
                    //data : request,
                   // dataType : "json",
                    url: 'http://192.168.1.55:805/api_cod/index.php?type=deleteProdut&token='+ getToken +'&id='+id,
                    //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                    success: function(msg){
                     
                        if(msg == 'success'){
                          alert('Product details successfully deleted.');
                           //$('#adj_no').val('');
                           //$('#invdate').val('');
                          // $('#remarks').val('');
                          // $('#warehouse').val('');
                           displayProdlist()
                        }else{
                          alert('error');
                        }
                    }
                  });
    }
}

function getWarehouse(){

     $.ajax({
          type: "GET",
          dataType : "json",
          //beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
          //url:"http://192.168.1.55:8002/api/getAllProducts",
          url: 'http://192.168.1.55:805/api_cod/index.php?type=warehouse&token='+ getToken,
          success: function( msg ) {
          var dets = '';
          dets +='<option></option>';
          $.each(msg, function(key, value){
             
            dets += '<option value='+value.WRH_CODE+'>'+value.WRH_CODE+'</option>';
          });
          $('#warehouse').append(dets);
      },
         error: function (errormessage) { alert('Error'); }
       });

}
</script>

@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Actual Inventory Entry</a>
      </li>
  </ol>
  <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <input type="hidden" id="store">
  <input type="hidden" id="storeid">
  <input type="hidden" id="recid">
 
 <table class="table">
    <tr>
        <td><button class="btn btn-primary" class="btnSearch" id="btnSearch" onclick="searchdetails()"><i class="fa fa-search"></i>&nbsp;Search</button></td>
        <td align="right"></td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Document #:</td>
          <td>
            <div class="form-group">
                <input type="text"  class="form-control" id="adj_no" style="border-color:#ffffff;"></input>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Inventory Date:</td>
          <td>
            <div class="form-group">
                <input type="date"  class="form-control" id="invdate" style="border-color:#ffffff;"></input>
             </div>     
          </td>
    </tr>
    <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Warehouse:</td>
          <td>
            <div class="form-group">
                <select  class="form-control" id="warehouse" style="border-color:#ffffff;">
                    <option></option>
                    <!--<option value="ORO">ORO</option>
                    <option value="SM">SM</option>
                    <option value="BW">BW</option>-->
                </select>
             </div>  
          </td>
      </tr>
      <tr>
        <td style="width:200px;"><span style="color:red;">*</span> &nbsp;Remarks:</td>
          <td>
            <div class="form-group">
                <textarea  class="form-control" id="remarks" style="border-color:#ffffff;"> 
                </textarea>
             </div>  
          </td>
      </tr>
    <tr>
        <td colspan="2" align="right">
            <button class="btn btn-primary"  id="btnadd" onclick="addnew()"><i class="fa fa-plus"></i>&nbsp;Add</button>
            <button class="btn btn-primary" class="btnSaved" id="btnSave" onclick="saveAdjustment()"><i class="fa fa-save"></i>&nbsp;Save</button>
            <button class="btn btn-primary"  id="btnUpdate" onclick="updateAdjustment()"><i class="fa fa-edit"></i>&nbsp;Update</button>
            <button class="btn btn-danger"  id="btnDelete" onclick="deleteEntry()"><i class="fa fa-trash"></i>&nbsp;Delete</button>
        </td>
    </tr>

 </table>

 <fieldset class="scheduler-border" >
        <legend class="scheduler-border">List of Product</legend>
        <button class="btn btn-primary"  id="addprod" onclick="addProduct()"><i class="fa fa-plus"></i>&nbsp;Add Product</button><br>
          <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  >
              <thead>
                <tr style="background-color:#737373; color:white;">
                  <th>PRODUCT CODE</th>
                  <th>PRODUCT DESCRIPTION</th>
                  <th>QTY</th>
                  <th>ACTION</th>
                </tr>
              </thead>
              <tbody id="temp_data">
              </tbody>
          </table>
</fieldset>

@endsection
<div class="modal fade" id="searchbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
            Search : &nbsp;<input type="text" id="search" class="input-control" onkeyup="getHeaderBySearch(this.value)" />
        </div><br>
           <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="scroll">
              <thead>
               
                <tr style="background-color:#737373; color:white;">
                  <th>ACTION</th>
                  <th>DOC #</th>
                  <th>INVENTORY DATE</th>
                  <th>WAREHOUSE</th>
                </tr>
              </thead>
                  <tbody id="rawdata"></tbody>
          </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="productbox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
      </div>
      <div class="modal-body">
        <div align="right">
           Product Code: <input type="text" id="prodcode" style="width: 300px; border-radius: 4px;" onkeyup="getProduct()"></input>
        </div>
        <br>
        <div>
              <div class="form-group">
                 <input type="hidden"  id="prodid" style="width: 100%;"></input>
                   Product <select type="text"  class="form-control" id="product" style="border-color:#ffffff; width: 100%;">
                    </select>
              </div>  
              <div class="form-group">
                Quantity
                    <input type="number"  id="qty" style="width: 100%;"></input>
               </div> 
               <div class="form-group">
                    <button class="btn btn-primary"  id="addtolist" onclick="addtolist()">&nbsp;Add to list</button>
                    <button class="btn btn-primary"  id="updateprod" onclick="updateprod()">&nbsp;Update</button>
                     <button class="btn btn-danger"  id="closeform" onclick="closeproductform()">&nbsp;Close</button>
               </div>
        </div>
      </div>
    </div>
  </div>
</div>



