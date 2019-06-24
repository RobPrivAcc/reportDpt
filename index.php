<?php
	session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <title>In shops sales report</title>
        <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	
	<!-- My CSS files   -->
	<link rel="stylesheet" href="css/myCSS.css">
	<link rel="stylesheet" href="css/switchSlider.css">
	</head>
  <body>
    <?php
        include("class/classProduct.php");
        include("class/classDb.php");
        include("class/classXML.php");
    ?>
    <div class="container">
      <div class="row">
        <div class='col-xs-12 col-12 text-center'>
          <h2> Sale by Department Raport</h2>
					<h6><?php include("version.php");?></h6>
        </div>
      </div>
 
			<div class='row'>
				<div class='col-xs-2 col-2'>
<!--					<form id='dateChangeRadio'>
						<div class="radio radio-inline">
					        <input type="radio" id="inlineRadio1" value="fullRange" name="dateRadio" checked>
					        <label for="inlineRadio1"> Full year </label>

					        <input type="radio" id="inlineRadio2" value="pickedRange" name="dateRadio">
					        <label for="inlineRadio2"> Date range </label>
					    </div>
					</form>-->

					<!-- Rounded switch -->
					<div id='divDateRangeId'>
						Full year
					</div>
					<label class="switch">
						<input type="checkbox" id='chbDateRangeId' checked=checked>
						<span class="slider round"></span>
					</label> 
				</div>
				
				<div class='col-xs-8 col-8'>
					<div id='dateDiv'>
						<div class='row'>
							<div class='col-xs-4 col-4 dateInputs'>
								Date from: <input type="text" class="form-control form-control-sm" name="dateFrom" id="dateFrom" value="" />
							</div>
							
							<div class='col-xs-4 col-4 dateInputs'>
								Date to: <input type="text" class="form-control form-control-sm" name="dateTo" id="dateTo" value="" />
							</div>
							
							<div class='col-xs-4 col-4'></div>
						</div>
					</div>
				</div>
				
			</div>
			
		<?php
			$xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
			$db = new dbConnection($xml->getConnectionArray());
			
			$shopNameArray = $db->getShopsName();
			
			$shops = "<select id='shopsName' class='selectpicker form-control'>";
			$shops.= "<option>Choose Shop</option>";
			
			foreach($shopNameArray as $key => $value){
				$shops.= "<option>".$value['shopName']."</option>";
			}
			
			$shops.= "</select>";
			
									
			$typeName = new product();
			$typeName->openConnection($db->getDbConnection(2));
			$typeNameArray = $typeName->getTypeSubtypeArray();

				$types = "<select id='typeName' class='selectpicker form-control'>";
					$types .= "<option>Choose Type</option>";
					
					foreach($typeNameArray as $key=>$value){
						$types .= "<option>".$key."</option>";
					}
					
			$types .= "</select>";
			
		?>
      
      <div class='row'>
        <div class='col-xs-10 col-10'>
				<?php
					echo $shops;
				?>
				</div>
					
        <div class='col-xs-1 col-1'>
            <button class = "btn btn-secondary" id = "searchBtn"><i class="fa fa-toggle-right fa-lg" aria-hidden="true"></i></button>
        </div>
        <div id = 'exportDivButton' class='col-xs-1 col-1'>
            
        </div>   
      </div>
      

			
      <div class="row">
        <div class='col-xs-12 col-12'>
          <div id="result" style="width: 100%;"></div>
        </div>
      </div>
      
			<div class="row">
        <div class='col-xs-12 col-12'>
          <div id="details" style="width: 100%;"></div>
        </div>
      </div>

    </div>
	    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
		
				<!-- Include Date Range Picker -->
		<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>		
				
		<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />


		  <script>
					$(function() {
						$('input[name="dateFrom"]').daterangepicker({
								singleDatePicker: true,
								showDropdowns: true,
								 locale: {
										format: 'YYYY-MM-DD'
								 }
					 });
								$('input[name="dateTo"]').daterangepicker({
								singleDatePicker: true,
								showDropdowns: true,
								 locale: {
										format: 'YYYY-MM-DD'
								 }
					 });
				});
			</script>
  
  <script>
	
		$('#chbDateRangeId').click(function() {
			if($('#chbDateRangeId').is(':checked')){
				
				$('#divDateRangeId').html("Full year");
				$('#dateDiv').hide();
			}else{
				$('#divDateRangeId').html("Date range");
				$('#dateDiv').show();
			}
		});
	
	
