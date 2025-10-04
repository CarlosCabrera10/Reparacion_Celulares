<?php
class Celular {
    private $id;
    private $id_cliente;
    private $marca;
    private $modelo;
    private $imei;
    
    public function __construct($id = null, $id_cliente = null, $marca = null, $modelo = null, $imei = null) {
        $this->id = $id;
        $this->id_cliente = $id_cliente;
        $this->marca = $marca;
        $this->modelo = $modelo;
        $this->imei = $imei;
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function getIdCliente() { return $this->id_cliente; }
    public function getMarca() { return $this->marca; }
    public function getModelo() { return $this->modelo; }
    public function getImei() { return $this->imei; }

    public function setId($id) { $this->id = $id; }
    public function setIdCliente($id_cliente) { $this->id_cliente = $id_cliente; }
    public function setMarca($marca) { $this->marca = $marca; }
    public function setModelo($modelo) { $this->modelo = $modelo; }
    public function setImei($imei) { $this->imei = $imei; }
}
