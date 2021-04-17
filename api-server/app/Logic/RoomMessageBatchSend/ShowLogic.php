<?php

declare(strict_types=1);


/**
 * This file is part of MoChat.
 * @link     https://mo.chat
 * @document https://mochat.wiki
 * @contact  group@mo.chat
 * @license  https://github.com/mochat-cloud/mochat/blob/master/LICENSE
 */

namespace App\Logic\RoomMessageBatchSend;

use App\Contract\RoomMessageBatchSendResultServiceInterface;
use App\Contract\RoomMessageBatchSendServiceInterface;
use App\Contract\WorkRoomServiceInterface;
use Hyperf\Di\Annotation\Inject;
use MoChat\Framework\Constants\ErrorCode;
use MoChat\Framework\Exception\CommonException;

class ShowLogic
{
    /**
     * @Inject()
     * @var RoomMessageBatchSendServiceInterface
     */
    private $roomMessageBatchSend;

    /**
     * @Inject()
     * @var RoomMessageBatchSendResultServiceInterface
     */
    private $roomMessageBatchSendResult;

    /**
     * @Inject()
     * @var WorkRoomServiceInterface
     */
    private $workRoom;

    /**
     * @param  array  $params  请求参数
     * @param  int  $userId  当前用户ID
     * @return array
     */
    public function handle(array $params, int $userId): array
    {
        $batch = $this->roomMessageBatchSend->getRoomMessageBatchSendById((int) $params['batchId']);
        if (!$batch) {
            throw new CommonException(ErrorCode::INVALID_PARAMS, '未找到记录');
        }
        if ($batch['userId'] != $userId) {
            throw new CommonException(ErrorCode::ACCESS_DENIED, "无操作权限");
        }

        $roomIds = $this->roomMessageBatchSendResult->getRoomMessageBatchSendResultRoomIdsByBatchIds($batch['id']);
        $rooms   = $this->workRoom->getWorkRoomsById(array_slice($roomIds, 0, 10), ['id', 'name']);

        return [
            'id'                => $batch['id'],
            'batchTitle'        => $batch['batchTitle'],
            'creator'           => $batch['userName'],
            'createdAt'         => $batch['createdAt'],
            'seedRooms'         => $rooms,
            'content'           => $batch['content'],
            'sendTime'          => $batch['sendTime'],
            'sendEmployeeTotal' => $batch['sendEmployeeTotal'],
            'sendRoomTotal'     => $batch['sendRoomTotal'],
            'sendTotal'         => $batch['sendTotal'],
            'receivedTotal'     => $batch['receivedTotal'],
            'notSendTotal'      => $batch['notSendTotal'],
            'notReceivedTotal'  => $batch['notReceivedTotal'],
        ];
    }
}