<?php

namespace App\repository;

class ThemeRepository extends AbstractRepository
{
    public function add($name, $chat_id): void
    {
        $stmt = $this->mysqli->prepare("INSERT INTO Themes (chat_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $chat_id, $name);
        $stmt->execute();
    }

    public function getRandom(int $chat_id): bool|array|null {
        $stmt = $this->mysqli->prepare(
            "SELECT * FROM Themes 
         WHERE chat_id = ?
         ORDER BY RAND() 
         LIMIT 1");
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public function findById(int $id): bool|array|null
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Themes where id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public function findByName(string $name): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Themes where name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
    }

    public function findByNameAndChatId(string $name, int $chatId): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Themes where name = ? and chat_id = ?");
        $stmt->bind_param("si", $name, $chatId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all();
    }

    public function delete($themeId): void
    {
        $stmt = $this->mysqli->prepare("delete from Themes where id = ?");
        $stmt->bind_param("i", $themeId);
        $stmt->execute();
    }

    public function findAllByChatId(int $chatId): array
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM Themes WHERE chat_id = ?");
        $stmt->bind_param("i", $chatId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}