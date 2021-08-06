@php
/*
*    Template Name: Download CSV
*/
@endphp
@extends('layouts.app')
@php

function array_to_csv_download($array, $filename = 'export.csv', $delimiter = ';')
{
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://output', 'w');
    // loop over the input array
    foreach ($array as $line) {
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter);
    }
    // reset the file pointer to the start of the file
    ftell($f);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
    exit();
}

function postArray()
{
    $array = [['title', 'content']];
    $args = [
        'post_type' => 'post',
        'posts_per_page' => -1,
    ];
    $the_query = new WP_Query($args);
    foreach ($the_query->posts as $post) {
        $id = $post->ID;
        $title = get_the_title($id);
        $content = get_post_field('post_content', $id);
        $array2 = [$title, $content];
        $array[] = $array2;
    }
    return $array;
}

array_to_csv_download(
    postArray(), // this array is going to be the second row
    'export.csv', // name of file
);
@endphp
<script>
    window.close();
</script>
