<?PHP

use yii2assets\pdfjs\PdfJs;

echo PdfJs::widget([
    'url' => ['viewdoc', 'id' => $id],
    'height' => '720px',
    'width' => '100%',
    //'print_resolution' => 300,
    "buttons" => [
        //"presentationMode" => false,
        //'openFile' => true,
        'print' => true,
        'download' => true,
    //'viewBookmark' => false,
    //'secondaryToolbarToggle' => false
    ]
]);

