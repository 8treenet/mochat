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


use App\Logic\RoomMessageBatchSend\RoomReceiveIndexLogic;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use MoChat\Framework\Action\AbstractAction;
use MoChat\Framework\Request\ValidateSceneTrait;

/**
 * 客户群消息群发-客户群接收详情
 * @Controller()
 */
class RoomReceiveIndex extends AbstractAction
{
    use ValidateSceneTrait;

    /**
     * @Inject()
     * @var RoomReceiveIndexLogic
     */
    private $roomReceiveIndexLogic;

    /**
     * @RequestMapping(path="/roomMessageBatchSend/roomReceiveIndex", methods="GET")
     */
    public function handle(): array
    {
        ## 参数验证
        $this->validated($this->request->all());
        ## 接收参数
        $employeeIds = $this->request->input('employeeIds', '');
        $params      = [
            'batchId'     => $this->request->input('batchId'),
            'employeeIds' => array_filter(explode(',', $employeeIds)),
            'sendStatus'  => $this->request->input('sendStatus', ''),
            'keyWords'    => $this->request->input('keyWords', null),
            'page'        => $this->request->input('page', 1),
            'perPage'     => $this->request->input('perPage', 15),
        ];
        return $this->roomReceiveIndexLogic->handle($params, intval(user()['id']));
    }

    /**
     * 验证规则.
     *
     * @return array 响应数据
     */
    protected function rules(): array
    {
        return [
            'batchId'  => 'required|numeric',
            'sendType' => 'numeric',
            'keyWords' => 'max:255',
            'page'     => 'number|min:1',
            'perPage'  => 'number',
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