<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DatabaseImporter {
    private $conn;
     
    public function __construct() {
        $this->conn = new mysqli('localhost', 'root', '', 'Laptop');
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8mb4");
    }
    
    private function removeBOM($text) {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $text);
    }
    
    public function importProcessors() {
        echo "<h3>Importing Processors...</h3>";
        
        // Clear existing data
        $this->conn->query("TRUNCATE TABLE processor");
        
        $file = __DIR__ . '/images/processor.txt';
        if (!file_exists($file)) {
            die("Error: processor.txt file not found in " . __DIR__ . '/images/');
        }
        
        $content = file_get_contents($file);
        if ($content === false) {
            die("Error: Unable to read processor.txt file");
        }
        
        $content = $this->removeBOM($content);
        $lines = explode("\n", $content);
        array_shift($lines); // Remove header
        
        $count = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $parts = explode("\t", $line);
            
            if (count($parts) >= 3) {
                try {
                    $sql = sprintf(
                        "INSERT INTO processor (id, manufacturer, type) 
                        VALUES (%d, '%s', '%s')",
                        intval($parts[0]),
                        $this->conn->real_escape_string(trim($parts[1])),
                        $this->conn->real_escape_string(trim($parts[2]))
                    );
                    
                    if ($this->conn->query($sql)) {
                        $count++;
                        echo "Imported processor $count: {$parts[1]} {$parts[2]}<br>";
                    } else {
                        echo "Error importing processor: " . $this->conn->error . "<br>";
                        echo "SQL: $sql<br>";
                    }
                } catch (Exception $e) {
                    echo "Error processing line: " . htmlspecialchars($line) . "<br>";
                    echo "Error: " . $e->getMessage() . "<br>";
                }
            } else {
                echo "Invalid line (not enough fields): " . htmlspecialchars($line) . "<br>";
            }
        }
        echo "Total processors imported: $count<br><br>";
    }
    
    public function importOperatingSystems() {
        echo "<h3>Importing Operating Systems...</h3>";
        
        // Clear existing data
        $this->conn->query("TRUNCATE TABLE opsystem");
        
        $file = __DIR__ . '/images/opsystem.txt';
        $content = file_get_contents($file);
        $content = $this->removeBOM($content);
        $lines = explode("\n", $content);
        array_shift($lines); // Remove header
        
        $count = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $parts = explode("\t", $line);
            
            if (count($parts) >= 2) {
                try {
                    $sql = sprintf(
                        "INSERT INTO opsystem (id, name) 
                        VALUES (%d, '%s')",
                        intval($parts[0]),
                        $this->conn->real_escape_string(trim($parts[1]))
                    );
                    
                    if ($this->conn->query($sql)) {
                        $count++;
                        echo "Imported OS $count: {$parts[1]}<br>";
                    } else {
                        echo "Error importing OS: " . $this->conn->error . "<br>";
                    }
                } catch (Exception $e) {
                    echo "Error processing OS line: " . htmlspecialchars($line) . "<br>";
                    echo "Error: " . $e->getMessage() . "<br>";
                }
            }
        }
        echo "Total operating systems imported: $count<br><br>";
    }
    
    public function importNotebooks() {
        echo "<h3>Importing Notebooks...</h3>";
        
        // Clear existing data
        $this->conn->query("TRUNCATE TABLE notebook");
        
        $file = __DIR__ . '/images/notebook.txt';
        $content = file_get_contents($file);
        $content = $this->removeBOM($content);
        $lines = explode("\n", $content);
        array_shift($lines);
        
        $count = 0;
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            $parts = explode("\t", $line);
            
            if (count($parts) >= 10) {
                try {
                    $display = str_replace(',', '.', $parts[2]);
                    
                    $sql = sprintf(
                        "INSERT INTO notebook (id, manufacturer, type, display, memory, harddisk, videocontroller, price, processorid, opsystemid, pieces) 
                        VALUES (%d, '%s', '%s', %f, %d, %d, '%s', %d, %d, %d, %d)",
                        $count,
                        $this->conn->real_escape_string(trim($parts[0])),
                        $this->conn->real_escape_string(trim($parts[1])),
                        floatval($display),
                        intval($parts[3]),
                        intval($parts[4]),
                        $this->conn->real_escape_string(trim($parts[5])),
                        intval($parts[6]),
                        intval($parts[7]),
                        intval($parts[8]),
                        intval($parts[9])
                    );
                    
                    if ($this->conn->query($sql)) {
                        $count++;
                        echo "Imported notebook $count: {$parts[0]} {$parts[1]}<br>";
                    } else {
                        echo "Error importing notebook: " . $this->conn->error . "<br>";
                    }
                } catch (Exception $e) {
                    echo "Error processing notebook line: " . htmlspecialchars($line) . "<br>";
                    echo "Error: " . $e->getMessage() . "<br>";
                }
            }
        }
        echo "Total notebooks imported: $count<br><br>";
    }
    
    public function importAll() {
        echo "<h2>Starting Database Import...</h2>";
        
        // Import in proper order to maintain referential integrity
        $this->importProcessors();
        $this->importOperatingSystems();
        $this->importNotebooks();
        
        echo "<h2>Import Complete!</h2>";
    }
    
    public function close() {
        $this->conn->close();
    }
}

// Run the import
$importer = new DatabaseImporter();
$importer->importAll();
$importer->close();
?>