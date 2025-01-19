<?php

class Student
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }


    // metode untuk mengambil semua siswa
    public function getAllStudents()
    {
        $query = "SELECT * FROM students ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // metode untuk mengambil data berdasarkan NIS
    public function getStudentByNIS($nis)
    {
        $query = "SELECT * FROM students WHERE nis = :nis";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nis', $nis);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // metode untuk menambahkan siswa
    public function insertStudent($nis, $fullname, $class, $address)
    {
        $query = "INSERT INTO students (nis, fullname, class, address) VALUES (:nis, :fullname, :class, :address)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nis', $nis);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':address', $address);
        return $stmt->execute();
    }


    // metode untuk mengupdate data siswa
    public function updateStudent($nis, $fullname, $class, $address)
    {
        $query = "UPDATE students SET fullname = :fullname, class = :class, address = :address WHERE nis = :nis";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nis', $nis);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':address', $address);
        return $stmt->execute();
    }


    // metode untuk menghapus data siswa
    public function deleteStudent($nis)
    {
        $query = "DELETE FROM students WHERE nis = :nis";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nis', $nis);
        return $stmt->execute();
    }


    // metode untuk mengambil kelas distinct
    public function getDistinctClasses()
    {
        $query = "SELECT DISTINCT class FROM students";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }


    // metode untuk mengambil data siswa berdasarkan kelas
    public function filterByClass($class)
    {
        $query = "SELECT * FROM students WHERE class = :class";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':class', $class);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
