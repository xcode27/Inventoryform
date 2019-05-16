@extends('layout.app')

<script src="{{asset("/dashboards/js/jquery.min.js")}}"></script>
<script src="{{asset("/dashboards/js/custom_js.js")}}"></script>
<script src="{{asset("/dashboards/select2s/dist/js/select2.js")}}"></script>
<link  href="{{asset("/dashboards/select2s/dist/css/select2.css")}}" rel="stylesheet">
<script type="text/javascript">
  $('document').ready(function(){
      checkUserControl();
      getProducts();
      getWarehouse();

      $("#product").select2({
          placeholder: "Select product",
          allowClear: true
      });

      $('#product').change(function(){
          $('#productname').val($('#product option:selected').text());
       });

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
        <a href="#">Inventory History</a>
      </li>
  </ol>
   <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <form method="POST" action="http://192.168.1.55:805/exxel/reports/inventory_history.php" target="_blank">
  <table>
      <tr>
        <td>Warehouse&nbsp;:</td>
        <td style="width: 250px;">
          <select type="text"  class="form-control" name="warehouse" style="border-color:#ffffff; width: 100%;" id="warehouse">
              <option></option>
              <!--<option value="ORO">ORO</option>
              <option value="SM">SM</option>
              <option value="BW">BW</option>-->
          </select>
        </td>
        <td>Product&nbsp;:</td>
        <td style="width: 250px;">
          <select type="text"  class="form-control" id="product" style="border-color:#ffffff; width: 100%;" name="product"></select>
          <input type="hidden" name="productname" id="productname" />
        </td>
        <td style="padding:auto;"><span>&nbsp;Date From&nbsp;:&nbsp;</span></td>
        <td>
            <input type="date" class="form-control" id="sdate" name="sdate">
        </td>
        <td style="padding:auto;"><span>&nbsp;To&nbsp;:&nbsp;</span></td>
        <td>
            <input type="date" class="form-control" id="edate" name="edate">
        </td>
        <td>&nbsp;<button class="btn btn-primary"  id="btnView" >&nbsp;Search</button></td>
      </tr>
  </table>
  </form>
 <br>

@endsection