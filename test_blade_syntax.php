<?php
$code = file_get_contents('d:/sathwara_infotech/community_project/test_compiled_event.php');
$tokens = token_get_all($code);

$controlStack = [];

for ($i = 0; $i < count($tokens); $i++) {
    $t = $tokens[$i];
    if (is_array($t)) {
        $name = token_name($t[0]);
        $line = $t[2];
        if ($name === 'T_IF') {
            // Find colon
            $hasColon = false;
            for ($j = $i + 1; $j < count($tokens); $j++) {
                if (is_string($tokens[$j]) && $tokens[$j] === ':') { $hasColon = true; break; }
                if (is_string($tokens[$j]) && $tokens[$j] === '{') { break; }
                if (is_array($tokens[$j]) && $tokens[$j][0] === T_CLOSE_TAG) break;
            }
            array_push($controlStack, ['line' => $line, 'type' => 'if', 'hasColon' => $hasColon]);
            echo "Line $line: T_IF (colon: " . ($hasColon ? 'YES' : 'NO') . ")\n";
        } elseif ($name === 'T_ELSEIF') {
            echo "Line $line: T_ELSEIF\n";
        } elseif ($name === 'T_ELSE') {
            echo "Line $line: T_ELSE\n";
        } elseif ($name === 'T_ENDIF') {
            $top = array_pop($controlStack);
            echo "Line $line: T_ENDIF (matches T_IF at line {$top['line']})\n";
        }
    }
}

echo "Remaining control stack count: " . count($controlStack) . "\n";
print_r($controlStack);
