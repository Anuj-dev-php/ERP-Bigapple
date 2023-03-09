<?php
use \koolreport\widgets\koolphp\Table;
?>
<html>
    <head>
    <title>My Report</title>
    </head>
    <body>
        <h1>It works</h1>
        <?php
  

        Table::create(array(
            "dataSource"=>$this->dataStore('data') ,
            "showFooter"=>true,
            "headers"=>array(
                array(
                    "Basic Information"=>array("colSpan"=>1),
                    "Other Information"=>array("colSpan"=>2),
                )
            ), 
        "cssClass"=>array(
            "table"=>"table-bordered table-striped table-hover"
        ),
            "columns"=>array(
                "name",
                "age"=>array(
                    "cssStyle"=>"font-weight:bold"
                ),
                "income"=>array(
                    "cssStyle"=>"text-align:right",
                    "prefix"=>"$",
                    "footer"=>"sum",
                    "footerText"=>"<b>Total:</b> @value"
                    
                )
            )
,

"paging"=>array(
    "pageSize"=>1,
    "pageIndex"=>0,
),

        ));
        ?>
    </body>
</html> 