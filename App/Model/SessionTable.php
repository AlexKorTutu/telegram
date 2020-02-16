<?php
declare(strict_types=1);

namespace App\Model;

use PDO;

class SessionTable extends AbstractTable
{
    public const SESSION_TABLE = 'session';

    public function getLastActiveSessionByChatId(string $chatId): int
    {
        $request = 'SELECT id FROM ' . self::SESSION_TABLE
            . ' WHERE chat_id = ? AND is_active = true ORDER BY id DESC LIMIT 1';

        $statement = $this->pdo->prepare($request);
        $statement->execute([$chatId]);
        $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!isset($queryResult[0]['id'])) {
            $sql = 'INSERT INTO ' . self::SESSION_TABLE . ' (chat_id, is_active) VALUES (:chat_id, :is_active)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'chat_id' => $chatId,
                'is_active' => TRUE,
            ]);
            $queryResult = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        return $queryResult[0]['id'];
    }

    public function closeSession(int $sessionId, string $chatId)
    {
        $request = 'UPDATE ' . self::SESSION_TABLE . ' SET is_active = FALSE'
            . ' WHERE id = ? AND chat_id = ?';

        $statement = $this->pdo->prepare($request);
        $statement->execute([$sessionId, $chatId]);
    }

}