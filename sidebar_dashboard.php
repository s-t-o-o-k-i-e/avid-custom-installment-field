<?php
function installment_month_anabling_sidebar() {
    add_menu_page(
        'Installment Months Activation',
        'Installment Months Activation',
        'manage_options',
        'installment-months-activation',
        'enable_installment_months',
        'dashicons-calendar-alt',
        10
    );
}
add_action('admin_menu', 'installment_month_anabling_sidebar');

function enable_installment_months() {
    echo '<h1>Installment Months Activation</h1>';
    echo '<h2>Add New Month Offer</h2>';
    echo '<form method="post">
            <input type="number" min="2" name="new_month" placeholder="Month to add">
            <input type="submit" name="add_month" value="Add Month">
          </form>';
    $months_offered = get_option('months_offered', array()); // Retrieve the saved array
    $enable_months_offered = get_option('enable_months_offered', array()); // Retrieve the saved array

    if (isset($_POST['update_months'])) {
        $enable_months_offered = array(); // Initialize the array

        foreach ($months_offered as $month) {
            if (isset($_POST['enable_month_' . $month])) {
                $enable_months_offered[] = $month; // Add selected months to the array
            }
        }

        update_option('enable_months_offered', $enable_months_offered); // Update the saved array
    }

    if (isset($_POST['add_month'])) {
        $new_month = (int)$_POST['new_month'];

        if ($new_month > 0 && !in_array($new_month, $months_offered)) {
            $months_offered[] = $new_month;

            // Sort the array
            rsort($months_offered);

            update_option('months_offered', $months_offered); // Update the saved array
        } elseif (in_array($new_month, $months_offered)) {
            echo '<div class="error">Month ' . $new_month . ' already exists.</div>';
        }
    }

    if (isset($_POST['delete_month'])) {
        $month_to_delete = (int)$_POST['delete_month'];
        $index = array_search($month_to_delete, $months_offered);
        $index_enabled = array_search($month_to_delete, $enable_months_offered);

        if ($index !== false) {
            unset($months_offered[$index]); // Remove the month
            update_option('months_offered', $months_offered); // Update the saved array
        }
        //removves an enabled month from its array if month offer is deleted on its array
        if ($index_enabled !== false) {
            unset($enable_months_offered[$index_enabled]); // Remove the month
            update_option('months_offered', $enable_months_offered); // Update the saved array
        }
    }

    echo '<form method="post">'; // Start the form for updating months
    echo '<table>';
    foreach ($months_offered as $month) {
        $checked = in_array($month, $enable_months_offered) ? 'checked' : '';
        echo '<tr><td><input type="checkbox" name="enable_month_' . $month . '" ' . $checked . '> ' . $month . ' months </td><td><button name="delete_month" value="' . $month . '">Delete</button></br><td></tr>';
    }
    echo '</table>';
    echo '<input type="submit" name="update_months" value="Update">'; // Submit button for updating months
    echo '</form>'; // End the form for updating months

    echo '<br><strong>Database validation</strong></br>';
    echo '<span>Months offered: ' . serialize($months_offered) . '</span>';

    $ctr = 0;
    foreach ($months_offered as $index) {
        echo '</br>Index ' . $ctr++ . ' : ' . $index;
    }

    echo '<br><br>';
    echo '<span>Months enabled: ' . serialize($enable_months_offered) . '</span>';

    $ctr = 0;
    foreach ($enable_months_offered as $index) {
        echo '</br>Index ' . $ctr++ . ' : ' . $index;
    }
}

//todo '$months' submit button into Delete' submit button
//todo if an index from $months_offered is deleted and is included in the $enable_months_offered, remove it also from $enable_months_offered

//! Error case:
/*
18 unchecked, 12 checked
12 delete button
12 and 18 both gone
*/