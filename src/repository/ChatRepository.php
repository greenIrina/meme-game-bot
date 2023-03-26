<?php

namespace App\repository;

class ChatRepository extends AbstractRepository
{
    public function add(int $id): void
    {
        $stmt = $this->mysqli->prepare("INSERT INTO Chats (id) values (?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    public function addIfAbsent(int $id): void
    {
        if (!$this->findById($id)) {
            $this->add($id);
        }
    }

    public function findAll(): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Chats");
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
    }

    public function findById(int $id): bool|array|null
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Chats where id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_row();
    }
}