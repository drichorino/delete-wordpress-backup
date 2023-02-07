<?php

//Set number of latest dates to be retained
$number_of_latest_dates = 2;


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
$dates_to_be_deleted = array_slice($unique_dates, $number_of_latest_dates);
$deleted_files = [];

// Loop through each file
foreach ($files as $file) {
    // Check if the file name contains one of the strings in the array
    foreach ($dates_to_be_deleted as $date_to_be_deleted) {
        if (strpos($file, $date_to_be_deleted) !== false) {
            // If it does, delete the file
			print "Deleted file: " . $file . PHP_EOL;
			array_push($deleted_files, $file);
            unlink($backups_dir . $file);					
        }
    }
}

//Create a log file
echo "Creating log file..." . PHP_EOL;

// Set the default timezone to your desired timezone
date_default_timezone_set("Asia/Singapore");
$current_datetime = date("Y-m-d_H-i-s");
$handle  = fopen($current_datetime."_log.txt", "w") or die("Unable to open file!");
fwrite($handle , "Files deleted on " . $current_datetime . ".\n\n");

foreach ($deleted_files as $deleted_file) {
    fwrite($handle, $deleted_file . PHP_EOL);
}

fclose($handle );
echo "... SUCCESS!" . PHP_EOL;

?>