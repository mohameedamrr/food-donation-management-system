<?php
// controllers/BoxController.php

spl_autoload_register(function ($class_name) {
    $directories = [
        '../Model/',
        '../Controller/',
        '../View/',
        '../interfaces/',
    ];
    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

class BoxController {
    private $boxView;

    public function __construct() {
        $this->boxView = new BoxView();
    }

    public function createBox($boxData) {
        $basicBox = new BasicBox($boxData['boxSize'], $boxData['weightLimit']);

        foreach ($boxData['items'] as $item) {
            $basicBox->addItem($item);
        }

        // Add decorators if any
        $donateBox = $basicBox;

        if (isset($boxData['additionalRice'])) {
            $donateBox = new BoxAdditionalRice($donateBox, $boxData['additionalRice']);
        }
        if (isset($boxData['additionalPasta'])) {
            $donateBox = new BoxAdditionalPasta($donateBox, $boxData['additionalPasta']);
        }
        if (isset($boxData['additionalOil'])) {
            $donateBox = new BoxAdditionalOil($donateBox, $boxData['additionalOil']);
        }

        $this->boxView->displayBoxCreated($donateBox);
    }
}
?>
