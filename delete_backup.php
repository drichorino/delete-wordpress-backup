<?php

// Define the path to the backups directory
$backups_dir = "../wp-content/updraft/";

// Get all files in the backups directory
$files = scandir($backups_dir);

$dates = [];

// Loop through each file and check if it's a backup
foreach ($files as $file) {	
    if (preg_match("/^.*\.zip$/", $file)) {
		// Use a regular expression to extract the date from the string
		preg_match("/^backup_(\d{4}-\d{2}-\d{2})/", $file, $matches);

		// Push to array of dates
		array_push($dates,$matches[1]);
    }		
}

//Array of unique dates
$unique_dates = array_unique($dates);

//Sort array in descending order
rsort($unique_dates);

$files = scandir($backups_dir);

//Make sure to not delete the latest 2 dates of the backup
$dates_to_be_deleted = array_slice($unique_dates, 2);

// Loop through each file
foreach ($files as $file) {
    // Check if the file name contains one of the strings in the array
    foreach ($dates_to_be_deleted as $date_to_be_deleted) {
        if (strpos($file, $date_to_be_deleted) !== false) {
            // If it does, delete the file
			print "Deleted file: " . $file . PHP_EOL;
            unlink($backups_dir . $file);					
        }
    }
}

?>