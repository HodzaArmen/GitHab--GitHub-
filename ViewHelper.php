<?php

class ViewHelper
{
    public static function render($file, $variables = array())
    {
        extract($variables);

        // Start output buffering
        ob_start();

        // Include header
        include("view/partials/header.php");

        // Include the main view file
        include($file);

        // Include footer
        include("view/partials/footer.php");

        // Get the buffered content and clean the buffer
        $renderedView = ob_get_clean();

        // Output the rendered view
        echo $renderedView;
    }

    public static function redirect($url)
    {
        header("Location: " . $url);
    }

    public static function error404()
    {
        header('HTTP/1.1 404 Not Found');
        $html404 = sprintf("<!doctype html>\n" .
            "<title>Error 404: Page does not exist</title>\n" .
            "<h1>Error 404: Page does not exist</h1>\n" .
            "<p>The page <i>%s</i> does not exist.</p>", $_SERVER["REQUEST_URI"]);
        echo $html404;
    }

    public static function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public static function timeElapsed($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        $result = [];
        foreach ($string as $k => $v) {
            if ($diff->$k) {
                $result[] = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            }
        }

        if (!$full) {
            $result = array_slice($result, 0, 1);
        }

        if ($result) {
            return implode(', ', $result) . ' ago';
        } else {
            return 'just now';
        }
    }
}
