<?php

class crudModel extends Model {

    protected function init() {
        
    }

    public function create($name, $phone, $adress) {
        $id = false;
        $name = mysqli_real_escape_string($this->db, $name);
        $phone = mysqli_real_escape_string($this->db, $phone);
        $adress = mysqli_real_escape_string($this->db, $adress);
        $result = $this->db->query("INSERT INTO `emp`(`name`,`phone`,`address`) VALUES('$author','{$title}','{$content}',NOW())");
        if ($result !== false) {
            $id = $this->db->insert_id;
        }
        return $id;
    }

    public function read($id = false) {
        $data = array();
        $q = "";

        if (!empty($id)) {
            $id = mysqli_real_escape_string($this->db, $id);

            $q = "WHERE `id`={$id}";
        }

        $query = "SELECT * FROM `emp` {$q} ";

        $result = $this->db->query($query);


        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }


        return $data;
    }

    public function update($id, $name, $phone, $address) {
        $id = mysqli_real_escape_string($this->db, $id);
        $name = mysqli_real_escape_string($this->db, $name);
        $phone = mysqli_real_escape_string($this->db, $phone);
        $adress = mysqli_real_escape_string($this->db, $adress);
        if ($this->db->query("UPDATE `emp` SET `name`='$name', `phone`='$phone', `address`='$address', `last_update`=NOW() WHERE `id`={$id}"))
            return true;
        return false;
    }

    public function delete($id) {
        $id = mysqli_real_escape_string($this->db, $id);
        $result = $this->db->query("DELETE FROM `emp` WHERE `id`={$id}");
        return $result;
    }

}
