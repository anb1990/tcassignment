<?php

class crudController extends Controller {

    private $addresses = [];

    protected function init() {
        $this->db = new DbAdapter($this->cfg['db']['hostname'], $this->cfg['db']['username'], $this->cfg['db']['password'], $this->cfg['db']['database']);
    }

    public function index() {
        $data = $this->_model->read();

        $this->view->set('items', $data);
        return $this->view();
    }

    /**
     * Encoding json
     */
    function encode_json() {
        $this->rcd();
        $id = $_GET['id'];
        $address = $this->addresses[$id];
        return json_encode($address);
    }

    function rcd() {
        $data = $this->_model->read();
        foreach ($data as $row) {
            $this->addresses[] = [
                'name' => $row['name'],
                'phone' => $row['phone'],
                'street' => $row['street']
            ];
        }
    }

    public function create($name = null, $phone = null, $address = null) {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $id = $this->_model->create($name, $phone, $address);
            if ($id !== false) {
                header('Location: /crud/index');
                exit;
            }
        }
        return $this->view();
    }

    public function edit($id, $name = null, $phone = null, $address = null) {
        if (empty($id)) {
            return $this->unknownAction();
        }

        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            if ($this->_model->update($id, $name, $phone, $address)) {
                header('Location: /crud/index');
                exit;
            }
        }
    }

    public function delete($id) {

        $result = $this->_model->delete($id);
        if ($result !== false) {
            header('Location: /crud');
            exit;
        }
    }

}
