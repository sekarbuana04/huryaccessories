<?php
require_once 'database.php';

try {
    $pdo = getDBConnection();
    
    // Read the schema file
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    
    // Split by semicolon and execute each statement
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^\s*--/', $statement)) {
            try {
                $pdo->exec($statement);
                echo "Executed: " . substr($statement, 0, 50) . "...\n";
            } catch (PDOException $e) {
                // Ignore table already exists errors
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "Error: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\nSchema executed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>