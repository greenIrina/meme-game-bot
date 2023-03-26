<?php

namespace App\service;

use App\repository\ThemeRepository;

class ThemeService
{
    private ThemeRepository $themeRepository;

    /**
     * @param ThemeRepository $themeRepository
     */
    public function __construct(ThemeRepository $themeRepository)
    {
        $this->themeRepository = $themeRepository;
    }

    public function getRandomTheme(int $chatId): bool|array|null {
        $theme = $this->themeRepository->getRandom($chatId);
        if ($theme == null) {
            $theme = $this->themeRepository->getRandom($chatId);
            if ($theme == null) {
                $theme = $this->themeRepository->getRandom($chatId);
            }
        }
        return $theme;
    }


    public function deleteById($textId): void
    {
        $this->themeRepository->delete($textId);
    }

    public function addTheme(string $name, int $chatId): void
    {
        $this->themeRepository->add($name, $chatId);
    }

    public function findAllByChatId(int $chatId): array
    {
        return $this->themeRepository->findAllByChatId($chatId);
    }

    public function findById(int $categoryId): bool|array|null
    {
        return $this->themeRepository->findById($categoryId);
    }
}