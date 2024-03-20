<?php

class StatusHistoryOperations {
    private $db;
    public $id;
    public $objectId;
    public $status;
    public $createdAt;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create() {
        $stmt = $this->db->prepare("INSERT INTO status_history (object_id, status, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $this->objectId, $this->status);
        $stmt->execute();
        $this->id = $this->db->insert_id;
    }

    public function read($id) {
        $stmt = $this->db->prepare("SELECT * FROM status_history WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->number = $row['number'];
            $this->createdAt = $row['created_at'];
            $this->currentStatus = $row['current_status'];
        }
    }

    public function update() {
        $stmt = $this->db->prepare("UPDATE status_history SET object_id = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssi", $this->objectId, $this->status, $this->id);
        $stmt->execute();
    }

    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM status_history WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
    }
}