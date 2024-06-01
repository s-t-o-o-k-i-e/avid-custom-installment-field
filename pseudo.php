<?php 
$instalment_price = 700;
$start_date = DateTime::createFromFormat('Y-d-m', '2024-24-01')->format('Y-d-m');
$end_date = DateTime::createFromFormat('Y-d-m', '2024-31-01')->format('Y-d-m');
$current_date = date('Y-d-m');

echo '<h1>' . $current_date . '</h1>';

//* CONDITION 1
if ($start_date == null && $end_date == null){
    //set instalment_price instantly, unset manually
}

if ($start_date != null && $end_date != null){
    //* CONDITION 5, 6, AND 7
    if ($start_date > $end_date || $current_date > $start_date  || $current_date > $end_date){
        echo '<strong>invalid</strong>, ensure the conditions below are satisfied;<br>
                end date should be higher than start date,<br>
                start date should be higher than current date,<br>
                end date should be higher than current date
        ';
    }

    //* CONDITION 2
    else if ($current_date < $start_date && $start_date < $end_date){
        //set instalment price on start_date, unset after end_date
        echo $current_date . ' < ' . $start_date . ' < ' . $end_date;
        //$from_current_to_end = datediff($end_date, $current_date);
    }
}

//* CONDITION 3
if ($current_date < $start_date && $start_date != null){
    //set instalment_price on start_date, unset manually
}

//* CONDITION 4
if ($current_date < $end_date && $end_date != null){
    //set instalment_price instantly, unsets after end_date
}