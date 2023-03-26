<?php

namespace App\service;

use App\repository\GameRepository;

class GameService
{
    private GameRepository $gameRepository;

    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function checkStartedGame(int $chat_id): bool {
        return $this->gameRepository->findAllByChatId($chat_id) == null;
    }

    public function addGame(int $chat_id): void
    {
        $this->gameRepository->addGame($chat_id);
    }

    public function deleteGame(int $chat_id): void
    {
        $this->gameRepository->delete($chat_id);
    }

    public function getPlayers(int $chat_id): array
    {
        return $this->gameRepository->findAllByChatId($chat_id);
    }

    public function addPlayer(int $chat_id, string $player_name): bool
    {

        $players = $this->getPlayers($chat_id);
        foreach ($players as $player) {
            if ($player_name == $player['player_name']) {
                return false;
            }
        }
        $this->gameRepository->addPlayerToGame($chat_id, $player_name);
        return true;
    }

    public function deletePlayer(int $chat_id, string $player_name): bool {
        $players = $this->getPlayers($chat_id);
        foreach ($players as $player) {
            if ($player_name == $player['player_name']) {
                $this->gameRepository->deletePlayer($chat_id, $player_name);
                return true;
            }
        }
        return false;
    }
}