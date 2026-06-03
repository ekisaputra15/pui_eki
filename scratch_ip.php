<?php
$ip = gethostbyname(gethostname());
echo "Hostname IP: " . $ip . "\n";
echo "Server Addr: " . ($_SERVER['SERVER_ADDR'] ?? 'N/A') . "\n";
echo "HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