//    $( document ).ready(function() {
//        console.log( "ready!" );
//				$('#details').html();
//        $('#search').tooltip({title: "Generate stats.", trigger: "hover"});
//        $('#exportToExcel').tooltip({title: "Create <b>Excel</b> file.",  html: true, trigger: "hover"}); 
//        $("#exportToExcel").hide();
//        $('[data-toggle="tooltip"]').tooltip();
//        
//          $.get( "https://www.robertkocjan.com/petRepublic/ip/ipGetArray.php", function(i) {
//                    //console.log(i);
//                    var configArray = i;
//          $.get( "getIpFromServer.php", { ipArray: configArray }, function(data) {
//              //console.log(data);
//              });
//        });
//    });
		
		$("#searchBtn").click(function(){
				$('#details').html("");
				var shopsName = $("#shopsName option:selected").text();
				$('#exportDivButton').html("");
				
				if (shopsName != 'Choose Shop'){
					var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
					$('#result').html(spinner);
					
					var dateFrom = '';
					var dateTo = '';
					if($('#chbDateRangeId').is(':checked') === false){
						dateFrom = $('#dateFrom').val();
						dateTo = $('#dateTo').val();
					}

					$.post( "sql/sqlType.php", { shopName: shopsName, dateFrom: dateFrom, dateTo: dateTo })
						.done(function( data ) {
								$('#result').html(data);
								$('#details').html("");
								$('#exportDivButton').html("");
						}); 
				}
		});

		$(document).ready(function(){
			$(".dateInputs").hide();
			      //var val = $('input[name="dateRadio"]:checked').val();
			$('#chbDateRangeId').click(function() {
				if($('#chbDateRangeId').is(':checked')){
					$(".dateInputs").hide();
				}else{
					$(".dateInputs").show();
				}
      });
		});
		
		$(document).on("mouseover", ".row.selectedCatRow",function() {
				$(this).addClass(' selectedCat');
				$(this).css({"background-color": "#9bb8e8","cursor": "pointer"});
			}).on("mouseout", "div.selectedCatRow",function() {
				$(this).css({"background-color": "","cursor": "default"});
				$(this).removeClass("pointer");
				$(this).removeClass(' selectedCat');
		});
		
		
		$(document).on("click", ".selectedCat", function(){
			var shopName = $("#shop").val();
			var type = $(this).children('div.catName').text();
			var typeArray = $("#typesArray").val();
			
			console.log(shopName+" > "+type+" > "+typeArray);
			
			var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';					
			$('#result').html(spinner);
			$('#details').html("");
			
			$.post( "sql/sqlSubType.php", { shopName: shopName, type: type, typesArray:typeArray })
			.done(function( data ) {
				$('#result').html(data);
				$('#details').html("");
			});
		});
		
		$(document).on("mouseover", ".row.selectedSubCatRow",function() {
				$(this).addClass(' selectedSubCat');
				$(this).css({"background-color": "#9bb8e8","cursor": "pointer"});
		
			}).on("mouseout", "div.selectedSubCatRow",function() {
				$(this).css({"background-color": "","cursor": "default"});
				$(this).removeClass("pointer");
				$(this).removeClass(' selectedSubCat');
		});
		
		$(document).on("click", ".selectedSubCat", function(){
			
			var subType = $(this).children('div.subCatName').text();
			var shopName = $("#shop").val();
			var type = $("#type").val();
			var typeArray = $("#typesArray").val();
			var subTypeArray = $("#subTypeArray").val();
			
			var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';					
			//$('#result').html(spinner);
			$('#details').html(spinner);
			
			$.post( "sql/sqlProductDetails.php", { shopName: shopName, type: type, subType: subType, typesArray:typeArray, subTypeArray:subTypeArray })
			.done(function( data ) {
				$('#details').html(data);
				$('#exportDivButton').html("<button class = 'btn btn-success' id = 'exportToExcel'><i class='fa fa-file-excel-o fa-lg' aria-hidden='true'></i></button>");
			});	
		});
		
		
		
		$(document).on("click",'#exportDivButton',function(){
			var type = $("#type").val();
			var typeArray = $("#typesArray").val();
			var subTypeArray = $("#subTypeArray").val();
			var productsArray = $("#productsArray").val();
			var shop = $("#shop").val();
			var header = $("#header").val();
		
			$.post( "pages/exportToExcel.php", {typesArray:typeArray, subTypeArray:subTypeArray, productsArray:productsArray,shop:shop,header:header, type: type })
				.done(function( data ) {
					$('#result').html(data);
					$('#details').html("");
					$('#exportDivButton').html("");
				});	
		});
  </script>



  </body>
</html>