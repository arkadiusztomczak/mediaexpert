<?php

class ObjectOperations {
    private $db;
    public $id;
    public $number;
    public $status;
    public $createdAt;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create() {
        $stmt = $this->db->prepare("INSERT INTO objects (number, created_at, status) VALUES (?, NOW(), ?)");
        $stmt->bind_param("ss", $this->number, $this->status);
        $stmt->execute();
        $this->id = $this->db->insert_id;
    }

    public function read($id) {
        $stmt = $this->db->prepare("SELECT * FROM objects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->number = $row['number'];
            $this->createdAt = $row['created_at'];
            $this->status = $row['status'];
        }
    }

    public function update() {
        $stmt = $this->db->prepare("UPDATE objects SET number = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $this->number, $this->status, $this->id);
        $stmt->execute();
    }

    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM objects WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }

    public function search($number = '', $date = '', $current_status = '', $past_status = '') {
        $sql = "SELECT objects.* FROM objects LEFT JOIN status_history ON objects.id = status_history.object_id";
        $params = [];
        $conditions = [];

        if ($number != '') {
            $conditions[] = "objects.number = ?";
            $params[] = $number;
        }
        if ($date != '') {
            $conditions[] = "objects.created_at = ?";
            $params[] = $date;
        }
        if ($current_status != '') {
            $conditions[] = "objects.status = ?";
            $params[] = $current_status;
        }
        if ($past_status != '') {
            $conditions[] = "status_history.status_name = ?";
            $params[] = $past_status;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->db->prepare($sql);
        if ($params) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $objects = [];
        while ($row = $result->fetch_assoc()) {
            $objects[] = $row;
        }

        return $objects;
    }
}