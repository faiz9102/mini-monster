<?php
namespace framework\View\Layout;

class View
{
    public function render($template, $data = [])
    {
        extract($data);
        require $template;
    }

    public function test ()
    {
        return "<h1>This is a test method in the View class. Modified By Faiz</h1>
                <p>Welcome to the view testing page.</p>
                <p>Current date and time: " . date('Y-m-d H:i:s') . "</p>";
    }
}