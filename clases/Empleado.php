<?php
class Empleado {
    private $id;
    private $nombre;
    private $cargo;
    private $usuario;
    private $password;

    // Constructor
    public function __construct($id, $nombre, $cargo, $usuario, $password) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->cargo = $cargo;
        $this->usuario = $usuario;
        $this->password = $password;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
}
