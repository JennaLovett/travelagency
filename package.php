<!--package.php allows the user to 
--  search through all available packages
--  via ajax-enabled filtering.
-->
<?php
    //echo include('http://localhost/Project/database_connection.php');
    //$connect = new PDO('mysql:host=localhost;dbname=BlackMesaTravel', 'iw3htp', 'password');
    //connecting to AWS instance of database
    $connect = new PDO('mysql:host=mysql-bmtravel.cj3sjwqrps9d.us-east-1.rds.amazonaws.com;port=3306;dbname=BlackMesaTravel', 'masterUsername', 'blackmesatravel');
    //echo $connect;
?>
<!DOCTYPE html>
<html>
   <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Packages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="http://localhost/Project/jquery-3.3.1.min.js"></script>
    <script src="http://localhost/Project/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="http://localhost/Project/jquery-ui.css">
    <style>
      body {
         font-family: Arial, Helvetica, sans-serif;
         margin: 0;
         padding: 0;
      }

      /***********
      Nav Bar section
      ***********/
      .navbar {
         list-style-type: none;
         margin: 0;
         padding: 0;
         position: fixed;
         top: 0;
         width: 100%;
         overflow: auto;
         z-index: 1000;
         background-color: #1CCAD8;
         -webkit-box-shadow: 0px 3px 7px 0px rgba(0,0,0,0.55);
         -moz-box-shadow: 0px 3px 7px 0px rgba(0,0,0,0.55);
         box-shadow: 0px 3px 7px 0px rgba(0,0,0,0.55);
      }

      #navbranding {
         float: left;
         font-size: 21px;
      }

      .navitem {
         float: right;
         font-size: 18px;
      }


      .navbar li a {
         text-decoration: none;
         display: block;
         color: white;
         text-align: center;
         padding: 15px 18px;
      }

      h1 {
         margin-top: 100px;
         text-align: center;
      }
      /**********
      Buttons
      ***********/
      button {
         background-color: white;
         border: none;
         color: black;
         border: 2px solid #1CCAD8;
         border-radius: 5px;
         padding: 12px 14px;
         text-align: center;
         text-decoration: none;
         font-size: 16px;
         -webkit-transition-duration: 0.4s;
         transition-duration: 0.4s;
         cursor: pointer;
         margin-left: 150px;
      }
      button:hover {
         background-color: #1CCAD8;
         color: white;
      }
      /**********
      Filter
      ***********/
      .filter {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #f4f8ff;
            overflow-x: hidden;
            padding-top: 20px;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 50px;
        }
      .section {
          margin:auto;
          width:50%;
          margin-bottom: 10px;
      }

      .searched_package {
          display: grid;
          grid-template-columns: auto;
          margin-left: 250px; /* Same as the width of the sidenav */
            font-size: 15px; /* Increased text to enable scrolling */
            padding: 0px 10px;
      }

      .filter_data {
          display: grid;
          grid-template-columns: auto auto;
          margin-left: 250px; /* Same as the width of the sidenav */
            font-size: 15px; /* Increased text to enable scrolling */
            padding: 0px 10px;
      }

     img {
         width: 400px;
         height: 200px;
     }

    </style>
   </head>
   <body>

      <!-- Navigation Bar -->
      <ul class="navbar">
         <li id="navbranding" class="navitem"><a href="index.php">Black Mesa Travel</a></li>
         <li class="navitem" id="loginLink"><a href="login.html">Login/Signup</a></li>
         <li class="navitem"><a href="package.php">Premium Packages</a></li>
         <li class="navitem"><a href="trending.php">Trending</a></li>
         <li class="navitem"><a href="index.php">Home</a></li>
      </ul>

      <!-- Packages header -->
    <h1 style="margin-left:250px">Packages</h1>
    

    <!-- Sidebar that will contain filtering options -->
    <div class="filter">
        <div class="section">
            <br><br>
            <input type="hidden" id="hidden_minimum_price" value="0"/>
            <input type="hidden" id="hidden_maximum_price" value="1200"/>
            <h3>Price Range</h3>
            <p id="price_show">200-1200</p>
            <div id="price_range"></div>
        </div>
        <!-- Filtering of biomes of all packages -->
        <div class="section">
            <h3>Biome</h3>
            <?php
            $query = "SELECT BiomeName FROM BIOMES ORDER BY BiomeName;"; //returns all biomes
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            foreach($result as $row) {
                ?>
                <label><input type="checkbox" class="common_selector biome"
                value="<?php echo $row['BiomeName']; ?>">
                <?php echo $row['BiomeName']; ?></label><br>
                <?php

            }
            ?>
        </div>
        <!-- Filtering of all countries of all packages -->
        <div class="section">
            <h3>Country</h3>
            <?php
            $query = "select distinct(Country) from PREMIER_LOCATIONS order by LocationName;"; //returns all distinct countries
            $statement = $connect->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            foreach($result as $row) {
                ?>
                <label><input type="checkbox" class="common_selector country"
                value="<?php echo $row['Country']; ?>">
                <?php echo $row['Country']; ?></label><br>
                <?php

            }
            ?>
        </div>
        <!-- Filtering of all dates of all packages -->
        <div class="section">
            <h3>Date</h3>
            Arrival Date: <br><input type="date" name="arrival" id="arrival" class="date_selector"><br>
            Departure Date: <br><input type="date" name="departure" id="departure" class="date_selector">
            <!--Placeholder h3 tags that allow spacing at bottom of filtering side-bar -->
            <h3></h3>
            <h3></h3>
            <h3></h3>
            <h3></h3>
            <h3></h3>
        </div>
    </div>
    <div class="searched_package">
    <?php
    if(isset($_GET["locationname"])) {
        $locationname = $_GET["locationname"];
        $query2 = "select i.url, l.locationname, l.country, b.biomename, p.priceperday, p.packageid, DATE_FORMAT(p.startdate, '%M %d, %Y') as startdate, DATE_FORMAT(p.enddate, '%M %d, %Y') as enddate from PREMIER_LOCATIONS l join BIOMES b on l.biomeid = b.biomeid join PACKAGES p on l.locationid = p.locationid join IMAGES i on l.locationid = i.locationid WHERE l.locationname LIKE '" . $locationname .  "';";
        //prepare the query for execution
        $statement = $connect->prepare($query2);
        //execute query
        $statement->execute();
        //store the result
        $result = $statement->fetchAll();
        //gather total rows returned from query
        $total_row = $statement->rowCount();
        //initialize output variable
        $output="";
        if($total_row > 0) {
            foreach($result as $row) {
                //create and output div for each package
                $output .= '
                    <div class="package">
                    <div style="border:1px solid green; border-radius:5px;
                        padding:16px; margin:auto; margin-bottom:16px; height:400px; width:400px;">
                    <img src="'.$row['url'].'">
                    <h4 align="center">'. $row['locationname'] .', ' . $row['country'] . '</h4>
                    <p>'. $row['biomename'] .'<br/>
                    Start Date: '. $row['startdate'] .'<br/>
                    End Date: '. $row['enddate'] .'</p>
                    <p>$'. $row['priceperday'] .' per day</p>            
                    <button type="button" class="addtocart" onClick="func(this)">Purchase</button>
                    <b id=' . $row['packageid'] . '></b>
                    </div>
                    </div>
                    
                ';
            }
        } else {
            $output = '<h3>No Data Found</h3>';
        }
        echo $output;
    }
    ?>
    </div>
    <!-- Div where all packages will appear asynchronously -->
    <div class="filter_data">
    </div>

    <script>
        function func(elem){
            var parent = elem.parentElement;
            var children = parent.children;
            var location;
            var price;
            for(let i = 0; i < children.length; i++) {
                if(i == 1) {
                    location = children[i].innerHTML;
                }
                if(i == 3) {
                    price = children[i].innerHTML;
                }
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            filter_data();
            //Ajax function to collect filtering data and send it to fetch_data.php
            function filter_data() {
                var action = 'fetch_data';
                var minimum_price = $('#hidden_minimum_price').val();
                var maximum_price = $('#hidden_maximum_price').val();
                var arrival_date =  $("input[name='arrival']").val();
                var departure_date = $("input[name='departure']").val();
                var biome = get_filter('biome');
                var country = get_filter('country');
                $.ajax({
                    url:"http://localhost/Project/fetch_data.php",
                    method:"POST",
                    data:{action:action, 
                    minimum_price:minimum_price, 
                    maximum_price:maximum_price,
                    arrival_date:arrival_date,
                    departure_date:departure_date,
                    biome:biome, country:country},
                    success:function(data) {
                        $('.filter_data').html(data);
                        foobar();
                    }
                });
            }
            //gets all filters from a specific checkbox section
            function get_filter(class_name) {
                var filter = [];
                $('.'+class_name+':checked').each(function() {
                    filter.push($(this).val());
                });
                return filter;
            }
            //when a filter option is clicked, update the data
            $('.common_selector').click(function(){
                filter_data();
            });
            //when a date is blured, update the data
            $('.date_selector').blur(function() {
                filter_data();
            });
            //setting the jquery slider for the price range
            $('#price_range').slider({
                range:true,
                min:200,
                max:1200,
                values:[200, 1200],
                step:100,
                stop:function(event, ui) {
                    $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);//display min and max price
                    $('#hidden_minimum_price').val(ui.values[0]);
                    $('#hidden_maximum_price').val(ui.values[1]);
                    filter_data();
                }
            });

            //Grabs whatever package you clicked, adds information as cookies
            var itemCount = 0;
            function foobar(){
                $('.addtocart').click(function() {
                    var p = this.parentNode;
                    var c = p.children;
                    itemCount++;
                    var PackageName = c[1].innerHTML;
                    var cost = c[3].innerHTML;
                    var id = c[5].id;
                    $.ajax({
                        url:"http://localhost/Project/additem.php",
                        method:"POST",
                        data:{
                            PackageName:PackageName,
                            cost:cost,
                            count:itemCount,
                            id:id
                        },
                        success:function(){
                            window.alert("Item added to cart!");
                            window.location.href = "http://localhost/Project/checkout.php";
                        }
                    });
                });
            }
            
        });
    </script>

    <script src="checkLogin.js"></script>
   </body>
   
</html>
