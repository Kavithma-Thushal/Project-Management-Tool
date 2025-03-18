<?php

namespace App\Classes;

class MessageGenerator
{
    public function generateChangeStatusMessage(string $userName, string $currentStatus, string $newStatus): string
    {
        return "{$userName} moved this card from {$currentStatus} to {$newStatus}";
    }

    public function generateTaskCreateMessage(string $userName, string $status): string
    {
        return "{$userName} added this card to {$status}";
    }
}
