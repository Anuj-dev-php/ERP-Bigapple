<?php

include("db.php");

$query = "select manoj,ankit,naresh from  saleschart";
$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) >= 1) 
    while ($row = mysqli_fetch_assoc($result)) {

        $sales1 = $row['Manoj'];
        $sales2= $row['ankit'];
        $sales3 = $row['naresh'];
      
}
    else
    {
    echo "something went wrong";
    }
?>


<html>

<head>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css" rel="stylesheet">

</head>
<body>



<canvas id="mysalesChart" style="height: auto; width: 500px;"></canvas>

<?php

echo "<input type='hidden' id= 'manoj' value = '$manoj' >";
echo "<input type='hidden' id= 'ankit' value = '$ankit' >";
echo "<input type='hidden' id= 'naresh' value = '$naresh' >";


?>



<script>
    var manoj = document.getElementById("manoj").value;
    var ankit = document.getElementById("ankit").value;
    var naresh = document.getElementById("naresh").value;


    window.onload = function()
    {
        var randomScalingFactor = function() {
            return Math.round(Math.random() * 100);
        };
        var config = {
            type: 'bar',
            data: {
                borderColor : "#fffff",
                datasets: [{
                    data: [
                        manoj,
                        ankit,
                        naresh,
                        
                    ],
                    borderColor : "#fff",
                    borderWidth : "3",
                    hoverBorderColor : "#000",

                    label: 'Monthly Sales Report',

                    backgroundColor: [
                        "#0190ff",
                        "#56d798",
                        "#ff8397",
                        "#6970d5",
                        "#f312cb",
                        "#ff0060",
                        "#ffe400"

                    ],
                    hoverBackgroundColor: [
                        "#f38b4a",
                        "#56d798",
                        "#ff8397",
                        "#6970d5",
                        "#ffe400"
                    ]
                }],

                labels: [
                    'manoj',
                    'ankit',
                    'naresh']
                    
            },

            options: {
                responsive: true

            }
        };
        var sales = document.getElementById('mysalesChart').getContext('2d');
        window.myPie = new Chart(sales, config);


    };
</script>

</body>

</html>