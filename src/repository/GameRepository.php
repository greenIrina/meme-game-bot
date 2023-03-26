<?php

namespace App\repository;

class GameRepository extends AbstractRepository
{

    public function addGame(int $chat_id) {
        $stmt = $this->mysqli->prepare("INSERT INTO Games (chat_id, player_name) VALUE (?,0)");
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
    }

    public function addPlayerToGame(int $chat_id, string $player_name) {
        $stmt = $this->mysqli->prepare("INSERT INTO Games (chat_id, player_name) VALUE (?,?)");
        $stmt->bind_param("is", $chat_id, $player_name);
        $stmt->execute();
    }

    public function findAllByChatId(int $chat_id): array {
        $stmt = $this->mysqli->prepare("SELECT * FROM Games where chat_id = ?");
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function delete(int $chat_id): void
    {
        $stmt = $this->mysqli->prepare("delete from Games where chat_id = ?");
        $stmt->bind_param("i", $chat_id);
        $stmt->execute();
    }

    public function deletePlayer(int $chat_id, string $player_name): void {
        $stmt = $this->mysqli->prepare("delete from Games where chat_id = ? and player_name = ?");
        $stmt->bind_param("is", $chat_id, $player_name);
        $stmt->execute();
    }
}