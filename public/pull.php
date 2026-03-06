<?php

$secret = "pullM3Now"; // bebas, nanti dipakai di webhook

if ($_GET['secret'] !== $secret) {
    die("Invalid secret");
}

$output = shell_exec("cd /home/alstudio/quiz.alstudio.my.id && git pull origin main 2>&1");

echo "<pre>$output</pre>";
