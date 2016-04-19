<?php
class DbAdapter {

    protected $_server, $_username, $_password, $_errorInfo;
    public $dbName;
    public $connection;
    protected $_result;

    /**
     * @param string $hostname Database hostname
     * @param string $username Database username
     * @param string $password Database password
     * @param string $dbName Database name
     * @return void
     */
    public function __construct($hostname, $username, $password, $dbName) {
        $this->_hostname = $hostname;
        $this->_username = $username;
        $this->_password = $password;
        $this->_dbName = $dbName;

        $this->connect();
    }

    /**
     * @return void
     */
    public function __destruct() {
        $this->close();
    }

    /**
     * @return boolean
     */
    public function connect() {



        try {
            $this->connection = mysqli_connect($this->_hostname, $this->_username, $this->_password, $this->_dbName);
        } catch (Exception $e) {
            echo "Error connection to database";
            return false;
        }


        return $this->connection;
    }

    /**
     * @param array $values 3D array of fields and values to be updated
     * @param string $table Table to update
     * @param array $where 3D array of fields and values
     * @param string $limit Limit condition
     * @return boolean Result
     */
    public function update($table, array $values, $where = false, $limit = false) {
        if (count($values) < 0)
            return false;

        $fields = array();
        foreach ($values as $field => $val)
            $fields[] = "`" . $field . "` = '" . mysqli_real_escape_string($this->connection, $val) . "'";


        if ($where) {
            $whereString = '';
            foreach ($where as $field => $value) {
                $whereString .= $field . " '" . mysqli_real_escape_string($this->connection, $value) . "' ";
            }
            $where = $whereString;
        }//echo $where;die;

        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        if ($this->connection->query("UPDATE " . $table . " SET " . implode($fields, ", ") . $where . $limit))
            return true;
        else
            return false;
    }

    /**
     * @param array $values 3D array of fields and values to be inserted
     * @param string $table Table to insert
     * @return boolean Result
     */
    public function insert($table, array $values) {
        if (count($values) < 0)
            return false;

        foreach ($values as $field => $val)
            $values[$field] = mysqli_real_escape_string($this->connection, $val);

        if ($this->connection->query("INSERT INTO " . $table . " (`" . implode(array_keys($values), "`, `") . "`) VALUES ('" . implode($values, "', '") . "')"))
            return true;
        else
            return false;
    }

    /**
     * @param mixed $fields Array or string of fields to retrieve
     * @param string $table Table to retrieve from
     * @param array $where 3D array of fields and values
     * @param string $orderby Order by clause
     * @param string $limit Limit condition
     * @return array Array of rows
     */
    public function select($fields, $table, $where = false, $orderby = false, $limit = false, $join = '', $groupby = '') {
        if (is_array($fields)) {
            $fields = "`" . implode($fields, "`, `") . "`";
        }

        $orderby = ($orderby) ? " ORDER BY " . $orderby : '';
        if ($where) {
            $whereString = '';
            foreach ($where as $field => $value) {
                $whereString .= $field . " '" . mysqli_real_escape_string($this->connection, $value) . "' ";
            }
            $where = $whereString;
        }
        $where = ($where) ? " WHERE " . $where : '';
        $limit = ($limit) ? " LIMIT " . $limit : '';

        $results = $this->connection->query("SELECT " . $fields . " FROM " . $table . " " . $join . " " . $where . " " . $groupby . $orderby . $limit);


        if ($results->num_rows > 0) {
            while ($row = $results->fetch_object()) {
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * @param string $table Table to delete from
     * @param array $where 3D array of fields and values
     * @param string $limit Limit condition
     * @return boolean Result
     */
    public function delete($table, $where = false, $limit = 1) {
        $where = ($where) ? "WHERE {$where}" : "";
        $limit = ($limit) ? "LIMIT {$limit}" : "";
        if ($where) {
            $whereString = '';
            foreach ($where as $field => $value) {
                $whereString .= $field . " '" . mysqli_real_escape_string($this->connection, $value) . "' ";
            }
            $where = $whereString;
        }
        if ($this->connection->query("DELETE FROM `{$table}` {$where} {$limit}"))
            return true;
        else
            return false;
    }

    /**
     * @return boolean
     */
    public function close() {
        if (isset($this->connection)) {
            $this->connection->close();
            return true;
        }
        return false;
    }

}
