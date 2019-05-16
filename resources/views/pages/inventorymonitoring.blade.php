@extends('layout.app')

<script src="{{asset("/dashboards/js/jquery.min.js")}}"></script>
<script src="{{asset("/dashboards/js/custom_js.js")}}"></script>
<script type="text/javascript">
  $('document').ready(function(){
      //displaySaveItems();
      checkUserControl();
  });


function displaySaveItems(){
  //$('#rawdata').DataTable().ajax.reload();
  $("#rawdata").dataTable().fnDestroy();
  var  year = $('#year').val();
  var form = $('#formcode').val();
  var param  = form + '@' + year
  var url = '{{ route("getMonitoring", ":year") }}';
  var url1 = url.replace(':year', param);
  

  if(form == ''){
    alert('Form is required');
    return false;
  }

  if(year == ''){
    alert('Please input year');
    return false;
  }

  $('#rawdata').DataTable({
    "processing": true,
    "serverSide": true,
    "bFilter": true,
    "lengthChange": true,
    "pageLength": 10,
    "ajax":url1,
    "columns":[
        {"data" : "store"},
        {"data" : "store_name"},
        {"data" : "JANUARY"},
        {"data" : "FEBRUARY"},
        {"data" : "MARCH"},
        {"data" : "APRIL"},
        {"data" : "MAY"},
        {"data" : "JUNE"},
        {"data" : "JULY"},
        {"data" : "AUGUST"},
        {"data" : "SEPTEMBER"},
        {"data" : "OCTOBER"},
        {"data" : "NOVEMBER"},
        {"data" : "DECEMBER"}

      ]
  });
}

function downloadReport(){
  var  year = $('#year').val();
  var form = $('#formcode').val();
  var param  = form + '@' + year

  if(year == ''){
    alert('Please input year');
  }

  
  window.location.href='{{URL::to('downloadReport')}}'+'/'+param;
}

</script>

@section('content')
 <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Monitoring Report</a>
      </li>
  </ol>
   <input type="hidden" id="add">
  <input type="hidden" id="del">
  <input type="hidden" id="upd">
  <input type="hidden" id="moduleid" value="{{$mod_id}}">
  <table>
      <tr>
        <td>Form&nbsp;:</td>
        <td>
          <select  class="form-control" id="formcode" style="border-color:#ffffff;">
              <option></option>
              <option value="ORO-FO">ORO FORM</option>
              <option value="OMG-NA">OMG NAIL FORM</option>
              <option value="OMG-HA">OMG HAIR FORM</option>
          </select>
        </td>
        <td style="padding:auto;"><span>&nbsp;Enter year&nbsp;:&nbsp;</span></td>
        <td>
            <input type="number" class="form-control" id="year" placeholder="Enter year">
        </td>
        <td>&nbsp;<button class="btn btn-primary"  id="btnSearch" onclick="displaySaveItems()"><i class="fa fa-search"></i>&nbsp;Search</button></td>
        <td>&nbsp;<button class="btn btn-primary"  id="btnDL" onclick="downloadReport()"><i class="fa fa-download"></i>&nbsp;Download</button></td>
      </tr>
  </table>
 <br>
 <table class="table table-striped" style="width: 100%; overflow-x: scroll; font-size: 12px; text-align: center; "  id="rawdata">
      <thead>
        <tr style="background-color:#737373; color:white;">
          <th>STORE CODE</th>
          <th>STORE</th>
          <th>JAN</th>
          <th>FEB</th>
          <th>MAR</th>
          <th>APR</th>
          <th>MAY</th>
          <th>JUN</th>
          <th>JUL</th>
          <th>AUG</th>
          <th>SEPT</th>
          <th>OCT</th>
          <th>NOV</th>
          <th>DEC</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <br><br>
   


@endsection