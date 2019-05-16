<!DOCTYPE html>
<html lang="{{config('app.locale')}}">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{config('app.name', 'Inventoryformtracking')}}</title>
    <link rel="shortcut icon" href="{{ asset('images/exxel_logo.jpg') }}">
    <link  href="{{asset("/dashboards/vendor/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet"><!-- Bootstrap core CSS-->
    <link href="{{asset("/dashboards/vendor/fontawesome-free/css/all.min.css")}}" rel="stylesheet" type="text/css">   <!-- Custom fonts for this template-->
    <link href="{{asset("/dashboards/vendor/datatables/dataTables.bootstrap4.css")}}" rel="stylesheet"> <!-- Page level plugin CSS-->
    <link href="{{asset("/dashboards/css/sb-admin.css")}}" rel="stylesheet"> <!-- Custom styles for this template-->
     <!-- Bootstrap core JavaScript-->
    <script src="{{asset("/dashboards/vendor/jquery/jquery.min.js")}}"></script>
    <script src="{{asset("/dashboards/vendor/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{asset("/dashboards/vendor/jquery-easing/jquery.easing.min.js")}}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{asset("/dashboards/vendor/chart.js/Chart.min.js")}}"></script>
    <script src="{{asset("/dashboards/vendor/datatables/jquery.dataTables.js")}}"></script>
    <script src="{{asset("/dashboards/vendor/datatables/dataTables.bootstrap4.js")}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset("/dashboards/js/sb-admin.min.js")}}"></script>
    <!-- Demo scripts for this page-->
    <script src="{{asset("/dashboards/js/demo/datatables-demo.js")}}"></script>
    <script type="text/javascript">
    $(document).ready(function(){

    	$('#loader').hide();

		$('#pw').keypress(function(e){
			var key = e.which;
			 if(key == 13)  // the enter key code
			  {
			    loginUser();
			  }
			
		});

	});
    	
    	function loginUser(){

    		var un = $('#un').val();
    		var pw = $('#pw').val();

    		if(un == ''){
    			alert('Username is required. !')

    			return false;
    		}

    		if(pw == ''){
    			alert('Password is required. !')

    			return false;
    		}

    		var data = {
    			username : un,
    			password : pw
    		};

    		post('/login', data, function(response){
    			alert(response);
    		});

        }	


		var api_url = 'http://192.168.1.55:8002/api';
		function post(url, request, callback){
			$.ajax({
				url : api_url + url,
				type: "POST",
				data : request,
				headers:{

				},
				success: function(msg){
					var data = jQuery.parseJSON(JSON.stringify(msg));

				 	var token = window.localStorage.setItem('access_token', data.token); //getting auth token from api

				 	var getToken = window.localStorage.getItem('access_token'); // getting auth token in local storage 

				 	window.localStorage.setItem('name', data.user.name);
				 	window.localStorage.setItem('usergroup', data.user.usergroup);

				 		if(getToken != ''){
				 			callback('Welcome user');
				 			$('#loader').show();
				 			setTimeout(
				 						function() { 
											$("#loader").hide(); 
											$('#un').val('');
				 							$('#pw').val('');
				 							window.location ='{{ action("PagesController@home") }}';
				 						}, 1000
											
				 					);
				 	}
					
				},
				error: function(data){
					callback('Error sending request to the serve');
				}
			});
		}

    </script>
  </head>

  <body style="background-color: #f2f2f2;">
  	<center><br><br><br><br><br><br><br><br>
		   <table class="table table-bordered" style="width: 500px; background-color: #ffffff;" >
		   		<tr>
		   			<td colspan="2"><center><h3 style="text-shadow: 2px 2px #E5E2E2;">Inventory Form Monitoring</h3></center></td>
		   		</tr>
		   		<tr>
		   			<td style="width: 110px;">Username :</td>
		   			<td>
		   				<div class="input-group">
							<div class="input-group-prepend">
						    <span class="input-group-text"><i class="far fa-user"></i></span>
						  	</div>
						  <input type="text" class="form-control" id="un" placeholder="Enter User name" style="resize: none; "></input>
						  <input type="hidden" id="userid" />
						</div>
		   			</td>
		   		</tr>
		   		<tr>
		   			<td style="width: 110px;">Password :</td>
		   			<td>
		   				<div class="input-group">
							<div class="input-group-prepend">
						    <span class="input-group-text"><i class="fa fa-key"></i></span>
						  	</div>
						  <input type="password" class="form-control" id="pw" placeholder="Enter Password" style="resize: none;"></input>
						</div>
		   			</td>
		   		</tr>
		   		<tr>
		   			<td colspan="2" align="right"><img src="{{url('/images/loader.gif')}}" id="loader"></img>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="btnLogin" style="cursor: pointer;" onclick="loginUser()"><i class="fa fa-lock"></i>&nbsp;Login</button></td>
		   		</tr>
		   </table>
		   <span>Copyright © Exxel Prime Trading <?php echo date("Y"); ?></span>
	</center>
  </body>

</html>

