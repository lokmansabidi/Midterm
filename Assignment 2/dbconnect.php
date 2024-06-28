<?php
class dbconnect {
    private $host = 'localhost';
    private $db = 'clinic';
    private $user = 'root';
    private $pass = '';
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }

    public function close() {
        if ($this->conn != null) {
            $this->conn->close();
        }
    }

    public function getServices() {
        $this->connect();
        $query = "SELECT * FROM tbl_services";
        $result = $this->conn->query($query);
        $services = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $services[] = $row;
            }
        }
        $this->close();
        return $services;
    }
}
?>
