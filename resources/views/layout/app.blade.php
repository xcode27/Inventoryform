<!DOCTYPE html>
<html lang="{{config('app.locale')}}">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{config('app.name', 'Chart Of Accounts')}}</title>
    <link rel="shortcut icon" href="{{ asset('images/exxel_logo.jpg') }}">
    <link  href="{{asset("/dashboards/vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet"><!-- Bootstrap core CSS-->
    <link href="{{asset("/dashboards/vendor/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css">   <!-- Custom fonts for this template-->
    <link href="{{asset("/dashboards/vendor/datatables/dataTables.bootstrap4.css")}}" rel="stylesheet"> <!-- Page level plugin CSS-->
    <link href="{{asset("/dashboards/css/sb-admin.css")}}" rel="stylesheet"> <!-- Custom styles for this template-->
     <!-- Bootstrap core JavaScript-->
    <!--<script src="{asset("/dashboards/js/jquery-1.10.2.min.js")}}"></script>-->
    <script src="{{asset("/dashboards/vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset("/dashboards/vendor/jquery-easing/jquery.easing.min.js")}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{asset("/dashboards/vendor/chart.js/Chart.min.js")}}"></script>
    <script src="{{asset("/dashboards/vendor/datatables/jquery.dataTables.js")}}"></script>
    <script src="{{asset("/dashboards/vendor/datatables/dataTables.bootstrap4.js")}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset("/dashboards/js/sb-admin.min.js")}}"></script>
   <!-- <script src="{asset("/dashboards/js/custom_js.js")}}"></script>-->
    <!-- Demo scripts for this page-->
    <script type="text/javascript">

      var getToken = window.localStorage.getItem('access_token');
      var user = window.localStorage.getItem('name');
      var userGroup = window.localStorage.getItem('usergroup');
      
      $(document).ready(function(){

        if(getToken == null){

          window.location = "{{ url('/') }}";

        }

        $('#user').html(user);
        startTime();

        getUserMenus();
        useraccess();
        
      });

    function checkUserControl(){
     
           $.ajax({
                type: "GET",
                dataType : "json",
                beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                cache: false,
                url:"http://192.168.1.55:8002/api/checkUserControl",
                success: function( msg ) {
                  
                  var data = jQuery.parseJSON(JSON.stringify(msg));

                   if(data.status == 'Token is Expired'){

                         alert('Sorry you dont have activity for a couple of minutes. Session expired. Please login again. Thank you !');
                         window.localStorage.removeItem('access_token');
                         window.localStorage.removeItem('name');
                        // window.location.href='{ action("PagesController") }}';
                         window.location = "{{ url('/') }}";
                   }

                    $.each(msg, function(key, value){

                      if(value.module_id == $('#moduleid').val()){

                          if(value.create != 0){
                            
                             $('#add').val(1);

                          }else{

                            $('#add').val(0);

                          }

                          if(value.read == 0){

                            $('#btnSearch').attr("disabled", "disabled");
                           
                          }

                          if(value.update != 0){
                           
                              $('#upd').val(1);

                          }else{
                            $('#upd').val(0);
                          }

                          if(value.delete != 0){

                              $('#del').val(1);

                          }else{

                             $('#del').val(0);

                          }

                      }

                    });
              
            },
               error: function (errormessage) { alert('Error sending request to the server'); }
             });
      }

  function startTime() {

      var today = new Date();
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      m = checkTime(m);
      s = checkTime(s);
      var mode = '';

      if(h > 12 ){
        h = h - 12;
        mode = 'PM';
      }else{
        h = h;
        mode = 'AM';
      }
      document.getElementById('systime').innerHTML =
      h + ":" + m + ":" + s + " "+mode;
      var t = setTimeout(startTime, 500);

  }
    
    function checkTime(i) {

      if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
      return i;

    }

      function logout(){

        window.localStorage.removeItem('access_token');
        window.localStorage.removeItem('name');
        window.localStorage.removeItem('usergroup');
        window.location = "{{ url('/') }}";

      }

      function isNumber(evt) {

          evt = (evt) ? evt : window.event;
          var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode > 31 && (charCode < 48 || charCode > 57)) {
              return false;
          }
          return true;

      }


      function getUserMenus(){
        //var counter = 0;
           $.ajax({

                type: "POST",
                dataType : "json",
                beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                cache: false,
                url:"http://192.168.1.55:8002/api/getParentMenu?system_use=IFT",
                success: function( msg ) {
                  var data = jQuery.parseJSON(JSON.stringify(msg));
                  var dets = '';
                  
                    $.each(msg, function(key, value){
                      var parentname = value.module_description;
                
                                  $.ajax({
                                          type: "POST",
                                          dataType : "json",
                                          beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + getToken ); },
                                          cache: false,
                                          url:"http://192.168.1.55:8002/api/getUserMenu?system_use=IFT&module_id="+value.id,
                                          success: function( msg ) {
                                            //console.log(msg);
                                            //return false;
                                            var data = jQuery.parseJSON(JSON.stringify(msg));
                                            var det1s = '';
                                            
                                             det1s += '<li class="nav-item dropdown ">'+
                                                            '<a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'+
                                                              '<i class="fas fa-fw fa-folder"></i>'+
                                                                  '<span>'+parentname+'</span>'+
                                                            '</a>'+
                                                            '<div class="dropdown-menu" aria-labelledby="pagesDropdown" id="menus" style="width: 250px;">';
                                              $.each(msg, function(key, value){
    
                                                 var parentId = value.id;
                                                
                                                 var mod_url = "{{ url('/') }}" + `/${value.module_url}` +'/'+parentId;
                                                 
                                                 if(mod_url != ''){

                                                      det1s += "<a class='dropdown-item' href="+mod_url+">"+value.module_description+'</a>';

                                                  }
                                                                    
                                              });
                                              det1s += '</div></li>';
                                              $('#parentMenu').append(det1s);
                                              
                                      },
                                         error: function (errormessage) { alert('Error'); }
                                       });
                             
                    });
                    
            },
               error: function (errormessage) { alert('Error'); }
             });

      }

      
      function useraccess(){

          if(userGroup == 0){
              $('#useraccess').show();
          }else{
              $('#useraccess').hide();
          }

      }

       
    </script>

  </head>

  <body id="page-top">
    <nav class="navbar navbar-expand navbar-dark bg-dark navbar-fixed-top">
      <a class="navbar-brand mr-1" href="#"><img src="{{url('/images/inventory.png')}}" style="height: 35px;" alt="cod logo"></img></a>
      <a class="navbar-brand mr-1" href="#">Inventory Form Tracking System</a>
      <a class="navbar-brand mr-1" href="#" style="width:100%; text-align: right;"><i class="fas fa-fw fa-clock"></i>
            <span id="systime"></span> | <i class="fas fa-fw fa-calendar"></i>
            <span id="sysdate"><?php echo date("m-d-Y"); ?></span></a>
      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-12">
        
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw" style="width:250px; text-align: right;">&nbsp;<span>Current User&nbsp;:</span><span id="user">
             </span>
            </i>
          </a>
        
        </li>
      </ul>

    </nav>

    <div id="wrapper">

      <!-- Sidebar -->
      <ul class="sidebar navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="{{ action("PagesController@home") }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="nav-item dropdown " id="supadminmenus">
          <div id="useraccess">
          <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>Menu Management</span>
          </a>
          <div class="dropdown-menu" aria-labelledby="pagesDropdown">
                <a class="dropdown-item" href="{{ action("PagesController@CreateMenu") }}">Create Menu</a>
                <a class="dropdown-item" href="{{ action("PagesController@MappedMenu") }}">User Access Rights</a>
                <a class="dropdown-item" href="{{ action("PagesController@CreateUser") }}">Create User</a>
          </div>
        </div>
        </li>
       
        <div id="parentMenu"></div>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="fas fa-fw fa-arrow-left"></i>
            <span id="logout" onclick="logout();">Logout</span></a>
        </li>
      </ul>

      <div id="content-wrapper">
          <div class="container-fluid" style="height:400px; font-size: 14px;">
            <!-- Breadcrumbs-->

            @yield('content')

          </div>

      </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
  </body>

</html>

