<?php

    $data = <<<'EOD'
    X, -9\\\10\100\-5\\\0\\\\, A
    Y, \\13\\1\, B
    Z, \\\5\\\-3\\2\\\800, C
    EOD;

    $rows = explode("\n", $data);
    $results = [];

    foreach ($rows as $row) {
        $cols = array_map('trim', explode(',', trim($row)));
        $values = array_filter(explode('\\', $cols[1]), 'strlen');
        
        foreach ($values as $value) {
            $results[] = [
                'var_xyz' => $cols[0],
                'value' => $value,
                'var_abc' => $cols[2]
            ];
        }
    }

    array_multisort(array_column($results, 'value'), SORT_ASC, $results);

    $count = [];
    foreach ($results as $result) {
        $current = $result['var_abc'];
        $count[$current] = ($count[$current] ?? 0) + 1;
        echo nl2br($result['var_xyz'] . ', ' . $result['value'] . ', ' . $result['var_abc'] . ', ' . $count[$current] . "\n");
    }

?>
