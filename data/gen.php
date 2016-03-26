<?php
// The URL to get your HTML
$url = "out.html";

// Name of your output image
$name = "example" . rand(100, 10000) . ".jpg";

// Command to execute
$command = "/usr/bin/wkhtmltoimage --width 400 --load-error-handling ignore";

// Directory for the image to be saved
$image_dir = "images/";

// Putting together the command for `shell_exec()`
$ex = "$command $url " . $image_dir . $name;

$documentTemplate = file_get_contents ("template.html");
$documentTemplate = str_replace ("[NAME]", $_GET['name'], $documentTemplate);
$documentTemplate = str_replace ("[TEXT]", $_GET['text'], $documentTemplate);

file_put_contents ("out.html", $documentTemplate);

// The full command is: "/usr/bin/wkhtmltoimage-i386 --load-error-handling ignore http://www.google.com/ /var/www/images/example.jpg"
// If we were to run this command via SSH, it would take a picture of google.com, and save it to /vaw/www/images/example.jpg

// Generate the image
// NOTE: Don't forget to `escapeshellarg()` any user input!
$output = shell_exec($ex);

$file = $image_dir.$name;
header("content-type: image/jpeg");
header('Content-Length: ' . filesize($file));

echo file_get_contents($file);