<?php
// Seiteki Saito Jenereetaa

// 反映元
$src = 'index.html';

// 反映先
$dists = ['company.html', 'contact.html'];

// セクション
$sections = ['header', 'sidebar', 'footer'];

$parts = [];
$html = file_get_contents($src);
foreach ($sections as $section) {
    preg_match('|<!-- {' . $section . '} -->(.*?)<!-- /{' . $section . '} -->|s', $html, $ms);
    $parts[$section] = $ms[1];
}
preg_match_all('|<!-- {s(\d+?)} -->(.*?)<!-- /{s\d+?} -->|s', $html, $mss, PREG_SET_ORDER);
$slots = [];
foreach ($mss as $ms) $slots[$ms[1]] = $ms[2];
foreach ($dists as $dist) {
    $html = file_get_contents($dist);
    foreach ($parts as $section => $part) {
        $html = preg_replace('|(<!-- {' . $section . '} -->)(.*?)(<!-- /{' . $section . '} -->)|s', "$1$part$3", $html);
    }
    file_put_contents($dist, $html);
    echo "$dist OK<br>";
}
