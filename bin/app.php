<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/bootstrap.php';

echo "PHP REPL - Press Enter for new line, type 'exec' to execute, 'clear' to clear buffer, 'exit' or 'quit' to stop\n";

$buffer = '';

while (true) {
    $input = readline("php> ");

    // Strip the prompt if it was pasted in
    $input = preg_replace('/^php>\s*/', '', $input);

    // Check for exit commands
    if (in_array(trim($input), ['exit', 'quit'])) {
        break;
    }

    // Check for clear command
    if (trim($input) === 'clear') {
        $buffer = '';
        echo "Buffer cleared.\n";
        continue;
    }

    // Check for execute command
    if (trim($input) === 'exec') {
        if (!empty($buffer)) {
            try {
                // Add semicolon if not present
                $code = $buffer;
                if (!str_ends_with(trim($code), ';') && !str_ends_with(trim($code), '}')) {
                    $code .= ';';
                }

                $result = eval($code);

                // Display result if it's not null
                if ($result !== null) {
                    echo "=> ";
                    var_dump($result);
                }
            } catch (Throwable $e) {
                echo "⚠️  Error: " . $e->getMessage() . PHP_EOL;
            }
        }

        // Reset buffer after exec (whether successful or not)
        $buffer = '';
        continue;
    }

    // Skip empty lines
    if (trim($input) === '') {
        continue;
    }

    // Add input to buffer
    $buffer .= ($buffer ? "\n" : '') . $input;

    // Try to execute if it looks like a complete statement
    $trimmed = trim($buffer);
    if (!empty($trimmed) && (str_ends_with($trimmed, ';') || str_ends_with($trimmed, '}'))) {
        // Check if braces are balanced
        $openBraces = substr_count($buffer, '{');
        $closeBraces = substr_count($buffer, '}');
        $openParens = substr_count($buffer, '(');
        $closeParens = substr_count($buffer, ')');

        if ($openBraces === $closeBraces && $openParens === $closeParens) {
            try {
                $result = eval($buffer);

                // Display result if it's not null
                if ($result !== null) {
                    echo "=> ";
                    var_dump($result);
                }

                // Reset buffer after successful execution
                $buffer = '';
            } catch (Throwable $e) {
                echo "⚠️  Error: " . $e->getMessage() . PHP_EOL;
                // Clear buffer on error to prevent accumulation of bad code
                $buffer = '';
            }
        }
    }

    // Add to readline history (without the prompt)
    if (!empty(trim($input))) {
        readline_add_history($input);
    }
}

echo "Goodbye!\n";