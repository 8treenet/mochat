<?php

declare(strict_types=1);

/**
 * This file is part of MoChat.
 * @link     https://mo.chat
 * @document https://mochat.wiki
 * @contact  group@mo.chat
 * @license  https://github.com/mochatcloud/mochat/blob/master/LICENSE
 */

namespace App\Action\RoomMessageBatchSend;


use App\Logic\RoomMessageBatchSend\RoomOwnerSendIndexLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use MoChat\Framework\Action\AbstractAction;
use MoChat\Framework\Request\ValidateSceneTrait;

/**
 * 客户群消息群发-群主发送详情
 * @Controller()
 */
class RoomOwnerSendIndex extends AbstractAction
{
    use ValidateSceneTrait;

    /**
     * @Inject()
     * @var RoomOwnerSendIndexLogic
     */
    private $roomOwnerSendIndexLogic;

    /**
     * @RequestMapping(path="/roomMessageBatchSend/roomOwnerSendIndex", methods="GET")
     */
    public function handle(): array
    {
        ## 参数验证
        $this->validated($this->request->all());
        ## 接收参数
        $employeeIds = $this->request->input('employeeIds', '');
        $params = [
            'batchId'     => $this->request->input('batchId'),
            'employeeIds' => array_filter(explode(',', $employeeIds)),
            'sendStatus'  => $this->request->input('sendStatus', ''),
            'page'        => $this->request->input('page', 1),
            'perPage'     => $this->request->input('perPage', 15),
        ];
        return $this->roomOwnerSendIndexLogic->handle($params, intval(user()['id']));
    }

    /**
     * 验证规则.
     *
     * @return array 响应数据
     */
    protected function rules(): array
    {
        return [
            'batchId'    => 'required|numeric',
            'sendStatus' => 'numeric',
            'page'       => 'number|min:1',
            'perPage'    => 'number',
        ];
    }

    /**
     * 验证错误提示.
     * @return array 响应数据
     */
    protected function messages(): array
    {
        return [];
    }
}