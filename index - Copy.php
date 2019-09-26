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

                    <!-- Rounded switch -->
                    <div id='divAllShopsStatsId'>
                        All shop stats OFF
                    </div>
                    <label class="switch">
                        <input type="checkbox" id='chbAllShopsStatsId'>
                        <span class="slider round"></span>
                    </label>
                </div>

				<div class='col-xs-2 col-2'>

					<!-- Rounded switch -->
					<div id='divDateRangeId'>
						Full year
					</div>
					<label class="switch">
						<input type="checkbox" id='chbDateRangeId' checked=checked>
						<span class="slider round"></span>
					</label> 
				</div>
				
				<div class='col-xs-6 col-6'>
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
			
			$shopNameArray = $db->getShopsArray();
			
			$shops = "<select id='shopsName' class='selectpicker form-control'>";
                $shops.= "<option>Choose Shop</option>";

                foreach($shopNameArray as $key => $value){
                    $shops.= "<option>".$value['shopName']."</option>";
                }
			
			$shops.= "</select>";
			
									
			$typeName = new product($db->connect(2));
//			$typeName->openConnection($db->getDbConnection(2));
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
            <div id="allShops" style="width: 100%;"></div>
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
        var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
        var summaryHeader = "<div class='row'><div class='col-xs-12 col-12 text-center'> <h2>Stats for All stores</h2></div></div>";

        var dateFrom = '';
        var dateTo = '';
        var allShopsStats = '';

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

		$('#chbDateRangeId').click(function() {
			if($('#chbDateRangeId').is(':checked')){
				$('#divDateRangeId').html("Full year");
                $('#dateDiv').hide();
                dateFrom = '';
                dateTo = '';
			}else{
				$('#divDateRangeId').html("Date range");
				$('#dateDiv').show();

			}
		});

        $('#chbAllShopsStatsId').click(function() {

            if($('#chbAllShopsStatsId').is(':checked')){

                $('#divAllShopsStatsId').html("All shop stats ON");

                if(allShopsStats === ''){
                    getSummary();
                }else{
                    let btn = "<div><div id='btnReset' class = 'btn btn-primary'><i class=\"fas fa-sync\"></i> refresh</div></div>";
                    $('#allShops').html(summaryHeader + btn + allShopsStats);
                }
            }else{
                $('#divAllShopsStatsId').html("All shop stats OFF");
                $('#allShops').html("");
            }
        });

        $(document).on("click", "#btnReset",function() {
            console.log("reset clicked")
            allShopsStats = '';
            $('#allShops').html(spinner);
            getSummary();
        });

        function getSummary(){
            $('#allShops').html(spinner);
            $.post( "sql/summary.php", { dateFrom: dateFrom, dateTo: dateTo })
                .done(function( data ) {
                    allShopsStats = data;
                    $('#allShops').html(summaryHeader + allShopsStats);
                });
        }

		$("#searchBtn").click(function(){
				$('#details').html("");
				var shopsName = $("#shopsName option:selected").text();
				$('#exportDivButton').html("");
				
				if (shopsName != 'Choose Shop'){

					$('#result').html(spinner);
					
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

			$('#chbDateRangeId').click(function() {
				if($('#chbDateRangeId').is(':checked')){
					$(".dateInputs").hide();
                    getDates(false);
				}else{
					$(".dateInputs").show();

                    $('#dateFrom').change(function () {
                        getDates(true);
                    });
                    $('#dateTo').change(function () {
                        getDates(true);
                    });
				}
            });
		});

		function getDates(selected){

		    if(selected === true){
                dateFrom = $('#dateFrom').val();
                dateTo = $('#dateTo').val();
            }else{
                dateFrom = '';
                dateTo = '';
            }
            console.log('>>> dateFrom: '+dateFrom+'dateTo: '+dateTo);
        }

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

			$('#result').html(spinner);
			$('#details').html("");
			
			$.post( "sql/sqlSubType.php", { shopName: shopName, type: type, typesArray:typeArray, dateFrom: dateFrom, dateTo: dateTo })
			.done(function( data ) {
				$('#result').html(data);
				$('#details').html("");
			});
		});
		
		$(document).on("mouseover", ".row.selectedSubCatRow",function() {
                        $(this).addClass(' selectedSubCat');
                        $(this).css({"background-color": "#9bb8e8","cursor": "pointer"});
                    })
                   .on("mouseout", "div.selectedSubCatRow",function() {
                        $(this).css({"background-color": "","cursor": "default"});
                        $(this).removeClass("pointer");
                        $(this).removeClass(' selectedSubCat');
                   });
		
		$(document).on("click", ".selectedSubCat", function(){
			
			let subType = $(this).children('div.subCatName').text();
			let shopName = $("#shop").val();
			let type = $("#type").val();
			let typeArray = $("#typesArray").val();
			let subTypeArray = $("#subTypeArray").val();
			
			$('#details').html(spinner);
			
			$.post( "sql/sqlProductDetails.php", { shopName: shopName, type: type, subType: subType, typesArray:typeArray, subTypeArray:subTypeArray })
			.done(function( data ) {
				$('#details').html(data);
				$('#exportDivButton').html("<button class = 'btn btn-success' id = 'exportToExcel'><i class='fa fa-file-excel-o fa-lg' aria-hidden='true'></i></button>");
			});	
		});
		
		
		
		$(document).on("click",'#exportDivButton',function(){
			let type = $("#type").val();
			let typeArray = $("#typesArray").val();
			let subTypeArray = $("#subTypeArray").val();
			let productsArray = $("#productsArray").val();
			let shop = $("#shop").val();
			let header = $("#header").val();
		
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