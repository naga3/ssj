<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Seiteki Saito Jenereta</title>
</head>
<body>
<?php
// 反映元
$src = 'index.html';

// 反映先
$dists = ['company.html', 'contact.html'];

// セクション
$sections = ['header', 'sidebar', 'footer'];

$parts = [];
$html = file_get_contents($src);
foreach ($sections as $section) {
    preg_match('|<!-- <' . $section . '> -->(.*?)<!-- /<' . $section . '> -->|s', $html, $ms);
    $parts[$section] = $ms[1];
}
preg_match_all('|<!-- <slot(\d+?)> -->.*?<!-- /<slot\d+?> -->|s', $html, $mss, PREG_SET_ORDER);
$slots = [];
foreach ($mss as $ms) $slots[$ms[1]] = null;
foreach ($dists as $dist) {
    echo "$dist:<br>";
    $html = file_get_contents($dist);
    foreach ($slots as $no => $slot) {
        if (preg_match('|<!-- <slot' . $no . '> -->(.*?)<!-- /<slot' . $no . '> -->|s', $html, $ms)) {
            $slots[$no] = $ms[1];
        } else {
            $slots[$no] = null;
        }
    }
    foreach ($parts as $section => $part) {
        $html = preg_replace('|(<!-- <' . $section . '> -->).*?(<!-- /<' . $section . '> -->)|s', '${1}' . $part . '${2}', $html);
        echo " section: $section OK<br>";
    }
    foreach ($slots as $no => $slot) {
        if ($slot) {
            $html = preg_replace('|(<!-- <slot' . $no . '> -->).*?(<!-- /<slot' . $no . '> -->)|s', '${1}' . $slot . '${2}', $html);
            echo " slot$no: $slot OK<br>";
        }
    }
    file_put_contents($dist, $html);
    echo '<br>';
}
?>
</body>
</html>
